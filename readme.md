# Code-Pad

[![CI Pipeline](https://github.com/ncs-jss/Code-Pad/actions/workflows/ci.yml/badge.svg)](https://github.com/ncs-jss/Code-Pad/actions/workflows/ci.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE.md)

**Code-Pad** is a collegiate competitive programming platform for teacher-student interaction. Teachers organize timed programming events and assignments, and students write, compile, run, and benchmark their code against test cases in real time.

---

## 🚀 Key Modernizations & Fixes

1. **Modern Code Execution Engine**
   - Replaced retired HackerEarth API v3 endpoints with **Piston API** (default, free, over secure HTTPS, no API key needed).
   - Added support for **Judge0 CE** and **HackerEarth v4** via environment variables.
   - Removed cleartext secrets and added dynamic multi-language code compilation (C, C++, Python, Java, JavaScript, PHP).
2. **Security & Vulnerability Hardening**
   - Eliminated **PHP Object Injection / Remote Code Execution (RCE)** vulnerabilities caused by native `unserialize()` on user data.
   - Introduced `SecurityHelper` enforcing safe JSON serialization with strict class deserialization guards (`allowed_classes => false`).
   - Removed hardcoded default passwords (`helloworld`) from database seeders, replacing them with configurable environment secrets.
3. **Containerized Production Infrastructure**
   - Provided production-ready `Dockerfile` and `docker-compose.yml` (PHP 7.4 Apache + MySQL 5.7).
   - Added GitHub Actions CI pipeline (`.github/workflows/ci.yml`).

---

## 🐳 Quick Start with Docker (Recommended)

The easiest and most reproducible way to run Code-Pad locally or in production is with Docker.

### 1. Clone & Configure Environment

```bash
cp .env.example .env
```

Review `.env` and configure keys if needed:
- `CODE_EXECUTION_DRIVER=piston` (Options: `piston`, `judge0`, `hackerearth`)
- `SEED_ADMIN_PASSWORD=AdminSecure@CodePad2026!`
- `SEED_TEACHER_PASSWORD=TeacherSecure@CodePad2026!`
- `SEED_STUDENT_PASSWORD=StudentSecure@CodePad2026!`

### 2. Start Containers

```bash
docker-compose up -d --build
```

### 3. Initialize Application & Database

```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

Open your browser at: **[http://localhost:8000](http://localhost:8000)**

---

## 🔑 Default Seeded Accounts

| Role | Username / Email | Default Password (Configurable in `.env`) |
| :--- | :--- | :--- |
| **Admin** | `admin@admin.com` | `AdminSecure@CodePad2026!` |
| **Teacher** | `teacher@jssaten.com` | `TeacherSecure@CodePad2026!` |
| **Student** | `15cse075` (Admission No.) | `StudentSecure@CodePad2026!` |

---

## 🛠️ Code Execution Engines

Configure your execution engine in `.env`:

### Piston (Default)
```env
CODE_EXECUTION_DRIVER=piston
PISTON_API_URL=https://emkc.org/api/v2/piston/execute
```

### Judge0
```env
CODE_EXECUTION_DRIVER=judge0
JUDGE0_API_URL=https://ce.judge0.com
JUDGE0_API_KEY=your_optional_api_key
```

---

## 📜 License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
