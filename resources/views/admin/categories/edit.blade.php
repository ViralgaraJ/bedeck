@extends('layouts.admin')
@section('title', 'Edit '.$category->name)

@section('content')
<div class="admin-head"><h1>Edit category</h1></div>
<form method="post" action="{{ route('admin.categories.update', $category) }}">
    @include('admin.categories._form', ['method' => 'PUT', 'submitLabel' => 'Update category'])
</form>
@endsection
