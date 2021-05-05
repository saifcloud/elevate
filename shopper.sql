-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 05, 2021 at 04:04 PM
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
-- Database: `shopper`
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
(1, '/public/images/user/default.png', 'admin', 'admin@admin.com', NULL, '$2y$10$NUFw/ZnlTp5Y7aW7pb9SIOaXsjBp/TII6o1zGa/Micg2s2MMClZAO', 0, 0, '2021-05-05 06:12:35', '2021-05-05 06:12:35');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/public/images/user/default.png',
  `en_category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ar_category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `image`, `en_category`, `ar_category`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, '/public/images/category/default.png', 'Clothing', 'ملابس', 1, 0, '2021-05-03 12:59:31', '2021-05-10 12:59:37'),
(2, '/public/images/category/default.png', 'ACCESSORIES', 'مستلزمات', 1, 0, '2021-05-03 12:59:33', '2021-05-05 12:59:41'),
(3, '/public/images/category/default.png', 'GAMES', 'ألعاب', 1, 0, '2021-05-03 12:59:35', '2021-05-05 04:13:27'),
(5, '/public/images/category/default.png', 'Electronics', 'إلكترونيات', 1, 0, '2021-05-14 12:59:43', '2021-05-24 12:59:48'),
(6, '/public/images/category/default.png', 'Furniture', 'أثاث', 1, 0, '2021-05-18 12:59:45', '2021-05-10 12:59:46'),
(7, '/public/images/category/1620210184.jpg', 'beauty', 'جمال', 1, 0, '2021-05-05 04:34:09', '2021-05-05 04:53:04');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2021_05_03_111415_create_categories_table', 1),
(5, '2021_05_03_131518_create_subcategories_table', 2),
(6, '2021_05_03_141528_create_vendor_subcategories_table', 3),
(9, '2021_05_05_044604_create_sizes_table', 4),
(10, '2021_05_05_060505_create_admins_table', 5),
(13, '2021_05_05_112434_create_subsubcategories_table', 6),
(14, '2021_05_05_112536_create_types_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subcategory` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/public/images/subcategory/default.png',
  `en_subcategory` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ar_subcategory` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `image`, `en_subcategory`, `ar_subcategory`, `category_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, '/public/images/subcategory/1620212091.jpg', 'women', 'امرأة', 1, 1, 0, NULL, '2021-05-05 05:24:51'),
(2, '/public/images/subcategory/default.png', 'men', 'رجال', 1, 1, 0, NULL, NULL),
(3, '/public/images/subcategory/default.png', 'kids ', 'صغار في السن', 1, 1, 0, NULL, NULL),
(4, '/public/images/subcategory/default.png', 'Mobiles & smart watch', 'الهواتف الذكية والساعات الذكية', 5, 1, 0, NULL, NULL),
(5, '/public/images/subcategory/default.png', 'Computers ', 'أجهزة الكمبيوتر', 5, 1, 0, NULL, NULL),
(6, '/public/images/subcategory/1620211563.jpeg', 'make-up', 'ميك أب', 7, 1, 0, '2021-05-05 05:16:03', '2021-05-05 05:27:33'),
(7, '/public/images/subcategory/1620220544.jpeg', 'home furniture', 'الأثاث المنزلي', 6, 1, 0, '2021-05-05 07:45:44', '2021-05-05 07:45:44');

-- --------------------------------------------------------

--
-- Table structure for table `subsubcategories`
--

CREATE TABLE `subsubcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/public/images/subcategroy/default.png',
  `en_subsubcategory` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ar_subsubcategory` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subcategory_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subsubcategories`
--

INSERT INTO `subsubcategories` (`id`, `image`, `en_subsubcategory`, `ar_subsubcategory`, `subcategory_id`, `category_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, '/public/images/subsubcategory/1620216462.jpg', 'Jeans', 'جينز', '2', '1', 1, 0, '2021-05-05 06:37:42', '2021-05-05 06:53:36'),
(2, '/public/images/subsubcategory/1620220611.png', 'bedrooms', 'غرف نوم', '7', '6', 1, 1, '2021-05-05 07:46:51', '2021-05-05 07:48:52'),
(3, '/public/images/subsubcategory/1620220625.png', 'bedrooms', 'غرف نوم', '7', '6', 1, 1, '2021-05-05 07:47:05', '2021-05-05 07:48:55'),
(4, '/public/images/subsubcategory/1620220647.png', 'bedrooms', 'غرف نوم', '7', '6', 1, 0, '2021-05-05 07:47:27', '2021-05-05 07:47:27');

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/public/images/subcategroy/default.png',
  `en_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ar_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subsubcategory_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subcategory_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `image`, `en_type`, `ar_type`, `subsubcategory_id`, `subcategory_id`, `category_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, '/public/images/type/1620220818.jpg', 'beds', 'beds', '4', '7', '6', 1, 0, '2021-05-05 07:50:18', '2021-05-05 08:03:28');

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
  `commercial_reg_num` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `bio` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auth_token` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1=>shopper,2=>vendor',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `image`, `name`, `email`, `email_verified_at`, `password`, `phone`, `commercial_reg_num`, `category_id`, `bio`, `otp`, `fcm_token`, `device_token`, `auth_token`, `role`, `status`, `is_deleted`, `remember_token`, `created_at`, `updated_at`) VALUES
(5, '/public/images/user/1620199389.jpg', 'faiz', 'john@mailinator.com', NULL, '$2y$10$vK5JESxj6Rby.yvSha5KMumdYO31Q6sA1WUDSR90qBVUV6f67wB9u', '9898547525', 'DA25254', 1, 'hi this is bio section', NULL, 'f5d4f54sf5sd4f5s4af5', 'fs4f4dsff54sfa5s4df5sd4f5', 'nRaA9mVD6D577XoMEcDDJSXmW4RV6ygIpntP', '2', 1, 0, NULL, '2021-05-03 09:17:42', '2021-05-05 01:53:09'),
(9, '/public/images/user/default.png', 'John1', 'john1@mailinator.com', NULL, '$2y$10$E5d.0Sdnu4L0f.ZZVvMgd.rvBDudlO.ha.h8ydVM9e5e0ey6hUMsW', '9898547522', '12525544444', 1, NULL, NULL, 'f5d4f54sf5sd4f5s4af5', 'fs4f4dsff54sfa5s4df5sd4f5', 'BcQNCYBPPmDaOqKEq4L55J1ug5zfJms6ZKy4', '1', 1, 0, NULL, '2021-05-03 09:23:35', '2021-05-03 09:23:35');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_subcategories`
--

CREATE TABLE `vendor_subcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_subcategories`
--

INSERT INTO `vendor_subcategories` (`id`, `subcategory_id`, `category_id`, `vendor_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, 0, 0, '2021-05-03 09:17:42', '2021-05-03 09:17:42'),
(2, 2, 1, 5, 0, 0, '2021-05-03 09:17:42', '2021-05-03 09:17:42');

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subsubcategories`
--
ALTER TABLE `subsubcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vendor_subcategories`
--
ALTER TABLE `vendor_subcategories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `subsubcategories`
--
ALTER TABLE `subsubcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vendor_subcategories`
--
ALTER TABLE `vendor_subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
