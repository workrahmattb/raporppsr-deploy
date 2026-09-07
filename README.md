<div align="center">

# 📚 Rapor PPSR

**Sistem Informasi Manajemen Rapor Madrasah**
*Islamic School (Madrasah) Report Card Management System*

A modern, role-based academic reporting platform for Madrasah — covering master data, grade entry,
attendance, homeroom notes, printable report cards (rapor), and class ledgers (leger), with full
**Arabic & Hijri date support**.

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-4-FB50A0?style=flat-square&logo=livewire&logoColor=white)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-38B2AC?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net)
[![License](https://img.shields.io/badge/License-MIT-22c55e?style=flat-square)](LICENSE)

</div>

---

## ✨ Features

### 🏫 Admin — Master Data

- **Academic Years & Semesters** — manage `Tahun Ajaran` and `Semester` records with active-period flags; the active year is auto-preselected across the app.
- **Classes (Kelas)** — full CRUD with student roster management (`Manage Siswa`), including class-level promotion data.
- **Students (Siswa)** — full CRUD with profile detail view, optional NIS, entry date, and **Arabic name** support.
- **Teachers (Guru)** — teacher management with Arabic name support.
- **Subjects (Mata Pelajaran)** — subject CRUD grouped by education level (**VII – XII**: MTs & MA) with **Arabic subject names**.
- **Teacher Assignments (Penugasan Guru)** — assign teachers to subjects per education level.

### 📝 Grade Management (Guru)

- **Input Nilai** — teachers pick a class + their assigned subject and enter grades through a fast Livewire form (knowledge & skills scores).

### 🧑‍🏫 Homeroom Tools (Wali Kelas)

- **Attendance (Kehadiran)** — record sick / permitted / absent days per student per semester.
- **Homeroom Notes (Catatan)** — write narrative remarks per student for each semester.
- **Report Cards & Ledger** — preview and print rapor for their own class, including **batch printing for the entire class**.

### 🖨️ Report Card Engine

- **Per-student preview & print** — a print-ready rapor layout with identity box, grades, attendance, attitude scores, and signature section.
- **Batch print** — print all report cards for one class in a single request.
- **Configurable** — report date (Hijri-ready), and principal names for **MTs & MA** maintained in a singleton settings page.
- **Arabic & Hijri support** — Arabic-Indic numeral conversion (`١٢٣`) and Arabic text rendering in printed output.

### 📊 Class Ledger (Leger Kelas)

- Consolidated grade ledger per class, available to Admin and Wali Kelas.
- **One-click Excel export** with styled headings, column widths, and multiple sheets.

### 🔐 Authentication & Access Control

- Built on **Laravel Fortify** — login, optional **two-factor authentication**, profile & password management.
- Custom **role middleware** with three roles: `admin`, `guru` (teacher), and `wali_kelas` (homeroom teacher) — users may hold both teacher roles.
- **Per-user academic period** — each user's selected academic year & semester is stored on their account, so switching context never leaks between sessions.
- True **SPA-like navigation** via Livewire `wire:navigate` — no full page reloads, no jQuery.

---

## 👤 Roles & Permissions

| Role | Slug | Capabilities |
| --- | --- | --- |
| **Admin** | `admin` | Full master data management, teacher assignments, report settings, all rapor & leger access |
| **Guru** (Teacher) | `guru` | Grade entry for assigned classes & subjects |
| **Wali Kelas** (Homeroom) | `wali_kelas` | Attendance, homeroom notes, rapor printing, class ledger for their own class |

> Route access is enforced by a custom `role:` middleware alias registered in `bootstrap/app.php`.

---

## 🛠️ Tech Stack

| Technology | Version | Purpose |
| --- | --- | --- |
| [Laravel](https://laravel.com) | ^12.0 | Application framework |
| [Livewire](https://livewire.laravel.com) | ^4.0 | Reactive full-stack components (SPA behavior) |
| [Livewire Flux](https://fluxui.dev) | ^2.9 | UI component library |
| [Tailwind CSS](https://tailwindcss.com) | ^4.0 | Utility-first styling |
| [Vite](https://vite.dev) | ^7.0 | Frontend asset bundling |
| [Laravel Fortify](https://github.com/laravel/fortify) | ^1.30 | Headless authentication (login, 2FA) |
| [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf) | ^3.1 | PDF rendering for report cards |
| [carlos-meneses/laravel-mpdf](https://github.com/carlos-meneses/laravel-mpdf) | ^2.1 | PDF engine with Arabic/RTL support |
| [maatwebsite/excel](https://docs.laravel-excel.com) | ^3.1 | Excel exports (class ledger) |
| PHP | ^8.2 | Runtime |
| Database | SQLite / MySQL | SQLite by default, MySQL supported via `.env` |

---

## 🚀 Getting Started

### Prerequisites

- **PHP ≥ 8.2** with `pdo_sqlite` (or `pdo_mysql`), `mbstring`, `gd`, and `dom` extensions
- **Composer**
- **Node.js ≥ 20** and npm

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/your-username/rapor-ppsr.git
cd rapor-ppsr

# 2. Run the setup script (composer install, .env, app key, migrate, npm build)
composer setup
```

Or set it up manually:

```bash
composer install
cp .env.example .env
php artisan key:generate

# Configure your database in .env (SQLite works out of the box):
# touch database/database.sqlite

php artisan migrate --seed
npm install && npm run build
```

### Run the application

```bash
php artisan serve
# Open http://localhost:8000
```

<details>
<summary><b>🔑 Default credentials (seeded)</b></summary>

| Field | Value |
| --- | --- |
| Login URL | `/login` |
| Username | `admin` |
| Password | `admin123` |

> ⚠️ **Important:** change the default password immediately in any non-local environment.

</details>

<details>
<summary><b>🎬 Optional: seed demo data</b></summary>

A test data seeder is available for development and screenshots:

```bash
php artisan db:seed --class=RaporTestDataSeeder
```

</details>

---

## 🧑‍💻 Development

Run the full development stack (Laravel server, queue worker, log tail, Vite dev server) with one command:

```bash
composer dev
```

### Code quality

The project uses [Laravel Pint](https://github.com/laravel/pint) for code style:

```bash
composer lint       # fix style issues
composer test:lint  # check only
composer test       # lint + test suite
```

---

## 📂 Project Structure

```
├── app/
│   ├── Exports/                  # Excel export classes (Leger Kelas)
│   ├── Http/Middleware/          # RoleMiddleware (role: alias)
│   ├── Livewire/
│   │   ├── Admin/                # Master data, Rapor, Leger, Rapor Settings
│   │   │   ├── Guru/             # Teacher CRUD
│   │   │   ├── Kelas/            # Classes + roster management
│   │   │   ├── LegerKelas/       # Class ledger + Excel export
│   │   │   ├── MataPelajaran/    # Subject CRUD
│   │   │   ├── PenugasanGuru/    # Teacher assignments
│   │   │   ├── Rapor/            # Report card preview & printing
│   │   │   ├── RaporSettings/    # Singleton settings page
│   │   │   ├── Siswa/            # Student CRUD + profile
│   │   │   └── TahunAjaran/      # Academic year & semester
│   │   ├── Guru/                 # InputNilai (grade entry)
│   │   ├── WaliKelas/            # Kehadiran, Catatan, Rapor, Leger
│   │   ├── Dashboard.php
│   │   └── SelectAcademicPeriod.php
│   └── Models/                   # Eloquent models
├── database/
│   ├── migrations/               # Schema (students, grades, attendance, rapor…)
│   └── seeders/                  # Admin, school, and test data seeders
├── resources/views/
│   ├── livewire/                 # Component views (incl. print layouts)
│   └── layouts/                  # App & guest layouts
└── routes/web.php                # Role-grouped route definitions
```

---

## 🏗️ Architecture Highlights

- **Livewire-first design** — every feature is a Livewire component; controllers are only used for streaming print output. Business logic stays out of Blade templates.
- **Role middleware** — a lightweight `RoleMiddleware` guards route groups (`role:admin`, `role:guru,wali_kelas`) instead of pulling in a heavy ACL package.
- **Academic period on the user** — `users.session_tahun_ajaran_id` / `session_semester_id` hold each user's active context, set through the `SelectAcademicPeriod` component. All queries filter by the user's school (`sekolah_id`) and selected period.
- **PDF pipeline** — print views are plain Blade rendered through DomPDF/mPDF, keeping print layouts fully separate from screen views. mPDF is used where Arabic shaping and RTL matter.
- **Performance** — eager loading (`with()`) throughout to avoid N+1, and pagination on every listing.

---

## 🖼️ Screenshots

<!-- Drop screenshots into the docs/ folder and uncomment below.

| Dashboard | Report Card |
| --- | --- |
| ![Dashboard](docs/screenshot-dashboard.png) | ![Report Card](docs/screenshot-rapor.png) |

-->

*Coming soon — see [`docs/`](docs).*

---

## 🗺️ Roadmap

- [ ] Multi-school support (per-school isolation is already modeled via `sekolah_id`)
- [ ] Automated test coverage (Pest)
- [ ] REST API layer for mobile clients
- [ ] Bahasa Indonesia / English UI localization toggle
- [ ] Dark mode
- [ ] Student & parent self-service portal

---

## 🤝 Contributing

Contributions are welcome!

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

Please run `composer lint` before submitting to keep the codebase consistent.

---

## 🔒 Security

If you discover a security vulnerability, please **do not** open a public issue.
Report it directly to the maintainers via email instead.

---

## 📄 License

This project is open-sourced under the [MIT license](LICENSE).

---

## 🙏 Acknowledgements

- [Laravel](https://laravel.com) & the Livewire team
- [Tailwind CSS](https://tailwindcss.com) & [Flux UI](https://fluxui.dev)
- [DomPDF](https://github.com/dompdf/dompdf), [mPDF](https://mpdf.github.io), and [Laravel Excel](https://docs.laravel-excel.com)
- The maintainers, teachers, and staff of **PPSR** who shaped this system

---

<div align="center">
Made with ❤️ for Madrasah education
</div>
