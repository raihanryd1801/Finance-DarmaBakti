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
                <input type="text" name="instansi" class="form-control" placeholder="Cth: PADANG, BANTEN, ACEH" required>
            </div>

            <div class="form-group">
                <label>Nama Pengadaan / Pekerjaan (Opsional)</label>
                <input type="text" name="nama_pengadaan" class="form-control">
            </div>
            <!-- BARU: PILIHAN REKENING TUJUAN -->
            <div class="form-group">
                <label>Rekening Penerima (Pemilik Rekening)</label>
                <select name="rekening_tujuan" class="form-control" required>
                    <option value="">-- Pilih Rekening --</option>
                    <option value="DARMA">DARMA</option>
                    <option value="LINTANG">LINTANG</option>
                    <!-- Abang bisa tambah opsi lain di sini jika ada -->
                </select>
            </div>

            <!-- BARU: OPSI POTONGAN ADMIN BANK -->
            <div class="form-group" style="display: flex; gap: 10px; margin-bottom: 20px; background: #f9fafb; padding: 15px; border-radius: 6px; border: 1px solid #e5e7eb;">
                <div style="flex: 1;">
                    <label>Status Bank (Rekening Koran)</label>
                    <select name="jenis_transfer_bank" id="jenis_transfer_bank" class="form-control" onchange="toggleAdminFee()" required>
                        <option value="sesama">Sesama Bank (Tanpa Potongan)</option>
                        <option value="beda">Beda Bank (Ada Biaya Admin)</option>
                    </select>
                </div>
                <div style="flex: 1;" id="div_biaya_admin" style="display: none;">
                    <label>Nominal Biaya Admin</label>
                    <input type="number" name="biaya_admin" id="biaya_admin" class="form-control" placeholder="Contoh: 2500 atau 30000" value="0">
                </div>
            </div>

            <!-- OPSI JENIS INPUT NOMINAL -->
            <div class="form-group" style="display: flex; gap: 10px;">
                <div style="flex: 1;">
                    <label>Jenis Nominal yang Diinput</label>
                    <select name="jenis_input" class="form-control" required>
                        <option value="include">Harga Kotor (Sudah Termasuk PPN 11%)</option>
                        <option value="exclude">Harga Bersih (Belum Termasuk PPN 11%)</option>
                    </select>
                </div>
                <div style="flex: 2;">
                    <label>Nominal Transfer</label>
                    <input type="text" name="jumlah_nominal_input" class="form-control" placeholder="Contoh: 153000000" required>
                </div>
            </div>
            <small style="color: #6b7280; display: block; margin-top: -10px; margin-bottom: 15px;">*PPN, PPh 22 (1.5%), dan Total Bersih akan dihitung otomatis sesuai jenis nominal.</small>

            <div class="form-group">
                <label>Total Rekening Koran (Opsional)</label>
                <input type="text" name="total_rekening_koran" class="form-control" placeholder="Opsional untuk cross-check selisih">
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Simpan Data</button>
                <a href="{{ route('uang_masuk.index') }}" class="btn btn-danger" style="margin-left: 10px;">Batal</a>
            </div>
        </form>
    </div>
    <script>
                function toggleAdminFee() {
                    var jenis = document.getElementById('jenis_transfer_bank').value;
                    var divAdmin = document.getElementById('div_biaya_admin');
                    var inputAdmin = document.getElementById('biaya_admin');
                    
                    if (jenis === 'beda') {
                        divAdmin.style.display = 'block';
                        inputAdmin.value = ''; // Kosongkan biar user bisa ketik
                        inputAdmin.required = true;
                    } else {
                        divAdmin.style.display = 'none';
                        inputAdmin.value = 0;
                        inputAdmin.required = false;
                    }
                }
            </script>
@endsection