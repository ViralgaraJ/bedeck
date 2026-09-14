<header class="site-header" data-header>
    <div class="nav-shell">
        @php($brandWords = explode(' ', setting('company_name', 'Bedeck International'), 2))
        <a class="brand" href="{{ route('home') }}" aria-label="{{ setting('company_name', 'Bedeck International') }} home">
            <span class="brand-logo-wrap">
                <img src="{{ asset('assets/optimized/bedeck-logo.webp') }}" alt="{{ setting('company_name', 'Bedeck International') }} logo" class="brand-logo-img">
            </span>
            <span class="brand-copy">
                <span class="brand-name">
                    <span>{{ strtoupper($brandWords[0]) }}</span>
                    @if(!empty($brandWords[1]))
                        <span>{{ strtoupper($brandWords[1]) }}</span>
                    @endif
                </span>
                <span class="brand-consultants">{{ strtoupper(setting('company_role', 'Engineering Consultants')) }}</span>
                @if(setting('company_tagline'))
                    <span class="brand-slogan">{{ setting('company_tagline') }}</span>
                @endif
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
        </nav>
    </div>
</header>
