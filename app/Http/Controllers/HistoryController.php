<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HistoryController extends Controller
{
    public function index()
    {
        // Jika belum ada data riwayat di sesi, inisialisasi data contoh agar halaman tidak kosong
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

        $histories = Session::get('histories', []);

        $total = 0;
        foreach ($histories as $h) {
            $total += (float) ($h['sugar'] ?? 0);
        }
        $total = round($total, 1);
        $percentage = round(($total / 50) * 100);

        // Rekap level
        $highCount = 0;
        $medCount = 0;
        $lowCount = 0;
        foreach ($histories as $h) {
            $lvl = $h['level'] ?? 'RENDAH';
            if ($lvl === 'TINGGI') {
                $highCount++;
            } elseif ($lvl === 'SEDANG') {
                $medCount++;
            } else {
                $lowCount++;
            }
        }

        return view('history', compact('histories', 'total', 'percentage', 'highCount', 'medCount', 'lowCount'));
    }

    public function quickAdd(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'sugar' => 'required|numeric|min:0|max:300',
        ]);

        $sugar = round((float) $validated['sugar'], 1);
        $level = 'RENDAH';
        if ($sugar > 15) {
            $level = 'TINGGI';
        } elseif ($sugar >= 6) {
            $level = 'SEDANG';
        }

        $histories = Session::get('histories', []);
        array_unshift($histories, [
            'id' => 'item_'.uniqid(),
            'name' => trim($validated['name']),
            'sugar' => $sugar,
            'time' => now()->format('H:i'),
            'source' => 'Catat Cepat',
            'level' => $level,
        ]);

        Session::put('histories', $histories);
        Session::save();

        return redirect()->route('history')->with('success', 'Berhasil mencatat "'.$validated['name'].'" (+'.$sugar.'g gula)!');
    }

    public function deleteItem(string $id)
    {
        $histories = Session::get('histories', []);

        $filtered = array_filter($histories, function ($item) use ($id) {
            return ($item['id'] ?? null) !== $id;
        });

        Session::put('histories', array_values($filtered));
        Session::save();

        return redirect()->route('history')->with('success', 'Catatan konsumsi berhasil dihapus.');
    }

    public function clearHistory()
    {
        Session::put('histories', []);
        Session::save();

        return redirect()->route('history')->with('success', 'Semua riwayat konsumsi telah dikosongkan.');
    }

    public function seedDemo()
    {
        $demo = [
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
            [
                'id' => 'demo_3',
                'name' => 'Es Kopi Susu Gula Aren (1 Gelas)',
                'sugar' => 24,
                'time' => '16:00',
                'source' => 'Kalkulator Manual',
                'level' => 'TINGGI',
            ],
        ];

        Session::put('histories', $demo);
        Session::save();

        return redirect()->route('history')->with('success', 'Data contoh berhasil dimuat ke riwayat!');
    }
}
