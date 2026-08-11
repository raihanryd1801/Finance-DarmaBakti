@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --surface: #ffffff;
        --border: #e2e8f0;
        --text: #1e293b;
        --text-muted: #64748b;
        --danger: #dc2626;
        --radius: 12px;
        --input-bg: #ffffff;
        --cancel-bg: #f1f5f9;
        --cancel-bg-hover: #e2e8f0;
    }

    [data-theme="dark"] {
        --surface: #1e293b;
        --border: #334155;
        --text: #f8fafc;
        --text-muted: #94a3b8;
        --input-bg: #0f172a;
        --cancel-bg: #334155;
        --cancel-bg-hover: #475569;
    }

    .form-wrap {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        max-width: 640px;
        margin: 0 auto;
    }

    .form-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 28px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .form-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 4px;
        letter-spacing: -0.01em;
    }

    .form-subtitle {
        font-size: 13px;
        color: var(--text-muted);
        margin: 0 0 24px;
    }

    .field-group {
        margin-bottom: 18px;
    }

    .field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .field-label {
        display: block;
        font-weight: 600;
        font-size: 13px;
        color: var(--text);
        margin-bottom: 6px;
    }

    .field-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 14px;
        color: var(--text);
        background: var(--input-bg);
        transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.3s ease;
        box-sizing: border-box;
        font-family: inherit;
    }

    .field-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }

    textarea.field-input {
        resize: vertical;
        min-height: 80px;
    }

    .field-error {
        display: block;
        color: var(--danger);
        font-size: 12px;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 24px;
    }

    .btn-submit {
        padding: 11px 22px;
        border-radius: 8px;
        background: var(--primary);
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: background 0.15s ease;
    }

    .btn-submit:hover {
        background: var(--primary-dark);
    }

    .btn-cancel {
        padding: 11px 22px;
        border-radius: 8px;
        background: var(--cancel-bg);
        color: var(--text-muted);
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        border: 1px solid var(--border);
        transition: background 0.15s ease;
    }

    .btn-cancel:hover {
        background: var(--cancel-bg-hover);
        color: var(--text);
    }

    @media (max-width: 560px) {
        .form-card {
            padding: 20px;
            border-radius: 10px;
        }

        .field-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn-submit, .btn-cancel {
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }
    }
</style>

<div class="form-wrap">
    <div class="form-card">
        <h3 class="form-title">Tambah Data Barang</h3>
        <p class="form-subtitle">Lengkapi informasi barang baru di bawah ini</p>

        <form action="{{ route('barang.store') }}" method="POST">
            @csrf
            <div class="field-group">
                <label class="field-label">Kode Barang</label>
                <input type="text" name="kode_barang" class="field-input" required placeholder="Contoh: 01-001">
                @error('kode_barang') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field-group">
                <label class="field-label">Nama Barang</label>
                <input type="text" name="nama_barang" class="field-input" required placeholder="Contoh: Buku Tulis">
                @error('nama_barang') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field-row">
                <div class="field-group">
                    <label class="field-label">Satuan</label>
                    <input type="text" name="satuan" class="field-input" required placeholder="Contoh: Pcs, Rim, Stel">
                    @error('satuan') <span class="field-error">{{ $message }}</span> @enderror
                </div>
                <div class="field-group">
                    <label class="field-label">Harga Satuan (Rp)</label>
                    <input type="number" name="harga" class="field-input" required placeholder="Contoh: 10000">
                    @error('harga') <span class="field-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="field-group">
                <label class="field-label">Keterangan (Opsional)</label>
                <textarea name="keterangan" class="field-input" rows="3" placeholder="Catatan tambahan..."></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Simpan Barang</button>
                <a href="{{ route('barang.index') }}" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection