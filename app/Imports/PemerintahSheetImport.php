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
        // 1. TANGKAP SEMUA NAMA KOLOM TANGGAL
        $tanggalRaw = $row['tanggal_tf_dokumen'] ?? $row['tanggal_tf_rek'] ?? $row['tanggal_transfer'] ?? $row['tanggal'] ?? $row['tgl_transfer'] ?? $row['tgl'] ?? null;

        if (empty($tanggalRaw)) {
            return null;
        }

        $tanggal = $tanggalRaw;
        
        if (is_numeric($tanggal)) {
            try {
                $tanggal = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal))->format('Y-m-d');
            } catch (\Exception $e) {
                $tanggal = now()->format('Y-m-d');
            }
        }

        // 2. Ambil Nominal Transfer / Rekening Koran sebagai basis hitung mundur (/ 0.985)
        $jumlahTransfer = $this->cleanNumber($row['total_rekening_koran'] ?? $row['jumlah_transfer'] ?? 0);
        $totalRekeningKoran = $jumlahTransfer;

        // 3. RUMUS HITUNG MUNDUR PEMERINTAH (Sesuai Excel)
        $excludePpn = $jumlahTransfer > 0 ? ($jumlahTransfer / 0.985) : 0;
        $pph22      = $excludePpn * 0.015;
        $ppn        = $excludePpn * 0.11;
        $includePpn = $excludePpn + $ppn;

        // Total Diterima (Bersih kurang PPh 22)
        $totalDiterima = $excludePpn - $pph22;

        // Total Bruto (Sesuai rumus excel: Diterima + PPN + PPh 22)
        $totalBruto = $totalDiterima + $ppn + $pph22;

        // 4. Ambil Nilai Dokumen / Nota dari Excel (Jika ada)
        $nilaiDokumen = $this->cleanNumber($row['nilai_dokumen'] ?? $row['jumlah_include_ppn'] ?? 0);

        // 5. Hitung Selisih (Total Bruto terhadap Nilai Dokumen)
        if ($nilaiDokumen > 0) {
            $selisih = $totalBruto - $nilaiDokumen;
        } else {
            $selisih = $this->cleanNumber($row['selisih'] ?? 0);
        }

        // 6. Tentukan Instansi
        $namaInstansi = !empty($row['instansi']) ? $row['instansi'] : $this->instansi;

        return new UangMasuk([
            'kategori'             => 'pemerintah',
            'instansi'             => strtoupper($namaInstansi),
            'tanggal_transfer'     => $tanggal,
            'nama_pengadaan'       => $row['nama_pengadaan'] ?? null,
            'nama_ppk'             => $row['nama_ppk'] ?? null,
            'jumlah_include_ppn'   => $includePpn,
            'jumlah_exclude_ppn'   => $excludePpn,
            'ppn'                  => $ppn,
            'pph_22'               => $pph22,
            'total_diterima'       => $totalDiterima,
            'total_rekening_koran' => $totalRekeningKoran,
            'nilai_nota'           => $nilaiDokumen > 0 ? $nilaiDokumen : null,
            'rekening_tujuan'      => $row['rekening'] ?? 'DARMA',
            'status_pengembalian'  => $row['no_pengembalian'] ?? null,
            'selisih'              => $selisih,
            'status_transfer'      => 'SUDAH',
        ]);
    }
}