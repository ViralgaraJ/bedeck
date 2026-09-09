<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', setting('company_name', 'Bedeck International').' | Industrial Engineering Solutions Sri Lanka')</title>
    <meta name="description" content="@yield('meta_description', 'Modern industrial engineering, metering, pumping, fuel management, automation, MEP, BMS, EMS and product supply solutions from Bedeck International in Sri Lanka.')">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">
    <meta name="theme-color" content="#040814">
    <meta property="og:title" content="@yield('title', setting('company_name', 'Bedeck International'))">
    <meta property="og:description" content="@yield('meta_description', 'Industrial engineering, product supply and consultation in Sri Lanka.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('assets/images/site/hero-slide-01.webp'))">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}?v=20">
    <script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => ['Organization', 'LocalBusiness'],
        'name' => setting('company_name', 'Bedeck International'),
        'description' => 'Industrial engineering consultancy and product supplier in Sri Lanka covering petroleum, process instrumentation, MEP, energy management and building management systems.',
        'url' => url('/'),
        'logo' => asset('assets/optimized/bedeck-logo.webp'),
        'image' => asset('assets/optimized/bedeck-logo.webp'),
        'telephone' => setting('phone', '+94 11 274 6006'),
        'email' => setting('email', 'info@bedeckinternational.lk'),
        'foundingDate' => (string) setting('established_year', '2010'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => '10/3, Salmal Place, Devala Road, Depanama',
            'addressLocality' => 'Pannipitiya',
            'postalCode' => '10230',
            'addressCountry' => 'LK',
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => setting('phone', '+94 11 274 6006'),
            'contactType' => 'sales',
            'areaServed' => 'LK',
        ],
        'sameAs' => array_values(array_filter([
            'https://www.linkedin.com/in/bedeck-international-aab605220/',
        ])),
    ], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_PRETTY_PRINT) !!}
    </script>
    @if(setting('analytics_ga4_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('analytics_ga4_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ setting('analytics_ga4_id') }}');
    </script>
    @endif
    @stack('head')
</head>
<body @yield('body_attr')>
    <a class="skip-link" href="#main">Skip to content</a>

    <div class="fx" aria-hidden="true">
        <div class="aurora" data-aurora>
            <span class="blob b1"></span>
            <span class="blob b2"></span>
            <span class="blob b3"></span>
        </div>
        <div class="grain"></div>
        <div class="spotlight" data-spotlight></div>
    </div>

    <div class="scroll-progress" data-scroll-progress></div>

    @include('partials.site-header')

    <main id="main">
        @yield('content')
    </main>

    @include('partials.site-footer')

    <a class="wa-float" href="{{ whatsapp_link() }}" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2c-5.5 0-9.96 4.46-9.96 9.96 0 1.76.46 3.48 1.34 5L2 22l5.2-1.36a9.9 9.9 0 0 0 4.84 1.24h.01c5.5 0 9.96-4.46 9.96-9.96 0-2.66-1.04-5.16-2.92-7.04A9.9 9.9 0 0 0 12.04 2zm0 1.67c2.2 0 4.27.86 5.83 2.42a8.2 8.2 0 0 1 2.42 5.85c0 4.55-3.7 8.25-8.26 8.25a8.2 8.2 0 0 1-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.24 8.24 0 0 1-1.26-4.39c0-4.55 3.7-8.24 8.25-8.24zm-2.9 4.36c-.15 0-.4.06-.6.29-.2.22-.79.77-.79 1.87s.81 2.17.92 2.32c.11.15 1.57 2.4 3.85 3.36.54.23.96.37 1.29.48.54.17 1.03.15 1.42.09.43-.06 1.34-.55 1.53-1.08.19-.53.19-.98.13-1.08-.06-.09-.2-.15-.43-.26-.22-.11-1.34-.66-1.55-.74-.21-.08-.36-.11-.51.11-.15.22-.58.74-.71.89-.13.15-.26.17-.48.06-.22-.11-.94-.35-1.79-1.11-.66-.59-1.11-1.32-1.24-1.54-.13-.22-.01-.34.1-.45.1-.1.22-.26.33-.39.11-.13.15-.22.22-.37.07-.15.04-.28-.02-.39-.06-.11-.5-1.24-.7-1.7-.18-.44-.37-.38-.51-.39-.13-.01-.28-.01-.43-.01z"/></svg>
    </a>

    <script src="{{ asset('assets/js/site.js') }}?v=3" defer></script>
    @stack('scripts')
</body>
</html>
