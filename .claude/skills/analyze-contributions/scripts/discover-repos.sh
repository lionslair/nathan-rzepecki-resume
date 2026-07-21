#!/usr/bin/env bash
# Maintains data/repos.json: every non-archived, non-fork repo across the
# configured orgs (+ personal account) that the configured author has
# actually committed to. Safe to re-run; it fully rebuilds the file each
# time from the GitHub API so it never drifts from reality.
set -euo pipefail

SKILL_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
CONFIG_FILE="$SKILL_DIR/data/config.json"
DATA_FILE="$SKILL_DIR/data/repos.json"

if ! command -v gh >/dev/null 2>&1; then
  echo "error: gh CLI is required" >&2
  exit 1
fi
if ! command -v jq >/dev/null 2>&1; then
  echo "error: jq is required" >&2
  exit 1
fi

GITHUB_USER=$(jq -r '.github_user' "$CONFIG_FILE")
AUTHOR_EMAIL=$(jq -r '.author_emails[0]' "$CONFIG_FILE")
mapfile -t ORGS < <(jq -r '.orgs[]' "$CONFIG_FILE")
mapfile -t LOCAL_ROOTS < <(jq -r '.local_roots[]' "$CONFIG_FILE")

find_local_path() {
  local name="$1"
  for root in "${LOCAL_ROOTS[@]}"; do
    if [ -d "$root/$name/.git" ]; then
      echo "$root/$name"
      return 0
    fi
  done
  echo ""
}

tmp_lines=$(mktemp)
trap 'rm -f "$tmp_lines"' EXIT

scan_owner() {
  local owner="$1"
  echo "Scanning $owner..." >&2

  gh repo list "$owner" --limit 300 \
    --json name,owner,isArchived,isFork,pushedAt \
    --jq '.[] | select(.isArchived==false and .isFork==false)' 2>/dev/null |
  jq -c '.' |
  while IFS= read -r repo; do
    name=$(jq -r '.name' <<<"$repo")
    login=$(jq -r '.owner.login' <<<"$repo")
    pushedAt=$(jq -r '.pushedAt' <<<"$repo")
    fullname="$login/$name"

    # Cheap authorship check via the commit search API - no clone needed.
    # Rate-limited (search API), so pace requests gently.
    count=$(gh api "search/commits?q=repo:$fullname+author-email:$AUTHOR_EMAIL" \
      --jq '.total_count' 2>/dev/null || echo "")
    sleep 1.5

    if [ -z "$count" ] || [ "$count" = "0" ]; then
      continue
    fi

    localPath=$(find_local_path "$name")

    jq -nc \
      --arg owner "$login" \
      --arg name "$name" \
      --arg fullname "$fullname" \
      --arg pushedAt "$pushedAt" \
      --arg localPath "$localPath" \
      --argjson authoredCommits "$count" \
      '{owner:$owner,name:$name,fullname:$fullname,pushedAt:$pushedAt,
        localPath:(if $localPath=="" then null else $localPath end),
        authoredCommits:$authoredCommits}' >> "$tmp_lines"

    echo "  + $fullname ($count commits authored)" >&2
  done
}

for org in "${ORGS[@]}"; do
  scan_owner "$org"
done
scan_owner "$GITHUB_USER"

if [ -s "$tmp_lines" ]; then
  jq -s '{generatedAt: (now | todate), repos: sort_by(.fullname)}' "$tmp_lines" > "$DATA_FILE"
else
  jq -n '{generatedAt: (now | todate), repos: []}' > "$DATA_FILE"
fi

echo "Wrote $(jq '.repos | length' "$DATA_FILE") authored repos to $DATA_FILE" >&2
