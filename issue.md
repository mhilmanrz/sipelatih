# Feature Planning: Role & Permission Management Implementation

## Deskripsi Singkat
Mengimplementasikan sistem manajemen `Permission` memanfaatkan package `spatie/laravel-permission` yang sudah terinstal pada aplikasi. Fitur ini berfokus pada pembuatan halaman CRUD untuk Data Permissions, mengintegrasikan assign permission pada halaman Role, dan menerapkan permission check di sidebar menu.

## Scope of Work (Lingkup Pekerjaan)

### 1. Modul Manajemen Permission (CRUD)
- **Routing**: Daftarkan resource route untuk `permissions` di `routes/web.php` yang diproteksi dengan middleware auth dan otorisasi yang sesuai (misal khusus superadmin).
- **Controller**: Buat `PermissionController` untuk melayani fungsi list, create, edit, update, dan delete permission.
- **Views**:
  - Buat file view `index.blade.php` (menampilkan daftar permission dalam bentuk tabel, mendukung pencarian, dan pagination).
  - Buat file view `create.blade.php` & `edit.blade.php` (form input nama permission).
- **Validasi**: Pastikan field `name` divalidasi `required` dan `unique` di dalam tabel `permissions`.

### 2. Integrasi Permission ke Modul Role
- **Controller Modifikasi**: Update method `create` dan `edit` pada `RoleController` saat ini agar mengirimkan seluruh data permission (bisa di-grouping jika datanya banyak) ke view.
- **View Modifikasi**:
  - Di halaman Create dan Edit Role (`roles/create.blade.php` & `roles/edit.blade.php`), tambahkan komponen input berbasis checkbox (atau multiselect) yang me-list semua permission.
  - Tandai secara otomatis checkbox permission yang sudah dimiliki role tersebut ketika membuka halaman edit.
- **Action Modifikasi**: Update proses store dan update di `RoleController` agar memanggil `$role->syncPermissions($request->permissions)` berdasarkan input dari form.

### 3. Pengecekan Permission di Navigasi Sidebar
- **File Target**: `resources/views/components/layouts/sidebar.blade.php`.
- **Instruksi**:
  - Ganti atau kombinasikan logic `@hasrole` dan `@hasanyrole` yang hardcoded saat ini dengan directive `@can('nama-permission')` yang lebih granular untuk setiap menu (sesuaikan penamaan permissionnya).
  - Sebagai contoh: `Data Pegawai` hanya muncul jika user memiliki permission `@can('view users')`.
  - Pastikan setiap blok menu master data memiliki pengecekan permission-nya sendiri (misal: `view professions`, `view accounts`, dll).

### 4. Seeder Inisialisasi (Penting)
- **Seeder File**: Buat atau update `PermissionSeeder`.
- **Instruksi**: Daftarkan nama-nama permission standar yang akan dipakai di sidebar (seperti `view users`, `view roles`, `view dashboard`, dll) dan pastikan secara otomatis memberikan (`assign`) semua permission tersebut ke role `superadmin` agar akses tidak terputus.

## Catatan Tambahan (High Level)
- Gunakan TailwindCSS dan styling form/table yang sudah ada (reuse layout & komponen yang sudah dipakai di menu lain).
- Fokus pada fungsionalitas dan pastikan flow penambahan role baru dengan permission-nya bisa berfungsi dengan lancar sebelum lanjut ke middleware/route protection yang lebih ketat.