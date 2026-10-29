<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->query('q', '');
        $selectedCategory = $request->query('category', 'all');
        $selectedLevel = $request->query('level', 'all');

        $products = ProductService::search($keyword, $selectedCategory, $selectedLevel);
        $categories = ProductService::categories();

        return view('catalog', [
            'products' => $products,
            'categories' => $categories,
            'keyword' => $keyword,
            'selectedCategory' => $selectedCategory,
            'selectedLevel' => $selectedLevel,
            'totalCount' => count($products),
        ]);
    }

    public function show(int $id)
    {
        $product = ProductService::find($id);

        if (! $product) {
            abort(404, 'Produk tidak ditemukan di katalog.');
        }

        $product['percentage'] = round(($product['sugar'] / 50) * 100);
        $product['category'] = $product['sugar_level'];

        return view('product', compact('product'));
    }

    public function addHistory(Request $request, int $id)
    {
        $product = ProductService::find($id);

        if (! $product) {
            abort(404, 'Produk tidak ditemukan.');
        }

        $histories = Session::get('histories', []);
        array_unshift($histories, [
            'id' => 'item_'.uniqid(),
            'name' => $product['name'].' ('.$product['volume'].')',
            'sugar' => $product['sugar'],
            'time' => now()->format('H:i'),
            'source' => 'Katalog',
            'level' => $product['sugar_level'],
        ]);

        Session::put('histories', $histories);
        Session::save();

        return redirect()->route('history')->with('success', 'Berhasil menambahkan '.$product['name'].' (+ '.$product['sugar'].'g gula) ke catatan hari ini!');
    }
}
