<?php
namespace App\Imports;

use App\Models\UangMasuk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class PemerintahSheetImport implements ToModel, WithHeadingRow
{
    protected $instansi;

    public function __construct($instansi)
    {
        $this->instansi = $instansi;
    }

    private function cleanNumber($val)
    {
        // Jika nilai mengandung huruf atau format rumus Excel (seperti Table42), kembalikan 0
        if (is_string($val) && (preg_match('/[a-zA-Z]/', $val) || str_contains($val, 'Table'))) {
            return 0;
        }
        $clean = preg_replace('/[^0-9.-]/', '', (string)$val);
        return $clean === '' ? 0 : (float) $clean;
    }

    public function model(array $row)
    {
        if (!isset($row['tanggal_transfer']) || empty($row['tanggal_transfer'])) {
            return null;
        }

        $tanggal = $row['tanggal_transfer'];
        if (is_numeric($tanggal)) {
            try {
                $tanggal = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal))->format('Y-m-d');
            } catch (\Exception $e) {
                $tanggal = now()->format('Y-m-d');
            }
        }

        // Ambil nilai mentah
        $includePpn = $this->cleanNumber($row['nilai_dokumen'] ?? 0);
        if ($includePpn <= 0) {
            $includePpn = $this->cleanNumber($row['jumlah_transfer'] ?? 0);
        }

        $pph22 = $this->cleanNumber($row['pph_22'] ?? 0);
        $excludePpn = $this->cleanNumber($row['jumlah_kurang_pph_22'] ?? 0);
        
        // Jika exclude PPN gagal dibaca karena rumus Excel, hitung otomatis dari Include PPN
        if ($excludePpn <= 0 && $includePpn > 0) {
            $excludePpn = $includePpn / 1.11;
        }

        $ppn = $this->cleanNumber($row['ppn'] ?? 0);
        if ($ppn <= 0 && $includePpn > 0) {
            $ppn = $includePpn - $excludePpn;
        }

        // Jika PPh 22 tidak terbaca atau 0 padahal ada exclude PPN, hitung otomatis 1.5%
        if ($pph22 <= 0 && $excludePpn > 0) {
            $pph22 = $excludePpn * 0.015;
        }

        $totalDiterima = $this->cleanNumber($row['jumlah_transfer'] ?? 0);
        if ($totalDiterima <= 0) {
            $totalDiterima = $excludePpn - $pph22;
        }

        $totalRekeningKoran = $this->cleanNumber($row['total'] ?? $totalDiterima);
        $selisih = $this->cleanNumber($row['selisih'] ?? 0);

        return new UangMasuk([
            'kategori' => 'pemerintah',
            'instansi' => strtoupper($this->instansi),
            'tanggal_transfer' => $tanggal,
            'nama_ppk' => $row['nama_ppk'] ?? null,
            'jumlah_include_ppn' => $includePpn,
            'jumlah_exclude_ppn' => $excludePpn,
            'ppn' => $ppn,
            'pph_22' => $pph22,
            'total_diterima' => $totalDiterima,
            'total_rekening_koran' => $totalRekeningKoran,
            'rekening_tujuan' => $row['rekening'] ?? 'DARMA',
            'status_pengembalian' => $row['no_pengembalian'] ?? null,
            'selisih' => $selisih,
            'status_transfer' => 'SUDAH',
        ]);
    }
}