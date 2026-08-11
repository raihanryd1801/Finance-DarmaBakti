<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use Illuminate\Http\Request;

class RekeningController extends Controller
{
    public function index()
    {
        $rekenings = Rekening::orderBy('nama_bank', 'asc')->paginate(10);
        return view('rekening.index', compact('rekenings'));
    }

    public function create()
    {
        return view('rekening.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required',
            'nomor_rekening' => 'required|unique:rekenings',
            'atas_nama' => 'required',
        ], [
            'nomor_rekening.unique' => 'Nomor rekening ini sudah ada di database!',
        ]);

        Rekening::create([
            'nama_bank'      => strtoupper($request->nama_bank),
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama'      => strtoupper($request->atas_nama),
        ]);

        return redirect()->route('rekening.index')->with('success', 'Data Rekening berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $rekening = Rekening::findOrFail($id);
        return view('rekening.edit', compact('rekening'));
    }

    public function update(Request $request, $id)
    {
        $rekening = Rekening::findOrFail($id);

        $request->validate([
            'nama_bank' => 'required',
            'nomor_rekening' => 'required|unique:rekenings,nomor_rekening,' . $id,
            'atas_nama' => 'required',
        ]);

        $rekening->update([
            'nama_bank'      => strtoupper($request->nama_bank),
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama'      => strtoupper($request->atas_nama),
        ]);

        return redirect()->route('rekening.index')->with('success', 'Data Rekening berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $rekening = Rekening::findOrFail($id);
        $rekening->delete();

        return redirect()->route('rekening.index')->with('success', 'Data Rekening berhasil dihapus!');
    }
}