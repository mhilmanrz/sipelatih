# 📘 Panduan Frontend siPELATIH

> Panduan lengkap untuk menyamakan tampilan frontend di projek lain agar identik dengan siPELATIH.

---

## 1. Tech Stack & Dependencies

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| CSS Framework | Tailwind CSS | v4 |
| Font | Poppins (Google Fonts) | 300-700 |
| Icons | Font Awesome | 6.5.0 |
| JS Interactivity | Alpine.js (via Vite) | - |
| Build Tool | Vite | - |
| Backend Template | Laravel Blade | v12 |

### CDN yang Digunakan
```html
<!-- Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
```

---

## 2. Color Palette

| Token | Hex | Penggunaan |
|-------|-----|------------|
| **Primary BG** | `#0DBBCB` | Background body utama |
| **Sidebar BG** | `#1A5555` | Background sidebar |
| **Sidebar Header** | `#113a3a` | Header logo sidebar |
| **Sidebar Active Icon** | `#1fd1d1` | Icon menu aktif |
| **Table Toolbar** | `#205252` | Background toolbar tabel |
| **Table Header** | `#007A7F` / `#007a7a` | Background thead |
| **Accent Yellow** | `#D6DE20` | Tombol Search, Import |
| **Teal Button** | `#1A5555` | Tombol Tambah Data |
| **Card Icon** | `#00B8A5` | Icon pada stat card dashboard |
| **Topbar** | `#FFFFFF` | Background topbar |
| **Card BG** | `#FFFFFF` | Background card/tabel |
| **Login BG** | `#1bb7b7` | Background halaman login |
| **Login Slogan** | `#0a4f4f` | Background bar slogan |
| **Login Button** | `#0a8f8f` | Tombol login |

---

## 3. Layout Utama (App Shell)

Struktur layout menggunakan **sidebar fixed kiri + topbar fixed atas + content area**.

```
┌──────────────────────────────────────────────┐
│ SIDEBAR (240px) │ TOPBAR (60px, fixed top)   │
│ - Logo           │ [☰] ............. [User ▾]│
│ - Nav Menu       ├──────────────────────────-─┤
│ - Dropdowns      │                            │
│                  │   CONTENT AREA (p-6)       │
│                  │                            │
│                  │                            │
│                  ├────────────────────────────-┤
│                  │   FOOTER                   │
└──────────────────────────────────────────────-┘
```

### Body
```html
<body class="bg-[#0DBBCB] font-['Poppins'] text-gray-800 antialiased overflow-x-hidden min-h-screen"
      x-data="{ sidebarOpen: true }">
```

### Main Wrapper
```html
<div class="flex relative w-full min-h-screen">
    <!-- SIDEBAR -->
    <!-- MAIN CONTENT -->
    <div class="flex flex-col w-full transition-all duration-300 ease-in-out"
         :class="sidebarOpen ? 'ml-[240px]' : 'ml-0'">
        <!-- TOPBAR -->
        <main class="flex-1 p-6 mt-[60px] transition-all duration-300 ease-in-out">
            {{ $slot }}
        </main>
        <footer class="text-center p-3 text-sm text-white/80">
            © 2026 — Credit
        </footer>
    </div>
</div>
```

---

## 4. Sidebar

- **Lebar**: `240px`, fixed left, full height
- **Background**: `bg-[#1A5555]`
- **Scrollbar custom**: thin, transparent track

### Menu Item (Normal)
```html
<a href="#" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group text-gray-300 hover:bg-white/5 hover:text-white">
    <i class="fa-solid fa-house w-6 text-center mr-2 text-gray-400 group-hover:text-gray-200"></i>
    <span class="text-sm">Dashboard</span>
</a>
```

### Menu Item (Active)
```html
<a href="#" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group bg-white/10 text-white font-medium shadow-sm ring-1 ring-white/5">
    <i class="fa-solid fa-house w-6 text-center mr-2 text-[#1fd1d1]"></i>
    <span class="text-sm">Dashboard</span>
</a>
```

### Dropdown Menu (menggunakan `<details>`)
```html
<details class="group mt-2">
    <summary class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 cursor-pointer list-none [&::-webkit-details-marker]:hidden select-none text-gray-300 hover:bg-white/5 hover:text-white">
        <span class="flex items-center">
            <i class="fa-solid fa-chart-line w-6 text-center mr-2 text-gray-400 group-hover:text-gray-200"></i>
            <span class="text-sm">Monitoring</span>
        </span>
        <i class="fa-solid fa-chevron-down text-[10px] transform group-open:rotate-180 transition-transform duration-300 text-gray-400"></i>
    </summary>
    <div class="flex flex-col mt-1 mb-2 space-y-1 relative before:content-[''] before:absolute before:left-[23px] before:top-2 before:bottom-2 before:w-[1.5px] before:bg-white/10 before:rounded-full">
        <a href="#" class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm text-gray-400 hover:text-white hover:bg-white/5">
            <i class="fa-solid fa-chart-line w-5 text-center mr-2 opacity-80"></i> Sub Menu
        </a>
    </div>
</details>
```

