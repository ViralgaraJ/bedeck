<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title', 'Admin') · {{ setting('company_name', 'Bedeck International') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}?v=9">
    @stack('head')
</head>
<body>
<div class="admin-shell">
    <aside class="admin-side">
        <div class="admin-side__bar">
            <a class="brand" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/optimized/bedeck-logo2.jpg') }}" alt="{{ setting('company_name', 'Bedeck International') }}"> ADMIN
            </a>
            <button class="admin-nav-toggle" type="button" aria-expanded="false" aria-controls="admin-side-links" aria-label="Toggle menu" data-admin-menu-toggle>
                <span></span><span></span><span></span>
            </button>
        </div>
        <div class="admin-side__links" id="admin-side-links" data-admin-side-links>
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
            <a class="nav-link {{ $is('admin.hero-slides.*') }}" href="{{ route('admin.hero-slides.index') }}">Carousel Images</a>
            <a class="nav-link {{ $is('admin.settings.*') }}" href="{{ route('admin.settings.edit') }}">Site Settings</a>
            <a class="nav-link {{ $is('admin.profile.*') }}" href="{{ route('admin.profile.edit') }}">Account Security</a>
        </div>
        <div class="admin-side__footer">
            <a class="side-action side-action--view" href="{{ route('home') }}" target="_blank" rel="noopener">
                <span>View site</span> ↗
            </a>
            <form method="post" action="{{ route('admin.logout') }}">
                @csrf
                <button class="side-action side-action--logout" type="submit">Log out ({{ auth()->user()->name }})</button>
            </form>
        </div>
    </aside>

    <main class="admin-main">
        @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert error">{{ session('error') }}</div>@endif
        @yield('content')
    </main>
</div>
<script src="{{ asset('assets/js/admin.js') }}?v=3" defer></script>
@stack('scripts')
</body>
</html>
