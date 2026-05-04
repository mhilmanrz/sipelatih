<x-layouts.app>
@section('title', 'Tambah Data Kegiatan')

@push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/tambahdata.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
@endpush
    <div class="input-page" style="padding: 15px;">
        <h3 class="title">Data Kegiatan</h3>

        <form method="POST" action="{{ route('kegiatan.store') }}">
            @csrf

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Oops! Ada kesalahan.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-card">
                <div class="form-row">
                    <label>Tanggal Surat</label>
                    <input type="date" name="date" value="{{ old('date') }}">
                </div>

                <div class="form-row">
                    <label>No. Surat</label>
                    <input type="text" name="reference_number" value="{{ old('reference_number') }}">
                </div>

                <div class="form-row">
                    <label>Tahun</label>
                    <select id="year_filter">
                        <option value="">-PILIH TAHUN-</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Nama Kegiatan</label>
                    <select name="activity_name_id" id="activity_name_select" required>
                        <option value="" data-start="" data-end="" data-year="">-PILIH-</option>
                        @foreach ($activity_names as $actName)
                            <option value="{{ $actName->id }}"
                                data-start="{{ $actName->start_date }}" data-end="{{ $actName->end_date }}"
                                data-year="{{ $actName->year }}"
                                {{ old('activity_name_id') == $actName->id ? 'selected' : '' }}>{{ $actName->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Jenis Kegiatan</label>
                    <select name="activity_type_id" required>
                        <option value="">-PILIH-</option>
                        @foreach ($activity_types as $type)
                            <option value="{{ $type->id }}"
                                {{ old('activity_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Kategori Kegiatan</label>
                    <select name="activity_category_id" required>
                        <option value="">-PILIH-</option>
                        @foreach ($activity_categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('activity_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Cakupan Kegiatan</label>
                    <select name="activity_scope_id" required>
                        <option value="">-PILIH-</option>
                        @foreach ($activity_scopes as $scope)
                            <option value="{{ $scope->id }}"
                                {{ old('activity_scope_id') == $scope->id ? 'selected' : '' }}>{{ $scope->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Jenis Materi</label>
                    <select name="material_type_id" required>
                        <option value="">-PILIH-</option>
                        @foreach ($material_types as $mat)
                            <option value="{{ $mat->id }}"
                                {{ old('material_type_id') == $mat->id ? 'selected' : '' }}>{{ $mat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Metode</label>
                    <select name="activity_method_id" required>
                        <option value="">-PILIH-</option>
                        @foreach ($activity_methods as $method)
                            <option value="{{ $method->id }}"
                                {{ old('activity_method_id') == $method->id ? 'selected' : '' }}>{{ $method->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Angkatan</label>
                    <select id="angka" name="batch_id" required>
                        <option value="">-PILIH-</option>
                        @foreach ($batches as $batch)
                            <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                                {{ $batch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Bentuk</label>
                    <select name="activity_format_id" required>
                        <option value="">-PILIH-</option>
                        @foreach ($activity_formats as $format)
                            <option value="{{ $format->id }}"
                                {{ old('activity_format_id') == $format->id ? 'selected' : '' }}>{{ $format->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Institusi Kerjasama (Opsional)</label>
                    <input type="text" name="collaboration_inst" value="{{ old('collaboration_inst') }}">
                </div>

                <div class="form-row">
                    <label>Tempat</label>
                    <input type="text" name="tempat" value="{{ old('tempat') }}">
                </div>

                <div class="form-row">
                    <label>Tujuan</label>
                    <textarea name="tujuan" rows="3" style="resize:vertical;">{{ old('tujuan') }}</textarea>
                </div>

                <div class="form-row">
                    <label>Justifikasi</label>
                    <textarea name="justifikasi" rows="3" style="resize:vertical;">{{ old('justifikasi') }}</textarea>
                </div>

                <div class="form-row">
                    <label>Target Kompetensi</label>
                    <textarea name="target_kompetensi" rows="3" style="resize:vertical;">{{ old('target_kompetensi') }}</textarea>
                </div>

                <div class="form-row">
                    <label>Sumber Dana</label>
                    <select name="fund_source_id">
                        <option value="">-PILIH-</option>
                        @foreach ($fund_sources as $fs)
                            <option value="{{ $fs->id }}" {{ old('fund_source_id') == $fs->id ? 'selected' : '' }}>
                                {{ $fs->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Target Peserta</label>
                    <select name="target_participant_id">
                        <option value="">-PILIH-</option>
                        @foreach ($target_participants as $target)
                            <option value="{{ $target->id }}"
                                {{ old('target_participant_id') == $target->id ? 'selected' : '' }}>{{ $target->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Kuota Peserta (Orang)</label>
                    <input type="number" name="quota_participant" value="{{ old('quota_participant') }}" min="1">
                </div>

                <div class="form-row two">
                    <label>Tgl Mulai / Selesai</label>
                    <input type="date" name="start_date" id="act_start_date" value="{{ old('start_date') }}">
                    <input type="date" name="end_date" id="act_end_date" value="{{ old('end_date') }}">
                </div>

                <div class="form-row">
                    <label>Anggaran (Rp)</label>
                    <input type="number" name="budget_amount" value="{{ old('budget_amount') }}">
                </div>

                <div class="form-row">
                    <label>Pagu</label>
                    <select name="budget_id" id="budget_select">
                        <option value="" data-year="">-PILIH PAGU-</option>
                        @foreach ($budgets as $bg)
                            @php $sisa = $bg->total_amount - ($bg->activities_sum_budget_amount ?? 0); @endphp
                            <option value="{{ $bg->id }}" data-year="{{ $bg->year }}" {{ old('budget_id') == $bg->id ? 'selected' : '' }}>
                                {{ $bg->rkkal_code }} - {{ $bg->budgetCategory->name ?? '' }} (Sisa: Rp {{ number_format($sisa, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Unit Pengusul</label>
                    <select name="work_unit_id">
                        <option value="">-PILIH-</option>
                        @foreach ($work_units as $unit)
                            <option value="{{ $unit->id }}" {{ old('work_unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Nama PIC</label>
                    <select name="pic_user_id" id="pic_user_id" onchange="updateWaPic(this)">
                        <option value="" data-phone="">-PILIH PEGAWAI (PIC)-</option>
                        @foreach ($picCandidates as $pic)
                            <option value="{{ $pic->id }}" data-phone="{{ $pic->phone_number ?? '-' }}"
                                {{ old('pic_user_id') == $pic->id ? 'selected' : '' }}>
                                {{ $pic->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>WA PIC</label>
                    <input type="text" id="wa_pic_temp" disabled placeholder="Pilih Nama PIC di atas untuk melihat WA"
                        class="bg-gray-100 font-bold text-gray-700">
                </div>

                <div class="form-row two">
                    <label>Jam Mulai / Selesai</label>
                    <input type="time" name="start_time" value="{{ old('start_time') }}">
                    <input type="time" name="end_time" value="{{ old('end_time') }}">
                </div>

                <div class="form-row">
                    <label>PIC Penyelenggara</label>
                    <select name="organizer_pic_id">
                        <option value="">-PILIH PEGAWAI-</option>
                        @foreach ($picCandidates as $pic)
                            <option value="{{ $pic->id }}"
                                {{ old('organizer_pic_id') == $pic->id ? 'selected' : '' }}>
                                {{ $pic->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-action">
                    <button type="submit" id="btnSave" class="btn-save" style="cursor:pointer;">💾 SIMPAN</button>
                    <a href="{{ route('kegiatan.index') }}" id="btnCancel" class="btn-cancel"
                        style="cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">✖
                        BATAL</a>
                </div>

            </div>
        </form>
    </div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="{{ asset('assets/js/tambahdata.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('select[name="fund_source_id"]', {
                create: true,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });

            const yearFilter = document.getElementById('year_filter');
            const actNameSelect = document.getElementById('activity_name_select');
            const budgetSelect = document.getElementById('budget_select');
            const startDateInput = document.getElementById('act_start_date');
            const endDateInput = document.getElementById('act_end_date');

            // Simpan semua opsi asli
            const allActivityOptions = Array.from(actNameSelect.options).slice(1);
            const allBudgetOptions = Array.from(budgetSelect.options).slice(1);

            function filterByYear(year) {
                // Reset Nama Kegiatan
                actNameSelect.innerHTML = '<option value="" data-start="" data-end="" data-year="">-PILIH-</option>';
                startDateInput.value = '';
                endDateInput.value = '';

                // Reset Pagu
                budgetSelect.innerHTML = '<option value="" data-year="">-PILIH PAGU-</option>';

                if (!year) return;

                allActivityOptions.forEach(opt => {
                    if (opt.dataset.year == year) {
                        actNameSelect.appendChild(opt.cloneNode(true));
                    }
                });

                allBudgetOptions.forEach(opt => {
                    if (opt.dataset.year == year) {
                        budgetSelect.appendChild(opt.cloneNode(true));
                    }
                });
            }

            // Kosongkan saat pertama load
            filterByYear('');

            yearFilter.addEventListener('change', function() {
                filterByYear(this.value);
            });

            actNameSelect.addEventListener('change', function() {
                const selectedOption = actNameSelect.options[actNameSelect.selectedIndex];
                const start = selectedOption.getAttribute('data-start');
                const end = selectedOption.getAttribute('data-end');

                if (start) startDateInput.value = start;
                if (end) endDateInput.value = end;
            });

            updateWaPic(document.getElementById('pic_user_id'));
        });

        function updateWaPic(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const phoneNumber = selectedOption.getAttribute('data-phone');
            const waInput = document.getElementById('wa_pic_temp');

            if (phoneNumber && phoneNumber !== '-') {
                waInput.value = phoneNumber;
            } else if (selectElement.value) {
                waInput.value = "(Nomor WA tidak terdaftar)";
            } else {
                waInput.value = "";
            }
        }
    </script>
@endpush
</x-layouts.app>
