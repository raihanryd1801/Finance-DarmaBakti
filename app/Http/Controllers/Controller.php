<?php

namespace App\Http\Controllers;

// ... (use bawaan Laravel biarkan saja) ...

abstract class Controller
{
    // ==========================================
    // TAMBAHKAN FUNGSI GEMBOK INI DI DALAM CLASS
    // ==========================================
    public function cekAkses($permission)
    {
        // 1. Kalau dia Admin, langsung bebas masuk
        if (auth()->check() && auth()->user()->role === 'admin') {
            return true;
        }

        // 2. Cek apakah user viewer punya izin spesifik
        $perms = auth()->user()->permissions ?? [];
        
        // 3. Kalau tidak punya izin, tendang dengan pesan 403 Forbidden!
        abort_if(!in_array($permission, $perms), 403, 'FORBIDDEN 🛑 : Hayo mau ngapain? Lurrr tidak memiliki hak akses untuk fitur ini!');
    }
}