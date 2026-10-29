<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CalculatorController extends Controller
{
    public function index()
    {
        $presets = [
            [
                'name' => 'Es Teh Manis Warung',
                'amount' => 14,
                'unit' => 'gram',
                'description' => 'Rata-rata 3-4 sendok teh gula pasir',
            ],
            [
                'name' => 'Kopi Susu Gula Aren',
                'amount' => 24,
                'unit' => 'gram',
                'description' => 'Kopi kekinian dengan sirup gula aren',
            ],
            [
                'name' => 'Boba Brown Sugar Fresh Milk',
                'amount' => 38,
                'unit' => 'gram',
                'description' => 'Sangat manis, 75% dari batas harian',
            ],
            [
                'name' => 'Jus Alpukat Cokelat',
                'amount' => 26,
                'unit' => 'gram',
                'description' => 'Alpukat dengan kental manis cokelat',
            ],
            [
                'name' => 'Kopi Hitam Seduh Sendiri (1 sdt)',
                'amount' => 1,
                'unit' => 'teaspoon',
                'description' => 'Sekitar 4 gram gula pasir',
            ],
        ];

        return view('calculator', compact('presets'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'sugar_amount' => 'required|numeric|min:0',
            'unit' => 'required|in:gram,teaspoon,tablespoon',
            'portions' => 'nullable|numeric|min:0.5|max:10',
            'meal_time' => 'nullable|string',
        ]);

        $name = trim($validated['name']);
        $amount = (float) $validated['sugar_amount'];
        $unit = $validated['unit'];
        $portions = (float) ($validated['portions'] ?? 1);

        if ($unit === 'teaspoon') {
            $totalSugarGrams = $amount * 4.2 * $portions;
        } elseif ($unit === 'tablespoon') {
            $totalSugarGrams = $amount * 12.5 * $portions;
        } else {
            $totalSugarGrams = $amount * $portions;
        }

        $totalSugarGrams = round($totalSugarGrams, 1);

        $level = 'RENDAH';
        if ($totalSugarGrams > 15) {
            $level = 'TINGGI';
        } elseif ($totalSugarGrams >= 6) {
            $level = 'SEDANG';
        }

        $portionLabel = $portions > 1 ? " ({$portions} porsi)" : '';

        $histories = Session::get('histories', []);
        array_unshift($histories, [
            'id' => 'item_'.uniqid(),
            'name' => $name.$portionLabel,
            'sugar' => $totalSugarGrams,
            'time' => now()->format('H:i'),
            'source' => 'Kalkulator Manual',
            'level' => $level,
        ]);

        Session::put('histories', $histories);
        Session::save();

        return redirect()->route('history')->with('success', "Berhasil mencatat {$name} (+{$totalSugarGrams}g gula) ke catatan hari ini!");
    }
}
