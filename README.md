<p align="center">
  <img src="/public/images/logo/logo.png" width="200" alt="Logo BEM Unib">
</p>

# BEM Universitas Ibrahimy

Official website **Badan Eksekutif Mahasiswa Universitas Ibrahimy** — sistem informasi manajemen organisasi kemahasiswaan berbasis web. Dibangun untuk memudahkan pengelolaan data pengurus, program kerja, dokumentasi, surat-menyurat, berita, serta komunikasi dengan mahasiswa.

---

## Fitur

- **Manajemen Pengurus** — Data pengurus lengkap dengan foto, jabatan, dan departemen
- **Program Kerja** — Perencanaan, pelaporan, dan monitoring proker
- **Dokumentasi** — Galeri foto kegiatan
- **Berita & Informasi** — Publikasi berita organisasi
- **Surat Menyurat** — Manajemen surat masuk dan keluar
- **FAQ** — Pusat pertanyaan umum
- **Kontak & Pesan** — Form kontak publik dan manajemen pesan
- **Manajemen Role & Permission** — Kontrol akses berbasis peran (super-admin, admin, operator)
- **Multi-level jabatan** — Pimpinan, Pengurus Inti, Kementerian

---

## Teknologi

| Bagian | Teknologi |
|--------|-----------|
| Backend | Laravel 12 |
| Frontend | Vue 3 + Inertia.js |
| Styling | TailwindCSS v4 |
| Database | MySQL |
| Server | Apache (Docker) |
| Realtime | Laravel Reverb (WebSocket) |
| Auth | Spatie Laravel Permission |

---

## Clone & Development

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & npm
- Docker & Docker Compose

### Langkah-langkah

```bash
# 1. Clone repository
git clone https://github.com/AlbiGuntara/bem-unib.git
cd bem-unib

# 2. Copy environment file
cp .env.example .env

# 3. Install dependencies
composer install
npm install

# 4. Atur konfigurasi di .env
#    - DB_HOST=mysql (untuk Docker) atau 127.0.0.1 (untuk local)
#    - DB_DATABASE, DB_USERNAME, DB_PASSWORD sesuai kebutuhan

# 5. Jalankan Docker (MySQL + App)
docker compose up -d --build

# 6. Generate app key
docker compose exec app php artisan key:generate

# 7. Jalankan migrasi & seeder
docker compose exec app php artisan migrate --seed

# 8. Storage link
docker compose exec app php artisan storage:link

# 9. Jalankan Vite dev server (di host, bukan container)
npm run dev

# 10. Akses aplikasi
#     http://localhost:8001
```

### Default User (Seeder)

| Role | Email | Username | Password |
|------|-------|----------|----------|
| Super Admin | albigoentara@gmail.com | alby | alby1234 |

> **Penting:** Ubah password default setelah pertama login!

---

## Production Deployment

### 1. Clone di Server

```bash
git clone https://github.com/AlbiGuntara/bem-unib.git
cd bem-unib
```

### 2. Environment Setup

```bash
cp .env.example .env
```

Ubah konfigurasi di `.env`:

| Variable | Nilai Production |
|----------|-----------------|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://domain-anda.com` |
| `SESSION_SECURE_COOKIE` | `true` |
| `DB_*` | Gunakan kredensial database production |
| `MAIL_*` | Gunakan email khusus organisasi, bukan pribadi |
| `RECAPTCHA_*` | Daftar site & secret key baru untuk domain anda |

### 3. Generate APP_KEY

```bash
php artisan key:generate
```

### 4. Install Dependencies

```bash
composer install --optimize-autoloader --no-dev
npm install && npm run build
```

### 5. Database

```bash
php artisan migrate --seed
```

### 6. Storage Link

```bash
php artisan storage:link
```

### 7. Optimasi Cache

```bash
php artisan route:cache
php artisan config:cache
php artisan view:cache
```

### 8. Generate Route untuk Frontend

```bash
php artisan ziggy:generate
```

### 9. Web Server

Arahkan document root ke folder `public/`.

Jika menggunakan Docker untuk production:

```bash
docker compose up -d --build
```

> **Catatan:** Sesuaikan `docker-compose.yml` — jangan gunakan kredensial default untuk production. Gunakan file `.env` atau Docker secrets untuk menyimpan password.

---

## Struktur Direktori

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Controller untuk halaman admin
│   │   │   └── ...             # Controller untuk halaman publik
│   │   └── Requests/           # Form request validation
│   ├── Models/                 # Eloquent models
│   └── ...
├── config/                     # Laravel config files
├── database/
│   ├── migrations/             # Skema database
│   └── seeders/                # Data awal
├── resources/
│   ├── js/                     # Vue 3 components & pages
│   │   ├── Components/         # Shared components (Tabel, dll)
│   │   └── Pages/              # Halaman per route
│   └── views/                  # Blade template (app layout)
├── routes/
│   └── web.php                 # Semua route aplikasi
├── storage/
│   └── app/public/             # File uploads
├── docker-compose.yml
├── Dockerfile
└── ...
```

---

## Lisensi

Hak cipta milik BEM Universitas Ibrahimy.
