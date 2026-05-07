<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Dinas Permohonan Narasumber</title>
    <style>
        /* DOMPDF Compatibility: Fixed units and basic tables */
        @page {
            margin: 0.5cm 1.5cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
        }
        
        /* Layout container to prevent shrinking */
        .wrapper {
            width: 100%;
        }

        /* Header Table - Explicit widths to prevent collapse */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: top;
            padding-bottom: 10px;
        }
        
        .kemenkes-title {
            font-size: 14pt;
            color: #0d9488;
            font-weight: bold;
            margin: 0;
        }
        .dirjen-title {
            font-size: 12pt;
            font-weight: bold;
            margin: 2px 0;
        }
        .rsup-title {
            font-size: 11pt;
            margin: 0 0 8px 0;
        }
        .contact-item img {
            height: 10pt;
            width: 10pt;
            vertical-align: middle;
            margin-right: 5px;
            margin-top: -2px;
        }

        .contact-item {
            font-size: 8.5pt;
            color: #666;
            margin-bottom: 2px;
            display: block;
            line-height: 1.2;
        }

        /* Document Title */
        .doc-title {
            text-align: center;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }
        .nomor-surat {
            font-weight: normal;
            text-transform: none;
        }
        
        /* Meta Table (Yth, Dari, Hal, Tanggal) */
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .meta-table td {
            vertical-align: top;
            padding-bottom: 5px;
        }
        
        /* Content Styling */
        .main-text {
            text-align: justify;
        }
        .main-text p {
            margin-bottom: 15px;
            text-indent: 1cm;
        }
        
        .event-detail-table {
            margin-left: 1cm;
            margin-bottom: 20px;
        }
        .event-detail-table td {
            padding-bottom: 5px;
        }
        
        /* Signature Area - Table based for stability */
        .signature-table {
            width: 100%;
            margin-top: 30px;
        }
        .qr-placeholder {
            border: 1px solid #ccc;
            width: 80px;
            height: 80px;
            background-color: #f9f9f9;
            text-align: center;
            vertical-align: middle;
            margin: 0 auto 10px auto;
        }
        
        /* Footer */
        .page-footer {
            position: fixed;
            bottom: 0.5cm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5pt;
            color: #444;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- PAGE 1: NOTA DINAS -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 30%;">
                @if(isset($logoBase64))
                    <img src="{{ $logoBase64 }}" style="width: 180px;">
                @else
                    <div style="color: #0d9488; font-size: 18pt; font-weight: bold;">Kemenkes</div>
                    <div style="color: #64748b; font-weight: bold; font-size: 9pt;">RS Cipto Mangunkusumo</div>
                @endif
            </td>
            <td style="width: 70%; padding-left: 15px;">
                <div class="kemenkes-title">Kementerian Kesehatan</div>
                <div class="dirjen-title">Direktorat Jenderal Kesehatan Lanjutan</div>
                <div class="rsup-title">RSUP Nasional Dr. Cipto Mangunkusumo Jakarta</div>
                
<div class="contact-item">
                    @if(isset($iconBase64['map']))
                        <img style="height: 1em; width: 1em; vertical-align: middle; margin-right: 3px;" src="{{ $iconBase64['map'] }}">
                    @endif
                    Jalan Diponegoro Nomor 71 Jakarta 10430
                </div>
                <div class="contact-item">
                    @if(isset($iconBase64['phone']))
                        <img style="height: 1em; width: 1em; vertical-align: middle; margin-right: 3px;" src="{{ $iconBase64['phone'] }}">
                    @endif
                    1500135
                </div>
                <div class="contact-item">
                    @if(isset($iconBase64['internet']))
                        <img style="height: 1em; width: 1em; vertical-align: middle; margin-right: 3px;" src="{{ $iconBase64['internet'] }}">
                    @endif
                    https://www.rscm.co.id
                </div>
            </td>
        </tr>
    </table>

    <div class="doc-title">
        NOTA DINAS<br>
        <span class="nomor-surat">NOMOR : {{ $nomorSurat }}</span>
    </div>

    <table class="meta-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 15%;">Yth</td>
            <td style="width: 3%;">:</td>
            <td>Terlampir</td>
        </tr>
        <tr>
            <td>Dari</td>
            <td>:</td>
            <td>{{ $signerPosition }}</td>
        </tr>
        <tr>
            <td>Hal</td>
            <td>:</td>
            <td>Permohonan Narasumber {{ $hal }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ $tanggalSuratFormatted }}</td>
        </tr>
    </table>
    
    <div style="border-bottom: 2px solid #000; margin-bottom: 20px;"></div>

    <div class="main-text">
        <p>Dalam rangka meningkatkan kompetensi tenaga keperawatan dalam asuhan keperawatan dan kolaborasi interprofesional, akan dilaksanakan kegiatan <strong>{{ $hal }}</strong> secara luring pada:</p>
        
        <table class="event-detail-table" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 100px;">tanggal</td>
                <td style="width: 20px;">:</td>
                <td>{{ $hariTanggalAcara }}</td>
            </tr>
            <tr>
                <td>waktu</td>
                <td>:</td>
                <td>{{ $waktuAcara }}</td>
            </tr>
            <tr>
                <td>tempat</td>
                <td>:</td>
                <td>{{ $tempat }}</td>
            </tr>
        </table>

        <p>Demikian hal ini kami sampaikan, mohon Kepala Unit dapat menugaskan peserta terlampir untuk mengikuti kegiatan sesuai dengan jadwal yang telah ditetapkan. Informasi lebih lanjut dapat menghubungi contact person Tim Kerja {{ $picUnitName }} : {{ $picName }} ({{ $picPhone }}).</p>
    </div>

    <table class="signature-table">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center;">
                <div class="qr-placeholder">
                    <br><small>QR TTE</small>
                </div>
                <div class="signer-position">{{ $signerPosition }}</div>
                <div class="signer-name"><strong>{{ $namaPengirim }}</strong></div>
                <div class="signer-nip">NIP. {{ $nipPengirim }}</div>
            </td>
        </tr>
    </table>

    <div class="page-footer">
        Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik<br>
        yang diterbitkan oleh Balai Besar Sertifikasi Elektronik (BSrE), Badan Siber dan Sandi Negara (BSSN).
    </div>
</div>

</body>
</html>