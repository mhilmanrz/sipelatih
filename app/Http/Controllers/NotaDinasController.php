<?php

namespace App\Http\Controllers;

use App\Models\Act\Activity;
use App\Models\Act\ActivitySpeaker;
use App\Models\AppSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Language;

class NotaDinasController extends Controller
{
    /**
     * Stream the test PDF (legacy route).
     */
    public function streamPdf()
    {
        $settings = AppSetting::pluck('value', 'key');
        $logoBase64 = null;

        if (isset($settings['kemenkes_logo']) && Storage::disk('public')->exists($settings['kemenkes_logo'])) {
            $path = storage_path('app/public/' . $settings['kemenkes_logo']);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $iconBase64 = [];
        foreach (['map' => 'map', 'phone' => 'phone', 'internet' => 'internet'] as $key => $file) {
            $iconPath = public_path("assets/icons/{$file}.png");
            if (file_exists($iconPath)) {
                $iconBase64[$key] = 'data:image/png;base64,' . base64_encode(file_get_contents($iconPath));
            }
        }

        $pdf = Pdf::loadView('pdf.nota-dinas', compact('logoBase64', 'iconBase64'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Nota_Dinas_Permohonan_Narasumber.pdf');
    }

    /**
     * Download Nota Dinas as PDF for a specific speaker.
     */
    public function downloadPdf(Request $request, $kegiatanId, $speakerId)
    {
        $data = $this->getNotaDinasData($kegiatanId, $speakerId);

        $pdf = Pdf::loadView('pdf.nota-dinas', $data);
        $pdf->setPaper('A4', 'portrait');

        $fileName = 'Nota_Dinas_' . $data['nomorSurat'] . '.pdf';

        return $pdf->stream($fileName);
    }

    /**
     * Download Nota Dinas as DOCX for a specific speaker.
     */
    public function downloadDocx(Request $request, $kegiatanId, $speakerId)
    {
        $data = $this->getNotaDinasData($kegiatanId, $speakerId);

        $phpWord = new PhpWord;
        $phpWord->getSettings()->setThemeFontLang(new Language('id-ID'));

        $section = $phpWord->addSection([
            'marginLeft' => 1417, // 2.5cm in twips (1cm = 567 twips)
            'marginRight' => 1417,
            'marginTop' => 567, // 1cm
            'marginBottom' => 567,
        ]);

        // --- HEADER TABLE ---
        $headerTable = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
        $headerTable->addRow();
        $logoCell = $headerTable->addCell(30 * 50);
        $infoCell = $headerTable->addCell(70 * 50);

        // Logo image or fallback text
        $logoPath = storage_path('app/public/' . $data['logoPath']);
        if ($data['logoPath'] && file_exists($logoPath)) {
            $logoCell->addImage($logoPath, ['width' => 180, 'height' => 50]);
        } else {
            $logoCell->addText('Kemenkes', ['bold' => true, 'size' => 18, 'color' => '0D9488']);
            $logoCell->addText('RS Cipto Mangunkusumo', ['bold' => true, 'size' => 9, 'color' => '64748B']);
        }

        $infoCell->addText('Kementerian Kesehatan', ['bold' => true, 'size' => 14, 'color' => '0D9488']);
        $infoCell->addText('Direktorat Jenderal Kesehatan Lanjutan', ['bold' => true, 'size' => 12]);
        $infoCell->addText('RSUP Nasional Dr. Cipto Mangunkusumo Jakarta', ['size' => 11]);
        $infoCell->addText('Jalan Diponegoro Nomor 71 Jakarta 10430', ['size' => 8.5, 'color' => '666666']);
        $infoCell->addText('1500135', ['size' => 8.5, 'color' => '666666']);
        $infoCell->addText('https://www.rscm.co.id', ['size' => 8.5, 'color' => '666666']);

        // Horizontal line
        $section->addText('', [], ['borderBottom' => ['size' => 2, 'color' => '000000']]);

        // --- TITLE ---
        $section->addText('NOTA DINAS', ['bold' => true, 'size' => 14, 'align' => 'center']);
        $section->addText('NOMOR : ' . $data['nomorSurat'], ['size' => 11, 'align' => 'center']);

        // --- META TABLE ---
        $metaTable = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
        $metaTable->addRow();
        $metaTable->addCell(15 * 50)->addText('Yth');
        $metaTable->addCell(3 * 50)->addText(':');
        $metaTable->addCell(82 * 50)->addText('Terlampir');

        $metaTable->addRow();
        $metaTable->addCell(15 * 50)->addText('Dari');
        $metaTable->addCell(3 * 50)->addText(':');
        $metaTable->addCell(82 * 50)->addText($data['signerPosition']);

        $metaTable->addRow();
        $metaTable->addCell(15 * 50)->addText('Hal');
        $metaTable->addCell(3 * 50)->addText(':');
        $metaTable->addCell(82 * 50)->addText('Permohonan Narasumber ' . $data['hal']);

        $metaTable->addRow();
        $metaTable->addCell(15 * 50)->addText('Tanggal');
        $metaTable->addCell(3 * 50)->addText(':');
        $metaTable->addCell(82 * 50)->addText($data['tanggalSuratFormatted']);

        // Horizontal line
        $section->addText('', [], ['borderBottom' => ['size' => 2, 'color' => '000000']]);

        // --- MAIN TEXT ---
        $section->addText(
            'Dalam rangka meningkatkan kompetensi tenaga keperawatan dalam asuhan keperawatan dan kolaborasi interprofesional, akan dilaksanakan kegiatan '
                . $data['hal']
                . ' secara luring pada:',
            ['size' => 11],
            ['indentation' => ['firstLine' => 567]]
        );

        // --- EVENT DETAIL TABLE ---
        $detailTable = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
        $detailTable->addRow();
        $detailTable->addCell(15 * 50)->addText('tanggal');
        $detailTable->addCell(3 * 50)->addText(':');
        $detailTable->addCell(82 * 50)->addText($data['hariTanggalAcara']);

        $detailTable->addRow();
        $detailTable->addCell(15 * 50)->addText('waktu');
        $detailTable->addCell(3 * 50)->addText(':');
        $detailTable->addCell(82 * 50)->addText($data['waktuAcara']);

        $detailTable->addRow();
        $detailTable->addCell(15 * 50)->addText('tempat');
        $detailTable->addCell(3 * 50)->addText(':');
        $detailTable->addCell(82 * 50)->addText($data['tempat']);

        // --- CLOSING PARAGRAPH ---
        $section->addText(
            'Demikian hal ini kami sampaikan, mohon Kepala Unit dapat menugaskan peserta terlampir untuk mengikuti kegiatan sesuai dengan jadwal yang telah ditetapkan. Informasi lebih lanjut dapat menghubungi contact person Tim Kerja '
                . $data['picUnitName'] . ' : ' . $data['picName'] . ' (' . $data['picPhone'] . ').',
            ['size' => 11],
            ['indentation' => ['firstLine' => 567]]
        );

        // --- SIGNATURE ---
        $section->addText('');
        $section->addText('');

        $sigTable = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
        $sigTable->addRow();
        $sigTable->addCell(60 * 50);
        $sigCell = $sigTable->addCell(40 * 50);
        $sigCell->addText('${ttd_pengirim}', ['align' => 'center', 'size' => 11]);
        $sigCell->addText('${nama_pengirim}', ['align' => 'center', 'bold' => true, 'size' => 11]);
        $sigCell->addText('NIP. ${nip_pengirim}', ['align' => 'center', 'size' => 11]);

        // --- FOOTER ---
        $section->addText(
            'Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan oleh Balai Besar Sertifikasi Elektronik (BSrE), Badan Siber dan Sandi Negara (BSSN).',
            ['size' => 7.5, 'color' => '444444', 'align' => 'center']
        );

        $fileName = 'Nota_Dinas_' . $data['nomorSurat'] . '.docx';

        $tempPath = storage_path('app/temp');
        if (! is_dir($tempPath)) {
            mkdir($tempPath, 0755, true);
        }

        $filePath = $tempPath . '/' . $fileName;
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filePath);

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Gather all dynamic data for the Nota Dinas document.
     */
    private function getNotaDinasData($kegiatanId, $speakerId): array
    {
        $kegiatan = Activity::with([
            'activityName',
            'picUser.workUnit',
        ])->findOrFail($kegiatanId);

        $speaker = ActivitySpeaker::with('user')->findOrFail($speakerId);

        $settings = AppSetting::pluck('value', 'key');

        // Logo
        $logoBase64 = null;
        $logoPath = $settings['kemenkes_logo'] ?? null;
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            $path = storage_path('app/public/' . $logoPath);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $logoData = file_get_contents($path);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($logoData);
        }

        // Icons
        $iconBase64 = [];
        foreach (['map' => 'map', 'phone' => 'phone', 'internet' => 'internet'] as $key => $file) {
            $iconPath = public_path("assets/icons/{$file}.png");
            if (file_exists($iconPath)) {
                $iconBase64[$key] = 'data:image/png;base64,' . base64_encode(file_get_contents($iconPath));
            }
        }

        // Date formatting
        Carbon::setLocale('id');
        $tanggalSurat = Carbon::parse($kegiatan->date);
        $tanggalSuratFormatted = $tanggalSurat->translatedFormat('j F Y');

        // Event date formatting
        $startDate = Carbon::parse($kegiatan->start_date);
        $endDate = Carbon::parse($kegiatan->end_date);

        if ($startDate->isSameDay($endDate)) {
            $hariTanggalAcara = $startDate->translatedFormat('l, j F Y');
        } else {
            $hariTanggalAcara = $startDate->translatedFormat('l') . "\u{2013}" . $endDate->translatedFormat('l') . ', ' .
                $startDate->format('j') . "\u{2013}" . $endDate->translatedFormat('j F Y');
        }

        // Time formatting
        $waktuAcara = 'Pukul ' .
            Carbon::parse($kegiatan->start_time)->format('H.i') .
            ' – ' .
            Carbon::parse($kegiatan->end_time)->format('H.i') .
            ' WIB';

        // PIC data
        $picUser = $kegiatan->picUser;
        $picUnitName = $picUser?->workUnit?->name ?? '-';
        $picName = $picUser?->name ?? '-';
        $picPhone = $picUser?->phone_number ?? '-';

        // Signer data
        $signerPosition = $settings['nota_dinas_signer_position'] ?? '-';
        $namaPengirim = $picUser?->name ?? '-';
        $nipPengirim = $picUser?->employee_id ?? '-';

        return [
            'kegiatan' => $kegiatan,
            'speaker' => $speaker,
            'logoBase64' => $logoBase64,
            'logoPath' => $logoPath,
            'iconBase64' => $iconBase64,
            'nomorSurat' => $kegiatan->reference_number ?? '-',
            'hal' => $kegiatan->activityName?->name ?? '-',
            'tanggalSuratFormatted' => $tanggalSuratFormatted,
            'signerPosition' => $signerPosition,
            'hariTanggalAcara' => $hariTanggalAcara,
            'waktuAcara' => $waktuAcara,
            'tempat' => $kegiatan->tempat ?? '-',
            'picUnitName' => $picUnitName,
            'picName' => $picName,
            'picPhone' => $picPhone,
            'namaPengirim' => $namaPengirim,
            'nipPengirim' => $nipPengirim,
        ];
    }
}
