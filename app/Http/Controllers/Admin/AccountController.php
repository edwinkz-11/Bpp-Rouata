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

/** Akun Saya: setiap pengguna (Admin/Editor) dapat mengubah nama, email, dan kata sandinya sendiri. */
class AccountController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.account.edit', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => [
                Rule::requiredIf(fn () => $request->filled('password') || $request->input('email') !== $user->email),
                'nullable',
                'current_password',
            ],
            'password' => ['nullable', 'confirmed', Password::min(10)->mixedCase()->numbers()],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini salah.',
            'current_password.required' => 'Isi kata sandi saat ini untuk mengganti email atau kata sandi.',
        ], [
            'name' => 'nama', 'email' => 'email', 'password' => 'kata sandi baru', 'current_password' => 'kata sandi saat ini',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        $passwordChanged = ! empty($data['password']);

        if ($passwordChanged) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        // Sesi di perangkat lain otomatis keluar setelah kata sandi diganti.
        if ($passwordChanged) {
            Auth::logoutOtherDevices($data['password']);
        }

        return redirect()->route('admin.account.edit')->with('success', 'Akun diperbarui.');
    }
}