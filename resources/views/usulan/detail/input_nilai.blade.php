<section>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $scoreComponents = $kegiatan->scoreComponents ?? collect();
        $scoreSetting = $kegiatan->scoreSetting;
        $passingThreshold = $scoreSetting?->passing_threshold ?? 70;
        $isConfigured = $scoreComponents->isNotEmpty();
        $gradedComponents = $scoreComponents->filter(fn($c) => $c->type !== 'pre_test');
        $gradeCategories = $kegiatan->gradeCategories ?? collect();
        $hasCategories = $gradeCategories->isNotEmpty();

        $colorOptions = ['green', 'blue', 'amber', 'red', 'purple', 'orange', 'gray', 'pink'];

        $colorMap = [
            'green' => 'bg-green-100 text-green-800 border-green-400',
            'blue' => 'bg-blue-100 text-blue-800 border-blue-400',
            'amber' => 'bg-amber-100 text-amber-800 border-amber-400',
            'red' => 'bg-red-100 text-red-800 border-red-400',
            'purple' => 'bg-purple-100 text-purple-800 border-purple-400',
            'orange' => 'bg-orange-100 text-orange-800 border-orange-400',
            'gray' => 'bg-gray-100 text-gray-800 border-gray-400',
            'pink' => 'bg-pink-100 text-pink-800 border-pink-400',
        ];
    @endphp

    {{-- CARD: PENGATURAN PENILAIAN --}}
    <x-detail-section title="Pengaturan Komponen Penilaian" icon="fa-cog">
        <div class="flex justify-end mb-4">
            <button type="button" onclick="toggleSettingForm()"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-cog"></i> <span id="toggleSettingLabel">{{ $isConfigured ? 'Edit Pengaturan' : 'Atur Sekarang' }}</span>
            </button>
        </div>

        @if ($isConfigured)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="bg-white border border-blue-200 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-blue-700">{{ $scoreComponents->count() }}</div>
                    <div class="text-sm text-blue-600">Komponen Penilaian</div>
                </div>
                <div class="bg-white border border-amber-200 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-amber-700">{{ number_format($passingThreshold, 0) }}</div>
                    <div class="text-sm text-amber-600">Batas Nilai Lulus</div>
                </div>
                <div class="bg-white border border-green-200 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-green-700">{{ number_format($gradedComponents->sum('percentage'), 0) }}%</div>
                    <div class="text-sm text-green-600">Total Persentase</div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse border border-gray-200">
                    <thead class="bg-[#007a7a] text-white">
                        <tr>
                            <th class="text-left border border-white py-3 px-4 font-semibold text-sm">Komponen</th>
                            <th class="text-center border border-white py-3 px-4 font-semibold text-sm w-32">Tipe</th>
                            <th class="text-center border border-white py-3 px-4 font-semibold text-sm w-32">Persentase</th>
                            <th class="text-center border border-white py-3 px-4 font-semibold text-sm w-32">Masuk Nilai Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($scoreComponents as $comp)
                            <tr class="{{ $comp->type === 'pre_test' ? 'bg-gray-50' : '' }}">
                                <td class="border border-gray-200 py-2 px-3 font-medium">{{ $comp->name }}</td>
                                <td class="border border-gray-200 py-2 px-3 text-center">
                                    @if ($comp->type === 'pre_test')
                                        <span class="bg-gray-200 text-gray-700 text-xs font-bold px-2 py-1 rounded">Pre Test</span>
                                    @elseif ($comp->type === 'post_test')
                                        <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded">Post Test</span>
                                    @else
                                        <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2 py-1 rounded">Custom</span>
                                    @endif
                                </td>
                                <td class="border border-gray-200 py-2 px-3 text-center">
                                    {{ $comp->type !== 'pre_test' ? number_format($comp->percentage, 0).'%' : '-' }}
                                </td>
                                <td class="border border-gray-200 py-2 px-3 text-center">
                                    @if ($comp->type !== 'pre_test')
                                        <span class="text-green-600 font-bold">Ya</span>
                                    @else
                                        <span class="text-gray-400">Tidak</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-6 text-gray-500">
                <i class="fas fa-sliders-h text-3xl mb-2 block text-gray-300"></i>
                Komponen penilaian belum dikonfigurasi. Klik <strong>Atur Sekarang</strong> untuk memulai.
            </div>
        @endif

        <!-- FORM PENGATURAN (tersembunyi) -->
        <div id="settingForm" style="display: none;" class="mt-6 border-t border-gray-200 pt-6">
            <form method="POST" action="{{ route('kegiatan.pengaturan-penilaian.update', $kegiatan->id) }}" id="scoreSettingForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="has_grade_categories" value="1">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Batas Nilai Lulus <span class="text-red-500">*</span></label>
                        <input type="number" name="passing_threshold" step="0.01" min="0" max="100"
                            value="{{ old('passing_threshold', $passingThreshold) }}"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
                            required>
                        <p class="text-xs text-gray-500 mt-1">Nilai akhir harus ≥ batas ini agar peserta dinyatakan lulus.</p>
                    </div>
                    <div class="flex items-end">
                        <div id="percentageInfo" class="w-full bg-amber-50 border border-amber-200 rounded p-3 text-sm">
                            <span class="font-bold text-amber-700">Total persentase:</span>
                            <span id="totalPercentageDisplay" class="font-bold text-lg ml-1">0%</span>
                            <span class="text-amber-600 text-xs block">(Post Test + Custom harus = 100%)</span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-sm font-medium text-gray-700">Komponen Penilaian <span class="text-red-500">*</span></label>
                        <button type="button" onclick="addCustomComponent()"
                            class="bg-[#007a7a] hover:bg-[#005f5f] text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                            + Tambah Komponen Custom
                        </button>
                    </div>

                    <div id="componentsContainer" class="space-y-2">
                        {{-- Default rows: 1 pre_test + 1 post_test (atau dari data existing) --}}
                    </div>
                </div>

                <div class="border-t-2 border-gray-200 pt-6 mt-6">
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-sm font-medium text-gray-700">Kategori Nilai <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                        <button type="button" onclick="addGradeCategory()"
                            class="bg-[#007a7a] hover:bg-[#005f5f] text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                            + Tambah Kategori
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mb-3">Tentukan kategori berdasarkan rentang nilai akhir. Jika dikosongkan, status menampilkan Lulus/Tidak Lulus.</p>

                    <div id="gradeCategoriesContainer" class="space-y-2">
                    </div>

                    <div id="gradeCategoriesValidationNote" class="text-xs text-amber-600 mt-2 hidden">
                        <i class="fas fa-exclamation-circle"></i> <span id="gradeCategoriesValidationMsg"></span>
                    </div>
                </div>

                <div class="flex gap-3 justify-end mt-4">
                    <button type="button" onclick="toggleSettingForm()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition">Batal</button>
                    <button type="submit" class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </x-detail-section>

    {{-- CARD: KATEGORI NILAI --}}
    <x-detail-section title="Kategori Nilai" icon="fa-tags">

        @if ($hasCategories)
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse border border-gray-200">
                    <thead class="bg-[#007a7a] text-white">
                        <tr>
                            <th class="text-left border border-white py-3 px-4 font-semibold text-sm">Label</th>
                            <th class="text-center border border-white py-3 px-4 font-semibold text-sm w-32">Rentang Nilai</th>
                            <th class="text-center border border-white py-3 px-4 font-semibold text-sm w-32">Warna</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($gradeCategories as $cat)
                            @php $badgeColor = $colorMap[$cat->color] ?? 'bg-gray-100 text-gray-800 border-gray-400'; @endphp
                            <tr>
                                <td class="border border-gray-200 py-2 px-3">
                                    <span class="inline-block px-2 py-1 rounded text-xs font-semibold border {{ $badgeColor }}">
                                        {{ $cat->label }}
                                    </span>
                                </td>
                                <td class="border border-gray-200 py-2 px-3 text-center font-medium">
                                    {{ number_format($cat->min_score, 0) }} - {{ number_format($cat->max_score, 0) }}
                                </td>
                                <td class="border border-gray-200 py-2 px-3 text-center">
                                    <span class="inline-block w-6 h-6 rounded-full border border-gray-300"
                                        style="background-color: {{ match($cat->color) {
                                            'green' => '#16a34a', 'blue' => '#2563eb', 'amber' => '#d97706',
                                            'red' => '#dc2626', 'purple' => '#9333ea', 'orange' => '#ea580c',
                                            'gray' => '#6b7280', 'pink' => '#db2777',
                                            default => '#9ca3af'
                                        } }};"></span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-xs text-gray-500 mt-3">Kategori dapat diubah melalui tombol <strong class="text-gray-700">Edit Pengaturan</strong> di atas.</p>
        @else
            <div class="text-center py-6 text-gray-500">
                <i class="fas fa-tags text-3xl mb-2 block text-gray-300"></i>
                Kategori nilai belum ditentukan. Status akan menampilkan <strong>Lulus</strong> / <strong>Tidak Lulus</strong> berdasarkan batas nilai.
                <p class="text-xs mt-1">Kategori dapat ditambahkan melalui tombol <strong class="text-gray-600">Edit Pengaturan</strong> di atas.</p>
            </div>
        @endif
    </x-detail-section>

    {{-- CARD: INPUT NILAI --}}
    <x-detail-section title="Input Nilai Peserta" icon="fa-pen-alt">
        <div class="flex justify-end mb-4">
            @if ($isConfigured)
                <a href="{{ route('kegiatan.input-nilai.import.page', $kegiatan->id) }}"
                    class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition no-underline inline-flex items-center gap-2">
                    Import Nilai
                </a>
            @endif
        </div>

        @if (!$isConfigured)
            <div class="text-center py-10 text-gray-400">
                <i class="fas fa-lock text-4xl mb-3 block"></i>
                <p class="font-medium">Atur komponen penilaian terlebih dahulu sebelum menginput nilai.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse border border-gray-200 min-w-[900px]">
                    <thead class="bg-[#007a7a] text-white">
                        <tr>
                            <th class="text-center w-12 border border-white py-3 px-4 font-semibold">NO.</th>
                            <th class="text-left border border-white py-3 px-4 font-semibold">Nama Peserta</th>
                            <th class="text-left border border-white py-3 px-4 font-semibold">NIP/NPS</th>
                            <th class="text-left border border-white py-3 px-4 font-semibold">Unit Kerja</th>
                            @foreach ($scoreComponents as $comp)
                                <th class="text-center w-24 border border-white py-3 px-4 font-semibold">
                                    {{ $comp->name }}
                                    @if ($comp->type !== 'pre_test')
                                        <br><span class="text-xs font-normal opacity-80">({{ number_format($comp->percentage, 0) }}%)</span>
                                    @else
                                        <br><span class="text-xs font-normal opacity-80">(tidak dihitung)</span>
                                    @endif
                                </th>
                            @endforeach
                            <th class="text-center w-24 border border-white py-3 px-4 font-semibold">Nilai Akhir</th>
                            <th class="text-center w-24 border border-white py-3 px-4 font-semibold">Batas Lulus</th>
                            <th class="text-center w-24 border border-white py-3 px-4 font-semibold">Status</th>
                            <th class="text-center w-20 border border-white py-3 px-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($kegiatan->activityParticipants as $index => $participant)
                @php
                    $finalScore = $participant->calculateFinalScore();
                    $isPassed = $finalScore !== null && $finalScore >= $passingThreshold;
                    $gradeCat = $participant->getGradeCategory($gradeCategories);
                @endphp
                            <tr>
                                <td class="text-center border border-gray-200 py-3 px-4">{{ $index + 1 }}</td>
                                <td class="border border-gray-200 py-3 px-4">{{ $participant->user->name ?? '-' }}</td>
                                <td class="border border-gray-200 py-3 px-4">{{ $participant->user->nip ?? '-' }}</td>
                                <td class="border border-gray-200 py-3 px-4">{{ $participant->user->workUnit->name ?? '-' }}</td>
                                @foreach ($scoreComponents as $comp)
                                    @php
                                        $cs = $participant->componentScores->firstWhere('activity_score_component_id', $comp->id);
                                        $val = $cs?->score;
                                    @endphp
                                    <td class="text-center font-semibold border border-gray-200 py-3 px-4 {{ $comp->type === 'pre_test' ? 'bg-gray-50 text-gray-500' : '' }}">
                                        {{ $val !== null ? number_format($val, 0) : '-' }}
                                    </td>
                                @endforeach
                                <td class="text-center font-bold {{ $finalScore !== null && $finalScore >= $passingThreshold ? 'text-green-600' : ($finalScore !== null ? 'text-red-600' : 'text-gray-400') }} border border-gray-200 py-3 px-4">
                                    {{ $finalScore !== null ? number_format($finalScore, 2) : '-' }}
                                </td>
                                <td class="text-center font-semibold text-gray-600 border border-gray-200 py-3 px-4">{{ number_format($passingThreshold, 0) }}</td>
                            <td class="text-center border border-gray-200 py-3 px-4">
                                @if ($hasCategories && $gradeCat)
                                    @php $gcBadge = $colorMap[$gradeCat->color] ?? 'bg-gray-100 text-gray-800 border-gray-400'; @endphp
                                    <span class="inline-block px-2 py-1 rounded text-xs font-semibold border {{ $gcBadge }}">
                                        {{ $gradeCat->label }}
                                    </span>
                                @elseif ($participant->is_passed)
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded border border-green-400">Lulus</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded border border-red-400">Tidak Lulus</span>
                                @endif
                            </td>
                                <td class="text-center border border-gray-200 py-3 px-4">
                                    <button type="button"
                                        onclick="openScoreModal({{ $participant->id }}, '{{ addslashes($participant->user->name ?? '-') }}', {{ $participant->componentScores->toJson() }})"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 8 + $scoreComponents->count() }}" class="text-center text-gray-500 border border-gray-200 py-3 px-4">
                                    Belum ada peserta di kegiatan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </x-detail-section>
