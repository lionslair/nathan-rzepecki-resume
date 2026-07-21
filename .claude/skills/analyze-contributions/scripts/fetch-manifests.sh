#!/usr/bin/env bash
# For every repo in data/repos.json, cache composer.json / package.json
# (dependency names only) into data/manifests/<owner>__<name>.json.
# Prefers a local clone (fast, free); falls back to the GitHub Contents API.
# Skips repos whose cached manifest is already newer than the repo's last push.
set -euo pipefail

SKILL_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
DATA_FILE="$SKILL_DIR/data/repos.json"
MANIFEST_DIR="$SKILL_DIR/data/manifests"

if [ ! -f "$DATA_FILE" ]; then
  echo "error: $DATA_FILE not found - run discover-repos.sh first" >&2
  exit 1
fi

mkdir -p "$MANIFEST_DIR"

extract_deps() {
  # Reads a composer.json or package.json blob on stdin, prints its
  # top-level dependency names (require + require-dev, or
  # dependencies + devDependencies), one per line.
  jq -r '
    ( .require // {} | keys[] ),
    ( ."require-dev" // {} | keys[] ),
    ( .dependencies // {} | keys[] ),
    ( .devDependencies // {} | keys[] )
  ' 2>/dev/null | grep -v '^ext-' | grep -v '^php$' | sort -u
}

fetch_local() {
  local path="$1" file="$2"
  [ -f "$path/$file" ] && cat "$path/$file"
}

fetch_remote() {
  local fullname="$1" file="$2"
  gh api "repos/$fullname/contents/$file" --jq '.content' 2>/dev/null | base64 -d 2>/dev/null
}

count=$(jq '.repos | length' "$DATA_FILE")
echo "Fetching manifests for $count repos..." >&2

jq -c '.repos[]' "$DATA_FILE" | while IFS= read -r repo; do
  owner=$(jq -r '.owner' <<<"$repo")
  name=$(jq -r '.name' <<<"$repo")
  fullname=$(jq -r '.fullname' <<<"$repo")
  pushedAt=$(jq -r '.pushedAt' <<<"$repo")
  localPath=$(jq -r '.localPath // empty' <<<"$repo")
  outFile="$MANIFEST_DIR/${owner}__${name}.json"

  if [ -f "$outFile" ]; then
    cachedPushedAt=$(jq -r '.pushedAt // empty' "$outFile" 2>/dev/null || echo "")
    if [ "$cachedPushedAt" = "$pushedAt" ]; then
      continue
    fi
  fi

  composer_json=""
  package_json=""
  readme=""

  if [ -n "$localPath" ]; then
    composer_json=$(fetch_local "$localPath" composer.json || true)
    package_json=$(fetch_local "$localPath" package.json || true)
    readme=$(fetch_local "$localPath" README.md || true)
  else
    composer_json=$(fetch_remote "$fullname" composer.json || true)
    package_json=$(fetch_remote "$fullname" package.json || true)
    readme=$(fetch_remote "$fullname" README.md || true)
    sleep 0.5
  fi

  composer_deps="[]"
  package_deps="[]"
  [ -n "$composer_json" ] && composer_deps=$(echo "$composer_json" | extract_deps | jq -R . | jq -s .)
  [ -n "$package_json" ] && package_deps=$(echo "$package_json" | extract_deps | jq -R . | jq -s .)

  jq -n \
    --arg owner "$owner" --arg name "$name" --arg fullname "$fullname" \
    --arg pushedAt "$pushedAt" --arg readme "${readme:0:2000}" \
    --argjson composerDeps "$composer_deps" --argjson packageDeps "$package_deps" \
    '{owner:$owner,name:$name,fullname:$fullname,pushedAt:$pushedAt,
      composerDeps:$composerDeps,packageDeps:$packageDeps,readmeExcerpt:$readme}' \
    > "$outFile"

  echo "  + $fullname" >&2
done

echo "Manifests cached in $MANIFEST_DIR" >&2
