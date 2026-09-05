@extends('layouts.public')

@section('title', 'Principal Partners | Industrial Brand Portfolio | Bedeck International')
@section('meta_description', 'Principal partner and supplier references for Bedeck International including ISOIL, OMC, Fluidwell, Hytek, PROTECH, Accord, Excel Instruments and UFLOW.')

@section('content')
@include('partials.page-hero', [
    'pageKey' => 'partners',
    'eyebrow' => 'Partners',
    'heading' => 'International brand references we represent.',
    'text' => 'Bedeck International sources equipment through a portfolio of international suppliers and principal partners.',
])

<section class="section">
    <div class="partner-grid">
        @foreach($partners as $partner)
            <a href="{{ $partner->website ?: '#' }}" target="_blank" rel="noopener" data-reveal data-reveal-delay="{{ $loop->index % 4 }}">
                @if($partner->logo)<img src="{{ asset($partner->logo) }}" alt="{{ $partner->name }} logo" loading="lazy">@endif
                <span>{{ $partner->name }}@if($partner->country) — {{ $partner->country }}@endif</span>
            </a>
        @endforeach
    </div>
</section>

@include('partials.contact-cta')
@endsection
