<x-layouts.app>
    <x-slot:title>Kategori Evaluasi</x-slot>

    <div class="px-8 py-6">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Kategori Evaluasi
                </h1>
                <p class="text-gray-600 mt-1">
                    Kelola kategori kriteria evaluasi sistem.
                </p>
            </div>
            <a href="{{ route('evaluation-categories.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Kategori
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Nama Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Tipe Evaluasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Tipe Form</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Urutan</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($categories as $category)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $category->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($category->evaluation_type === 1)
                                            bg-blue-100 text-blue-800
                                        @else
                                            bg-orange-100 text-orange-800
                                        @endif">
                                        Level {{ $category->evaluation_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if ($category->form_type)
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $category->form_type === 'speaker' ? 'Narasumber' : 'Kegiatan' }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $category->order }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('evaluation-categories.edit', $category->id) }}"
                                           class="p-2 text-teal-600 hover:bg-teal-50 rounded-lg transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('evaluation-categories.destroy', $category->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p>Belum ada kategori evaluasi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($categories->hasPages())
            <div class="mt-8">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
