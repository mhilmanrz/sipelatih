# 📋 Table Component Guide — Sipelatih

> Dokumentasi ini berisi seluruh komponen tabel, tombol aksi, toolbar, dan pagination yang digunakan di project **Sipelatih**. Copy-paste langsung ke project Laravel lain untuk tampilan yang identik.

---

## 🎨 Color Tokens

Semua warna yang dipakai di komponen tabel:

| Peran | Hex | Keterangan |
|---|---|---|
| **Primary Dark** | `#205252` | Toolbar background, button primer |
| **Primary Light** | `#1A5555` | Button "Tambah", button "Edit" (versi baru) |
| **Teal / Header** | `#007A7F` | Thead background tabel |
| **Accent Yellow** | `#D6DE20` | Button "Search", button "Import per Peserta" |
| **Blue** | `#3b82f6` | Button "Detail" |
| **Yellow** | `#eab308` | Button "Edit" (versi alternate) |
| **Red** | `#ef4444` | Button "Hapus" |

---

## 📦 Blade Components

### 1. `<x-table>` — Wrapper Tabel

**File:** `resources/views/components/table.blade.php`

```blade
<div class="overflow-x-auto w-full">
    <table class="w-full text-left border-collapse">
        @if (isset($header))
        <thead class="font-semibold text-white bg-[#007A7F] border border-white">
            {{ $header }}
        </thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
```

**Cara pakai:**

```blade
<x-table>
    <x-slot name="header">
        <tr>
            <th class="px-4 py-3">No.</th>
            <th class="px-4 py-3">Nama</th>
            <th class="px-4 py-3">Aksi</th>
        </tr>
    </x-slot>

    <tr class="border-b border-gray-200">
        <td class="px-4 py-3">1</td>
        <td class="px-4 py-3">Contoh Data</td>
        <td class="px-4 py-3">...</td>
    </tr>
</x-table>
```

---

### 2. `<x-table-toolbar>` — Search & Entries Bar

**File:** `resources/views/components/table-toolbar.blade.php`

```blade
@props([
    'actionUrl' => request()->url(),
    'searchPlaceholder' => 'Cari...'
])

<form method="GET" action="{{ $actionUrl }}"
    class="flex flex-wrap items-center justify-between gap-4 p-6 text-white rounded-t-lg bg-[#205252]">

    <div class="flex items-center gap-2">
        <span class="text-sm">Show</span>
        <select name="per_page" onchange="this.form.submit()"
            class="px-2 py-1 text-sm bg-transparent border border-white rounded outline-none w-20">
            <option value="10" class="text-black" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
            <option value="25" class="text-black" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
            <option value="50" class="text-black" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
        </select>
        <span class="text-sm">entries</span>
    </div>

    <div class="flex items-center gap-4">
        {{ $slot }}

        <div class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}"
                placeholder="{{ $searchPlaceholder }}"
                class="px-4 py-1.5 text-sm bg-transparent border border-white rounded-full outline-none placeholder-gray-300 w-64">
            <button type="submit"
                class="px-4 py-1.5 text-sm font-bold text-black transition rounded-full bg-[#D6DE20] hover:opacity-90">
                Search
            </button>
        </div>
    </div>
</form>
```

**Cara pakai:**

```blade
{{-- Tanpa slot tambahan --}}
<x-table-toolbar actionUrl="{{ route('users.index') }}" searchPlaceholder="Cari nama atau NIP..." />

{{-- Dengan filter di slot --}}
<x-table-toolbar actionUrl="{{ route('kegiatan.index') }}" searchPlaceholder="Cari kegiatan...">
    <select name="status" onchange="this.form.submit()"
        class="bg-transparent border border-white text-white rounded-full px-3 py-1.5 outline-none text-sm">
        <option value="" class="text-black">Semua Status</option>
        <option value="draft" class="text-black">Draft</option>
        <option value="submitted" class="text-black">Submitted</option>
    </select>
</x-table-toolbar>
```

---

### 3. `<x-table-footer>` — Pagination

**File:** `resources/views/components/table-footer.blade.php`

