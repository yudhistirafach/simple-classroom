<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Classroom — Jadwal Kuliah & Reminder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #ffffff;
            color: #3c4043;
            overflow-x: hidden;
        }

        /* Google Style Minimalist Navbar */
        .navbar {
            height: 64px;
            background-color: #ffffff;
            transition: box-shadow 0.2s ease;
        }
        .navbar-brand {
            font-weight: 500;
            font-size: 1.35rem;
            color: #5f6368;
            letter-spacing: -0.2px;
        }
        .navbar-brand span {
            color: #1a73e8;
            font-weight: 700;
        }

        /* Hero Area */
        .hero-section {
            padding: 3rem 0;
        }
        @media (min-width: 768px) {
            .hero-section {
                padding: 5rem 0;
            }
        }
        .hero-title {
            font-size: calc(1.8rem + 1.5vw);
            font-weight: 400;
            line-height: 1.25;
            color: #202124;
            letter-spacing: -0.5px;
        }
        .hero-desc {
            font-size: 1.1rem;
            color: #5f6368;
            max-width: 540px;
        }

        /* Google Product Style Cards */
        .gc-card {
            border: 1px solid #dadce0;
            border-radius: 8px;
            background-color: #ffffff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .gc-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(60,64,67,0.12);
        }
        .icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1.25rem;
        }
        .icon-blue { background-color: #e8f0fe; color: #1a73e8; }
        .icon-green { background-color: #e6f4ea; color: #137333; }
        .icon-yellow { background-color: #fef7e0; color: #b06000; }

        /* Buttons style matching Google Material */
        .btn-google-primary {
            background-color: #1a73e8;
            color: #ffffff;
            font-weight: 500;
            border-radius: 4px;
            padding: 0.6rem 1.5rem;
            border: none;
            transition: background-color 0.2s;
        }
        .btn-google-primary:hover {
            background-color: #1557b0;
            color: #ffffff;
        }
        .btn-google-outline {
            border: 1px solid #dadce0;
            background-color: #ffffff;
            color: #1a73e8;
            font-weight: 500;
            border-radius: 4px;
            padding: 0.6rem 1.5rem;
            transition: background-color 0.2s, border-color 0.2s;
        }
        .btn-google-outline:hover {
            background-color: #f8f9fa;
            border-color: #c3c7cb;
            color: #1557b0;
        }

        /* Minimalist Footer */
        .footer {
            border-top: 1px solid #dadce0;
            background-color: #f8f9fa;
            font-size: 0.85rem;
            color: #70757a;
        }
        .footer a {
            color: #70757a;
            text-decoration: none;
        }
        .footer a:hover {
            color: #202124;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand border-bottom sticky-top shadow-sm-mobile">
        <div class="container px-3">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <i class="fas fa-graduation-cap text-primary fs-4"></i>
                <span>Simple</span>Classroom
            </a>
            
            <div class="ms-auto d-flex align-items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-google-primary btn-sm px-3">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-link text-secondary text-decoration-none fw-medium btn-sm px-3">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-google-primary btn-sm px-3 d-none d-sm-inline-block">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <section class="hero-section bg-white">
        <div class="container px-3">
            <div class="row align-items-center g-4 g-md-5">
                <div class="col-12 col-md-7 text-center text-md-start">
                    <h1 class="hero-title mb-3">
                        Kelola perkuliahan & tenggat waktu dalam satu tempat yang bersih
                    </h1>
                    <p class="hero-desc mx-auto mx-md-0 mb-4">
                        Aplikasi mini-LMS terpusat yang dirancang khusus untuk membantu mahasiswa dan dosen mengelola jadwal kuliah, pengumuman, dan reminder tugas dengan antarmuka yang intuitif.
                    </p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-md-start gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-google-primary">
                                Kembali ke Dashboard <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-google-primary">
                                Mulai Sekarang secara Gratis
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-google-outline">
                                Pelajari Fitur
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-12 col-md-5 text-center d-flex justify-content-center align-items-center order-first order-md-last">
                    <div class="position-relative d-flex align-items-center justify-content-center" style="width: 220px; height: 220px; max-width: 100%;">
                        <div class="position-absolute w-100 h-100 rounded-circle bg-light opacity-50 shadow-sm animate-pulse"></div>
                        <i class="fas fa-laptop-code text-primary opacity-75" style="font-size: 7rem; z-index: 2;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light border-top border-bottom">
        <div class="container px-3">
            <div class="text-center mb-5">
                <h2 class="text-dark fw-normal mb-2" style="letter-spacing: -0.3px;">Segala kemudahan akademik yang Anda butuhkan</h2>
                <p class="text-secondary small mx-auto" style="max-width: 480px;">Sederhana namun andal dalam memastikan tidak ada informasi perkuliahan penting yang terlewat.</p>
            </div>

            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card gc-card h-100 p-4">
                        <div class="card-body p-0">
                            <div class="icon-wrapper icon-blue">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <h5 class="card-title text-dark fw-medium mb-2 fs-5">Manajemen Ruang Kelas</h5>
                            <p class="card-text text-secondary small mb-0 lh-base">
                                Dosen dapat membuat kelas terstruktur dalam hitungan detik. Mahasiswa cukup bergabung menggunakan kode akses unik (`join_code`) tanpa alur pendaftaran yang rumit.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card gc-card h-100 p-4">
                        <div class="card-body p-0">
                            <div class="icon-wrapper icon-green">
                                <i class="fas fa-stream"></i>
                            </div>
                            <h5 class="card-title text-dark fw-medium mb-2 fs-5">Forum Pengumuman & Tugas</h5>
                            <p class="card-text text-secondary small mb-0 lh-base">
                                Alur informasi (stream) yang bersih memadukan distribusi tugas kuliah dan pengumuman penting langsung dari dosen penampu dalam satu halaman utama yang teratur.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card gc-card h-100 p-4">
                        <div class="card-body p-0">
                            <div class="icon-wrapper icon-yellow">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h5 class="card-title text-dark fw-medium mb-2 fs-5">Pengingat Otomatis</h5>
                            <p class="card-text text-secondary small mb-0 lh-base">
                                Mengurangi risiko keterlambatan pengumpulan berkas melalui pemicu notifikasi in-app dan surat elektronik (email) yang dikirim otomatis H-1 sebelum deadline tugas selesai.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white text-center">
        <div class="container px-3 py-3">
            <h3 class="text-dark fw-normal mb-2">Siap untuk merapikan jadwal perkuliahan Anda?</h3>
            <p class="text-secondary small mb-4 mx-auto" style="max-width: 420px;">Gabung sekarang dengan ribuan pengguna lain dan rasakan kemudahan asisten akademik digital terpadu.</p>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-google-primary">
                    Kembali ke Halaman Utama
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-google-primary px-4">
                    <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                </a>
            @endauth
        </div>
    </section>

    <footer class="footer py-4 mt-auto">
        <div class="container px-3 d-flex flex-column flex-sm-row justify-content-between align-items-center text-center text-sm-start gap-3">
            <span>&copy; {{ date('Y') }} Simple Classroom. Semua hak cipta dilindungi.</span>
            <div class="d-flex gap-3">
                <a href="{{ route('login') }}">Masuk</a>
                <a href="{{ route('register') }}">Daftar Akun</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>