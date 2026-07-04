# File Map

Peta struktur folder dan file penting di proyek. Update saat ada file/folder baru yang signifikan.

---

## Struktur Backend (Laravel)

```
+---app
| +---Http
| | +---Controllers
| | | | AuthController.php
| | | | Controller.php
| | | | DashboardController.php
| | | |
| | | ---Auth
| | | (manual auth controllers - tidak pakai Breeze)
| | |
| | ---Requests
| | AuthRequest.php
| |
| +---Models
| | Announcement.php
| | ClassParticipants.php
| | Classroom.php
| | Tasks.php
| | User.php
| |
| ---Providers
| AppServiceProvider.php
|
+---bootstrap
| | app.php
| | providers.php
| |
| ---cache
| .gitignore
| packages.php
| services.php
|
+---config
| app.php
| auth.php
| cache.php
| database.php
| filesystems.php
| logging.php
| mail.php
| queue.php
| services.php
| session.php
|
+---database
| | .gitignore
| |
| +---factories
| | UserFactory.php
| |
| +---migrations
| | 0001_01_01_000000_create_users_table.php
| | 0001_01_01_000001_create_cache_table.php
| | 2026_06_29_125846_create_classes_table.php
| | 2026_06_29_125847_create_tasks_table.php
| | 2026_06_29_125848_create_announcements_table.php
| | 2026_07_03_044703_create_class_participants_table.php
| | 2026_07_03_044734_create_notifications_table.php
| |
| ---seeders
| DatabaseSeeder.php
+---routes
| console.php
| web.php
```

---

## Struktur Frontend (React + Inertia)

```
+---resources
| +---css
| | app.css (tidak dipakai - menggunakan Vite + main.css)
| | auth.css (custom auth styles - via Vite)
| | main.css (custom main styles - via Vite, termasuk sidebar & topbar)
| |
| +---js
| | app.js (entry point Vite)
| | bootstrap.js (konfigurasi Bootstrap JS)
| |
| ---views
| | welcome.blade.php
| |
| +---auth
| | login.blade.php (extends layouts.auth)
| | register.blade.php (extends layouts.auth)
| |
| +---components
| | class-card.blade.php
| | sidebar.blade.php
| | toast.blade.php
| | topbar.blade.php
| |
| +---layouts
| | app.blade.php (tidak dipakai - menggunakan main.blade.php)
| | auth.blade.php (layout untuk login/register)
| | main.blade.php (layout utama dengan sidebar + topbar)
| |
| +---lecturer
| | | dashboard.blade.php
| | |
| | ---classes
| | index.blade.php
| |
| +---student
| dashboard.blade.php             

```
---
## File Konfigurasi Penting

| File | Fungsi |
|---|---|
| `routes/web.php` | Semua route aplikasi |
| `app/Providers/AuthServiceProvider.php` | Registrasi semua Policy (`ClassPolicy`, `TaskPolicy`, `AnnouncementPolicy`) |
| `app/Console/Kernel.php` | Jadwal command (`UpdateExpiredTasks`, `SendDeadlineReminders`) |
| `database/seeders/DatabaseSeeder.php` | Entry point seeder |
| `config/mail.php` | Konfigurasi pengiriman email notifikasi |

---

_Update file ini saat ada folder/file baru yang penting untuk diketahui AI._
