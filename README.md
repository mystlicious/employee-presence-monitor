# Pemantau Kehadiran (Presence Monitor)

Aplikasi web ringan untuk memantau kehadiran pegawai di lingkungan kantor. Sistem ini menggabungkan **papan informasi digital (display mode)**, **formulir input kehadiran untuk pegawai**, dan **konsol admin** dalam satu portal yang responsif, mendukung bahasa **Indonesia & Inggris**, serta antarmuka bergaya glassmorphism modern.

> **Catatan teknis:** Proyek ini menggunakan pola mirip Laravel (routing, middleware, model, view) tetapi **bukan instalasi Laravel penuh** — entry point utama ada di `public/index.php` dengan router minimal di `routes/web.php`.

---

## Ringkasan

| Aspek | Keterangan |
|--------|------------|
| **Nama aplikasi** | Pemantau Kehadiran / In·Out |
| **Backend** | PHP 8+ (PDO MySQL) |
| **Basis data** | MySQL (`presence_monitor`) |
| **Frontend** | PHP views + Tailwind CSS (CDN) + Chart.js (admin) |
| **Autentikasi admin** | Kata sandi tunggal via `.env` (`ADMIN_PANEL_PASSWORD`) |
| **Bahasa UI** | Indonesia (default) & English |

---

## Fitur Utama

### 1. Portal Beranda (`/`)

Halaman landing dengan tiga akses utama:

- **Mode Tampilan** — dashboard untuk monitor/TV di area publik kantor
- **Formulir Input** — pegawai mencatat status kehadiran harian
- **Panel Admin** — konsol operasional (memerlukan login)

Desain portal menggunakan kartu glassmorphism; pada layar kecil kartu ditumpuk vertikal, pada desktop tampil tiga kolom.

### 2. Mode Tampilan / Display Mode (`/display-mode`)

Dashboard signage untuk layar besar (TV/monitor koridor):

- Statistik **Di Kantor** vs **Tidak di Kantor** secara real-time
- Jam dan tanggal lokal
- Daftar log kehadiran dengan foto pegawai, status berwarna, lokasi, catatan, dan waktu submit
- Filter tanggal dan status (Izin Keluar, WFH, Sakit, Cuti Tahunan, Dinas Luar)
- Auto-refresh halaman setiap 2 menit; rotasi kartu otomatis saat data banyak
- Responsif: pada tablet/ponsel layout menyesuaikan (kartu vertikal, tidak overlap)

### 3. Formulir Input Kehadiran (`/input-form`, `/mobile-mode`)

Form publik bagi pegawai untuk melaporkan status:

- Pencarian dan pemilihan nama pegawai (autocomplete dari master data)
- Status: **Izin Keluar**, **Sakit**, **Cuti Tahunan**, **WFH**, **Dinas Luar**
- Lokasi/tujuan, tanggal log, catatan opsional
- **Izin Keluar**: wajib mengisi jam mulai & selesai
- **Sakit / Cuti Tahunan / Dinas Luar**: dapat melampirkan bukti dokumen (PDF/JPG/PNG)
- Waktu submit dicatat otomatis oleh server
- Halaman terima kasih setelah berhasil mengirim

### 4. Konsol Admin (area `/admin` & `/admin-panel`)

Dilindungi kata sandi. Setelah login, admin dapat:

#### Beranda Admin (`/admin-panel`)

- Ringkasan jumlah pegawai, yang di kantor hari ini, dan tren absensi 7 hari
- Shortcut ke manajemen pegawai dan analitik

#### Manajemen Pegawai (`/admin/employees`)

- Direktori pegawai dengan filter tanggal, status, kategori ASN (PNS/PPPK/dll.), dan pencarian nama/NIP
- Tabel data dengan scroll horizontal di mobile & layout penuh di desktop
- Tambah / edit / hapus pegawai, unggah foto profil
- **Impor massal** dari file Excel (`.xlsx`, `.xls`)
- Detail pegawai per orang: riwayat log & bukti dokumen

#### Analitik (`/admin/dashboard`)

- Distribusi status harian (donut chart interaktif)
- Grafik tren ketidakhadiran (7 hari / 30 hari / 12 bulan)
- Filter per tanggal

#### Lainnya

- Login / logout admin
- **Toggle bahasa EN/ID** di sidebar (desktop) atau menu overlay (mobile)
- Unduh / pratinjau bukti absensi untuk log tertentu

---

## Arsitektur & Struktur Folder

```
presence-monitor/
├── app/
│   ├── Controllers/          # Logika dashboard analitik
│   ├── Http/
│   │   ├── Controllers/      # Employee CRUD, daftar, detail
│   │   └── Middleware/       # AdminAuth
│   └── Models/               # Employee, PresenceLog
├── bootstrap/
│   ├── app.php               # Bootstrap PDO, migrasi tabel otomatis
│   ├── helpers.php           # i18n, config, locale, auth helper
│   ├── admin_upload.php      # Upload foto pegawai
│   └── absence_proof_upload.php
├── config/                   # app.php, database.php, filesystem.php
├── database/migrations/      # Migrasi skema (referensi)
├── public/
│   ├── index.php             # Front controller
│   └── uploads/              # Foto pegawai (runtime)
├── resources/
│   ├── lang/                 # id.php, en.php (terjemahan UI)
│   └── views/                # Template halaman & partial admin
├── routes/web.php            # Router utama
├── storage/app/absence_proofs/  # Bukti dokumen (runtime)
├── .env.example
└── composer.json
```

