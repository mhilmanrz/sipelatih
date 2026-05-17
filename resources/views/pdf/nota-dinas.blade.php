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
        @include('pdf.nota-dinas.header')

        @include('pdf.nota-dinas.doc-title')

        @include('pdf.nota-dinas.meta-table')

        <div style="border-bottom: 2px solid #000; margin-bottom: 20px;"></div>

        @include('pdf.nota-dinas.body')

        @include('pdf.nota-dinas.signature')

        {{-- @include('pdf.nota-dinas.footer') --}}

        @include('pdf.nota-dinas.lampiran')
    </div>

</body>

</html>
