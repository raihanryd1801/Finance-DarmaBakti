<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Invoice - {{ $invoice->no_invoice }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #000;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat h1 {
            margin: 0 0 5px;
            font-size: 24px;
            color: #000080; /* Warna biru gelap ala logo */
        }
        .kop-surat p {
            margin: 2px 0;
            font-size: 13px;
        }
        .invoice-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            text-decoration: underline;
        }
        .header-info {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .header-info td {
            vertical-align: top;
            padding: 3px;
        }
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-items th, .table-items td {
            border: 1px solid #000;
            padding: 8px;
        }
        .table-items th {
            background-color: #f0f0f0;
            text-align: center;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer-info { margin-top: 30px; }
        
        @media print {
            body { margin: 0; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <!-- Tombol Print (Sembunyi saat dicetak) -->
        <div class="no-print" style="margin-bottom: 20px; text-align: right;">
            <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: #fff; border: none; cursor: pointer; border-radius: 4px;">🖨️ Cetak Sekarang</button>
        </div>

        <!-- KOP SURAT -->
        <div class="kop-surat">
            <h1>CV DARMA BAKTI MANDIRI</h1>
            <p>Mengerjakan Macam-Macam Seragam</p>
            <p>Bordir, Lencana, Vandel, Plakat, Topi, Sablon Percetakan, dll</p>
            <p>Jl. Bentengan X No.11 RT/RW 011/001, Sunter Jaya - Jakarta Utara</p>
            <p>Email : darmabaktimandiri@ymail.com</p>
        </div>

        <div class="invoice-title">INVOICE PENJUALAN</div>

        <!-- INFO PELANGGAN & INVOICE -->
        <table class="header-info">
            <tr>
                <td width="15%">Kepada Yth.</td>
                <td width="2%">:</td>
                <td width="43%"><strong>{{ $invoice->nama_pelanggan }}</strong></td>
                <td width="15%">Tanggal</td>
                <td width="2%">:</td>
                <td width="23%">{{ date('d F Y', strtotime($invoice->tanggal)) }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>{{ $invoice->alamat_pelanggan }}</td>
                <td>No. Invoice</td>
                <td>:</td>
                <td><strong>{{ $invoice->no_invoice }}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>No. SO/PO</td>
                <td>:</td>
                <td>{{ $invoice->no_so ?? '-' }}</td>
            </tr>
        </table>

        <!-- TABEL BARANG -->
        <table class="table-items">
            <thead>
                <tr>
                    <th width="5%">NO.</th>
                    <th width="40%">NAMA BARANG</th>
                    <th width="10%">QTY</th>
                    <th width="15%">HARGA @</th>
                    <th width="30%">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td class="text-center">{{ $item->qty }} {{ $item->satuan }}</td>
                    <td class="text-right">{{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                
                <!-- SUBTOTAL, PPN, GRAND TOTAL -->
                @if($invoice->ppn > 0)
                <tr>
                    <td colspan="4" class="text-right" style="border:none; padding-top:15px;"><strong>Subtotal :</strong></td>
                    <td class="text-right" style="border:none; padding-top:15px;">{{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right" style="border:none;"><strong>PPN 11% :</strong></td>
                    <td class="text-right" style="border:none;">{{ number_format($invoice->ppn, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr>
                    <td colspan="4" class="text-right" style="border:none; padding-top:10px;"><strong>GRAND TOTAL :</strong></td>
                    <td class="text-right" style="border-bottom: 3px double #000; padding-top:10px;"><strong>Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- TERBILANG & REKENING -->
        <div style="margin-top: 10px;">
            <p><strong>Terbilang :</strong> <br> 
               <em style="background: #f0f0f0; padding: 5px 10px; display: inline-block; min-width: 60%;"># {{ $invoice->terbilang }} #</em>
            </p>
            <p style="margin-top: 20px;">
                <strong>Pembayaran dapat ditransfer ke Rekening:</strong><br>
                BNI 0430643011 an. DANURI
            </p>
        </div>

        <!-- TANDA TANGAN -->
        <table style="width: 100%; margin-top: 50px;">
            <tr>
                <td width="70%"></td>
                <td width="30%" class="text-center">
                    Hormat Kami,<br><br><br><br><br>
                    <strong>( ...................................... )</strong>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>