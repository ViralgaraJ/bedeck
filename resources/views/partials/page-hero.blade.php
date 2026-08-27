@php($eyebrow = $eyebrow ?? '')
@php($heading = $heading ?? '')
@php($text = $text ?? '')
@php($image = $image ?? 'assets/images/site/page-products-01.webp')
<section class="page-hero">
    <div class="page-hero__bg" data-parallax="0.18" style="background-image:url('{{ asset($image) }}')"></div>
    <div class="page-hero__inner">
        @if($eyebrow)<p class="eyebrow" data-reveal>{{ $eyebrow }}</p>@endif
        <h1 class="grad-text" data-reveal data-reveal-delay="1">{{ $heading }}</h1>
        @if($text)<p data-reveal data-reveal-delay="2">{{ $text }}</p>@endif
    </div>
</section>
