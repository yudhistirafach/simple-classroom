# Status Proyek: Aplikasi Jadwal Kuliah & Reminder
Fokus: Manajemen Tugas — CRUD Tugas untuk Dosen

## Sedang dikerjakan
**Fase 6: Manajemen Tugas** — Implementasi CRUD tugas untuk dosen. Tugas hanya berupa informasi (judul, deskripsi, deadline) tanpa fitur submit/upload jawaban dari mahasiswa.

---

## Selesai

**Fase 1: Migrasi Database** ✅
- [x] Migration `users` (`fullname`, `role` enum, `email_verified_at`, `remember_token`, `timestamps`) + `password_reset_tokens` + `sessions`.
- [x] Migration `classes` (`owner_id`, `name`, `description`, `schedule_day` JSON, `join_code` unique, `timestamps`) — tanpa kolom `participant_id`.
- [x] Migration `tasks` (`class_id`, `title`, `description`, `status` enum default `Active`, `deadline_at`, `timestamps`).
- [x] Migration `announcements` (`class_id`, `title`, `description`, `expired_at`, `timestamps`).
- [x] Migration `class_participants` — pivot `class_id` + `user_id` + `joined_at`, unique constraint `(class_id, user_id)`.
- [x] Migration `notifications` — skema standar Laravel (UUID, morphs `notifiable`, `data`, `read_at`).

**Fase 2: Model & Relasi Eloquent** ✅
- [x] Model `User` dengan relasi `ownedClasses()`, `joinedClasses()`, method helper `isLecturer()` & `isStudent()`.
- [x] Model `Classroom` dengan relasi `owner()`, `participants()`, `tasks()`, `announcements()`.
- [x] Model `Task` dengan relasi `class()`, scope `active()`, method `updateStatus()`.
- [x] Model `Announcement` dengan relasi `class()`, method `isActive()`.
- [x] Model `ClassParticipant` (Pivot) untuk mengelola relasi many-to-many.

**Fase 3: Seeder Data Dummy** ✅
- [ ] Seeder akun dosen contoh (`lecturer@example.com` / `password`).
- [ ] Seeder akun mahasiswa contoh (`student@example.com` / `password`).
- [ ] Seeder 2 kelas contoh milik dosen dengan `join_code` dummy (`ABC123`, `DEF456`).
- [ ] Seeder mahasiswa terdaftar sebagai peserta di kelas-kelas contoh.

**Fase 4: Layout & Navigasi Dasar** ✅
- [x] `layouts/main.blade.php` dengan sidebar + topbar.
- [x] `layouts/auth.blade.php` untuk halaman login/register.
- [x] `components/sidebar.blade.php` dengan role-based menu.
- [x] `components/topbar.blade.php` dengan user dropdown & notification bell.
- [x] `components/class-card.blade.php` reusable.
- [x] `components/toast.blade.php` untuk alert notifikasi.
- [x] Custom CSS (`main.css`) dengan tema Google Classroom via Vite.
- [x] `routes/web.php` diperbarui dengan auth manual & dashboard.
- [x] `DashboardController.php` dengan redirect berdasarkan role.
- [x] Dashboard dosen & mahasiswa (placeholder).

**Fase 5: Manajemen Kelas (CRUD + Join)** ✅
- [ ] Route `GET /classes` → `ClassController@index` — daftar kelas (role-based view).
- [ ] Route `GET /classes/create` → `ClassController@create` — form buat kelas (hanya dosen).
- [ ] Route `POST /classes` → `ClassController@store` — simpan kelas baru dengan `join_code` otomatis.
- [ ] Route `GET /classes/{class}` → `ClassController@show` — detail kelas (role-based view).
- [ ] Route `GET /classes/{class}/edit` → `ClassController@edit` — form edit kelas (hanya owner).
- [ ] Route `PUT /classes/{class}` → `ClassController@update` — update kelas.
- [ ] Route `DELETE /classes/{class}` → `ClassController@destroy` — hapus kelas (dengan cascade).
- [ ] Route `POST /classes/join` → `ClassController@join` — join kelas via kode (mahasiswa).
- [ ] Route `DELETE /classes/{class}/leave` → `ClassController@leave` — keluar dari kelas (mahasiswa).
- [ ] `ClassController` dengan semua method CRUD + join/leave.
- [ ] `ClassPolicy` untuk otorisasi (BR-AUTH-01, BR-AUTH-03).
- [ ] View `lecturer/classes/index.blade.php` — daftar kelas milik dosen.
- [ ] View `lecturer/classes/create.blade.php` — form buat kelas.
- [ ] View `lecturer/classes/show.blade.php` — detail kelas + tab stream/tugas/pengumuman/anggota.
- [ ] View `lecturer/classes/edit.blade.php` — form edit kelas.
- [ ] View `student/classes/index.blade.php` — daftar kelas yang diikuti mahasiswa.
- [ ] View `student/classes/show.blade.php` — detail kelas (student view).
- [ ] View `components/class-card.blade.php` digunakan di index dosen & mahasiswa.
- [ ] Validasi `schedule_day` JSON di `ClassRequest`.
- [ ] Notifikasi sukses/error via `components/toast.blade.php`.

