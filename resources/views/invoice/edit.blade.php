@extends('layouts.app')

@section('content')
    <div class="card" style="padding: 20px;">
        <h3 style="margin-bottom: 20px;">✏️ Edit Invoice</h3>
        
        <form action="{{ route('invoice.update', $invoice->id) }}" method="POST">
            @csrf
            <!-- HEADER INVOICE -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; background: #f8fafc; padding: 20px; border-radius: 8px;">
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">No Invoice</label>
                    <input type="text" value="{{ $invoice->no_invoice }}" class="form-control" readonly style="width: 100%; padding: 8px; background: #e2e8f0;">
                </div>
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $invoice->tanggal }}" class="form-control" required style="width: 100%; padding: 8px;">
                </div>
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Kepada Yth (Nama Pelanggan)</label>
                    <input type="text" name="nama_pelanggan" value="{{ $invoice->nama_pelanggan }}" class="form-control" required style="width: 100%; padding: 8px;">
                </div>
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">No SO / PO</label>
                    <input type="text" name="no_so" value="{{ $invoice->no_so }}" class="form-control" style="width: 100%; padding: 8px;">
                </div>
                <div style="grid-column: span 2;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Alamat Pelanggan</label>
                    <textarea name="alamat_pelanggan" class="form-control" rows="2" style="width: 100%; padding: 8px;">{{ $invoice->alamat_pelanggan }}</textarea>
                </div>
            </div>

            <!-- DETAIL BARANG -->
            <h4 style="margin-bottom: 15px;">Daftar Barang</h4>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;" id="itemTable">
                <thead>
                    <tr style="background: #e2e8f0;">
                        <th style="padding: 10px; text-align: left;">Pilih Barang</th>
                        <th style="padding: 10px; width: 100px;">Qty</th>
                        <th style="padding: 10px; width: 150px;">Satuan</th>
                        <th style="padding: 10px; width: 200px;">Harga Satuan</th>
                        <th style="padding: 10px; width: 50px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="itemBody">
                    @foreach($invoice->items as $idx => $item)
                    <tr id="row_{{ $idx }}">
                        <td style="padding: 8px;">
                            <select name="items[{{ $idx }}][nama_barang]" id="barang_{{ $idx }}" class="form-control" required style="width: 100%; padding: 8px;" onchange="pilihBarang({{ $idx }})">
                                <option value="{{ $item->nama_barang }}">{{ $item->nama_barang }} (Lama)</option>
                                @foreach($barangs as $b)
                                    <option value="{{ $b->nama_barang }}" data-satuan="{{ $b->satuan }}" data-harga="{{ round($b->harga) }}" {{ $item->nama_barang == $b->nama_barang ? 'selected' : '' }}>
                                        {{ $b->kode_barang }} - {{ $b->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td style="padding: 8px;"><input type="number" name="items[{{ $idx }}][qty]" value="{{ $item->qty }}" min="1" required style="width: 100%; padding: 8px;"></td>
                        <td style="padding: 8px;"><input type="text" name="items[{{ $idx }}][satuan]" id="satuan_{{ $idx }}" value="{{ $item->satuan }}" readonly style="width: 100%; padding: 8px; background: #f1f5f9;"></td>
                        <td style="padding: 8px;"><input type="number" name="items[{{ $idx }}][harga]" id="harga_{{ $idx }}" value="{{ round($item->harga) }}" required style="width: 100%; padding: 8px;"></td>
                        <td style="padding: 8px; text-align: center;">
                            @if($idx > 0)
                                <button type="button" onclick="hapusBaris({{ $idx }})" style="background: #ef4444; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">X</button>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <button type="button" onclick="tambahBaris()" class="btn" style="background: #10b981; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; margin-bottom: 20px;">+ Tambah Barang</button>

            <!-- OPSI PPN -->
            <div style="margin-bottom: 30px; padding: 15px; border: 1px solid #cbd5e1; border-radius: 6px; display: inline-block;">
                <label style="font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="pakai_ppn" value="1" {{ $invoice->ppn > 0 ? 'checked' : '' }} style="width: 18px; height: 18px;"> 
                    Tambahkan PPN 11%
                </label>
            </div>

            <hr style="margin-bottom: 20px;">
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 20px; font-size: 16px; border-radius: 6px;">💾 Update Invoice</button>
                <a href="{{ route('invoice.index') }}" class="btn" style="padding: 10px 20px; background: #64748b; color: white; text-decoration: none; border-radius: 6px; font-size: 16px;">Batal</a>
            </div>
        </form>
    </div>

    <!-- SCRIPT TAMBAH BARIS & AUTO FILL -->
    <script>
        // Set index row berdasarkan jumlah item yang sudah ada
        let rowIdx = {{ count($invoice->items) }};
        const listBarang = {!! json_encode($barangs) !!};

        function pilihBarang(id) {
            let select = document.getElementById('barang_' + id);
            let selectedOption = select.options[select.selectedIndex];
            
            let harga = selectedOption.getAttribute('data-harga');
            let satuan = selectedOption.getAttribute('data-satuan');
            
            if(harga && satuan) {
                document.getElementById('harga_' + id).value = harga;
                document.getElementById('satuan_' + id).value = satuan;
            }
        }

        function tambahBaris() {
            let optionsHtml = '<option value="">-- Pilih Barang --</option>';
            listBarang.forEach(function(b) {
                optionsHtml += `<option value="${b.nama_barang}" data-satuan="${b.satuan}" data-harga="${Math.round(b.harga)}">${b.kode_barang} - ${b.nama_barang}</option>`;
            });

            let html = `
                <tr id="row_${rowIdx}">
                    <td style="padding: 8px;">
                        <select name="items[${rowIdx}][nama_barang]" id="barang_${rowIdx}" class="form-control" required style="width: 100%; padding: 8px;" onchange="pilihBarang(${rowIdx})">
                            ${optionsHtml}
                        </select>
                    </td>
                    <td style="padding: 8px;"><input type="number" name="items[${rowIdx}][qty]" value="1" min="1" required style="width: 100%; padding: 8px;"></td>
                    <td style="padding: 8px;"><input type="text" name="items[${rowIdx}][satuan]" id="satuan_${rowIdx}" readonly style="width: 100%; padding: 8px; background: #f1f5f9;"></td>
                    <td style="padding: 8px;"><input type="number" name="items[${rowIdx}][harga]" id="harga_${rowIdx}" required style="width: 100%; padding: 8px;"></td>
                    <td style="padding: 8px; text-align: center;"><button type="button" onclick="hapusBaris(${rowIdx})" style="background: #ef4444; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">X</button></td>
                </tr>
            `;
            document.getElementById('itemBody').insertAdjacentHTML('beforeend', html);
            rowIdx++;
        }

        function hapusBaris(id) {
            document.getElementById('row_' + id).remove();
        }
    </script>
@endsection