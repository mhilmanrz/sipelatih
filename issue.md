# Feature: Sistem Evaluasi Kegiatan Bertingkat (3 Level)

## Konteks

Membangun sistem evaluasi kegiatan bertingkat (Evaluasi 1, 2, 3) untuk kegiatan yang sudah selesai (status `accepted`). Setiap tingkatan memiliki kriteria yang bisa di-customize. Kegiatan harus lulus evaluasi tingkat sebelumnya agar muncul di daftar tingkat berikutnya. Menggantikan halaman statis `evaluasi1.blade.php`, `evaluasi2.blade.php`, `evaluasi3.blade.php`.

## Keputusan Desain

- Kegiatan yang bisa dievaluasi = yang `latestStatus` bernilai `accepted`
- Evaluasi yang sudah di-submit bisa diedit kembali
- Semua user dengan permission `create evaluasi` bisa mengevaluasi
- Download laporan PDF tetap diperlukan (terpisah dari fitur evaluasi ini, sudah ada di menu Laporan)

---

## Data Model

### Tabel `evaluation_criteria` (Master Kriteria)

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint unsigned PK | |
| code | varchar(50) | Kode unik, misal `E1-01` |
| name | varchar(255) | Nama kriteria |
| is_fillable | boolean, default false | Jika true, tampilkan input di form |
| type | enum('string','number') | Tipe input (relevan jika is_fillable = true) |
| evaluation_type | tinyint unsigned | 1, 2, atau 3 |
| order | smallint unsigned | Urutan tampil |
| timestamps | | |

### Tabel `activity_evaluations`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint unsigned PK | |
| activity_id | bigint unsigned FK | FK ke activities |
| evaluation_type | tinyint unsigned | 1, 2, atau 3 |
| is_passed | boolean, default false | Manual check oleh user |
| notes | text, nullable | Catatan evaluator |
| evaluated_by | bigint unsigned FK, nullable | FK ke users |
| evaluated_at | timestamp, nullable | |
| timestamps | | |

**Unique constraint**: `(activity_id, evaluation_type)`

### Tabel `activity_evaluation_criteria`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint unsigned PK | |
| activity_evaluation_id | bigint unsigned FK | |
| evaluation_criteria_id | bigint unsigned FK | |
| value | text, nullable | Nilai input (jika is_fillable) |
| is_passed | boolean, default false | Manual check per kriteria |
| timestamps | | |

---

## Scope of Work

### 1. Migration & Model

- Buat 3 migration sesuai tabel di atas.
- Buat 3 model di `App\Models\Act`: `EvaluationCriteria`, `ActivityEvaluation`, `ActivityEvaluationCriteria` dengan relasi yang sesuai.
- Tambahkan relasi `evaluations()` di model `Activity`.
- Buat factory untuk `EvaluationCriteria`.
- Buat seeder `EvaluationCriteriaSeeder` dengan contoh kriteria per tingkat (buat 3-5 kriteria per level, beberapa dengan `is_fillable = true`).

### 2. CRUD Kriteria Evaluasi (Master Data)

- Buat `EvaluationCriteriaController` di `App\Http\Controllers\Act` — resource controller lengkap (index, create, store, edit, update, destroy).
- Buat `StoreEvaluationCriteriaRequest` dan `UpdateEvaluationCriteriaRequest`.
- Buat views di `resources/views/evaluation_criteria/` (index, create, edit) — ikuti pola yang sama dengan `budget_categories` views.
- Halaman index harus bisa filter berdasarkan `evaluation_type` (1/2/3).
- Daftarkan resource route: `Route::resource('evaluation-criteria', EvaluationCriteriaController::class)`.

### 3. Halaman Evaluasi Kegiatan

- Buat `ActivityEvaluationController` di `App\Http\Controllers\Act` dengan method:
  - `index` — halaman utama evaluasi
  - `show($activityId, $type)` — detail evaluasi per kegiatan
  - `store($activityId, $type)` — simpan/update evaluasi

- **Halaman Index** (`resources/views/evaluations/index.blade.php`):
  - Filter tahun (dropdown dari `activity_names.year`)
  - Stepper/tab horizontal: Evaluasi 1, Evaluasi 2, Evaluasi 3
  - Setiap tab menampilkan tabel kegiatan yang relevan:
    - Tab 1: kegiatan dengan `latestStatus` = `accepted`
    - Tab 2: kegiatan yang `is_passed = true` di evaluasi tipe 1
    - Tab 3: kegiatan yang `is_passed = true` di evaluasi tipe 2
  - Kolom tabel: No, Nama Kegiatan, Tanggal, Jumlah Peserta, Status Evaluasi (badge), Aksi
  - Pagination

- **Halaman Detail** (`resources/views/evaluations/show.blade.php`):
  - Info kegiatan (nama, tanggal, tempat, tipe, kategori, dll)
  - Statistik cards: jumlah peserta, total JPL (`activity_materials.value` sum), jumlah materi
  - Form evaluasi:
    - List kriteria sesuai `evaluation_type`
    - Input field jika `is_fillable = true` (text/number sesuai `type`)
    - Checkbox `is_passed` per kriteria
    - Textarea catatan
    - Toggle utama "Lulus Evaluasi"
    - Tombol Simpan

- Routes:
  ```
  GET  /evaluasi                       → index   (evaluasi.index)
  GET  /evaluasi/{activity}/{type}     → show    (evaluasi.show)
  POST /evaluasi/{activity}/{type}     → store   (evaluasi.store)
  ```

### 4. Sidebar & Navigasi

- Update sidebar: ubah link Evaluasi ke `route('evaluasi.index')`, active state `request()->is('evaluasi*')`
- Tambah menu "Kriteria Evaluasi" di group Master Data dengan permission `view evaluation criteria`
- Hapus route lama `Route::view('/evaluasi1', 'evaluasi1')`
- Hapus file lama: `evaluasi1.blade.php`, `evaluasi2.blade.php`, `evaluasi3.blade.php`

### 5. Permissions

Tambahkan di `RolePermissionSeeder`:
- `view evaluation criteria`, `create evaluation criteria`, `edit evaluation criteria`, `delete evaluation criteria`
- `view evaluasi`, `create evaluasi`

Assign semua ke role `superadmin`.

### 6. Testing

- Feature test CRUD `EvaluationCriteria`
- Feature test evaluasi: kegiatan accepted muncul di tab 1, lulus tab 1 muncul di tab 2, lulus tab 2 muncul di tab 3, simpan evaluasi dengan kriteria, filter tahun

### 7. Pint

Jalankan `vendor/bin/pint --dirty --format agent` setelah semua PHP file selesai.

---

## Referensi Pola Kode

- Controller CRUD: lihat `App\Http\Controllers\Act\BudgetCategoryController`
- Views CRUD: lihat `resources/views/budget_categories/`
- Model dengan relasi: lihat `App\Models\Act\Activity`
- Sidebar menu item: lihat `resources/views/components/layouts/sidebar.blade.php`
- Permission check: gunakan `@can()` di Blade, pattern sudah ada di sidebar