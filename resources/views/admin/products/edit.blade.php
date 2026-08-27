@extends('layouts.admin')
@section('title', 'Edit '.$product->name)

@section('content')
<div class="admin-head"><h1>Edit product</h1>
    <a class="btn" href="{{ route('products.show', $product) }}" target="_blank" rel="noopener">View on site ↗</a>
</div>
<form method="post" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @include('admin.products._form', ['method' => 'PUT', 'submitLabel' => 'Update product'])
</form>
@endsection

@push('scripts')<script src="{{ asset('assets/js/admin.js') }}" defer></script>@endpush
