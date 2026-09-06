<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title', 'Admin') · {{ setting('company_name', 'Bedeck International') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}?v=3">
    @stack('head')
</head>
<body>
<div class="admin-shell">
    <aside class="admin-side">
        <a class="brand" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets/optimized/bedeck-logo.webp') }}" alt=""> BEDECK ADMIN
        </a>
        @php($is = fn($p) => request()->routeIs($p) ? 'active' : '')
        <a class="nav-link {{ $is('admin.dashboard') }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a class="nav-link {{ $is('admin.products.*') }}" href="{{ route('admin.products.index') }}">Products</a>
        <a class="nav-link {{ $is('admin.categories.*') }}" href="{{ route('admin.categories.index') }}">Categories</a>
        <a class="nav-link {{ $is('admin.partners.*') }}" href="{{ route('admin.partners.index') }}">Partners</a>
        <a class="nav-link {{ $is('admin.enquiries.*') }}" href="{{ route('admin.enquiries.index') }}">
            Enquiries
            @php($unread = \App\Models\Enquiry::where('is_read', false)->count())
            @if($unread)<span class="badge-pill">{{ $unread }}</span>@endif
        </a>
        <a class="nav-link {{ $is('admin.settings.*') }}" href="{{ route('admin.settings.edit') }}">Site Settings</a>
        <div class="spacer"></div>
        <a class="nav-link" href="{{ route('home') }}" target="_blank" rel="noopener">View site ↗</a>
        <form method="post" action="{{ route('admin.logout') }}">
            @csrf
            <button class="logout" type="submit">Log out ({{ auth()->user()->name }})</button>
        </form>
    </aside>

    <main class="admin-main">
        @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert error">{{ session('error') }}</div>@endif
        @yield('content')
    </main>
</div>
<script src="{{ asset('assets/js/admin.js') }}" defer></script>
@stack('scripts')
</body>
</html>
