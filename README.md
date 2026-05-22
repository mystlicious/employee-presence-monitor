# 📋 Pemantau Kehadiran · In/Out

> **Satu portal untuk melihat, mencatat, dan mengelola kehadiran pegawai** — dari monitor di lorong kantor hingga form di HP, plus dashboard admin yang rapi.

Antarmuka **glassmorphism**, responsif di HP & desktop, dan bisa **Indonesia / English** 🇮🇩 🇬🇧

---

## ✨ Apa ini?

**Pemantau Kehadiran** membantu tim mengetahui siapa yang ada di kantor, siapa yang izin/WFH/dinas, tanpa spreadsheet yang berantakan.

| | |
|:---:|:---|
| 📺 | **Tampilan publik** di TV/monitor |
| 📱 | **Input cepat** oleh pegawai |
| 🔐 | **Panel admin** untuk data & grafik |

---

## 🎯 Tiga pintu masuk

### 🏠 Portal Beranda — `/`

Tiga kartu besar, langsung pilih peran Anda:

- **Mode Tampilan** → untuk layar di area komunal  
- **Formulir Input** → pegawai update status sendiri  
- **Panel Admin** → HR/operator (perlu login)

---

### 📺 Mode Tampilan — `/display-mode`

Dashboard “signage” yang enak dilihat dari jauh:

- 🟢 **Di Kantor** vs 🔴 **Tidak di Kantor** — angka live  
- 🕐 Jam & tanggal lokal  
- 👤 Kartu pegawai: foto, status warna-warni, lokasi, catatan  
- 🔍 Filter tanggal & status (Izin Keluar, WFH, Sakit, Cuti, Dinas Luar)  
- ♻️ Auto-refresh & rotasi kartu saat banyak data  

---

### 📝 Formulir Input — `/input-form`

Pegawai isi sendiri dalam hitungan detik:

- 🔎 Cari nama → pilih dari daftar  
- Status: **Izin Keluar · Sakit · Cuti Tahunan · WFH · Dinas Luar**  
- 📍 Lokasi, tanggal, catatan  
- ⏱️ Izin Keluar → jam mulai & selesai  
- 📎 Sakit / Cuti / Dinas Luar → bisa upload bukti (PDF/gambar)  

---

### 🛡️ Panel Admin — `/admin-panel`

Konsol operasional setelah login:

| Area | Yang bisa dilakukan |
|------|---------------------|
| 🏡 **Beranda** | Ringkasan hari ini + shortcut |
| 👥 **Pegawai** | CRUD, foto, filter, impor Excel, riwayat per orang |
| 📊 **Analitik** | Donut chart status + tren 7 hari / 30 hari / 1 tahun |
| 🌐 **Bahasa** | Toggle EN / ID kapan saja |

---

## 🛠️ Dibangun dengan

PHP 8 · MySQL · Tailwind CSS · Chart.js — aplikasi ringan (router minimal, bukan Laravel full-stack).

---

## ⚡ Mulai cepat

```bash
git clone <url-repo-anda>
cd presence-monitor
composer install
cp .env.example .env
```

Isi `.env` — minimal yang wajib:

- `ADMIN_PANEL_PASSWORD` → kata sandi admin  
- `DB_*` → koneksi MySQL  

```bash
php artisan serve
```

Buka **http://127.0.0.1:8000** 🎉

Database & tabel dibuat otomatis saat pertama jalan. Repo ini **tidak berisi data pegawai atau log** — mulai dari nol lewat panel admin, lalu pegawai isi form.

---

## 🗺️ Peta URL

| URL | Siapa | Untuk apa |
|-----|-------|-----------|
| `/` | Semua | Portal utama |
| `/display-mode` | Publik | Monitor TV |
| `/input-form` | Pegawai | Catat kehadiran |
| `/admin/login` | Admin | Masuk |
| `/admin-panel` | Admin | Beranda konsol |
| `/admin/employees` | Admin | Data pegawai |
| `/admin/dashboard` | Admin | Grafik & insight |

---

## 🔒 Privasi data

Repositori sumber **kosong dari data pegawai nyata** — tidak ada nama, NIP, log, atau upload di git. Semua data hidup hanya di server & database Anda sendiri.

---

<p align="center">
  <sub>Dibuat untuk operasional kantor yang lebih transparan — <strong>In</strong> when you're here, <strong>Out</strong> when you're not.</sub>
</p>
