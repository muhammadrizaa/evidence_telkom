<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyawan Panel | {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background: #f3f4f6; display: flex; min-height: 100vh; }
        .sidebar { width: 250px; min-width: 250px; background: linear-gradient(180deg, #991b1b, #dc2626, #ef4444); color: #fff; display: flex; flex-direction: column; padding: 20px 0; position: sticky; top: 0; height: 100vh; overflow-y: auto; z-index: 50; flex-shrink: 0; }
        .sidebar .logo-container { text-align: center; margin-bottom: 25px; padding: 0 20px; }
        .sidebar img { display: block; margin: 0 auto 10px; width: 80px; }
        .sidebar h2 { font-size: 1.2rem; font-weight: 600; }
        .sidebar a { padding: 13px 25px; color: #fff; text-decoration: none; font-weight: 500; display: flex; align-items: center; transition: background 0.3s; }
        .sidebar a i { margin-right: 15px; width: 20px; text-align: center; }
        .sidebar a.active, .sidebar a:hover { background: rgba(255,255,255,0.2); border-radius: 5px; margin: 0 10px; padding: 13px 15px; }
        .sidebar .logout { margin-top: auto; border-top: 1px solid rgba(255,255,255,0.3); }
        .sidebar .logout a:hover { background: none; }
        .content { flex: 1; padding: 25px; min-width: 0; overflow-x: hidden; }
        .topbar { background: #fff; padding: 15px 25px; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        .welcome { font-size: 1.1rem; font-weight: 600; color: #991b1b; }
        .date { font-weight: 500; color: #4b5563; font-size: 0.9rem; }
        .hamburger { display: none; background: none; border: none; color: #991b1b; font-size: 1.5rem; cursor: pointer; padding: 0; margin-right: 12px; }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 40; }
        .sidebar-overlay.open { display: block; }

        @media (max-width: 768px) {
            .hamburger { display: block; }
            .sidebar { position: fixed; left: -260px; top: 0; height: 100vh; transition: left 0.3s ease; }
            .sidebar.open { left: 0; }
            .content { padding: 15px; }
            .welcome { font-size: 0.9rem; }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

    <div class="sidebar" id="sidebar">
        <div class="logo-container">
            <img src="{{ asset('images/logo-kiri.png') }}" alt="Logo Telkom Akses">
            <h2>Karyawan</h2>
        </div>
        <a href="{{ route('karyawan.dashboard') }}" class="{{ request()->routeIs('karyawan.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('karyawan.evidence.create') }}" class="{{ request()->routeIs('karyawan.evidence.create') ? 'active' : '' }}"><i class="fa-solid fa-upload"></i> Input Evidence</a>
        <a href="{{ route('karyawan.evidence.index') }}" class="{{ request()->routeIs('karyawan.evidence.index') ? 'active' : '' }}"><i class="fa-solid fa-history"></i> Riwayat Evidence</a>
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}"><i class="fa-solid fa-user-circle"></i> Kelola Profile</a>
        <div class="logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"><i class="fa-solid fa-right-from-bracket"></i> Log Out</a>
            </form>
        </div>
    </div>

    <div class="content">
        <div class="topbar">
            <div style="display:flex; align-items:center;">
                <button class="hamburger" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
                <div class="welcome">Selamat Datang, {{ Auth::user()->name }}</div>
            </div>
            <div class="date" id="realtime-clock"></div>
        </div>
        {{ $slot }}
    </div>

    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('open');
        }
        function updateClock() {
            const now = new Date();
            const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            const h = String(now.getHours()).padStart(2,'0');
            const m = String(now.getMinutes()).padStart(2,'0');
            const s = String(now.getSeconds()).padStart(2,'0');
            document.getElementById('realtime-clock').textContent =
                `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()} ${h}:${m}:${s}`;
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>

    @stack('scripts')
</body>
</html>