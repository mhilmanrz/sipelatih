@extends('layout.LayoutSuperAdmin')

@section('title', 'Detail Pagu Anggaran')

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .tw-wrap p,
        .tw-wrap h1,
        .tw-wrap h2,
        .tw-wrap h3,
        .tw-wrap h4,
        .tw-wrap h5,
        .tw-wrap h6,
        .tw-wrap span,
        .tw-wrap div,
        .tw-wrap a,
        .tw-wrap button,
        .tw-wrap table,
        .tw-wrap th,
        .tw-wrap td,
        .tw-wrap tr,
        .tw-wrap thead,
        .tw-wrap tbody,
        .tw-wrap form,
        .tw-wrap input,
        .tw-wrap label,
        .tw-wrap select {
            font-family: inherit;
        }
    </style>
@endpush

@section('content')
    <div class="tw-wrap p-6 mt-4">
        
        <!-- HEADER / NAVIGATION -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detail Pagu Anggaran</h1>
            <a href="{{ route('pagu.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow text-sm font-medium transition-colors" style="text-decoration:none;">
                Kembali
            </a>
        </div>

        <!-- INFO CARD -->
        <div class="bg-white rounded-lg shadow p-6 mb-8 border-l-4 border-teal-600">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">No. RKAKL</h3>
                    <p class="mt-1 text-lg font-bold text-gray-900">{{ $pagu->rkkal_code }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tahun Anggaran</h3>
                    <p class="mt-1 text-lg font-bold text-gray-900">{{ $pagu->year }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Kategori Pagu</h3>
                    <p class="mt-1 text-lg font-bold text-gray-900">{{ $pagu->budgetCategory->name ?? '-' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Submark</h3>
                    <p class="mt-1 text-lg font-bold text-gray-900">{{ $pagu->submark ?: '-' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pagu</h3>
                    <p class="mt-1 text-lg font-bold text-gray-900 text-teal-700">Rp {{ number_format($pagu->total_amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Sisa Pagu</h3>
                    <p class="mt-1 text-lg font-bold {{ $pagu->remaining_amount < 0 ? 'text-red-600' : 'text-blue-600' }}">
                        Rp {{ number_format($pagu->remaining_amount, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- ACTIVITIES TABLE -->
        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Daftar Kegiatan Menggunakan Pagu Ini</h2>
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-16">No.</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nama Kegiatan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Unit Pengusul</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">PIC</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Penggunaan Anggaran</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pagu->activities as $index => $activity)
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $activity->activityName->name ?? ($activity->name ?? 'N/A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($activity->start_date)->format('d M Y') }} s.d.<br>
                                {{ \Carbon\Carbon::parse($activity->end_date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $activity->workUnit->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $activity->picUser->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-orange-600">
                                Rp {{ number_format($activity->budget_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 text-sm">
                                Belum ada kegiatan yang menggunakan pagu ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($pagu->activities->count() > 0)
                <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase">
                            Total Penggunaan
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-orange-700">
                            Rp {{ number_format($pagu->activities->sum('budget_amount'), 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

    </div>
@endsection
