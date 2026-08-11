@extends('layouts.app')

@section('content')
    <div class="card" style="padding: 20px; max-width: 600px; margin: 0 auto;">
        <h3 style="margin-bottom: 20px;">➕ Tambah Data Barang</h3>

        <form action="{{ route('barang.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Kode Barang</label>
                <input type="text" name="kode_barang" class="form-control" required placeholder="Contoh: 01-001" style="width: 100%; padding: 8px;">
                @error('kode_barang') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" required placeholder="Contoh: Buku Tulis" style="width: 100%; padding: 8px;">
            </div>

            <div style="margin-bottom: 15px; display: flex; gap: 15px;">
                <div style="flex: 1;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Satuan</label>
                    <input type="text" name="satuan" class="form-control" required placeholder="Contoh: Pcs, Rim, Stel" style="width: 100%; padding: 8px;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Harga Satuan (Rp)</label>
                    <input type="number" name="harga" class="form-control" required placeholder="Contoh: 10000" style="width: 100%; padding: 8px;">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Keterangan (Opsional)</label>
                <textarea name="keterangan" class="form-control" rows="3" style="width: 100%; padding: 8px;"></textarea>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 20px; border-radius: 6px;">💾 Simpan Barang</button>
                <a href="{{ route('barang.index') }}" class="btn" style="padding: 10px 20px; background: #64748b; color: white; text-decoration: none; border-radius: 6px;">Batal</a>
            </div>
        </form>
    </div>
@endsection