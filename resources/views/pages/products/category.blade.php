@extends('layouts.public')

@section('title', $category->name.' | Products | '.setting('company_name', 'Bedeck International'))
@section('meta_description', \Illuminate\Support\Str::limit($category->description ?: $category->name.' from Bedeck International, Sri Lanka.', 155))

@section('content')
@include('partials.page-hero', [
    'pageKey' => 'products',
    'eyebrow' => 'Product Category',
    'heading' => $category->name,
    'text' => $category->description ?: 'Explore our range of high-quality '.$category->name.' supplied by Bedeck International.',
    'crumbs' => [
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Products', 'url' => route('products.index')],
        ['name' => $category->name, 'url' => route('products.category', $category)],
    ],
])

@include('partials.catalog', [
    'products' => $products,
    'categories' => $categories,
    'q' => $q,
    'activeCategory' => $category,
    'action' => route('products.category', $category),
])

@include('partials.contact-cta')
@endsection
