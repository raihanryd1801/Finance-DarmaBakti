@extends('layouts.app')

@section('content')
<style>
    .invoice-wrap {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --primary-light: #eef2ff;
        --surface: #ffffff;
        --border: #e2e8f0;
        --text: #1e293b;
        --text-muted: #64748b;
        --success: #16a34a;
        --success-bg: #dcfce7;
        --success-text: #14532d;
        --success-border: #bbf7d0;
        --danger: #dc2626;
        --danger-bg: #fee2e2;
        --danger-text: #991b1b;
        --warning: #d97706;
        --warning-bg: #fef3c7;
        --info: #0284c7;
        --info-bg: #e0f2fe;
        --radius: 12px;
        --table-header-bg: #f8fafc;
        --table-hover-bg: #fafbff;
        --divider: #f1f5f9;
    }

    [data-theme="dark"] .invoice-wrap {
        --primary-light: rgba(79, 70, 229, 0.18);
        --surface: #1e293b;
        --border: #334155;
        --text: #f8fafc;
        --text-muted: #94a3b8;
        --success-bg: rgba(22, 163, 74, 0.18);
        --success-text: #86efac;
        --success-border: rgba(22, 163, 74, 0.35);
        --danger-bg: rgba(220, 38, 38, 0.18);
        --danger-text: #fca5a5;
        --warning-bg: rgba(217, 119, 6, 0.18);
        --info: #38bdf8;
        --info-bg: rgba(2, 132, 199, 0.18);
        --table-header-bg: #0f172a;
        --table-hover-bg: #263449;
        --divider: #334155;
    }

    .invoice-wrap {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: var(--text);
    }

    .invoice-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        margin-bottom: 20px;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .invoice-header h3 {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.01em;
    }

    .invoice-header p {
        margin: 4px 0 0;
        color: var(--text-muted);
        font-size: 13px;
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        border-radius: 8px;
        background: var(--primary);
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: background 0.15s ease;
        white-space: nowrap;
    }

    .btn-add:hover {
        background: var(--primary-dark);
        color: #fff;
    }

    .alert-success {
        background: var(--success-bg);
        color: var(--success-text);
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 500;
        border: 1px solid var(--success-border);
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }

    /* ===== FILTER BAR ===== */
    .filter-bar {
        background: var(--table-header-bg);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 16px 18px;
        margin-bottom: 20px;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .filter-bar form {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-group label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--text-muted);
    }

    .filter-search-wrap {
        position: relative;
    }

    .filter-search-wrap svg {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        width: 15px;
        height: 15px;
        stroke: var(--text-muted);
        fill: none;
        stroke-width: 2;
        pointer-events: none;
    }

    .filter-search-wrap input {
        padding-left: 32px !important;
    }

    .filter-group input,
    .filter-group select {
        padding: 9px 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--surface);
        color: var(--text);
        font-size: 13px;
        font-family: inherit;
        transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.3s ease;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }

    .filter-actions {
        display: flex;
        gap: 8px;
    }

    .btn-filter-submit {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 9px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s ease;
        white-space: nowrap;
    }

    .btn-filter-submit:hover {
        background: var(--primary-dark);
    }

    .btn-filter-submit svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
    }

    .btn-filter-reset {
        display: inline-flex;
        align-items: center;
        background: none;
        border: 1px solid var(--border);
        color: var(--text-muted);
        padding: 9px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.15s ease, color 0.15s ease;
        white-space: nowrap;
    }

    .btn-filter-reset:hover {
        background: var(--table-hover-bg);
        color: var(--text);
    }

    @media (max-width: 640px) {
        .filter-bar form {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-group,
        .filter-actions {
            width: 100%;
        }
        .filter-actions {
            justify-content: flex-end;
        }
    }

    .invoice-table-container {
        overflow-x: auto;
        border-radius: 10px;
        border: 1px solid var(--border);
        transition: border-color 0.3s ease;
    }

    table.invoice-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .invoice-table thead tr {
        background: var(--table-header-bg);
        text-align: left;
    }

    .invoice-table th {
        padding: 12px 16px;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border);
        transition: color 0.3s ease, border-color 0.3s ease, background-color 0.3s ease;
    }

    .invoice-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
        color: var(--text);
        transition: color 0.3s ease, border-color 0.3s ease;
    }

    .invoice-table tbody tr:last-child td {
        border-bottom: none;
    }

    .invoice-table tbody tr {
        transition: background 0.12s ease;
    }

    .invoice-table tbody tr:hover {
        background: var(--table-hover-bg);
    }

    .no-invoice-badge {
        display: inline-block;
        background: var(--primary-light);
        color: var(--primary-dark);
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 13px;
    }

    .pelanggan-name {
        font-weight: 600;
    }

    .grand-total {
        font-weight: 700;
        color: var(--success);
    }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.03em;
    }

    .status-lunas {
        background: var(--success-bg);
        color: var(--success-text);
    }

    .status-belum {
        background: var(--danger-bg);
        color: var(--danger-text);
    }

    .action-group {
        display: flex;
        gap: 6px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: opacity 0.15s ease;
        white-space: nowrap;
    }

    .btn-action:hover {
        opacity: 0.85;
    }

    .btn-cetak {
        background: var(--primary);
        color: #fff;
    }

    .btn-edit {
        background: var(--warning-bg);
        color: var(--warning);
    }

    .btn-lunas {
        background: #10b981;
        color: #fff;
    }

    .btn-delete {
        background: var(--danger-bg);
        color: var(--danger);
    }

    .dropdown-wrap {
        position: relative;
        display: inline-block;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        top: calc(100% + 4px);
        background: var(--surface);
        min-width: 190px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.16);
        z-index: 20;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--border);
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-item {
        width: 100%;
        text-align: left;
        padding: 10px 14px;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-family: inherit;
        color: var(--text);
        border-bottom: 1px solid var(--divider);
        transition: background 0.12s ease;
    }

    .dropdown-item:last-child {
        border-bottom: none;
    }

    .dropdown-item:hover {
        background: var(--table-header-bg);
    }

    .dropdown-item.pemerintah {
        color: var(--info);
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: var(--text-muted);
    }

    .empty-state .empty-title {
        font-weight: 600;
        color: var(--text);
        margin-bottom: 4px;
    }

    .pagination-wrap {
        margin-top: 18px;
    }

    @media (max-width: 720px) {
        .invoice-card {
            padding: 16px;
            border-radius: 10px;
        }

        .invoice-table-container {
            border: none;
            overflow: visible;
        }

        .invoice-table thead {
            display: none;
        }

        .invoice-table, .invoice-table tbody, .invoice-table tr, .invoice-table td {
            display: block;
            width: 100%;
        }

        .invoice-table tr {
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-bottom: 12px;
            padding: 12px 14px;
            background: var(--surface);
        }

        .invoice-table td {
            border-bottom: none;
            padding: 6px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            text-align: right;
        }

        .invoice-table td::before {
            content: attr(data-label);
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.03em;
            text-align: left;
        }

        .invoice-table td.no-cell {
            display: none;
        }

        .invoice-table td.aksi-cell {
            justify-content: flex-start;
            padding-top: 10px;
            margin-top: 6px;
            border-top: 1px solid var(--border);
        }

        .invoice-table td.aksi-cell::before {
            content: none;
        }

        .action-group {
            justify-content: flex-start;
            width: 100%;
        }
    }
