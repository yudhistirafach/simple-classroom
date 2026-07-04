# Decision Log

Log ini mencatat keputusan arsitektur yang sudah dibuat dan alasannya.
AI tidak boleh membalik keputusan ini kecuali developer meminta review secara eksplisit.

---

## Format entry

```
### [YYYY-MM-DD] — Judul keputusan
**Keputusan:** Apa yang diputuskan
**Alasan:** Mengapa
**Alternatif yang ditolak:** Opsi lain yang dipertimbangkan tapi tidak dipilih
```

---

### [2026-06-29] — Pivot table `class_participants` untuk relasi many-to-many

**Keputusan:** Relasi antara `users` (mahasiswa) dan `classes` direalisasikan lewat tabel pivot `class_participants`, menggantikan kolom `participant_id` di tabel `classes` pada migration awal.
**Alasan:** Satu mahasiswa bisa mengikuti banyak kelas, dan satu kelas bisa diikuti banyak mahasiswa — relasi ini murni many-to-many, tidak bisa direpresentasikan dengan foreign key tunggal.
**Alternatif yang ditolak:** Kolom `participant_id` langsung di `classes` (desain migration awal) — ditolak karena hanya menampung satu mahasiswa per kelas.

---

### [2026-06-29] — Join kelas via kode, tanpa proses approve

**Keputusan:** Mahasiswa bergabung ke kelas menggunakan `join_code` melalui URL (`/class/join/{join_code}`). Begitu kode valid, mahasiswa langsung masuk ke `class_participants`.
**Alasan:** Mengikuti pola Google Classroom yang familiar bagi mahasiswa, dan menyederhanakan alur — dosen tidak perlu meninjau/menyetujui satu per satu.
**Alternatif yang ditolak:** Alur approval manual oleh dosen — ditolak karena menambah friksi tanpa kebutuhan bisnis yang jelas untuk skala aplikasi ini.

---

### [2026-06-29] — Tidak ada fitur submit tugas

**Keputusan:** Tugas hanya berupa informasi (judul, deskripsi, deadline). Tidak ada mekanisme upload/submit jawaban di dalam aplikasi.
**Alasan:** Sesuai requirement — aplikasi ini adalah alat pengingat jadwal, bukan LMS penuh dengan alur pengumpulan & penilaian tugas.
**Alternatif yang ditolak:** Fitur submit file jawaban — ditolak karena di luar scope MVP dan menambah kompleksitas storage & validasi.

---

### [2026-06-29] — Notifikasi 2 channel tanpa real-time

**Keputusan:** Notifikasi dikirim lewat 2 kanal: database (in-app) dan email, menggunakan Laravel Notifications bawaan. Tidak ada websocket/real-time push.
**Alasan:** Kebutuhan notifikasi bersifat non-mendesak (tugas baru, pengumuman, pengingat H-1) — cukup dicek saat mahasiswa membuka aplikasi atau email masuk, tanpa perlu infrastruktur real-time.
**Alternatif yang ditolak:** Pusher/Soketi/WebSocket — ditolak karena overkill untuk kebutuhan notifikasi yang tidak time-critical dalam hitungan detik.

---

### [2026-06-29] — Blade + Bootstrap 5, bukan SPA

**Keputusan:** Frontend dibangun dengan Laravel Blade + Bootstrap 5.3, bukan stack SPA (Inertia/React/Vue).
**Alasan:** Kemudahan dan kecepatan development untuk tim kecil, serta desain acuan (Google Classroom) bisa dicapai dengan komponen Bootstrap standar tanpa kompleksitas build tool SPA.
**Alternatif yang ditolak:** Inertia + React — ditolak karena menambah kompleksitas tooling tanpa manfaat signifikan untuk aplikasi seukuran ini.

---

### [2026-07-03] — Migrasi database difinalisasi sesuai desain

**Keputusan:** Migration `classes`, `tasks`, `announcements` sudah diperbaiki (kolom `participant_id` dihapus, `timestamps()` lengkap), ditambah migration baru `class_participants` (pivot) dan `notifications` (standar Laravel). Kolom `users.fullname` dipertahankan apa adanya (bukan diganti ke `name`).
**Alasan:** Migration aktual menjadi sumber kebenaran struktur database; dokumentasi disesuaikan mengikuti migration, bukan sebaliknya.
**Alternatif yang ditolak:** Mengganti `fullname` → `name` di migration agar "sesuai standar Laravel" — ditolak untuk saat ini karena migration sudah berjalan dan mengubahnya butuh migration tambahan (`rename column`) yang tidak mendesak.

---

### [2026-06-29] — Queue driver `sync`, tanpa Redis/Horizon

**Keputusan:** Semua proses (termasuk pengiriman notifikasi) berjalan synchronous dengan queue driver `sync`.
**Alasan:** Skala awal aplikasi kecil, kompleksitas infrastruktur Redis/Horizon belum dibutuhkan.
**Alternatif yang ditolak:** Redis + Laravel Horizon — bisa dipertimbangkan lagi jika volume notifikasi (email massal) menjadi bottleneck di kemudian hari.

_Tambahkan entry baru di atas baris ini setiap kali ada keputusan arsitektur baru._