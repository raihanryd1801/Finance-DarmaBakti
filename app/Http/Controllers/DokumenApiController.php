<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DokumenApiController extends Controller
{
    private $baseUrl = 'http://192.168.99.253:8000/api/dokumen';

    public function index(Request $request)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'FORBIDDEN 🛑 : Hanya Admin yang boleh Akses Menu ini!');
        
        $response = Http::get($this->baseUrl);
        $dokumen = $response->successful() ? $response->json('data') : [];

        $collection = collect($dokumen);

        // Ambil list kategori unik yang benar-benar ada datanya dari API untuk dropdown
        $listKategori = $collection->pluck('kategori')->unique()->filter()->values()->all();

        // 1. Filter berdasarkan Kategori yang dipilih di dropdown
        if ($request->filled('kategori')) {
            $collection = $collection->filter(function ($item) use ($request) {
                return isset($item['kategori']) && $item['kategori'] == $request->kategori;
            });
        }

        // 2. Filter berdasarkan Rentang Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $collection = $collection->filter(function ($item) use ($request) {
                $tgl = $item['tanggal_dokumen'] ?? $item['created_at'] ?? null;
                if (!$tgl) return false;

                $formatTgl = date('Y-m-d', strtotime($tgl));
                return $formatTgl >= $request->start_date && $formatTgl <= $request->end_date;
            });
        }

        // 3. BARU: Filter berdasarkan Pencarian Teks (Ketik)
        if ($request->filled('search')) {
            $search = $request->search;
            $collection = $collection->filter(function ($item) use ($search) {
                // Sesuaikan 'nama_dokumen' atau 'keterangan' dengan key JSON dari API Abang
                $nama = $item['nama_dokumen'] ?? $item['judul'] ?? ''; 
                $keterangan = $item['keterangan'] ?? $item['deskripsi'] ?? '';

                // stripos digunakan untuk pencarian teks yang tidak mempedulikan huruf besar/kecil (case-insensitive)
                return stripos($nama, $search) !== false || stripos($keterangan, $search) !== false;
            });
        }

        $dokumen = $collection->values()->all();

        return view('dokumen.index', compact('dokumen', 'listKategori'));
    }

    // 1. PREVIEW PDF (Tampil otomatis di tab browser)
    public function preview($id)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'FORBIDDEN 🛑 : Hanya Admin yang boleh Akses Menu ini!');
        $response = Http::get("{$this->baseUrl}/{$id}/download"); // Mengambil file dari API pusat

        if ($response->successful()) {
            return response($response->body(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="dokumen_' . $id . '.pdf"',
            ]);
        }

        return back()->with('error', 'Gagal memuat preview dokumen dari server API.');
    }

    // 2. DOWNLOAD FILE
    public function download($id)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'FORBIDDEN 🛑 : Hanya Admin yang boleh Akses Menu ini!');
        $response = Http::get("{$this->baseUrl}/{$id}/download");

        if ($response->successful()) {
            $fileName = 'dokumen_' . $id . '.pdf';

            return response()->streamDownload(function () use ($response) {
                echo $response->body();
            }, $fileName);
        }

        return back()->with('error', 'Gagal mendownload file dari server API.');
    }
}