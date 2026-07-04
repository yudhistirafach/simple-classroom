# Modules

Status: `[ ]` belum mulai · `[~]` sedang dikerjakan · `[x]` selesai

---

## Foundation

| Status | Modul | Catatan |
|---|---|---|
| [x] | Auth (Login/Register) | Laravel Breeze Blade stack, termasuk field `role` saat register |
| [x] | Seeder Role Dasar | Seeder akun contoh dosen & mahasiswa untuk development |
| [x] | Layout & navigasi dasar | Sidebar + topbar mirip Google Classroom |

## Domain: Manajemen Kelas

| Status | Modul | Catatan |
|---|---|---|
| [ ] | CRUD Kelas (dosen) | Nama, deskripsi, jadwal (`schedule_day`) |
| [ ] | Generate `join_code` otomatis | Saat kelas dibuat |
| [ ] | Join Kelas (mahasiswa) | Via URL `/class/join/{join_code}`, tanpa approval |
| [ ] | Daftar peserta kelas | Dilihat oleh dosen pemilik kelas |

## Domain: Manajemen Tugas

| Status | Modul | Catatan |
|---|---|---|
| [ ] | CRUD Tugas (dosen) | Judul, deskripsi, deadline — tanpa submit jawaban |
| [ ] | Status otomatis Active/Expired | Berdasarkan `deadline_at` vs waktu sekarang |
| [ ] | Notifikasi tugas baru | Terkirim ke semua peserta kelas (in-app + email) |
| [ ] | Notifikasi H-1 deadline | Scheduled command harian |

## Domain: Manajemen Pengumuman

| Status | Modul | Catatan |
|---|---|---|
| [ ] | CRUD Pengumuman (dosen) | Judul, deskripsi, tanggal kadaluarsa |
| [ ] | Notifikasi pengumuman baru | Terkirim ke semua peserta kelas (in-app + email) |

## Domain: Dashboard / Stream

| Status | Modul | Catatan |
|---|---|---|
| [ ] | Beranda/Stream mahasiswa | Kartu kelas + feed tugas & pengumuman, mirip Google Classroom |
| [ ] | Beranda dosen | Daftar kelas milik dosen + ringkasan tugas/pengumuman terbaru |

## Domain: Notifikasi

| Status | Modul | Catatan |
|---|---|---|
| [ ] | Notification Center in-app | Daftar notifikasi + tandai terbaca |
| [ ] | Notifikasi Email | Menggunakan Laravel Notifications channel `mail` |

---

## Dependensi antar modul

```
Auth & Role
  └─ diperlukan oleh semua modul

Manajemen Kelas (join_code)
  └─ diperlukan oleh: Manajemen Tugas, Manajemen Pengumuman, Dashboard/Stream

Tugas & Pengumuman baru
  └─ trigger: Notifikasi (in-app & email)

Deadline Tugas (H-1)
  └─ trigger: Notifikasi terjadwal
```

---

_Update status modul ini setiap kali ada progress._