<?php

namespace App\Http\Controllers;

use App\Models\Act\Activity;
use App\Models\AppSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Language;
use Symfony\Component\HttpFoundation\Response;

class SuratTugasController extends Controller
{
    /**
     * Download Surat Tugas as PDF for a specific kegiatan.
     */
    public function downloadPdf(Activity $kegiatan): Response
    {
        $data = $this->getData($kegiatan);

        $pdf = Pdf::loadView('pdf.surat-tugas', $data);
        $pdf->setPaper('A4', 'portrait');

        $fileName = 'Surat_Tugas_'.($data['nomorSurat'] !== '-' ? $data['nomorSurat'] : $kegiatan->id).'.pdf';

        return $pdf->stream($fileName);
    }

    /**
     * Download Surat Pemanggilan Peserta as DOCX for a specific kegiatan.
     */
    public function downloadDocx(Activity $kegiatan): Response
    {
        $data = $this->getData($kegiatan);

        $phpWord = new PhpWord;
        $phpWord->getSettings()->setThemeFontLang(new Language('id-ID'));

        $section = $phpWord->addSection([
            'marginLeft' => 1417,
            'marginRight' => 1417,
            'marginTop' => 567,
            'marginBottom' => 567,
        ]);

        // --- HEADER ---
        $kopPath = $data['kopPath'] ? storage_path('app/public/'.$data['kopPath']) : null;
        if ($kopPath && file_exists($kopPath)) {
            $section->addImage($kopPath, [
                'width' => 450,
                'alignment' => Jc::CENTER,
            ]);
        } else {
            $headerTable = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
            $headerTable->addRow();
            $logoCell = $headerTable->addCell(30 * 50);
            $infoCell = $headerTable->addCell(70 * 50);

            $logoPath = $data['logoPath'] ? storage_path('app/public/'.$data['logoPath']) : null;
            if ($logoPath && file_exists($logoPath)) {
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
        }

        $section->addText('', [], ['borderBottom' => ['size' => 2, 'color' => '000000']]);

        // --- TITLE ---
        $section->addText('SURAT TUGAS', ['bold' => true, 'size' => 14, 'align' => 'center']);
        $section->addText('NOMOR '.$data['nomorSurat'], ['size' => 11, 'align' => 'center']);

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
        $metaTable->addCell(82 * 50)->addText('${hal}');

        $metaTable->addRow();
        $metaTable->addCell(15 * 50)->addText('Tanggal');
        $metaTable->addCell(3 * 50)->addText(':');
        $metaTable->addCell(82 * 50)->addText('${tanggal_naskah}');

        $section->addText('', [], ['borderBottom' => ['size' => 2, 'color' => '000000']]);

        // --- BODY ---
        $section->addText(
            'Sehubungan dengan kegiatan '.$data['hal'].' yang bertujuan untuk '.$data['kegiatan']->tujuan.', dengan ini kami menugaskan kepada:',
            ['size' => 11],
            ['indentation' => ['firstLine' => 567]]
        );

        $section->addText('');
        $section->addText('(nama, NIP/NPS, pangkat/ golongan dan jabatan terlampir)', ['size' => 11, 'align' => 'center']);
        $section->addText('');

        $detailTable = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
        $detailTable->addRow();
        $detailTable->addCell(15 * 50)->addText('untuk', ['size' => 11]);
        $detailTable->addCell(3 * 50)->addText(':', ['size' => 11]);
        $detailTable->addCell(5 * 50)->addText('1.', ['size' => 11]);
        $detailTable->addCell(77 * 50)->addText('sebagai peserta dan panitia kegiatan '.$data['hal'].' pada tanggal '.$data['hariTanggalAcara'].' di '.$data['tempat'].';', ['size' => 11]);

        $detailTable->addRow();
        $detailTable->addCell(15 * 50);
        $detailTable->addCell(3 * 50);
        $detailTable->addCell(5 * 50)->addText('2.', ['size' => 11]);
        $detailTable->addCell(77 * 50)->addText('tidak melakukan rekam absensi datang atau pulang.', ['size' => 11]);

        $section->addText('');
        $section->addText('Agar yang bersangkutan melaksanakan tugas dengan baik dan penuh tanggung jawab.', ['size' => 11], ['indentation' => ['firstLine' => 567]]);

        // --- SIGNATURE ---
        $section->addText('');
        $section->addText('');

        $sigTable = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
        $sigTable->addRow();
        $sigTable->addCell(60 * 50);
        $sigCell = $sigTable->addCell(40 * 50);
        $sigCell->addText(\Carbon\Carbon::now()->translatedFormat('d F Y'), ['align' => 'left', 'size' => 11]);
        $sigCell->addText('Direktur Utama,', ['align' => 'left', 'size' => 11]);
        $sigCell->addText('');
        $sigCell->addText('');
        $sigCell->addText('');
        $sigCell->addText('dr. Supriyanto, Sp.B, FINACS, M.Kes', ['align' => 'left', 'bold' => true, 'size' => 11]);

        $section->addText(
            'Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan oleh Balai Besar Sertifikasi Elektronik (BSrE), Badan Siber dan Sandi Negara (BSSN).',
            ['size' => 7.5, 'color' => '444444', 'align' => 'center']
        );

        // --- LAMPIRAN ---
        $section->addPageBreak();

        // Lampiran Header
        $lampiranHeaderTable = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
        $lampiranHeaderTable->addRow();
        $lampiranHeaderTable->addCell(60 * 50); // Empty left
        $lampiranInfoCell = $lampiranHeaderTable->addCell(40 * 50);

        $lampiranInfoCell->addText('Lampiran - 1', ['bold' => true, 'size' => 11]);
        $lampiranMetaTable = $lampiranInfoCell->addTable();
        $lampiranMetaTable->addRow();
        $lampiranMetaTable->addCell(1500)->addText('Nomor', ['size' => 11]);
        $lampiranMetaTable->addCell(300)->addText(':', ['size' => 11]);
        $lampiranMetaTable->addCell(3000)->addText('${nomor_naskah}', ['size' => 11]);
        $lampiranMetaTable->addRow();
        $lampiranMetaTable->addCell(1500)->addText('Tanggal', ['size' => 11]);
        $lampiranMetaTable->addCell(300)->addText(':', ['size' => 11]);
        $lampiranMetaTable->addCell(3000)->addText('${tanggal_naskah}', ['size' => 11]);

        $section->addText('');
        $section->addText('Daftar Peserta yang terdaftar', ['bold' => true, 'size' => 11]);

        // Lampiran Table
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
        ];
        $phpWord->addTableStyle('PesertaTable', $tableStyle);
        $pesertaTable = $section->addTable('PesertaTable');

            $pesertaTable->addCell(500, ['bgColor' => 'F2F2F2'])->addText('No.', ['bold' => true, 'size' => 11]);
            $pesertaTable->addCell(2500, ['bgColor' => 'F2F2F2'])->addText('Nama', ['bold' => true, 'size' => 11]);
            $pesertaTable->addCell(2000, ['bgColor' => 'F2F2F2'])->addText('NIP/NPS', ['bold' => true, 'size' => 11]);
            $pesertaTable->addCell(2000, ['bgColor' => 'F2F2F2'])->addText('Pangkat/ Golongan', ['bold' => true, 'size' => 11]);
            $pesertaTable->addCell(3000, ['bgColor' => 'F2F2F2'])->addText('Jabatan', ['bold' => true, 'size' => 11]);

        if ($data['peserta']->isEmpty()) {
            $pesertaTable->addRow();
            $pesertaTable->addCell(9500, ['gridSpan' => 4])->addText('Belum ada peserta terdaftar.', ['italic' => true, 'color' => '666666', 'align' => 'center']);
        } else {
            $index = 1;
            foreach ($data['peserta'] as $p) {
                $pesertaTable->addRow();
                $pesertaTable->addCell(500)->addText($index.'.', ['size' => 11]);
                $pesertaTable->addCell(2500)->addText($p->user?->name ?? '-', ['size' => 11]);
                $pesertaTable->addCell(2000)->addText($p->user?->employee_id ?? '-', ['size' => 11]);
                $pesertaTable->addCell(2000)->addText($p->user?->rank?->name ?? '-', ['size' => 11]);
                $pesertaTable->addCell(3000)->addText($p->user?->position?->name ?? '-', ['size' => 11]);
                $index++;
            }
        }

        // --- LAMPIRAN NARASUMBER ---
        if ($data['narasumber']->isNotEmpty()) {
            $section->addPageBreak();

            // Lampiran Header
            $lampiranHeaderTable = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
            $lampiranHeaderTable->addRow();
            $lampiranHeaderTable->addCell(60 * 50); // Empty left
            $lampiranInfoCell = $lampiranHeaderTable->addCell(40 * 50);

            $lampiranInfoCell->addText('Lampiran - 2', ['bold' => true, 'size' => 11]);
            $lampiranMetaTable = $lampiranInfoCell->addTable();
            $lampiranMetaTable->addRow();
            $lampiranMetaTable->addCell(1500)->addText('Nomor', ['size' => 11]);
            $lampiranMetaTable->addCell(300)->addText(':', ['size' => 11]);
            $lampiranMetaTable->addCell(3000)->addText('${nomor_naskah}', ['size' => 11]);
            $lampiranMetaTable->addRow();
            $lampiranMetaTable->addCell(1500)->addText('Tanggal', ['size' => 11]);
            $lampiranMetaTable->addCell(300)->addText(':', ['size' => 11]);
            $lampiranMetaTable->addCell(3000)->addText('${tanggal_naskah}', ['size' => 11]);

            $section->addText('');
            $section->addText('Daftar Narasumber yang ditugaskan', ['bold' => true, 'size' => 11]);

            // Lampiran Table (Reuse Table Style)
            $narasumberTable = $section->addTable('PesertaTable');

            $narasumberTable->addRow();
            $narasumberTable->addCell(500, ['bgColor' => 'F2F2F2'])->addText('No.', ['bold' => true, 'size' => 11]);
            $narasumberTable->addCell(2500, ['bgColor' => 'F2F2F2'])->addText('Nama', ['bold' => true, 'size' => 11]);
            $narasumberTable->addCell(2000, ['bgColor' => 'F2F2F2'])->addText('NIP/NPS', ['bold' => true, 'size' => 11]);
            $narasumberTable->addCell(2000, ['bgColor' => 'F2F2F2'])->addText('Pangkat/ Golongan', ['bold' => true, 'size' => 11]);
            $narasumberTable->addCell(3000, ['bgColor' => 'F2F2F2'])->addText('Jabatan', ['bold' => true, 'size' => 11]);

            $index = 1;
            foreach ($data['narasumber'] as $n) {
                $narasumberTable->addRow();
                $narasumberTable->addCell(500)->addText($index.'.', ['size' => 11]);
                $narasumberTable->addCell(2500)->addText($n->user?->name ?? '-', ['size' => 11]);
                $narasumberTable->addCell(2000)->addText($n->user?->employee_id ?? '-', ['size' => 11]);
                $narasumberTable->addCell(2000)->addText($n->user?->rank?->name ?? '-', ['size' => 11]);
                $narasumberTable->addCell(3000)->addText($n->user?->position?->name ?? '-', ['size' => 11]);
                $index++;
            }
        }

        // --- SAVE ---
        $fileName = 'Surat_Tugas_'.($data['nomorSurat'] !== '-' ? $data['nomorSurat'] : $kegiatan->id).'.docx';

        $tempPath = storage_path('app/temp');
        if (! is_dir($tempPath)) {
            mkdir($tempPath, 0755, true);
        }

        $filePath = $tempPath.'/'.$fileName;
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filePath);

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Gather all dynamic data for the Surat Pemanggilan document.
     */
    private function getData(Activity $kegiatan): array
    {
        $kegiatan->loadMissing([
            'activityName',
            'picUser.workUnit',
            'activityParticipants.user.rank',
            'activityParticipants.user.position',
            'activityMaterials.speakers.user.rank',
            'activityMaterials.speakers.user.position',
        ]);

        $settings = AppSetting::pluck('value', 'key');

        // Logo (fallback)
        $logoBase64 = null;
        $logoPath = $settings['kemenkes_logo'] ?? null;
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            $path = storage_path('app/public/'.$logoPath);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/'.$type.';base64,'.base64_encode(file_get_contents($path));
        }

        // Kop (full-width header image)
        $kopBase64 = null;
        $kopPath = $settings['nota_dinas_kop'] ?? null;
        if ($kopPath && Storage::disk('public')->exists($kopPath)) {
            $path = storage_path('app/public/'.$kopPath);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $kopBase64 = 'data:image/'.$type.';base64,'.base64_encode(file_get_contents($path));
        }

        // Date formatting
        Carbon::setLocale('id');
        $tanggalSuratFormatted = Carbon::parse($kegiatan->date)->translatedFormat('j F Y');

        // Event date formatting
        $startDate = Carbon::parse($kegiatan->start_date);
        $endDate = Carbon::parse($kegiatan->end_date);

        if ($startDate->isSameDay($endDate)) {
            $hariTanggalAcara = $startDate->translatedFormat('l, j F Y');
        } else {
            $hariTanggalAcara = $startDate->translatedFormat('l')."\u{2013}".$endDate->translatedFormat('l').', '.
                $startDate->format('j')."\u{2013}".$endDate->translatedFormat('j F Y');
        }

        // Time formatting
        $waktuAcara = 'Pukul '.
            Carbon::parse($kegiatan->start_time)->format('H.i').
            ' – '.
            Carbon::parse($kegiatan->end_time)->format('H.i').
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

        // Peserta list (sorted by name)
        $peserta = $kegiatan->activityParticipants
            ->sortBy(fn ($p) => $p->user?->name);

        // Narasumber list
        $narasumber = collect();
        if ($kegiatan->relationLoaded('activityMaterials')) {
            foreach ($kegiatan->activityMaterials as $material) {
                foreach ($material->speakers as $speaker) {
                    $narasumber->push($speaker);
                }
            }
        }
        $narasumber = $narasumber->unique('user_id')->sortBy(fn ($s) => $s->user?->name);

        return [
            'kegiatan' => $kegiatan,
            'logoBase64' => $logoBase64,
            'kopBase64' => $kopBase64,
            'logoPath' => $logoPath,
            'kopPath' => $kopPath,
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
            'peserta' => $peserta,
            'narasumber' => $narasumber,
        ];
    }
}
