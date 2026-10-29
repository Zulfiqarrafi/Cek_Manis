@extends('layouts.app')

@section('content')
<div class="page-card" style="text-align: center;">
    <h2 style="color: var(--primary-color); margin-bottom: 20px;">Pemindai Barcode CekManis</h2>
    <p style="color: var(--text-muted); font-size: 15px; margin-bottom: 30px;">Izinkan akses dan arahkan kamera ke barcode pada kemasan produk.</p>

    <!-- Kamera Web Reader -->
    <div id="reader" style="width: 100%; max-width: 500px; margin: 0 auto 30px; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 2px solid #E2E8F0;"></div>

    <!-- Hidden Form to submit barcode -->
    <form id="scan-form" action="{{ route('scan.process') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="barcode" id="barcode-input">
    </form>

    <div style="margin-top: 30px; font-size: 14px; color: var(--text-muted); border-top: 1px solid #E2E8F0; padding-top: 20px;">
        Jika kamera Anda tidak merespon (seperti di localhost), gunakan mode simulasi:<br><br>
        <a href="{{ route('scan.simulate') }}" style="display: inline-block; padding: 12px 24px; background: #E2E8F0; color: #475569; text-decoration: none; border-radius: 12px; font-weight: bold; transition: background 0.2s;">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Simulasikan Hasil Kamera
        </a>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const html5QrCode = new Html5Qrcode("reader");
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            html5QrCode.stop().then((ignore) => {
                document.getElementById('barcode-input').value = decodedText;
                document.getElementById('scan-form').submit();
            }).catch((err) => {
                console.log(err);
            });
        };
        
        const config = { fps: 10, qrbox: { width: 250, height: 150 } };
        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
            .catch((err) => {
                console.log("Error starting camera: ", err);
                document.getElementById("reader").innerHTML = "<div style='padding:40px; background:#FEF2F2; color:#991B1B;'><i class='fa-solid fa-camera-slash' style='font-size:40px; margin-bottom:10px;'></i><br>Kamera gagal dimuat. Pastikan browser mengizinkan akses kamera (biasanya butuh domain HTTPS / localhost khusus).</div>";
            });
    });
</script>
@endsection
