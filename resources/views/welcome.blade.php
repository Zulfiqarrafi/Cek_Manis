@extends('layouts.app')

@section('title', 'CekManis - Sahabat Cek Gula Jajanan Anak & Remaja')

@section('content')
<div class="home-grid">
    <!-- Left Side: Hero Information (Kid-Friendly & Playful) -->
    <div class="hero-text">
        <div class="mascot-greeting">
            <span class="mascot-avatar">🐻</span>
            <div class="mascot-bubble">
                <strong>Hai Sobat CekManis! 👋</strong>
                <span>Sudah cek berapa sendok gula jajananmu hari ini?</span>
            </div>
        </div>

        <h1 class="kid-title">
            Yuk Cek Gulamu!<br>
            Biar Tetap <span>Kuat, Ceria & Aktif!</span> 🚀✨
        </h1>

        <p class="kid-subtitle">
            Cari tahu berapa banyak gula di minuman dan camilan favoritmu. Batasi konsumsi gula berlebih supaya gigi tetap sehat, badan bugar, dan semangat belajar setiap hari!
        </p>

        <!-- Fun Feature Pills -->
        <div class="fun-pills">
            <div class="fun-pill green">
                <i class="fa-solid fa-apple-whole"></i> Jaga Gigi Sehat
            </div>
            <div class="fun-pill blue">
                <i class="fa-solid fa-gamepad"></i> Belajar Gizi Seru
            </div>
            <div class="fun-pill amber">
                <i class="fa-solid fa-person-running"></i> Aktif Bergerak
            </div>
        </div>
    </div>

    <!-- Right Side: Action Panel (Ceria & Mengundang Interaksi) -->
    <div class="action-panel kid-panel">
        <div class="panel-header">
            <div class="panel-title-group">
                <span class="panel-tag">🎯 PUSAT CEK JAJANAN</span>
                <h3 class="panel-title">Mau Cek Apa Hari Ini?</h3>
            </div>
            <span class="panel-emoji">🥤🍪</span>
        </div>
        
        <!-- Search Bar yang Terhubung ke Katalog -->
        <form action="{{ route('catalog') }}" method="GET" class="search-form">
            <div class="search-bar kid-search-bar">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" name="q" placeholder="Ketik nama jajanan (misal: Ultra Milk, Milo, Oreo, Teh Botol)..." required autocomplete="off">
                <button type="submit" class="btn-search kid-btn-search">
                    Cari! 🚀
                </button>
            </div>
        </form>

        <!-- Shortcut Pencarian Cepat Jajanan Favorit -->
        <div class="quick-keywords kid-keywords">
            <span class="quick-label">Jajanan Populer:</span>
            <a href="{{ route('catalog', ['q' => 'Ultra Milk']) }}">🥛 Ultra Milk</a>
            <a href="{{ route('catalog', ['q' => 'Milo']) }}">⚡ Milo</a>
            <a href="{{ route('catalog', ['q' => 'Oreo']) }}">🍪 Oreo</a>
            <a href="{{ route('catalog', ['q' => 'Teh']) }}">🍵 Teh Pucuk</a>
            <a href="{{ route('catalog', ['level' => 'rendah']) }}">💧 Air 0g Gula</a>
        </div>

        <!-- Action Buttons Baru (Warna-Warni Ceria) -->
        <div class="action-buttons kid-buttons">
            <a href="{{ route('catalog') }}" class="btn-action catalog kid-card-btn">
                <div class="btn-icon-wrapper bubble-blue">
                    <i class="fa-solid fa-cookie-bite"></i>
                </div>
                <span class="btn-text-main">Katalog Jajanan</span>
                <span class="btn-text-sub">45+ Produk Enak</span>
            </a>
            
            <a href="{{ route('calculator') }}" class="btn-action calculator kid-card-btn">
                <div class="btn-icon-wrapper bubble-green">
                    <i class="fa-solid fa-calculator"></i>
                </div>
                <span class="btn-text-main">Kalkulator Gula</span>
                <span class="btn-text-sub">Hitung Sendok Gula</span>
            </a>
            
            <a href="{{ route('upload') }}" class="btn-action upload kid-card-btn">
                <div class="btn-icon-wrapper bubble-purple">
                    <i class="fa-solid fa-camera"></i>
                </div>
                <span class="btn-text-main">Upload Foto</span>
                <span class="btn-text-sub">Foto Label Kemasan</span>
            </a>
        </div>

        <!-- Fun Fact Card -->
        <div class="fun-fact-card">
            <span class="fact-icon">💡</span>
            <div class="fact-text">
                <strong>Tahukah Kamu?</strong> 1 botol teh manis bisa mengandung <strong>5 sampai 6 sendok teh gula</strong> lho! Jangan lupa minum air putih ya!
            </div>
        </div>
    </div>
</div>
@endsection
