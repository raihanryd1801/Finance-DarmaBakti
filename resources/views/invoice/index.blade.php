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
        --danger: #dc2626;
        --danger-bg: #fee2e2;
        --warning: #d97706;
        --warning-bg: #fef3c7;
        --info: #0284c7;
        --info-bg: #e0f2fe;
        --radius: 12px;
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
        color: #14532d;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 500;
        border: 1px solid #bbf7d0;
    }

    .invoice-table-container {
        overflow-x: auto;
        border-radius: 10px;
        border: 1px solid var(--border);
    }

    table.invoice-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .invoice-table thead tr {
        background: #f8fafc;
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
    }

    .invoice-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .invoice-table tbody tr:last-child td {
        border-bottom: none;
    }

    .invoice-table tbody tr {
        transition: background 0.12s ease;
    }

    .invoice-table tbody tr:hover {
        background: #fafbff;
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
        color: #14532d;
    }

    .status-belum {
        background: var(--danger-bg);
        color: #991b1b;
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
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.12s ease;
    }

    .dropdown-item:last-child {
        border-bottom: none;
    }

    .dropdown-item:hover {
        background: #f8fafc;
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

    /* ===== Mobile: table collapses into stacked cards ===== */
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

<div class="invoice-wrap">
    <div class="invoice-card">
        <div class="invoice-header">
            <div>
                <h3>Data Invoice Penjualan</h3>
                <p>Kelola invoice, status pembayaran, dan pelunasan</p>
            </div>
            <a href="{{ route('invoice.create') }}" class="btn-add">+ Buat Invoice Baru</a>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

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
                            <td class="no-cell" data-label="No">{{ $index + 1 }}</td>
                            <td data-label="No Invoice"><span class="no-invoice-badge">{{ $inv->no_invoice }}</span></td>
                            <td data-label="Tanggal">{{ date('d M Y', strtotime($inv->tanggal)) }}</td>
                            <td class="pelanggan-name" data-label="Pelanggan">{{ $inv->nama_pelanggan }}</td>
                            <td class="grand-total" data-label="Grand Total">Rp {{ number_format($inv->grand_total, 0, ',', '.') }}</td>
                            <td data-label="Status">
                                @if($inv->status_pembayaran == 'Lunas')
                                    <span class="status-badge status-lunas">LUNAS</span>
                                @else
                                    <span class="status-badge status-belum">BELUM LUNAS</span>
                                @endif
                            </td>
                            <td class="aksi-cell" data-label="Aksi">
                                <div class="action-group">
                                    <a href="{{ route('invoice.print', $inv->id) }}" target="_blank" class="btn-action btn-cetak">Cetak</a>

                                    @if($inv->status_pembayaran != 'Lunas')
                                        <a href="{{ route('invoice.edit', $inv->id) }}" class="btn-action btn-edit">Edit</a>

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
                                                    <button type="submit" class="dropdown-item hanya_status">Lunas Aja</button>
                                                </form>
                                            </div>
                                        </div>

                                        <form action="{{ route('invoice.destroy', $inv->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus Invoice ini permanen?');" style="display:inline-block;">
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
            {{ $invoices->links() }}
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