---

## Belum selesai

**Fase 6: Manajemen Tugas (CRUD untuk Dosen)**

### 6.1 Route & Controller
- [ ] Tambahkan route resource/nested untuk tugas di `routes/web.php`:
  - `GET /classes/{class}/tasks` → `TaskController@index` (tampil dalam tab Tugas di detail kelas).
  - `GET /classes/{class}/tasks/create` → `TaskController@create` (form buat tugas).
  - `POST /classes/{class}/tasks` → `TaskController@store` (simpan tugas).
  - `GET /tasks/{task}/edit` → `TaskController@edit` (form edit tugas).
  - `PUT /tasks/{task}` → `TaskController@update` (update tugas).
  - `DELETE /tasks/{task}` → `TaskController@destroy` (hapus tugas).
- [ ] Buat `TaskController` dengan method: `index`, `create`, `store`, `edit`, `update`, `destroy`.

### 6.2 Policy & Otorisasi
- [ ] Buat `TaskPolicy` dengan method:
  - `viewAny` → dosen pemilik kelas, mahasiswa peserta kelas.
  - `view` → dosen pemilik kelas, mahasiswa peserta kelas.
  - `create` → hanya `role = lecturer` dan pemilik kelas (BR-AUTH-01, BR-AUTH-03).
  - `update` → hanya `role = lecturer` dan pemilik kelas.
  - `delete` → hanya `role = lecturer` dan pemilik kelas.
- [ ] Daftarkan `TaskPolicy` di `AuthServiceProvider`.

### 6.3 Form Request
- [ ] Buat `TaskRequest` dengan aturan validasi:
  - `class_id` → required, exists di `classes`, dan user adalah owner kelas.
  - `title` → required, string, max 255.
  - `description` → nullable, string.
  - `deadline_at` → required, date, after:now (minimal hari ini).
  - `status` → tidak perlu diinput (otomatis dihitung, BR-LMS-05).

### 6.4 Views untuk Dosen (Lecturer)
- [ ] `lecturer/classes/show.blade.php` — tambahkan tab "Tugas" dengan daftar tugas.
- [ ] `lecturer/tasks/create.blade.php` — form buat tugas (dalam konteks kelas tertentu).
- [ ] `lecturer/tasks/edit.blade.php` — form edit tugas.
- [ ] `lecturer/tasks/index.blade.php` — daftar tugas (opsional, bisa ditampilkan di tab).

### 6.5 Logic Status Tugas (BR-LMS-05)
- [ ] Implementasikan accessor `getStatusAttribute()` di model `Task`:
  - Jika `deadline_at > now()` → `Active`.
  - Jika `deadline_at <= now()` → `Expired`.
- [ ] Opsional: buat scheduled command `UpdateExpiredTasks` untuk menyinkronkan kolom `status` secara periodik (agar filter/query cepat).

### 6.6 Tampilan Mahasiswa (Student View)
- [ ] `student/classes/show.blade.php` — tambahkan tab "Tugas" dengan daftar tugas (read-only).
- [ ] Mahasiswa hanya bisa melihat tugas, tidak ada tombol aksi (BR-LMS-06).

---

## Detail Sub-tugas Manajemen Tugas

| No | Sub-tugas | Status | Catatan |
|---|---|---|---|
| 1 | Tambahkan route tugas (nested) di `web.php` | [ ] | Route di bawah middleware auth |
| 2 | Buat `TaskController` dengan method CRUD | [ ] | Gunakan dependency injection |
| 3 | Buat `TaskPolicy` untuk otorisasi | [ ] | Registrasi di AuthServiceProvider |
| 4 | Buat `TaskRequest` untuk validasi | [ ] | Custom validation untuk class ownership |
| 5 | Modifikasi `lecturer/classes/show.blade.php` | [ ] | Tambahkan tab Tugas dengan daftar |
| 6 | Buat `lecturer/tasks/create.blade.php` | [ ] | Form dengan hidden class_id |
| 7 | Buat `lecturer/tasks/edit.blade.php` | [ ] | Form edit tugas |
| 8 | Buat `lecturer/tasks/index.blade.php` | [ ] | Partial daftar tugas (bisa di-include) |
| 9 | Implementasi accessor status di model `Task` | [ ] | Auto `Active` / `Expired` |
| 10 | Modifikasi `student/classes/show.blade.php` | [ ] | Tambahkan tab Tugas (read-only) |
| 11 | Buat partial `components/task-card.blade.php` | [ ] | Reusable untuk daftar tugas |
| 12 | Tambahkan notifikasi sukses di controller | [ ] | Toast setelah create/update/delete |
| 13 | Test alur CRUD tugas | [ ] | Pastikan hanya owner yang bisa akses |

