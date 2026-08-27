<header class="site-header" data-header>
    <div class="topbar">
        <span>{{ setting('company_role', 'Engineering Consultants') }} in Sri Lanka since {{ setting('established_year', '2010') }}</span>
        <a href="{{ whatsapp_link() }}" target="_blank" rel="noopener">WhatsApp {{ setting('whatsapp_display', '+94 77 171 1440') }}</a>
    </div>
    <div class="nav-shell">
        <a class="brand" href="{{ route('home') }}" aria-label="{{ setting('company_name', 'Bedeck International') }} home">
            <img src="{{ asset('assets/optimized/bedeck-logo.webp') }}" alt="{{ setting('company_name', 'Bedeck International') }} logo" width="46" height="46">
            <span class="brand-copy">
                <span class="brand-name">
                    @foreach(explode(' ', setting('company_name', 'Bedeck International')) as $part)<span>{{ strtoupper($part) }}</span>@endforeach
                </span>
                <span class="brand-consultants">{{ strtoupper(setting('company_role', 'Engineering Consultants')) }}</span>
            </span>
        </a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-menu-toggle>
            <span></span><span></span><span></span>
        </button>
        <nav class="site-nav" id="site-nav" data-nav>
            <a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Home</a>
            <a href="{{ route('about') }}" @if(request()->routeIs('about')) aria-current="page" @endif>About</a>
            <a href="{{ route('services') }}" @if(request()->routeIs('services')) aria-current="page" @endif>Services</a>
            <a href="{{ route('products.index') }}" @if(request()->routeIs('products.*')) aria-current="page" @endif>Products</a>
            <a href="{{ route('partners') }}" @if(request()->routeIs('partners')) aria-current="page" @endif>Partners</a>
            <a href="{{ route('contact') }}" @if(request()->routeIs('contact')) aria-current="page" @endif>Contact</a>
            <a class="button primary nav-cta" href="{{ whatsapp_link() }}" target="_blank" rel="noopener">Enquire</a>
        </nav>
    </div>
</header>
