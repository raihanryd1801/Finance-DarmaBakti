<?php

namespace App\Http\Controllers;

use App\Models\UangMasuk;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UangMasukImport;

class UangMasukController extends Controller
{
    // --- METHOD INDEX ---
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'pemerintah');
        $query = UangMasuk::where('kategori', $kategori);

        // Filter Instansi
        if ($request->filled('instansi')) {
            $query->where('instansi', $request->instansi);
        }

        // Filter Search (Kata Kunci)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_pengadaan', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%');
            });
        }

        // BARU: Filter Rentang Tanggal Transfer
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transfer', [$request->start_date, $request->end_date]);
        }

        $dataUangMasuk = $query->orderBy('tanggal_transfer', 'desc')->paginate(20);
        $listInstansi = UangMasuk::where('kategori', $kategori)->select('instansi')->distinct()->pluck('instansi');

        return view('finance.uang_masuk.index', compact('dataUangMasuk', 'listInstansi', 'kategori'));
    }

    // --- METHOD CREATE ---
    public function create()
    {
        return view('finance.uang_masuk.create');
    }

    // --- METHOD STORE ---
    public function store(Request $request)
    {
        $nominal = preg_replace('/[^0-9.]/', '', $request->jumlah_nominal_input);
        $nominal = $nominal === '' ? 0 : (float) $nominal;
        
        $biaya_admin = $request->jenis_transfer_bank == 'beda' ? (float) preg_replace('/[^0-9.]/', '', $request->biaya_admin) : 0;
        
        $include_ppn = 0; $exclude_ppn = 0; $ppn = 0; $pph_22 = 0;

        if ($request->has('potong_ppn')) {
            $include_ppn = $nominal;
            $exclude_ppn = $include_ppn / 1.11;
            $ppn = $include_ppn - $exclude_ppn;
        } else {
            $include_ppn = $nominal;
            $exclude_ppn = $nominal;
        }

        if ($request->has('potong_pph')) {
            $pph_22 = $exclude_ppn * 0.015;
        }

        $total_diterima = $exclude_ppn - $pph_22;
        $total_rekening_koran = $total_diterima - $biaya_admin;
        $selisih = $total_rekening_koran - $total_diterima;

        UangMasuk::create([
            'tanggal_transfer' => $request->tanggal_transfer,
            'kategori' => $request->kategori,
            'instansi' => strtoupper($request->instansi),
            'nama_pengadaan' => $request->nama_pengadaan,
            'keterangan' => $request->keterangan,
            'rekening_tujuan' => $request->rekening_tujuan,
            'status_transfer' => $request->status_transfer,
            'jumlah_include_ppn' => $include_ppn,
            'jumlah_exclude_ppn' => $exclude_ppn,
            'ppn' => $ppn,
            'pph_22' => $pph_22,
            'total_diterima' => $total_diterima,
            'total_rekening_koran' => $total_rekening_koran,
            'selisih' => $selisih,
            'status_transfer' => 'BELUM',
        ]);

        return redirect()->route('uang_masuk.index', ['kategori' => $request->kategori])
                         ->with('success', 'Data ' . ucfirst($request->kategori) . ' berhasil ditambahkan!');
    }

    // --- METHOD IMPORT ---
    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new UangMasukImport, $request->file('file_excel'));

        return redirect()->route('uang_masuk.index')->with('success', 'Data Excel Pemerintah & Swasta berhasil di-import sekaligus!');
    }

    // --- METHOD REPORT YANG SUDAH BERSIH DARI HTML ---
    public function report(Request $request)
    {
        $query = UangMasuk::query();
        $rekapQuery = UangMasuk::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transfer', [$request->start_date, $request->end_date]);
            $rekapQuery->whereBetween('tanggal_transfer', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('instansi')) {
            $query->where('instansi', $request->instansi);
            $rekapQuery->where('instansi', $request->instansi);
        }

        $totalIncludePpn = $query->sum('jumlah_include_ppn');
        $totalExcludePpn = $query->sum('jumlah_exclude_ppn');
        $totalPpn = $query->sum('ppn');
        $totalPph22 = $query->sum('pph_22');
        $totalDiterima = $query->sum('total_diterima');
        $totalRekeningKoran = $query->sum('total_rekening_koran');

        $rekapPerInstansi = $rekapQuery->select('instansi')
            ->selectRaw('count(*) as total_transaksi')
            ->selectRaw('sum(jumlah_include_ppn) as sum_include')
            ->selectRaw('sum(pph_22) as sum_pph')
            ->selectRaw('sum(total_diterima) as sum_diterima')
            ->groupBy('instansi')
            ->get();

        $listInstansi = UangMasuk::select('instansi')->whereNotNull('instansi')->distinct()->pluck('instansi');

        // PASTIKAN NAMA FILE DAN FOLDER INI TEPAT DIBUAT (resources/views/finance/report.blade.php)
        return view('finance.report', compact(
            'totalIncludePpn', 
            'totalExcludePpn', 
            'totalPpn', 
            'totalPph22', 
            'totalDiterima', 
            'totalRekeningKoran', 
            'rekapPerInstansi',
            'listInstansi'
        ));
    }
    // --- METHOD EDIT ---
    public function edit($id)
    {
        $data = UangMasuk::findOrFail($id);
        return view('finance.uang_masuk.edit', compact('data'));
    }

    // --- METHOD UPDATE ---
    public function update(Request $request, $id)
    {
        $uangMasuk = UangMasuk::findOrFail($id);

        // 1. Bersihkan input
        $nominal = preg_replace('/[^0-9.]/', '', $request->jumlah_nominal_input);
        $nominal = $nominal === '' ? 0 : (float) $nominal;
        
        $biaya_admin = $request->jenis_transfer_bank == 'beda' ? (float) preg_replace('/[^0-9.]/', '', $request->biaya_admin) : 0;
        
        $include_ppn = 0; $exclude_ppn = 0; $ppn = 0; $pph_22 = 0;

        // 2. LOGIKA CENTANG PPN (11%)
        if ($request->has('potong_ppn')) {
            $include_ppn = $nominal;
            $exclude_ppn = $include_ppn / 1.11;
            $ppn = $include_ppn - $exclude_ppn;
        } else {
            $include_ppn = $nominal;
            $exclude_ppn = $nominal;
        }

        // 3. LOGIKA CENTANG PPH 22 (1.5%)
        if ($request->has('potong_pph')) {
            $pph_22 = $exclude_ppn * 0.015;
        }

        // 4. Hitung Akhir
        $total_diterima = $exclude_ppn - $pph_22;
        $total_rekening_koran = $total_diterima - $biaya_admin;
        $selisih = $total_rekening_koran - $total_diterima;

        // 5. Update ke Database
        $uangMasuk->update([
            'tanggal_transfer' => $request->tanggal_transfer,
            'kategori' => $request->kategori,
            'instansi' => strtoupper($request->instansi),
            'nama_pengadaan' => $request->nama_pengadaan,
            'keterangan' => $request->keterangan,
            'rekening_tujuan' => $request->rekening_tujuan,
            'status_transfer' => $request->status_transfer,
            'jumlah_include_ppn' => $include_ppn,
            'jumlah_exclude_ppn' => $exclude_ppn,
            'ppn' => $ppn,
            'pph_22' => $pph_22,
            'total_diterima' => $total_diterima,
            'total_rekening_koran' => $total_rekening_koran,
            'selisih' => $selisih,
        ]);

        return redirect()->route('uang_masuk.index', ['kategori' => $request->kategori])
                         ->with('success', 'Data berhasil diperbarui!');
    }

    // --- METHOD DELETE ---
    public function destroy($id)
    {
        $uangMasuk = UangMasuk::findOrFail($id);
        $kategori = $uangMasuk->kategori; // Simpan kategori sebelum dihapus buat redirect
        
        $uangMasuk->delete();
        
        return redirect()->route('uang_masuk.index', ['kategori' => $kategori])
                         ->with('success', 'Data berhasil dihapus permanen!');
    }
}