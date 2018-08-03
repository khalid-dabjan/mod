-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 28, 2018 at 02:10 PM
-- Server version: 5.7.21-0ubuntu0.17.10.1
-- PHP Version: 7.1.15-0ubuntu0.17.10.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel_modisti`
--

-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

CREATE TABLE `blocks` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `limit` int(11) NOT NULL,
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blocks`
--

INSERT INTO `blocks` (`id`, `name`, `slug`, `type`, `limit`, `lang`) VALUES
(1, 'Home', 'home', 'category', 20, 'en');

-- --------------------------------------------------------

--
-- Table structure for table `blocks_categories`
--

CREATE TABLE `blocks_categories` (
  `block_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blocks_tags`
--

CREATE TABLE `blocks_tags` (
  `block_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `title`, `slug`, `excerpt`, `image_id`, `user_id`, `lang`, `created_at`, `updated_at`) VALUES
(1, 'Brand test', 'brand-name-test2', 'test2dsads', 1, 1, 'en', '2018-03-12 12:54:55', '2018-03-12 12:56:00'),
(2, 'text', 'text', 'text', 9, 1, 'en', '2018-03-12 12:57:02', '2018-03-12 12:57:02');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent`, `name`, `slug`, `image_id`, `user_id`, `lang`, `status`, `created_at`, `updated_at`) VALUES
(1, 0, 'Test', 'test', 0, 1, 'en', '1', '2018-03-06 14:29:29', '2018-03-06 14:29:29');

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE `collections` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `image_id` int(11) NOT NULL DEFAULT '0',
  `front_page` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `collections`
--

INSERT INTO `collections` (`id`, `title`, `slug`, `excerpt`, `content`, `image_id`, `front_page`, `user_id`, `lang`, `created_at`, `published_at`, `updated_at`) VALUES
(1, 'Cras ultricies ligula sed magna dictum porta. Cras ultricies ligula sed magna dictum porta.', 'cras-ultricies-ligula-sed-magna-dictum-porta-cras-ultricies-ligula-sed-magna-dictum-porta', 'Cras ultricies ligula sed magna dictum porta. Cras ultricies ligula sed magna dictum porta.', '<p>Cras ultricies ligula sed magna dictum porta. Cras ultricies ligula sed magna dictum porta. Nulla porttitor accumsan tincidunt. Donec rutrum congue leo eget malesuada. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Cras ultricies ligula sed magna dictum porta. Cras ultricies ligula sed magna dictum porta.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus suscipit tortor eget felis porttitor volutpat. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Vivamus suscipit tortor eget felis porttitor volutpat. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Pellentesque in ipsum id orci porta dapibus.</p>\r\n\r\n<p>Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Curabitur aliquet quam id dui posuere blandit. Sed porttitor lectus nibh. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Donec sollicitudin molestie malesuada. Curabitur aliquet quam id dui posuere blandit. Donec sollicitudin molestie malesuada.</p>\r\n\r\n<p>Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Proin eget tortor risus. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Donec sollicitudin molestie malesuada. Cras ultricies ligula sed magna dictum porta. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>', 14, 1, 1, 'en', '2018-03-12 18:52:19', '2018-03-13 18:51:32', '2018-03-12 18:52:19');

-- --------------------------------------------------------

--
-- Table structure for table `collections_posts`
--

CREATE TABLE `collections_posts` (
  `post_id` int(11) NOT NULL DEFAULT '0',
  `collection_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `collections_posts`
--

INSERT INTO `collections_posts` (`post_id`, `collection_id`) VALUES
(4, 1),
(1, 1),
(4, 1),
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_to_filter` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `value`, `add_to_filter`, `user_id`, `lang`, `created_at`, `updated_at`) VALUES
(1, 'Red', '#a05d5d', 1, 1, 'en', '2018-03-07 13:45:48', '2018-03-07 14:02:24'),
(2, 'Blue', '#f60a0a', 1, 1, 'en', '2018-03-07 13:55:21', '2018-03-07 14:01:35');

-- --------------------------------------------------------

--
-- Table structure for table `color_post`
--

CREATE TABLE `color_post` (
  `post_id` int(11) NOT NULL DEFAULT '0',
  `color_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contests`
--

CREATE TABLE `contests` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `hash_tag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `image_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `reward` int(11) DEFAULT NULL,
  `reward_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galleries_media`
--

CREATE TABLE `galleries_media` (
  `gallery_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `provider` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `length` int(11) DEFAULT NULL,
  `hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `type`, `path`, `title`, `description`, `provider`, `provider_id`, `provider_image`, `user_id`, `length`, `hash`, `created_at`, `updated_at`) VALUES
(1, 'image', '2018/03/1957511588889455820.jpg', '1489185106-desert', NULL, '', NULL, NULL, 1, NULL, '84a60c5aa19441caa5fca41e79a77a9b8adbd393', '2018-03-11 13:10:16', '2018-03-11 13:10:16'),
(2, 'image', '2018/03/1155865165967890232.jpg', '1489180750-6d34a39944661.560dcd973eb8c', NULL, '', NULL, NULL, 1, NULL, '4fd3ade723767d11e90cb68934e605d24bb0c124', '2018-03-11 13:10:16', '2018-03-11 13:10:16'),
(3, 'image', '2018/03/1737443857554362100.jpg', '1495122990-image14018', NULL, '', NULL, NULL, 1, NULL, 'f8c8ecabbfbf0f4ebf24cf68cd00d145cca78249', '2018-03-11 13:10:16', '2018-03-11 13:10:16'),
(4, 'image', '2018/03/3047125809347036000.jpg', '1495114709-18386850_10156272731323975_1099252912_n', NULL, '', NULL, NULL, 1, NULL, '993a536c029070a55cefbf2b8c1b78d912c5357f', '2018-03-11 13:10:16', '2018-03-11 13:10:16'),
(6, 'image', '2018/03/588063744007658648.jpg', '1489064090-03', NULL, '', NULL, NULL, 1, NULL, '8409a98d68bed1360827c49021950f387890867f', '2018-03-11 13:10:16', '2018-03-11 13:10:16'),
(7, 'image', '2018/03/1893169076038467216.jpg', '1489158174-penguins', NULL, '', NULL, NULL, 1, NULL, '73f7a42f71fdabc9e84719b17f1a588d39371558', '2018-03-11 13:10:16', '2018-03-11 13:10:16'),
(8, 'image', '2018/03/1757487422478229768.jpg', '1489017652-penguins', NULL, '', NULL, NULL, 1, NULL, '73f7a42f71fdabc9e84719b17f1a588d39371558', '2018-03-11 13:10:16', '2018-03-11 13:10:16'),
(9, 'image', '2018/03/3199636229449578384.jpg', '1489166105-fadbe89944241.560dcd5506edd', NULL, '', NULL, NULL, 1, NULL, '8ca0bfee897be8ce9a7d759109155fb72723fdf2', '2018-03-11 13:10:16', '2018-03-11 13:10:16'),
(10, 'image', '2018/03/2887556827112145120.jpg', '1489017513-koala', NULL, '', NULL, NULL, 1, NULL, 'c8fac2059c75e8b74a89eeba495096d92a81a34d', '2018-03-11 13:10:17', '2018-03-11 13:10:17'),
(11, 'image', '2018/03/3179856378791194732.jpg', '1488985503-cooking-range-1280x640', NULL, '', NULL, NULL, 1, NULL, '87a8247f3ea29af5af8c64942ec371471cda43cc', '2018-03-11 13:10:17', '2018-03-11 13:10:17'),
(12, 'image', '2018/03/1647819191597636008.jpg', '1486982448-mgid-ao-image-mtv_1', NULL, '', NULL, NULL, 1, NULL, '1d5fe48d6bebf109bdde5d5c0c696e771b231334', '2018-03-11 13:10:17', '2018-03-11 13:10:17'),
(13, 'image', '2018/03/2742010187985910048.png', '1495728410-picture1', NULL, '', NULL, NULL, 1, NULL, '942a10c7eae631e7cf4f86231ff3c7a865c1d17b', '2018-03-11 13:10:17', '2018-03-11 13:10:17'),
(14, 'image', '2018/03/1840361741864922500.jpg', '1488459143-yyy_yyyyy_yyyyy_yyyy_yyyy_4', NULL, '', NULL, NULL, 1, NULL, 'a82e808ae776d05073c6bbcbc64e3b458c353cb1', '2018-03-11 13:10:17', '2018-03-11 13:10:17'),
(15, 'image', '2018/03/339194950887468473.jpg', '1484843148-loslos2', NULL, '', NULL, NULL, 1, NULL, '50155a5e1820e45f8f9ed3b7ca3e51306a248f84', '2018-03-11 13:10:17', '2018-03-11 13:10:17'),
(16, 'image', '2018/03/2455449203763161228.jpg', '1484841325-ruby', NULL, '', NULL, NULL, 1, NULL, '738106be7bdeb51453d1e279a0d55b8a7c0ee621', '2018-03-11 13:10:17', '2018-03-11 13:10:17');

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
(3, '2016_05_21_085608_create_options_table', 2),
(4, '2016_05_21_094053_create_media_table', 3),
(5, '2016_05_21_095017_create_galleries_table', 4),
(6, '2016_05_21_095240_create_galleries_media_table', 4),
(7, '2014_10_12_000000_make_users_table', 5),
(8, '2016_05_18_163151_create_roles_table', 6),
(9, '2016_05_23_152047_create_role_permissions', 6),
(10, '2016_05_21_100944_create_categories_table', 7),
(11, '2016_05_21_100046_create_tags_table', 8),
(12, '2016_05_21_100046_create_blocks_table', 9),
(13, '2016_06_13_101314_create_blocks_tags_table', 9),
(14, '2016_06_13_101416_create_blocks_categories_table', 9),
(15, '2016_06_13_104109_create_blocks_posts_table', 9),
(16, '2016_05_19_101229_create_posts_table', 10),
(17, '2016_05_26_101230_create_posts_categories_table', 10),
(18, '2016_05_26_101230_create_posts_tags_table', 10),
(19, '2016_05_26_101231_create_posts_galleries_table', 10),
(20, '2016_06_22_124405_create_posts_meta_table', 10),
(21, '2016_05_19_101230_create_colors_table', 11),
(22, '2016_05_19_122230_create_color_post_table', 12),
(23, '2018_07_26_124406_create_posts_sizes_table', 13),
(24, '2018_07_26_124477_create_brands_table', 14),
(25, '2018_07_26_124480_create_sets_table', 15),
(26, '2018_07_26_124490_create_sets_posts_table', 15),
(27, '2018_07_27_124488_create_collections_table', 16),
(28, '2018_08_26_124490_create_collections_posts_table', 17),
(29, '2018_08_28_124488_create_contests_table', 18);

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `lang` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `name`, `value`, `lang`) VALUES
(1, 'media_allowed_file_types', 'jpg,png,jpeg,bmp,gif,zip,doc,docx,rar,zip,pdf,txt,csv,xls', NULL),
(2, 'media_max_file_size', '512000', NULL),
(3, 'media_max_width', '2500', NULL),
(4, 'media_thumbnails', '0', NULL),
(5, 'media_resize_mode', 'resize', NULL),
(6, 'media_resize_background_color', '#ffffff', NULL),
(7, 'media_resize_gradient_first_color', '#ffffff', NULL),
(8, 'media_resize_gradient_second_color', '#000000', NULL),
(9, 'site_name', 'Site Name', 'en'),
(10, 'site_slogan', 'Site Slogan', 'en'),
(11, 'site_email', 'dot@platform.com', NULL),
(12, 'site_copyrights', 'All rights reserved', 'en'),
(13, 'site_timezone', 'Etc/GMT', NULL),
(14, 'site_date_format', 'relative', NULL),
(15, 'site_status', '1', NULL),
(16, 'site_offline_message', NULL, 'en');

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
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `front_page` int(11) DEFAULT '0',
  `coverage` int(11) DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `size_system` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_id` int(11) NOT NULL DEFAULT '0',
  `media_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `brand_id` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `color_id` int(11) DEFAULT '0',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `format` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `views` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `likes` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `front_page`, `coverage`, `url`, `slug`, `excerpt`, `content`, `size_system`, `price`, `sale_price`, `image_id`, `media_id`, `user_id`, `brand_id`, `status`, `color_id`, `reason`, `format`, `lang`, `views`, `likes`, `created_at`, `updated_at`, `published_at`) VALUES
