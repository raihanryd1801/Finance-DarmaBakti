@extends('layouts.app')

@section('content')
    <div class="card" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem;">
        <h2 style="margin: 0;">Data Uang Masuk</h2>
        <a href="{{ route('uang_masuk.create') }}" class="btn btn-success">+ Tambah Data</a>
    </div>
    <!-- Form Import -->
<form action="{{ route('uang_masuk.import') }}" method="POST" enctype="multipart/form-data" style="display: inline-block; margin-left: 10px;">
    @csrf
    <input type="file" name="file_excel" required style="font-size: 0.875rem;">
    <button type="submit" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.875rem;">Import Excel</button>
</form>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="filter-section" style="margin-bottom: 20px;">
            <form action="{{ route('uang_masuk.index') }}" method="GET">
                <select name="instansi" class="form-control">
                    <option value="">-- Semua Instansi --</option>
                    @foreach($listInstansi as $instansi)
                        <option value="{{ $instansi }}" {{ request('instansi') == $instansi ? 'selected' : '' }}>
                            {{ strtoupper($instansi) }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="search" placeholder="Cari nama pengadaan..." value="{{ request('search') }}" class="form-control">
                
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('uang_masuk.index') }}" class="btn btn-secondary">Reset</a>
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Instansi</th>
                        <th>Nama Pengadaan</th>
                        <th>Include PPN</th>
                        <th>Total Diterima</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataUangMasuk as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal_transfer)->format('d M Y') }}</td>
                        <td>{{ $row->instansi }}</td>
                        <td>{{ $row->nama_pengadaan ?? '-' }}</td>
                        <td>Rp {{ number_format($row->jumlah_include_ppn, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($row->total_diterima, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $row->status_transfer == 'SUDAH' ? 'bg-success' : 'bg-warning' }}">
                                {{ $row->status_transfer }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #6b7280;">Belum ada data uang masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Section -->
        <div style="margin-top: 20px;">
            {{ $dataUangMasuk->links() }}
        </div>
    </div>
@endsection