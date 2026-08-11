<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Invoice - {{ $invoice->no_invoice }}</title>
    <style>
        /* Pengaturan Kertas A4 & Reset */
        @page {
            size: A4;
            margin: 10mm 15mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .page-container {
            width: 100%;
            max-width: 190mm;
            margin: 0 auto;
            background: #fff;
        }
        
        /* Kop Surat */
        .kop-table {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        .kop-table td {
            vertical-align: middle;
        }
        
        /* Kotak Judul Invoice */
        .invoice-title-box {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            border: 2px solid #000;
            padding: 6px;
            margin-bottom: 12px;
            background: #f2f2f2;
            letter-spacing: 1px;
        }

        /* Tabel Informasi Header (Kepada & Tanggal) */
        .info-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 2px 4px;
            vertical-align: top;
            font-size: 10.5pt;
        }

        /* Tabel Rincian Barang */
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table-items th, .table-items td {
            border: 1px solid #000;
            padding: 5px 6px;
            font-size: 10.5pt;
        }
        .table-items th {
            background-color: #e6e6e6;
            text-align: center;
            text-transform: uppercase;
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }

        /* Tabel Bagian Bawah (Terbilang, Rekening & Tanda Tangan) */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .footer-table td {
            vertical-align: top;
            font-size: 10.5pt;
            padding: 2px;
        }

        /* Tombol Cetak (Hilang saat diprint) */
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="page-container">
        
        <!-- Tombol Cetak -->
        <div class="no-print" style="margin-bottom: 15px; text-align: right;">
            <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: #fff; border: none; cursor: pointer; border-radius: 4px; font-weight: bold; font-size: 11pt;">🖨️ Cetak / Simpan PDF</button>
        </div>

        <!-- KOP SURAT (LOGO & TEKS) -->
        <table class="kop-table">
            <tr>
                <td style="width: 15%; text-align: left;">
                    <img src="{{ asset('darma.webp') }}" alt="Logo" style="width: 70px; height: auto; display: block;">
                </td>
                <td style="width: 85%; text-align: center;">
                    <h2 style="margin: 0 0 3px; font-size: 16pt; color: #000080; font-weight: bold; text-transform: uppercase;">CV DARMA BAKTI MANDIRI</h2>
                    <div style="font-size: 10pt; font-weight: bold; margin-bottom: 2px;">Mengerjakan Macam-Macam Seragam</div>
                    <div style="font-size: 9pt; margin-bottom: 2px;">Bordir, Lencana, Vandel, Plakat, Topi, Sablon Percetakan, dll</div>
                    <div style="font-size: 9pt; margin-bottom: 2px;">Jl. Bentengan X No.11 RT/RW 011/001, Sunter Jaya - Jakarta Utara</div>
                    <div style="font-size: 9pt;">Email : darmabaktimandiri@ymail.com</div>
                </td>
            </tr>
        </table>

        <!-- JUDUL INVOICE -->
        <div class="invoice-title-box">
            INVOICE PENJUALAN
        </div>

        <!-- INFORMASI PELANGGAN & SO/PO -->
        <table class="info-table">
            <tr>
                <td style="width: 12%;">Kepada Yth.</td>
                <td style="width: 2%;">:</td>
                <td style="width: 46%;"><strong>{{ $invoice->nama_pelanggan }}</strong><br>{{ $invoice->alamat_pelanggan }}</td>
                <td style="width: 15%;">Tanggal</td>
                <td style="width: 2%;">:</td>
                <td style="width: 23%;">{{ date('d F Y', strtotime($invoice->tanggal)) }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
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

        <!-- TABEL RINCIAN BARANG -->
        <table class="table-items">
            <thead>
                <tr>
                    <th style="width: 6%;">NO.</th>
                    <th style="width: 44%;">NAMA BARANG</th>
                    <th style="width: 12%;">QTY</th>
                    <th style="width: 18%;">HARGA @</th>
                    <th style="width: 20%;">TOTAL</th>
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
                
                <!-- BARIS KOSONG PENYEIMBANG (Biar tabel tetap proporsional jika item sedikit) -->
                @if(count($invoice->items) < 3)
                    @for($i = count($invoice->items); $i < 3; $i++)
                    <tr>
                        <td class="text-center" style="color: transparent;">-</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endfor
                @endif

                <!-- SUBTOTAL, PPN, GRAND TOTAL -->
                @if($invoice->ppn > 0)
                <tr>
                    <td colspan="4" class="text-right" style="border-right: none;"><strong>Subtotal :</strong></td>
                    <td class="text-right"><strong>{{ number_format($invoice->subtotal, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right" style="border-right: none;"><strong>PPN 11% :</strong></td>
                    <td class="text-right"><strong>{{ number_format($invoice->ppn, 0, ',', '.') }}</strong></td>
                </tr>
                @endif
                <tr>
                    <td colspan="4" class="text-right" style="border-right: none; background: #f2f2f2;"><strong>GRAND TOTAL :</strong></td>
                    <td class="text-right" style="background: #f2f2f2;"><strong>Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- BAGIAN BAWAH: TERBILANG, REKENING & TANDA TANGAN -->
        <table class="footer-table">
            <tr>
                <td style="width: 60%;">
                    <div style="margin-bottom: 10px;">
                        <strong>Terbilang :</strong><br>
                        <div style="background: #f9f9f9; padding: 5px 8px; border: 1px dashed #444; margin-top: 3px; font-style: italic; display: inline-block;">
                            # {{ $invoice->terbilang }} #
                        </div>
                    </div>
                    
                    <div style="font-size: 10pt; line-height: 1.4;">
                        <strong>Bank Transfer / Cek:</strong><br>
                        @if($invoice->rekening)
                            <strong>{{ $invoice->rekening->nama_bank }} {{ $invoice->rekening->nomor_rekening }} an. {{ $invoice->rekening->atas_nama }}</strong><br><br>
                        @else
                            <strong>(Informasi rekening belum dipilih)</strong><br><br>
                        @endif
                        <span style="font-size: 9pt; font-weight: bold;">TIDAK MENERIMA PEMBAYARAN MELALUI BG<br>HANYA MENERIMA TRANSFER</span>
                    </div>
                </td>
                <td style="width: 40%; text-align: center;">
                    <div style="margin-top: 5px;">
                        Hormat Kami,<br>
                        <strong>CV DARMA BAKTI MANDIRI</strong>
                        <br><br><br><br>
                        <strong>( DANURI )</strong>
                    </div>
                </td>
            </tr>
        </table>

    </div>
</body>
</html>