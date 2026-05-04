<x-layouts.app>
    <x-slot:title>Manajemen Ruang Lingkup Kegiatan</x-slot>

    @push('styles')
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

    <div class="tw-wrap p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">MANAJEMEN RUANG LINGKUP KEGIATAN</h1>
            <a href="{{ route('activity-scopes.create') }}"
                class="bg-[#1A5555] hover:bg-[#1A5555] text-white font-semibold py-2 px-4 rounded shadow">
                + Tambah Ruang Lingkup
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
                            Ruang Lingkup
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider w-48">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activityScopes as $index => $activityScope)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $activityScopes->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $activityScope->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center space-x-2">
                                <a href="{{ route('activity-scopes.edit', $activityScope->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-[#1A5555] text-white hover:bg-[#1A5555] border border-[#1A5555] rounded text-sm font-medium transition-colors">
                                    Edit
                                </a>
                                <form action="{{ route('activity-scopes.destroy', $activityScope->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 bg-[#FF0000] text-white hover:bg-red-100 border border-red-200 rounded text-sm font-medium transition-colors"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus Ruang Lingkup ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500 text-sm">
                                Belum ada data Ruang Lingkup.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($activityScopes->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $activityScopes->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
