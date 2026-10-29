@extends('layouts.app')

@section('title', 'Kalkulator Sendok Gula Jajanan - CekManis')

@section('content')
<div class="calculator-container kid-calculator">
    <!-- Header Section -->
    <div class="page-header kid-page-header">
        <div>
            <h2><span class="header-icon">🧮</span> Kalkulator Sendok Gula</h2>
            <p>Berapa sendok teh gula yang kamu makan atau minum hari ini? Yuk kita hitung bareng-bareng!</p>
        </div>
        <a href="{{ route('catalog') }}" class="btn-secondary-link kid-link-btn">
            <i class="fa-solid fa-cookie-bite"></i> Lihat Jajanan di Katalog
        </a>
    </div>

    <div class="calc-grid kid-calc-grid">
        <!-- Left Side: Input Form -->
        <div class="calc-card input-card kid-card">
            <h3 class="card-section-title">
                <i class="fa-solid fa-pencil" style="color: var(--primary-color);"></i> Masukkan Jajananmu
            </h3>

            <!-- Preset Minuman Populer untuk Anak -->
            <div style="margin-bottom: 24px;">
                <label class="kid-form-label">
                    <span>✨ Pilih Contoh Jajanan Populer:</span>
                </label>
                <div class="preset-buttons kid-preset-buttons">
                    @foreach($presets as $p)
                        <button type="button" class="preset-btn kid-preset-btn" 
                                onclick="applyPreset('{{ $p['name'] }}', {{ $p['amount'] }}, '{{ $p['unit'] }}')">
                            {{ $p['name'] }} ({{ $p['amount'] }}{{ $p['unit'] === 'gram' ? 'g' : ' sdt' }})
                        </button>
                    @endforeach
                </div>
            </div>

            <form action="{{ route('calculator.save') }}" method="POST" id="calc-form">
                @csrf
                <!-- Input Nama -->
                <div class="form-group">
                    <label for="item-name" class="kid-form-label">Nama Makanan atau Minuman</label>
                    <div class="input-with-icon kid-input">
                        <i class="fa-solid fa-mug-hot"></i>
                        <input type="text" id="item-name" name="name" placeholder="Misal: Es Teh Manis, Boba, Susu Cokelat..." required value="Es Teh Manis Warung">
                    </div>
                </div>

                <!-- Satuan Takaran -->
                <div class="form-group">
                    <label class="kid-form-label">Mau Hitung Pakai Apa?</label>
                    <div class="unit-selector kid-unit-selector">
                        <label class="unit-option">
                            <input type="radio" name="unit" value="gram" checked onchange="updateCalculations()">
                            <span class="unit-box kid-unit-box">
                                <span class="unit-emoji">⚖️</span>
                                <strong>Gram (g)</strong>
                                <small>Di label kemasan</small>
                            </span>
                        </label>
                        <label class="unit-option">
                            <input type="radio" name="unit" value="teaspoon" onchange="updateCalculations()">
                            <span class="unit-box kid-unit-box">
                                <span class="unit-emoji">🥄</span>
                                <strong>Sendok Teh (sdt)</strong>
                                <small>1 sdt = 4.2g</small>
                            </span>
                        </label>
                        <label class="unit-option">
                            <input type="radio" name="unit" value="tablespoon" onchange="updateCalculations()">
                            <span class="unit-box kid-unit-box">
                                <span class="unit-emoji">🥄✨</span>
                                <strong>Sendok Makan</strong>
                                <small>1 sdm = 12.5g</small>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Input Jumlah Gula & Slider -->
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <label for="sugar-amount" class="kid-form-label" style="margin-bottom: 0;">Banyaknya Gula</label>
                        <span id="unit-label-badge" class="unit-indicator kid-indicator">gram (g)</span>
                    </div>
                    <div class="input-with-icon kid-input">
                        <i class="fa-solid fa-cubes-stacked"></i>
                        <input type="number" step="0.5" min="0" max="200" id="sugar-amount" name="sugar_amount" value="14" required oninput="syncFromNumber()">
                    </div>
                    <!-- Range Slider Ramah Anak -->
                    <input type="range" min="0" max="60" step="0.5" id="sugar-slider" value="14" class="kid-slider" oninput="syncFromSlider()">
                </div>

                <!-- Jumlah Porsi -->
                <div class="form-group">
                    <label for="portions" class="kid-form-label">Berapa Gelas / Porsi Kamu Minum?</label>
                    <div class="input-with-icon kid-input">
                        <i class="fa-solid fa-layer-group"></i>
                        <select id="portions" name="portions" onchange="updateCalculations()">
                            <option value="1" selected>1 Gelas / Porsi (Standar)</option>
                            <option value="0.5">Setengah Gelas (0.5)</option>
                            <option value="1.5">1 Gelas Besar Jumbo (1.5)</option>
                            <option value="2">2 Gelas / Porsi</option>
                            <option value="3">3 Gelas / Porsi</option>
                        </select>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <button type="submit" class="btn-save-calc kid-btn-main">
                    <i class="fa-solid fa-bookmark"></i> + Simpan ke Buku Jajanku Hari Ini!
                </button>
            </form>
        </div>

        <!-- Right Side: Live Interactive Preview (Visual Sendok Ramah Anak) -->
        <div class="calc-card preview-card kid-card">
            <h3 class="card-section-title">
                <i class="fa-solid fa-chart-pie" style="color: #059669;"></i> Hasil Pengecekan
            </h3>

            <!-- Item Name Preview -->
            <div style="text-align: center; margin-bottom: 20px;">
                <h4 id="preview-title" class="kid-preview-title">Es Teh Manis Warung</h4>
                <span id="preview-portion-text" class="kid-portion-badge">1 Porsi Standar</span>
            </div>

            <!-- Sugar Meter Big Display -->
            <div class="preview-meter-box kid-meter-box" id="preview-meter-box">
                <div id="preview-badge" class="preview-badge badge-medium">
                    <span id="preview-badge-emoji">👍</span>
                    <span id="preview-badge-text">GULA SEDANG</span>
                </div>
                <div class="preview-sugar-number" id="preview-sugar-number">
                    14.0<span style="font-size: 26px; font-weight: 700;"> g</span>
                </div>
                
                <!-- Sendok Ekuivalen -->
                <div class="preview-teaspoon kid-spoon-counter" id="preview-teaspoon">
                    🥄 Setara ≈ <strong>3.3</strong> Sendok Teh Gula Pasir
                </div>

                <!-- Visual Spoon Icons Box -->
                <div id="visual-spoons" class="visual-spoons-box" title="Visualisasi banyaknya sendok gula">
                    🥄 🥄 🥄
                </div>
            </div>

            <!-- Limit Progress Bar Gamified -->
            <div class="progress-section kid-progress-section">
                <div style="display: flex; justify-content: space-between; font-size: 14px; font-weight: 800; margin-bottom: 8px;">
                    <span style="color: var(--text-dark);">Batas Gula Harian (50g)</span>
                    <span id="preview-percentage" style="color: #D97706;">28%</span>
                </div>
                <div class="custom-progress-track kid-track">
                    <div class="custom-progress-bar" id="custom-progress-bar" style="width: 28%; background: #F59E0B;"></div>
                </div>
                <div id="preview-limit-warning" class="limit-alert kid-alert" style="display: none;">
                    <span>🚨</span> Wah, jajanan ini sudah melebihi batas gula harianmu!
                </div>
            </div>

            <!-- Health Breakdown Grid -->
            <div class="health-breakdown kid-breakdown">
                <div class="health-pill kid-stat-pill">
                    <div class="stat-icon-wrapper fire">🔥</div>
                    <div>
                        <small>Kalori Gula</small>
                        <strong id="preview-calories">56 kkal</strong>
                    </div>
                </div>
                <div class="health-pill kid-stat-pill">
                    <div class="stat-icon-wrapper run">🚲</div>
                    <div>
                        <small>Misi Gerak</small>
                        <strong id="preview-burn-time">≈ 14 mnt main</strong>
                    </div>
                </div>
            </div>

            <!-- Advice Box -->
            <div class="advice-card kid-advice-box" id="advice-card">
                <span class="advice-icon">💡</span>
                <p id="preview-advice-text">
                    Kandungan gula masih wajar! Jangan lupa minum 1 gelas air putih setelahnya ya biar gigimu tetap bersih.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function getSelectedUnit() {
        const radios = document.getElementsByName('unit');
        for (let r of radios) {
            if (r.checked) return r.value;
        }
        return 'gram';
    }

    function syncFromNumber() {
        const numInput = document.getElementById('sugar-amount');
        const slider = document.getElementById('sugar-slider');
        slider.value = numInput.value;
        updateCalculations();
    }

    function syncFromSlider() {
        const numInput = document.getElementById('sugar-amount');
        const slider = document.getElementById('sugar-slider');
        numInput.value = slider.value;
        updateCalculations();
    }

    function applyPreset(name, amount, unit) {
        document.getElementById('item-name').value = name;
        document.getElementById('sugar-amount').value = amount;
        document.getElementById('sugar-slider').value = amount;

        const radios = document.getElementsByName('unit');
        for (let r of radios) {
            r.checked = (r.value === unit);
        }

        document.getElementById('portions').value = "1";
        updateCalculations();
    }

    function updateCalculations() {
        const nameInput = document.getElementById('item-name').value || 'Jajanan Kamu';
        const amount = parseFloat(document.getElementById('sugar-amount').value) || 0;
        const portions = parseFloat(document.getElementById('portions').value) || 1;
        const unit = getSelectedUnit();

        // Label badge update
        const unitLabelBadge = document.getElementById('unit-label-badge');
        if (unit === 'teaspoon') {
            unitLabelBadge.textContent = 'sendok teh (sdt)';
        } else if (unit === 'tablespoon') {
            unitLabelBadge.textContent = 'sendok makan (sdm)';
        } else {
            unitLabelBadge.textContent = 'gram (g)';
        }

        // Calculate sugar in grams
        let gramsPerPortion = amount;
        if (unit === 'teaspoon') {
            gramsPerPortion = amount * 4.2;
        } else if (unit === 'tablespoon') {
            gramsPerPortion = amount * 12.5;
        }

        const totalGrams = gramsPerPortion * portions;
        const teaspoons = (totalGrams / 4.2).toFixed(1);
        const calories = Math.round(totalGrams * 4);
        const walkMinutes = Math.round(calories / 4);
        const percentage = Math.round((totalGrams / 50) * 100);

        // Visual spoons generator
        const spoonCount = Math.min(Math.round(totalGrams / 4.2), 12);
        let spoonsIcons = '';
        for (let i = 0; i < spoonCount; i++) {
            spoonsIcons += '🥄 ';
        }
        if (spoonCount === 0) spoonsIcons = '💧 (0 Sendok Gula / Bebas Gula)';
        document.getElementById('visual-spoons').textContent = spoonsIcons;

        // Update preview headers
        document.getElementById('preview-title').textContent = nameInput;
        document.getElementById('preview-portion-text').textContent = portions === 1 ? '1 Porsi Standar' : portions + ' Porsi Dikonsumsi';
        document.getElementById('preview-sugar-number').innerHTML = totalGrams.toFixed(1) + '<span style="font-size: 26px; font-weight: 700;"> g</span>';
        document.getElementById('preview-teaspoon').innerHTML = '🥄 Setara ≈ <strong>' + teaspoons + '</strong> Sendok Teh Gula';
        document.getElementById('preview-calories').textContent = calories + ' kkal';
        document.getElementById('preview-burn-time').textContent = '≈ ' + walkMinutes + ' mnt aktif';

        // Update progress bar
        const progressBar = document.getElementById('custom-progress-bar');
        const percentageLabel = document.getElementById('preview-percentage');
        const limitWarning = document.getElementById('preview-limit-warning');
        const meterBox = document.getElementById('preview-meter-box');
        const badge = document.getElementById('preview-badge');
        const badgeText = document.getElementById('preview-badge-text');
        const badgeEmoji = document.getElementById('preview-badge-emoji');
        const adviceText = document.getElementById('preview-advice-text');

        percentageLabel.textContent = percentage + '% / 50g';
        progressBar.style.width = Math.min(percentage, 100) + '%';

        // Styling based on level
        if (totalGrams <= 5) {
            // RENDAH
            badge.className = 'preview-badge badge-low';
            badgeText.textContent = 'SUPER AMAN';
            badgeEmoji.textContent = '🌟';
            progressBar.style.background = '#10B981';
            percentageLabel.style.color = '#059669';
            limitWarning.style.display = 'none';
            meterBox.style.borderColor = '#A7F3D0';
            meterBox.style.background = '#ECFDF5';
            adviceText.textContent = 'Hebat! Jajanan ini rendah gula. Sangat bagus untuk kesehatan gigi dan tubuhmu!';
        } else if (totalGrams <= 15) {
            // SEDANG
            badge.className = 'preview-badge badge-medium';
            badgeText.textContent = 'MANIS SEDANG';
            badgeEmoji.textContent = '👍';
            progressBar.style.background = '#F59E0B';
            percentageLabel.style.color = '#D97706';
            limitWarning.style.display = 'none';
            meterBox.style.borderColor = '#FDE68A';
            meterBox.style.background = '#FFFBEB';
            adviceText.textContent = 'Kandungan gula masih wajar! Jangan lupa minum 1 gelas air putih setelahnya ya biar gigimu tetap bersih.';
        } else {
            // TINGGI
            badge.className = 'preview-badge badge-high';
            badgeText.textContent = 'MANIS BANGET!';
            badgeEmoji.textContent = '🚨';
            progressBar.style.background = '#EF4444';
            percentageLabel.style.color = '#DC2626';
            limitWarning.style.display = percentage > 100 ? 'flex' : 'none';
            meterBox.style.borderColor = '#FECACA';
            meterBox.style.background = '#FEF2F2';
            adviceText.textContent = 'Awas! Jajanan ini manis sekali. Jangan jajan manis lagi ya hari ini biar nggak gampang sakit gigi atau lemas!';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateCalculations();
        document.getElementById('item-name').addEventListener('input', function() {
            document.getElementById('preview-title').textContent = this.value || 'Jajanan Kamu';
        });
    });
</script>
@endsection
