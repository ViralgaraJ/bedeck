@extends('layouts.admin')
@section('title', 'Edit Partner')

@section('content')
<div class="admin-head">
    <h1>Edit Partner: {{ $partner->name }}</h1>
</div>
<form method="post" action="{{ route('admin.partners.update', $partner) }}" enctype="multipart/form-data">
    @include('admin.partners._form', ['method' => 'PUT', 'submitLabel' => 'Update partner'])
</form>
@endsection
