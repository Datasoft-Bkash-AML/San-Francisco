# San Francisco Demo Website

This project replicates the San Francisco demo site using PHP and MySQL.

## Environment Information

- PHP: Compatible with PHP 7.3+ (code has been kept compatible with macOS bundled PHP 7.3). Recommended: PHP 7.3–8.3 depending on your environment.
- MariaDB/MySQL: tested with MySQL/MariaDB versions in the 5.7+/10.x range.

## Prerequisites

- PHP with PDO extension (7.3+ recommended)
- MySQL 5.7+ (or MariaDB) OR the built-in SQLite fallback
- Apache or another web server

## Setup Instructions

1. **Clone or copy the project**
   ```bash
   git clone <repository-url> San-Francisco
   cd San-Francisco
   ```

2. **Create the database**
   - Import the SQL schema and seed data:
     ```bash
     mysql -u <username> -p < database.sql
     ```
   - This will create a database named `sanfrancisco` with the required tables.

3. **Configure database connection**
   You can configure database settings using environment variables or a `.env` file.

   a) Using environment variables (recommended):

   - Set `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_CHARSET`.
   - If you want to force the app to use MySQL and not fall back to SQLite, set `PREFER_MYSQL=1`.

   Example (macOS/Linux):
   ```bash
   export DB_HOST=192.168.14.252
   export DB_PORT=3306
   export DB_NAME=babui_test
   export DB_USER=sohel
   export DB_PASS='Remi@123'
   export PREFER_MYSQL=1
   php -S localhost:8000
   ```

   b) Using a `.env` file (convenient for local development):

   - Copy the provided `.env.example` to `.env` and edit the values.
   - The project contains a tiny `tools/dotenv.php` loader that will auto-load `.env` when present.

   Example `.env` (copy from `.env.example`):
   ```text
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_NAME=sanfrancisco
   DB_USER=root
   DB_PASS=
   DB_CHARSET=utf8mb4
   PREFER_MYSQL=0
   ```

   Notes:
   - Environment variables override `.env` if both are present.
   - With `PREFER_MYSQL=0` (default), the app attempts MySQL and will fall back to the included SQLite file `data/sanfrancisco.sqlite` if MySQL can't be reached.

4. **Place product images**
   - Copy your product images into the `images/` directory.
   - Ensure the image filenames match the entries in the `products` table (for demo, `product-a.jpg`, etc.).

5. **Serve the project locally**
   - If using PHP's built-in server (for development):
     ```bash
     php -S localhost:8000
     ```
   - Or configure Apache virtual host pointing document root to this project folder.

6. **View in browser**
   - Navigate to `http://localhost:8000/index.php` (or your server URL).
   - Click on product categories to filter and use the "Explore Products" button to scroll.

## Project Structure

```
San-Francisco/
├── css/
│   └── style.css
├── images/
│   └── ... product images ...
├── includes/
│   ├── footer.php
│   └── header.php
├── js/
│   └── script.js
├── config.php
├── database.sql
├── index.php
└── README.md
```

## Customization

- Edit CSS in `css/style.css` to adjust styling.
- Update JavaScript interactions in `js/script.js`.
- Add or modify categories and products via the `database.sql` or directly in MySQL.

## Demo data & seeding (local images and featured flag)

- This repository includes a small CLI seeder at `scripts/seed_products.php` which will create the demo categories (Headphones, Speakers, Accessories) and populate each with 10–15 deterministic products.
- The seeder now uses local images from the `images/` folder so the site renders reliably without external network calls. It also creates a `featured` column on the `products` table (if missing) and flags a few demo products as featured.

How to run the seeder (CLI):

```bash
php scripts/seed_products.php
```

What this does:
- Ensures a `featured` column exists (SQLite/MySQL compatible ALTER).
- Ensures the three categories exist.
- Inserts or updates products with local image filenames (examples: `product-a.jpg`, `2-936x1024.jpg`).

Admin UI:
- There is a "Seed Demo Products" button in the admin panel at `/admin/index.php` (requires admin login). That button triggers the same seeder logic from the web UI.
- The admin add/edit product form includes a "Featured" checkbox so you can toggle items manually.

Notes:
- If you previously seeded products using external URLs, running the seeder again will update image references to the local filenames used by the seeder.
- If your images folder is missing any of the filenames referenced by the seeder, the frontend will fall back to placeholders; add matching images to `images/` for full fidelity.

If you'd like me to also download a curated set of CC0 images into `images/` and re-run the seeder to use those, tell me and I'll add that next.

---

© 2025 San Francisco Demo

## Local development: PHP 7.3 + MySQL (or SQLite fallback)

This project is intentionally compatible with PHP 7.3 so you can run it on macOS or a lightweight Linux dev environment.

Minimum/Recommended PHP settings and extensions
- PHP 7.3+ (7.3 recommended to match macOS bundled CLI). Newer versions are also supported.
- Enable PDO and the PDO MySQL driver (for MySQL) and PDO SQLite (for fallback). On Debian/Ubuntu packages are typically named php7.3-mysql and php7.3-sqlite.
- Recommended php.ini settings for local dev (adjust path to your php.ini):

   - display_errors = On
   - error_reporting = E_ALL
   - memory_limit = 256M
   - date.timezone = UTC

Logging and debugging
- DB connection attempts and errors are logged to `logs/db.log` by the built-in development logger (`tools/logger.php`).
- Control logging via env var `DB_LOG`:
   - `DB_LOG=0` disables logging
   - unset or `DB_LOG=1` enables logging (default)
- Example: enable logging and start server
```bash
export DB_LOG=1
php -S localhost:8000
tail -f logs/db.log
```

Using MySQL locally
- Example steps to run locally with MySQL and persistent DB:
   1. Create a local MySQL database and user, then import schema:
       ```bash
       mysql -u root -p < database.sql
       ```
   2. Set env or `.env` values (see `.env.example`) and optionally `PREFER_MYSQL=1` to force use of MySQL.

Git workflow and cloning
- This repository includes a `.gitignore` entry for `.env`, `logs/`, and the local SQLite DB to avoid committing secrets or large generated files.
- To clone and run locally:
   ```bash
   git clone <repository-url>
   cd San-Francisco
   cp .env.example .env    # edit .env with your local DB creds
   php -S localhost:8000
   ```

Notes and troubleshooting
- If you see `could not find driver` in `logs/db.log` it means the required PDO driver is missing from your PHP install. Install the appropriate PHP extension (e.g. php7.3-mysql) and restart.
- The default behavior (`PREFER_MYSQL=0`) tries MySQL first and falls back to `data/sanfrancisco.sqlite` if MySQL is unreachable. Set `PREFER_MYSQL=1` to fail fast with MySQL-only behavior.
