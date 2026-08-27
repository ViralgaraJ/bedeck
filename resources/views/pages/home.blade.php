@extends('layouts.public')

@section('title', setting('company_name', 'Bedeck International').' | Industrial Engineering Solutions Sri Lanka')

@section('content')

{{-- ============ MAGICAL HERO ============ --}}
<section class="hero">
    <div class="hero__media" aria-hidden="true">
        <div class="hero__photos">
            @foreach($heroImages as $img)
                <div data-hero-photo style="background-image:url('{{ asset($img) }}')"></div>
            @endforeach
        </div>
        <div class="hero__video" data-hero-video="{{ setting('hero_video_id', 'fdYHkgcxuiw') }}"></div>
        <div class="hero__scrim"></div>
    </div>
    <canvas class="hero__canvas" id="hero-canvas" aria-hidden="true"></canvas>
    <div class="hero__inner">
        <p class="hero__eyebrow">{{ setting('hero_eyebrow', 'Industrial equipment supplier and engineering consultant in Sri Lanka') }}</p>
        <h1 data-hero-heading>{{ setting('hero_heading', 'Engineering solutions for industrial operations.') }}</h1>
        <p class="lead">{{ setting('hero_intro', 'We bring you the best in the world. Bedeck International has supported Sri Lankan industrial requirements since 2010 with dependable engineering consultation, product sourcing and total industrial solutions.') }}</p>
        <div class="hero__actions">
            <a class="button primary" href="{{ route('products.index') }}">Explore Products</a>
            <a class="button secondary" href="{{ whatsapp_link() }}" target="_blank" rel="noopener">WhatsApp Enquiry</a>
        </div>
    </div>
    <div class="hero__scroll" aria-hidden="true">Scroll<span></span></div>
</section>

{{-- ============ TRUST BAND (count-up) ============ --}}
<div class="trust">
    <div data-reveal><strong data-count="{{ (int) setting('established_year', 2010) }}">{{ setting('established_year', '2010') }}</strong><span>Established in Sri Lanka</span></div>
    <div data-reveal data-reveal-delay="1"><strong data-count="{{ (int) setting('stat_products', 92) }}" data-suffix="+">{{ (int) setting('stat_products', 92) }}+</strong><span>Active product entries</span></div>
    <div data-reveal data-reveal-delay="2"><strong data-count="{{ (int) setting('stat_partners', 8) }}">{{ (int) setting('stat_partners', 8) }}</strong><span>Principal partner references</span></div>
    <div data-reveal data-reveal-delay="3"><strong data-count="{{ (int) setting('stat_categories', 11) }}">{{ (int) setting('stat_categories', 11) }}</strong><span>Product categories</span></div>
</div>

{{-- ============ CORE SERVICES ============ --}}
<section class="section">
    <div class="section-heading" data-reveal>
        <p class="eyebrow">Core Services</p>
        <h2>Engineering support across petroleum, process, MEP and building systems.</h2>
    </div>
    <div class="service-icon-grid">
        @foreach($services as $service)
            <a class="service-icon-item" href="{{ route('services') }}#{{ $service->slug }}" data-reveal data-reveal-delay="{{ $loop->index % 4 }}">
                @if($service->icon)<img src="{{ asset($service->icon) }}" alt="" loading="lazy">@endif
                <span>{{ $service->title }}</span>
            </a>
        @endforeach
    </div>
    <div style="margin-top:1.6rem" data-reveal><a class="button secondary dark" href="{{ route('services') }}">View Services</a></div>
</section>

{{-- ============ FEATURED PRODUCTS ============ --}}
@if($featured->count())
<section class="section muted">
    <div class="section-heading" data-reveal><p class="eyebrow">Featured</p>
        <h2>Instruments and systems we are asked about most.</h2>
        <p style="margin:.6rem 0 0"><a class="text-link" href="{{ route('products.index') }}">Browse the full 92-product catalogue &rarr;</a></p></div>
    <div class="product-grid">
        @foreach($featured as $product)
            @include('partials.product-card', ['product' => $product])
        @endforeach
    </div>
</section>
@endif

{{-- ============ WHY BEDECK ============ --}}
<section class="split">
    <div data-reveal><p class="eyebrow">Why {{ setting('company_name', 'Bedeck International') }}</p>
        <h2>Reliable engineering consultation with an international product portfolio.</h2></div>
    <div class="prose" data-reveal data-reveal-delay="1">
        <p>{{ setting('company_name', 'Bedeck International') }} is renowned across Sri Lanka as an engineering consultant since {{ setting('established_year', '2010') }}. The company is built around reputation, dependability and industrial solutions supported by international brands including ISOIL IMPIANTI Italy, OMC Italy, Fluidwell Netherlands, PROTECH India, Accord Fuel Services India and UFLOW India.</p>
        <p>At {{ setting('company_name', 'Bedeck International') }}, we maintain high standards of quality, technology, design and sustainability to serve customers better. We specialize in providing total solutions for industrial requirements.</p>
        <p>Vastuworld Satellite Center supports Vastu-related requirements for land selection, property purchase, built-house evaluation and corrective recommendations.</p>
    </div>
</section>

{{-- ============ PARTNERS ============ --}}
@if($partners->count())
<section class="section muted">
    <div class="section-heading" data-reveal><p class="eyebrow">Principal Partners</p>
        <h2>International brands we represent.</h2></div>
    <div class="partner-grid">
        @foreach($partners as $partner)
            <a href="{{ $partner->website ?: '#' }}" target="_blank" rel="noopener" data-reveal data-reveal-delay="{{ $loop->index % 4 }}">
                @if($partner->logo)<img src="{{ asset($partner->logo) }}" alt="{{ $partner->name }} logo" loading="lazy">@endif
                <span>{{ $partner->name }}@if($partner->country) — {{ $partner->country }}@endif</span>
            </a>
        @endforeach
    </div>
</section>
@endif

@include('partials.contact-cta')

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/home.js') }}?v=7" defer></script>
@endpush
