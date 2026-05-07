# Installation Guide (Windows)

This project is a Laravel 13 application with Vite/Tailwind for frontend assets.

## 1) Prerequisites

Install these tools first:

- PHP `8.3+` (required by `composer.json`)
- [Composer](https://getcomposer.org/download/)
- Node.js `20+` and npm
- MySQL Server `8.0+` (or compatible MySQL/MariaDB)
- Git (optional, but recommended for cloning/version control)

### Recommended PHP Extensions

Enable common Laravel extensions in your PHP installation:

- `openssl`
- `pdo`
- `mbstring`
- `tokenizer`
- `xml`
- `ctype`
- `json`
- `bcmath`
- `fileinfo`
- `mysqli` and `pdo_mysql` (for MySQL setup)

## 2) Get the Project

If cloning from GitHub:

```powershell
git clone <your-repo-url>
cd Karen_1
```

If you already have the folder, open a terminal in the project root:

```powershell
cd "c:\Users\Lenovo\Downloads\Karen_1"
```

## 3) Configure Environment

Copy environment file (if not already present):

```powershell
Copy-Item .env.example .env
```

Set your MySQL database values in `.env`:

- `DB_CONNECTION=mysql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=vehicle_maintenance` (or your preferred DB name)
- `DB_USERNAME=root` (or your MySQL user)
- `DB_PASSWORD=your_password`

Example `.env` DB block:

```powershell
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vehicle_maintenance
DB_USERNAME=root
DB_PASSWORD=
```

Create the database in MySQL before migrating:

```sql
CREATE DATABASE vehicle_maintenance;
```

## 4) Install Dependencies

Install PHP and Node dependencies:

```powershell
composer install
npm install
```

Generate app key and run migrations:

```powershell
php artisan key:generate
php artisan migrate
```

## 5) Run the App (Development)

### Option A: One-command dev stack (recommended)

This runs Laravel server, queue listener, logs, and Vite together:

```powershell
composer run dev
```

### Option B: Run services manually in separate terminals

Terminal 1:

```powershell
php artisan serve
```

Terminal 2:

```powershell
npm run dev
```

## 6) Access the Application

Open:

- `http://127.0.0.1:8000` (or the URL shown by `php artisan serve`)

## 7) Useful Commands

- Run tests: `composer test`
- Build assets for production: `npm run build`
- Re-run migrations: `php artisan migrate`

## Troubleshooting

- **`php` not recognized**: add PHP to your system `PATH`.
- **`composer` not recognized**: reinstall Composer and enable PATH integration.
- **Vite/Node errors**: ensure Node `20+` and delete/reinstall `node_modules`.
- **Database errors**: verify MySQL is running and `.env` credentials (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) are correct.

