@extends('layouts.app')

@section('content')
<style>
.form-page{max-width:680px;margin:0 auto}

.form-page-header{margin-bottom:1.5rem}
.form-page-header .eyebrow{
    display:flex;align-items:center;gap:.5rem;
    font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;
    color:#2563eb;margin-bottom:.4rem;
}
.form-page-header .eyebrow svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2}
.form-page-header h3{margin:0;font-size:1.3rem;color:#0f172a;font-weight:700}
.form-page-header p{margin:.3rem 0 0;font-size:.85rem;color:#64748b}

.form-card{background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.06);
border:1px solid #e2e8f0;overflow:hidden}

.form-section{padding:1.5rem;border-bottom:1px solid #eef1f5}
.form-section:last-of-type{border-bottom:none}
.form-section-title{
    display:flex;align-items:center;gap:.5rem;
    font-size:.78rem;font-weight:700;color:#334155;text-transform:uppercase;
    letter-spacing:.05em;margin-bottom:1rem;
}
.form-section-title svg{width:16px;height:16px;stroke:#2563eb;fill:none;stroke-width:2}

.field-row{display:flex;gap:1rem}
.field-row .form-group{flex:1}

/* Pilihan kategori sebagai kartu radio */
.category-picker{display:flex;gap:.75rem}
.category-option{
    flex:1;position:relative;cursor:pointer;
    border:1.5px solid #e2e8f0;border-radius:10px;padding:.85rem 1rem;
    display:flex;align-items:center;gap:.65rem;
    background:#f8fafc;transition:.15s ease;
}
.category-option:hover{border-color:#93c5fd;background:#eff6ff}
.category-option input{position:absolute;opacity:0;inset:0;cursor:pointer;margin:0}
.category-option svg{width:20px;height:20px;stroke:#64748b;fill:none;stroke-width:1.8;flex-shrink:0;transition:.15s ease}
.category-option span{font-size:.875rem;font-weight:600;color:#475569}
.category-option:has(input:checked){
    border-color:#2563eb;background:#eff6ff;
    box-shadow:0 0 0 3px rgba(37,99,235,.1);
}
.category-option:has(input:checked) svg{stroke:#2563eb}
.category-option:has(input:checked) span{color:#1d4ed8}

/* Blok pajak & admin bank */
.info-block{
    background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;
    padding:1.1rem 1.25rem;
}
.info-block.tax{background:#fffbeb;border-color:#fde68a}
.info-block-label{
    display:flex;align-items:center;gap:.5rem;
    font-size:.8rem;font-weight:700;color:#334155;margin-bottom:.75rem;
}
.info-block-label svg{width:16px;height:16px;stroke:#d97706;fill:none;stroke-width:2}
.check-option{
    display:flex;align-items:center;gap:.5rem;cursor:pointer;
    font-size:.85rem;color:#475569;font-weight:500;
}
.check-option input{width:16px;height:16px;accent-color:#2563eb;cursor:pointer}
.check-row{display:flex;gap:1.5rem;flex-wrap:wrap}

.hint-text{color:#94a3b8;font-size:.75rem;margin-top:.4rem;display:block}

.nominal-input{padding:.65rem .85rem !important;font-size:1rem !important;font-weight:600;color:#0f172a}

.form-actions{
    padding:1.25rem 1.5rem;background:#f8fafc;
    display:flex;gap:.75rem;align-items:center;
}
.btn-icon{display:inline-flex;align-items:center;gap:.5rem}
.btn-icon svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2}

@media (max-width:576px){
    .field-row{flex-direction:column;gap:0}
    .category-picker{flex-direction:column}
}
</style>

<div class="form-page">
    <div class="form-page-header">
        <h3>Edit Data Uang Masuk</h3>
        <p>Perbarui detail transaksi di bawah ini, lalu simpan perubahan.</p>
    </div>

    <div class="form-card">
        <form action="{{ route('uang_masuk.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Wajib untuk proses Update di Laravel -->

            <!-- ==== INFORMASI TRANSAKSI ==== -->
            <div class="form-section">
                <div class="form-section-title">
                    <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18" stroke-linecap="round"/></svg>
                    Informasi Transaksi
                </div>

                <div class="form-group">
                    <label>Tanggal Transfer</label>
                    <!-- Jika tanggal ada, kita potong cuma ambil Y-m-d -->
                    <input type="date" name="tanggal_transfer" class="form-control" value="{{ $data->tanggal_transfer ? substr($data->tanggal_transfer, 0, 10) : '' }}" required>
                </div>

                <!-- PILIHAN KATEGORI SWASTA/PEMERINTAH -->
                <div class="form-group">
                    <label>Kategori Klien</label>
                    <div class="category-picker">
                        <label class="category-option">
                            <input type="radio" name="kategori" value="pemerintah" {{ $data->kategori == 'pemerintah' ? 'checked' : '' }} onchange="toggleForm()">
                            <svg viewBox="0 0 24 24"><path d="M4 21h16" stroke-linecap="round"/><path d="M5 21V10l7-6 7 6v11" stroke-linejoin="round"/><path d="M9 21v-6h6v6" stroke-linejoin="round"/></svg>
                            <span>Pemerintah</span>
                        </label>
                        <label class="category-option">
                            <input type="radio" name="kategori" value="swasta" {{ $data->kategori == 'swasta' ? 'checked' : '' }} onchange="toggleForm()">
                            <svg viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span>Swasta</span>
                        </label>
                    </div>
                </div>

                <div class="field-row">
                    <div class="form-group">
                        <label>Instansi / Wilayah</label>
                        <input type="text" name="instansi" class="form-control" value="{{ $data->instansi }}" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Pengadaan / Pekerjaan</label>
                        <input type="text" name="nama_pengadaan" class="form-control" value="{{ $data->nama_pengadaan }}">
                    </div>
                </div>

                <div class="form-group" id="div_keterangan" style="display: {{ $data->kategori == 'swasta' ? 'block' : 'none' }};">
                    <label>Keterangan (Khusus Swasta)</label>
                    <input type="text" name="keterangan" class="form-control" value="{{ $data->keterangan }}">
                </div>
            </div>

            <!-- ==== REKENING & STATUS ==== -->
            <div class="form-section">
                <div class="form-section-title">
                    <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20" stroke-linecap="round"/></svg>
                    Rekening & Status
                </div>

                <div class="field-row">
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
                </div>
            </div>

            <!-- ==== NOMINAL & PAJAK ==== -->
            <div class="form-section">
                <div class="form-section-title">
                    <svg viewBox="0 0 24 24"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Nominal & Pajak
                </div>

                <!-- NOMINAL TRANSFER (Kita ambil dari include_ppn) -->
                <div class="form-group">
                    <label>Nominal Dasar Transfer</label>
                    <input type="text" name="jumlah_nominal_input" class="form-control nominal-input" value="{{ number_format((float)$data->jumlah_include_ppn, 0, '', '') }}" required>
                </div>

                <!-- CHECKBOX PAJAK -->
                <div class="info-block tax" style="margin-bottom:1rem;">
                    <div class="info-block-label">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Pengaturan Pajak Otomatis
                    </div>
                    <div class="check-row">
                        <!-- Deteksi apakah sebelumnya PPN dan PPh terhitung -->
                        <label class="check-option"><input type="checkbox" name="potong_ppn" id="potong_ppn" {{ $data->ppn > 0 ? 'checked' : '' }}> Hitung PPN 11%</label>
                        <label class="check-option"><input type="checkbox" name="potong_pph" id="potong_pph" {{ $data->pph_22 > 0 ? 'checked' : '' }}> Potong PPh 22 (1.5%)</label>
                    </div>
                </div>

                <!-- KOLOM TAMBAHAN UNTUK NILAI DOKUMEN / NOTA -->
                <div class="form-group">
                    <label>Nilai Dokumen / Nota (Opsional)</label>
                    <input type="text" name="nilai_nota" id="input_nilai_nota" class="form-control" placeholder="Contoh: 113.512.763" value="{{ isset($data) ? number_format((float)$data->nilai_nota, 0, ',', '.') : old('nilai_nota') }}">
                    <small class="hint-text">Opsional: ketik angka, titik ribuan akan muncul otomatis.</small>
                </div>
            </div>

            <!-- ==== ADMIN BANK ==== -->
            <div class="form-section">
                <div class="form-section-title">
                    <svg viewBox="0 0 24 24"><path d="M3 12h18M3 6h18M3 18h18" stroke-linecap="round"/></svg>
                    Biaya Admin Bank
                </div>

                <!-- OPSI POTONGAN ADMIN BANK -->
                <div class="info-block">
                    <div class="field-row" style="margin-bottom:0;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label>Status Bank (Rekening Koran)</label>
                            <select name="jenis_transfer_bank" id="jenis_transfer_bank" class="form-control" onchange="toggleAdminFee()" required>
                                <option value="sesama" {{ $data->selisih == 0 ? 'selected' : '' }}>Sesama Bank (Tanpa Potongan)</option>
                                <option value="beda" {{ $data->selisih != 0 ? 'selected' : '' }}>Beda Bank (Ada Biaya Admin)</option>
                            </select>
                        </div>
                        <div class="form-group" id="div_biaya_admin" style="display: {{ $data->selisih != 0 ? 'block' : 'none' }}; margin-bottom:0;">
                            <label>Nominal Biaya Admin</label>
                            <!-- Tampilkan nilai positif dari selisih jika ada admin fee -->
                            <input type="number" name="biaya_admin" id="biaya_admin" class="form-control" value="{{ abs($data->selisih) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-icon">
                    <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z" stroke-linejoin="round"/><path d="M17 21v-8H7v8M7 3v5h8" stroke-linejoin="round"/></svg>
                    Update Data
                </button>
                <a href="{{ route('uang_masuk.index', ['kategori' => $data->kategori]) }}" class="btn btn-secondary btn-icon">
                    <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
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

        // Fungsi pembantu format Rupiah
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

        // Terapkan ke input Nominal Transfer & Nilai Nota saat user mengetik
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Untuk Nominal Dasar Transfer
            var inputNominal = document.querySelector('input[name="jumlah_nominal_input"]');
            if (inputNominal) {
                if(inputNominal.value) inputNominal.value = formatRupiah(inputNominal.value, '');
                inputNominal.addEventListener('keyup', function(e) {
                    this.value = formatRupiah(this.value, '');
                });
            }

            // 2. Untuk Nilai Dokumen / Nota
            var inputNota = document.getElementById('input_nilai_nota');
            if (inputNota) {
                if(inputNota.value) inputNota.value = formatRupiah(inputNota.value, '');
                inputNota.addEventListener('keyup', function(e) {
                    this.value = formatRupiah(this.value, '');
                });
            }
        });
    </script>
@endsection