(1, 'Test item', 1, 1, 'https://fontawesome.com/get-started/web-fonts-with-css', 'test-item', 'Nulla quis lorem ut libero malesuada', '<p>Nulla quis lorem ut libero malesuada feugiat. Vivamus suscipit tortor eget felis porttitor volutpat. Donec rutrum congue leo eget malesuada. Pellentesque in ipsum id orci porta dapibus. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.</p>\r\n\r\n<p>Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Sed porttitor lectus nibh. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Curabitur aliquet quam id dui posuere blandit.</p>\r\n\r\n<p>Pellentesque in ipsum id orci porta dapibus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis lorem ut libero malesuada feugiat. Sed porttitor lectus nibh. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>\r\n\r\n<p>Donec sollicitudin molestie malesuada. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Donec sollicitudin molestie malesuada. Vivamus suscipit tortor eget felis porttitor volutpat. Proin eget tortor risus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a.</p>', 'uk', '155', '16.', 10, 0, 1, 2, 1, 2, 'test Reason', 'item', 'en', '0', 0, '2018-03-11 13:11:23', '2018-03-12 13:16:08', '2018-03-11 13:09:05'),
(4, 'Sed porttitor lectus nibh. Vestibulum ante', 1, NULL, NULL, 'sed-porttitor-lectus-nibh-vestibulum-ante', 'Sed porttitor lectus nibh. Vestibulum ante', '<p>Sed porttitor lectus nibh. Vestibulum ante&nbsp;</p>', NULL, '0', '0', 0, 0, 1, 0, 0, 0, NULL, 'item', 'en', '0', 0, '2018-03-12 13:19:14', '2018-03-13 12:58:46', '2018-03-12 13:18:50'),
(5, 'Vestibulum ante ipsum primis in faucibus', 0, NULL, NULL, 'sed-porttitor-lectus-nibh', 'Vestibulum ante ipsum primis in faucibus', '<p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Sed porttitor lectus nibh. Donec rutrum congue leo eget malesuada. Curabitur aliquet quam id dui posuere blandit. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Nulla porttitor accumsan tincidunt. Vivamus suscipit tortor eget felis porttitor volutpat.</p>\r\n\r\n<p>Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Vivamus suscipit tortor eget felis porttitor volutpat. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Cras ultricies ligula sed magna dictum porta. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.</p>\r\n\r\n<p>Pellentesque in ipsum id orci porta dapibus. Curabitur aliquet quam id dui posuere blandit. Donec sollicitudin molestie malesuada. Nulla quis lorem ut libero malesuada feugiat. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Donec sollicitudin molestie malesuada. Sed porttitor lectus nibh.</p>\r\n\r\n<p>Nulla quis lorem ut libero malesuada feugiat. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Nulla quis lorem ut libero malesuada feugiat. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque velit nisi, pretium ut lacinia in, elementum id enim.</p>', 'eu', '5', '50', 4, 0, 1, 2, 0, 0, NULL, 'item', 'en', '0', 0, '2018-03-26 07:18:05', '2018-03-26 07:19:37', '2018-03-26 07:17:31');

-- --------------------------------------------------------

--
-- Table structure for table `posts_blocks`
--

CREATE TABLE `posts_blocks` (
  `post_id` int(11) NOT NULL,
  `block_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts_blocks_orders`
--

CREATE TABLE `posts_blocks_orders` (
  `order` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL,
  `block_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts_blocks_orders`
--

INSERT INTO `posts_blocks_orders` (`order`, `post_id`, `block_id`) VALUES
(3, 1, 1),
(2, 5, 1),
(1, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts_categories`
--

CREATE TABLE `posts_categories` (
  `post_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts_categories`
--

INSERT INTO `posts_categories` (`post_id`, `category_id`) VALUES
(1, 1),
(4, 1),
(1, 1),
(4, 1),
(5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts_categories_orders`
--

CREATE TABLE `posts_categories_orders` (
  `order` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts_categories_orders`
--

INSERT INTO `posts_categories_orders` (`order`, `post_id`, `category_id`) VALUES
(2, 4, 1),
(1, 1, 1),
(3, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts_galleries`
--

CREATE TABLE `posts_galleries` (
  `post_id` int(11) NOT NULL,
  `gallery_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts_meta`
--

CREATE TABLE `posts_meta` (
  `post_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts_sizes`
--

CREATE TABLE `posts_sizes` (
  `post_id` int(11) NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts_sizes`
--

INSERT INTO `posts_sizes` (`post_id`, `size`) VALUES
(1, 'dasdsa'),
(1, 'dasdas'),
(1, 'dasdasdd'),
(4, ''),
(1, 'dasdsa'),
(1, 'dasdas'),
(1, 'dasdasdd'),
(4, ''),
(5, '');

-- --------------------------------------------------------

--
-- Table structure for table `posts_tags`
--

CREATE TABLE `posts_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'superadmin');

-- --------------------------------------------------------

--
-- Table structure for table `roles_permissions`
--

CREATE TABLE `roles_permissions` (
  `role_id` int(11) NOT NULL,
  `permission` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sets`
--

CREATE TABLE `sets` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `front_page` int(11) DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `image_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sets`
--

INSERT INTO `sets` (`id`, `title`, `slug`, `excerpt`, `front_page`, `content`, `image_id`, `user_id`, `lang`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Cras ultricies ligula sed magna', 'test', 'Cras ultricies ligula sed magna', 1, '<p>Cras ultricies ligula sed magna dictum porta. Sed porttitor lectus nibh. Nulla porttitor accumsan tincidunt. Pellentesque in ipsum id orci porta dapibus. Sed porttitor lectus nibh. Curabitur aliquet quam id dui posuere blandit. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>', 16, 1, 'en', '2018-03-06 17:41:57', '2018-03-12 17:42:41', '2018-03-12 17:43:18');

-- --------------------------------------------------------

--
-- Table structure for table `sets_posts`
--

CREATE TABLE `sets_posts` (
  `post_id` int(11) NOT NULL DEFAULT '0',
  `set_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sets_posts`
--

INSERT INTO `sets_posts` (`post_id`, `set_id`) VALUES
(4, 1),
(1, 1),
(4, 1),
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_token` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` int(11) NOT NULL DEFAULT '0',
  `last_login` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `backend` int(11) NOT NULL DEFAULT '0',
  `root` int(11) NOT NULL DEFAULT '0',
  `photo_id` int(11) NOT NULL DEFAULT '0',
  `lang` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'blue',
  `about` text COLLATE utf8mb4_unicode_ci,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linked_in` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_plus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `username`, `last_name`, `provider`, `provider_id`, `api_token`, `code`, `role_id`, `last_login`, `status`, `backend`, `root`, `photo_id`, `lang`, `color`, `about`, `facebook`, `twitter`, `linked_in`, `google_plus`) VALUES
(1, 'Abdulrhman Gamal', 'admin@modisti.com', '$2y$10$c9DluFV8b2/LTUu.FzXVdePhUrdEaM/2QxFk2XtJcotaUX1TgPHze', 'CX5i7kEDenThtaEA8lizLJvtfkBQRR1zkteWTAKk4n05QqbzLr6U66Zu6d72', '2018-03-06 13:11:25', '2018-03-06 13:11:25', 'admin', NULL, NULL, NULL, '0EjHK8X3ICIRFsF0IuZJbNt1t1YsCy5W0FUeZc0qNslefwTXIQ2in65P1UL3', NULL, 1, NULL, 1, 1, 1, 0, 'en', 'blue', NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blocks`
--
ALTER TABLE `blocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blocks_name_index` (`name`),
  ADD KEY `blocks_slug_index` (`slug`),
  ADD KEY `blocks_type_index` (`type`),
  ADD KEY `blocks_limit_index` (`limit`),
  ADD KEY `blocks_lang_index` (`lang`);

--
-- Indexes for table `blocks_categories`
--
ALTER TABLE `blocks_categories`
  ADD KEY `blocks_categories_block_id_index` (`block_id`),
  ADD KEY `blocks_categories_category_id_index` (`category_id`);

--
-- Indexes for table `blocks_tags`
--
ALTER TABLE `blocks_tags`
  ADD KEY `blocks_tags_block_id_index` (`block_id`),
  ADD KEY `blocks_tags_tag_id_index` (`tag_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brands_slug_unique` (`slug`),
  ADD KEY `brands_title_index` (`title`),
  ADD KEY `brands_excerpt_index` (`excerpt`),
  ADD KEY `brands_image_id_index` (`image_id`),
  ADD KEY `brands_user_id_index` (`user_id`),
  ADD KEY `brands_lang_index` (`lang`),
  ADD KEY `brands_created_at_index` (`created_at`),
  ADD KEY `brands_updated_at_index` (`updated_at`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_parent_index` (`parent`),
  ADD KEY `categories_name_index` (`name`),
  ADD KEY `categories_slug_index` (`slug`),
  ADD KEY `categories_image_id_index` (`image_id`),
  ADD KEY `categories_user_id_index` (`user_id`),
  ADD KEY `categories_lang_index` (`lang`),
  ADD KEY `categories_status_index` (`status`),
  ADD KEY `categories_created_at_index` (`created_at`),
  ADD KEY `categories_updated_at_index` (`updated_at`);

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `collections_slug_unique` (`slug`),
  ADD KEY `collections_title_index` (`title`),
  ADD KEY `collections_excerpt_index` (`excerpt`),
  ADD KEY `collections_image_id_index` (`image_id`),
  ADD KEY `collections_front_page_index` (`front_page`),
  ADD KEY `collections_user_id_index` (`user_id`),
  ADD KEY `collections_lang_index` (`lang`),
  ADD KEY `collections_created_at_index` (`created_at`),
  ADD KEY `collections_published_at_index` (`published_at`),
  ADD KEY `collections_updated_at_index` (`updated_at`);

--
-- Indexes for table `collections_posts`
--
ALTER TABLE `collections_posts`
  ADD KEY `collections_posts_post_id_index` (`post_id`),
  ADD KEY `collections_posts_collection_id_index` (`collection_id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `colors_name_unique` (`name`),
  ADD UNIQUE KEY `colors_value_unique` (`value`),
  ADD KEY `colors_add_to_filter_index` (`add_to_filter`),
  ADD KEY `colors_user_id_index` (`user_id`),
  ADD KEY `colors_lang_index` (`lang`),
  ADD KEY `colors_created_at_index` (`created_at`),
  ADD KEY `colors_updated_at_index` (`updated_at`);

--
-- Indexes for table `color_post`
--
ALTER TABLE `color_post`
  ADD KEY `color_post_post_id_index` (`post_id`),
  ADD KEY `color_post_color_id_index` (`color_id`);

--
-- Indexes for table `contests`
--
ALTER TABLE `contests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contests_slug_unique` (`slug`),
  ADD KEY `contests_title_index` (`title`),
  ADD KEY `contests_hash_tag_index` (`hash_tag`),
  ADD KEY `contests_lang_index` (`lang`),
  ADD KEY `contests_image_id_index` (`image_id`),
  ADD KEY `contests_user_id_index` (`user_id`),
  ADD KEY `contests_expired_at_index` (`expired_at`),
  ADD KEY `contests_published_at_index` (`published_at`),
  ADD KEY `contests_created_at_index` (`created_at`),
  ADD KEY `contests_updated_at_index` (`updated_at`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galleries_name_index` (`name`),
  ADD KEY `galleries_slug_index` (`slug`),
  ADD KEY `galleries_author_index` (`author`),
  ADD KEY `galleries_lang_index` (`lang`),
  ADD KEY `galleries_user_id_index` (`user_id`),
  ADD KEY `galleries_created_at_index` (`created_at`),
  ADD KEY `galleries_updated_at_index` (`updated_at`);

--
-- Indexes for table `galleries_media`
--
ALTER TABLE `galleries_media`
  ADD KEY `galleries_media_gallery_id_index` (`gallery_id`),
  ADD KEY `galleries_media_media_id_index` (`media_id`),
  ADD KEY `galleries_media_order_index` (`order`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_type_index` (`type`),
  ADD KEY `media_path_index` (`path`),
  ADD KEY `media_title_index` (`title`),
  ADD KEY `media_provider_index` (`provider`),
  ADD KEY `media_provider_id_index` (`provider_id`),
  ADD KEY `media_provider_image_index` (`provider_image`),
  ADD KEY `media_user_id_index` (`user_id`),
  ADD KEY `media_length_index` (`length`),
  ADD KEY `media_hash_index` (`hash`),
  ADD KEY `media_created_at_index` (`created_at`),
  ADD KEY `media_updated_at_index` (`updated_at`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `options_name_index` (`name`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `posts_slug_unique` (`slug`),
  ADD KEY `posts_title_index` (`title`),
  ADD KEY `posts_excerpt_index` (`excerpt`),
  ADD KEY `posts_image_id_index` (`image_id`),
  ADD KEY `posts_media_id_index` (`media_id`),
  ADD KEY `posts_user_id_index` (`user_id`),
  ADD KEY `posts_status_index` (`status`),
  ADD KEY `posts_format_index` (`format`),
  ADD KEY `posts_lang_index` (`lang`),
  ADD KEY `posts_views_index` (`views`),
  ADD KEY `posts_created_at_index` (`created_at`),
  ADD KEY `posts_updated_at_index` (`updated_at`),
  ADD KEY `posts_published_at_index` (`published_at`);

--
-- Indexes for table `posts_blocks`
--
ALTER TABLE `posts_blocks`
  ADD KEY `posts_blocks_post_id_index` (`post_id`),
  ADD KEY `posts_blocks_block_id_index` (`block_id`),
  ADD KEY `posts_blocks_order_index` (`order`),
  ADD KEY `posts_blocks_lang_index` (`lang`);

--
-- Indexes for table `posts_categories`
--
ALTER TABLE `posts_categories`
  ADD KEY `posts_categories_post_id_index` (`post_id`),
  ADD KEY `posts_categories_category_id_index` (`category_id`);

--
-- Indexes for table `posts_galleries`
--
ALTER TABLE `posts_galleries`
  ADD KEY `posts_galleries_post_id_index` (`post_id`),
  ADD KEY `posts_galleries_gallery_id_index` (`gallery_id`);

--
-- Indexes for table `posts_meta`
--
ALTER TABLE `posts_meta`
  ADD KEY `posts_meta_post_id_index` (`post_id`),
  ADD KEY `posts_meta_name_index` (`name`);

--
-- Indexes for table `posts_sizes`
--
ALTER TABLE `posts_sizes`
  ADD KEY `posts_sizes_post_id_index` (`post_id`),
  ADD KEY `posts_sizes_size_index` (`size`);

--
-- Indexes for table `posts_tags`
--
ALTER TABLE `posts_tags`
  ADD KEY `posts_tags_post_id_index` (`post_id`),
  ADD KEY `posts_tags_tag_id_index` (`tag_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  ADD KEY `roles_permissions_role_id_index` (`role_id`),
  ADD KEY `roles_permissions_permission_index` (`permission`);

--
-- Indexes for table `sets`
--
ALTER TABLE `sets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sets_slug_unique` (`slug`),
  ADD KEY `sets_title_index` (`title`),
  ADD KEY `sets_excerpt_index` (`excerpt`),
  ADD KEY `sets_image_id_index` (`image_id`),
  ADD KEY `sets_user_id_index` (`user_id`),
  ADD KEY `sets_lang_index` (`lang`),
  ADD KEY `sets_created_at_index` (`created_at`),
  ADD KEY `sets_updated_at_index` (`updated_at`);

--
-- Indexes for table `sets_posts`
--
ALTER TABLE `sets_posts`
  ADD KEY `sets_posts_post_id_index` (`post_id`),
  ADD KEY `sets_posts_set_id_index` (`set_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tags_name_index` (`name`),
  ADD KEY `tags_slug_index` (`slug`),
  ADD KEY `tags_created_at_index` (`created_at`),
  ADD KEY `tags_updated_at_index` (`updated_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_api_token_unique` (`api_token`),
  ADD KEY `users_first_name_index` (`first_name`),
  ADD KEY `users_created_at_index` (`created_at`),
  ADD KEY `users_updated_at_index` (`updated_at`),
  ADD KEY `users_username_index` (`username`),
  ADD KEY `users_last_name_index` (`last_name`),
  ADD KEY `users_provider_index` (`provider`),
  ADD KEY `users_provider_id_index` (`provider_id`),
  ADD KEY `users_code_index` (`code`),
  ADD KEY `users_role_id_index` (`role_id`),
  ADD KEY `users_last_login_index` (`last_login`),
  ADD KEY `users_status_index` (`status`),
  ADD KEY `users_backend_index` (`backend`),
  ADD KEY `users_root_index` (`root`),
  ADD KEY `users_photo_id_index` (`photo_id`),
  ADD KEY `users_lang_index` (`lang`),
  ADD KEY `users_color_index` (`color`),
  ADD KEY `users_facebook_index` (`facebook`),
  ADD KEY `users_twitter_index` (`twitter`),
  ADD KEY `users_linked_in_index` (`linked_in`),
  ADD KEY `users_google_plus_index` (`google_plus`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blocks`
--
ALTER TABLE `blocks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `contests`
--
ALTER TABLE `contests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sets`
--
ALTER TABLE `sets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
