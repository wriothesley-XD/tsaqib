#!/bin/bash
# Ponytail ultra: Image optimization in one pass
# Usage: ./optimize-images.sh
# Requirements: cwebp (webp) or imagemagick (convert)

set -e

echo "🖼️  Optimizing landing images..."

TARGET_DIR="public/assets/landing"
BACKUP_DIR="public/assets/landing-backup-$(date +%Y%m%d)"

# Backup original
mkdir -p "$BACKUP_DIR"
cp "$TARGET_DIR"/*.jpg "$BACKUP_DIR"/ 2>/dev/null || true

# Convert to WebP with compression
if command -v cwebp &> /dev/null; then
    for img in "$TARGET_DIR"/*.jpg; do
        echo "Converting $(basename "$img") → WebP..."
        cwebp -q 85 "$img" -o "${img%.jpg}.webp"
    done
elif command -v convert &> /dev/null; then
    for img in "$TARGET_DIR"/*.jpg; do
        echo "Converting $(basename "$img") → WebP..."
        convert "$img" -quality 85 "${img%.jpg}.webp"
    done
else
    echo "❌ Install cwebp or imagemagick first"
    exit 1
fi

echo "✅ Done. Backups in $BACKUP_DIR"
echo "📝 Next: Update Blade templates to use .webp files"
