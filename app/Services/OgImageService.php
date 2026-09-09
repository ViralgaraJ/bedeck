<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use RuntimeException;

/**
 * Handles the site-wide "social sharing" image used in og:image / WhatsApp
 * and Facebook link previews.
 *
 * Every upload is centre-cropped to 1200x630 (Meta's recommended 1.91:1
 * ratio) and re-encoded as JPEG — link-preview crawlers are far more
 * reliable with JPEG than with WEBP/PNG/GIF, whatever format was uploaded.
 */
class OgImageService
{
    public const WIDTH = 1200;

    public const HEIGHT = 630;

    /** Relative path under /public. Fixed name so re-uploads simply replace it. */
    public string $path = 'uploads/settings/og-share-image.jpg';

    public function store(UploadedFile $file): string
    {
        $src = $this->readImage($file);
        $w = imagesx($src);
        $h = imagesy($src);

        $targetDir = public_path(dirname($this->path));
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $srcRatio = $w / max($h, 1);
        $targetRatio = self::WIDTH / self::HEIGHT;

        if ($srcRatio > $targetRatio) {
            $cropH = $h;
            $cropW = (int) round($h * $targetRatio);
        } else {
            $cropW = $w;
            $cropH = (int) round($w / $targetRatio);
        }
        $sx = (int) (($w - $cropW) / 2);
        $sy = (int) (($h - $cropH) / 2);

        $dst = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, self::WIDTH, self::HEIGHT, $white);
        imagecopyresampled($dst, $src, 0, 0, $sx, $sy, self::WIDTH, self::HEIGHT, $cropW, $cropH);

        imagejpeg($dst, public_path($this->path), 87);

        imagedestroy($src);
        imagedestroy($dst);

        return $this->path;
    }

    public function delete(): void
    {
        $abs = public_path($this->path);
        if (is_file($abs)) {
            @unlink($abs);
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
}
