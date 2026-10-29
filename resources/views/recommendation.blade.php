@extends('layouts.app')

@section('title', 'Misi Sehat & Aktif Bergerak - CekManis')

@section('content')
<div class="recommendation-container kid-recommendation" style="max-width: 1000px; margin: 0 auto;">
    <!-- Header Section -->
    <div class="page-header kid-page-header" style="margin-bottom: 28px;">
        <div>
            <h2><span class="header-icon">🏆</span> Misi Sehat & Aktif Bergerak!</h2>
            <p>Saran seru buat Sobat CekManis biar tubuh tetap kuat, berenergi, dan gigi nggak gampang berlubang!</p>
        </div>
        <a href="{{ route('history') }}" class="btn-secondary-link kid-link-btn">
            <i class="fa-solid fa-clock-rotate-left"></i> Buka Buku Jajanku
        </a>
    </div>

    <!-- Status Evaluasi Gula Real-Time Gamified -->
    @php
        $statusEmoji = $status === 'BAHAYA' ? '🚨' : ($status === 'WASPADA' ? '⚠️' : '🌟');
    @endphp
    <div class="kid-eval-card" style="background: {{ $statusBg }}; border: 2px solid {{ $statusBorder }};">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div class="eval-emoji-box" style="color: {{ $statusColor }};">
                    {{ $statusEmoji }}
                </div>
                <div>
                    <span class="eval-tag" style="color: {{ $statusColor }};">STATUS GULA HARI INI</span>
                    <h3 class="eval-title">{{ $statusTitle }}</h3>
                </div>
            </div>
            
            <div class="eval-badge-counter" style="border-color: {{ $statusBorder }}; color: {{ $statusColor }};">
                {{ $totalSugar }}g / 50g ({{ $percentage }}%)
            </div>
        </div>

        <p class="eval-desc">
            {{ $statusMessage }}
        </p>

        <!-- Sisa Kuota Bar -->
        <div class="eval-quota-grid" style="border-color: {{ $statusBorder }};">
            <div class="quota-item">
                <span class="quota-label">Gula Terkonsumsi</span>
                <div class="quota-val" style="color: {{ $statusColor }};">{{ $totalSugar }} gram</div>
                <small>≈ {{ round($totalSugar / 4.2, 1) }} sendok teh</small>
            </div>
            <div class="quota-item">
                <span class="quota-label">Sisa Kuota Aman</span>
                <div class="quota-val" style="color: {{ $remainingSugar > 0 ? '#059669' : '#DC2626' }};">{{ $remainingSugar }} gram</div>
                <small>{{ $remainingSugar > 0 ? 'Masih boleh jajan dikit' : 'Stop jajan manis!' }}</small>
            </div>
            <div class="quota-item">
                <span class="quota-label">Kalori dari Gula</span>
                <div class="quota-val" style="color: #334155;">{{ $sugarCalories }} kkal</div>
                <small>Perlu dibakar lewat main!</small>
            </div>
        </div>
    </div>

    <!-- Section 1: Aktif Bergerak (Misi Main Anak) -->
    <div class="kid-section-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
            <div>
                <h3 class="kid-card-header-title">
                    <span>🎮🏃‍♂️</span> Misi Bermain & Bergerak Hari Ini
                </h3>
                <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">
                    @if($sugarCalories > 0)
                        Yuk bakar <strong>{{ $sugarCalories }} kkal</strong> energi manis dari jajananmu dengan aktivitas seru ini:
                    @else
                        Jaga badan tetap lincah dan berenergi dengan aktivitas fisik menyenangkan minimal Kemenkes:
                    @endif
                </p>
            </div>
            <span class="kid-badge-pill">
                ⭐ Level: Pahlawan Aktif
            </span>
        </div>

        <div class="kid-mission-grid">
            <!-- Misi 1 -->
            <div class="kid-mission-box blue">
                <div class="mission-icon">🏃‍♂️</div>
                <h4>Main Lari-Larian</h4>
                <div class="mission-time">≈ {{ $walkingMinutes }} Menit</div>
                <p>Main petak umpet atau kejar-kejaran bareng teman</p>
            </div>

            <!-- Misi 2 -->
            <div class="kid-mission-box green">
                <div class="mission-icon">🚲</div>
                <h4>Gowes Sepeda</h4>
                <div class="mission-time">≈ {{ $cyclingMinutes }} Menit</div>
                <p>Keliling sore naik sepeda bikin kaki dan jantung sehat!</p>
            </div>

            <!-- Misi 3 -->
            <div class="kid-mission-box amber">
                <div class="mission-icon">⚡</div>
                <h4>Lompat Tali / Tangga</h4>
                <div class="mission-time">≈ {{ $stairsMinutes }} Menit</div>
                <p>Gerakan cepat yang ampuh bakar kalori gula!</p>
            </div>
        </div>
    </div>

    <!-- Section 2: Smart Swap (Tukar Minuman Manis ke Minuman Sehat) -->
    <div class="kid-section-card">
        <h3 class="kid-card-header-title">
            <span>🔄✨</span> Tukar Jajanan Pintar (Smart Swap)
        </h3>
        <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 24px;">
            Ganti minuman yang terlalu manis dengan pilihan yang segar tanpa bikin gigi sakit!
        </p>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            @foreach($swaps as $swap)
            <div class="kid-swap-card">
                <!-- Minuman Manis -->
                <div class="swap-side bad">
                    <div class="swap-tag">🛑 JANGAN SERING:</div>
                    <div class="swap-name">{{ $swap['bad_name'] }}</div>
                    <span class="swap-gram">{{ $swap['bad_sugar'] }}g Gula</span>
                </div>

                <!-- Panah -->
                <div class="swap-arrow">
                    <i class="fa-solid fa-arrow-right"></i>
                </div>

                <!-- Minuman Sehat -->
                <div class="swap-side good">
                    <div class="swap-tag">🌟 LEBIH BAIK PILIH:</div>
                    <div class="swap-name">{{ $swap['good_name'] }}</div>
                    <span class="swap-gram">Hanya {{ $swap['good_sugar'] }}g Gula</span>
                </div>

                <!-- Penghematan Gula -->
                <div class="swap-save">
                    <div class="save-badge">
                        Hemat {{ $swap['saved_sugar'] }}g Gula! 🎉
                    </div>
                    <div class="save-benefit">
                        {{ $swap['benefit'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Section 3: Produk Sehat Pilihan dari Katalog (Bebas / Rendah Gula) -->
    <div class="kid-section-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
            <div>
                <h3 class="kid-card-header-title">
                    <span>💧🍎</span> Jajanan Paling Aman (0g - 5g Gula)
                </h3>
                <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">
                    Jajanan dan minuman segar dari katalog yang ramah buat tubuh dan gigi.
                </p>
            </div>
            <a href="{{ route('catalog', ['level' => 'rendah']) }}" class="btn-secondary-link kid-link-btn">
                <i class="fa-solid fa-arrow-right"></i> Lihat Semua yang Aman
            </a>
        </div>

        <div class="kid-healthy-grid">
            @foreach($healthyProducts as $prod)
            <div class="kid-healthy-card">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <div class="healthy-icon-box">
                            <i class="fa-solid {{ $prod['icon'] }}"></i>
                        </div>
                        <span class="healthy-sugar-tag">
                            ⭐ {{ $prod['sugar'] }}g Gula
                        </span>
                    </div>

                    <h4 class="healthy-title">{{ $prod['name'] }}</h4>
                    <span class="healthy-brand">{{ $prod['brand'] }} • {{ $prod['volume'] }}</span>
                </div>

                <div style="display: flex; gap: 8px; align-items: center; margin-top: 14px;">
                    <a href="{{ route('product.detail', $prod['id']) }}" class="healthy-btn-detail">
                        Info
                    </a>
                    <form action="{{ route('catalog.add', $prod['id']) }}" method="POST" style="flex: 1;">
                        @csrf
                        <button type="submit" class="healthy-btn-add">
                            + Catat Ini
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
