<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Tampilkan daftar user
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->get(); // Hanya tampilkan viewer/non-admin
        return view('users.index', compact('users'));
    }

    // Form edit permission user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Simpan permission
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Ambil data centang menu (bentuknya array)
        $permissions = $request->input('permissions', []);

        $user->update([
            'permissions' => $permissions
        ]);

        return redirect()->route('users.index')->with('success', 'Hak akses menu untuk ' . $user->email . ' berhasil diperbarui!');
    }
}