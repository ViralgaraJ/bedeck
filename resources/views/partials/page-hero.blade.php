@php($pageKey = $pageKey ?? 'default')
@php($eyebrow = $eyebrow ?? '')
@php($heading = $heading ?? '')
@php($text = $text ?? '')
@php($breadcrumb = $breadcrumb ?? null)

@php($pageImageMap = [
    'about' => [
        'assets/images/pages/software.webp',
        'assets/images/pages/BLOG_gas-station.jpg',
        'assets/images/pages/LA-IT-Services-1.jpg',
        'assets/images/pages/unnamed.jpg',
    ],
    'services' => [
        'assets/images/pages/fundamentals_measurement1.jpg',
        'assets/images/pages/mep1.jpg',
        'assets/images/pages/Tb09300179_g.jpg',
        'assets/images/pages/service-4.jpg',
    ],
    'products' => [
        'assets/images/pages/FW-Totaal_2021-incl-C595_HR.png',
        'assets/images/pages/HotelEnergyManagement1.jpg',
        'assets/images/pages/kodak-building-B326.jpg',
        'assets/images/pages/Turnaround-Support-Instrumentation-TM.jpg',
    ],
    'partners' => [
        'assets/images/pages/unnamed (1).jpg',
        'assets/images/pages/bms1.png',
        'assets/images/pages/product-engineering-services-in-Bangalore.jpg',
        'assets/images/pages/program-industrial-eng.jpg',
    ],
    'contact' => [
        'assets/images/pages/contact-us-customer-support-hotline-people-connect-150492744.jpg',
        'assets/images/pages/Contact-banner.jpg',
        'assets/images/pages/resized-image-Promo (24).jpeg',
        'assets/images/pages/business-man-showing-contact-us-260nw-763718359.webp',
    ],
])

@php($rawList = $pageImageMap[$pageKey] ?? [
    'assets/images/pages/software.webp',
    'assets/images/pages/BLOG_gas-station.jpg',
    'assets/images/pages/LA-IT-Services-1.jpg',
    'assets/images/pages/unnamed.jpg',
])

@php($carouselImages = collect($rawList)->filter(fn($p) => str_starts_with($p, 'assets/images/pages/') && is_file(public_path($p)))->values()->all())
@if(empty($carouselImages))
    @php($carouselImages = ['assets/images/pages/software.webp'])
@endif

<section class="page-hero">
    <div class="page-hero__carousel" data-page-hero-carousel>
        <div class="page-hero__slides">
            @foreach($carouselImages as $idx => $img)
                <div class="page-hero__slide @if($loop->first) is-active @endif"
                     data-slide-index="{{ $idx }}"
                     style="background-image:url('{{ asset($img) }}')"></div>
            @endforeach
        </div>
        <div class="page-hero__overlay"></div>

        <button type="button" class="page-hero__arrow page-hero__arrow--prev" aria-label="Previous slide" data-carousel-prev>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
        <button type="button" class="page-hero__arrow page-hero__arrow--next" aria-label="Next slide" data-carousel-next>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>

        <div class="page-hero__dots" data-carousel-dots>
            @foreach($carouselImages as $idx => $img)
                <button type="button" class="page-hero__dot @if($loop->first) is-active @endif"
                        aria-label="Go to slide {{ $idx + 1 }}" data-dot-index="{{ $idx }}"></button>
            @endforeach
        </div>
    </div>

    <div class="page-hero__inner" data-reveal>
        @if(isset($breadcrumb))
            <nav class="hero-breadcrumb">{!! $breadcrumb !!}</nav>
        @endif
        @if($eyebrow)<p class="eyebrow">{{ $eyebrow }}</p>@endif
        <h1 class="grad-text">{{ $heading }}</h1>
        @if($text)<p>{{ $text }}</p>@endif
    </div>
</section>



