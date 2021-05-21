-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 21, 2021 at 05:03 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elevate`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/public/images/user/default.png',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `image`, `name`, `email`, `email_verified_at`, `password`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, '/public/images/user/default.png', 'admin', 'admin@admin.com', NULL, '$2y$10$NUFw/ZnlTp5Y7aW7pb9SIOaXsjBp/TII6o1zGa/Micg2s2MMClZAO', 1, 0, '2021-05-18 11:35:45', '2021-05-18 11:35:45');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `coach_id` int(11) DEFAULT NULL,
  `booking_date` datetime DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_status` int(11) DEFAULT 0,
  `status` int(11) DEFAULT 1,
  `is_deleted` int(11) DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `coach_id`, `booking_date`, `duration`, `category_id`, `amount`, `transaction_id`, `payment_status`, `status`, `is_deleted`, `createdAt`, `updatedAt`) VALUES
(1, 11, 9, '2021-05-21 00:00:00', '02:30', 1, 50, NULL, 0, 3, 0, '2021-05-21 07:09:26', '2021-05-21 11:57:11'),
(2, 11, 10, '2021-05-21 00:00:00', '02:30', 1, 50, NULL, 0, 1, 0, '2021-05-21 07:52:43', '2021-05-21 07:52:43');

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name_on_card` varchar(255) DEFAULT NULL,
  `card_number` varchar(255) DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `is_deleted` int(11) DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `user_id`, `name_on_card`, `card_number`, `expire_date`, `status`, `is_deleted`, `createdAt`, `updatedAt`) VALUES
(9, 11, 'John Doe', '88854448888', '2021-05-01', 1, 0, '2021-05-21 09:48:41', '2021-05-21 09:48:41'),
(10, 11, 'John Doe', '000854448888', '2021-05-01', 1, 0, '2021-05-21 09:48:48', '2021-05-21 09:48:48'),
(11, 11, 'John Doe', '88854448588', '2021-05-01', 1, 0, '2021-05-21 09:55:34', '2021-05-21 09:55:34');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/public/images/user/default.png',
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `image`, `category`, `status`, `is_deleted`, `createdAt`, `updatedAt`) VALUES
(1, '/public/images/category/1621338204.jpg', 'Football', 1, 0, '2021-05-18 06:13:24', '2021-05-18 06:15:41'),
(2, '/public/images/category/1621338363.jpg', 'Basket Ball', 1, 0, '2021-05-18 06:16:03', '2021-05-18 06:16:03'),
(3, '/public/images/category/1621338404.jpg', 'Athletics', 1, 0, '2021-05-18 06:16:44', '2021-05-18 06:16:44'),
(4, '/public/images/category/1621338449.jpg', 'Gymnastic', 1, 0, '2021-05-18 06:17:29', '2021-05-18 06:17:29'),
(5, '/public/images/category/1621338462.jpg', 'Gym', 1, 0, '2021-05-18 06:17:42', '2021-05-18 06:17:42'),
(6, '/public/images/category/1621338478.jpeg', 'Volleyball', 1, 0, '2021-05-18 06:17:58', '2021-05-18 06:17:58');

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE `favourites` (
  `id` int(11) NOT NULL,
  `coach_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `favourites`
--

INSERT INTO `favourites` (`id`, `coach_id`, `user_id`, `category_id`, `createdAt`, `updatedAt`) VALUES
(6, 9, 11, 1, '2021-05-21 13:11:05', '2021-05-21 13:11:05');

-- --------------------------------------------------------

--
-- Table structure for table `privacies`
--

CREATE TABLE `privacies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `privacies`
--

INSERT INTO `privacies` (`id`, `title`, `description`, `createdAt`, `updatedAt`) VALUES
(1, 'test', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.\r\n\r\nWhy do we use it?\r\nIt is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).\r\n\r\n', '2021-05-20 14:15:09', '2021-05-20 14:15:09');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `coach_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `coach_id`, `user_id`, `rating`, `comment`, `status`, `is_deleted`, `createdAt`, `updatedAt`) VALUES
(1, 9, 11, 4, 'One of best coach in our city', 1, 0, '2021-05-21 05:54:10', '2021-05-21 05:54:10'),
(2, 9, 12, 4, 'One of best coach in our city', 1, 0, '2021-05-21 05:54:19', '2021-05-21 05:54:19');

