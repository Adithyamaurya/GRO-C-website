-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2025 at 06:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gro_c`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('Pending','Completed','Shipped','Canceled') NOT NULL DEFAULT 'Pending',
  `email` varchar(255) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `seller_id`, `customer_name`, `order_date`, `total_amount`, `status`, `email`, `product_id`) VALUES
(1, 1, 'John Doe', '2025-02-28 10:00:00', 100.00, 'Shipped', NULL, NULL),
(2, 1, 'Jane Smith', '2025-02-27 14:30:00', 150.50, 'Shipped', NULL, NULL),
(3, 2, 'Alice Johnson', '2025-02-26 09:15:00', 200.75, 'Completed', NULL, NULL),
(4, 2, 'Bob Brown', '2025-02-25 16:45:00', 250.00, 'Canceled', NULL, NULL),
(5, 3, 'Charlie Davis', '2025-02-24 11:00:00', 300.25, 'Pending', NULL, NULL),
(6, 3, 'Diana Evans', '2025-02-23 13:30:00', 350.50, 'Completed', NULL, NULL),
(7, 1, 'Eve Foster', '2025-02-22 15:00:00', 400.75, 'Shipped', NULL, NULL),
(8, 2, 'Frank Green', '2025-02-21 17:30:00', 450.00, 'Canceled', NULL, NULL),
(9, 3, 'Grace Harris', '2025-02-20 12:00:00', 500.25, 'Pending', NULL, NULL),
(10, 1, 'Henry Irving', '2025-02-19 14:00:00', 550.50, 'Completed', NULL, NULL),
(11, 2, 'Aakash', '2025-02-28 16:22:50', 12.00, 'Pending', NULL, NULL),
(12, 2, 'Adithya  Maurya', '2025-03-26 15:36:40', 314.99, 'Canceled', '4427adithya@gmail.com', NULL),
(13, 1, 'Adarsh Maurya', '2025-03-26 16:06:52', 648.99, 'Pending', 'madarsh024@gmail.com', 11),
(14, 3, 'Adithya maurya', '2025-04-20 14:43:57', 613.99, 'Pending', '4427adithya@gmail.com', NULL),
(15, 2, 'Rahul Maurya', '2025-04-20 16:27:16', 314.99, 'Pending', 'rahul@gmail.com', NULL),
(18, 4, 'Mangesh Gupta', '2025-04-21 10:37:32', 343.99, 'Pending', 'mangesh@gmail.com', NULL),
(19, 1, 'Mangesh Gupta', '2025-04-21 12:26:54', 563.99, 'Shipped', 'madarsh024@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`) VALUES
(1, 12, 2, 1),
(2, 13, 18, 1),
(3, 13, 17, 1),
(4, 14, 3, 1),
(5, 15, 2, 1),
(6, 18, 14, 1),
(7, 18, 13, 1),
(8, 18, 11, 1),
(9, 19, 18, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `prevprice` decimal(10,2) NOT NULL,
  `weight` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `seller_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `image`, `description`, `price`, `prevprice`, `weight`, `category`, `seller_id`) VALUES
(1, 'Bread', 'images/product-1.jpg', 'A loaf of fresh bread made from whole wheat flour. Perfect for sandwiches and toast.', 50.00, 60.00, '500g', 'Bakery', 1),
(2, 'Elaichi', 'images/product-2.jpg', 'A pack of fresh elaichi (cardamom) pods. Adds a fragrant aroma to your dishes.', 300.00, 350.00, '100g', 'Spices', 2),
(3, 'Grains', 'images/product-3.png', 'A variety of grains including rice, wheat, and barley. Essential for a balanced diet.', 599.00, 699.00, '1kg', 'Grains', 3),
(4, 'Watermelon', 'images/product-4.jpg', 'A juicy watermelon, perfect for a refreshing summer treat.', 30.00, 40.00, '1.5kg', 'Fruits', 4),
(5, 'Oil', 'images/product-5.png', 'A bottle of pure cooking oil, ideal for frying and baking.', 70.00, 80.00, '1L', 'Cooking Essentials', 4),
(6, 'Mint', 'images/product-6.jpg', 'A bunch of fresh mint leaves, perfect for garnishing and adding flavor to your dishes.', 20.00, 30.00, '100g', 'Herbs', 3),
(7, 'Cabbage', 'images/product-7.png', 'A fresh cabbage, great for salads and stir-fries.', 50.00, 60.00, '1kg', 'Vegetables', 3),
(8, 'Salt', 'images/product-8.png', 'A pack of iodized salt, essential for seasoning your dishes.', 40.00, 50.00, '1kg', 'Condiments', 4),
(9, 'MDH Masala', 'images/product-9.png', 'A pack of MDH Masala, a blend of spices for adding flavor to your dishes.', 30.00, 40.00, '100g', 'Spices', 2),
(10, 'Atta', 'images/product-10.png', 'High-quality wheat grains, ideal for making flour and various dishes.', 599.00, 699.00, '1kg', 'Grains', 2),
(11, 'Ketchup', 'images/product-11.png', 'A bottle of tangy and delicious ketchup, perfect for adding flavor to your meals.', 69.00, 100.00, '500ml', 'Condiments', 4),
(12, 'Potato', 'images/product-12.png', 'Fresh potatoes, versatile and essential for many dishes.', 40.00, 50.00, '1kg', 'Vegetables', 3),
(13, 'Tomato', 'images/product-13.png', 'Fresh tomatoes, great for salads, sauces, and cooking.', 60.00, 80.00, '1kg', 'Vegetables', 3),
(14, 'Fish', 'images/product-14.png', 'Fresh fish, perfect for a variety of dishes.', 200.00, 250.00, '1kg', 'Seafood', 4),
(15, 'Yogurt', 'images/product-15.png', 'Fresh and creamy yogurt, perfect for a healthy snack or cooking.', 249.00, 300.00, '500g', 'Dairy', 1),
(16, 'Chicken Meat', 'images/product-16.png', 'Fresh chicken meat, perfect for a variety of dishes.', 1000.00, 1200.00, '1kg', 'Meat', 2),
(17, 'Milk', 'images/product-17.png', 'Fresh milk from local farms, rich in nutrients.', 94.00, 96.00, '1L', 'Dairy', 1),
(18, 'Paneer', 'images/product-18.png', 'Fresh paneer made from cow\'s milk, perfect for Indian dishes.', 549.00, 600.00, '1kg', 'Dairy', 1),
(24, 'Ghee', 'https://th.bing.com/th/id/OIP.FGwZuFCJocGvdwDi4SnVJQHaE7?w=306&h=204&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2', 'Shudh desi ghee', 600.00, 625.00, '1l', 'Condiments', 0),
(25, 'refined oil', 'https://th.bing.com/th/id/OIP.-RBJh_pMwW112n7DfHEPmAHaHa?w=250&h=250&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2', 'sarso oil', 250.00, 220.00, '1L', 'Cooking Essentials', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sellers`
--

CREATE TABLE `sellers` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sellers`
--

INSERT INTO `sellers` (`id`, `username`, `email`, `password`, `address`, `zip_code`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'ady', 'adithya@gmail.com', '$2y$10$nKoOFxOlOB9F/yVq3k/zNOyLh74vS5YluwIfCH1O0lDT3QjZ2k8dO', '1102/R1', '401107', '07208248380', '2025-02-26 14:51:57', NULL),
(2, 'ady', 'adithya1@gmail.com', '$2y$10$YL1RR6m.TkHyTLrZa2VCOOKeE9wLnsDdOh4XQk5iW0NxQQgbpXKJy', NULL, NULL, NULL, '2025-02-26 14:54:10', NULL),
(3, 'mangu seller', 'mangu@gmail.com', '$2y$10$YL1RR6m.TkHyTLrZa2VCOOKeE9wLnsDdOh4XQk5iW0NxQQgbpXKJy', 'dahisar', '401102', '7208248380', '2025-02-28 04:13:29', NULL),
(4, 'seller', 'seller@gmail.com', '$2y$10$iqpUeAZ5g0ztNY8ztykRB.U3IKlgEnlPKNHfc0mKNuGxvpuJ6kf7m', NULL, NULL, NULL, '2025-02-28 04:39:01', NULL),
(5, 'bhajiwala', 'bhajiwala@gmail.com', '$2y$10$w5pzrTI7IPgRT.0oqvdGI.yCc/92RvsfwhOc1vGk0KBflYZdPYmX6', NULL, NULL, NULL, '2025-04-21 06:49:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `address`, `zip_code`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Addy', 'adithyama012@gmail.com', '123', 'Adithya', 'Maurya', '1102/ man opus R1 / kashimira ', '401104', '7208248380', '2025-02-26 07:36:49', '2025-02-26 07:36:49'),
(2, 'admin_groc', 'admin_groc@gmail.com', '$2y$10$cxz8cgUPVo9OwNTKFH5EZuDYa6y4BcflZ4bSZfep4yZmGFqq0qH6a', NULL, NULL, 'Gro-C hub ', NULL, NULL, '2025-02-28 06:08:16', '2025-02-28 06:08:32'),
(6, 'adi', '4427adithya@gmail.com', '$2y$10$ZrZ5F/5d4iP4sZGPUZIPOOfxAM33e6pFMo8vaUHW40VQuZUBvGacW', NULL, NULL, '1102/R1', '401107', NULL, '2025-02-26 11:40:28', '2025-03-20 11:42:46'),
(7, 'mangu', 'mangesh@gmail.com', '$2y$10$gGruurEtPLssRPJbX.LoVeymEJ3yjEUq1BZY1pvsFjQ0ssBAXLAhy', NULL, NULL, 'jannat', NULL, NULL, '2025-02-26 11:42:13', '2025-02-26 11:42:13'),
(8, 'rahul', 'rahul@gmail.com', '$2y$10$GqfFvJBtDpZb4GLjSLrgnOVlO5KQXJrkFrtWikI2DMX8u8EqTl9lC', NULL, NULL, 'jannat ke upper', '401107', NULL, '2025-02-27 12:10:13', '2025-02-27 14:32:43'),
(10, 'adarsh12', 'madarsh034@gmail.com', '$2y$10$.0aBYRC7WQDYXnBBXP95aevdmUBcegF7oLQODop52TmjhYBJ9JcF2', NULL, NULL, '1102/R1 man opus', NULL, NULL, '2025-02-27 14:44:17', '2025-02-27 14:44:17'),
(11, 'adarsh12', 'madarsh024@gmail.com', '$2y$10$fhSSsfVmxbCVH0HSPhRN4Ouv6vCn1gBNzj1wi421PN84JccpZ.RkO', NULL, NULL, '1102/R1 man opus kashimira', '401107', NULL, '2025-02-27 15:14:51', '2025-02-27 15:15:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `fk_product_id` (`product_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sellers`
--
ALTER TABLE `sellers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `sellers`
--
ALTER TABLE `sellers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
