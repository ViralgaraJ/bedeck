<?php

use App\Models\SiteSetting;
use Illuminate\Support\Str;

if (! function_exists('setting')) {
    /**
     * Read a site setting with a fallback default.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        try {
            return SiteSetting::get($key, $default);
        } catch (Throwable) {
            return $default;
        }
    }
}

if (! function_exists('whatsapp_link')) {
    /**
     * Build a wa.me link with a prefilled enquiry message.
     */
    function whatsapp_link(?string $text = null): string
    {
        $number = preg_replace('/\D+/', '', (string) setting('whatsapp', '94771711440'));
        $text ??= 'Hello Bedeck International, I would like to request information about your industrial products and engineering solutions.';

        return 'https://wa.me/'.$number.'?text='.rawurlencode($text);
    }
}

if (! function_exists('og_image_meta')) {
    /**
     * Resolve an Open Graph image (a local relative path like 'assets/...', or
     * an already-absolute URL) into its URL plus real width/height/mime type.
     *
     * Facebook/WhatsApp's link-preview crawler frequently fails to render a
     * preview image when it has to guess dimensions and format itself —
     * declaring them explicitly via og:image:width/height/type avoids that.
     *
     * @return array{url:string,width:?int,height:?int,mime:?string}
     */
    function og_image_meta(string $pathOrUrl): array
    {
        if (Str::startsWith($pathOrUrl, ['http://', 'https://'])) {
            return ['url' => $pathOrUrl, 'width' => null, 'height' => null, 'mime' => null];
        }

        $info = @getimagesize(public_path($pathOrUrl)) ?: [];

        return [
            'url' => asset($pathOrUrl),
            'width' => $info[0] ?? null,
            'height' => $info[1] ?? null,
            'mime' => $info['mime'] ?? null,
        ];
    }
}
