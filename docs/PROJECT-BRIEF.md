# Project Brief — Aplikasi Jadwal Kuliah & Reminder Berbasis Agile

## Satu kalimat
Aplikasi web mini-LMS yang membantu mahasiswa mengelola waktu secara efektif dengan menyediakan sistem pengingat (reminder) untuk jadwal kelas, tugas, dan pengumuman.

## Latar belakang & Visi
Mahasiswa dengan jadwal kuliah yang padat sering kesulitan melacak jadwal kelas pengganti, tenggat tugas, dan pengumuman dari dosen yang tersebar di berbagai kanal. Aplikasi ini hadir sebagai satu pusat informasi akademik yang ringkas: dosen mempublikasikan kelas, tugas, dan pengumuman; mahasiswa menerima pengingat otomatis sehingga tidak ada informasi penting yang terlewat.

**Visi:** Menjadi asisten jadwal akademik yang sederhana namun andal bagi mahasiswa dan dosen.

**Misi:**
- Menyediakan satu tempat terpusat untuk jadwal kelas, tugas, dan pengumuman.
- Mengirim pengingat otomatis (in-app & email) agar mahasiswa tidak melewatkan tenggat waktu.
- Mempermudah dosen mengelola kelas tanpa proses administratif yang rumit (join kelas cukup dengan kode).

## Tujuan (SMART Goals)
- **Specific:** Menyediakan fitur pengingat otomatis untuk jadwal kelas dan tenggat waktu tugas.
- **Measurable:** Digunakan oleh minimal 100 mahasiswa dalam bulan pertama peluncuran.
- **Achievable:** Dikembangkan menggunakan kerangka kerja Scrum dalam 5 sprint.
- **Relevant:** Mengurangi tingkat keterlambatan pengumpulan tugas hingga 30%.
- **Time-bound:** Prototipe fungsional siap dalam 2 bulan.

## Ini BUKAN
- **Bukan** LMS dengan fitur submit/upload jawaban tugas — dosen hanya mempublikasikan tugas beserta deadline, tidak ada mekanisme pengumpulan jawaban di dalam aplikasi.
- **Bukan** sistem penilaian atau rapor akademik (tidak ada nilai/grading).
- **Bukan** aplikasi konferensi video atau kelas online real-time.
- **Bukan** marketplace kursus atau platform pembelajaran berbayar.

## Pengguna & hubungan

### Dosen (`lecturer`)
- Membuat, mengedit, menghapus kelas miliknya.
- Membuat, mengedit, menghapus tugas & pengumuman di kelas miliknya.
- Melihat daftar mahasiswa yang tergabung di kelasnya.

### Mahasiswa (`student`)
- Bergabung ke kelas menggunakan `join_code` yang dibagikan dosen.
- Melihat stream/beranda berisi tugas & pengumuman dari kelas yang diikuti.
- Menerima notifikasi (in-app & email) untuk tugas baru, pengumuman baru, dan pengingat H-1 deadline.

**Hubungan:** Many-to-many antara `users` dan `classes`, direalisasikan lewat tabel pivot `class_participants`. Satu dosen bisa memiliki banyak kelas (one-to-many via `owner_id`); satu mahasiswa bisa mengikuti banyak kelas, dan satu kelas bisa diikuti banyak mahasiswa.

## Referensi tech stack
Backend Laravel 12 (PHP 8.2) dengan database MySQL. Antarmuka dibangun dengan **Laravel Blade + Bootstrap 5**, didesain semirip mungkin dengan Google Classroom (sidebar navigasi, kartu kelas, halaman stream/beranda). Detail lengkap ada di `TECH-STACK.md`.

## Kompleksitas target
Sistem **kecil-menengah**, monolith Laravel murni (tanpa Inertia/React/SPA). Satu database MySQL. Tidak ada microservice, tidak ada queue async (queue driver `sync`), tidak ada real-time (notifikasi cukup database + email, bukan websocket).