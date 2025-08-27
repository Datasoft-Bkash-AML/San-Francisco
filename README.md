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

---

© 2025 San Francisco Demo