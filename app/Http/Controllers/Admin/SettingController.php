<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\EnvWriter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SettingController extends Controller
{
    /** Content settings stored in the site_settings table. */
    private const SITE_FIELDS = [
        'company_name' => 'Company name',
        'company_tagline' => 'Tagline / slogan',
        'company_role' => 'Role line (e.g. Engineering Consultants)',
        'established_year' => 'Established year',
        'hero_eyebrow' => 'Homepage hero — eyebrow',
        'hero_heading' => 'Homepage hero — heading',
        'hero_intro' => 'Homepage hero — intro paragraph',
        'address' => 'Address',
        'phone' => 'Phone',
        'whatsapp' => 'WhatsApp number (digits only, e.g. 94771711440)',
        'whatsapp_display' => 'WhatsApp number (display)',
        'email' => 'Primary email',
        'email_alt' => 'Secondary email',
        'analytics_ga4_id' => 'Google Analytics 4 Measurement ID (e.g. G-XXXXXXXXXX)',
    ];

    public function edit(): View
    {
        return view('admin.settings', [
            'fields' => self::SITE_FIELDS,
            'values' => SiteSetting::pluck('value', 'key')->all(),
            'mail' => $this->currentMail(),
            'envWritable' => is_writable(app()->environmentFilePath()),
            'configCached' => app()->configurationIsCached(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate(
            collect(self::SITE_FIELDS)
                ->mapWithKeys(fn ($label, $key) => [$key => ['nullable', 'string', 'max:2000']])
                ->merge([
                    'mail_enabled' => ['sometimes', 'boolean'],
                    'mail_host' => ['nullable', 'string', 'max:190'],
                    'mail_port' => ['nullable', 'integer', 'between:1,65535'],
                    'mail_security' => ['nullable', 'in:tls,ssl'],
                    'mail_username' => ['nullable', 'string', 'max:190'],
                    'mail_password' => ['nullable', 'string', 'max:190'],
                    'mail_from_name' => ['nullable', 'string', 'max:120'],
                    'mail_from_address' => ['nullable', 'email', 'max:190'],
                    'enquiry_to' => ['nullable', 'email', 'max:190'],
                ])->all()
        );

        // 1. Content settings -> database
        foreach (self::SITE_FIELDS as $key => $label) {
            SiteSetting::set($key, $data[$key] ?? null);
        }

        // 2. Mail settings -> .env
        $enabled = $request->boolean('mail_enabled');
        $username = trim((string) ($data['mail_username'] ?? ''));
        $scheme = ($data['mail_security'] ?? 'tls') === 'ssl' ? 'smtps' : 'smtp';

        $env = [
            'MAIL_MAILER' => $enabled ? 'smtp' : 'log',
            'MAIL_HOST' => ($data['mail_host'] ?? null) ?: 'smtp.gmail.com',
            'MAIL_PORT' => (string) (($data['mail_port'] ?? null) ?: 587),
            'MAIL_SCHEME' => $scheme,
            'MAIL_USERNAME' => $username,
            'MAIL_FROM_NAME' => ($data['mail_from_name'] ?? null) ?: config('app.name'),
            'MAIL_FROM_ADDRESS' => ($data['mail_from_address'] ?? null) ?: $username,
            'MAIL_ENQUIRY_TO' => $data['enquiry_to'] ?? '',
        ];

        // Only overwrite the password when a new one was typed.
        if (filled($data['mail_password'] ?? null)) {
            $env['MAIL_PASSWORD'] = $data['mail_password'];
        }

        try {
            EnvWriter::write($env);
        } catch (\Throwable $e) {
            return back()->with('error', 'Content settings saved, but email settings could not be written: '.$e->getMessage());
        }

        // Apply immediately for this + subsequent requests.
        $this->applyMailRuntime($env, $data['mail_password'] ?? null);
        Artisan::call('config:clear');

        $msg = 'Settings saved.';
        if (app()->configurationIsCached()) {
            $msg .= ' Run "php artisan config:cache" on the server to refresh the cached config.';
        }

        return back()->with('success', $msg);
    }

    public function testMail(Request $request): RedirectResponse
    {
        $to = $request->validate([
            'test_to' => ['required', 'email'],
        ])['test_to'];

        try {
            Mail::raw(
                "This is a test message from the {$request->getHost()} admin panel.\n\n"
                .'If you received it, your Gmail SMTP settings are working.',
                fn ($m) => $m->to($to)->subject('Bedeck International — SMTP test')
            );
        } catch (\Throwable $e) {
            return back()->with('error', 'Test email failed: '.$e->getMessage());
        }

        return back()->with('success', "Test email sent to {$to}. Check that inbox (and spam).");
    }

    /** @return array<string,mixed> */
    private function currentMail(): array
    {
        return [
            'enabled' => config('mail.default') === 'smtp',
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'security' => config('mail.mailers.smtp.scheme') === 'smtps' ? 'ssl' : 'tls',
            'username' => config('mail.mailers.smtp.username'),
            'has_password' => filled(config('mail.mailers.smtp.password')),
            'from_name' => config('mail.from.name'),
            'from_address' => config('mail.from.address'),
            'enquiry_to' => config('mail.enquiry_to'),
        ];
    }

    /** @param  array<string,string>  $env */
    private function applyMailRuntime(array $env, ?string $newPassword): void
    {
        config([
            'mail.default' => $env['MAIL_MAILER'],
            'mail.mailers.smtp.host' => $env['MAIL_HOST'],
            'mail.mailers.smtp.port' => (int) $env['MAIL_PORT'],
            'mail.mailers.smtp.scheme' => $env['MAIL_SCHEME'],
            'mail.mailers.smtp.username' => $env['MAIL_USERNAME'] ?: null,
            'mail.from.name' => $env['MAIL_FROM_NAME'],
            'mail.from.address' => $env['MAIL_FROM_ADDRESS'] ?: 'hello@example.com',
            'mail.enquiry_to' => $env['MAIL_ENQUIRY_TO'] ?: null,
        ]);

        if (filled($newPassword)) {
            config(['mail.mailers.smtp.password' => $newPassword]);
        }
    }
}
