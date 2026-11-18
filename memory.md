# Memory - Edit Session

## Tanggal
24 April 2026

---

## 1. Penugasan Guru - Tambah Tingkat Kelas X, XI, XII

### Files Edited:
- `app/Livewire/Admin/PenugasanGuru/Create.php`
- `app/Livewire/Admin/PenugasanGuru/Edit.php`
- `resources/views/livewire/admin/penugasan-guru/index.blade.php`

### Perubahan:
- Validasi tingkat: `VII,VIII,IX` → `VII,VIII,IX,X,XI,XII`
- Dropdown options tingkat di Create & Edit
- Filter dropdown di Index page

---

## 2. Admin/Guru/Create.php - Redirect SPA Fix

### File Edited:
- `app/Livewire/Admin/Guru/Create.php`

### Perubahan:
- Line 92: `return redirect()->route('admin.guru.index')` → `$this->redirectRoute('admin.guru.index', navigate: true)`
- Membuat save button menggunakan SPA behavior (tanpa full page reload)

---

## 3. Semua Tombol Batal - Tambahkan wire:navigate

### Files Edited:

#### Create Pages:
- `resources/views/livewire/admin/guru/create.blade.php`
- `resources/views/livewire/admin/siswa/create.blade.php`
- `resources/views/livewire/admin/kelas/create.blade.php`
- `resources/views/livewire/admin/mata-pelajaran/create.blade.php`
- `resources/views/livewire/admin/tahun-ajaran/create.blade.php`
- `resources/views/livewire/admin/penugasan-guru/create.blade.php` (sudah ada)

#### Edit Pages:
- `resources/views/livewire/admin/kelas/edit.blade.php`
- `resources/views/livewire/admin/tahun-ajaran/edit.blade.php`

### Perubahan:
- Semua `<a href="{{ route(...) }}">` → `<a href="{{ route(...) }}" wire:navigate>`
- Back button & Batal button sekarang tidak full page reload

---

## 4. Link Back (Header) di Create Pages - Tambahkan wire:navigate

### Files Edited:
- `resources/views/livewire/admin/tahun-ajaran/create.blade.php`
- `resources/views/livewire/admin/mata-pelajaran/create.blade.php`
- `resources/views/livewire/admin/kelas/create.blade.php`
- `resources/views/livewire/admin/siswa/create.blade.php`
- `resources/views/livewire/admin/guru/create.blade.php`

### Perubahan:
- Back button (icon panah) sudah pakai `wire:navigate`

---

## 5. Index Pages - Semua Link Navigasi Pakai wire:navigate

### Files Edited:
- `resources/views/livewire/admin/tahun-ajaran/index.blade.php`
  - Tambah button + Edit link
- `resources/views/livewire/dashboard.blade.php`
  - Kelola Tahun Ajaran link
  - Enable & fix link Kelola Siswa, Kelola Kelas (temporarily disabled)
  - Enable & fix link Rekap Nilai, Cetak Rapor (temporarily disabled)

---

## Ringkasan

| Kategori | Jumlah Edit |
|----------|-------------|
| PHP Files | 3 |
| Blade Files | 11 |
| Total Link Diperbaiki | 20+ |

**Semua navigasi sekarang menggunakan SPA behavior Livewire (tanpa full page reload)**

---

## Tanggal
5 Mei 2026

---

## 1. Perbaikan Identity Section - Border & Alignment

### Files Edited:
- `resources/views/livewire/admin/rapor/print.blade.php`
- `resources/views/livewire/admin/rapor/print-all.blade.php`
- `resources/views/livewire/wali-kelas/rapor/print.blade.php`

### Perubahan:
- Menambahkan double border (`3px double #000`) pada `.identity-box`
- Menambahkan `border-collapse: collapse` pada `.identity-table`
- Menghapus border dalam cell (`border: none`)
- Menambahkan `text-align: right` pada nama siswa Arabic, tahun ajaran, kelas, semester
- Memperbaiki padding dan width separator agar lebih dekat

---

## 2. Fix Function Redeclaration Error

### Masalah:
Error `Cannot redeclare function toArabicNumerals()` saat cetak rapor semua siswa (print-all-class).

### Files Edited:
- `resources/views/livewire/admin/rapor/print.blade.php`
- `resources/views/livewire/admin/rapor/print-all.blade.php`
- `resources/views/livewire/wali-kelas/rapor/print.blade.php`

### Solusi:
Menambahkan `function_exists()` check sebelum mendeklarasikan fungsi:
```php
if (!function_exists('toArabicNumerals')) {
    function toArabicNumerals($number) { ... }
}
```

---

## 3. Menghapus Tanda Kurung di Nama Wali Kelas

