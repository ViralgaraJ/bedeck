@extends('layouts.public')

@section('title', $service->title.' | Bedeck International')
@section('meta_description', \Illuminate\Support\Str::limit($service->summary ?: $service->body, 155))
@section('og_image', $service->icon ?: 'assets/images/site/placeholder.svg')

@php($crumbs = [
    ['name' => 'Home', 'url' => route('home')],
    ['name' => 'Services', 'url' => route('services')],
    ['name' => $service->title, 'url' => route('services.show', $service)],
])

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'Service',
    'name' => $service->title,
    'description' => $service->summary ?: $service->body,
    'provider' => ['@type' => 'Organization', 'name' => 'Bedeck International'],
], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
</script>
@include('partials.breadcrumb-schema', ['crumbs' => $crumbs])
@endpush

@section('content')
<nav class="breadcrumb">
    <a href="{{ route('home') }}">Home</a><span>/</span>
    <a href="{{ route('services') }}">Services</a><span>/</span>
    <span>{{ $service->title }}</span>
</nav>

<div class="product-back-row" data-reveal>
    <a href="{{ route('services') }}" class="back-link">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        Back to Services
    </a>
</div>

@php($accents = ['59 130 246', '34 211 238', '99 102 241', '14 165 233'])
@php($sc = $accents[$serviceIndex % count($accents)])
<div class="product-detail service-detail" style="--sc: {{ $sc }}">
    <div class="product-detail__media product-detail__media--icon" data-reveal>
        @if($service->icon)
            <img src="{{ $service->icon_url }}" alt="{{ $service->title }}" width="240" height="240" style="max-width:220px;">
        @endif
    </div>
    <div class="product-detail__body" data-reveal data-reveal-delay="1">
        <div class="service-detail__badge">
            <span class="service-detail__num">{{ sprintf('%02d', $serviceIndex + 1) }} / {{ sprintf('%02d', $serviceCount) }}</span>
            <p class="eyebrow">Bedeck International</p>
        </div>
        <h1>{{ $service->title }}</h1>
        @if($service->summary)<p class="lead">{{ $service->summary }}</p>@endif

        @foreach(explode("\n\n", $service->body) as $paragraph)
            @if(trim($paragraph) !== '')
                <p>{{ trim($paragraph) }}</p>
            @endif
        @endforeach

        <div class="product-detail__actions">
            <a class="button primary" href="{{ whatsapp_link('Hello Bedeck International, I would like to enquire about: '.$service->title.'.') }}" target="_blank" rel="noopener">Enquire on WhatsApp</a>
            <a class="button secondary" href="{{ route('contact') }}">Enquire by Form</a>
        </div>
    </div>
</div>

@if($related->count())
<section class="section">
    <div class="section-heading" data-reveal><p class="eyebrow">More Services</p><h2>Explore other service lines.</h2></div>
    <div class="services-bento">
        @foreach($related as $item)
            <a class="service-card" href="{{ route('services.show', $item) }}" id="{{ $item->slug }}" style="--sc: 59 130 246" data-reveal data-reveal-delay="{{ $loop->index }}">
                <div class="ic">
                    @if($item->icon)<img src="{{ asset($item->icon) }}" alt="" loading="lazy">@endif
                </div>
                <h3>{{ $item->title }}</h3>
                <span class="text-link">More &rarr;</span>
            </a>
        @endforeach
    </div>
</section>
@endif

@include('partials.contact-cta')
@endsection
