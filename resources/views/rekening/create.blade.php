@extends('layouts.app')

@section('content')
    <div class="card" style="padding: 20px; max-width: 500px; margin: 0 auto;">
        <h3 style="margin-bottom: 20px;">➕ Tambah Rekening</h3>

        <form action="{{ route('rekening.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nama Bank</label>
                <input type="text" name="nama_bank" class="form-control" required placeholder="Contoh: BNI, BCA, Mandiri" style="width: 100%; padding: 8px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nomor Rekening</label>
                <input type="text" name="nomor_rekening" class="form-control" required placeholder="Contoh: 0430643011" style="width: 100%; padding: 8px;">
                @error('nomor_rekening') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Atas Nama (A/N)</label>
                <input type="text" name="atas_nama" class="form-control" required placeholder="Contoh: DANURI" style="width: 100%; padding: 8px;">
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 20px; border-radius: 6px;">💾 Simpan</button>
                <a href="{{ route('rekening.index') }}" class="btn" style="padding: 10px 20px; background: #64748b; color: white; text-decoration: none; border-radius: 6px;">Batal</a>
            </div>
        </form>
    </div>
@endsection