### Files Edited:
- `resources/views/livewire/admin/rapor/print.blade.php`
- `resources/views/livewire/admin/rapor/print-all.blade.php`
- `resources/views/livewire/wali-kelas/rapor/print.blade.php`

### Perubahan:
- Menghapus `(....................................)` di area tanda tangan
- Menggunakan string kosong jika nama kosong

---

## 4. Menambahkan Fitur Cetak Semua Siswa untuk Wali Kelas

### Files Created/Edited:

#### a. Controller
- `app/Livewire/WaliKelas/Rapor/Index.php`
  - Method baru: `printAllByClass()`
  - Load kelas langsung dari database (tidak menggunakan $this->kelas)
  - Validasi semester dari query string
  - Verifikasi user adalah wali kelas yang benar

#### b. Route
- `routes/web.php` (line 117)
  ```php
  Route::get('/print-all-class', [\App\Livewire\WaliKelas\Rapor\Index::class, 'printAllByClass'])->name('print-all-class');
  ```

#### c. View
- `resources/views/livewire/wali-kelas/rapor/index.blade.php`
  - Button "Cetak Semua" muncul setelah pilih semester
  - Link ke route `wali-kelas.rapor.print-all-class` dengan parameter semesterId

---

## 5. Sinkronisasi UI Admin & Wali Kelas

### Files Disinkronkan:
- `print.blade.php` (admin)
- `print.blade.php` (wali-kelas)
- `print-all.blade.php` (admin)

### Hasil:
- Ketiga file sekarang memiliki UI yang konsisten
- Menghapus comment yang tidak diperlukan
- Menyatukan format signature/tanda tangan

---

## Ringkasan

| Kategori | Jumlah File |
|----------|-------------|
| PHP Files | 4 |
| Blade Files | 3 |
| Routes | 1 |

| Fitur | Status |
|-------|--------|
| Border identity section | ✅ Selesai |
| Alignment Arabic text | ✅ Selesai |
| Hapus tanda kurung nama | ✅ Selesai |
| Fix function error | ✅ Selesai |
| Print all (Admin) | ✅ Selesai |
| Print all (Wali Kelas) | ✅ Selesai |
| Sinkronisasi UI | ✅ Selesai |

**Semua perbaikan telah diuji dan berjalan dengan baik**
---

## Tanggal
17 November 2026

## Fitur dan keputusan terbaru

- Detail guru: tombol View pada daftar guru; halaman menampilkan identitas, kontak, kepegawaian, username, dan role. Password tetap berupa hash dan tidak ditampilkan. Usulan penyimpanan password yang dapat dibuka kembali dibatalkan pengguna.
- Kelas memiliki kategori Putra/Putri. Kelas dan penugasan lama diberi nilai awal Putri.
- Penugasan guru memiliki kategori Putra/Putri, termasuk validasi duplikasi berdasarkan guru, mapel, tingkat, dan kategori. Pilihan guru/mapel dapat dicari lewat dropdown pada formulir tambah/edit.
- Daftar input nilai mengikuti tingkat serta kategori penugasan. Form input nilai memeriksa akses saat membuka/menyimpan; ID kelas/mapel dikunci dan siswa yang dikirim harus anggota kelas tersebut.
- Leger menyesuaikan mata pelajaran dengan kategori kelas.
- Pengaturan rapor Putra/Putri memisahkan tanggal rapor, kepala MTs, dan kepala MA. Penyesuaian tampilan dibatasi oleh keputusan pengguna: **hanya logo, judul rapor, dan subjudul/nama pondok**. Teks lainnya mengikuti format lama yang sama.
- Logo PNG/JPG disimpan di `storage/app/private/rapor-logos/`, lalu disisipkan sebagai data URI pada hasil cetak. Jangan memasukkan unggahan privat ke Git.
- Akun berusername `admin` menjadi Super Admin (`super_admin`) dan tetap dapat mengakses kedua bagian. Password lama dipertahankan.
- Admin biasa tetap ber-role `admin`, dengan kolom `kategori_admin` Putra/Putri. Admin tanpa kategori ditolak. Guru dan mata pelajaran dipakai bersama sesuai keputusan pengguna.
- Pembatasan kategori diterapkan pada siswa, kelas, penugasan, rapor, leger, dan pengaturan rapor, termasuk akses URL/aksi simpan/ekspor. Siswa memiliki kategori tersendiri, tidak disimpulkan hanya dari jenis kelamin.
- Super Admin memiliki menu Kelola Admin untuk membuat/mengedit akun admin dan menentukan kategorinya. Menu ini berada **paling bawah sidebar** dan hanya terlihat bagi Super Admin.
- Tahun ajaran/semester dipakai bersama; mutasinya dibatasi ke Super Admin untuk menghindari perubahan atau penghapusan kelas lintas bagian.
- Middleware role didaftarkan sebagai persistent middleware Livewire agar pemeriksaan tetap berjalan pada permintaan lanjutan.

