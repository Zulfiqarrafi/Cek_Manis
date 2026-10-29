<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ScanController extends Controller
{
    public function scan()
    {
        return view('scan');
    }

    public function simulateScan()
    {
        return redirect()->route('product.detail', ['id' => 1]);
    }

    public function processScan(Request $request)
    {
        $barcode = $request->input('barcode');

        return redirect()->route('product.detail', ['id' => 1])->with('success', 'Barcode berhasil dibaca: '.$barcode);
    }

    public function product(int $id)
    {
        $product = ProductService::find($id);
        if (! $product) {
            abort(404, 'Produk tidak ditemukan');
        }

        $product['percentage'] = round(($product['sugar'] / 50) * 100);
        $product['category'] = $product['sugar_level'];

        return view('product', compact('product'));
    }

    public function saveHistory(Request $request, int $id)
    {
        $product = ProductService::find($id);
        if (! $product) {
            abort(404);
        }

        $histories = Session::get('histories', []);
        $histories[] = [
            'name' => $product['name'].' ('.$product['volume'].')',
            'sugar' => $product['sugar'],
            'time' => now()->format('H:i'),
            'source' => 'Katalog',
            'level' => $product['sugar_level'],
        ];

        Session::put('histories', $histories);

        return redirect()->route('history')->with('success', 'Berhasil mencatat konsumsi gula: '.$product['name']);
    }

    public function history()
    {
        $histories = Session::get('histories', []);

        $total = 0;
        foreach ($histories as $h) {
            $total += $h['sugar'];
        }

        $percentage = round(($total / 50) * 100);

        return view('history', compact('histories', 'total', 'percentage'));
    }

    public function clearHistory()
    {
        Session::forget('histories');

        return redirect()->route('history')->with('success', 'Riwayat konsumsi gula berhasil direset!');
    }
}
