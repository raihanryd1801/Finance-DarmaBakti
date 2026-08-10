@extends('layouts.app')

@section('content')
<style>
    .page-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem}
    .page-header h2{margin:0;font-size:1.4rem;color:#1e293b;font-weight:600}

    .filter-card{background:#f8fafc;border:1px solid var(--border-color,#e2e8f0);box-shadow:none;
        padding:1rem 1.5rem;margin-bottom:1.5rem}
    .filter-card form{display:flex;flex-wrap:wrap;gap:15px;align-items:flex-end}
    .filter-card label{font-size:.8rem;font-weight:600;color:#475569;margin-bottom:5px;text-transform:uppercase;letter-spacing:.02em}
    .filter-actions{display:flex;gap:10px}

    .summary-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin-bottom:2rem}
    .summary-card{margin:0;border-left:4px solid var(--accent,#2563eb)}
    .summary-card .label{font-size:.8rem;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.02em}
    .summary-card .value{font-size:1.35rem;font-weight:700;color:#1e293b;margin-top:.4rem}

    .section-title{margin:0 0 1rem;color:#334155;font-size:1.05rem;font-weight:600}
    .empty-state{text-align:center;color:#6b7280;padding:2.5rem}
</style>

<div class="page-header">
    <h2>Laporan &amp; Rekapitulasi Keuangan</h2>
</div>

<!-- FILTER -->
<div class="card filter-card">
    <form action="{{ route('finance.report') }}" method="GET">
        <div>
            <label>Instansi / Wilayah</label>
            <select name="instansi" class="form-control" style="width:220px;">
                <option value="">-- Semua Instansi --</option>
                @foreach($listInstansi as $inst)
                    <option value="{{ $inst }}" {{ request('instansi') == $inst ? 'selected' : '' }}>{{ strtoupper($inst) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Periode Mulai</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" style="width:150px;">
        </div>

        <div>
            <label>Periode Sampai</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" style="width:150px;">
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
            <a href="{{ route('finance.report') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- RINGKASAN -->
<div class="summary-grid">
    <div class="card summary-card" style="--accent:#2563eb;">
        <div class="label">Total Include PPN</div>
        <div class="value">Rp {{ number_format($totalIncludePpn, 0, ',', '.') }}</div>
    </div>

    <div class="card summary-card" style="--accent:#8b5cf6;">
        <div class="label">Total Exclude PPN</div>
        <div class="value">Rp {{ number_format($totalExcludePpn, 0, ',', '.') }}</div>
    </div>

    <div class="card summary-card" style="--accent:#f59e0b;">
        <div class="label">Total PPh 22 (1.5%)</div>
        <div class="value" style="color:#d97706;">Rp {{ number_format($totalPph22, 0, ',', '.') }}</div>
    </div>

    <div class="card summary-card" style="--accent:#10b981;">
        <div class="label">Total Bersih Diterima</div>
        <div class="value" style="color:#047857;">Rp {{ number_format($totalDiterima, 0, ',', '.') }}</div>
    </div>
</div>

<!-- TABEL REKAPITULASI -->
<div class="card">
    <h3 class="section-title">Rincian Per Instansi / Wilayah</h3>

    <div class="table-container">
        <table class="finance-table" style="width:100%;">
            <thead>
                <tr>
                    <th>Instansi / Wilayah</th>
                    <th>Jumlah Transaksi</th>
                    <th>Total Kotor (Include PPN)</th>
                    <th>Total Potongan PPh 22</th>
                    <th>Total Bersih Diterima</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapPerInstansi as $rekap)
                    <tr>
                        <td><strong>{{ strtoupper($rekap->instansi) }}</strong></td>
                        <td style="text-align:center;">{{ $rekap->total_transaksi }} Kegiatan</td>
                        <td class="angka">Rp {{ number_format($rekap->sum_include, 0, ',', '.') }}</td>
                        <td class="angka" style="color:#d97706;">Rp {{ number_format($rekap->sum_pph, 0, ',', '.') }}</td>
                        <td class="angka" style="font-weight:700;color:#047857;">Rp {{ number_format($rekap->sum_diterima, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">Tidak ada data pada periode / filter tersebut.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection