@extends('layouts.app')

@section('content')
<div class="card" style="padding: 25px; max-width: 700px; margin: 0 auto;">
    <h3>⚙️ Atur Hak Akses Detail untuk: {{ $user->email }}</h3>
    
    <form action="{{ route('users.update', $user->id) }}" method="POST" style="margin-top: 20px;">
        @csrf
        
        @php
            $currentPerms = $user->permissions ?? [];
            
            // Kelompok Menu & Aksi (CRUD)
            $permissionGroups = [
                '📊 Data Report Keuangan' => [
                    'finance_report' => 'Lihat Halaman Report'
                ],
                '💰 Modul Uang Masuk' => [
                    'uang_masuk_pemerintah' => 'Lihat Menu Uang Masuk Pemerintah',
                    'uang_masuk_swasta'     => 'Lihat Menu Uang Masuk Swasta',
                    'uang_masuk_create'     => 'Tambah Data Uang Masuk',
                    'uang_masuk_edit'       => 'Edit Data Uang Masuk',
                    'uang_masuk_delete'     => 'Hapus Data Uang Masuk',
                ],
                '📄 Modul Invoice' => [
                    'invoice_index'         => 'Lihat Daftar Invoice',
                    'invoice_create'        => 'Buat / Tambah Invoice Baru',
                    'invoice_edit'          => 'Edit Invoice',
                    'invoice_delete'        => 'Hapus Invoice',
                    'invoice_print'         => 'Cetak / Print Invoice',
                    'invoice_lunas'         => 'Tandai Lunas & Masuk Keuangan',
                ],
                '📦 Master Data Barang' => [
                    'barang_index'          => 'Lihat Master Data Barang',
                ]
            ];
        @endphp

        <div style="display: flex; flex-direction: column; gap: 20px; margin-bottom: 25px;">
            @foreach($permissionGroups as $groupName => $permissions)
                <div style="background: var(--bg-body); padding: 15px; border-radius: 8px; border: 1px solid var(--border-color);">
                    <h4 style="margin: 0 0 10px 0; font-size: 1rem; color: var(--primary);">{{ $groupName }}</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px;">
                        @foreach($permissions as $key => $label)
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.85rem; color: var(--text-primary);">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}" {{ in_array($key, $currentPerms) ? 'checked' : '' }} style="width: 16px; height: 16px;">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">💾 Simpan Hak Akses</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary" style="text-decoration: none;">Kembali</a>
        </div>
    </form>
</div>
@endsection