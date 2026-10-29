<?php

use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// ==================== FITUR KATALOG PRODUK ====================
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/catalog/{id}', [CatalogController::class, 'show'])->name('catalog.show');
Route::post('/catalog/{id}/add', [CatalogController::class, 'addHistory'])->name('catalog.add');

// ==================== FITUR KALKULATOR MANUAL ====================
Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator');
Route::post('/calculator/save', [CalculatorController::class, 'save'])->name('calculator.save');

// ==================== FITUR RIWAYAT KONSUMSI ====================
Route::get('/history', [HistoryController::class, 'index'])->name('history');
Route::post('/history/add-quick', [HistoryController::class, 'quickAdd'])->name('history.quick_add');
Route::post('/history/delete/{id}', [HistoryController::class, 'deleteItem'])->name('history.delete');
Route::post('/history/clear', [HistoryController::class, 'clearHistory'])->name('history.clear');
Route::post('/history/seed', [HistoryController::class, 'seedDemo'])->name('history.seed');

// ==================== FITUR REKOMENDASI PINTAR ====================
Route::get('/recommendation', [RecommendationController::class, 'index'])->name('recommendation');

// ==================== FITUR UPLOAD FOTO ====================
Route::get('/upload', function () {
    return view('upload');
})->name('upload');

// Detail Produk & Simpan
Route::get('/product/{id}', [CatalogController::class, 'show'])->name('product.detail');
Route::post('/product/{id}/save', [CatalogController::class, 'addHistory'])->name('product.save');

// Legacy Scan Routes (Kompatibilitas)
Route::get('/scan', [ScanController::class, 'scan'])->name('scan');
Route::post('/scan/process', [ScanController::class, 'processScan'])->name('scan.process');
Route::get('/scan/simulate', [ScanController::class, 'simulateScan'])->name('scan.simulate');