</section>

<!-- Modal Input Nilai -->
<style>
    .score-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0; top: 0;
        width: 100%; height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }
    .score-modal.active { display: flex; }
    .score-modal-content {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        width: 100%;
        max-width: 560px;
        overflow: hidden;
    }
    .score-modal-header {
        background-color: #0d9488;
        color: white;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
        font-size: 1.1rem;
    }
    .score-modal-close { color: white; font-size: 1.5rem; cursor: pointer; line-height: 1; }
    .score-modal-body { padding: 20px; }
    .score-form-group { margin-bottom: 15px; }
    .score-form-group label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9rem; color: #374151; }
    .score-form-control { width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.95rem; }
    .score-form-control[readonly] { background-color: #f3f4f6; cursor: not-allowed; }
    .score-modal-footer { padding: 15px 20px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 10px; }
    .pre-test-note { background-color: #f3f4f6; border-left: 3px solid #9ca3af; padding: 4px 8px; font-size: 0.75rem; color: #6b7280; margin-top: 3px; }
</style>

<div id="scoreModal" class="score-modal">
    <div class="score-modal-content">
        <div class="score-modal-header">
            <span>Input Nilai Peserta</span>
            <span class="score-modal-close" onclick="closeScoreModal()">&times;</span>
        </div>

        <form id="scoreForm" method="POST">
            @csrf
            @method('PUT')

            <div class="score-modal-body">
                <div class="score-form-group">
                    <label>Nama Peserta</label>
                    <input type="text" id="modal_participant_name" class="score-form-control" readonly>
                </div>

                <div id="modalComponentInputs">
                    {{-- Dinamis diisi via JS --}}
                </div>

                <div class="score-form-group mt-3 bg-teal-50 border border-teal-200 rounded-lg px-3.5 py-2.5">
                    <div class="text-sm text-teal-700">
                        <strong>Batas Nilai Lulus:</strong> {{ number_format($passingThreshold, 0) }}<br>
                        <span class="text-xs text-gray-500">Nilai akhir dihitung dari persentase masing-masing komponen (Pre Test tidak dihitung).</span>
                    </div>
                </div>
            </div>

            <div class="score-modal-footer">
                <button type="button" onclick="closeScoreModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition">Batal</button>
                <button type="submit" class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

@php
    $jsComponents = $scoreComponents->values();
    $jsExistingComponents = $scoreComponents->values()->map(fn($c) => [
        'name' => $c->name,
        'type' => $c->type,
        'percentage' => $c->percentage,
    ]);
    $jsExistingCategories = $gradeCategories->values()->map(fn($c) => [
        'label' => $c->label,
        'min_score' => $c->min_score,
        'max_score' => $c->max_score,
        'color' => $c->color,
        'order' => $c->order,
    ]);
    $jsColorOptions = $colorOptions;
    $jsScoreUpdateUrl = route('kegiatan.input-nilai.update', [
        'kegiatan' => $kegiatan->id,
        'participant_id' => '__ID__',
    ]);
@endphp

<script>
    const scoreComponents = @json($jsComponents);
    const existingComponents = @json($jsExistingComponents);
    const existingCategories = @json($jsExistingCategories);
    const colorOptions = @json($jsColorOptions);
    const isConfigured = @json($isConfigured);
    const scoreUpdateBaseUrl = @json($jsScoreUpdateUrl);

    function toggleSettingForm() {
        const form = document.getElementById('settingForm');
        const label = document.getElementById('toggleSettingLabel');
        const isVisible = form.style.display !== 'none';
        form.style.display = isVisible ? 'none' : 'block';
        if (!isVisible) {
            label.textContent = 'Tutup';
            initSettingForm();
        } else {
            label.textContent = isConfigured ? 'Edit Pengaturan' : 'Atur Sekarang';
        }
    }
    let componentIndex = 0;

    function initSettingForm() {
        const container = document.getElementById('componentsContainer');
        container.innerHTML = '';
        componentIndex = 0;
        gradeCatIndex = 0;

        if (existingComponents.length > 0) {
            existingComponents.forEach(comp => addComponentRow(comp.name, comp.type, comp.percentage));
        } else {
            addComponentRow('Pre Test', 'pre_test', null);
            addComponentRow('Post Test', 'post_test', 100);
        }
        updateTotalPercentage();
        initGradeCategories();
    }

    function addComponentRow(name = '', type = 'custom', percentage = null) {
        const container = document.getElementById('componentsContainer');
        const isPreTest = type === 'pre_test';
        const isPostTest = type === 'post_test';
        const canDelete = !isPreTest && !isPostTest;

        const row = document.createElement('div');
        row.className = 'flex gap-2 items-center p-2 bg-white rounded border border-gray-200';
        row.dataset.index = componentIndex;

        row.innerHTML = `
            <div class="flex-1">
                <input type="text" name="components[${componentIndex}][name]" value="${name}"
                    class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm"
                    placeholder="Nama komponen" required
                    ${isPreTest || isPostTest ? 'readonly class="bg-gray-100"' : ''}>
            </div>
            <input type="hidden" name="components[${componentIndex}][type]" value="${type}">
            <div class="w-32">
                ${isPreTest
                    ? `<input type="text" value="Tidak dihitung" class="w-full border border-gray-200 rounded px-2 py-1.5 text-sm bg-gray-100 text-gray-400" readonly>`
                    : `<div class="flex items-center gap-1">
                        <input type="number" name="components[${componentIndex}][percentage]" value="${percentage ?? ''}"
                            class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm percentage-input"
                            min="0" max="100" step="0.01" placeholder="%" required onchange="updateTotalPercentage()">
                        <span class="text-sm text-gray-500">%</span>
                       </div>`
                }
            </div>
            <div class="w-24 text-center">
                <span class="text-xs px-2 py-1 rounded font-bold ${isPreTest ? 'bg-gray-200 text-gray-600' : isPostTest ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'}">
                    ${isPreTest ? 'Pre Test' : isPostTest ? 'Post Test' : 'Custom'}
                </span>
            </div>
            <div class="w-8 text-center">
                ${canDelete ? `<button type="button" onclick="removeComponent(this)" class="text-red-500 hover:text-red-700 font-bold text-lg leading-none">&times;</button>` : '<span class="text-gray-300">-</span>'}
            </div>
        `;

        container.appendChild(row);
        componentIndex++;
    }

    function addCustomComponent() {
        addComponentRow('', 'custom', null);
        updateTotalPercentage();
    }

    function removeComponent(btn) {
        btn.closest('[data-index]').remove();
        updateTotalPercentage();
    }

    function updateTotalPercentage() {
        const inputs = document.querySelectorAll('.percentage-input');
        let total = 0;
        inputs.forEach(inp => { total += parseFloat(inp.value) || 0; });
        const display = document.getElementById('totalPercentageDisplay');
        if (display) {
            display.textContent = total.toFixed(1) + '%';
            display.className = Math.abs(total - 100) < 0.01 ? 'font-bold text-lg ml-1 text-green-600' : 'font-bold text-lg ml-1 text-red-600';
        }
    }

    // Modal input nilai
    function openScoreModal(participantId, participantName, componentScoresJson) {
        document.getElementById('modal_participant_name').value = participantName;

        const scoresMap = {};
        if (componentScoresJson) {
            (typeof componentScoresJson === 'string' ? JSON.parse(componentScoresJson) : componentScoresJson)
                .forEach(cs => { scoresMap[cs.activity_score_component_id] = cs.score; });
        }

        const container = document.getElementById('modalComponentInputs');
        container.innerHTML = '';

        scoreComponents.forEach(comp => {
            const currentScore = scoresMap[comp.id] ?? '';
            const isPreTest = comp.type === 'pre_test';

            const div = document.createElement('div');
            div.className = 'score-form-group';
            div.innerHTML = `
                <label>
                    ${comp.name}
                    ${!isPreTest ? `<span class="text-gray-400 font-normal text-xs">(${comp.percentage}%)</span>` : ''}
                </label>
                <input type="number" name="score_${comp.id}" value="${currentScore}"
                    min="0" max="100" step="0.01" class="score-form-control" placeholder="0 - 100">
                ${isPreTest ? '<p class="pre-test-note">Pre Test tidak masuk perhitungan nilai akhir.</p>' : ''}
            `;
            container.appendChild(div);
        });

        const form = document.getElementById('scoreForm');
        form.action = scoreUpdateBaseUrl.replace('__ID__', participantId);

        document.getElementById('scoreModal').classList.add('active');
    }

    function closeScoreModal() {
        document.getElementById('scoreModal').classList.remove('active');
    }

    // Grade Categories Management
    let gradeCatIndex = 0;

    function initGradeCategories() {
        const container = document.getElementById('gradeCategoriesContainer');
        container.innerHTML = '';
        gradeCatIndex = 0;

        if (existingCategories.length > 0) {
            existingCategories.forEach(cat => addGradeCategoryRow(cat.label, cat.min_score, cat.max_score, cat.color));
        }
    }

    function addGradeCategory() {
        addGradeCategoryRow('', '', '', 'blue');
    }

    function addGradeCategoryRow(label = '', minScore = '', maxScore = '', color = 'blue') {
        const container = document.getElementById('gradeCategoriesContainer');
        const idx = gradeCatIndex;

        const colorOptionsHtml = colorOptions.map(c =>
            `<option value="${c}" ${c === color ? 'selected' : ''}>${c.charAt(0).toUpperCase() + c.slice(1)}</option>`
        ).join('');

        const row = document.createElement('div');
        row.className = 'flex gap-2 items-center p-2 bg-white rounded border border-gray-200';
        row.dataset.gradeIndex = idx;

        row.innerHTML = `
            <div class="flex-1">
                <input type="text" name="grade_categories[${idx}][label]" value="${escapeHtml(label)}"
                    class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm"
                    placeholder="Nama label (contoh: Sangat Baik)" required>
            </div>
            <div class="w-24">
                <input type="number" name="grade_categories[${idx}][min_score]" value="${minScore}"
                    class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm"
                    min="0" max="100" step="0.01" placeholder="Min" required onchange="validateGradeRanges()">
            </div>
            <div class="text-sm text-gray-400">-</div>
            <div class="w-24">
                <input type="number" name="grade_categories[${idx}][max_score]" value="${maxScore}"
                    class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm"
                    min="0" max="100" step="0.01" placeholder="Max" required onchange="validateGradeRanges()">
            </div>
            <div class="w-28">
                <select name="grade_categories[${idx}][color]"
                    class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                    ${colorOptionsHtml}
                </select>
            </div>
            <input type="hidden" name="grade_categories[${idx}][order]" value="${idx + 1}">
            <div class="w-8 text-center">
                <button type="button" onclick="removeGradeCategory(this)" class="text-red-500 hover:text-red-700 font-bold text-lg leading-none">&times;</button>
            </div>
        `;

        container.appendChild(row);
        gradeCatIndex++;
    }

    function removeGradeCategory(btn) {
        btn.closest('[data-grade-index]').remove();
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function validateGradeRanges() {
        const rows = document.querySelectorAll('#gradeCategoriesContainer [data-grade-index]');
        let valid = true;
        let msg = '';

        const ranges = [];
        rows.forEach(row => {
            const minInp = row.querySelector('input[name*="[min_score]"]');
            const maxInp = row.querySelector('input[name*="[max_score]"]');
            const labelInp = row.querySelector('input[name*="[label]"]');

            const min = parseFloat(minInp?.value) || 0;
            const max = parseFloat(maxInp?.value) || 0;
            const label = labelInp?.value || '?';

            if (min >= max && maxInp?.value !== '') {
                valid = false;
                msg = `"${label}": nilai minimal harus < nilai maksimal.`;
            }

            ranges.push({ min, max, label });
        });

        if (valid) {
            ranges.sort((a, b) => a.min - b.min);
            for (let i = 0; i < ranges.length - 1; i++) {
                if (ranges[i].max >= ranges[i + 1].min) {
                    valid = false;
                    msg = `"${ranges[i].label}" (${ranges[i].min}-${ranges[i].max}) tumpang tindih dengan "${ranges[i + 1].label}" (${ranges[i + 1].min}-${ranges[i + 1].max}).`;
                    break;
                }
            }
        }

        const note = document.getElementById('gradeCategoriesValidationNote');
        const msgEl = document.getElementById('gradeCategoriesValidationMsg');
        if (note && msgEl) {
            if (!valid) {
                note.classList.remove('hidden');
                msgEl.textContent = msg;
            } else {
                note.classList.add('hidden');
            }
        }
    }
</script>