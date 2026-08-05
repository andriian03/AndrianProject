-- phpMyAdmin SQL Dump

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Membuat Database
--
CREATE DATABASE IF NOT EXISTS `Project_AndrianDB` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `Project_AndrianDB`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--
CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `price` int NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `category` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--
CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `total` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Indexes & Keys
--

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_user` (`user_id`),
  ADD KEY `fk_orders_product` (`product_id`);

--
-- AUTO_INCREMENT
--

ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Foreign Keys / Constraints
--

ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- --------------------------------------------------------

--
-- DML (Data Manipulation Language)
--

INSERT INTO `products` (`id`, `name`, `description`, `image`, `price`, `stock`, `category`) VALUES
(1, 'Product 1', 'Description for Product 1', NULL, 100, 10, 'Category A'),
(2, 'Product 2', 'Description for Product 2', NULL, 200, 5, 'Category B'),
(3, 'Product 3', 'Description for Product 3', NULL, 150, 8, 'Category A'),
(4, 'Product 4', 'Description for Product 4', NULL, 300, 3, 'Category C'),
(5, 'Product 5', 'Description for Product 5', NULL, 250, 7, 'Category B');

SELECT * FROM `products`;

UPDATE `products` SET `stock` = 20 WHERE `id` = 1;

DELETE FROM `products` WHERE `id` = 5;