## Verifikasi lokal dan batasnya

- Pengujian akses terakhir: 22 tes, 92 assertion lulus. Tes sementara berada di `/private/tmp/AdminCategoryAccessTest.php`; belum menjadi suite regresi yang tersimpan di repositori.
- Pengujian penyesuaian rapor sebelumnya: 9 tes, 58 assertion lulus. Contoh PDF telah diperiksa secara visual dengan mPDF.
- Pint, kompilasi Blade, build Vite, dan validasi Composer berhasil pada pemeriksaan lokal.
- Pemeriksaan ini bukan audit menyeluruh. Pengguna sedang memeriksa seluruh fitur di hosting; hasil pemeriksaan lengkap belum dilaporkan.

## Deploy cPanel yang telah dilakukan pengguna

- Domain: `https://rpi.ppsyafaaturrasul.com`.
- Document Root berdasarkan tangkapan layar cPanel: `$HOME/public_html/rpi.ppsyafaaturrasul.com`.
- Root proyek pada Terminal bernama `raporppsr-deploy`; **folder publik domain terpisah dari `public` proyek**. Jangan mengasumsikan keduanya sama.
- Pengguna melaporkan PHP CLI 8.4.27. Dependency lock saat ini membutuhkan PHP >= 8.4.
- Update hosting berhasil fast-forward dari `24d58f2` ke `eda597e`.
- `composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction` dan pemeriksaan platform berhasil. Cache lama dibersihkan.
- Keenam migrasi November selesai `DONE`, lalu tercatat `Ran` pada batch 15 menurut output hosting. Migrasi Maret tetap `Ran` pada batch 14.
- Pengguna menyatakan website kembali bisa diakses setelah perbaikan CSS. Jangan menyamakan pernyataan ini dengan verifikasi seluruh fitur/data produksi.

## Backup dan perubahan khusus hosting

- Sebelum update, pengguna menjalankan maintenance dan membuat arsip `.env`, `storage`, `public`, migrasi, serta file Composer pada `$HOME/rapor-backups/<timestamp>/file-sebelum-update.tar.gz`. Arsip berhasil dibaca dengan `tar -tzf`.
- Arsip tersebut **tidak mencakup database**. Backup SQL diminta berulang kali, tetapi konfirmasi eksplisit keberadaan/hasil pemulihannya belum diberikan dalam percakapan.
- Git stash hosting berhasil dibuat dengan pesan `backup perubahan hosting sebelum update`, mencakup perubahan migrasi Maret dan file terlacak dalam `storage`. Jangan melakukan `stash pop` seluruhnya karena cache lama sudah dilepas dari Git.
- Instruksi pemulihan selektif migrasi Maret dan `storage/fonts` sudah diberikan. Pengguna tidak mengirim output khusus langkah tersebut; pelaksanaannya belum diverifikasi secara terpisah.
- Migrasi Maret di hosting pernah diedit manual: menghapus `kelas_id`, membuat `tingkat`, dan memulihkan foreign key guru. Ada berkas `.bak`. Jangan menimpa, menjalankan ulang, atau rollback migrasi ini sembarangan.
- Cache mPDF, cache Blade di `public/storage`, dan log telah dilepas dari pelacakan Git; file runtime/unggahan privat diabaikan melalui `.gitignore`. Penghapusan `delete mode` dalam pull berkaitan dengan file terlacak tersebut, bukan penghapusan tabel siswa/nilai.

## Insiden CSS dan prosedur deploy berikutnya

