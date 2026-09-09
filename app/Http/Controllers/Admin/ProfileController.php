<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.profile', [
            'user' => Auth::user(),
        ]);
    }

    /** Change the login email — requires the current password. */
    public function updateEmail(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validateWithBag('updateEmail', [
            'current_password' => ['required', 'current_password'],
            'email' => ['required', 'string', 'email', 'max:190', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->forceFill(['email' => $data['email']])->save();

        $request->session()->regenerate();

        return back()->with('success', 'Login email updated.');
    }

    /** Change the password — requires the current password and a strong new one. */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                Password::min(10)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        if (Hash::check($data['password'], $user->password)) {
            return back()
                ->withErrors(['password' => 'Choose a password that is different from your current one.'], 'updatePassword')
                ->withInput();
        }

        $user->forceFill(['password' => $data['password']])->save();

        $request->session()->regenerate();

        return back()->with('success', 'Password updated.');
    }
}
