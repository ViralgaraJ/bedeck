<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

/**
 * One-time installer for shared hosting without SSH / Terminal.
 *
 * Inert unless SETUP_TOKEN is set in .env. To use:
 *   1. add  SETUP_TOKEN=<long-random-string>  to .env
 *   2. visit  https://your-domain/__install/<that-string>  once
 *   3. remove the SETUP_TOKEN line from .env
 *
 * Being a real controller (not a Closure) keeps `php artisan route:cache` working.
 */
class InstallController extends Controller
{
    public function __invoke(string $token): Response
    {
        $secret = (string) env('SETUP_TOKEN');
        abort_unless($secret !== '' && hash_equals($secret, $token), 404);

        $log = [];

        if (blank(config('app.key'))) {
            Artisan::call('key:generate', ['--force' => true]);
            $log[] = 'key:generate → '.trim(Artisan::output());
        }

        Artisan::call('migrate', ['--force' => true]);
        $log[] = trim(Artisan::output());

        // Seed once, only into a genuinely empty database (no users yet) — this
        // bootstraps a fresh no-SSH deploy without ever re-running on a live site
        // and clobbering content already edited through the admin panel.
        if (User::query()->count() === 0) {
            Artisan::call('db:seed', ['--force' => true]);
            $log[] = 'db:seed → '.trim(Artisan::output());
        }

        try {
            Artisan::call('storage:link');
            $log[] = 'storage:link → '.trim(Artisan::output());
        } catch (\Throwable $e) {
            $log[] = 'storage:link skipped: '.$e->getMessage().' (harmless — uploads go to public/uploads)';
        }

        Artisan::call('config:cache');
        $log[] = 'config:cache → '.trim(Artisan::output());

        $body = '<pre style="font:14px/1.5 ui-monospace,monospace;padding:24px">'
            .e(implode("\n\n", array_filter($log)))
            ."\n\n──────────\nInstall complete. Now remove SETUP_TOKEN from .env,"
            .' then load the site.</pre>';

        return response($body, 200);
    }
}
