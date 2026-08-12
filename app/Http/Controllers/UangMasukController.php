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

        if ($request->filled('instansi')) {
            $query->where('instansi', $request->instansi);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_pengadaan', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%');
            });
        }

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
        // 1. Bersihkan Input dari Rp, Titik, dan Spasi
        $nominal      = (float) preg_replace('/[^0-9]/', '', $request->jumlah_nominal_input ?? '0');
        $biaya_admin  = (float) preg_replace('/[^0-9]/', '', $request->biaya_admin ?? '0');
        $nilai_nota   = (float) preg_replace('/[^0-9]/', '', $request->nilai_nota ?? '0');

        $include_ppn = 0; $exclude_ppn = 0; $ppn = 0; $pph_22 = 0;

        // 2. LOGIKA PERHITUNGAN BERDASARKAN KATEGORI
        if ($request->kategori == 'pemerintah') {
            // RUMUS PEMERINTAH (Hitung Mundur / 0.985)
            if ($request->has('potong_pph')) {
                $exclude_ppn = $nominal / 0.985;
                $pph_22      = $exclude_ppn * 0.015;
            } else {
                $exclude_ppn = $nominal;
                $pph_22      = 0;
            }

            if ($request->has('potong_ppn')) {
                $ppn         = $exclude_ppn * 0.11;
                $include_ppn = $exclude_ppn + $ppn;
            } else {
                $ppn         = 0;
                $include_ppn = $exclude_ppn;
            }
        } else {
            // RUMUS SWASTA (Normal / 1.11)
            if ($request->has('potong_ppn')) {
                $include_ppn = $nominal;
                $exclude_ppn = $include_ppn / 1.11;
                $ppn         = $include_ppn - $exclude_ppn;
            } else {
                $include_ppn = $nominal;
                $exclude_ppn = $nominal;
                $ppn         = 0;
            }

            if ($request->has('potong_pph')) {
                $pph_22 = $exclude_ppn * 0.015;
            } else {
                $pph_22 = 0;
            }
        }

        // 3. Hitung Total & Rekening Koran
        $total_diterima       = $exclude_ppn - $pph_22; // Bersih (Kurang PPh 22)
        $total_bruto          = $total_diterima + $ppn + $pph_22; // Sesuai rumus Excel Abang
        $total_rekening_koran = $total_diterima - $biaya_admin;

        // 4. RUMUS SELISIH
        if ($nilai_nota > 0) {
            $selisih = $total_bruto - $nilai_nota;
        } else {
            $selisih = $total_rekening_koran - $total_diterima;
        }

        // 5. Simpan ke Database
        UangMasuk::create([
            'tanggal_transfer'     => $request->tanggal_transfer,
            'kategori'             => $request->kategori,
            'instansi'             => strtoupper($request->instansi),
            'nama_pengadaan'       => $request->nama_pengadaan,
            'keterangan'           => $request->keterangan,
            'rekening_tujuan'      => $request->rekening_tujuan,
            'status_transfer'      => $request->status_transfer ?? 'BELUM', 
            'jumlah_include_ppn'   => $include_ppn,
            'jumlah_exclude_ppn'   => $exclude_ppn,
            'ppn'                  => $ppn,
            'pph_22'               => $pph_22,
            'total_diterima'       => $total_diterima,
            'total_rekening_koran' => $total_rekening_koran,
            'nilai_nota'           => $nilai_nota > 0 ? $nilai_nota : null,
            'selisih'              => $selisih,
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

        return redirect()->route('uang_masuk.index')->with('success', 'Data Excel berhasil di-import!');
    }

    // --- METHOD REPORT ---
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

        $totalIncludePpn    = $query->sum('jumlah_include_ppn');
        $totalExcludePpn    = $query->sum('jumlah_exclude_ppn');
        $totalPpn           = $query->sum('ppn');
        $totalPph22         = $query->sum('pph_22');
        $totalDiterima      = $query->sum('total_diterima');
        $totalRekeningKoran = $query->sum('total_rekening_koran');

        $rekapPerInstansi = $rekapQuery->select('instansi')
            ->selectRaw('count(*) as total_transaksi')
            ->selectRaw('sum(jumlah_include_ppn) as sum_include')
            ->selectRaw('sum(pph_22) as sum_pph')
            ->selectRaw('sum(total_diterima) as sum_diterima')
            ->groupBy('instansi')
            ->get();

        $listInstansi = UangMasuk::select('instansi')->whereNotNull('instansi')->distinct()->pluck('instansi');

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

        // 1. Bersihkan Input dari Rp, Titik, dan Spasi
        $nominal      = (float) preg_replace('/[^0-9]/', '', $request->jumlah_nominal_input ?? '0');
        $nilai_nota   = (float) preg_replace('/[^0-9]/', '', $request->nilai_nota ?? '0');
        $biaya_admin  = (float) preg_replace('/[^0-9]/', '', $request->biaya_admin ?? '0');
        
        $include_ppn = 0; $exclude_ppn = 0; $ppn = 0; $pph_22 = 0;

        // 2. LOGIKA PERHITUNGAN BERDASARKAN KATEGORI
        if ($request->kategori == 'pemerintah') {
            // RUMUS PEMERINTAH (Hitung Mundur / 0.985)
            if ($request->has('potong_pph')) {
                $exclude_ppn = $nominal / 0.985;
                $pph_22      = $exclude_ppn * 0.015;
            } else {
                $exclude_ppn = $nominal;
                $pph_22      = 0;
            }

            if ($request->has('potong_ppn')) {
                $ppn         = $exclude_ppn * 0.11;
                $include_ppn = $exclude_ppn + $ppn;
            } else {
                $ppn         = 0;
                $include_ppn = $exclude_ppn;
            }
        } else {
            // RUMUS SWASTA (Normal / 1.11)
            if ($request->has('potong_ppn')) {
                $include_ppn = $nominal;
                $exclude_ppn = $include_ppn / 1.11;
                $ppn         = $include_ppn - $exclude_ppn;
            } else {
                $include_ppn = $nominal;
                $exclude_ppn = $nominal;
                $ppn         = 0;
            }

            if ($request->has('potong_pph')) {
                $pph_22 = $exclude_ppn * 0.015;
            } else {
                $pph_22 = 0;
            }
        }

        // 3. Hitung Total & Rekening Koran
        $total_diterima       = $exclude_ppn - $pph_22;
        $total_bruto          = $total_diterima + $ppn + $pph_22;
        $total_rekening_koran = $total_diterima - $biaya_admin;

        // 4. RUMUS SELISIH
        if ($nilai_nota > 0) {
            $selisih = $total_bruto - $nilai_nota;
        } else {
            $selisih = $total_rekening_koran - $total_diterima;
        }

        // 5. Update ke Database
        $uangMasuk->update([
            'tanggal_transfer'     => $request->tanggal_transfer,
            'kategori'             => $request->kategori,
            'instansi'             => strtoupper($request->instansi),
            'nama_pengadaan'       => $request->nama_pengadaan,
            'keterangan'           => $request->keterangan,
            'rekening_tujuan'      => $request->rekening_tujuan,
            'status_transfer'      => $request->status_transfer,
            'jumlah_include_ppn'   => $include_ppn,
            'jumlah_exclude_ppn'   => $exclude_ppn,
            'ppn'                  => $ppn,
            'pph_22'               => $pph_22,
            'total_diterima'       => $total_diterima,
            'total_rekening_koran' => $total_rekening_koran,
            'nilai_nota'           => $nilai_nota > 0 ? $nilai_nota : null,
            'selisih'              => $selisih,
        ]);

        return redirect()->route('uang_masuk.index', ['kategori' => $request->kategori])
                         ->with('success', 'Data berhasil diperbarui!');
    }

    // --- METHOD DELETE ---
    public function destroy($id)
    {
        $uangMasuk = UangMasuk::findOrFail($id);
        $kategori = $uangMasuk->kategori; 
        
        $uangMasuk->delete();
        
        return redirect()->route('uang_masuk.index', ['kategori' => $kategori])
                         ->with('success', 'Data berhasil dihapus permanen!');
    }
}