@extends('layouts.app')

@section('content')
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <h3>✏️ Edit Data Uang Masuk</h3>
        
        <form action="{{ route('uang_masuk.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Wajib untuk proses Update di Laravel -->
            
            <div class="form-group">
                <label>Tanggal Transfer</label>
                <!-- Jika tanggal ada, kita potong cuma ambil Y-m-d -->
                <input type="date" name="tanggal_transfer" class="form-control" value="{{ $data->tanggal_transfer ? substr($data->tanggal_transfer, 0, 10) : '' }}" required>
            </div>

            <!-- PILIHAN KATEGORI SWASTA/PEMERINTAH -->
            <div class="form-group" style="margin-bottom: 20px;">
                <label>Kategori Klien</label>
                <div style="display: flex; gap: 20px; background: #e0f2fe; padding: 10px; border-radius: 6px; border: 1px solid #bae6fd;">
                    <label style="cursor: pointer;"><input type="radio" name="kategori" value="pemerintah" {{ $data->kategori == 'pemerintah' ? 'checked' : '' }} onchange="toggleForm()"> 🏛️ Pemerintah</label>
                    <label style="cursor: pointer;"><input type="radio" name="kategori" value="swasta" {{ $data->kategori == 'swasta' ? 'checked' : '' }} onchange="toggleForm()"> 🏢 Swasta</label>
                </div>
            </div>

            <div class="form-group">
                <label>Instansi / Wilayah</label>
                <input type="text" name="instansi" class="form-control" value="{{ $data->instansi }}" required>
            </div>

            <div class="form-group">
                <label>Nama Pengadaan / Pekerjaan</label>
                <input type="text" name="nama_pengadaan" class="form-control" value="{{ $data->nama_pengadaan }}">
            </div>

            <div class="form-group" id="div_keterangan" style="display: {{ $data->kategori == 'swasta' ? 'block' : 'none' }};">
                <label>Keterangan (Khusus Swasta)</label>
                <input type="text" name="keterangan" class="form-control" value="{{ $data->keterangan }}">
            </div>

            <div class="form-group">
                <label>Rekening Penerima</label>
                <select name="rekening_tujuan" class="form-control" required>
                    <option value="">-- Pilih Rekening --</option>
                    <option value="DARMA" {{ $data->rekening_tujuan == 'DARMA' ? 'selected' : '' }}>DARMA</option>
                    <option value="LINTANG" {{ $data->rekening_tujuan == 'LINTANG' ? 'selected' : '' }}>LINTANG</option>
                </select>
            </div>

            <!-- BARU: STATUS TRANSFER EDIT -->
            <div class="form-group">
                <label>Status Transfer</label>
                <select name="status_transfer" class="form-control" required>
                    <option value="BELUM" {{ $data->status_transfer == 'BELUM' ? 'selected' : '' }}>BELUM TF</option>
                    <option value="SUDAH" {{ $data->status_transfer == 'SUDAH' ? 'selected' : '' }}>SUDAH TF</option>
                </select>
            </div>

            <!-- NOMINAL TRANSFER (Kita ambil dari include_ppn) -->
            <div class="form-group" style="margin-top: 20px;">
                <label style="font-weight: bold; font-size: 1rem;">Nominal Dasar Transfer</label>
                <input type="text" name="jumlah_nominal_input" class="form-control" value="{{ number_format((float)$data->jumlah_include_ppn, 0, '', '') }}" style="padding: 10px; font-size: 1rem;" required>
            </div>

            <!-- CHECKBOX PAJAK -->
            <div class="form-group" style="margin-bottom: 20px; background: #fef3c7; padding: 15px; border-radius: 6px; border: 1px solid #fde68a;">
                <label>Pengaturan Pajak Otomatis</label>
                <div style="display: flex; gap: 20px; margin-top: 10px;">
                    <!-- Deteksi apakah sebelumnya PPN dan PPh terhitung -->
                    <label style="cursor: pointer;"><input type="checkbox" name="potong_ppn" id="potong_ppn" {{ $data->ppn > 0 ? 'checked' : '' }}> Hitung PPN 11%</label>
                    <label style="cursor: pointer;"><input type="checkbox" name="potong_pph" id="potong_pph" {{ $data->pph_22 > 0 ? 'checked' : '' }}> Potong PPh 22 (1.5%)</label>
                </div>
            </div>

            <!-- OPSI POTONGAN ADMIN BANK -->
            <div class="form-group" style="display: flex; gap: 10px; margin-bottom: 20px; background: #f9fafb; padding: 15px; border-radius: 6px; border: 1px solid #e5e7eb;">
                <div style="flex: 1;">
                    <label>Status Bank (Rekening Koran)</label>
                    <select name="jenis_transfer_bank" id="jenis_transfer_bank" class="form-control" onchange="toggleAdminFee()" required>
                        <option value="sesama" {{ $data->selisih == 0 ? 'selected' : '' }}>Sesama Bank (Tanpa Potongan)</option>
                        <option value="beda" {{ $data->selisih != 0 ? 'selected' : '' }}>Beda Bank (Ada Biaya Admin)</option>
                    </select>
                </div>
                <div style="flex: 1;" id="div_biaya_admin" style="display: {{ $data->selisih != 0 ? 'block' : 'none' }};">
                    <label>Nominal Biaya Admin</label>
                    <!-- Tampilkan nilai positif dari selisih jika ada admin fee -->
                    <input type="number" name="biaya_admin" id="biaya_admin" class="form-control" value="{{ abs($data->selisih) }}">
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 20px; font-size: 1rem;">💾 Update Data</button>
                <a href="{{ route('uang_masuk.index', ['kategori' => $data->kategori]) }}" class="btn btn-danger" style="margin-left: 10px; padding: 10px 20px; font-size: 1rem;">Batal</a>
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
                pph.checked = false; 
                divKeterangan.style.display = 'block';
            } else {
                pph.checked = true; 
                divKeterangan.style.display = 'none';
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