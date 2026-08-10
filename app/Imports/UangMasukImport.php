<?php

namespace App\Imports;

use App\Models\UangMasuk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class UangMasukImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Skip jika datanya kosong (misal baris kosong di Excel)
        if (!isset($row['tanggal_tf_dokumen']) || !isset($row['jumlah_include_ppn'])) {
            return null;
        }

        // Konversi format tanggal dari Excel ke format Y-m-d untuk MySQL
        // Biasanya Excel menyimpan tanggal dalam bentuk integer (serial number)
        $tanggal_transfer = is_numeric($row['tanggal_tf_dokumen']) 
            ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_tf_dokumen'])->format('Y-m-d')
            : Carbon::parse($row['tanggal_tf_dokumen'])->format('Y-m-d');

        return new UangMasuk([
            'tanggal_transfer'   => $tanggal_transfer,
            'instansi'           => $row['instansi'] ?? 'TIDAK DIKETAHUI',
            'nama_pengadaan'     => $row['nama_pengadaan'] ?? null,
            'nama_ppk'           => $row['nama_ppk'] ?? null,
            'jumlah_include_ppn' => $row['jumlah_include_ppn'],
            // Exclude, PPN, dan PPh 22 tidak perlu dimasukkan ke sini 
            // karena model otomatis menghitungnya (Event saving yang kita buat sebelumnya)
            'total_rekening_koran' => $row['total_rekening_koran'] ?? null,
            'status_transfer'    => $row['sudah_tfbelum_tf'] ?? 'BELUM',
            'rekening_tujuan'    => $row['rekening'] ?? null,
            'status_pengembalian'=> $row['no_pengembalian'] ?? null,
            'status_faktur'      => $row['sudah_buat_faktur'] ?? null,
        ]);
    }
}
