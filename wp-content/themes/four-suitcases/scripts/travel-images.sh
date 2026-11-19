#!/bin/sh
# Run from any directory. Keep the originals outside the public site.
set -eu
theme=$(cd "$(dirname "$0")/.." && pwd)
source_dir="$theme/../../../../../_design"
dest="$theme/assets/travel"
mkdir -p "$dest"
for file in "$source_dir"/web-photos/*.webp "$source_dir"/four-suitcases-travel-items/png/*.png "$source_dir"/lindsay-photo.jpg; do
  name=$(basename "$file")
  case "$name" in *needs-fixing*) continue ;; esac
  case "$file" in */png/*) width=960 ;; */hero*) width=1800 ;; *) width=960 ;; esac
  case "$name" in *--edited.png|*--edtied.png) name="${name%%--*}.png" ;; esac
  if [ "$width" = 960 ] && [ "${file##*.}" = png ]; then
    magick "$file" -trim +repage -resize '440x440>' -strip -quality 76 "$dest/${name%.*}-small.webp"
    magick "$file" -trim +repage -resize '960x960>' -strip -quality 80 "$dest/${name%.*}.webp"
    continue
  fi
  magick "$file" -auto-orient -resize "${width}x${width}>" -strip -quality 78 "$dest/${name%.*}.webp"
done
