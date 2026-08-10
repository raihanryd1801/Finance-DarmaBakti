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
        // 1. TANGKAP SEMUA NAMA KOLOM TANGGAL (Termasuk tanggal_tf_dokumen)
        $tanggalRaw = $row['tanggal_tf_dokumen'] ?? $row['tanggal_tf_rek'] ?? $row['tanggal_transfer'] ?? $row['tanggal'] ?? $row['tgl_transfer'] ?? $row['tgl'] ?? null;

        // Skip jika benar-benar kosong
        if (empty($tanggalRaw)) {
            return null;
        }

        $tanggal = $tanggalRaw;
        
        // Konversi jika format tanggalnya berupa angka seri Excel (contoh: 46059)
        if (is_numeric($tanggal)) {
            try {
                $tanggal = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal))->format('Y-m-d');
            } catch (\Exception $e) {
                $tanggal = now()->format('Y-m-d');
            }
        }

        // 2. Ambil uang fisik (Netto) dari total rekening koran / jumlah transfer
        $jumlahTransfer = $this->cleanNumber($row['total_rekening_koran'] ?? $row['jumlah_transfer'] ?? 0);
        $totalDiterima = $jumlahTransfer;
        $totalRekeningKoran = $jumlahTransfer;

        // 3. Ambil Nilai Dokumen Asli / Jumlah Include PPN
        $nilaiDokumen = $this->cleanNumber($row['nilai_dokumen'] ?? $row['jumlah_include_ppn'] ?? 0);

        // 4. Hitung Pajak (Otomatis ditangani Fallback jika dari Excel berupa string rumus seperti =F2/$P$1)
        $includePpn = $nilaiDokumen > 0 ? $nilaiDokumen : $jumlahTransfer;

        $excludePpn = $this->cleanNumber($row['jumlah_exclude_ppn'] ?? $row['jumlah_kurang_pph_22'] ?? 0);
        if ($excludePpn <= 0 && $includePpn > 0) {
            $excludePpn = $includePpn / 1.11;
        }

        $ppn = $this->cleanNumber($row['ppn'] ?? 0);
        if ($ppn <= 0 && $includePpn > 0) {
            $ppn = $includePpn - $excludePpn;
        }

        $pph22 = $this->cleanNumber($row['pph_22'] ?? $row['pph22'] ?? $row['pajak_pph_22'] ?? 0);
        if ($pph22 <= 0 && $excludePpn > 0) {
            $pph22 = $excludePpn * 0.015;
        }

        // 5. Hitung Selisih
        if ($nilaiDokumen > 0) {
            $selisih = $totalRekeningKoran - $nilaiDokumen;
        } else {
            $selisih = $this->cleanNumber($row['selisih'] ?? 0);
        }

        // 6. Tentukan Instansi (Prioritas dari kolom tabel, kalau kosong pakai parameter construct)
        $namaInstansi = !empty($row['instansi']) ? $row['instansi'] : $this->instansi;

        return new UangMasuk([
            'kategori' => 'pemerintah',
            'instansi' => strtoupper($namaInstansi),
            'tanggal_transfer' => $tanggal,
            'nama_pengadaan' => $row['nama_pengadaan'] ?? null,
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