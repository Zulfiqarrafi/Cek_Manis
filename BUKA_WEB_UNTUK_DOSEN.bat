@echo off
title CEKMANIS - Server Presentasi Dosen
color 0b
cls
echo =================================================================
echo       CEKMANIS - MODE PRESENTASI UNTUK DOSEN
echo =================================================================
echo.
echo [1/2] Menyiapkan server lokal Laravel...

:: Matikan proses lama jika ada agar port 8000 bersih
taskkill /F /IM php.exe >nul 2>&1
timeout /t 1 /nobreak >nul

:: Jalankan Laravel serve di background
start "" /min php artisan serve --host=127.0.0.1 --port=8000
timeout /t 2 /nobreak >nul

echo [2/2] Menghubungkan ke Internet via Cloudflare Tunnel...
echo.
echo =================================================================
echo  PETUNJUK PENGGUNAAN:
echo.
echo  1. Tunggu 5-10 detik sampai muncul link publik HTTPS:
echo     https://[nama-acak].trycloudflare.com
echo.
echo  2. Copy link https://... tersebut, lalu kirim ke WhatsApp Adik / Dosen.
echo.
echo  3. Adik dan Dosen tinggal KLIK link tersebut di HP / Laptop!
echo     (Tidak perlu install aplikasi atau tools apapun).
echo.
echo  * PENTING: JANGAN TUTUP jendela ini selama adik/dosen masih akses web!
echo    Jika presentasi sudah selesai, cukup tutup jendela ini.
echo =================================================================
echo.

"%~dp0cloudflared.exe" tunnel --url http://127.0.0.1:8000

:: Bersihkan proses php saat tunnel ditutup
taskkill /F /IM php.exe >nul 2>&1
echo.
echo Server dan koneksi internet telah dinonaktifkan.
pause

