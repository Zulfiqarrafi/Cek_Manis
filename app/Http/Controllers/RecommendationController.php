<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Support\Facades\Session;

class RecommendationController extends Controller
{
    public function index()
    {
        // Inisialisasi data contoh jika session baru dibuka
        if (! Session::has('histories')) {
            $defaultHistories = [
                [
                    'id' => 'demo_1',
                    'name' => 'Teh Botol Sosro Kotak (250 ml)',
                    'sugar' => 22,
                    'time' => '09:30',
                    'source' => 'Katalog',
                    'level' => 'TINGGI',
                ],
                [
                    'id' => 'demo_2',
                    'name' => 'Biskuit Oreo Original (3 Keping)',
                    'sugar' => 11,
                    'time' => '13:15',
                    'source' => 'Katalog',
                    'level' => 'SEDANG',
                ],
            ];
            Session::put('histories', $defaultHistories);
            Session::save();
        }

        // Ambil data riwayat konsumsi hari ini
        $histories = Session::get('histories', []);

        $totalSugar = 0;
        foreach ($histories as $h) {
            $totalSugar += (float) ($h['sugar'] ?? 0);
        }
        $totalSugar = round($totalSugar, 1);
        $percentage = round(($totalSugar / 50) * 100);
        $remainingSugar = max(0, round(50 - $totalSugar, 1));
        $sugarCalories = round($totalSugar * 4);

        // Evaluasi Status Pengguna
        if ($totalSugar > 50) {
            $status = 'BAHAYA';
            $statusTitle = 'Peringatan: Melebihi Batas Aman!';
            $statusBadge = 'badge-danger';
            $statusColor = '#EF4444';
            $statusBg = '#FEF2F2';
            $statusBorder = '#FECACA';
            $statusMessage = "Asupan gula Anda hari ini telah mencapai {$totalSugar}g ({$percentage}% dari batas aman harian 50g). Segera lakukan langkah pencegahan untuk menjaga kesehatan metabolisme tubuh!";
        } elseif ($totalSugar >= 25) {
            $status = 'WASPADA';
            $statusTitle = 'Waspada: Mendekati Batas Maksimal';
            $statusBadge = 'badge-warning';
            $statusColor = '#D97706';
            $statusBg = '#FFFBEB';
            $statusBorder = '#FDE68A';
            $statusMessage = "Konsumsi gula Anda sudah mencapai {$totalSugar}g ({$percentage}%). Sisa kuota gula aman Anda hari ini tinggal {$remainingSugar}g.";
        } else {
            $status = 'AMAN';
            $statusTitle = 'Hebat: Asupan Gula Masih Sangat Aman!';
            $statusBadge = 'badge-success';
            $statusColor = '#059669';
            $statusBg = '#ECFDF5';
            $statusBorder = '#A7F3D0';
            $statusMessage = "Konsumsi gula Anda baru {$totalSugar}g ({$percentage}% dari batas 50g). Pertahankan kebiasaan sehat ini untuk menjaga kebugaran tubuh harian.";
        }

        // Rekomendasi Pintar "Smart Swap" (Tukar Minuman Manis ke Minuman Sehat)
        $swaps = [
            [
                'bad_name' => 'Boba Brown Sugar Milk',
                'bad_sugar' => 38,
                'good_name' => 'Air Kelapa Murni / Hydro Coco',
                'good_sugar' => 11,
                'saved_sugar' => 27,
                'icon' => 'fa-glass-water',
                'benefit' => 'Menghemat 27g gula (setara ~6.5 sendok teh gula pasir).',
            ],
            [
                'bad_name' => 'Teh Kemasan Botol Manis',
                'bad_sugar' => 22,
                'good_name' => 'Teh Hijau Tawar Dingin / Teh Melati No Sugar',
                'good_sugar' => 0,
                'saved_sugar' => 22,
                'icon' => 'fa-leaf',
                'benefit' => '0 gram gula & 0 kalori, kaya antioksidan polifenol.',
            ],
            [
                'bad_name' => 'Kopi Susu Gula Aren Manis',
                'bad_sugar' => 24,
                'good_name' => 'Kopi Hitam / Americano / Susu Less Sugar',
                'good_sugar' => 4,
                'saved_sugar' => 20,
                'icon' => 'fa-mug-hot',
                'benefit' => 'Tetap menikmati aroma kopi tanpa lonjakan gula darah mendadak.',
            ],
            [
                'bad_name' => 'Minuman Bersoda Kaleng',
                'bad_sugar' => 35,
                'good_name' => 'Air Es Jeruk Nipis / Infused Water Lemon',
                'good_sugar' => 0,
                'saved_sugar' => 35,
                'icon' => 'fa-lemon',
                'benefit' => 'Sensasi segar alami tanpa tambahan sukrosa sintetis.',
            ],
        ];

        // Produk Sehat Pilihan (Rendah / Bebas Gula) dari Katalog
        $healthyProducts = ProductService::lowSugarProducts(6);

        // Rekomendasi Bergerak / Olahraga (Baseline minimal Kemenkes 30 menit)
        $walkingMinutes = max(30, round($sugarCalories / 4)); // ~4 kkal/menit jalan
        $cyclingMinutes = max(20, round($sugarCalories / 7)); // ~7 kkal/menit gowes
        $stairsMinutes = max(10, round($sugarCalories / 9));   // ~9 kkal/menit tangga

        return view('recommendation', compact(
            'totalSugar',
            'percentage',
            'remainingSugar',
            'sugarCalories',
            'status',
            'statusTitle',
            'statusBadge',
            'statusColor',
            'statusBg',
            'statusBorder',
            'statusMessage',
            'swaps',
            'healthyProducts',
            'walkingMinutes',
            'cyclingMinutes',
            'stairsMinutes'
        ));
    }
}
