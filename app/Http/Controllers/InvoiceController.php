<?php

namespace App\Http\Controllers;

use App\Models\UangMasuk; // Tambahkan ini di atas!
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class InvoiceController extends Controller
{
    // Fungsi untuk mengubah angka jadi teks (Terbilang)
    private function terbilang($angka)
    {
        $angka = abs($angka);
        $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $terbilang = "";

        if ($angka < 12) {
            $terbilang = " " . $baca[$angka];
        } else if ($angka < 20) {
            $terbilang = $this->terbilang($angka - 10) . " Belas";
        } else if ($angka < 100) {
            $terbilang = $this->terbilang($angka / 10) . " Puluh" . $this->terbilang($angka % 10);
        } else if ($angka < 200) {
            $terbilang = " Seratus" . $this->terbilang($angka - 100);
        } else if ($angka < 1000) {
            $terbilang = $this->terbilang($angka / 100) . " Ratus" . $this->terbilang($angka % 100);
        } else if ($angka < 2000) {
            $terbilang = " Seribu" . $this->terbilang($angka - 1000);
        } else if ($angka < 1000000) {
            $terbilang = $this->terbilang($angka / 1000) . " Ribu" . $this->terbilang($angka % 1000);
        } else if ($angka < 1000000000) {
            $terbilang = $this->terbilang($angka / 1000000) . " Juta" . $this->terbilang($angka % 1000000);
        } else if ($angka < 1000000000000) {
            $terbilang = $this->terbilang($angka / 1000000000) . " Milyar" . $this->terbilang(fmod($angka, 1000000000));
        }
        return $terbilang;
    }

    public function index()
    {
        $invoices = Invoice::orderBy('created_at', 'desc')->paginate(20);
        return view('invoice.index', compact('invoices'));
    }

    public function create()
    {
        // Generate No Invoice Otomatis
        $bulan = date('m');
        $tahun = date('y');
        $lastInvoice = Invoice::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->count();
        $urut = str_pad($lastInvoice + 1, 3, '0', STR_PAD_LEFT);
        $no_invoice = "INV-DBM/{$bulan}-{$tahun}/{$urut}";

        // Tarik semua data barang dari database
        $barangs = \App\Models\Barang::orderBy('nama_barang', 'asc')->get();

        return view('invoice.create', compact('no_invoice', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_invoice' => 'required|unique:invoices',
            'tanggal' => 'required|date',
            'nama_pelanggan' => 'required',
            'items' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // Hitung Subtotal dari baris barang
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += ($item['qty'] * $item['harga']);
            }

            // Hitung PPN jika dicentang
            $ppn = $request->has('pakai_ppn') ? ($subtotal * 0.11) : 0;
            $grand_total = $subtotal + $ppn;

            // Generate Terbilang
            $teks_terbilang = trim($this->terbilang($grand_total)) . " Rupiah";

            // 1. Simpan Header Invoice
            $invoice = Invoice::create([
                'no_invoice' => $request->no_invoice,
                'tanggal' => $request->tanggal,
                'nama_pelanggan' => $request->nama_pelanggan,
                'alamat_pelanggan' => $request->alamat_pelanggan,
                'no_so' => $request->no_so,
                'subtotal' => $subtotal,
                'ppn' => $ppn,
                'grand_total' => $grand_total,
                'terbilang' => $teks_terbilang,
            ]);

            // 2. Simpan Detail Barang
            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'nama_barang' => $item['nama_barang'],
                    'qty' => $item['qty'],
                    'satuan' => $item['satuan'],
                    'harga' => $item['harga'],
                    'total' => ($item['qty'] * $item['harga']),
                ]);
            }

            DB::commit();
            return redirect()->route('invoice.index')->with('success', 'Invoice berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyimpan invoice: ' . $e->getMessage());
        }
    }

    // Fungsi untuk menampilkan halaman Cetak / Print
    public function print($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        return view('invoice.print', compact('invoice'));
    }

    // Fungsi untuk menandai lunas dan kirim ke Uang Masuk
    // Fungsi untuk menandai lunas & kirim otomatis ke Uang Masuk (Bisa Swasta / Pemerintah)
    public function tandaiLunas($id, $kategori)
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->status_pembayaran == 'Lunas') {
            return back()->with('error', 'Invoice ini sudah lunas!');
        }

        DB::beginTransaction();
        try {
            // 1. Ubah status invoice jadi Lunas
            $invoice->update([
                'status_pembayaran' => 'Lunas'
            ]);

            // 2. Logika Penghitungan Berdasarkan Kategori
            if ($kategori == 'pemerintah') {
                // Jika Pemerintah, hitung mundur PPN dan PPh 22 dari Grand Total
                $includePpn = $invoice->grand_total;
                $excludePpn = $includePpn / 1.11;
                $ppn = $includePpn - $excludePpn;
                $pph22 = $excludePpn * 0.015; // Potongan PPh 22 (1.5%)
                $totalDiterima = $excludePpn - $pph22;
                $totalRekeningKoran = $totalDiterima; // Asumsi bersih masuk bank
            } else {
                // Jika Swasta, tidak ada PPh 22
                $includePpn = $invoice->grand_total;
                $excludePpn = $invoice->subtotal;
                $ppn = $invoice->ppn;
                $pph22 = 0;
                $totalDiterima = $invoice->grand_total;
                $totalRekeningKoran = $invoice->grand_total;
            }

            // 3. Insert otomatis ke tabel Uang Masuk
            UangMasuk::create([
                'tanggal_transfer'     => date('Y-m-d'),
                'kategori'             => $kategori, // 'pemerintah' atau 'swasta'
                'instansi'             => strtoupper($invoice->nama_pelanggan),
                'nama_pengadaan'       => 'Pelunasan ' . $invoice->no_invoice,
                'keterangan'           => $invoice->no_so ?? '-',
                'rekening_tujuan'      => 'DARMA',
                'status_transfer'      => 'SUDAH',
                'jumlah_include_ppn'   => $includePpn,
                'jumlah_exclude_ppn'   => $excludePpn,
                'ppn'                  => $ppn,
                'pph_22'               => $pph22,
                'total_diterima'       => $totalDiterima,
                'total_rekening_koran' => $totalRekeningKoran,
                'nilai_nota'           => $invoice->grand_total,
                'selisih'              => 0,
            ]);

            DB::commit();
            return back()->with('success', 'Invoice berhasil dilunasi & otomatis masuk ke Laporan Keuangan ' . ucfirst($kategori) . '!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memproses pelunasan: ' . $e->getMessage());
        }
    }

    // --- FORM EDIT INVOICE ---
    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        
        // Gembok Keamanan: Cek apakah sudah lunas
        if ($invoice->status_pembayaran == 'Lunas') {
            return redirect()->route('invoice.index')->with('error', 'Akses ditolak! Invoice yang sudah lunas tidak dapat diedit.');
        }

        $barangs = \App\Models\Barang::orderBy('nama_barang', 'asc')->get();
        return view('invoice.edit', compact('invoice', 'barangs'));
    }

    // --- PROSES UPDATE INVOICE ---
    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->status_pembayaran == 'Lunas') {
            return redirect()->route('invoice.index')->with('error', 'Gagal! Invoice yang sudah lunas tidak dapat diubah.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'nama_pelanggan' => 'required',
            'items' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += ($item['qty'] * $item['harga']);
            }

            $ppn = $request->has('pakai_ppn') ? ($subtotal * 0.11) : 0;
            $grand_total = $subtotal + $ppn;
            $teks_terbilang = trim($this->terbilang($grand_total)) . " Rupiah";

            // 1. Update Header
            $invoice->update([
                'tanggal' => $request->tanggal,
                'nama_pelanggan' => $request->nama_pelanggan,
                'alamat_pelanggan' => $request->alamat_pelanggan,
                'no_so' => $request->no_so,
                'subtotal' => $subtotal,
                'ppn' => $ppn,
                'grand_total' => $grand_total,
                'terbilang' => $teks_terbilang,
            ]);

            // 2. Hapus detail barang yang lama
            InvoiceItem::where('invoice_id', $invoice->id)->delete();

            // 3. Masukkan detail barang yang baru
            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'nama_barang' => $item['nama_barang'],
                    'qty' => $item['qty'],
                    'satuan' => $item['satuan'],
                    'harga' => $item['harga'],
                    'total' => ($item['qty'] * $item['harga']),
                ]);
            }

            DB::commit();
            return redirect()->route('invoice.index')->with('success', 'Data Invoice berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal update invoice: ' . $e->getMessage());
        }
    }

    // --- PROSES HAPUS INVOICE ---
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        if ($invoice->status_pembayaran == 'Lunas') {
            return redirect()->route('invoice.index')->with('error', 'Gagal! Invoice yang sudah lunas tidak boleh dihapus.');
        }

        $invoice->delete(); // Detail barang otomatis terhapus karena pakai cascadeOnDelete di migration

        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil dihapus permanen!');
    }
}