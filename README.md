<p align="center">
  <img src="/public/images/logo.png" width="200" style="margin: 0 8px;" alt="Logo BEM Unib">
  <img src="/public/images/logo-kabinet.png" width="200" style="margin: 0 8px;" alt="Logo BEM Unib">
  <img src="/public/images/log0-kps.png" width="200" style="margin: 0 8px;" alt="Logo BEM Unib">
</p>

# BEM Universitas Ibrahimy

Official website **Badan Eksekutif Mahasiswa Universitas Ibrahimy**.
website ini dibangun bertujuan untuk memudahkan manajemen orgnasisai, pengelolaan arsip, serta menyediakan sistem informasi modern yang terintegrasi dengan kebutuhan mahasiswa.

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: [Laravel 12](https://laravel.com/)
- **Frontend**: [TailwindCSS](https://tailwindcss.com/) + [Inertia.js](https://inertiajs.com/) + [Vue 3](https://vuejs.org/)
- **Database**: MySQL
- **Server**: Apache (via Docker)
- **Containerization**: Docker & Docker Compose

---

## ⚙️ Instalasi & Setup

### 1. Clone Repository

```bash
git clone https://github.com/AlbiGuntara/bem-unib.git
cd bem-unib
```

### 2. Install TailwindCSS Vite

```bash
npm install
```

### 3. Tambahkan File Environment

```bash
composer install
```

### 4. Konfigurasi Environment

Atur koneksi database di file .env sesuai kebutuhan:

### 5. Jalankan Docker

Pastikan sudah meginstall docker di perangkat masing-masing. Lalu jalankan perintah:

```bash
docker compose up -d --build
```

### 6. Jalankan TailwindCSS vite

```bash
npm run dev
```

### 7. Migrasi & Seeder

```bash
docker compose exec app
php artisan migrate --seed
```

### 8. Akses Aplikasi

Buka browser:
👉 http://localhost:8001
