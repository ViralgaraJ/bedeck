@extends('layouts.public')

@section('title', 'About '.setting('company_name', 'Bedeck International').' | Engineering Consultant Sri Lanka')
@section('meta_description', 'Learn about Bedeck International, a Sri Lankan multi-disciplinary independent engineering consulting organization established in 2010.')

@section('content')
@include('partials.page-hero', [
    'pageKey' => 'about',
    'eyebrow' => 'About',
    'heading' => 'Multi-disciplinary engineering consulting from Sri Lanka.',
    'text' => 'Bedeck International provides products and services for a wide range of industrial needs supported by technical experience and international product references.',
    'crumbs' => [
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'About', 'url' => route('about')],
    ],
])

<section class="split">
    <div data-reveal><p class="eyebrow">Company Profile</p>
        <h2>Established in {{ setting('established_year', '2010') }} with an industrial engineering focus.</h2></div>
    <div class="prose" data-reveal data-reveal-delay="1">
        <p>{{ setting('company_name', 'Bedeck International') }} is a multi-disciplinary independent engineering consulting firm established in 2010. The company is supported by a team of experienced professionals with over 30 years of combined industry experience, providing a wide range of products and services to meet the diverse requirements of various industrial sectors.</p>
        <p>The company headquarters are at {{ setting('address', 'No. 10/3 Salmal Place, Devala Road, Depanama, Pannipitiya, Sri Lanka') }}.</p>
        <div class="mission-grid">
            <article><h3>Vision</h3><p>To be recognized through performance and excellence as a leading engineering consultancy delivering innovative and reliable solutions that consistently meet and exceed our customers’ needs.</p></article>
            <article><h3>Mission</h3><p>As a socially responsible multi-disciplinary engineering consultancy Bedeck International is committed to growing and developing through professionalism innovation and consistent performance. We strive to deliver reliable and value-driven solutions that ensure customer satisfaction foster a secure and rewarding working environment for our employees, and achieve sustainable profitability.</p></article>
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
