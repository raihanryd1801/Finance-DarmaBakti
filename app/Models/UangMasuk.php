<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice; // <-- WAJIB ADA INI DI ATAS

class UangMasuk extends Model
{
    use HasFactory;

    protected $table = 'uang_masuk';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_transfer' => 'date',
        'jumlah_include_ppn' => 'float',
        'jumlah_exclude_ppn' => 'float',
        'ppn' => 'float',
        'pph_22' => 'float',
        'total_diterima' => 'float',
        'total_rekening_koran' => 'float',
        'selisih' => 'float',
        'nilai_nota' => 'float',
    ];

    // --- FUNGSI PENGAMAN HUMAN ERROR (SINKRONISASI OTOMATIS) ---
    protected static function booted()
    {
        parent::booted();

        static::deleting(function ($uangMasuk) {
            // Cari nomor invoice yang tersimpan di nama pengadaan
            if (str_contains($uangMasuk->nama_pengadaan, 'INV-DBM/')) {
                // Ambil nomor invoice dari string
                preg_match('/INV-DBM\/[\d\-\/]+/', $uangMasuk->nama_pengadaan, $matches);
                
                if (!empty($matches[0])) {
                    $noInvoice = $matches[0];
                    
                    // Cari invoice tersebut lalu kembalikan statusnya jadi "Belum Lunas"
                    $invoice = Invoice::where('no_invoice', $noInvoice)->first();
                    if ($invoice) {
                        $invoice->update([
                            'status_pembayaran' => 'Belum Lunas'
                        ]);
                    }
                }
            }
        });
    }
}