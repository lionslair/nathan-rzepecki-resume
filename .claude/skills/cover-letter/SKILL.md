---
name: cover-letter
description: Generates a tailored plain-text cover letter matching build.php resume data (work history, skills, projects) against a job ad from Seek.com.au or LinkedIn Jobs. Use when the user gives a job ad and wants a cover letter written to match it.
---

# Skill: Cover Letter Generator

Writes a cover letter grounded in the actual resume data in `build.php` (via `resume-php` — see that skill for the data model), tailored to a specific job ad. Never invents skills or experience that aren't in the resume — if the ad wants something not covered, the letter just doesn't address it rather than stretching the truth.

## 1. Get the Job Ad

**Always ask the user to paste the job ad text** — don't attempt to fetch the Seek/LinkedIn URL directly. Both sites are JS-rendered and typically block plain scraping or gate the full description behind login, so a fetch attempt is unreliable and wastes a turn. If the user only gives a URL, ask them to open it and paste back:

- Job title
- Company name
- Full description / responsibilities / requirements text

## 2. Extract Requirements

From the pasted text, pull out:
- Company name, role title, location/work arrangement (remote/hybrid/onsite)
- Must-have requirements and tech stack keywords
- Nice-to-haves
- Seniority level
- Any culture/mission language worth echoing in the opening (what the company actually does, not generic corporate-speak)

## 3. Load Resume Data

Ensure `output/resume.json` is current before reading it:

```bash
[ output/resume.json -nt build.php ] || php build.php
```

This only regenerates the JSON/YAML/Markdown exports (no Chrome/PDF step needed here — that's `build.sh`'s job). Read `output/resume.json` for `basics`, `work`, `skills`, and `projects` — or read `build.php` directly if that's easier, it's equally structured.

## 4. Match

Identify the 3-5 strongest overlaps between the ad's requirements and the resume: specific technologies, named projects/employers, quantifiable achievements, years of experience. Prefer concrete matches (e.g. "built Stripe Connect payouts and PCI-compliant checkout at Best Rated Transport") over restating skill keywords. Skip anything the resume doesn't actually support — don't pad with a weak or generic match just to cover every requirement.

## 5. Draft the Letter

**Plain text only — no markdown formatting** (no headers, bold, bullets). Job application forms almost always take a plain-text paste into a textarea, not a file.

Structure:
1. Greeting — `Dear Hiring Manager,` or `Dear [Company] Team,` unless a named contact is given in the ad.
2. Opening line naming the role and company, with a hook tied to what the company does (from the ad), not a generic "I am writing to apply for...".
3. 2-3 body paragraphs mapping specific resume experience to the ad's stated requirements — this is the core of the letter.
4. Short closing paragraph (availability/enthusiasm), noting a preference for initial contact by email — Nathan doesn't answer phone calls from unknown or unexpected numbers — then sign-off with `Nathan Rzepecki`.

Aim for ~250-400 words (one screen, not a full page). Match the voice already used in `build.php`'s `Basics.summary` and project highlights — direct, outcome-oriented, no filler adjectives.

## 6. Save and Present

Save to `output/cover-letters/<company-slug>-<role-slug>.txt` (create the directory if missing — `output/` is already gitignored, so this never gets committed). Also show the full letter text in the chat response so the user can copy-paste it straight into the application form without opening the file.

Treat it as a first draft — offer to adjust tone, trim length, or re-weight which projects get emphasized if the user wants changes.
