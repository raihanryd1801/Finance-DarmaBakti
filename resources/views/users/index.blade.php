@extends('layouts.app')

@section('content')
<style>
    .users-page { font-family:'Inter',system-ui,sans-serif; color:var(--text-primary); }
    .users-header { display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; padding:20px 4px; border-bottom:1px solid var(--border-color); margin-bottom:20px; }
    .users-header h3 { margin:0; font-size:19px; font-weight:700; }
    .users-header p { margin:4px 0 0; font-size:13px; color:var(--text-secondary,#64748b); }
    .btn-add { display:inline-flex; align-items:center; gap:6px; padding:10px 18px; border-radius:8px; background:var(--primary); color:#fff; font-weight:600; font-size:14px; text-decoration:none; white-space:nowrap; }
    .btn-add:hover { opacity:.88; color:#fff; }
    .plus-icon { width:14px; height:14px; position:relative; }
    .plus-icon::before, .plus-icon::after { content:""; position:absolute; background:#fff; border-radius:2px; }
    .plus-icon::before { width:14px; height:2px; top:6px; left:0; }
    .plus-icon::after { width:2px; height:14px; top:0; left:6px; }
    .alert-success { background:var(--success-bg,#d1fae5); color:var(--success-text,#065f46); padding:10px 15px; border-radius:8px; margin-bottom:16px; font-size:14px; border:1px solid #a7f3d0; }
    .alert-error { background:#fee2e2; color:#991b1b; padding:10px 15px; border-radius:8px; margin-bottom:16px; font-size:14px; border:1px solid #fecaca; }
    .users-table-container { overflow-x:auto; border-radius:12px; border:1px solid var(--border-color); }
    table.users-table { width:100%; border-collapse:collapse; font-size:14px; }
    .users-table thead tr { background:var(--bg-body); text-align:left; }
    .users-table th { padding:12px 16px; font-weight:600; font-size:12px; text-transform:uppercase; letter-spacing:.04em; color:var(--text-secondary,#64748b); border-bottom:1px solid var(--border-color); }
    .users-table td { padding:14px 16px; border-bottom:1px solid var(--border-color); vertical-align:middle; }
    .users-table tbody tr:last-child td { border-bottom:none; }
    .users-table tbody tr:hover { background:var(--bg-body); }
    .user-icon { width:32px; height:32px; border-radius:8px; background:var(--primary); color:#fff; display:inline-flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; margin-right:10px; vertical-align:middle; }
    .role-badge { display:inline-block; padding:4px 10px; border-radius:6px; font-size:11px; font-weight:700; letter-spacing:.03em; background:#fef3c7; color:#92400e; }
    .role-badge.admin { background:#dcfce7; color:#14532d; }
    .action-group { display:inline-flex; gap:6px; }
    .btn-manage { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:8px; background:var(--primary); color:#fff; font-weight:600; font-size:13px; text-decoration:none; border:none; cursor:pointer; }
    .btn-manage:hover { opacity:.88; color:#fff; }
    .btn-delete { background:#fee2e2; color:#dc2626; }
    .gear-icon { width:12px; height:12px; border:2px solid #fff; border-radius:50%; position:relative; }
    .gear-icon::before { content:""; position:absolute; inset:-4px; border:2px dashed #fff; border-radius:50%; opacity:.6; }
    .users-table th:last-child, .users-table td.aksi-cell { text-align:center; }

    @media (max-width: 720px) {
        .users-table thead { display:none; }
        .users-table, .users-table tbody, .users-table tr, .users-table td { display:block; width:100%; }
        .users-table tr { border:1px solid var(--border-color); border-radius:10px; margin-bottom:12px; padding:12px 14px; }
        .users-table td { border-bottom:none; padding:6px 0; display:flex; justify-content:space-between; align-items:center; gap:12px; text-align:right; }
        .users-table td::before { content:attr(data-label); font-size:12px; font-weight:600; color:var(--text-secondary,#64748b); text-transform:uppercase; letter-spacing:.03em; text-align:left; }
        .users-table td.no-cell { display:none; }
        .users-table td.aksi-cell::before { content:none; }
    }
</style>

<div class="users-page">
    <div class="users-header">
        <div>
            <h3>Kelola Pengguna & Hak Akses</h3>
            <p>Tambah, ubah role, dan atur izin akses setiap pengguna</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn-add"><span class="plus-icon"></span> Tambah User Baru</a>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="users-table-container">
        <table class="users-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Email User</th>
                    <th>Role</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $u)
                    <tr>
                        <td class="no-cell" data-label="No">{{ $index + 1 }}</td>
                        <td data-label="Email">
                            <span class="user-icon">{{ strtoupper(mb_substr($u->email, 0, 1)) }}</span>{{ $u->email }}
                        </td>
                        <td data-label="Role">
                            <span class="role-badge {{ $u->role == 'admin' ? 'admin' : '' }}">{{ strtoupper($u->role) }}</span>
                        </td>
                        <td class="aksi-cell" data-label="Aksi">
                            <div class="action-group">
                                <a href="{{ route('users.edit', $u->id) }}" class="btn-manage"><span class="gear-icon"></span> Edit & Akses</a>

                                @if($u->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini secara permanen?');">
                                        @csrf
                                        <button type="submit" class="btn-manage btn-delete">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection