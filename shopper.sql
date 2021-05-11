-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 11, 2021 at 01:31 PM
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
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `product_id`, `user_id`, `vendor_id`, `qty`, `size_id`, `color_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 22, 9, 11, 3, 1, 1, 1, 0, '2021-05-10 06:35:18', '2021-05-11 02:14:06'),
(2, 21, 9, 11, 1, 1, 1, 1, 0, '2021-05-10 06:35:52', '2021-05-10 06:35:52'),
(3, 22, 9, 16, 1, 1, 1, 1, 0, '2021-05-10 06:35:18', '2021-05-10 06:35:18'),
(5, 22, 9, 16, 1, 1, 1, 1, 0, '2021-05-10 06:35:18', '2021-05-10 06:35:18');

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
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) DEFAULT NULL,
  `subsubcategory_id` int(11) DEFAULT NULL,
  `subcategory_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `type`, `subsubcategory_id`, `subcategory_id`, `category_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, '#5b5757', NULL, 5, 2, 1, 1, 0, '2021-05-07 02:07:17', '2021-05-07 02:14:24'),
(2, '#ba7373', NULL, 5, 2, 1, 1, 0, '2021-05-07 03:30:39', '2021-05-07 03:30:39');

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
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`id`, `user_id`, `follower_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(2, 11, 9, 1, 0, '2021-05-10 05:49:08', '2021-05-10 05:49:08');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL COMMENT 'shopper_id',
  `vendor_id` int(11) NOT NULL COMMENT 'vendor_id',
  `user_id` int(11) NOT NULL COMMENT 'shopper_id',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `product_id`, `vendor_id`, `user_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(3, 16, 11, 9, 1, 0, '2021-05-10 04:13:15', '2021-05-10 04:13:15'),
(4, 17, 11, 9, 1, 0, '2021-05-10 04:25:05', '2021-05-10 04:25:05');

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
(14, '2021_05_05_112536_create_types_table', 6),
(15, '2021_05_05_044613_create_colors_table', 7),
(16, '2021_05_05_044831_create_products_table', 8),
(17, '2021_05_07_092334_create_product_sizes_table', 8),
(18, '2021_05_07_092408_create_product_colors_table', 8),
(19, '2021_05_10_075823_create_likes_table', 9),
(20, '2021_05_10_110353_create_follows_table', 10),
(23, '2021_05_10_045456_create_carts_table', 11),
(24, '2021_05_10_045509_create_orders_table', 12),
(25, '2021_05_10_135503_create_order_details_table', 12),
(27, '2021_05_11_055406_create_reviews_table', 13),
(28, '2021_05_11_072629_create_trackings_table', 14);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` double(10,2) NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `long` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `delivery_status` int(11) NOT NULL DEFAULT 0,
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `user_id`, `total`, `transaction_id`, `transaction_type`, `shipping_address`, `lat`, `long`, `status`, `delivery_status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'ORDER9677002555', '9', 500.00, NULL, NULL, 'New Palasia', '22.7244', '75.8839', 1, 0, 0, '2021-05-10 09:03:56', '2021-05-10 09:03:56');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `amount` double(10,2) NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `size_id`, `color_id`, `qty`, `amount`, `user_id`, `vendor_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(3, 'ORDER9677002555', 22, 1, 1, 2, 250.00, '9', '11', 1, 0, '2021-05-10 09:03:56', '2021-05-10 09:03:56'),
(4, 'ORDER9677002555', 21, 1, 1, 1, 250.00, '9', '11', 1, 0, '2021-05-10 09:03:56', '2021-05-10 09:03:56');

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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `img1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `img2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_id` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `sub_subcategory_id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL DEFAULT 1,
  `category_id` int(11) NOT NULL,
  `price` double(10,2) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `description`, `img1`, `img2`, `img3`, `img4`, `vendor_id`, `type_id`, `sub_subcategory_id`, `subcategory_id`, `category_id`, `price`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(16, 'Puma shoes', 'This is one of puma best brand shoes', '/public/images/product/16203888021.jpg', '/public/images/product/16203888022.webp', '/public/images/product/16203888023.png', '/public/images/product/16203888024.jpeg', '11', NULL, 5, 2, 1, 250.00, 1, 0, '2021-05-07 06:30:02', '2021-05-07 06:30:02'),
