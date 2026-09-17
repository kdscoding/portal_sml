# Portal SML

A Laravel-based data management portal for importing and tracking label data via Excel uploads, with inbound barcode scanning capabilities.

## Features

- **Dashboard** — Overview of upload progress, recent activities, and system status
- **Excel Import** — Upload and import `.xlsx`, `.xls`, or `.csv` files with automatic version management and real-time preview
- **Inbound Check** — Scan barcodes against imported data with audio alerts for unmatched items
- **Race-Condition Safe** — Atomic version generation using database transactions with row-level locking

---

## Clone Guide

Follow these steps to clone and set up the project on a different computer:

### Prerequisites

| Requirement | Minimum Version |
|-------------|----------------|
| PHP         | 8.3            |
| Composer    | 2.x            |
| MySQL       | 8.0+           |
| Git         | 2.30+          |
| Node.js     | 18+ (optional, for asset builds) |

### Step 1: Clone the Repository

Open a terminal and run:

```bash
git clone https://github.com/kdscoding/portal_sml.git
cd portal_sml
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Copy Environment File

```bash
copy .env.example .env
```

### Step 4: Configure Database

Edit `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_sml
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database manually if it does not exist:

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS portal_sml;"
```

### Step 5: Generate Application Key

```bash
php artisan key:generate
```

### Step 6: Run Migrations

```bash
php artisan migrate
```

### Step 7: Start the Development Server

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Open [http://localhost:8000](http://localhost:8000) in your browser.

---

## Application Routes

| Method | URL                       | Controller                  | Description                    |
|--------|---------------------------|-----------------------------|--------------------------------|
| GET    | `/`                       | DashboardController@index   | Dashboard overview             |
| GET    | `/inbound-check`          | InboundCheckController@index| Barcode scanning page          |
| GET    | `/inbound-check/versions` | InboundCheckController@versions | List upload versions       |
| POST   | `/inbound-check/scan`     | InboundCheckController@scan | Scan a barcode                 |
| GET    | `/inbound-check/progress` | InboundCheckController@progress | Check scan progress        |
| GET    | `/import`                 | ImportController@index      | Import upload page             |
| POST   | `/import`                 | ImportController@store      | Process uploaded file          |
| POST   | `/import/preview`         | ImportController@preview    | Preview file contents          |

---

## Project Structure

```
portal_sml/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php
│   │   ├── ImportController.php
│   │   └── InboundCheckController.php
│   ├── Imports/
│   │   └── DataLabelSbsiteImport.php
│   └── Models/
│       └── DataLabelSbsite.php
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   └── 2026_09_17_000000_create_data_label_sbsite_table.php
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   ├── dashboard.blade.php
│   │   ├── import.blade.php
│   │   └── inbound-check.blade.php
│   └── css/
├── routes/
│   └── web.php
├── storage/
├── public/
├── .env
├── .env.example
├── .gitignore
├── composer.json
└── README.md
```

---

## Key Configuration

### Import Version Management

The import system auto-generates version identifiers in `YYYYMMDD-NN` format (e.g., `20260918-01`). Versions are reserved atomically within a database transaction using row-level locking to prevent race conditions during concurrent uploads.

- Maximum sequential attempts: 10 (`-01` through `-10`)
- Fallback: Random `NN` between 10-99 if all sequential versions are taken

### Environment Variables

| Variable          | Description                | Default                 |
|-------------------|----------------------------|-------------------------|
| `APP_NAME`        | Application name           | Laravel                 |
| `APP_ENV`         | Environment                | local                   |
| `APP_DEBUG`       | Debug mode                 | true                    |
| `APP_URL`         | Base URL                   | http://localhost:8000   |
| `DB_CONNECTION`   | Database driver            | mysql                   |
| `DB_HOST`         | Database host              | 127.0.0.1               |
| `DB_PORT`         | Database port              | 3306                    |
| `DB_DATABASE`     | Database name              | portal_sml              |
| `DB_USERNAME`     | Database user              | root                    |
| `DB_PASSWORD`     | Database password          | laragon                 |

---

## Development Commands

```bash
php artisan serve          # Start dev server on port 8000
php artisan migrate        # Run database migrations
php artisan migrate:rollback  # Rollback last migration batch
php artisan route:list     # List all registered routes
php artisan view:clear     # Clear compiled views
php artisan config:clear   # Clear config cache
php artisan cache:clear    # Clear application cache
php artisan route:clear    # Clear route cache
php -l app/Http/Controllers/ImportController.php  # Check PHP syntax
```

---

## Database Schema

### `data_label_sbsite`

| Column            | Type        | Notes                |
|-------------------|-------------|----------------------|
| `id`              | BIGINT      | Primary key, auto-increment |
| `no_urut`         | INT         | Row number           |
| `upload_version`  | VARCHAR     | Version identifier   |
| `id_sb_site`      | VARCHAR     | Site ID              |
| `id_vendor`       | VARCHAR     | Vendor ID            |
| `po`              | VARCHAR     | Purchase order       |
| `item`            | VARCHAR     | Item name            |
| `country`         | VARCHAR     | Country              |
| `building`        | VARCHAR     | Building             |
| `cell`            | VARCHAR     | Cell                 |
| `sdd`             | DATE        | Date                 |
| `qty`             | INT         | Quantity             |
| `status_received` | BOOLEAN     | Default: false       |
| `created_at`      | TIMESTAMP   | Auto-generated       |
| `updated_at`      | TIMESTAMP   | Auto-generated       |

---

## Security Notes

- CSRF protection is enabled on all POST routes (Laravel `VerifyCsrfToken` middleware)
- All AJAX requests include `X-CSRF-TOKEN` header from `<meta name="csrf-token">`
- File uploads validate MIME type (`.xlsx`, `.xls`, `.csv`) and max size (10MB)
- Database operations use transactions with row-level locking for concurrent safety

---

## License

MIT License
