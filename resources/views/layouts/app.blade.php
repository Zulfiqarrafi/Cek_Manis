<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CEKMANIS - Sahabat Cek Gula Jajanan Anak')</title>
    <!-- Kid-Friendly & Playful Fonts (Quicksand & Plus Jakarta Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&family=Quicksand:wght@500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="kid-theme">
    <!-- Top Navigation for PC / Desktop -->
    <nav class="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">
            <div class="brand-mascot">
                <i class="fa-solid fa-cube"></i>
                <span class="sparkle">✨</span>
            </div>
            <div class="brand-text">
                <span class="brand-title">CEKMANIS</span>
                <span class="brand-subtitle">Sahabat Sehat Anak</span>
            </div>
        </a>
        <div class="navbar-nav">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-house"></i></span>
                <span>Beranda</span>
            </a>
            <a href="{{ route('catalog') }}" class="nav-link {{ request()->routeIs('catalog*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-cookie-bite"></i></span>
                <span>Katalog Jajanan</span>
            </a>
            <a href="{{ route('calculator') }}" class="nav-link {{ request()->routeIs('calculator') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-calculator"></i></span>
                <span>Kalkulator Gula</span>
            </a>
            <a href="{{ route('history') }}" class="nav-link {{ request()->routeIs('history*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-clock-rotate-left"></i></span>
                <span>Buku Jajanku</span>
            </a>
            <a href="{{ route('recommendation') }}" class="nav-link {{ request()->routeIs('recommendation*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-medal"></i></span>
                <span>Misi Sehat</span>
            </a>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="container">
        @yield('content')
    </main>

    <!-- Kid-Friendly Friendly Footer -->
    <footer class="kid-footer">
        <div class="footer-content">
            <div class="footer-badge">
                <i class="fa-solid fa-heart-pulse"></i> Cek Gulanya, Atur Asupannya, Aktif Bergerak! 🏃‍♂️💨
            </div>
            <p>Aplikasi Edukasi Cek Gula Ramah Anak • Batas Gula Harian Maksimal 50g (≈ 4-5 Sendok Makan) sesuai Standar Kemenkes RI</p>
        </div>
    </footer>
</body>
</html>
