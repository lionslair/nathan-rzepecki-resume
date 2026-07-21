# analyze-contributions

Keeps `build.php` honest by mining actual git history instead of guesswork. Three things it can do — just describe what you want in plain language, Claude Code picks the right mode:

## 1. Update one project

> "Repeat for /home/nathanr/Code/some-repo, update the [Project Name] project"

Reads the repo's git log, README, and `composer.json`/`package.json`, figures out what was actually built, and writes/updates that project's `description` + `highlights` in `build.php` directly. Low-risk, always run `php -l build.php` after.

## 2. Audit the Skills section for gaps

> "Audit my skills section" / "what am I missing based on everything I've worked on"

Scans **every repo across all your GitHub orgs** you've actually committed to (not just the ones already in `Projects`), pulls their dependencies, and diffs against the `->addSkill(...)` keywords in `build.php`. Always proposes a diff first — never edits Skills/Projects without you approving the wording.

Runs two scripts under the hood:
- `scripts/discover-repos.sh` → rebuilds `data/repos.json` (every non-fork, non-archived repo you've authored commits in, across the orgs in `data/config.json`). Takes a few minutes — it's rate-limited against GitHub's search API.
- `scripts/fetch-manifests.sh` → caches each repo's dependency list into `data/manifests/`. Incremental — only re-fetches repos that have been pushed to since the last run, so re-running this often is cheap.

## 3. Find repos missing from Projects entirely

> "Are there any repos I should add as a project?"

Same repo discovery as above, cross-referenced against existing `Project` names/URLs in `build.php`, ranked by commit count and recency. Proposal only — pick one and run mode 1 on it properly.

## Config

`data/config.json` — the orgs to scan, your GitHub username/commit email(s), where local clones live, and the path to `build.php`. Edit this directly if you join a new org, add an email alias, or move the resume repo.

## Data cache (gitignored, not committed)

- `data/repos.json` — output of the last `discover-repos.sh` run
- `data/manifests/<owner>__<repo>.json` — cached dependency lists per repo

Safe to delete either; both scripts fully regenerate what they need.
