@extends('layouts.app')

@section('content')
<style>
.form-page{max-width:680px;margin:0 auto}

.form-page{
    --fp-text:#0f172a; --fp-text-muted:#64748b;
    --fp-surface:#ffffff; --fp-border:#e2e8f0; --fp-border-soft:#eef1f5;
    --fp-section-title:#334155;
    --fp-option-bg:#f8fafc; --fp-option-border:#e2e8f0;
    --fp-option-hover-border:#93c5fd; --fp-option-hover-bg:#eff6ff;
    --fp-option-text:#475569;
    --fp-option-checked-border:#2563eb; --fp-option-checked-bg:#eff6ff; --fp-option-checked-text:#1d4ed8;
    --fp-info-bg:#f8fafc; --fp-info-border:#e2e8f0;
    --fp-tax-bg:#fffbeb; --fp-tax-border:#fde68a;
    --fp-hint:#94a3b8; --fp-tax-hint:#92400e;
    --fp-actions-bg:#f8fafc;
}
[data-theme="dark"] .form-page{
    --fp-text:#f8fafc; --fp-text-muted:#94a3b8;
    --fp-surface:#1e293b; --fp-border:#334155; --fp-border-soft:#263449;
    --fp-section-title:#cbd5e1;
    --fp-option-bg:#0f172a; --fp-option-border:#334155;
    --fp-option-hover-border:#3b82f6; --fp-option-hover-bg:rgba(37,99,235,.15);
    --fp-option-text:#cbd5e1;
    --fp-option-checked-border:#3b82f6; --fp-option-checked-bg:rgba(37,99,235,.18); --fp-option-checked-text:#93c5fd;
    --fp-info-bg:#0f172a; --fp-info-border:#334155;
    --fp-tax-bg:rgba(217,119,6,.14); --fp-tax-border:rgba(217,119,6,.4);
    --fp-hint:#64748b; --fp-tax-hint:#fcd34d;
    --fp-actions-bg:#0f172a;
}

.form-page-header{margin-bottom:1.5rem}
.form-page-header h3{margin:0;font-size:1.3rem;color:var(--fp-text);font-weight:700}
.form-page-header p{margin:.3rem 0 0;font-size:.85rem;color:var(--fp-text-muted)}

.form-card{background:var(--fp-surface);border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.06);
border:1px solid var(--fp-border);overflow:hidden}

