@extends('layouts.admin')
@section('title', 'Add Partner')

@section('content')
<div class="admin-head">
    <h1>Add Partner</h1>
</div>
<form method="post" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data">
    @include('admin.partners._form', ['submitLabel' => 'Create partner'])
</form>
@endsection
