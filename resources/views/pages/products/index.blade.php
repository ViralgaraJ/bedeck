@extends('layouts.public')

@section('title', 'Products | Category Index | '.setting('company_name', 'Bedeck International').' Sri Lanka')
@section('meta_description', 'Browse Bedeck International product categories for metering equipment, pumping equipment, electronic devices, valves, pressure, level, temperature, automation, MEP and accessories.')

@section('content')
@include('partials.page-hero', [
    'pageKey' => 'products',
    'eyebrow' => 'Catalogue',
    'heading' => 'Industrial Products & Systems',
    'text' => 'Browse our range of specialized instruments, metering units, valves, transmitters, and engineering solutions.',
])

@include('partials.catalog', [
    'products' => $products,
    'categories' => $categories,
    'q' => $q,
    'activeCategory' => $activeCategory,
])

@include('partials.contact-cta')
@endsection
