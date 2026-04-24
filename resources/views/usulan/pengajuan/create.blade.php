@extends('layout.LayoutSuperAdmin')

@section('title', 'Tambah Data Kegiatan')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/tambahdata.css') }}">
@endpush

@section('content')
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
                <label>Tanggal Usulan</label>
                <input type="date" name="date" value="{{ old('date') }}">
            </div>

            <div class="form-row">
                <label>No. Surat</label>
                <input type="text" name="reference_number" value="{{ old('reference_number') }}">
            </div>

            <div class="form-row">
                <label>Nama Kegiatan</label>
                <select name="activity_name_id" id="activity_name_select" required>
                    <option value="" data-start="" data-end="">-PILIH-</option>
                    @foreach ($activity_names as $actName)
                    <option value="{{ $actName->id }}"
                        data-start="{{ $actName->start_date }}" data-end="{{ $actName->end_date }}"
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
                <div>
                    <input type="number" name="batch_id" id="angka" min="1"
                        value="{{ old('batch_id') }}" placeholder="Contoh: 1">
                    <small style="color: #6b7280; font-size: 0.78rem; margin-top: 4px; display: block;">*Masukan angka</small>
                </div>
            </div>

            <div class="form-row">
                <label>Bentuk</label>
                <select name="activity_format_id" id="activity_format_select" required>
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
                <input type="text" id="collaboration_inst" name="collaboration_inst" value="{{ old('collaboration_inst') }}">
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
                <select name="budget_id">
                    <option value="">-PILIH PAGU-</option>
                    @foreach ($budgets as $bg)
                    <option value="{{ $bg->id }}" {{ old('budget_id') == $bg->id ? 'selected' : '' }}>
                        {{ $bg->rkkal_code }} - {{ $bg->budgetCategory->name ?? '' }} (Rp {{ number_format($bg->total_amount, 0, ',', '.') }})
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
                        {{ $unit->name }}
                    </option>
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

            <div class="form-action">
                <button type="submit" id="btnSave" class="btn-save" style="cursor:pointer;">💾 SIMPAN</button>
                <a href="{{ route('kegiatan.index') }}" id="btnCancel" class="btn-cancel"
                    style="cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">✖
                    BATAL</a>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('activity_name_select');
        const startDateInput = document.getElementById('act_start_date');
        const endDateInput = document.getElementById('act_end_date');

        if (select && startDateInput && endDateInput) {
            select.addEventListener('change', function() {
                const selectedOption = select.options[select.selectedIndex];
                const start = selectedOption.getAttribute('data-start');
                const end = selectedOption.getAttribute('data-end');

                if (start) {
                    startDateInput.value = start;
                }
                if (end) {
                    endDateInput.value = end;
                }
            });
        }

        // Disable Institusi Kerjasama jika Bentuk = Mandiri
        const formatSelect = document.getElementById('activity_format_select');
        const collabInput = document.getElementById('collaboration_inst');

        function toggleCollabInst() {
            const selectedText = formatSelect.options[formatSelect.selectedIndex].text.trim().toLowerCase();
            if (selectedText === 'mandiri') {
                collabInput.value = '';
                collabInput.disabled = true;
                collabInput.style.backgroundColor = '#e5e7eb';
                collabInput.style.cursor = 'not-allowed';
                collabInput.placeholder = 'Tidak tersedia untuk Mandiri';
            } else {
                collabInput.disabled = false;
                collabInput.style.backgroundColor = '';
                collabInput.style.cursor = '';
                collabInput.placeholder = '';
            }
        }

        if (formatSelect && collabInput) {
            formatSelect.addEventListener('change', toggleCollabInst);
            toggleCollabInst(); // cek saat halaman dimuat (untuk kasus old())
        }
    });
</script>
@endpush

@push('scripts')
<script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
<script src="{{ asset('assets/js/tambahdata.js') }}"></script>
<script>
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

    // Run on load in case of old() input
    document.addEventListener('DOMContentLoaded', function() {
        updateWaPic(document.getElementById('pic_user_id'));
    });
</script>
@endpush