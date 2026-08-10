@extends('layouts.app')

@section('content')
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <h3>Tambah Data Uang Masuk</h3>
        
        <form action="{{ route('uang_masuk.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Tanggal Transfer</label>
                <input type="date" name="tanggal_transfer" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Instansi / Wilayah</label>
                <input type="text" name="instansi" class="form-control" placeholder="Cth: BANTEN, ACEH, SWASTA" required>
            </div>

            <div class="form-group">
                <label>Nama Pengadaan / Pekerjaan (Opsional)</label>
                <input type="text" name="nama_pengadaan" class="form-control" placeholder="Contoh: Pengadaan Perlengkapan...">
            </div>

            <div class="form-group">
                <label>Jumlah Transfer (Include PPN)</label>
                <input type="number" name="jumlah_include_ppn" class="form-control" placeholder="Contoh: 112525695" required>
                <small style="color: #6b7280; margin-top: 5px; display: block;">*PPN, PPh 22, dan Total Bersih akan dihitung otomatis.</small>
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Simpan Data</button>
                <a href="{{ route('uang_masuk.index') }}" class="btn btn-danger" style="margin-left: 10px;">Batal</a>
            </div>
        </form>
    </div>
@endsection