</style>

@php
    $userPerms = Auth::user()->permissions ?? [];
@endphp

<div class="invoice-wrap">
    <div class="invoice-card">
        <div class="invoice-header">
            <div>
                <h3>Data Invoice Penjualan</h3>
                <p>Kelola invoice, status pembayaran, dan pelunasan</p>
            </div>
            @if(Auth::user()->role == 'admin' || in_array('invoice_create', $userPerms))
                <a href="{{ route('invoice.create') }}" class="btn-add">+ Buat Invoice Baru</a>
            @endif
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <!-- ===== FILTER: Pencarian, Bulan, Tahun ===== -->
        <div class="filter-bar">
            <form action="{{ route('invoice.index') }}" method="GET">
                <div class="filter-group" style="flex: 1; min-width: 220px;">
                    <label>Pencarian</label>
                    <div class="filter-search-wrap">
                        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3" stroke-linecap="round"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No Invoice atau Nama Pelanggan..." style="width: 100%; box-sizing: border-box;">
                    </div>
                </div>

                <div class="filter-group" style="min-width: 150px;">
                    <label>Bulan</label>
                    <select name="bulan">
                        <option value="">-- Semua Bulan --</option>
                        @php
                            $namaBulan = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
                        @endphp
                        @foreach($namaBulan as $angka => $nama)
                            <option value="{{ $angka }}" {{ request('bulan') == $angka ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group" style="min-width: 120px;">
                    <label>Tahun</label>
                    <select name="tahun">
                        <option value="">-- Semua --</option>
                        @for($i = 2024; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter-submit">
                        <svg viewBox="0 0 24 24"><path d="M4 6h16M7 12h10M10 18h4" stroke-linecap="round"/></svg>
                        Terapkan
                    </button>
                    <a href="{{ route('invoice.index') }}" class="btn-filter-reset">Reset</a>
                </div>
            </form>
        </div>

        <div class="invoice-table-container">
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Invoice</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Grand Total</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $index => $inv)
                        <tr>
                            <td class="no-cell" data-label="No">{{ $invoices->firstItem() + $index }}</td>
                            <td data-label="No Invoice"><span class="no-invoice-badge">{{ $inv->no_invoice }}</span></td>
                            <td data-label="Tanggal">{{ date('d M Y', strtotime($inv->tanggal)) }}</td>
                            <td class="pelanggan-name" data-label="Pelanggan">{{ $inv->nama_pelanggan }}</td>
                            <td class="grand-total" data-label="Grand Total">Rp {{ number_format($inv->grand_total, 0, ',', '.') }}</td>
                            <td data-label="Status" style="text-align: center;">
                                @if($inv->status_pembayaran == 'Lunas')
                                    <span class="status-badge status-lunas">LUNAS</span>
                                @else
                                    <span class="status-badge status-belum">BELUM LUNAS</span>
                                @endif
                            </td>

                            <td class="aksi-cell" data-label="Aksi">
                                <div class="action-group">
                                    {{-- Tombol Cetak --}}
                                    @if(Auth::user()->role == 'admin' || in_array('invoice_print', $userPerms))
                                        <a href="{{ route('invoice.print', $inv->id) }}" class="btn-action btn-cetak" target="_blank">Cetak</a>
                                    @endif

                                    @if($inv->status_pembayaran != 'Lunas')
                                        {{-- Tombol Edit --}}
                                        @if(Auth::user()->role == 'admin' || in_array('invoice_edit', $userPerms))
                                            <a href="{{ route('invoice.edit', $inv->id) }}" class="btn-action btn-edit">Edit</a>
                                        @endif

                                        {{-- Tombol Dropdown Lunas --}}
                                        <div class="dropdown-wrap">
                                            <button type="button" onclick="toggleDropdown({{ $inv->id }})" class="btn-action btn-lunas">
                                                Lunas &#9662;
                                            </button>

                                            <div id="dropdown_{{ $inv->id }}" class="dropdown-menu">
                                                <form action="{{ route('invoice.lunas', [$inv->id, 'swasta']) }}" method="POST" onsubmit="return confirm('Kirim pembayaran ini ke Uang Masuk SWASTA?');">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">Masuk Swasta</button>
                                                </form>
                                                <form action="{{ route('invoice.lunas', [$inv->id, 'pemerintah']) }}" method="POST" onsubmit="return confirm('Kirim pembayaran ini ke Uang Masuk PEMERINTAH (Otomatis hitung PPN & PPh 22)?');">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item pemerintah">Masuk Pemerintah</button>
                                                </form>
                                                <form action="{{ route('invoice.lunas', [$inv->id, 'hanya_status']) }}" method="POST" onsubmit="return confirm('Yakin invoice ini di Tandai sebagai LUNAS? Invoice tidak bisa di delete jika Status LUNAS');">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">Lunas Aja</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Tombol Hapus --}}
                                    @if(Auth::user()->role == 'admin' || in_array('invoice_delete', $userPerms))
                                        <form action="{{ route('invoice.destroy', $inv->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus invoice ini?')">
                                            @csrf
                                            <button type="submit" class="btn-action btn-delete">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-title">Belum ada data invoice</div>
                                    <div>Klik "Buat Invoice Baru" untuk menambahkan invoice pertama.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $invoices->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<script>
function toggleDropdown(id) {
    let dropdown = document.getElementById('dropdown_' + id);
    document.querySelectorAll('.dropdown-menu').forEach(el => {
        if (el.id !== 'dropdown_' + id) el.classList.remove('show');
    });
    dropdown.classList.toggle('show');
}

window.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown-wrap')) {
        document.querySelectorAll('.dropdown-menu').forEach(el => {
            el.classList.remove('show');
        });
    }
});
</script>
@endsection