### Section Divider
```html
<div class="px-3 pb-1 pt-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Administrator</div>
```

---

## 5. Topbar

```html
<header class="h-[60px] bg-white flex items-center justify-between px-6 fixed top-0 right-0 z-[9000] shadow-sm transition-all duration-300"
    :class="sidebarOpen ? 'left-[240px]' : 'left-0'">
    <!-- Toggle Button -->
    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 hover:text-black focus:outline-none">
        <i class="fa fa-bars text-xl"></i>
    </button>
    <!-- Profile Badge -->
    <div class="flex items-center gap-2 px-5 py-2 bg-gray-200 hover:bg-gray-300 rounded-full cursor-pointer select-none transition-colors">
        <i class="fa fa-user-circle text-gray-700 text-lg"></i>
        <span class="text-sm font-medium text-black">Username</span>
        <i class="fa fa-chevron-down text-gray-600 text-xs ml-1"></i>
    </div>
</header>
```

---

## 6. Page Title

```html
<h2 class="text-white text-2xl font-semibold uppercase mb-6">
    JUDUL HALAMAN
</h2>
```

Atau gunakan `<h1>` untuk halaman yang menggunakan inline styling:
```html
<h1 class="text-2xl font-bold text-white">JUDUL HALAMAN</h1>
```

---

## 7. Buttons

### 7.1 Primary (Teal/Dark)
```html
<a href="#" class="inline-flex items-center justify-center bg-[#1A5555] hover:opacity-85 text-white font-bold px-5 py-2.5 rounded-full shadow transition">
    + Tambah Data
</a>
```

### 7.2 Accent (Yellow)
```html
<button class="inline-flex items-center justify-center bg-[#D6DE20] hover:opacity-85 text-black font-bold px-5 py-2.5 rounded-full shadow transition">
    Import Excel
</button>
```

### 7.3 Search Button
```html
<button type="submit" style="background-color:#D6DE20; color:black;"
    class="px-4 py-1.5 rounded-full font-bold hover:opacity-90 transition text-sm">
    Search
</button>
```

### 7.4 Action Buttons (dalam tabel)

**Detail (Blue)**
```html
<a href="#" class="text-white px-3 py-1.5 rounded text-sm font-semibold transition hover:opacity-85"
   style="background-color: #3b82f6;">Detail</a>
```

**Edit (Yellow)**
```html
<a href="#" class="text-white px-3 py-1.5 rounded text-sm font-semibold transition hover:opacity-85"
   style="background-color: #eab308;">Edit</a>
```

**Hapus (Red)**
```html
<button type="submit" style="background-color: #ef4444;"
    class="text-white px-3 py-1.5 rounded text-sm font-semibold transition hover:opacity-85">
    Hapus
</button>
```

**Alternative Edit (Outline style)**
```html
<a href="#" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 rounded text-sm font-medium transition-colors">
    Edit
</a>
```

### 7.5 Download Button
```html
<a href="#" class="bg-[#006D73] text-white px-6 py-3 rounded-full flex items-center gap-2 shadow hover:opacity-90 w-fit">
    ⬇ Download Laporan
</a>
```

### 7.6 Form Save/Cancel
```html
<!-- Save -->
<button class="btn-save" style="background:#007a7f; color:#fff; padding:9px 22px; border-radius:20px; border:none;">
    💾 SIMPAN
</button>
<!-- Cancel -->
<button class="btn-cancel" style="background:#999; color:#fff; padding:9px 22px; border-radius:20px; border:none;">
    ✖ BATAL
</button>
```

---

## 8. Table System

### 8.1 Table Container
```html
<div class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Toolbar -->
    <!-- Table -->
    <!-- Footer/Pagination -->
</div>
```

### 8.2 Table Toolbar (Control Bar)
```html
<form method="GET" action="#"
    class="flex flex-wrap justify-between items-center p-6 gap-4 text-white"
    style="background-color:#205252;">
    <!-- Left: Show entries -->
    <div class="flex items-center gap-2">
        <span>Show</span>
        <select name="entries" onchange="this.form.submit()"
            class="bg-transparent border border-white text-white rounded px-2 py-1 outline-none">
            <option value="10" class="text-black">10</option>
            <option value="25" class="text-black">25</option>
            <option value="50" class="text-black">50</option>
        </select>
        <span>entries</span>
    </div>
    <!-- Right: Filters + Search -->
    <div class="flex items-center gap-2">
        <input type="text" name="q" placeholder="Search..."
            class="bg-transparent border border-white text-white placeholder-gray-300 rounded-full px-4 py-1.5 outline-none text-sm">
        <button type="submit" style="background-color:#D6DE20; color:black;"
            class="px-4 py-1.5 rounded-full font-bold hover:opacity-90 transition text-sm">
            Search
        </button>
    </div>
</form>
```