- Setelah pull, manifest meminta `app-7yltKew0.css`. Pemeriksaan HTTP awal menunjukkan CSS baru 404, sementara CSS lama `app-2tDBhRDi.css` masih 200.
- Izin `public/build` proyek sudah diperbaiki, tetapi penyebab utama yang terkonfirmasi adalah **build baru belum disalin ke Document Root domain yang terpisah**.
- Pengguna menyalin hanya `public/build/.` ke `$HOME/public_html/rpi.ppsyafaaturrasul.com/build/`, lalu mengatur direktori 755 dan file 644. Database, `.env`, index publik, dan unggahan tidak disalin oleh langkah ini.
- Sesudah penyalinan, pemeriksaan HTTP langsung atas CSS baru menghasilkan **200, Content-Type text/css, Content-Length 229614**. Pengguna mengonfirmasi tampilan kembali normal.
- `umask 077` hanya digunakan untuk backup privat; kembalikan `umask 022` sebelum Git/Composer/build agar file publik baru tidak terlalu ketat izinnya. Hal ini merupakan potensi masalah izin yang ditangani, bukan satu-satunya penyebab CSS pada insiden ini.
- Untuk deploy berikutnya: backup dan periksa migrasi, update kode, install dependensi terkunci, jalankan migrasi tambahan yang sudah diverifikasi, optimasi cache, serta **sinkronkan `public/build` ke folder publik domain**. Jangan menyalin seluruh proyek ke Document Root atau menimpa `index.php`/`.htaccess` hosting tanpa memeriksa susunannya.
- Panduan lengkap: `docs/Panduan Deploy cPanel Aman.md`. Panduan lokal telah ditambahkan penjelasan pengembalian umask setelah backup.
- Pertahankan `.env`, `APP_KEY`, database hosting, storage, backup, dan stash selama pemeriksaan. Jangan memakai `composer setup`, `key:generate`, `migrate:fresh`, `migrate:refresh`, seeder, reset/clean paksa, atau rollback otomatis pada update berisi data.

## Pembaruan 18 November 2026

- Cetak Rapor wali kelas: pagination 10 siswa per halaman, nomor urut berlanjut antarhalaman, dan kolom Nama Arabic yang dapat diedit langsung. Cetak Semua tetap mengambil seluruh siswa kelas, bukan hanya halaman aktif.
- Tabel Siswa admin: kolom Nama Orang Tua diganti Nama Arabic. Data orang tua tetap tersimpan; hanya tampilan kolom yang diganti.
- Pada kedua tabel, tombol Simpan telah dihapus atas permintaan pengguna. Nama Arabic disimpan saat input kehilangan fokus (`wire:blur`), termasuk klik tempat lain atau Tab. Enter memanggil `$el.blur()` agar memakai alur simpan yang sama. Input mendukung RTL, maksimal 255 karakter, nilai kosong menjadi null, dan indikator Tersimpan tersedia.
- Penyimpanan Nama Arabic memeriksa akses: wali kelas hanya siswa kelas yang diwalikan pada sekolahnya; admin mengikuti kategori Putra/Putri dan sekolah; Super Admin tetap dibatasi sekolah pada aksi simpan ini. Catatan wali kelas juga memverifikasi keanggotaan siswa dan semester tahun ajaran kelas.
- Pengujian lokal tabel: 8 tes, 28 assertion lulus (tes sementara `/private/tmp/AdminArabicTableTest.php` dan `/private/tmp/WaliRaporTableTest.php`, belum menjadi suite regresi di repositori). Kompilasi Blade, Pint, dan build Vite berhasil. Aset build yang dihasilkan `app-Br1n9PAa.css`.
- Pengguna berhasil pull hosting dari `e6d1d47` ke `7ba581f` (perubahan input Nama Arabic). Penyalinan build awal gagal karena variabel `WEB_ROOT` kosong dan mengarah ke `/build`; instruksi menetapkan ulang variabel ke `$HOME/public_html/rpi.ppsyafaaturrasul.com` telah diberikan. Output keberhasilan penyalinan ulang tidak dikirim.
- Diagnosis Cetak PDF hosting: GD dan Mbstring asli aktif di CLI; tes mPDF sederhana berhasil menghasilkan PDF 14689 byte. Route admin Cetak PDF terdaftar. Tangkapan layar menunjukkan 404 untuk siswa 452 dan semester 5, bukan error renderer PDF. Pengguna menemukan penyebabnya adalah semester yang dipilih tidak sesuai data dan menyatakan sudah menemukan solusi. Tidak ada perubahan konfigurasi PDF pada diagnosis ini.
- Perbaikan terbaru tampilan Tahun Ajaran: `resources/views/livewire/admin/rapor/index.blade.php` mengganti `$ta->nama` menjadi `$ta->tahun`; `resources/views/livewire/wali-kelas/rapor/index.blade.php` mengganti `$kelas->tahunAjaran->nama` menjadi `$kelas->tahunAjaran->tahun`. Model TahunAjaran memakai kolom `tahun`, tanpa atribut `nama`, sehingga label sebelumnya kosong.
- Perbaikan Tahun Ajaran hanya memperbaiki label yang sudah ada: admin memiliki dropdown, wali kelas menampilkan tahun pada informasi kelas dan belum memiliki dropdown Tahun Ajaran tersendiri. Tidak menambahkan pemilihan lintas tahun/kelas.
- Kompilasi Blade dan `git diff --check` untuk perbaikan label Tahun Ajaran berhasil. Tidak ada migrasi atau perubahan dependensi. Perbaikan label masih lokal; belum ada push atau deploy yang dilakukan/dikonfirmasi untuk perubahan ini.
