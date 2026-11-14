-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2026 at 03:41 AM
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
(3, 'Samsung Refrigerator', 28999.00, 'refrigerator.JPG', 'Refrigerator designed to keep food fresh and cool', 'HOM', 1),
(4, 'Samsung Washing Machine', 18999.00, 'washing.JPG', 'Automatic washing machine used for cleaning clothes.', 'HOM', 1),
(5, 'LG Microwave Oven', 7999.00, 'microwave_2.JPG', 'Quick and conenient food preparation', 'KCH', 2),
(6, 'LG Air Fryer', 5999.00, 'airfryer_1.JPG', 'Kitchen Appliance that cooks food using hot air circulation.', 'KCH', 2),
(8, 'LG Air Conditioner', 24999.00, 'aircon.JPG', 'Designed to cool indooor spaces.', 'HOM', 2),
(9, 'Panasonic Rice Cooker', 2875.00, 'ricecooker_1.JPG', 'Rice cooker that automatically cooks rice and keeps it warm.', 'KCH', 3),
(10, 'Panasonic Electric Kettle', 1899.00, 'kettle_1.PNG', 'Used for boiling water quickly.', 'KCH', 3),
(11, 'Panasonic Vacuum Cleaner', 5499.00, 'vacuum_1.JPG', 'Used to remove dust and dirt.', 'HOM', 3),
(12, 'Panasonic Hair Dryer', 1299.00, 'hairdryer_1.JPG', 'Used for drying hair after washing.', 'PER', 3),
(13, 'Philips Air Fryer', 5999.00, 'airfryer_2.JPG', 'Air fryer that cooks food using hot air circulatio.', 'KCH', 4),
(14, 'Philips Blender', 2499.00, 'blender.JPG', 'used for mixing fruits and preparing smoothies.', 'KCH', 4),
(15, 'Philips Hair Dryer', 1499.00, 'hairdryer_2.JPG', 'for drying and styling hair.', 'PER', 4),
(16, 'Philips Electric Shaver', 1999.00, 'electricshaver.JPG', 'designed for personal grooming.', 'PER', 4),
(19, 'Sharp Air Purifier', 7999.00, 'airpurifier.JPG', 'Air purifier designed to help remove airborne particles.', 'HOM', 5),
(22, 'Hanabishi Electric Fan', 1799.00, 'electricfan_2.JPG', 'Electricfan that provides air circulation at home.', 'HOM', 6),
(23, 'Hanabishi Toaster', 899.00, 'toaster_1.JPG', 'Toaster designed for preparing crispy bread slices.', 'KCH', 6),
(24, 'Panasonic Blender', 2299.00, 'blender_1.JPG', 'Blender designed for mixing fruits and preparing drinks.', 'KCH', 3),
(25, 'LG Dishwasher', 32999.00, 'dishwasher_1.JPG', 'Dishwasher designed for cleaning plates and kitchen utensils.', 'KCH', 1),
(26, 'Philips Food Processor', 4999.00, 'foodprocessor_1.JPG', 'Food processor designed for chopping and preparing ingredients.', 'KCH', 4),
(27, 'Sharp Electric Oven', 5499.00, 'electricoven_1.JPG', 'Electric oven designed for baking and roasting food.', 'KCH', 5),
(30, 'LG Handmixer', 349.00, 'handmixer.JPG', 'Electric hand mixer designed for mixing ingredients and preparing baking recipes.', 'KCH', 2),
(31, 'Samsung Soundbar', 8999.00, 'soundbar.Jpg', 'Soundbar designed to improve home entertainment audio.', 'HOM', 1),
(32, 'LG Dehumidifier', 12999.00, 'dehumidifier.JPG', 'Dehumidifier designed to reduce excess moisture indoors.', 'HOM', 2),
(34, 'Philips Air Purifier', 6999.00, 'purifier.Jpg', 'Air purifier designed to help improve indoor air quality.', 'HOM', 4),
(35, 'Sharp Refrigerator', 19999.00, 'refrigerator_1.JPG', 'Refrigerator designed to keep food and beverages cool.', 'HOM', 5),
(36, 'Hanabishi Water Dispenser', 3499.00, 'dispenser.Jpg', 'Water dispenser designed to provide hot and cold water.', 'HOM', 6),
(37, 'Samsung Robot Vacuum', 14999.00, 'robotvacuum.JPG', 'Robot vacuum designed to clean floors automatically.', 'HOM', 1),
(38, 'LG Portable Air Conditioner', 22999.00, 'portairconditioner.JPG', 'Portable air conditioner designed to cool indoor spaces.', 'HOM', 2),
(39, 'Panasonic Facial Steamer', 3499.00, 'facialsteamer.JPG', 'Facial steamer designed for personal skincare routines.', 'PER', 3),
(40, 'Philips Hair Curler', 4718.00, 'curler.JPG', 'Hair curler designed for creating curls and waves.', 'PER', 6),
(42, 'Philips Body Groomer', 2299.00, 'bodygroomer.JPG', 'Body groomer designed for personal grooming and trimming.', 'PER', 4),
(43, 'Panasonic Electric Toothbrush', 3300.00, 'toothbrush_1.JPG', 'Electric toothbrush designed for everyday oral care.', 'PER', 3),
(44, 'Hanabishi Electric Shaver', 999.00, 'electricshaver_1.JPG', 'Electric shaver designed for personal grooming.', 'PER', 6),
(45, 'Philips Hair Straightening Brush', 2199.00, 'straighteningbrush.JPG', 'Heated brush designed for straightening and styling hair.', 'PER', 4),
(47, 'Samsung Stand Mixer', 8999.00, 'standmixer.JPG', 'Stand mixer designed for mixing and preparing baking ingredients.', 'KCH', 1),
(48, 'LG Bread Maker', 6499.00, 'breadmaker.JPG', 'Bread maker designed for preparing homemade bread.', 'KCH', 2),
(49, 'Panasonic Juicer', 3299.00, 'juicer.JPG', 'Juicer designed for extracting fresh juice from fruits and vegetables.', 'KCH', 3),
(50, 'Philips Hand Mixer', 1899.00, 'handmixer_1.JPG', 'Hand mixer designed for mixing and beating ingredients.', 'KCH', 4),
(51, 'Hanabishi Sandwich Maker', 1299.00, 'sandwichmaker.JPG', 'Sandwich maker designed for preparing toasted sandwiches.', 'KCH', 6),
(52, 'Samsung Dehumidifier', 10999.00, 'dehumidifier_1.JPG', 'Dehumidifier designed to reduce excess moisture indoors.', 'HOM', 2),
(53, 'LG Cordless Vacuum Cleaner', 12999.00, 'vacuum_1.JPG', 'Cordless vacuum cleaner designed for convenient floor cleaning.', 'HOM', 2),
(54, 'Panasonic Steam Iron', 1899.00, 'steamiron.JPG', 'Steam iron designed for removing wrinkles from clothes.', 'HOM', 3),
(55, 'Sharp Air Cooler', 5999.00, 'aircooler.JPG', 'Air cooler designed to provide cooling air indoors.', 'HOM', 6),
(56, 'Hanabishi Garment Steamer', 1599.00, 'garmentsteamer.JPG', 'Garment steamer designed for removing wrinkles from clothes.', 'HOM', 6),
(58, 'LG Electric Toothbrush', 1899.00, 'toothbrush_2.JPG', 'Electric toothbrush designed for everyday oral hygiene.', 'PER', 2),
(59, 'Hanabishi Rechargeable Personal Fan', 699.00, 'fan.JPG', 'Rechargeable personal fan designed for portable cooling.', 'PER', 6),
(60, 'Hanabishi Foot Spa', 1999.00, 'footspa.JPG', 'Foot spa designed for relaxing foot care at home.', 'PER', 6),
(61, 'Philips Epilator', 2499.00, 'epilator.JPG', 'Epilator designed for personal hair removal and grooming.', 'PER', 4);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
