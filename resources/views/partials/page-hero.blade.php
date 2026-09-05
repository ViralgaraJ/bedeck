@php($pageKey = $pageKey ?? 'default')
@php($eyebrow = $eyebrow ?? '')
@php($heading = $heading ?? '')
@php($text = $text ?? '')
@php($breadcrumb = $breadcrumb ?? null)

@php($pageImageMap = [
    'about' => [
        'assets/images/pages/20160526_132916.jpg',
        'assets/images/pages/Turnaround-Support-Instrumentation-TM.jpg',
        'assets/images/pages/fundamentals_measurement1.jpg',
        'assets/images/pages/extra-03799935.jpg',
        'assets/images/pages/istockphoto-494128660-612x612.jpg',
    ],
    'services' => [
        'assets/images/pages/BLOG_gas-station.jpg',
        'assets/images/pages/Future-of-Petroleum-Automation.jpg',
        'assets/images/pages/HotelEnergyManagement1.jpg',
        'assets/images/pages/bms1.png',
        'assets/images/pages/20160526_132916.jpg',
    ],
    'products' => [
        'assets/images/pages/FW-Totaal_2021-incl-C595_HR.png',
        'assets/images/pages/333-3339647_fleet-monitoring-benefits-camera-systems-for-monitoring-petrol.png',
        'assets/images/pages/real-time-digital-insights-and-automation-powering-digital-and-marketers-performance.png',
        'assets/images/pages/Future-of-Petroleum-Automation.jpg',
        'assets/images/pages/fundamentals_measurement1.jpg',
    ],
    'partners' => [
        'assets/images/pages/FW-Totaal_2021-incl-C595_HR.png',
        'assets/images/pages/26-06-171498539192-770x440_intro.png',
        'assets/images/pages/real-time-digital-insights-and-automation-powering-digital-and-marketers-performance.png',
        'assets/images/pages/extra-03799935.jpg',
    ],
    'contact' => [
        'assets/images/pages/BLOG_gas-station.jpg',
        'assets/images/pages/42.jpg',
        'assets/images/pages/22-1.jpg',
        'assets/images/pages/imageedit_16_7962794877.jpg',
    ],
])

@php($rawList = $pageImageMap[$pageKey] ?? [
    'assets/images/pages/20160526_132916.jpg',
    'assets/images/pages/Turnaround-Support-Instrumentation-TM.jpg',
    'assets/images/pages/fundamentals_measurement1.jpg',
    'assets/images/pages/extra-03799935.jpg',
    'assets/images/pages/BLOG_gas-station.jpg',
    'assets/images/pages/Future-of-Petroleum-Automation.jpg',
    'assets/images/pages/HotelEnergyManagement1.jpg',
    'assets/images/pages/bms1.png',
    'assets/images/pages/FW-Totaal_2021-incl-C595_HR.png',
])

@php($carouselImages = collect($rawList)->filter(fn($p) => str_starts_with($p, 'assets/images/pages/') && is_file(public_path($p)))->shuffle()->values()->all())
@if(empty($carouselImages))
    @php($carouselImages = ['assets/images/pages/20160526_132916.jpg'])
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