```blade
@props(['paginator'])

<div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-6 py-4 bg-white border-t border-gray-200 rounded-b-[20px]">
    <div class="text-sm text-gray-500">
        @if ($paginator->total() > 0)
        Showing
        <span class="font-semibold text-gray-700">{{ $paginator->firstItem() }}</span>
        –
        <span class="font-semibold text-gray-700">{{ $paginator->lastItem() }}</span>
        of
        <span class="font-semibold text-gray-700">{{ $paginator->total() }}</span>
        entries
        @else
        No entries found
        @endif
    </div>

    @if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-1">
        @if ($paginator->onFirstPage())
        <span class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-300 bg-gray-100 rounded-lg cursor-not-allowed select-none">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Prev
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}"
            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-[#205252] bg-white border border-gray-200 rounded-lg transition-all duration-200 hover:bg-[#205252] hover:text-white hover:border-[#205252] hover:shadow-md">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Prev
        </a>
        @endif

        @php
            $currentPage = $paginator->currentPage();
            $lastPage    = $paginator->lastPage();
            $start = max(1, $currentPage - 2);
            $end   = min($lastPage, $currentPage + 2);
            if ($currentPage <= 3) { $end = min($lastPage, 5); }
            if ($currentPage >= $lastPage - 2) { $start = max(1, $lastPage - 4); }
        @endphp

        @if ($start > 1)
        <a href="{{ $paginator->url(1) }}"
            class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-[#205252] bg-white border border-gray-200 rounded-lg transition-all duration-200 hover:bg-[#205252] hover:text-white hover:border-[#205252] hover:shadow-md">1</a>
        @if ($start > 2)
        <span class="inline-flex items-center justify-center w-9 h-9 text-sm text-gray-400 select-none">…</span>
        @endif
        @endif

        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $currentPage)
            <span class="inline-flex items-center justify-center w-9 h-9 text-sm font-bold text-white bg-[#205252] rounded-lg shadow-sm shadow-[#205252]/30">{{ $page }}</span>
            @else
            <a href="{{ $paginator->url($page) }}"
                class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-[#205252] bg-white border border-gray-200 rounded-lg transition-all duration-200 hover:bg-[#205252] hover:text-white hover:border-[#205252] hover:shadow-md">{{ $page }}</a>
            @endif
        @endfor

        @if ($end < $lastPage)
            @if ($end < $lastPage - 1)
            <span class="inline-flex items-center justify-center w-9 h-9 text-sm text-gray-400 select-none">…</span>
            @endif
            <a href="{{ $paginator->url($lastPage) }}"
                class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-[#205252] bg-white border border-gray-200 rounded-lg transition-all duration-200 hover:bg-[#205252] hover:text-white hover:border-[#205252] hover:shadow-md">{{ $lastPage }}</a>
        @endif

        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-[#205252] bg-white border border-gray-200 rounded-lg transition-all duration-200 hover:bg-[#205252] hover:text-white hover:border-[#205252] hover:shadow-md">
            Next
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        @else
        <span class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-300 bg-gray-100 rounded-lg cursor-not-allowed select-none">
            Next
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </span>
        @endif
    </nav>
    @endif
</div>
```

**Cara pakai:**

```blade
{{-- Selalu append query agar filter/search ikut terbawa ke halaman berikutnya --}}
<x-table-footer :paginator="$items->appends(request()->query())" />

{{-- Simple tanpa filter --}}
<x-table-footer :paginator="$users" />
```

---

## 🔘 Button Patterns

### Tombol di Page Header

```blade
{{-- Tambah Data (Hijau Tua) --}}
<a href="{{ route('resource.create') }}"
    class="inline-flex items-center justify-center bg-[#1A5555] hover:opacity-85 text-white font-bold px-5 py-2.5 rounded-full shadow transition">
    <svg class="w-5 h-5 mr-2 inline-block -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
    </svg>
    Tambah Data
</a>

{{-- Import Kegiatan (Hijau Tua + Icon) --}}
<a href="{{ route('resource.import.page') }}"
    class="inline-flex items-center justify-center text-white px-5 py-2.5 rounded-full font-bold shadow transition hover:opacity-85"
    style="background-color:#205252;">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
    </svg>
    Import Kegiatan
</a>

{{-- Import per Peserta (Kuning) --}}
<a href="{{ route('resource.import-per-peserta.page') }}"
    class="inline-flex items-center justify-center text-black px-5 py-2.5 rounded-full font-bold shadow transition hover:opacity-85"
    style="background-color:#D6DE20;">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
    </svg>
    Import per Peserta
</a>
```

---

### Tombol Aksi di Kolom Tabel

#### Detail (Biru)

```blade
<a href="{{ route('resource.show', $item->id) }}"
    class="text-white px-3 py-1.5 rounded text-sm font-semibold transition hover:opacity-85"
    style="background-color: #3b82f6;">
    Detail
</a>
```

#### Edit — Hijau (Standar)

```blade
<a href="{{ route('resource.edit', $item->id) }}"
    class="inline-flex items-center px-3 py-1.5 bg-[#1A5555] text-white rounded text-sm font-medium transition hover:opacity-90">
    Edit
</a>
```

#### Edit — Kuning (Alternate)

```blade
<a href="{{ route('resource.edit', $item->id) }}"
    class="text-white px-3 py-1.5 rounded text-sm font-semibold transition hover:opacity-85"
    style="background-color: #eab308;">
    Edit
</a>
```