---

## Konteks penting — Aturan yang wajib diikuti

### Business Rules (lihat BUSINESS-RULES.md)
- **BR-LMS-05**: Status tugas dihitung otomatis berdasarkan `deadline_at`:
  - `Active` jika `deadline_at > NOW()`.
  - `Expired` jika `deadline_at <= NOW()`.
  - Implementasi via accessor di model `Task`, bukan input manual.
- **BR-LMS-06**: Tugas hanya berupa informasi (judul, deskripsi, deadline) — **TIDAK ADA** fitur submit/upload jawaban.
- **BR-LMS-09**: Notifikasi tugas baru akan dikirim di fase terpisah (setelah CRUD selesai).
- **BR-AUTH-01**: Hanya dosen yang boleh membuat, mengedit, menghapus tugas.
- **BR-AUTH-03**: Dosen hanya boleh mengelola tugas di kelas miliknya sendiri.

### Database & Model
- **Tabel `tasks`**: Kolom `status` ada di database (ENUM), tetapi akan di-sync otomatis melalui accessor atau scheduled command.
- **Relasi**: `Task` belongs to `Classroom` → gunakan `$task->class` untuk akses kelas terkait.
- **Delete cascade**: Saat kelas dihapus, semua tugas ikut terhapus (sesuai migration).

### Desain & UI
- **Tab Tugas**: Di halaman detail kelas, tab "Tugas" menampilkan daftar tugas dengan informasi:
  - Judul tugas (link ke detail/editable).
  - Deskripsi (singkat).
  - Deadline (format tanggal + waktu).
  - Status badge (`Active` atau `Expired`).
  - Tombol aksi (Edit, Hapus) hanya untuk dosen pemilik kelas.
- **Form Tugas**: Di dalam konteks kelas — dosen tidak perlu memilih kelas, `class_id` dikirim via hidden atau URL parameter.

### Otorisasi (Policy)
- **BR-AUTH-01 + BR-AUTH-03**: Implementasikan di `TaskPolicy`:
  - `create`: cek `$user->isLecturer()` dan `$user->id === $class->owner_id`.
  - `update`/`delete`: cek `$user->isLecturer()` dan `$user->id === $task->class->owner_id`.
  - `view`: dosen owner boleh lihat semua, mahasiswa hanya lihat jika terdaftar di `class_participants`.

### Notifikasi (akan diimplementasikan di fase berikutnya)
- **BR-LMS-09**: Notifikasi tugas baru akan dikirim setelah CRUD selesai — ini akan menjadi fase terpisah setelah CRUD stabil.
- **BR-LMS-10**: Notifikasi H-1 deadline juga akan diimplementasikan terpisah (scheduled command).

### Tech Stack (lihat TECH-STACK.md)
- Laravel 12 + PHP 8.2 + MySQL 8.x.
- Blade + Bootstrap 5.3 (tanpa React/Inertia/Vue).
- **Vite** untuk asset bundling.
- Queue driver `sync`.

---

## Dependensi antar tugas
Fase 6: CRUD Tugas
├── Fase 5: Manajemen Kelas (✅ selesai) — kelas sudah tersedia
├── Fase 4: Layout & Navigasi (✅ selesai) — layout siap
└── Fase 2: Model & Relasi (✅ selesai) — model Task sudah ada

Setelah Fase 6 selesai:
├── Fase 6b: Notifikasi Tugas Baru (BR-LMS-09)
├── Fase 6c: Notifikasi H-1 Deadline (BR-LMS-10)
└── Fase 7: Manajemen Pengumuman (CRUD + notifikasi)


---

## Catatan Implementasi

### Nested Route vs Resource Route
Gunakan nested route agar tugas selalu dalam konteks kelas tertentu:
```php
Route::prefix('classes/{class}')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
});

Route::prefix('tasks')->group(function () {
    Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

---

// app/Models/Task.php
public function getStatusAttribute($value): string
{
    if ($this->deadline_at->isPast()) {
        return 'Expired';
    }
    return 'Active';
}

Validasi Deadline
deadline_at harus after:now atau after:today agar tidak bisa membuat tugas dengan deadline masa lalu.

Terakhir diperbarui: 2026-07-03 — Fokus pada CRUD Tugas untuk dosen sesuai MODULES.md