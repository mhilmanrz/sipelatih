<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Pemanggilan Peserta</title>
    <style>
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

        .wrapper {
            width: 100%;
        }

        /* Reused from nota-dinas */
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

        /* Meta Table */
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .meta-table td {
            vertical-align: top;
            padding-bottom: 5px;
        }

        /* Content */
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

        /* Signature */
        .signature-table {
            width: 100%;
            margin-top: 30px;
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

        /* Lampiran Table */
        .peserta-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 10.5pt;
        }

        .peserta-table th,
        .peserta-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }

        .peserta-table th {
            text-align: center;
            font-weight: bold;
        }

        .peserta-table td.center {
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        {{-- Reuse header dari nota-dinas --}}
        @include('pdf.nota-dinas.header')

        @include('pdf.surat-pemanggilan.doc-title')

        @include('pdf.surat-pemanggilan.meta-table')

        <div style="border-bottom: 2px solid #000; margin-bottom: 20px;"></div>

        @include('pdf.surat-pemanggilan.body')

        @include('pdf.surat-pemanggilan.signature')

        {{-- Reuse footer dari nota-dinas --}}
        @include('pdf.nota-dinas.footer')

        @include('pdf.surat-pemanggilan.lampiran')
    </div>

</body>

</html>
