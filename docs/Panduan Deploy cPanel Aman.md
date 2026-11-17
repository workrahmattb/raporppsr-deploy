---
title: Panduan Deploy cPanel Aman
tags:
  - raporppsr
  - deployment
---

# Panduan update Rapor PPSR dari GitHub ke cPanel

Panduan ini untuk **memperbarui aplikasi yang sudah berjalan dan sudah berisi data**, dengan akses Terminal cPanel. Kode berasal dari repositori `workrahmattb/raporppsr-deploy`. Jalankan langkah per langkah; jika satu langkah gagal, hentikan proses dan jangan lanjut ke migrasi atau membuka situs.

> [!important] Status kesiapan
> Pemeriksaan lokal: 22 pengujian akses dengan 92 assertion lulus, build frontend lulus, dan Composer valid. Ini belum merupakan pemeriksaan server hosting. Hosting harus menggunakan PHP 8.4, database lama harus dikenali, backup harus bisa dipulihkan, dan perubahan lokal harus sudah di-commit/push sebelum deploy.

## 1. Apa yang dipertahankan

| Bagian | Perlakuan saat update |
| --- | --- |
| Database hosting | Tetap gunakan database hosting yang lama; jalankan migrasi tambahan |
| `.env` hosting | Pertahankan konfigurasi dan `APP_KEY` lama |
| `storage/` hosting | Pertahankan seluruh isinya, termasuk logo |
| Logo baru | Disimpan dalam `storage/app/private/rapor-logos/`; jangan ditimpa dengan folder lokal |
| `public/img/` | Backup; periksa jika logo lama pernah diganti langsung di server |
| `public/build/` | Ikut kode hasil build yang sudah di-commit |
| `bootstrap/cache/` | Jangan salin cache lokal; buat ulang di hosting |

**Database lokal tidak menggantikan database hosting.** Migrasi baru akan mempertahankan kelas, siswa, nilai, dan penugasan yang sudah ada sambil menambah kolom baru. Logo yang hanya diunggah di komputer lokal tidak otomatis ikut GitHub; jika memang ingin memindahkannya ke hosting, lakukan pemindahan file dan pengaturan terkait secara terpisah, tanpa menimpa database hosting.

## 2. Pemeriksaan PHP dan lokasi aplikasi

Di cPanel, pilih PHP **8.4** melalui MultiPHP Manager/Select PHP Version. Composer lock proyek saat ini memuat paket Symfony 8 yang memerlukan PHP >= 8.4, walaupun deklarasi dasar di `composer.json` masih `^8.2`.

PHP pada website dan Terminal bisa berbeda. Contoh path PHP 8.4 pada cPanel EasyApache:

```bash
/opt/cpanel/ea-php84/root/usr/bin/php -v
```

Jika path tersebut tidak ada, minta provider menunjukkan binary PHP 8.4 CLI. Jangan memaksa instalasi dengan `--ignore-platform-reqs`.

Pastikan ekstensi PHP database MySQL (`pdo_mysql`), GD, Fileinfo, Mbstring, XML/DOM, Zip, dan ekstensi yang diminta Composer tersedia. Pemeriksaan Composer di langkah berikutnya menjadi syarat sebelum melanjutkan.

**Document Root domain harus menunjuk ke folder `public` Laravel**, misalnya `/home/CPUSER/rapor/public`. Jangan mengekspos root proyek yang berisi `.env`, `app`, `vendor`, dan `storage`. Jika instalasi lama memisahkan isi `public` ke `public_html`, susunan path harus disesuaikan terlebih dahulu; jangan menyalin seluruh proyek ke `public_html`.

## 3. Siapkan commit di komputer lokal

Perubahan fitur pada percakapan ini belum otomatis menjadi commit atau ter-push ke GitHub. Pembersihan cache/log dari pelacakan Git sudah disiapkan di index; file fisiknya tetap ada di komputer.

Dari folder proyek lokal:

```bash
git status --short
npm run build
git diff --cached --stat
```

Pilih dan stage perubahan kode yang memang ingin diterbitkan. Pastikan termasuk berkas baru pada `app`, komponen Blade, keenam migrasi November, `composer.json`, `composer.lock`, `.gitignore`, serta `public/build/manifest.json` dan aset yang dirujuknya. Review juga penghapusan cache/log dari index yang sudah disiapkan.

```bash
git diff --cached --name-only
```

Daftar commit tidak boleh memuat `.env` aktif, database dump, logo unggahan privat, `storage/logs`, `storage/app/mpdf`, atau cache di `public/storage`. Jangan menjalankan `git add .` tanpa memeriksa isinya.

Sesudah review, buat commit dan push ke branch yang akan dipakai hosting:

```bash
git commit -m "Pisahkan akses admin Putra Putri dan pengaturan rapor"
git push origin NAMA_BRANCH
```

