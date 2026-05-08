# SiPelatih — Sistem Informasi Pelatihan

Aplikasi web untuk manajemen pelatihan diklat yang mencakup pengelolaan kegiatan pelatihan, peserta, narasumber, moderator, penilaian, pagu anggaran, dan pelaporan. Dibangun dengan Laravel 13 dan Tailwind CSS 4.

---

## Daftar Isi

- [Tech Stack](#tech-stack)
- [Arsitektur & Struktur Proyek](#arsitektur--struktur-proyek)
- [Database Schema](#database-schema)
- [API Reference](#api-reference)
- [Setup & Instalasi](#setup--instalasi)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Testing](#testing)
- [Library & Dependencies](#library--dependencies)

---

## Tech Stack

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| Backend Framework | Laravel | 13.7 |
| Bahasa | PHP | 8.2+ |
| Database | MySQL | - |
| Frontend CSS | Tailwind CSS | 4.1 |
| Frontend JS | Alpine.js | 3.15 |
| Asset Bundler | Vite | 7.x |
| PDF Generation | barryvdh/laravel-dompdf | 3.1 |
| DOCX Generation | phpoffice/phpword | - |
| Excel Import/Export | maatwebsite/excel | 4.0 |
| Auth/Permissions | spatie/laravel-permission | 7.2 |
| API Auth | Laravel Sanctum | 4.3 |
| Code Style | Laravel Pint | 1.29 |
| Testing | PHPUnit | 12.x |

---

## Arsitektur & Struktur Proyek

Aplikasi mengikuti konvensi Laravel 13 dengan struktur yang dimodularisasi berdasarkan domain.

```
sipelatih/
├── app/
│   ├── Exports/                    # Excel export classes (maatwebsite/excel)
│   │   ├── AccountsTemplateExport.php
│   │   ├── ActivityPerParticipantTemplateExport.php
│   │   ├── ActivityScoreTemplateExport.php
│   │   ├── ActivityTemplateExport.php
│   │   ├── BudgetTemplateExport.php
│   │   ├── ParticipantTemplateExport.php
│   │   └── UsersTemplateExport.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Act/                  # Domain Kegiatan/Pelatihan
│   │   │   │   ├── ActivityController.php
│   │   │   │   ├── ActivityCategoryController.php
│   │   │   │   ├── ActivityFormatController.php
│   │   │   │   ├── ActivityKakFileController.php
│   │   │   │   ├── ActivityMaterialController.php
│   │   │   │   ├── ActivityMethodController.php
│   │   │   │   ├── ActivityModeratorController.php
│   │   │   │   ├── ActivityParticipantController.php
│   │   │   │   ├── ActivityProfessionController.php
│   │   │   │   ├── ActivityReportController.php
│   │   │   │   ├── ActivityScopeController.php
│   │   │   │   ├── ActivityScoreController.php
│   │   │   │   ├── ActivityScoreSettingController.php
│   │   │   │   ├── ActivitySpeakerController.php
│   │   │   │   ├── ActivityStatusController.php
│   │   │   │   ├── ActivityTargetController.php
│   │   │   │   ├── ActivityTypeController.php
│   │   │   │   ├── BatchController.php
│   │   │   │   ├── BudgetCategoryController.php
│   │   │   │   ├── FundSourceController.php
│   │   │   │   ├── MaterialTypeController.php
│   │   │   │   └── TargetParticipantController.php
│   │   │   │
│   │   │   ├── User/                # Domain Manajemen Pengguna
│   │   │   │   ├── AccountController.php
│   │   │   │   ├── EmploymentTypeController.php
│   │   │   │   ├── PositionController.php
│   │   │   │   ├── PositionsController.php  (API-only)
│   │   │   │   ├── ProfessionController.php
│   │   │   │   ├── RankController.php
│   │   │   │   ├── RoleController.php
│   │   │   │   ├── UserController.php
│   │   │   │   └── WorkUnitController.php
│   │   │   │
│   │   │   ├── ActivityNameController.php
│   │   │   ├── AppSettingController.php
│   │   │   ├── AuthController.php
│   │   │   ├── BudgetCategoryController.php  (API-only)
│   │   │   ├── BudgetController.php          (API-only)
│   │   │   ├── Controller.php                (Base controller)
│   │   │   ├── DashboardController.php
│   │   │   ├── IndikatorKinerjaController.php
│   │   │   ├── InternalActivityFormController.php
│   │   │   ├── MonitoringJplController.php
│   │   │   ├── NotaDinasController.php
│   │   │   ├── PaguController.php
│   │   │   ├── ProfessionCategoryController.php
│   │   │   └── UsulanDiklatController.php
│   │   │
│   │   └── Requests/
│   │       ├── act/                  # Form requests domain Kegiatan
│   │       └── user/                 # Form requests domain Pengguna
│   │
│   ├── Imports/                     # Excel import classes (maatwebsite/excel)
│   │   ├── AccountsImport.php
│   │   ├── ActivityImport.php
│   │   ├── ActivityNameImport.php
│   │   ├── ActivityPerParticipantImport.php
│   │   ├── ActivityScoreImport.php
│   │   ├── BudgetImport.php
│   │   ├── ParticipantImport.php
│   │   └── UsersImport.php
│   │
│   ├── Jobs/                        # Queue jobs untuk import data
│   │   ├── ImportAccountsJob.php
│   │   ├── ImportActivityJob.php
│   │   ├── ImportActivityNameJob.php
│   │   ├── ImportActivityParticipantJob.php
│   │   ├── ImportActivityScoreJob.php
│   │   ├── ImportBudgetJob.php
│   │   ├── ImportParticipantJob.php
│   │   └── ImportUsersJob.php
│   │
│   ├── Models/
│   │   ├── Act/                      # Model domain Kegiatan
│   │   │   ├── Activity.php
│   │   │   ├── ActivityCategory.php
│   │   │   ├── ActivityComponentScore.php
│   │   │   ├── ActivityFormat.php
│   │   │   ├── ActivityGradeCategory.php
│   │   │   ├── ActivityKakFile.php
│   │   │   ├── ActivityMaterial.php
│   │   │   ├── ActivityMethod.php
│   │   │   ├── ActivityModerator.php
│   │   │   ├── ActivityName.php
│   │   │   ├── ActivityParticipant.php
│   │   │   ├── ActivityProfession.php
│   │   │   ├── ActivityReport.php
│   │   │   ├── ActivityScope.php
│   │   │   ├── ActivityScore.php
│   │   │   ├── ActivityScoreComponent.php
│   │   │   ├── ActivityScoreSetting.php
│   │   │   ├── ActivitySpeaker.php
│   │   │   ├── ActivityStatus.php
│   │   │   ├── ActivityTarget.php
│   │   │   ├── ActivityType.php
│   │   │   ├── Batch.php
│   │   │   ├── FundSource.php
│   │   │   ├── MaterialType.php
│   │   │   └── TargetParticipant.php
│   │   │
│   │   ├── User/                    # Model domain Pengguna
│   │   │   ├── EmploymentType.php
│   │   │   ├── Position.php
│   │   │   ├── Positions.php
│   │   │   ├── Profession.php
│   │   │   ├── Rank.php
│   │   │   ├── User.php
│   │   │   └── WorkUnit.php
│   │   │
│   │   ├── AppSetting.php
│   │   ├── Budget.php
│   │   ├── BudgetCategory.php
│   │   ├── ProfessionCategory.php
│   │   └── ... (legacy models di root)
│   │
│   └── Providers/
│       └── AppServiceProvider.php
│
├── database/
│   ├── migrations/                  # 61 migration files
│   ├── factories/
│   │   └── UserFactory.php
│   └── seeders/                     # 30 seeder files
│       ├── DatabaseSeeder.php
│       ├── SuperAdminSeeder.php
│       ├── RolePermissionSeeder.php
│       └── ... (seeders per model)
│
├── resources/
│   ├── css/                         # Tailwind CSS
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/                       # Blade templates
│       ├── account/
│       ├── act/
│       ├── activity_category/
│       ├── activity_format/
│       ├── activity_method/
│       ├── activity_scope/
│       ├── activity_type/
│       ├── batch/
│       ├── budget_categories/
│       ├── components/
│       ├── dashboard.blade.php
│       ├── dictionaries/
│       ├── employment_type/
│       ├── evaluasi1.blade.php
│       ├── evaluasi2.blade.php
│       ├── evaluasi3.blade.php
│       ├── fund_source/
│       ├── indikatorKinerja.blade.php
│       ├── laporanKegiatan.blade.php
│       ├── login.blade.php
│       ├── ManajemenSasaranProfesi.blade.php
│       ├── material_type/
│       ├── monitoringJpl.blade.php
│       ├── pagu.blade.php
│       ├── paguDetail.blade.php
│       ├── paguImport.blade.php
│       ├── pdf/
│       ├── profession-category/
│       ├── profession/
│       ├── rank/
│       ├── role/
│       ├── settings/
│       ├── user/
│       ├── usulan/
│       ├── vendor/
│       └── workunit/
│
├── routes/
│   ├── web.php                      # Web routes (168 lines)
│   ├── api.php                      # API routes (171 lines)
│   └── console.php                  # Console routes
│
├── bootstrap/
│   └── app.php                      # App config, middleware, exceptions
│
├── config/                          # Laravel config files
├── public/                          # Web root
├── storage/                         # Logs, uploads, framework cache
├── tests/                           # PHPUnit tests
└── vite.config.js                   # Vite configuration
```

### Konvensi Penamaan

| Pola | Contoh | Keterangan |
|------|--------|------------|
| Controller domain `Act\` | `Act\ActivityController` | Semua controller terkait kegiatan/pelatihan |
| Controller domain `User\` | `User\UserController` | Semua controller terkait manajemen pengguna |
| Model domain `Act\` | `Act\Activity` | Model terkait kegiatan/pelatihan |
| Model domain `User\` | `User\User` | Model terkait pengguna |
| Form Request domain | `Requests\act\StoreActivityRequest` | Request validation per domain |
| Import class | `Imports\ActivityImport` | Excel import per entity |
| Export class | `Exports\ActivityTemplateExport` | Excel template export |
| Queue Job | `Jobs\ImportActivityJob` | Async import job |

---

## Database Schema

### Core Tables

| Tabel | Keterangan |
|-------|------------|
| `users` | Data pengguna (NIP, nama, email, role, unit kerja, jabatan, golongan, profesi) |
| `activities` | Data kegiatan pelatihan (judul, jenis, format, metode, lingkup, anggaran, PIC, tanggal, tempat) |
| `activity_types` | Master jenis kegiatan |
| `activity_categories` | Master kategori kegiatan |
| `activity_formats` | Master format kegiatan (online/offline/blended) |
| `activity_methods` | Master metode kegiatan |
| `activity_scopes` | Master lingkup kegiatan |
| `activity_names` | Master nama kegiatan (dictionary) |
| `activity_statuses` | Status pengiriman/progress kegiatan |
| `activity_participants` | Peserta per kegiatan (nilai, sertifikat) |
| `activity_speakers` | Narasumber per kegiatan |
| `activity_moderators` | Moderator per kegiatan |
| `activity_materials` | Materi per kegiatan |
| `activity_kak_files` | File KAK per kegiatan |
| `activity_professions` | Sasaran profesi per kegiatan |
| `activity_scores` | Nilai peserta per kegiatan |
| `activity_score_settings` | Pengaturan penilaian per kegiatan |
| `activity_score_components` | Komponen penilaian |
| `activity_component_scores` | Skor per komponen |
| `activity_grade_categories` | Kategori grade penilaian |
| `activity_targets` | Target per kegiatan |
| `activity_reports` | Laporan kegiatan |
| `batches` | Master angkatan/batch |
| `budgets` | Data anggaran per kegiatan |
| `budget_categories` | Master kategori anggaran |
| `fund_sources` | Master sumber dana |
| `material_types` | Master jenis materi |
| `target_participants` | Master target peserta |
| `professions` | Master profesi |
| `profession_categories` | Master kategori profesi |
| `positions` | Master jabatan |
| `work_units` | Master unit kerja |
| `employment_types` | Master jenis pegawai |
| `ranks` | Master golongan/pangkat |
| `app_settings` | Pengaturan aplikasi (logo, nama instansi, dll) |
| `sessions` | Session driver (database) |
| `jobs` | Queue jobs |
| `cache` | Cache driver (database) |
| `personal_access_tokens` | API tokens (Sanctum) |
| `permissions` | Spatie permissions |
| `roles` | Spatie roles |
| `model_has_permissions` | Spatie permission pivot |
| `model_has_roles` | Spatie role pivot |
| `role_has_permissions` | Spatie role-permission pivot |

### Relasi Utama

```
users ──┬── belongsTo work_units
        ├── belongsTo positions
        ├── belongsTo professions
        ├── belongsTo employment_types
        └── belongsTo ranks

activities ──┬── belongsTo activity_types
              ├── belongsTo activity_categories
              ├── belongsTo activity_formats
              ├── belongsTo activity_methods
              ├── belongsTo activity_scopes
              ├── belongsTo fund_sources
              ├── belongsTo budgets
              ├── belongsTo activity_names
              ├── hasMany activity_participants
              ├── hasMany activity_speakers
              ├── hasMany activity_moderators
              ├── hasMany activity_materials
              ├── hasMany activity_kak_files
              ├── hasMany activity_professions
              ├── hasMany activity_scores
              ├── hasMany activity_statuses
              ├── hasMany activity_targets
              └── hasOne activity_score_setting
```

---

## API Reference

### Authentication

| Method | URI | Keterangan |
|--------|-----|------------|
| POST | `/login` | Login |
| POST | `/logout` | Logout |

### Web Routes — Kegiatan (Pelatihan)

| Method | URI | Name | Keterangan |
|--------|-----|------|------------|
| GET | `/kegiatan` | `kegiatan.index` | Daftar kegiatan |
| GET | `/kegiatan/create` | `kegiatan.create` | Form tambah kegiatan |
| POST | `/kegiatan` | `kegiatan.store` | Simpan kegiatan baru |
| GET | `/kegiatan/{id}` | `kegiatan.show` | Detail kegiatan |
| GET | `/kegiatan/{id}/edit` | `kegiatan.edit` | Form edit kegiatan |
| PUT | `/kegiatan/{id}` | `kegiatan.update` | Update kegiatan |
| DELETE | `/kegiatan/{id}` | `kegiatan.destroy` | Hapus kegiatan |
| POST | `/kegiatan/{id}/sasaran-profesi` | `kegiatan.sasaran-profesi.store` | Tambah sasaran profesi |
| DELETE | `/kegiatan/{id}/sasaran-profesi/{id}` | `kegiatan.sasaran-profesi.destroy` | Hapus sasaran profesi |
| POST | `/kegiatan/{id}/materi` | `kegiatan.materi.store` | Tambah materi |
| DELETE | `/kegiatan/{id}/materi/{id}` | `kegiatan.materi.destroy` | Hapus materi |
| POST | `/kegiatan/{id}/narasumber` | `kegiatan.narasumber.store` | Tambah narasumber |
| DELETE | `/kegiatan/{id}/narasumber/{id}` | `kegiatan.narasumber.destroy` | Hapus narasumber |
| GET | `/kegiatan/{id}/narasumber/{speaker}/pdf` | `kegiatan.narasumber.pdf` | Download nota dinas PDF |
| GET | `/kegiatan/{id}/narasumber/{speaker}/docx` | `kegiatan.narasumber.docx` | Download nota dinas DOCX |
| POST | `/kegiatan/{id}/moderator` | `kegiatan.moderator.store` | Tambah moderator |
| DELETE | `/kegiatan/{id}/moderator/{id}` | `kegiatan.moderator.destroy` | Hapus moderator |
| POST | `/kegiatan/{id}/target` | `kegiatan.target.store` | Tambah target |
| PUT | `/kegiatan/{id}/target/{id}` | `kegiatan.target.update` | Update target |
| DELETE | `/kegiatan/{id}/target/{id}` | `kegiatan.target.destroy` | Hapus target |
| GET | `/kegiatan/{id}/peserta/tambah` | `kegiatan.peserta.create` | Form tambah peserta |
| POST | `/kegiatan/{id}/peserta` | `kegiatan.peserta.store` | Simpan peserta |
| PUT | `/kegiatan/{id}/peserta/{id}/sertifikat` | `kegiatan.peserta.update_certificate` | Update sertifikat |
| DELETE | `/kegiatan/{id}/peserta/{id}` | `kegiatan.peserta.destroy` | Hapus peserta |
| GET | `/kegiatan/{id}/peserta/available-users` | `kegiatan.peserta.available-users` | Daftar user tersedia |
| GET | `/kegiatan/peserta/template` | `kegiatan.peserta.template` | Download template peserta |
| GET | `/kegiatan/{id}/peserta/import` | `kegiatan.peserta.import.page` | Halaman import peserta |
| POST | `/kegiatan/{id}/peserta/import` | `kegiatan.peserta.import.store` | Import peserta |
| GET | `/kegiatan/{id}/input-nilai/template` | `kegiatan.input-nilai.template` | Download template nilai |
| GET | `/kegiatan/{id}/input-nilai/import` | `kegiatan.input-nilai.import.page` | Halaman import nilai |
| POST | `/kegiatan/{id}/input-nilai/import` | `kegiatan.input-nilai.import.store` | Import nilai |
| PUT | `/kegiatan/{id}/input-nilai/{participant_id}` | `kegiatan.input-nilai.update` | Update nilai peserta |
| PUT | `/kegiatan/{id}/pengaturan-penilaian` | `kegiatan.pengaturan-penilaian.update` | Update pengaturan penilaian |
| POST | `/kegiatan/{id}/submit` | `kegiatan.submit` | Kirim kegiatan |
| POST | `/kegiatan/{id}/cancel-submit` | `kegiatan.cancel_submit` | Batalkan pengiriman |
| GET | `/kegiatan/{id}/formulir-permintaan-kegiatan` | `kegiatan.pdf.formulir` | Download formulir PDF |

### Web Routes — Laporan

| Method | URI | Name | Keterangan |
|--------|-----|------|------------|
| GET | `/laporan-kegiatan` | `kegiatan.laporan.index` | Daftar laporan |
| POST | `/laporan-kegiatan` | `kegiatan.laporan.store` | Simpan laporan |
| PUT | `/laporan-kegiatan/{id}` | `kegiatan.laporan.update` | Update laporan |
| GET | `/laporan-kegiatan/template` | `kegiatan.laporan.template` | Download template |

### Web Routes — Dictionary (Master Data)

| Resource Route | Name Prefix | Keterangan |
|----------------|-------------|------------|
| `/dictionaries/activity-types` | `activity-types` | Jenis kegiatan |
| `/dictionaries/activity-categories` | `activity-categories` | Kategori kegiatan |
| `/dictionaries/activity-formats` | `activity-formats` | Format kegiatan |
| `/dictionaries/activity-methods` | `activity-methods` | Metode kegiatan |
| `/dictionaries/activity-scopes` | `activity-scopes` | Lingkup kegiatan |
| `/dictionaries/material-types` | `material-types` | Jenis materi |
| `/dictionaries/target-participants` | `target-participants` | Target peserta |
| `/dictionaries/batches` | `batches` | Angkatan/batch |
| `/dictionaries/activity-names` | `activity-names` | Nama kegiatan (+ import) |
| `/dictionaries/budget-categories` | `budget-categories` | Kategori anggaran |

### Web Routes — User Management

| Resource Route | Name Prefix | Keterangan |
|----------------|-------------|------------|
| `/users` | `users` | Manajemen pengguna (+ import) |
| `/accounts` | `accounts` | Manajemen akun (+ import) |
| `/roles` | `roles` | Manajemen role |
| `/professions` | `professions` | Master profesi |
| `/profession-categories` | `profession-categories` | Kategori profesi |
| `/work-units` | `work-units` | Unit kerja |
| `/positions` | `positions` | Jabatan |
| `/ranks` | `ranks` | Golongan/pangkat |
| `/employment-types` | `employment-types` | Jenis pegawai |
| `/fund-sources` | `fund-sources` | Sumber dana |
| `/pagu` | `pagu` | Pagu anggaran (+ import) |

### Web Routes — Lainnya

| Method | URI | Name | Keterangan |
|--------|-----|------|------------|
| GET | `/` | - | Redirect ke dashboard |
| GET | `/dashboard` | `dashboard` | Halaman dashboard |
| GET | `/dashboard/activities` | `dashboard.activities` | Data kegiatan per tanggal (JSON) |
| GET | `/usulan-diklat` | `usulan-diklat` | Daftar usulan diklat |
| GET | `/usulan-diklat/{id}` | `usulan-diklat.show` | Detail usulan |
| GET | `/monitoring-jpl` | `monitoring.jpl.index` | Monitoring JPL |
| GET | `/indikator-kinerja` | `indikator-kinerja.index` | Indikator kinerja |
| GET | `/settings` | `settings.index` | Pengaturan aplikasi |
| PUT | `/settings` | `settings.update` | Update pengaturan |
| DELETE | `/settings/logo` | `settings.delete-logo` | Hapus logo |
| DELETE | `/settings/login-image` | `settings.delete-login-image` | Hapus gambar login |
| DELETE | `/settings/kemenkes-logo` | `settings.delete-kemenkes-logo` | Hapus logo kemenkes |

### API Routes (prefix: `/api`)

Semua API route menggunakan RESTful convention dengan full CRUD (index, store, show, update, destroy).

| Endpoint | Controller | Keterangan |
|----------|------------|------------|
| `/api/activities` | `Act\ActivityController` | Data kegiatan |
| `/api/activity-types` | `Act\ActivityTypeController` | Jenis kegiatan |
| `/api/activity-scopes` | `Act\ActivityScopeController` | Lingkup kegiatan |
| `/api/activity-methods` | `Act\ActivityMethodController` | Metode kegiatan |
| `/api/activity-formats` | `Act\ActivityFormatController` | Format kegiatan |
| `/api/activity-materials` | `Act\ActivityMaterialController` | Materi kegiatan |
| `/api/activity-speakers` | `Act\ActivitySpeakerController` | Narasumber |
| `/api/activity-moderators` | `Act\ActivityModeratorController` | Moderator |
| `/api/activity-participants` | `Act\ActivityParticipantController` | Peserta |
| `/api/activity-professions` | `Act\ActivityProfessionController` | Sasaran profesi |
| `/api/activity-kak-files` | `Act\ActivityKakFileController` | File KAK |
| `/api/activity-scores` | `Act\ActivityScoreController` | Nilai peserta |
| `/api/activity-statuses` | `Act\ActivityStatusController` | Status kegiatan |
| `/api/batches` | `Act\BatchController` | Angkatan/batch |
| `/api/budget-categories` | `BudgetCategoryController` | Kategori anggaran |
| `/api/budgets` | `BudgetController` | Data anggaran |
| `/api/employment-types` | `User\EmploymentTypeController` | Jenis pegawai |
| `/api/material-types` | `Act\MaterialTypeController` | Jenis materi |
| `/api/target-participants` | `Act\TargetParticipantController` | Target peserta |
| `/api/users` | `User\UserController` | Data pengguna |
| `/api/professions` | `User\ProfessionController` | Profesi |
| `/api/positions` | `User\PositionsController` | Jabatan |
| `/api/ranks` | `User\RankController` | Golongan/pangkat |
| `/api/workunits` | `User\WorkUnitController` | Unit kerja |

---

## Setup & Instalasi

### Prasyarat

- PHP >= 8.2
- MySQL
- Node.js & npm/bun
- Composer

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/mhilmanrz/sipelatih.git
cd sipelatih

# 2. Install PHP dependencies
composer install

# 3. Install frontend dependencies
npm install
# atau
bun install

# 4. Salin environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Konfigurasi database di .env
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=sipelatih
#    DB_USERNAME=root
#    DB_PASSWORD=your_password

# 7. Jalankan migrasi database
php artisan migrate

# 8. (Opsional) Jalankan seeder untuk data awal
php artisan db:seed

# 9. Build frontend assets
npm run build
```

### Quick Setup (Otomatis)

```bash
composer run setup
```

Perintah ini akan menjalankan: `composer install`, copy `.env`, generate key, migrate, `npm install`, dan `npm run build`.

---

## Menjalankan Aplikasi

### Development

```bash
# Jalankan semua service sekaligus (server, queue, vite)
composer run dev
```

Atau jalankan secara terpisah:

```bash
# Backend server
php artisan serve

# Queue worker
php artisan queue:listen --tries=1 --timeout=0

# Frontend hot reload
npm run dev
```

### Production

```bash
# Build optimized assets
npm run build

# Jalankan queue worker (gunakan supervisor/process manager)
php artisan queue:work
```

---

## Testing

```bash
# Jalankan semua test
php artisan test

# Atau dengan compact output
php artisan test --compact

# Jalankan test spesifik
php artisan test --filter=TestName

# Jalankan test file tertentu
php artisan test tests/Feature/ExampleTest.php

# Format kode sebelum commit
vendor/bin/pint --dirty
```

---

## Library & Dependencies

### Backend (Composer)

| Package | Versi | Keterangan |
|---------|-------|------------|
| `laravel/framework` | ^13.0 | Core framework |
| `laravel/sanctum` | ^4.0 | API token authentication |
| `laravel/tinker` | ^3.0 | REPL interaktif |
| `laravel/ai` | ^0.4.4 | Laravel AI SDK |
| `barryvdh/laravel-dompdf` | ^3.1 | PDF generation |
| `maatwebsite/excel` | ^4.0@dev | Excel import/export |
| `phpoffice/phpword` | * | DOCX generation |
| `spatie/laravel-permission` | ^7.2 | Role & permission management |

### Dev (Composer)

| Package | Versi | Keterangan |
|---------|-------|------------|
| `laravel/boost` | ^2.4 | AI-powered development tools |
| `laravel/pail` | ^1.2 | Log viewer |
| `laravel/pint` | ^1.24 | Code style fixer |
| `laravel/sail` | ^1.41 | Docker dev environment |
| `phpunit/phpunit` | ^12.0 | Testing framework |

### Frontend (npm)

| Package | Versi | Keterangan |
|---------|-------|------------|
| `tailwindcss` | ^4.1 | Utility-first CSS framework |
| `@tailwindcss/vite` | ^4.1 | Tailwind Vite plugin |
| `alpinejs` | ^3.15 | Lightweight JS framework |
| `axios` | ^1.11 | HTTP client |
| `laravel-vite-plugin` | ^2.0 | Laravel Vite integration |
| `vite` | ^7.0 | Asset bundler |
| `concurrently` | ^9.0 | Run multiple commands |