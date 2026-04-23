@extends('layout.LayoutSuperAdmin')

@section('title', 'Indikator Kinerja')

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
@endpush

@section('content')
    <div class="p-8 flex flex-col items-center justify-center min-h-[60vh]">
        <div class="bg-white rounded-lg shadow p-10 text-center max-w-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-teal-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z" />
            </svg>
            <h2 class="text-xl font-bold text-gray-700 mb-2">Halaman Dipindahkan</h2>
            <p class="text-gray-500 mb-6">Konten Indikator Kinerja telah dipindahkan ke halaman Monitoring JPL.</p>
            <a href="{{ route('monitoring.jpl.index') }}"
                class="inline-block bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-3 rounded transition-colors"
                style="text-decoration: none;">
                Buka Monitoring JPL
            </a>
        </div>
    </div>
@endsection
