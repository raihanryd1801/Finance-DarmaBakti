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
            color: var(--text-primary);
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            text-decoration: none;
            line-height: 1;
            transition: all 0.15s ease;
        }

        .pagination .page-link:hover {
            background-color: var(--table-hover);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .pagination .page-item.active .page-link {
            background-color: #2563eb;
            border-color: #2563eb;
            color: #fff;
            font-weight: 600;
        }

        .pagination .page-item.disabled .page-link {
            color: var(--text-secondary);
            background-color: var(--table-header);
            border-color: var(--border-color);
            cursor: not-allowed;
            pointer-events: none;
        }

        .pagination svg {
            width: 16px !important;
            height: 16px !important;
            display: inline-block;
        }

        .alert-success-box {
            background: #d1fae5;
            color: #065f46;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }
        [data-theme="dark"] .alert-success-box {
            background: rgba(16, 185, 129, 0.18);
            color: #6ee7b7;
        }
    </style>

    @php
        $userPerms = Auth::user()->permissions ?? [];
        $isAdmin = Auth::check() && Auth::user()->role == 'admin';
        
        // Izin aksi (Edit / Hapus / Tambah)
        $canCreate = $isAdmin || in_array('uang_masuk_create', $userPerms);
        $canEdit = $isAdmin || in_array('uang_masuk_edit', $userPerms);
        $canDelete = $isAdmin || in_array('uang_masuk_delete', $userPerms);
        $showActionColumn = $canEdit || $canDelete;
    @endphp

    <!-- Header Atas (Judul & Tombol Tambah/Import) -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px;">
        <h2 style="margin: 0; font-size: 1.5rem; color: var(--text-primary);">
            Data Uang Masuk - {{ strtoupper($kategori) }}
        </h2>
        
        <div style="display: flex; gap: 10px; align-items: center;">
            {{-- Tombol Tambah Data (Muncul jika Admin atau punya izin create) --}}
            @if($canCreate)
                <a href="{{ route('uang_masuk.create') }}" class="btn btn-success">+ Tambah Data</a>
            @endif

            {{-- Form Import Excel (Khusus Admin) --}}
            @if($isAdmin)
                <form action="{{ route('uang_masuk.import') }}" method="POST" enctype="multipart/form-data" style="display: inline-flex; gap: 5px; align-items: center;">
                    @csrf
                    <input type="file" name="file_excel" required style="font-size: 0.85rem; background: var(--bg-card); color: var(--text-primary); padding: 4px; border: 1px solid var(--border-color); border-radius: 4px;">
                    <button type="submit" class="btn btn-secondary">Import Excel</button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success-box">
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
                    <label style="font-size: 0.75rem; color: var(--text-secondary); display: block; margin-bottom: 2px;">Instansi</label>
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
                    <label style="font-size: 0.75rem; color: var(--text-secondary); display: block; margin-bottom: 2px;">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>

                <div>
                    <label style="font-size: 0.75rem; color: var(--text-secondary); display: block; margin-bottom: 2px;">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>

                <div style="flex-grow: 1; min-width: 200px;">
                    <label style="font-size: 0.75rem; color: var(--text-secondary); display: block; margin-bottom: 2px;">Cari Data</label>
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
                            @if($showActionColumn)
                                <th style="padding: 12px; text-align: center;">Aksi</th>
                            @endif
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
                            <th>Nilai Dokumen</th>
                            <th>Selisih</th>
                            <th>SUDAH TF/BELUM TF</th>
                            <th>Tanggal TF Rek</th>
                            <th>Rekening</th>
                            <th>NO PENGEMBALIAN</th>
                            <th>SUDAH BUAT FAKTUR</th>
                            @if($showActionColumn)
                                <th style="text-align: center;">Aksi</th>
                            @endif
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
                                <!-- KOLOM SELISIH -->
                                <td class="angka" style="{{ $row->selisih < 0 ? 'color: red; font-weight: bold;' : '' }}">
                                    @if($row->selisih == 0)
                                        <span style="color: #94a3b8;">0</span>
                                    @else
                                        Rp {{ number_format((float)$row->selisih, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td><strong>{{ $row->instansi }}</strong></td>
                                <td>{{ $row->rekening_tujuan ?? '-' }}</td>
                                <td style="max-width: 200px; white-space: normal; word-wrap: break-word;">{{ $row->keterangan ?? '-' }}</td>

                                <!-- Tombol Aksi Swasta -->
                                @if($showActionColumn)
                                <td style="text-align: center; white-space: nowrap;">
                                    @if($canEdit)
                                        <a href="{{ route('uang_masuk.edit', $row->id) }}" class="btn btn-primary" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Edit</a>
                                    @endif

                                    @if($canDelete)
                                        <form action="{{ route('uang_masuk.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                                @endif
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
                                <td class="angka">Rp {{ number_format((float)$row->nilai_nota, 0, ',', '.') }}</td>
                                <!-- KOLOM SELISIH -->
                                <td class="angka" style="{{ $row->selisih < 0 ? 'color: red; font-weight: bold;' : '' }}">
                                    @if($row->selisih == 0)
                                        <span style="color: #94a3b8;">0</span>
                                    @else
                                        Rp {{ number_format((float)$row->selisih, 0, ',', '.') }}
                                    @endif
                                </td>
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
                                @if($showActionColumn)
                                <td style="text-align: center; white-space: nowrap;">
                                    @if($canEdit)
                                        <a href="{{ route('uang_masuk.edit', $row->id) }}" class="btn btn-primary" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Edit</a>
                                    @endif

                                    @if($canDelete)
                                        <form action="{{ route('uang_masuk.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                                @endif
                            </tr>
                        @endif
                    @empty
                        <!-- KONDISI JIKA DATA KOSONG -->
                        <tr>
                            <td colspan="{{ $kategori == 'swasta' ? ($showActionColumn ? 11 : 10) : ($showActionColumn ? 16 : 15) }}" style="text-align: center; color: var(--text-secondary); padding: 3rem;">
                                Belum ada data uang masuk untuk kategori {{ strtoupper($kategori) }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 1.5rem;">
            {{ $dataUangMasuk->appends([
                'kategori' => $kategori,
                'instansi' => request('instansi'),
                'search' => request('search'),
                'start_date' => request('start_date'),
                'end_date' => request('end_date')
            ])->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection