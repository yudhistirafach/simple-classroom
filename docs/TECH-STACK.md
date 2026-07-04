# Tech Stack

## Core Backend

| Layer | Pilihan | Versi |
|---|---|---|
| Bahasa | PHP | 8.2 |
| Framework | Laravel | 12.x |
| Database | MySQL (atau MariaDB) | 8.x / 10.x |
| Autentikasi | **Manual (Custom Controller)** | — |
| Dependency Manager | Composer | 2.8.x |

## Core Frontend

| Layer | Pilihan | Catatan |
|---|---|---|
| Template Engine | Laravel Blade | Server-rendered, tanpa SPA/API terpisah |
| CSS Framework | Bootstrap | 5.3 (via CDN) |
| Asset Bundling | Vite | Entry: `resources/css/main.css`, `resources/js/app.js` |
| Icons | Font Awesome | 6.x (via CDN) |
| Desain acuan | Google Classroom | Sidebar navigasi, kartu kelas, halaman stream/beranda |

## Notifikasi

- **Laravel Notifications** (built-in) — 2 channel: `mail` dan `database`.
- Trigger: tugas baru dibuat, pengumuman baru dibuat, H-1 deadline tugas (via scheduled command `schedule:run`).

## Queue

- Driver: **`sync`** — semua job (termasuk pengiriman notifikasi) dijalankan langsung secara synchronous, tidak ada Redis/Horizon/worker terpisah.

## Tooling pendukung
- **Laravel Scheduler** — untuk cron job pengecekan H-1 deadline dan update status tugas.
- **Vite** — untuk bundling dan development asset (hot-reload).
- Seeder & Factory bawaan Laravel untuk data dummy (role dosen/mahasiswa, kelas contoh).

---

## Yang TIDAK dipakai (dan alasannya)

| Tidak dipakai | Alasan |
|---|---|
| Laravel Breeze | Diganti dengan controller manual agar lebih fleksibel dengan field `fullname` dan `role` |
| Redis / Laravel Horizon | Queue `sync` sudah cukup untuk skala mini-LMS |
| Inertia.js / React / Vue | Cukup Blade + Bootstrap, tidak perlu SPA |
| WebSocket / Pusher / Soketi | Notifikasi cukup database + email, tidak perlu real-time |
| API (Sanctum/Passport) | Tidak ada mobile app atau konsumen API eksternal |
| Fitur submit tugas / upload jawaban | Di luar scope aplikasi (lihat `PROJECT-BRIEF.md`) |

## Environment
- Dev: XAMPP / Laravel Herd lokal.
- Storage: Laravel filesystem lokal (tidak ada kebutuhan cloud storage untuk MVP).
- Queue: `sync` driver untuk semua environment (dev maupun produksi awal).
- Asset: Vite development server (`npm run dev`) atau build (`npm run build`).