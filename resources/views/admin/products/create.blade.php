@extends('layouts.admin')
@section('title', 'Add product')

@section('content')
<div class="admin-head"><h1>Add product</h1></div>
<form method="post" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @include('admin.products._form', ['submitLabel' => 'Create product'])
</form>
@endsection

@push('scripts')<script src="{{ asset('assets/js/admin.js') }}" defer></script>@endpush
