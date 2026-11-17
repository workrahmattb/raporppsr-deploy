---
title: "Catatan Pemeriksaan Rapor PPSR"
tags:
  - raporppsr
  - dokumentasi
updated: 2026-11-08
---

# Catatan Pemeriksaan Rapor PPSR

Kembali ke [[Beranda Rapor PPSR]].

## Fakta yang sudah ditelusuri

- Rute fitur utama dipisahkan menjadi admin, guru, dan wali kelas.
- Halaman detail guru tersedia dan menampilkan username tanpa password.
- Penugasan guru memakai tingkat VII sampai XII pada formulir yang diperiksa.
- Cetak rapor yang diperiksa menggunakan mPDF; ekspor leger memakai Laravel Excel.
- Pemilihan periode tersimpan pada User, sementara form input nilai mengambil semester aktif.
- `EnsureAcademicPeriodSelected` hanya meneruskan request pada kode yang diperiksa dan tidak tercantum sebagai alias middleware di `bootstrap/app.php`.

## Batas pemeriksaan

Ini dokumentasi awal berdasarkan penelusuran kode. Belum merupakan audit menyeluruh, uji seluruh halaman melalui browser, atau verifikasi skema database produksi.

Versi yang tercatat pada pemeriksaan sebelumnya: Laravel 12.49.0, Livewire 4.1.0, Flux 2.11.1, Fortify 1.34.0, Boost 2.10.3. Verifikasi ulang melalui Composer atau Boost sebelum mengandalkannya.

## Setup Obsidian

Vault berada di root proyek. Dokumentasi memakai folder `docs` yang sudah ada, wikilink, properties, dan diagram Mermaid. Setup ini tidak menyediakan sinkronisasi otomatis antara kode dan catatan.
