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
                        <th style="padding: 12px; text-align: center;">Status</th>
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
                            
                            <!-- 1. KOLOM STATUS YANG TADI HILANG -->
                            <td style="padding: 12px; text-align: center;">
                                @if($inv->status_pembayaran == 'Lunas')
                                    <span style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">LUNAS</span>
                                @else
                                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">BELUM LUNAS</span>
                                @endif
                            </td>

                            <!-- 2. KOLOM AKSI (SUDAH DIRAPIKAN, TIDAK DOBEL) -->
                            <td style="padding: 12px; text-align: center;">
                                <div style="display: flex; gap: 5px; justify-content: center;">
                                    <a href="{{ route('invoice.print', $inv->id) }}" target="_blank" class="btn btn-primary" style="background: #4f46e5; color: white; padding: 6px 10px; font-size: 0.85rem; text-decoration: none; border-radius: 6px;">
                                        🖨️ Cetak
                                    </a>
                                    
                                    @if($inv->status_pembayaran != 'Lunas')
                                        <a href="{{ route('invoice.edit', $inv->id) }}" class="btn" style="background: #eab308; color: white; padding: 6px 10px; font-size: 0.85rem; text-decoration: none; border-radius: 6px;">
                                            ✏️ Edit
                                        </a>

                                        <!-- DROPDOWN / PILIHAN PELUNASAN -->
                                        <div style="position: relative; display: inline-block;">
                                            <button type="button" onclick="toggleDropdown({{ $inv->id }})" class="btn" style="background: #10b981; color: white; border: none; padding: 6px 10px; font-size: 0.85rem; border-radius: 6px; cursor: pointer;">
                                                ✅ Lunas ▾
                                            </button>
                                            
                                            <div id="dropdown_{{ $inv->id }}" style="display: none; position: absolute; right: 0; background: white; min-width: 160px; box-shadow: 0px 8px 16px rgba(0,0,0,0.2); z-index: 10; border-radius: 6px; overflow: hidden; border: 1px solid #cbd5e1;">
                                                
                                                <!-- Opsi Swasta -->
                                                <form action="{{ route('invoice.lunas', [$inv->id, 'swasta']) }}" method="POST" onsubmit="return confirm('Kirim pembayaran ini ke Uang Masuk SWASTA?');">
                                                    @csrf
                                                    <button type="submit" style="width: 100%; text-align: left; padding: 10px; background: none; border: none; cursor: pointer; font-size: 13px; border-bottom: 1px solid #f1f5f9;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='none'">
                                                        🏢 Masuk Swasta
                                                    </button>
                                                </form>

                                                <!-- Opsi Pemerintah -->
                                                <form action="{{ route('invoice.lunas', [$inv->id, 'pemerintah']) }}" method="POST" onsubmit="return confirm('Kirim pembayaran ini ke Uang Masuk PEMERINTAH (Otomatis hitung PPN & PPh 22)?');">
                                                    @csrf
                                                    <button type="submit" style="width: 100%; text-align: left; padding: 10px; background: none; border: none; cursor: pointer; font-size: 13px; color: #0284c7; font-weight: bold;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='none'">
                                                        🏛️ Masuk Pemerintah
                                                    </button>
                                                </form>
                                                
                                            </div>
                                        </div>

                                        <form action="{{ route('invoice.destroy', $inv->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus Invoice ini permanen?');" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn" style="background: #ef4444; color: white; border: none; padding: 6px 10px; font-size: 0.85rem; border-radius: 6px; cursor: pointer;">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: #64748b;">Belum ada data invoice.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 15px;">
            {{ $invoices->links() }}
        </div>
    </div>

    <script>
    function toggleDropdown(id) {
        let dropdown = document.getElementById('dropdown_' + id);
        // Tutup semua dropdown lain dulu biar nggak numpuk
        document.querySelectorAll('[id^="dropdown_"]').forEach(el => {
            if(el.id !== 'dropdown_' + id) el.style.display = 'none';
        });
        // Toggle menu yang dipilih
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    // Klik di luar dropdown bakal nutup sendiri
    window.onclick = function(event) {
        if (!event.target.matches('button')) {
            document.querySelectorAll('[id^="dropdown_"]').forEach(el => {
                el.style.display = 'none';
            });
        }
    }
</script>
@endsection