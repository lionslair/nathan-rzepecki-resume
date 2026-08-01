#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

SOURCE="${1:-assets/headshot.jpg}"
OUTDIR="output/favicons"

if [ ! -f "$SOURCE" ]; then
  echo "Source photo not found: $SOURCE" >&2
  exit 1
fi

mkdir -p "$OUTDIR"

echo "Generating favicons from $SOURCE..."

# Multi-resolution favicon.ico (16/32/48)
tmpdir="$(mktemp -d)"
trap 'rm -rf "$tmpdir"' EXIT
convert "$SOURCE" -resize 16x16 "$tmpdir/16.png"
convert "$SOURCE" -resize 32x32 "$tmpdir/32.png"
convert "$SOURCE" -resize 48x48 "$tmpdir/48.png"
convert "$tmpdir/16.png" "$tmpdir/32.png" "$tmpdir/48.png" "$OUTDIR/favicon.ico"
echo "✓ $OUTDIR/favicon.ico"

# Standalone 32x32 PNG
convert "$SOURCE" -resize 32x32 "$OUTDIR/favicon-32x32.png"
echo "✓ $OUTDIR/favicon-32x32.png"

# Apple touch icon
convert "$SOURCE" -resize 180x180 "$OUTDIR/apple-touch-icon.png"
echo "✓ $OUTDIR/apple-touch-icon.png"

# Open Graph image: cover-crop the square source to 1200x630
convert "$SOURCE" -resize 1200x1200^ -gravity center -extent 1200x630 -quality 90 "$OUTDIR/og-image.jpg"
echo "✓ $OUTDIR/og-image.jpg"

echo ""
echo "Done. Upload the contents of $OUTDIR/ to the R2 bucket under a 'resume/' prefix."