### 8.3 Filter Dropdown (dalam toolbar)
```html
<select name="year" onchange="this.form.submit()"
    class="bg-transparent border border-white text-white rounded-full px-3 py-1.5 outline-none text-sm">
    <option value="" class="text-black">Semua Tahun</option>
    <option value="2026" class="text-black">2026</option>
</select>
```

### 8.4 Table Header (thead)
```html
<thead class="bg-[#007a7a] border border-white py-3 px-4 font-semibold">
    <tr>
        <th class="text-center w-12 border border-white py-3 px-4 font-semibold">No</th>
        <th class="text-center border border-white py-3 px-4 font-semibold">Aksi</th>
        <th class="text-left border border-white py-3 px-4 font-semibold">Nama</th>
    </tr>
</thead>
```

### 8.5 Table Body (tbody)
```html
<tbody class="bg-white divide-y divide-gray-200">
    <tr class="hover:bg-gray-50 transition">
        <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-500">1</td>
        <td class="border border-gray-200 py-3 px-4 text-sm text-gray-900">Data</td>
    </tr>
    <!-- Empty state -->
    <tr>
        <td colspan="9" class="text-center text-gray-500 text-sm border border-gray-200 py-6 px-4">
            Tidak ada data.
        </td>
    </tr>
</tbody>
```

### 8.6 Table Footer / Pagination

```html
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-6 py-4 bg-white border-t border-gray-200 rounded-b-[20px]">
    <!-- Info -->
    <div class="text-sm text-gray-500">
        Showing <span class="font-semibold text-gray-700">1</span> –
        <span class="font-semibold text-gray-700">10</span> of
        <span class="font-semibold text-gray-700">50</span> entries
    </div>
    <!-- Pagination -->
    <nav class="flex items-center gap-1">
        <!-- Disabled Prev -->
        <span class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-300 bg-gray-100 rounded-lg cursor-not-allowed">
            Prev
        </span>
        <!-- Active Page -->
        <span class="inline-flex items-center justify-center w-9 h-9 text-sm font-bold text-white bg-[#205252] rounded-lg shadow-sm shadow-[#205252]/30">
            1
        </span>
        <!-- Inactive Page -->
        <a href="#" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-[#205252] bg-white border border-gray-200 rounded-lg transition-all duration-200 hover:bg-[#205252] hover:text-white hover:border-[#205252] hover:shadow-md">
            2
        </a>
        <!-- Next -->
        <a href="#" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-[#205252] bg-white border border-gray-200 rounded-lg transition-all duration-200 hover:bg-[#205252] hover:text-white hover:border-[#205252] hover:shadow-md">
            Next
        </a>
    </nav>
</div>
```

---

## 9. Status Badges

```html
<!-- Draft -->
<span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">Draft</span>

<!-- Submitted -->
<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">Submitted</span>

<!-- Revision -->
<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">Revision</span>

<!-- Accepted -->
<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Accepted</span>

<!-- Tercapai -->
<span class="bg-green-500 text-white px-4 py-1 rounded-full text-xs">Tercapai</span>

<!-- Belum Tercapai -->
<span class="bg-red-500 text-white px-4 py-1 rounded-full text-xs">Belum Tercapai</span>
```

---

## 10. Dashboard Stat Cards

```html
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-[15px] mb-6">
    <div class="bg-white rounded-xl p-[18px] text-center shadow-[0_2px_6px_rgba(0,0,0,0.12)]">
        <i class="fa fa-file-alt text-[26px] text-[#00B8A5] mb-1.5 block"></i>
        <span class="text-gray-800 text-sm">Label</span><br>
        <b class="text-base text-gray-900">0</b>
    </div>
</div>
```

---

## 11. Cards (White Content Area)

```html
<!-- Standard Card -->
<div class="bg-white rounded-xl p-5 shadow-sm min-h-[320px]">
    <h3 class="mb-4 border-b border-gray-100 pb-3 text-lg font-semibold text-gray-800">Title</h3>
    <!-- Content -->
</div>

<!-- KPI Summary Card -->
<div class="bg-white rounded-lg shadow p-6 border-t-4 border-teal-500">
    <h3 class="text-lg font-bold text-gray-700 mb-2">Metric Title</h3>
    <p class="text-3xl font-extrabold text-teal-600">85%</p>
    <p class="text-sm text-gray-500 mt-1">Description</p>
</div>
```

---

## 12. Forms

### 12.1 Form Card (Legacy CSS)
```css
.form-card {
    background: #fff;
    padding: 25px;
    border-radius: 18px;
    max-width: 750px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}
```

