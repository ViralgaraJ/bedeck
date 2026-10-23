<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(SecurityHeaders::class);
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// Shared hosts that can't point the domain's Document Root at /public (it's
// locked to public_html) instead set PUBLIC_PATH=/home/user/public_html in
// .env, so uploaded files (products, datasheets, hero images, ...) land in
// the folder Apache actually serves instead of the inaccessible /public.
//
// This runs before Laravel's own env-loading bootstrapper, and once
// config:cache has been run that bootstrapper never loads .env again on any
// later request — so read PUBLIC_PATH straight from the file. Only parse it:
// loading the whole .env into the process env here made `php artisan test`
// pass DB_CONNECTION=mysql on to PHPUnit, wiping the local database.
$publicPath = env('PUBLIC_PATH');
if (! $publicPath && is_file($envFile = $app->environmentFilePath())) {
    $publicPath = \Dotenv\Dotenv::parse(file_get_contents($envFile))['PUBLIC_PATH'] ?? null;
}

if ($publicPath) {
    $app->usePublicPath($publicPath);
}

return $app;
