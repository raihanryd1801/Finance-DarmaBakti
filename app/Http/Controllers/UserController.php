<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // --- METHOD INDEX ---
    public function index()
    {
        // 🔒 KUNCI MATI KHUSUS ADMIN
        abort_if(auth()->user()->role !== 'admin', 403, 'FORBIDDEN 🛑 : Hanya Admin yang boleh masuk ke menu Manajemen User!');

        $users = User::all();
        return view('users.index', compact('users'));
    }

    // --- METHOD CREATE ---
    public function create()
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'FORBIDDEN 🛑 : Hanya Admin yang boleh menambah User!');

        return view('users.create');
    }

    // --- METHOD STORE ---
    public function store(Request $request)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'FORBIDDEN 🛑 : Akses ditolak!');

        $request->validate([
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,viewer',
        ]);

        $nameFromEmail = explode('@', $request->email)[0];

        User::create([
            'name'        => ucfirst($nameFromEmail),
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'permissions' => $request->input('permissions', []),
        ]);

        return redirect()->route('users.index')->with('success', 'User baru berhasil ditambahkan!');
    }

    // --- METHOD EDIT ---
    public function edit($id)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'FORBIDDEN 🛑 : Hanya Admin yang boleh mengedit User!');

        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // --- METHOD UPDATE ---
    public function update(Request $request, $id)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'FORBIDDEN 🛑 : Akses ditolak!');

        $user = User::findOrFail($id);
        
        $request->validate([
            'role' => 'required|in:admin,viewer',
        ]);

        $data = [
            'role'        => $request->role,
            'permissions' => $request->input('permissions', []),
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data user ' . $user->email . ' berhasil diperbarui!');
    }

    // --- METHOD DELETE ---
    public function destroy($id)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'FORBIDDEN 🛑 : Hanya Admin yang boleh menghapus User!');

        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun yang sedang Abang gunakan sendiri!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus permanen!');
    }
}