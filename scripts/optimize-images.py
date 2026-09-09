import os
from PIL import Image

def optimize_image(filepath, max_dimension=1920, quality=84):
    if not os.path.exists(filepath):
        print(f"Skipping non-existent: {filepath}")
        return

    orig_size = os.path.getsize(filepath) / (1024 * 1024)
    try:
        with Image.open(filepath) as img:
            # Handle EXIF orientation if present
            try:
                from PIL import ImageOps
                img = ImageOps.exif_transpose(img)
            except Exception:
                pass

            orig_w, orig_h = img.size

            # Determine new dimensions
            if max(orig_w, orig_h) > max_dimension:
                scale = max_dimension / max(orig_w, orig_h)
                new_w = int(orig_w * scale)
                new_h = int(orig_h * scale)
                resized = img.resize((new_w, new_h), Image.Resampling.LANCZOS)
            else:
                resized = img.copy()

            # Ensure RGB for JPEG/WebP
            if resized.mode in ("RGBA", "P"):
                rgb_img = Image.new("RGB", resized.size, (13, 40, 24))
                if resized.mode == "RGBA":
                    rgb_img.paste(resized, mask=resized.split()[3])
                else:
                    rgb_img.paste(resized)
            else:
                rgb_img = resized.convert("RGB")

            # Save WebP version
            base_name, _ = os.path.splitext(filepath)
            webp_path = f"{base_name}.webp"
            rgb_img.save(webp_path, "WEBP", quality=quality, method=6)
            webp_size = os.path.getsize(webp_path) / (1024 * 1024)

            # Re-save optimized JPG
            jpg_path = f"{base_name}.jpg"
            rgb_img.save(jpg_path, "JPEG", quality=quality, optimize=True)
            jpg_size = os.path.getsize(jpg_path) / (1024 * 1024)

            print(f"Optimized {filepath}: {orig_w}x{orig_h} ({orig_size:.2f} MB) -> {rgb_img.size[0]}x{rgb_img.size[1]} | JPG: {jpg_size:.2f} MB | WebP: {webp_size:.2f} MB")
    except Exception as e:
        print(f"Error optimizing {filepath}: {e}")

if __name__ == "__main__":
    targets = [
        "public/assets/images/laboratorium/foto-1.jpg",
        "public/assets/images/laboratorium/foto-2.jpg",
        "public/assets/images/laboratorium/foto-3.jpg",
        "public/assets/landing/bg-komunitas.jpg",
        "public/assets/landing/card-figma.jpg",
        "public/assets/landing/2.jpg",
        "public/assets/landing/card-labor.jpg",
        "public/assets/landing/card-perpus.jpg",
        "public/assets/landing/card-komunitas.jpg",
        "public/assets/landing/fsi.jpg",
        "public/assets/landing/tower.jpg"
    ]

    for t in targets:
        optimize_image(t)
