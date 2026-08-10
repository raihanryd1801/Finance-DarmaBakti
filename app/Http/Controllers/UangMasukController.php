<?php

namespace App\Http\Controllers;

use App\Models\UangMasuk;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UangMasukImport;

class UangMasukController extends Controller
{
    public function index(Request $request)
    {
        // Inisiasi query
        $query = UangMasuk::query();

        // Cek jika ada request filter berdasarkan 'instansi'
        if ($request->filled('instansi')) {
            $query->where('instansi', $request->instansi);
        }

        // Cek jika ada request pencarian berdasarkan 'nama_pengadaan'
        if ($request->filled('search')) {
            $query->where('nama_pengadaan', 'like', '%' . $request->search . '%');
        }

        // Ambil data dengan pagination agar tidak berat saat data sudah ribuan
        $dataUangMasuk = $query->orderBy('tanggal_transfer', 'desc')->paginate(20);

        // Ambil list instansi unik untuk mengisi opsi Dropdown Filter di View
        $listInstansi = UangMasuk::select('instansi')
                            ->whereNotNull('instansi')
                            ->distinct()
                            ->pluck('instansi');

        return view('finance.uang_masuk.index', compact('dataUangMasuk', 'listInstansi'));
    }
    // ... method index yang tadi ...

    public function create()
    {
        return view('finance.uang_masuk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_transfer' => 'required|date',
            'instansi' => 'required|string',
            'jumlah_nominal_input' => 'required',
            'jenis_input' => 'required|in:include,exclude',
            'jenis_transfer_bank' => 'required|in:sesama,beda',
        ]);

        // 1. Bersihkan string nominal input
        $nominal_input = preg_replace('/[^0-9.]/', '', $request->jumlah_nominal_input);
        $nominal_input = $nominal_input === '' ? 0 : (float) $nominal_input;

        // 2. Bersihkan nilai biaya admin (jika ada)
        $biaya_admin = 0;
        if ($request->jenis_transfer_bank == 'beda') {
            $biaya_admin = preg_replace('/[^0-9.]/', '', $request->biaya_admin);
            $biaya_admin = $biaya_admin === '' ? 0 : (float) $biaya_admin;
        }

        // 3. Kalkulasi Dasar (Include & Exclude PPN)
        if ($request->jenis_input == 'include') {
            $include_ppn = $nominal_input;
            $exclude_ppn = $include_ppn / 1.11;
        } else {
            $exclude_ppn = $nominal_input;
            $include_ppn = $exclude_ppn * 1.11;
        }

        // 4. Hitung Simulasi Total Diterima untuk Rekening Koran
        $pph_22 = $exclude_ppn * 0.015;
        $total_diterima = $exclude_ppn - $pph_22;
        
        // Finalisasi Total Rekening Koran
        $total_rekening_koran = $total_diterima - $biaya_admin;

        // 5. Simpan ke database
        UangMasuk::create([
            'tanggal_transfer' => $request->tanggal_transfer,
            'instansi' => strtoupper($request->instansi),
            'nama_pengadaan' => $request->nama_pengadaan,
            'rekening_tujuan' => $request->rekening_tujuan,
            'jumlah_include_ppn' => $include_ppn,
            'total_rekening_koran' => $total_rekening_koran,
            'status_transfer' => 'BELUM',
            
        ]);

        return redirect()->route('uang_masuk.index')->with('success', 'Data Uang Masuk berhasil ditambah! Rekening Koran otomatis disesuaikan.');
    }
    public function import(Request $request)
{
    $request->validate([
        'file_excel' => 'required|mimes:xlsx,xls,csv'
    ]);

    Excel::import(new UangMasukImport, $request->file('file_excel'));

    return redirect()->route('uang_masuk.index')->with('success', 'Data Excel berhasil di-import!');
}
}