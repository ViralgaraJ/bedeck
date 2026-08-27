<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Admin Login · {{ setting('company_name', 'Bedeck International') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}?v=3">
</head>
<body>
<div class="login-wrap">
    <form class="login-card" method="post" action="{{ route('admin.login.attempt') }}">
        @csrf
        <div class="brand"><img src="{{ asset('assets/optimized/bedeck-logo.webp') }}" alt=""> Bedeck International</div>
        <p style="margin-top:0;color:#64727a">Sign in to manage products and content.</p>

        @if($errors->any())
            <div class="alert error">{{ $errors->first() }}</div>
        @endif

        <label class="field" style="margin-bottom:.9rem">Email
            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        </label>
        <label class="field" style="margin-bottom:.9rem">Password
            <input type="password" name="password" required autocomplete="current-password">
        </label>
        <label class="check" style="margin-bottom:1.1rem">
            <input type="checkbox" name="remember" value="1"> Remember this device
        </label>
        <button class="btn primary" type="submit" style="width:100%;justify-content:center">Sign in</button>
    </form>
</div>
</body>
</html>
