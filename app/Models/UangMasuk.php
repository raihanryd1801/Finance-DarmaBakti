<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UangMasuk extends Model
{
    use HasFactory;

    protected $table = 'uang_masuk';
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::saving(function ($model) {
            
            // BERSILAT LIDAH DENGAN STRING:
            // Pastikan nilai yang masuk diubah dulu jadi angka murni
            // Hilangkan semua huruf, koma, spasi, atau Rp jika tidak sengaja terbawa dari Excel
            $nilai_kotor = preg_replace('/[^0-9.]/', '', $model->jumlah_include_ppn);
            
            // Ubah string kosong jadi 0 agar tidak error pembagian
            $nilai_kotor = $nilai_kotor === '' ? 0 : (float) $nilai_kotor;
            
            // Simpan kembali nilai yang sudah bersih ke dalam model
            $model->jumlah_include_ppn = $nilai_kotor;

            // Jika ada nilai uang masuk yang disubmit
            if ($model->jumlah_include_ppn > 0) {
                // 1. Hitung Exclude PPN (Asumsi PPN 11%)
                $model->jumlah_exclude_ppn = $model->jumlah_include_ppn / 1.11;
                
                // 2. Hitung Nilai PPN murni
                $model->ppn = $model->jumlah_include_ppn - $model->jumlah_exclude_ppn;
                
                // 3. Hitung Potongan PPh 22 (1.5%)
                $model->pph_22 = $model->jumlah_exclude_ppn * 0.015;
                
                // 4. Hitung Total Bersih yang Diterima
                $model->total_diterima = $model->jumlah_exclude_ppn - $model->pph_22;
                
                // BERSILAT LIDAH REKENING KORAN JUGA:
                if ($model->total_rekening_koran) {
                    $rek_koran = preg_replace('/[^0-9.]/', '', $model->total_rekening_koran);
                    $rek_koran = $rek_koran === '' ? 0 : (float) $rek_koran;
                    $model->total_rekening_koran = $rek_koran;

                    $model->selisih = $model->total_rekening_koran - $model->total_diterima;
                }
            }
        });
    }
}