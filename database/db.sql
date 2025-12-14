-- =================================================================================
-- PROJECT: QUẢN LÝ CỬA HÀNG ĐIỆN THOẠI (O2O & SOCIAL LOGIN)
-- DATABASE FINAL VERSION
-- =================================================================================

CREATE DATABASE IF NOT EXISTS `db_quanlydienthoai` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_quanlydienthoai`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET FOREIGN_KEY_CHECKS = 0; -- Tắt kiểm tra khóa ngoại để reset DB

-- XÓA BẢNG CŨ
DROP TABLE IF EXISTS `order_items`, `orders`, `stock_movements`, `product_specs`, `products`, `promotions`, `customers`, `suppliers`, `brands`, `categories`, `users`, `roles`;

SET FOREIGN_KEY_CHECKS = 1;

-- =================================================================================
-- 1. QUẢN TRỊ & NHÂN SỰ
-- =================================================================================

-- Bảng Roles (4 Quyền chuẩn)
CREATE TABLE `roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) UNIQUE NOT NULL,
  `description` TEXT,
  `permissions` JSON COMMENT 'Danh sách quyền của role',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng Users (Nhân viên)
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `role_id` INT NOT NULL,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE,
  `phone` VARCHAR(15),
  `avatar` VARCHAR(255) COMMENT 'Ảnh đại diện nhân viên',
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`)
);

-- =================================================================================
-- 2. KHÁCH HÀNG (LOGIN GOOGLE + PASSWORD)
-- =================================================================================

CREATE TABLE `customers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  -- Thông tin định danh (Bắt buộc)
  `email` VARCHAR(100) UNIQUE NOT NULL COMMENT 'Email là khóa duy nhất để check trùng',
  `password` VARCHAR(255) NULL COMMENT 'Mật khẩu Hash (Bắt buộc nhập bổ sung nếu login Google lần đầu)',
  
  -- Thông tin hiển thị
  `full_name` VARCHAR(100) NOT NULL COMMENT 'Lấy từ Google hoặc nhập tay',
  `phone` VARCHAR(15) NULL,
  `avatar` VARCHAR(255) NULL COMMENT 'Link ảnh (Google hoặc Upload)',
  
  -- Thông tin Mạng xã hội
  `google_id` VARCHAR(50) UNIQUE NULL COMMENT 'ID từ Google API',
  `facebook_id` VARCHAR(50) UNIQUE NULL COMMENT 'ID từ Facebook API',
  
  -- Thông tin thành viên
  `points` INT DEFAULT 0 COMMENT 'Điểm tích lũy',
  `address` TEXT COMMENT 'Địa chỉ giao hàng mặc định',
  `city` VARCHAR(100),
  `status` ENUM('active', 'locked') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =================================================================================
-- 3. KHO & SẢN PHẨM
-- =================================================================================

CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) UNIQUE NOT NULL,
  `description` TEXT
);

CREATE TABLE `brands` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) UNIQUE NOT NULL,
  `origin` VARCHAR(100),
  `logo` VARCHAR(255)
);

CREATE TABLE `suppliers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) UNIQUE NOT NULL,
  `tax_code` VARCHAR(50) COMMENT 'Mã số thuế',
  `phone` VARCHAR(15),
  `email` VARCHAR(100),
  `address` TEXT
);

CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `brand_id` INT NOT NULL,
  `sku` VARCHAR(50) UNIQUE NOT NULL COMMENT 'Mã kho/Barcode',
  `name` VARCHAR(200) NOT NULL,
  `price` DECIMAL(12,2) NOT NULL COMMENT 'Giá bán',
  `cost` DECIMAL(12,2) DEFAULT 0 COMMENT 'Giá vốn',
  `quantity` INT DEFAULT 0,
  `image` VARCHAR(255),
  `warranty_months` INT DEFAULT 12 COMMENT 'Bảo hành (tháng)',
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`),
  FOREIGN KEY (`brand_id`) REFERENCES `brands`(`id`)
);

CREATE TABLE `product_specs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL UNIQUE,
  `screen` VARCHAR(100),
  `os` VARCHAR(50),
  `cpu` VARCHAR(100),
  `ram` VARCHAR(50),
  `rom` VARCHAR(50),
  `camera` VARCHAR(150),
  `battery` VARCHAR(100),
  `sim` VARCHAR(100),
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
);

CREATE TABLE `stock_movements` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `user_id` INT,
  `supplier_id` INT,
  `type` ENUM('in', 'out') NOT NULL,
  `quantity` INT NOT NULL,
  `ref_code` VARCHAR(50),
  `note` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`)
);

