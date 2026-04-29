@extends('layout.LayoutSuperAdmin')

@section('title', 'Manajemen Jenis Kepegawaian')

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
        <h1 class="text-2xl font-bold text-white">MANAJEMEN JENIS KEPEGAWAIAN</h1>
        <a href="{{ route('employment-types.create') }}"
            class="inline-flex items-center justify-center bg-[#1A5555] hover:opacity-85 text-white font-bold px-5 py-2.5 rounded-full shadow transition">
            + Tambah Jenis Kepegawaian
        </a>
    </div>

    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Table Control Bar -->
        <form method="GET" action="{{ route('employment-types.index') }}"
            class="flex flex-wrap justify-between items-center p-6 gap-4 text-white" style="background-color:#205252;">
            <div class="flex items-center gap-2">
                <span>Show</span>
                <select name="entries" onchange="this.form.submit()"
                    class="bg-transparent border border-white text-white rounded px-2 py-1 outline-none">
                    <option value="10" class="text-black" {{ request('entries', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" class="text-black" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" class="text-black" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                </select>
                <span>entries</span>
            </div>
            <div class="flex items-center gap-2">
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Cari Jenis Kepegawaian..."
                    class="bg-transparent border border-white text-white placeholder-gray-300 rounded-full px-4 py-1.5 outline-none text-sm">
                <button type="submit" style="background-color:#D6DE20; color:black;"
                    class="px-4 py-1.5 rounded-full font-bold hover:opacity-90 transition text-sm">Search</button>
            </div>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#007a7a] border border-white py-3 px-4 font-semibold">
                    <tr>
                        <th scope="col"
                            class="text-left w-16 border border-white py-3 px-4 font-semibold">
                            No.
                        </th>
                        <th scope="col"
                            class="text-left border border-white py-3 px-4 font-semibold">
                            Jenis Kepegawaian
                        </th>
                        <th scope="col"
                            class="text-center w-48 border border-white py-3 px-4 font-semibold">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($employmentTypes as $index => $employmentType)
                    <tr class="hover:bg-gray-50">
                        <td class="whitespace-nowrap text-sm text-gray-500 border border-gray-200 py-3 px-4">
                            {{ $employmentTypes->firstItem() + $index }}
                        </td>
                        <td class="whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200 py-3 px-4">
                            {{ $employmentType->name }}
                        </td>
                        <td class="whitespace-nowrap text-sm font-medium text-center space-x-2 border border-gray-200 py-3 px-4">
                            <a href="{{ route('employment-types.edit', $employmentType->id) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-[#1A5555] text-white hover:bg-[#1A5555] border border-[#1A5555] rounded text-sm font-medium transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('employment-types.destroy', $employmentType->id) }}" method="POST"
                                class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"

                                    onclick="return confirm('Apakah Anda yakin ingin menghapus Jenis Kepegawaian ini?')" style="background-color: #ef4444;" class="text-white px-3 py-1.5 rounded hover:bg-[#dc2626] text-sm font-semibold transition inline-block">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-gray-500 text-sm border border-gray-200 py-3 px-4">
                            Belum ada data Jenis Kepegawaian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-table-footer :paginator="$employmentTypes->appends(request()->query())" />
    </div>
</div>
@endsection