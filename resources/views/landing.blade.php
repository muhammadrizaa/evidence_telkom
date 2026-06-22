<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Telkom Akses Banjarmasin | Evidence Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #fdfaf9;
            color: #1f2937;
            overflow-x: hidden;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(8px);
            z-index: 100;
            padding: 16px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        }
        .navbar .logo { display: flex; align-items: center; gap: 10px; }
        .navbar .logo img { height: 36px; }
        .navbar .logo span { font-weight: 700; color: #991b1b; font-size: 1.05rem; }
        .navbar .login-btn {
            background: linear-gradient(135deg, #991b1b, #dc2626);
            color: white;
            padding: 10px 24px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(220,38,38,0.3);
        }
        .navbar .login-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(220,38,38,0.4); }

        /* ===== HERO ===== */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 120px 40px 80px;
            background:
                radial-gradient(circle at 15% 20%, rgba(220,38,38,0.07), transparent 40%),
                radial-gradient(circle at 85% 80%, rgba(153,27,27,0.07), transparent 40%),
                #fdfaf9;
        }
        .hero-inner {
            max-width: 1100px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }
        @media (max-width: 900px) {
            .hero-inner { grid-template-columns: 1fr; text-align: center; }
            .hero-logo-wrap { order: -1; margin: 0 auto; }
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fee2e2;
            color: #991b1b;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 999px;
            margin-bottom: 24px;
        }
        .eyebrow i { font-size: 0.65rem; }

        .hero h1 {
            font-size: 2.6rem;
            font-weight: 800;
            line-height: 1.15;
            color: #111827;
            margin-bottom: 18px;
            letter-spacing: -0.02em;
        }
        .hero h1 .accent { color: #dc2626; }
        .hero p.lead {
            font-size: 1.05rem;
            color: #4b5563;
            line-height: 1.7;
            margin-bottom: 32px;
            max-width: 480px;
        }
        @media (max-width: 900px) { .hero p.lead { margin: 0 auto 32px; } }

        .hero-cta { display: flex; gap: 14px; flex-wrap: wrap; }
        @media (max-width: 900px) { .hero-cta { justify-content: center; } }
        .btn-primary {
            background: linear-gradient(135deg, #991b1b, #dc2626);
            color: white;
            padding: 14px 32px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 20px rgba(220,38,38,0.3);
            transition: all 0.25s ease;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(220,38,38,0.4); }
        .btn-secondary {
            background: white;
            color: #991b1b;
            border: 1.5px solid #fecaca;
            padding: 14px 28px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.25s ease;
        }
        .btn-secondary:hover { background: #fef2f2; border-color: #dc2626; }

        /* Logo signature panel */
        .hero-logo-wrap {
            position: relative;
            width: 100%;
            max-width: 380px;
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: linear-gradient(135deg, #fee2e2, #fff5f5);
            box-shadow: inset 0 0 0 1px rgba(220,38,38,0.08), 0 30px 60px -20px rgba(153,27,27,0.25);
        }
        .logo-ring::before {
            content: '';
            position: absolute;
            inset: 14px;
            border-radius: 50%;
            border: 1.5px dashed rgba(220,38,38,0.25);
        }
        .hero-logo-wrap img {
            position: relative;
            width: 62%;
            filter: drop-shadow(0 10px 24px rgba(0,0,0,0.12));
        }

        /* ===== ABOUT ===== */
        .about {
            background: #ffffff;
            padding: 90px 40px;
        }
        .about-inner { max-width: 1000px; margin: 0 auto; }
        .section-eyebrow {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #dc2626;
            margin-bottom: 12px;
        }
        .about h2 {
            font-size: 1.9rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 20px;
            max-width: 640px;
        }
        .about p {
            color: #4b5563;
            line-height: 1.8;
            font-size: 1rem;
            max-width: 700px;
            margin-bottom: 16px;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-top: 48px;
        }
        @media (max-width: 700px) { .stats-row { grid-template-columns: 1fr; } }
        .stat-box {
            background: #fdfaf9;
            border: 1px solid #f3e8e8;
            border-radius: 16px;
            padding: 28px 24px;
            text-align: left;
        }
        .stat-box .num { font-size: 1.9rem; font-weight: 800; color: #991b1b; line-height: 1; margin-bottom: 6px; }
        .stat-box .label { font-size: 0.85rem; color: #6b7280; font-weight: 500; }

        /* ===== FEATURES ===== */
        .features {
            background: #fdfaf9;
            padding: 90px 40px;
        }
        .features-inner { max-width: 1100px; margin: 0 auto; }
        .features-head { text-align: center; max-width: 600px; margin: 0 auto 56px; }
        .features-head h2 { font-size: 1.9rem; font-weight: 800; color: #111827; margin-bottom: 14px; }
        .features-head p { color: #6b7280; font-size: 1rem; line-height: 1.7; }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }
        @media (max-width: 900px) { .feature-grid { grid-template-columns: 1fr; } }
        .feature-card {
            background: white;
            border-radius: 18px;
            padding: 32px 28px;
            border: 1px solid #f3e8e8;
            transition: all 0.25s ease;
        }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 16px 32px -12px rgba(153,27,27,0.15); border-color: #fecaca; }
        .feature-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #991b1b, #dc2626);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px;
        }
        .feature-icon i { color: white; font-size: 1.2rem; }
        .feature-card h3 { font-size: 1.05rem; font-weight: 700; color: #111827; margin-bottom: 10px; }
        .feature-card p { font-size: 0.9rem; color: #6b7280; line-height: 1.65; }

        /* ===== ROLES ===== */
        .roles {
            background: linear-gradient(135deg, #991b1b 0%, #b91c1c 50%, #dc2626 100%);
            padding: 90px 40px;
            position: relative;
            overflow: hidden;
        }
        .roles::before {
            content: '';
            position: absolute;
            top: -50%; right: -10%;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.08), transparent 70%);
            border-radius: 50%;
        }
        .roles-inner { max-width: 1000px; margin: 0 auto; position: relative; z-index: 1; }
        .roles-head { text-align: center; max-width: 560px; margin: 0 auto 50px; }
        .roles-head .section-eyebrow { color: #fecaca; }
        .roles-head h2 { font-size: 1.9rem; font-weight: 800; color: white; margin-bottom: 14px; }
        .roles-head p { color: rgba(255,255,255,0.85); font-size: 1rem; line-height: 1.7; }

        .role-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }
        @media (max-width: 900px) { .role-grid { grid-template-columns: 1fr; } }
        .role-card {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 16px;
            padding: 28px 24px;
            backdrop-filter: blur(6px);
        }
        .role-card i { font-size: 1.6rem; color: white; margin-bottom: 16px; display: block; }
        .role-card h3 { color: white; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px; }
        .role-card p { color: rgba(255,255,255,0.8); font-size: 0.85rem; line-height: 1.6; }

        /* ===== CTA FOOTER ===== */
        .cta-section {
            background: white;
            padding: 80px 40px;
            text-align: center;
        }
        .cta-section h2 { font-size: 1.7rem; font-weight: 800; color: #111827; margin-bottom: 16px; }
        .cta-section p { color: #6b7280; margin-bottom: 32px; }

        footer {
            background: #1f1414;
            color: rgba(255,255,255,0.6);
            padding: 36px 40px;
            text-align: center;
            font-size: 0.85rem;
        }
        footer strong { color: white; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">
            <img src="{{ asset('images/logo-kiri.png') }}" alt="Telkom Indonesia">
            <span>PT Telkom Akses</span>
        </div>
        <a href="{{ route('login') }}" class="login-btn">Masuk <i class="fa-solid fa-arrow-right" style="margin-left:6px; font-size:0.75rem;"></i></a>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-inner">
            <div>
                <div class="eyebrow"><i class="fa-solid fa-circle"></i> Banjarmasin, Kalimantan Selatan</div>
                <h1>Bukti kerja lapangan, <span class="accent">tercatat rapi</span> dari hari pertama.</h1>
                <p class="lead">
                    Sistem manajemen evidence PT Telkom Akses Banjarmasin — satu portal untuk pencatatan,
                    persetujuan, dan pelaporan hasil pekerjaan jaringan fiber di lapangan.
                </p>
                <div class="hero-cta">
                    <a href="{{ route('login') }}" class="btn-primary">
                        <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Portal
                    </a>
                    <a href="#tentang" class="btn-secondary">Pelajari Lebih Lanjut</a>
                </div>
            </div>
            <div class="hero-logo-wrap">
                <div class="logo-ring"></div>
                <img src="{{ asset('images/logo-kiri.png') }}" alt="Telkom Indonesia">
            </div>
        </div>
    </section>

    <!-- ABOUT -->
    <section class="about" id="tentang">
        <div class="about-inner">
            <div class="section-eyebrow">Tentang Sistem</div>
            <h2>Mendukung pekerjaan OSP-FTTH dengan pencatatan yang dapat dipertanggungjawabkan.</h2>
            <p>
                PT Telkom Akses Banjarmasin menangani pekerjaan Outside Plant Fiber to the Home (OSP-FTTH)
                di wilayah Telkom Regional IV Kalimantan. Setiap pekerjaan lapangan — mulai dari pemasangan,
                perbaikan, hingga maintenance jaringan — perlu didokumentasikan secara akurat untuk keperluan
                pelaporan dan verifikasi kepada pelanggan.
            </p>
            <p>
                Sistem ini menjembatani karyawan lapangan, team leader, dan admin dalam satu alur kerja:
                penugasan dibuat, evidence diunggah, ditinjau, lalu dikompilasi menjadi laporan resmi
                berformat Word maupun PDF.
            </p>

            <div class="stats-row">
                <div class="stat-box">
                    <div class="num">3</div>
                    <div class="label">Peran pengguna terintegrasi — Admin, Team Leader, Karyawan</div>
                </div>
                <div class="stat-box">
                    <div class="num">Real-time</div>
                    <div class="label">Status persetujuan terlihat langsung oleh semua pihak</div>
                </div>
                <div class="stat-box">
                    <div class="num">2 Format</div>
                    <div class="label">Laporan otomatis dalam Word dan PDF</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="features">
        <div class="features-inner">
            <div class="features-head">
                <div class="section-eyebrow">Yang Bisa Dilakukan</div>
                <h2>Satu sistem, seluruh alur kerja evidence</h2>
                <p>Dari penugasan hingga laporan akhir, semua tercatat dalam satu portal yang sama.</p>
            </div>
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-diagram-project"></i></div>
                    <h3>Kelola Project & Penugasan</h3>
                    <p>Team leader membuat project, menentukan lokasi, tematik pekerjaan, dan menugaskan karyawan secara terstruktur.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-camera"></i></div>
                    <h3>Upload Evidence Lapangan</h3>
                    <p>Karyawan mengunggah foto bukti kerja lengkap dengan lokasi, waspang, dan nomor PO yang otomatis terisi.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-check-double"></i></div>
                    <h3>Verifikasi oleh Admin</h3>
                    <p>Setiap evidence ditinjau dan disetujui atau ditolak dengan catatan, menjaga kualitas dokumentasi.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-map-location-dot"></i></div>
                    <h3>Pemetaan Area Kerja</h3>
                    <p>Setiap penugasan terhubung dengan data mapping area, mempermudah pelacakan progres per wilayah.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-file-export"></i></div>
                    <h3>Generate Laporan Otomatis</h3>
                    <p>Evidence yang disetujui dikompilasi otomatis menjadi laporan Word atau PDF siap kirim ke pelanggan.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-gauge-high"></i></div>
                    <h3>Dashboard per Peran</h3>
                    <p>Admin, team leader, dan karyawan masing-masing memiliki dashboard yang relevan dengan tugasnya.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ROLES -->
    <section class="roles">
        <div class="roles-inner">
            <div class="roles-head">
                <div class="section-eyebrow">Tiga Peran, Satu Alur Kerja</div>
                <h2>Dibangun untuk cara tim ini bekerja</h2>
                <p>Setiap peran memiliki akses dan tampilan yang disesuaikan dengan tanggung jawabnya.</p>
            </div>
            <div class="role-grid">
                <div class="role-card">
                    <i class="fa-solid fa-user-tie"></i>
                    <h3>Admin</h3>
                    <p>Meninjau dan menyetujui evidence, mengelola data master, serta men-generate laporan akhir.</p>
                </div>
                <div class="role-card">
                    <i class="fa-solid fa-user-check"></i>
                    <h3>Team Leader</h3>
                    <p>Membuat project, menugaskan karyawan, mengatur mapping area dan deadline pekerjaan.</p>
                </div>
                <div class="role-card">
                    <i class="fa-solid fa-helmet-safety"></i>
                    <h3>Karyawan</h3>
                    <p>Mengerjakan penugasan di lapangan dan mengunggah evidence sebagai bukti hasil kerja.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
        <h2>Siap mencatat pekerjaan hari ini?</h2>
        <p>Masuk dengan akun yang telah didaftarkan oleh admin untuk mulai menggunakan sistem.</p>
        <a href="{{ route('login') }}" class="btn-primary">
            <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Portal
        </a>
    </section>

    <footer>
        <strong>PT Telkom Akses</strong> — Cabang Banjarmasin &nbsp;|&nbsp; Evidence Management System &copy; {{ date('Y') }}
    </footer>

</body>
</html>