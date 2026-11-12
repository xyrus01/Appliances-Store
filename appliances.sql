-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2026 at 05:10 PM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `appliances`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`) VALUES
(1, 'Samsung'),
(2, 'LG'),
(3, 'Panasonic'),
(4, 'Philips'),
(5, 'Sharp'),
(6, 'Hanabishi');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(3, 3, 2, 1),
(4, 3, 1, 1),
(8, 2, 21, 1),
(9, 2, 20, 1),
(10, 2, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` varchar(3) NOT NULL,
  `name` varchar(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
('HOM', 'Home'),
('KCH', 'Kitchen'),
('PER', 'Personal');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(70) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(70) NOT NULL,
  `description` varchar(300) NOT NULL,
  `category_id` varchar(3) NOT NULL,
  `brand_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `description`, `category_id`, `brand_id`) VALUES
(1, 'Samsung Microwave Oven', 6599.00, 'microwave_1.JPG', 'Microwave oven used for heating and cooking food.', 'KCH', 1),
(2, 'Samsung Induction Cooker', 4999.00, 'induction.JPG', 'Electric cooker designed for convenient cooking.', 'KCH', 1),
(3, 'Samsung Refrigerator', 28999.00, 'refrigerator.JPG', 'Refrigerator designed to keep food fresh and cool', 'HOM', 1),
(4, 'Samsung Washing Machine', 18999.00, 'washing.JPG', 'Automatic washing machine used for cleaning clothes.', 'HOM', 1),
(5, 'LG Microwave Oven', 7999.00, 'microwave_2.JPG', 'Quick and conenient food preparation', 'KCH', 2),
(6, 'LG Air Fryer', 5999.00, 'airfryer_1.JPG', 'Kitchen Appliance that cooks food using hot air circulation.', 'KCH', 2),
(7, 'LG smart TV', 25999.00, 'smarttv.JPG', 'Smart television for watching movies and digital entertainment.', 'HOM', 2),
(8, 'LG Air Conditioner', 24999.00, 'aircon.JPG', 'Designed to cool indooor spaces.', 'HOM', 2),
(9, 'Panasonic Rice Cooker', 2875.00, 'ricecooker_1.JPG', 'Rice cooker that automatically cooks rice and keeps it warm.', 'KCH', 3),
(10, 'Panasonic Electric Kettle', 1899.00, 'kettle_1.PNG', 'Used for boiling water quickly.', 'KCH', 3),
(11, 'Panasonic Vacuum Cleaner', 5499.00, 'vacuum.JPG', 'Used to remove dust and dirt.', 'HOM', 3),
(12, 'Panasonic Hair Dryer', 1299.00, 'hairdryer_1.JPG', 'Used for drying hair after washing.', 'PER', 3),
(13, 'Philips Air Fryer', 5999.00, 'airfryer_2.JPG', 'Air fryer that cooks food using hot air circulatio.', 'KCH', 4),
(14, 'Philips Blender', 2499.00, 'blender.JPG', 'used for mixing fruits and preparing smoothies.', 'KCH', 4),
(15, 'Philips Hair Dryer', 1499.00, 'hairdryer_2.JPG', 'for drying and styling hair.', 'PER', 4),
(16, 'Philips Electric Shaver', 1999.00, 'electricshaver.JPG', 'designed for personal grooming.', 'PER', 4),
(17, 'Sharp Rice cooker', 1899.00, 'ricecooker_2.JPG', 'Rice cooker designed for everyday rice preparation.', 'KCH', 5),
(18, 'Sharp Electric Fan', 2499.00, 'electricfan_1.JPG', 'Electricfan that provides air circulation and cooling.', 'HOM', 5),
(19, 'Sharp Air Purifier', 7999.00, 'airpurifier.JPG', 'Air purifier designed to help remove airborne particles.', 'HOM', 5),
(20, 'Hanabishi Rice Cooker', 1499.00, 'ricecooker_3.JPG', '1.8L rice cooker, ideal for everyday cooking.', 'KCH', 6),
(21, 'Hanabishi Electric kettle', 799.00, 'kettle_2.JPG', 'used for boiling water.', 'KCH', 6),
(22, 'Hanabishi Electric Fan', 1799.00, 'electricfan_2.JPG', 'Electricfan that provides air circulation at home.', 'HOM', 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(70) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `password`) VALUES
(1, 'Selah Andrada', '09987654321', 'selah@gmail.com', '$2y$12$0bgpgRI2/QxqVtKPMM9AF.ir1TMYuc1V4TcjaQnlPu3Bw9HAo6gRW'),
(2, 'Shalom Andrada', '09987654321', 'shalom@gmail.com', '$2y$12$oytv5GbbWm2xdyEvZqknHuz1DXcliv/CT0tG6lPlU9iV5lzKz14d2'),
(3, 'Xyrus Sagun', '09123456789', 'xyrus@gmail.com', '$2y$12$ueGbNTlMkveXVuI3yEOU3eDgb1Vl4kewycyNM0IEmhy2uKhI2o4z6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
