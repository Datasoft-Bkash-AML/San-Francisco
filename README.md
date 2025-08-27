# San Francisco Demo Website

This project replicates the San Francisco demo site using PHP and MySQL.

## Environment Information

- PHP Version: 8.3.14
- MariaDB Version: 10.11.13

## Prerequisites

- PHP 7.4+ with PDO extension
- MySQL 5.7+ (or MariaDB)
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
   - Open `config.php` in the project root.
   - Update the variables `$host`, `$db`, `$user`, and `$pass` with your MySQL credentials.

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