Ganti `NAMA_BRANCH` dengan branch yang benar, lalu catat hash commit dengan `git rev-parse HEAD`.

## 4. Tentukan path di Terminal cPanel

Contoh berikut **harus disesuaikan**. `APP_DIR` harus merupakan root aplikasi lama yang memiliki `artisan` dan `.env`, bukan otomatis `public_html`.

```bash
APP_DIR="/home/CPUSER/rapor"
CODE_DIR="/home/CPUSER/rapor-source-$(date +%Y%m%d-%H%M%S)"
BACKUP_DIR="/home/CPUSER/rapor-backups/$(date +%Y%m%d-%H%M%S)"
PHP_BIN="/opt/cpanel/ea-php84/root/usr/bin/php"
COMPOSER_BIN="$(command -v composer)"

test -f "$APP_DIR/artisan"
test -f "$APP_DIR/.env"
"$PHP_BIN" -v
```

`COMPOSER_BIN` harus menunjuk ke script PHP/PHAR Composer yang benar. Jika provider memakai wrapper shell, gunakan path Composer PHAR dari provider. Backup dan source checkout ditempatkan **di luar Document Root**.

## 5. Ambil kode baru ke folder terpisah

```bash
git clone --branch NAMA_BRANCH --single-branch https://github.com/workrahmattb/raporppsr-deploy.git "$CODE_DIR"
git -C "$CODE_DIR" rev-parse HEAD
```

Pastikan hash sesuai commit yang sudah direview. Untuk repositori privat, gunakan autentikasi GitHub yang disediakan hosting atau SSH deploy key dengan akses baca; jangan menaruh token dalam URL repositori atau dokumen ini.

Cara ini mengambil kode dari GitHub melalui Terminal. Tidak bergantung pada tombol **Deploy HEAD Commit** cPanel yang membutuhkan konfigurasi `.cpanel.yml`; proyek ini belum menyiapkan konfigurasi tombol tersebut.

## 6. Backup sebelum mengubah aplikasi aktif

Pastikan ada ruang disk yang cukup. Jika ada queue worker/cron yang menulis data, hentikan sementara proses tersebut selama pencadangan dan update.

```bash
cd "$APP_DIR"
"$PHP_BIN" artisan down --retry=60
umask 077
mkdir -p "$BACKUP_DIR"
tar -czf "$BACKUP_DIR/aplikasi-sebelum-update.tar.gz" -C "$APP_DIR" .
test -s "$BACKUP_DIR/aplikasi-sebelum-update.tar.gz"
```

Setelah arsip file berhasil dibuat, kembalikan izin bawaan terminal sebelum Git, Composer, atau build membuat file baru:

```bash
umask 022
```

`umask 077` hanya dipakai untuk membuat backup privat. Jika dibiarkan saat mengambil kode, aset CSS/JS baru dapat berizin `600` dan tidak bisa dibaca server web. File backup yang sudah dibuat tetap memiliki izin privat.

Buat backup database yang dipakai **hosting** melalui cPanel Backup/Backup Wizard atau Export SQL phpMyAdmin. Simpan hasilnya di luar direktori publik dan unduh salinannya ke komputer.

Jika `mysqldump` tersedia, contoh alternatifnya:

```bash
mysqldump --single-transaction --no-tablespaces --default-character-set=utf8mb4 -u NAMA_USER_DATABASE -p NAMA_DATABASE > "$BACKUP_DIR/database-sebelum-update.sql"
test -s "$BACKUP_DIR/database-sebelum-update.sql"
```

Ganti nama database dan user dengan konfigurasi hosting; ketik password ketika diminta, jangan tulis password di perintah. Jika klien tidak mendukung opsi tersebut atau akses ditolak, gunakan export cPanel/phpMyAdmin. File yang tidak kosong belum membuktikan backup valid: lakukan uji pemulihan ke **database staging yang berbeda** sebelum update produksi.

Catat jumlah siswa, guru, kelas, nilai, dan penugasan pada phpMyAdmin. Contoh query baca:

```sql
SELECT 'siswa' AS bagian, COUNT(*) AS jumlah FROM siswas_rapor
UNION ALL SELECT 'guru', COUNT(*) FROM gurus_rapor
UNION ALL SELECT 'kelas', COUNT(*) FROM kelas_rapor
UNION ALL SELECT 'nilai', COUNT(*) FROM nilais
UNION ALL SELECT 'penugasan', COUNT(*) FROM guru_mata_pelajaran;
```

Jika backup gagal, jangan lanjut. Aplikasi lama dapat dibuka kembali dengan `artisan up` menggunakan PHP yang sesuai instalasi lama.

## 7. Periksa migrasi lama yang berisiko

Di phpMyAdmin, periksa tabel `migrations` dan struktur `guru_mata_pelajaran`.

