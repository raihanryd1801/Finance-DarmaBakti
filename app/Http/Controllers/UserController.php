<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,viewer',
        ]);

        // Ambil teks sebelum '@' dari email untuk dijadikan nama otomatis
        $nameFromEmail = explode('@', $request->email)[0];

        User::create([
            'name'        => ucfirst($nameFromEmail), // Membuat huruf depan kapital (Cth: Staff)
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'permissions' => $request->input('permissions', []),
        ]);

        return redirect()->route('users.index')->with('success', 'User baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'role' => 'required|in:admin,viewer',
        ]);

        $data = [
            'role'        => $request->role,
            'permissions' => $request->input('permissions', []),
        ];

        // Jika kolom password diisi, update password baru
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data user ' . $user->email . ' berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun yang sedang Abang gunakan sendiri!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus permanen!');
    }
}