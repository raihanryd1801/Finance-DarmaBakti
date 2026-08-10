@extends('layouts.app')

@section('content')
    <!-- Header Atas (Judul & Tombol Tambah/Import) -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px;">
        <h2 style="margin: 0; font-size: 1.5rem; color: #1e293b;">Data Uang Masuk</h2>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('uang_masuk.create') }}" class="btn btn-success">+ Tambah Data</a>
            
            <!-- Form Import -->
            <form action="{{ route('uang_masuk.import') }}" method="POST" enctype="multipart/form-data" style="display: inline-flex; gap: 5px; align-items: center;">
                @csrf
                <input type="file" name="file_excel" required style="font-size: 0.85rem; background: white; padding: 4px; border: 1px solid var(--border-color); border-radius: 4px;">
                <button type="submit" class="btn btn-secondary">Import Excel</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Area Tabel Full Width -->
    <div class="card" style="width: 100%; box-sizing: border-box; padding: 1rem;">
        
        <!-- Form Filter -->
        <div class="filter-section" style="margin-bottom: 1.5rem;">
            <form action="{{ route('uang_masuk.index') }}" method="GET">
                <select name="instansi" class="form-control">
                    <option value="">-- Semua Instansi --</option>
                    @foreach($listInstansi as $instansi)
                        <option value="{{ $instansi }}" {{ request('instansi') == $instansi ? 'selected' : '' }}>
                            {{ strtoupper($instansi) }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="search" placeholder="Cari nama pengadaan..." value="{{ request('search') }}" class="form-control" style="flex-grow: 1; max-width: 300px;">
                
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('uang_masuk.index') }}" class="btn btn-secondary">Reset</a>
            </form>
        </div>

        <!-- Tambahkan CSS khusus untuk tabel agar bisa scroll mendatar dengan nyaman -->
        <style>
            .table-container {
                overflow-x: auto;
                border-radius: 6px;
                border: 1px solid var(--border-color);
                margin-top: 15px;
            }
            .finance-table {
                width: 100%;
                border-collapse: collapse;
                white-space: nowrap;
                font-size: 0.8rem; /* Ukuran font dikecilkan sedikit agar muat banyak kolom */
            }
            .finance-table th, .finance-table td {
                padding: 0.5rem 0.75rem;
                text-align: left;
                border: 1px solid var(--border-color);
            }
            .finance-table th {
                background-color: #f3f4f6;
                font-weight: 600;
                text-align: center;
                vertical-align: middle;
            }
            .finance-table td.angka {
                text-align: right;
            }
            /* Styling untuk row yang meniru warna abu-abu pada gambar */
            .finance-table tbody tr:nth-child(even) {
                background-color: #f9fafb;
            }
            /* Styling highlight kuning untuk kolom tertentu jika ingin ditiru */
            .highlight-yellow {
                background-color: #ffff00;
                font-weight: bold;
            }
        </style>

        <!-- Tabel Full Screen -->
        <div class="table-container" style="width: 100%;">
            <table class="finance-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Instansi</th>
                        <th>Nama Pengadaan</th>
                        <th>Nama PPK</th>
                        <th>Jumlah (include PPN)</th>
                        <th>Jumlah (exclude PPN)</th>
                        <th>PPh 22</th>
                        <th>Total yang di terima</th>
                        <th>Total Rekening Koran</th>
                        <th>SUDAH TF/BELUM TF</th>
                        <th>Tanggal TF Rek</th>
                        <th>Rekening</th>
                        <th>NO PENGEMBALIAN</th>
                        <th>SUDAH BUAT FAKTUR</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataUangMasuk as $row)
                    <tr>
                        <td><strong>{{ $row->instansi }}</strong></td>
                        <td style="max-width: 300px; white-space: normal; word-wrap: break-word;">{{ $row->nama_pengadaan ?? '-' }}</td>
                        <td>{{ $row->nama_ppk ?? '-' }}</td>
                        <td class="angka">Rp {{ number_format($row->jumlah_include_ppn, 0, ',', '.') }}</td>
                        <td class="angka">Rp {{ number_format($row->jumlah_exclude_ppn, 0, ',', '.') }}</td>
                        <td class="angka">Rp {{ number_format($row->pph_22, 0, ',', '.') }}</td>
                        <td class="angka" style="font-weight: bold; color: #047857;">Rp {{ number_format($row->total_diterima, 0, ',', '.') }}</td>
                        <td class="angka">Rp {{ number_format($row->total_rekening_koran, 0, ',', '.') }}</td>
                        <td style="text-align: center;">
                            <span class="badge {{ $row->status_transfer == 'SUDAH' ? 'bg-success' : 'bg-warning' }}">
                                {{ $row->status_transfer ?? 'BELUM' }}
                            </span>
                        </td>
                        <td>{{ $row->tanggal_transfer ? \Carbon\Carbon::parse($row->tanggal_transfer)->translatedFormat('d F Y') : '-' }}</td>
                        <td>{{ $row->rekening_tujuan ?? '-' }}</td>
                        <td style="max-width: 200px; white-space: normal; word-wrap: break-word;">{{ $row->status_pengembalian ?? '-' }}</td>
                        <td style="text-align: center;">{{ $row->status_faktur ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13" style="text-align: center; color: #6b7280; padding: 3rem;">Belum ada data uang masuk. Silakan import file Excel atau tambah data baru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div style="margin-top: 1.5rem;">
            {{ $dataUangMasuk->links() }}
        </div>
    </div>
@endsection