@extends('layout.LayoutSuperAdmin')

@section('title', 'Edit Data Kegiatan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tambahdata.css') }}">
@endpush

@section('content')
    <div class="input-page" style="padding: 15px;">
        <h3 class="title">Edit Data Kegiatan</h3>

        <form method="POST" action="{{ route('kegiatan.update', $kegiatan->id) }}">
            @csrf
            @method('PUT')

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
                    <input type="date" name="date" value="{{ old('date', $kegiatan->date) }}">
                </div>

                <div class="form-row">
                    <label>No. Surat</label>
                    <input type="text" name="reference_number"
                        value="{{ old('reference_number', $kegiatan->reference_number) }}">
                </div>

                <div class="form-row">
                    <label>Nama Kegiatan</label>
                    <select name="activity_name_id" required>
                        <option value="">-PILIH-</option>
                        @foreach ($activity_names as $actName)
                            <option value="{{ $actName->id }}"
                                {{ old('activity_name_id', $kegiatan->activity_name_id) == $actName->id ? 'selected' : '' }}>
                                {{ $actName->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Jenis Kegiatan</label>
                    <select name="activity_type_id" required>
                        <option value="">-PILIH-</option>
                        @foreach ($activity_types as $type)
                            <option value="{{ $type->id }}"
                                {{ old('activity_type_id', $kegiatan->activity_type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Cakupan Kegiatan</label>
                    <select name="activity_scope_id" required>
                        <option value="">-PILIH-</option>
                        @foreach ($activity_scopes as $scope)
                            <option value="{{ $scope->id }}"
                                {{ old('activity_scope_id', $kegiatan->activity_scope_id) == $scope->id ? 'selected' : '' }}>
                                {{ $scope->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Jenis Materi</label>
                    <select name="material_type_id" required>
                        <option value="">-PILIH-</option>
                        @foreach ($material_types as $mat)
                            <option value="{{ $mat->id }}"
                                {{ old('material_type_id', $kegiatan->material_type_id) == $mat->id ? 'selected' : '' }}>
                                {{ $mat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Metode</label>
                    <select name="activity_method_id" required>
                        <option value="">-PILIH-</option>
                        @foreach ($activity_methods as $method)
                            <option value="{{ $method->id }}"
                                {{ old('activity_method_id', $kegiatan->activity_method_id) == $method->id ? 'selected' : '' }}>
                                {{ $method->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Angkatan</label>
                    <select id="angka" name="batch_id" required>
                        <option value="">-PILIH-</option>
                        @foreach ($batches as $batch)
                            <option value="{{ $batch->id }}"
                                {{ old('batch_id', $kegiatan->batch_id) == $batch->id ? 'selected' : '' }}>
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
                                {{ old('activity_format_id', $kegiatan->activity_format_id) == $format->id ? 'selected' : '' }}>
                                {{ $format->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Institusi Kerjasama (Opsional)</label>
                    <input type="text" name="collaboration_inst"
                        value="{{ old('collaboration_inst', $kegiatan->collaboration_inst) }}">
                </div>

                <div class="form-row">
                    <label>Target Peserta</label>
                    <select name="target_participant_id">
                        <option value="">-PILIH-</option>
                        @foreach ($target_participants as $target)
                            <option value="{{ $target->id }}"
                                {{ old('target_participant_id', $kegiatan->target_participant_id) == $target->id ? 'selected' : '' }}>
                                {{ $target->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Kuota Peserta (Orang)</label>
                    <input type="number" name="quota_participant" value="{{ old('quota_participant', $kegiatan->quota_participant) }}" min="1">
                </div>

                <div class="form-row two">
                    <label>Tgl Mulai / Selesai</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $kegiatan->start_date) }}">
                    <input type="date" name="end_date" value="{{ old('end_date', $kegiatan->end_date) }}">
                </div>

                <div class="form-row">
                    <label>Anggaran (Rp)</label>
                    <input type="number" name="budget_amount"
                        value="{{ old('budget_amount', $kegiatan->budget_amount ? floor($kegiatan->budget_amount) : '') }}">
                </div>

                <div class="form-row">
                    <label>Pagu</label>
                    <select name="budget_id">
                        <option value="">-PILIH PAGU-</option>
                        @foreach ($budgets as $bg)
                            <option value="{{ $bg->id }}"
                                {{ old('budget_id', $kegiatan->budget_id) == $bg->id ? 'selected' : '' }}>
                                {{ $bg->rkkal_code }} - {{ $bg->budgetCategory->name ?? '' }} (Rp {{ number_format($bg->total_amount, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label>Unit Pengusul</label>
                    <select name="work_unit_id">
                        <option value="">-PILIH-</option>
                        @foreach ($work_units as $unit)
                            <option value="{{ $unit->id }}"
                                {{ old('work_unit_id', $kegiatan->work_unit_id) == $unit->id ? 'selected' : '' }}>
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
                                {{ old('pic_user_id', $kegiatan->pic_user_id) == $pic->id ? 'selected' : '' }}>
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
                    <button type="submit" id="btnSave" class="btn-save" style="cursor:pointer;">💾 SIMPAN
                        PERUBAHAN</button>
                    <a href="{{ route('kegiatan.index') }}" id="btnCancel" class="btn-cancel"
                        style="cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">✖
                        BATAL</a>
                </div>

            </div>
        </form>
    </div>
@endsection

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

        // Run on load to pre-fill the WA PIC based on the initially selected Kegiatan PIC
        document.addEventListener('DOMContentLoaded', function() {
            updateWaPic(document.getElementById('pic_user_id'));
        });
    </script>
@endpush
