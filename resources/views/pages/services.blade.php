@extends('layouts.public')

@section('title', 'Services | Petroleum, Process, MEP, EMS, BMS and FMS Sri Lanka')
@section('meta_description', 'Explore Bedeck International services for petroleum installations, process equipment, MEP supplies, energy management, building management, fuel management and Vastu consultation.')

@section('content')
@include('partials.page-hero', [
    'pageKey' => 'services',
    'eyebrow' => 'Services',
    'heading' => 'Engineering services and equipment supplies for industrial and building requirements.',
    'text' => 'Bedeck International supports petroleum, process, MEP, EMS, BMS, FMS and Vastu consultation requirements across Sri Lanka.',
])

<div class="services-intro">
    <div data-reveal>
        <p class="eyebrow">What we do</p>
        <h2>Seven service lines, one point of contact.</h2>
    </div>
    <p class="prose" data-reveal data-reveal-delay="1">
        From upstream petroleum installations to building automation and Vastu consultation, every
        engagement is backed by 30+ years of engineering experience and an international product portfolio.
    </p>
</div>

@php($accents = ['59 130 246', '34 211 238', '99 102 241', '14 165 233'])
<div class="services-bento">
    @foreach($services as $service)
        @php($wide = $loop->last && $services->count() % 3 === 1 && $services->count() > 3)
        <article class="service-card {{ $wide ? 'wide' : '' }}"
                 id="{{ $service->slug }}"
                 style="--sc: {{ $accents[$loop->index % count($accents)] }}"
                 data-reveal data-reveal-delay="{{ $loop->index % 4 }}" data-tilt>
            <span class="num">{{ sprintf('%02d', $loop->iteration) }}</span>
            <div class="ic">
                @if($service->icon)<img src="{{ asset($service->icon) }}" alt="" loading="lazy">@endif
            </div>
            <h3>{{ $service->title }}</h3>
            <p>{{ $service->body ?: $service->summary }}</p>
        </article>
    @endforeach
</div>

<div class="services-cta" style="text-align: center; margin: -1rem auto 4rem;" data-reveal>
    <a class="services-cta-link" href="{{ route('products.index') }}">
        <span>Browse All Products</span>
        <svg class="cta-arrow" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
    </a>
</div>

@include('partials.contact-cta')
@endsection
