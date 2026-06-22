#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

echo "Building resume..."

php build.php

npx resumed render output/resume.json --theme jsonresume-theme-even 2>/dev/null
mv resume.html output/resume.html
echo "✓ output/resume.html"

echo ""
echo "Done. Files in output/:"
ls -lh output/
