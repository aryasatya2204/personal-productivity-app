# 🚀 Personal Productivity App

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-v4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Composer](https://img.shields.io/badge/Composer-2.0-885630?style=for-the-badge&logo=composer&logoColor=white)

> **A Robust, MVC-based Personal Productivity Suite**  
> Aplikasi manajemen produktivitas *all-in-one* berbasis PHP Native (MVC) yang aman, modular, dan modern.  
> Menggabungkan **task management, notes, habit tracker, dan focus mode** dalam satu aplikasi responsif.

---

## 📋 Daftar Isi

- [✨ Fitur Utama](#-fitur-utama)
- [🛠 Arsitektur & Teknologi](#-arsitektur--teknologi)
- [⚙️ Prasyarat Sistem](#️-prasyarat-sistem)
- [🚀 Instalasi & Konfigurasi](#-instalasi--konfigurasi)
- [📂 Struktur Direktori](#-struktur-direktori)
- [👨‍💻 Panduan Pengembangan](#-panduan-pengembangan)
- [📄 Lisensi](#-lisensi)

---

## ✨ Fitur Utama

### 🔐 Autentikasi & Keamanan
- Login & Register dengan **password hashing (bcrypt)**
- **Google OAuth 2.0** (Sign In with Google)
- **Verifikasi Email** menggunakan token
- **Reset Password** dengan token kedaluwarsa (1 jam)
- Email service menggunakan **PHPMailer**

### ✅ Manajemen Tugas (Todos)
- CRUD tugas (Create, Read, Update, Delete)
- Subtasks (tugas bertingkat)
- Tagging system (Many-to-Many)
- Prioritas & pin task penting
- Due date & sorting

### 📝 Catatan (Notes)
- Folder kustom
- Pin catatan penting
- Struktur fleksibel untuk rich text

### 🧘‍♂️ Produktivitas Tambahan
- Habit Tracker dengan sistem streak
- Focus Mode (Pomodoro-style)
- Global Search (todos & notes)
- Dashboard statistik produktivitas

---

## 🛠 Arsitektur & Teknologi

### Backend
- **PHP:** 8.2+ (Native MVC)
- **Database:** MySQL / MariaDB (PDO)
- **Dependency Manager:** Composer
- **Library Utama:**
  - `vlucas/phpdotenv`
  - `phpmailer/phpmailer`
  - `league/oauth2-google`

### Frontend
- **Tailwind CSS v4**
- Mobile-first & responsive design
- Asset terkompilasi & minified

### Arsitektur Inti
- **Single Entry Point:** `public/index.php`
- **Adaptive Routing:** Support subfolder (XAMPP) & root domain (VPS)
- **Database Singleton Connection**

---

## ⚙️ Prasyarat Sistem

Pastikan environment memenuhi syarat berikut:

- PHP ≥ 8.1  
  Ekstensi: `pdo_mysql`, `curl`, `mbstring`
- Composer (global)
- Node.js & NPM
- MySQL ≥ 5.7

---

## 🚀 Instalasi & Konfigurasi

### 1️⃣ Clone Repository
```bash
git clone https://github.com/aryasatya2204/personal-productivity-app.git
cd personal-productivity-app
2️⃣ Install Dependency PHP
bash
Copy code
composer install
3️⃣ Setup Environment
bash
Copy code
cp .env.example .env
Isi konfigurasi .env:

env
Copy code
# Database
DB_HOST=localhost
DB_NAME=productivity_app_db
DB_USER=root
DB_PASS=

# App URL
BASE_URL=http://localhost/personal-productivity-app/public

# Google OAuth
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://localhost/personal-productivity-app/public/auth/google/callback

# SMTP
SMTP_HOST=smtp.gmail.com
SMTP_USER=your_email@gmail.com
SMTP_PASS=your_app_password
SMTP_PORT=587
4️⃣ Setup Database
sql
Copy code
CREATE DATABASE productivity_app_db;
Import tabel: users, todos, notes, habits, focus_logs, tags, dll.

5️⃣ Build Frontend (Tailwind)
bash
Copy code
npm install
npm run dev     # development
npm run build   # production
6️⃣ Jalankan Aplikasi
XAMPP / Laragon

ruby
Copy code
http://localhost/personal-productivity-app/public
PHP Built-in Server

bash
Copy code
cd public
php -S localhost:8000
📂 Struktur Direktori
plaintext
Copy code
personal-productivity-app/
├── app/
│   ├── controllers/
│   ├── models/
│   ├── services/
│   └── views/
│       ├── auth/
│       ├── layouts/
│       └── ...
├── config/
├── public/
│   ├── assets/
│   ├── uploads/
│   └── index.php
├── src/
├── vendor/
├── .env
├── composer.json
├── package.json
└── README.md
👨‍💻 Panduan Pengembangan
Menambah Controller
Buat file di app/controllers/NamaController.php

Nama class harus sama dengan nama file

Daftarkan route di public/index.php

Workflow Tailwind
❌ Jangan edit public/assets/css/output.css

✅ Edit:

app/views/

src/input.css

Jalankan:

bash
Copy code
npm run dev
📄 Lisensi
Project ini menggunakan MIT License

<p align="center"> Dibuat dengan ❤️ dan ☕ oleh <b>Arya Satya</b> </p> ```