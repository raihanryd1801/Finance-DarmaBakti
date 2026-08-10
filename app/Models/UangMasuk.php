<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UangMasuk extends Model
{
    use HasFactory;

    protected $table = 'uang_masuk';
    protected $guarded = ['id'];

    // TAMBAHKAN INI:
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
}