(17, 'Top', 'This is one of puma best brand shoes', '/public/images/product/16203895041.jpeg', '/public/images/product/16203895042.jpg', NULL, NULL, '11', NULL, 7, 1, 1, 250.00, 1, 0, '2021-05-07 06:41:44', '2021-05-07 06:41:44'),
(18, 'Tsirt b', 'This is one of puma best brand shoes', '/public/images/product/16203905121.jpeg', '/public/images/product/16203905122.jpg', NULL, NULL, '11', NULL, 7, 1, 1, 250.00, 1, 0, '2021-05-07 06:58:32', '2021-05-07 06:58:32'),
(19, 'Nacker', 'This is one of puma best brand shoes', '/public/images/product/16203905201.jpeg', '/public/images/product/16203905202.jpg', NULL, NULL, '11', NULL, 7, 1, 1, 250.00, 1, 0, '2021-05-07 06:58:40', '2021-05-07 06:58:40'),
(20, 'Lower', 'This is one of puma best brand shoes', '/public/images/product/16203905251.jpeg', '/public/images/product/16203905252.jpg', NULL, NULL, '11', NULL, 7, 1, 1, 250.00, 1, 0, '2021-05-07 06:58:45', '2021-05-07 06:58:45'),
(21, 'upper', 'This is one of puma best brand shoes', '/public/images/product/16203905301.jpeg', '/public/images/product/16203905302.jpg', NULL, NULL, '11', NULL, 7, 1, 1, 250.00, 1, 0, '2021-05-07 06:58:50', '2021-05-07 06:58:50'),
(22, 'Suit custom', 'This is one of puma best brand shoes', '/public/images/product/16203905371.jpeg', '/public/images/product/16203905372.jpg', NULL, NULL, '11', NULL, 7, 1, 1, 250.00, 1, 0, '2021-05-07 06:58:57', '2021-05-07 06:58:57');

-- --------------------------------------------------------

--
-- Table structure for table `product_colors`
--

