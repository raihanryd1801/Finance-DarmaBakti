@extends('layouts.app')

@section('content')
    <!-- Header Atas (Judul & Tombol Tambah/Import) -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px;">
        <h2 style="margin: 0; font-size: 1.5rem; color: #1e293b;">Data Uang Masuk</h2>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('uang_masuk.create') }}" class="btn btn-success">Tambah Data</a>
            
            <!-- Form Import -->
            <form action="{{ route('uang_masuk.import') }}" method="POST" enctype="multipart/form-data" style="display: inline-flex; gap: 5px; align-items: center; flex-wrap: wrap;">
                @csrf
                <input type="file" name="file_excel" required style="font-size: 0.85rem; background: white; padding: 4px; border: 1px solid #d1d5db; border-radius: 4px;">
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
    <div class="card" style="width: 100%; box-sizing: border-box; padding: 1rem; background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        
        <!-- Form Filter -->
        <div class="filter-section" style="margin-bottom: 1.5rem;">
            <form action="{{ route('uang_masuk.index') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <select name="instansi" class="form-control" style="padding: 6px 12px; border: 1px solid #d1d5db; border-radius: 4px; background: white; min-width: 150px;">
                    <option value="">Semua Instansi</option>
                    @foreach($listInstansi as $instansi)
                        <option value="{{ $instansi }}" {{ request('instansi') == $instansi ? 'selected' : '' }}>
                            {{ strtoupper($instansi) }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="search" placeholder="Cari nama pengadaan..." value="{{ request('search') }}" class="form-control" style="flex: 1; min-width: 200px; padding: 6px 12px; border: 1px solid #d1d5db; border-radius: 4px;">
                
                <button type="submit" class="btn btn-primary" style="padding: 6px 16px; background: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer;">Filter</button>
                <a href="{{ route('uang_masuk.index') }}" class="btn btn-secondary" style="padding: 6px 16px; background: #6b7280; color: white; border: none; border-radius: 4px; text-decoration: none;">Reset</a>
            </form>
        </div>

        <!-- Tabel dengan Scroll Horizontal -->
        <div style="overflow-x: auto; border-radius: 6px; border: 1px solid #e5e7eb; margin-top: 15px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem; min-width: 1200px;">
                <thead>
                    <tr style="background-color: #f3f4f6;">
                        <th style="padding: 0.6rem 0.75rem; text-align: left; border: 1px solid #e5e7eb; font-weight: 600; white-space: nowrap;">Instansi</th>
                        <th style="padding: 0.6rem 0.75rem; text-align: left; border: 1px solid #e5e7eb; font-weight: 600; white-space: nowrap;">Nama Pengadaan</th>
                        <th style="padding: 0.6rem 0.75rem; text-align: left; border: 1px solid #e5e7eb; font-weight: 600; white-space: nowrap;">Nama PPK</th>
                        <th style="padding: 0.6rem 0.75rem; text-align: right; border: 1px solid #e5e7eb; font-weight: 600; white-space: nowrap;">Jumlah (include PPN)</th>
                        <th style="padding: 0.6rem 0.75rem; text-align: right; border: 1px solid #e5e7eb; font-weight: 600; white-space: nowrap;">Jumlah (exclude PPN)</th>
                        <th style="padding: 0.6rem 0.75rem; text-align: right; border: 1px solid #e5e7eb; font-weight: 600; white-space: nowrap;">PPh 22</th>
                        <th style="padding: 0.6rem 0.75rem; text-align: right; border: 1px solid #e5e7eb; font-weight: 600; white-space: nowrap;">Total Diterima</th>
                        <th style="padding: 0.6rem 0.75rem; text-align: right; border: 1px solid #e5e7eb; font-weight: 600; white-space: nowrap;">Total Rekening Koran</th>
                        <th style="padding: 0.6rem 0.75rem; text-align: center; border: 1px solid #e5e7eb; font-weight: 600; white-space: nowrap;">Status Transfer</th>
                        <th style="padding: 0.6rem 0.75rem; text-align: left; border: 1px solid #e5e7eb; font-weight: 600; white-space: nowrap;">Tanggal TF Rek</th>
                        <th style="padding: 0.6rem 0.75rem; text-align: left; border: 1px solid #e5e7eb; font-weight: 600; white-space: nowrap;">Rekening</th>
                        <th style="padding: 0.6rem 0.75rem; text-align: left; border: 1px solid #e5e7eb; font-weight: 600; white-space: nowrap;">No Pengembalian</th>
                        <th style="padding: 0.6rem 0.75rem; text-align: center; border: 1px solid #e5e7eb; font-weight: 600; white-space: nowrap;">Status Faktur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataUangMasuk as $row)
                    <tr style="{{ $loop->even ? 'background-color: #f9fafb;' : '' }}">
                        <td style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; white-space: nowrap;">
                            <strong>{{ $row->instansi }}</strong>
                        </td>
                        <td style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; word-wrap: break-word; white-space: normal; max-width: 250px;">
                            {{ $row->nama_pengadaan ?? '-' }}
                        </td>
                        <td style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; white-space: nowrap;">
                            {{ $row->nama_ppk ?? '-' }}
                        </td>
                        <td style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; text-align: right; white-space: nowrap;">
                            Rp {{ number_format($row->jumlah_include_ppn, 0, ',', '.') }}
                        </td>
                        <td style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; text-align: right; white-space: nowrap;">
                            Rp {{ number_format($row->jumlah_exclude_ppn, 0, ',', '.') }}
                        </td>
                        <td style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; text-align: right; white-space: nowrap;">
                            Rp {{ number_format($row->pph_22, 0, ',', '.') }}
                        </td>
                        <td style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; text-align: right; white-space: nowrap; font-weight: bold; color: #047857;">
                            Rp {{ number_format($row->total_diterima, 0, ',', '.') }}
                        </td>
                        <td style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; text-align: right; white-space: nowrap;">
                            Rp {{ number_format($row->total_rekening_koran, 0, ',', '.') }}
                        </td>
                        <td style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; text-align: center; white-space: nowrap;">
                            <span style="padding: 3px 10px; border-radius: 12px; font-size: 0.7rem; font-weight: 600; {{ $row->status_transfer == 'SUDAH' ? 'background: #10b981; color: white;' : 'background: #f59e0b; color: white;' }}">
                                {{ $row->status_transfer ?? 'BELUM' }}
                            </span>
                        </td>
                        <td style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; white-space: nowrap;">
                            {{ $row->tanggal_transfer ? \Carbon\Carbon::parse($row->tanggal_transfer)->translatedFormat('d F Y') : '-' }}
                        </td>
                        <td style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; white-space: nowrap;">
                            {{ $row->rekening_tujuan ?? '-' }}
                        </td>
                        <td style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; word-wrap: break-word; white-space: normal; max-width: 200px;">
                            {{ $row->status_pengembalian ?? '-' }}
                        </td>
                        <td style="padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; text-align: center; white-space: nowrap;">
                            {{ $row->status_faktur ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13" style="padding: 3rem; text-align: center; color: #6b7280; border: 1px solid #e5e7eb;">
                            Belum ada data uang masuk. Silakan import file Excel atau tambah data baru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- PAGINATION YANG SUDAH DIPERBAIKI -->
        <div style="margin-top: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; padding: 10px 0;">
            <div style="font-size: 0.85rem; color: #6b7280;">
                Menampilkan {{ $dataUangMasuk->firstItem() ?? 0 }} - {{ $dataUangMasuk->lastItem() ?? 0 }} dari {{ $dataUangMasuk->total() }} data
            </div>
            
            <div style="display: flex; align-items: center; gap: 5px; flex-wrap: wrap;">
                <!-- Tombol Previous -->
                @if($dataUangMasuk->onFirstPage())
                    <span style="padding: 6px 12px; background: #e5e7eb; color: #9ca3af; border-radius: 4px; font-size: 0.85rem; cursor: not-allowed;">Previous</span>
                @else
                    <a href="{{ $dataUangMasuk->previousPageUrl() }}" style="padding: 6px 12px; background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.85rem; text-decoration: none; transition: 0.2s;">Previous</a>
                @endif

                <!-- Nomor Halaman -->
                @php
                    $currentPage = $dataUangMasuk->currentPage();
                    $lastPage = $dataUangMasuk->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                    
                    if($lastPage > 5) {
                        if($currentPage <= 3) {
                            $start = 1;
                            $end = 5;
                        } elseif($currentPage >= $lastPage - 2) {
                            $start = $lastPage - 4;
                            $end = $lastPage;
                        }
                    }
                @endphp

                @if($start > 1)
                    <a href="{{ $dataUangMasuk->url(1) }}" style="padding: 6px 12px; background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.85rem; text-decoration: none; transition: 0.2s;">1</a>
                    @if($start > 2)
                        <span style="padding: 6px 8px; color: #6b7280; font-size: 0.85rem;">...</span>
                    @endif
                @endif

                @for($i = $start; $i <= $end; $i++)
                    @if($i == $currentPage)
                        <span style="padding: 6px 12px; background: #2563eb; color: white; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">{{ $i }}</span>
                    @else
                        <a href="{{ $dataUangMasuk->url($i) }}" style="padding: 6px 12px; background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.85rem; text-decoration: none; transition: 0.2s;">{{ $i }}</a>
                    @endif
                @endfor

                @if($end < $lastPage)
                    @if($end < $lastPage - 1)
                        <span style="padding: 6px 8px; color: #6b7280; font-size: 0.85rem;">...</span>
                    @endif
                    <a href="{{ $dataUangMasuk->url($lastPage) }}" style="padding: 6px 12px; background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.85rem; text-decoration: none; transition: 0.2s;">{{ $lastPage }}</a>
                @endif

                <!-- Tombol Next -->
                @if($dataUangMasuk->hasMorePages())
                    <a href="{{ $dataUangMasuk->nextPageUrl() }}" style="padding: 6px 12px; background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.85rem; text-decoration: none; transition: 0.2s;">Next</a>
                @else
                    <span style="padding: 6px 12px; background: #e5e7eb; color: #9ca3af; border-radius: 4px; font-size: 0.85rem; cursor: not-allowed;">Next</span>
                @endif
            </div>
        </div>
    </div>
@endsection