@extends('layouts.public')

@section('title', 'Products | Category Index | '.setting('company_name', 'Bedeck International').' Sri Lanka')
@section('meta_description', 'Browse Bedeck International product categories for metering equipment, pumping equipment, electronic devices, valves, pressure, level, temperature, automation, MEP and accessories.')

@section('content')
@include('partials.page-hero', [
    'eyebrow' => 'Products',
    'heading' => 'Product Catalogue',
    'text' => 'The catalogue is organized by category so engineering buyers can move quickly to the correct product group.',
    'image' => 'assets/images/site/page-products-01.webp',
])

<nav class="breadcrumb"><a href="{{ route('home') }}">Home</a><span>/</span><span>Products</span></nav>

@include('partials.catalog', [
    'products' => $products,
    'categories' => $categories,
    'q' => $q,
    'activeCategory' => $activeCategory,
])

@include('partials.contact-cta')
@endsection
