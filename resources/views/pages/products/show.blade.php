@extends('layouts.public')

@section('title', $product->name.' | '.($product->brand ?: 'Bedeck International'))
@section('meta_description', \Illuminate\Support\Str::limit($product->short_description ?: $product->description, 155))

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $product->name,
    'image' => $product->image_url,
    'description' => $product->short_description ?: $product->description,
    'brand' => ['@type' => 'Brand', 'name' => $product->brand ?: 'Bedeck International'],
    'category' => optional($product->category)->name,
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<nav class="breadcrumb">
    <a href="{{ route('home') }}">Home</a><span>/</span>
    <a href="{{ route('products.index') }}">Products</a><span>/</span>
    @if($product->category)
        <a href="{{ route('products.category', $product->category) }}">{{ $product->category->name }}</a><span>/</span>
    @endif
    <span>{{ $product->name }}</span>
</nav>

<div class="product-detail">
    <div class="product-detail__media" data-reveal>
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" width="800" height="800">
    </div>
    <div class="product-detail__body" data-reveal data-reveal-delay="1">
        <p class="eyebrow">{{ $product->brand ?: 'Bedeck International' }}</p>
        <h1>{{ $product->name }}</h1>
        <div class="product-detail__meta">
            @if($product->category)<span class="tag">{{ $product->category->name }}</span>@endif
            @if($product->brand)<span class="tag">{{ $product->brand }}</span>@endif
        </div>
        @if($product->short_description)<p class="lead">{{ $product->short_description }}</p>@endif
        @if($product->description)<p>{{ $product->description }}</p>@endif

        <div class="product-detail__actions">
            <a class="button primary" href="{{ whatsapp_link('Hello Bedeck International, I would like a quotation for: '.$product->name.'.') }}" target="_blank" rel="noopener">Request a Quote on WhatsApp</a>
            <a class="button secondary" href="{{ route('contact') }}?product_id={{ $product->id }}">Enquire by Form</a>
            @if($product->datasheet_url)<a class="button secondary dark" href="{{ $product->datasheet_url }}" target="_blank" rel="noopener">Download Datasheet (PDF)</a>@endif
        </div>

        <div class="spec-note">
            Pricing and lead time are quoted per project. Contact Bedeck International with your process
            conditions (flow, pressure, temperature, media and connection sizes) for a matched selection.
        </div>
    </div>
</div>

@if($related->count())
<section class="section">
    <div class="section-heading" data-reveal><p class="eyebrow">Related</p><h2>More from this category.</h2></div>
    <div class="product-grid">
        @foreach($related as $item)
            @include('partials.product-card', ['product' => $item])
        @endforeach
    </div>
</section>
@endif

@include('partials.contact-cta')
@endsection
