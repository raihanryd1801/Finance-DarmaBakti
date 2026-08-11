@extends('layouts.app')

@section('content')
    <div class="card" style="margin-bottom: 20px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>📄 Data Invoice Penjualan</h3>
            <a href="{{ route('invoice.create') }}" class="btn btn-primary" style="padding: 8px 16px; border-radius: 6px; text-decoration: none;">+ Buat Invoice Baru</a>
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
                        <th style="padding: 12px;">No Invoice</th>
                        <th style="padding: 12px;">Tanggal</th>
                        <th style="padding: 12px;">Pelanggan</th>
                        <th style="padding: 12px;">Grand Total</th>
                        <th style="padding: 12px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $index => $inv)
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 12px;">{{ $index + 1 }}</td>
                            <td style="padding: 12px; font-weight: bold; color: #0f172a;">{{ $inv->no_invoice }}</td>
                            <td style="padding: 12px;">{{ date('d M Y', strtotime($inv->tanggal)) }}</td>
                            <td style="padding: 12px;">{{ $inv->nama_pelanggan }}</td>
                            <td style="padding: 12px; font-weight: bold; color: #16a34a;">Rp {{ number_format($inv->grand_total, 0, ',', '.') }}</td>
                            <td style="padding: 12px; text-align: center;">
                                <a href="{{ route('invoice.print', $inv->id) }}" target="_blank" class="btn btn-primary" style="background: #4f46e5; color: white; padding: 6px 12px; font-size: 0.85rem; text-decoration: none; border-radius: 6px;">
                                    🖨️ Cetak
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #64748b;">Belum ada data invoice.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 15px;">
            {{ $invoices->links() }}
        </div>
    </div>
@endsection