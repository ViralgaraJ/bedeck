<?php

use App\Models\SiteSetting;

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
