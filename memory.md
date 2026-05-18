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