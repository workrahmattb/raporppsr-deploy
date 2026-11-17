---
title: "Panduan Pengembangan Rapor PPSR"
tags:
  - raporppsr
  - dokumentasi
updated: 2026-11-08
---

# Panduan Pengembangan Rapor PPSR

Kembali ke [[Beranda Rapor PPSR]]. Catatan pekerjaan baru dapat memakai [[Template Catatan Pengembangan]].

## Aturan proyek

Baca `AGENTS.md`. Jika `.ai/rules` tersedia, baca indeks dan semua aturan yang sesuai cakupan perubahan. Ikuti pola berkas di area yang dikerjakan. Dokumentasi ini tidak menggantikan instruksi proyek.

Rute fitur utama menggunakan komponen kelas dalam `app/Livewire`, dengan tampilan dalam `resources/views/livewire`. Ada juga komponen Blade berawalan petir; periksa komponen yang benar-benar digunakan rute sebelum mengedit.

## Laravel Boost

Konfigurasi proyek tersedia di `boost.json`, `.mcp.json`, dan `.codex/config.toml`. MCP menjalankan `php artisan boost:mcp`.

- Gunakan `application_info` untuk memeriksa versi aktual.
- Gunakan `search-docs` sebelum perubahan yang bergantung pada API Laravel atau Livewire.
- Gunakan `database-schema` untuk struktur database dan `database-query` untuk query baca.
- Gunakan `get-absolute-url` sebelum membagikan URL aplikasi.

Boost telah berhasil digunakan dalam percakapan proyek ini. Status koneksi perlu diperiksa kembali jika sesi atau konfigurasi berubah.

## Pemeriksaan sesuai perubahan

```bash
composer show --direct
php artisan route:list --except-vendor --no-interaction
vendor/bin/pint --dirty --format agent
npm run build
```

Jalankan Pint jika mengubah PHP; build frontend jika diperlukan perubahan tampilan. Jika ada pengujian relevan, jalankan cakupan paling sempit. Folder `tests` belum ditemukan pada pemeriksaan awal; jangan menyebut fitur telah lulus tes otomatis hanya berdasarkan kompilasi.

## Pengelolaan vault

Catatan dan template berada di `docs`. Pencarian mengecualikan dependensi, cache, hasil build, dan konfigurasi tooling. Pengecualian pencarian tidak menghapus berkas atau menjadi kontrol akses.

Jangan memasukkan password, isi `.env`, data pribadi siswa, atau kunci aplikasi ke catatan pengembangan.
