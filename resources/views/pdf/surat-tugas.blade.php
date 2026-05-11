<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Tugas</title>
    <style>
        @page { margin: 0.5cm 1.5cm; }
        body { font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.4; color: #000; margin: 0; padding: 0; }
        .wrapper { width: 100%; }
        /* Reused from nota-dinas */
        .header-table { width: 100%; }
        .header-table td { vertical-align: top; padding-bottom: 10px; }
        .kemenkes-title { font-size: 14pt; color: #0d9488; font-weight: bold; margin: 0; }
        .dirjen-title { font-size: 12pt; font-weight: bold; margin: 2px 0; }
        .rsup-title { font-size: 11pt; margin: 0 0 8px 0; }
        .contact-item { font-size: 8.5pt; color: #666; margin-bottom: 2px; display: block; line-height: 1.2; }
        /* Document Title */
        .doc-title { text-align: center; font-weight: bold; margin: 20px 0; text-transform: uppercase; }
        .nomor-surat { font-weight: normal; text-transform: none; }
        /* Content */
        .main-text { text-align: justify; }
        .main-text p { margin-bottom: 15px; text-indent: 1cm; }
        /* Signature */
        .signature-table { width: 100%; margin-top: 30px; }
        /* Footer */
        .page-footer { position: fixed; bottom: 0.5cm; left: 0; right: 0; text-align: center; font-size: 7.5pt; color: #444; }
        /* Lampiran Table */
        .peserta-table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 10.5pt; }
        .peserta-table th, .peserta-table td { border: 1px solid #000; padding: 6px 8px; vertical-align: top; }
        .peserta-table th { text-align: center; font-weight: bold; }
        .peserta-table td.center { text-align: center; }
    </style>
</head>
<body>
    <div class="wrapper">
        @include('pdf.surat-tugas.header')
        @include('pdf.surat-tugas.doc-title')
        @include('pdf.surat-tugas.body')
        @include('pdf.surat-tugas.signature')
        @include('pdf.surat-tugas.footer')
        @include('pdf.surat-tugas.lampiran-peserta')
        @if($narasumber->isNotEmpty())
            @include('pdf.surat-tugas.lampiran-narasumber')
        @endif
    </div>
</body>
</html>
