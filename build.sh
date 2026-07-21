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
@page { size: A4; margin: 10mm 8mm; }
@media print {
  html { font-size: 24px; }
  body {
    gap: 1em;
    margin-bottom: 0;
    font-family: Calibri, Carlito, "Liberation Sans", Arial, sans-serif;
    /* The theme's default grid reserves a 1fr gutter on each side to center a
       fixed-width (12em + 36em) column pair, which wastes ~25% of the page
       width when printed. Drop the gutters and shrink the section-label
       sidebar to just what the labels need, so content starts near the
       left edge instead of a wide mostly-empty gutter. */
    grid-template-columns: [full-start] 0 [main-start side-start] minmax(9em,12%) [side-end content-start] 1fr [main-end content-end] 0 [full-end];
  }
  .masthead { padding: 1.2em 0; }
  article > * + *, .timeline > div > * + * { margin-top: .35em; }
  .timeline > div:not(:last-child) { padding-bottom: .35rem; }
  /* Section labels (Work, References, ...) use a theme font-size that scales
     with the root, so doubling the body font for print also doubled these
     past their narrow sidebar column, overflowing into the content next to
     them. Keep them at a fixed, moderate size instead. */
  h3 { font-size: 1.15em; }
  h3, article > header { page-break-after: avoid; break-after: avoid; }
  /* Chromium mis-paginates CSS Grid across page breaks (rows overlap instead of
     flowing onto the next page), so drop to plain block/column flow for print. */
  .stack { display: block; }
  .stack > * + * { margin-top: 1em; }
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

pdfname="nathan_rzepecki_$(date +%B_%Y | tr '[:upper:]' '[:lower:]').pdf"
google-chrome --headless --disable-gpu --print-to-pdf="$(pwd)/output/$pdfname" --no-pdf-header-footer "$tmpdir/resume-print.html" 2>/dev/null
echo "✓ output/$pdfname"

echo ""
echo "Done. Files in output/:"
ls -lh output/
