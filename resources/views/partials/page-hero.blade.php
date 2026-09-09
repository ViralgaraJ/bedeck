@php($pageKey = $pageKey ?? 'default')
@php($eyebrow = $eyebrow ?? '')
@php($heading = $heading ?? '')
@php($text = $text ?? '')
@php($crumbs = $crumbs ?? [])

@php($carouselImages = \App\Models\HeroSlide::where('page_key', $pageKey)->orderBy('sort_order')->pluck('image')->filter(fn ($p) => is_file(public_path($p)))->values()->all())
@if(empty($carouselImages))
    @php($carouselImages = ['assets/images/pages/automation-software-technology.webp'])
@endif

@section('og_image', $carouselImages[0])

@push('head')
<link rel="preload" as="image" href="{{ asset($carouselImages[0]) }}" fetchpriority="high">
@include('partials.breadcrumb-schema', ['crumbs' => $crumbs])
@endpush

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
        @if(count($crumbs))
            <nav class="hero-breadcrumb" aria-label="Breadcrumb">
                @foreach($crumbs as $crumb)
                    @if(!$loop->first)<span>/</span>@endif
                    @if($loop->last)
                        <span aria-current="page">{{ $crumb['name'] }}</span>
                    @else
                        <a href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a>
                    @endif
                @endforeach
            </nav>
        @endif
        @if($eyebrow)<p class="eyebrow">{{ $eyebrow }}</p>@endif
        <h1 class="grad-text">{{ $heading }}</h1>
        @if($text)<p>{{ $text }}</p>@endif
    </div>
</section>



