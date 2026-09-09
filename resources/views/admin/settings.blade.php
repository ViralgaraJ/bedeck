@extends('layouts.admin')
@section('title', 'Site Settings')

@section('content')
<div class="admin-head"><h1>Site Settings</h1></div>

<form method="post" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')

    <div class="card">
        <h2 style="margin-top:0">Content</h2>
        <div class="form-grid">
            @foreach($fields as $key => $label)
                <label class="field {{ $key === 'hero_intro' ? 'full' : '' }}">{{ $label }}
                    @if($key === 'hero_intro')
                        <textarea name="{{ $key }}">{{ old($key, $values[$key] ?? '') }}</textarea>
                    @else
                        <input name="{{ $key }}" value="{{ old($key, $values[$key] ?? '') }}" maxlength="2000">
                    @endif
                </label>
            @endforeach
        </div>
    </div>

    <div class="card">
        <h2 style="margin-top:0">Email delivery (Gmail SMTP)</h2>
        <p style="color:#64727a;margin-top:0">
            When enabled, every contact-form submission is emailed to the address below
            <em>and</em> stored in the Enquiries list. These values are written to the
            server's <code>.env</code> file.
        </p>

        @unless($envWritable)
            <div class="alert error">The <code>.env</code> file is not writable — email settings can't be saved from here. Fix permissions (chmod 644 .env) or edit it directly.</div>
        @endunless
        @if($configCached)
            <div class="alert" style="background:#fff6e5;border:1px solid #f0c66b;color:#7a5a12">
                Config is cached. After saving, run <code>php artisan config:cache</code> on the server for changes to take effect in production.
            </div>
        @endif

        <label class="check" style="margin-bottom:1rem">
            <input type="checkbox" name="mail_enabled" value="1" @checked(old('mail_enabled', $mail['enabled']))>
            Send enquiry emails (uncheck to only store them / write to the log)
        </label>

        <div class="form-grid">
            <label class="field full">Send enquiry notifications to
                <input type="email" name="enquiry_to" value="{{ old('enquiry_to', $mail['enquiry_to']) }}" placeholder="sales@bedeckinternational.lk">
                <span class="hint">The inbox that should receive website enquiries.</span>
            </label>

            <label class="field">SMTP host
                <input name="mail_host" value="{{ old('mail_host', $mail['host'] ?: 'smtp.gmail.com') }}">
            </label>
            <label class="field">Port
                <input type="number" name="mail_port" value="{{ old('mail_port', $mail['port'] ?: 587) }}" min="1" max="65535">
            </label>
            <label class="field">Security
                <select name="mail_security">
                    <option value="tls" @selected(old('mail_security', $mail['security']) === 'tls')>STARTTLS (port 587)</option>
                    <option value="ssl" @selected(old('mail_security', $mail['security']) === 'ssl')>SSL / TLS (port 465)</option>
                </select>
            </label>
            <label class="field">From name
                <input name="mail_from_name" value="{{ old('mail_from_name', $mail['from_name']) }}">
            </label>

            <label class="field">Gmail address (SMTP username)
                <input type="email" name="mail_username" value="{{ old('mail_username', $mail['username']) }}" placeholder="youraccount@gmail.com" autocomplete="off">
            </label>
            <label class="field">Gmail App Password
                <input type="password" name="mail_password" value="" autocomplete="new-password"
                       placeholder="{{ $mail['has_password'] ? '•••••••••••• (leave blank to keep current)' : '16-character app password' }}">
                <span class="hint">Not your Google password. Create one at myaccount.google.com → Security → App passwords (needs 2-Step Verification).</span>
            </label>

            <label class="field full">From address
                <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $mail['from_address']) }}" placeholder="youraccount@gmail.com">
                <span class="hint">Gmail rewrites this to the authenticated account, so use the same Gmail address (or a verified “Send mail as” alias).</span>
            </label>
        </div>
    </div>

    <button class="btn primary" type="submit">Save settings</button>
</form>

<form method="post" action="{{ route('admin.settings.test-mail') }}" class="card" style="margin-top:1.2rem">
    @csrf
    <h2 style="margin-top:0">Send a test email</h2>
    <p style="color:#64727a;margin-top:0">Save your settings first, then send yourself a test to confirm Gmail accepts them.</p>
    <div style="display:flex;gap:.6rem;flex-wrap:wrap;align-items:end">
        <label class="field" style="flex:1;min-width:240px">Send test to
            <input type="email" name="test_to" value="{{ old('test_to', $mail['enquiry_to'] ?: $mail['from_address']) }}" required>
        </label>
        <button class="btn" type="submit">Send test</button>
    </div>
</form>
@endsection
