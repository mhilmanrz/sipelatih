@extends('layout.LayoutSuperAdmin')

@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
@endpush

@section('content')
    <h1 style="color: #007A7F; margin-bottom: 20px;">Dashboard</h1>
    
    <div style="display: flex; gap: 20px; margin-bottom: 30px;">
        <!-- Card 1 -->
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); flex: 1; border-left: 5px solid #00B8A5;">
            <div style="color: #00B8A5; font-weight: bold; font-size: 14px; margin-bottom: 5px;">TOTAL KEGIATAN</div>
            <div style="font-size: 24px; font-weight: bold; color: #333;">{{ $totalActivities ?? 0 }}</div>
        </div>

        <!-- Card 2 -->
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); flex: 1; border-left: 5px solid #0d9488;">
            <div style="color: #0d9488; font-weight: bold; font-size: 14px; margin-bottom: 5px;">TOTAL PEGAWAI</div>
            <div style="font-size: 24px; font-weight: bold; color: #333;">{{ $totalUsers ?? 0 }}</div>
        </div>
    </div>
    
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="color: #007A7F; margin-top: 0;">Informasi</h3>
        <p>Selamat datang di Sistem Informasi Pelatihan Terpadu (SIPELATIH).</p>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush
