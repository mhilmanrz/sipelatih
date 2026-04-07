@extends('layout.LayoutSuperAdmin')

@section('title', 'Tambah Data Kegiatan')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/tambahdata.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                <select name="activity_name_id" required>
                    <option value="">-PILIH-</option>
                    @foreach ($activity_names as $actName)
                    <option value="{{ $actName->id }}"
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
                <div style="width: 100%;">
                    <input type="number" id="angka" name="batch_id" value="{{ old('batch_id') }}" required style="width: 100%;">
                    <small style="color: #666; font-size: 13px; margin-top: 5px; display: block;">*Masukan Angka</small>
                </div>
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
                <label>Institusi Kerjasama</label>
                <input type="text" name="collaboration_inst" value="{{ old('collaboration_inst') }}">
            </div>

            <div class="form-row">
                <label>Target Peserta</label>
                <input type="number" name="quota_participant" value="{{ old('quota_participant') }}" min="1">
            </div>


            <div class="form-row two">
                <label>Tgl Mulai / Selesai</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}">
                <input type="date" name="end_date" value="{{ old('end_date') }}">
            </div>

            <div class="form-row">
                <label>Anggaran (Rp)</label>
                <div style="width: 100%;">
                    <input type="text" id="budget_display" value="{{ old('budget_amount') }}" required>
                    <input type="hidden" name="budget_amount" id="budget_actual" value="{{ old('budget_amount') }}">
                    <small style="color: #666; font-size: 13px; margin-top: 5px; display: block;">*Masukan Angka</small>
                </div>
            </div>

            <div class="form-row">
                <label>Sumber Dana</label>
                <select name="fund_source_id">
                    <option value="">-PILIH SUMBER DANA-</option>
                    @foreach ($fund_sources as $fs)
                    <option value="{{ $fs->id }}" {{ old('fund_source_id') == $fs->id ? 'selected' : '' }}>
                        {{ $fs->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <label>Pagu Anggaran</label>
                <select name="budget_id">
                    <option value="">-PILIH PAGU (OPSIONAL)-</option>
                    @foreach ($budgets as $budget)
                    <option value="{{ $budget->id }}" {{ old('budget_id') == $budget->id ? 'selected' : '' }}>
                        [{{ $budget->rkkal_code }}] {{ $budget->budgetCategory->name ?? '-' }} — Sisa: Rp {{ number_format($budget->remaining_amount, 0, ',', '.') }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <label>Unit Pengusul</label>
                <select name="work_unit_id" id="work_unit_id" required>
                    <option value="">-PILIH ATAU KETIK BARU-</option>
                    @foreach ($work_units as $wu)
                    <option value="{{ $wu->name }}" {{ old('work_unit_id') == $wu->name ? 'selected' : '' }}>
                        {{ $wu->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <label>Nama PIC</label>
                <select name="pic_user_id" id="pic_user_id" required onchange="updateWaPic(this)">
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
<script src="{{ asset('assets/js/tambahdata.js') }}"></script>
<script>
    $(document).ready(function() {
        var createableIds = [
            'activity_name_id', 'activity_type_id', 'activity_scope_id',
            'material_type_id', 'activity_method_id', 'activity_format_id',
            'fund_source_id', 'work_unit_id'
        ];

        createableIds.forEach(function(id) {
            $('select[name="' + id + '"]').select2({
                tags: true,
                placeholder: "-PILIH ATAU KETIK BARU-",
                allowClear: true,
                width: '100%'
            });
        });

        $('select[name="pic_user_id"]').select2({
            placeholder: "-PILIH PEGAWAI (PIC)-",
            allowClear: true,
            width: '100%'
        });

        // Lock Institusi Kerjasama if Bentuk is "Mandiri"
        $('select[name="activity_format_id"]').on('change', function() {
            var selectedText = $(this).find("option:selected").text().trim().toLowerCase();
            var selectedVal = $(this).val();
            var isMandiri = (selectedText === 'mandiri') || (selectedVal && selectedVal.toString().toLowerCase() === 'mandiri');

            if (isMandiri) {
                $('input[name="collaboration_inst"]').val('').prop('disabled', true).css('background-color', '#e9ecef');
            } else {
                $('input[name="collaboration_inst"]').prop('disabled', false).css('background-color', '');
            }
        });

        // Trigger on load for pre-filled data
        $('select[name="activity_format_id"]').trigger('change');

        // Formatter Mata Uang (Anggaran)
        const formatRupiah = (angka) => {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            return rupiah;
        };

        const budgetDisplay = document.getElementById('budget_display');
        const budgetActual = document.getElementById('budget_actual');

        if (budgetDisplay && budgetActual) {
            if (budgetDisplay.value) {
                budgetDisplay.value = formatRupiah(budgetDisplay.value);
            }
            budgetDisplay.addEventListener('keyup', function(e) {
                let pureNumber = this.value.replace(/[^,\d]/g, '');
                budgetActual.value = pureNumber;
                this.value = formatRupiah(pureNumber);
            });
        }
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

    // Run on load in case of old() input
    document.addEventListener('DOMContentLoaded', function() {
        updateWaPic(document.getElementById('pic_user_id'));
    });
</script>
@endpush