---

## Persyaratan Sistem

- **PHP** ≥ 8.0 dengan ekstensi: `pdo`, `pdo_mysql`, `mbstring`, `fileinfo`, `gd` (disarankan untuk gambar)
- **Composer**
- **MySQL** 5.7+ / MariaDB 10.3+
- Web server (development: `php -S` atau `php artisan serve` jika menggunakan `artisan`)

---

## Instalasi Lokal

### 1. Clone repositori

```bash
git clone <url-repositori-github-anda>
cd presence-monitor
```

### 2. Dependensi PHP

```bash
composer install
```

### 3. Konfigurasi lingkungan

```bash
cp .env.example .env
```

Edit `.env`:

| Variabel | Deskripsi |
|----------|-----------|
| `APP_URL` | URL aplikasi (mis. `http://127.0.0.1:8000`) |
| `ADMIN_PANEL_PASSWORD` | **Wajib** — kata sandi panel admin (gunakan string panjang & acak di produksi) |
| `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Koneksi MySQL |
| `APP_TIMEZONE` | Opsional — default zona waktu (mis. `Asia/Jakarta`) |

> Jangan commit file `.env` ke GitHub. Pastikan `.env` ada di `.gitignore`.

### 4. Basis data

Aplikasi akan **membuat database otomatis** jika belum ada (syarat: user MySQL punya hak `CREATE DATABASE`), lalu membuat tabel `employees` dan `presence_logs` saat pertama kali dijalankan.

### 5. Data awal (produksi)

Repositori ini **tidak menyertakan** data pegawai, log kehadiran, atau file upload. Database produksi dimulai kosong; tambahkan pegawai lewat panel admin, lalu log kehadiran akan terbentuk dari form input pegawai.

### 6. Jalankan server

```bash
php artisan serve
```

atau:

```bash
php -S 127.0.0.1:8000 -t public
```

Buka: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## Rute Penting

| URL | Akses | Fungsi |
|-----|--------|--------|
| `/` | Publik | Portal beranda |
| `/display-mode` | Publik | Dashboard TV / signage |
| `/input-form` | Publik | Form input kehadiran |
| `/input-form/thanks` | Publik | Konfirmasi setelah submit |
| `/admin/login` | Publik | Login admin |
| `/admin-panel` | Admin | Beranda konsol |
| `/admin/employees` | Admin | Manajemen pegawai |
| `/admin/dashboard` | Admin | Analitik & grafik |
| `/admin/logout` | Admin | Keluar sesi |
| `/admin/locale` | Admin | Ganti bahasa (EN/ID) |

---

## Internasionalisasi (i18n)

- File terjemahan: `resources/lang/id.php` (default), `resources/lang/en.php`
- Locale disimpan di **session** + cookie `pm_locale`
- Fungsi `__('kunci')` dipakai di seluruh view
- Admin dapat mengganti bahasa tanpa kehilangan halaman aktif

---

## Keamanan (ringkas)

- Semua rute `/admin/*` dan `/admin-panel/*` (kecuali login & locale) memerlukan sesi admin aktif
- Kata sandi admin dibandingkan dengan `hash_equals()` (timing-safe)
- Redirect setelah login/locale dibatasi ke path same-site
- Upload file dibatasi tipe & disimpan di folder terpisah (`uploads/employees`, `storage/app/absence_proofs`)
- **Produksi:** set `APP_DEBUG=false`, gunakan HTTPS, kata sandi admin yang kuat, dan hak MySQL minimal

---

## Deploy ke GitHub / Produksi

### Sebelum push ke GitHub

1. Pastikan **`.env` tidak ikut** di commit (tambahkan ke `.gitignore`)
2. Pastikan folder runtime (`vendor/` bisa di-install ulang via Composer; `public/uploads/`, `storage/`) — commit `.gitkeep` saja, bukan file upload pengguna
3. Review tidak ada kata sandi atau secret di kode sumber

### Produksi (umum)

1. `composer install --no-dev --optimize-autoloader`
2. Set document root web server ke folder **`public/`**
3. Pastikan `public/uploads/` dan `storage/app/absence_proofs/` **writable** oleh user PHP
4. Isi `.env` produksi dengan `APP_DEBUG=false` dan `ADMIN_PANEL_PASSWORD` yang kuat

---

## Lisensi

Proyek internal / organisasi — sesuaikan lisensi dengan kebijakan institusi Anda jika akan didistribusikan publik.

---

## Kontak & Pengembangan

Untuk laporan bug atau penyesuaian fitur, buat issue di repositori GitHub atau hubungi tim pengembang internal yang memelihara proyek ini.