CREATE TABLE `product_colors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `color_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `img1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `img2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_colors`
--

INSERT INTO `product_colors` (`id`, `color_id`, `product_id`, `img1`, `img2`, `img3`, `img4`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(9, '1', '16', '/public/images/product/162038880211.jpg', '/public/images/product/162038880212.jpg', '/public/images/product/162038880213.jpg', '/public/images/product/162038880214.jpg', 1, 0, '2021-05-07 06:30:02', '2021-05-07 06:30:02'),
(10, '2', '16', '/public/images/product/162038880221.webp', '/public/images/product/162038880222.png', '/public/images/product/162038880223.jpeg', '/public/images/product/162038880224.jpg', 1, 0, '2021-05-07 06:30:02', '2021-05-07 06:30:02'),
(11, '1', '17', '/public/images/product/162038950411.jpg', '/public/images/product/162038950412.jpg', '/public/images/product/162038950413.jpg', '/public/images/product/162038950414.jpg', 1, 0, '2021-05-07 06:41:44', '2021-05-07 06:41:44'),
(12, '2', '17', '/public/images/product/162038950421.webp', '/public/images/product/162038950422.png', '/public/images/product/162038950423.jpeg', '/public/images/product/162038950424.jpg', 1, 0, '2021-05-07 06:41:44', '2021-05-07 06:41:44'),
(13, '1', '18', '/public/images/product/162039051211.jpg', '/public/images/product/162039051212.jpg', '/public/images/product/162039051213.jpg', '/public/images/product/162039051214.jpg', 1, 0, '2021-05-07 06:58:32', '2021-05-07 06:58:32'),
(14, '2', '18', '/public/images/product/162039051221.webp', '/public/images/product/162039051222.png', '/public/images/product/162039051223.jpeg', '/public/images/product/162039051224.jpg', 1, 0, '2021-05-07 06:58:32', '2021-05-07 06:58:32'),
(15, '1', '19', '/public/images/product/162039052011.jpg', '/public/images/product/162039052012.jpg', '/public/images/product/162039052013.jpg', '/public/images/product/162039052014.jpg', 1, 0, '2021-05-07 06:58:40', '2021-05-07 06:58:40'),
(16, '2', '19', '/public/images/product/162039052021.webp', '/public/images/product/162039052022.png', '/public/images/product/162039052023.jpeg', '/public/images/product/162039052024.jpg', 1, 0, '2021-05-07 06:58:40', '2021-05-07 06:58:40'),
(17, '1', '20', '/public/images/product/162039052611.jpg', '/public/images/product/162039052612.jpg', '/public/images/product/162039052613.jpg', '/public/images/product/162039052614.jpg', 1, 0, '2021-05-07 06:58:46', '2021-05-07 06:58:46'),
(18, '2', '20', '/public/images/product/162039052621.webp', '/public/images/product/162039052622.png', '/public/images/product/162039052623.jpeg', '/public/images/product/162039052624.jpg', 1, 0, '2021-05-07 06:58:46', '2021-05-07 06:58:46'),
(19, '1', '21', '/public/images/product/162039053011.jpg', '/public/images/product/162039053012.jpg', '/public/images/product/162039053013.jpg', '/public/images/product/162039053014.jpg', 1, 0, '2021-05-07 06:58:50', '2021-05-07 06:58:50'),
(20, '2', '21', '/public/images/product/162039053021.webp', '/public/images/product/162039053022.png', '/public/images/product/162039053023.jpeg', '/public/images/product/162039053024.jpg', 1, 0, '2021-05-07 06:58:50', '2021-05-07 06:58:50'),
(21, '1', '22', '/public/images/product/162039053711.jpg', '/public/images/product/162039053712.jpg', '/public/images/product/162039053713.jpg', '/public/images/product/162039053714.jpg', 1, 0, '2021-05-07 06:58:57', '2021-05-07 06:58:57'),
(22, '2', '22', '/public/images/product/162039053721.webp', '/public/images/product/162039053722.png', '/public/images/product/162039053723.jpeg', '/public/images/product/162039053724.jpg', 1, 0, '2021-05-07 06:58:57', '2021-05-07 06:58:57');

-- --------------------------------------------------------

--
-- Table structure for table `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `size_id` int(11) NOT NULL,
  `product_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_sizes`
--

