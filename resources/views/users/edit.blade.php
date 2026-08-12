@extends('layouts.app')

@section('content')
<div class="card" style="padding: 20px; max-width: 600px; margin: 0 auto;">
    <h3>⚙️ Atur Menu Akses untuk: {{ $user->email }}</h3>
    
    <form action="{{ route('users.update', $user->id) }}" method="POST" style="margin-top: 20px;">
        @csrf
        <p style="font-weight: bold; margin-bottom: 15px; color: var(--text-primary);">Pilih menu yang boleh dilihat oleh user ini:</p>
        
        @php
            $currentPerms = $user->permissions ?? [];
            $menus = [
                'finance_report' => 'Data Report Keuangan',
                'uang_masuk_pemerintah' => 'Uang Masuk Pemerintah',
                'uang_masuk_swasta' => 'Uang Masuk Swasta',
                'invoice_index'         => 'Cetak Invoice',         // <-- Menu Invoice
                'barang_index'          => 'Master Data Barang',
            ];
        @endphp

        <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 25px;">
            @foreach($menus as $key => $label)
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 1rem; color: var(--text-primary);">
                    <input type="checkbox" name="permissions[]" value="{{ $key }}" {{ in_array($key, $currentPerms) ? 'checked' : '' }} style="width: 18px; height: 18px;">
                    {{ $label }}
                </label>
            @endforeach
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary" style="text-decoration: none;">Kembali</a>
        </div>
    </form>
</div>
@endsection