Migrasi lama ini harus sudah **Ran** dan kolom penugasan harus berisi tingkat yang benar (`VII` sampai `XII`):

```text
2026_03_15_000001_change_kelas_id_to_tingkat_in_guru_mata_pelajaran_table
```

> [!warning] Jika migrasi Maret masih pending
> Jangan langsung menjalankannya di produksi. Migrasi lama tersebut mengganti `kelas_id` menjadi `tingkat` tanpa memetakan ID kelas lama ke tingkat, serta memilih foreign key tanpa menyaring kolomnya. Penugasan lama dapat menjadi salah. Database hosting perlu diperiksa dan direncanakan konversinya secara khusus terlebih dahulu.

Jangan mengisi tabel `migrations` secara manual hanya untuk melewati pemeriksaan.

## 8. Salin kode dengan mengecualikan data hosting

Pastikan `rsync` tersedia. Lihat perubahan dulu memakai `--dry-run`:

```bash
rsync -avn \
  --exclude='/.git/' --exclude='/.env' --exclude='/.env.*' \
  --exclude='/storage/' --exclude='/public/storage/' \
  --exclude='/bootstrap/cache/' --exclude='/vendor/' --exclude='/node_modules/' \
  --exclude='/public/.htaccess' --exclude='/.user.ini' --exclude='/php.ini' \
  "$CODE_DIR/" "$APP_DIR/"
```

Setelah daftar file benar, jalankan versi salinnya:

```bash
rsync -av \
  --exclude='/.git/' --exclude='/.env' --exclude='/.env.*' \
  --exclude='/storage/' --exclude='/public/storage/' \
  --exclude='/bootstrap/cache/' --exclude='/vendor/' --exclude='/node_modules/' \
  --exclude='/public/.htaccess' --exclude='/.user.ini' --exclude='/php.ini' \
  "$CODE_DIR/" "$APP_DIR/"
```

**Jangan menambahkan `--delete` atau `--delete-excluded`.** Folder storage dan `.env` lama tidak disalin dari komputer lokal. `.htaccess` publik lama dipertahankan agar aturan Laravel dan handler PHP cPanel tidak tertimpa. Periksa apakah ada aturan `.htaccess` yang perlu diperbarui secara terpisah.

Jika tidak ada `rsync`, jangan menggantinya dengan penghapusan seluruh folder aplikasi. Gunakan File Manager untuk menyalin hanya kode dan aset, dengan pengecualian yang sama.

## 9. Periksa `public/storage` lama

Repositori lama pernah melacak cache Blade dalam `public/storage/framework/views`. Pembersihan Git tidak otomatis menghapus salinan yang sudah ada di hosting karena langkah salin di atas sengaja mempertahankan data.

```bash
ls -ld "$APP_DIR/public/storage"
```

- Jika berupa symlink yang benar ke `storage/app/public`, pertahankan.
- Jika berupa direktori biasa, backup sudah harus tersedia. Periksa isinya melalui File Manager. Jika isinya hanya cache `framework/views`, pindahkan seluruh direktori itu ke lokasi backup privat, bukan menghapusnya.
- Jika berisi unggahan asli, inventarisasi dan pindahkan unggahan tersebut ke penyimpanan yang benar terlebih dahulu. Jangan menjalankan `storage:link` atau menimpa direktori secara membabi buta.

Pastikan cache PHP tidak tetap tersedia di bawah direktori publik. Logo baru rapor memakai storage privat sehingga tidak membutuhkan symlink publik.

## 10. Install dependensi dan jalankan migrasi tambahan

```bash
cd "$APP_DIR"
"$PHP_BIN" "$COMPOSER_BIN" install --no-dev --prefer-dist --optimize-autoloader --no-interaction
"$PHP_BIN" "$COMPOSER_BIN" check-platform-reqs --no-dev
"$PHP_BIN" artisan optimize:clear
"$PHP_BIN" artisan migrate:status
"$PHP_BIN" artisan migrate --pretend --force
```

**Periksa output sebelum melanjutkan.** Jika ada kegagalan Composer, versi PHP salah, migrasi lama yang belum diverifikasi, atau SQL yang tidak sesuai, hentikan update.

Enam migrasi November yang ditambahkan dalam pekerjaan ini:

1. `2026_11_14_041440_add_kategori_to_kelas_rapor_table`
2. `2026_11_14_071959_add_putra_fields_to_rapor_settings_table`
3. `2026_11_17_035830_add_kategori_to_guru_mata_pelajaran_table`
4. `2026_11_17_043503_add_print_customization_to_rapor_settings_table`
5. `2026_11_17_074804_add_super_admin_role_to_users_table`
6. `2026_11_17_075236_add_admin_category_access`

Jika yang pending hanya migrasi yang sudah diperiksa, jalankan:

