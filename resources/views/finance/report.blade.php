@extends('layouts.app')

@section('content')
<style>
    .page-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem}
    .page-header h2{margin:0;font-size:1.4rem;color:var(--text-primary);font-weight:600}

    .filter-card{background:var(--table-header);border:1px solid var(--border-color,#e2e8f0);box-shadow:none;
        padding:1rem 1.5rem;margin-bottom:1.5rem}
    .filter-card form{display:flex;flex-wrap:wrap;gap:15px;align-items:flex-end}
    .filter-card label{font-size:.8rem;font-weight:600;color:var(--text-secondary);margin-bottom:5px;text-transform:uppercase;letter-spacing:.02em}
    .filter-actions{display:flex;gap:10px}

    .summary-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin-bottom:2rem}
    .summary-card{margin:0;border-left:4px solid var(--accent,#2563eb)}
    .summary-card .label{font-size:.8rem;color:var(--text-secondary);font-weight:600;text-transform:uppercase;letter-spacing:.02em}
    .summary-card .value{font-size:1.35rem;font-weight:700;color:var(--text-primary);margin-top:.4rem}

    .section-title{margin:0 0 1rem;color:var(--text-primary);font-size:1.05rem;font-weight:600}
    .empty-state{text-align:center;color:var(--text-secondary);padding:2.5rem}

    /* ===== GRAFIK ===== */
    .chart-grid{display:grid;grid-template-columns:2fr 1fr;gap:1rem;margin-bottom:2rem}
    .chart-card{padding:1.25rem 1.5rem}
    .chart-card .section-title{display:flex;align-items:center;justify-content:space-between;gap:.5rem}
    .chart-card .section-title small{font-size:.72rem;font-weight:500;color:var(--text-secondary);text-transform:none;letter-spacing:0}
    .chart-canvas-wrap{position:relative;height:280px}
    .chart-legend-custom{display:flex;flex-wrap:wrap;gap:.6rem 1.1rem;margin-top:1rem;justify-content:center}
    .chart-legend-custom span{display:inline-flex;align-items:center;gap:.4rem;font-size:.78rem;color:var(--text-secondary)}
    .chart-legend-custom i{width:9px;height:9px;border-radius:2px;display:inline-block}

    @media (max-width: 900px){
        .chart-grid{grid-template-columns:1fr}
    }
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

<!-- GRAFIK -->
@if($rekapPerInstansi->count() > 0)
<div class="chart-grid">
    <div class="card chart-card">
        <h3 class="section-title">
            Kotor vs Bersih per Instansi
            <small>Perbandingan nilai transaksi</small>
        </h3>
        <div class="chart-canvas-wrap">
            <canvas id="chartPerInstansi"></canvas>
        </div>
    </div>

    <div class="card chart-card">
        <h3 class="section-title">Komposisi Total</h3>
        <div class="chart-canvas-wrap" style="height:220px;">
            <canvas id="chartKomposisi"></canvas>
        </div>
        <div class="chart-legend-custom">
            <span><i style="background:#10b981;"></i> Bersih Diterima</span>
            <span><i style="background:#f59e0b;"></i> PPh 22</span>
        </div>
    </div>
</div>
@endif

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

@if($rekapPerInstansi->count() > 0)
<script src="{{ asset('js/chart.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const labels = @json($rekapPerInstansi->pluck('instansi')->map(fn($i) => strtoupper($i)));
    const kotor  = @json($rekapPerInstansi->pluck('sum_include'));
    const bersih = @json($rekapPerInstansi->pluck('sum_diterima'));

    const totalBersih = {{ (float) $totalDiterima }};
    const totalPph    = {{ (float) $totalPph22 }};

    const rupiah = (val) => 'Rp ' + Number(val).toLocaleString('id-ID');

    // Deteksi tema aktif supaya teks & garis grid grafik tetap kebaca
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const chartTextColor = isDark ? '#cbd5e1' : '#475569';
    const chartTickColor = isDark ? '#94a3b8' : '#64748b';
    const chartGridColor = isDark ? '#334155' : '#f1f5f9';

    // ===== BAR CHART: Kotor vs Bersih per Instansi =====
    new Chart(document.getElementById('chartPerInstansi'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Total Kotor (Include PPN)',
                    data: kotor,
                    backgroundColor: '#2563eb',
                    borderRadius: 4,
                    maxBarThickness: 28
                },
                {
                    label: 'Total Bersih Diterima',
                    data: bersih,
                    backgroundColor: '#10b981',
                    borderRadius: 4,
                    maxBarThickness: 28
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { boxWidth: 10, boxHeight: 10, font: { size: 11 }, color: chartTextColor }
                },
                tooltip: {
                    callbacks: {
                        label: (ctx) => ctx.dataset.label + ': ' + rupiah(ctx.raw)
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: chartTickColor, font: { size: 11 } } },
                y: {
                    grid: { color: chartGridColor },
                    ticks: {
                        color: chartTickColor,
                        font: { size: 11 },
                        callback: (val) => 'Rp ' + (val / 1000000).toLocaleString('id-ID') + 'jt'
                    }
                }
            }
        }
    });

    // ===== DOUGHNUT CHART: Komposisi Total =====
    new Chart(document.getElementById('chartKomposisi'), {
        type: 'doughnut',
        data: {
            labels: ['Bersih Diterima', 'PPh 22'],
            datasets: [{
                data: [totalBersih, totalPph],
                backgroundColor: ['#10b981', '#f59e0b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => ctx.label + ': ' + rupiah(ctx.raw)
                    }
                }
            }
        }
    });
});
</script>
@endif
@endsection