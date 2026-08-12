@extends('layouts.app')

@section('content')
<div class="card" style="padding: 20px;">
    <h3>👥 Kelola Hak Akses User (Viewer)</h3>
    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 10px; border-radius: 6px; margin: 15px 0;">{{ session('success') }}</div>
    @endif
    <div class="table-container" style="overflow-x: auto; margin-top: 15px;">
        <table class="finance-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Email User</th>
                    <th>Role</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $u)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $u->email }}</td>
                    <td><span class="badge badge-warning" style="background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 4px; font-weight: bold;">{{ strtoupper($u->role) }}</span></td>
                    <td style="text-align: center;">
                        <a href="{{ route('users.edit', $u->id) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem; text-decoration: none;">⚙️ Atur Hak Akses</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection