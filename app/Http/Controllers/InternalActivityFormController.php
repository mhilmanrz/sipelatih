<?php

namespace App\Http\Controllers;

use App\Models\Act\Activity;
use App\Models\AppSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InternalActivityFormController extends Controller
{
    /**
     * Generate and stream the Internal Activity Request Form PDF.
     */
    public function streamPdf(Request $request, $id): Response
    {
        $kegiatan = Activity::with([
            'activityName',
            'workUnit',
            'picUser',
            'activityType',
            'activityCategory',
            'activityScope',
            'materialType',
            'fundSource',
            'activityTargets',
        ])->findOrFail($id);

        // Get the logo from settings or use a default if not set
        $logoPath = AppSetting::get('kemenkes_logo');
        $logoBase64 = null;

        if ($logoPath && file_exists(storage_path('app/public/'.$logoPath))) {
            $path = storage_path('app/public/'.$logoPath);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoBase64 = 'data:image/'.$type.';base64,'.base64_encode($data);
        }

        $pdf = Pdf::loadView('pdf.internal-activity-form', [
            'logoBase64' => $logoBase64,
            'kegiatan' => $kegiatan,
        ]);

        // You can set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Formulir_Permintaan_Kegiatan_Internal_'.$kegiatan->id.'.pdf');
    }
}
