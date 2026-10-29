@extends('layouts.app')

@section('title', $product['name'] . ' - Info Gula Jajanan CekManis')

@section('content')
<div class="product-detail-container" style="max-width: 780px; margin: 0 auto;">
    <a href="{{ route('catalog') }}" class="kid-back-link">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog Jajanan
    </a>

    @php
        $isHigh = ($product['category'] ?? $product['sugar_level'] ?? '') === 'TINGGI';
        $isMedium = ($product['category'] ?? $product['sugar_level'] ?? '') === 'SEDANG';
        $themeColor = $isHigh ? '#EF4444' : ($isMedium ? '#F59E0B' : '#10B981');
        $themeBg = $isHigh ? '#FEF2F2' : ($isMedium ? '#FFFBEB' : '#ECFDF5');
        $themeBorder = $isHigh ? '#FECACA' : ($isMedium ? '#FDE68A' : '#A7F3D0');
        $themeEmoji = $isHigh ? '🚨' : ($isMedium ? '👍' : '🌟');
        $themeLabel = $isHigh ? 'Manis Banget! (Awas Kebanyakan)' : ($isMedium ? 'Manis Sedang (Cukup Wajar)' : 'Super Aman & Sehat!');
    @endphp

    <!-- Kartu Informasi Utama -->
    <div class="kid-card" style="padding: 36px 30px; text-align: center; margin-bottom: 24px; border: 2px solid {{ $themeBorder }};">
        <div class="kid-detail-icon-box" style="background: {{ $themeBg }}; color: {{ $themeColor }};">
            <i class="fa-solid {{ $product['icon'] ?? 'fa-cookie-bite' }}"></i>
        </div>
        
        <h2 style="font-size: 26px; font-weight: 900; color: var(--text-dark); margin-bottom: 6px;">{{ $product['name'] }}</h2>
        <div style="color: var(--text-muted); font-size: 14px; margin-bottom: 16px; font-weight: 600;">
            🏷️ {{ $product['brand'] ?? 'Jajanan' }} • {{ $product['volume'] ?? '1 Sajian' }}
        </div>

        <div style="display: inline-flex; align-items: center; gap: 8px; background: {{ $themeBg }}; border: 2px solid {{ $themeBorder }}; color: {{ $themeColor }}; padding: 6px 18px; border-radius: 20px; font-weight: 800; font-size: 14px; margin-bottom: 24px;">
            <span>{{ $themeEmoji }}</span> Kategori: {{ $themeLabel }}
        </div>
        
        <div style="font-size: 60px; font-weight: 900; color: {{ $themeColor }}; line-height: 1; letter-spacing: -1px;">
            {{ $product['sugar'] }}g
        </div>
        <p style="color: var(--text-dark); font-size: 16px; margin-top: 8px; margin-bottom: 24px; font-weight: 700;">
            Kandungan gula per sajian (🥄 setara <strong>{{ $product['sugar_teaspoons'] }}</strong> sendok teh gula pasir)
        </p>

        <!-- Visual Spoons -->
        <div class="visual-spoons-box" style="margin-bottom: 20px;">
            @php
                $spoons = min(round($product['sugar'] / 4.2), 12);
            @endphp
            @for($i = 0; $i < $spoons; $i++)
                🥄
            @endfor
            @if($spoons == 0)
                💧 0 Sendok Gula (Bebas Gula!)
            @endif
        </div>

        <div style="background: {{ $themeBg }}; border: 1px solid {{ $themeBorder }}; padding: 16px 20px; border-radius: 18px; font-size: 14px; color: var(--text-dark); text-align: left; display: flex; gap: 14px; align-items: center;">
            <span style="font-size: 24px;">💡</span>
            <span>
                Minuman/makanan ini sudah menghabiskan <strong>{{ $product['percentage'] }}%</strong> dari jatah gula maksimal harianmu (50 gram).
            </span>
        </div>
    </div>

    <!-- Informasi Nilai Gizi -->
    <div class="kid-card" style="padding: 28px; margin-bottom: 24px;">
        <h3 style="font-size: 18px; margin-bottom: 20px; color: var(--primary-color); font-weight: 900; display: flex; align-items: center; gap: 10px;">
            <span>📋</span> Rincian Kandungan Gizi Jajanan
        </h3>
        
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #F1F5F9; padding-bottom: 14px; margin-bottom: 14px; font-size: 15px; font-weight: 700;">
            <span style="color: var(--text-muted);"><i class="fa-solid fa-bolt" style="width: 22px; color: #F59E0B;"></i> Energi Total</span>
            <strong style="color: var(--text-dark);">{{ $product['energy'] }}</strong>
        </div>
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #F1F5F9; padding-bottom: 14px; margin-bottom: 14px; font-size: 15px; font-weight: 700;">
            <span style="color: var(--text-muted);"><i class="fa-solid fa-wheat-awn" style="width: 22px; color: #10B981;"></i> Karbohidrat</span>
            <strong style="color: var(--text-dark);">{{ $product['carbs'] }}</strong>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: 800;">
            <span style="color: var(--text-muted);"><i class="fa-solid fa-cubes-stacked" style="width: 22px; color: {{ $themeColor }};"></i> Gula Pasir / Sukrosa</span>
            <strong style="color: {{ $themeColor }}; font-size: 18px;">{{ $product['sugar'] }} g</strong>
        </div>
    </div>

    <!-- Tombol Simpan Riwayat -->
    <form action="{{ route('product.save', ['id' => $product['id']]) }}" method="POST">
        @csrf
        <button type="submit" class="kid-btn-main" style="width: 100%; padding: 18px; font-size: 17px;">
            <i class="fa-solid fa-plus-circle"></i> + Masukkan ke Buku Jajanku Hari Ini!
        </button>
    </form>
</div>
@endsection
