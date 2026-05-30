# Redesign Sistem Evaluasi Kegiatan — 3 Level Independen

## Latar Belakang

Sistem evaluasi saat ini dirancang sebagai evaluasi bertingkat (harus selesai level 1 dulu untuk lanjut ke level 2, dst) dan form diisi oleh admin/evaluator. Desain ini perlu diubah total menjadi:

- Level 1, 2, dan 3 adalah **kategori independen**, bukan hierarki bertingkat
- **Tidak ada status lulus/gagal** per evaluasi
- Form diisi oleh **peserta sendiri**, bukan admin
- Admin tetap bisa mengisi form mewakili peserta

---

## Spesifikasi Per Level

### Level 1 — Evaluasi Penyelenggaraan
Semua kegiatan berstatus *accepted* otomatis masuk ke Level 1. Peserta mengisi dua jenis form:

1. **Form Evaluasi Narasumber** — setiap peserta mengisi satu form per narasumber (semua narasumber wajib dinilai). Jawaban berupa rating bintang 1–4 (tidak memuaskan → sangat memuaskan). Form dikelompokkan dalam kategori dan kriteria.
2. **Form Evaluasi Kegiatan** — setiap peserta mengisi satu kali per kegiatan. Jawaban berupa rating bintang 1–4. Form dikelompokkan dalam kategori dan kriteria.

### Level 2 — Evaluasi Hasil Belajar
Semua kegiatan berstatus *accepted* otomatis masuk ke Level 2. **Tidak ada form baru.** Hanya menampilkan data nilai pre-test dan post-test yang sudah ada di sistem beserta status kelulusan per peserta.

### Level 3 — Evaluasi Dampak
Hanya kegiatan yang **dipilih manual oleh admin** yang masuk ke Level 3. Peserta mengisi satu form untuk dirinya sendiri, berisi:
- Kriteria dengan kategori, jawaban rating 1–4 (tidak setuju → sangat setuju)
- Upload file data dukung
- Field teks: Rekomendasi/Saran Atasan Langsung (diisi oleh peserta sendiri)

---

## Mode Akses Form Peserta
Tiga mode akses untuk peserta mengisi form:
1. **Login sistem** — peserta login ke aplikasi dan mengakses daftar form yang perlu diisi
2. **Public link (token)** — setiap form punya token unik; peserta bisa isi via link tanpa login
3. **Admin isi mewakili** — admin bisa membuka dan mengisi form atas nama peserta tertentu

---

## Yang Perlu Diimplementasikan

### 1. Database — Tabel Baru
- `evaluation_categories` — mengelompokkan kriteria per level dan jenis form (speaker/activity)
- `participant_evaluations` — record evaluasi per peserta, menyimpan token unik untuk public link
- `participant_evaluation_answers` — jawaban rating per kriteria
- `participant_evaluation_files` — file upload untuk data dukung Level 3

### 2. Database — Modifikasi Tabel Existing
- `evaluation_criteria` — tambah kolom `evaluation_category_id` (FK) dan `form_type` (speaker/activity/null)

### 3. Fitur Admin
- **Generate form evaluasi** — admin men-generate `participant_evaluations` untuk semua peserta suatu kegiatan. Level 1 otomatis saat kegiatan accepted, Level 3 dipilih manual
- **Enable/disable Level 3** — toggle untuk menentukan kegiatan mana yang masuk ke evaluasi Level 3
- **Isi form mewakili peserta** — admin bisa membuka dan submit form evaluasi atas nama peserta
- **Halaman evaluasi (revamp)** — tampilkan progress pengisian per kegiatan: Level 1 (berapa peserta submit), Level 2 (tabel nilai), Level 3 (progress + files + rekomendasi)
- **Manajemen kategori evaluasi** — CRUD untuk `evaluation_categories`
- **Update manajemen kriteria** — form add/edit kriteria mendukung kategori dan `form_type`

### 4. Fitur Peserta (Login)
- Halaman daftar form evaluasi yang perlu diisi (pending)
- Halaman form evaluasi dengan tampilan rating bintang, dikelompokkan per kategori
- Submit form dan tandai `submitted_at`

### 5. Fitur Public (Token)
- Route publik tanpa auth: `GET /e/{token}` dan `POST /e/{token}`
- Tampilan form evaluasi tanpa layout login
- Setelah submit, tandai `submitted_at` dan catat `ip_address`

---

## Catatan Teknis
- Tabel `activity_evaluations` dan `activity_evaluation_criteria` yang lama tidak dipakai di sistem baru (legacy), jangan dihapus dulu
- Token publik dibuat saat generate form, format string random 64 karakter
- Narasumber diambil dari relasi `activity_materials → activity_speakers → user`
- Untuk Level 3, field "Rekomendasi/Saran Atasan" adalah text field biasa yang diisi peserta sendiri
