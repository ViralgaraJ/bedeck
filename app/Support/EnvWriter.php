<?php

namespace App\Support;

use RuntimeException;

/**
 * Minimal writer for the project's .env file.
 *
 * Only ever called with an explicit whitelist of keys (see Admin\SettingController),
 * never with arbitrary user-supplied key names.
 */
class EnvWriter
{
    /**
     * Upsert the given KEY => value pairs into .env.
     *
     * @param  array<string,string|null>  $pairs
     */
    public static function write(array $pairs, ?string $path = null): void
    {
        $path ??= app()->environmentFilePath();

        if (! is_file($path) || ! is_writable($path)) {
            throw new RuntimeException('The .env file is missing or not writable ('.$path.').');
        }

        $contents = file_get_contents($path);

        foreach ($pairs as $key => $value) {
            $key = strtoupper(preg_replace('/[^A-Z0-9_]/i', '', $key));
            if ($key === '') {
                continue;
            }

            $line = $key.'='.self::formatValue((string) ($value ?? ''));
            $pattern = '/^'.preg_quote($key, '/').'=.*$/m';

            if (preg_match($pattern, $contents)) {
                $contents = preg_replace_callback($pattern, fn () => $line, $contents, 1);
            } else {
                $contents = rtrim($contents, "\r\n")."\n".$line."\n";
            }
        }

        file_put_contents($path, $contents, LOCK_EX);
    }

    private static function formatValue(string $value): string
    {
        if ($value === '') {
            return '';
        }

        // Quote when the value contains whitespace or characters that would
        // otherwise be misread by the dotenv parser.
        if (preg_match('/[\s"\'#=$\\\\]/', $value)) {
            return '"'.str_replace(['\\', '"'], ['\\\\', '\\"'], $value).'"';
        }

        return $value;
    }
}
