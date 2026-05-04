<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use App\Models\Act\ActivityGradeCategory;
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
            'grade_categories' => 'nullable|array',
            'grade_categories.*.label' => 'required|string|max:100',
            'grade_categories.*.min_score' => 'required|numeric|min:0|max:100',
            'grade_categories.*.max_score' => 'required|numeric|min:0|max:100',
            'grade_categories.*.color' => 'nullable|string|max:30',
            'grade_categories.*.order' => 'nullable|integer|min:1',
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

        // Simpan Kategori Nilai
        if ($request->has('has_grade_categories')) {
            $gradeCategories = $request->grade_categories ?? [];

            if (! empty($gradeCategories)) {
                // Validasi range per kategori
                foreach ($gradeCategories as $i => $cat) {
                    $min = (float) $cat['min_score'];
                    $max = (float) $cat['max_score'];

                    if ($min >= $max) {
                        return back()->withErrors([
                            "grade_categories.{$i}.min_score" => "Nilai minimal harus lebih kecil dari nilai maksimal pada kategori \"{$cat['label']}\".",
                        ])->withInput();
                    }
                }

                // Validasi tidak ada overlap antar range
                $sorted = $gradeCategories;
                usort($sorted, fn ($a, $b) => (float) $a['min_score'] <=> (float) $b['min_score']);

                for ($i = 0; $i < count($sorted) - 1; $i++) {
                    $currentMax = (float) $sorted[$i]['max_score'];
                    $nextMin = (float) $sorted[$i + 1]['min_score'];

                    if ($currentMax >= $nextMin) {
                        return back()->withErrors([
                            'grade_categories' => "Range nilai \"{$sorted[$i]['label']}\" ({$sorted[$i]['min_score']}-{$sorted[$i]['max_score']}) tumpang tindih dengan \"{$sorted[$i + 1]['label']}\" ({$sorted[$i + 1]['min_score']}-{$sorted[$i + 1]['max_score']}). Pastikan tidak ada range yang overlap.",
                        ])->withInput();
                    }
                }
            }

            $kegiatan->gradeCategories()->delete();

            foreach ($gradeCategories as $index => $cat) {
                ActivityGradeCategory::create([
                    'activity_id' => $kegiatan->id,
                    'label' => $cat['label'],
                    'min_score' => $cat['min_score'],
                    'max_score' => $cat['max_score'],
                    'color' => $cat['color'] ?? null,
                    'order' => $cat['order'] ?? $index + 1,
                ]);
            }
        }

        return back()->with('success', 'Pengaturan penilaian berhasil disimpan.');
    }
}
