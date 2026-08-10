<?php
namespace App\Imports;

use App\Models\UangMasuk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class SwastaSheetImport implements ToModel, WithHeadingRow
{
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

        $includePpn = $this->cleanNumber($row['jumlah_transfer_include_ppn'] ?? 0);
        $excludePpn = $this->cleanNumber($row['total_exclude_ppn'] ?? 0);
        if ($excludePpn <= 0 && $includePpn > 0) {
            $excludePpn = $includePpn / 1.11;
        }

        $ppn = $this->cleanNumber($row['ppn'] ?? 0);
        if ($ppn <= 0 && $includePpn > 0) {
            $ppn = $includePpn - $excludePpn;
        }

        return new UangMasuk([
            'kategori' => 'swasta',
            'instansi' => strtoupper($row['instansi'] ?? $row['keterangan'] ?? 'SWASTA'),
            'tanggal_transfer' => $tanggal,
            'jumlah_include_ppn' => $includePpn,
            'jumlah_exclude_ppn' => $excludePpn,
            'ppn' => $ppn,
            'pph_22' => 0,
            'total_diterima' => $excludePpn,
            'total_rekening_koran' => $excludePpn,
            'nilai_nota' => $this->cleanNumber($row['nilai_nota'] ?? 0),
            'selisih' => $this->cleanNumber($row['selisih'] ?? 0),
            'rekening_tujuan' => $row['rekening'] ?? 'DARMA',
            'keterangan' => $row['keterangan'] ?? null,
            'status_transfer' => 'SUDAH',
        ]);
    }
}