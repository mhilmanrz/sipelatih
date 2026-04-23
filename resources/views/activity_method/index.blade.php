@extends('layout.LayoutSuperAdmin')

@section('title', 'Manajemen Metode Kegiatan')

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
        .tw-wrap tbody {
            font-family: inherit;
        }
    </style>
@endpush

@section('content')
    <div class="tw-wrap p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">MANAJEMEN METODE KEGIATAN</h1>
            <a href="{{ route('activity-methods.create') }}"
                class="inline-flex items-center justify-center bg-[#1A5555] text-white px-5 py-2.5 rounded-full font-bold shadow hover:bg-[#154444] transition w-full md:w-auto">
                + Tambah Metode Kegiatan
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-teal-600">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider w-16">
                            No.
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Metode Kegiatan
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider w-48">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activityMethods as $index => $activityMethod)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $activityMethods->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $activityMethod->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center space-x-2">
                                <a href="{{ route('activity-methods.edit', $activityMethod->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-[#1A5555] text-white hover:bg-[#1A5555] border border-[#1A5555] rounded text-sm font-medium transition-colors">
                                    Edit
                                </a>
                                <form action="{{ route('activity-methods.destroy', $activityMethod->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 bg-[#FF0000] text-white hover:bg-red-100 border border-red-200 rounded text-sm font-medium transition-colors"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus Metode Kegiatan ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500 text-sm">
                                Belum ada data Metode Kegiatan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($activityMethods->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $activityMethods->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