#### Hapus (Merah)

```blade
<form action="{{ route('resource.destroy', $item->id) }}" method="POST"
    class="inline m-0"
    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
    @csrf
    @method('DELETE')
    <button type="submit"
        style="background-color: #ef4444;"
        class="text-white px-3 py-1.5 rounded text-sm font-semibold transition hover:opacity-85 inline-block">
        Hapus
    </button>
</form>
```

#### Wrapper Kolom Aksi

```blade
<td class="whitespace-nowrap text-sm font-medium text-center border border-gray-200 py-3 px-4">
    <div class="flex justify-center items-center gap-2">
        {{-- Tombol di sini --}}
    </div>
</td>
```

---

## 🏗️ Template Halaman Lengkap (Full CRUD Page)

```blade
<x-layouts.app>
    @section('title', 'Nama Halaman')

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">NAMA HALAMAN</h1>
        <a href="{{ route('resource.create') }}"
            class="inline-flex items-center justify-center bg-[#1A5555] hover:opacity-85 text-white font-bold px-5 py-2.5 rounded-full shadow transition">
            + Tambah Data
        </a>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
    <div class="relative px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Card Tabel --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">

        <x-table-toolbar actionUrl="{{ route('resource.index') }}" searchPlaceholder="Cari...">
            {{-- Filter tambahan (opsional) --}}
        </x-table-toolbar>

        <x-table>
            <x-slot name="header">
                <tr>
                    <th class="px-4 py-3">No.</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </x-slot>

            @forelse($items as $index => $item)
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-500">{{ $items->firstItem() + $index }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ $item->name }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('resource.show', $item->id) }}"
                            class="text-white px-3 py-1.5 rounded text-sm font-semibold transition hover:opacity-85"
                            style="background-color: #3b82f6;">Detail</a>

                        <a href="{{ route('resource.edit', $item->id) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-[#1A5555] text-white rounded text-sm font-medium transition hover:opacity-90">Edit</a>

                        <form action="{{ route('resource.destroy', $item->id) }}" method="POST"
                            class="inline m-0"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="background-color: #ef4444;"
                                class="text-white px-3 py-1.5 rounded text-sm font-semibold transition hover:opacity-85 inline-block">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-4 py-8 text-center text-gray-500">Belum ada data.</td>
            </tr>
            @endforelse
        </x-table>

        <x-table-footer :paginator="$items->appends(request()->query())" />
    </div>

</x-layouts.app>
```

---

## 📐 Anatomy Visual

```
┌────────────────────────────────────────────────────────────────┐
│  NAMA HALAMAN                              [+ Tambah Data]     │  ← Page Header
└────────────────────────────────────────────────────────────────┘
┌────────────────────────────────────────────────────────────────┐
│ ██████████████████████ TABLE CARD ██████████████████████████   │
│┌──────────────────────────────────────────────────────────────┐│
││ Show [10▾] entries                [Cari...] [Search]         ││  ← x-table-toolbar (bg #205252)
│└──────────────────────────────────────────────────────────────┘│
│┌──────────────────────────────────────────────────────────────┐│
││ No. │ Kolom 1  │ Kolom 2  │ Aksi                             ││  ← thead (bg #007A7F)
│├──────────────────────────────────────────────────────────────┤│
││  1  │  Data    │  Data    │ [Detail][Edit][Hapus]            ││
││  2  │  Data    │  Data    │ [Detail][Edit][Hapus]            ││  ← tbody
│└──────────────────────────────────────────────────────────────┘│
│┌──────────────────────────────────────────────────────────────┐│
││ Showing 1–10 of 42 entries    [Prev][1][2][3][4][5][Next]    ││  ← x-table-footer
│└──────────────────────────────────────────────────────────────┘│
└────────────────────────────────────────────────────────────────┘
```

---

## ✅ Checklist Setup di Project Baru

- [ ] Copy `resources/views/components/table.blade.php`
- [ ] Copy `resources/views/components/table-toolbar.blade.php`
- [ ] Copy `resources/views/components/table-footer.blade.php`
- [ ] TailwindCSS v4 terpasang dan dikonfigurasi
- [ ] Route sesuai dengan nama resource
- [ ] Controller menggunakan `->paginate($perPage)`
- [ ] Paginator menggunakan `->appends(request()->query())`

---

## 🔧 Tips Controller

```php
public function index(Request $request): View
{
    $perPage = $request->integer('per_page', 10);

    $items = Item::query()
        ->when($request->q, fn ($q, $search) => $q->where('name', 'like', "%{$search}%"))
        ->latest()
        ->paginate($perPage);

    return view('resource.index', compact('items'));
}
```
