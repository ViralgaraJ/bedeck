@extends('layouts.admin')
@section('title', 'Site Settings')

@section('content')
<div class="admin-head"><h1>Site Settings</h1></div>

<form method="post" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')
    <div class="card">
        <div class="form-grid">
            @foreach($fields as $key => $label)
                <label class="field {{ in_array($key, ['hero_intro']) ? 'full' : '' }}">{{ $label }}
                    @if($key === 'hero_intro')
                        <textarea name="{{ $key }}">{{ old($key, $values[$key] ?? '') }}</textarea>
                    @else
                        <input name="{{ $key }}" value="{{ old($key, $values[$key] ?? '') }}" maxlength="2000">
                    @endif
                </label>
            @endforeach
        </div>
    </div>
    <button class="btn primary" type="submit">Save settings</button>
</form>
@endsection
