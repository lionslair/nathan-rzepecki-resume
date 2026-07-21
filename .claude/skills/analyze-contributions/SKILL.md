---
name: analyze-contributions
description: Analyzes git commits (single repo, or across every GitHub org the user contributes to) to generate resume-ready project descriptions/highlights, audit the Skills section for gaps, and flag repos that aren't represented as Projects yet - using juststeveking/resume-php
---

# Skill: Analyze Repository Contributions for `resume-php`

This skill has three modes. Pick the one that matches the request:

1. **Single-project sync** (below) - "update the X project", "repeat for /path/to/repo" -> write a description + highlights for one `Project` entry in `build.php`. Low-risk and reversible via git - edit directly, no need to stage a proposal first.
2. **[Skill-gap audit](#mode-2-skill-gap-audit)** - "check for missing skills", "audit skills", "what am I missing" -> scan dependency manifests across every repo the user has authored commits in, diff against the `Skills` section, propose additions.
3. **[New-project candidates](#mode-3-new-project-candidates)** - "what repos should I add", "am I missing a project" -> diff the discovered repo list against existing `Project` entries, flag good candidates.

Modes 2 and 3 use `data/config.json` for org/email/path config, and `scripts/discover-repos.sh` + `scripts/fetch-manifests.sh` to build a repo/manifest cache under `data/`. Read `data/config.json` first - it has `github_user`, `author_emails`, `orgs`, `local_roots`, and `build_php_path`. Treat it as the source of truth instead of hardcoding those values; if the user mentions a new org or email, update `data/config.json` rather than improvising inline.

---

## Mode 1: Single-Project Sync

### 1. Identify the Source

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

---

## Mode 2: Skill-Gap Audit

Trigger: the user wants to know if the `Skills` section (`->addSkill(...)` blocks near the top of `build.php`) is missing anything, based on what they've actually built across *all* their repos - not just the ones already in `Projects`.

**Never edit the `Skills` (or `Projects`) section in this mode without showing the user the proposed diff first and getting a go-ahead.** Unlike Mode 1, this involves judgment calls about resume framing that are the user's call.

### Step 1 - Refresh the repo list

Run `scripts/discover-repos.sh`. It rebuilds `data/repos.json` from scratch each time: for every non-archived, non-fork repo across `orgs` (+ the personal account) in `config.json`, it checks the GitHub commit-search API for commits authored by `author_emails[0]`, and keeps only repos with at least one hit. Each entry records `fullname`, `pushedAt`, `authoredCommits`, and `localPath` (if a matching clone exists under `local_roots`).

This calls the GitHub search API once per repo with a ~1.5s pace to stay under rate limits, so it can take several minutes across ~150 repos - that's expected, let it run.

### Step 2 - Cache manifests

Run `scripts/fetch-manifests.sh`. For each repo in `data/repos.json` it writes `data/manifests/<owner>__<name>.json` containing the flattened dependency names from `composer.json`/`package.json` (`require`+`require-dev`, `dependencies`+`devDependencies`, PHP extension stubs and the bare `php` entry filtered out) plus a short README excerpt. It prefers reading a local clone when `localPath` is set (fast, free) and falls back to the GitHub Contents API otherwise. Repos whose cached `pushedAt` already matches the live value are skipped, so re-runs are incremental - safe to run often.

### Step 2.5 - Fill in what the manifests can't show

Package names tell you *what libraries* were used, not *what was integrated by hand*. Some of the best skill signals from past analyses came from README prose (e.g. SOAP/legacy ERP integration, GPS telematics, SMS gateways, multi-domain routing) rather than a dependency name. Skim the `readmeExcerpt` field and, for repos that look unusually significant (high `authoredCommits`, or a name you don't recognize), open the actual README for more context.

### Step 3 - Diff against the current Skills section

Read the `->addSkill(...)` blocks in `build.php` (`data/config.json` -> `build_php_path`) and their `keywords` arrays. Aggregate every dependency name across all cached manifests, weighted by how many distinct repos use it. Map package names to the human-readable technology they represent (e.g. `stancl/tenancy` -> "Multi-tenancy", `laravel/reverb` -> "Laravel Reverb / real-time"), and fuzzy-match against existing keywords so you don't suggest something already covered under different wording.

Only surface a candidate skill if:
- it appears in 2+ repos, OR it's a single strong, resume-worthy signal (e.g. `openai-php/laravel`, `@simplewebauthn/browser`) that isn't generic dev tooling, AND
- it isn't already represented (even loosely) by an existing keyword.

Skip pure dev-tooling noise that's already implied by existing categories (faker, mockery, collision, individual eslint plugins, etc.) unless the user's `Testing`/`DevOps` categories genuinely don't cover the pattern yet.

### Step 4 - Report, don't edit

Present findings grouped by: additions to existing skill categories, vs. gaps that need a new category - each with the repos that justify it. Ask whether to apply them. Only edit `build.php` after the user approves specific wording (this mirrors how the last audit went: the user asked to see the draft before it was applied).

---

## Mode 3: New-Project Candidates

Trigger: the user wants to know if there are repos they've contributed meaningfully to that aren't reflected as a `Project` in the resume yet.

1. Run `scripts/discover-repos.sh` if `data/repos.json` is missing or stale (check `generatedAt`).
2. Read the existing `->addProject(...)` names and `url` values from `build.php`.
3. Cross-reference `data/repos.json` against that list by name/URL. For repos with no match, rank by `authoredCommits` and recency (`pushedAt`). Low-commit repos (a handful of commits, e.g. dependency bumps or a one-off fix) usually aren't worth a `Project` entry - use judgment, and say so rather than listing everything.
4. For each strong candidate, give a one-line reason (commit count, recency, and - if you opened the README/manifest - a guess at what the project actually is) so the user can decide whether it's worth a full Mode 1 pass.
5. This is a proposal only - never add a `Project` entry without the user picking it first, since Mode 1 needs to run properly (README, models, domain research) to write it well anyway.

---

## Notes on running this periodically

The user runs this manually rather than on a schedule. If asked to set up recurring execution, prefer a **local** mechanism (the repos live under `local_roots` on this machine, and several are private org repos on GitHub) over a cloud routine - a cloud agent can't see local clones and would need GitHub App access individually granted to every org, which adds friction for no benefit here.
