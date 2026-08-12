@extends('layouts.app')

@section('content')
<style>
    .form-page { font-family:'Inter',system-ui,sans-serif; color:var(--text-primary); }
    .form-header { padding:20px 4px; border-bottom:1px solid var(--border-color); margin-bottom:24px; }
    .form-header h3 { margin:0; font-size:19px; font-weight:700; }
    .form-header p { margin:4px 0 0; font-size:13px; color:var(--text-secondary,#64748b); }
    .alert-error { background:#fee2e2; color:#991b1b; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:13px; border:1px solid #fecaca; }
    .alert-error ul { margin:0; padding-left:18px; }
    .form-section { max-width:640px; background:var(--bg-body); border:1px solid var(--border-color); border-radius:12px; padding:22px; margin-bottom:24px; }
    .field-group { margin-bottom:16px; }
    .field-group:last-child { margin-bottom:0; }
    .field-label { display:block; font-weight:600; font-size:13px; margin-bottom:6px; }
    .field-input { width:100%; padding:10px 14px; border:1px solid var(--border-color); border-radius:8px; font-size:14px; background:var(--surface,transparent); color:var(--text-primary); box-sizing:border-box; font-family:inherit; }
    .divider { border:0; border-top:1px solid var(--border-color); margin:24px 0; }
    .perm-lead { font-weight:700; font-size:15px; margin:0 0 16px 4px; }
    .perm-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:16px; margin-bottom:28px; }
    .perm-group { background:var(--bg-body); border:1px solid var(--border-color); border-radius:12px; padding:18px; }
    .perm-group-head { display:flex; align-items:center; gap:10px; margin-bottom:14px; }
    .perm-icon { width:30px; height:30px; border-radius:8px; background:var(--primary); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; flex-shrink:0; }
    .perm-group-head h4 { margin:0; font-size:14px; font-weight:600; }
    .perm-items { display:grid; grid-template-columns:repeat(auto-fit,minmax(210px,1fr)); gap:8px; }
    .perm-chip { display:flex; align-items:center; gap:8px; padding:9px 12px; border-radius:8px; border:1px solid var(--border-color); cursor:pointer; font-size:13px; transition:.15s; }
    .perm-chip:hover { border-color:var(--primary); }
    .perm-chip input { accent-color:var(--primary); width:15px; height:15px; }
    .perm-chip:has(input:checked) { background:var(--primary); border-color:var(--primary); color:#fff; }
    .form-actions { position:sticky; bottom:0; display:flex; gap:10px; padding:16px 4px; background:var(--bg-body); border-top:1px solid var(--border-color); }
    .btn-save { padding:11px 24px; border-radius:8px; background:var(--primary); color:#fff; font-weight:600; font-size:14px; border:none; cursor:pointer; }
    .btn-back { padding:11px 24px; border-radius:8px; background:transparent; color:var(--text-primary); font-weight:600; font-size:14px; border:1px solid var(--border-color); text-decoration:none; }
</style>

<div class="form-page">
    <div class="form-header">
        <h3>Tambah User Baru</h3>
        <p>Buat akun pengguna baru beserta hak aksesnya</p>
    </div>

    @if($errors->any())
        <div class="alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="form-section">
            <div class="field-group">
                <label class="field-label">Email User</label>
                <input type="email" name="email" class="field-input" value="{{ old('email') }}" required placeholder="cth: staff@finance.com">
            </div>

            <div class="field-group">
                <label class="field-label">Password</label>
                <input type="password" name="password" class="field-input" required placeholder="Minimal 6 karakter">
            </div>

            <div class="field-group">
                <label class="field-label">Role / Level</label>
                <select name="role" class="field-input" required>
                    <option value="viewer">Viewer (Staff / Pembatas Menu)</option>
                    <option value="admin">Admin (Akses Penuh)</option>
                </select>
            </div>
        </div>

        <hr class="divider">
        <p class="perm-lead">Hak akses menu & aksi (khusus role Viewer)</p>

        @php
            $permissionGroups = [
                'Data Report Keuangan' => [
                    'finance_report' => 'Lihat Halaman Report'
                ],
                'Modul Uang Masuk' => [
                    'uang_masuk_pemerintah' => 'Lihat Menu Uang Masuk Pemerintah',
                    'uang_masuk_swasta'     => 'Lihat Menu Uang Masuk Swasta',
                    'uang_masuk_create'     => 'Tambah Data Uang Masuk',
                    'uang_masuk_edit'       => 'Edit Data Uang Masuk',
                    'uang_masuk_delete'     => 'Hapus Data Uang Masuk',
                ],
                'Modul Invoice' => [
                    'invoice_index'  => 'Lihat Daftar Invoice',
                    'invoice_create' => 'Buat / Tambah Invoice Baru',
                    'invoice_edit'   => 'Edit Invoice',
                    'invoice_delete' => 'Hapus Invoice',
                    'invoice_print'  => 'Cetak / Print Invoice',
                    'invoice_lunas'  => 'Tandai Lunas & Masuk Keuangan',
                ],
                'Master Data Barang' => [
                    'barang_index' => 'Lihat Master Data Barang',
                ],
            ];
        @endphp

        <div class="perm-grid">
            @foreach($permissionGroups as $groupName => $permissions)
                <div class="perm-group">
                    <div class="perm-group-head">
                        <div class="perm-icon">{{ mb_substr($groupName, 0, 1) }}</div>
                        <h4>{{ $groupName }}</h4>
                    </div>
                    <div class="perm-items">
                        @foreach($permissions as $key => $label)
                            <label class="perm-chip">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">Simpan User Baru</button>
            <a href="{{ route('users.index') }}" class="btn-back">Kembali</a>
        </div>
    </form>
</div>
@endsection