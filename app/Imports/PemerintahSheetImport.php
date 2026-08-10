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

        // 1. Ambil uang fisik (Netto) yang mendarat di Bank
        $jumlahTransfer = $this->cleanNumber($row['jumlah_transfer'] ?? 0);
        $totalDiterima = $jumlahTransfer;
        $totalRekeningKoran = $jumlahTransfer;

        // 2. Ambil Nilai Dokumen Asli
        $nilaiDokumen = $this->cleanNumber($row['nilai_dokumen'] ?? 0);

        // 3. Ambil Gross Invoice dari Excel kolom 'Total'. 
        // Jika rumusnya error/0, kita anggap sama dengan nilai dokumen
        $totalExcel = $this->cleanNumber($row['total'] ?? 0);
        if ($totalExcel <= 0) {
            $totalExcel = $nilaiDokumen > 0 ? $nilaiDokumen : $jumlahTransfer;
        }
        $includePpn = $totalExcel;

        // 4. Hitung Pajak (PPN & PPh 22)
        $excludePpn = $this->cleanNumber($row['jumlah_kurang_pph_22'] ?? 0);
        if ($excludePpn <= 0 && $includePpn > 0) {
            $excludePpn = $includePpn / 1.11;
        }

        $ppn = $this->cleanNumber($row['ppn'] ?? 0);
        if ($ppn <= 0 && $includePpn > 0) {
            $ppn = $includePpn - $excludePpn;
        }

        // Cek berbagai kemungkinan nama header PPh 22 di Excel
        $pph22 = $this->cleanNumber(
            $row['pph_22'] ?? 
            $row['pph22'] ?? 
            $row['pajak_pph_22'] ?? 
            0
        );

        // FALLBACK OTOMATIS: Jika PPh 22 di Excel kosong/0, hitung otomatis 1.5% dari Exclude PPN
        if ($pph22 <= 0 && $excludePpn > 0) {
            $pph22 = $excludePpn * 0.015;
        }

        // 5. RUMUS SELISIH PERSIS SEPERTI EXCEL KLIEN (Total - Nilai Dokumen)
        // 5. RUMUS SELISIH YANG BENAR: Total Rekening Koran - Nilai Dokumen
        if ($nilaiDokumen > 0) {
            $selisih = $totalRekeningKoran - $nilaiDokumen;
        } else {
            $selisih = $this->cleanNumber($row['selisih'] ?? 0);
        }

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
            'nilai_nota' => $nilaiDokumen > 0 ? $nilaiDokumen : null,
            'rekening_tujuan' => $row['rekening'] ?? 'DARMA',
            'status_pengembalian' => $row['no_pengembalian'] ?? null,
            'selisih' => $selisih,
            'status_transfer' => 'SUDAH',
        ]);
    }
}