<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // 1. Tampilkan semua data barang
    public function index()
    {
        $barangs = Barang::orderBy('kode_barang', 'asc')->paginate(20);
        return view('barang.index', compact('barangs'));
    }

    // 2. Form tambah barang
    public function create()
    {
        return view('barang.create');
    }

    // 3. Proses simpan data barang baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barangs',
            'nama_barang' => 'required',
            'harga'       => 'required|numeric|min:0',
            'satuan'      => 'required',
        ], [
            'kode_barang.unique' => 'Kode Barang ini sudah terdaftar!',
        ]);

        Barang::create([
            'kode_barang' => strtoupper($request->kode_barang),
            'nama_barang' => $request->nama_barang,
            'harga'       => $request->harga,
            'satuan'      => ucfirst($request->satuan),
            'keterangan'  => $request->keterangan,
        ]);

        return redirect()->route('barang.index')->with('success', 'Data Barang berhasil ditambahkan!');
    }

    // 4. Form edit barang
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    // 5. Proses update data barang
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang,' . $id,
            'nama_barang' => 'required',
            'harga'       => 'required|numeric|min:0',
            'satuan'      => 'required',
        ]);

        $barang->update([
            'kode_barang' => strtoupper($request->kode_barang),
            'nama_barang' => $request->nama_barang,
            'harga'       => $request->harga,
            'satuan'      => ucfirst($request->satuan),
            'keterangan'  => $request->keterangan,
        ]);

        return redirect()->route('barang.index')->with('success', 'Data Barang berhasil diperbarui!');
    }

    // 6. Hapus barang
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Data Barang berhasil dihapus!');
    }
}