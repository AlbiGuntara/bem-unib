<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# 📚 GLAM - Perpustakaan Ibrahimy

Sistem informasi **GLAM (Gallery, Library, Archives, and Museum)** adalah aplikasi berbasis web yang dikembangkan untuk mendukung layanan digital di **Perpustakaan Universitas Ibrahimy**.  
Project ini bertujuan untuk meningkatkan literasi, pengelolaan arsip, serta menyediakan sistem informasi modern yang terintegrasi dengan kebutuhan civitas akademika.

---

## 🛠️ Teknologi yang Digunakan

-   **Backend**: [Laravel 12](https://laravel.com/)
-   **Frontend**: [TailwindCSS](https://tailwindcss.com/) + [Inertia.js](https://inertiajs.com/) + [Vue 3](https://vuejs.org/)
-   **Database**: MySQL
-   **Server**: Apache (via Docker)
-   **Containerization**: Docker & Docker Compose

---

## ⚙️ Instalasi & Setup

### 1. Clone Repository

```bash
git clone https://gitlab.com/ibrahimy-library/glam-i.git
cd glam-i
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

-   DB_CONNECTION=mysql
-   DB_HOST=mysql
-   DB_PORT=3306
-   DB_DATABASE=glam_db
-   DB_USERNAME=root
-   DB_PASSWORD=root

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
👉 http://localhost:8000
