---
name: Analyze Contributions & Sync Resume
description: Analyzes git commits for a local or remote repository to generate resume-ready description and highlights using juststeveking/resume-php
---

# Skill: Analyze Repository Contributions for `resume-php`

When asked to analyze contributions or update a project in `resume-php`, follow these instructions to inspect git logs, generate professional summary points, and hydrate the resume model.

---

## 1. Identify the Source

Figure out which of these the user gave you, and branch accordingly:

- **Local path** — a directory that already exists on disk → Option A.
- **GitHub URL or `owner/repo` shorthand** (e.g. `https://github.com/owner/repo`) → Option B if the `gh` CLI is available, otherwise Option C.

Check for `gh` once, up front:
```bash
gh auth status
```
If this succeeds, prefer Option B for any remote repo — it's faster and needs no clone. If `gh` is missing or unauthenticated, fall back to Option C.

### Option A: Local Directory
Run git log filtered by author, excluding merge commits:
```bash
git -C <path-to-repo> log --author="<author-email-or-username>" --no-merges --pretty=format:"%s" -n 100
```

### Option B: Remote Repo via `gh` (preferred when available)
Query the GitHub API directly through `gh` — no clone needed. `author` accepts a GitHub username or a verified email:
```bash
gh api "repos/<owner>/<repo>/commits?author=<github-username-or-email>&per_page=100" \
  --paginate --jq '.[].commit.message'
```
Useful companions:
```bash
# Repo metadata for description/url/dates
gh repo view <owner>/<repo> --json name,description,url,createdAt,pushedAt,primaryLanguage,languages

# If you need diffs/stats rather than just messages (heavier, use sparingly)
gh api "repos/<owner>/<repo>/commits?author=<github-username-or-email>&per_page=30" \
  --paginate --jq '.[] | {sha: .sha, message: .commit.message}'
```
If the repo is private, confirm the authenticated `gh` account has access before proceeding; if the API call 404s, fall back to Option C with an authenticated clone URL.

### Option C: Remote Repo without `gh` (shallow clone)
Clone into a scratch directory, analyze, then clean up:
```bash
tmp_dir=$(mktemp -d)
git clone --no-checkout <github-url> "$tmp_dir"
git -C "$tmp_dir" log --author="<author-email-or-username>" --no-merges --pretty=format:"%s" -n 100
rm -rf "$tmp_dir"
```
Use `--no-checkout` since only history is needed, not a working tree — this keeps the clone fast and avoids touching disk with the repo's files.

---

## 2. Analyze the Commits

From whichever commit message list you gathered:
- Group related commits into themes (features, integrations, infra/CI, migrations, performance, etc.) rather than listing raw commit subjects.
- Drop noise: version bumps, formatting-only commits, merge/revert commits, dependency bumps with no functional story.
- Write each theme as a resume `highlight`: past tense, outcome-oriented, specific about the tech involved (frameworks, APIs, cloud services) — match the voice already used in `build.php`'s existing `Project`/`Work` entries (e.g. "Implemented Xero API integrations for automated accounting workflows").
- Draft a 1-2 sentence `description`/`summary` covering what the project/role is and its stack.
- Pull `startDate` / `endDate` from the first and last commit dates (`git log --author=... --no-merges --pretty=format:"%ad" --date=format:"%Y-%m-%d" | tail -1` for start, `| head -1` for end), or from `gh repo view` timestamps / the user if commit history doesn't span the full engagement.

---

## 3. Hydrate the Resume

Open `build.php` at the repo root and add or update an entry using the `resume-php` fluent builder (see the `resume-php` skill for the full API). Match the existing style in the file:

- Ongoing personal/freelance repos → `->addProject(new Project(name:, startDate:, endDate:, description:, highlights: [...], url:))`.
- Employment-based work → `->addWork(new Work(name:, position:, location:, url:, startDate:, endDate:, summary:, highlights: [...]))`.

Insert the new entry near entries with similar dates to keep the file's chronological ordering intact. After editing, run the project's build to confirm it still compiles and exports cleanly:
```bash
./build.sh
```
