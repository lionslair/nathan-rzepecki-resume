#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

echo "Building resume..."

php build.php

npx resumed render output/resume.json --theme jsonresume-theme-even 2>/dev/null
mv resume.html output/resume.html
echo "✓ output/resume.html"

pandoc output/resume.md -f markdown -t docx -o output/resume.docx
echo "✓ output/resume.docx"

echo ""
echo "Done. Files in output/:"
ls -lh output/
