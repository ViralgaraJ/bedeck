<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Handles product image uploads.
 *
 * Upload spec enforced here + in Admin\ProductRequest:
 *   - Accepted types : JPG, PNG, WEBP
 *   - Min dimensions : 600 x 600 px
 *   - Recommended    : 1200 x 1200 px, square (1:1)
 *   - Max file size  : 4 MB
 *
 * Every upload is centre-cropped to a square and written as WEBP in two sizes:
 *   - {slug}.webp        1200 x 1200  (primary / detail page)
 *   - {slug}-thumb.webp   600 x 600   (catalogue grid)
 */
class ProductImageService
{
    public const FULL_SIZE = 1200;

    public const THUMB_SIZE = 600;

    public const MAX_BYTES = 12 * 1024 * 1024;

    public const ACCEPT = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    /** Relative directory under /public. */
    public string $dir = 'uploads/products';

    /**
     * @return array{image:string,image_thumb:string}
     */
    public function store(UploadedFile $file, string $slug): array
    {
        $slug = Str::slug($slug) ?: 'product-'.Str::random(6);
        $src = $this->readImage($file);

        $w = imagesx($src);
        $h = imagesy($src);
        $ratio = $w / max($h, 1);

        $targetDir = public_path($this->dir);
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Smart Crop / Fit Strategy:
        // - Roughly square (0.8 <= ratio <= 1.25): Center crop to fill the square canvas cleanly.
        // - Non-square (landscape or portrait): Fit entire image inside square canvas centered on a crisp white matte so no details are cut off.
        if ($ratio >= 0.8 && $ratio <= 1.25) {
            $side = min($w, $h);
            $sx = (int) (($w - $side) / 2);
            $sy = (int) (($h - $side) / 2);

            $full = $this->squareCrop($src, $sx, $sy, $side, self::FULL_SIZE);
            $thumb = $this->squareCrop($src, $sx, $sy, $side, self::THUMB_SIZE);
        } else {
            $full = $this->squareFit($src, $w, $h, self::FULL_SIZE);
            $thumb = $this->squareFit($src, $w, $h, self::THUMB_SIZE);
        }

        $imagePath = $this->dir.'/'.$slug.'.webp';
        $thumbPath = $this->dir.'/'.$slug.'-thumb.webp';

        imagewebp($full, public_path($imagePath), 85);
        imagewebp($thumb, public_path($thumbPath), 82);

        imagedestroy($src);
        imagedestroy($full);
        imagedestroy($thumb);

        return ['image' => $imagePath, 'image_thumb' => $thumbPath];
    }

    /** Remove previously uploaded files for this product (never touches seeded assets/). */
    public function delete(?string ...$paths): void
    {
        foreach ($paths as $path) {
            if ($path && Str::startsWith($path, $this->dir.'/')) {
                $abs = public_path($path);
                if (is_file($abs)) {
                    @unlink($abs);
                }
            }
        }
    }

    private function readImage(UploadedFile $file): \GdImage
    {
        $data = file_get_contents($file->getRealPath());
        $img = @imagecreatefromstring($data);
        if (! $img instanceof \GdImage) {
            throw new RuntimeException('Unsupported or corrupt image file.');
        }

        return $img;
    }

    private function squareCrop(\GdImage $src, int $sx, int $sy, int $side, int $targetSize): \GdImage
    {
        $dst = imagecreatetruecolor($targetSize, $targetSize);
        // White matte so transparent PNGs export cleanly on the product card.
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $targetSize, $targetSize, $white);
        imagecopyresampled($dst, $src, 0, 0, $sx, $sy, $targetSize, $targetSize, $side, $side);

        return $dst;
    }

    private function squareFit(\GdImage $src, int $w, int $h, int $targetSize): \GdImage
    {
        $dst = imagecreatetruecolor($targetSize, $targetSize);
        // White matte background for non-square product images
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $targetSize, $targetSize, $white);

        if ($w >= $h) {
            $newW = $targetSize;
            $newH = (int) round($h * ($targetSize / $w));
            $dstX = 0;
            $dstY = (int) round(($targetSize - $newH) / 2);
        } else {
            $newH = $targetSize;
            $newW = (int) round($w * ($targetSize / $h));
            $dstX = (int) round(($targetSize - $newW) / 2);
            $dstY = 0;
        }

        imagecopyresampled($dst, $src, $dstX, $dstY, 0, 0, $newW, $newH, $w, $h);

        return $dst;
    }
}
