@php($eyebrow = $eyebrow ?? '')
@php($heading = $heading ?? '')
@php($text = $text ?? '')
@php($image = $image ?? 'assets/images/site/page-products-01.webp')
@php($breadcrumb = $breadcrumb ?? null)
<section class="page-hero">
    <div class="page-hero__bg" style="background-image:url('{{ asset($image) }}')"></div>
    <div class="page-hero__inner">
        @if(isset($breadcrumb))
            <nav class="hero-breadcrumb">{!! $breadcrumb !!}</nav>
        @endif
        @if($eyebrow)<p class="eyebrow">{{ $eyebrow }}</p>@endif
        <h1 class="grad-text">{{ $heading }}</h1>
        @if($text)<p>{{ $text }}</p>@endif
    </div>
</section>
