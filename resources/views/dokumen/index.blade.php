@extends('layouts.app')

@section('content')
    <div class="card" style="margin-bottom: 20px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>
                <!-- Ikon folder SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px; color: #f59e0b;">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                </svg>
                Data Dokumen dari Server Pusat (API)
            </h3>
        </div>

        <!-- FORM FILTER -->
        <form action="{{ route('dokumen-api.index') }}" method="GET" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
            
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px; color: #475569;">Pilih Kategori</label>
                <select name="kategori" class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; background: white;">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($listKategori as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                            {{ $kat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px; color: #475569;">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
            </div>

            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px; color: #475569;">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary" style="padding: 9px 18px; cursor: pointer; border: none; border-radius: 6px; font-weight: 600; background: #2563eb; color: white; display: inline-flex; align-items: center; gap: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('dokumen-api.index') }}" class="btn" style="padding: 9px 18px; background: #64748b; color: white; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; font-weight: 600;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="1 4 1 10 7 10"/>
                        <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                    </svg>
                    Reset
                </a>
            </div>
        </form>

        <!-- Notifikasi Error -->
        @if(session('error'))
            <div style="background: #fee2e2; color: #991b1b; padding: 10px 15px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #fecaca;">
                {{ session('error') }}
            </div>
        @endif

        <!-- TABEL -->
        <div style="overflow-x: auto;">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; text-align: left; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px;">No</th>
                        <th style="padding: 12px;">Judul / Nama Dokumen</th>
                        <th style="padding: 12px;">Perusahaan / Kategori</th>
                        <th style="padding: 12px;">Tanggal Dokumen</th>
                        <th style="padding: 12px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokumen as $index => $item)
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 12px;">{{ $index + 1 }}</td>
                            <td style="padding: 12px; font-weight: 500;">
                                {{ $item['judul'] ?? $item['nama_dokumen'] ?? $item['keterangan'] ?? 'Dokumen #' . $item['id'] }}
                            </td>
                            <td style="padding: 12px; color: #64748b;">
                                {{ $item['perusahaan'] ?? '-' }} 
                                <span style="font-size: 0.8rem; background: #eef2ff; color: #4f46e5; padding: 2px 8px; border-radius: 10px; margin-left: 5px;">
                                    {{ $item['kategori'] ?? '-' }}
                                </span>
                            </td>
                            <td style="padding: 12px;">
                                {{ $item['tanggal_dokumen'] ?? (isset($item['created_at']) ? date('d M Y', strtotime($item['created_at'])) : '-') }}
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                    @php
                                        $filePath = $item['file_path'] ?? '';
                                        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                        $canPreview = in_array($ext, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp

                                    @if($canPreview)
                                        <a href="{{ route('dokumen-api.preview', $item['id']) }}" target="_blank" 
                                           class="btn btn-sm" 
                                           style="background: #0284c7; color: white; padding: 6px 10px; font-size: 0.85rem; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; gap: 4px; border: none; cursor: pointer; min-width: 80px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                            Lihat
                                        </a>
                                    @endif

                                    <a href="{{ route('dokumen-api.download', $item['id']) }}" 
                                       class="btn btn-sm" 
                                       style="background: #2563eb; color: white; padding: 6px 10px; font-size: 0.85rem; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; gap: 4px; border: none; cursor: pointer; min-width: 80px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                            <polyline points="7 10 12 15 17 10"/>
                                            <line x1="12" y1="15" x2="12" y2="3"/>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: #64748b;">
                                Tidak ada data dokumen yang ditemukan sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection