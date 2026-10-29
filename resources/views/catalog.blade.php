@extends('layouts.app')

@section('title', 'Katalog Jajanan & Minuman Seru - CekManis')

@section('content')
<div class="catalog-container kid-catalog">
    <!-- Header Section -->
    <div class="page-header kid-page-header">
        <div>
            <h2><span class="header-icon">🧃</span> Katalog Jajanan & Minuman Seru</h2>
            <p>Yuk cari tahu berapa sendok gula di jajanan kesukaanmu biar nggak kelebihan gula!</p>
        </div>
        <a href="{{ route('calculator') }}" class="btn-secondary-link kid-link-btn">
            <i class="fa-solid fa-calculator"></i> Hitung Sendok Gula Sendiri
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="catalog-toolbar kid-toolbar">
        <form action="{{ route('catalog') }}" method="GET" class="filter-form">
            <div class="toolbar-search kid-search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="q" value="{{ $keyword }}" placeholder="Cari jajanan kesukaanmu (contoh: Milo, Oreo, Ultra Milk, Teh Pucuk)...">
                @if($keyword)
                    <a href="{{ route('catalog', ['category' => $selectedCategory, 'level' => $selectedLevel]) }}" class="clear-search" title="Hapus pencarian">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
                <button type="submit" class="kid-btn-small">Cari! 🚀</button>
            </div>

            <!-- Kategori Chips Warna-Warni -->
            <div class="category-chips kid-category-chips">
                @php
                    $categoryEmojis = [
                        'all' => '🌈',
                        'teh' => '🍵',
                        'susu' => '🥛',
                        'jus' => '🍓',
                        'isotonik' => '⚡',
                        'soda' => '🥤',
                        'air' => '💧',
                        'snack' => '🍪',
                    ];
                @endphp
                @foreach($categories as $key => $label)
                    <a href="{{ route('catalog', ['category' => $key, 'q' => $keyword, 'level' => $selectedLevel]) }}" 
                       class="chip kid-chip {{ $selectedCategory === $key ? 'active' : '' }}">
                        <span class="chip-emoji">{{ $categoryEmojis[$key] ?? '✨' }}</span> {{ $label }}
                    </a>
                @endforeach
            </div>

            <!-- Filter Level Gula Ramah Anak -->
            <div class="level-filters kid-level-filters">
                <span class="filter-label">🏷️ Level Manis:</span>
                <a href="{{ route('catalog', ['level' => 'all', 'category' => $selectedCategory, 'q' => $keyword]) }}" 
                   class="level-btn {{ $selectedLevel === 'all' ? 'active' : '' }}">Semua Level</a>
                <a href="{{ route('catalog', ['level' => 'rendah', 'category' => $selectedCategory, 'q' => $keyword]) }}" 
                   class="level-btn level-rendah {{ $selectedLevel === 'rendah' ? 'active' : '' }}">
                    <span>🌟</span> Aman (≤ 5g)
                </a>
                <a href="{{ route('catalog', ['level' => 'sedang', 'category' => $selectedCategory, 'q' => $keyword]) }}" 
                   class="level-btn level-sedang {{ $selectedLevel === 'sedang' ? 'active' : '' }}">
                    <span>👍</span> Sedang (6-15g)
                </a>
                <a href="{{ route('catalog', ['level' => 'tinggi', 'category' => $selectedCategory, 'q' => $keyword]) }}" 
                   class="level-btn level-tinggi {{ $selectedLevel === 'tinggi' ? 'active' : '' }}">
                    <span>🚨</span> Manis Banget (> 15g)
                </a>
            </div>
        </form>
    </div>

    <!-- Info Bar Hasil -->
    <div class="results-info">
        <span class="results-badge">🎉 Ada <strong>{{ $totalCount }}</strong> jajanan ditemukan!</span>
        @if($keyword || $selectedCategory !== 'all' || $selectedLevel !== 'all')
            <a href="{{ route('catalog') }}" class="reset-all-filters">
                <i class="fa-solid fa-rotate-left"></i> Tampilkan Semua
            </a>
        @endif
    </div>

    <!-- Product Grid -->
    @if(count($products) > 0)
        <div class="product-grid kid-product-grid">
            @foreach($products as $product)
                @php
                    $isHigh = $product['sugar_level'] === 'TINGGI';
                    $isMedium = $product['sugar_level'] === 'SEDANG';
                    $badgeClass = $isHigh ? 'badge-high' : ($isMedium ? 'badge-medium' : 'badge-low');
                    $badgeLabel = $isHigh ? '🚨 Manis Banget' : ($isMedium ? '👍 Manis Sedang' : '🌟 Super Sehat');
                @endphp
                <div class="product-card kid-card">
                    <!-- Top Card: Icon + Category Badge -->
                    <div class="card-header">
                        <div class="product-icon kid-icon-box">
                            <i class="fa-solid {{ $product['icon'] }}"></i>
                        </div>
                        <span class="sugar-badge kid-badge {{ $badgeClass }}">
                            {{ $badgeLabel }}
                        </span>
                    </div>

                    <!-- Middle Card: Product Info -->
                    <div class="card-body">
                        <div class="brand-tag">🏷️ {{ $product['brand'] }} • {{ $product['volume'] }}</div>
                        <h4 class="product-title">{{ $product['name'] }}</h4>
                        <p class="product-desc">{{ $product['description'] }}</p>

                        <!-- Sugar Meter Box -->
                        <div class="sugar-meter-box kid-meter {{ $badgeClass }}">
                            <div class="sugar-main">
                                <span class="sugar-gram">{{ $product['sugar'] }}g</span>
                                <span class="sugar-label">Gula per porsi</span>
                            </div>
                            <div class="sugar-teaspoon">
                                <span>🥄 ≈ <strong>{{ $product['sugar_teaspoons'] }}</strong> sdt gula</span>
                            </div>
                        </div>

                        <!-- Nutrition Snippet -->
                        <div class="nutrition-snippet kid-snippet">
                            <span>⚡ Energi: <strong>{{ $product['energy'] }}</strong></span>
                            <span>🌾 Karbo: <strong>{{ $product['carbs'] }}</strong></span>
                        </div>
                    </div>

                    <!-- Bottom Card: Actions -->
                    <div class="card-footer">
                        <a href="{{ route('product.detail', $product['id']) }}" class="btn-detail kid-btn-detail" title="Lihat Nilai Gizi Lengkap">
                            <i class="fa-solid fa-circle-info"></i> Info
                        </a>
                        <form action="{{ route('catalog.add', $product['id']) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" class="btn-add-history kid-btn-add" title="Catat jajanan ini ke riwayat">
                                <i class="fa-solid fa-plus-circle"></i> + Catat Jajan Ini
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state kid-empty">
            <div class="empty-icon">🔍🍪</div>
            <h3>Yah, Jajanan Belum Ditemukan!</h3>
            <p>Tidak ada jajanan dengan kata kunci "<strong>{{ $keyword }}</strong>". Mau coba cari jajanan lain?</p>
            <div style="display: flex; gap: 14px; justify-content: center; margin-top: 20px;">
                <a href="{{ route('catalog') }}" class="btn-back-catalog">
                    <i class="fa-solid fa-arrow-rotate-left"></i> Lihat Semua Jajanan
                </a>
                <a href="{{ route('calculator') }}" class="btn-use-calc">
                    <i class="fa-solid fa-calculator"></i> Hitung di Kalkulator
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
