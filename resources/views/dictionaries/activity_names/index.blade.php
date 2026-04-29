@extends('layout.LayoutSuperAdmin')

@section('title', 'Manajemen Nama Kegiatan')

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
        <h1 class="text-2xl font-bold text-white">MANAJEMEN NAMA KEGIATAN</h1>
        <div class="flex space-x-2">
            <button type="button" onclick="toggleModal('importModal')"
                class="inline-flex items-center justify-center bg-[#D6DE20] hover:opacity-85 text-[#007A7F] font-bold px-5 py-2.5 rounded-full shadow transition">
                Import Excel
            </button>
            <a href="{{ route('activity-names.create') }}"
                class="inline-flex items-center justify-center bg-[#1A5555] hover:opacity-85 text-white font-bold px-5 py-2.5 rounded-full shadow transition">
                + Tambah Nama Kegiatan
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Table Control Bar -->
        <form method="GET" action="{{ route('activity-names.index') }}"
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
                    placeholder="Cari Nama Kegiatan..."
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
                            Nama Kegiatan
                        </th>
                        <th scope="col"
                            class="text-left border border-white py-3 px-4 font-semibold">
                            Tanggal Mulai
                        </th>
                        <th scope="col"
                            class="text-left border border-white py-3 px-4 font-semibold">
                            Tanggal Selesai
                        </th>
                        <th scope="col"
                            class="text-left border border-white py-3 px-4 font-semibold">
                            Tahun
                        </th>
                        <th scope="col"
                            class="text-center w-48 border border-white py-3 px-4 font-semibold">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activityNames as $index => $activityName)
                    <tr class="hover:bg-gray-50">
                        <td class="whitespace-nowrap text-sm text-gray-500 border border-gray-200 py-3 px-4">
                            {{ $activityNames->firstItem() + $index }}
                        </td>
                        <td class="whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200 py-3 px-4">
                            {{ $activityName->name }}
                        </td>
                        <td class="whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200 py-3 px-4">
                            {{ $activityName->start_date }}
                        </td>
                        <td class="whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200 py-3 px-4">
                            {{ $activityName->end_date }}
                        </td>
                        <td class="whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200 py-3 px-4">
                            {{ $activityName->year ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap text-sm font-medium text-center space-x-2 border border-gray-200 py-3 px-4">
                            <a href="{{ route('activity-names.edit', $activityName->id) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 rounded text-sm font-medium transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('activity-names.destroy', $activityName->id) }}" method="POST"
                                class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"

                                    onclick="return confirm('Apakah Anda yakin ingin menghapus Nama Kegiatan ini?')" style="background-color: #ef4444;" class="text-white px-3 py-1.5 rounded hover:bg-[#dc2626] text-sm font-semibold transition inline-block">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 text-sm border border-gray-200 py-3 px-4">
                            Belum ada data Nama Kegiatan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-table-footer :paginator="$activityNames->appends(request()->query())" />
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="toggleModal('importModal')"></div>

        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full z-50">
            <form action="{{ route('activity-names.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Import Nama Kegiatan
                            </h3>
                            <div class="mb-4">
                                <p class="text-sm text-gray-500 mb-2">
                                    Unduh template Excel untuk format yang benar.
                                </p>
                                <a href="{{ route('activity-names.template') }}" class="text-teal-600 hover:text-teal-700 text-sm font-semibold underline">
                                    Download Template Excel
                                </a>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih File Excel (.xlsx, .xls, .csv)
                                </label>
                                <input type="file" name="file" required
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Upload
                    </button>
                    <button type="button" onclick="toggleModal('importModal')"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.toggle('hidden');
    }
</script>
@endsection