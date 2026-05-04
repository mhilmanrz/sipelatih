# Issue: Migrasi Legacy Layout (@extends) ke Component Layout (<x-layouts.app>)

## Latar Belakang
Saat ini project `sipelatih` memiliki inkonsistensi pada arsitektur layouting Blade. Berdasarkan hasil audit, terdapat sekitar 77 file yang masih menggunakan pendekatan template inheritance lama (`@extends`), dengan mayoritas menggunakan `@extends('layout.LayoutSuperAdmin')`. 

Standar baru untuk project ini adalah menggunakan Blade Component, yaitu `<x-layouts.app>`. Issue ini bertujuan untuk menyeragamkan seluruh view agar menggunakan satu arsitektur layout component.

## Tujuan
1. Menyeragamkan penggunaan layout menggunakan `<x-layouts.app>`.
2. Menghapus *technical debt* berupa file-file legacy layout yang redundan.

## Scope Pekerjaan
Target file adalah seluruh file `.blade.php` yang masih memanggil:
- `@extends('layout.LayoutSuperAdmin')`
- `@extends('layout.dashboard')`
- `@extends('layout.app')`

## High-Level Implementation Plan

### Phase 1: Validasi Kesiapan Component Layout
Sebelum memulai migrasi masal, pastikan component `<x-layouts.app>` sudah siap menampung semua kebutuhan dari layout lama.
- Pastikan slot untuk konten utama (`$slot`) berfungsi dengan baik.
- Pastikan cara passing variable seperti title halaman (`<x-slot:title>` atau component attribute) sudah terstandarisasi.
- Pastikan injection untuk CSS/JS khusus halaman (pengganti `@push('styles')` dan `@push('scripts')`) sudah didukung oleh `<x-layouts.app>`. Jika belum, tambahkan kapabilitas tersebut ke komponen `app.blade.php`.

### Phase 2: Konversi View secara Bertahap (Chunking)
Lakukan refaktor pada file `.blade.php` secara bertahap per modul (misal: `account`, `activity_*`, `usulan`, dst) untuk memudahkan proses *code review*.
- Ganti deklarasi `@extends` dan `@section('content')` menjadi tag pembungkus `<x-layouts.app>` ... `</x-layouts.app>`.
- Sesuaikan sintaks title dan block lainnya sesuai dengan format Blade Component.

### Phase 3: Verifikasi Partial Views
Beberapa direktori seperti `usulan/detail/*.blade.php` berisi file partial (potongan UI) yang di-include ke halaman utama.
- Pastikan perubahan pada parent layout tidak merusak rendering dari file-file partial tersebut.

### Phase 4: Cleanup & Penghapusan File Legacy
Setelah seluruh view berhasil dimigrasi dan ditest, lakukan pembersihan direktori layout:
- Hapus `resources/views/layout/LayoutSuperAdmin.blade.php`.
- Hapus komponen pendukung legacy seperti `sidebar-legacy.blade.php` dan `topbar-legacy.blade.php`.
- Hapus file layout lama lainnya yang sudah dipastikan *orphaned* (tidak terpakai sama sekali).

### Phase 5: QA & Formatting
- Pengecekan visual (UI Regression Test): Pastikan Sidebar, Topbar, dan styling area konten tidak pecah pada halaman yang dimigrasi.
- Jalankan code formatter standar project (contoh: Laravel Pint) pada file yang telah diubah untuk memastikan kerapian kode.

## Catatan Khusus untuk Implementer
- **Hindari Refaktor Logic**: Fokus murni pada penggantian struktur HTML/Blade layout. Jangan mengubah logic backend, controller, atau query database.
- **Efisiensi**: Sangat disarankan menggunakan metode *search and replace* berbasis regex atau script otomatisasi untuk mempercepat migrasi pola-pola yang identik, lalu disempurnakan secara manual.