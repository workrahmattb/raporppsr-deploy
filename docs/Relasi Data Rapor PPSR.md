---
title: "Relasi Data Rapor PPSR"
tags:
  - raporppsr
  - dokumentasi
updated: 2026-11-08
---

# Relasi Data Rapor PPSR

Kembali ke [[Beranda Rapor PPSR]]. Lihat juga [[Peta Fitur Rapor PPSR]].

Ringkasan berdasarkan model di `app/Models`. Ini peta relasi kode, bukan hasil inspeksi skema database yang berjalan.

| Model | Hubungan utama |
| --- | --- |
| Sekolah | User, TahunAjaran, SiswaRapor, GuruRapor, MataPelajaran |
| TahunAjaran | Sekolah, Semester, KelasRapor |
| Semester | TahunAjaran, Nilai, NilaiSikap, Kehadiran, CatatanWaliKelas, KenaikanKelas |
| KelasRapor | TahunAjaran, GuruRapor sebagai wali kelas, siswa melalui `kelas_siswa` |
| SiswaRapor | Kelas melalui pivot, nilai, sikap, kehadiran, catatan, kenaikan |
| GuruRapor | Sekolah, User, mata pelajaran melalui `guru_mata_pelajaran`, kelas wali |
| GuruMataPelajaran | Guru, mata pelajaran, tingkat |
| Nilai | Siswa, kelas, mata pelajaran, semester, guru |

## Penugasan guru

`guru_mata_pelajaran` menyimpan `guru_id`, `mata_pelajaran_id`, dan `tingkat`. Relasi kelas menggunakan kecocokan tingkat. Jangan mengasumsikan penugasan masih menggunakan kolom `kelas_id`.

## Periode akademik

User memiliki `session_tahun_ajaran_id` dan `session_semester_id`. Namun, keberadaan kolom tersebut tidak berarti semua fitur memakai periode pilihan pengguna. Form input nilai yang diperiksa mengambil semester dengan `is_active = true`.

## Sebelum mengubah database

Baca model dan migrasi terkait, lalu periksa skema aktual menggunakan Laravel Boost `database-schema`. Perubahan relasi harus mempertimbangkan data sekolah, tahun ajaran, dan semester.