```bash
"$PHP_BIN" artisan migrate --force
"$PHP_BIN" artisan migrate:status
```

Efek yang memang diharapkan: kelas/penugasan lama menjadi Putri; akun `admin` ber-role admin menjadi Super Admin; admin biasa lama menjadi admin Putri; siswa mengikuti kelas Putra yang sudah ada, sedangkan siswa lain diawali Putri. Jumlah baris siswa, kelas, dan nilai tidak seharusnya berkurang. Nama, nilai, dan password lama tidak diganti migrasi ini.

## 11. Konfigurasi produksi dan cache

Pertahankan `.env` hosting, terutama `APP_KEY` dan koneksi database lama. Pastikan:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://DOMAIN_ANDA
SESSION_SECURE_COOKIE=true
```

Gunakan `SESSION_SECURE_COOKIE=true` setelah HTTPS aktif. Pastikan PHP dapat menulis ke `storage` dan `bootstrap/cache`, tanpa memberikan izin `777` secara menyeluruh. `storage/app` harus dapat dipakai mPDF membuat file sementara.

```bash
"$PHP_BIN" artisan optimize
```

Jika aplikasi memakai queue worker, jalankan `artisan queue:restart` dan aktifkan kembali proses pengelolanya setelah pemeriksaan selesai. Node.js di hosting tidak diperlukan jika hasil `npm run build` yang cocok sudah ikut commit pada `public/build/`.

## 12. Verifikasi sebelum menerima input baru

Bandingkan jumlah baris dengan catatan sebelum deploy. Untuk mencoba lewat browser saat maintenance, gunakan maintenance bypass milik Laravel secara privat atau staging; jangan membuka input publik sebelum pemeriksaan data selesai.

Periksa:

- Login akun `admin` berhasil sebagai Super Admin dengan password lama.
- Kelas, siswa, nilai, serta penugasan lama tetap tersedia.
- Super Admin dapat membuka Kelola Admin dan menentukan bagian akun admin biasa.
- Admin Putra tidak bisa melihat/mengubah data Putri; lakukan pemeriksaan kebalikannya.
- Data guru dan mata pelajaran tetap bersama.
- Tanggal/kepala sekolah Putri yang lama tetap ada; pengaturan Putra diisi sesuai kebutuhan.
- Logo, judul, subjudul, cetak satu siswa, cetak satu kelas, serta ekspor leger berfungsi.
- Perubahan pengaturan/unggahan dapat disimpan dan bertahan setelah halaman dimuat ulang.

Setelah berhasil:

```bash
"$PHP_BIN" artisan up
```

Simpan backup dan hash commit yang terpasang. Tidak ada deployment hosting atau push GitHub yang dilakukan otomatis oleh pembuatan panduan ini.

## 13. Jika update gagal

Jangan menjalankan migrasi lanjutan dan jangan menghapus database. Pertahankan maintenance, simpan pesan error, dan periksa log privat di `storage/logs/laravel.log`.

Pemulihan harus menggunakan backup kode, database, dan unggahan yang berasal dari waktu konsisten. Jika situs belum menerima input baru sejak backup, pemulihan snapshot sebelum update bisa dilakukan setelah memastikan backup valid. Jika sudah ada input baru, jangan menimpa database dengan backup lama karena input baru akan hilang; perlu rencana pemulihan khusus.

Jangan memakai `migrate:rollback` sebagai tombol undo otomatis: beberapa `down()` menghapus kolom kategori/pengaturan, dan penyusutan indeks penugasan dapat gagal jika sudah ada penugasan untuk kedua kategori. Perubahan skema MySQL juga tidak selalu dapat dibatalkan secara atomik saat suatu migrasi berhenti di tengah.

## Perintah yang tidak digunakan pada update berisi data

```text
php artisan migrate:fresh
php artisan migrate:refresh
php artisan db:wipe
php artisan db:seed
php artisan migrate --seed
php artisan key:generate
composer setup
composer update
```

Jangan menjalankan `git clean -fdx`, menghapus storage, mengimpor database lokal ke produksi, atau menimpa `.env` hosting. `composer setup` khususnya menjalankan `key:generate`, sehingga bukan prosedur update situs lama.

## Rujukan resmi

- [cPanel: menjalankan PHP melalui Terminal](https://support.cpanel.net/hc/en-us/articles/360050224413-How-to-run-PHP-commands-as-a-cPanel-user-via-Terminal)
- [cPanel: Git deployment dan `.cpanel.yml`](https://docs.cpanel.net/knowledge-base/web-services/guide-to-git-deployment/)
- [Laravel 12: deployment](https://laravel.com/docs/12.x/deployment)
- [Laravel 12: migrations](https://laravel.com/docs/12.x/migrations)
- [Laravel 12: encryption dan application key](https://laravel.com/docs/12.x/encryption)