.form-section{padding:1.5rem;border-bottom:1px solid var(--fp-border-soft)}
.form-section:last-of-type{border-bottom:none}
.form-section-title{
    display:flex;align-items:center;gap:.5rem;
    font-size:.78rem;font-weight:700;color:var(--fp-section-title);text-transform:uppercase;
    letter-spacing:.05em;margin-bottom:1rem;
}
.form-section-title svg{width:16px;height:16px;stroke:#2563eb;fill:none;stroke-width:2}

.field-row{display:flex;gap:1rem}
.field-row .form-group{flex:1}

.category-picker{display:flex;gap:.75rem}
.category-option{
    flex:1;position:relative;cursor:pointer;
    border:1.5px solid var(--fp-option-border);border-radius:10px;padding:.85rem 1rem;
    display:flex;align-items:center;gap:.65rem;
    background:var(--fp-option-bg);transition:.15s ease;
}
.category-option input{position:absolute;opacity:0;inset:0;cursor:pointer;margin:0}
.category-option svg{width:20px;height:20px;stroke:#64748b;fill:none;stroke-width:1.8;flex-shrink:0}
.category-option span{font-size:.875rem;font-weight:600;color:var(--fp-option-text)}
.category-option:has(input:checked){
    border-color:var(--fp-option-checked-border);background:var(--fp-option-checked-bg);
    box-shadow:0 0 0 3px rgba(37,99,235,.1);
}
.category-option:has(input:checked) svg{stroke:#2563eb}
.category-option:has(input:checked) span{color:var(--fp-option-checked-text)}

.info-block{
    background:var(--fp-info-bg);border:1px solid var(--fp-info-border);border-radius:10px;
    padding:1.1rem 1.25rem;
}
.info-block.tax{background:var(--fp-tax-bg);border-color:var(--fp-tax-border)}
.info-block-label{
    display:flex;align-items:center;gap:.5rem;
    font-size:.8rem;font-weight:700;color:var(--fp-section-title);margin-bottom:.75rem;
}
.check-option{
    display:flex;align-items:center;gap:.5rem;cursor:pointer;
    font-size:.85rem;color:var(--fp-option-text);font-weight:500;
}
.check-option input{width:16px;height:16px;accent-color:#2563eb;cursor:pointer}
.check-row{display:flex;gap:1.5rem;flex-wrap:wrap}
.hint-text{color:var(--fp-hint);font-size:.75rem;margin-top:.4rem;display:block}
.nominal-input{padding:.65rem .85rem !important;font-size:1rem !important;font-weight:600;color:var(--fp-text)}
.form-actions{
    padding:1.25rem 1.5rem;background:var(--fp-actions-bg);
    display:flex;gap:.75rem;align-items:center;
}
.btn-icon{display:inline-flex;align-items:center;gap:.5rem}
.btn-icon svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2}
</style>

<div class="form-page">
    <div class="form-page-header">
        <h3>Edit Data Uang Masuk</h3>
        <p>Perbarui detail transaksi di bawah ini, lalu simpan perubahan.</p>
    </div>

    <div class="form-card">
        <form action="{{ route('uang_masuk.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- INFORMASI TRANSAKSI -->
            <div class="form-section">
                <div class="form-section-title">
                    <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18" stroke-linecap="round"/></svg>
                    Informasi Transaksi
                </div>

                <div class="form-group">
                    <label>Tanggal Transfer</label>
                    <input type="date" name="tanggal_transfer" class="form-control" value="{{ $data->tanggal_transfer ? substr($data->tanggal_transfer, 0, 10) : '' }}" required>
                </div>

                <div class="form-group">
                    <label>Kategori Klien</label>
                    <div class="category-picker">
                        <label class="category-option">
                            <input type="radio" name="kategori" value="pemerintah" {{ $data->kategori == 'pemerintah' ? 'checked' : '' }} onchange="toggleForm()">
                            <span>Pemerintah</span>
                        </label>
                        <label class="category-option">
                            <input type="radio" name="kategori" value="swasta" {{ $data->kategori == 'swasta' ? 'checked' : '' }} onchange="toggleForm()">
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

            <!-- REKENING & STATUS -->
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
                            <option value="DANURI" {{ $data->rekening_tujuan == 'DANURI' ? 'selected' : '' }}>DANURI</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status Transfer</label>
                        <select name="status_transfer" class="form-control" required>
                            <option value="BELUM" {{ $data->status_transfer == 'BELUM' ? 'selected' : '' }}>BELUM TF</option>
                            <option value="SUDAH" {{ $data->status_transfer == 'SUDAH' ? 'selected' : '' }}>SUDAH TF</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- NOMINAL & PAJAK -->
            <div class="form-section">
                <div class="form-section-title">
                    <svg viewBox="0 0 24 24"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Nominal & Pajak
                </div>

                @php
                    // Jika pemerintah, nominal dasarnya adalah total_diterima (netto transfer). Jika swasta, pakai jumlah_include_ppn
                    $nominalAwal = $data->kategori == 'pemerintah' ? $data->total_diterima : $data->jumlah_include_ppn;
                @endphp
                <div class="form-group">
                    <label>Nominal Dasar Transfer</label>
                    <input type="text" name="jumlah_nominal_input" class="form-control nominal-input" value="{{ number_format((float)$nominalAwal, 0, '', '') }}" required>
                </div>

                <div class="info-block tax" style="margin-bottom:1rem;">
                    <div class="info-block-label">Pengaturan Pajak Otomatis</div>
                    <div class="check-row">
                        <label class="check-option"><input type="checkbox" name="potong_ppn" id="potong_ppn" {{ $data->ppn > 0 ? 'checked' : '' }}> Hitung PPN 11%</label>
                        <label class="check-option"><input type="checkbox" name="potong_pph" id="potong_pph" {{ $data->pph_22 > 0 ? 'checked' : '' }}> Potong PPh 22 (1.5%)</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Nilai Dokumen / Nota (Opsional)</label>
                    <input type="text" name="nilai_nota" id="input_nilai_nota" class="form-control" value="{{ $data->nilai_nota ? number_format((float)$data->nilai_nota, 0, '', '') : '' }}">
                </div>
            </div>

            <!-- ADMIN BANK -->
            <div class="form-section">
                <div class="form-section-title">Biaya Admin Bank</div>
                <div class="info-block">
                    <div class="field-row" style="margin-bottom:0;">
                        @php
                            // Selisih antara Rekening Koran dan Total Diterima murni adalah Biaya Admin
                            $biayaAdminVal = $data->total_diterima - $data->total_rekening_koran;
                            if($biayaAdminVal < 0) $biayaAdminVal = abs($biayaAdminVal);
                        @endphp
                        <div class="form-group" style="margin-bottom:0;">
                            <label>Status Bank (Rekening Koran)</label>
                            <select name="jenis_transfer_bank" id="jenis_transfer_bank" class="form-control" onchange="toggleAdminFee()" required>
                                <option value="sesama" {{ $biayaAdminVal == 0 ? 'selected' : '' }}>Sesama Bank (Tanpa Potongan)</option>
                                <option value="beda" {{ $biayaAdminVal > 0 ? 'selected' : '' }}>Beda Bank (Ada Biaya Admin)</option>
                            </select>
                        </div>
                        <div class="form-group" id="div_biaya_admin" style="display: {{ $biayaAdminVal > 0 ? 'block' : 'none' }}; margin-bottom:0;">
                            <label>Nominal Biaya Admin</label>
                            <input type="text" name="biaya_admin" id="biaya_admin" class="form-control" value="{{ $biayaAdminVal > 0 ? number_format((float)$biayaAdminVal, 0, '', '') : 0 }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-icon">Update Data</button>
                <a href="{{ route('uang_masuk.index', ['kategori' => $data->kategori]) }}" class="btn btn-secondary btn-icon">Batal</a>
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
        } else {
            divAdmin.style.display = 'none';
            inputAdmin.value = 0;
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

    document.addEventListener("DOMContentLoaded", function() {
        ['jumlah_nominal_input', 'input_nilai_nota', 'biaya_admin'].forEach(function(classNameOrId) {
            var el = document.querySelector('input[name="' + classNameOrId + '"]') || document.getElementById(classNameOrId);
            if (el) {
                if(el.value) el.value = formatRupiah(el.value, '');
                el.addEventListener('keyup', function(e) {
                    this.value = formatRupiah(this.value, '');
                });
            }
        });
    });
</script>
@endsection