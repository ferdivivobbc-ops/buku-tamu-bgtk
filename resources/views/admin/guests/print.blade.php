<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kunjungan Tamu BGTK Sumatera Barat</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm 15mm 15mm 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 20px;
            font-size: 11pt;
            line-height: 1.3;
        }
        /* Kop Surat Resmi */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .kop-logo {
            width: 85px;
            height: 85px;
            margin-right: 20px;
        }
        .kop-text h3 {
            margin: 0;
            font-size: 13pt;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .kop-text h2 {
            margin: 2px 0;
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kop-text h1 {
            margin: 2px 0;
            font-size: 17pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-text p {
            margin: 2px 0;
            font-size: 9.5pt;
        }
        /* Judul Laporan */
        .judul-laporan {
            text-align: center;
            margin-bottom: 20px;
        }
        .judul-laporan h3 {
            margin: 0;
            font-size: 13pt;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .judul-laporan p {
            margin: 4px 0 0;
            font-size: 10pt;
            font-style: italic;
        }
        /* Tabel Data */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th {
            background-color: #f2f2f2;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        td {
            padding: 5px 4px;
            vertical-align: top;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        /* Tanda Tangan */
        .ttd-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .ttd-box {
            width: 320px;
            text-align: left;
            font-size: 10.5pt;
        }
        .ttd-space {
            height: 70px;
        }
        .no-print-bar {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 10px 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: sans-serif;
            font-size: 12px;
        }
        .btn-print {
            background: #0b3259;
            color: #fff;
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        @media print {
            .no-print-bar {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Aksi (Hanya di Layar) -->
    <div class="no-print-bar">
        <span>Pratinjau Cetak Laporan Kunjungan Tamu BGTK Sumatera Barat</span>
        <div>
            <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan sebagai PDF</button>
            <button onclick="window.close()" style="padding: 8px 14px; margin-left: 5px; cursor: pointer;">Tutup</button>
        </div>
    </div>

    <!-- Kop Surat Resmi -->
    <div class="kop-surat">
        <!-- Emblem Vector Kemendikbud -->
        <svg class="kop-logo" viewBox="0 0 24 24" fill="#000">
            <path d="M12 2L2 7l10 5 10-5-10-5zm0 8.7L5.5 8 12 4.7 18.5 8 12 10.7z"/>
            <path d="M4 10.5v5.8c0 3.2 3.6 5.7 8 5.7s8-2.5 8-5.7v-5.8l-8 4-8-4z"/>
        </svg>

        <div class="kop-text">
            <h3>{{ $ministryName }}</h3>
            <h2>DIREKTORAT JENDERAL GURU DAN TENAGA KEPENDIDIKAN</h2>
            <h1>{{ $institutionName }}</h1>
            <p>{{ $address }}</p>
            <p>Laman: bgtksumbar.kemdikbud.go.id &bull; Pos-el: bgtk.sumbar@kemdikbud.go.id</p>
        </div>
    </div>

    <!-- Judul Laporan -->
    <div class="judul-laporan">
        <h3>REKAPITULASI BUKU TAMU DIGITAL</h3>
        <p>
            Periode Tanggal: 
            <strong>{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}</strong> 
            s.d. 
            <strong>{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</strong>
        </p>
    </div>

    <!-- Tabel Data Kunjungan -->
    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 100px;">No. Tiket</th>
                <th style="width: 75px;">Tanggal / Jam</th>
                <th>Nama Lengkap & NIP</th>
                <th>Asal Instansi</th>
                <th>Jabatan</th>
                <th style="width: 90px;">Kab / Kota</th>
                <th>Bagian Dituju</th>
                <th>Keperluan Kunjungan</th>
                <th style="width: 30px;">Jml</th>
                <th style="width: 55px;">Status</th>
                <th style="width: 35px;">IKM</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guests as $idx => $g)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td class="text-center" style="font-family: monospace; font-weight: bold;">{{ $g->ticket_number }}</td>
                <td class="text-center">
                    {{ $g->visit_date ? $g->visit_date->format('d/m/Y') : '-' }}<br>
                    <small>{{ substr($g->check_in_time, 0, 5) }} WIB</small>
                </td>
                <td>
                    <strong>{{ $g->name }}</strong><br>
                    <small>{{ $g->identity_number ?: '-' }}</small>
                </td>
                <td>{{ $g->institution }}</td>
                <td>{{ $g->position }}</td>
                <td>{{ $g->city_regency }}</td>
                <td>{{ $g->department ? $g->department->name : '-' }}</td>
                <td>{{ $g->purpose }}</td>
                <td class="text-center">{{ $g->companion_count }}</td>
                <td class="text-center">{{ $g->status_label }}</td>
                <td class="text-center">{{ $g->satisfaction_rating ? $g->satisfaction_rating . '★' : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="12" class="text-center" style="padding: 20px;">
                    Tidak ada catatan kunjungan tamu pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <p style="font-size: 9.5pt; margin-top: -10px;">
        <em>Total Kunjungan: <strong>{{ $guests->count() }} Orang</strong></em>
    </p>

    <!-- Tanda Tangan Pejabat BGTK -->
    <div class="ttd-container">
        <div class="ttd-box">
            <p>Padang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Kepala Balai Guru dan Tenaga Kependidikan<br>Provinsi Sumatera Barat,</p>
            <div class="ttd-space"></div>
            <p style="margin-bottom: 2px;"><strong><u>{{ $headOfInstitution ?: 'Drs. H. Mulyadi, M.Pd.' }}</u></strong></p>
            <p style="margin: 0;">NIP. {{ $nipHead ?: '19680512 199303 1 002' }}</p>
        </div>
    </div>

</body>
</html>
