@extends('layouts.app')

@section('content')
    <style>
        /* ===== FIX PAGINATION - PROFESSIONAL & COMPACT ===== */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 4px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .pagination .page-item {
            margin: 0;
        }

        .pagination .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 8px;
            font-size: 0.85rem;
            color: #334155;
            background-color: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            text-decoration: none;
            line-height: 1;
            transition: all 0.15s ease;
        }

        .pagination .page-link:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .pagination .page-item.active .page-link {
            background-color: #2563eb;
            border-color: #2563eb;
            color: #fff;
            font-weight: 600;
        }

        .pagination .page-item.disabled .page-link {
            color: #cbd5e1;
            background-color: #f8fafc;
            border-color: #e2e8f0;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Jaga-jaga kalau suatu saat pagination pakai SVG lagi, ukurannya tetap dikunci */
        .pagination svg {
            width: 16px !important;
            height: 16px !important;
            display: inline-block;
        }
    </style>

    <!-- Header Atas (Judul & Tombol Tambah/Import) -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px;">
        <h2 style="margin: 0; font-size: 1.5rem; color: #1e293b;">
            Data Uang Masuk - {{ strtoupper($kategori) }}
        </h2>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('uang_masuk.create') }}" class="btn btn-success">+ Tambah Data</a>

            <!-- Form Import Excel -->
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

    <!-- Area Konten Utama -->
    <div class="card" style="width: 100%; box-sizing: border-box; padding: 1rem;">

        <!-- Form Filter dengan Tanggal -->
        <div class="filter-section" style="margin-bottom: 1.5rem;">
            <form action="{{ route('uang_masuk.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;">
                <!-- Lempar variabel kategori -->
                <input type="hidden" name="kategori" value="{{ $kategori }}">

                <div>
                    <label style="font-size: 0.75rem; color: #64748b; display: block; margin-bottom: 2px;">Instansi</label>
                    <select name="instansi" class="form-control">
                        <option value="">-- Semua Instansi --</option>
                        @foreach($listInstansi as $instansi)
                            <option value="{{ $instansi }}" {{ request('instansi') == $instansi ? 'selected' : '' }}>
                                {{ strtoupper($instansi) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="font-size: 0.75rem; color: #64748b; display: block; margin-bottom: 2px;">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>

                <div>
                    <label style="font-size: 0.75rem; color: #64748b; display: block; margin-bottom: 2px;">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>

                <div style="flex-grow: 1; min-width: 200px;">
                    <label style="font-size: 0.75rem; color: #64748b; display: block; margin-bottom: 2px;">Cari Data</label>
                    <input type="text" name="search" placeholder="Cari nama pengadaan / keterangan..." value="{{ request('search') }}" class="form-control" style="width: 100%;">
                </div>

                <div style="display: flex; gap: 5px;">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('uang_masuk.index', ['kategori' => $kategori]) }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
        <!-- Tabel Full Screen -->
        <div class="table-container" style="width: 100%; overflow-x: auto;">
            <table class="finance-table" style="width: 100%;">
                <thead>
                    @if($kategori == 'swasta')
                        <!-- HEADER TABEL SWASTA -->
                        <tr>
                            <th>No</th>
                            <th>Tanggal Transfer</th>
                            <th>Jumlah Transfer (Include PPN)</th>
                            <th>Total (Exclude PPN)</th>
                            <th>PPN</th>
                            <th>Nilai Nota</th>
                            <th>Selisih</th>
                            <th>INSTANSI</th>
                            <th>Rekening</th>
                            <th>Keterangan</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    @else
                        <!-- HEADER TABEL PEMERINTAH -->
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
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    @endif
                </thead>
                <tbody>
                    @forelse($dataUangMasuk as $index => $row)
                        @if($kategori == 'swasta')
                            <!-- ISI TABEL SWASTA -->
                            <tr>
                                <td style="text-align: center;">{{ $dataUangMasuk->firstItem() + $index }}</td>
                                <td>{{ $row->tanggal_transfer ? \Carbon\Carbon::parse($row->tanggal_transfer)->translatedFormat('d F Y') : '-' }}</td>
                                <td class="angka">Rp {{ number_format((float)$row->jumlah_include_ppn, 0, ',', '.') }}</td>
                                <td class="angka">Rp {{ number_format((float)$row->jumlah_exclude_ppn, 0, ',', '.') }}</td>
                                <td class="angka" style="color: #0284c7;">Rp {{ number_format((float)$row->ppn, 0, ',', '.') }}</td>
                                <td class="angka">Rp {{ number_format((float)$row->nilai_nota, 0, ',', '.') }}</td>
                                <td class="angka" style="{{ $row->selisih < 0 ? 'color: red;' : '' }}">
                                    Rp {{ number_format((float)$row->selisih, 0, ',', '.') }}
                                </td>
                                <td><strong>{{ $row->instansi }}</strong></td>
                                <td>{{ $row->rekening_tujuan ?? '-' }}</td>
                                <td style="max-width: 200px; white-space: normal; word-wrap: break-word;">{{ $row->keterangan ?? '-' }}</td>

                                <!-- Tombol Aksi Swasta -->
                                <td style="text-align: center; white-space: nowrap;">
                                    <a href="{{ route('uang_masuk.edit', $row->id) }}" class="btn btn-primary" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Edit</a>
                                    <form action="{{ route('uang_masuk.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @else
                            <!-- ISI TABEL PEMERINTAH -->
                            <tr>
                                <td><strong>{{ $row->instansi }}</strong></td>
                                <td style="max-width: 300px; white-space: normal; word-wrap: break-word;">{{ $row->nama_pengadaan ?? '-' }}</td>
                                <td>{{ $row->nama_ppk ?? '-' }}</td>
                                <td class="angka">Rp {{ number_format((float)$row->jumlah_include_ppn, 0, ',', '.') }}</td>
                                <td class="angka">Rp {{ number_format((float)$row->jumlah_exclude_ppn, 0, ',', '.') }}</td>
                                <td class="angka" style="color: #d97706;">Rp {{ number_format((float)$row->pph_22, 0, ',', '.') }}</td>
                                <td class="angka" style="font-weight: bold; color: #047857;">Rp {{ number_format((float)$row->total_diterima, 0, ',', '.') }}</td>
                                <td class="angka">Rp {{ number_format((float)$row->total_rekening_koran, 0, ',', '.') }}</td>
                                <td style="text-align: center;">
                                    <span class="badge {{ $row->status_transfer == 'SUDAH' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $row->status_transfer ?? 'BELUM' }}
                                    </span>
                                </td>
                                <td>{{ $row->tanggal_transfer ? \Carbon\Carbon::parse($row->tanggal_transfer)->translatedFormat('d F Y') : '-' }}</td>
                                <td>{{ $row->rekening_tujuan ?? '-' }}</td>
                                <td style="max-width: 200px; white-space: normal; word-wrap: break-word;">{{ $row->status_pengembalian ?? '-' }}</td>
                                <td style="text-align: center;">{{ $row->status_faktur ?? '-' }}</td>

                                <!-- Tombol Aksi Pemerintah -->
                                <td style="text-align: center; white-space: nowrap;">
                                    <a href="{{ route('uang_masuk.edit', $row->id) }}" class="btn btn-primary" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Edit</a>
                                    <form action="{{ route('uang_masuk.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <!-- KONDISI JIKA DATA KOSONG -->
                        <tr>
                            <td colspan="{{ $kategori == 'swasta' ? 11 : 14 }}" style="text-align: center; color: #6b7280; padding: 3rem;">
                                Belum ada data uang masuk untuk kategori {{ strtoupper($kategori) }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 1.5rem;">
            {{ $dataUangMasuk->appends(['kategori' => $kategori, 'instansi' => request('instansi'), 'search' => request('search')])->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection