-- database.sql: schema and seed data for San Francisco demo

CREATE DATABASE IF NOT EXISTS `sanfrancisco` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sanfrancisco`;

-- Categories table
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Products table
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `image` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data
INSERT INTO `categories` (`name`) VALUES
('Category One'),
('Category Two'),
('Category Three');

INSERT INTO `products` (`category_id`, `name`, `description`, `image`) VALUES
(1, 'Product A', 'Description for Product A', 'product-a.jpg'),
(1, 'Product B', 'Description for Product B', 'product-b.jpg'),
(2, 'Product C', 'Description for Product C', 'product-c.jpg'),
(3, 'Product D', 'Description for Product D', 'product-d.jpg');
