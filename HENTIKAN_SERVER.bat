@echo off
title CEKMANIS - Matikan Server
echo Mematikan server website dan koneksi online...
taskkill /F /IM php.exe >nul 2>&1
taskkill /F /IM cloudflared.exe >nul 2>&1
echo.
echo Selesai! Server berhasil dinonaktifkan.
timeout /t 3

