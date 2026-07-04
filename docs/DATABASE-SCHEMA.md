# Database Schema

MySQL 8.x (kompatibel MariaDB 10.x). Semua tabel menggunakan storage engine **InnoDB** dan charset/collation **utf8mb4** (`utf8mb4_unicode_ci`). Primary key seluruh tabel domain menggunakan `id BIGINT UNSIGNED AUTO_INCREMENT`, kecuali tabel notifikasi standar Laravel yang menggunakan UUID sesuai konvensi bawaan framework.

> **Catatan migrasi:** Desain awal `classes` sempat memiliki kolom `participant_id` yang salah (tidak bisa merepresentasikan relasi many-to-many). Ini **sudah diperbaiki** di migration aktual: `classes` kini hanya berisi `owner_id` + `join_code`, dan relasi many-to-many direalisasikan lewat migration terpisah `2026_07_03_044703_create_class_participants_table.php`. Tabel `notifications` juga sudah dibuat di `2026_07_03_044734_create_notifications_table.php`.

---

## `users`

| Kolom | Tipe | Catatan |
|---|---|---|
| id | BIGINT UNSIGNED, PK, AUTO_INCREMENT | |
| fullname | VARCHAR(255) | Nama lengkap user |
| email | VARCHAR(255) UNIQUE | |
| email_verified_at | TIMESTAMP NULL | |
| password | VARCHAR(255) | |
| role | ENUM('lecturer','student') | DEFAULT `student` |
| remember_token | VARCHAR(100) NULL | |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

## `password_reset_tokens` (standar Laravel)

| Kolom | Tipe | Catatan |
|---|---|---|
| email | VARCHAR(255), PK | |
| token | VARCHAR(255) | |
| created_at | TIMESTAMP NULL | |

## `sessions` (standar Laravel)

| Kolom | Tipe | Catatan |
|---|---|---|
| id | VARCHAR(255), PK | |
| user_id | BIGINT UNSIGNED NULL | FK → `users.id`, indexed |
| ip_address | VARCHAR(45) NULL | |
| user_agent | TEXT NULL | |
| payload | LONGTEXT | |
| last_activity | INT | Indexed |

## `classes`

| Kolom | Tipe | Catatan |
|---|---|---|
| id | BIGINT UNSIGNED, PK, AUTO_INCREMENT | |
| owner_id | BIGINT UNSIGNED | FK → `users.id` (dosen pemilik kelas) |
| name | VARCHAR(255) | |
| description | TEXT NULL | |
| schedule_day | JSON NULL | Format `{"monday":"08:00-10:00"}`, hanya hari aktif |
| join_code | VARCHAR(255) UNIQUE | Kode unik untuk share link join kelas |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

`participant_id` **dihapus** dari desain ini — digantikan tabel pivot `class_participants` di bawah.

## `class_participants` (pivot, baru)

| Kolom | Tipe | Catatan |
|---|---|---|
| id | BIGINT UNSIGNED, PK, AUTO_INCREMENT | |
| class_id | BIGINT UNSIGNED | FK → `classes.id` |
| user_id | BIGINT UNSIGNED | FK → `users.id` (mahasiswa) |
| joined_at | TIMESTAMP | DEFAULT `CURRENT_TIMESTAMP` |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

**Constraint:** `UNIQUE (class_id, user_id)` — mencegah mahasiswa join kelas yang sama dua kali.

## `tasks`

| Kolom | Tipe | Catatan |
|---|---|---|
| id | BIGINT UNSIGNED, PK, AUTO_INCREMENT | |
| class_id | BIGINT UNSIGNED | FK → `classes.id` |
| title | VARCHAR(255) | |
| description | TEXT NULL | |
| status | ENUM('Active','Expired') | DEFAULT `Active`, dihitung ulang otomatis dari `deadline_at` |
| deadline_at | TIMESTAMP | |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

## `announcements`

| Kolom | Tipe | Catatan |
|---|---|---|
| id | BIGINT UNSIGNED, PK, AUTO_INCREMENT | |
| class_id | BIGINT UNSIGNED | FK → `classes.id` |
| title | VARCHAR(255) | |
| description | TEXT NULL | |
| expired_at | DATE NULL | Pengumuman berhenti tampil setelah tanggal ini |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

## `notifications` (standar Laravel)

Mengikuti skema bawaan `Illuminate\Notifications\DatabaseNotification`:

| Kolom | Tipe | Catatan |
|---|---|---|
| id | CHAR(36), PK | UUID |
| type | VARCHAR(255) | Nama class Notification |
| notifiable_type | VARCHAR(255) | Polymorphic — biasanya `App\Models\User` |
| notifiable_id | BIGINT UNSIGNED | |
| data | TEXT | Payload JSON notifikasi |
| read_at | TIMESTAMP NULL | |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

---

## Relasi antar tabel

```
users (1) ───< classes (owner_id)                    -- satu dosen banyak kelas
users (M) ───< class_participants >─── (M) classes    -- many-to-many via pivot
classes (1) ───< tasks
classes (1) ───< announcements
users (1) ───< notifications (notifiable)
```

## Catatan umum
- Semua foreign key menggunakan `onDelete('cascade')` untuk data anak yang tidak punya makna historis (`class_participants`, `tasks`, `announcements`) mengikuti kelas induknya — sesuai migration aktual (`->constrained(...)->onDelete('cascade')`).
- Semua tabel wajib InnoDB + utf8mb4 agar mendukung foreign key constraint dan emoji/karakter khusus di deskripsi.
- Kolom `schedule_day` divalidasi di level aplikasi (bukan CHECK constraint MySQL) agar formatnya konsisten `{"day": "HH:MM-HH:MM"}`.

## Pemetaan ke file migration

| Tabel | File migration |
|---|---|
| `users`, `password_reset_tokens`, `sessions` | `0001_01_01_000000_create_users_table.php` |
| `cache`, `cache_locks` | `0001_01_01_000001_create_cache_table.php` |
| `classes` | `2026_06_29_125846_create_classes_table.php` |
| `tasks` | `2026_06_29_125847_create_tasks_table.php` |
| `announcements` | `2026_06_29_125848_create_announcements_table.php` |
| `class_participants` | `2026_07_03_044703_create_class_participants_table.php` |
| `notifications` | `2026_07_03_044734_create_notifications_table.php` |