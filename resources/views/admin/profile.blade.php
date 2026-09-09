@extends('layouts.admin')
@section('title', 'Account Security')

@section('content')
<div class="admin-head"><h1>Account Security</h1></div>

<div class="card">
    <h2 style="margin-top:0">Login email</h2>
    <p style="color:#a3adc6;margin-top:0">This is the email address you use to sign in to the admin panel.</p>

    <form method="post" action="{{ route('admin.profile.email') }}" autocomplete="off">
        @csrf @method('PUT')
        <div class="form-grid">
            <label class="field">Current email
                <input value="{{ $user->email }}" disabled>
            </label>
            <label class="field">New email
                <input type="email" name="email" value="{{ old('email') }}" required maxlength="190" autocomplete="username">
                @error('email', 'updateEmail')<span class="hint" style="color:var(--danger)">{{ $message }}</span>@enderror
            </label>
            <label class="field full">Current password, to confirm this change
                <input type="password" name="current_password" required autocomplete="current-password">
                @error('current_password', 'updateEmail')<span class="hint" style="color:var(--danger)">{{ $message }}</span>@enderror
            </label>
        </div>
        <button class="btn primary" type="submit" style="margin-top:1rem">Update email</button>
    </form>
</div>

<div class="card">
    <h2 style="margin-top:0">Password</h2>
    <p style="color:#a3adc6;margin-top:0">
        Use at least 10 characters, mixing upper and lower case letters, numbers, and a symbol.
    </p>

    <form method="post" action="{{ route('admin.profile.password') }}" autocomplete="off">
        @csrf @method('PUT')
        <div class="form-grid">
            <label class="field full">Current password
                <input type="password" name="current_password" required autocomplete="current-password">
                @error('current_password', 'updatePassword')<span class="hint" style="color:var(--danger)">{{ $message }}</span>@enderror
            </label>
            <label class="field">New password
                <input type="password" name="password" required minlength="10" autocomplete="new-password">
                @error('password', 'updatePassword')<span class="hint" style="color:var(--danger)">{{ $message }}</span>@enderror
            </label>
            <label class="field">Confirm new password
                <input type="password" name="password_confirmation" required minlength="10" autocomplete="new-password">
            </label>
        </div>
        <button class="btn primary" type="submit" style="margin-top:1rem">Update password</button>
    </form>
</div>
@endsection
