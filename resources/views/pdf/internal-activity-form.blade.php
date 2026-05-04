<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Formulir Permintaan Kegiatan Internal</title>
    <style>
        @page { margin: 40px 50px; }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px; /* Smaller font to fit everything */
            color: #000;
            line-height: 1.3;
        }
        
        .header {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        table.header-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .header-logo {
            width: 120px;
            vertical-align: middle;
            text-align: left;
        }
        
        .header-logo img {
            max-height: 90px;
            max-width: 150px;
        }
        
        .header-title {
            text-align: center;
            vertical-align: middle;
        }
        
        .header-title h2 {
            margin: 0;
            padding: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        
        .header-title h3 {
            margin: 3px 0 0 0;
            padding: 0;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: normal;
        }
        
        .header-empty {
            width: 120px;
        }

        .section-header {
            background-color: #000;
            color: #fff;
            font-weight: bold;
            padding: 3px 5px;
            margin-top: 15px;
            margin-bottom: 5px;
            font-size: 11px;
        }

        .underline-text {
            border-bottom: 1px solid #000;
            display: inline-block;
            width: 100%;
            min-height: 15px;
            margin-bottom: 5px;
        }

        table.details-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        table.details-table td {
            vertical-align: top;
            padding: 3px 0;
        }

        .label-col {
            width: 130px;
            font-weight: bold;
        }

        .colon-col {
            width: 15px;
        }

        table.target-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.target-table td {
            vertical-align: bottom;
            padding-bottom: 15px;
        }

        table.signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table.signature-table td {
            width: 25%;
            vertical-align: top;
            padding-right: 15px;
        }

        .signature-box {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }

        .signature-line {
            border-top: 1px solid #000;
            padding-top: 3px;
            font-weight: bold;
            font-size: 10px;
        }

        .checkbox-container {
            margin-bottom: 3px;
        }

        .check {
            font-family: DejaVu Sans, sans-serif; /* For checkmark character */
        }
    </style>
</head>
<body>

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Logo Kemenkes / RSCM">
                    @else
                        <!-- Fallback jika logo belum disetting -->
                        <div style="border: 1px solid #ccc; width: 80px; height: 50px; text-align: center; line-height: 50px; font-size: 9px; color: #999;">
                            [LOGO DI SINI]
                        </div>
                    @endif
                </td>
                <td class="header-title">
                    <h2>FORMULIR PERMINTAAN KEGIATAN</h2>
                    <h3>PENGEMBANGAN KOMPETENSI INTERNAL</h3>
                </td>
                <td class="header-empty"></td>
            </tr>
        </table>
    </div>

    <div class="content">
        
        <div class="section-header">Tujuan Kegiatan Pengembangan Kompetensi</div>
        <div style="margin-bottom: 10px;">
            <div style="font-weight: bold; margin-bottom: 5px;">{{ $kegiatan->tujuan ?? '........................................................................................................................................' }}</div>
            @if(!$kegiatan->tujuan)
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 48%; border-bottom: 1px solid #000; height: 15px;"></td>
                    <td style="width: 4%;"></td>
                    <td style="width: 48%; border-bottom: 1px solid #000; height: 15px;"></td>
                </tr>
                <tr>
                    <td style="width: 48%; border-bottom: 1px solid #000; height: 20px;"></td>
                    <td style="width: 4%;"></td>
                    <td style="width: 48%; border-bottom: 1px solid #000; height: 20px;"></td>
                </tr>
            </table>
            @endif
        </div>

        <div class="section-header">Detail Kegiatan Pengembangan Kompetensi</div>
        <table class="details-table">
            <tr>
                <td class="label-col">Judul Kegiatan</td>
                <td class="colon-col">:</td>
                <td style="width: 35%;">{{ $kegiatan->activityName->name ?? ($kegiatan->name ?? '-') }}</td>
                
                <td class="label-col" style="padding-left: 20px;">Biaya Kegiatan</td>
                <td class="colon-col">:</td>
                <td>Rp {{ number_format($kegiatan->budget_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label-col">Penyelenggara</td>
                <td class="colon-col">:</td>
                <td>{{ $kegiatan->workUnit->name ?? '-' }}</td>
                
                <td class="label-col" style="padding-left: 20px;">Tempat/Lokasi</td>
                <td class="colon-col">:</td>
                <td>{{ $kegiatan->tempat ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Tanggal Kegiatan</td>
                <td class="colon-col">:</td>
                <td>
                    @if ($kegiatan->start_date && $kegiatan->end_date)
                        {{ \Carbon\Carbon::parse($kegiatan->start_date)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($kegiatan->end_date)->translatedFormat('d F Y') }}
                    @else
                        Terlampir
                    @endif
                </td>
                
                <td class="label-col" style="padding-left: 20px;" rowspan="3">Sumber Dana</td>
                <td class="colon-col" rowspan="3">:</td>
                <td rowspan="3">
                    @php
                        $fund = $kegiatan->fundSource->name ?? '';
                    @endphp
                    <div class="checkbox-container"><span class="check">{{ $fund == 'DIPA RSCM' ? '√' : '☐' }}</span> DIPA RSCM</div>
                    <div class="checkbox-container"><span class="check">{{ $fund == 'APBN Kemenkes' ? '√' : '☐' }}</span> APBN Kemenkes</div>
                    <div class="checkbox-container">
                        <span class="check">{{ (!in_array($fund, ['DIPA RSCM', 'APBN Kemenkes']) && $fund != '') ? '√' : '☐' }}</span> Lainnya, Mohon Sebutkan
                        @if(!in_array($fund, ['DIPA RSCM', 'APBN Kemenkes']) && $fund != '')
                            <i>({{ $fund }})</i>
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-col">Metode Pembelajaran</td>
                <td class="colon-col">:</td>
                <td>{{ $kegiatan->activityMethod->name ?? '-' }}</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
        </table>

        <div class="section-header">Justifikasi Kegiatan Pengembangan Kompetensi</div>
        <div style="margin-bottom: 10px;">
            {{ $kegiatan->justifikasi ?? '........................................................................................................................................' }}
        </div>

        <div class="section-header">Target Kompetensi Setelah Mengikuti Kegiatan Pengembangan Kompetensi</div>
        <div style="font-style: italic; margin-bottom: 15px;">
            (Diskusikan dengan atasan langsung/kepala unit kerja anda mengenai target/tugas kinerja pasca kegiatan yang spesifik)
        </div>

        @php
            $targetLabels = [
                '<i>(1 minggu pasca pelatihan)</i>',
                '<i>(3-6 bulan)</i>',
                '<i>(6-12 bulan)</i>'
            ];
            $startDate = $kegiatan->start_date ? \Carbon\Carbon::parse($kegiatan->start_date) : null;
            $tanggalTinjauan = [
                $startDate ? $startDate->copy()->addWeeks(1)->translatedFormat('d/m/Y') : 'HH/BB/TTTT',
                $startDate ? $startDate->copy()->addMonths(6)->translatedFormat('d/m/Y') : 'HH/BB/TTTT',
                $startDate ? $startDate->copy()->addMonths(12)->translatedFormat('d/m/Y') : 'HH/BB/TTTT',
            ];
        @endphp

        <table class="target-table">
            @for ($i = 0; $i < 3; $i++)
                @php
                    $targetDesc = '.........................................................................................................';
                    if (isset($kegiatan->activityTargets) && $kegiatan->activityTargets->count() > $i) {
                        $targetDesc = $kegiatan->activityTargets[$i]->description;
                    } elseif ($i == 0 && isset($kegiatan->target_kompetensi) && $kegiatan->target_kompetensi != '') {
                        $targetDesc = $kegiatan->target_kompetensi;
                    }
                @endphp
                <tr>
                    <td style="width: 70%;">
                        <b>Target/Tugas ({{ $i + 1 }}):</b> {!! $targetLabels[$i] !!}<br>
                        {{ $targetDesc }}
                    </td>
                    <td style="width: 30%; text-align: center; font-weight: bold;">
                        Tanggal Tinjauan<br>
                        <i style="font-size: 10px;">{{ $tanggalTinjauan[$i] }}</i>
                    </td>
                </tr>
            @endfor
        </table>

        <table class="signature-table">
            <tr>
                <td>Pemohon,</td>
                <td>Menyetujui,</td>
                <td>Menyetujui,</td>
                <td>Menyetujui,</td>
            </tr>
            
            <tr>
                <td colspan="4" style="height: 30px;"></td>
            </tr>
            <tr>
                <td><div class="signature-box">$(ttd_pengirim1)</div></td>
                <td><div class="signature-box">$(ttd_pengirim2)</div></td>
                <td><div class="signature-box">$(ttd_pengirim3)</div></td>
                <td><div class="signature-box">$(ttd_pengirim4)</div></td>
            </tr>
            <tr>
                <td>
                    <div class="signature-line">
                        ${nama_pengirim1}<br>
                        <span style="font-weight: normal;">NIP ${nip_pengirim1}</span>
                    </div>
                </td>
                <td>
                    <div class="signature-line">
                        ${nama_pengirim2}<br>
                        <span style="font-weight: normal;">NIP ${nip_pengirim2}</span>
                    </div>
                </td>
                <td>
                    <div class="signature-line">
                        ${nama_pengirim3}<br>
                        <span style="font-weight: normal;">NIP ${nip_pengirim3}</span>
                    </div>
                </td>
                <td>
                    <div class="signature-line">
                        ${nama_pengirim4}<br>
                        <span style="font-weight: normal;">NIP ${nip_pengirim4}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="height: 20px;"></td>
            </tr>
            <tr>
                <td colspan="4">Catatan: Lampirkan Nota Dinas, kerangka acuan kegiatan dan atau dokumen terkait lainnya</td>
            </tr>
        </table>

    </div>

</body>
</html>
