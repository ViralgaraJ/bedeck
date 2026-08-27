@extends('layouts.public')

@section('title', $category->name.' | Products | '.setting('company_name', 'Bedeck International'))
@section('meta_description', \Illuminate\Support\Str::limit($category->description ?: $category->name.' from Bedeck International, Sri Lanka.', 155))

@section('content')
@include('partials.page-hero', [
    'eyebrow' => 'Products',
    'heading' => $category->name,
    'text' => $category->description,
    'image' => 'assets/images/site/page-products-01.webp',
])

<nav class="breadcrumb">
    <a href="{{ route('home') }}">Home</a><span>/</span>
    <a href="{{ route('products.index') }}">Products</a><span>/</span>
    <span>{{ $category->name }}</span>
</nav>

@include('partials.catalog', [
    'products' => $products,
    'categories' => $categories,
    'q' => $q,
    'activeCategory' => $category,
    'action' => route('products.category', $category),
])

@include('partials.contact-cta')
@endsection
