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

        $iconBase64 = [];
        foreach (['maps', 'telephone', 'web'] as $icon) {
            $iconPath = public_path("assets/icons/{$icon}.svg");
            if (file_exists($iconPath)) {
                $iconBase64[$icon] = 'data:image/svg+xml;base64,'.base64_encode(file_get_contents($iconPath));
            }
        }

        $pdf = Pdf::loadView('pdf.internal-activity-form', [
            'logoBase64' => $logoBase64,
            'iconBase64' => $iconBase64,
            'kegiatan' => $kegiatan,
        ]);

        // You can set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Formulir_Permintaan_Kegiatan_Internal_'.$kegiatan->id.'.pdf');
    }
}