-- --------------------------------------------------------

--
-- Table structure for table `SequelizeMeta`
--

CREATE TABLE `SequelizeMeta` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `SequelizeMeta`
--

INSERT INTO `SequelizeMeta` (`name`) VALUES
('20210203124116-create-user.js'),
('20210204055009-create-category.js'),
('20210204090954-create-setting.js'),
('20210520062543-create-service.js'),
('20210520121200-create-privacy.js'),
('20210521054109-create-review.js'),
('20210521062856-create-appointment.js'),
('20210521092825-create-card.js'),
('20210521122029-create-favourite.js');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `coach_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `amount` double(10,2) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `coach_id`, `category_id`, `amount`, `status`, `is_deleted`, `createdAt`, `updatedAt`) VALUES
(3, 9, 1, 10.00, 1, 0, '2021-05-20 09:49:45', '2021-05-20 09:49:45'),
(4, 9, 2, 20.00, 1, 0, '2021-05-20 09:49:45', '2021-05-20 09:49:45'),
(5, 10, 1, 8.00, 1, 0, '2021-05-20 09:49:45', '2021-05-20 09:49:45');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `field_key` varchar(255) DEFAULT NULL,
  `field_value` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `is_deleted` int(11) DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/public/images/user/default.png',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `long` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1=>user,2=>vendor',
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `image`, `name`, `email`, `email_verified_at`, `password`, `phone`, `bio`, `document`, `experience`, `location`, `lat`, `long`, `otp`, `fcm_token`, `device_token`, `role`, `status`, `is_deleted`, `remember_token`, `createdAt`, `updatedAt`) VALUES
(9, '/backend/public/images/user/1621510534726.png', 'emma', 'saif.cloudwapp@gmail.com', NULL, '$2a$10$9VAdvbHRLQSC.MBj1MaJiuH8NaYEtXcvnvFri3OO/6f30NJX489wC', '9753244000', 'testeststsadf', '/backend/public/images/documents/1621512312047.png', 'afsfsdffs', 'indore', '22.7244', '75.8839', NULL, 'f4f45dsf4dsf5sd4f5fs5f45s', 'ffdfdsf74dsf864sf5f4asf564', '2', 1, 0, NULL, '2021-05-20 06:18:34', '2021-05-20 12:05:12'),
(10, '/public/images/user/default.png', 'saif1', 'saif1.cloudwapp@gmail.com', NULL, '$2a$10$ExrO8KY7H1LVUpWE6tWRcOVuJqIxFmH6y9mofVaHxMc.miucbB6.C', '7896541235', NULL, NULL, NULL, 'ujjain', '22.7244', '75.8839', NULL, 'f4f45dsf4dsf5sd4f5fs5f45s', 'ffdfdsf74dsf864sf5f4asf564', '2', 1, 0, NULL, '2021-05-20 11:57:03', '2021-05-20 11:57:03'),
(11, '/public/images/user/default.png', 'john', 'john.cloudwapp@gmail.com', NULL, '$2a$10$Bk21fxFeFsSKNY1xpFkrx.e4J20pgARcHh1E6CYMORl1AGPEOgS62', '1414145221', NULL, NULL, NULL, 'indore', '12111.22', '12245.22', NULL, 'f4f45dsf4dsf5sd4f5fs5f45s', 'ffdfdsf74dsf864sf5f4asf564', '1', 1, 0, NULL, '2021-05-20 12:22:06', '2021-05-21 05:50:28'),
(12, '/public/images/user/default.png', 'nathan', 'nathan.cloudwapp@gmail.com', NULL, '$2a$10$Bk21fxFeFsSKNY1xpFkrx.e4J20pgARcHh1E6CYMORl1AGPEOgS62', '4655555555', 'nathannathannathannathannathannathannathan', NULL, NULL, 'indore', '12111.22', '12245.22', NULL, 'f4f45dsf4dsf5sd4f5fs5f45s', 'ffdfdsf74dsf864sf5f4asf564', '1', 1, 0, NULL, '2021-05-20 12:22:06', '2021-05-21 05:50:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `privacies`
--
ALTER TABLE `privacies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `SequelizeMeta`
--
ALTER TABLE `SequelizeMeta`
  ADD PRIMARY KEY (`name`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `favourites`
--
ALTER TABLE `favourites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `privacies`
--
ALTER TABLE `privacies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
