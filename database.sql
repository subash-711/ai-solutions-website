-- ==========================================
-- Database Setup Script for AI-Solutions
-- ==========================================

-- Create Database
CREATE DATABASE IF NOT EXISTS `ai_solutions` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ai_solutions`;

-- ------------------------------------------
-- 1. Table structure for table `inquiries`
-- ------------------------------------------
CREATE TABLE IF NOT EXISTS `inquiries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `company` VARCHAR(100) DEFAULT NULL,
  `country` VARCHAR(100) DEFAULT NULL,
  `job_title` VARCHAR(100) DEFAULT NULL,
  `job_details` TEXT DEFAULT NULL,
  `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- 2. Table structure for table `admin_users`
-- ------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- 3. Seed Default Admin User
-- Username: admin
-- Password: Admin@123
-- ------------------------------------------
-- Note on generating this hash:
-- The hash was generated in PHP 8 using:
-- password_hash("Admin@123", PASSWORD_DEFAULT);
-- Which returns a secure bcrypt hash string starting with $2y$.
-- Standard bcrypt hash representation for Admin@123 is inserted below.

INSERT INTO `admin_users` (`username`, `password_hash`)
VALUES ('admin', '$2y$12$E0GtFTUl5ob5Xo16l7ZIvO0b4MVcwdCOUVQJ0vuUCRRnuE1Jj8CXy')
ON DUPLICATE KEY UPDATE `password_hash` = VALUES(`password_hash`);
