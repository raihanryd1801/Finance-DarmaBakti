<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;

class UangMasukImport implements WithMultipleSheets, SkipsUnknownSheets
{
    public function sheets(): array
    {
        return [
            'BANTEN' => new PemerintahSheetImport('BANTEN'),
            'ACEH' => new PemerintahSheetImport('ACEH'),
            'PADANG' => new PemerintahSheetImport('PADANG'),
            'SORONG' => new PemerintahSheetImport('SORONG'),
            'BELUM TAU SIAPA' => new PemerintahSheetImport('BELUM TAU SIAPA'),
            'SWASTA' => new SwastaSheetImport(),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // Mengabaikan lembar kerja lain yang tidak perlu di-import (seperti Rekap Keseluruhan)
    }
}