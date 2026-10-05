-- Luxury Club Perfume E-commerce Database Schema (MySQL 8 / MariaDB)

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `hero_slides`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `subscribers`;
DROP TABLE IF EXISTS `admin_users`;
DROP TABLE IF EXISTS `settings`;

-- 1. Categories
CREATE TABLE `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `cover_image` VARCHAR(255) NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Products
CREATE TABLE `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `size_label` VARCHAR(50) NOT NULL,
    `price` INT NOT NULL, -- Rupees
    `compare_at_price` INT NULL,
    `image` VARCHAR(255) NOT NULL,
    `tint` VARCHAR(20) NOT NULL DEFAULT '#F3EDE2',
    `hero_bg` VARCHAR(20) NULL,
    `badge` VARCHAR(50) NULL, -- 'Bestseller', 'New', 'Gift pick', 'Limited'
    `short_description` VARCHAR(300) NULL,
    `description` TEXT NULL,
    `notes_top` VARCHAR(255) NULL,
    `notes_heart` VARCHAR(255) NULL,
    `notes_base` VARCHAR(255) NULL,
    `how_to_use` TEXT NULL,
    `tags` VARCHAR(255) NULL,
    `stock_qty` INT NOT NULL DEFAULT 50,
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    `meta_title` VARCHAR(200) NULL,
    `meta_description` VARCHAR(300) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Hero Slides
CREATE TABLE `hero_slides` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NULL,
    `eyebrow` VARCHAR(100) NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `subline` VARCHAR(255) NOT NULL,
    `cta_label` VARCHAR(50) NOT NULL DEFAULT 'Explore Now',
    `cta_url` VARCHAR(255) NOT NULL DEFAULT '/shop',
    `cta2_label` VARCHAR(50) NULL,
    `cta2_url` VARCHAR(255) NULL,
    `image` VARCHAR(255) NOT NULL,
    `tint` VARCHAR(20) NOT NULL DEFAULT '#E3E7F3',
    `bg_color` VARCHAR(20) NOT NULL DEFAULT '#0E1633',
    `outline_word` VARCHAR(50) NOT NULL DEFAULT 'Luxury',
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT `fk_hero_slides_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Orders
CREATE TABLE `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_no` VARCHAR(50) NOT NULL UNIQUE,
    `customer_name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(25) NOT NULL,
    `address_line1` VARCHAR(255) NOT NULL,
    `address_line2` VARCHAR(255) NULL,
    `city` VARCHAR(100) NOT NULL,
    `state` VARCHAR(100) NOT NULL,
    `pincode` VARCHAR(20) NOT NULL,
    `subtotal` INT NOT NULL,
    `shipping` INT NOT NULL DEFAULT 0,
    `total` INT NOT NULL,
    `payment_method` VARCHAR(50) NOT NULL DEFAULT 'razorpay',
    `payment_status` VARCHAR(30) NOT NULL DEFAULT 'pending', -- pending, paid, failed, refunded
    `razorpay_order_id` VARCHAR(100) NULL,
    `razorpay_payment_id` VARCHAR(100) NULL,
    `status` VARCHAR(30) NOT NULL DEFAULT 'new', -- new, processing, shipped, delivered, cancelled
    `tracking_number` VARCHAR(100) NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Order Items
CREATE TABLE `order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `product_id` INT NULL,
    `name_snapshot` VARCHAR(150) NOT NULL,
    `price_snapshot` INT NOT NULL,
    `qty` INT NOT NULL DEFAULT 1,
    `line_total` INT NOT NULL,
    CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Messages (Contact form inquiries)
CREATE TABLE `messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(30) NULL,
    `topic` VARCHAR(100) NOT NULL,
    `message` TEXT NOT NULL,
    `ip` VARCHAR(45) NULL,
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Subscribers (Newsletter)
CREATE TABLE `subscribers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Admin Users
CREATE TABLE `admin_users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `last_login_at` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Settings (Key-Value Store)
CREATE TABLE `settings` (
    `key` VARCHAR(100) PRIMARY KEY,
    `value` LONGTEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
