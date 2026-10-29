@extends('layouts.app')

@section('title', 'Buku Harian Jajanku - CekManis')

@section('content')
<div class="page-card kid-page-card" style="padding: 36px; max-width: 920px;">
    <!-- Alert Notifikasi Sukses Ceria -->
    @if(session('success'))
    <div class="kid-success-alert">
        <span style="font-size: 22px;">🎉</span>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Header & Action Buttons -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 class="kid-section-title">
                <span>📖</span> Buku Harian Jajanku
            </h2>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Catatan jajanan manis yang sudah kamu santap hari ini. Yuk cek apakah masih aman!</p>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="button" onclick="toggleQuickAdd()" class="kid-btn-small" style="background: var(--primary-color); color: white;">
                <i class="fa-solid fa-plus-circle"></i> + Catat Cepat
            </button>
            <form action="{{ route('history.seed') }}" method="POST">
                @csrf
                <button type="submit" class="kid-btn-small" style="background: #F1F5F9; color: #334155;" title="Muat data contoh">
                    <i class="fa-solid fa-rotate"></i> Contoh Jajanan
                </button>
            </form>
            @if(count($histories) > 0)
            <form action="{{ route('history.clear') }}" method="POST" onsubmit="return confirm('Apakah kamu ingin mengosongkan buku jajan hari ini?');">
                @csrf
                <button type="submit" class="kid-btn-small" style="background: #FEF2F2; color: #DC2626; border-color: #FECACA;">
                    <i class="fa-solid fa-trash-can"></i> Kosongkan
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Quick Add Form (Collapsible) -->
    <div id="quick-add-panel" class="kid-quick-panel" style="display: none;">
        <h4 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <span>⚡</span> Tambah Jajanan Baru dengan Cepat
        </h4>
        <form action="{{ route('history.quick_add') }}" method="POST" style="display: grid; grid-template-columns: 2fr 1.2fr auto; gap: 12px; align-items: flex-end;">
            @csrf
            <div>
                <label style="display: block; font-size: 13px; font-weight: 700; color: var(--text-muted); margin-bottom: 6px;">Nama Makanan / Minuman</label>
                <input type="text" name="name" placeholder="Misal: Es Krim Cokelat, Boba, Susu Kotak..." required style="width: 100%; padding: 11px 16px; border: 2px solid #CBD5E1; border-radius: 12px; font-size: 14px; font-family: inherit; outline: none; background: white;">
            </div>
            <div>
                <label style="display: block; font-size: 13px; font-weight: 700; color: var(--text-muted); margin-bottom: 6px;">Gula (Gram)</label>
                <input type="number" step="0.5" min="0" max="250" name="sugar" placeholder="Misal: 15" required style="width: 100%; padding: 11px 16px; border: 2px solid #CBD5E1; border-radius: 12px; font-size: 14px; font-family: inherit; outline: none; background: white;">
            </div>
            <button type="submit" class="kid-btn-small" style="background: #10B981; color: white; height: 44px; padding: 0 20px;">
                <i class="fa-solid fa-check"></i> Simpan
            </button>
        </form>
    </div>

    <!-- Total Gula Harian Card Gamified -->
    @php
        $isDanger = $percentage > 100;
        $isWarning = $percentage >= 50 && $percentage <= 100;
        $cardBg = $isDanger ? '#FEF2F2' : ($isWarning ? '#FFFBEB' : '#ECFDF5');
        $cardBorder = $isDanger ? '#FECACA' : ($isWarning ? '#FDE68A' : '#A7F3D0');
        $mainColor = $isDanger ? '#EF4444' : ($isWarning ? '#D97706' : '#059669');
        $progressColor = $isDanger ? '#EF4444' : ($isWarning ? '#F59E0B' : '#10B981');
    @endphp

    <div style="background: {{ $cardBg }}; border: 2px solid {{ $cardBorder }}; border-radius: 26px; padding: 28px; margin-bottom: 32px; box-shadow: 0 8px 24px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
            <span style="font-size: 13px; font-weight: 800; color: {{ $mainColor }}; text-transform: uppercase; letter-spacing: 0.5px; background: white; padding: 4px 12px; border-radius: 20px; border: 1px solid {{ $cardBorder }};">
                🏆 METERAN GULA HARIAN
            </span>
            <span style="font-size: 15px; font-weight: 900; color: {{ $mainColor }};">
                {{ $percentage }}% dari Batas 50g
            </span>
        </div>
        
        <div style="display: flex; align-items: baseline; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
            <span style="font-size: 54px; font-weight: 900; color: {{ $mainColor }}; line-height: 1;">
                {{ $total }}g
            </span>
            <span style="font-size: 16px; color: var(--text-muted); font-weight: 700;">/ 50g (Batas Maksimal)</span>
            <span style="font-size: 15px; color: #475569; font-weight: 800; margin-left: 8px;">
                🥄 ≈ {{ round($total / 4.2, 1) }} Sendok Teh Gula
            </span>
        </div>
        
        <!-- Progress Bar Bubbly -->
        <div class="custom-progress-track kid-track" style="height: 16px; margin-top: 14px;">
            <div class="custom-progress-bar" style="width: {{ min($percentage, 100) }}%; height: 100%; background: {{ $progressColor }};"></div>
        </div>
        
        @if($isDanger)
        <div class="kid-message-box danger">
            <span style="font-size: 24px;">🚨</span>
            <div>
                <strong>Awas Bahaya!</strong> Kamu sudah melewati batas 50 gram gula hari ini. Jangan jajan manis lagi ya, dan perbanyak minum air putih biar ginjal dan gigimu sehat!
            </div>
        </div>
        @elseif($isWarning)
        <div class="kid-message-box warning">
            <span style="font-size: 24px;">⚠️</span>
            <div>
                <strong>Hati-hati Sobat!</strong> Gulamu sudah mencapai {{ $percentage }}% dari batas harian. Sisa jatah amanmu tinggal {{ max(0, 50 - $total) }}g lagi.
            </div>
        </div>
        @else
        <div class="kid-message-box success">
            <span style="font-size: 24px;">🌟</span>
            <div>
                <strong>Keren Banget!</strong> Asupan gulamu masih sangat aman dan terkontrol. Gigi tetap kuat dan tubuh tetap bugar buat main dan belajar!
            </div>
        </div>
        @endif

        <!-- Mini Stats Breakdown Gamified -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 18px;">
            <div class="kid-stat-mini">
                <span class="stat-emoji">🌟</span>
                <div>
                    <small>Jajanan Aman</small>
                    <strong style="color: #059669;">{{ $lowCount }} Item</strong>
                </div>
            </div>
            <div class="kid-stat-mini">
                <span class="stat-emoji">👍</span>
                <div>
                    <small>Manis Sedang</small>
                    <strong style="color: #D97706;">{{ $medCount }} Item</strong>
                </div>
            </div>
            <div class="kid-stat-mini">
                <span class="stat-emoji">🚨</span>
                <div>
                    <small>Manis Tinggi</small>
                    <strong style="color: #DC2626;">{{ $highCount }} Item</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Subheader List -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark);">
            <span>🥤</span> Daftar Jajanan yang Kamu Santap
        </h3>
        <span style="font-size: 13px; color: var(--text-muted); font-weight: 700;">Ada {{ count($histories) }} Jajanan</span>
    </div>

    <!-- List Items -->
    <div style="display: flex; flex-direction: column; gap: 12px;">
        @forelse($histories as $index => $h)
        @php
            $itemSugar = (float) ($h['sugar'] ?? 0);
            $itemLevel = $h['level'] ?? ($itemSugar > 15 ? 'TINGGI' : ($itemSugar >= 6 ? 'SEDANG' : 'RENDAH'));
            $itemBg = $itemLevel === 'TINGGI' ? '#FEF2F2' : ($itemLevel === 'SEDANG' ? '#FFFBEB' : '#ECFDF5');
            $itemColor = $itemLevel === 'TINGGI' ? '#DC2626' : ($itemLevel === 'SEDANG' ? '#D97706' : '#059669');
            $itemEmoji = $itemLevel === 'TINGGI' ? '🚨' : ($itemLevel === 'SEDANG' ? '👍' : '🌟');
            $itemId = $h['id'] ?? ('item_' . $index);
        @endphp
        <div class="kid-history-item">
            <!-- Left: Info -->
            <div style="display: flex; align-items: center; gap: 16px;">
                <div class="kid-item-icon {{ ($h['source'] ?? '') === 'Kalkulator Manual' ? 'green' : 'blue' }}">
                    <i class="fa-solid {{ ($h['source'] ?? '') === 'Kalkulator Manual' ? 'fa-calculator' : 'fa-cookie-bite' }}"></i>
                </div>
                <div>
                    <h4 class="kid-item-title">{{ $h['name'] }}</h4>
                    <div style="display: flex; gap: 10px; font-size: 12px; color: var(--text-muted); align-items: center; flex-wrap: wrap;">
                        <span><i class="fa-regular fa-clock"></i> {{ $h['time'] ?? 'Hari ini' }}</span>
                        <span>•</span>
                        <span class="source-tag">{{ $h['source'] ?? 'Katalog' }}</span>
                        <span>•</span>
                        <span style="color: {{ $itemColor }}; font-weight: 800;">
                            {{ $itemEmoji }} {{ $itemLevel }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right: Sugar count + Delete button -->
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="text-align: right;">
                    <div class="kid-sugar-pill" style="background: {{ $itemBg }}; color: {{ $itemColor }};">
                        +{{ $itemSugar }}g
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px; font-weight: 700;">
                        🥄 ≈ {{ round($itemSugar / 4.2, 1) }} sdt
                    </div>
                </div>

                <!-- Form Hapus Satuan -->
                <form action="{{ route('history.delete', $itemId) }}" method="POST" onsubmit="return confirm('Hapus jajanan ini dari buku jajan?');">
                    @csrf
                    <button type="submit" class="kid-delete-btn" title="Hapus catatan ini">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="empty-state kid-empty">
            <div class="empty-icon">🎒✨</div>
            <h3>Buku Jajanku Masih Bersih!</h3>
            <p>Belum ada catatan jajanan manis untuk hari ini. Yuk mulai catat jajananmu dari Katalog atau Kalkulator!</p>
            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('catalog') }}" class="kid-btn-small" style="background: var(--primary-color); color: white;">
                    <i class="fa-solid fa-cookie-bite"></i> Buka Katalog
                </a>
                <a href="{{ route('calculator') }}" class="kid-btn-small" style="background: #10B981; color: white;">
                    <i class="fa-solid fa-calculator"></i> Buka Kalkulator
                </a>
                <form action="{{ route('history.seed') }}" method="POST">
                    @csrf
                    <button type="submit" class="kid-btn-small" style="background: #E2E8F0; color: #334155;">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> Muat Data Contoh
                    </button>
                </form>
            </div>
        </div>
        @endforelse
    </div>
</div>

<script>
    function toggleQuickAdd() {
        const panel = document.getElementById('quick-add-panel');
        if (panel.style.display === 'none' || panel.style.display === '') {
            panel.style.display = 'block';
        } else {
            panel.style.display = 'none';
        }
    }
</script>
@endsection
