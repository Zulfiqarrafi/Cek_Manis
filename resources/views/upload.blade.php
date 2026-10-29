@extends('layouts.app')

@section('content')
<div class="page-card" style="text-align: center; max-width: 600px;">
    <h2 style="color: var(--primary-color); margin-bottom: 20px;">Upload Label Gizi (OCR)</h2>
    <p style="color: var(--text-muted); font-size: 15px; margin-bottom: 30px;">Unggah foto tabel Informasi Nilai Gizi dari kemasan produk makanan/minuman Anda.</p>

    <!-- Formulir Simulasi Upload -->
    <form action="{{ route('scan.simulate') }}" method="GET">
        <div style="border: 2px dashed #CBD5E1; padding: 50px; border-radius: 16px; margin-bottom: 30px; background: #F8FAFC; cursor: pointer; position: relative;">
            <input type="file" accept="image/*" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; opacity: 0; cursor: pointer;" required />
            <i class="fa-solid fa-cloud-arrow-up" style="font-size: 48px; color: #94A3B8; margin-bottom: 15px;"></i>
            <h4 style="color: var(--text-dark); font-weight: 700; margin-bottom: 5px;">Klik atau Tarik Foto Kesini</h4>
            <span style="color: var(--text-muted); font-size: 13px;">Format: JPG, PNG, WEBP</span>
        </div>
        
        <button type="submit" style="width: 100%; padding: 16px; background: var(--primary-color); color: white; border: none; border-radius: 12px; font-weight: 800; font-size: 16px; cursor: pointer; transition: transform 0.2s;">
            <i class="fa-solid fa-magnifying-glass-arrow-right"></i> Mulai Analisis Foto
        </button>
    </form>
</div>
@endsection