-- =================================================================================
-- 4. BÁN HÀNG & MARKETING
-- =================================================================================

CREATE TABLE `promotions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(20) UNIQUE NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `type` ENUM('fixed', 'percent') NOT NULL,
  `value` DECIMAL(12,2) NOT NULL,
  `min_order` DECIMAL(12,2) DEFAULT 0,
  `max_discount` DECIMAL(12,2) NULL,
  `start_date` DATETIME NOT NULL,
  `end_date` DATETIME NOT NULL,
  `usage_limit` INT DEFAULT 0,
  `status` TINYINT(1) DEFAULT 1
);

CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_code` VARCHAR(50) UNIQUE NOT NULL,
  `source` ENUM('web', 'store') DEFAULT 'web',
  
  `customer_id` INT,
  `user_id` INT,
  
  `subtotal` DECIMAL(15,2) NOT NULL,
  `discount` DECIMAL(15,2) DEFAULT 0,
  `tax` DECIMAL(15,2) DEFAULT 0,
  `total_money` DECIMAL(15,2) NOT NULL,
  
  `payment_method` ENUM('cash', 'card', 'transfer', 'cod') DEFAULT 'cod',
  `payment_status` ENUM('unpaid', 'paid') DEFAULT 'unpaid',
  `order_status` ENUM('pending', 'confirmed', 'shipping', 'completed', 'cancelled') DEFAULT 'pending',
  
  `shipping_name` VARCHAR(100),
  `shipping_phone` VARCHAR(15),
  `shipping_address` TEXT,
  `shipping_carrier` VARCHAR(50),
  `tracking_code` VARCHAR(50),
  
  `note` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `price` DECIMAL(12,2) NOT NULL,
  `imei_list` TEXT COMMENT 'Lưu IMEI máy bán ra',
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
);

-- =================================================================================
-- 5. TRIGGER & DỮ LIỆU MẪU
-- =================================================================================

DELIMITER $$
-- Trigger cập nhật kho
CREATE TRIGGER `update_stock` AFTER INSERT ON `stock_movements`
FOR EACH ROW BEGIN
    IF NEW.type = 'in' THEN UPDATE `products` SET `quantity` = `quantity` + NEW.quantity WHERE `id` = NEW.product_id;
    ELSEIF NEW.type = 'out' THEN UPDATE `products` SET `quantity` = `quantity` - NEW.quantity WHERE `id` = NEW.product_id;
    END IF;
END$$

-- Trigger tích điểm
CREATE TRIGGER `add_points` AFTER UPDATE ON `orders`
FOR EACH ROW BEGIN
    IF NEW.order_status = 'completed' AND OLD.order_status != 'completed' AND NEW.customer_id IS NOT NULL THEN
        UPDATE `customers` SET `points` = `points` + FLOOR(NEW.total_money / 100000) WHERE `id` = NEW.customer_id;
    END IF;
END$$
DELIMITER ;

-- Dữ liệu mẫu Roles
INSERT INTO `roles` (`id`, `name`, `description`, `permissions`) VALUES 
(1, 'Admin', 'Chủ cửa hàng - Toàn quyền quản lý hệ thống', '["*"]'),
(2, 'Manager', 'Quản lý - Quản lý sản phẩm, đơn hàng, kho, báo cáo', '["view-all","manage-products","manage-orders","manage-inventory","view-reports","manage-customers","manage-promotions"]'),
(3, 'Sales', 'Nhân viên bán hàng - POS, đơn hàng, xem sản phẩm, khách hàng', '["access-pos","manage-orders","view-products","view-customers"]'),
(4, 'Warehouse', 'Thủ kho - Quản lý kho, xem sản phẩm', '["manage-inventory","view-products"]');

-- User Admin
INSERT INTO `users` (`role_id`, `username`, `password`, `full_name`, `email`) VALUES 
(1, 'admin', '$2y$10$YourHashedPasswordHere', 'Administrator', 'admin@store.com');

-- Khách lẻ mặc định
INSERT INTO `customers` (`id`, `full_name`, `email`, `phone`) VALUES (1, 'Khách Vãng Lai', 'guest@store.com', '0000000000');
INSERT INTO `categories` (`name`) VALUES ('Điện thoại'), ('Phụ kiện');
INSERT INTO `brands` (`name`) VALUES ('Apple'), ('Samsung');

COMMIT;