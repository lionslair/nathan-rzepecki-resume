#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

echo "Building resume..."

php build.php

npx resumed render output/resume.json --theme jsonresume-theme-even 2>/dev/null
mv resume.html output/resume.html
echo "✓ output/resume.html"

# Ensure Carlito (a free, metric-compatible Calibri clone) is available.
# Installed to the user font dir via a downloaded .deb — no root required.
if [ ! -f "$HOME/.local/share/fonts/Carlito-Regular.ttf" ]; then
  echo "Installing Carlito font (Calibri-compatible, user-local, no sudo)..."
  fonttmp="$(mktemp -d)"
  (cd "$fonttmp" && apt-get download fonts-crosextra-carlito >/dev/null 2>&1)
  mkdir -p "$HOME/.local/share/fonts"
  dpkg-deb -x "$fonttmp"/fonts-crosextra-carlito_*.deb "$fonttmp/extracted"
  cp "$fonttmp"/extracted/usr/share/fonts/truetype/crosextra/*.ttf "$HOME/.local/share/fonts/"
  fc-cache -f "$HOME/.local/share/fonts" >/dev/null
  rm -rf "$fonttmp"
  echo "✓ Carlito installed"
fi

# PDF (rendered from the HTML theme via headless Chrome, with print-tuned CSS)
tmpdir="$(mktemp -d)"
trap 'rm -rf "$tmpdir"' EXIT

cat > "$tmpdir/print.css" <<'CSS'
@page { size: A4; margin: 14mm 12mm; }
@media print {
  html { font-size: 10.5px; }
  body { gap: 1.2em; margin-bottom: 0; font-family: Calibri, Carlito, "Liberation Sans", Arial, sans-serif; }
  .masthead { padding: 2em 0; }
  article > * + *, .timeline > div > * + * { margin-top: .4em; }
  .timeline > div:not(:last-child) { padding-bottom: .5rem; }
  h3, article > header { page-break-after: avoid; break-after: avoid; }
  /* Chromium mis-paginates CSS Grid across page breaks (rows overlap instead of
     flowing onto the next page), so drop to plain block/column flow for print. */
  .stack { display: block; }
  .stack > * + * { margin-top: 1.5em; }
  .grid-list { display: block; columns: 2; column-gap: 2em; }
  .grid-list > * { break-inside: avoid; margin-bottom: 1em; }
  blockquote { break-inside: avoid-page; }
}
CSS

awk -v cssfile="$tmpdir/print.css" '
  /<\/head>/ {
    print "<style>"
    while ((getline line < cssfile) > 0) print line
    print "</style>"
  }
  { print }
' output/resume.html > "$tmpdir/resume-print.html"

google-chrome --headless --disable-gpu --print-to-pdf="$(pwd)/output/resume.pdf" --no-pdf-header-footer "$tmpdir/resume-print.html" 2>/dev/null
echo "✓ output/resume.pdf"

echo ""
echo "Done. Files in output/:"
ls -lh output/
