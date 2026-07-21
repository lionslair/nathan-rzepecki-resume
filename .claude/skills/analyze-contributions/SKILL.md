---
name: Analyze Contributions & Sync Resume
description: Analyzes git commits for a local or remote repository to generate resume-ready description and highlights using juststeveking/resume-php
---

# Skill: Analyze Repository Contributions for `resume-php`

When asked to analyze contributions or update a project in `resume-php`, follow these instructions to inspect git logs, generate professional summary points, and hydrate the resume model.

---

## 1. Extract Git Commit Logs

### Option A: Local Directory
Run git log filtered by author, excluding merge commits:
```bash
git -C <path-to-repo> log --author="<author-email-or-username>" --no-merges --pretty=format:"%s" -n 100