INSERT INTO `product_sizes` (`id`, `size_id`, `product_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(7, 1, '16', 1, 0, '2021-05-07 06:30:02', '2021-05-07 06:30:02'),
(8, 2, '16', 1, 0, '2021-05-07 06:30:02', '2021-05-07 06:30:02'),
(9, 1, '17', 1, 0, '2021-05-07 06:41:44', '2021-05-07 06:41:44'),
(10, 2, '17', 1, 0, '2021-05-07 06:41:44', '2021-05-07 06:41:44'),
(11, 1, '18', 1, 0, '2021-05-07 06:58:32', '2021-05-07 06:58:32'),
(12, 2, '18', 1, 0, '2021-05-07 06:58:32', '2021-05-07 06:58:32'),
(13, 1, '19', 1, 0, '2021-05-07 06:58:40', '2021-05-07 06:58:40'),
(14, 2, '19', 1, 0, '2021-05-07 06:58:40', '2021-05-07 06:58:40'),
(15, 1, '20', 1, 0, '2021-05-07 06:58:45', '2021-05-07 06:58:45'),
(16, 2, '20', 1, 0, '2021-05-07 06:58:46', '2021-05-07 06:58:46'),
(17, 1, '21', 1, 0, '2021-05-07 06:58:50', '2021-05-07 06:58:50'),
(18, 2, '21', 1, 0, '2021-05-07 06:58:50', '2021-05-07 06:58:50'),
(19, 1, '22', 1, 0, '2021-05-07 06:58:57', '2021-05-07 06:58:57'),
(20, 2, '22', 1, 0, '2021-05-07 06:58:57', '2021-05-07 06:58:57');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 16, 9, 4, 'This best product', 1, 0, '2021-05-11 00:44:54', '2021-05-11 00:44:54'),
(2, 16, 9, 4, 'This best product', 1, 0, '2021-05-11 01:43:58', '2021-05-11 01:43:58');

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) DEFAULT NULL,
  `subsubcategory_id` int(11) DEFAULT NULL,
  `subcategory_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `name`, `type`, `subsubcategory_id`, `subcategory_id`, `category_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'L', NULL, 1, 1, 1, 1, 0, '2021-05-07 01:28:15', '2021-05-07 01:49:17'),
(2, 'X', NULL, 5, 2, 1, 1, 0, '2021-05-07 01:28:42', '2021-05-07 01:28:42'),
(3, 'XL', NULL, 5, 2, 1, 1, 0, '2021-05-07 01:28:55', '2021-05-07 01:28:55'),
(4, 'X', NULL, 7, 1, 1, 1, 0, '2021-05-07 01:37:56', '2021-05-07 01:37:56');

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
(7, '/public/images/subcategory/1620220544.jpeg', 'home furniture', 'الأثاث المنزلي', 6, 1, 0, '2021-05-05 07:45:44', '2021-05-05 07:45:44'),
(8, '/public/images/subcategory/1620365707.jpg', 'outdoor furniture', 'الأثاث في الهواء الطلق', 6, 1, 0, '2021-05-07 00:05:07', '2021-05-07 00:05:07');

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
(1, '/public/images/subsubcategory/1620216462.jpg', 'Jeans', 'جينز', '1', '1', 1, 0, '2021-05-05 06:37:42', '2021-05-06 23:47:55'),
(4, '/public/images/subsubcategory/1620220647.png', 'bedrooms', 'غرف نوم', '7', '6', 1, 0, '2021-05-05 07:47:27', '2021-05-05 07:47:27'),
(5, '/public/images/subsubcategory/1620364146.jpg', 'Jeans', 'جينز', '2', '1', 1, 0, '2021-05-06 23:39:06', '2021-05-06 23:39:06'),
(6, '/public/images/subsubcategory/1620364737.jpg', 'shirts', 'قمصان', '2', '1', 1, 0, '2021-05-06 23:48:57', '2021-05-06 23:48:57'),
(7, '/public/images/subsubcategory/1620364820.jpeg', 'Tops', 'بلايز', '1', '1', 1, 0, '2021-05-06 23:50:20', '2021-05-06 23:50:20'),
(8, '/public/images/subsubcategory/1620365775.png', 'dining', 'تناول الطعام', '8', '6', 1, 0, '2021-05-07 00:06:15', '2021-05-07 00:06:15'),
(9, '/public/images/subsubcategory/1620365881.jpeg', 'living rooms', 'غرف المعيشة', '7', '6', 1, 0, '2021-05-07 00:08:01', '2021-05-07 00:08:01');

-- --------------------------------------------------------

--
-- Table structure for table `trackings`
--

CREATE TABLE `trackings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordered_date` datetime NOT NULL DEFAULT current_timestamp(),
  `package_date` datetime DEFAULT NULL,
  `transporting_date` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trackings`
--

INSERT INTO `trackings` (`id`, `order_id`, `ordered_date`, `package_date`, `transporting_date`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'ORDER9677002555', '2021-05-11 15:13:33', NULL, NULL, 1, 0, NULL, NULL);

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
(1, '/public/images/type/1620220818.jpg', 'beds', 'beds', '4', '7', '6', 1, 0, '2021-05-05 07:50:18', '2021-05-05 08:03:28'),
(2, '/public/images/type/1620365938.jpeg', 'sofas', 'الأرائك', '9', '7', '6', 1, 0, '2021-05-07 00:08:58', '2021-05-07 00:08:58');

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
(5, '/public/images/user/1620199389.jpg', 'faiz', 'john@mailinator.com', NULL, '$2y$10$lj3k58VPRsSCjv71FFAcd.m5ZqLt2iDVscKAOxwmLN7ql1n8pfQHi', '9898547525', 'DA25254', 6, 'hi this is bio section', NULL, 'f5d4f54sf5sd4f5s4af5', 'fs4f4dsff54sfa5s4df5sd4f5', '5rnSV0wzAyahHYdI12NZb3YoLWnQrbvCN1Ns', '2', 1, 0, NULL, '2021-05-03 09:17:42', '2021-05-11 05:36:29'),
(9, '/public/images/user/1620643212.jpg', 'Rehat', 'john1@mailinator.com', NULL, '$2y$10$UVJftTgEoHjlFKsCAikq5eNLbJVTlxSYPK/62vL9EP7CKfLAbEitS', '9898547522', '12525544444', 0, 'test test test', NULL, 'f5d4f54sf5sd4f5s4af5', 'fs4f4dsff54sfa5s4df5sd4f5', 'eJv7BfvCuMNXOksoyWCeeuA6mmAd1YmgBDwX', '1', 1, 0, NULL, '2021-05-03 09:23:35', '2021-05-10 05:10:12'),
(11, '/public/images/user/default.png', 'joram', 'joram@mailinator.com', NULL, '$2y$10$.kw1ajVaMS0pCJskGVFeDu6jRwWOgCK9OkgbGc7DWy8IfBbA.QFmS', '9898547544', '12525544444', 1, NULL, NULL, 'f5d4f54sf5sd4f5s4af5', 'fs4f4dsff54sfa5s4df5sd4f5', '0VFcNBiFxz32k9nV84bZj21szHXXrcBTU0Rp', '2', 1, 0, NULL, '2021-05-07 00:27:33', '2021-05-07 06:48:29'),
(16, '/public/images/user/1620199389.jpg', 'chloe', 'chloe@mailinator.com', NULL, '$2y$10$lj3k58VPRsSCjv71FFAcd.m5ZqLt2iDVscKAOxwmLN7ql1n8pfQHi', '989854755', 'DA25254S', 6, 'i am doe', NULL, 'f5d4f54sf5sd4f5s4af5', 'fs4f4dsff54sfa5s4df5sd4f5', 'qL8c6RQNbTljTZtm058nzfGvnHTFkeRg5Irj', '2', 1, 0, NULL, '2021-05-03 09:17:42', '2021-05-07 00:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_subcategories`
--

CREATE TABLE `vendor_subcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>active',
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1=>deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_subcategories`
--

INSERT INTO `vendor_subcategories` (`id`, `subcategory_id`, `category_id`, `vendor_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 7, 6, 5, 1, 0, '2021-05-03 09:17:42', '2021-05-03 09:17:42'),
(2, 8, 6, 5, 1, 0, '2021-05-03 09:17:42', '2021-05-03 09:17:42'),
(3, 1, 1, 11, 1, 0, '2021-05-07 00:27:33', '2021-05-07 00:27:33'),
(4, 2, 1, 11, 1, 0, '2021-05-07 00:27:33', '2021-05-07 00:27:33');

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
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_colors`
--
ALTER TABLE `product_colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `trackings`
--
ALTER TABLE `trackings`
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
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `product_colors`
--
ALTER TABLE `product_colors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `subsubcategories`
--
ALTER TABLE `subsubcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `trackings`
--
ALTER TABLE `trackings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `vendor_subcategories`
--
ALTER TABLE `vendor_subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
