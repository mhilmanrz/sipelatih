@extends('layout.LayoutSuperAdmin')

@section('title', 'Edit Pegawai')

@push('styles')
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

@section('content')
<h1 style="color:#007A7F; margin-bottom: 20px;">Edit Pegawai</h1>

<div style="background:white; padding:30px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1); max-width: 800px;">
    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label>NIP / Employee ID</label>
            <input type="text" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}">
        </div>

        <div class="form-group">
            <label>No HP</label>
            <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
        </div>

        <div class="form-group">
            <label>Unit Kerja</label>
            <select name="work_unit_id">
                <option value="">-- Pilih Unit Kerja --</option>
                @foreach ($workUnits as $wu)
                <option value="{{ $wu->id }}"
                    {{ old('work_unit_id', $user->work_unit_id) == $wu->id ? 'selected' : '' }}>
                    {{ $wu->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Profesi</label>
            <select name="profession_id">
                <option value="">-- Pilih Profesi --</option>
                @foreach ($professions as $prof)
                <option value="{{ $prof->id }}"
                    {{ old('profession_id', $user->profession_id) == $prof->id ? 'selected' : '' }}>
                    {{ $prof->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Posisi / Jabatan</label>
            <select name="position_id">
                <option value="">-- Pilih Posisi --</option>
                @foreach ($positions as $pos)
                <option value="{{ $pos->id }}"
                    {{ old('position_id', $user->position_id) == $pos->id ? 'selected' : '' }}>
                    {{ $pos->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Jenis Tenaga</label>
            <select name="employment_type_id">
                <option value="">-- Pilih Jenis Tenaga --</option>
                @foreach ($employmentTypes as $et)
                <option value="{{ $et->id }}"
                    {{ old('employment_type_id', $user->employment_type_id) == $et->id ? 'selected' : '' }}>
                    {{ $et->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Role Sistem <span style="color:red;">*</span></label>
            <select name="role" required>
                <option value="">-- Pilih Role --</option>
                @php $currentRole = $user->roles->first()->name ?? ''; @endphp
                @foreach ($roles as $role)
                <option value="{{ $role->name }}"
                    {{ old('role', $currentRole) == $role->name ? 'selected' : '' }}>{{ $role->name }}
                </option>
                @endforeach
            </select>
        </div>

        <hr style="margin: 30px 0;">
        <p style="text-align:center; color:#666; font-size:12px;">Abaikan form di bawah jika tidak ingin mengganti
            password</p>

        <div class="form-group">
            <label>Password Baru</label>
            <input type="password" name="password" placeholder="Minimal 8 karakter">
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-save">💾 SIMPAN</button>
            <a href="{{ url('/users') }}" class="btn-reset">BATAL</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- LayoutSuperAdmin.js removed - conflicts with Alpine.js sidebar --}}
@endpush