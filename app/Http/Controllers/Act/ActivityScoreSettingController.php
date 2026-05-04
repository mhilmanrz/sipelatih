<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use App\Models\Act\ActivityScoreComponent;
use App\Models\Act\ActivityScoreSetting;
use Illuminate\Http\Request;

class ActivityScoreSettingController extends Controller
{
    public function update(Request $request, $kegiatan_id)
    {
        $request->validate([
            'passing_threshold' => 'required|numeric|min:0|max:100',
            'components' => 'required|array|min:1',
            'components.*.name' => 'required|string|max:100',
            'components.*.type' => 'required|in:pre_test,post_test,custom',
            'components.*.percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $kegiatan = Activity::findOrFail($kegiatan_id);

        // Validasi: harus ada tepat 1 pre_test dan 1 post_test
        $types = collect($request->components)->pluck('type');
        if ($types->filter(fn ($t) => $t === 'pre_test')->count() !== 1) {
            return back()->withErrors(['components' => 'Harus ada tepat satu komponen Pre Test.'])->withInput();
        }
        if ($types->filter(fn ($t) => $t === 'post_test')->count() !== 1) {
            return back()->withErrors(['components' => 'Harus ada tepat satu komponen Post Test.'])->withInput();
        }

        // Validasi: total persentase komponen non-pre_test harus = 100
        $totalPercentage = collect($request->components)
            ->filter(fn ($c) => $c['type'] !== 'pre_test')
            ->sum(fn ($c) => (float) ($c['percentage'] ?? 0));

        if (abs($totalPercentage - 100) > 0.01) {
            return back()->withErrors(['components' => "Total persentase komponen penilaian (Post Test + Custom) harus = 100%. Saat ini: {$totalPercentage}%"])->withInput();
        }

        // Simpan setting
        ActivityScoreSetting::updateOrCreate(
            ['activity_id' => $kegiatan->id],
            ['passing_threshold' => $request->passing_threshold]
        );

        // Hapus komponen lama dan buat ulang
        $kegiatan->scoreComponents()->delete();

        foreach ($request->components as $index => $comp) {
            ActivityScoreComponent::create([
                'activity_id' => $kegiatan->id,
                'name' => $comp['name'],
                'type' => $comp['type'],
                'percentage' => $comp['type'] !== 'pre_test' ? $comp['percentage'] : null,
                'order' => $index + 1,
            ]);
        }

        return back()->with('success', 'Pengaturan penilaian berhasil disimpan.');
    }
}