### 12.2 Form Row (Legacy CSS)
```css
.form-row {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 15px;
    margin-bottom: 16px;
    align-items: center;
}
```

### 12.3 Form Input (Legacy CSS)
```css
.form-row input,
.form-row select,
.form-row textarea {
    width: 100%;
    padding: 9px 12px;
    border: 2px solid #00B8A5;
    border-radius: 12px;
    font-size: 14px;
    background: #f9f9f9;
}
.form-row input:focus,
.form-row select:focus {
    border-color: #007a7f;
    box-shadow: 0 0 0 3px rgba(0,184,165,.15);
    background: #fff;
    outline: none;
}
```

### 12.4 Modal Form (Tailwind)
```html
<div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 overflow-hidden">
    <form>
        <!-- Header -->
        <div class="bg-teal-600 px-6 py-4 flex justify-between items-center text-white">
            <h2 class="text-lg font-bold">MODAL TITLE</h2>
            <button type="button" class="text-white hover:text-gray-200 text-2xl">&times;</button>
        </div>
        <!-- Body -->
        <div class="p-6 space-y-4 text-left">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Field <span class="text-red-500">*</span></label>
                <input type="text" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm">
            </div>
        </div>
        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 border-t border-gray-200">
            <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 font-medium">BATAL</button>
            <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 font-medium shadow">SIMPAN</button>
        </div>
    </form>
</div>
```

---

## 13. Alert Messages

```html
<!-- Success -->
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">Pesan sukses</span>
</div>

<!-- Error -->
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
    <strong>Terdapat kesalahan:</strong>
    <ul class="mt-2 list-disc list-inside">
        <li>Error detail</li>
    </ul>
</div>
```

---

## 14. Login Page

Halaman login **tidak** menggunakan layout app shell. Standalone page dengan CSS khusus.

| Elemen | Style |
|--------|-------|
| Body BG | `#1bb7b7` |
| Card | `background:#fff; border-radius:18px; padding:40px; width:420px; box-shadow:0 15px 40px rgba(0,0,0,.15)` |
| Label | `color:#0a6a6a; font-weight:bold` |
| Input | `border:none; background:#e5e5e5; border-radius:12px; padding:12px` |
| Button | `background:#0a8f8f; color:#fff; border-radius:25px; padding:12px 40px` |
| Slogan Bar | `background:#0a4f4f; color:#fff; padding:20px 0; animation: marquee` |
| Footer | `background:#0a4f4f; color:#fff; padding:20px` |

---

## 15. Checklist Implementasi

Gunakan checklist ini saat apply ke projek lain:

- [ ] Install Tailwind CSS v4 via Vite
- [ ] Pasang Google Font **Poppins** (300-700)
- [ ] Pasang **Font Awesome 6.5**
- [ ] Set body: `bg-[#0DBBCB] font-['Poppins']`
- [ ] Buat sidebar component (240px, `bg-[#1A5555]`, fixed)
- [ ] Buat topbar component (60px, white, fixed, z-9000)
- [ ] Sidebar toggle via Alpine.js `x-data="{ sidebarOpen: true }"`
- [ ] Table header: `bg-[#007a7a]` + white borders
- [ ] Table toolbar: `bg-[#205252]` + white text
- [ ] Search button: `bg-[#D6DE20]` + black text + rounded-full
- [ ] Tambah button: `bg-[#1A5555]` + white text + rounded-full
- [ ] Pagination: `bg-[#205252]` active + white outline pages
- [ ] Status badges: rounded-full, color-coded
- [ ] Form inputs: `border: 2px solid #00B8A5; border-radius:12px`
- [ ] Modal: teal-600 header, gray-50 footer
- [ ] Alert: green-100/red-100 with border

---

## 16. Pattern: Halaman CRUD Index

Template standar untuk semua halaman index/list:

```
┌─────────────────────────────────────┐
│ TITLE (white, uppercase)   [Button] │
├─────────────────────────────────────┤
│ ┌─────────────────────────────────┐ │
│ │ TOOLBAR (bg-[#205252])          │ │
│ │ Show [10▾] entries    [Search]  │ │
│ ├─────────────────────────────────┤ │
│ │ TABLE HEADER (bg-[#007a7a])     │ │
│ ├─────────────────────────────────┤ │
│ │ TABLE BODY (white, hover:gray)  │ │
│ ├─────────────────────────────────┤ │
│ │ FOOTER (pagination)             │ │
│ └─────────────────────────────────┘ │
└─────────────────────────────────────┘
```

---

> **Catatan**: Projek ini menggunakan dua pola layout — `<x-layouts.app>` (component-based, Blade component) dan `@extends('layout.LayoutSuperAdmin')` (legacy, section-based). Untuk projek baru, gunakan pattern `<x-layouts.app>` yang lebih modern.
