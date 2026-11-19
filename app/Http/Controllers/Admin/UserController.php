<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/** Hanya Admin (dijaga middleware EnsureRole:admin pada route). Tidak ada registrasi publik. */
class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', ['users' => User::orderBy('name')->paginate(15)]);
    }

    public function create(): View
    {
        return view('admin.users.form', ['user' => new User(['role' => 'editor'])]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->safe()->only(['name', 'email', 'role']);
        $data['password'] = Hash::make($request->input('password'));
        $data['email_verified_at'] = now();

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->safe()->only(['name', 'email', 'role']);

        if ($user->isAdmin() && $data['role'] !== 'admin' && $this->isLastAdmin($user)) {
            return back()->withInput()->withErrors(['role' => 'Tidak bisa mengubah peran: ini satu-satunya Admin.']);
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun yang sedang dipakai.');
        }

        if ($user->isAdmin() && $this->isLastAdmin($user)) {
            return back()->with('error', 'Tidak bisa menghapus Admin terakhir.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna dihapus.');
    }

    private function isLastAdmin(User $user): bool
    {
        return User::where('role', 'admin')->where('id', '!=', $user->id)->doesntExist();
    }
}
