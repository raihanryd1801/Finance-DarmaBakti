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
        // Validasi input dasar
        $request->validate([
            'tanggal_transfer' => 'required|date',
            'instansi' => 'required|string',
            'jumlah_include_ppn' => 'required|numeric|min:0',
        ]);

        // Simpan ke database (Rumus matematika akan otomatis berjalan di Model)
        UangMasuk::create([
            'tanggal_transfer' => $request->tanggal_transfer,
            'instansi' => strtoupper($request->instansi),
            'nama_pengadaan' => $request->nama_pengadaan,
            'nama_ppk' => $request->nama_ppk,
            'jumlah_include_ppn' => $request->jumlah_include_ppn,
            'status_transfer' => $request->status_transfer ?? 'BELUM',
            'rekening_tujuan' => $request->rekening_tujuan,
        ]);

        return redirect()->route('uang_masuk.index')->with('success', 'Data Uang Masuk berhasil ditambahkan dan dikalkulasi otomatis!');
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