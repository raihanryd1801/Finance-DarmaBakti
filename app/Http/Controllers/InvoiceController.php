<?php

namespace App\Http\Controllers;

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
}