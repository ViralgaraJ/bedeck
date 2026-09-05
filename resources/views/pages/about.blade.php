@extends('layouts.public')

@section('title', 'About '.setting('company_name', 'Bedeck International').' | Engineering Consultant Sri Lanka')
@section('meta_description', 'Learn about Bedeck International, a Sri Lankan multi-disciplinary independent engineering consulting organization established in 2010.')

@section('content')
@include('partials.page-hero', [
    'pageKey' => 'about',
    'eyebrow' => 'About',
    'heading' => 'Multi-disciplinary engineering consulting from Sri Lanka.',
    'text' => 'Bedeck International provides products and services for a wide range of industrial needs, supported by technical experience and international product references.',
])

<section class="split">
    <div data-reveal><p class="eyebrow">Company Profile</p>
        <h2>Established in {{ setting('established_year', '2010') }} with an industrial engineering focus.</h2></div>
    <div class="prose" data-reveal data-reveal-delay="1">
        <p>{{ setting('company_name', 'Bedeck International') }} is a multi-disciplinary independent engineering consulting firm established in {{ setting('established_year', '2010') }} with a team of experienced individuals with more than 30+ years of working experience and offering products and services required for various industrial needs.</p>
        <p>The company headquarters are at {{ setting('address', 'No. 10/3 Salmal Place, Devala Road, Depanama, Pannipitiya, Sri Lanka') }}.</p>
        <div class="mission-grid">
            <article><h3>Vision</h3><p>To be recognized through performance and excellence as the leading consultants providing solutions to the customer’s needs.</p></article>
            <article><h3>Mission</h3><p>As a socially responsible multi-disciplinary consultant, Bedeck International will grow and develop through professionalism, innovation and consistent performance while providing security to staff and satisfaction to clients.</p></article>
        </div>
    </div>
</section>

<section class="section muted">
    <div class="section-heading" data-reveal><p class="eyebrow">Service Areas</p><h2>Industrial services and equipment support.</h2></div>
    <div class="service-icon-grid">
        @foreach($services as $service)
            <a class="service-icon-item" href="{{ route('services') }}#{{ $service->slug }}" data-reveal data-reveal-delay="{{ $loop->index % 4 }}">
                @if($service->icon)<img src="{{ asset($service->icon) }}" alt="" loading="lazy">@endif
                <span>{{ $service->title }}</span>
            </a>
        @endforeach
    </div>
</section>

@include('partials.contact-cta')
@endsection
