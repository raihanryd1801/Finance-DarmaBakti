@extends('layouts.app')

@section('content')
<style>
    .barang-wrap {
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
        --radius: 12px;
    }

    .barang-wrap {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: var(--text);
    }

    .barang-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    }

    .barang-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .barang-header h3 {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.01em;
    }

    .barang-header p {
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

    .barang-table-container {
        overflow-x: auto;
        border-radius: 10px;
        border: 1px solid var(--border);
    }

    table.barang-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .barang-table thead tr {
        background: #f8fafc;
        text-align: left;
    }

    .barang-table th {
        padding: 12px 16px;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border);
    }

    .barang-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .barang-table tbody tr:last-child td {
        border-bottom: none;
    }

    .barang-table tbody tr {
        transition: background 0.12s ease;
    }

    .barang-table tbody tr:hover {
        background: #fafbff;
    }

    .kode-badge {
        display: inline-block;
        background: var(--primary-light);
        color: var(--primary-dark);
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 13px;
    }

    .nama-barang {
        font-weight: 600;
    }

    .harga-value {
        font-weight: 700;
        color: var(--success);
    }

    .keterangan-text {
        color: var(--text-muted);
    }

    .action-group {
        display: flex;
        gap: 8px;
        justify-content: center;
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
    }

    .btn-action:hover {
        opacity: 0.85;
    }

    .btn-edit {
        background: var(--warning-bg);
        color: var(--warning);
    }

    .btn-delete {
        background: var(--danger-bg);
        color: var(--danger);
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
        .barang-card {
            padding: 16px;
            border-radius: 10px;
        }

        .barang-table-container {
            border: none;
            overflow: visible;
        }

        .barang-table thead {
            display: none;
        }

        .barang-table, .barang-table tbody, .barang-table tr, .barang-table td {
            display: block;
            width: 100%;
        }

        .barang-table tr {
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-bottom: 12px;
            padding: 12px 14px;
            background: var(--surface);
        }

        .barang-table td {
            border-bottom: none;
            padding: 6px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            text-align: right;
        }

        .barang-table td::before {
            content: attr(data-label);
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.03em;
            text-align: left;
        }

        .barang-table td.no-cell {
            display: none;
        }

        .barang-table td.aksi-cell {
            justify-content: flex-end;
        }

        .barang-table td.aksi-cell::before {
            content: none;
        }

        .action-group {
            justify-content: flex-end;
        }
    }
</style>

<div class="barang-wrap">
    <div class="barang-card">
        <div class="barang-header">
            <div>
                <h3>Master Data Barang</h3>
                <p>Kelola daftar barang, harga, dan satuan</p>
            </div>
            <a href="{{ route('barang.create') }}" class="btn-add">+ Tambah Barang</a>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="barang-table-container">
            <table class="barang-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Keterangan</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $index => $item)
                        <tr>
                            <td class="no-cell" data-label="No">{{ $index + 1 }}</td>
                            <td data-label="Kode"><span class="kode-badge">{{ $item->kode_barang }}</span></td>
                            <td class="nama-barang" data-label="Nama Barang">{{ $item->nama_barang }}</td>
                            <td data-label="Satuan">{{ $item->satuan }}</td>
                            <td class="harga-value" data-label="Harga">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="keterangan-text" data-label="Keterangan">{{ $item->keterangan ?? '-' }}</td>
                            <td class="aksi-cell" data-label="Aksi">
                                <div class="action-group">
                                    <a href="{{ route('barang.edit', $item->id) }}" class="btn-action btn-edit">Edit</a>
                                    <form action="{{ route('barang.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                                        @csrf
                                        <button type="submit" class="btn-action btn-delete">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-title">Belum ada data barang</div>
                                    <div>Klik "Tambah Barang" untuk menambahkan data pertama.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $barangs->links() }}
        </div>
    </div>
</div>
@endsection