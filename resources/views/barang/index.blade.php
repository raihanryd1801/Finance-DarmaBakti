@extends('layouts.app')

@section('content')
    <div class="card" style="padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>📦 Master Data Barang</h3>
            <a href="{{ route('barang.create') }}" class="btn btn-primary" style="padding: 8px 16px; border-radius: 6px; text-decoration: none;">+ Tambah Barang</a>
        </div>

        @if(session('success'))
            <div style="background: #d1fae5; color: #065f46; padding: 10px 15px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #a7f3d0;">
                {{ session('success') }}
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; text-align: left; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px;">No</th>
                        <th style="padding: 12px;">Kode</th>
                        <th style="padding: 12px;">Nama Barang</th>
                        <th style="padding: 12px;">Satuan</th>
                        <th style="padding: 12px;">Harga</th>
                        <th style="padding: 12px;">Keterangan</th>
                        <th style="padding: 12px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $index => $item)
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 12px;">{{ $index + 1 }}</td>
                            <td style="padding: 12px; font-weight: bold;">{{ $item->kode_barang }}</td>
                            <td style="padding: 12px;">{{ $item->nama_barang }}</td>
                            <td style="padding: 12px;">{{ $item->satuan }}</td>
                            <td style="padding: 12px; font-weight: bold; color: #16a34a;">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td style="padding: 12px;">{{ $item->keterangan ?? '-' }}</td>
                            <td style="padding: 12px; text-align: center;">
                                <div style="display: flex; gap: 5px; justify-content: center;">
                                    <a href="{{ route('barang.edit', $item->id) }}" class="btn" style="background: #eab308; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 13px;">✏️ Edit</a>
                                    
                                    <form action="{{ route('barang.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                                        @csrf
                                        <button type="submit" class="btn" style="background: #ef4444; color: white; padding: 5px 10px; border: none; border-radius: 4px; font-size: 13px; cursor: pointer;">🗑️ Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: #64748b;">Belum ada data barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 15px;">
            {{ $barangs->links() }}
        </div>
    </div>
@endsection