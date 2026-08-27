@extends('layouts.admin')
@section('title', 'Add category')

@section('content')
<div class="admin-head"><h1>Add category</h1></div>
<form method="post" action="{{ route('admin.categories.store') }}">
    @include('admin.categories._form', ['submitLabel' => 'Create category'])
</form>
@endsection
