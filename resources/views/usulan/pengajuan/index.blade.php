@extends('layout.app')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
    <h3 class="text-xl font-semibold mb-6 text-gray-800">
        {{ $title }}
    </h3>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">
                        No
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">
                        Judul Kegiatan
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">
                        Jenis Kegiatan
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">
                        Cakupan Kegiatan
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">
                        Jenis Materi
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse ($dataKegiatan as $index => $kegiatan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-center text-sm text-gray-700">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $kegiatan['nama'] }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $kegiatan['jenis_kegiatan'] }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $kegiatan['cakupan'] }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $kegiatan['jenis_materi'] }}
                        </td>
                        {{-- <td class="px-4 py-3 text-sm">
                            @php
                                $statusClass = match($kegiatan['status']) {
                                    'Disetujui' => 'bg-green-100 text-green-700',
                                    'Ditolak'   => 'bg-red-100 text-red-700',
                                    default     => 'bg-yellow-100 text-yellow-700',
                            @endphp

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $kegiatan['status'] }}
                            </span>
                        </td>}; --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                            Data kegiatan belum tersedia
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

