-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 27, 2022 at 07:27 AM
-- Server version: 8.0.28-0ubuntu0.20.04.3
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_hrm`
--

-- --------------------------------------------------------

--
-- Table structure for table `all_contents`
--

CREATE TABLE `all_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keywords` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT '1',
  `updated_by` bigint UNSIGNED DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `all_contents`
--

INSERT INTO `all_contents` (`id`, `company_id`, `user_id`, `type`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `keywords`, `meta_image`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'page', 'About Us', 'about-us', 'About Us', 'About Us', 'About Us', 'about, us, about us', '', 1, 1, '2022-03-22 06:39:40', '2022-03-22 06:39:40'),
(2, 1, 1, 'page', 'Contact Us', 'contact-us', '<p>Contact Us</p>', 'Contact Us', 'Contact Us', 'contact, us, contact us', '', 1, 1, '2022-03-22 06:39:40', '2022-03-22 06:39:40'),
(3, 1, 1, 'page', 'Privacy Policy', 'privacy-policy', '<p>This CRM Privacy Policy includes how we collect, use, disclose, transfer, and store your personal data for the activities mentioned-below, including your visit to CRM website that links to this page, your attendance to our marketing and learning events both online and offline, and for our business account management. Your choices and rights related to your personal data are extensively described here.</p>', 'Privacy Policy', NULL, 'privacy, policy, privacy policy', '', 1, 1, '2022-03-22 06:39:40', '2022-03-27 00:59:57'),
(4, 1, 1, 'page', 'Support 24/7', 'support-24-7', '<p>support</p>', 'Terms of Use', 'support', 'supports, 24, 7, support 24/7', '', 1, 1, '2022-03-22 06:39:40', '2022-03-22 06:39:40');
INSERT INTO `all_contents` (`id`, `company_id`, `user_id`, `type`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `keywords`, `meta_image`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(5, 1, 1, 'page', 'Terms of Use', 'terms-of-use', '<p>Terms of Use</p>', 'Terms of Use', NULL, 'terms, of, use, terms of use', '', 1, 1, '2022-03-22 06:39:40', '2022-03-27 01:08:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `all_contents`
--
ALTER TABLE `all_contents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `all_contents_title_unique` (`title`),
  ADD UNIQUE KEY `all_contents_slug_unique` (`slug`),
  ADD KEY `all_contents_company_id_foreign` (`company_id`),
  ADD KEY `all_contents_user_id_foreign` (`user_id`),
  ADD KEY `all_contents_created_by_foreign` (`created_by`),
  ADD KEY `all_contents_updated_by_foreign` (`updated_by`),
  ADD KEY `all_contents_type_title_slug_index` (`type`,`title`,`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `all_contents`
--
ALTER TABLE `all_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `all_contents`
--
ALTER TABLE `all_contents`
  ADD CONSTRAINT `all_contents_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `all_contents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `all_contents_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `all_contents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
