# Business Rules

Aturan-aturan ini adalah logika bisnis inti sistem **Aplikasi Jadwal Kuliah & Reminder**. Implementasi kode harus selalu mematuhi aturan ini, bahkan jika tidak disebutkan eksplisit dalam task.

---

## Otorisasi

- **BR-AUTH-01**: Hanya user dengan `role = lecturer` yang boleh membuat, mengedit, atau menghapus **kelas**, **tugas**, dan **pengumuman**. Backend wajib mengecek via Policy (`ClassPolicy`, `TaskPolicy`, `AnnouncementPolicy`), bukan hanya `$user->role === 'lecturer'` yang ditulis inline di controller.
- **BR-AUTH-02**: Frontend (Blade) hanya menyembunyikan/menampilkan tombol aksi berdasarkan role — ini bukan pengganti pengecekan otorisasi di backend.
- **BR-AUTH-03**: Dosen hanya boleh mengelola kelas, tugas, dan pengumuman **miliknya sendiri** (`owner_id` / relasi ke kelas miliknya). Dosen tidak boleh mengedit kelas dosen lain.

---

## Akses & Keanggotaan Kelas

- **BR-LMS-01**: Mahasiswa hanya bisa melihat kelas, tugas, dan pengumuman dari kelas yang ia ikuti — yaitu kelas yang tercatat di `class_participants` dengan `user_id` miliknya.
- **BR-LMS-02**: Mahasiswa bergabung ke kelas melalui URL `/class/join/{join_code}`. Tidak ada proses approval dari dosen — begitu kode valid, mahasiswa langsung dimasukkan ke `class_participants`.
- **BR-LMS-03**: `join_code` bersifat unik per kelas dan digenerate otomatis oleh sistem saat kelas dibuat (bukan diinput manual oleh dosen).
- **BR-LMS-04**: Kombinasi (`class_id`, `user_id`) di `class_participants` bersifat unik — satu mahasiswa tidak bisa join ke kelas yang sama dua kali. Percobaan join ulang dengan kode yang sudah diikuti tidak menghasilkan baris duplikat (idempotent).

---

## Tugas

- **BR-LMS-05**: Status tugas dihitung otomatis, tidak diinput manual: `Active` jika `deadline_at > NOW()`, `Expired` jika `deadline_at <= NOW()`. Bisa direalisasikan via accessor Eloquent dan/atau scheduled command yang menyinkronkan kolom `status` secara berkala.
- **BR-LMS-06**: Aplikasi tidak menyediakan mekanisme submit/upload jawaban tugas. Tugas hanya berupa informasi (judul, deskripsi, deadline) yang harus dikerjakan mahasiswa di luar sistem.

---

## Pengumuman & Jadwal

- **BR-LMS-07**: Pengumuman hanya tampil di stream/beranda mahasiswa yang tergabung di kelas terkait, dan berhenti tampil setelah `expired_at` terlewati.
- **BR-LMS-08**: Kolom `schedule_day` pada tabel `classes` wajib berformat JSON dan **hanya berisi hari yang aktif** — contoh: `{"monday": "08:00-10:00", "wednesday": "13:00-15:00"}`. Hari yang tidak ada jadwal tidak boleh muncul sebagai key dengan value kosong.

---

## Notifikasi

- **BR-LMS-09**: Setiap kali dosen membuat tugas baru atau pengumuman baru, sistem **wajib** mengirim notifikasi ke seluruh mahasiswa yang terdaftar di `class_participants` kelas tersebut, melalui 2 kanal: in-app (tabel `notifications`) dan email.
- **BR-LMS-10**: Sistem mengirim notifikasi pengingat H-1 deadline untuk setiap tugas yang statusnya masih `Active` dan `deadline_at` jatuh dalam 24 jam ke depan. Pengecekan ini dijalankan lewat scheduled command (Laravel Scheduler), bukan proses real-time.
- **BR-LMS-11**: Notifikasi dikirim menggunakan queue driver `sync` — dikirim langsung saat trigger terjadi, tidak diantrekan ke worker terpisah.