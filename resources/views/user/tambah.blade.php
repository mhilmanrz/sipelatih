<x-layouts.app>
    <x-slot:title>Tambah Pegawai</x-slot>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/tambahdata.css') }}">
        <style>
            .form-group {
                margin-bottom: 15px;
            }

            .form-group label {
                display: block;
                margin-bottom: 5px;
                color: #007A7F;
                font-weight: 500;
            }

            .form-group input,
            .form-group select {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            .btn-save {
                background: #00B8A5;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
            }

            .btn-reset {
                background: #ccc;
                color: black;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                text-decoration: none;
            }
        </style>
    @endpush

    <h1 style="color:#007A7F; margin-bottom: 20px;">Tambah Pegawai</h1>

    <div
        style="background:white; padding:30px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1); max-width: 800px;">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="form-group">
                <label>Nama Lengkap <span style="color:red;">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label>Email <span style="color:red;">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label>Password <span style="color:red;">*</span></label>
                <input type="password" name="password" placeholder="Minimal 8 karakter" required>
            </div>

            <div class="form-group">
                <label>NIP / Employee ID</label>
                <input type="text" name="employee_id" value="{{ old('employee_id') }}">
            </div>

            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="phone_number" value="{{ old('phone_number') }}">
            </div>

            <div class="form-group">
                <label>Unit Kerja</label>
                <select name="work_unit_id">
                    <option value="">-- Pilih Unit Kerja --</option>
                    @foreach ($workUnits as $wu)
                        <option value="{{ $wu->id }}" {{ old('work_unit_id') == $wu->id ? 'selected' : '' }}>
                            {{ $wu->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Profesi</label>
                <select name="profession_id">
                    <option value="">-- Pilih Profesi --</option>
                    @foreach ($professions as $prof)
                        <option value="{{ $prof->id }}" {{ old('profession_id') == $prof->id ? 'selected' : '' }}>
                            {{ $prof->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Posisi / Jabatan</label>
                <select name="position_id">
                    <option value="">-- Pilih Posisi --</option>
                    @foreach ($positions as $pos)
                        <option value="{{ $pos->id }}" {{ old('position_id') == $pos->id ? 'selected' : '' }}>
                            {{ $pos->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Jenis Tenaga</label>
                <select name="employment_type_id">
                    <option value="">-- Pilih Jenis Tenaga --</option>
                    @foreach ($employmentTypes as $et)
                        <option value="{{ $et->id }}" {{ old('employment_type_id') == $et->id ? 'selected' : '' }}>
                            {{ $et->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-top: 30px;">
                <button type="submit" class="btn-save">💾 Simpan</button>
                <a href="{{ url('/users') }}" class="btn-reset">BATAL</a>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
    @endpush
</x-layouts.app>
