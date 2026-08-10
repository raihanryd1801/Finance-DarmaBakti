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

            <!-- PILIHAN KATEGORI SWASTA/PEMERINTAH -->
            <div class="form-group" style="margin-bottom: 20px;">
                <label>Kategori Klien</label>
                <div style="display: flex; gap: 20px; background: #e0f2fe; padding: 10px; border-radius: 6px; border: 1px solid #bae6fd;">
                    <label style="cursor: pointer;"><input type="radio" name="kategori" value="pemerintah" checked onchange="toggleForm()"> 🏛️ Pemerintah</label>
                    <label style="cursor: pointer;"><input type="radio" name="kategori" value="swasta" onchange="toggleForm()"> 🏢 Swasta</label>
                </div>
            </div>

            <div class="form-group">
                <label>Instansi / Wilayah</label>
                <input type="text" name="instansi" class="form-control" placeholder="Cth: PADANG, BANTEN, URAMI, ALFA GROUP" required>
            </div>

            <div class="form-group">
                <label>Nama Pengadaan / Pekerjaan (Opsional)</label>
                <input type="text" name="nama_pengadaan" class="form-control" placeholder="Nama pengadaan untuk Pemerintah">
            </div>

            <div class="form-group" id="div_keterangan" style="display: none;">
                <label>Keterangan (Khusus Swasta)</label>
                <input type="text" name="keterangan" class="form-control" placeholder="Keterangan tambahan untuk Swasta">
            </div>

            <div class="form-group">
                <label>Rekening Penerima (Pemilik Rekening)</label>
                <select name="rekening_tujuan" class="form-control" required>
                    <option value="">-- Pilih Rekening --</option>
                    <option value="DARMA">DARMA</option>
                    <option value="LINTANG">LINTANG</option>
                </select>
            </div>

            <!-- BARU: STATUS TRANSFER -->
            <div class="form-group">
                <label>Status Transfer</label>
                <select name="status_transfer" class="form-control" required>
                    <option value="BELUM">BELUM TF</option>
                    <option value="SUDAH">SUDAH TF</option>
                </select>
            </div>

            <!-- NOMINAL TRANSFER -->
            <div class="form-group" style="margin-top: 20px;">
                <label style="font-weight: bold; font-size: 1rem;">Nominal Dasar Transfer</label>
                <input type="text" name="jumlah_nominal_input" class="form-control" placeholder="Contoh: 150000000" style="padding: 10px; font-size: 1rem;" required>
            </div>

            <!-- CHECKBOX PAJAK -->
            <div class="form-group" style="margin-bottom: 20px; background: #fef3c7; padding: 15px; border-radius: 6px; border: 1px solid #fde68a;">
                <label>Pengaturan Pajak Otomatis</label>
                <div style="display: flex; gap: 20px; margin-top: 10px;">
                    <label style="cursor: pointer;"><input type="checkbox" name="potong_ppn" id="potong_ppn" checked> Hitung PPN 11%</label>
                    <label style="cursor: pointer;"><input type="checkbox" name="potong_pph" id="potong_pph" checked> Potong PPh 22 (1.5%)</label>
                </div>
                <small style="display:block; margin-top:5px; color:#92400e;">*Centang PPN berarti Nominal Dasar dianggap sebagai Harga Kotor (Include PPN).</small>
            </div>

            <!-- KOLOM TAMBAHAN UNTUK NILAI DOKUMEN / NOTA -->
            <div class="form-group" style="margin-top: 15px;">
                <label>Nilai Dokumen / Nota (Opsional)</label>
                <input type="text" name="nilai_nota" id="input_nilai_nota" class="form-control" placeholder="Contoh: 113.512.763" value="{{ isset($data) ? number_format((float)$data->nilai_nota, 0, ',', '.') : old('nilai_nota') }}">
                <small style="color: #64748b;">*Opsional: Ketik angka, titik ribuan akan muncul otomatis.</small>
            </div>

            <!-- OPSI POTONGAN ADMIN BANK -->
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
                    <input type="number" name="biaya_admin" id="biaya_admin" class="form-control" placeholder="Cth: 2500 atau 30000" value="0">
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 20px; font-size: 1rem;">💾 Simpan Data</button>
                <a href="{{ route('uang_masuk.index') }}" class="btn btn-danger" style="margin-left: 10px; padding: 10px 20px; font-size: 1rem;">Batal</a>
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
                inputAdmin.value = ''; 
                inputAdmin.required = true;
            } else {
                divAdmin.style.display = 'none';
                inputAdmin.value = 0;
                inputAdmin.required = false;
            }
        }

        function toggleForm() {
            let kat = document.querySelector('input[name="kategori"]:checked').value;
            let pph = document.getElementById('potong_pph');
            let divKeterangan = document.getElementById('div_keterangan');
            
            if(kat === 'swasta') {
                pph.checked = false; // Otomatis lepas centang PPh 22
                divKeterangan.style.display = 'block'; // Munculkan kolom Keterangan Swasta
            } else {
                pph.checked = true; // Centang balik kalau Pemerintah
                divKeterangan.style.display = 'none'; // Sembunyikan kolom Keterangan Swasta
            }
        }

        // Fungsi untuk memformat angka menjadi format Rupiah (misal: 1000000 jadi 1.000.000)
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split   = number_string.split(','),
                sisa    = split[0].length % 3,
                rupiah  = split[0].substr(0, sisa),
                ribuan  = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }

        // Terapkan ke input Nominal Transfer saat user mengetik
        document.addEventListener("DOMContentLoaded", function() {
            var inputNominal = document.querySelector('input[name="jumlah_nominal_input"]');
            
            if (inputNominal) {
                // Format saat halaman dimuat (berguna untuk form Edit)
                if(inputNominal.value) {
                    inputNominal.value = formatRupiah(inputNominal.value, '');
                }

                // Format secara real-time saat user mengetik
                inputNominal.addEventListener('keyup', function(e) {
                    this.value = formatRupiah(this.value, '');
                });
            }
        });
    </script>
@endsection