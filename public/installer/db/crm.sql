-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 18, 2023 at 07:15 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crm`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ac_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ac_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(16,2) NOT NULL DEFAULT '0.00',
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `updated_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `company_id`, `name`, `ac_name`, `ac_number`, `code`, `branch`, `amount`, `status_id`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'Account 1', 'John Doe', '123456789', '123456789', 'California', 160000.00, 1, 2, 2, '2023-04-18 01:07:43', '2023-04-18 01:07:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint UNSIGNED NOT NULL,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advance_salaries`
--

CREATE TABLE `advance_salaries` (
  `id` bigint UNSIGNED NOT NULL,
  `advance_type_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `company_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `amount` double(16,2) DEFAULT NULL,
  `request_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `paid_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `due_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `recovery_mode` tinyint NOT NULL DEFAULT '1' COMMENT '1=Installment, 2=One Time',
  `recovery_cycle` tinyint NOT NULL DEFAULT '1' COMMENT '1=Monthly, 2=Yearly',
  `installment_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `recover_from` date NOT NULL,
  `pay` bigint UNSIGNED NOT NULL DEFAULT '9',
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '2',
  `approver_id` bigint UNSIGNED DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `return_status` bigint UNSIGNED NOT NULL DEFAULT '22',
  `created_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `updated_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advance_salary_logs`
--

CREATE TABLE `advance_salary_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `amount` double(16,2) NOT NULL,
  `due_amount` double(16,2) DEFAULT NULL,
  `is_pay` tinyint NOT NULL DEFAULT '0' COMMENT '0=Company Pay, 1= Staff Pay',
  `advance_salary_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `updated_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `payment_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advance_types`
--

CREATE TABLE `advance_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT '1',
  `updated_by` bigint UNSIGNED DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `advance_types`
--

INSERT INTO `advance_types` (`id`, `name`, `company_id`, `status_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Salary Advance', 2, 1, 1, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(2, 'Loan', 2, 1, 1, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43');

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
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `all_contents`
--

INSERT INTO `all_contents` (`id`, `company_id`, `user_id`, `type`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `keywords`, `meta_image`, `created_by`, `updated_by`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'page', 'About Us', 'about-us', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id.</p>', 'About Us', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id.', 'about, us, about us', '', 1, 1, 1, '2022-03-22 00:39:40', '2022-03-22 00:39:40'),
(2, 1, 1, 'page', 'Contact Us', 'contact-us', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id.</p>', 'Contact Us', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id.', 'contact, us, contact us', '', 1, 1, 1, '2022-03-22 00:39:40', '2022-03-22 00:39:40'),
(3, 1, 1, 'page', 'Privacy Policy', 'privacy-policy', '<p>This CRM Privacy Policy includes how we collect, use, disclose, transfer, and store your personal data for the activities mentioned-below, including your visit to CRM website that links to this page, your attendance to our marketing and learning events both online and offline, and for our business account management. </p>', 'Privacy Policy', NULL, 'privacy, policy, privacy policy', '', 1, 1, 1, '2022-03-22 00:39:40', '2022-03-26 18:59:57'),
(4, 1, 1, 'page', 'Support 24/7', 'support-24-7', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id.</p>', 'Terms of Use', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id.', 'supports, 24, 7, support 24/7', '', 1, 1, 1, '2022-03-22 00:39:40', '2022-03-22 00:39:40'),
(5, 1, 1, 'page', 'Terms of Use', 'terms-of-use', '<p>Terms and Conditions</p>', 'Terms of Use', NULL, 'terms, of, use, terms of use', '', 1, 1, 1, '2022-03-22 00:39:40', '2022-03-26 19:08:52');

-- --------------------------------------------------------

--
-- Table structure for table `api_setups`
--

CREATE TABLE `api_setups` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endpoint` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `docs_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `api_setups`
--

INSERT INTO `api_setups` (`id`, `name`, `key`, `secret`, `endpoint`, `docs_url`, `company_id`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 'google', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL),
(2, 'barikoi', NULL, NULL, NULL, NULL, 1, 4, NULL, NULL),
(3, 'google', NULL, NULL, NULL, NULL, 2, 1, NULL, NULL),
(4, 'barikoi', NULL, NULL, NULL, NULL, 2, 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `appoinments`
--

CREATE TABLE `appoinments` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `appoinment_with` bigint UNSIGNED NOT NULL,
  `appoinment_start_at` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `appoinment_end_at` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `date` date DEFAULT NULL,
  `attachment_file_id` bigint UNSIGNED DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appoinments`
--

INSERT INTO `appoinments` (`id`, `company_id`, `created_by`, `appoinment_with`, `appoinment_start_at`, `appoinment_end_at`, `title`, `location`, `description`, `date`, `attachment_file_id`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 4, '07:07:43', '8:07:43', 'Veritatis amet numquam est aut minus.', '31269 Freda Turnpike Suite 962\nSouth Nyamouth, OK 81120-5664', 'Adipisci nesciunt rerum aut excepturi aliquam explicabo quas. Laudantium repellat dolor sapiente quia. Qui sint est ab nemo. Porro et ratione hic debitis ut iste nulla illo.', '2023-04-24', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(2, 2, 3, 5, '07:07:43', '8:07:43', 'Velit non eius inventore quisquam dolore qui qui.', '2594 Botsford Village\nSchroedershire, ME 48215', 'Impedit est nihil illo odio consequatur eveniet voluptatem. Odit ut aut est possimus autem. Alias iure voluptatem ipsa ipsa ipsum. Iusto unde sunt aut impedit nostrum.', '2023-04-27', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(3, 2, 4, 3, '07:07:43', '8:07:43', 'Quia quaerat iste magnam.', '4651 Kuhlman Lodge Suite 243\nNew Frankland, MO 11563', 'Quisquam sed cupiditate id dolor provident tempore. Labore at ipsum et nihil non rerum. Corporis modi vero repudiandae inventore.', '2023-04-15', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(4, 2, 5, 4, '07:07:43', '8:07:43', 'Dolores qui ea similique iste placeat sed.', '3790 Wallace Vista Suite 810\nFriesenton, AR 62408-4629', 'Facilis aliquid autem tempore autem quos dolor nostrum quia. Aut non autem qui molestiae velit. Maiores fugiat blanditiis sed quia qui dicta voluptas.', '2023-04-06', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `appoinment_participants`
--

CREATE TABLE `appoinment_participants` (
  `id` bigint UNSIGNED NOT NULL,
  `participant_id` bigint UNSIGNED NOT NULL,
  `appoinment_id` bigint UNSIGNED NOT NULL,
  `is_agree` tinyint NOT NULL DEFAULT '0' COMMENT '0: Not agree, 1: Agree',
  `is_present` tinyint NOT NULL DEFAULT '0' COMMENT '0: Absent, 1: Present',
  `present_at` datetime DEFAULT NULL,
  `appoinment_started_at` datetime DEFAULT NULL,
  `appoinment_ended_at` datetime DEFAULT NULL,
  `appoinment_duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'appoinment duration in minutes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appoinment_participants`
--

INSERT INTO `appoinment_participants` (`id`, `participant_id`, `appoinment_id`, `is_agree`, `is_present`, `present_at`, `appoinment_started_at`, `appoinment_ended_at`, `appoinment_duration`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 0, NULL, NULL, NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(2, 2, 1, 1, 0, NULL, NULL, NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(3, 2, 2, 1, 0, NULL, NULL, NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(4, 3, 2, 1, 0, NULL, NULL, NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(5, 2, 3, 1, 0, NULL, NULL, NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(6, 4, 3, 1, 0, NULL, NULL, NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(7, 2, 4, 1, 0, NULL, NULL, NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(8, 5, 4, 1, 0, NULL, NULL, NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `appraisals`
--

CREATE TABLE `appraisals` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rates` json DEFAULT NULL,
  `rating` double(8,2) DEFAULT '0.00',
  `user_id` bigint UNSIGNED NOT NULL,
  `added_by` bigint UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `remarks` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appraisals`
--

INSERT INTO `appraisals` (`id`, `company_id`, `name`, `rates`, `rating`, `user_id`, `added_by`, `date`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 2, 'Project Management', '[{\"id\": 1, \"rating\": 1}, {\"id\": 2, \"rating\": 1}, {\"id\": 3, \"rating\": 1}, {\"id\": 4, \"rating\": 1}, {\"id\": 5, \"rating\": 1}, {\"id\": 6, \"rating\": 1}]', 1.00, 4, 2, '2023-04-18', NULL, '2023-04-18 01:07:53', '2023-04-18 01:07:53');

-- --------------------------------------------------------

--
-- Table structure for table `appreciates`
--

CREATE TABLE `appreciates` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `appreciate_by` bigint UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_screens`
--

CREATE TABLE `app_screens` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` int DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL,
  `lavel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_screens`
--

INSERT INTO `app_screens` (`id`, `name`, `slug`, `position`, `icon`, `status_id`, `lavel`, `created_at`, `updated_at`) VALUES
(1, 'Clients', 'Clients', 1, 'public/assets/app/ScreenIcons/Clients.png', 1, NULL, '2023-04-18 01:07:12', '2023-04-18 01:07:12'),
(2, 'Employees', 'Employees', 2, 'public/assets/app/ScreenIcons/Employees.png', 1, NULL, '2023-04-18 01:07:12', '2023-04-18 01:07:12'),
(3, 'Projects', 'Projects', 3, 'public/assets/app/ScreenIcons/Projects.png', 1, NULL, '2023-04-18 01:07:12', '2023-04-18 01:07:12'),
(4, 'Tasks', 'Tasks', 4, 'public/assets/app/ScreenIcons/Tasks.png', 1, NULL, '2023-04-18 01:07:12', '2023-04-18 01:07:12'),
(5, 'Notice', 'Notice', 5, 'public/assets/app/ScreenIcons/Notice.png', 1, NULL, '2023-04-18 01:07:12', '2023-04-18 01:07:12'),
(6, 'Phonebook', 'Phonebook', 6, 'public/assets/app/ScreenIcons/Phonebook.png', 1, NULL, '2023-04-18 01:07:12', '2023-04-18 01:07:12'),
(7, 'Meeting', 'Meeting', 7, 'public/assets/app/ScreenIcons/Meeting.png', 1, NULL, '2023-04-18 01:07:12', '2023-04-18 01:07:12'),
(8, 'Attendance', 'Attendance', 8, 'public/assets/app/ScreenIcons/Attendance.png', 1, NULL, '2023-04-18 01:07:12', '2023-04-18 01:07:12'),
(9, 'Leave', 'Leave', 9, 'public/assets/app/ScreenIcons/Leave.png', 1, NULL, '2023-04-18 01:07:12', '2023-04-18 01:07:12'),
(10, 'Visit', 'Visit', 10, 'public/assets/app/ScreenIcons/Visit.png', 1, NULL, '2023-04-18 01:07:12', '2023-04-18 01:07:12'),
(11, 'Support', 'Support', 11, 'public/assets/app/ScreenIcons/Support.png', 1, NULL, '2023-04-18 01:07:12', '2023-04-18 01:07:12');

-- --------------------------------------------------------

--
-- Table structure for table `assign_leaves`
--

CREATE TABLE `assign_leaves` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `type_id` bigint UNSIGNED NOT NULL,
  `days` int NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assign_leaves`
--

INSERT INTO `assign_leaves` (`id`, `company_id`, `type_id`, `days`, `department_id`, `status_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 20, 1, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(2, 1, 1, 14, 2, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(3, 1, 1, 10, 3, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(4, 1, 2, 15, 1, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(5, 1, 2, 10, 2, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(6, 1, 2, 17, 3, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(7, 1, 3, 11, 1, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(8, 1, 3, 17, 2, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(9, 1, 3, 14, 3, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(10, 1, 4, 11, 1, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(11, 1, 4, 11, 2, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(12, 1, 4, 14, 3, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(13, 1, 5, 14, 1, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(14, 1, 5, 19, 2, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(15, 1, 5, 10, 3, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(16, 2, 6, 12, 4, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(17, 2, 6, 19, 5, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(18, 2, 6, 12, 6, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(19, 2, 7, 16, 4, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(20, 2, 7, 11, 5, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(21, 2, 7, 12, 6, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(22, 2, 8, 13, 4, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(23, 2, 8, 17, 5, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(24, 2, 8, 12, 6, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(25, 2, 9, 16, 4, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(26, 2, 9, 17, 5, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(27, 2, 9, 10, 6, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(28, 2, 10, 16, 4, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(29, 2, 10, 14, 5, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(30, 2, 10, 11, 6, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `check_in` timestamp NULL DEFAULT NULL,
  `check_out` timestamp NULL DEFAULT NULL,
  `stay_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `late_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `late_time` int NOT NULL DEFAULT '0',
  `in_status` enum('OT','L','A') COLLATE utf8mb4_unicode_ci DEFAULT 'OT' COMMENT 'OT=On Time, L=Late, A=Absent',
  `out_status` enum('LT','LE','LL') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'LT=Left Timely, LE=Left Early, LL = Left Later',
  `checkin_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checkout_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remote_mode_in` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = home , 1 = office',
  `remote_mode_out` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = home , 1 = office',
  `check_in_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_in_latitude` double DEFAULT NULL COMMENT 'check in latitude',
  `check_in_longitude` double DEFAULT NULL COMMENT 'check in longitude',
  `check_out_latitude` double DEFAULT NULL COMMENT 'check out latitude',
  `check_out_longitude` double DEFAULT NULL COMMENT 'check out longitude',
  `check_in_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'city',
  `check_in_country_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'countryCode',
  `check_in_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Bangladesh' COMMENT 'country',
  `check_out_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'city',
  `check_out_country_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'countryCode',
  `check_out_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Bangladesh' COMMENT 'country',
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `author_infos`
--

CREATE TABLE `author_infos` (
  `id` bigint UNSIGNED NOT NULL,
  `authorable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `authorable_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint UNSIGNED DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `cancelled_by` bigint UNSIGNED DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `published_by` bigint UNSIGNED DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `unpublished_by` bigint UNSIGNED DEFAULT NULL,
  `unpublished_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `archived_by` bigint UNSIGNED DEFAULT NULL,
  `archived_at` timestamp NULL DEFAULT NULL,
  `restored_by` bigint UNSIGNED DEFAULT NULL,
  `restored_at` timestamp NULL DEFAULT NULL,
  `referred_by` bigint UNSIGNED DEFAULT NULL,
  `referred_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `author_infos`
--

INSERT INTO `author_infos` (`id`, `authorable_type`, `authorable_id`, `created_by`, `updated_by`, `approved_by`, `approved_at`, `rejected_by`, `rejected_at`, `cancelled_by`, `cancelled_at`, `published_by`, `published_at`, `unpublished_by`, `unpublished_at`, `deleted_by`, `deleted_at`, `archived_by`, `archived_at`, `restored_by`, `restored_at`, `referred_by`, `referred_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(2, 'App\\Models\\User', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(3, 'App\\Models\\User', 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(4, 'App\\Models\\User', 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(5, 'App\\Models\\User', 5, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42');

-- --------------------------------------------------------

--
-- Table structure for table `awards`
--

CREATE TABLE `awards` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `award_type_id` bigint UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `gift` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(16,2) DEFAULT NULL,
  `gift_info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `attachment` bigint UNSIGNED DEFAULT NULL,
  `goal_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `awards`
--

INSERT INTO `awards` (`id`, `company_id`, `user_id`, `created_by`, `award_type_id`, `date`, `gift`, `amount`, `gift_info`, `description`, `status_id`, `attachment`, `goal_id`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 2, 1, '2020-01-01', 'Gift 1', 100.00, 'Gift info 1', '<strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 1, NULL, NULL, '2019-12-31 18:00:00', '2019-12-31 18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `award_types`
--

CREATE TABLE `award_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT '1',
  `updated_by` bigint UNSIGNED DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `award_types`
--

INSERT INTO `award_types` (`id`, `name`, `company_id`, `status_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Award 1', 2, 1, 2, 2, '2023-04-18 01:07:53', '2023-04-18 01:07:53'),
(2, 'Award 2', 2, 1, 2, 2, '2023-04-18 01:07:53', '2023-04-18 01:07:53'),
(3, 'Award 3', 2, 1, 2, 2, '2023-04-18 01:07:53', '2023-04-18 01:07:53');

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Account Number',
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Bank Name',
  `branch_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Bank branch name',
  `ifsc_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IFSC Code',
  `account_type` enum('savings','current') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'savings',
  `account_holder_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_holder_mobile` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_holder_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` bigint UNSIGNED DEFAULT NULL,
  `author_info_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'name',
  `type` tinyint NOT NULL COMMENT '1=income 2=expense',
  `serial` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'serial',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'description',
  `status_id` bigint UNSIGNED DEFAULT NULL,
  `author_info_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `date` date DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `company_id` bigint UNSIGNED NOT NULL,
  `avatar_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `email`, `phone`, `address`, `city`, `state`, `zip`, `country`, `website`, `description`, `date`, `status_id`, `company_id`, `avatar_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Arun Kumar', 'arun@crm.com', '1234567891', '123 Main St1', 'New York1', 'NY1', '100011', 'USA1', 'www.john1.com', NULL, NULL, 1, 2, 51, '2023-04-18 01:07:46', '2023-04-18 01:07:46', NULL),
(2, 'Ebrahim', 'ebrahim@crm.com', '12345678912', '123 Main St1', 'New York1', 'NY1', '100011', 'USA1', 'www.john1.com', NULL, NULL, 1, 2, 49, '2023-04-18 01:07:46', '2023-04-18 01:07:46', NULL),
(3, 'Nithin', 'nithin@crm.com', '12345678913', '123 Main St1', 'New York1', 'NY1', '100011', 'USA1', 'www.john1.com', NULL, NULL, 1, 2, 52, '2023-04-18 01:07:46', '2023-04-18 01:07:46', NULL),
(4, 'Rajesh', 'rajesh@crm.com', '12345678914', '123 Main St1', 'New York1', 'NY1', '100011', 'USA1', 'www.john1.com', NULL, NULL, 1, 2, 48, '2023-04-18 01:07:46', '2023-04-18 01:07:46', NULL),
(5, 'Viraj Kumar', 'viraj@crm.com', '12345678915', '123 Main St1', 'New York1', 'NY1', '100011', 'USA1', 'www.john1.com', NULL, NULL, 1, 2, 39, '2023-04-18 01:07:46', '2023-04-18 01:07:46', NULL),
(6, 'Eng Khaled', 'khaled@crm.com', '12345678916', '123 Main St1', 'New York1', 'NY1', '100011', 'USA1', 'www.john1.com', NULL, NULL, 1, 2, 53, '2023-04-18 01:07:46', '2023-04-18 01:07:46', NULL),
(7, 'Mark Nicolau', 'mark@crm.com', '12345678917', '123 Main St1', 'New York1', 'NY1', '100011', 'USA1', 'www.john1.com', NULL, NULL, 1, 2, 47, '2023-04-18 01:07:46', '2023-04-18 01:07:46', NULL),
(8, 'Harriben', 'harriben@crm.com', '12345678918', '123 Main St1', 'New York1', 'NY1', '100011', 'USA1', 'www.john1.com', NULL, NULL, 1, 2, 47, '2023-04-18 01:07:46', '2023-04-18 01:07:46', NULL),
(9, 'Muhammad Irfan', 'irfan@crm.com', '12345678919', '123 Main St1', 'New York1', 'NY1', '100011', 'USA1', 'www.john1.com', NULL, NULL, 1, 2, 45, '2023-04-18 01:07:46', '2023-04-18 01:07:46', NULL),
(10, 'Prakash', 'prakash@crm.com', '123456789', '123 Main St1', 'New York1', 'NY1', '100011', 'USA1', 'www.john1.com', NULL, NULL, 1, 2, 53, '2023-04-18 01:07:46', '2023-04-18 01:07:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `type` tinyint NOT NULL DEFAULT '1' COMMENT '1: addition, 2: deduction',
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT '1',
  `updated_by` bigint UNSIGNED DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `commissions`
--

INSERT INTO `commissions` (`id`, `name`, `company_id`, `type`, `status_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Bonus', 2, 1, 1, 1, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(2, 'Penalty', 2, 2, 1, 1, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint UNSIGNED NOT NULL,
  `country_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_employee` int DEFAULT NULL,
  `business_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trade_licence_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trade_licence_id` bigint UNSIGNED DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `is_main_company` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `country_id`, `name`, `company_name`, `email`, `phone`, `total_employee`, `business_type`, `trade_licence_number`, `trade_licence_id`, `status_id`, `is_main_company`, `created_at`, `updated_at`) VALUES
(1, 16, 'Super Admin', 'Company 01', 'superadmin@onesttech.com', '+8801910077628', 10, 'Service', NULL, NULL, 1, 'yes', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(2, 223, 'Admin', 'Company 02', 'admin@onesttech.com', '+880177777777', 400, 'Service', NULL, NULL, 1, 'no', '2023-04-18 01:07:40', '2023-04-18 01:07:40');

-- --------------------------------------------------------

--
-- Table structure for table `company_configs`
--

CREATE TABLE `company_configs` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_configs`
--

INSERT INTO `company_configs` (`id`, `key`, `value`, `company_id`, `created_at`, `updated_at`) VALUES
(1, 'date_format', 'd-m-Y', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(2, 'time_format', 'h', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(3, 'ip_check', '0', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(4, 'currency_symbol', '$', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(5, 'location_service', '0', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(6, 'app_sync_time', '', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(7, 'live_data_store_time', '', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(8, 'lang', 'en', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(9, 'multi_checkin', '0', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(10, 'currency', '2', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(11, 'timezone', 'Asia/Dhaka', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(12, 'currency_code', 'USD', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(13, 'location_check', '0', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(14, 'attendance_method', 'N', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(15, 'exchange_rate', '1', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(16, 'date_format', 'd-m-Y', 2, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(17, 'time_format', 'h', 2, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(18, 'ip_check', '0', 2, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(19, 'currency_symbol', '$', 2, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(20, 'location_service', '0', 2, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(21, 'app_sync_time', '', 2, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(22, 'live_data_store_time', '', 2, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(23, 'lang', 'en', 2, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(24, 'multi_checkin', '0', 2, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(25, 'currency', '2', 2, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(26, 'timezone', 'Asia/Dhaka', 2, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(27, 'currency_code', 'USD', 2, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(28, 'location_check', '0', 2, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(29, 'attendance_method', 'N', 2, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(30, 'exchange_rate', '1', 2, '2023-04-18 01:07:42', '2023-04-18 01:07:42');

-- --------------------------------------------------------

--
-- Table structure for table `competences`
--

CREATE TABLE `competences` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `competence_type_id` bigint UNSIGNED DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT '1',
  `updated_by` bigint UNSIGNED DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `competences`
--

INSERT INTO `competences` (`id`, `name`, `competence_type_id`, `company_id`, `status_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Leadership', 1, 2, 1, 2, 2, '2023-04-18 01:07:53', '2023-04-18 01:07:53'),
(2, 'Project Management', 1, 2, 1, 2, 2, '2023-04-18 01:07:53', '2023-04-18 01:07:53'),
(3, 'Allocating Resources', 2, 2, 1, 2, 2, '2023-04-18 01:07:53', '2023-04-18 01:07:53'),
(4, 'Team Work', 2, 2, 1, 2, 2, '2023-04-18 01:07:53', '2023-04-18 01:07:53'),
(5, 'Business Process', 3, 2, 1, 2, 2, '2023-04-18 01:07:53', '2023-04-18 01:07:53'),
(6, 'Oral Communication', 3, 2, 1, 2, 2, '2023-04-18 01:07:53', '2023-04-18 01:07:53');

-- --------------------------------------------------------

--
-- Table structure for table `competence_types`
--

CREATE TABLE `competence_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT '1',
  `updated_by` bigint UNSIGNED DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `competence_types`
--

INSERT INTO `competence_types` (`id`, `name`, `company_id`, `status_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Organizational Competencies', 2, 1, 2, 2, '2023-04-18 01:07:53', '2023-04-18 01:07:53'),
(2, 'Technical Competencies', 2, 1, 2, 2, '2023-04-18 01:07:53', '2023-04-18 01:07:53'),
(3, 'Behavioural Competencies', 2, 1, 2, 2, '2023-04-18 01:07:53', '2023-04-18 01:07:53');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_for` int NOT NULL DEFAULT '0' COMMENT '1 for support,0 for query',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 for unread,1 for read',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint UNSIGNED NOT NULL,
  `sender_id` bigint UNSIGNED NOT NULL,
  `receiver_id` bigint UNSIGNED NOT NULL,
  `type` enum('notification','message') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'notification' COMMENT 'notification: notification, message: message',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `read_at` timestamp NULL DEFAULT NULL,
  `image_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint UNSIGNED NOT NULL,
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_zone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_placement` enum('before','after') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country_code`, `name`, `time_zone`, `currency_code`, `currency_symbol`, `currency_name`, `currency_symbol_placement`, `created_at`, `updated_at`) VALUES
(1, 'AF', 'Afghanistan', 'Asia/Kabul', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(2, 'AL', 'Albania', 'Europe/Tirane', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(3, 'DZ', 'Algeria', 'Africa/Algiers', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(4, 'AD', 'Andorra', 'Europe/Andorra', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(5, 'AO', 'Angola', 'Africa/Luanda', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(6, 'AI', 'Anguilla', 'America/Anguilla', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(7, 'AQ', 'Antarctica', 'Antarctica/Casey', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(8, 'AR', 'Argentina', 'America/Argentina/Buenos_Aires', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(9, 'AM', 'Armenia', 'Asia/Yerevan', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(10, 'AW', 'Aruba', 'America/Aruba', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(11, 'AU', 'Australia', 'Antarctica/Macquarie', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(12, 'AT', 'Austria', 'Europe/Vienna', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(13, 'AZ', 'Azerbaijan', 'Asia/Baku', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(14, 'BS', 'Bahamas', 'America/Nassau', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(15, 'BH', 'Bahrain', 'Asia/Bahrain', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(16, 'BD', 'Bangladesh', 'Asia/Dhaka', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(17, 'BB', 'Barbados', 'America/Barbados', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(18, 'BY', 'Belarus', 'Europe/Minsk', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(19, 'BE', 'Belgium', 'Europe/Brussels', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(20, 'BZ', 'Belize', 'America/Belize', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(21, 'BJ', 'Benin', 'Africa/Porto-Novo', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(22, 'BM', 'Bermuda', 'Atlantic/Bermuda', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(23, 'BT', 'Bhutan', 'Asia/Thimphu', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(24, 'BO', 'Bolivia', 'America/La_Paz', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(25, 'BW', 'Botswana', 'Africa/Gaborone', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(26, 'BR', 'Brazil', 'America/Araguaina', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(27, 'BG', 'Bulgaria', 'Europe/Sofia', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(28, 'BI', 'Burundi', 'Africa/Bujumbura', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(29, 'KH', 'Cambodia', 'Asia/Phnom_Penh', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(30, 'CM', 'Cameroon', 'Africa/Douala', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(31, 'CA', 'Canada', 'America/Atikokan', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(32, 'CV', 'Cape Verde', 'Atlantic/Cape_Verde', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(33, 'KY', 'Cayman Islands', 'America/Cayman', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(34, 'TD', 'Chad', 'Africa/Ndjamena', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(35, 'CL', 'Chile', 'America/Punta_Arenas', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(36, 'CN', 'China', 'Asia/Shanghai', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(37, 'CX', 'Christmas Island', 'Indian/Christmas', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(38, 'CO', 'Colombia', 'America/Bogota', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(39, 'KM', 'Comoros', 'Indian/Comoro', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(40, 'CG', 'Republic of Congo', 'Africa/Brazzaville', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(41, 'CK', 'Cook Islands', 'Pacific/Rarotonga', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(42, 'CR', 'Costa Rica', 'America/Costa_Rica', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(43, 'HR', 'Croatia', 'Europe/Zagreb', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(44, 'CU', 'Cuba', 'America/Havana', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(45, 'CY', 'Cyprus', 'Asia/Famagusta', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(46, 'CZ', 'Czech Republic', 'Europe/Prague', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(47, 'DK', 'Denmark', 'Europe/Copenhagen', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(48, 'DJ', 'Djibouti', 'Africa/Djibouti', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(49, 'DM', 'Dominica', 'America/Dominica', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(50, 'DO', 'Dominican Republic', 'America/Santo_Domingo', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(51, 'EC', 'Ecuador', 'America/Guayaquil', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(52, 'EG', 'Egypt', 'Africa/Cairo', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(53, 'SV', 'El Salvador', 'America/El_Salvador', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(54, 'GQ', 'Equatorial Guinea', 'Africa/Malabo', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(55, 'ER', 'Eritrea', 'Africa/Asmara', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(56, 'EE', 'Estonia', 'Europe/Tallinn', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(57, 'ET', 'Ethiopia', 'Africa/Addis_Ababa', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(58, 'FO', 'Faroe Islands', 'Atlantic/Faroe', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(59, 'FJ', 'Fiji', 'Pacific/Fiji', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(60, 'FI', 'Finland', 'Europe/Helsinki', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(61, 'FR', 'France', 'Europe/Paris', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(62, 'GA', 'Gabon', 'Africa/Libreville', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(63, 'GM', 'Gambia', 'Africa/Banjul', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(64, 'GE', 'Georgia', 'Asia/Tbilisi', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(65, 'DE', 'Germany', 'Europe/Berlin', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(66, 'GH', 'Ghana', 'Africa/Accra', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(67, 'GI', 'Gibraltar', 'Europe/Gibraltar', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(68, 'GR', 'Greece', 'Europe/Athens', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(69, 'GL', 'Greenland', 'America/Danmarkshavn', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(70, 'GD', 'Grenada', 'America/Grenada', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(71, 'GP', 'Guadeloupe', 'America/Guadeloupe', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(72, 'GU', 'Guam', 'Pacific/Guam', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(73, 'GT', 'Guatemala', 'America/Guatemala', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(74, 'GN', 'Guinea', 'Africa/Conakry', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(75, 'GW', 'Guinea-Bissau', 'Africa/Bissau', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(76, 'GY', 'Guyana', 'America/Guyana', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(77, 'HT', 'Haiti', 'America/Port-au-Prince', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(78, 'HN', 'Honduras', 'America/Tegucigalpa', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(79, 'HK', 'Hong Kong', 'Asia/Hong_Kong', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(80, 'HU', 'Hungary', 'Europe/Budapest', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(81, 'IS', 'Iceland', 'Atlantic/Reykjavik', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(82, 'IN', 'India', 'Asia/Kolkata', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(83, 'IM', 'Isle of Man', 'Europe/Isle_of_Man', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(84, 'ID', 'Indonesia', 'Asia/Jakarta', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(85, 'IR', 'Iran', 'Asia/Tehran', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(86, 'IQ', 'Iraq', 'Asia/Baghdad', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(87, 'IE', 'Ireland', 'Europe/Dublin', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(88, 'IL', 'Israel', 'Asia/Jerusalem', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(89, 'IT', 'Italy', 'Europe/Rome', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(90, 'CI', 'Ivory Coast', 'Africa/Abidjan', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(91, 'JE', 'Jersey', 'Europe/Jersey', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(92, 'JM', 'Jamaica', 'America/Jamaica', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(93, 'JP', 'Japan', 'Asia/Tokyo', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(94, 'JO', 'Jordan', 'Asia/Amman', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(95, 'KZ', 'Kazakhstan', 'Asia/Almaty', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(96, 'KE', 'Kenya', 'Africa/Nairobi', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(97, 'KI', 'Kiribati', 'Pacific/Kanton', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(98, 'KR', 'Korea, Republic of', 'Asia/Seoul', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(99, 'KW', 'Kuwait', 'Asia/Kuwait', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(100, 'KG', 'Kyrgyzstan', 'Asia/Bishkek', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(101, 'LV', 'Latvia', 'Europe/Riga', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(102, 'LB', 'Lebanon', 'Asia/Beirut', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(103, 'LS', 'Lesotho', 'Africa/Maseru', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(104, 'LR', 'Liberia', 'Africa/Monrovia', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(105, 'LI', 'Liechtenstein', 'Europe/Vaduz', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(106, 'LT', 'Lithuania', 'Europe/Vilnius', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(107, 'LU', 'Luxembourg', 'Europe/Luxembourg', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(108, 'MO', 'Macau', 'Asia/Macau', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(109, 'MK', 'North Macedonia', 'Europe/Skopje', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(110, 'MG', 'Madagascar', 'Indian/Antananarivo', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(111, 'MW', 'Malawi', 'Africa/Blantyre', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(112, 'MY', 'Malaysia', 'Asia/Kuala_Lumpur', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(113, 'MV', 'Maldives', 'Indian/Maldives', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(114, 'ML', 'Mali', 'Africa/Bamako', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(115, 'MT', 'Malta', 'Europe/Malta', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(116, 'MH', 'Marshall Islands', 'Pacific/Kwajalein', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(117, 'MQ', 'Martinique', 'America/Martinique', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(118, 'MR', 'Mauritania', 'Africa/Nouakchott', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(119, 'MU', 'Mauritius', 'Indian/Mauritius', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(120, 'MX', 'Mexico', 'America/Bahia_Banderas', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(121, 'MD', 'Moldova, Republic of', 'Europe/Chisinau', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(122, 'MC', 'Monaco', 'Europe/Monaco', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(123, 'MN', 'Mongolia', 'Asia/Choibalsan', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(124, 'ME', 'Montenegro', 'Europe/Podgorica', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(125, 'MS', 'Montserrat', 'America/Montserrat', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(126, 'MA', 'Morocco', 'Africa/Casablanca', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(127, 'MZ', 'Mozambique', 'Africa/Maputo', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(128, 'MM', 'Myanmar', 'Asia/Yangon', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(129, 'NA', 'Namibia', 'Africa/Windhoek', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(130, 'NR', 'Nauru', 'Pacific/Nauru', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(131, 'NP', 'Nepal', 'Asia/Kathmandu', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(132, 'NL', 'Netherlands', 'Europe/Amsterdam', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(133, 'NC', 'New Caledonia', 'Pacific/Noumea', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(134, 'NZ', 'New Zealand', 'Pacific/Auckland', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(135, 'NI', 'Nicaragua', 'America/Managua', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(136, 'NE', 'Niger', 'Africa/Niamey', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(137, 'NG', 'Nigeria', 'Africa/Lagos', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(138, 'NU', 'Niue', 'Pacific/Niue', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(139, 'NF', 'Norfolk Island', 'Pacific/Norfolk', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(140, 'NO', 'Norway', 'Europe/Oslo', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(141, 'OM', 'Oman', 'Asia/Muscat', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(142, 'PK', 'Pakistan', 'Asia/Karachi', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(143, 'PW', 'Palau', 'Pacific/Palau', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(144, 'PS', 'Palestine', 'Asia/Gaza', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(145, 'PA', 'Panama', 'America/Panama', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(146, 'PG', 'Papua New Guinea', 'Pacific/Bougainville', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(147, 'PY', 'Paraguay', 'America/Asuncion', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(148, 'PE', 'Peru', 'America/Lima', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(149, 'PH', 'Philippines', 'Asia/Manila', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(150, 'PN', 'Pitcairn', 'Pacific/Pitcairn', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(151, 'PL', 'Poland', 'Europe/Warsaw', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(152, 'PT', 'Portugal', 'Atlantic/Azores', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(153, 'PR', 'Puerto Rico', 'America/Puerto_Rico', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(154, 'QA', 'Qatar', 'Asia/Qatar', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(155, 'RE', 'Reunion', 'Indian/Reunion', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(156, 'RO', 'Romania', 'Europe/Bucharest', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(157, 'RU', 'Russian Federation', 'Asia/Anadyr', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(158, 'RW', 'Rwanda', 'Africa/Kigali', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(159, 'LC', 'Saint Lucia', 'America/St_Lucia', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(160, 'WS', 'Samoa', 'Pacific/Apia', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(161, 'ST', 'Sao Tome and Principe', 'Africa/Sao_Tome', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(162, 'SA', 'Saudi Arabia', 'Asia/Riyadh', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(163, 'SN', 'Senegal', 'Africa/Dakar', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(164, 'RS', 'Serbia', 'Europe/Belgrade', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(165, 'SC', 'Seychelles', 'Indian/Mahe', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(166, 'SL', 'Sierra Leone', 'Africa/Freetown', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(167, 'SG', 'Singapore', 'Asia/Singapore', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(168, 'SK', 'Slovakia', 'Europe/Bratislava', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(169, 'SI', 'Slovenia', 'Europe/Ljubljana', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(170, 'SB', 'Solomon Islands', 'Pacific/Guadalcanal', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(171, 'SO', 'Somalia', 'Africa/Mogadishu', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(172, 'ZA', 'South Africa', 'Africa/Johannesburg', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(173, 'SS', 'South Sudan', 'Africa/Juba', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(174, 'ES', 'Spain', 'Africa/Ceuta', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(175, 'LK', 'Sri Lanka', 'Asia/Colombo', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(176, 'SH', 'St. Helena', 'Atlantic/St_Helena', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(177, 'PM', 'St. Pierre and Miquelon', 'America/Miquelon', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(178, 'SD', 'Sudan', 'Africa/Khartoum', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(179, 'SR', 'Suriname', 'America/Paramaribo', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(180, 'SZ', 'Swaziland', 'Africa/Mbabane', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(181, 'SE', 'Sweden', 'Europe/Stockholm', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(182, 'CH', 'Switzerland', 'Europe/Zurich', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(183, 'SY', 'Syrian Arab Republic', 'Asia/Damascus', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(184, 'TW', 'Taiwan', 'Asia/Taipei', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(185, 'TJ', 'Tajikistan', 'Asia/Dushanbe', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(186, 'TZ', 'Tanzania, United Republic of', 'Africa/Dar_es_Salaam', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(187, 'TH', 'Thailand', 'Asia/Bangkok', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(188, 'TG', 'Togo', 'Africa/Lome', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(189, 'TO', 'Tonga', 'Pacific/Tongatapu', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(190, 'TT', 'Trinidad and Tobago', 'America/Port_of_Spain', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(191, 'TN', 'Tunisia', 'Africa/Tunis', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(192, 'TR', 'Turkey', 'Europe/Istanbul', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(193, 'TM', 'Turkmenistan', 'Asia/Ashgabat', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(194, 'TV', 'Tuvalu', 'Pacific/Funafuti', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(195, 'UG', 'Uganda', 'Africa/Kampala', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(196, 'UA', 'Ukraine', 'Europe/Kyiv', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(197, 'AE', 'United Arab Emirates', 'Asia/Dubai', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(198, 'GB', 'United Kingdom', 'Europe/London', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(199, 'US', 'United States', 'America/Adak', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(200, 'UY', 'Uruguay', 'America/Montevideo', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(201, 'UZ', 'Uzbekistan', 'Asia/Samarkand', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(202, 'VU', 'Vanuatu', 'Pacific/Efate', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(203, 'VA', 'Vatican City State', 'Europe/Vatican', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(204, 'VE', 'Venezuela', 'America/Caracas', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(205, 'VN', 'Vietnam', 'Asia/Ho_Chi_Minh', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(206, 'VG', 'Virgin Islands (British)', 'America/Tortola', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(207, 'VI', 'Virgin Islands (U.S.)', 'America/St_Thomas', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(208, 'EH', 'Western Sahara', 'Africa/El_Aaiun', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(209, 'YE', 'Yemen', 'Asia/Aden', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(210, 'ZM', 'Zambia', 'Africa/Lusaka', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(211, 'ZW', 'Zimbabwe', 'Africa/Harare', NULL, NULL, NULL, NULL, '2023-04-18 01:07:40', '2023-04-18 01:07:40');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exchange_rate` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `code`, `symbol`, `exchange_rate`, `created_at`, `updated_at`) VALUES
(1, 'Leke', 'ALL', 'Lek', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(2, 'Dollars', 'USD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(3, 'Afghanis', 'AFN', '؋', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(4, 'Pesos', 'ARS', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(5, 'Guilders', 'AWG', 'ƒ', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(6, 'Dollars', 'AUD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(7, 'New Manats', 'AZN', 'ман', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(8, 'Dollars', 'BSD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(9, 'Dollars', 'BBD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(10, 'Rubles', 'BYR', 'p.', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(11, 'Euro', 'EUR', '€', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(12, 'Dollars', 'BZD', 'BZ$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(13, 'Dollars', 'BMD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(14, 'Bolivianos', 'BOB', '$b', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(15, 'Convertible Marka', 'BAM', 'KM', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(16, 'Pula', 'BWP', 'P', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(17, 'Leva', 'BGN', 'лв', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(18, 'Reais', 'BRL', 'R$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(19, 'Pounds', 'GBP', '£', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(20, 'Dollars', 'BND', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(21, 'Riels', 'KHR', '៛', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(22, 'Dollars', 'CAD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(23, 'Dollars', 'KYD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(24, 'Pesos', 'CLP', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(25, 'Yuan Renminbi', 'CNY', '¥', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(26, 'Pesos', 'COP', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(27, 'Colón', 'CRC', '₡', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(28, 'Kuna', 'HRK', 'kn', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(29, 'Pesos', 'CUP', '₱', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(30, 'Koruny', 'CZK', 'Kč', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(31, 'Kroner', 'DKK', 'kr', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(32, 'Pesos', 'DOP ', 'RD$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(33, 'Dollars', 'XCD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(34, 'Pounds', 'EGP', '£', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(35, 'Colones', 'SVC', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(36, 'Pounds', 'FKP', '£', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(37, 'Dollars', 'FJD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(38, 'Cedis', 'GHC', '¢', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(39, 'Pounds', 'GIP', '£', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(40, 'Quetzales', 'GTQ', 'Q', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(41, 'Pounds', 'GGP', '£', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(42, 'Dollars', 'GYD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(43, 'Lempiras', 'HNL', 'L', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(44, 'Dollars', 'HKD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(45, 'Forint', 'HUF', 'Ft', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(46, 'Kronur', 'ISK', 'kr', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(47, 'Rupees', 'INR', '₹', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(48, 'Rupiahs', 'IDR', 'Rp', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(49, 'Rials', 'IRR', '﷼', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(50, 'Pounds', 'IMP', '£', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(51, 'New Shekels', 'ILS', '₪', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(52, 'Dollars', 'JMD', 'J$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(53, 'Yen', 'JPY', '¥', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(54, 'Pounds', 'JEP', '£', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(55, 'Tenge', 'KZT', 'лв', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(56, 'Won', 'KPW', '₩', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(57, 'Won', 'KRW', '₩', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(58, 'Soms', 'KGS', 'лв', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(59, 'Kips', 'LAK', '₭', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(60, 'Lati', 'LVL', 'Ls', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(61, 'Pounds', 'LBP', '£', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(62, 'Dollars', 'LRD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(63, 'Switzerland Francs', 'CHF', 'CHF', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(64, 'Litai', 'LTL', 'Lt', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(65, 'Denars', 'MKD', 'ден', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(66, 'Ringgits', 'MYR', 'RM', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(67, 'Rupees', 'MUR', '₨', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(68, 'Pesos', 'MXN', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(69, 'Tugriks', 'MNT', '₮', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(70, 'Meticais', 'MZN', 'MT', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(71, 'Dollars', 'NAD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(72, 'Rupees', 'NPR', '₨', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(73, 'Guilders', 'ANG', 'ƒ', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(74, 'Dollars', 'NZD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(75, 'Cordobas', 'NIO', 'C$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(76, 'Nairas', 'NGN', '₦', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(77, 'Krone', 'NOK', 'kr', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(78, 'Rials', 'OMR', '﷼', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(79, 'Rupees', 'PKR', '₨', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(80, 'Balboa', 'PAB', 'B/.', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(81, 'Guarani', 'PYG', 'Gs', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(82, 'Nuevos Soles', 'PEN', 'S/.', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(83, 'Pesos', 'PHP', 'Php', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(84, 'Zlotych', 'PLN', 'zł', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(85, 'Rials', 'QAR', '﷼', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(86, 'New Lei', 'RON', 'lei', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(87, 'Rubles', 'RUB', 'руб', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(88, 'Pounds', 'SHP', '£', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(89, 'Riyals', 'SAR', '﷼', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(90, 'Dinars', 'RSD', 'Дин.', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(91, 'Rupees', 'SCR', '₨', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(92, 'Dollars', 'SGD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(93, 'Dollars', 'SBD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(94, 'Shillings', 'SOS', 'S', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(95, 'Rand', 'ZAR', 'R', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(96, 'Rupees', 'LKR', '₨', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(97, 'Kronor', 'SEK', 'kr', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(98, 'Dollars', 'SRD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(99, 'Pounds', 'SYP', '£', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(100, 'New Dollars', 'TWD', 'NT$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(101, 'Baht', 'THB', '฿', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(102, 'Dollars', 'TTD', 'TT$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(103, 'Lira', 'TRY', 'TL', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(104, 'Liras', 'TRL', '£', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(105, 'Dollars', 'TVD', '$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(106, 'Hryvnia', 'UAH', '₴', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(107, 'Pesos', 'UYU', '$U', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(108, 'Sums', 'UZS', 'лв', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(109, 'Bolivares Fuertes', 'VEF', 'Bs', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(110, 'Dong', 'VND', '₫', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(111, 'Rials', 'YER', '﷼', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(112, 'Taka', 'BDT', '৳', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(113, 'Zimbabwe Dollars', 'ZWD', 'Z$', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(114, 'Kenya', 'KES', 'KSh', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(115, 'Nigeria', 'naira', '₦', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(116, 'Ghana', 'GHS', 'GH₵', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(117, 'Ethiopian', 'ETB', 'Br', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(118, 'Tanzania', 'TZS', 'TSh', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(119, 'Uganda', 'UGX', 'USh', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(120, 'Rwandan', 'FRW', 'FRw', NULL, '2023-04-18 01:07:17', '2023-04-18 01:07:17');

-- --------------------------------------------------------

--
-- Table structure for table `daily_leaves`
--

CREATE TABLE `daily_leaves` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `approved_by_tl` bigint UNSIGNED DEFAULT NULL,
  `approved_at_tl` timestamp NULL DEFAULT NULL,
  `approved_by_hr` bigint UNSIGNED DEFAULT NULL,
  `approved_at_hr` timestamp NULL DEFAULT NULL,
  `rejected_by_tl` bigint UNSIGNED DEFAULT NULL,
  `rejected_at_tl` timestamp NULL DEFAULT NULL,
  `rejected_by_hr` bigint UNSIGNED DEFAULT NULL,
  `rejected_at_hr` timestamp NULL DEFAULT NULL,
  `leave_type` enum('early_leave','late_arrive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `database_backups`
--

CREATE TABLE `database_backups` (
  `id` bigint UNSIGNED NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `date_formats`
--

CREATE TABLE `date_formats` (
  `id` bigint UNSIGNED NOT NULL,
  `format` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `normal_view` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_by` int UNSIGNED DEFAULT '1',
  `updated_by` int UNSIGNED DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `date_formats`
--

INSERT INTO `date_formats` (`id`, `format`, `normal_view`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'jS M, Y', '17th May, 2019', 1, 1, 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(2, 'Y-m-d', '2019-05-17', 1, 1, 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(3, 'Y-d-m', '2019-17-05', 1, 1, 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(4, 'd-m-Y', '17-05-2019', 1, 1, 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(5, 'm-d-Y', '05-17-2019', 1, 1, 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(6, 'Y/m/d', '2019/05/17', 1, 1, 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(7, 'Y/d/m', '2019/17/05', 1, 1, 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(8, 'd/m/Y', '17/05/2019', 1, 1, 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(9, 'm/d/Y', '05/17/2019', 1, 1, 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(10, 'l jS \\of F Y', 'Monday 17th of May 2019', 1, 1, 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(11, 'jS \\of F Y', '17th of May 2019', 1, 1, 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(12, 'g:ia \\o\\n l jS F Y', '12:00am on Monday 17th May 2019', 1, 1, 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(13, 'F j, Y, g:i a', 'May 7, 2019, 6:20 pm', 1, 1, 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(14, 'F j, Y', 'May 17, 2019', 1, 1, 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(15, '\\i\\t \\i\\s \\t\\h\\e jS \\d\\a\\y', 'it is the 17th day', 1, 1, 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `company_id`, `title`, `status_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Management', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL),
(2, 1, 'HR', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL),
(3, 1, 'IT', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL),
(4, 2, 'Management', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL),
(5, 2, 'HR', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL),
(6, 2, 'IT', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `income_expense_category_id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `amount` double(16,2) DEFAULT NULL,
  `request_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `ref` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method_id` bigint UNSIGNED DEFAULT NULL,
  `transaction_id` bigint UNSIGNED DEFAULT NULL,
  `pay` bigint UNSIGNED NOT NULL DEFAULT '9',
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '2',
  `approver_id` bigint UNSIGNED DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `updated_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `attachment` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deposits`
--

INSERT INTO `deposits` (`id`, `user_id`, `income_expense_category_id`, `company_id`, `date`, `amount`, `request_amount`, `ref`, `payment_method_id`, `transaction_id`, `pay`, `status_id`, `approver_id`, `remarks`, `created_by`, `updated_by`, `attachment`, `created_at`, `updated_at`) VALUES
(1, 2, 45, 2, '2022-03-14', 7142.00, 7142.00, NULL, NULL, 1, 7142, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(2, 2, 45, 2, '2022-01-28', 1380.00, 1380.00, NULL, NULL, 2, 1380, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(3, 2, 45, 2, '2021-09-17', 4028.00, 4028.00, NULL, NULL, 3, 4028, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(4, 2, 45, 2, '2021-09-27', 3988.00, 3988.00, NULL, NULL, 4, 3988, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(5, 2, 45, 2, '2022-10-04', 7872.00, 7872.00, NULL, NULL, 5, 7872, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(6, 2, 46, 2, '2021-03-24', 6094.00, 6094.00, NULL, NULL, 6, 6094, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(7, 2, 46, 2, '2021-10-25', 9947.00, 9947.00, NULL, NULL, 7, 9947, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(8, 2, 46, 2, '2022-07-25', 4555.00, 4555.00, NULL, NULL, 8, 4555, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(9, 2, 46, 2, '2021-06-25', 6004.00, 6004.00, NULL, NULL, 9, 6004, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(10, 2, 46, 2, '2021-03-03', 2407.00, 2407.00, NULL, NULL, 10, 2407, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(11, 2, 47, 2, '2022-08-07', 3415.00, 3415.00, NULL, NULL, 11, 3415, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(12, 2, 47, 2, '2022-10-10', 1990.00, 1990.00, NULL, NULL, 12, 1990, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(13, 2, 47, 2, '2021-06-12', 3848.00, 3848.00, NULL, NULL, 13, 3848, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(14, 2, 47, 2, '2022-10-02', 6135.00, 6135.00, NULL, NULL, 14, 6135, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(15, 2, 47, 2, '2022-09-26', 5203.00, 5203.00, NULL, NULL, 15, 5203, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(16, 2, 48, 2, '2022-06-07', 2787.00, 2787.00, NULL, NULL, 16, 2787, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(17, 2, 48, 2, '2022-05-13', 6483.00, 6483.00, NULL, NULL, 17, 6483, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(18, 2, 48, 2, '2021-01-18', 2955.00, 2955.00, NULL, NULL, 18, 2955, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(19, 2, 48, 2, '2021-06-25', 8305.00, 8305.00, NULL, NULL, 19, 8305, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(20, 2, 48, 2, '2022-11-26', 8636.00, 8636.00, NULL, NULL, 20, 8636, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(21, 2, 45, 2, '2022-10-12', 3012.00, 3012.00, NULL, NULL, 21, 3012, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(22, 2, 45, 2, '2021-01-10', 2397.00, 2397.00, NULL, NULL, 22, 2397, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(23, 2, 45, 2, '2022-08-03', 1686.00, 1686.00, NULL, NULL, 23, 1686, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(24, 2, 45, 2, '2022-04-12', 6348.00, 6348.00, NULL, NULL, 24, 6348, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(25, 2, 45, 2, '2021-10-28', 4894.00, 4894.00, NULL, NULL, 25, 4894, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(26, 2, 46, 2, '2022-07-02', 9726.00, 9726.00, NULL, NULL, 26, 9726, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(27, 2, 46, 2, '2022-09-14', 7165.00, 7165.00, NULL, NULL, 27, 7165, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(28, 2, 46, 2, '2022-12-13', 4562.00, 4562.00, NULL, NULL, 28, 4562, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(29, 2, 46, 2, '2021-11-26', 3099.00, 3099.00, NULL, NULL, 29, 3099, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(30, 2, 46, 2, '2021-12-01', 9370.00, 9370.00, NULL, NULL, 30, 9370, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(31, 2, 47, 2, '2021-08-23', 5881.00, 5881.00, NULL, NULL, 31, 5881, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(32, 2, 47, 2, '2021-06-08', 9592.00, 9592.00, NULL, NULL, 32, 9592, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(33, 2, 47, 2, '2021-01-19', 4919.00, 4919.00, NULL, NULL, 33, 4919, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(34, 2, 47, 2, '2022-01-21', 2598.00, 2598.00, NULL, NULL, 34, 2598, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(35, 2, 47, 2, '2021-08-16', 6443.00, 6443.00, NULL, NULL, 35, 6443, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(36, 2, 48, 2, '2022-09-08', 2190.00, 2190.00, NULL, NULL, 36, 2190, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(37, 2, 48, 2, '2021-12-18', 8853.00, 8853.00, NULL, NULL, 37, 8853, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(38, 2, 48, 2, '2021-12-04', 5172.00, 5172.00, NULL, NULL, 38, 5172, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(39, 2, 48, 2, '2022-08-01', 1983.00, 1983.00, NULL, NULL, 39, 1983, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(40, 2, 48, 2, '2022-06-11', 1565.00, 1565.00, NULL, NULL, 40, 1565, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(41, 2, 45, 2, '2021-07-25', 1181.00, 1181.00, NULL, NULL, 41, 1181, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(42, 2, 45, 2, '2021-08-19', 6315.00, 6315.00, NULL, NULL, 42, 6315, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(43, 2, 45, 2, '2021-05-02', 4742.00, 4742.00, NULL, NULL, 43, 4742, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(44, 2, 45, 2, '2022-04-19', 4452.00, 4452.00, NULL, NULL, 44, 4452, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(45, 2, 45, 2, '2021-10-07', 7823.00, 7823.00, NULL, NULL, 45, 7823, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(46, 2, 46, 2, '2021-04-13', 3905.00, 3905.00, NULL, NULL, 46, 3905, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(47, 2, 46, 2, '2021-05-14', 4441.00, 4441.00, NULL, NULL, 47, 4441, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(48, 2, 46, 2, '2021-02-26', 3855.00, 3855.00, NULL, NULL, 48, 3855, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(49, 2, 46, 2, '2021-12-25', 3671.00, 3671.00, NULL, NULL, 49, 3671, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(50, 2, 46, 2, '2022-07-18', 8607.00, 8607.00, NULL, NULL, 50, 8607, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(51, 2, 47, 2, '2021-11-06', 1735.00, 1735.00, NULL, NULL, 51, 1735, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(52, 2, 47, 2, '2022-04-23', 8187.00, 8187.00, NULL, NULL, 52, 8187, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(53, 2, 47, 2, '2022-06-08', 4826.00, 4826.00, NULL, NULL, 53, 4826, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(54, 2, 47, 2, '2021-01-22', 1538.00, 1538.00, NULL, NULL, 54, 1538, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(55, 2, 47, 2, '2022-11-21', 2752.00, 2752.00, NULL, NULL, 55, 2752, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(56, 2, 48, 2, '2022-05-26', 7484.00, 7484.00, NULL, NULL, 56, 7484, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(57, 2, 48, 2, '2021-04-25', 3591.00, 3591.00, NULL, NULL, 57, 3591, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(58, 2, 48, 2, '2022-09-22', 2858.00, 2858.00, NULL, NULL, 58, 2858, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(59, 2, 48, 2, '2021-05-13', 8505.00, 8505.00, NULL, NULL, 59, 8505, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(60, 2, 48, 2, '2021-06-24', 6472.00, 6472.00, NULL, NULL, 60, 6472, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(61, 2, 45, 2, '2022-11-20', 7082.00, 7082.00, NULL, NULL, 61, 7082, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(62, 2, 45, 2, '2022-01-21', 1604.00, 1604.00, NULL, NULL, 62, 1604, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(63, 2, 45, 2, '2022-07-28', 1045.00, 1045.00, NULL, NULL, 63, 1045, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(64, 2, 45, 2, '2022-07-08', 8412.00, 8412.00, NULL, NULL, 64, 8412, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(65, 2, 45, 2, '2021-05-13', 3762.00, 3762.00, NULL, NULL, 65, 3762, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(66, 2, 46, 2, '2021-09-21', 7852.00, 7852.00, NULL, NULL, 66, 7852, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(67, 2, 46, 2, '2021-03-28', 9609.00, 9609.00, NULL, NULL, 67, 9609, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(68, 2, 46, 2, '2021-01-22', 7690.00, 7690.00, NULL, NULL, 68, 7690, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(69, 2, 46, 2, '2021-11-12', 5624.00, 5624.00, NULL, NULL, 69, 5624, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(70, 2, 46, 2, '2021-05-13', 6357.00, 6357.00, NULL, NULL, 70, 6357, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(71, 2, 47, 2, '2022-10-01', 6966.00, 6966.00, NULL, NULL, 71, 6966, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(72, 2, 47, 2, '2021-09-06', 8272.00, 8272.00, NULL, NULL, 72, 8272, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(73, 2, 47, 2, '2022-02-01', 1979.00, 1979.00, NULL, NULL, 73, 1979, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(74, 2, 47, 2, '2021-03-10', 6283.00, 6283.00, NULL, NULL, 74, 6283, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(75, 2, 47, 2, '2022-04-24', 2667.00, 2667.00, NULL, NULL, 75, 2667, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(76, 2, 48, 2, '2022-05-01', 6927.00, 6927.00, NULL, NULL, 76, 6927, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(77, 2, 48, 2, '2021-04-24', 1756.00, 1756.00, NULL, NULL, 77, 1756, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(78, 2, 48, 2, '2021-09-16', 2021.00, 2021.00, NULL, NULL, 78, 2021, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(79, 2, 48, 2, '2022-11-05', 7554.00, 7554.00, NULL, NULL, 79, 7554, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(80, 2, 48, 2, '2021-07-14', 9772.00, 9772.00, NULL, NULL, 80, 9772, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(81, 2, 45, 2, '2022-05-17', 6947.00, 6947.00, NULL, NULL, 81, 6947, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(82, 2, 45, 2, '2022-10-27', 4373.00, 4373.00, NULL, NULL, 82, 4373, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(83, 2, 45, 2, '2021-08-16', 1138.00, 1138.00, NULL, NULL, 83, 1138, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(84, 2, 45, 2, '2021-01-20', 5247.00, 5247.00, NULL, NULL, 84, 5247, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(85, 2, 45, 2, '2022-01-10', 8729.00, 8729.00, NULL, NULL, 85, 8729, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(86, 2, 46, 2, '2022-08-15', 5814.00, 5814.00, NULL, NULL, 86, 5814, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(87, 2, 46, 2, '2022-07-16', 3815.00, 3815.00, NULL, NULL, 87, 3815, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(88, 2, 46, 2, '2022-11-06', 1724.00, 1724.00, NULL, NULL, 88, 1724, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(89, 2, 46, 2, '2021-12-03', 7007.00, 7007.00, NULL, NULL, 89, 7007, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(90, 2, 46, 2, '2022-02-27', 3898.00, 3898.00, NULL, NULL, 90, 3898, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(91, 2, 47, 2, '2021-04-27', 7954.00, 7954.00, NULL, NULL, 91, 7954, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(92, 2, 47, 2, '2022-11-10', 7575.00, 7575.00, NULL, NULL, 92, 7575, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(93, 2, 47, 2, '2021-07-05', 4357.00, 4357.00, NULL, NULL, 93, 4357, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(94, 2, 47, 2, '2021-03-04', 5344.00, 5344.00, NULL, NULL, 94, 5344, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(95, 2, 47, 2, '2021-01-13', 1369.00, 1369.00, NULL, NULL, 95, 1369, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(96, 2, 48, 2, '2021-01-14', 6598.00, 6598.00, NULL, NULL, 96, 6598, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(97, 2, 48, 2, '2022-06-14', 9287.00, 9287.00, NULL, NULL, 97, 9287, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(98, 2, 48, 2, '2021-07-21', 3574.00, 3574.00, NULL, NULL, 98, 3574, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(99, 2, 48, 2, '2021-03-22', 1056.00, 1056.00, NULL, NULL, 99, 1056, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(100, 2, 48, 2, '2022-01-06', 2535.00, 2535.00, NULL, NULL, 100, 2535, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(101, 2, 45, 2, '2021-02-01', 4353.00, 4353.00, NULL, NULL, 101, 4353, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(102, 2, 45, 2, '2021-03-16', 5548.00, 5548.00, NULL, NULL, 102, 5548, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(103, 2, 45, 2, '2021-01-23', 3762.00, 3762.00, NULL, NULL, 103, 3762, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(104, 2, 45, 2, '2022-08-13', 9334.00, 9334.00, NULL, NULL, 104, 9334, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(105, 2, 45, 2, '2021-12-04', 4608.00, 4608.00, NULL, NULL, 105, 4608, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(106, 2, 46, 2, '2021-03-14', 8542.00, 8542.00, NULL, NULL, 106, 8542, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(107, 2, 46, 2, '2022-04-05', 5590.00, 5590.00, NULL, NULL, 107, 5590, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(108, 2, 46, 2, '2021-03-26', 5735.00, 5735.00, NULL, NULL, 108, 5735, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(109, 2, 46, 2, '2021-05-02', 8848.00, 8848.00, NULL, NULL, 109, 8848, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(110, 2, 46, 2, '2021-09-15', 6260.00, 6260.00, NULL, NULL, 110, 6260, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(111, 2, 47, 2, '2022-11-15', 8636.00, 8636.00, NULL, NULL, 111, 8636, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(112, 2, 47, 2, '2021-04-24', 1038.00, 1038.00, NULL, NULL, 112, 1038, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(113, 2, 47, 2, '2021-04-21', 2105.00, 2105.00, NULL, NULL, 113, 2105, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(114, 2, 47, 2, '2022-07-15', 4657.00, 4657.00, NULL, NULL, 114, 4657, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(115, 2, 47, 2, '2021-09-22', 4785.00, 4785.00, NULL, NULL, 115, 4785, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(116, 2, 48, 2, '2021-02-25', 6533.00, 6533.00, NULL, NULL, 116, 6533, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(117, 2, 48, 2, '2021-10-27', 4425.00, 4425.00, NULL, NULL, 117, 4425, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(118, 2, 48, 2, '2021-08-14', 2332.00, 2332.00, NULL, NULL, 118, 2332, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(119, 2, 48, 2, '2022-04-08', 7143.00, 7143.00, NULL, NULL, 119, 7143, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(120, 2, 48, 2, '2021-01-15', 7141.00, 7141.00, NULL, NULL, 120, 7141, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(121, 2, 45, 2, '2022-09-25', 2911.00, 2911.00, NULL, NULL, 121, 2911, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(122, 2, 45, 2, '2022-05-11', 5797.00, 5797.00, NULL, NULL, 122, 5797, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(123, 2, 45, 2, '2021-11-21', 8457.00, 8457.00, NULL, NULL, 123, 8457, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(124, 2, 45, 2, '2022-09-05', 4511.00, 4511.00, NULL, NULL, 124, 4511, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(125, 2, 45, 2, '2021-02-05', 3467.00, 3467.00, NULL, NULL, 125, 3467, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(126, 2, 46, 2, '2021-03-13', 8862.00, 8862.00, NULL, NULL, 126, 8862, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(127, 2, 46, 2, '2021-08-07', 9928.00, 9928.00, NULL, NULL, 127, 9928, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(128, 2, 46, 2, '2022-03-15', 5432.00, 5432.00, NULL, NULL, 128, 5432, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(129, 2, 46, 2, '2022-03-08', 7704.00, 7704.00, NULL, NULL, 129, 7704, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(130, 2, 46, 2, '2021-04-22', 4928.00, 4928.00, NULL, NULL, 130, 4928, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(131, 2, 47, 2, '2022-08-05', 5139.00, 5139.00, NULL, NULL, 131, 5139, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(132, 2, 47, 2, '2022-10-05', 8814.00, 8814.00, NULL, NULL, 132, 8814, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(133, 2, 47, 2, '2022-11-17', 7896.00, 7896.00, NULL, NULL, 133, 7896, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(134, 2, 47, 2, '2022-11-01', 3233.00, 3233.00, NULL, NULL, 134, 3233, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(135, 2, 47, 2, '2022-04-22', 5439.00, 5439.00, NULL, NULL, 135, 5439, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(136, 2, 48, 2, '2021-08-01', 6498.00, 6498.00, NULL, NULL, 136, 6498, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(137, 2, 48, 2, '2021-04-01', 1604.00, 1604.00, NULL, NULL, 137, 1604, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(138, 2, 48, 2, '2021-09-10', 8520.00, 8520.00, NULL, NULL, 138, 8520, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(139, 2, 48, 2, '2022-07-07', 8940.00, 8940.00, NULL, NULL, 139, 8940, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(140, 2, 48, 2, '2021-10-06', 6069.00, 6069.00, NULL, NULL, 140, 6069, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(141, 2, 45, 2, '2021-09-13', 5500.00, 5500.00, NULL, NULL, 141, 5500, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(142, 2, 45, 2, '2022-12-06', 6148.00, 6148.00, NULL, NULL, 142, 6148, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(143, 2, 45, 2, '2022-01-04', 6590.00, 6590.00, NULL, NULL, 143, 6590, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(144, 2, 45, 2, '2021-10-26', 8358.00, 8358.00, NULL, NULL, 144, 8358, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(145, 2, 45, 2, '2022-06-15', 4635.00, 4635.00, NULL, NULL, 145, 4635, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(146, 2, 46, 2, '2021-02-17', 3763.00, 3763.00, NULL, NULL, 146, 3763, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(147, 2, 46, 2, '2022-04-11', 8376.00, 8376.00, NULL, NULL, 147, 8376, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(148, 2, 46, 2, '2021-07-02', 6663.00, 6663.00, NULL, NULL, 148, 6663, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(149, 2, 46, 2, '2021-09-02', 7417.00, 7417.00, NULL, NULL, 149, 7417, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(150, 2, 46, 2, '2022-10-03', 8468.00, 8468.00, NULL, NULL, 150, 8468, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(151, 2, 47, 2, '2022-08-19', 9220.00, 9220.00, NULL, NULL, 151, 9220, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(152, 2, 47, 2, '2022-10-24', 2766.00, 2766.00, NULL, NULL, 152, 2766, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(153, 2, 47, 2, '2022-10-20', 1973.00, 1973.00, NULL, NULL, 153, 1973, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(154, 2, 47, 2, '2022-08-16', 8024.00, 8024.00, NULL, NULL, 154, 8024, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(155, 2, 47, 2, '2021-09-27', 4682.00, 4682.00, NULL, NULL, 155, 4682, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(156, 2, 48, 2, '2022-03-22', 1898.00, 1898.00, NULL, NULL, 156, 1898, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(157, 2, 48, 2, '2022-10-20', 8213.00, 8213.00, NULL, NULL, 157, 8213, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(158, 2, 48, 2, '2021-10-01', 4869.00, 4869.00, NULL, NULL, 158, 4869, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(159, 2, 48, 2, '2022-05-27', 2217.00, 2217.00, NULL, NULL, 159, 2217, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(160, 2, 48, 2, '2021-05-27', 8040.00, 8040.00, NULL, NULL, 160, 8040, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(161, 2, 45, 2, '2022-02-08', 2294.00, 2294.00, NULL, NULL, 161, 2294, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(162, 2, 45, 2, '2021-11-14', 4419.00, 4419.00, NULL, NULL, 162, 4419, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(163, 2, 45, 2, '2021-03-20', 9206.00, 9206.00, NULL, NULL, 163, 9206, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(164, 2, 45, 2, '2021-05-20', 3387.00, 3387.00, NULL, NULL, 164, 3387, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(165, 2, 45, 2, '2022-06-27', 7361.00, 7361.00, NULL, NULL, 165, 7361, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(166, 2, 46, 2, '2022-12-03', 7773.00, 7773.00, NULL, NULL, 166, 7773, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(167, 2, 46, 2, '2021-01-21', 9488.00, 9488.00, NULL, NULL, 167, 9488, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(168, 2, 46, 2, '2021-09-22', 3623.00, 3623.00, NULL, NULL, 168, 3623, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(169, 2, 46, 2, '2021-09-05', 7915.00, 7915.00, NULL, NULL, 169, 7915, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(170, 2, 46, 2, '2022-05-26', 1551.00, 1551.00, NULL, NULL, 170, 1551, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(171, 2, 47, 2, '2022-12-15', 4934.00, 4934.00, NULL, NULL, 171, 4934, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(172, 2, 47, 2, '2021-04-14', 9389.00, 9389.00, NULL, NULL, 172, 9389, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(173, 2, 47, 2, '2021-04-10', 3180.00, 3180.00, NULL, NULL, 173, 3180, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(174, 2, 47, 2, '2022-11-16', 9943.00, 9943.00, NULL, NULL, 174, 9943, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(175, 2, 47, 2, '2021-02-08', 5128.00, 5128.00, NULL, NULL, 175, 5128, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(176, 2, 48, 2, '2021-04-16', 6947.00, 6947.00, NULL, NULL, 176, 6947, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(177, 2, 48, 2, '2021-09-07', 6445.00, 6445.00, NULL, NULL, 177, 6445, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(178, 2, 48, 2, '2022-03-20', 5051.00, 5051.00, NULL, NULL, 178, 5051, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(179, 2, 48, 2, '2022-10-16', 7970.00, 7970.00, NULL, NULL, 179, 7970, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(180, 2, 48, 2, '2021-06-18', 1797.00, 1797.00, NULL, NULL, 180, 1797, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(181, 2, 45, 2, '2021-10-17', 5964.00, 5964.00, NULL, NULL, 181, 5964, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(182, 2, 45, 2, '2021-06-07', 7421.00, 7421.00, NULL, NULL, 182, 7421, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(183, 2, 45, 2, '2022-03-18', 7642.00, 7642.00, NULL, NULL, 183, 7642, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(184, 2, 45, 2, '2021-07-08', 5233.00, 5233.00, NULL, NULL, 184, 5233, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(185, 2, 45, 2, '2022-05-20', 8652.00, 8652.00, NULL, NULL, 185, 8652, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(186, 2, 46, 2, '2022-01-18', 7111.00, 7111.00, NULL, NULL, 186, 7111, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(187, 2, 46, 2, '2022-01-28', 8385.00, 8385.00, NULL, NULL, 187, 8385, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(188, 2, 46, 2, '2021-02-23', 3216.00, 3216.00, NULL, NULL, 188, 3216, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(189, 2, 46, 2, '2021-06-03', 7546.00, 7546.00, NULL, NULL, 189, 7546, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(190, 2, 46, 2, '2021-06-04', 1953.00, 1953.00, NULL, NULL, 190, 1953, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(191, 2, 47, 2, '2021-10-19', 8849.00, 8849.00, NULL, NULL, 191, 8849, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(192, 2, 47, 2, '2021-08-14', 7506.00, 7506.00, NULL, NULL, 192, 7506, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(193, 2, 47, 2, '2021-07-07', 5767.00, 5767.00, NULL, NULL, 193, 5767, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(194, 2, 47, 2, '2022-02-12', 2563.00, 2563.00, NULL, NULL, 194, 2563, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(195, 2, 47, 2, '2021-03-09', 5460.00, 5460.00, NULL, NULL, 195, 5460, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(196, 2, 48, 2, '2021-03-24', 9322.00, 9322.00, NULL, NULL, 196, 9322, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(197, 2, 48, 2, '2022-11-26', 7839.00, 7839.00, NULL, NULL, 197, 7839, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(198, 2, 48, 2, '2022-06-27', 5709.00, 5709.00, NULL, NULL, 198, 5709, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(199, 2, 48, 2, '2022-02-14', 4064.00, 4064.00, NULL, NULL, 199, 4064, 8, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(200, 2, 48, 2, '2021-01-04', 1410.00, 1410.00, NULL, NULL, 200, 1410, 9, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `company_id`, `title`, `status_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Chairman', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL),
(2, 1, 'MD', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL),
(3, 1, 'CEO', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL),
(4, 1, 'CIO', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL),
(5, 1, 'HR Manager', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL),
(6, 1, 'Staff', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL),
(7, 2, 'Chairman', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL),
(8, 2, 'MD', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL),
(9, 2, 'CEO', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL),
(10, 2, 'CIO', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL),
(11, 2, 'HR Manager', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL),
(12, 2, 'Staff', 1, '2023-04-18 01:07:40', '2023-04-18 01:07:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `discussions`
--

CREATE TABLE `discussions` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `show_to_customer` bigint UNSIGNED NOT NULL DEFAULT '22',
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `last_activity` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discussion_comments`
--

CREATE TABLE `discussion_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `comment_id` bigint UNSIGNED DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_to_customer` tinyint NOT NULL DEFAULT '1' COMMENT '0=no,1=yes',
  `discussion_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `attachment` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discussion_likes`
--

CREATE TABLE `discussion_likes` (
  `id` bigint UNSIGNED NOT NULL,
  `discussion_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `like` int DEFAULT '0',
  `dislike` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `duty_schedules`
--

CREATE TABLE `duty_schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `shift_id` bigint UNSIGNED NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `consider_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `hour` int NOT NULL DEFAULT '0',
  `status_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `end_on_same_date` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `duty_schedules`
--

INSERT INTO `duty_schedules` (`id`, `company_id`, `shift_id`, `start_time`, `end_time`, `consider_time`, `hour`, `status_id`, `created_at`, `updated_at`, `end_on_same_date`) VALUES
(1, 2, 4, '09:00:00', '17:00:00', '15', 8, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41', 1),
(2, 1, 1, '09:00:00', '18:00:00', '30', 8, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43', 1),
(3, 1, 2, '09:00:00', '18:00:00', '30', 8, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43', 1),
(4, 2, 3, '09:00:00', '18:00:00', '30', 8, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43', 1),
(5, 2, 4, '09:00:00', '18:00:00', '30', 8, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employee_breaks`
--

CREATE TABLE `employee_breaks` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `break_time` time DEFAULT NULL,
  `back_time` time DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_tasks`
--

CREATE TABLE `employee_tasks` (
  `id` bigint UNSIGNED NOT NULL,
  `assigned_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `due_date` date DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `attachment_file_id` bigint UNSIGNED DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_tasks`
--

INSERT INTO `employee_tasks` (`id`, `assigned_id`, `created_by`, `due_date`, `title`, `description`, `attachment_file_id`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '2023-04-03', 'Et voluptatem quis repudiandae accusamus aut.', 'Et sapiente dolorum voluptates necessitatibus et autem voluptatem. Ab fugit nam repellat architecto mollitia qui et voluptas. Magni facilis minima aut.', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(2, 2, 3, '2023-04-17', 'Rerum aliquid facere consequuntur voluptatum temporibus quia aliquid.', 'Ut facilis consequatur qui aut. Ad doloribus sunt minus quis est ut dignissimos qui. Et provident quibusdam mollitia et voluptas. Quas atque iste omnis dolorum omnis quia cupiditate doloribus.', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(3, 3, 4, '2023-04-28', 'Eveniet ea dolores soluta sequi autem iste quis consequatur.', 'Fugiat quisquam sed repellat nisi doloremque aspernatur deleniti velit. Est cumque qui omnis recusandae quia tempora soluta.', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(4, 4, 3, '2023-04-24', 'Voluptas aspernatur eos explicabo illum.', 'Alias unde eos omnis voluptatem culpa reprehenderit aliquam. Aut possimus nihil voluptas et odit autem eligendi. Itaque nihil modi totam eos. Alias asperiores provident aut sed dolores sint optio.', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(5, 5, 2, '2023-04-02', 'Deleniti veniam consectetur quia quisquam omnis minus.', 'Consequuntur voluptatem velit est earum et ut pariatur. Temporibus magnam a reiciendis quae similique quaerat. Suscipit asperiores dolorem veniam odio.', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `income_expense_category_id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `amount` double(16,2) DEFAULT NULL,
  `request_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `pay` bigint UNSIGNED NOT NULL DEFAULT '9',
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '2',
  `ref` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` bigint UNSIGNED DEFAULT NULL,
  `payment_method_id` bigint UNSIGNED DEFAULT NULL,
  `approver_id` bigint UNSIGNED DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `updated_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `attachment` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `income_expense_category_id`, `company_id`, `date`, `amount`, `request_amount`, `pay`, `status_id`, `ref`, `transaction_id`, `payment_method_id`, `approver_id`, `remarks`, `created_by`, `updated_by`, `attachment`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 2, '2021-01-03', 7820.00, 7820.00, 7820, 9, NULL, 201, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(2, 2, 1, 2, '2022-03-26', 9030.00, 9030.00, 9030, 9, NULL, 202, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(3, 2, 1, 2, '2022-05-20', 9651.00, 9651.00, 9651, 9, NULL, 203, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(4, 2, 1, 2, '2021-08-09', 2563.00, 2563.00, 2563, 8, NULL, 204, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(5, 2, 1, 2, '2021-11-19', 5511.00, 5511.00, 5511, 8, NULL, 205, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(6, 2, 2, 2, '2021-01-25', 9455.00, 9455.00, 9455, 8, NULL, 206, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(7, 2, 2, 2, '2021-02-26', 7062.00, 7062.00, 7062, 9, NULL, 207, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(8, 2, 2, 2, '2022-07-21', 4595.00, 4595.00, 4595, 9, NULL, 208, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(9, 2, 2, 2, '2021-01-05', 6179.00, 6179.00, 6179, 9, NULL, 209, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(10, 2, 2, 2, '2022-02-18', 1058.00, 1058.00, 1058, 8, NULL, 210, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(11, 2, 3, 2, '2021-09-15', 9557.00, 9557.00, 9557, 9, NULL, 211, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(12, 2, 3, 2, '2021-06-19', 6220.00, 6220.00, 6220, 8, NULL, 212, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(13, 2, 3, 2, '2022-01-03', 4107.00, 4107.00, 4107, 9, NULL, 213, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(14, 2, 3, 2, '2022-10-24', 8961.00, 8961.00, 8961, 8, NULL, 214, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(15, 2, 3, 2, '2021-02-15', 3642.00, 3642.00, 3642, 9, NULL, 215, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(16, 2, 4, 2, '2022-09-17', 4727.00, 4727.00, 4727, 8, NULL, 216, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(17, 2, 4, 2, '2022-05-12', 8080.00, 8080.00, 8080, 9, NULL, 217, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(18, 2, 4, 2, '2021-09-23', 6894.00, 6894.00, 6894, 9, NULL, 218, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(19, 2, 4, 2, '2021-08-12', 8503.00, 8503.00, 8503, 9, NULL, 219, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(20, 2, 4, 2, '2021-05-02', 6007.00, 6007.00, 6007, 8, NULL, 220, NULL, NULL, NULL, 1, 1, NULL, '2023-04-18 01:08:06', '2023-04-18 01:08:06');

-- --------------------------------------------------------

--
-- Table structure for table `expense_claims`
--

CREATE TABLE `expense_claims` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'invoice number',
  `claim_date` date DEFAULT NULL COMMENT 'date of claim',
  `remarks` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'remarks of payment',
  `payable_amount` double(10,2) DEFAULT NULL COMMENT 'amount of payment',
  `due_amount` double(10,2) DEFAULT NULL COMMENT 'due amount of payment',
  `attachment_file_id` bigint UNSIGNED DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_claim_details`
--

CREATE TABLE `expense_claim_details` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `hrm_expense_id` bigint UNSIGNED NOT NULL,
  `expense_claim_id` bigint UNSIGNED NOT NULL,
  `amount` double(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` bigint UNSIGNED NOT NULL,
  `type` enum('company_growth','advance_features','awesome_features') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_file_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `long_description` longtext COLLATE utf8mb4_unicode_ci,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `type`, `attachment_file_id`, `title`, `short_description`, `long_description`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 'company_growth', NULL, 'Employee-Centric', 'Make the lives of your employees less problematic. Try to create an atmosphere where your employees feel like giving their best every day. You can only expect more work efficiency if you are able to keep your employees happier.', 'Make the lives of your employees less problematic. Try to create an atmosphere where your employees feel like giving their best every day. You can only expect more work efficiency if you are able to keep your employees happier.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(2, 'company_growth', NULL, 'Development-Centric', 'To meet your business demands, it is very crucial to meet current and future growth requirements. For fulfilling them, employees’ development is a must. Through your agile strategies and planning it out beforehand can be helpful to reach your goals.', 'To meet your business demands, it is very crucial to meet current and future growth requirements. For fulfilling them, employees’ development is a must. Through your agile strategies and planning it out beforehand can be helpful to reach your goals.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(3, 'company_growth', NULL, 'Individual Progress', 'Having the ability to develop individual relationships with the employees can be beneficial for any company. You can easily get to know their general behavior, social aspects of life, emotional well- being and act upon it to improve employee experience.', 'Having the ability to develop individual relationships with the employees can be beneficial for any company. You can easily get to know their general behavior, social aspects of life, emotional well- being and act upon it to improve employee experience.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(4, 'company_growth', NULL, 'Decision Making', 'It is very essential to know how to use data rather than just collecting them. Crunching data after getting helpful information can make an impact on decision-making. Easily dive into future possibilities, also analyze potential outcomes beforehand.', 'It is very essential to know how to use data rather than just collecting them. Crunching data after getting helpful information can make an impact on decision-making. Easily dive into future possibilities, also analyze potential outcomes beforehand.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(5, 'company_growth', NULL, 'Continuity', 'It may occur to anybody, even the HR management people can get sick. Keeping constant workflow and overcome such disruptions, it is vital to get notified earlier or get to know employees’ health condition, effectiveness, feelings towards their job.', 'It may occur to anybody, even the HR management people can get sick. Keeping constant workflow and overcome such disruptions, it is vital to get notified earlier or get to know employees’ health condition, effectiveness, feelings towards their job.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(6, 'company_growth', NULL, 'Universal', 'Universality is the most vital feature for HRM software. It really doesn’t matter if you are running only a two-person job or a company of 500+ employees, this software is applicable for any. It is truly reliable for any type of organization.', 'Universality is the most vital feature for HRM software. It really doesn’t matter if you are running only a two-person job or a company of 500+ employees, this software is applicable for any. It is truly reliable for any type of organization.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(7, 'advance_features', NULL, 'Leave', 'Employees can express their Leave Type, Find Assigned Leaves and get Leave Request approval. They can also submit necessary documents to ensure the validity of their leave.', 'Employees can express their Leave Type, Find Assigned Leaves and get Leave Request approval. They can also submit necessary documents to ensure the validity of their leave.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(8, 'advance_features', NULL, 'Attendance', 'Records employees’ In /Out time, Working hours, Overtime automatically in its system. Whether they are working from home or office, their activities can be easily traceable to authority.', 'Records employees’ In /Out time, Working hours, Overtime automatically in its system. Whether they are working from home or office, their activities can be easily traceable to authority.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(9, 'advance_features', NULL, 'Expense', 'For any additional expenses, managing legal claims or keeping track on payment history can be easily done in few clicks. You can also Keep an updated routine for any additional disbursement.', 'For any additional expenses, managing legal claims or keeping track on payment history can be easily done in few clicks. You can also Keep an updated routine for any additional disbursement.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(10, 'advance_features', NULL, 'Visit', 'For outdoor visits or participating in crucial meetings, employees can input their check in/out timings too. Also such visits can be monitored by the officials anytime of the day.', 'For outdoor visits or participating in crucial meetings, employees can input their check in/out timings too. Also such visits can be monitored by the officials anytime of the day.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(11, 'advance_features', NULL, 'Notice', 'Let everyone aware of any upcoming events, disciplinary, holidays at once. You can also update any notice for individuals, departmental wise or even for all without any effort.', 'Let everyone aware of any upcoming events, disciplinary, holidays at once. You can also update any notice for individuals, departmental wise or even for all without any effort.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(12, 'advance_features', NULL, 'Report', 'Collects data of individuals -Working days/On time/Late Comings/Early Leave/Overtime and creates monthly/half-yearly, annual report based on their regular performance.', 'Collects data of individuals -Working days/On time/Late Comings/Early Leave/Overtime and creates monthly/half-yearly, annual report based on their regular performance.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(13, 'awesome_features', NULL, 'Employee Data', 'Records everything that indicates all necessary information for any of the employees.', 'Records everything that indicates all necessary information for any of the employees.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(14, 'awesome_features', NULL, 'Custom Permission', 'Provide accessibility to the designated personnel for further analysis of any individual.', 'Provide accessibility to the designated personnel for further analysis of any individual.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(15, 'awesome_features', NULL, 'Employee Onboarding', 'Onboard employees online and make a remarkable first impression during the process.', 'Onboard employees online and make a remarkable first impression during the process.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(16, 'awesome_features', NULL, 'Announcement', 'Celebrate special moments with everyone in the company with a few words.', 'Celebrate special moments with everyone in the company with a few words.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(17, 'awesome_features', NULL, 'Custom Profile', 'You can also get to customize your own profile as you may seem right for the company.', 'You can also get to customize your own profile as you may seem right for the company.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(18, 'awesome_features', NULL, 'Project & Tasks', 'Allows transparent access to overview employee’s assigned tasks for daily reports.', 'Allows transparent access to overview employee’s assigned tasks for daily reports.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `front_teams`
--

CREATE TABLE `front_teams` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `attachment` bigint UNSIGNED DEFAULT NULL,
  `fb_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tw_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ln_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sky_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `front_teams`
--

INSERT INTO `front_teams` (`id`, `name`, `designation`, `description`, `attachment`, `fb_url`, `tw_url`, `ln_url`, `sky_url`, `user_id`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 'Ahsan ahmed', 'Software engineer', 'Dedicated,fast forward Software engineer with 4+ years of professional experience.', 29, NULL, NULL, NULL, NULL, 2, 1, '2023-04-18 01:07:53', NULL),
(2, 'Jobbar ali', 'Project manager', 'Dedicated,fast forward Project manager with 5+ years of professional experience.', 30, NULL, NULL, NULL, NULL, 2, 1, '2023-04-18 01:07:53', NULL),
(3, 'Johan evan', 'Designer', 'Dedicated,fast forward  Designer with 3+ years of professional experience.', 31, NULL, NULL, NULL, NULL, 2, 1, '2023-04-18 01:07:53', NULL),
(4, 'Akram khan', 'Mechanical Engineer', 'Dedicated,fast forward Mechanical Engineer with 3+ years of professional experience.', 32, NULL, NULL, NULL, NULL, 2, 1, '2023-04-18 01:07:53', NULL),
(5, 'Ahsan ahmed', 'Software engineer', 'Dedicated,fast forward Software engineer with 7+ years of professional experience.', 33, NULL, NULL, NULL, NULL, 2, 1, '2023-04-18 01:07:53', NULL),
(6, 'Jobbar ali', 'Project manager', 'Dedicated,fast forward Project manager with 3+ years of professional experience.', 34, NULL, NULL, NULL, NULL, 2, 1, '2023-04-18 01:07:53', NULL),
(7, 'Johan evan', 'Designer', 'Dedicated,fast forward Project Designer with 3+ years of professional experience.', 35, NULL, NULL, NULL, NULL, 2, 1, '2023-04-18 01:07:53', NULL),
(8, 'Akram khan', 'Mechanical Engineer', 'Dedicated,fast forward Mechanical Engineer with 5+ years of professional experience.', 36, NULL, NULL, NULL, NULL, 2, 1, '2023-04-18 01:07:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `goal_type_id` bigint UNSIGNED DEFAULT NULL,
  `progress` int DEFAULT '0',
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '24',
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `rating` int DEFAULT '0',
  `created_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`id`, `company_id`, `subject`, `target`, `goal_type_id`, `progress`, `status_id`, `description`, `start_date`, `end_date`, `rating`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 2, 'Employee Experience', 'Employee Experience', 1, 1, 24, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2023-04-18', '2024-04-18', 0, 2, '2023-04-18 01:07:46', '2023-04-18 01:07:46');

-- --------------------------------------------------------

--
-- Table structure for table `goal_types`
--

CREATE TABLE `goal_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT '1',
  `updated_by` bigint UNSIGNED DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goal_types`
--

INSERT INTO `goal_types` (`id`, `name`, `company_id`, `status_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Employee Experience', 2, 1, 1, 1, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(2, 'Objective', 2, 1, 1, 1, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(3, 'Target', 2, 1, 1, 1, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(4, 'KPI', 2, 1, 1, 1, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(5, 'Measure', 2, 1, 1, 1, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(6, 'Indicator', 2, 1, 1, 1, '2023-04-18 01:07:46', '2023-04-18 01:07:46');

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `attachment_id` bigint UNSIGNED DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `company_id`, `title`, `type`, `description`, `start_date`, `end_date`, `attachment_id`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'New Year', 'Federal Holiday', '', '2022-01-01', '2022-01-01', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(2, 1, 'Martin Luther King Jr Day', 'Federal Holiday', '3rd Monday in January', '2022-01-17', '2022-01-17', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(3, 1, 'Washington\'s Birthday', 'Federal Holiday', '3rd Monday in February', '2022-02-21', '2022-02-21', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(4, 1, 'Memorial Day', 'Federal Holiday', 'Last Monday in May', '2022-05-26', '2022-05-26', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(5, 1, 'Independence Day', 'Federal Holiday', '', '2022-07-04', '2022-07-04', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(6, 1, 'Labor Day', 'Federal Holiday', '1st Monday in September', '2022-09-01', '2022-09-01', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(7, 1, 'Columbus Day', 'Federal Holiday', '2nd Monday in October', '2022-10-13', '2022-10-13', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(8, 1, 'Veterans Day', 'Federal Holiday', '11th November', '2022-11-11', '2022-11-11', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(9, 1, 'Thanksgiving Day', 'Federal Holiday', '4th Thursday in November', '2022-11-24', '2022-11-24', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(10, 1, 'Christmas Day', 'Federal Holiday', '', '2022-12-25', '2022-12-25', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(11, 2, 'New Year', 'Federal Holiday', '', '2022-01-01', '2022-01-01', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(12, 2, 'Martin Luther King Jr Day', 'Federal Holiday', '3rd Monday in January', '2022-01-17', '2022-01-17', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(13, 2, 'Washington\'s Birthday', 'Federal Holiday', '3rd Monday in February', '2022-02-21', '2022-02-21', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(14, 2, 'Memorial Day', 'Federal Holiday', 'Last Monday in May', '2022-05-26', '2022-05-26', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(15, 2, 'Independence Day', 'Federal Holiday', '', '2022-07-04', '2022-07-04', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(16, 2, 'Labor Day', 'Federal Holiday', '1st Monday in September', '2022-09-01', '2022-09-01', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(17, 2, 'Columbus Day', 'Federal Holiday', '2nd Monday in October', '2022-10-13', '2022-10-13', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(18, 2, 'Veterans Day', 'Federal Holiday', '11th November', '2022-11-11', '2022-11-11', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(19, 2, 'Thanksgiving Day', 'Federal Holiday', '4th Thursday in November', '2022-11-24', '2022-11-24', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(20, 2, 'Christmas Day', 'Federal Holiday', '', '2022-12-25', '2022-12-25', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(21, 1, '21 February - Language Day', NULL, '21 February - Language Day', '2022-02-21', '2022-02-21', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(22, 1, '1st May - Labour Day', NULL, '1st May - Labour Day', '2022-05-01', '2022-05-01', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(23, 1, '9th May - Independence Day', NULL, '9th May - Independence Day', '2022-05-09', '2022-05-09', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(24, 1, '15th August - Nation Day', NULL, '15th August - Nation Day', '2022-08-15', '2022-08-15', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(25, 1, '25th December - Christmas Day', NULL, '25th December - Christmas Day', '2022-12-25', '2022-12-25', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(26, 1, '26th December - Boxing Day', NULL, '26th December - Boxing Day', '2022-12-26', '2022-12-26', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(27, 2, '21 February - Language Day', NULL, '21 February - Language Day', '2022-02-21', '2022-02-21', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(28, 2, '1st May - Labour Day', NULL, '1st May - Labour Day', '2022-05-01', '2022-05-01', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(29, 2, '9th May - Independence Day', NULL, '9th May - Independence Day', '2022-05-09', '2022-05-09', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(30, 2, '15th August - Nation Day', NULL, '15th August - Nation Day', '2022-08-15', '2022-08-15', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(31, 2, '25th December - Christmas Day', NULL, '25th December - Christmas Day', '2022-12-25', '2022-12-25', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(32, 2, '26th December - Boxing Day', NULL, '26th December - Boxing Day', '2022-12-26', '2022-12-26', NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `home_pages`
--

CREATE TABLE `home_pages` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contents` json DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED NOT NULL,
  `updated_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_pages`
--

INSERT INTO `home_pages` (`id`, `title`, `contents`, `status_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Home Section 1', '{\"title\": \"Get Best Innovation Software\", \"attachment\": \"http://crm.test/public/assets/images/user.png\", \"description\": \"Integrated market before enterprise wide e-commerce. Competently actualize bleeding-edge testing.\"}', 1, 2, 2, '2023-04-18 01:07:53', NULL),
(2, 'About Section', '{\"image\": null, \"title\": \"About Us\", \"slogan\": \"One Goal, One Passion\", \"description\": \"We believes in painting the perfect picture of your idea while maintaining industry standards and following upcoming trends. It is a professional software development company managed by tech-heads, engineers who are highly qualified in creating and solving issues of all kinds.\\r\\n\\r\\n            This software development company was established in Dhaka, Bangladesh on September 1, 2017 and since then, it has developed a relentless focus on technical achievement both nationally and internationally.\\r\\n            \\r\\n            So, you can certainly bet the farm as our expertise uses every muscle to provide dogmatic solutions, that results in best user experience with us.\"}', 1, 2, 2, '2023-04-18 01:07:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hrm_expenses`
--

CREATE TABLE `hrm_expenses` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `income_expense_category_id` bigint UNSIGNED NOT NULL,
  `date` date DEFAULT NULL COMMENT 'date of expense',
  `remarks` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'remarks of expense',
  `amount` double(10,2) DEFAULT NULL COMMENT 'amount of expense',
  `attachment_file_id` bigint UNSIGNED DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `is_claimed_status_id` bigint UNSIGNED NOT NULL,
  `claimed_approved_status_id` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_expenses`
--

INSERT INTO `hrm_expenses` (`id`, `company_id`, `user_id`, `income_expense_category_id`, `date`, `remarks`, `amount`, `attachment_file_id`, `status_id`, `is_claimed_status_id`, `claimed_approved_status_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2023-04-18', 'remarks', 145.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(2, 1, 1, 3, '2023-04-18', 'remarks', 149.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(3, 1, 1, 5, '2023-04-18', 'remarks', 122.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(4, 1, 1, 7, '2023-04-18', 'remarks', 143.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(5, 1, 1, 9, '2023-04-18', 'remarks', 182.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(6, 1, 1, 11, '2023-04-18', 'remarks', 146.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(7, 1, 1, 13, '2023-04-18', 'remarks', 111.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(8, 1, 1, 15, '2023-04-18', 'remarks', 185.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(9, 1, 1, 17, '2023-04-18', 'remarks', 139.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(10, 1, 1, 19, '2023-04-18', 'remarks', 162.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(11, 1, 1, 21, '2023-04-18', 'remarks', 197.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(12, 1, 1, 23, '2023-04-18', 'remarks', 140.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(13, 1, 1, 25, '2023-04-18', 'remarks', 151.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(14, 1, 1, 27, '2023-04-18', 'remarks', 141.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(15, 1, 1, 29, '2023-04-18', 'remarks', 113.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(16, 1, 1, 31, '2023-04-18', 'remarks', 182.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(17, 1, 1, 33, '2023-04-18', 'remarks', 184.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(18, 1, 1, 35, '2023-04-18', 'remarks', 179.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(19, 1, 1, 37, '2023-04-18', 'remarks', 119.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(20, 1, 1, 39, '2023-04-18', 'remarks', 172.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(21, 1, 1, 41, '2023-04-18', 'remarks', 113.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(22, 1, 1, 43, '2023-04-18', 'remarks', 184.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(23, 1, 1, 45, '2023-04-18', 'remarks', 107.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(24, 1, 1, 47, '2023-04-18', 'remarks', 148.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(25, 1, 1, 49, '2023-04-18', 'remarks', 137.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(26, 1, 1, 51, '2023-04-18', 'remarks', 144.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(27, 2, 2, 2, '2023-04-18', 'remarks', 172.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(28, 2, 2, 4, '2023-04-18', 'remarks', 157.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(29, 2, 2, 6, '2023-04-18', 'remarks', 171.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(30, 2, 2, 8, '2023-04-18', 'remarks', 141.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(31, 2, 2, 10, '2023-04-18', 'remarks', 147.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(32, 2, 2, 12, '2023-04-18', 'remarks', 200.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(33, 2, 2, 14, '2023-04-18', 'remarks', 194.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(34, 2, 2, 16, '2023-04-18', 'remarks', 151.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(35, 2, 2, 18, '2023-04-18', 'remarks', 154.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(36, 2, 2, 20, '2023-04-18', 'remarks', 138.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(37, 2, 2, 22, '2023-04-18', 'remarks', 110.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(38, 2, 2, 24, '2023-04-18', 'remarks', 111.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(39, 2, 2, 26, '2023-04-18', 'remarks', 182.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(40, 2, 2, 28, '2023-04-18', 'remarks', 168.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(41, 2, 2, 30, '2023-04-18', 'remarks', 153.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(42, 2, 2, 32, '2023-04-18', 'remarks', 195.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(43, 2, 2, 34, '2023-04-18', 'remarks', 155.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(44, 2, 2, 36, '2023-04-18', 'remarks', 114.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(45, 2, 2, 38, '2023-04-18', 'remarks', 140.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(46, 2, 2, 40, '2023-04-18', 'remarks', 108.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(47, 2, 2, 42, '2023-04-18', 'remarks', 197.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(48, 2, 2, 44, '2023-04-18', 'remarks', 154.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(49, 2, 2, 46, '2023-04-18', 'remarks', 188.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(50, 2, 2, 48, '2023-04-18', 'remarks', 155.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(51, 2, 2, 50, '2023-04-18', 'remarks', 162.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(52, 2, 2, 52, '2023-04-18', 'remarks', 155.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(53, 2, 3, 2, '2023-04-18', 'remarks', 138.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(54, 2, 3, 4, '2023-04-18', 'remarks', 197.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(55, 2, 3, 6, '2023-04-18', 'remarks', 124.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(56, 2, 3, 8, '2023-04-18', 'remarks', 126.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(57, 2, 3, 10, '2023-04-18', 'remarks', 145.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(58, 2, 3, 12, '2023-04-18', 'remarks', 124.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(59, 2, 3, 14, '2023-04-18', 'remarks', 110.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(60, 2, 3, 16, '2023-04-18', 'remarks', 141.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(61, 2, 3, 18, '2023-04-18', 'remarks', 103.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(62, 2, 3, 20, '2023-04-18', 'remarks', 196.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(63, 2, 3, 22, '2023-04-18', 'remarks', 181.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(64, 2, 3, 24, '2023-04-18', 'remarks', 132.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(65, 2, 3, 26, '2023-04-18', 'remarks', 166.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(66, 2, 3, 28, '2023-04-18', 'remarks', 197.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(67, 2, 3, 30, '2023-04-18', 'remarks', 102.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(68, 2, 3, 32, '2023-04-18', 'remarks', 134.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(69, 2, 3, 34, '2023-04-18', 'remarks', 114.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(70, 2, 3, 36, '2023-04-18', 'remarks', 159.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(71, 2, 3, 38, '2023-04-18', 'remarks', 124.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(72, 2, 3, 40, '2023-04-18', 'remarks', 116.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(73, 2, 3, 42, '2023-04-18', 'remarks', 152.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(74, 2, 3, 44, '2023-04-18', 'remarks', 174.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(75, 2, 3, 46, '2023-04-18', 'remarks', 179.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(76, 2, 3, 48, '2023-04-18', 'remarks', 141.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(77, 2, 3, 50, '2023-04-18', 'remarks', 182.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(78, 2, 3, 52, '2023-04-18', 'remarks', 105.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(79, 2, 4, 2, '2023-04-18', 'remarks', 144.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(80, 2, 4, 4, '2023-04-18', 'remarks', 196.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(81, 2, 4, 6, '2023-04-18', 'remarks', 178.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(82, 2, 4, 8, '2023-04-18', 'remarks', 132.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(83, 2, 4, 10, '2023-04-18', 'remarks', 178.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(84, 2, 4, 12, '2023-04-18', 'remarks', 179.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(85, 2, 4, 14, '2023-04-18', 'remarks', 166.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(86, 2, 4, 16, '2023-04-18', 'remarks', 192.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(87, 2, 4, 18, '2023-04-18', 'remarks', 178.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(88, 2, 4, 20, '2023-04-18', 'remarks', 132.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(89, 2, 4, 22, '2023-04-18', 'remarks', 158.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(90, 2, 4, 24, '2023-04-18', 'remarks', 125.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(91, 2, 4, 26, '2023-04-18', 'remarks', 176.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(92, 2, 4, 28, '2023-04-18', 'remarks', 155.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(93, 2, 4, 30, '2023-04-18', 'remarks', 137.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(94, 2, 4, 32, '2023-04-18', 'remarks', 122.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(95, 2, 4, 34, '2023-04-18', 'remarks', 199.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(96, 2, 4, 36, '2023-04-18', 'remarks', 172.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(97, 2, 4, 38, '2023-04-18', 'remarks', 151.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(98, 2, 4, 40, '2023-04-18', 'remarks', 113.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(99, 2, 4, 42, '2023-04-18', 'remarks', 156.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(100, 2, 4, 44, '2023-04-18', 'remarks', 138.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(101, 2, 4, 46, '2023-04-18', 'remarks', 172.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(102, 2, 4, 48, '2023-04-18', 'remarks', 143.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(103, 2, 4, 50, '2023-04-18', 'remarks', 101.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(104, 2, 4, 52, '2023-04-18', 'remarks', 182.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(105, 2, 5, 2, '2023-04-18', 'remarks', 159.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(106, 2, 5, 4, '2023-04-18', 'remarks', 172.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(107, 2, 5, 6, '2023-04-18', 'remarks', 114.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(108, 2, 5, 8, '2023-04-18', 'remarks', 158.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(109, 2, 5, 10, '2023-04-18', 'remarks', 101.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(110, 2, 5, 12, '2023-04-18', 'remarks', 138.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(111, 2, 5, 14, '2023-04-18', 'remarks', 158.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(112, 2, 5, 16, '2023-04-18', 'remarks', 167.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(113, 2, 5, 18, '2023-04-18', 'remarks', 187.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(114, 2, 5, 20, '2023-04-18', 'remarks', 151.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(115, 2, 5, 22, '2023-04-18', 'remarks', 136.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(116, 2, 5, 24, '2023-04-18', 'remarks', 194.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(117, 2, 5, 26, '2023-04-18', 'remarks', 179.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(118, 2, 5, 28, '2023-04-18', 'remarks', 143.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(119, 2, 5, 30, '2023-04-18', 'remarks', 181.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(120, 2, 5, 32, '2023-04-18', 'remarks', 134.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(121, 2, 5, 34, '2023-04-18', 'remarks', 109.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(122, 2, 5, 36, '2023-04-18', 'remarks', 136.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(123, 2, 5, 38, '2023-04-18', 'remarks', 180.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(124, 2, 5, 40, '2023-04-18', 'remarks', 131.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(125, 2, 5, 42, '2023-04-18', 'remarks', 127.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(126, 2, 5, 44, '2023-04-18', 'remarks', 187.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(127, 2, 5, 46, '2023-04-18', 'remarks', 165.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(128, 2, 5, 48, '2023-04-18', 'remarks', 115.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(129, 2, 5, 50, '2023-04-18', 'remarks', 183.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(130, 2, 5, 52, '2023-04-18', 'remarks', 169.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(131, 1, 1, 1, '2023-04-18', 'remarks', 150.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(132, 1, 1, 3, '2023-04-18', 'remarks', 178.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(133, 1, 1, 5, '2023-04-18', 'remarks', 159.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(134, 1, 1, 7, '2023-04-18', 'remarks', 124.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(135, 1, 1, 9, '2023-04-18', 'remarks', 151.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(136, 1, 1, 11, '2023-04-18', 'remarks', 107.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(137, 1, 1, 13, '2023-04-18', 'remarks', 180.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(138, 1, 1, 15, '2023-04-18', 'remarks', 182.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(139, 1, 1, 17, '2023-04-18', 'remarks', 197.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(140, 1, 1, 19, '2023-04-18', 'remarks', 149.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(141, 1, 1, 21, '2023-04-18', 'remarks', 199.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(142, 1, 1, 23, '2023-04-18', 'remarks', 138.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(143, 1, 1, 25, '2023-04-18', 'remarks', 165.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(144, 1, 1, 27, '2023-04-18', 'remarks', 125.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(145, 1, 1, 29, '2023-04-18', 'remarks', 173.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(146, 1, 1, 31, '2023-04-18', 'remarks', 117.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(147, 1, 1, 33, '2023-04-18', 'remarks', 161.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(148, 1, 1, 35, '2023-04-18', 'remarks', 103.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(149, 1, 1, 37, '2023-04-18', 'remarks', 147.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(150, 1, 1, 39, '2023-04-18', 'remarks', 141.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(151, 1, 1, 41, '2023-04-18', 'remarks', 181.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(152, 1, 1, 43, '2023-04-18', 'remarks', 112.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(153, 1, 1, 45, '2023-04-18', 'remarks', 165.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(154, 1, 1, 47, '2023-04-18', 'remarks', 144.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(155, 1, 1, 49, '2023-04-18', 'remarks', 188.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(156, 1, 1, 51, '2023-04-18', 'remarks', 158.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(157, 1, 1, 53, '2023-04-18', 'remarks', 165.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(158, 1, 1, 55, '2023-04-18', 'remarks', 196.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(159, 1, 1, 57, '2023-04-18', 'remarks', 175.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(160, 1, 1, 59, '2023-04-18', 'remarks', 125.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(161, 1, 1, 61, '2023-04-18', 'remarks', 177.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(162, 1, 1, 63, '2023-04-18', 'remarks', 168.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(163, 1, 1, 65, '2023-04-18', 'remarks', 129.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(164, 1, 1, 67, '2023-04-18', 'remarks', 116.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(165, 1, 1, 69, '2023-04-18', 'remarks', 153.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(166, 1, 1, 71, '2023-04-18', 'remarks', 115.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(167, 1, 1, 73, '2023-04-18', 'remarks', 111.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(168, 1, 1, 75, '2023-04-18', 'remarks', 191.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(169, 1, 1, 77, '2023-04-18', 'remarks', 158.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(170, 1, 1, 79, '2023-04-18', 'remarks', 128.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(171, 1, 1, 81, '2023-04-18', 'remarks', 118.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(172, 1, 1, 83, '2023-04-18', 'remarks', 137.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(173, 1, 1, 85, '2023-04-18', 'remarks', 114.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(174, 1, 1, 87, '2023-04-18', 'remarks', 188.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(175, 1, 1, 89, '2023-04-18', 'remarks', 143.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(176, 1, 1, 91, '2023-04-18', 'remarks', 183.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(177, 1, 1, 93, '2023-04-18', 'remarks', 165.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(178, 1, 1, 95, '2023-04-18', 'remarks', 185.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(179, 1, 1, 97, '2023-04-18', 'remarks', 166.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(180, 1, 1, 99, '2023-04-18', 'remarks', 128.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(181, 1, 1, 101, '2023-04-18', 'remarks', 133.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(182, 1, 1, 103, '2023-04-18', 'remarks', 151.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(183, 2, 2, 2, '2023-04-18', 'remarks', 141.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(184, 2, 2, 4, '2023-04-18', 'remarks', 192.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(185, 2, 2, 6, '2023-04-18', 'remarks', 196.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(186, 2, 2, 8, '2023-04-18', 'remarks', 167.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(187, 2, 2, 10, '2023-04-18', 'remarks', 136.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(188, 2, 2, 12, '2023-04-18', 'remarks', 132.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(189, 2, 2, 14, '2023-04-18', 'remarks', 121.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(190, 2, 2, 16, '2023-04-18', 'remarks', 108.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(191, 2, 2, 18, '2023-04-18', 'remarks', 158.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(192, 2, 2, 20, '2023-04-18', 'remarks', 115.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(193, 2, 2, 22, '2023-04-18', 'remarks', 169.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(194, 2, 2, 24, '2023-04-18', 'remarks', 172.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(195, 2, 2, 26, '2023-04-18', 'remarks', 157.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(196, 2, 2, 28, '2023-04-18', 'remarks', 181.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(197, 2, 2, 30, '2023-04-18', 'remarks', 188.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(198, 2, 2, 32, '2023-04-18', 'remarks', 163.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(199, 2, 2, 34, '2023-04-18', 'remarks', 163.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(200, 2, 2, 36, '2023-04-18', 'remarks', 111.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(201, 2, 2, 38, '2023-04-18', 'remarks', 115.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(202, 2, 2, 40, '2023-04-18', 'remarks', 110.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(203, 2, 2, 42, '2023-04-18', 'remarks', 187.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(204, 2, 2, 44, '2023-04-18', 'remarks', 191.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(205, 2, 2, 46, '2023-04-18', 'remarks', 163.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(206, 2, 2, 48, '2023-04-18', 'remarks', 124.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(207, 2, 2, 50, '2023-04-18', 'remarks', 107.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(208, 2, 2, 52, '2023-04-18', 'remarks', 129.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(209, 2, 2, 54, '2023-04-18', 'remarks', 145.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(210, 2, 2, 56, '2023-04-18', 'remarks', 146.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(211, 2, 2, 58, '2023-04-18', 'remarks', 151.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(212, 2, 2, 60, '2023-04-18', 'remarks', 140.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(213, 2, 2, 62, '2023-04-18', 'remarks', 106.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(214, 2, 2, 64, '2023-04-18', 'remarks', 184.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(215, 2, 2, 66, '2023-04-18', 'remarks', 162.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(216, 2, 2, 68, '2023-04-18', 'remarks', 121.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(217, 2, 2, 70, '2023-04-18', 'remarks', 171.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(218, 2, 2, 72, '2023-04-18', 'remarks', 197.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(219, 2, 2, 74, '2023-04-18', 'remarks', 198.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(220, 2, 2, 76, '2023-04-18', 'remarks', 156.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(221, 2, 2, 78, '2023-04-18', 'remarks', 190.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(222, 2, 2, 80, '2023-04-18', 'remarks', 142.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(223, 2, 2, 82, '2023-04-18', 'remarks', 131.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(224, 2, 2, 84, '2023-04-18', 'remarks', 161.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(225, 2, 2, 86, '2023-04-18', 'remarks', 138.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(226, 2, 2, 88, '2023-04-18', 'remarks', 154.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(227, 2, 2, 90, '2023-04-18', 'remarks', 148.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(228, 2, 2, 92, '2023-04-18', 'remarks', 144.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(229, 2, 2, 94, '2023-04-18', 'remarks', 112.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(230, 2, 2, 96, '2023-04-18', 'remarks', 183.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(231, 2, 2, 98, '2023-04-18', 'remarks', 101.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(232, 2, 2, 100, '2023-04-18', 'remarks', 106.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(233, 2, 2, 102, '2023-04-18', 'remarks', 139.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(234, 2, 2, 104, '2023-04-18', 'remarks', 119.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(235, 2, 3, 2, '2023-04-18', 'remarks', 107.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(236, 2, 3, 4, '2023-04-18', 'remarks', 198.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(237, 2, 3, 6, '2023-04-18', 'remarks', 118.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(238, 2, 3, 8, '2023-04-18', 'remarks', 122.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(239, 2, 3, 10, '2023-04-18', 'remarks', 170.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(240, 2, 3, 12, '2023-04-18', 'remarks', 164.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(241, 2, 3, 14, '2023-04-18', 'remarks', 172.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(242, 2, 3, 16, '2023-04-18', 'remarks', 189.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(243, 2, 3, 18, '2023-04-18', 'remarks', 133.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(244, 2, 3, 20, '2023-04-18', 'remarks', 141.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(245, 2, 3, 22, '2023-04-18', 'remarks', 134.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(246, 2, 3, 24, '2023-04-18', 'remarks', 108.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(247, 2, 3, 26, '2023-04-18', 'remarks', 105.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(248, 2, 3, 28, '2023-04-18', 'remarks', 112.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(249, 2, 3, 30, '2023-04-18', 'remarks', 172.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(250, 2, 3, 32, '2023-04-18', 'remarks', 114.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(251, 2, 3, 34, '2023-04-18', 'remarks', 179.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(252, 2, 3, 36, '2023-04-18', 'remarks', 106.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(253, 2, 3, 38, '2023-04-18', 'remarks', 149.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(254, 2, 3, 40, '2023-04-18', 'remarks', 158.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(255, 2, 3, 42, '2023-04-18', 'remarks', 106.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(256, 2, 3, 44, '2023-04-18', 'remarks', 123.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(257, 2, 3, 46, '2023-04-18', 'remarks', 151.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(258, 2, 3, 48, '2023-04-18', 'remarks', 153.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(259, 2, 3, 50, '2023-04-18', 'remarks', 119.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(260, 2, 3, 52, '2023-04-18', 'remarks', 193.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(261, 2, 3, 54, '2023-04-18', 'remarks', 195.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(262, 2, 3, 56, '2023-04-18', 'remarks', 182.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(263, 2, 3, 58, '2023-04-18', 'remarks', 118.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(264, 2, 3, 60, '2023-04-18', 'remarks', 106.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(265, 2, 3, 62, '2023-04-18', 'remarks', 117.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(266, 2, 3, 64, '2023-04-18', 'remarks', 104.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(267, 2, 3, 66, '2023-04-18', 'remarks', 143.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(268, 2, 3, 68, '2023-04-18', 'remarks', 196.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(269, 2, 3, 70, '2023-04-18', 'remarks', 104.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(270, 2, 3, 72, '2023-04-18', 'remarks', 124.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(271, 2, 3, 74, '2023-04-18', 'remarks', 170.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(272, 2, 3, 76, '2023-04-18', 'remarks', 198.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(273, 2, 3, 78, '2023-04-18', 'remarks', 145.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(274, 2, 3, 80, '2023-04-18', 'remarks', 199.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(275, 2, 3, 82, '2023-04-18', 'remarks', 186.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(276, 2, 3, 84, '2023-04-18', 'remarks', 200.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(277, 2, 3, 86, '2023-04-18', 'remarks', 129.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(278, 2, 3, 88, '2023-04-18', 'remarks', 170.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(279, 2, 3, 90, '2023-04-18', 'remarks', 174.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(280, 2, 3, 92, '2023-04-18', 'remarks', 109.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(281, 2, 3, 94, '2023-04-18', 'remarks', 132.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(282, 2, 3, 96, '2023-04-18', 'remarks', 165.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(283, 2, 3, 98, '2023-04-18', 'remarks', 146.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(284, 2, 3, 100, '2023-04-18', 'remarks', 123.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(285, 2, 3, 102, '2023-04-18', 'remarks', 140.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(286, 2, 3, 104, '2023-04-18', 'remarks', 145.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(287, 2, 4, 2, '2023-04-18', 'remarks', 157.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(288, 2, 4, 4, '2023-04-18', 'remarks', 103.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(289, 2, 4, 6, '2023-04-18', 'remarks', 114.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(290, 2, 4, 8, '2023-04-18', 'remarks', 134.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(291, 2, 4, 10, '2023-04-18', 'remarks', 136.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(292, 2, 4, 12, '2023-04-18', 'remarks', 134.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(293, 2, 4, 14, '2023-04-18', 'remarks', 108.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(294, 2, 4, 16, '2023-04-18', 'remarks', 187.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(295, 2, 4, 18, '2023-04-18', 'remarks', 155.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(296, 2, 4, 20, '2023-04-18', 'remarks', 143.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(297, 2, 4, 22, '2023-04-18', 'remarks', 166.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(298, 2, 4, 24, '2023-04-18', 'remarks', 183.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(299, 2, 4, 26, '2023-04-18', 'remarks', 166.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(300, 2, 4, 28, '2023-04-18', 'remarks', 137.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(301, 2, 4, 30, '2023-04-18', 'remarks', 163.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(302, 2, 4, 32, '2023-04-18', 'remarks', 146.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(303, 2, 4, 34, '2023-04-18', 'remarks', 123.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(304, 2, 4, 36, '2023-04-18', 'remarks', 150.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(305, 2, 4, 38, '2023-04-18', 'remarks', 160.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(306, 2, 4, 40, '2023-04-18', 'remarks', 150.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(307, 2, 4, 42, '2023-04-18', 'remarks', 167.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(308, 2, 4, 44, '2023-04-18', 'remarks', 116.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(309, 2, 4, 46, '2023-04-18', 'remarks', 140.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(310, 2, 4, 48, '2023-04-18', 'remarks', 107.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(311, 2, 4, 50, '2023-04-18', 'remarks', 145.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(312, 2, 4, 52, '2023-04-18', 'remarks', 139.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(313, 2, 4, 54, '2023-04-18', 'remarks', 156.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(314, 2, 4, 56, '2023-04-18', 'remarks', 107.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(315, 2, 4, 58, '2023-04-18', 'remarks', 117.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(316, 2, 4, 60, '2023-04-18', 'remarks', 124.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(317, 2, 4, 62, '2023-04-18', 'remarks', 169.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(318, 2, 4, 64, '2023-04-18', 'remarks', 156.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(319, 2, 4, 66, '2023-04-18', 'remarks', 122.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(320, 2, 4, 68, '2023-04-18', 'remarks', 184.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(321, 2, 4, 70, '2023-04-18', 'remarks', 176.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(322, 2, 4, 72, '2023-04-18', 'remarks', 137.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(323, 2, 4, 74, '2023-04-18', 'remarks', 168.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(324, 2, 4, 76, '2023-04-18', 'remarks', 145.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(325, 2, 4, 78, '2023-04-18', 'remarks', 128.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(326, 2, 4, 80, '2023-04-18', 'remarks', 122.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(327, 2, 4, 82, '2023-04-18', 'remarks', 176.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(328, 2, 4, 84, '2023-04-18', 'remarks', 173.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(329, 2, 4, 86, '2023-04-18', 'remarks', 166.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(330, 2, 4, 88, '2023-04-18', 'remarks', 148.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(331, 2, 4, 90, '2023-04-18', 'remarks', 168.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(332, 2, 4, 92, '2023-04-18', 'remarks', 167.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(333, 2, 4, 94, '2023-04-18', 'remarks', 164.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(334, 2, 4, 96, '2023-04-18', 'remarks', 123.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(335, 2, 4, 98, '2023-04-18', 'remarks', 180.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(336, 2, 4, 100, '2023-04-18', 'remarks', 137.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(337, 2, 4, 102, '2023-04-18', 'remarks', 121.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(338, 2, 4, 104, '2023-04-18', 'remarks', 169.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(339, 2, 5, 2, '2023-04-18', 'remarks', 146.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(340, 2, 5, 4, '2023-04-18', 'remarks', 193.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(341, 2, 5, 6, '2023-04-18', 'remarks', 189.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(342, 2, 5, 8, '2023-04-18', 'remarks', 135.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(343, 2, 5, 10, '2023-04-18', 'remarks', 137.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(344, 2, 5, 12, '2023-04-18', 'remarks', 178.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(345, 2, 5, 14, '2023-04-18', 'remarks', 200.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(346, 2, 5, 16, '2023-04-18', 'remarks', 150.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(347, 2, 5, 18, '2023-04-18', 'remarks', 183.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(348, 2, 5, 20, '2023-04-18', 'remarks', 157.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(349, 2, 5, 22, '2023-04-18', 'remarks', 178.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(350, 2, 5, 24, '2023-04-18', 'remarks', 119.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(351, 2, 5, 26, '2023-04-18', 'remarks', 125.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(352, 2, 5, 28, '2023-04-18', 'remarks', 121.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(353, 2, 5, 30, '2023-04-18', 'remarks', 125.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(354, 2, 5, 32, '2023-04-18', 'remarks', 109.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(355, 2, 5, 34, '2023-04-18', 'remarks', 126.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(356, 2, 5, 36, '2023-04-18', 'remarks', 146.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(357, 2, 5, 38, '2023-04-18', 'remarks', 168.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(358, 2, 5, 40, '2023-04-18', 'remarks', 185.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(359, 2, 5, 42, '2023-04-18', 'remarks', 169.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(360, 2, 5, 44, '2023-04-18', 'remarks', 169.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(361, 2, 5, 46, '2023-04-18', 'remarks', 184.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(362, 2, 5, 48, '2023-04-18', 'remarks', 142.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(363, 2, 5, 50, '2023-04-18', 'remarks', 107.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(364, 2, 5, 52, '2023-04-18', 'remarks', 189.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(365, 2, 5, 54, '2023-04-18', 'remarks', 184.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(366, 2, 5, 56, '2023-04-18', 'remarks', 154.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(367, 2, 5, 58, '2023-04-18', 'remarks', 103.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(368, 2, 5, 60, '2023-04-18', 'remarks', 160.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(369, 2, 5, 62, '2023-04-18', 'remarks', 117.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(370, 2, 5, 64, '2023-04-18', 'remarks', 189.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(371, 2, 5, 66, '2023-04-18', 'remarks', 171.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(372, 2, 5, 68, '2023-04-18', 'remarks', 130.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(373, 2, 5, 70, '2023-04-18', 'remarks', 136.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(374, 2, 5, 72, '2023-04-18', 'remarks', 107.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(375, 2, 5, 74, '2023-04-18', 'remarks', 171.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(376, 2, 5, 76, '2023-04-18', 'remarks', 187.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(377, 2, 5, 78, '2023-04-18', 'remarks', 154.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(378, 2, 5, 80, '2023-04-18', 'remarks', 181.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(379, 2, 5, 82, '2023-04-18', 'remarks', 105.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(380, 2, 5, 84, '2023-04-18', 'remarks', 128.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(381, 2, 5, 86, '2023-04-18', 'remarks', 177.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(382, 2, 5, 88, '2023-04-18', 'remarks', 159.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(383, 2, 5, 90, '2023-04-18', 'remarks', 113.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(384, 2, 5, 92, '2023-04-18', 'remarks', 192.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(385, 2, 5, 94, '2023-04-18', 'remarks', 164.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(386, 2, 5, 96, '2023-04-18', 'remarks', 195.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(387, 2, 5, 98, '2023-04-18', 'remarks', 146.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(388, 2, 5, 100, '2023-04-18', 'remarks', 200.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(389, 2, 5, 102, '2023-04-18', 'remarks', 105.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46'),
(390, 2, 5, 104, '2023-04-18', 'remarks', 137.00, NULL, 1, 11, 9, NULL, '2023-04-18 01:07:46', '2023-04-18 01:07:46');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_languages`
--

CREATE TABLE `hrm_languages` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` int NOT NULL,
  `is_default` int NOT NULL DEFAULT '0',
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_languages`
--

INSERT INTO `hrm_languages` (`id`, `language_id`, `is_default`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 19, 1, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42');

-- --------------------------------------------------------

--
-- Table structure for table `income_expense_categories`
--

CREATE TABLE `income_expense_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_income` tinyint NOT NULL DEFAULT '0' COMMENT '0=Expense, 1=Income',
  `attachment_file_id` bigint UNSIGNED DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `income_expense_categories`
--

INSERT INTO `income_expense_categories` (`id`, `company_id`, `name`, `is_income`, `attachment_file_id`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Advance Salary', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(2, 2, 'Advance Salary', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(3, 1, 'Employee Loan', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(4, 2, 'Employee Loan', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(5, 1, 'Advertising', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(6, 2, 'Advertising', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(7, 1, 'Bank Charges', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(8, 2, 'Bank Charges', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(9, 1, 'Business Development', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(10, 2, 'Business Development', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(11, 1, 'Business Travel', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(12, 2, 'Business Travel', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(13, 1, 'Communication', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(14, 2, 'Communication', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(15, 1, 'Customer Service', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(16, 2, 'Customer Service', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(17, 1, 'Delivery', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(18, 2, 'Delivery', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(19, 1, 'Events', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(20, 2, 'Events', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(21, 1, 'Food & Beverage', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(22, 2, 'Food & Beverage', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(23, 1, 'Gifts', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(24, 2, 'Gifts', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(25, 1, 'Hospitality', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(26, 2, 'Hospitality', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(27, 1, 'Human Resources', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(28, 2, 'Human Resources', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(29, 1, 'Maintenance', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(30, 2, 'Maintenance', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(31, 1, 'Marketing', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(32, 2, 'Marketing', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(33, 1, 'Meeting', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(34, 2, 'Meeting', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(35, 1, 'Office Supplies', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(36, 2, 'Office Supplies', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(37, 1, 'Purchasing', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(38, 2, 'Purchasing', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(39, 1, 'Public Relations', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(40, 2, 'Public Relations', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(41, 1, 'Food', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(42, 2, 'Food', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(43, 1, 'Others', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(44, 2, 'Others', 0, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(45, 1, 'Project', 1, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(46, 2, 'Project', 1, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(47, 1, 'Income', 1, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(48, 2, 'Income', 1, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(49, 1, 'Revenue', 1, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(50, 2, 'Revenue', 1, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(51, 1, 'COGS', 1, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(52, 2, 'COGS', 1, NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(53, 1, 'Advance Salary', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(54, 2, 'Advance Salary', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(55, 1, 'Employee Loan', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(56, 2, 'Employee Loan', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(57, 1, 'Advertising', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(58, 2, 'Advertising', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(59, 1, 'Bank Charges', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(60, 2, 'Bank Charges', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(61, 1, 'Business Development', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(62, 2, 'Business Development', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(63, 1, 'Business Travel', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(64, 2, 'Business Travel', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(65, 1, 'Communication', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(66, 2, 'Communication', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(67, 1, 'Customer Service', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(68, 2, 'Customer Service', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(69, 1, 'Delivery', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(70, 2, 'Delivery', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(71, 1, 'Events', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(72, 2, 'Events', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(73, 1, 'Food & Beverage', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(74, 2, 'Food & Beverage', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(75, 1, 'Gifts', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(76, 2, 'Gifts', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(77, 1, 'Hospitality', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(78, 2, 'Hospitality', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(79, 1, 'Human Resources', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(80, 2, 'Human Resources', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(81, 1, 'Maintenance', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(82, 2, 'Maintenance', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(83, 1, 'Marketing', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(84, 2, 'Marketing', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(85, 1, 'Meeting', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(86, 2, 'Meeting', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(87, 1, 'Office Supplies', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(88, 2, 'Office Supplies', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(89, 1, 'Purchasing', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(90, 2, 'Purchasing', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(91, 1, 'Public Relations', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(92, 2, 'Public Relations', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(93, 1, 'Food', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(94, 2, 'Food', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(95, 1, 'Others', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(96, 2, 'Others', 0, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(97, 1, 'Project', 1, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(98, 2, 'Project', 1, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(99, 1, 'Income', 1, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(100, 2, 'Income', 1, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(101, 1, 'Revenue', 1, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(102, 2, 'Revenue', 1, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(103, 1, 'COGS', 1, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45'),
(104, 2, 'COGS', 1, NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45');

-- --------------------------------------------------------

--
-- Table structure for table `indicators`
--

CREATE TABLE `indicators` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `shift_id` bigint UNSIGNED DEFAULT NULL,
  `designation_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rates` json DEFAULT NULL,
  `rating` double(8,2) DEFAULT NULL,
  `added_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `indicators`
--

INSERT INTO `indicators` (`id`, `company_id`, `department_id`, `shift_id`, `designation_id`, `name`, `rates`, `rating`, `added_by`, `created_at`, `updated_at`) VALUES
(1, 2, 18, 4, 43, 'Project Management', '[{\"id\": 1, \"rating\": 1}, {\"id\": 2, \"rating\": 1}, {\"id\": 3, \"rating\": 1}, {\"id\": 4, \"rating\": 1}, {\"id\": 5, \"rating\": 1}, {\"id\": 6, \"rating\": 1}]', 1.00, 2, '2023-04-18 01:07:53', '2023-04-18 01:07:53');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `sent` tinyint NOT NULL DEFAULT '0',
  `datesend` datetime DEFAULT NULL,
  `clientid` int NOT NULL,
  `deleted_customer_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` int NOT NULL,
  `prefix` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_format` int NOT NULL DEFAULT '0',
  `datecreated` datetime NOT NULL,
  `date` date NOT NULL,
  `duedate` date DEFAULT NULL,
  `currency` int NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `total_tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL,
  `adjustment` decimal(15,2) DEFAULT NULL,
  `addedfrom` int DEFAULT NULL,
  `hash` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `clientnote` text COLLATE utf8mb4_unicode_ci,
  `adminnote` text COLLATE utf8mb4_unicode_ci,
  `last_overdue_reminder` date DEFAULT NULL,
  `last_due_reminder` date DEFAULT NULL,
  `cancel_overdue_reminders` int NOT NULL DEFAULT '0',
  `allowed_payment_modes` mediumtext COLLATE utf8mb4_unicode_ci,
  `token` mediumtext COLLATE utf8mb4_unicode_ci,
  `discount_percent` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recurring` int NOT NULL DEFAULT '0',
  `recurring_type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_recurring` tinyint NOT NULL DEFAULT '0',
  `cycles` int NOT NULL DEFAULT '0',
  `total_cycles` int NOT NULL DEFAULT '0',
  `is_recurring_from` int DEFAULT NULL,
  `last_recurring_date` date DEFAULT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `sale_agent` int NOT NULL DEFAULT '0',
  `billing_street` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_zip` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_country` int DEFAULT NULL,
  `shipping_street` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_zip` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_country` int DEFAULT NULL,
  `include_shipping` tinyint NOT NULL DEFAULT '0',
  `show_shipping_on_invoice` tinyint NOT NULL DEFAULT '1',
  `show_quantity_as` int NOT NULL DEFAULT '1',
  `project_id` int NOT NULL DEFAULT '0',
  `subscription_id` int NOT NULL DEFAULT '0',
  `short_link` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_payment_records`
--

CREATE TABLE `invoice_payment_records` (
  `id` bigint UNSIGNED NOT NULL,
  `invoiceid` int NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `paymentmode` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paymentmethod` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `daterecorded` datetime NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `transactionid` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ip_setups`
--

CREATE TABLE `ip_setups` (
  `id` bigint UNSIGNED NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `company_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jitsi_meetings`
--

CREATE TABLE `jitsi_meetings` (
  `id` bigint UNSIGNED NOT NULL,
  `meeting_id` text COLLATE utf8mb4_unicode_ci,
  `time_start_before` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `native` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rtl` tinyint DEFAULT '0',
  `status` tinyint DEFAULT '1' COMMENT '1=active, 0=inactive',
  `json_exist` tinyint DEFAULT '0',
  `is_default` tinyint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `code`, `name`, `native`, `rtl`, `status`, `json_exist`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'en', 'English', 'English', 0, 1, 0, 1, NULL, '2023-03-12 23:55:06'),
(2, 'ar', 'Arabic', 'العربية', 1, 1, 0, 0, NULL, NULL),
(3, 'es', 'Spanish', 'Español', 0, 1, 0, 0, NULL, NULL),
(4, 'bn', 'Bengali', 'বাংলা', 0, 0, 0, 0, NULL, NULL),
(5, 'af', 'Afrikaans', 'Afrikaans', 0, 0, 0, 0, NULL, NULL),
(6, 'am', 'Amharic', 'አማርኛ', 0, 0, 0, 0, NULL, NULL),
(7, 'ay', 'Aymara', 'Aymar', 0, 0, 0, 0, NULL, NULL),
(8, 'az', 'Azerbaijani', 'Azərbaycanca / آذربايجان', 0, 0, 0, 0, NULL, NULL),
(9, 'be', 'Belarusian', 'Беларуская', 0, 0, 0, 0, NULL, NULL),
(10, 'bg', 'Bulgarian', 'Български', 0, 0, 0, 0, NULL, NULL),
(11, 'bi', 'Bislama', 'Bislama', 0, 0, 0, 0, NULL, NULL),
(12, 'bs', 'Bosnian', 'Bosanski', 0, 0, 0, 0, NULL, NULL),
(13, 'ca', 'Catalan', 'Català', 0, 0, 0, 0, NULL, NULL),
(14, 'ch', 'Chamorro', 'Chamoru', 0, 0, 0, 0, NULL, NULL),
(15, 'cs', 'Czech', 'Česky', 0, 0, 0, 0, NULL, NULL),
(16, 'da', 'Danish', 'Dansk', 0, 0, 0, 0, NULL, NULL),
(17, 'de', 'German', 'Deutsch', 0, 0, 0, 0, NULL, NULL),
(18, 'dv', 'Divehi', 'ދިވެހިބަސް', 1, 0, 0, 0, NULL, NULL),
(19, 'dz', 'Dzongkha', 'ཇོང་ཁ', 0, 0, 0, 0, NULL, NULL),
(20, 'el', 'Greek', 'Ελληνικά', 0, 0, 0, 0, NULL, NULL),
(21, 'et', 'Estonian', 'Eesti', 0, 0, 0, 0, NULL, NULL),
(22, 'eu', 'Basque', 'Euskara', 0, 0, 0, 0, NULL, NULL),
(23, 'fa', 'Persian', 'فارسی', 1, 0, 0, 0, NULL, NULL),
(24, 'ff', 'Peul', 'Fulfulde', 0, 0, 0, 0, NULL, NULL),
(25, 'fi', 'Finnish', 'Suomi', 0, 0, 0, 0, NULL, NULL),
(26, 'fj', 'Fijian', 'Na Vosa Vakaviti', 0, 0, 0, 0, NULL, NULL),
(27, 'fo', 'Faroese', 'Føroyskt', 0, 0, 0, 0, NULL, NULL),
(28, 'fr', 'French', 'Français', 0, 0, 0, 0, NULL, NULL),
(29, 'ga', 'Irish', 'Gaeilge', 0, 0, 0, 0, NULL, NULL),
(30, 'gl', 'Galician', 'Galego', 0, 0, 0, 0, NULL, NULL),
(31, 'gn', 'Guarani', 'Avañe\'ẽ', 0, 0, 0, 0, NULL, NULL),
(32, 'gv', 'Manx', 'Gaelg', 0, 0, 0, 0, NULL, NULL),
(33, 'he', 'Hebrew', 'עברית', 1, 0, 0, 0, NULL, NULL),
(34, 'hi', 'Hindi', 'हिन्दी', 0, 0, 0, 0, NULL, NULL),
(35, 'hr', 'Croatian', 'Hrvatski', 0, 0, 0, 0, NULL, NULL),
(36, 'ht', 'Haitian', 'Krèyol ayisyen', 0, 0, 0, 0, NULL, NULL),
(37, 'hu', 'Hungarian', 'Magyar', 0, 0, 0, 0, NULL, NULL),
(38, 'hy', 'Armenian', 'Հայերեն', 0, 0, 0, 0, NULL, NULL),
(39, 'indo', 'Indonesian', 'Bahasa Indonesia', 0, 0, 0, 0, NULL, NULL),
(40, 'is', 'Icelandic', 'Íslenska', 0, 0, 0, 0, NULL, NULL),
(41, 'it', 'Italian', 'Italiano', 0, 0, 0, 0, NULL, NULL),
(42, 'ja', 'Japanese', '日本語', 0, 0, 0, 0, NULL, NULL),
(43, 'ka', 'Georgian', 'ქართული', 0, 0, 0, 0, NULL, NULL),
(44, 'kg', 'Kongo', 'KiKongo', 0, 0, 0, 0, NULL, NULL),
(45, 'kk', 'Kazakh', 'Қазақша', 0, 0, 0, 0, NULL, NULL),
(46, 'kl', 'Greenlandic', 'Kalaallisut', 0, 0, 0, 0, NULL, NULL),
(47, 'km', 'Cambodian', 'ភាសាខ្មែរ', 0, 0, 0, 0, NULL, NULL),
(48, 'ko', 'Korean', '한국어', 0, 0, 0, 0, NULL, NULL),
(49, 'ku', 'Kurdish', 'Kurdî / كوردی', 1, 0, 0, 0, NULL, NULL),
(50, 'ky', 'Kirghiz', 'Kırgızca / Кыргызча', 0, 0, 0, 0, NULL, NULL),
(51, 'la', 'Latin', 'Latina', 0, 0, 0, 0, NULL, NULL),
(52, 'lb', 'Luxembourgish', 'Lëtzebuergesch', 0, 0, 0, 0, NULL, NULL),
(53, 'ln', 'Lingala', 'Lingála', 0, 0, 0, 0, NULL, NULL),
(54, 'lo', 'Laotian', 'ລາວ / Pha xa lao', 0, 0, 0, 0, NULL, NULL),
(55, 'lt', 'Lithuanian', 'Lietuvių', 0, 0, 0, 0, NULL, NULL),
(56, 'lu', 'Luxembourg', 'Luxembourg', 0, 0, 0, 0, NULL, NULL),
(57, 'lv', 'Latvian', 'Latviešu', 0, 0, 0, 0, NULL, NULL),
(58, 'mg', 'Malagasy', 'Malagasy', 0, 0, 0, 0, NULL, NULL),
(59, 'mh', 'Marshallese', 'Kajin Majel / Ebon', 0, 0, 0, 0, NULL, NULL),
(60, 'mi', 'Maori', 'Māori', 0, 0, 0, 0, NULL, NULL),
(61, 'mk', 'Macedonian', 'Македонски', 0, 0, 0, 0, NULL, NULL),
(62, 'mn', 'Mongolian', 'Монгол', 0, 0, 0, 0, NULL, NULL),
(63, 'ms', 'Malay', 'Bahasa Melayu', 0, 0, 0, 0, NULL, NULL),
(64, 'mt', 'Maltese', 'bil-Malti', 0, 0, 0, 0, NULL, NULL),
(65, 'my', 'Burmese', 'မြန်မာစာ', 0, 0, 0, 0, NULL, NULL),
(66, 'na', 'Nauruan', 'Dorerin Naoero', 0, 0, 0, 0, NULL, NULL),
(67, 'nb', 'Bokmål', 'Bokmål', 0, 0, 0, 0, NULL, NULL),
(68, 'nd', 'North Ndebele', 'Sindebele', 0, 0, 0, 0, NULL, NULL),
(69, 'ne', 'Nepali', 'नेपाली', 0, 0, 0, 0, NULL, NULL),
(70, 'nl', 'Dutch', 'Nederlands', 0, 0, 0, 0, NULL, NULL),
(71, 'nn', 'Norwegian Nynorsk', 'Norsk (nynorsk)', 0, 0, 0, 0, NULL, NULL),
(72, 'no', 'Norwegian', 'Norsk (bokmål / riksmål)', 0, 0, 0, 0, NULL, NULL),
(73, 'nr', 'South Ndebele', 'isiNdebele', 0, 0, 0, 0, NULL, NULL),
(74, 'ny', 'Chichewa', 'Chi-Chewa', 0, 0, 0, 0, NULL, NULL),
(75, 'oc', 'Occitan', 'Occitan', 0, 0, 0, 0, NULL, NULL),
(76, 'pa', 'Panjabi / Punjabi', 'ਪੰਜਾਬੀ / पंजाबी / پنجابي', 0, 0, 0, 0, NULL, NULL),
(77, 'pl', 'Polish', 'Polski', 0, 0, 0, 0, NULL, NULL),
(78, 'ps', 'Pashto', 'پښتو', 1, 0, 0, 0, NULL, NULL),
(79, 'pt', 'Portuguese', 'Português', 0, 0, 0, 0, NULL, NULL),
(80, 'qu', 'Quechua', 'Runa Simi', 0, 0, 0, 0, NULL, NULL),
(81, 'rn', 'Kirundi', 'Kirundi', 0, 0, 0, 0, NULL, NULL),
(82, 'ro', 'Romanian', 'Română', 0, 0, 0, 0, NULL, NULL),
(83, 'ru', 'Russian', 'Русский', 0, 0, 0, 0, NULL, NULL),
(84, 'rw', 'Rwandi', 'Kinyarwandi', 0, 0, 0, 0, NULL, NULL),
(85, 'sg', 'Sango', 'Sängö', 0, 0, 0, 0, NULL, NULL),
(86, 'si', 'Sinhalese', 'සිංහල', 0, 0, 0, 0, NULL, NULL),
(87, 'sk', 'Slovak', 'Slovenčina', 0, 0, 0, 0, NULL, NULL),
(88, 'sl', 'Slovenian', 'Slovenščina', 0, 0, 0, 0, NULL, NULL),
(89, 'sm', 'Samoan', 'Gagana Samoa', 0, 0, 0, 0, NULL, NULL),
(90, 'sn', 'Shona', 'chiShona', 0, 0, 0, 0, NULL, NULL),
(91, 'so', 'Somalia', 'Soomaaliga', 0, 0, 0, 0, NULL, NULL),
(92, 'sq', 'Albanian', 'Shqip', 0, 0, 0, 0, NULL, NULL),
(93, 'sr', 'Serbian', 'Српски', 0, 0, 0, 0, NULL, NULL),
(94, 'ss', 'Swati', 'SiSwati', 0, 0, 0, 0, NULL, NULL),
(95, 'st', 'Southern Sotho', 'Sesotho', 0, 0, 0, 0, NULL, NULL),
(96, 'sv', 'Swedish', 'Svenska', 0, 0, 0, 0, NULL, NULL),
(97, 'sw', 'Swahili', 'Kiswahili', 0, 0, 0, 0, NULL, NULL),
(98, 'ta', 'Tamil', 'தமிழ்', 0, 0, 0, 0, NULL, NULL),
(99, 'tg', 'Tajik', 'Тоҷикӣ', 0, 0, 0, 0, NULL, NULL),
(100, 'th', 'Thai', 'ไทย / Phasa Thai', 0, 0, 0, 0, NULL, NULL),
(101, 'ti', 'Tigrinya', 'ትግርኛ', 0, 0, 0, 0, NULL, NULL),
(102, 'tk', 'Turkmen', 'Туркмен /تركمن ', 0, 0, 0, 0, NULL, NULL),
(103, 'tn', 'Tswana', 'Setswana', 0, 0, 0, 0, NULL, NULL),
(104, 'to', 'Tonga', 'Lea Faka-Tonga', 0, 0, 0, 0, NULL, NULL),
(105, 'tr', 'Turkish', 'Türkçe', 0, 0, 0, 0, NULL, NULL),
(106, 'ts', 'Tsonga', 'Xitsonga', 0, 0, 0, 0, NULL, NULL),
(107, 'uk', 'Ukrainian', 'Українська', 0, 0, 0, 0, NULL, NULL),
(108, 'ur', 'Urdu', 'اردو', 1, 0, 0, 0, NULL, NULL),
(109, 'uz', 'Uzbek', 'Ўзбек', 0, 0, 0, 0, NULL, NULL),
(110, 've', 'Venda', 'Tshivenḓa', 0, 0, 0, 0, NULL, NULL),
(111, 'vi', 'Vietnamese', 'Tiếng Việt', 0, 0, 0, 0, NULL, NULL),
(112, 'xh', 'Xhosa', 'isiXhosa', 0, 0, 0, 0, NULL, NULL),
(113, 'zh', 'Chinese', '中文', 0, 0, 0, 0, NULL, NULL),
(114, 'zu', 'Zulu', 'isiZulu', 0, 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `late_in_out_reasons`
--

CREATE TABLE `late_in_out_reasons` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `attendance_id` bigint UNSIGNED NOT NULL,
  `type` enum('in','out') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in' COMMENT 'in = late in reason out = late out reason',
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` bigint UNSIGNED NOT NULL,
  `lead_type_id` bigint UNSIGNED NOT NULL,
  `lead_source_id` bigint UNSIGNED NOT NULL,
  `lead_status_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `country` int NOT NULL DEFAULT '0',
  `state` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `next_follow_up` date DEFAULT NULL,
  `client_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attachments` json DEFAULT NULL COMMENT 'index,title, path,author,date,size',
  `emails` json DEFAULT NULL COMMENT 'index, date, subject, body, from, to, cc, bcc',
  `calls` json DEFAULT NULL COMMENT 'index, date, duration, type, subject, body, number,author',
  `activities` json DEFAULT NULL COMMENT 'index, date, status, author, message',
  `notes` json DEFAULT NULL COMMENT 'index, date, author, subject, message',
  `tasks` json DEFAULT NULL COMMENT 'index, date, status, author, subject, message',
  `reminders` json DEFAULT NULL COMMENT 'index, date, status, author, subject, message',
  `tags` json DEFAULT NULL COMMENT 'index, name',
  `deals` json DEFAULT NULL COMMENT 'index, name, amount, date, status, author, subject, message'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `lead_type_id`, `lead_source_id`, `lead_status_id`, `name`, `company`, `title`, `description`, `country`, `state`, `city`, `zip`, `address`, `email`, `phone`, `website`, `date`, `next_follow_up`, `client_id`, `created_by`, `company_id`, `status_id`, `created_at`, `updated_at`, `attachments`, `emails`, `calls`, `activities`, `notes`, `tasks`, `reminders`, `tags`, `deals`) VALUES
(1, 1, 1, 1, 'Mrs. Juana Nitzsche Jr.', 'Kling-Rolfson', 'Food Batchmaker', 'Lorem Ipsum is simply dummy text of th ever since the 1500s', 68, 'Maine', 'West Mateo', '33911-2780', '2604 Feil Ville\nNorth Dawsontown, RI 54931', 'mitchell.matilde@gmail.com', '580.980.4697', 'http://www.heathcote.com/', '2023-01-09', '2023-11-23', NULL, 2, 2, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06', '[{\"path\": \"public/assets/app/clients/1.png\", \"size\": \"5600\", \"index\": 0, \"title\": \"Attachment Title 1\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/2.png\", \"size\": \"5600\", \"index\": 1, \"title\": \"Attachment Title 2\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/3.png\", \"size\": \"5600\", \"index\": 2, \"title\": \"Attachment Title 3\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/4.png\", \"size\": \"5600\", \"index\": 3, \"title\": \"Attachment Title 4\", \"author\": \"Mr. Zaman [Admin]\"}]', '[{\"cc\": \"egoodwin@yahoo.com\", \"to\": \"arthur.rogahn@wiza.com\", \"bcc\": \"lkoch@yahoo.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"noemie.brown@daugherty.com\", \"index\": 0, \"subject\": \"Email Subject\"}, {\"cc\": \"rory.larson@russel.com\", \"to\": \"prohaska.dallas@ratke.com\", \"bcc\": \"kip.gutmann@reichel.org\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"veum.mateo@farrell.biz\", \"index\": 1, \"subject\": \"Email Subject\"}, {\"cc\": \"smorar@paucek.info\", \"to\": \"juliet.deckow@streich.net\", \"bcc\": \"lucienne.friesen@harvey.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"stracke.savion@gmail.com\", \"index\": 2, \"subject\": \"Email Subject\"}]', '[{\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 0, \"number\": \"(212) 419-9455\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 1, \"number\": \"+1 (620) 320-9215\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 2, \"number\": \"432-979-9142\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"body\": \"Neque est est et libero consequatur dolor. Ut et vitae quisquam molestiae. Autem fuga cupiditate tempora eos provident totam. Sed enim vel et eaque exercitationem iste.\", \"date\": \"2023-05-25\", \"index\": 1, \"subject\": \"I called him but not reachable 2\", \"created_by\": 1}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}]', '[{\"name\": \"Tag 1\", \"index\": 0}, {\"name\": \"Tag 2\", \"index\": 1}, {\"name\": \"Tag 3\", \"index\": 2}]', '[{\"date\": \"2023-05-25\", \"name\": \"Deal 1\", \"index\": 0, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 2\", \"index\": 1, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 3\", \"index\": 2, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}]'),
(2, 1, 1, 2, 'Prof. Kevon Jacobi', 'Larkin-Mraz', 'Retail Salesperson', 'Lorem Ipsum is simply dummy text of th ever since the 1500s', 10, 'Hawaii', 'Port Pascale', '93927', '6827 Beulah Courts Suite 370\nSouth Trevorberg, OK 02291', 'ueffertz@yahoo.com', '+1-628-983-6749', 'http://rogahn.com/dolores-sit-quas-ut-ipsum.html', '2022-05-15', '2023-08-24', NULL, 2, 2, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06', '[{\"path\": \"public/assets/app/clients/1.png\", \"size\": \"5600\", \"index\": 0, \"title\": \"Attachment Title 1\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/2.png\", \"size\": \"5600\", \"index\": 1, \"title\": \"Attachment Title 2\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/3.png\", \"size\": \"5600\", \"index\": 2, \"title\": \"Attachment Title 3\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/4.png\", \"size\": \"5600\", \"index\": 3, \"title\": \"Attachment Title 4\", \"author\": \"Mr. Zaman [Admin]\"}]', '[{\"cc\": \"loyce91@turcotte.com\", \"to\": \"rempel.ethan@yahoo.com\", \"bcc\": \"weissnat.pietro@hegmann.biz\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"psporer@gmail.com\", \"index\": 0, \"subject\": \"Email Subject\"}, {\"cc\": \"desmond.keebler@gmail.com\", \"to\": \"nils61@schulist.biz\", \"bcc\": \"lacey.erdman@hotmail.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"rodger.bogan@friesen.com\", \"index\": 1, \"subject\": \"Email Subject\"}, {\"cc\": \"tlesch@gmail.com\", \"to\": \"alehner@hotmail.com\", \"bcc\": \"broderick31@barton.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"ashlee39@weber.com\", \"index\": 2, \"subject\": \"Email Subject\"}]', '[{\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 0, \"number\": \"1-520-430-7090\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 1, \"number\": \"743.814.7134\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 2, \"number\": \"507-399-6292\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"body\": \"Possimus ex rem eos illum explicabo quasi labore. Recusandae cupiditate est accusantium quae dolores. Et consequatur cumque nihil debitis sunt exercitationem impedit. Quaerat placeat est deleniti perspiciatis suscipit sint.\", \"date\": \"2023-05-25\", \"index\": 1, \"subject\": \"I called him but not reachable 2\", \"created_by\": 1}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}]', '[{\"name\": \"Tag 1\", \"index\": 0}, {\"name\": \"Tag 2\", \"index\": 1}, {\"name\": \"Tag 3\", \"index\": 2}]', '[{\"date\": \"2023-05-25\", \"name\": \"Deal 1\", \"index\": 0, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 2\", \"index\": 1, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 3\", \"index\": 2, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}]'),
(3, 1, 1, 4, 'Santa Rogahn', 'Sporer-Braun', 'Sawing Machine Operator', 'Lorem Ipsum is simply dummy text of th ever since the 1500s', 71, 'Alaska', 'Koelpinburgh', '91136-4963', '727 Graham Stream Suite 736\nAdrianmouth, ME 98889-1960', 'tmccullough@hirthe.org', '(740) 817-8147', 'http://grant.com/', '2022-08-17', '2023-12-31', NULL, 2, 2, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06', '[{\"path\": \"public/assets/app/clients/1.png\", \"size\": \"5600\", \"index\": 0, \"title\": \"Attachment Title 1\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/2.png\", \"size\": \"5600\", \"index\": 1, \"title\": \"Attachment Title 2\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/3.png\", \"size\": \"5600\", \"index\": 2, \"title\": \"Attachment Title 3\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/4.png\", \"size\": \"5600\", \"index\": 3, \"title\": \"Attachment Title 4\", \"author\": \"Mr. Zaman [Admin]\"}]', '[{\"cc\": \"velda09@gmail.com\", \"to\": \"louie.davis@gmail.com\", \"bcc\": \"taryn25@gmail.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"bednar.madisyn@blick.biz\", \"index\": 0, \"subject\": \"Email Subject\"}, {\"cc\": \"zdickens@farrell.biz\", \"to\": \"mgleichner@yahoo.com\", \"bcc\": \"kuhn.maurice@hotmail.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"lowe.blake@corwin.com\", \"index\": 1, \"subject\": \"Email Subject\"}, {\"cc\": \"wolff.kole@marks.net\", \"to\": \"brakus.laisha@kilback.com\", \"bcc\": \"ggleason@tillman.biz\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"wunsch.kianna@hotmail.com\", \"index\": 2, \"subject\": \"Email Subject\"}]', '[{\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 0, \"number\": \"+1.930.879.1272\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 1, \"number\": \"+1-518-649-6605\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 2, \"number\": \"+19793809731\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"body\": \"Voluptatem sed aut aut optio laudantium. Iure hic vel quibusdam architecto dolor. Ut cumque ullam quae cum odio tempora. Atque qui maxime aut adipisci exercitationem. Aut et placeat dolores voluptatem nisi aut laboriosam.\", \"date\": \"2023-05-25\", \"index\": 1, \"subject\": \"I called him but not reachable 2\", \"created_by\": 1}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}]', '[{\"name\": \"Tag 1\", \"index\": 0}, {\"name\": \"Tag 2\", \"index\": 1}, {\"name\": \"Tag 3\", \"index\": 2}]', '[{\"date\": \"2023-05-25\", \"name\": \"Deal 1\", \"index\": 0, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 2\", \"index\": 1, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 3\", \"index\": 2, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}]'),
(4, 1, 1, 1, 'Prof. Jordan Ortiz I', 'Bayer Inc', 'Office Machine and Cash Register Servicer', 'Lorem Ipsum is simply dummy text of th ever since the 1500s', 48, 'New Hampshire', 'West Emerald', '34657', '988 Alysson Vista Suite 857\nEast Camdenshire, MA 96488', 'hickle.marilie@powlowski.biz', '857.624.8075', 'http://www.bergnaum.com/sunt-recusandae-quidem-et-et', '2022-09-17', '2024-04-13', NULL, 2, 2, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06', '[{\"path\": \"public/assets/app/clients/1.png\", \"size\": \"5600\", \"index\": 0, \"title\": \"Attachment Title 1\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/2.png\", \"size\": \"5600\", \"index\": 1, \"title\": \"Attachment Title 2\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/3.png\", \"size\": \"5600\", \"index\": 2, \"title\": \"Attachment Title 3\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/4.png\", \"size\": \"5600\", \"index\": 3, \"title\": \"Attachment Title 4\", \"author\": \"Mr. Zaman [Admin]\"}]', '[{\"cc\": \"gwaters@gmail.com\", \"to\": \"vada.schamberger@gmail.com\", \"bcc\": \"clara.mclaughlin@yahoo.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"florencio.windler@hotmail.com\", \"index\": 0, \"subject\": \"Email Subject\"}, {\"cc\": \"mellie48@hotmail.com\", \"to\": \"vlarkin@kerluke.net\", \"bcc\": \"lynch.lindsey@heaney.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"kiera.beier@ernser.com\", \"index\": 1, \"subject\": \"Email Subject\"}, {\"cc\": \"gutkowski.jaquelin@yahoo.com\", \"to\": \"willms.branson@hotmail.com\", \"bcc\": \"kristian73@russel.org\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"bettye84@wintheiser.net\", \"index\": 2, \"subject\": \"Email Subject\"}]', '[{\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 0, \"number\": \"1-470-815-3831\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 1, \"number\": \"(838) 576-6195\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 2, \"number\": \"(331) 596-8795\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"body\": \"Ut modi tempore iusto minus fuga. Tempora est et sunt nulla et rem voluptas. Quia hic nulla maiores voluptatem. Est officiis eligendi in earum autem minus. Ab dicta doloribus quia.\", \"date\": \"2023-05-25\", \"index\": 1, \"subject\": \"I called him but not reachable 2\", \"created_by\": 1}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}]', '[{\"name\": \"Tag 1\", \"index\": 0}, {\"name\": \"Tag 2\", \"index\": 1}, {\"name\": \"Tag 3\", \"index\": 2}]', '[{\"date\": \"2023-05-25\", \"name\": \"Deal 1\", \"index\": 0, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 2\", \"index\": 1, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 3\", \"index\": 2, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}]'),
(5, 1, 1, 3, 'Prof. Alyson Hessel DVM', 'Witting Inc', 'Materials Inspector', 'Lorem Ipsum is simply dummy text of th ever since the 1500s', 4, 'New Jersey', 'Hayesshire', '81187', '8710 Marlee Ville Apt. 670\nDevanchester, MD 94361', 'marilie.kirlin@gutmann.biz', '+1-956-656-6238', 'http://okon.com/eius-placeat-asperiores-accusamus-ut-consequatur', '2022-06-02', '2023-08-19', NULL, 2, 2, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06', '[{\"path\": \"public/assets/app/clients/1.png\", \"size\": \"5600\", \"index\": 0, \"title\": \"Attachment Title 1\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/2.png\", \"size\": \"5600\", \"index\": 1, \"title\": \"Attachment Title 2\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/3.png\", \"size\": \"5600\", \"index\": 2, \"title\": \"Attachment Title 3\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/4.png\", \"size\": \"5600\", \"index\": 3, \"title\": \"Attachment Title 4\", \"author\": \"Mr. Zaman [Admin]\"}]', '[{\"cc\": \"bosco.jeromy@moore.com\", \"to\": \"koss.vanessa@kutch.net\", \"bcc\": \"torp.lourdes@yahoo.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"bosco.bessie@yahoo.com\", \"index\": 0, \"subject\": \"Email Subject\"}, {\"cc\": \"mills.hunter@dubuque.com\", \"to\": \"athena47@hotmail.com\", \"bcc\": \"predovic.kayleigh@yahoo.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"shany39@paucek.com\", \"index\": 1, \"subject\": \"Email Subject\"}, {\"cc\": \"mbahringer@wolff.com\", \"to\": \"amina.trantow@reilly.com\", \"bcc\": \"tkerluke@christiansen.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"nitzsche.ole@lebsack.info\", \"index\": 2, \"subject\": \"Email Subject\"}]', '[{\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 0, \"number\": \"512-672-8231\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 1, \"number\": \"772.991.4281\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 2, \"number\": \"+17323896630\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"body\": \"Voluptatem et enim qui rerum ut. Veniam officiis sit nihil accusamus sapiente. Consequatur ipsa est nihil ea omnis. Et sequi et dolor saepe. Fugit cumque ea dolorem.\", \"date\": \"2023-05-25\", \"index\": 1, \"subject\": \"I called him but not reachable 2\", \"created_by\": 1}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}]', '[{\"name\": \"Tag 1\", \"index\": 0}, {\"name\": \"Tag 2\", \"index\": 1}, {\"name\": \"Tag 3\", \"index\": 2}]', '[{\"date\": \"2023-05-25\", \"name\": \"Deal 1\", \"index\": 0, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 2\", \"index\": 1, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 3\", \"index\": 2, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}]'),
(6, 1, 1, 5, 'Mrs. Patricia Hermiston', 'Heaney, Trantow and Gibson', 'Dredge Operator', 'Lorem Ipsum is simply dummy text of th ever since the 1500s', 98, 'Iowa', 'Greenborough', '98315-6812', '4289 Pacocha Brook\nZiemetown, TX 61972-9781', 'pupton@gibson.com', '561.919.8458', 'http://schneider.org/iusto-quam-laudantium-est-aut.html', '2022-05-15', '2024-03-01', NULL, 2, 2, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06', '[{\"path\": \"public/assets/app/clients/1.png\", \"size\": \"5600\", \"index\": 0, \"title\": \"Attachment Title 1\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/2.png\", \"size\": \"5600\", \"index\": 1, \"title\": \"Attachment Title 2\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/3.png\", \"size\": \"5600\", \"index\": 2, \"title\": \"Attachment Title 3\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/4.png\", \"size\": \"5600\", \"index\": 3, \"title\": \"Attachment Title 4\", \"author\": \"Mr. Zaman [Admin]\"}]', '[{\"cc\": \"czulauf@yahoo.com\", \"to\": \"kaci.walter@bayer.biz\", \"bcc\": \"volkman.preston@yahoo.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"elvie.blick@aufderhar.biz\", \"index\": 0, \"subject\": \"Email Subject\"}, {\"cc\": \"little.charlotte@mann.com\", \"to\": \"annalise.kassulke@yahoo.com\", \"bcc\": \"kurtis.bosco@farrell.net\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"corwin.nella@bernier.com\", \"index\": 1, \"subject\": \"Email Subject\"}, {\"cc\": \"sydnee48@nikolaus.com\", \"to\": \"dino.maggio@okeefe.com\", \"bcc\": \"kolby45@gmail.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"robyn.weimann@wolf.com\", \"index\": 2, \"subject\": \"Email Subject\"}]', '[{\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 0, \"number\": \"520.492.2223\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 1, \"number\": \"803-359-5232\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 2, \"number\": \"445.575.1371\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"body\": \"Sit mollitia ratione ut earum ut. Culpa vitae ipsam quo dolore. Voluptatem et tempore et repellat.\", \"date\": \"2023-05-25\", \"index\": 1, \"subject\": \"I called him but not reachable 2\", \"created_by\": 1}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}]', '[{\"name\": \"Tag 1\", \"index\": 0}, {\"name\": \"Tag 2\", \"index\": 1}, {\"name\": \"Tag 3\", \"index\": 2}]', '[{\"date\": \"2023-05-25\", \"name\": \"Deal 1\", \"index\": 0, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 2\", \"index\": 1, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 3\", \"index\": 2, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}]'),
(7, 1, 1, 5, 'Jamir Goodwin', 'Stokes Ltd', 'Percussion Instrument Repairer', 'Lorem Ipsum is simply dummy text of th ever since the 1500s', 65, 'Virginia', 'Annamouth', '98644', '180 Dare Mountains Apt. 835\nTorphymouth, MN 90734', 'isaac.reinger@hotmail.com', '847.959.9521', 'http://www.conn.com/et-quia-accusamus-placeat-culpa-exercitationem-commodi', '2022-11-27', '2023-09-14', NULL, 2, 2, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06', '[{\"path\": \"public/assets/app/clients/1.png\", \"size\": \"5600\", \"index\": 0, \"title\": \"Attachment Title 1\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/2.png\", \"size\": \"5600\", \"index\": 1, \"title\": \"Attachment Title 2\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/3.png\", \"size\": \"5600\", \"index\": 2, \"title\": \"Attachment Title 3\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/4.png\", \"size\": \"5600\", \"index\": 3, \"title\": \"Attachment Title 4\", \"author\": \"Mr. Zaman [Admin]\"}]', '[{\"cc\": \"mohr.angus@hotmail.com\", \"to\": \"dudley32@ankunding.com\", \"bcc\": \"mueller.briana@yahoo.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"gunner.mueller@hotmail.com\", \"index\": 0, \"subject\": \"Email Subject\"}, {\"cc\": \"ashly.funk@yahoo.com\", \"to\": \"vilma08@yahoo.com\", \"bcc\": \"jamaal50@kohler.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"ambrose60@crooks.com\", \"index\": 1, \"subject\": \"Email Subject\"}, {\"cc\": \"retha.robel@jenkins.com\", \"to\": \"dfay@kemmer.org\", \"bcc\": \"champlin.will@goyette.net\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"vlittel@yahoo.com\", \"index\": 2, \"subject\": \"Email Subject\"}]', '[{\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 0, \"number\": \"1-724-996-8748\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 1, \"number\": \"(725) 820-6565\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 2, \"number\": \"662.955.5752\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"body\": \"Nobis laboriosam perspiciatis consequatur dolorum ex voluptatibus consequatur. Et voluptas totam distinctio ea quibusdam earum aliquid possimus. Autem fugiat amet autem quisquam. Similique delectus voluptatem delectus ipsum expedita doloremque. Facere repudiandae doloribus commodi tempora repudiandae assumenda omnis.\", \"date\": \"2023-05-25\", \"index\": 1, \"subject\": \"I called him but not reachable 2\", \"created_by\": 1}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}]', '[{\"name\": \"Tag 1\", \"index\": 0}, {\"name\": \"Tag 2\", \"index\": 1}, {\"name\": \"Tag 3\", \"index\": 2}]', '[{\"date\": \"2023-05-25\", \"name\": \"Deal 1\", \"index\": 0, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 2\", \"index\": 1, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 3\", \"index\": 2, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}]'),
(8, 1, 1, 2, 'Noah Gleason DDS', 'Nikolaus, Feil and Fay', 'Spotters', 'Lorem Ipsum is simply dummy text of th ever since the 1500s', 2, 'Arizona', 'West Babychester', '72687', '7964 Jacobs Parkway\nMarvintown, KY 42247-8019', 'bparker@gmail.com', '(320) 474-1472', 'http://www.treutel.biz/fugiat-tempore-dolor-nesciunt-totam-aperiam-ipsam.html', '2022-10-25', '2023-11-23', NULL, 2, 2, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06', '[{\"path\": \"public/assets/app/clients/1.png\", \"size\": \"5600\", \"index\": 0, \"title\": \"Attachment Title 1\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/2.png\", \"size\": \"5600\", \"index\": 1, \"title\": \"Attachment Title 2\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/3.png\", \"size\": \"5600\", \"index\": 2, \"title\": \"Attachment Title 3\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/4.png\", \"size\": \"5600\", \"index\": 3, \"title\": \"Attachment Title 4\", \"author\": \"Mr. Zaman [Admin]\"}]', '[{\"cc\": \"gdare@hotmail.com\", \"to\": \"alison.hessel@hotmail.com\", \"bcc\": \"beier.lavina@mohr.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"marshall.ankunding@hauck.info\", \"index\": 0, \"subject\": \"Email Subject\"}, {\"cc\": \"huels.davon@yahoo.com\", \"to\": \"vgulgowski@klein.com\", \"bcc\": \"christelle.dubuque@raynor.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"cydney.shields@yahoo.com\", \"index\": 1, \"subject\": \"Email Subject\"}, {\"cc\": \"austyn.schmidt@hand.com\", \"to\": \"deshaun.oconner@bashirian.biz\", \"bcc\": \"cassandra09@hotmail.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"pfannerstill.carmen@gmail.com\", \"index\": 2, \"subject\": \"Email Subject\"}]', '[{\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 0, \"number\": \"+1-458-267-3594\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 1, \"number\": \"+1-907-957-9489\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 2, \"number\": \"601-230-8599\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"body\": \"Labore ratione et possimus vel. Aliquam porro quis et maxime dolor in facere neque. Voluptate odit incidunt doloremque. Voluptates laborum natus dolorem et in est sed iusto. Quis non facere expedita et non esse est.\", \"date\": \"2023-05-25\", \"index\": 1, \"subject\": \"I called him but not reachable 2\", \"created_by\": 1}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}]', '[{\"name\": \"Tag 1\", \"index\": 0}, {\"name\": \"Tag 2\", \"index\": 1}, {\"name\": \"Tag 3\", \"index\": 2}]', '[{\"date\": \"2023-05-25\", \"name\": \"Deal 1\", \"index\": 0, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 2\", \"index\": 1, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 3\", \"index\": 2, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}]'),
(9, 1, 1, 5, 'Mrs. Nya Kiehn III', 'Bode, Marks and West', 'Makeup Artists', 'Lorem Ipsum is simply dummy text of th ever since the 1500s', 80, 'Idaho', 'East Bette', '60839', '13895 Erdman Fall Apt. 023\nWest Justine, CT 95832-1040', 'gcremin@gmail.com', '+1-559-449-1719', 'http://fadel.com/officia-soluta-ipsam-qui-in-quis-consectetur-in-facilis', '2022-04-22', '2023-10-15', NULL, 2, 2, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06', '[{\"path\": \"public/assets/app/clients/1.png\", \"size\": \"5600\", \"index\": 0, \"title\": \"Attachment Title 1\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/2.png\", \"size\": \"5600\", \"index\": 1, \"title\": \"Attachment Title 2\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/3.png\", \"size\": \"5600\", \"index\": 2, \"title\": \"Attachment Title 3\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/4.png\", \"size\": \"5600\", \"index\": 3, \"title\": \"Attachment Title 4\", \"author\": \"Mr. Zaman [Admin]\"}]', '[{\"cc\": \"stephany18@gmail.com\", \"to\": \"kamren06@dickinson.com\", \"bcc\": \"delta.kuhlman@gmail.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"xwindler@gmail.com\", \"index\": 0, \"subject\": \"Email Subject\"}, {\"cc\": \"kunde.brent@gmail.com\", \"to\": \"lempi.abernathy@kovacek.org\", \"bcc\": \"zora.bogisich@bergstrom.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"lexus00@kessler.info\", \"index\": 1, \"subject\": \"Email Subject\"}, {\"cc\": \"qkertzmann@gmail.com\", \"to\": \"zora50@yahoo.com\", \"bcc\": \"awelch@hotmail.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"satterfield.geovanni@gmail.com\", \"index\": 2, \"subject\": \"Email Subject\"}]', '[{\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 0, \"number\": \"1-339-939-0603\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 1, \"number\": \"+1-432-807-1910\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 2, \"number\": \"(440) 899-5465\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"body\": \"Nesciunt voluptate neque culpa autem. Accusantium nihil in et accusamus debitis laborum et. Asperiores sed rerum ex vel cupiditate voluptates.\", \"date\": \"2023-05-25\", \"index\": 1, \"subject\": \"I called him but not reachable 2\", \"created_by\": 1}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}]', '[{\"name\": \"Tag 1\", \"index\": 0}, {\"name\": \"Tag 2\", \"index\": 1}, {\"name\": \"Tag 3\", \"index\": 2}]', '[{\"date\": \"2023-05-25\", \"name\": \"Deal 1\", \"index\": 0, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 2\", \"index\": 1, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 3\", \"index\": 2, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}]'),
(10, 1, 1, 3, 'Dr. Godfrey Will', 'Stamm, Medhurst and Kshlerin', 'Talent Director', 'Lorem Ipsum is simply dummy text of th ever since the 1500s', 81, 'California', 'Thielview', '70683-7708', '879 Rosanna Locks Suite 847\nJamarcusview, MO 81806', 'nmarvin@hotmail.com', '+1-956-999-7360', 'http://www.quitzon.com/', '2022-09-30', '2023-07-19', NULL, 2, 2, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06', '[{\"path\": \"public/assets/app/clients/1.png\", \"size\": \"5600\", \"index\": 0, \"title\": \"Attachment Title 1\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/2.png\", \"size\": \"5600\", \"index\": 1, \"title\": \"Attachment Title 2\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/3.png\", \"size\": \"5600\", \"index\": 2, \"title\": \"Attachment Title 3\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/4.png\", \"size\": \"5600\", \"index\": 3, \"title\": \"Attachment Title 4\", \"author\": \"Mr. Zaman [Admin]\"}]', '[{\"cc\": \"benny.braun@hotmail.com\", \"to\": \"durgan.lenny@toy.biz\", \"bcc\": \"schultz.adell@luettgen.biz\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"hayley94@rogahn.com\", \"index\": 0, \"subject\": \"Email Subject\"}, {\"cc\": \"obernier@kuhic.biz\", \"to\": \"cfeest@hotmail.com\", \"bcc\": \"herzog.diego@lynch.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"beahan.oda@mante.com\", \"index\": 1, \"subject\": \"Email Subject\"}, {\"cc\": \"hagenes.leif@runolfsdottir.com\", \"to\": \"skling@gmail.com\", \"bcc\": \"camron.feest@gmail.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"loberbrunner@nienow.info\", \"index\": 2, \"subject\": \"Email Subject\"}]', '[{\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 0, \"number\": \"+16239819940\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 1, \"number\": \"+1 (772) 310-0269\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 2, \"number\": \"+1.209.958.0642\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"body\": \"Libero odit quod nobis enim tempora. Qui provident deleniti culpa labore sed perferendis et. Expedita vel est vero aut. Quis eaque sunt quidem suscipit autem nesciunt.\", \"date\": \"2023-05-25\", \"index\": 1, \"subject\": \"I called him but not reachable 2\", \"created_by\": 1}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}]', '[{\"name\": \"Tag 1\", \"index\": 0}, {\"name\": \"Tag 2\", \"index\": 1}, {\"name\": \"Tag 3\", \"index\": 2}]', '[{\"date\": \"2023-05-25\", \"name\": \"Deal 1\", \"index\": 0, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 2\", \"index\": 1, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 3\", \"index\": 2, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}]');
INSERT INTO `leads` (`id`, `lead_type_id`, `lead_source_id`, `lead_status_id`, `name`, `company`, `title`, `description`, `country`, `state`, `city`, `zip`, `address`, `email`, `phone`, `website`, `date`, `next_follow_up`, `client_id`, `created_by`, `company_id`, `status_id`, `created_at`, `updated_at`, `attachments`, `emails`, `calls`, `activities`, `notes`, `tasks`, `reminders`, `tags`, `deals`) VALUES
(11, 1, 1, 2, 'Miss Trycia Marks PhD', 'Weimann PLC', 'Title Abstractor', 'Lorem Ipsum is simply dummy text of th ever since the 1500s', 16, 'Delaware', 'East Cydney', '51107', '2178 Dion Knoll\nKeshawnmouth, NC 50500-6863', 'mclaughlin.abraham@yahoo.com', '(475) 418-1676', 'https://bernier.com/voluptatem-aut-commodi-consequatur-nihil-dolorum-deleniti-incidunt-ut.html', '2022-08-16', '2023-05-26', NULL, 2, 2, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06', '[{\"path\": \"public/assets/app/clients/1.png\", \"size\": \"5600\", \"index\": 0, \"title\": \"Attachment Title 1\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/2.png\", \"size\": \"5600\", \"index\": 1, \"title\": \"Attachment Title 2\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/3.png\", \"size\": \"5600\", \"index\": 2, \"title\": \"Attachment Title 3\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/4.png\", \"size\": \"5600\", \"index\": 3, \"title\": \"Attachment Title 4\", \"author\": \"Mr. Zaman [Admin]\"}]', '[{\"cc\": \"hane.neva@gmail.com\", \"to\": \"bortiz@yahoo.com\", \"bcc\": \"nedra93@hotmail.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"daniella.auer@bergstrom.com\", \"index\": 0, \"subject\": \"Email Subject\"}, {\"cc\": \"elizabeth69@hotmail.com\", \"to\": \"buddy.walsh@walter.com\", \"bcc\": \"jlittel@hotmail.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"ari.altenwerth@yahoo.com\", \"index\": 1, \"subject\": \"Email Subject\"}, {\"cc\": \"bskiles@aufderhar.com\", \"to\": \"lkovacek@kreiger.org\", \"bcc\": \"adolph.douglas@hotmail.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"leda.nolan@gmail.com\", \"index\": 2, \"subject\": \"Email Subject\"}]', '[{\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 0, \"number\": \"229-285-0538\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 1, \"number\": \"+1-847-678-5911\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 2, \"number\": \"(283) 905-0213\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"body\": \"Nihil quis at maxime accusantium rem. Voluptas asperiores blanditiis officia quia. Placeat voluptatem et veniam alias in nisi est. Repellat quod dolor repellendus tempore sapiente.\", \"date\": \"2023-05-25\", \"index\": 1, \"subject\": \"I called him but not reachable 2\", \"created_by\": 1}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}]', '[{\"name\": \"Tag 1\", \"index\": 0}, {\"name\": \"Tag 2\", \"index\": 1}, {\"name\": \"Tag 3\", \"index\": 2}]', '[{\"date\": \"2023-05-25\", \"name\": \"Deal 1\", \"index\": 0, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 2\", \"index\": 1, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 3\", \"index\": 2, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}]'),
(12, 1, 1, 4, 'Dr. Lauriane Schumm PhD', 'Prohaska, Schoen and Padberg', 'Industrial Engineering Technician', 'Lorem Ipsum is simply dummy text of th ever since the 1500s', 98, 'District of Columbia', 'South Royhaven', '29352-0546', '57748 Esta Route\nGrimesmouth, WY 75679-5067', 'maggio.narciso@gmail.com', '+1 (283) 465-0904', 'http://www.vandervort.com/iusto-provident-reprehenderit-iste-similique-et', '2023-04-13', '2023-05-24', NULL, 2, 2, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06', '[{\"path\": \"public/assets/app/clients/1.png\", \"size\": \"5600\", \"index\": 0, \"title\": \"Attachment Title 1\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/2.png\", \"size\": \"5600\", \"index\": 1, \"title\": \"Attachment Title 2\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/3.png\", \"size\": \"5600\", \"index\": 2, \"title\": \"Attachment Title 3\", \"author\": \"Mr. Zaman [Admin]\"}, {\"path\": \"public/assets/app/clients/4.png\", \"size\": \"5600\", \"index\": 3, \"title\": \"Attachment Title 4\", \"author\": \"Mr. Zaman [Admin]\"}]', '[{\"cc\": \"nschroeder@smith.com\", \"to\": \"gjaskolski@hotmail.com\", \"bcc\": \"ila12@kunze.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"bechtelar.sam@yahoo.com\", \"index\": 0, \"subject\": \"Email Subject\"}, {\"cc\": \"aruecker@sauer.com\", \"to\": \"fsawayn@reichel.com\", \"bcc\": \"murazik.keagan@kihn.biz\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"jadyn.kemmer@gmail.com\", \"index\": 1, \"subject\": \"Email Subject\"}, {\"cc\": \"heller.torrance@yahoo.com\", \"to\": \"anastasia58@yahoo.com\", \"bcc\": \"olen74@sipes.com\", \"body\": \"Email Body\", \"date\": \"2023-05-25\", \"from\": \"tpowlowski@yahoo.com\", \"index\": 2, \"subject\": \"Email Subject\"}]', '[{\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 0, \"number\": \"747.468.4664\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 1, \"number\": \"747.370.7477\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}, {\"body\": \"Call Body\", \"date\": \"2023-05-25\", \"type\": \"Incoming\", \"index\": 2, \"number\": \"+1 (872) 372-6259\", \"subject\": \"Call Subject\", \"duration\": \"00:00:00\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"New\", \"message\": \"Activity Message\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"message\": \"Note Message\", \"subject\": \"Note Subject\"}, {\"body\": \"Quidem et autem repellendus velit. Repudiandae dolor odio ab omnis consequuntur. Harum reprehenderit cumque molestiae saepe consequatur a earum velit.\", \"date\": \"2023-05-25\", \"index\": 1, \"subject\": \"I called him but not reachable 2\", \"created_by\": 1}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Task Message\", \"subject\": \"Task Subject\"}]', '[{\"date\": \"2023-05-25\", \"index\": 0, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 1, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}, {\"date\": \"2023-05-25\", \"index\": 2, \"author\": 1, \"status\": \"pending\", \"message\": \"Reminder Message\", \"subject\": \"Reminder Subject\"}]', '[{\"name\": \"Tag 1\", \"index\": 0}, {\"name\": \"Tag 2\", \"index\": 1}, {\"name\": \"Tag 3\", \"index\": 2}]', '[{\"date\": \"2023-05-25\", \"name\": \"Deal 1\", \"index\": 0, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 2\", \"index\": 1, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}, {\"date\": \"2023-05-25\", \"name\": \"Deal 3\", \"index\": 2, \"amount\": 1000, \"author\": 1, \"status\": \"pending\", \"message\": \"Deal Message\", \"subject\": \"Deal Subject\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `leads_email_integrations`
--

CREATE TABLE `leads_email_integrations` (
  `id` bigint UNSIGNED NOT NULL,
  `active` int NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imap_server` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `check_every` int NOT NULL DEFAULT '5',
  `responsible` int NOT NULL,
  `lead_source` int NOT NULL,
  `lead_status` int NOT NULL,
  `encryption` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `folder` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_run` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notify_lead_imported` tinyint(1) NOT NULL DEFAULT '1',
  `notify_lead_contact_more_times` tinyint(1) NOT NULL DEFAULT '1',
  `notify_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notify_ids` mediumtext COLLATE utf8mb4_unicode_ci,
  `mark_public` int NOT NULL DEFAULT '0',
  `only_loop_on_unseen_emails` tinyint(1) NOT NULL DEFAULT '1',
  `delete_after_import` int NOT NULL DEFAULT '0',
  `create_task_if_customer` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_activity_logs`
--

CREATE TABLE `lead_activity_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `lead_type_id` bigint UNSIGNED NOT NULL,
  `lead_source_id` bigint UNSIGNED NOT NULL,
  `lead_status_id` bigint UNSIGNED NOT NULL,
  `lead_id` bigint UNSIGNED NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `date` datetime NOT NULL,
  `next_follow_up` datetime DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `assigned_date` date DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_attachments`
--

CREATE TABLE `lead_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_to_clients` bigint UNSIGNED NOT NULL DEFAULT '22',
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `lead_id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_integration_emails`
--

CREATE TABLE `lead_integration_emails` (
  `id` bigint UNSIGNED NOT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci,
  `body` text COLLATE utf8mb4_unicode_ci,
  `dateadded` datetime NOT NULL,
  `leadid` int NOT NULL,
  `emailid` int NOT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `updated_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_sources`
--

CREATE TABLE `lead_sources` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` bigint NOT NULL DEFAULT '0',
  `status_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `updated_by` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lead_sources`
--

INSERT INTO `lead_sources` (`id`, `title`, `order`, `status_id`, `created_by`, `updated_by`, `company_id`, `created_at`, `updated_at`) VALUES
(1, 'Advertisement', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(2, 'Cold Call', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(3, 'Employee Referral', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(4, 'External Referral', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(5, 'Online Store', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(6, 'Partner', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(7, 'Public Relations', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(8, 'Sales Mail Alias', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(9, 'Seminar Partner', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(10, 'Internal Seminar', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(11, 'Trade Show', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(12, 'Web Research', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(13, 'Chat', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(14, 'Email', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(15, 'Support Portal', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(16, 'Phone', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(17, 'Social Media', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(18, 'Other', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(19, 'Advertisement', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(20, 'Cold Call', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(21, 'Employee Referral', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(22, 'External Referral', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(23, 'Online Store', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(24, 'Partner', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(25, 'Public Relations', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(26, 'Sales Mail Alias', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(27, 'Seminar Partner', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(28, 'Internal Seminar', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(29, 'Trade Show', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(30, 'Web Research', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(31, 'Chat', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(32, 'Email', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(33, 'Support Portal', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(34, 'Phone', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(35, 'Social Media', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(36, 'Other', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06');

-- --------------------------------------------------------

--
-- Table structure for table `lead_statuses`
--

CREATE TABLE `lead_statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `border_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#000000',
  `background_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#ffffff',
  `text_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#000000',
  `status_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `updated_by` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lead_statuses`
--

INSERT INTO `lead_statuses` (`id`, `title`, `order`, `border_color`, `background_color`, `text_color`, `status_id`, `created_by`, `updated_by`, `company_id`, `created_at`, `updated_at`) VALUES
(1, 'New', 1, '#007bff', '#007bff', '#fff', 1, 1, 1, 1, NULL, NULL),
(2, 'Contacted', 2, '#6c757d', '#6c757d', '#fff', 1, 1, 1, 1, NULL, NULL),
(3, 'Qualified', 3, '#17a2b8', '#17a2b8', '#fff', 1, 1, 1, 1, NULL, NULL),
(4, 'Unqualified', 4, '#ffc107', '#ffc107', '#fff', 1, 1, 1, 1, NULL, NULL),
(5, 'Proposal Sent', 5, '#28a745', '#28a745', '#fff', 1, 1, 1, 1, NULL, NULL),
(6, 'Negotiation/Review', 6, '#777777', '#ffffff', '#777777', 1, 1, 1, 1, NULL, NULL),
(7, 'Lost', 7, '#dc3545', '#dc3545', '#fff', 1, 1, 1, 1, NULL, NULL),
(8, 'New', 1, '#007bff', '#007bff', '#fff', 1, 1, 1, 2, NULL, NULL),
(9, 'Contacted', 2, '#6c757d', '#6c757d', '#fff', 1, 1, 1, 2, NULL, NULL),
(10, 'Qualified', 3, '#17a2b8', '#17a2b8', '#fff', 1, 1, 1, 2, NULL, NULL),
(11, 'Unqualified', 4, '#ffc107', '#ffc107', '#fff', 1, 1, 1, 2, NULL, NULL),
(12, 'Proposal Sent', 5, '#28a745', '#28a745', '#fff', 1, 1, 1, 2, NULL, NULL),
(13, 'Negotiation/Review', 6, '#777777', '#ffffff', '#777777', 1, 1, 1, 2, NULL, NULL),
(14, 'Lost', 7, '#dc3545', '#dc3545', '#fff', 1, 1, 1, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lead_subscriptions`
--

CREATE TABLE `lead_subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `description_in_item` tinyint(1) NOT NULL DEFAULT '0',
  `clientid` bigint UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `currency` bigint UNSIGNED NOT NULL,
  `tax_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `stripe_tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id_2` bigint UNSIGNED NOT NULL DEFAULT '0',
  `stripe_tax_id_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_plan_id` text COLLATE utf8mb4_unicode_ci,
  `stripe_subscription_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `next_billing_cycle` bigint DEFAULT NULL,
  `ends_at` bigint DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` bigint UNSIGNED NOT NULL DEFAULT '1',
  `project_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `hash` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_from` bigint UNSIGNED NOT NULL,
  `date_subscribed` timestamp NULL DEFAULT NULL,
  `in_test_environment` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_types`
--

CREATE TABLE `lead_types` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` bigint NOT NULL DEFAULT '0',
  `status_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `updated_by` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lead_types`
--

INSERT INTO `lead_types` (`id`, `title`, `order`, `status_id`, `created_by`, `updated_by`, `company_id`, `created_at`, `updated_at`) VALUES
(1, 'Institution/Software 01', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(2, 'Institution/Software 02', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(3, 'Institution/Software 03', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(4, 'Institution/Software 04', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(5, 'Institution/Software 05', 0, 1, 1, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(6, 'Institution/Software 01', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(7, 'Institution/Software 02', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(8, 'Institution/Software 03', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(9, 'Institution/Software 04', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(10, 'Institution/Software 05', 0, 1, 1, 1, 2, '2023-04-18 01:08:06', '2023-04-18 01:08:06');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `assign_leave_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `apply_date` date NOT NULL,
  `leave_from` date NOT NULL,
  `leave_to` date NOT NULL,
  `days` int NOT NULL,
  `reason` longtext COLLATE utf8mb4_unicode_ci,
  `substitute_id` bigint UNSIGNED DEFAULT NULL,
  `attachment_file_id` bigint UNSIGNED DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL,
  `author_info_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_settings`
--

CREATE TABLE `leave_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `sandwich_leave` tinyint(1) NOT NULL DEFAULT '0',
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `prorate_leave` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_settings`
--

INSERT INTO `leave_settings` (`id`, `company_id`, `sandwich_leave`, `month`, `prorate_leave`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '1', 3, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(2, 2, 0, '1', 0, '2023-04-18 01:07:42', '2023-04-18 01:07:42');

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `company_id`, `name`, `status_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Casual Leave', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(2, 1, 'Sick Leave', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(3, 1, 'Maternity Leave', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(4, 1, 'Paternity Leave', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(5, 1, 'Leave Without Pay', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(6, 2, 'Casual Leave', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(7, 2, 'Sick Leave', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(8, 2, 'Maternity Leave', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(9, 2, 'Paternity Leave', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(10, 2, 'Leave Without Pay', 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `location_binds`
--

CREATE TABLE `location_binds` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `latitude` double DEFAULT NULL COMMENT 'latitude',
  `longitude` double DEFAULT NULL COMMENT 'longitude',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'address',
  `distance` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_logs`
--

CREATE TABLE `location_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `distance` double(10,2) DEFAULT NULL COMMENT 'in km',
  `latitude` double DEFAULT NULL COMMENT 'latitude',
  `longitude` double DEFAULT NULL COMMENT 'longitude',
  `speed` double DEFAULT NULL COMMENT 'speed',
  `heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'heading',
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'city',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'address',
  `countryCode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'countryCode',
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Bangladesh' COMMENT 'country',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `location` text COLLATE utf8mb4_unicode_ci,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `start_at` time DEFAULT NULL,
  `end_at` time DEFAULT NULL,
  `attachment_file_id` bigint UNSIGNED DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `meetings`
--

INSERT INTO `meetings` (`id`, `company_id`, `user_id`, `title`, `description`, `location`, `duration`, `date`, `start_at`, `end_at`, `attachment_file_id`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Totam non omnis autem amet quia aliquam.', 'Quibusdam maxime inventore sapiente. Omnis quas delectus mollitia ut excepturi. Voluptate et ab consectetur sunt.', NULL, NULL, '2023-04-12', NULL, NULL, NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(2, 2, 2, 'Rerum in dolorem ad temporibus quo.', 'Quo maxime facilis cumque officia unde quis quia quia. Eum a recusandae consequatur id fuga asperiores. Ut architecto illo deserunt est.', NULL, NULL, '2023-04-26', NULL, NULL, NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(3, 2, 3, 'Nihil et sed provident molestiae consequatur vitae pariatur.', 'Est ab consequatur est ratione nemo voluptate. Harum iure quia tempora alias. Ipsa expedita culpa id qui magni occaecati debitis.', NULL, NULL, '2023-04-11', NULL, NULL, NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(4, 2, 4, 'Et ea architecto cumque dolorem rem.', 'Ut alias quas quia sint repellat. Rerum minus non in impedit ratione. Tenetur quis voluptatem natus ipsum molestias quos. Expedita nisi earum doloribus minus soluta perferendis.', NULL, NULL, '2023-04-20', NULL, NULL, NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(5, 2, 5, 'Et tempora voluptates quis molestias magnam quod.', 'Ipsum suscipit numquam enim accusantium sunt reprehenderit. Doloremque repellendus non dolor. Animi atque quaerat sunt consectetur iusto corrupti.', NULL, NULL, '2023-04-21', NULL, NULL, NULL, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_members`
--

CREATE TABLE `meeting_members` (
  `id` bigint UNSIGNED NOT NULL,
  `meeting_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `is_present` tinyint NOT NULL DEFAULT '0',
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meeting_participants`
--

CREATE TABLE `meeting_participants` (
  `id` bigint UNSIGNED NOT NULL,
  `participant_id` bigint UNSIGNED NOT NULL,
  `meeting_id` bigint UNSIGNED NOT NULL,
  `is_going` tinyint NOT NULL DEFAULT '0' COMMENT '0: Not going, 1: Going',
  `is_present` tinyint NOT NULL DEFAULT '0' COMMENT '0: Absent, 1: Present',
  `present_at` datetime DEFAULT NULL,
  `meeting_started_at` datetime DEFAULT NULL,
  `meeting_ended_at` datetime DEFAULT NULL,
  `meeting_duration` time DEFAULT NULL COMMENT 'Meeting duration in minutes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meeting_setups`
--

CREATE TABLE `meeting_setups` (
  `id` bigint UNSIGNED NOT NULL,
  `host_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `company_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `all_content_id` int DEFAULT NULL,
  `type` int NOT NULL DEFAULT '1' COMMENT '1=menu,2=footer',
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `position`, `url`, `all_content_id`, `type`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 'Home', 1, 'http://crm.test', NULL, 1, 1, '2023-04-18 01:07:53', NULL),
(2, 'About Us', 2, NULL, 1, 1, 1, '2023-04-18 01:07:53', NULL),
(3, 'Contact Us', 3, NULL, 2, 1, 1, '2023-04-18 01:07:53', NULL),
(4, 'Privacy Policy', 1, NULL, 3, 2, 1, '2023-04-18 01:07:53', NULL),
(5, 'Terms of Use', 2, NULL, 5, 2, 1, '2023-04-18 01:07:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `meta_information`
--

CREATE TABLE `meta_information` (
  `id` bigint UNSIGNED NOT NULL,
  `type` enum('all_shop','all_brand','all_search','login','registration','student_registration','affiliate_registration','be_a_seller','compare_list','add_to_cart','about_us','faqs','contact_us','careers','return_refund','support_policy','privacy_policy','terms_condition') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `meta_information`
--

INSERT INTO `meta_information` (`id`, `type`, `meta_title`, `meta_description`, `meta_image`, `meta_keywords`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'all_shop', 'all_shop-title', 'all_shop-description', 'all_shop-image', 'all_shop-keywors', NULL, NULL, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(2, 'all_brand', 'all_brand-title', 'all_brand-description', 'all_brand-image', 'all_brand-keywors', NULL, NULL, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(3, 'all_search', 'all_search-title', 'all_search-description', 'all_search-image', 'all_search-keywors', NULL, NULL, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(4, 'login', 'login-title', 'login-description', 'login-image', 'login-keywors', NULL, NULL, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(5, 'registration', 'registration-title', 'registration-description', 'registration-image', 'registration-keywors', NULL, NULL, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(6, 'student_registration', 'student_registration-title', 'student_registration-description', 'student_registration-image', 'student_registration-keywors', NULL, NULL, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(7, 'affiliate_registration', 'affiliate_registration-title', 'affiliate_registration-description', 'affiliate_registration-image', 'affiliate_registration-keywors', NULL, NULL, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(8, 'be_a_seller', 'be_a_seller-title', 'be_a_seller-description', 'be_a_seller-image', 'be_a_seller-keywors', NULL, NULL, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(9, 'compare_list', 'compare_list-title', 'compare_list-description', 'compare_list-image', 'compare_list-keywors', NULL, NULL, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(10, 'add_to_cart', 'add_to_cart-title', 'add_to_cart-description', 'add_to_cart-image', 'add_to_cart-keywors', NULL, NULL, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(11, 'about_us', 'about_us-title', 'about_us-description', 'about_us-image', 'about_us-keywors', NULL, NULL, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(12, 'faqs', 'faqs-title', 'faqs-description', 'faqs-image', 'faqs-keywors', NULL, NULL, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(13, 'contact_us', 'contact_us-title', 'contact_us-description', 'contact_us-image', 'contact_us-keywors', NULL, NULL, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(14, 'careers', 'careers-title', 'careers-description', 'careers-image', 'careers-keywors', NULL, NULL, '2023-04-18 01:07:16', '2023-04-18 01:07:16');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2013_10_11_000000_create_countries_table', 1),
(2, '2014_01_11_000000_create_statuses_table', 1),
(3, '2014_10_11_000001_create_activity_log_table', 1),
(4, '2014_10_11_000002_create_uploads_table', 1),
(5, '2014_10_11_000003_create_companies_table', 1),
(6, '2014_10_11_000004_create_roles_table', 1),
(7, '2014_10_11_000004_create_shifts_table', 1),
(8, '2014_10_11_000005_create_departments_table', 1),
(9, '2014_10_11_000005_create_designations_table', 1),
(10, '2014_10_12_000000_create_users_table', 1),
(11, '2014_10_12_000001_create_author_infos_table', 1),
(12, '2014_10_12_100000_create_password_resets_table', 1),
(13, '2019_05_03_000001_create_customer_columns', 1),
(14, '2019_05_03_000002_create_subscriptions_table', 1),
(15, '2019_05_03_000003_create_subscription_items_table', 1),
(16, '2019_08_19_000000_create_failed_jobs_table', 1),
(17, '2020_06_01_130821_create_settings_table', 1),
(18, '2020_06_01_130822_create_permissions_table', 1),
(19, '2020_06_01_130824_create_role_users_table', 1),
(20, '2021_09_24_050720_create_bank_accounts_table', 1),
(21, '2021_09_25_070000_create_payment_types_table', 1),
(22, '2021_09_25_080345_create_categories_table', 1),
(23, '2021_10_31_121218_create_translations_table', 1),
(24, '2021_11_03_044301_create_social_identities_table', 1),
(25, '2021_11_14_070513_create_notifications_old_table', 1),
(26, '2021_11_14_070607_create_conversations_table', 1),
(27, '2022_01_05_105820_create_leave_types_table', 1),
(28, '2022_01_05_111318_create_assign_leaves_table', 1),
(29, '2022_01_05_112116_create_leave_requests_table', 1),
(30, '2022_01_23_165353_create_weekends_table', 1),
(31, '2022_01_23_165357_create_holidays_table', 1),
(32, '2022_01_26_104953_create_duty_schedules_table', 1),
(33, '2022_02_07_144952_create_attendances_table', 1),
(34, '2022_02_07_175133_create_leave_settings_table', 1),
(35, '2022_02_10_151245_create_late_in_out_reasons_table', 1),
(36, '2022_02_25_160849_create_clients_table', 1),
(37, '2022_03_01_174425_create_company_configs_table', 1),
(38, '2022_03_02_170908_create_ip_setups_table', 1),
(39, '2022_03_05_000002_create_expense_categories_table', 1),
(40, '2022_03_05_050001_create_payment_methods_table', 1),
(41, '2022_03_05_060051_create_accounts_table', 1),
(42, '2022_03_05_060052_create_transactions_table', 1),
(43, '2022_03_05_061025_create_expenses_table', 1),
(44, '2022_03_05_061055_create_deposits_table', 1),
(45, '2022_03_05_100003_create_hrm_expenses_table', 1),
(46, '2022_03_05_100004_create_expense_claims_table', 1),
(47, '2022_03_05_100006_create_expense_claim_details_table', 1),
(48, '2022_03_05_100007_create_payment_histories_table', 1),
(49, '2022_03_05_100008_create_payment_history_details_table', 1),
(50, '2022_03_05_100009_create_payment_history_logs_table', 1),
(51, '2022_03_06_101527_create_visits_table', 1),
(52, '2022_03_06_103136_create_visit_images_table', 1),
(53, '2022_03_06_104118_create_visit_notes_table', 1),
(54, '2022_03_06_104139_create_visit_schedules_table', 1),
(55, '2022_03_09_174416_create_subscription_plans_table', 1),
(56, '2022_03_10_110216_create_app_screens_table', 1),
(57, '2022_03_10_114654_create_support_tickets_table', 1),
(58, '2022_03_10_131726_create_notices_table', 1),
(59, '2022_03_10_132017_create_notice_view_logs_table', 1),
(60, '2022_03_12_114157_create_appreciates_table', 1),
(61, '2022_03_13_104916_create_meetings_table', 1),
(62, '2022_03_13_112149_create_meeting_participants_table', 1),
(63, '2022_03_13_112853_create_appoinments_table', 1),
(64, '2022_03_13_112914_create_appoinment_participants_table', 1),
(65, '2022_03_13_113319_create_employee_tasks_table', 1),
(66, '2022_03_13_123151_create_employee_breaks_table', 1),
(67, '2022_03_15_131235_create_all_contents_table', 1),
(68, '2022_03_16_104248_create_contacts_table', 1),
(69, '2022_03_30_061715_create_features_table', 1),
(70, '2022_03_30_113900_create_testimonials_table', 1),
(71, '2022_03_31_140233_create_teams_table', 1),
(72, '2022_03_31_140552_create_team_members_table', 1),
(73, '2022_04_06_042459_create_sms_logs_table', 1),
(74, '2022_04_07_035721_create_user_devices_table', 1),
(75, '2022_04_07_044946_create_notification_types_table', 1),
(76, '2022_04_12_065957_create_ticket_replies_table', 1),
(77, '2022_05_16_071031_create_notifications_table', 1),
(78, '2022_05_17_062749_create_daily_leaves_table', 1),
(79, '2022_05_19_055538_create_notice_departments_table', 1),
(80, '2022_06_05_101104_create_meta_information_table', 1),
(81, '2022_06_09_093509_create_time_zones_table', 1),
(82, '2022_06_11_075042_create_date_formats_table', 1),
(83, '2022_06_12_080741_create_api_setups_table', 1),
(84, '2022_06_12_100839_create_currencies_table', 1),
(85, '2022_06_15_090457_create_advance_types_table', 1),
(86, '2022_06_15_130017_create_advance_salaries_table', 1),
(87, '2022_06_15_131620_create_advance_salary_logs_table', 1),
(88, '2022_06_16_115529_create_commissions_table', 1),
(89, '2022_06_16_122623_create_salary_setups_table', 1),
(90, '2022_06_16_122641_create_salary_setup_details_table', 1),
(91, '2022_06_16_122709_create_salary_generates_table', 1),
(92, '2022_06_16_122750_create_salary_payment_logs_table', 1),
(93, '2022_06_18_154114_create_languages_table', 1),
(94, '2022_06_18_155339_create_hrm_languages_table', 1),
(95, '2022_06_23_030258_create_location_logs_table', 1),
(96, '2022_06_25_080155_create_database_backups_table', 1),
(97, '2022_06_27_115744_create_meeting_setups_table', 1),
(98, '2022_06_27_121222_create_virtual_meetings_table', 1),
(99, '2022_06_27_121626_create_meeting_members_table', 1),
(100, '2022_06_27_123238_create_jitsi_meetings_table', 1),
(101, '2022_07_18_113807_database_updates_for_v_3_1', 1),
(102, '2022_07_21_132450_create_location_binds_table', 1),
(103, '2022_07_25_160850_create_goal_types_table', 1),
(104, '2022_07_25_160851_create_goals_table', 1),
(105, '2022_07_26_160617_create_projects_table', 1),
(106, '2022_07_26_160618_create_project_membars_table', 1),
(107, '2022_07_26_165806_create_discussions_table', 1),
(108, '2022_07_26_165807_create_discussion_comments_table', 1),
(109, '2022_07_26_165908_create_notes_table', 1),
(110, '2022_07_26_170007_create_project_files_table', 1),
(111, '2022_07_26_170008_create_project_file_comments_table', 1),
(112, '2022_07_26_170031_create_project_activities_table', 1),
(113, '2022_07_26_170205_create_project_payments_table', 1),
(114, '2022_08_01_140657_create_tasks_table', 1),
(115, '2022_08_01_140658_create_task_members_table', 1),
(116, '2022_08_01_141239_create_task_discussions_table', 1),
(117, '2022_08_01_141255_create_task_discussion_comments_table', 1),
(118, '2022_08_01_141323_create_task_notes_table', 1),
(119, '2022_08_01_141341_create_task_files_table', 1),
(120, '2022_08_01_141401_create_task_file_comments_table', 1),
(121, '2022_08_01_142250_create_task_activities_table', 1),
(122, '2022_08_03_130453_create_award_types_table', 1),
(123, '2022_08_03_130519_create_awards_table', 1),
(124, '2022_08_04_101142_create_travel_types_table', 1),
(125, '2022_08_04_101522_create_travel_table', 1),
(126, '2022_08_04_161248_create_competence_types_table', 1),
(127, '2022_08_04_161249_create_competences_table', 1),
(128, '2022_08_04_161325_create_indicators_table', 1),
(129, '2022_08_04_161344_create_appraisals_table', 1),
(130, '2022_08_10_132731_database_update_for_v33', 1),
(131, '2022_09_19_104223_create_services_table', 1),
(132, '2022_09_19_104344_create_portfolios_table', 1),
(133, '2022_09_19_105655_create_front_teams_table', 1),
(134, '2022_09_19_112019_create_menus_table', 1),
(135, '2022_09_19_112527_create_home_pages_table', 1),
(136, '2022_11_29_091119_create_stock_categories_table', 1),
(137, '2022_11_29_091120_create_stock_brands_table', 1),
(138, '2022_11_29_091950_create_product_units_table', 1),
(139, '2022_11_29_091958_create_stock_products_table', 1),
(140, '2022_11_30_060400_create_stock_payment_histories_table', 1),
(141, '2022_11_30_060401_create_product_purchases_table', 1),
(142, '2022_11_30_060402_create_product_purchase_histories_table', 1),
(143, '2022_11_30_060404_create_stock_histories_table', 1),
(144, '2022_11_30_060405_create_stock_sales_table', 1),
(145, '2022_11_30_060406_create_stock_sale_histories_table', 1),
(146, '2022_12_01_111516_create_skills_table', 1),
(147, '2022_12_07_062250_create_discussion_likes_table', 1),
(148, '2023_02_06_161124_create_sale_categories_table', 1),
(149, '2023_02_06_161920_create_sale_brands_table', 1),
(150, '2023_02_06_162241_create_sale_units_table', 1),
(151, '2023_02_06_163032_create_sale_taxes_table', 1),
(152, '2023_02_06_163134_create_sale_warehouses_table', 1),
(153, '2023_02_06_163505_create_sale_products_table', 1),
(154, '2023_02_06_163506_create_sale_adjustments_table', 1),
(155, '2023_02_06_163507_create_sale_variants_table', 1),
(156, '2023_02_06_163508_create_sale_product_variants_table', 1),
(157, '2023_02_06_163508_create_sale_product_warehouses_table', 1),
(158, '2023_02_06_171657_create_sale_product_adjustments_table', 1),
(159, '2023_02_06_180003_create_sale_stock_counts_table', 1),
(160, '2023_02_06_182100_create_sale_suppliers_table', 1),
(161, '2023_02_06_182101_create_sale_purchases_table', 1),
(162, '2023_02_06_182117_create_sale_product_purchases_table', 1),
(163, '2023_02_07_103557_create_sales_table', 1),
(164, '2023_02_07_103558_create_sale_product_sales_table', 1),
(165, '2023_02_07_114615_create_sale_gift_cards_table', 1),
(166, '2023_02_07_114928_create_sale_gift_card_recharges_table', 1),
(167, '2023_02_07_115203_create_sale_coupons_table', 1),
(168, '2023_02_07_115203_create_sale_customer_groups_table', 1),
(169, '2023_02_07_115205_create_sale_customers_table', 1),
(170, '2023_02_07_115206_create_sale_billers_table', 1),
(171, '2023_02_07_122501_create_sale_expense_categories_table', 1),
(172, '2023_02_07_122818_create_sale_expenses_table', 1),
(173, '2023_02_07_123954_create_sale_quotations_table', 1),
(174, '2023_02_07_123955_create_sale_product_quotations_table', 1),
(175, '2023_02_07_151624_create_sale_transfers_table', 1),
(176, '2023_02_07_160238_create_sale_returns_table', 1),
(177, '2023_02_07_161310_create_sale_return_purchases_table', 1),
(178, '2023_02_15_113540_create_lead_types_table', 1),
(179, '2023_02_15_113541_create_lead_sources_table', 1),
(180, '2023_02_15_113542_create_lead_statuses_table', 1),
(181, '2023_02_15_113549_create_leads_table', 1),
(182, '2023_02_15_114827_create_lead_activity_logs_table', 1),
(183, '2023_02_15_120037_create_lead_integration_emails_table', 1),
(184, '2023_02_15_120510_create_web_to_leads_table', 1),
(185, '2023_02_15_123053_create_proposals_table', 1),
(186, '2023_02_15_123404_create_proposal_comments_table', 1),
(187, '2023_02_15_123955_create_leads_email_integrations_table', 1),
(188, '2023_02_15_124442_create_invoice_payment_records_table', 1),
(189, '2023_02_15_124737_create_invoices_table', 1),
(190, '2023_02_15_125329_create_lead_subscriptions_table', 1),
(191, '2023_02_15_125654_create_sale_accounts_table', 1),
(192, '2023_02_15_172450_create_sale_cash_registers_table', 1),
(193, '2023_02_16_115823_create_sale_product_batches_table', 1),
(194, '2023_02_17_112329_create_sale_deliveries_table', 1),
(195, '2023_02_20_103402_create_sale_pos_settings_table', 1),
(196, '2023_02_20_103744_create_sale_reward_point_settings_table', 1),
(197, '2023_02_20_115240_create_sale_discount_plan_customers_table', 1),
(198, '2023_02_20_115429_create_sale_discount_plans_table', 1),
(199, '2023_02_20_115526_create_sale_discount_plan_discounts_table', 1),
(200, '2023_02_20_115833_create_sale_discounts_table', 1),
(201, '2023_02_20_131230_create_sale_payments_table', 1),
(202, '2023_02_25_120313_create_sale_product_returns_table', 1),
(203, '2023_02_26_061023_create_sale_purchase_product_returns_table', 1),
(204, '2023_02_27_064255_create_sale_product_transfers_table', 1),
(205, '2023_03_01_112810_create_search_menus_table', 1),
(206, '2023_03_02_070701_create_lead_attachments_table', 1),
(207, '2023_03_03_072857_create_sale_payment_with_cheques_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_to_customer` bigint UNSIGNED NOT NULL DEFAULT '22',
  `project_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `last_activity` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `attachment_file_id` bigint UNSIGNED DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `created_by`, `company_id`, `department_id`, `attachment_file_id`, `subject`, `date`, `description`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, NULL, 'Est ullam quae sint voluptatibus esse impedit beatae.', '2023-04-18', 'Suscipit quo nisi dolorem architecto recusandae nesciunt. Ducimus velit qui rerum fugit inventore ut. Nisi accusantium voluptas officia rerum. Nihil est qui reiciendis corrupti praesentium.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(2, 1, 1, 1, NULL, 'Consequatur quas molestias temporibus pariatur nihil necessitatibus exercitationem.', '2023-04-18', 'Aut rerum placeat nostrum temporibus eum accusantium. Sapiente placeat non velit vel praesentium dicta soluta. Atque provident reprehenderit est consequatur. Est ipsa error libero doloribus porro excepturi.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(3, 1, 1, 1, NULL, 'Delectus mollitia ea necessitatibus aperiam voluptas.', '2023-04-18', 'Et eaque eos sunt officia delectus praesentium molestiae. Similique ut beatae ad sunt aliquid inventore asperiores. Sit neque at illo et laborum voluptatem.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(4, 1, 1, 1, NULL, 'Molestiae maxime quod in sed qui.', '2023-04-18', 'Omnis aut accusamus aut harum placeat aut est et. Expedita eos odio iusto assumenda reiciendis deleniti. Suscipit temporibus neque error tempore fuga nesciunt.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(5, 1, 1, 1, NULL, 'Quia non ut voluptatum ea a quis sed.', '2023-04-18', 'Omnis necessitatibus laboriosam autem et aliquam qui. Enim delectus incidunt temporibus laborum iure corporis porro. Qui unde animi exercitationem culpa illo.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(6, 1, 1, 1, NULL, 'Atque aut quo adipisci officia voluptas vero.', '2023-04-18', 'Qui reprehenderit nulla delectus dolores et nam. Numquam quisquam sint doloremque molestias consequatur facere. Molestiae iusto quia sit rem occaecati corporis.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(7, 1, 1, 1, NULL, 'At eum nihil repellendus quibusdam dolor et et.', '2023-04-18', 'Dolorum quod corrupti hic officia consequatur omnis ut nobis. Nesciunt dolorem ullam atque ut.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(8, 1, 1, 1, NULL, 'Quo necessitatibus culpa placeat corporis ipsum sequi.', '2023-04-18', 'Ut dignissimos eum placeat repudiandae repudiandae eos et. Alias expedita aut ipsam voluptate. Voluptatem velit molestiae enim ratione officia corporis nihil. Quia aut pariatur facilis autem quia.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(9, 1, 1, 1, NULL, 'Qui animi sed nulla dicta rem perferendis non.', '2023-04-18', 'Deleniti rem consequatur voluptas explicabo. Qui ut suscipit harum. Tempore sit provident voluptatem sunt nostrum. Eum distinctio reiciendis laborum tenetur repellendus.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(10, 1, 1, 1, NULL, 'Dignissimos voluptatem non eos sapiente.', '2023-04-18', 'Sint excepturi voluptatem unde iusto dicta ut error excepturi. Animi dolores tenetur voluptatem quis aperiam ipsum. Non repudiandae cupiditate voluptatem dolores corporis similique tempore.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(11, 1, 1, 2, NULL, 'Dignissimos eum deserunt eos nostrum et.', '2023-04-18', 'Et sapiente ab et odio nobis. Est consequatur non dolor atque et placeat aliquid. Nemo beatae ipsa occaecati. Molestiae reiciendis aut asperiores enim quis facilis.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(12, 1, 1, 2, NULL, 'Culpa animi ut voluptate veniam.', '2023-04-18', 'Amet corrupti dignissimos quas unde est doloribus quia ab. Odit et non quos enim officiis amet. Veritatis cumque ut enim hic est. Velit id et dolorem atque nihil dolores.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(13, 1, 1, 2, NULL, 'Nam nemo inventore odio officia.', '2023-04-18', 'Molestiae debitis dicta rem ipsam molestias et non. Corrupti consequuntur odio facilis neque dicta et aliquid. Libero velit quia neque voluptates explicabo unde.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(14, 1, 1, 2, NULL, 'Dolores dolor sed qui dolor et quam corporis odio.', '2023-04-18', 'Tenetur adipisci officia et aut. Corporis ipsum qui veritatis aspernatur. Omnis sit iure numquam ullam aliquid. Voluptate consequatur optio veritatis omnis.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(15, 1, 1, 2, NULL, 'Aut et fugit inventore esse et.', '2023-04-18', 'Porro quidem suscipit quis error sed. Ut consequatur assumenda vel delectus a sit.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(16, 1, 1, 2, NULL, 'Quo perferendis dignissimos recusandae qui molestiae.', '2023-04-18', 'Ullam sapiente ipsa voluptatem nihil facilis aliquam quis. Laborum repellendus mollitia tenetur qui exercitationem dolor ducimus. Omnis perspiciatis assumenda est totam et. Consequatur quidem cum quam impedit quis occaecati provident.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(17, 1, 1, 2, NULL, 'Voluptatem magni et nihil dolores esse praesentium.', '2023-04-18', 'Alias dolorem eaque quia et ut. Voluptas sint culpa in aut ab est aut. Vero facere sint molestias labore delectus aut. Mollitia natus aut ducimus numquam.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(18, 1, 1, 2, NULL, 'Nemo unde sunt rem dolor.', '2023-04-18', 'Veritatis aperiam dolore ut. Et voluptatem at magni iure unde iste. Eius culpa magni aut. Et reprehenderit accusamus ab provident corporis dolore.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(19, 1, 1, 2, NULL, 'Natus et error laboriosam saepe.', '2023-04-18', 'Porro aperiam qui autem rerum fugit est vitae. Natus placeat aut qui. Expedita aut consequatur eum quisquam quisquam iure aliquam et. Fugiat qui ipsum sapiente et.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(20, 1, 1, 2, NULL, 'Aut fugit explicabo ratione.', '2023-04-18', 'Reiciendis incidunt voluptatem omnis quas voluptate dolores eligendi. Enim aut illum expedita sed debitis ut perspiciatis impedit.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(21, 1, 1, 3, NULL, 'Facere doloremque quae quos aliquam.', '2023-04-18', 'Aspernatur minima iure doloribus dolorum. Voluptatem enim rerum in aliquam ducimus impedit dicta aut. Explicabo numquam quod cupiditate molestiae quisquam. Necessitatibus dicta et qui non. Mollitia maiores quaerat sequi eligendi ipsam assumenda eos.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(22, 1, 1, 3, NULL, 'Inventore consectetur illum ut nulla.', '2023-04-18', 'Adipisci itaque in sunt dolorem ducimus illum repellat. Sit soluta saepe voluptatum optio. Perferendis voluptates quam eligendi voluptatibus quaerat saepe saepe.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(23, 1, 1, 3, NULL, 'Asperiores odit qui dolores.', '2023-04-18', 'Dolores quos ut commodi impedit quisquam ut quis. Reprehenderit aut non praesentium dolorem laborum ipsam culpa ullam. Voluptas dolorum sit quasi unde illum officia. Qui sit adipisci commodi et nostrum beatae.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(24, 1, 1, 3, NULL, 'Aliquam fugiat et quia consequatur.', '2023-04-18', 'Ab aspernatur quia quas nemo quod. Quia quidem quasi eos qui. Corporis omnis aliquam debitis molestiae.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(25, 1, 1, 3, NULL, 'Omnis voluptas ut accusantium harum.', '2023-04-18', 'Optio voluptates omnis reprehenderit laudantium hic. Repudiandae deserunt sunt ea. In qui molestias autem ut voluptas.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(26, 1, 1, 3, NULL, 'Consequatur repellendus unde dolorum quo ut quia quia.', '2023-04-18', 'Quia laudantium deleniti velit est et enim. Magni cum deserunt earum pariatur. Aut aut exercitationem nostrum doloribus. Corporis asperiores dignissimos natus dolores est velit vel.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(27, 1, 1, 3, NULL, 'Quia tenetur sit dolore cumque nemo.', '2023-04-18', 'Illum non qui saepe amet. Et quis aliquam ratione molestiae ullam voluptatem. Vel at id quia nemo. Occaecati laborum eum et.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(28, 1, 1, 3, NULL, 'Consequatur dolores est libero non repellendus et consequuntur.', '2023-04-18', 'Perferendis saepe est corrupti non minima. Voluptas dolor perferendis sunt facilis ut. Laboriosam in enim non veritatis quaerat atque iure.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(29, 1, 1, 3, NULL, 'Nemo ut dolor harum reiciendis ut sit.', '2023-04-18', 'Consequatur consequatur ut eos eos. Debitis eum magnam maiores molestiae vero in qui. Architecto odit nemo illo itaque expedita neque.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(30, 1, 1, 3, NULL, 'Impedit itaque in est accusantium porro.', '2023-04-18', 'Voluptatem fuga et necessitatibus vero recusandae. Ullam et ut qui dicta inventore aut laborum. Et in totam voluptatibus aliquam.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(31, 1, 2, 4, NULL, 'Veniam excepturi impedit molestias ex voluptas.', '2023-04-18', 'Ipsum et asperiores corrupti voluptatem. Ipsam quaerat atque reiciendis dolores laboriosam unde. Delectus totam consequuntur ut quod voluptas maiores qui. Molestiae dolores et culpa voluptas voluptatem.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(32, 1, 2, 4, NULL, 'Quaerat ut hic nam temporibus fuga quo id aliquid.', '2023-04-18', 'Soluta et cumque dicta sapiente dolore sed. Et deleniti corporis doloribus rerum eum. Impedit nobis est et. Provident recusandae ut velit provident qui et.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(33, 1, 2, 4, NULL, 'Blanditiis quia dignissimos veniam eveniet.', '2023-04-18', 'Qui doloribus assumenda aut velit aut saepe alias. Ad facilis aspernatur odit aut aut id quisquam. Officia voluptatem et ut unde nam minima quia. Sed officiis in molestiae nisi qui ab ea illum. Ut voluptatem ipsam enim repudiandae dolorem omnis voluptatem', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(34, 1, 2, 4, NULL, 'Exercitationem et id quam at magnam omnis at.', '2023-04-18', 'Non corporis dignissimos accusamus eaque. In animi laudantium et sed aut pariatur nihil. Vel ut sunt sit aut necessitatibus incidunt et id.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(35, 1, 2, 4, NULL, 'Cum molestias occaecati similique voluptates ipsa soluta explicabo.', '2023-04-18', 'In velit sed error voluptatum. Quasi enim ut eaque voluptas iure impedit dolor sed. Doloribus autem ducimus expedita. Quia consequatur numquam sed reiciendis non molestiae.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(36, 1, 2, 4, NULL, 'Alias non ut et.', '2023-04-18', 'In nesciunt fugit dolores cum dignissimos unde cum. Eligendi est minus itaque maxime eligendi dolorem sapiente. Eius magni laborum qui et.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(37, 1, 2, 4, NULL, 'Accusantium adipisci rerum rerum ut dolore quam.', '2023-04-18', 'Magni id quia nihil ullam dolor omnis. Iste ea aspernatur sunt atque. Aut at dolorem ut ut rerum omnis qui. Facilis beatae ducimus sed aut nihil.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(38, 1, 2, 4, NULL, 'Quia deleniti quisquam vel facere corrupti laudantium culpa.', '2023-04-18', 'Soluta rerum incidunt veniam aperiam commodi earum. Laboriosam dignissimos quam sed consequatur repellendus illum corrupti. Facere quas magni eum. Voluptas beatae asperiores ad nulla et magnam hic fuga.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(39, 1, 2, 4, NULL, 'Tempora ducimus distinctio veniam perspiciatis modi est.', '2023-04-18', 'Explicabo sunt illo et maxime rerum labore sunt et. Deserunt neque mollitia culpa explicabo. Pariatur recusandae quaerat magni consequatur alias quo. Ad eveniet iusto itaque dolorem.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(40, 1, 2, 4, NULL, 'Eveniet est sed eaque.', '2023-04-18', 'Delectus laboriosam voluptatem non nobis architecto. Aut optio ea cumque doloremque maxime. Esse nihil rerum sed.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(41, 1, 2, 5, NULL, 'Et quo sunt omnis aut iste explicabo.', '2023-04-18', 'Ut explicabo dicta magnam quam culpa voluptatem earum voluptas. Eum ullam tempore doloremque laudantium id maxime ducimus. Iusto temporibus impedit sed sit repudiandae. Pariatur minima officia veniam molestiae rem sequi tempora.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(42, 1, 2, 5, NULL, 'Nihil natus enim omnis libero beatae non.', '2023-04-18', 'Ipsa vitae qui impedit voluptas similique quos sequi necessitatibus. Cum numquam beatae nihil architecto. Libero esse veritatis consequatur quia et tenetur iste odio.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(43, 1, 2, 5, NULL, 'Sit ipsam velit nemo consectetur aliquam.', '2023-04-18', 'Qui inventore ex ratione. Nulla in doloribus pariatur ducimus ipsum rerum accusantium. A iste qui dicta et aut expedita nihil. Facere omnis asperiores est ut veniam repellendus.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(44, 1, 2, 5, NULL, 'Facilis velit nihil soluta ea consequuntur.', '2023-04-18', 'Mollitia fugiat eaque sit cupiditate placeat amet. Magni exercitationem officia repudiandae expedita aut. Non voluptates nemo sit aspernatur molestiae. Repudiandae veritatis et sequi eaque ut nulla.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(45, 1, 2, 5, NULL, 'Omnis et hic esse.', '2023-04-18', 'Velit a perferendis voluptatum doloribus blanditiis alias inventore. Fugit animi non eos sunt aspernatur dicta. Non doloremque minus veritatis debitis. Dicta sunt et non consequatur. Sunt culpa quia sint dicta.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(46, 1, 2, 5, NULL, 'Aut voluptas rerum vel eligendi autem.', '2023-04-18', 'Dolores facilis blanditiis assumenda non tempora. Consequatur assumenda quaerat eligendi deleniti expedita. Saepe ut recusandae atque occaecati autem qui qui. Quia dicta labore optio perspiciatis dolorem sit veritatis.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(47, 1, 2, 5, NULL, 'Vitae ut facilis aspernatur beatae temporibus.', '2023-04-18', 'Et quia occaecati rem. Autem eveniet aliquid et iste quae laborum ut asperiores. Similique quod est qui eius sequi. Odio qui sint sequi pariatur sint eveniet.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(48, 1, 2, 5, NULL, 'Sunt ut saepe minima explicabo.', '2023-04-18', 'Corrupti quam possimus est modi harum officiis sed nam. Possimus magnam fugiat voluptatem in provident a. At voluptas eos quia rem. Modi quo suscipit in nam illum natus omnis.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(49, 1, 2, 5, NULL, 'Cum dicta ut accusantium consectetur et magni.', '2023-04-18', 'Nulla et harum eos soluta. Perferendis ipsa laboriosam illum id beatae est optio. Enim et ullam sit velit ipsum rerum neque. Distinctio omnis nobis doloribus reiciendis.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(50, 1, 2, 5, NULL, 'Dolore aut dolores delectus ea.', '2023-04-18', 'Sit dolores dolorem sed accusantium. Id molestiae ex saepe quam veniam dolores nihil. Voluptatum molestiae explicabo sit et deserunt ducimus suscipit.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(51, 1, 2, 6, NULL, 'Incidunt velit nisi tempora similique perspiciatis nulla ut aperiam.', '2023-04-18', 'Quisquam beatae voluptatum enim ut enim expedita. Molestias at provident porro dolore vero. Tempora ut quod ut illo dicta vitae.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(52, 1, 2, 6, NULL, 'Voluptas tenetur minus ratione illum maxime eligendi ea.', '2023-04-18', 'Dicta exercitationem assumenda maxime aperiam nam tempore dicta. Hic earum id at aliquid. Nisi omnis ut quo sequi magnam. Voluptas tempore explicabo quasi voluptatum quia incidunt.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(53, 1, 2, 6, NULL, 'Dolores neque eum officiis perspiciatis et voluptates dolore.', '2023-04-18', 'Nobis a enim aliquid praesentium. Error est quibusdam velit doloribus autem reprehenderit voluptatem. Molestias in optio aut autem.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(54, 1, 2, 6, NULL, 'Aliquam architecto et voluptatem minus sit eum.', '2023-04-18', 'Explicabo alias amet harum odio. Est quia earum ipsum inventore tenetur. Error maxime et voluptas debitis non doloremque eligendi.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(55, 1, 2, 6, NULL, 'Perspiciatis illum ea qui.', '2023-04-18', 'Dolorum ex quasi eveniet enim quia natus. Quae quas eligendi labore facilis rerum sapiente. Et ut ut sunt.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(56, 1, 2, 6, NULL, 'Provident praesentium quos nobis mollitia fugit.', '2023-04-18', 'Ut est pariatur illum libero. Dolor distinctio natus eos unde et voluptatem autem. Adipisci voluptatem tempora et quia iste.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(57, 1, 2, 6, NULL, 'Animi quia iste quis.', '2023-04-18', 'Qui ipsum dolores natus numquam nostrum vel. Sunt tempora rerum voluptatem accusantium dolores aut eos amet. Error velit quia tempora amet.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(58, 1, 2, 6, NULL, 'Rerum molestiae aut inventore neque tempora voluptate molestiae.', '2023-04-18', 'Ut qui et provident repudiandae. Id facilis enim vel explicabo aperiam iure provident. Error mollitia fugiat et velit dolorum ipsam nam accusamus.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(59, 1, 2, 6, NULL, 'Maiores debitis atque sit.', '2023-04-18', 'Aliquid temporibus qui qui. Corporis quo aperiam ratione officia. Et iure esse iste officia officia cum alias enim. Assumenda doloribus nesciunt at inventore eos nemo sed.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(60, 1, 2, 6, NULL, 'Provident aut aut alias totam voluptate.', '2023-04-18', 'Et laboriosam animi temporibus dignissimos amet fugiat. Quod aut debitis laborum magni ut. Ut numquam a autem quo ea est.', 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `notice_departments`
--

CREATE TABLE `notice_departments` (
  `id` bigint UNSIGNED NOT NULL,
  `department_id` int NOT NULL,
  `noticeable_id` int NOT NULL,
  `noticeable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notice_view_logs`
--

CREATE TABLE `notice_view_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `notice_id` bigint UNSIGNED NOT NULL,
  `is_view` tinyint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notice_view_logs`
--

INSERT INTO `notice_view_logs` (`id`, `company_id`, `user_id`, `notice_id`, `is_view`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(2, 1, 1, 2, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(3, 1, 1, 3, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(4, 1, 1, 4, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(5, 1, 1, 5, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(6, 1, 1, 6, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(7, 1, 1, 7, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(8, 1, 1, 8, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(9, 1, 1, 9, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(10, 1, 1, 10, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(11, 2, 2, 31, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(12, 2, 2, 32, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(13, 2, 2, 33, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(14, 2, 2, 34, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(15, 2, 2, 35, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(16, 2, 2, 36, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(17, 2, 2, 37, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(18, 2, 2, 38, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(19, 2, 2, 39, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(20, 2, 2, 40, 0, '2023-04-18 01:07:43', '2023-04-18 01:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications_old`
--

CREATE TABLE `notifications_old` (
  `id` bigint UNSIGNED NOT NULL,
  `sender_id` bigint UNSIGNED NOT NULL,
  `receiver_id` bigint UNSIGNED DEFAULT NULL,
  `type` enum('notification','message') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'notification' COMMENT 'notification: notification, message: message',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `image_id` bigint UNSIGNED DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_types`
--

CREATE TABLE `notification_types` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `icon` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_types`
--

INSERT INTO `notification_types` (`id`, `type`, `title`, `description`, `status_id`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'leave_request', 'Leave Request', 'Your Leave Request has been sent', 1, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(2, 'leave_approved', 'Leave Approved', 'Your Leave Application has been approved', 1, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(3, 'leave_rejected', 'Leave Rejected', 'Your Leave Application has been Rejected', 1, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(4, 'leave_cancelled', 'Leave Cancelled', 'Your Leave Application has been Cancelled', 1, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(5, 'leave_referred', 'Leave Referred', 'Your Leave Application has been Referred ', 1, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(6, 'notice', 'Notice', 'Notice ', 1, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43');

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
-- Table structure for table `payment_histories`
--

CREATE TABLE `payment_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `expense_claim_id` bigint UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'invoice number',
  `payment_date` date DEFAULT NULL COMMENT 'date of payment',
  `remarks` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'remarks of payment',
  `payable_amount` double(10,2) DEFAULT NULL COMMENT 'amount of payment',
  `paid_amount` double(10,2) DEFAULT NULL COMMENT 'paid amount of payment',
  `due_amount` double(10,2) DEFAULT NULL COMMENT 'due amount of payment',
  `attachment_file_id` bigint UNSIGNED DEFAULT NULL,
  `payment_status_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_history_details`
--

CREATE TABLE `payment_history_details` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `payment_history_id` bigint UNSIGNED NOT NULL,
  `payment_method_id` bigint UNSIGNED NOT NULL,
  `payment_details` longtext COLLATE utf8mb4_unicode_ci COMMENT 'remarks of payment',
  `payment_status_id` bigint UNSIGNED NOT NULL,
  `payment_date` date DEFAULT NULL COMMENT 'date of payment',
  `paid_by_id` bigint UNSIGNED DEFAULT NULL,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'bank name',
  `bank_branch` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'bank branch',
  `bank_account_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'bank account number',
  `bank_account_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'bank account name',
  `bank_transaction_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'bank transaction number',
  `bank_transaction_date` date DEFAULT NULL COMMENT 'bank transaction date',
  `bank_transaction_ref` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'bank transaction ref',
  `cheque_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'cheque number',
  `cheque_date` date DEFAULT NULL COMMENT 'cheque date',
  `cheque_bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'cheque bank name',
  `cheque_branch` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'cheque branch',
  `cheque_ref` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'cheque ref',
  `cash_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'cash number',
  `cash_date` date DEFAULT NULL COMMENT 'cash date',
  `cash_ref` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'cash ref',
  `paid_amount` double(15,2) NOT NULL DEFAULT '0.00',
  `due_amount` double(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_history_logs`
--

CREATE TABLE `payment_history_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `payment_history_id` bigint UNSIGNED NOT NULL,
  `expense_claim_id` bigint UNSIGNED NOT NULL,
  `payable_amount` double(10,2) DEFAULT NULL COMMENT 'amount of payment',
  `paid_amount` double(10,2) DEFAULT NULL COMMENT 'paid amount of payment',
  `due_amount` double(10,2) DEFAULT NULL COMMENT 'due amount of payment',
  `date` date DEFAULT NULL,
  `paid_by_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment_file_id` bigint UNSIGNED DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `company_id`, `name`, `attachment_file_id`, `status_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Cash', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(2, 2, 'Cash', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(3, 1, 'Cheque', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(4, 2, 'Cheque', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(5, 1, 'Bank Transfer', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(6, 2, 'Bank Transfer', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(7, 1, 'Credit Card', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(8, 2, 'Credit Card', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(9, 1, 'Debit Card', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(10, 2, 'Debit Card', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(11, 1, 'Paypal', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(12, 2, 'Paypal', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(13, 1, 'Stripe', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(14, 2, 'Stripe', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(15, 1, 'Payoneer', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(16, 2, 'Payoneer', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(17, 1, 'Paytm', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(18, 2, 'Paytm', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(19, 1, 'Amazon Pay', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(20, 2, 'Amazon Pay', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(21, 1, 'Google Pay', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(22, 2, 'Google Pay', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(23, 1, 'Apple Pay', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(24, 2, 'Apple Pay', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(25, 1, 'Phone Pay', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(26, 2, 'Phone Pay', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(27, 1, 'Other', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(28, 2, 'Other', NULL, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL),
(29, 1, 'Cash', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(30, 2, 'Cash', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(31, 1, 'Cheque', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(32, 2, 'Cheque', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(33, 1, 'Bank Transfer', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(34, 2, 'Bank Transfer', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(35, 1, 'Credit Card', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(36, 2, 'Credit Card', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(37, 1, 'Debit Card', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(38, 2, 'Debit Card', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(39, 1, 'Paypal', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(40, 2, 'Paypal', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(41, 1, 'Stripe', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(42, 2, 'Stripe', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(43, 1, 'Payoneer', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(44, 2, 'Payoneer', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(45, 1, 'Paytm', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(46, 2, 'Paytm', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(47, 1, 'Amazon Pay', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(48, 2, 'Amazon Pay', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(49, 1, 'Google Pay', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(50, 2, 'Google Pay', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(51, 1, 'Apple Pay', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(52, 2, 'Apple Pay', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(53, 1, 'Phone Pay', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(54, 2, 'Phone Pay', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(55, 1, 'Other', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL),
(56, 2, 'Other', NULL, 1, '2023-04-18 01:07:45', '2023-04-18 01:07:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_types`
--

CREATE TABLE `payment_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int NOT NULL DEFAULT '1' COMMENT '1 - cash, 2 - credit card, 3 - debit card, 4 - bank',
  `status_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `attribute` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keywords` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `attribute`, `keywords`, `created_at`, `updated_at`) VALUES
(1, 'hr_menu', '{\"menu\":\"hr_menu\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(2, 'designations', '{\"read\":\"designation_read\",\"create\":\"designation_create\",\"update\":\"designation_update\",\"delete\":\"designation_delete\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(3, 'departments', '{\"read\":\"department_read\",\"create\":\"department_create\",\"update\":\"department_update\",\"delete\":\"department_delete\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(4, 'users', '{\"read\":\"user_read\",\"profile\":\"profile_view\",\"create\":\"user_create\",\"edit\":\"user_edit\",\"user_permission\":\"user_permission\",\"update\":\"user_update\",\"delete\":\"user_delete\",\"menu\":\"user_menu\",\"make_hr\":\"make_hr\",\"profile_image_view\":\"profile_image_view\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(5, 'profile', '{\"attendance_profile\":\"attendance_profile\",\"contract_profile\":\"contract_profile\",\"phonebook_profile\":\"phonebook_profile\",\"support_ticket_profile\":\"support_ticket_profile\",\"advance_profile\":\"advance_profile\",\"commission_profile\":\"commission_profile\",\"appointment_profile\":\"appointment_profile\",\"visit_profile\":\"visit_profile\",\"leave_request_profile\":\"leave_request_profile\",\"notice_profile\":\"notice_profile\",\"salary_profile\":\"salary_profile\",\"project_profile\":\"project_profile\",\"task_profile\":\"task_profile\",\"award_profile\":\"award_profile\",\"travel_profile\":\"travel_profile\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(6, 'roles', '{\"read\":\"role_read\",\"create\":\"role_create\",\"update\":\"role_update\",\"delete\":\"role_delete\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(7, 'leave_type', '{\"read\":\"leave_type_read\",\"create\":\"leave_type_create\",\"update\":\"leave_type_update\",\"delete\":\"leave_type_delete\",\"menu\":\"leave_menu\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(8, 'leave_assign', '{\"read\":\"leave_assign_read\",\"create\":\"leave_assign_create\",\"update\":\"leave_assign_update\",\"delete\":\"leave_assign_delete\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(9, 'leave_request', '{\"read\":\"leave_request_read\",\"update\":\"leave_request_update\",\"store\":\"leave_request_store\",\"create\":\"leave_request_create\",\"approve\":\"leave_request_approve\",\"reject\":\"leave_request_reject\",\"delete\":\"leave_request_delete\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(10, 'weekend', '{\"read\":\"wekeend_read\",\"update\":\"wekeend_update\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(11, 'holiday', '{\"read\":\"holiday_read\",\"create\":\"holiday_create\",\"update\":\"holiday_update\",\"delete\":\"holiday_delete\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(12, 'schedule', '{\"read\":\"schedule_read\",\"create\":\"schedule_create\",\"update\":\"schedule_update\",\"delete\":\"schedule_delete\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(13, 'attendance', '{\"read\":\"attendance_read\",\"create\":\"attendance_create\",\"update\":\"attendance_update\",\"delete\":\"attendance_delete\",\"menu\":\"attendance_menu\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(14, 'shift', '{\"read\":\"shift_read\",\"create\":\"shift_create\",\"update\":\"shift_update\",\"delete\":\"shift_delete\",\"menu\":\"shift_menu\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(15, 'payroll', '{\"menu\":\"payroll_menu\",\"payroll_item read\":\"list_payroll_item\",\"payroll_item create\":\"create_payroll_item\",\"payroll_item store\":\"store_payroll_item\",\"payroll_item edit\":\"edit_payroll_item\",\"payroll_item update\":\"update_payroll_item\",\"payroll_item delete\":\"delete_payroll_item\",\"payroll_item view\":\"view_payroll_item\",\"payroll_item menu\":\"payroll_item_menu\",\"list_payroll_set \":\"list_payroll_set\",\"create_payroll_set\":\"create_payroll_set\",\"store_payroll_set\":\"store_payroll_set\",\"edit_payroll_set\":\"edit_payroll_set\",\"update_payroll_set\":\"update_payroll_set\",\"delete_payroll_set\":\"delete_payroll_set\",\"view_payroll_set\":\"view_payroll_set\",\"payroll_set_menu\":\"payroll_set_menu\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(16, 'payslip', '{\"menu\":\"payslip_menu\",\"salary_generate\":\"salary_generate\",\"salary_view\":\"salary_view\",\"salary_delete\":\"salary_delete\",\"salary_edit\":\"salary_edit\",\"salary_update\":\"salary_update\",\"salary_payment\":\"salary_payment\",\"payslip_list\":\"payslip_list\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(17, 'advance_type', '{\"menu\":\"advance_type_menu\",\"advance_type_create\":\"advance_type_create\",\"advance_type_store\":\"advance_type_store\",\"advance_type_edit\":\"advance_type_edit\",\"advance_type_update\":\"advance_type_update\",\"advance_type_delete\":\"advance_type_delete\",\"advance_type_view\":\"advance_type_view\",\"advance_type_list\":\"advance_type_list\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(18, 'advance_pay', '{\"menu\":\"advance_salaries_menu\",\"advance_salaries_create\":\"advance_salaries_create\",\"advance_salaries_store\":\"advance_salaries_store\",\"advance_salaries_edit\":\"advance_salaries_edit\",\"advance_salaries_update\":\"advance_salaries_update\",\"advance_salaries_delete\":\"advance_salaries_delete\",\"advance_salaries_view\":\"advance_salaries_view\",\"advance_salaries_approve\":\"advance_salaries_approve\",\"advance_salaries_list\":\"advance_salaries_list\",\"advance_salaries_pay\":\"advance_salaries_pay\",\"advance_salaries_invoice\":\"advance_salaries_invoice\",\"advance_salaries_search\":\"advance_salaries_search\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(19, 'salary', '{\"menu\":\"salary_menu\",\"salary_store\":\"salary_store\",\"salary_edit\":\"salary_edit\",\"salary_update\":\"salary_update\",\"salary_delete\":\"salary_delete\",\"salary_view\":\"salary_view\",\"salary_list\":\"salary_list\",\"salary_pay\":\"salary_pay\",\"salary_invoice\":\"salary_invoice\",\"salary_approve\":\"salary_approve\",\"salary_generate\":\"salary_generate\",\"salary_calculate\":\"salary_calculate\",\"salary_search\":\"salary_search\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(20, 'account', '{\"menu\":\"account_menu\",\"account_create\":\"account_create\",\"account_store\":\"account_store\",\"account_edit\":\"account_edit\",\"account_update\":\"account_update\",\"account_delete\":\"account_delete\",\"account_view\":\"account_view\",\"account_list\":\"account_list\",\"account_search\":\"account_search\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(21, 'deposit', '{\"menu\":\"deposit_menu\",\"deposit_create\":\"deposit_create\",\"deposit_store\":\"deposit_store\",\"deposit_edit\":\"deposit_edit\",\"deposit_update\":\"deposit_update\",\"deposit_delete\":\"deposit_delete\",\"deposit_list\":\"deposit_list\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(22, 'expense', '{\"menu\":\"expense_menu\",\"expense_create\":\"expense_create\",\"expense_store\":\"expense_store\",\"expense_edit\":\"expense_edit\",\"expense_update\":\"expense_update\",\"expense_delete\":\"expense_delete\",\"expense_list\":\"expense_list\",\"expense_approve\":\"expense_approve\",\"expense_invoice\":\"expense_invoice\",\"expense_pay\":\"expense_pay\",\"expense_view\":\"expense_view\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(23, 'deposit_category', '{\"menu\":\"deposit_category_menu\",\"deposit_category_create\":\"deposit_category_create\",\"deposit_category_store\":\"deposit_category_store\",\"deposit_category_edit\":\"deposit_category_edit\",\"deposit_category_update\":\"deposit_category_update\",\"deposit_category_delete\":\"deposit_category_delete\",\"deposit_category_list\":\"deposit_category_list\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(24, 'payment_method', '{\"menu\":\"payment_method_menu\",\"payment_method_create\":\"payment_method_create\",\"payment_method_store\":\"payment_method_store\",\"payment_method_edit\":\"payment_method_edit\",\"payment_method_update\":\"payment_method_update\",\"payment_method_delete\":\"payment_method_delete\",\"payment_method_list\":\"payment_method_list\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(25, 'transaction', '{\"menu\":\"transaction_menu\",\"transaction_create\":\"transaction_create\",\"transaction_store\":\"transaction_store\",\"transaction_edit\":\"transaction_edit\",\"transaction_update\":\"transaction_update\",\"transaction_delete\":\"transaction_delete\",\"transaction_view\":\"transaction_view\",\"transaction_list\":\"transaction_list\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(26, 'project', '{\"menu\":\"project_menu\",\"project_create\":\"project_create\",\"project_store\":\"project_store\",\"project_edit\":\"project_edit\",\"project_update\":\"project_update\",\"project_delete\":\"project_delete\",\"project_view\":\"project_view\",\"project_list\":\"project_list\",\"project_activity_view\":\"project_activity_view\",\"project_member_view\":\"project_member_view\",\"project_member_delete\":\"project_member_delete\",\"project_complete\":\"project_complete\",\"project_payment\":\"project_payment\",\"project_invoice_view\":\"project_invoice_view\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(27, 'project_discussion', '{\"project_discussion_create\":\"project_discussion_create\",\"project_discussion_store\":\"project_discussion_store\",\"project_discussion_edit\":\"project_discussion_edit\",\"project_discussion_update\":\"project_discussion_update\",\"project_discussion_delete\":\"project_discussion_delete\",\"project_discussion_view\":\"project_discussion_view\",\"project_discussion_list\":\"project_discussion_list\",\"project_discussion_comment\":\"project_discussion_comment\",\"project_discussion_reply\":\"project_discussion_reply\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(28, 'project_file', '{\"project_file_create\":\"project_file_create\",\"project_file_store\":\"project_file_store\",\"project_file_edit\":\"project_file_edit\",\"project_file_update\":\"project_file_update\",\"project_file_delete\":\"project_file_delete\",\"project_file_view\":\"project_file_view\",\"project_file_list\":\"project_file_list\",\"project_file_download\":\"project_file_download\",\"project_file_comment\":\"project_file_comment\",\"project_file_reply\":\"project_file_reply\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(29, 'project_notes', '{\"project_notes_create\":\"project_notes_create\",\"project_notes_store\":\"project_notes_store\",\"project_notes_edit\":\"project_notes_edit\",\"project_notes_update\":\"project_notes_update\",\"project_notes_delete\":\"project_notes_delete\",\"project_notes_list\":\"project_notes_list\",\"project_files_comment\":\"project_files_comment\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(30, 'task', '{\"menu\":\"task_menu\",\"task_create\":\"task_create\",\"task_store\":\"task_store\",\"task_edit\":\"task_edit\",\"task_update\":\"task_update\",\"task_delete\":\"task_delete\",\"task_view\":\"task_view\",\"task_list\":\"task_list\",\"task_activity_view\":\"task_activity_view\",\"task_assign_view\":\"task_assign_view\",\"task_assign_delete\":\"task_assign_delete\",\"task_complete\":\"task_complete\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(31, 'task_discussion', '{\"task_discussion_create\":\"task_discussion_create\",\"task_discussion_store\":\"task_discussion_store\",\"task_discussion_edit\":\"task_discussion_edit\",\"task_discussion_update\":\"task_discussion_update\",\"task_discussion_delete\":\"task_discussion_delete\",\"task_discussion_view\":\"task_discussion_view\",\"task_discussion_list\":\"task_discussion_list\",\"task_discussion_comment\":\"task_discussion_comment\",\"task_discussion_reply\":\"task_discussion_reply\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(32, 'task_file', '{\"task_file_create\":\"task_file_create\",\"task_file_store\":\"task_file_store\",\"task_file_edit\":\"task_file_edit\",\"task_file_update\":\"task_file_update\",\"task_file_delete\":\"task_file_delete\",\"task_file_view\":\"task_file_view\",\"task_file_list\":\"task_file_list\",\"task_file_download\":\"task_file_download\",\"task_file_comment\":\"task_file_comment\",\"task_file_reply\":\"task_file_reply\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(33, 'task_notes', '{\"task_notes_create\":\"task_notes_create\",\"task_notes_store\":\"task_notes_store\",\"task_notes_edit\":\"task_notes_edit\",\"task_notes_update\":\"task_notes_update\",\"task_notes_delete\":\"task_notes_delete\",\"task_notes_list\":\"task_notes_list\",\"task_files_comment\":\"task_files_comment\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(34, 'award_type', '{\"menu\":\"award_type_menu\",\"award_type_create\":\"award_type_create\",\"award_type_store\":\"award_type_store\",\"award_type_edit\":\"award_type_edit\",\"award_type_update\":\"award_type_update\",\"award_type_delete\":\"award_type_delete\",\"award_type_list\":\"award_type_list\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(35, 'award', '{\"menu\":\"award_menu\",\"award_create\":\"award_create\",\"award_store\":\"award_store\",\"award_edit\":\"award_edit\",\"award_update\":\"award_update\",\"award_delete\":\"award_delete\",\"award_view\":\"award_view\",\"award_list\":\"award_list\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(36, 'travel_type', '{\"menu\":\"travel_type_menu\",\"travel_type_create\":\"travel_type_create\",\"travel_type_store\":\"travel_type_store\",\"travel_type_edit\":\"travel_type_edit\",\"travel_type_update\":\"travel_type_update\",\"travel_type_delete\":\"travel_type_delete\",\"travel_type_list\":\"travel_type_list\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(37, 'travel', '{\"menu\":\"travel_menu\",\"travel_create\":\"travel_create\",\"travel_store\":\"travel_store\",\"travel_edit\":\"travel_edit\",\"travel_update\":\"travel_update\",\"travel_delete\":\"travel_delete\",\"travel_view\":\"travel_view\",\"travel_list\":\"travel_list\",\"travel_approve\":\"travel_approve\",\"travel_payment\":\"travel_payment\"}', '2023-04-18 01:07:40', '2023-04-18 01:07:40'),
(38, 'meeting', '{\"menu\":\"meeting_menu\",\"meeting_create\":\"meeting_create\",\"meeting_store\":\"meeting_store\",\"meeting_edit\":\"meeting_edit\",\"meeting_update\":\"meeting_update\",\"meeting_delete\":\"meeting_delete\",\"meeting_view\":\"meeting_view\",\"meeting_list\":\"meeting_list\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(39, 'appointment', '{\"appointment_menu\":\"appointment_menu\",\"appointment_read\":\"appointment_read\",\"appointment_create\":\"appointment_create\",\"appointment_approve\":\"appointment_approve\",\"appointment_reject\":\"appointment_reject\",\"appointment_delete\":\"appointment_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(40, 'performance', '{\"menu\":\"performance_menu\",\"settings\":\"performance_settings\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(41, 'performance_indicator', '{\"menu\":\"performance_indicator_menu\",\"performance_indicator_create\":\"performance_indicator_create\",\"performance_indicator_store\":\"performance_indicator_store\",\"performance_indicator_edit\":\"performance_indicator_edit\",\"performance_indicator_update\":\"performance_indicator_update\",\"performance_indicator_delete\":\"performance_indicator_delete\",\"performance_indicator_list\":\"performance_indicator_list\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(42, 'performance_appraisal', '{\"menu\":\"performance_appraisal_menu\",\"performance_appraisal_create\":\"performance_appraisal_create\",\"performance_appraisal_store\":\"performance_appraisal_store\",\"performance_appraisal_edit\":\"performance_appraisal_edit\",\"performance_appraisal_update\":\"performance_appraisal_update\",\"performance_appraisal_delete\":\"performance_appraisal_delete\",\"performance_appraisal_list\":\"performance_appraisal_list\",\"performance_appraisal_view\":\"performance_appraisal_view\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(43, 'performance_goal_type', '{\"menu\":\"performance_goal_type_menu\",\"performance_goal_type_create\":\"performance_goal_type_create\",\"performance_goal_type_store\":\"performance_goal_type_store\",\"performance_goal_type_edit\":\"performance_goal_type_edit\",\"performance_goal_type_update\":\"performance_goal_type_update\",\"performance_goal_type_delete\":\"performance_goal_type_delete\",\"performance_goal_type_list\":\"performance_goal_type_list\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(44, 'performance_goal', '{\"menu\":\"performance_goal_menu\",\"performance_goal_create\":\"performance_goal_create\",\"performance_goal_store\":\"performance_goal_store\",\"performance_goal_edit\":\"performance_goal_edit\",\"performance_goal_update\":\"performance_goal_update\",\"performance_goal_delete\":\"performance_goal_delete\",\"performance_goal_view\":\"performance_goal_view\",\"performance_goal_list\":\"performance_goal_list\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(45, 'performance_competence_type', '{\"menu\":\"performance_competence_type_menu\",\"performance_competence_type_create\":\"performance_competence_type_create\",\"performance_competence_type_store\":\"performance_competence_type_store\",\"performance_competence_type_edit\":\"performance_competence_type_edit\",\"performance_competence_type_update\":\"performance_competence_type_update\",\"performance_competence_type_delete\":\"performance_competence_type_delete\",\"performance_competence_type_list\":\"performance_competence_type_list\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(46, 'performance_competence', '{\"menu\":\"performance_competence_menu\",\"performance_competence_create\":\"performance_competence_create\",\"performance_competence_store\":\"performance_competence_store\",\"performance_competence_edit\":\"performance_competence_edit\",\"performance_competence_update\":\"performance_competence_update\",\"performance_competence_delete\":\"performance_competence_delete\",\"performance_competence_list\":\"performance_competence_list\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(47, 'report', '{\"attendance_report\":\"attendance_report_read\",\"live_tracking_read\":\"live_tracking_read\",\"menu\":\"report_menu\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(48, 'leave_settings', '{\"read\":\"leave_settings_read\",\"update\":\"leave_settings_update\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(49, 'ip', '{\"read\":\"ip_read\",\"create\":\"ip_create\",\"update\":\"ip_update\",\"delete\":\"ip_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(50, 'company_setup', '{\"menu\":\"company_setup_menu\",\"activation_read\":\"company_setup_activation\",\"activation_update\":\"company_setup_activation_update\",\"configuration_read\":\"company_setup_configuration\",\"configuration_update\":\"company_setup_configuration_update\",\"ip_whitelist_read\":\"company_setup_ip_whitelist\",\"location_read\":\"company_setup_location\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(51, 'location', '{\"location_create\":\"location_create\",\"location_store\":\"location_store\",\"location_edit\":\"location_edit\",\"location_update\":\"location_update\",\"location_delete\":\"location_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(52, 'api_setup', '{\"read\":\"locationApi\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(53, 'claim', '{\"read\":\"claim_read\",\"create\":\"claim_create\",\"update\":\"claim_update\",\"delete\":\"claim_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(54, 'payment', '{\"read\":\"payment_read\",\"create\":\"payment_create\",\"update\":\"payment_update\",\"delete\":\"payment_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(55, 'visit', '{\"menu\":\"visit_menu\",\"read\":\"visit_read\",\"update\":\"visit_update\",\"view\":\"visit_view\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(56, 'app_settings', '{\"menu\":\"app_settings_menu\",\"update\":\"app_settings_update\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(57, 'web_setup', '{\"menu\":\"web_setup_menu\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(58, 'content', '{\"menu\":\"content_menu\",\"read\":\"content_read\",\"create\":\"content_create\",\"update\":\"content_update\",\"delete\":\"content_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(59, 'menu', '{\"menu\":\"menu\",\"create\":\"menu_create\",\"menu_store\":\"menu_store\",\"menu_edit\":\"menu_edit\",\"update\":\"menu_update\",\"delete\":\"menu_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(60, 'service', '{\"menu\":\"service_menu\",\"create\":\"service_create\",\"service_store\":\"service_store\",\"edit\":\"portfolio_edit\",\"update\":\"service_update\",\"delete\":\"service_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(61, 'portfolio', '{\"menu\":\"portfolio_menu\",\"create\":\"portfolio_create\",\"portfolio_store\":\"portfolio_store\",\"edit\":\"portfolio_edit\",\"update\":\"portfolio_update\",\"delete\":\"portfolio_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(62, 'contact', '{\"menu\":\"contact_menu\",\"read\":\"contact_read\",\"create\":\"contact_create\",\"update\":\"contact_update\",\"delete\":\"contact_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(63, 'language', '{\"menu\":\"language_menu\",\"create\":\"language_create\",\"edit\":\"language_edit\",\"update\":\"language_update\",\"delete\":\"language_delete\",\"make_default\":\"make_default\",\"setup_language\":\"setup_language\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(64, 'team_member', '{\"menu\":\"team_member_menu\",\"read\":\"team_member_read\",\"create\":\"team_member_create\",\"team_member_store\":\"team_member_store\",\"team_member_edit\":\"team_member_edit\",\"update\":\"team_member_update\",\"delete\":\"team_member_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(65, 'support', '{\"support_menu\":\"support_menu\",\"support_read\":\"support_read\",\"support_create\":\"support_create\",\"support_reply\":\"support_reply\",\"support_delete\":\"support_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(66, 'lead', '{\"menu\":\"lead_menu\",\"create\":\"lead_create\",\"store\":\"lead_store\",\"edit\":\"lead_edit\",\"update\":\"lead_update\",\"delete\":\"lead_delete\",\"view\":\"lead_view\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(67, 'lead_detail', '{\"profile\":\"lead_detail_profile\",\"attachment\":\"lead_detail_attachment\",\"email\":\"lead_detail_email\",\"call\":\"lead_detail_call\",\"note\":\"lead_detail_note\",\"task\":\"lead_detail_task\",\"reminder\":\"lead_detail_reminder\",\"tag\":\"lead_detail_tag\",\"activities\":\"lead_detail_activities\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(68, 'lead_type', '{\"menu\":\"lead_type_menu\",\"create\":\"lead_type_create\",\"store\":\"lead_type_store\",\"edit\":\"lead_type_edit\",\"update\":\"lead_type_update\",\"delete\":\"lead_type_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(69, 'lead_source', '{\"menu\":\"lead_source_menu\",\"create\":\"lead_source_create\",\"store\":\"lead_source_store\",\"edit\":\"lead_source_edit\",\"update\":\"lead_source_update\",\"delete\":\"lead_source_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(70, 'lead_status', '{\"menu\":\"lead_status_menu\",\"create\":\"lead_status_create\",\"store\":\"lead_status_store\",\"edit\":\"lead_status_edit\",\"update\":\"lead_status_update\",\"delete\":\"lead_status_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(71, 'sales', '{\"menu\":\"sales_menu\",\"create\":\"sales_create\",\"store\":\"sales_store\",\"edit\":\"sales_edit\",\"update\":\"sales_update\",\"delete\":\"sales_delete\",\"add_payment\":\"sales_add_payment\",\"view_payment\":\"sales_view_payment\",\"delivery\":\"sales_delivery\",\"invoice\":\"sales_invoice\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(72, 'sales_products', '{\"menu\":\"sales_products_menu\",\"create\":\"sales_products_create\",\"store\":\"sales_products_store\",\"edit\":\"sales_products_edit\",\"update\":\"sales_products_update\",\"delete\":\"sales_products_delete\",\"view\":\"sales_products_view\",\"history\":\"sales_products_history\",\"import\":\"sales_products_import\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(73, 'sales_product_category', '{\"menu\":\"sales_product_category_menu\",\"create\":\"sales_product_category_create\",\"store\":\"sales_product_category_store\",\"edit\":\"sales_product_category_edit\",\"update\":\"sales_product_category_update\",\"delete\":\"sales_product_category_delete\",\"import\":\"sales_product_category_import\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(74, 'sales_product_brand', '{\"menu\":\"sales_product_brand_menu\",\"create\":\"sales_product_brand_create\",\"store\":\"sales_product_brand_store\",\"edit\":\"sales_product_brand_edit\",\"update\":\"sales_product_brand_update\",\"delete\":\"sales_product_brand_delete\",\"import\":\"sales_product_brand_import\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(75, 'sales_product_unit', '{\"menu\":\"sales_product_unit_menu\",\"create\":\"sales_product_unit_create\",\"store\":\"sales_product_unit_store\",\"edit\":\"sales_product_unit_edit\",\"update\":\"sales_product_unit_update\",\"delete\":\"sales_product_unit_delete\",\"import\":\"sales_product_unit_import\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(76, 'sales_product_tax', '{\"menu\":\"sales_product_tax_menu\",\"create\":\"sales_product_tax_create\",\"store\":\"sales_product_tax_store\",\"edit\":\"sales_product_tax_edit\",\"update\":\"sales_product_tax_update\",\"delete\":\"sales_product_tax_delete\",\"import\":\"sales_product_tax_import\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(77, 'sales_product_warehouse', '{\"menu\":\"sales_product_warehouse_menu\",\"create\":\"sales_product_warehouse_create\",\"store\":\"sales_product_warehouse_store\",\"edit\":\"sales_product_warehouse_edit\",\"update\":\"sales_product_warehouse_update\",\"delete\":\"sales_product_warehouse_delete\",\"import\":\"sales_product_warehouse_import\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(78, 'sales_product_supplier', '{\"menu\":\"sales_product_supplier_menu\",\"create\":\"sales_product_supplier_create\",\"store\":\"sales_product_supplier_store\",\"edit\":\"sales_product_supplier_edit\",\"update\":\"sales_product_supplier_update\",\"delete\":\"sales_product_supplier_delete\",\"import\":\"sales_product_supplier_import\",\"clear_due\":\"sales_product_supplier_clear_due\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(79, 'sales_product_barcode', '{\"menu\":\"sales_product_barcode_menu\",\"barcode\":\"sales_product_barcode_generate\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(80, 'sales_product_stock', '{\"menu\":\"sales_product_stock_menu\",\"create\":\"sales_product_stock_create\",\"store\":\"sales_product_stock_store\",\"edit\":\"sales_product_stock_edit\",\"update\":\"sales_product_stock_update\",\"delete\":\"sales_product_stock_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(81, 'sales_product_stock_adjustment', '{\"menu\":\"sales_product_stock_adjustment_menu\",\"create\":\"sales_product_stock_adjustment_create\",\"store\":\"sales_product_stock_adjustment_store\",\"edit\":\"sales_product_stock_adjustment_edit\",\"update\":\"sales_product_stock_adjustment_update\",\"delete\":\"sales_product_stock_adjustment_delete\",\"import\":\"sales_product_stock_adjustment_import\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(82, 'sales_purchase', '{\"menu\":\"sales_purchase_menu\",\"create\":\"sales_purchase_create\",\"store\":\"sales_purchase_store\",\"edit\":\"sales_purchase_edit\",\"update\":\"sales_purchase_update\",\"delete\":\"sales_purchase_delete\",\"add_payment\":\"sales_purchase_add_payment\",\"view_payment\":\"sales_purchase_view_payment\",\"import\":\"sales_purchase_import\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(83, 'sales_pos', '{\"menu\":\"sales_pos_menu\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(84, 'sales_gift_card', '{\"menu\":\"sales_giftcard_menu\",\"create\":\"sales_giftcard_create\",\"store\":\"sales_giftcard_store\",\"edit\":\"sales_giftcard_edit\",\"update\":\"sales_giftcard_update\",\"delete\":\"sales_giftcard_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(85, 'sales_coupon', '{\"menu\":\"sales_coupon_menu\",\"create\":\"sales_coupon_create\",\"store\":\"sales_coupon_store\",\"edit\":\"sales_coupon_edit\",\"update\":\"sales_coupon_update\",\"delete\":\"sales_coupon_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(86, 'sales_delivery', '{\"menu\":\"sales_delivery_menu\",\"store\":\"sales_delivery_store\",\"edit\":\"sales_delivery_edit\",\"update\":\"sales_delivery_update\",\"delete\":\"sales_delivery_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(87, 'sales_expense', '{\"menu\":\"sales_expense_menu\",\"create\":\"sales_expense_create\",\"store\":\"sales_expense_store\",\"edit\":\"sales_expense_edit\",\"update\":\"sales_expense_update\",\"delete\":\"sales_expense_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(88, 'sales_expense_category', '{\"menu\":\"sales_expense_category_menu\",\"create\":\"sales_expense_category_create\",\"store\":\"sales_expense_category_store\",\"edit\":\"sales_expense_category_edit\",\"update\":\"sales_expense_category_update\",\"delete\":\"sales_expense_category_delete\",\"import\":\"sales_expense_category_import\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(89, 'sales_quotation', '{\"menu\":\"sales_quotation_menu\",\"create\":\"sales_quotation_create\",\"store\":\"sales_quotation_store\",\"edit\":\"sales_quotation_edit\",\"update\":\"sales_quotation_update\",\"delete\":\"sales_quotation_delete\",\"sale_create\":\"sales_quotation_sale_create\",\"purchase_create\":\"sales_quotation_purchase_create\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(90, 'sales_return', '{\"menu\":\"sales_return_menu\",\"read\":\"sales_return_read\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(91, 'sales_return_purchase_menu', '{\"menu\":\"sales_return_purchase_menu\",\"create\":\"sales_return_purchase_create\",\"store\":\"sales_return_purchase_store\",\"edit\":\"sales_return_purchase_edit\",\"update\":\"sales_return_purchase_update\",\"delete\":\"sales_return_purchase_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(92, 'sales_return_sale_menu', '{\"menu\":\"sales_return_sale_menu\",\"create\":\"sales_return_sale_create\",\"store\":\"sales_return_sale_store\",\"edit\":\"sales_return_sale_edit\",\"update\":\"sales_return_sale_update\",\"delete\":\"sales_return_sale_delete\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(93, 'sales_transfer', '{\"menu\":\"sales_transfer_menu\",\"create\":\"sales_transfer_create\",\"store\":\"sales_transfer_store\",\"edit\":\"sales_transfer_edit\",\"update\":\"sales_transfer_update\",\"delete\":\"sales_transfer_delete\",\"view\":\"sales_transfer_view\"}', '2023-04-18 01:07:41', '2023-04-18 01:07:41');

-- --------------------------------------------------------

--
-- Table structure for table `portfolios`
--

CREATE TABLE `portfolios` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `portfolios`
--

INSERT INTO `portfolios` (`id`, `title`, `slug`, `description`, `url`, `attachment`, `user_id`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 'web & software dev', 'web-software-dev', 'We also provide Enterprise Web Applications Development, Cross-platform Apps, HRM,CRM, Multivendor E-commerce etc', NULL, 21, 2, 1, '2023-04-18 01:07:53', NULL),
(2, 'UX UI designers', 'ux-ui-designers', 'We also provide Most trending, Eye catching UI for our clients.', NULL, 22, 2, 1, '2023-04-18 01:07:53', NULL),
(3, 'project managers', 'project-managers', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', NULL, 23, 2, 1, '2023-04-18 01:07:53', NULL),
(4, 'Java dev', 'java-dev', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', NULL, 24, 2, 1, '2023-04-18 01:07:53', NULL),
(5, 'Mechanical support', 'mechanical-support', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', NULL, 25, 2, 1, '2023-04-18 01:07:53', NULL),
(6, 'Scrum master', 'scrum-master', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', NULL, 26, 2, 1, '2023-04-18 01:07:53', NULL),
(7, 'Finance Experts', 'finance-experts', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', NULL, 27, 2, 1, '2023-04-18 01:07:53', NULL),
(8, 'Ride Share', 'ride-share', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', NULL, 28, 2, 1, '2023-04-18 01:07:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_purchases`
--

CREATE TABLE `product_purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `stock_payment_history_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `invoice_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `batch_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` double(16,2) NOT NULL,
  `tax` double(16,2) NOT NULL,
  `grand_total` double(16,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_purchase_histories`
--

CREATE TABLE `product_purchase_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `product_purchase_id` bigint UNSIGNED NOT NULL,
  `batch_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_unit_id` bigint UNSIGNED NOT NULL,
  `quantity` bigint NOT NULL,
  `price` double(16,2) NOT NULL,
  `discount` double(16,2) NOT NULL,
  `total` double(16,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_units`
--

CREATE TABLE `product_units` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment_file_id` bigint UNSIGNED DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `progress_from_tasks` int DEFAULT '1',
  `progress` int DEFAULT '0',
  `billing_type` enum('hourly','fixed') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `per_rate` double(16,2) DEFAULT NULL,
  `total_rate` double(16,2) DEFAULT NULL,
  `estimated_hour` double(16,2) DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '24',
  `priority` bigint UNSIGNED NOT NULL DEFAULT '24',
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `payment` bigint UNSIGNED NOT NULL DEFAULT '9',
  `amount` double(16,2) DEFAULT NULL,
  `paid` double(16,2) NOT NULL DEFAULT '0.00',
  `due` double(16,2) NOT NULL DEFAULT '0.00',
  `created_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `notify_all_users` tinyint NOT NULL DEFAULT '0' COMMENT '0=no,1=yes',
  `notify_all_users_email` tinyint NOT NULL DEFAULT '0' COMMENT '0=no,1=yes',
  `goal_id` bigint UNSIGNED DEFAULT NULL,
  `avatar_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_activities`
--

CREATE TABLE `project_activities` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_files`
--

CREATE TABLE `project_files` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_to_customer` bigint UNSIGNED NOT NULL DEFAULT '22',
  `project_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `attachment` bigint UNSIGNED DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_file_comments`
--

CREATE TABLE `project_file_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `comment_id` bigint UNSIGNED DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_to_customer` tinyint NOT NULL DEFAULT '1' COMMENT '0=no,1=yes',
  `project_file_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `attachment` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_membars`
--

CREATE TABLE `project_membars` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `added_by` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_payments`
--

CREATE TABLE `project_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `amount` double(16,2) NOT NULL,
  `due_amount` double(16,2) DEFAULT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED DEFAULT NULL,
  `payment_method_id` bigint UNSIGNED DEFAULT NULL,
  `paid_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `updated_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `payment_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

CREATE TABLE `proposals` (
  `id` bigint UNSIGNED NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `addedfrom` int NOT NULL,
  `datecreated` datetime NOT NULL,
  `total` decimal(15,2) DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `total_tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `adjustment` decimal(15,2) DEFAULT NULL,
  `discount_percent` decimal(15,2) NOT NULL,
  `discount_total` decimal(15,2) NOT NULL,
  `discount_type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show_quantity_as` int NOT NULL DEFAULT '1',
  `currency` int NOT NULL,
  `open_till` date DEFAULT NULL,
  `date` date NOT NULL,
  `rel_id` int DEFAULT NULL,
  `rel_type` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned` int DEFAULT NULL,
  `hash` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proposal_to` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_id` int DEFAULT NULL,
  `country` int NOT NULL DEFAULT '0',
  `zip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allow_comments` tinyint(1) NOT NULL DEFAULT '1',
  `status` int NOT NULL,
  `estimate_id` int DEFAULT NULL,
  `invoice_id` int DEFAULT NULL,
  `date_converted` datetime DEFAULT NULL,
  `pipeline_order` int NOT NULL DEFAULT '1',
  `is_expiry_notified` int NOT NULL DEFAULT '0',
  `acceptance_firstname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acceptance_lastname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acceptance_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acceptance_date` datetime DEFAULT NULL,
  `acceptance_ip` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_link` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposal_comments`
--

CREATE TABLE `proposal_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `proposal_id` int NOT NULL,
  `staff_id` int NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `updated_by` int NOT NULL DEFAULT '0',
  `deleted_by` int NOT NULL DEFAULT '0',
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `is_active` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permissions` longtext COLLATE utf8mb4_unicode_ci,
  `status_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `company_id`, `slug`, `permissions`, `status_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'superadmin', 1, 'superadmin', '[\"company_read\",\"company_create\",\"company_update\",\"company_delete\",\"user_banned\",\"user_unbanned\",\"general_settings_read\",\"general_settings_update\",\"email_settings_read\",\"email_settings_update\",\"storage_settings_update\",\"user_read\",\"user_edit\",\"user_update\",\"content_update\",\"subscriptions_read\"]', 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41', NULL),
(2, 'admin', 1, 'admin', '[\"team_menu\",\"team_list\",\"team_create\",\"team_update\",\"team_edit\",\"team_delete\",\"team_member_view\",\"team_member_create\",\"team_member_edit\",\"team_member_delete\",\"team_member_assign\",\"team_member_unassign\",\"dashboard\",\"hr_menu\",\"designation_read\",\"designation_create\",\"designation_update\",\"designation_delete\",\"shift_read\",\"shift_create\",\"shift_update\",\"shift_delete\",\"department_read\",\"department_create\",\"department_update\",\"department_delete\",\"user_menu\",\"user_read\",\"profile_view\",\"user_create\",\"user_edit\",\"user_update\",\"user_delete\",\"user_banned\",\"user_unbanned\",\"make_hr\",\"user_permission\",\"profile_image_view\",\"phonebook_profile\",\"support_ticket_profile\",\"advance_profile\",\"commission_profile\",\"salary_profile\",\"project_profile\",\"task_profile\",\"award_profile\",\"travel_profile\",\"attendance_profile\",\"appointment_profile\",\"visit_profile\",\"leave_request_profile\",\"notice_profile\",\"role_read\",\"role_create\",\"role_update\",\"role_delete\",\"leave_menu\",\"leave_type_read\",\"leave_type_create\",\"leave_type_update\",\"leave_type_delete\",\"leave_assign_read\",\"leave_assign_create\",\"leave_assign_update\",\"leave_assign_delete\",\"leave_request_read\",\"leave_request_create\",\"leave_request_store\",\"leave_request_update\",\"leave_request_approve\",\"leave_request_reject\",\"leave_request_delete\",\"appointment_read\",\"appointment_menu\",\"appointment_create\",\"appointment_approve\",\"appointment_reject\",\"appointment_delete\",\"weekend_read\",\"weekend_update\",\"attendance_update\",\"holiday_read\",\"holiday_create\",\"holiday_update\",\"holiday_delete\",\"schedule_read\",\"schedule_create\",\"schedule_update\",\"schedule_delete\",\"attendance_menu\",\"attendance_read\",\"attendance_create\",\"attendance_update\",\"attendance_delete\",\"generate_qr_code\",\"leave_settings_read\",\"leave_settings_update\",\"company_settings_read\",\"company_settings_update\",\"locationApi\",\"company_setup_menu\",\"company_setup_activation\",\"company_setup_configuration\",\"company_setup_ip_whitelist\",\"company_setup_location\",\"location_create\",\"location_store\",\"location_edit\",\"location_update\",\"location_delete\",\"ip_read\",\"ip_create\",\"ip_update\",\"ip_delete\",\"attendance_report_read\",\"live_tracking_read\",\"report_menu\",\"report\",\"claim_read\",\"claim_create\",\"claim_update\",\"claim_delete\",\"payment_read\",\"payment_create\",\"payment_update\",\"payment_delete\",\"visit_menu\",\"visit_read\",\"visit_view\",\"visit_update\",\"payroll_menu\",\"list_payroll_item\",\"create_payroll_item\",\"store_payroll_item\",\"update_payroll_item\",\"delete_payroll_item\",\"view_payroll_item\",\"payroll_item_menu\",\"list_payroll_set\",\"create_payroll_set\",\"store_payroll_set\",\"update_payroll_set\",\"delete_payroll_set\",\"view_payroll_set\",\"edit_payroll_set\",\"payroll_set_menu\",\"advance_salaries_menu\",\"advance_salaries_create\",\"advance_salaries_store\",\"advance_salaries_edit\",\"advance_salaries_update\",\"advance_salaries_delete\",\"advance_salaries_view\",\"advance_salaries_approve\",\"advance_salaries_list\",\"advance_salaries_pay\",\"advance_salaries_invoice\",\"advance_salaries_search\",\"payslip_menu\",\"salary_generate\",\"salary_view\",\"salary_delete\",\"salary_edit\",\"salary_update\",\"salary_payment\",\"payslip_list\",\"advance_type_menu\",\"advance_type_create\",\"advance_type_store\",\"advance_type_edit\",\"advance_type_update\",\"advance_type_delete\",\"advance_type_view\",\"advance_type_list\",\"salary_menu\",\"salary_store\",\"salary_edit\",\"salary_update\",\"salary_delete\",\"salary_view\",\"salary_list\",\"salary_search\",\"salary_pay\",\"salary_invoice\",\"salary_approve\",\"salary_generate\",\"salary_calculate\",\"account_menu\",\"account_create\",\"account_store\",\"account_edit\",\"account_update\",\"account_delete\",\"account_view\",\"account_list\",\"account_search\",\"deposit_menu\",\"deposit_create\",\"deposit_store\",\"deposit_edit\",\"deposit_update\",\"deposit_delete\",\"deposit_list\",\"expense_menu\",\"expense_create\",\"expense_store\",\"expense_edit\",\"expense_update\",\"expense_delete\",\"expense_list\",\"expense_view\",\"expense_approve\",\"expense_invoice\",\"expense_pay\",\"transaction_menu\",\"transaction_create\",\"transaction_store\",\"transaction_edit\",\"transaction_update\",\"transaction_delete\",\"transaction_view\",\"transaction_list\",\"deposit_category_menu\",\"deposit_category_create\",\"deposit_category_store\",\"deposit_category_edit\",\"deposit_category_update\",\"deposit_category_delete\",\"deposit_category_list\",\"payment_method_menu\",\"payment_method_create\",\"payment_method_store\",\"payment_method_edit\",\"payment_method_update\",\"payment_method_delete\",\"payment_method_list\",\"project_menu\",\"project_create\",\"project_store\",\"project_edit\",\"project_update\",\"project_delete\",\"project_view\",\"project_list\",\"project_activity_view\",\"project_member_view\",\"project_member_delete\",\"project_complete\",\"project_payment\",\"project_invoice_view\",\"project_discussion_create\",\"project_discussion_store\",\"project_discussion_edit\",\"project_discussion_update\",\"project_discussion_delete\",\"project_discussion_view\",\"project_discussion_list\",\"project_discussion_comment\",\"project_discussion_reply\",\"project_file_create\",\"project_file_store\",\"project_file_edit\",\"project_file_update\",\"project_file_delete\",\"project_file_view\",\"project_file_list\",\"project_file_download\",\"project_file_comment\",\"project_file_reply\",\"project_notes_create\",\"project_notes_store\",\"project_notes_edit\",\"project_notes_update\",\"project_notes_delete\",\"project_notes_list\",\"client_menu\",\"client_create\",\"client_store\",\"client_edit\",\"client_update\",\"client_delete\",\"client_view\",\"client_list\",\"task_menu\",\"task_create\",\"task_store\",\"task_edit\",\"task_update\",\"task_delete\",\"task_view\",\"task_list\",\"task_activity_view\",\"task_assign_view\",\"task_assign_delete\",\"task_complete\",\"task_discussion_create\",\"task_discussion_store\",\"task_discussion_edit\",\"task_discussion_update\",\"task_discussion_delete\",\"task_discussion_view\",\"task_discussion_list\",\"task_discussion_comment\",\"task_discussion_reply\",\"task_file_create\",\"task_file_store\",\"task_file_edit\",\"task_file_update\",\"task_file_delete\",\"task_file_view\",\"task_file_list\",\"task_file_download\",\"task_file_comment\",\"task_file_reply\",\"task_notes_create\",\"task_notes_store\",\"task_notes_edit\",\"task_notes_update\",\"task_notes_delete\",\"task_notes_list\",\"task_files_comment\",\"award_type_menu\",\"award_type_create\",\"award_type_store\",\"award_type_edit\",\"award_type_update\",\"award_type_delete\",\"award_type_view\",\"award_type_list\",\"award_menu\",\"award_create\",\"award_store\",\"award_edit\",\"award_update\",\"award_delete\",\"award_list\",\"travel_type_menu\",\"travel_type_create\",\"travel_type_store\",\"travel_type_edit\",\"travel_type_update\",\"travel_type_delete\",\"travel_type_view\",\"travel_type_list\",\"travel_menu\",\"travel_create\",\"travel_store\",\"travel_edit\",\"travel_update\",\"travel_delete\",\"travel_list\",\"travel_approve\",\"travel_payment\",\"meeting_menu\",\"meeting_create\",\"meeting_store\",\"meeting_edit\",\"meeting_update\",\"meeting_delete\",\"meeting_list\",\"performance_menu\",\"performance_settings\",\"performance_indicator_menu\",\"performance_indicator_list\",\"performance_indicator_create\",\"performance_indicator_store\",\"performance_indicator_edit\",\"performance_indicator_update\",\"performance_indicator_delete\",\"performance_appraisal_menu\",\"performance_appraisal_create\",\"performance_appraisal_store\",\"performance_appraisal_edit\",\"performance_appraisal_update\",\"performance_appraisal_delete\",\"performance_appraisal_list\",\"performance_appraisal_view\",\"performance_goal_type_menu\",\"performance_goal_type_create\",\"performance_goal_type_store\",\"performance_goal_type_edit\",\"performance_goal_type_update\",\"performance_goal_type_delete\",\"performance_goal_type_list\",\"performance_goal_menu\",\"performance_goal_create\",\"performance_goal_store\",\"performance_goal_edit\",\"performance_goal_update\",\"performance_goal_delete\",\"performance_goal_view\",\"performance_goal_list\",\"performance_competence_type_list\",\"performance_competence_type_menu\",\"performance_competence_type_create\",\"performance_competence_type_store\",\"performance_competence_type_edit\",\"performance_competence_type_update\",\"performance_competence_type_delete\",\"performance_competence_type_view\",\"performance_competence_menu\",\"performance_competence_create\",\"performance_competence_store\",\"performance_competence_edit\",\"performance_competence_update\",\"performance_competence_delete\",\"performance_competence_view\",\"performance_competence_list\",\"app_settings_menu\",\"app_settings_update\",\"language_menu\",\"make_default\",\"sales_menu\",\"sales_products_menu\",\"sales_products_create\",\"sales_products_store\",\"sales_products_edit\",\"sales_products_update\",\"sales_products_delete\",\"sales_products_view\",\"sales_products_history\",\"sales_products_import\",\"sales_product_category_menu\",\"sales_product_category_create\",\"sales_product_category_store\",\"sales_product_category_edit\",\"sales_product_category_update\",\"sales_product_category_delete\",\"sales_product_category_import\",\"sales_product_brand_menu\",\"sales_product_brand_create\",\"sales_product_brand_store\",\"sales_product_brand_edit\",\"sales_product_brand_update\",\"sales_product_brand_delete\",\"sales_product_brand_import\",\"sales_product_unit_menu\",\"sales_product_unit_create\",\"sales_product_unit_store\",\"sales_product_unit_edit\",\"sales_product_unit_update\",\"sales_product_unit_delete\",\"sales_product_unit_import\",\"sales_product_tax_menu\",\"sales_product_tax_create\",\"sales_product_tax_store\",\"sales_product_tax_edit\",\"sales_product_tax_update\",\"sales_product_tax_delete\",\"sales_product_tax_import\",\"sales_product_warehouse_menu\",\"sales_product_warehouse_create\",\"sales_product_warehouse_store\",\"sales_product_warehouse_edit\",\"sales_product_warehouse_update\",\"sales_product_warehouse_delete\",\"sales_product_warehouse_import\",\"sales_product_supplier_menu\",\"sales_product_supplier_create\",\"sales_product_supplier_store\",\"sales_product_supplier_edit\",\"sales_product_supplier_update\",\"sales_product_supplier_delete\",\"sales_product_supplier_import\",\"sales_product_supplier_clear_due\",\"sales_product_barcode_menu\",\"sales_product_barcode_generate\",\"sales_product_stock_adjustment_menu\",\"sales_product_stock_adjustment_create\",\"sales_product_stock_adjustment_store\",\"sales_product_stock_adjustment_edit\",\"sales_product_stock_adjustment_update\",\"sales_product_stock_adjustment_delete\",\"sales_product_stock_count_menu\",\"sales_product_stock_count_create\",\"sales_product_stock_count_store\",\"sales_product_stock_count_edit\",\"sales_product_stock_count_update\",\"sales_product_stock_count_delete\",\"sales_purchase_menu\",\"sales_purchase_create\",\"sales_purchase_store\",\"sales_purchase_edit\",\"sales_purchase_update\",\"sales_purchase_delete\",\"sales_purchase_add_payment\",\"sales_purchase_view_payment\",\"sales_purchase_import\",\"sales_create\",\"sales_store\",\"sales_edit\",\"sales_update\",\"sales_delete\",\"sales_add_payment\",\"sales_view_payment\",\"sales_delivery\",\"sales_invoice\",\"sales_pos_menu\",\"sales_giftcard_menu\",\"sales_giftcard_create\",\"sales_giftcard_store\",\"sales_giftcard_edit\",\"sales_giftcard_update\",\"sales_giftcard_delete\",\"sales_coupon_menu\",\"sales_coupon_create\",\"sales_coupon_store\",\"sales_coupon_edit\",\"sales_coupon_update\",\"sales_coupon_delete\",\"sales_delivery_menu\",\"sales_delivery_store\",\"sales_delivery_edit\",\"sales_delivery_update\",\"sales_delivery_delete\",\"sales_expense_category_menu\",\"sales_expense_category_create\",\"sales_expense_category_store\",\"sales_expense_category_edit\",\"sales_expense_category_update\",\"sales_expense_category_delete\",\"sales_expense_category_import\",\"sales_expense_menu\",\"sales_expense_create\",\"sales_expense_store\",\"sales_expense_edit\",\"sales_expense_update\",\"sales_expense_delete\",\"sales_quotation_menu\",\"sales_quotation_create\",\"sales_quotation_store\",\"sales_quotation_edit\",\"sales_quotation_update\",\"sales_quotation_delete\",\"sales_quotation_sale_create\",\"sales_quotation_purchase_create\",\"sales_transfer_menu\",\"sales_transfer_create\",\"sales_transfer_store\",\"sales_transfer_edit\",\"sales_transfer_update\",\"sales_transfer_delete\",\"sales_transfer_view\",\"sales_return_menu\",\"sales_return_sale_menu\",\"sales_return_sale_create\",\"sales_return_sale_store\",\"sales_return_sale_edit\",\"sales_return_sale_update\",\"sales_return_sale_delete\",\"sales_return_purchase_menu\",\"sales_return_purchase_create\",\"sales_return_purchase_store\",\"sales_return_purchase_edit\",\"sales_return_purchase_update\",\"sales_return_purchase_delete\",\"lead_type_menu\",\"lead_type_create\",\"lead_type_store\",\"lead_type_edit\",\"lead_type_update\",\"lead_type_delete\",\"lead_source_menu\",\"lead_source_create\",\"lead_source_store\",\"lead_source_edit\",\"lead_source_update\",\"lead_source_delete\",\"lead_status_menu\",\"lead_status_create\",\"lead_status_store\",\"lead_status_edit\",\"lead_status_update\",\"lead_status_delete\",\"lead_menu\",\"lead_create\",\"lead_store\",\"lead_edit\",\"lead_update\",\"lead_delete\",\"lead_view\",\"lead_detail_profile\",\"lead_detail_attachment\",\"lead_detail_email\",\"lead_detail_call\",\"lead_detail_note\",\"lead_detail_task\",\"lead_detail_reminder\",\"lead_detail_tag\",\"lead_detail_activities\",\"general_settings_read\",\"general_settings_update\",\"language_create\",\"language_store\",\"language_edit\",\"language_update\",\"language_delete\",\"setup_language\",\"content_menu\",\"content_create\",\"content_store\",\"content_edit\",\"content_update\",\"content_delete\",\"contact_menu\",\"contact_create\",\"contact_store\",\"contact_edit\",\"contact_update\",\"contact_delete\",\"announcement_menu\",\"notice_menu\",\"notice_list\",\"notice_create\",\"notice_update\",\"notice_edit\",\"notice_delete\",\"send_sms_menu\",\"send_sms_list\",\"send_sms_create\",\"send_sms_update\",\"send_sms_edit\",\"send_sms_delete\",\"send_email_menu\",\"send_email_list\",\"send_email_create\",\"send_email_update\",\"send_email_edit\",\"send_email_delete\",\"send_notification_menu\",\"send_notification_list\",\"send_notification_create\",\"send_notification_update\",\"send_notification_edit\",\"send_notification_delete\",\"announcement_menu\",\"notice_menu\",\"notice_list\",\"notice_create\",\"notice_update\",\"notice_edit\",\"notice_delete\",\"send_sms_menu\",\"send_sms_list\",\"send_sms_create\",\"send_sms_update\",\"send_sms_edit\",\"send_sms_delete\",\"send_email_menu\",\"send_email_list\",\"send_email_create\",\"send_email_update\",\"send_email_edit\",\"send_email_delete\",\"send_notification_menu\",\"send_notification_list\",\"send_notification_create\",\"send_notification_update\",\"send_notification_edit\",\"send_notification_delete\",\"support_menu\",\"support_read\",\"support_create\",\"support_reply\",\"support_delete\"]', 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41', NULL),
(3, 'manager', 1, 'manager', '[\"team_menu\",\"team_list\",\"team_create\",\"team_update\",\"team_edit\",\"team_delete\",\"team_member_view\",\"team_member_create\",\"team_member_edit\",\"team_member_delete\",\"team_member_assign\",\"team_member_unassign\",\"dashboard\",\"designation_read\",\"designation_create\",\"designation_update\",\"designation_delete\",\"shift_read\",\"shift_create\",\"shift_update\",\"shift_delete\",\"department_read\",\"department_create\",\"department_update\",\"department_delete\",\"user_menu\",\"user_read\",\"profile_view\",\"user_create\",\"user_edit\",\"user_update\",\"user_delete\",\"user_banned\",\"user_unbanned\",\"make_hr\",\"user_permission\",\"profile_image_view\",\"role_read\",\"role_create\",\"role_update\",\"role_delete\",\"leave_menu\",\"leave_type_read\",\"leave_type_create\",\"leave_type_update\",\"leave_type_delete\",\"leave_assign_read\",\"leave_assign_create\",\"leave_assign_update\",\"leave_assign_delete\",\"leave_request_read\",\"leave_request_create\",\"leave_request_approve\",\"leave_request_reject\",\"leave_request_delete\",\"appointment_read\",\"appointment_menu\",\"appointment_create\",\"appointment_approve\",\"appointment_reject\",\"appointment_delete\",\"weekend_read\",\"weekend_update\",\"attendance_update\",\"holiday_read\",\"holiday_create\",\"holiday_update\",\"holiday_delete\",\"schedule_read\",\"schedule_create\",\"schedule_update\",\"schedule_delete\",\"attendance_menu\",\"attendance_read\",\"attendance_create\",\"attendance_update\",\"attendance_delete\",\"leave_settings_read\",\"leave_settings_update\",\"company_settings_read\",\"company_settings_update\",\"locationApi\",\"company_setup_menu\",\"company_setup_activation\",\"company_setup_configuration\",\"company_setup_ip_whitelist\",\"company_setup_location\",\"ip_read\",\"ip_create\",\"ip_update\",\"ip_delete\",\"attendance_report_read\",\"live_tracking_read\",\"report_menu\",\"report\",\"claim_read\",\"claim_create\",\"claim_update\",\"claim_delete\",\"payment_read\",\"payment_create\",\"payment_update\",\"payment_delete\",\"visit_menu\",\"visit_read\",\"visit_view\",\"visit_update\",\"payroll_menu\",\"list_payroll_item\",\"create_payroll_item\",\"store_payroll_item\",\"update_payroll_item\",\"delete_payroll_item\",\"view_payroll_item\",\"payroll_item_menu\",\"list_payroll_set\",\"create_payroll_set\",\"store_payroll_set\",\"update_payroll_set\",\"delete_payroll_set\",\"view_payroll_set\",\"edit_payroll_set\",\"payroll_set_menu\",\"advance_salaries_menu\",\"advance_salaries_create\",\"advance_salaries_store\",\"advance_salaries_edit\",\"advance_salaries_update\",\"advance_salaries_delete\",\"advance_salaries_view\",\"advance_salaries_approve\",\"advance_salaries_list\",\"advance_salaries_pay\",\"advance_salaries_invoice\",\"advance_salaries_search\",\"payslip_menu\",\"salary_generate\",\"salary_view\",\"salary_delete\",\"salary_edit\",\"salary_update\",\"salary_payment\",\"payslip_list\",\"advance_type_menu\",\"advance_type_create\",\"advance_type_store\",\"advance_type_edit\",\"advance_type_update\",\"advance_type_delete\",\"advance_type_view\",\"advance_type_list\",\"salary_menu\",\"salary_store\",\"salary_edit\",\"salary_update\",\"salary_delete\",\"salary_view\",\"salary_list\",\"salary_search\",\"salary_pay\",\"salary_invoice\",\"salary_approve\",\"salary_generate\",\"salary_calculate\",\"account_menu\",\"account_create\",\"account_store\",\"account_edit\",\"account_update\",\"account_delete\",\"account_view\",\"account_list\",\"account_search\",\"deposit_menu\",\"deposit_create\",\"deposit_store\",\"deposit_edit\",\"deposit_update\",\"deposit_delete\",\"deposit_list\",\"expense_menu\",\"expense_create\",\"expense_store\",\"expense_edit\",\"expense_update\",\"expense_delete\",\"expense_list\",\"expense_view\",\"expense_approve\",\"expense_invoice\",\"expense_pay\",\"transaction_menu\",\"transaction_create\",\"transaction_store\",\"transaction_edit\",\"transaction_update\",\"transaction_delete\",\"transaction_view\",\"transaction_list\",\"deposit_category_menu\",\"deposit_category_create\",\"deposit_category_store\",\"deposit_category_edit\",\"deposit_category_update\",\"deposit_category_delete\",\"deposit_category_list\",\"payment_method_menu\",\"payment_method_create\",\"payment_method_store\",\"payment_method_edit\",\"payment_method_update\",\"payment_method_delete\",\"payment_method_list\",\"travel_menu\",\"travel_create\",\"travel_store\",\"travel_edit\",\"travel_update\",\"travel_delete\",\"travel_list\",\"travel_view\",\"travel_approve\",\"travel_invoice\",\"travel_pay\",\"meeting_menu\",\"meeting_create\",\"meeting_store\",\"meeting_edit\",\"meeting_update\",\"meeting_delete\",\"meeting_list\",\"meeting_view\",\"announcement_menu\",\"notice_menu\",\"notice_list\",\"notice_create\",\"notice_update\",\"notice_edit\",\"notice_delete\",\"send_sms_menu\",\"send_sms_list\",\"send_sms_create\",\"send_sms_update\",\"send_sms_edit\",\"send_sms_delete\",\"send_email_menu\",\"send_email_list\",\"send_email_create\",\"send_email_update\",\"send_email_edit\",\"send_email_delete\",\"send_notification_menu\",\"send_notification_list\",\"send_notification_create\",\"send_notification_update\",\"send_notification_edit\",\"send_notification_delete\",\"announcement_menu\",\"notice_menu\",\"notice_list\",\"notice_create\",\"notice_update\",\"notice_edit\",\"notice_delete\",\"send_sms_menu\",\"send_sms_list\",\"send_sms_create\",\"send_sms_update\",\"send_sms_edit\",\"send_sms_delete\",\"send_email_menu\",\"send_email_list\",\"send_email_create\",\"send_email_update\",\"send_email_edit\",\"send_email_delete\",\"send_notification_menu\",\"send_notification_list\",\"send_notification_create\",\"send_notification_update\",\"send_notification_edit\",\"send_notification_delete\",\"support_menu\",\"support_read\",\"support_create\",\"support_reply\",\"support_delete\"]', 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41', NULL),
(4, 'staff', 1, 'staff', '[\"dashboard\",\"profile_view\",\"attendance_menu\",\"user_update\",\"attendance_read\",\"attendance_create\",\"leave_request_read\",\"leave_request_create\",\"support_menu\",\"support_read\",\"support_create\",\"support_reply\",\"support_delete\"]', 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41', NULL),
(5, 'client', 1, 'client', '[\"dashboard\",\"profile_view\",\"profile_update\",\"profile_change_password\",\"user_menu\",\"user_read\",\"profile_view\",\"profile_image_view\",\"project_menu\",\"project_edit\",\"project_update\",\"project_view\",\"project_list\",\"project_activity_view\",\"project_member_view\",\"project_complete\",\"project_payment\",\"project_invoice_view\",\"project_discussion_create\",\"project_discussion_store\",\"project_discussion_edit\",\"project_discussion_update\",\"project_discussion_view\",\"project_discussion_list\",\"project_discussion_comment\",\"project_discussion_reply\",\"project_file_create\",\"project_file_store\",\"project_file_edit\",\"project_file_update\",\"project_file_view\",\"project_file_list\",\"project_file_download\",\"project_file_comment\",\"project_file_reply\",\"project_notes_create\",\"project_notes_store\",\"project_notes_edit\",\"project_notes_update\",\"project_notes_list\",\"client_menu\",\"client_edit\",\"client_update\",\"client_view\",\"client_list\",\"task_menu\",\"task_create\",\"task_store\",\"task_edit\",\"task_update\",\"task_view\",\"task_list\",\"task_activity_view\",\"task_assign_view\",\"task_complete\",\"task_discussion_create\",\"task_discussion_store\",\"task_discussion_edit\",\"task_discussion_update\",\"task_discussion_view\",\"task_discussion_list\",\"task_discussion_comment\",\"task_discussion_reply\",\"task_file_create\",\"task_file_store\",\"task_file_edit\",\"task_file_update\",\"task_file_view\",\"task_file_list\",\"task_file_download\",\"task_file_comment\",\"task_file_reply\",\"task_notes_create\",\"task_notes_store\",\"task_notes_edit\",\"task_notes_list\",\"task_files_comment\",\"support_menu\",\"support_read\",\"support_create\",\"support_reply\"]', 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41', NULL),
(6, 'admin', 2, 'admin', '[\"team_menu\",\"team_list\",\"team_create\",\"team_update\",\"team_edit\",\"team_delete\",\"team_member_view\",\"team_member_create\",\"team_member_edit\",\"team_member_delete\",\"team_member_assign\",\"team_member_unassign\",\"dashboard\",\"hr_menu\",\"designation_read\",\"designation_create\",\"designation_update\",\"designation_delete\",\"shift_read\",\"shift_create\",\"shift_update\",\"shift_delete\",\"department_read\",\"department_create\",\"department_update\",\"department_delete\",\"user_menu\",\"user_read\",\"profile_view\",\"user_create\",\"user_edit\",\"user_update\",\"user_delete\",\"user_banned\",\"user_unbanned\",\"make_hr\",\"user_permission\",\"profile_image_view\",\"phonebook_profile\",\"support_ticket_profile\",\"advance_profile\",\"commission_profile\",\"salary_profile\",\"project_profile\",\"task_profile\",\"award_profile\",\"travel_profile\",\"attendance_profile\",\"appointment_profile\",\"visit_profile\",\"leave_request_profile\",\"notice_profile\",\"role_read\",\"role_create\",\"role_update\",\"role_delete\",\"leave_menu\",\"leave_type_read\",\"leave_type_create\",\"leave_type_update\",\"leave_type_delete\",\"leave_assign_read\",\"leave_assign_create\",\"leave_assign_update\",\"leave_assign_delete\",\"leave_request_read\",\"leave_request_create\",\"leave_request_store\",\"leave_request_update\",\"leave_request_approve\",\"leave_request_reject\",\"leave_request_delete\",\"appointment_read\",\"appointment_menu\",\"appointment_create\",\"appointment_approve\",\"appointment_reject\",\"appointment_delete\",\"weekend_read\",\"weekend_update\",\"attendance_update\",\"holiday_read\",\"holiday_create\",\"holiday_update\",\"holiday_delete\",\"schedule_read\",\"schedule_create\",\"schedule_update\",\"schedule_delete\",\"attendance_menu\",\"attendance_read\",\"attendance_create\",\"attendance_update\",\"attendance_delete\",\"generate_qr_code\",\"leave_settings_read\",\"leave_settings_update\",\"company_settings_read\",\"company_settings_update\",\"locationApi\",\"company_setup_menu\",\"company_setup_activation\",\"company_setup_configuration\",\"company_setup_ip_whitelist\",\"company_setup_location\",\"location_create\",\"location_store\",\"location_edit\",\"location_update\",\"location_delete\",\"ip_read\",\"ip_create\",\"ip_update\",\"ip_delete\",\"attendance_report_read\",\"live_tracking_read\",\"report_menu\",\"report\",\"claim_read\",\"claim_create\",\"claim_update\",\"claim_delete\",\"payment_read\",\"payment_create\",\"payment_update\",\"payment_delete\",\"visit_menu\",\"visit_read\",\"visit_view\",\"visit_update\",\"payroll_menu\",\"list_payroll_item\",\"create_payroll_item\",\"store_payroll_item\",\"update_payroll_item\",\"delete_payroll_item\",\"view_payroll_item\",\"payroll_item_menu\",\"list_payroll_set\",\"create_payroll_set\",\"store_payroll_set\",\"update_payroll_set\",\"delete_payroll_set\",\"view_payroll_set\",\"edit_payroll_set\",\"payroll_set_menu\",\"advance_salaries_menu\",\"advance_salaries_create\",\"advance_salaries_store\",\"advance_salaries_edit\",\"advance_salaries_update\",\"advance_salaries_delete\",\"advance_salaries_view\",\"advance_salaries_approve\",\"advance_salaries_list\",\"advance_salaries_pay\",\"advance_salaries_invoice\",\"advance_salaries_search\",\"payslip_menu\",\"salary_generate\",\"salary_view\",\"salary_delete\",\"salary_edit\",\"salary_update\",\"salary_payment\",\"payslip_list\",\"advance_type_menu\",\"advance_type_create\",\"advance_type_store\",\"advance_type_edit\",\"advance_type_update\",\"advance_type_delete\",\"advance_type_view\",\"advance_type_list\",\"salary_menu\",\"salary_store\",\"salary_edit\",\"salary_update\",\"salary_delete\",\"salary_view\",\"salary_list\",\"salary_search\",\"salary_pay\",\"salary_invoice\",\"salary_approve\",\"salary_generate\",\"salary_calculate\",\"account_menu\",\"account_create\",\"account_store\",\"account_edit\",\"account_update\",\"account_delete\",\"account_view\",\"account_list\",\"account_search\",\"deposit_menu\",\"deposit_create\",\"deposit_store\",\"deposit_edit\",\"deposit_update\",\"deposit_delete\",\"deposit_list\",\"expense_menu\",\"expense_create\",\"expense_store\",\"expense_edit\",\"expense_update\",\"expense_delete\",\"expense_list\",\"expense_view\",\"expense_approve\",\"expense_invoice\",\"expense_pay\",\"transaction_menu\",\"transaction_create\",\"transaction_store\",\"transaction_edit\",\"transaction_update\",\"transaction_delete\",\"transaction_view\",\"transaction_list\",\"deposit_category_menu\",\"deposit_category_create\",\"deposit_category_store\",\"deposit_category_edit\",\"deposit_category_update\",\"deposit_category_delete\",\"deposit_category_list\",\"payment_method_menu\",\"payment_method_create\",\"payment_method_store\",\"payment_method_edit\",\"payment_method_update\",\"payment_method_delete\",\"payment_method_list\",\"project_menu\",\"project_create\",\"project_store\",\"project_edit\",\"project_update\",\"project_delete\",\"project_view\",\"project_list\",\"project_activity_view\",\"project_member_view\",\"project_member_delete\",\"project_complete\",\"project_payment\",\"project_invoice_view\",\"project_discussion_create\",\"project_discussion_store\",\"project_discussion_edit\",\"project_discussion_update\",\"project_discussion_delete\",\"project_discussion_view\",\"project_discussion_list\",\"project_discussion_comment\",\"project_discussion_reply\",\"project_file_create\",\"project_file_store\",\"project_file_edit\",\"project_file_update\",\"project_file_delete\",\"project_file_view\",\"project_file_list\",\"project_file_download\",\"project_file_comment\",\"project_file_reply\",\"project_notes_create\",\"project_notes_store\",\"project_notes_edit\",\"project_notes_update\",\"project_notes_delete\",\"project_notes_list\",\"client_menu\",\"client_create\",\"client_store\",\"client_edit\",\"client_update\",\"client_delete\",\"client_view\",\"client_list\",\"task_menu\",\"task_create\",\"task_store\",\"task_edit\",\"task_update\",\"task_delete\",\"task_view\",\"task_list\",\"task_activity_view\",\"task_assign_view\",\"task_assign_delete\",\"task_complete\",\"task_discussion_create\",\"task_discussion_store\",\"task_discussion_edit\",\"task_discussion_update\",\"task_discussion_delete\",\"task_discussion_view\",\"task_discussion_list\",\"task_discussion_comment\",\"task_discussion_reply\",\"task_file_create\",\"task_file_store\",\"task_file_edit\",\"task_file_update\",\"task_file_delete\",\"task_file_view\",\"task_file_list\",\"task_file_download\",\"task_file_comment\",\"task_file_reply\",\"task_notes_create\",\"task_notes_store\",\"task_notes_edit\",\"task_notes_update\",\"task_notes_delete\",\"task_notes_list\",\"task_files_comment\",\"award_type_menu\",\"award_type_create\",\"award_type_store\",\"award_type_edit\",\"award_type_update\",\"award_type_delete\",\"award_type_view\",\"award_type_list\",\"award_menu\",\"award_create\",\"award_store\",\"award_edit\",\"award_update\",\"award_delete\",\"award_list\",\"travel_type_menu\",\"travel_type_create\",\"travel_type_store\",\"travel_type_edit\",\"travel_type_update\",\"travel_type_delete\",\"travel_type_view\",\"travel_type_list\",\"travel_menu\",\"travel_create\",\"travel_store\",\"travel_edit\",\"travel_update\",\"travel_delete\",\"travel_list\",\"travel_approve\",\"travel_payment\",\"meeting_menu\",\"meeting_create\",\"meeting_store\",\"meeting_edit\",\"meeting_update\",\"meeting_delete\",\"meeting_list\",\"performance_menu\",\"performance_settings\",\"performance_indicator_menu\",\"performance_indicator_list\",\"performance_indicator_create\",\"performance_indicator_store\",\"performance_indicator_edit\",\"performance_indicator_update\",\"performance_indicator_delete\",\"performance_appraisal_menu\",\"performance_appraisal_create\",\"performance_appraisal_store\",\"performance_appraisal_edit\",\"performance_appraisal_update\",\"performance_appraisal_delete\",\"performance_appraisal_list\",\"performance_appraisal_view\",\"performance_goal_type_menu\",\"performance_goal_type_create\",\"performance_goal_type_store\",\"performance_goal_type_edit\",\"performance_goal_type_update\",\"performance_goal_type_delete\",\"performance_goal_type_list\",\"performance_goal_menu\",\"performance_goal_create\",\"performance_goal_store\",\"performance_goal_edit\",\"performance_goal_update\",\"performance_goal_delete\",\"performance_goal_view\",\"performance_goal_list\",\"performance_competence_type_list\",\"performance_competence_type_menu\",\"performance_competence_type_create\",\"performance_competence_type_store\",\"performance_competence_type_edit\",\"performance_competence_type_update\",\"performance_competence_type_delete\",\"performance_competence_type_view\",\"performance_competence_menu\",\"performance_competence_create\",\"performance_competence_store\",\"performance_competence_edit\",\"performance_competence_update\",\"performance_competence_delete\",\"performance_competence_view\",\"performance_competence_list\",\"app_settings_menu\",\"app_settings_update\",\"language_menu\",\"make_default\",\"sales_menu\",\"sales_products_menu\",\"sales_products_create\",\"sales_products_store\",\"sales_products_edit\",\"sales_products_update\",\"sales_products_delete\",\"sales_products_view\",\"sales_products_history\",\"sales_products_import\",\"sales_product_category_menu\",\"sales_product_category_create\",\"sales_product_category_store\",\"sales_product_category_edit\",\"sales_product_category_update\",\"sales_product_category_delete\",\"sales_product_category_import\",\"sales_product_brand_menu\",\"sales_product_brand_create\",\"sales_product_brand_store\",\"sales_product_brand_edit\",\"sales_product_brand_update\",\"sales_product_brand_delete\",\"sales_product_brand_import\",\"sales_product_unit_menu\",\"sales_product_unit_create\",\"sales_product_unit_store\",\"sales_product_unit_edit\",\"sales_product_unit_update\",\"sales_product_unit_delete\",\"sales_product_unit_import\",\"sales_product_tax_menu\",\"sales_product_tax_create\",\"sales_product_tax_store\",\"sales_product_tax_edit\",\"sales_product_tax_update\",\"sales_product_tax_delete\",\"sales_product_tax_import\",\"sales_product_warehouse_menu\",\"sales_product_warehouse_create\",\"sales_product_warehouse_store\",\"sales_product_warehouse_edit\",\"sales_product_warehouse_update\",\"sales_product_warehouse_delete\",\"sales_product_warehouse_import\",\"sales_product_supplier_menu\",\"sales_product_supplier_create\",\"sales_product_supplier_store\",\"sales_product_supplier_edit\",\"sales_product_supplier_update\",\"sales_product_supplier_delete\",\"sales_product_supplier_import\",\"sales_product_supplier_clear_due\",\"sales_product_barcode_menu\",\"sales_product_barcode_generate\",\"sales_product_stock_adjustment_menu\",\"sales_product_stock_adjustment_create\",\"sales_product_stock_adjustment_store\",\"sales_product_stock_adjustment_edit\",\"sales_product_stock_adjustment_update\",\"sales_product_stock_adjustment_delete\",\"sales_product_stock_count_menu\",\"sales_product_stock_count_create\",\"sales_product_stock_count_store\",\"sales_product_stock_count_edit\",\"sales_product_stock_count_update\",\"sales_product_stock_count_delete\",\"sales_purchase_menu\",\"sales_purchase_create\",\"sales_purchase_store\",\"sales_purchase_edit\",\"sales_purchase_update\",\"sales_purchase_delete\",\"sales_purchase_add_payment\",\"sales_purchase_view_payment\",\"sales_purchase_import\",\"sales_create\",\"sales_store\",\"sales_edit\",\"sales_update\",\"sales_delete\",\"sales_add_payment\",\"sales_view_payment\",\"sales_delivery\",\"sales_invoice\",\"sales_pos_menu\",\"sales_giftcard_menu\",\"sales_giftcard_create\",\"sales_giftcard_store\",\"sales_giftcard_edit\",\"sales_giftcard_update\",\"sales_giftcard_delete\",\"sales_coupon_menu\",\"sales_coupon_create\",\"sales_coupon_store\",\"sales_coupon_edit\",\"sales_coupon_update\",\"sales_coupon_delete\",\"sales_delivery_menu\",\"sales_delivery_store\",\"sales_delivery_edit\",\"sales_delivery_update\",\"sales_delivery_delete\",\"sales_expense_category_menu\",\"sales_expense_category_create\",\"sales_expense_category_store\",\"sales_expense_category_edit\",\"sales_expense_category_update\",\"sales_expense_category_delete\",\"sales_expense_category_import\",\"sales_expense_menu\",\"sales_expense_create\",\"sales_expense_store\",\"sales_expense_edit\",\"sales_expense_update\",\"sales_expense_delete\",\"sales_quotation_menu\",\"sales_quotation_create\",\"sales_quotation_store\",\"sales_quotation_edit\",\"sales_quotation_update\",\"sales_quotation_delete\",\"sales_quotation_sale_create\",\"sales_quotation_purchase_create\",\"sales_transfer_menu\",\"sales_transfer_create\",\"sales_transfer_store\",\"sales_transfer_edit\",\"sales_transfer_update\",\"sales_transfer_delete\",\"sales_transfer_view\",\"sales_return_menu\",\"sales_return_sale_menu\",\"sales_return_sale_create\",\"sales_return_sale_store\",\"sales_return_sale_edit\",\"sales_return_sale_update\",\"sales_return_sale_delete\",\"sales_return_purchase_menu\",\"sales_return_purchase_create\",\"sales_return_purchase_store\",\"sales_return_purchase_edit\",\"sales_return_purchase_update\",\"sales_return_purchase_delete\",\"lead_type_menu\",\"lead_type_create\",\"lead_type_store\",\"lead_type_edit\",\"lead_type_update\",\"lead_type_delete\",\"lead_source_menu\",\"lead_source_create\",\"lead_source_store\",\"lead_source_edit\",\"lead_source_update\",\"lead_source_delete\",\"lead_status_menu\",\"lead_status_create\",\"lead_status_store\",\"lead_status_edit\",\"lead_status_update\",\"lead_status_delete\",\"lead_menu\",\"lead_create\",\"lead_store\",\"lead_edit\",\"lead_update\",\"lead_delete\",\"lead_view\",\"lead_detail_profile\",\"lead_detail_attachment\",\"lead_detail_email\",\"lead_detail_call\",\"lead_detail_note\",\"lead_detail_task\",\"lead_detail_reminder\",\"lead_detail_tag\",\"lead_detail_activities\",\"general_settings_read\",\"general_settings_update\",\"language_create\",\"language_store\",\"language_edit\",\"language_update\",\"language_delete\",\"setup_language\",\"content_menu\",\"content_create\",\"content_store\",\"content_edit\",\"content_update\",\"content_delete\",\"contact_menu\",\"contact_create\",\"contact_store\",\"contact_edit\",\"contact_update\",\"contact_delete\",\"announcement_menu\",\"notice_menu\",\"notice_list\",\"notice_create\",\"notice_update\",\"notice_edit\",\"notice_delete\",\"send_sms_menu\",\"send_sms_list\",\"send_sms_create\",\"send_sms_update\",\"send_sms_edit\",\"send_sms_delete\",\"send_email_menu\",\"send_email_list\",\"send_email_create\",\"send_email_update\",\"send_email_edit\",\"send_email_delete\",\"send_notification_menu\",\"send_notification_list\",\"send_notification_create\",\"send_notification_update\",\"send_notification_edit\",\"send_notification_delete\",\"announcement_menu\",\"notice_menu\",\"notice_list\",\"notice_create\",\"notice_update\",\"notice_edit\",\"notice_delete\",\"send_sms_menu\",\"send_sms_list\",\"send_sms_create\",\"send_sms_update\",\"send_sms_edit\",\"send_sms_delete\",\"send_email_menu\",\"send_email_list\",\"send_email_create\",\"send_email_update\",\"send_email_edit\",\"send_email_delete\",\"send_notification_menu\",\"send_notification_list\",\"send_notification_create\",\"send_notification_update\",\"send_notification_edit\",\"send_notification_delete\",\"support_menu\",\"support_read\",\"support_create\",\"support_reply\",\"support_delete\"]', 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41', NULL),
(7, 'manager', 2, 'manager', '[\"team_menu\",\"team_list\",\"team_create\",\"team_update\",\"team_edit\",\"team_delete\",\"team_member_view\",\"team_member_create\",\"team_member_edit\",\"team_member_delete\",\"team_member_assign\",\"team_member_unassign\",\"dashboard\",\"designation_read\",\"designation_create\",\"designation_update\",\"designation_delete\",\"shift_read\",\"shift_create\",\"shift_update\",\"shift_delete\",\"department_read\",\"department_create\",\"department_update\",\"department_delete\",\"user_menu\",\"user_read\",\"profile_view\",\"user_create\",\"user_edit\",\"user_update\",\"user_delete\",\"user_banned\",\"user_unbanned\",\"make_hr\",\"user_permission\",\"profile_image_view\",\"role_read\",\"role_create\",\"role_update\",\"role_delete\",\"leave_menu\",\"leave_type_read\",\"leave_type_create\",\"leave_type_update\",\"leave_type_delete\",\"leave_assign_read\",\"leave_assign_create\",\"leave_assign_update\",\"leave_assign_delete\",\"leave_request_read\",\"leave_request_create\",\"leave_request_approve\",\"leave_request_reject\",\"leave_request_delete\",\"appointment_read\",\"appointment_menu\",\"appointment_create\",\"appointment_approve\",\"appointment_reject\",\"appointment_delete\",\"weekend_read\",\"weekend_update\",\"attendance_update\",\"holiday_read\",\"holiday_create\",\"holiday_update\",\"holiday_delete\",\"schedule_read\",\"schedule_create\",\"schedule_update\",\"schedule_delete\",\"attendance_menu\",\"attendance_read\",\"attendance_create\",\"attendance_update\",\"attendance_delete\",\"leave_settings_read\",\"leave_settings_update\",\"company_settings_read\",\"company_settings_update\",\"locationApi\",\"company_setup_menu\",\"company_setup_activation\",\"company_setup_configuration\",\"company_setup_ip_whitelist\",\"company_setup_location\",\"ip_read\",\"ip_create\",\"ip_update\",\"ip_delete\",\"attendance_report_read\",\"live_tracking_read\",\"report_menu\",\"report\",\"claim_read\",\"claim_create\",\"claim_update\",\"claim_delete\",\"payment_read\",\"payment_create\",\"payment_update\",\"payment_delete\",\"visit_menu\",\"visit_read\",\"visit_view\",\"visit_update\",\"payroll_menu\",\"list_payroll_item\",\"create_payroll_item\",\"store_payroll_item\",\"update_payroll_item\",\"delete_payroll_item\",\"view_payroll_item\",\"payroll_item_menu\",\"list_payroll_set\",\"create_payroll_set\",\"store_payroll_set\",\"update_payroll_set\",\"delete_payroll_set\",\"view_payroll_set\",\"edit_payroll_set\",\"payroll_set_menu\",\"advance_salaries_menu\",\"advance_salaries_create\",\"advance_salaries_store\",\"advance_salaries_edit\",\"advance_salaries_update\",\"advance_salaries_delete\",\"advance_salaries_view\",\"advance_salaries_approve\",\"advance_salaries_list\",\"advance_salaries_pay\",\"advance_salaries_invoice\",\"advance_salaries_search\",\"payslip_menu\",\"salary_generate\",\"salary_view\",\"salary_delete\",\"salary_edit\",\"salary_update\",\"salary_payment\",\"payslip_list\",\"advance_type_menu\",\"advance_type_create\",\"advance_type_store\",\"advance_type_edit\",\"advance_type_update\",\"advance_type_delete\",\"advance_type_view\",\"advance_type_list\",\"salary_menu\",\"salary_store\",\"salary_edit\",\"salary_update\",\"salary_delete\",\"salary_view\",\"salary_list\",\"salary_search\",\"salary_pay\",\"salary_invoice\",\"salary_approve\",\"salary_generate\",\"salary_calculate\",\"account_menu\",\"account_create\",\"account_store\",\"account_edit\",\"account_update\",\"account_delete\",\"account_view\",\"account_list\",\"account_search\",\"deposit_menu\",\"deposit_create\",\"deposit_store\",\"deposit_edit\",\"deposit_update\",\"deposit_delete\",\"deposit_list\",\"expense_menu\",\"expense_create\",\"expense_store\",\"expense_edit\",\"expense_update\",\"expense_delete\",\"expense_list\",\"expense_view\",\"expense_approve\",\"expense_invoice\",\"expense_pay\",\"transaction_menu\",\"transaction_create\",\"transaction_store\",\"transaction_edit\",\"transaction_update\",\"transaction_delete\",\"transaction_view\",\"transaction_list\",\"deposit_category_menu\",\"deposit_category_create\",\"deposit_category_store\",\"deposit_category_edit\",\"deposit_category_update\",\"deposit_category_delete\",\"deposit_category_list\",\"payment_method_menu\",\"payment_method_create\",\"payment_method_store\",\"payment_method_edit\",\"payment_method_update\",\"payment_method_delete\",\"payment_method_list\",\"travel_menu\",\"travel_create\",\"travel_store\",\"travel_edit\",\"travel_update\",\"travel_delete\",\"travel_list\",\"travel_view\",\"travel_approve\",\"travel_invoice\",\"travel_pay\",\"meeting_menu\",\"meeting_create\",\"meeting_store\",\"meeting_edit\",\"meeting_update\",\"meeting_delete\",\"meeting_list\",\"meeting_view\",\"announcement_menu\",\"notice_menu\",\"notice_list\",\"notice_create\",\"notice_update\",\"notice_edit\",\"notice_delete\",\"send_sms_menu\",\"send_sms_list\",\"send_sms_create\",\"send_sms_update\",\"send_sms_edit\",\"send_sms_delete\",\"send_email_menu\",\"send_email_list\",\"send_email_create\",\"send_email_update\",\"send_email_edit\",\"send_email_delete\",\"send_notification_menu\",\"send_notification_list\",\"send_notification_create\",\"send_notification_update\",\"send_notification_edit\",\"send_notification_delete\",\"announcement_menu\",\"notice_menu\",\"notice_list\",\"notice_create\",\"notice_update\",\"notice_edit\",\"notice_delete\",\"send_sms_menu\",\"send_sms_list\",\"send_sms_create\",\"send_sms_update\",\"send_sms_edit\",\"send_sms_delete\",\"send_email_menu\",\"send_email_list\",\"send_email_create\",\"send_email_update\",\"send_email_edit\",\"send_email_delete\",\"send_notification_menu\",\"send_notification_list\",\"send_notification_create\",\"send_notification_update\",\"send_notification_edit\",\"send_notification_delete\",\"support_menu\",\"support_read\",\"support_create\",\"support_reply\",\"support_delete\"]', 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41', NULL),
(8, 'staff', 2, 'staff', '[\"dashboard\",\"profile_view\",\"attendance_menu\",\"user_update\",\"attendance_read\",\"attendance_create\",\"leave_request_read\",\"leave_request_create\",\"support_menu\",\"support_read\",\"support_create\",\"support_reply\",\"support_delete\"]', 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41', NULL),
(9, 'client', 2, 'client', '[\"dashboard\",\"profile_view\",\"profile_update\",\"profile_change_password\",\"user_menu\",\"user_read\",\"profile_view\",\"profile_image_view\",\"project_menu\",\"project_edit\",\"project_update\",\"project_view\",\"project_list\",\"project_activity_view\",\"project_member_view\",\"project_complete\",\"project_payment\",\"project_invoice_view\",\"project_discussion_create\",\"project_discussion_store\",\"project_discussion_edit\",\"project_discussion_update\",\"project_discussion_view\",\"project_discussion_list\",\"project_discussion_comment\",\"project_discussion_reply\",\"project_file_create\",\"project_file_store\",\"project_file_edit\",\"project_file_update\",\"project_file_view\",\"project_file_list\",\"project_file_download\",\"project_file_comment\",\"project_file_reply\",\"project_notes_create\",\"project_notes_store\",\"project_notes_edit\",\"project_notes_update\",\"project_notes_list\",\"client_menu\",\"client_edit\",\"client_update\",\"client_view\",\"client_list\",\"task_menu\",\"task_create\",\"task_store\",\"task_edit\",\"task_update\",\"task_view\",\"task_list\",\"task_activity_view\",\"task_assign_view\",\"task_complete\",\"task_discussion_create\",\"task_discussion_store\",\"task_discussion_edit\",\"task_discussion_update\",\"task_discussion_view\",\"task_discussion_list\",\"task_discussion_comment\",\"task_discussion_reply\",\"task_file_create\",\"task_file_store\",\"task_file_edit\",\"task_file_update\",\"task_file_view\",\"task_file_list\",\"task_file_download\",\"task_file_comment\",\"task_file_reply\",\"task_notes_create\",\"task_notes_store\",\"task_notes_edit\",\"task_notes_list\",\"task_files_comment\",\"support_menu\",\"support_read\",\"support_create\",\"support_reply\"]', 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_users`
--

CREATE TABLE `role_users` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_users`
--

INSERT INTO `role_users` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(2, 2, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(3, 3, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(4, 4, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(5, 5, 1, '2023-04-18 01:07:42', '2023-04-18 01:07:42');

-- --------------------------------------------------------

--
-- Table structure for table `salary_generates`
--

CREATE TABLE `salary_generates` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `company_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `amount` double(16,2) NOT NULL,
  `due_amount` double(16,2) DEFAULT NULL,
  `gross_salary` double(16,2) NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '9',
  `created_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `updated_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `total_working_day` int DEFAULT NULL,
  `present` int DEFAULT NULL,
  `absent` int DEFAULT NULL,
  `late` int DEFAULT NULL,
  `left_early` int DEFAULT NULL,
  `is_calculated` tinyint NOT NULL DEFAULT '0',
  `adjust` double(16,2) DEFAULT NULL,
  `absent_amount` double(16,2) DEFAULT NULL,
  `advance_amount` double(16,2) DEFAULT NULL,
  `advance_details` json DEFAULT NULL,
  `allowance_amount` double(16,2) DEFAULT NULL,
  `allowance_details` json DEFAULT NULL,
  `deduction_amount` double(16,2) DEFAULT NULL,
  `deduction_details` json DEFAULT NULL,
  `net_salary` double(16,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_payment_logs`
--

CREATE TABLE `salary_payment_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `amount` double(16,2) NOT NULL,
  `due_amount` double(16,2) DEFAULT NULL,
  `salary_generate_id` bigint UNSIGNED DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED DEFAULT NULL,
  `payment_method_id` bigint UNSIGNED DEFAULT NULL,
  `paid_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `updated_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `payment_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_setups`
--

CREATE TABLE `salary_setups` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `gross_salary` double(16,2) NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `updated_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_setup_details`
--

CREATE TABLE `salary_setup_details` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `salary_setup_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `commission_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `amount` double(16,2) NOT NULL,
  `amount_type` tinyint NOT NULL DEFAULT '1' COMMENT '1=fixed, 2=percentage',
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `updated_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint UNSIGNED NOT NULL,
  `sale_date` date DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `biller_id` int DEFAULT NULL,
  `item` int DEFAULT NULL,
  `total_qty` double DEFAULT NULL,
  `total_discount` double DEFAULT NULL,
  `total_tax` double DEFAULT NULL,
  `total_price` double DEFAULT NULL,
  `grand_total` double DEFAULT NULL,
  `order_tax_rate` double DEFAULT NULL,
  `order_tax` double DEFAULT NULL,
  `order_discount` double DEFAULT NULL,
  `shipping_cost` double DEFAULT NULL,
  `sale_status` int DEFAULT NULL,
  `payment_status` int DEFAULT NULL,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_amount` double DEFAULT NULL,
  `sale_note` text COLLATE utf8mb4_unicode_ci,
  `staff_note` text COLLATE utf8mb4_unicode_ci,
  `order_discount_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_discount_value` double DEFAULT NULL,
  `cash_register_id` int DEFAULT NULL,
  `coupon_id` int DEFAULT NULL,
  `coupon_discount` double DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_accounts`
--

CREATE TABLE `sale_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `initial_balance` double DEFAULT NULL,
  `total_balance` double NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `is_default` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_adjustments`
--

CREATE TABLE `sale_adjustments` (
  `id` bigint UNSIGNED NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` int NOT NULL,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_qty` double NOT NULL,
  `item` int NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_billers`
--

CREATE TABLE `sale_billers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vat_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_brands`
--

CREATE TABLE `sale_brands` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_cash_registers`
--

CREATE TABLE `sale_cash_registers` (
  `id` bigint UNSIGNED NOT NULL,
  `cash_in_hand` double NOT NULL,
  `user_id` int NOT NULL,
  `warehouse_id` int NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_categories`
--

CREATE TABLE `sale_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_coupons`
--

CREATE TABLE `sale_coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `minimum_amount` double DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `used` int DEFAULT NULL,
  `expired_date` date DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_customers`
--

CREATE TABLE `sale_customers` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_group_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `points` double DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `deposit` double DEFAULT NULL,
  `expense` double DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_customer_groups`
--

CREATE TABLE `sale_customer_groups` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `percentage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_deliveries`
--

CREATE TABLE `sale_deliveries` (
  `id` bigint UNSIGNED NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `delivered_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recieved_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_discounts`
--

CREATE TABLE `sale_discounts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `applicable_for` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_list` longtext COLLATE utf8mb4_unicode_ci,
  `valid_from` date NOT NULL,
  `valid_till` date NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` double NOT NULL,
  `minimum_qty` double NOT NULL,
  `maximum_qty` double NOT NULL,
  `days` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_discount_plans`
--

CREATE TABLE `sale_discount_plans` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_discount_plan_customers`
--

CREATE TABLE `sale_discount_plan_customers` (
  `id` bigint UNSIGNED NOT NULL,
  `discount_plan_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_discount_plan_discounts`
--

CREATE TABLE `sale_discount_plan_discounts` (
  `id` bigint UNSIGNED NOT NULL,
  `discount_id` int NOT NULL,
  `discount_plan_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_expenses`
--

CREATE TABLE `sale_expenses` (
  `id` bigint UNSIGNED NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expense_category_id` int NOT NULL,
  `warehouse_id` int NOT NULL,
  `account_id` int NOT NULL,
  `user_id` int NOT NULL,
  `cash_register_id` int NOT NULL,
  `amount` double NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_expense_categories`
--

CREATE TABLE `sale_expense_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_gift_cards`
--

CREATE TABLE `sale_gift_cards` (
  `id` bigint UNSIGNED NOT NULL,
  `card_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `expense` double DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `expired_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_gift_card_recharges`
--

CREATE TABLE `sale_gift_card_recharges` (
  `id` bigint UNSIGNED NOT NULL,
  `gift_card_id` int NOT NULL,
  `amount` double NOT NULL,
  `user_id` int NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_payments`
--

CREATE TABLE `sale_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `sale_id` int DEFAULT NULL,
  `change` double DEFAULT NULL,
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL,
  `paying_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_note` text COLLATE utf8mb4_unicode_ci,
  `purchase_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `account_id` int DEFAULT NULL,
  `used_points` double DEFAULT NULL,
  `cash_register_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_payment_with_cheques`
--

CREATE TABLE `sale_payment_with_cheques` (
  `id` bigint UNSIGNED NOT NULL,
  `payment_id` int NOT NULL,
  `cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_pos_settings`
--

CREATE TABLE `sale_pos_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` int NOT NULL,
  `warehouse_id` int NOT NULL,
  `biller_id` int NOT NULL,
  `product_number` int NOT NULL,
  `stripe_public_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_secret_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keybord_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_products`
--

CREATE TABLE `sale_products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode_symbology` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_id` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `unit_id` int DEFAULT NULL,
  `purchase_unit_id` int DEFAULT NULL,
  `sale_unit_id` int DEFAULT NULL,
  `cost` double DEFAULT NULL,
  `price` double DEFAULT NULL,
  `daily_sale_objective` int DEFAULT NULL,
  `is_variant` tinyint(1) DEFAULT NULL COMMENT '0 = No, 1 = Yes',
  `product_list` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty_list` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_list` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_imei` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `is_batch` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `is_embeded` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `variant_list` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variant_option` text COLLATE utf8mb4_unicode_ci,
  `variant_value` text COLLATE utf8mb4_unicode_ci,
  `is_diffPrice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `alert_quantity` double DEFAULT NULL,
  `tax_id` int DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `tax_method` int DEFAULT NULL,
  `featured` tinyint DEFAULT NULL,
  `embedded_barcode` tinyint DEFAULT NULL,
  `image` longtext COLLATE utf8mb4_unicode_ci,
  `product_details` text COLLATE utf8mb4_unicode_ci,
  `has_variant` tinyint NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `has_different_price` tinyint NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `has_badge` tinyint NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `expired_date` date DEFAULT NULL,
  `imei_serial` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `promotion` tinyint NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `promotion_price` double DEFAULT NULL,
  `starting_date` date DEFAULT NULL,
  `last_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_product_adjustments`
--

CREATE TABLE `sale_product_adjustments` (
  `id` bigint UNSIGNED NOT NULL,
  `adjustment_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `variant_id` int DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_product_batches`
--

CREATE TABLE `sale_product_batches` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` int NOT NULL,
  `batch_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired_date` date NOT NULL,
  `qty` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_product_purchases`
--

CREATE TABLE `sale_product_purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `product_batch_id` int DEFAULT NULL,
  `imei_number` int DEFAULT NULL,
  `variant_id` int DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `recieved` double DEFAULT NULL,
  `purchase_unit_id` int DEFAULT NULL,
  `net_unit_cost` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `tax_rate` double DEFAULT NULL,
  `tax` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_product_quotations`
--

CREATE TABLE `sale_product_quotations` (
  `id` bigint UNSIGNED NOT NULL,
  `quotation_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `variant_id` int DEFAULT NULL,
  `product_batch_id` int DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `sale_unit_id` int DEFAULT NULL,
  `net_unit_price` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `tax_rate` double DEFAULT NULL,
  `tax` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_product_returns`
--

CREATE TABLE `sale_product_returns` (
  `id` bigint UNSIGNED NOT NULL,
  `return_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `sale_unit_id` int DEFAULT NULL,
  `net_unit_price` double DEFAULT NULL,
  `variant_id` int DEFAULT NULL,
  `imei_number` text COLLATE utf8mb4_unicode_ci,
  `product_batch_id` int DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `tax_rate` double DEFAULT NULL,
  `tax` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_product_sales`
--

CREATE TABLE `sale_product_sales` (
  `id` bigint UNSIGNED NOT NULL,
  `sale_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `variant_id` int DEFAULT NULL,
  `imei_number` text COLLATE utf8mb4_unicode_ci,
  `product_batch_id` int DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `sale_unit_id` int DEFAULT NULL,
  `net_unit_price` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `tax_rate` double DEFAULT NULL,
  `tax` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_product_transfers`
--

CREATE TABLE `sale_product_transfers` (
  `id` bigint UNSIGNED NOT NULL,
  `transfer_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `purchase_unit_id` int DEFAULT NULL,
  `net_unit_cost` double DEFAULT NULL,
  `tax_rate` double DEFAULT NULL,
  `tax` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `variant_id` int DEFAULT NULL,
  `imei_number` text COLLATE utf8mb4_unicode_ci,
  `product_batch_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_product_variants`
--

CREATE TABLE `sale_product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` int NOT NULL,
  `variant_id` int NOT NULL,
  `position` int NOT NULL,
  `item_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional_price` double DEFAULT NULL,
  `qty` double NOT NULL,
  `additional_cost` double NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_product_warehouses`
--

CREATE TABLE `sale_product_warehouses` (
  `id` bigint UNSIGNED NOT NULL,
  `qty` int DEFAULT NULL,
  `price` double DEFAULT NULL,
  `variant_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `item_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imei_number` text COLLATE utf8mb4_unicode_ci,
  `product_batch_id` int DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_purchases`
--

CREATE TABLE `sale_purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` int NOT NULL,
  `supplier_id` int DEFAULT NULL,
  `item` int NOT NULL,
  `total_qty` int NOT NULL,
  `total_discount` double NOT NULL,
  `total_tax` double NOT NULL,
  `total_cost` double NOT NULL,
  `order_tax_rate` double DEFAULT NULL,
  `order_tax` double DEFAULT NULL,
  `order_discount` double DEFAULT NULL,
  `shipping_cost` double DEFAULT NULL,
  `grand_total` double NOT NULL,
  `paid_amount` double NOT NULL,
  `status` int NOT NULL,
  `payment_status` int NOT NULL,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_purchase_product_returns`
--

CREATE TABLE `sale_purchase_product_returns` (
  `id` bigint UNSIGNED NOT NULL,
  `return_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `purchase_unit_id` int DEFAULT NULL,
  `net_unit_cost` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `tax_rate` double DEFAULT NULL,
  `tax` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `variant_id` int DEFAULT NULL,
  `imei_number` text COLLATE utf8mb4_unicode_ci,
  `product_batch_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_quotations`
--

CREATE TABLE `sale_quotations` (
  `id` bigint UNSIGNED NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biller_id` int DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `item` int DEFAULT NULL,
  `total_qty` double DEFAULT NULL,
  `total_discount` double DEFAULT NULL,
  `total_tax` double DEFAULT NULL,
  `total_price` double DEFAULT NULL,
  `order_tax_rate` double DEFAULT NULL,
  `order_tax` double DEFAULT NULL,
  `order_discount` double DEFAULT NULL,
  `shipping_cost` double DEFAULT NULL,
  `grand_total` double DEFAULT NULL,
  `quotation_status` int DEFAULT NULL,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_returns`
--

CREATE TABLE `sale_returns` (
  `id` bigint UNSIGNED NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `biller_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `sale_id` int DEFAULT NULL,
  `account_id` int DEFAULT NULL,
  `cash_register_id` int DEFAULT NULL,
  `item` int DEFAULT NULL,
  `total_qty` double DEFAULT NULL,
  `total_discount` double DEFAULT NULL,
  `total_tax` double DEFAULT NULL,
  `total_price` double DEFAULT NULL,
  `order_tax_rate` double DEFAULT NULL,
  `order_tax` double DEFAULT NULL,
  `grand_total` double NOT NULL,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_note` text COLLATE utf8mb4_unicode_ci,
  `staff_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_purchases`
--

CREATE TABLE `sale_return_purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` int DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `account_id` int DEFAULT NULL,
  `purchase_id` int DEFAULT NULL,
  `item` int DEFAULT NULL,
  `total_qty` double DEFAULT NULL,
  `total_discount` double DEFAULT NULL,
  `total_tax` double DEFAULT NULL,
  `total_cost` double DEFAULT NULL,
  `order_tax_rate` double DEFAULT NULL,
  `order_tax` double DEFAULT NULL,
  `grand_total` double DEFAULT NULL,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_note` text COLLATE utf8mb4_unicode_ci,
  `staff_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_reward_point_settings`
--

CREATE TABLE `sale_reward_point_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `per_point_amount` double NOT NULL,
  `minimum_amount` double NOT NULL,
  `duration` int DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_stock_counts`
--

CREATE TABLE `sale_stock_counts` (
  `id` bigint UNSIGNED NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` int NOT NULL,
  `category_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `initial_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `is_adjusted` tinyint(1) NOT NULL DEFAULT '0',
  `action` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_suppliers`
--

CREATE TABLE `sale_suppliers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_taxes`
--

CREATE TABLE `sale_taxes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` double NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_transfers`
--

CREATE TABLE `sale_transfers` (
  `id` bigint UNSIGNED NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int DEFAULT NULL,
  `from_warehouse_id` int DEFAULT NULL,
  `to_warehouse_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `item` int DEFAULT NULL,
  `total_qty` double DEFAULT NULL,
  `total_tax` double DEFAULT NULL,
  `total_cost` double DEFAULT NULL,
  `shipping_cost` double NOT NULL DEFAULT '0',
  `grand_total` double DEFAULT NULL,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_units`
--

CREATE TABLE `sale_units` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_unit` int DEFAULT NULL,
  `operator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operation_value` double DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_variants`
--

CREATE TABLE `sale_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_warehouses`
--

CREATE TABLE `sale_warehouses` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_warehouses`
--

INSERT INTO `sale_warehouses` (`id`, `name`, `phone`, `email`, `address`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Warehouse 1', '01700000000', 'warehouse1@example.com', 'Warehouse 1 Address', 1, NULL, NULL, NULL, NULL),
(2, 'Warehouse 2', '01700000000', 'warehouse2@example.com', 'Warehouse 2 Address', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `search_menus`
--

CREATE TABLE `search_menus` (
  `id` bigint UNSIGNED NOT NULL,
  `route_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  `attachment` bigint UNSIGNED DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `slug`, `description`, `user_id`, `attachment`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 'Web Design', 'web-design', 'Our developers are always updated with the latest HTML5 Website, Encoding transcoding, User management CRM, CDN, DRM', 2, NULL, 1, '2023-04-18 01:07:53', NULL),
(2, 'Web Development', 'web-development', 'We also provide Enterprise Web Applications Development, Cross-platform Apps, HRM,CRM, Multivendor E-commerce etc', 2, NULL, 1, '2023-04-18 01:07:53', NULL),
(3, 'Graphics Design', 'graphics-design', 'A Graphic Designer is an artist who creates visual text and imagery. They design creative content for online campaigns, print ads, websites, and even videos.', 2, NULL, 1, '2023-04-18 01:07:53', NULL),
(4, 'Digital Marketing', 'digital-marketing', 'The objective of digital marketing is to develop strong and innovative strategies to promote the business brand, products, and services. A digital marketing professional is expected to effectively use all marketing tools and techniques like PPC, SEO, SEM, email, social media, and display advertising', 2, NULL, 1, '2023-04-18 01:07:53', NULL),
(5, 'Domain', 'domain', 'Most competitive price. Huge Choice & New Extension. Register your perfect domain name today.', 2, NULL, 1, '2023-04-18 01:07:53', NULL),
(6, 'Hosting', 'hosting', 'Web hosting, a service that hosts websites for clients and makes a website accessible on world wide web. We provide essential techniques and services for any website.', 2, NULL, 1, '2023-04-18 01:07:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `context` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'app',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `company_id`, `name`, `value`, `context`, `created_at`, `updated_at`) VALUES
(1, 1, 'company_name', 'One Stop CRM', 'app', '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(2, 1, 'dark_logo', 'public/assets/images/dark.png', 'app', '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(3, 1, 'white_logo', 'public/assets/images/white.png', 'app', '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(4, 1, 'company_icon', 'public/assets/images/favicon.png', 'app', '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(5, 1, 'android_url', '#', 'app', '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(6, 1, 'android_icon', 'public/assets/images/favicon.png', 'app', '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(7, 1, 'ios_url', '#', 'app', '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(8, 1, 'ios_icon', 'public/assets/images/favicon.png', 'app', '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(9, 1, 'language', 'en', 'app', '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(10, 1, 'emailSettingsProvider', 'smtp', 'app', '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(11, 1, 'emailSettings_from_name', 'crm@onest.com', 'app', '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(12, 1, 'emailSettings_from_email', 'crm@onest.com', 'app', '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(13, 1, 'site_under_maintenance', '0', 'app', '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(14, 1, 'company_description', 'We believes in painting the perfect picture of your idea while maintaining industry standards.', 'app', '2023-04-18 01:07:42', '2023-04-18 01:07:42'),
(15, 1, 'copy_right_text', '2023 One Stop CRM. All rights reserved.', 'app', '2023-04-18 01:07:42', '2023-04-18 01:07:42');

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`id`, `company_id`, `name`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Day', 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(2, 1, 'Night', 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(3, 2, 'Day', 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(4, 2, 'Night', 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `percentage` decimal(5,2) NOT NULL DEFAULT '60.00',
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs`
--

CREATE TABLE `sms_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `receiver_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `social_identities`
--

CREATE TABLE `social_identities` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `provider_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE `statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'hare name=status situation',
  `class` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'hare class=what type of class name property like success,danger,info,purple',
  `color_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`id`, `name`, `class`, `color_code`, `created_at`, `updated_at`) VALUES
(1, 'Active', 'success', '449d44', NULL, NULL),
(2, 'Pending', 'warning', 'ec971f', NULL, NULL),
(3, 'Suspended', 'danger', 'c9302c', NULL, NULL),
(4, 'Inactive', 'danger', 'c9302c', NULL, NULL),
(5, 'Approve', 'success', '449d44', NULL, NULL),
(6, 'Reject', 'danger', 'c9302c', NULL, NULL),
(7, 'Cancel', 'danger', 'c9302c', NULL, NULL),
(8, 'Paid', 'success', '449d44', NULL, NULL),
(9, 'Unpaid', 'danger', 'c9302c', NULL, NULL),
(10, 'Claimed', 'primary', '337ab7', NULL, NULL),
(11, 'Not Claimed', 'danger', 'c9302c', NULL, NULL),
(12, 'Open', 'danger', 'ffFD815B', NULL, NULL),
(13, 'Close', 'success', '449d44', NULL, NULL),
(14, 'High', 'danger', 'c9302c', NULL, NULL),
(15, 'Medium', 'primary', '337ab7', NULL, NULL),
(16, 'Low', 'warning', 'ec971f', NULL, NULL),
(17, 'Referred', 'warning', 'ec971f', NULL, NULL),
(18, 'Debit', 'danger', 'ffFD815B', NULL, NULL),
(19, 'Credit', 'success', '449d44', NULL, NULL),
(20, 'Partially Paid', 'info', '9DBBCE', NULL, NULL),
(21, 'Partially Returned', 'warning', 'ec971f', NULL, NULL),
(22, 'No', 'danger', 'c9302c', NULL, NULL),
(23, 'Returned', 'success', '449d44', NULL, NULL),
(24, 'Not Started', 'warning', 'ec971f', NULL, NULL),
(25, 'On Hold', 'info', '9DBBCE', NULL, NULL),
(26, 'In Progress', 'main', '7F58FE', NULL, NULL),
(27, 'Completed', 'success', '449d44', NULL, NULL),
(28, 'Cancelled', 'danger', 'c9302c', NULL, NULL),
(29, 'Urgent', 'danger', 'c9302c', NULL, NULL),
(30, 'High', 'danger', 'c9302c', NULL, NULL),
(31, 'Medium', 'primary', '337ab7', NULL, NULL),
(32, 'Low', 'warning', 'ec971f', NULL, NULL),
(33, 'Yes', 'primary', '337ab7', NULL, NULL),
(34, 'Contact', 'warning', 'ec971f', NULL, NULL),
(35, 'Hiring', 'warning', 'ec971f', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stock_brands`
--

CREATE TABLE `stock_brands` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` bigint UNSIGNED DEFAULT NULL,
  `author_info_id` bigint UNSIGNED DEFAULT NULL,
  `avatar_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_categories`
--

CREATE TABLE `stock_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` bigint UNSIGNED DEFAULT NULL,
  `author_info_id` bigint UNSIGNED DEFAULT NULL,
  `avatar_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_histories`
--

CREATE TABLE `stock_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `stock_product_id` bigint UNSIGNED NOT NULL,
  `product_purchase_id` bigint UNSIGNED NOT NULL,
  `invoice_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `quantity` bigint NOT NULL,
  `product_unit_id` bigint UNSIGNED NOT NULL,
  `purchase_price` double(16,2) NOT NULL,
  `selling_price` double(16,2) NOT NULL,
  `total` double(16,2) NOT NULL,
  `discount_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'percentage or fixed',
  `discount` double(16,2) DEFAULT NULL,
  `grand_total` double(16,2) DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_payment_histories`
--

CREATE TABLE `stock_payment_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `type` enum('purchase','sale') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(16,2) NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_type` int NOT NULL COMMENT '1=cash,2=bank,3=online,4=cheque',
  `bank_id` bigint UNSIGNED NOT NULL,
  `payment_method_id` bigint UNSIGNED NOT NULL,
  `bank_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_holder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_bank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_account_holder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_products`
--

CREATE TABLE `stock_products` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `serial` bigint DEFAULT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `stock_brand_id` bigint UNSIGNED NOT NULL,
  `stock_category_id` bigint UNSIGNED NOT NULL,
  `avatar_id` bigint UNSIGNED DEFAULT NULL,
  `tags` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `unit_price` double(16,2) DEFAULT NULL,
  `total_quantity` bigint DEFAULT '0',
  `published` int NOT NULL DEFAULT '0' COMMENT '0=unpublished,1=published,2=rejected,3=archived,4=deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_sales`
--

CREATE TABLE `stock_sales` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `stock_product_id` bigint UNSIGNED NOT NULL,
  `stock_payment_history_id` bigint UNSIGNED NOT NULL,
  `invoice` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL,
  `payment_status_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `updated_by` bigint UNSIGNED NOT NULL,
  `deleted_by` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `price` double(16,2) NOT NULL,
  `discount` double(16,2) NOT NULL,
  `tax` double(16,2) NOT NULL,
  `total` double(16,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_sale_histories`
--

CREATE TABLE `stock_sale_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `stock_sale_id` bigint UNSIGNED NOT NULL,
  `stock_product_id` bigint UNSIGNED NOT NULL,
  `quantity` bigint NOT NULL,
  `price` double(16,2) NOT NULL,
  `discount` double(16,2) NOT NULL,
  `tax` double(16,2) NOT NULL,
  `total` double(16,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_items`
--

CREATE TABLE `subscription_items` (
  `id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED NOT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_product` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identifier` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `title`, `identifier`, `stripe_id`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 'Basic', 'basic', 'plan_HeC7XMT2SVe21K', 1, NULL, NULL),
(2, 'Pro', 'pro', 'plan_HeC7XMT2SVe21L', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `assigned_id` bigint UNSIGNED DEFAULT NULL,
  `attachment_file_id` bigint UNSIGNED DEFAULT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `type_id` bigint UNSIGNED NOT NULL DEFAULT '12' COMMENT '12 = open , 13 = close',
  `priority_id` bigint UNSIGNED NOT NULL DEFAULT '14' COMMENT '14 = high , 15 = medium , 16 = low',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `company_id`, `date`, `code`, `user_id`, `assigned_id`, `attachment_file_id`, `subject`, `description`, `status_id`, `type_id`, `priority_id`, `created_at`, `updated_at`) VALUES
(1, 2, '2023-04-18', 'test-678', 4, 3, NULL, 'test', 'test', 1, 12, 14, '2023-04-18 01:07:46', '2023-04-18 01:07:46');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `progress` int DEFAULT '0',
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '24',
  `priority` bigint UNSIGNED NOT NULL DEFAULT '24',
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `notify_all_users` tinyint NOT NULL DEFAULT '0' COMMENT '0=no,1=yes',
  `notify_all_users_email` tinyint NOT NULL DEFAULT '0' COMMENT '0=no,1=yes',
  `type` tinyint NOT NULL DEFAULT '0' COMMENT '0=Regular , 1= Project',
  `client_id` bigint UNSIGNED DEFAULT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `reminder` tinyint NOT NULL DEFAULT '0' COMMENT '0=no,1=yes',
  `goal_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_activities`
--

CREATE TABLE `task_activities` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_discussions`
--

CREATE TABLE `task_discussions` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `show_to_customer` bigint UNSIGNED NOT NULL DEFAULT '22',
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `file_id` bigint UNSIGNED DEFAULT NULL COMMENT 'this will be attachment file',
  `last_activity` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_discussion_comments`
--

CREATE TABLE `task_discussion_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `comment_id` bigint UNSIGNED DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_to_customer` tinyint NOT NULL DEFAULT '1' COMMENT '0=no,1=yes',
  `task_discussion_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `attachment` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_files`
--

CREATE TABLE `task_files` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_to_customer` bigint UNSIGNED NOT NULL DEFAULT '22',
  `task_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `attachment` bigint UNSIGNED DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_file_comments`
--

CREATE TABLE `task_file_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `comment_id` bigint UNSIGNED DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_to_customer` tinyint NOT NULL DEFAULT '1' COMMENT '0=no,1=yes',
  `task_file_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `attachment` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_members`
--

CREATE TABLE `task_members` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `task_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `added_by` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_creator` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_notes`
--

CREATE TABLE `task_notes` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_to_customer` bigint UNSIGNED NOT NULL DEFAULT '22',
  `task_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `last_activity` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_file_id` bigint UNSIGNED DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `user_id` bigint UNSIGNED NOT NULL,
  `team_lead_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `attachment_file_id`, `company_id`, `status_id`, `user_id`, `team_lead_id`, `created_at`, `updated_at`) VALUES
(1, 'Management', NULL, 1, 1, 1, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(2, 'HR', NULL, 1, 1, 1, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(3, 'IT', NULL, 1, 1, 1, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(4, 'Management', NULL, 2, 1, 2, 2, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(5, 'HR', NULL, 2, 1, 2, 2, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(6, 'IT', NULL, 2, 1, 2, 2, '2023-04-18 01:07:43', '2023-04-18 01:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` bigint UNSIGNED NOT NULL,
  `team_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `expire_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `team_id`, `user_id`, `expire_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(2, 4, 2, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint UNSIGNED NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `message`, `company_id`, `status_id`, `created_at`, `updated_at`) VALUES
(1, ' Never felt this much relaxed in last couple of years It’s quiet comprehensible and helped me manage things very easily. A great software indeed!', 1, 1, '2023-04-18 01:07:43', '2023-04-18 01:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` bigint UNSIGNED NOT NULL,
  `support_ticket_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_zones`
--

CREATE TABLE `time_zones` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_zone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active_status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_zones`
--

INSERT INTO `time_zones` (`id`, `code`, `time_zone`, `active_status`, `created_at`, `updated_at`) VALUES
(1, 'AD', 'Europe/Andorra', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(2, 'AE', 'Asia/Dubai', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(3, 'AF', 'Asia/Kabul', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(4, 'AG', 'America/Antigua', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(5, 'AI', 'America/Anguilla', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(6, 'AL', 'Europe/Tirane', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(7, 'AM', 'Asia/Yerevan', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(8, 'AO', 'Africa/Luanda', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(9, 'AQ', 'Antarctica/McMurdo', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(10, 'AQ', 'Antarctica/Casey', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(11, 'AQ', 'Antarctica/Davis', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(12, 'AQ', 'Antarctica/DumontDUrville', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(13, 'AQ', 'Antarctica/Mawson', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(14, 'AQ', 'Antarctica/Palmer', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(15, 'AQ', 'Antarctica/Rothera', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(16, 'AQ', 'Antarctica/Syowa', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(17, 'AQ', 'Antarctica/Troll', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(18, 'AQ', 'Antarctica/Vostok', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(19, 'AR', 'America/Argentina/Buenos_Aires', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(20, 'AR', 'America/Argentina/Cordoba', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(21, 'AR', 'America/Argentina/Salta', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(22, 'AR', 'America/Argentina/Jujuy', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(23, 'AR', 'America/Argentina/Tucuman', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(24, 'AR', 'America/Argentina/Catamarca', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(25, 'AR', 'America/Argentina/La_Rioja', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(26, 'AR', 'America/Argentina/San_Juan', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(27, 'AR', 'America/Argentina/Mendoza', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(28, 'AR', 'America/Argentina/San_Luis', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(29, 'AR', 'America/Argentina/Rio_Gallegos', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(30, 'AR', 'America/Argentina/Ushuaia', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(31, 'AS', 'Pacific/Pago_Pago', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(32, 'AT', 'Europe/Vienna', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(33, 'AU', 'Australia/Lord_Howe', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(34, 'AU', 'Antarctica/Macquarie', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(35, 'AU', 'Australia/Hobart', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(36, 'AU', 'Australia/Currie', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(37, 'AU', 'Australia/Melbourne', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(38, 'AU', 'Australia/Sydney', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(39, 'AU', 'Australia/Broken_Hill', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(40, 'AU', 'Australia/Brisbane', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(41, 'AU', 'Australia/Lindeman', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(42, 'AU', 'Australia/Adelaide', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(43, 'AU', 'Australia/Darwin', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(44, 'AU', 'Australia/Perth', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(45, 'AU', 'Australia/Eucla', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(46, 'AW', 'America/Aruba', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(47, 'AX', 'Europe/Mariehamn', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(48, 'AZ', 'Asia/Baku', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(49, 'BA', 'Europe/Sarajevo', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(50, 'BB', 'America/Barbados', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(51, 'BD', 'Asia/Dhaka', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(52, 'BE', 'Europe/Brussels', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(53, 'BF', 'Africa/Ouagadougou', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(54, 'BG', 'Europe/Sofia', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(55, 'BH', 'Asia/Bahrain', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(56, 'BI', 'Africa/Bujumbura', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(57, 'BJ', 'Africa/Porto-Novo', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(58, 'BL', 'America/St_Barthelemy', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(59, 'BM', 'Atlantic/Bermuda', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(60, 'BN', 'Asia/Brunei', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(61, 'BO', 'America/La_Paz', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(62, 'BQ', 'America/Kralendijk', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(63, 'BR', 'America/Noronha', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(64, 'BR', 'America/Belem', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(65, 'BR', 'America/Fortaleza', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(66, 'BR', 'America/Recife', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(67, 'BR', 'America/Araguaina', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(68, 'BR', 'America/Maceio', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(69, 'BR', 'America/Bahia', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(70, 'BR', 'America/Sao_Paulo', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(71, 'BR', 'America/Campo_Grande', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(72, 'BR', 'America/Cuiaba', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(73, 'BR', 'America/Santarem', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(74, 'BR', 'America/Porto_Velho', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(75, 'BR', 'America/Boa_Vista', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(76, 'BR', 'America/Manaus', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(77, 'BR', 'America/Eirunepe', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(78, 'BR', 'America/Rio_Branco', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(79, 'BS', 'America/Nassau', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(80, 'BT', 'Asia/Thimphu', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(81, 'BW', 'Africa/Gaborone', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(82, 'BY', 'Europe/Minsk', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(83, 'BZ', 'America/Belize', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(84, 'CA', 'America/St_Johns', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(85, 'CA', 'America/Halifax', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(86, 'CA', 'America/Glace_Bay', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(87, 'CA', 'America/Moncton', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(88, 'CA', 'America/Goose_Bay', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(89, 'CA', 'America/Blanc-Sablon', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(90, 'CA', 'America/Toronto', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(91, 'CA', 'America/Nipigon', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(92, 'CA', 'America/Thunder_Bay', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(93, 'CA', 'America/Iqaluit', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(94, 'CA', 'America/Pangnirtung', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(95, 'CA', 'America/Atikokan', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(96, 'CA', 'America/Winnipeg', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(97, 'CA', 'America/Rainy_River', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(98, 'CA', 'America/Resolute', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(99, 'CA', 'America/Rankin_Inlet', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(100, 'CA', 'America/Regina', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(101, 'CA', 'America/Swift_Current', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(102, 'CA', 'America/Edmonton', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(103, 'CA', 'America/Cambridge_Bay', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(104, 'CA', 'America/Yellowknife', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(105, 'CA', 'America/Inuvik', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(106, 'CA', 'America/Creston', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(107, 'CA', 'America/Dawson_Creek', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(108, 'CA', 'America/Fort_Nelson', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(109, 'CA', 'America/Vancouver', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(110, 'CA', 'America/Whitehorse', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(111, 'CA', 'America/Dawson', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(112, 'CC', 'Indian/Cocos', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(113, 'CD', 'Africa/Kinshasa', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(114, 'CD', 'Africa/Lubumbashi', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(115, 'CF', 'Africa/Bangui', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(116, 'CG', 'Africa/Brazzaville', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(117, 'CH', 'Europe/Zurich', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(118, 'CI', 'Africa/Abidjan', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(119, 'CK', 'Pacific/Rarotonga', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(120, 'CL', 'America/Santiago', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(121, 'CL', 'America/Punta_Arenas', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(122, 'CL', 'Pacific/Easter', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(123, 'CM', 'Africa/Douala', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(124, 'CN', 'Asia/Shanghai', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(125, 'CN', 'Asia/Urumqi', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(126, 'CO', 'America/Bogota', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(127, 'CR', 'America/Costa_Rica', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(128, 'CU', 'America/Havana', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(129, 'CV', 'Atlantic/Cape_Verde', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(130, 'CW', 'America/Curacao', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(131, 'CX', 'Indian/Christmas', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(132, 'CY', 'Asia/Nicosia', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(133, 'CY', 'Asia/Famagusta', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(134, 'CZ', 'Europe/Prague', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(135, 'DE', 'Europe/Berlin', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(136, 'DE', 'Europe/Busingen', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(137, 'DJ', 'Africa/Djibouti', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(138, 'DK', 'Europe/Copenhagen', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(139, 'DM', 'America/Dominica', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(140, 'DO', 'America/Santo_Domingo', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(141, 'DZ', 'Africa/Algiers', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(142, 'EC', 'America/Guayaquil', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(143, 'EC', 'Pacific/Galapagos', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(144, 'EE', 'Europe/Tallinn', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(145, 'EG', 'Africa/Cairo', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(146, 'EH', 'Africa/El_Aaiun', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(147, 'ER', 'Africa/Asmara', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(148, 'ES', 'Europe/Madrid', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(149, 'ES', 'Africa/Ceuta', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(150, 'ES', 'Atlantic/Canary', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(151, 'ET', 'Africa/Addis_Ababa', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(152, 'FI', 'Europe/Helsinki', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(153, 'FJ', 'Pacific/Fiji', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(154, 'FK', 'Atlantic/Stanley', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(155, 'FM', 'Pacific/Chuuk', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(156, 'FM', 'Pacific/Pohnpei', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(157, 'FM', 'Pacific/Kosrae', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(158, 'FO', 'Atlantic/Faroe', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(159, 'FR', 'Europe/Paris', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(160, 'GA', 'Africa/Libreville', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(161, 'GB', 'Europe/London', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(162, 'GD', 'America/Grenada', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(163, 'GE', 'Asia/Tbilisi', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(164, 'GF', 'America/Cayenne', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(165, 'GG', 'Europe/Guernsey', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(166, 'GH', 'Africa/Accra', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(167, 'GI', 'Europe/Gibraltar', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(168, 'GL', 'America/Godthab', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(169, 'GL', 'America/Danmarkshavn', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(170, 'GL', 'America/Scoresbysund', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(171, 'GL', 'America/Thule', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(172, 'GM', 'Africa/Banjul', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(173, 'GN', 'Africa/Conakry', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(174, 'GP', 'America/Guadeloupe', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(175, 'GQ', 'Africa/Malabo', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(176, 'GR', 'Europe/Athens', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(177, 'GS', 'Atlantic/South_Georgia', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(178, 'GT', 'America/Guatemala', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(179, 'GU', 'Pacific/Guam', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(180, 'GW', 'Africa/Bissau', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(181, 'GY', 'America/Guyana', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(182, 'HK', 'Asia/Hong_Kong', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(183, 'HN', 'America/Tegucigalpa', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(184, 'HR', 'Europe/Zagreb', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(185, 'HT', 'America/Port-au-Prince', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(186, 'HU', 'Europe/Budapest', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(187, 'ID', 'Asia/Jakarta', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(188, 'ID', 'Asia/Pontianak', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(189, 'ID', 'Asia/Makassar', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(190, 'ID', 'Asia/Jayapura', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(191, 'IE', 'Europe/Dublin', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(192, 'IL', 'Asia/Jerusalem', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(193, 'IM', 'Europe/Isle_of_Man', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(194, 'IN', 'Asia/Kolkata', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(195, 'IO', 'Indian/Chagos', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(196, 'IQ', 'Asia/Baghdad', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(197, 'IR', 'Asia/Tehran', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(198, 'IS', 'Atlantic/Reykjavik', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(199, 'IT', 'Europe/Rome', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(200, 'JE', 'Europe/Jersey', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(201, 'JM', 'America/Jamaica', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(202, 'JO', 'Asia/Amman', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(203, 'JP', 'Asia/Tokyo', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(204, 'KE', 'Africa/Nairobi', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(205, 'KG', 'Asia/Bishkek', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(206, 'KH', 'Asia/Phnom_Penh', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(207, 'KI', 'Pacific/Tarawa', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(208, 'KI', 'Pacific/Enderbury', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(209, 'KI', 'Pacific/Kiritimati', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(210, 'KM', 'Indian/Comoro', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(211, 'KN', 'America/St_Kitts', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(212, 'KP', 'Asia/Pyongyang', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(213, 'KR', 'Asia/Seoul', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(214, 'KW', 'Asia/Kuwait', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(215, 'KY', 'America/Cayman', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(216, 'KZ', 'Asia/Almaty', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(217, 'KZ', 'Asia/Qyzylorda', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(218, 'KZ', 'Asia/Aqtobe', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(219, 'KZ', 'Asia/Aqtau', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(220, 'KZ', 'Asia/Atyrau', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(221, 'KZ', 'Asia/Oral', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(222, 'LA', 'Asia/Vientiane', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(223, 'LB', 'Asia/Beirut', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(224, 'LC', 'America/St_Lucia', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(225, 'LI', 'Europe/Vaduz', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(226, 'LK', 'Asia/Colombo', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(227, 'LR', 'Africa/Monrovia', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(228, 'LS', 'Africa/Maseru', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(229, 'LT', 'Europe/Vilnius', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(230, 'LU', 'Europe/Luxembourg', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(231, 'LV', 'Europe/Riga', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(232, 'LY', 'Africa/Tripoli', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(233, 'MA', 'Africa/Casablanca', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(234, 'MC', 'Europe/Monaco', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(235, 'MD', 'Europe/Chisinau', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(236, 'ME', 'Europe/Podgorica', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(237, 'MF', 'America/Marigot', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(238, 'MG', 'Indian/Antananarivo', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(239, 'MH', 'Pacific/Majuro', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(240, 'MH', 'Pacific/Kwajalein', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(241, 'MK', 'Europe/Skopje', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(242, 'ML', 'Africa/Bamako', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(243, 'MM', 'Asia/Yangon', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(244, 'MN', 'Asia/Ulaanbaatar', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(245, 'MN', 'Asia/Hovd', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(246, 'MN', 'Asia/Choibalsan', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(247, 'MO', 'Asia/Macau', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(248, 'MP', 'Pacific/Saipan', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(249, 'MQ', 'America/Martinique', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(250, 'MR', 'Africa/Nouakchott', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(251, 'MS', 'America/Montserrat', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(252, 'MT', 'Europe/Malta', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(253, 'MU', 'Indian/Mauritius', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(254, 'MV', 'Indian/Maldives', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(255, 'MW', 'Africa/Blantyre', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(256, 'MX', 'America/Mexico_City', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(257, 'MX', 'America/Cancun', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(258, 'MX', 'America/Merida', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(259, 'MX', 'America/Monterrey', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(260, 'MX', 'America/Matamoros', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(261, 'MX', 'America/Mazatlan', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(262, 'MX', 'America/Chihuahua', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(263, 'MX', 'America/Ojinaga', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(264, 'MX', 'America/Hermosillo', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(265, 'MX', 'America/Tijuana', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(266, 'MX', 'America/Bahia_Banderas', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(267, 'MY', 'Asia/Kuala_Lumpur', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(268, 'MY', 'Asia/Kuching', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(269, 'MZ', 'Africa/Maputo', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(270, 'NA', 'Africa/Windhoek', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(271, 'NC', 'Pacific/Noumea', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(272, 'NE', 'Africa/Niamey', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(273, 'NF', 'Pacific/Norfolk', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(274, 'NG', 'Africa/Lagos', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(275, 'NI', 'America/Managua', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(276, 'NL', 'Europe/Amsterdam', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(277, 'NO', 'Europe/Oslo', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(278, 'NP', 'Asia/Kathmandu', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(279, 'NR', 'Pacific/Nauru', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(280, 'NU', 'Pacific/Niue', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(281, 'NZ', 'Pacific/Auckland', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(282, 'NZ', 'Pacific/Chatham', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(283, 'OM', 'Asia/Muscat', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(284, 'PA', 'America/Panama', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(285, 'PE', 'America/Lima', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(286, 'PF', 'Pacific/Tahiti', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(287, 'PF', 'Pacific/Marquesas', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(288, 'PF', 'Pacific/Gambier', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(289, 'PG', 'Pacific/Port_Moresby', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(290, 'PG', 'Pacific/Bougainville', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(291, 'PH', 'Asia/Manila', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(292, 'PK', 'Asia/Karachi', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(293, 'PL', 'Europe/Warsaw', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(294, 'PM', 'America/Miquelon', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(295, 'PN', 'Pacific/Pitcairn', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(296, 'PR', 'America/Puerto_Rico', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(297, 'PS', 'Asia/Gaza', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(298, 'PS', 'Asia/Hebron', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(299, 'PT', 'Europe/Lisbon', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(300, 'PT', 'Atlantic/Madeira', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(301, 'PT', 'Atlantic/Azores', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(302, 'PW', 'Pacific/Palau', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(303, 'PY', 'America/Asuncion', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(304, 'QA', 'Asia/Qatar', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(305, 'RE', 'Indian/Reunion', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(306, 'RO', 'Europe/Bucharest', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(307, 'RS', 'Europe/Belgrade', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(308, 'RU', 'Europe/Kaliningrad', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(309, 'RU', 'Europe/Moscow', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(310, 'RU', 'Europe/Simferopol', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(311, 'RU', 'Europe/Volgograd', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(312, 'RU', 'Europe/Kirov', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(313, 'RU', 'Europe/Astrakhan', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(314, 'RU', 'Europe/Saratov', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(315, 'RU', 'Europe/Ulyanovsk', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(316, 'RU', 'Europe/Samara', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(317, 'RU', 'Asia/Yekaterinburg', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(318, 'RU', 'Asia/Omsk', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(319, 'RU', 'Asia/Novosibirsk', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(320, 'RU', 'Asia/Barnaul', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(321, 'RU', 'Asia/Tomsk', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(322, 'RU', 'Asia/Novokuznetsk', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(323, 'RU', 'Asia/Krasnoyarsk', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(324, 'RU', 'Asia/Irkutsk', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(325, 'RU', 'Asia/Chita', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(326, 'RU', 'Asia/Yakutsk', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(327, 'RU', 'Asia/Khandyga', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(328, 'RU', 'Asia/Vladivostok', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(329, 'RU', 'Asia/Ust-Nera', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(330, 'RU', 'Asia/Magadan', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(331, 'RU', 'Asia/Sakhalin', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(332, 'RU', 'Asia/Srednekolymsk', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(333, 'RU', 'Asia/Kamchatka', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(334, 'RU', 'Asia/Anadyr', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(335, 'RW', 'Africa/Kigali', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(336, 'SA', 'Asia/Riyadh', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(337, 'SB', 'Pacific/Guadalcanal', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(338, 'SC', 'Indian/Mahe', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(339, 'SD', 'Africa/Khartoum', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(340, 'SE', 'Europe/Stockholm', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(341, 'SG', 'Asia/Singapore', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(342, 'SH', 'Atlantic/St_Helena', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(343, 'SI', 'Europe/Ljubljana', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(344, 'SJ', 'Arctic/Longyearbyen', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(345, 'SK', 'Europe/Bratislava', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(346, 'SL', 'Africa/Freetown', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(347, 'SM', 'Europe/San_Marino', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(348, 'SN', 'Africa/Dakar', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(349, 'SO', 'Africa/Mogadishu', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(350, 'SR', 'America/Paramaribo', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(351, 'SS', 'Africa/Juba', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(352, 'ST', 'Africa/Sao_Tome', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(353, 'SV', 'America/El_Salvador', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(354, 'SX', 'America/Lower_Princes', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(355, 'SY', 'Asia/Damascus', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(356, 'SZ', 'Africa/Mbabane', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(357, 'TC', 'America/Grand_Turk', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(358, 'TD', 'Africa/Ndjamena', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(359, 'TF', 'Indian/Kerguelen', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(360, 'TG', 'Africa/Lome', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(361, 'TH', 'Asia/Bangkok', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(362, 'TJ', 'Asia/Dushanbe', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(363, 'TK', 'Pacific/Fakaofo', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(364, 'TL', 'Asia/Dili', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(365, 'TM', 'Asia/Ashgabat', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(366, 'TN', 'Africa/Tunis', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(367, 'TO', 'Pacific/Tongatapu', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(368, 'TR', 'Europe/Istanbul', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(369, 'TT', 'America/Port_of_Spain', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(370, 'TV', 'Pacific/Funafuti', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(371, 'TW', 'Asia/Taipei', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(372, 'TZ', 'Africa/Dar_es_Salaam', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(373, 'UA', 'Europe/Kiev', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(374, 'UA', 'Europe/Uzhgorod', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(375, 'UA', 'Europe/Zaporozhye', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(376, 'UG', 'Africa/Kampala', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(377, 'UM', 'Pacific/Midway', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(378, 'UM', 'Pacific/Wake', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(379, 'US', 'America/New_York', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(380, 'US', 'America/Detroit', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(381, 'US', 'America/Kentucky/Louisville', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(382, 'US', 'America/Kentucky/Monticello', 1, '2023-04-18 01:07:16', '2023-04-18 01:07:16'),
(383, 'US', 'America/Indiana/Indianapolis', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(384, 'US', 'America/Indiana/Vincennes', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(385, 'US', 'America/Indiana/Winamac', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(386, 'US', 'America/Indiana/Marengo', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(387, 'US', 'America/Indiana/Petersburg', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(388, 'US', 'America/Indiana/Vevay', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(389, 'US', 'America/Chicago', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(390, 'US', 'America/Indiana/Tell_City', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(391, 'US', 'America/Indiana/Knox', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(392, 'US', 'America/Menominee', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(393, 'US', 'America/North_Dakota/Center', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(394, 'US', 'America/North_Dakota/New_Salem', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(395, 'US', 'America/North_Dakota/Beulah', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(396, 'US', 'America/Denver', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(397, 'US', 'America/Boise', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(398, 'US', 'America/Phoenix', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(399, 'US', 'America/Los_Angeles', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(400, 'US', 'America/Anchorage', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(401, 'US', 'America/Juneau', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(402, 'US', 'America/Sitka', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(403, 'US', 'America/Metlakatla', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(404, 'US', 'America/Yakutat', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(405, 'US', 'America/Nome', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(406, 'US', 'America/Adak', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(407, 'US', 'Pacific/Honolulu', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(408, 'UY', 'America/Montevideo', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(409, 'UZ', 'Asia/Samarkand', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(410, 'UZ', 'Asia/Tashkent', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(411, 'VA', 'Europe/Vatican', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(412, 'VC', 'America/St_Vincent', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(413, 'VE', 'America/Caracas', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(414, 'VG', 'America/Tortola', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(415, 'VI', 'America/St_Thomas', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(416, 'VN', 'Asia/Ho_Chi_Minh', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(417, 'VU', 'Pacific/Efate', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(418, 'WF', 'Pacific/Wallis', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(419, 'WS', 'Pacific/Apia', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(420, 'YE', 'Asia/Aden', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(421, 'YT', 'Indian/Mayotte', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(422, 'ZA', 'Africa/Johannesburg', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(423, 'ZM', 'Africa/Lusaka', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17'),
(424, 'ZW', 'Africa/Harare', 1, '2023-04-18 01:07:17', '2023-04-18 01:07:17');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(16,2) NOT NULL DEFAULT '0.00',
  `transaction_type` bigint UNSIGNED NOT NULL DEFAULT '18',
  `client_id` bigint UNSIGNED DEFAULT NULL,
  `income_expense_category_id` bigint UNSIGNED DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '9',
  `created_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `updated_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `company_id`, `account_id`, `date`, `description`, `amount`, `transaction_type`, `client_id`, `income_expense_category_id`, `status_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2022-03-14', 'Income from Upwork', 7142.00, 19, 1, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(2, 2, 1, '2022-01-28', 'Income from Fiverr', 1380.00, 19, 1, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(3, 1, 1, '2021-09-17', 'Income from Freelancer', 4028.00, 19, 1, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(4, 1, 1, '2021-09-27', 'Income from Project', 3988.00, 19, 1, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(5, 2, 1, '2022-10-04', 'Income from Transfer', 7872.00, 19, 1, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(6, 1, 1, '2021-03-24', 'Income from Upwork', 6094.00, 19, 1, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(7, 2, 1, '2021-10-25', 'Income from Fiverr', 9947.00, 19, 1, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(8, 1, 1, '2022-07-25', 'Income from Freelancer', 4555.00, 19, 1, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(9, 2, 1, '2021-06-25', 'Income from Project', 6004.00, 19, 1, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(10, 2, 1, '2021-03-03', 'Income from Transfer', 2407.00, 19, 1, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(11, 2, 1, '2022-08-07', 'Income from Upwork', 3415.00, 19, 1, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(12, 1, 1, '2022-10-10', 'Income from Fiverr', 1990.00, 19, 1, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(13, 1, 1, '2021-06-12', 'Income from Freelancer', 3848.00, 19, 1, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(14, 2, 1, '2022-10-02', 'Income from Project', 6135.00, 19, 1, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(15, 2, 1, '2022-09-26', 'Income from Transfer', 5203.00, 19, 1, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(16, 1, 1, '2022-06-07', 'Income from Upwork', 2787.00, 19, 1, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(17, 2, 1, '2022-05-13', 'Income from Fiverr', 6483.00, 19, 1, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(18, 2, 1, '2021-01-18', 'Income from Freelancer', 2955.00, 19, 1, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(19, 1, 1, '2021-06-25', 'Income from Project', 8305.00, 19, 1, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(20, 1, 1, '2022-11-26', 'Income from Transfer', 8636.00, 19, 1, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(21, 2, 1, '2022-10-12', 'Income from Upwork', 3012.00, 19, 2, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(22, 2, 1, '2021-01-10', 'Income from Fiverr', 2397.00, 19, 2, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(23, 2, 1, '2022-08-03', 'Income from Freelancer', 1686.00, 19, 2, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(24, 2, 1, '2022-04-12', 'Income from Project', 6348.00, 19, 2, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(25, 1, 1, '2021-10-28', 'Income from Transfer', 4894.00, 19, 2, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(26, 1, 1, '2022-07-02', 'Income from Upwork', 9726.00, 19, 2, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(27, 1, 1, '2022-09-14', 'Income from Fiverr', 7165.00, 19, 2, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(28, 2, 1, '2022-12-13', 'Income from Freelancer', 4562.00, 19, 2, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(29, 1, 1, '2021-11-26', 'Income from Project', 3099.00, 19, 2, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(30, 2, 1, '2021-12-01', 'Income from Transfer', 9370.00, 19, 2, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(31, 2, 1, '2021-08-23', 'Income from Upwork', 5881.00, 19, 2, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(32, 2, 1, '2021-06-08', 'Income from Fiverr', 9592.00, 19, 2, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(33, 2, 1, '2021-01-19', 'Income from Freelancer', 4919.00, 19, 2, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(34, 1, 1, '2022-01-21', 'Income from Project', 2598.00, 19, 2, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(35, 2, 1, '2021-08-16', 'Income from Transfer', 6443.00, 19, 2, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(36, 2, 1, '2022-09-08', 'Income from Upwork', 2190.00, 19, 2, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(37, 2, 1, '2021-12-18', 'Income from Fiverr', 8853.00, 19, 2, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(38, 1, 1, '2021-12-04', 'Income from Freelancer', 5172.00, 19, 2, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(39, 1, 1, '2022-08-01', 'Income from Project', 1983.00, 19, 2, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(40, 2, 1, '2022-06-11', 'Income from Transfer', 1565.00, 19, 2, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(41, 2, 1, '2021-07-25', 'Income from Upwork', 1181.00, 19, 3, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(42, 2, 1, '2021-08-19', 'Income from Fiverr', 6315.00, 19, 3, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(43, 2, 1, '2021-05-02', 'Income from Freelancer', 4742.00, 19, 3, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(44, 1, 1, '2022-04-19', 'Income from Project', 4452.00, 19, 3, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(45, 2, 1, '2021-10-07', 'Income from Transfer', 7823.00, 19, 3, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(46, 2, 1, '2021-04-13', 'Income from Upwork', 3905.00, 19, 3, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(47, 2, 1, '2021-05-14', 'Income from Fiverr', 4441.00, 19, 3, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(48, 2, 1, '2021-02-26', 'Income from Freelancer', 3855.00, 19, 3, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(49, 2, 1, '2021-12-25', 'Income from Project', 3671.00, 19, 3, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(50, 1, 1, '2022-07-18', 'Income from Transfer', 8607.00, 19, 3, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(51, 1, 1, '2021-11-06', 'Income from Upwork', 1735.00, 19, 3, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(52, 1, 1, '2022-04-23', 'Income from Fiverr', 8187.00, 19, 3, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(53, 1, 1, '2022-06-08', 'Income from Freelancer', 4826.00, 19, 3, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(54, 1, 1, '2021-01-22', 'Income from Project', 1538.00, 19, 3, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(55, 2, 1, '2022-11-21', 'Income from Transfer', 2752.00, 19, 3, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(56, 1, 1, '2022-05-26', 'Income from Upwork', 7484.00, 19, 3, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(57, 2, 1, '2021-04-25', 'Income from Fiverr', 3591.00, 19, 3, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(58, 1, 1, '2022-09-22', 'Income from Freelancer', 2858.00, 19, 3, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(59, 1, 1, '2021-05-13', 'Income from Project', 8505.00, 19, 3, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(60, 1, 1, '2021-06-24', 'Income from Transfer', 6472.00, 19, 3, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(61, 2, 1, '2022-11-20', 'Income from Upwork', 7082.00, 19, 4, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(62, 1, 1, '2022-01-21', 'Income from Fiverr', 1604.00, 19, 4, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(63, 2, 1, '2022-07-28', 'Income from Freelancer', 1045.00, 19, 4, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(64, 1, 1, '2022-07-08', 'Income from Project', 8412.00, 19, 4, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(65, 1, 1, '2021-05-13', 'Income from Transfer', 3762.00, 19, 4, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(66, 2, 1, '2021-09-21', 'Income from Upwork', 7852.00, 19, 4, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(67, 1, 1, '2021-03-28', 'Income from Fiverr', 9609.00, 19, 4, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(68, 2, 1, '2021-01-22', 'Income from Freelancer', 7690.00, 19, 4, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(69, 2, 1, '2021-11-12', 'Income from Project', 5624.00, 19, 4, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(70, 1, 1, '2021-05-13', 'Income from Transfer', 6357.00, 19, 4, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(71, 1, 1, '2022-10-01', 'Income from Upwork', 6966.00, 19, 4, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(72, 2, 1, '2021-09-06', 'Income from Fiverr', 8272.00, 19, 4, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(73, 2, 1, '2022-02-01', 'Income from Freelancer', 1979.00, 19, 4, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(74, 2, 1, '2021-03-10', 'Income from Project', 6283.00, 19, 4, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(75, 1, 1, '2022-04-24', 'Income from Transfer', 2667.00, 19, 4, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(76, 2, 1, '2022-05-01', 'Income from Upwork', 6927.00, 19, 4, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(77, 2, 1, '2021-04-24', 'Income from Fiverr', 1756.00, 19, 4, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(78, 1, 1, '2021-09-16', 'Income from Freelancer', 2021.00, 19, 4, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(79, 1, 1, '2022-11-05', 'Income from Project', 7554.00, 19, 4, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(80, 1, 1, '2021-07-14', 'Income from Transfer', 9772.00, 19, 4, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(81, 1, 1, '2022-05-17', 'Income from Upwork', 6947.00, 19, 5, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(82, 1, 1, '2022-10-27', 'Income from Fiverr', 4373.00, 19, 5, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(83, 1, 1, '2021-08-16', 'Income from Freelancer', 1138.00, 19, 5, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(84, 1, 1, '2021-01-20', 'Income from Project', 5247.00, 19, 5, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(85, 1, 1, '2022-01-10', 'Income from Transfer', 8729.00, 19, 5, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(86, 1, 1, '2022-08-15', 'Income from Upwork', 5814.00, 19, 5, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(87, 2, 1, '2022-07-16', 'Income from Fiverr', 3815.00, 19, 5, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(88, 1, 1, '2022-11-06', 'Income from Freelancer', 1724.00, 19, 5, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(89, 1, 1, '2021-12-03', 'Income from Project', 7007.00, 19, 5, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(90, 2, 1, '2022-02-27', 'Income from Transfer', 3898.00, 19, 5, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(91, 2, 1, '2021-04-27', 'Income from Upwork', 7954.00, 19, 5, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(92, 2, 1, '2022-11-10', 'Income from Fiverr', 7575.00, 19, 5, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(93, 2, 1, '2021-07-05', 'Income from Freelancer', 4357.00, 19, 5, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(94, 1, 1, '2021-03-04', 'Income from Project', 5344.00, 19, 5, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(95, 2, 1, '2021-01-13', 'Income from Transfer', 1369.00, 19, 5, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(96, 1, 1, '2021-01-14', 'Income from Upwork', 6598.00, 19, 5, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(97, 2, 1, '2022-06-14', 'Income from Fiverr', 9287.00, 19, 5, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(98, 2, 1, '2021-07-21', 'Income from Freelancer', 3574.00, 19, 5, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(99, 2, 1, '2021-03-22', 'Income from Project', 1056.00, 19, 5, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(100, 1, 1, '2022-01-06', 'Income from Transfer', 2535.00, 19, 5, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(101, 1, 1, '2021-02-01', 'Income from Upwork', 4353.00, 19, 6, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(102, 1, 1, '2021-03-16', 'Income from Fiverr', 5548.00, 19, 6, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(103, 1, 1, '2021-01-23', 'Income from Freelancer', 3762.00, 19, 6, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(104, 1, 1, '2022-08-13', 'Income from Project', 9334.00, 19, 6, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(105, 1, 1, '2021-12-04', 'Income from Transfer', 4608.00, 19, 6, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(106, 1, 1, '2021-03-14', 'Income from Upwork', 8542.00, 19, 6, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(107, 2, 1, '2022-04-05', 'Income from Fiverr', 5590.00, 19, 6, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(108, 2, 1, '2021-03-26', 'Income from Freelancer', 5735.00, 19, 6, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(109, 2, 1, '2021-05-02', 'Income from Project', 8848.00, 19, 6, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(110, 1, 1, '2021-09-15', 'Income from Transfer', 6260.00, 19, 6, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(111, 1, 1, '2022-11-15', 'Income from Upwork', 8636.00, 19, 6, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(112, 2, 1, '2021-04-24', 'Income from Fiverr', 1038.00, 19, 6, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(113, 2, 1, '2021-04-21', 'Income from Freelancer', 2105.00, 19, 6, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(114, 1, 1, '2022-07-15', 'Income from Project', 4657.00, 19, 6, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(115, 1, 1, '2021-09-22', 'Income from Transfer', 4785.00, 19, 6, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(116, 1, 1, '2021-02-25', 'Income from Upwork', 6533.00, 19, 6, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(117, 2, 1, '2021-10-27', 'Income from Fiverr', 4425.00, 19, 6, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(118, 2, 1, '2021-08-14', 'Income from Freelancer', 2332.00, 19, 6, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(119, 2, 1, '2022-04-08', 'Income from Project', 7143.00, 19, 6, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(120, 2, 1, '2021-01-15', 'Income from Transfer', 7141.00, 19, 6, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(121, 2, 1, '2022-09-25', 'Income from Upwork', 2911.00, 19, 7, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(122, 1, 1, '2022-05-11', 'Income from Fiverr', 5797.00, 19, 7, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(123, 1, 1, '2021-11-21', 'Income from Freelancer', 8457.00, 19, 7, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(124, 2, 1, '2022-09-05', 'Income from Project', 4511.00, 19, 7, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(125, 2, 1, '2021-02-05', 'Income from Transfer', 3467.00, 19, 7, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(126, 1, 1, '2021-03-13', 'Income from Upwork', 8862.00, 19, 7, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(127, 2, 1, '2021-08-07', 'Income from Fiverr', 9928.00, 19, 7, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(128, 2, 1, '2022-03-15', 'Income from Freelancer', 5432.00, 19, 7, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(129, 1, 1, '2022-03-08', 'Income from Project', 7704.00, 19, 7, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(130, 1, 1, '2021-04-22', 'Income from Transfer', 4928.00, 19, 7, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(131, 2, 1, '2022-08-05', 'Income from Upwork', 5139.00, 19, 7, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(132, 1, 1, '2022-10-05', 'Income from Fiverr', 8814.00, 19, 7, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(133, 2, 1, '2022-11-17', 'Income from Freelancer', 7896.00, 19, 7, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(134, 1, 1, '2022-11-01', 'Income from Project', 3233.00, 19, 7, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(135, 1, 1, '2022-04-22', 'Income from Transfer', 5439.00, 19, 7, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(136, 2, 1, '2021-08-01', 'Income from Upwork', 6498.00, 19, 7, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(137, 2, 1, '2021-04-01', 'Income from Fiverr', 1604.00, 19, 7, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(138, 2, 1, '2021-09-10', 'Income from Freelancer', 8520.00, 19, 7, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(139, 2, 1, '2022-07-07', 'Income from Project', 8940.00, 19, 7, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(140, 2, 1, '2021-10-06', 'Income from Transfer', 6069.00, 19, 7, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(141, 1, 1, '2021-09-13', 'Income from Upwork', 5500.00, 19, 8, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(142, 2, 1, '2022-12-06', 'Income from Fiverr', 6148.00, 19, 8, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(143, 1, 1, '2022-01-04', 'Income from Freelancer', 6590.00, 19, 8, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(144, 1, 1, '2021-10-26', 'Income from Project', 8358.00, 19, 8, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(145, 1, 1, '2022-06-15', 'Income from Transfer', 4635.00, 19, 8, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(146, 2, 1, '2021-02-17', 'Income from Upwork', 3763.00, 19, 8, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(147, 2, 1, '2022-04-11', 'Income from Fiverr', 8376.00, 19, 8, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(148, 1, 1, '2021-07-02', 'Income from Freelancer', 6663.00, 19, 8, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(149, 2, 1, '2021-09-02', 'Income from Project', 7417.00, 19, 8, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(150, 2, 1, '2022-10-03', 'Income from Transfer', 8468.00, 19, 8, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(151, 1, 1, '2022-08-19', 'Income from Upwork', 9220.00, 19, 8, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(152, 1, 1, '2022-10-24', 'Income from Fiverr', 2766.00, 19, 8, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(153, 2, 1, '2022-10-20', 'Income from Freelancer', 1973.00, 19, 8, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(154, 2, 1, '2022-08-16', 'Income from Project', 8024.00, 19, 8, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(155, 1, 1, '2021-09-27', 'Income from Transfer', 4682.00, 19, 8, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(156, 1, 1, '2022-03-22', 'Income from Upwork', 1898.00, 19, 8, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(157, 1, 1, '2022-10-20', 'Income from Fiverr', 8213.00, 19, 8, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(158, 1, 1, '2021-10-01', 'Income from Freelancer', 4869.00, 19, 8, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(159, 2, 1, '2022-05-27', 'Income from Project', 2217.00, 19, 8, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(160, 2, 1, '2021-05-27', 'Income from Transfer', 8040.00, 19, 8, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(161, 2, 1, '2022-02-08', 'Income from Upwork', 2294.00, 19, 9, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(162, 1, 1, '2021-11-14', 'Income from Fiverr', 4419.00, 19, 9, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(163, 2, 1, '2021-03-20', 'Income from Freelancer', 9206.00, 19, 9, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(164, 1, 1, '2021-05-20', 'Income from Project', 3387.00, 19, 9, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(165, 2, 1, '2022-06-27', 'Income from Transfer', 7361.00, 19, 9, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(166, 1, 1, '2022-12-03', 'Income from Upwork', 7773.00, 19, 9, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(167, 1, 1, '2021-01-21', 'Income from Fiverr', 9488.00, 19, 9, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(168, 1, 1, '2021-09-22', 'Income from Freelancer', 3623.00, 19, 9, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(169, 2, 1, '2021-09-05', 'Income from Project', 7915.00, 19, 9, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(170, 2, 1, '2022-05-26', 'Income from Transfer', 1551.00, 19, 9, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(171, 1, 1, '2022-12-15', 'Income from Upwork', 4934.00, 19, 9, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(172, 2, 1, '2021-04-14', 'Income from Fiverr', 9389.00, 19, 9, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(173, 2, 1, '2021-04-10', 'Income from Freelancer', 3180.00, 19, 9, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(174, 1, 1, '2022-11-16', 'Income from Project', 9943.00, 19, 9, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(175, 1, 1, '2021-02-08', 'Income from Transfer', 5128.00, 19, 9, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(176, 2, 1, '2021-04-16', 'Income from Upwork', 6947.00, 19, 9, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(177, 1, 1, '2021-09-07', 'Income from Fiverr', 6445.00, 19, 9, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(178, 1, 1, '2022-03-20', 'Income from Freelancer', 5051.00, 19, 9, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(179, 1, 1, '2022-10-16', 'Income from Project', 7970.00, 19, 9, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(180, 1, 1, '2021-06-18', 'Income from Transfer', 1797.00, 19, 9, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(181, 2, 1, '2021-10-17', 'Income from Upwork', 5964.00, 19, 10, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(182, 1, 1, '2021-06-07', 'Income from Fiverr', 7421.00, 19, 10, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(183, 1, 1, '2022-03-18', 'Income from Freelancer', 7642.00, 19, 10, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(184, 1, 1, '2021-07-08', 'Income from Project', 5233.00, 19, 10, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(185, 1, 1, '2022-05-20', 'Income from Transfer', 8652.00, 19, 10, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(186, 1, 1, '2022-01-18', 'Income from Upwork', 7111.00, 19, 10, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(187, 1, 1, '2022-01-28', 'Income from Fiverr', 8385.00, 19, 10, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(188, 2, 1, '2021-02-23', 'Income from Freelancer', 3216.00, 19, 10, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(189, 1, 1, '2021-06-03', 'Income from Project', 7546.00, 19, 10, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(190, 1, 1, '2021-06-04', 'Income from Transfer', 1953.00, 19, 10, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(191, 1, 1, '2021-10-19', 'Income from Upwork', 8849.00, 19, 10, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(192, 1, 1, '2021-08-14', 'Income from Fiverr', 7506.00, 19, 10, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(193, 1, 1, '2021-07-07', 'Income from Freelancer', 5767.00, 19, 10, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(194, 2, 1, '2022-02-12', 'Income from Project', 2563.00, 19, 10, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(195, 1, 1, '2021-03-09', 'Income from Transfer', 5460.00, 19, 10, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(196, 1, 1, '2021-03-24', 'Income from Upwork', 9322.00, 19, 10, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(197, 2, 1, '2022-11-26', 'Income from Fiverr', 7839.00, 19, 10, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(198, 2, 1, '2022-06-27', 'Income from Freelancer', 5709.00, 19, 10, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(199, 1, 1, '2022-02-14', 'Income from Project', 4064.00, 19, 10, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(200, 2, 1, '2021-01-04', 'Income from Transfer', 1410.00, 19, 10, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(201, 1, 1, '2021-01-03', 'Expense from Rent', 7820.00, 18, 10, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(202, 2, 1, '2022-03-26', 'Expense from Electricity', 9030.00, 18, 10, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(203, 2, 1, '2022-05-20', 'Expense from Internet', 9651.00, 18, 10, NULL, 9, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(204, 2, 1, '2021-08-09', 'Expense from Water', 2563.00, 18, 10, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(205, 1, 1, '2021-11-19', 'Expense from Transfer', 5511.00, 18, 10, NULL, 8, 1, 1, '2023-04-18 01:08:05', '2023-04-18 01:08:05'),
(206, 2, 1, '2021-01-25', 'Expense from Rent', 9455.00, 18, 10, NULL, 8, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(207, 2, 1, '2021-02-26', 'Expense from Electricity', 7062.00, 18, 10, NULL, 9, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(208, 1, 1, '2022-07-21', 'Expense from Internet', 4595.00, 18, 10, NULL, 9, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(209, 1, 1, '2021-01-05', 'Expense from Water', 6179.00, 18, 10, NULL, 9, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(210, 1, 1, '2022-02-18', 'Expense from Transfer', 1058.00, 18, 10, NULL, 8, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(211, 1, 1, '2021-09-15', 'Expense from Rent', 9557.00, 18, 10, NULL, 9, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(212, 2, 1, '2021-06-19', 'Expense from Electricity', 6220.00, 18, 10, NULL, 8, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(213, 1, 1, '2022-01-03', 'Expense from Internet', 4107.00, 18, 10, NULL, 9, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(214, 2, 1, '2022-10-24', 'Expense from Water', 8961.00, 18, 10, NULL, 8, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(215, 1, 1, '2021-02-15', 'Expense from Transfer', 3642.00, 18, 10, NULL, 9, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(216, 1, 1, '2022-09-17', 'Expense from Rent', 4727.00, 18, 10, NULL, 8, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(217, 1, 1, '2022-05-12', 'Expense from Electricity', 8080.00, 18, 10, NULL, 9, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(218, 1, 1, '2021-09-23', 'Expense from Internet', 6894.00, 18, 10, NULL, 9, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(219, 2, 1, '2021-08-12', 'Expense from Water', 8503.00, 18, 10, NULL, 9, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06'),
(220, 2, 1, '2021-05-02', 'Expense from Transfer', 6007.00, 18, 10, NULL, 8, 1, 1, '2023-04-18 01:08:06', '2023-04-18 01:08:06');

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE `translations` (
  `id` bigint UNSIGNED NOT NULL,
  `default` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `en` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `bn` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `travel`
--

CREATE TABLE `travel` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `travel_type_id` bigint UNSIGNED NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `expect_amount` double(16,2) DEFAULT NULL,
  `amount` double(16,2) DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` bigint UNSIGNED DEFAULT NULL,
  `purpose` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `place` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mode` enum('bus','train','plane') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `goal_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `travel`
--

INSERT INTO `travel` (`id`, `company_id`, `user_id`, `created_by`, `travel_type_id`, `start_date`, `end_date`, `status_id`, `expect_amount`, `amount`, `description`, `attachment`, `purpose`, `place`, `mode`, `goal_id`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 2, 1, '2023-04-18', '2023-04-25', 1, 100.00, 100.00, '<strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', NULL, 'test', 'test', 'bus', NULL, '2023-04-18 01:07:53', '2023-04-18 01:07:53');

-- --------------------------------------------------------

--
-- Table structure for table `travel_types`
--

CREATE TABLE `travel_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT '1',
  `updated_by` bigint UNSIGNED DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `travel_types`
--

INSERT INTO `travel_types` (`id`, `name`, `company_id`, `status_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Business', 2, 2, 2, 1, '2023-04-18 01:07:53', '2023-04-18 01:07:53'),
(2, 'Personal', 2, 2, 2, 1, '2023-04-18 01:07:53', '2023-04-18 01:07:53'),
(3, 'Vacation', 2, 2, 2, 1, '2023-04-18 01:07:53', '2023-04-18 01:07:53'),
(4, 'Other', 2, 2, 2, 1, '2023-04-18 01:07:53', '2023-04-18 01:07:53');

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `file_original_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `big_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '1920 x 1080',
  `small_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '300 x 300',
  `thumbnail_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '500 x 400',
  `extension` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `width` int DEFAULT NULL,
  `height` int DEFAULT NULL,
  `status_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id`, `user_id`, `file_original_name`, `file_name`, `img_path`, `big_path`, `small_path`, `thumbnail_path`, `extension`, `type`, `file_size`, `width`, `height`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'dark_logo', 'dark.png', '/assets/images/dark.png', '/assets/images/dark.png', '/assets/images/dark.png', '/assets/images/dark.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(2, 1, 'white_logo', 'white.png', '/assets/images/white.png', '/assets/images/white.png', '/assets/images/white.png', '/assets/images/white.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(3, 1, 'fav', 'fav.png', '/assets/images/favicon.png', '/assets/images/favicon.png', '/assets/images/favicon.png', '/assets/images/favicon.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(4, 1, 'background_image', 'background_image.png', 'public/static/background_image.png', 'public/static/background_image.png', 'public/static/background_image.png', 'public/static/background_image.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(5, 1, 'android_icon', 'android_icon.png', 'public/static/android_icon.png', 'public/static/android_icon.png', 'public/static/android_icon.png', 'public/static/android_icon.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(6, 1, 'iso_icon', 'iso_icon.png', 'public/static/iso_icon.png', 'public/static/iso_icon.png', 'public/static/iso_icon.png', 'public/static/iso_icon.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(7, 1, 'support', 'support.png', 'public/static/support.png', 'public/static/support.png', 'public/static/support.png', 'public/static/support.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(8, 1, 'attendance', 'attendance.png', 'public/static/attendance.png', 'public/static/attendance.png', 'public/static/attendance.png', 'public/static/attendance.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(9, 1, 'notice', 'notice.png', 'public/static/notice.png', 'public/static/notice.png', 'public/static/notice.png', 'public/static/notice.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(10, 1, 'expense', 'expense.png', 'public/static/expense.png', 'public/static/expense.png', 'public/static/expense.png', 'public/static/expense.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(11, 1, 'leave', 'leave.png', 'public/static/leave.png', 'public/static/leave.png', 'public/static/leave.png', 'public/static/leave.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(12, 1, 'approval', 'approval.png', 'public/static/approval.png', 'public/static/approval.png', 'public/static/approval.png', 'public/static/approval.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(13, 1, 'phonebook', 'phonebook.png', 'public/static/phonebook.png', 'public/static/phonebook.png', 'public/static/phonebook.png', 'public/static/phonebook.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(14, 1, 'visit', 'visit.png', 'public/static/visit.png', 'public/static/visit.png', 'public/static/visit.png', 'public/static/visit.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(15, 1, 'appointments', 'appointments.png', 'public/static/appointments.png', 'public/static/appointments.png', 'public/static/appointments.png', 'public/static/appointments.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(16, 1, 'break', 'break.png', 'public/static/break.png', 'public/static/break.png', 'public/static/break.png', 'public/static/break.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(17, 1, 'report', 'report.png', 'public/static/report.png', 'public/static/report.png', 'public/static/report.png', 'public/static/report.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(21, 1, 'portfolio1', 'portfolio1.png', 'public/static/portfolio1.png', 'public/static/portfolio1.png', 'public/static/portfolio1.png', 'public/static/portfolio1.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(22, 1, 'portfolio2', 'portfolio2.png', 'public/static/portfolio2.png', 'public/static/portfolio2.png', 'public/static/portfolio2.png', 'public/static/portfolio2.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(23, 1, 'portfolio3', 'portfolio3.png', 'public/static/portfolio3.png', 'public/static/portfolio3.png', 'public/static/portfolio3.png', 'public/static/portfolio3.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(24, 1, 'portfolio4', 'portfolio4.png', 'public/static/portfolio4.png', 'public/static/portfolio4.png', 'public/static/portfolio4.png', 'public/static/portfolio4.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(25, 1, 'portfolio5', 'portfolio5.png', 'public/static/portfolio5.png', 'public/static/portfolio5.png', 'public/static/portfolio5.png', 'public/static/portfolio5.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(26, 1, 'portfolio6', 'portfolio6.png', 'public/static/portfolio6.png', 'public/static/portfolio6.png', 'public/static/portfolio6.png', 'public/static/portfolio6.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(27, 1, 'portfolio7', 'portfolio7.png', 'public/static/portfolio7.png', 'public/static/portfolio7.png', 'public/static/portfolio7.png', 'public/static/portfolio7.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(28, 1, 'portfolio8', 'portfolio8.png', 'public/static/portfolio8.png', 'public/static/portfolio8.png', 'public/static/portfolio8.png', 'public/static/portfolio8.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(29, 1, 'team1', 'team1.png', 'public/static/team1.png', 'public/static/team1.png', 'public/static/team1.png', 'public/static/team1.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(30, 1, 'team2', 'team2.png', 'public/static/team2.png', 'public/static/team2.png', 'public/static/team2.png', 'public/static/team2.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(31, 1, 'team3', 'team3.png', 'public/static/team3.png', 'public/static/team3.png', 'public/static/team3.png', 'public/static/team3.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(32, 1, 'team4', 'team4.png', 'public/static/team4.png', 'public/static/team4.png', 'public/static/team4.png', 'public/static/team4.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(33, 1, 'team5', 'team5.png', 'public/static/team5.png', 'public/static/team5.png', 'public/static/team5.png', 'public/static/team5.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(34, 1, 'team6', 'team6.png', 'public/static/team6.png', 'public/static/team6.png', 'public/static/team6.png', 'public/static/team6.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(35, 1, 'team7', 'team7.png', 'public/static/team7.png', 'public/static/team7.png', 'public/static/team7.png', 'public/static/team7.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(36, 1, 'team8', 'team8.png', 'public/static/team8.png', 'public/static/team8.png', 'public/static/team8.png', 'public/static/team8.png', '.png', 'png', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(37, 1, '1', '1.jpg', 'public/uploads/avatar/1.jpg', 'public/uploads/avatar/1.jpg', 'public/uploads/avatar/1.jpg', 'public/uploads/avatar/1.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(38, 1, '2', '2.jpg', 'public/uploads/avatar/2.jpg', 'public/uploads/avatar/2.jpg', 'public/uploads/avatar/2.jpg', 'public/uploads/avatar/2.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(39, 1, '3', '3.jpg', 'public/uploads/avatar/3.jpg', 'public/uploads/avatar/3.jpg', 'public/uploads/avatar/3.jpg', 'public/uploads/avatar/3.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(40, 1, '4', '4.jpg', 'public/uploads/avatar/4.jpg', 'public/uploads/avatar/4.jpg', 'public/uploads/avatar/4.jpg', 'public/uploads/avatar/4.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(41, 1, '5', '5.jpg', 'public/uploads/avatar/5.jpg', 'public/uploads/avatar/5.jpg', 'public/uploads/avatar/5.jpg', 'public/uploads/avatar/5.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(42, 1, '6', '6.jpg', 'public/uploads/avatar/6.jpg', 'public/uploads/avatar/6.jpg', 'public/uploads/avatar/6.jpg', 'public/uploads/avatar/6.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(43, 1, '7', '7.jpg', 'public/uploads/avatar/7.jpg', 'public/uploads/avatar/7.jpg', 'public/uploads/avatar/7.jpg', 'public/uploads/avatar/7.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(44, 1, '8', '8.jpg', 'public/uploads/avatar/8.jpg', 'public/uploads/avatar/8.jpg', 'public/uploads/avatar/8.jpg', 'public/uploads/avatar/8.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(45, 1, '9', '9.jpg', 'public/uploads/avatar/9.jpg', 'public/uploads/avatar/9.jpg', 'public/uploads/avatar/9.jpg', 'public/uploads/avatar/9.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(46, 1, '10', '10.jpg', 'public/uploads/avatar/10.jpg', 'public/uploads/avatar/10.jpg', 'public/uploads/avatar/10.jpg', 'public/uploads/avatar/10.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(47, 1, '11', '11.jpg', 'public/uploads/avatar/11.jpg', 'public/uploads/avatar/11.jpg', 'public/uploads/avatar/11.jpg', 'public/uploads/avatar/11.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(48, 1, '12', '12.jpg', 'public/uploads/avatar/12.jpg', 'public/uploads/avatar/12.jpg', 'public/uploads/avatar/12.jpg', 'public/uploads/avatar/12.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(49, 1, '13', '13.jpg', 'public/uploads/avatar/13.jpg', 'public/uploads/avatar/13.jpg', 'public/uploads/avatar/13.jpg', 'public/uploads/avatar/13.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(50, 1, '14', '14.jpg', 'public/uploads/avatar/14.jpg', 'public/uploads/avatar/14.jpg', 'public/uploads/avatar/14.jpg', 'public/uploads/avatar/14.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(51, 1, '15', '15.jpg', 'public/uploads/avatar/15.jpg', 'public/uploads/avatar/15.jpg', 'public/uploads/avatar/15.jpg', 'public/uploads/avatar/15.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(52, 1, '16', '16.jpg', 'public/uploads/avatar/16.jpg', 'public/uploads/avatar/16.jpg', 'public/uploads/avatar/16.jpg', 'public/uploads/avatar/16.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(53, 1, '17', '17.jpg', 'public/uploads/avatar/17.jpg', 'public/uploads/avatar/17.jpg', 'public/uploads/avatar/17.jpg', 'public/uploads/avatar/17.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(54, 1, '18', '18.jpg', 'public/uploads/avatar/18.jpg', 'public/uploads/avatar/18.jpg', 'public/uploads/avatar/18.jpg', 'public/uploads/avatar/18.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(55, 1, '19', '19.jpg', 'public/uploads/avatar/19.jpg', 'public/uploads/avatar/19.jpg', 'public/uploads/avatar/19.jpg', 'public/uploads/avatar/19.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(56, 1, '20', '20.jpg', 'public/uploads/avatar/20.jpg', 'public/uploads/avatar/20.jpg', 'public/uploads/avatar/20.jpg', 'public/uploads/avatar/20.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(57, 1, '20', '1.jpg', 'public/uploads/avatar/projects/1.jpg', 'public/uploads/avatar/projects/1.jpg', 'public/uploads/avatar/projects/1.jpg', 'public/uploads/avatar/projects/1.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(58, 1, '20', '2.jpg', 'public/uploads/avatar/projects/2.jpg', 'public/uploads/avatar/projects/2.jpg', 'public/uploads/avatar/projects/2.jpg', 'public/uploads/avatar/projects/2.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(59, 1, '20', '3.jpg', 'public/uploads/avatar/projects/3.jpg', 'public/uploads/avatar/projects/3.jpg', 'public/uploads/avatar/projects/3.jpg', 'public/uploads/avatar/projects/3.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(60, 1, '20', '4.jpg', 'public/uploads/avatar/projects/4.jpg', 'public/uploads/avatar/projects/4.jpg', 'public/uploads/avatar/projects/4.jpg', 'public/uploads/avatar/projects/4.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(61, 1, '20', '5.jpg', 'public/uploads/avatar/projects/5.jpg', 'public/uploads/avatar/projects/5.jpg', 'public/uploads/avatar/projects/5.jpg', 'public/uploads/avatar/projects/5.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(62, 1, '20', '6.jpg', 'public/uploads/avatar/projects/6.jpg', 'public/uploads/avatar/projects/6.jpg', 'public/uploads/avatar/projects/6.jpg', 'public/uploads/avatar/projects/6.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(63, 1, '20', '7.jpg', 'public/uploads/avatar/projects/7.jpg', 'public/uploads/avatar/projects/7.jpg', 'public/uploads/avatar/projects/7.jpg', 'public/uploads/avatar/projects/7.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(64, 1, '20', '8.jpg', 'public/uploads/avatar/projects/8.jpg', 'public/uploads/avatar/projects/8.jpg', 'public/uploads/avatar/projects/8.jpg', 'public/uploads/avatar/projects/8.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(65, 1, '20', '9.jpg', 'public/uploads/avatar/projects/9.jpg', 'public/uploads/avatar/projects/9.jpg', 'public/uploads/avatar/projects/9.jpg', 'public/uploads/avatar/projects/9.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(66, 1, '20', '10.jpg', 'public/uploads/avatar/projects/10.jpg', 'public/uploads/avatar/projects/10.jpg', 'public/uploads/avatar/projects/10.jpg', 'public/uploads/avatar/projects/10.jpg', '.jpg', 'jpg', 0, 100, 100, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `country_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `userID` int DEFAULT NULL,
  `face_recognition` tinyint DEFAULT '1',
  `face_data` longtext COLLATE utf8mb4_unicode_ci,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `is_hr` tinyint DEFAULT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `shift_id` bigint UNSIGNED DEFAULT NULL,
  `designation_id` bigint UNSIGNED DEFAULT NULL,
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `verification_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'email verification code',
  `manager_id` bigint UNSIGNED DEFAULT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_type` enum('Permanent','On Probation','Contractual','Intern') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'On Probation',
  `grade` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nid_card_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nid_card_id` bigint UNSIGNED DEFAULT NULL COMMENT 'this will be uploaded file',
  `facebook_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_file_id` bigint UNSIGNED DEFAULT NULL COMMENT 'this will be passport file',
  `tin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tin_id_front_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tin_id_back_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_mobile_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_mobile_relationship` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verify_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'email verify token',
  `is_email_verified` enum('verified','non-verified') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'verified',
  `email_verified_at` timestamp NULL DEFAULT NULL COMMENT 'email verified at',
  `phone_verify_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'phone verify token',
  `is_phone_verified` enum('verified','non-verified') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'verified',
  `phone_verified_at` timestamp NULL DEFAULT NULL COMMENT 'phone verified at',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hints` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'user can set a password hint for easy remember',
  `avatar_id` bigint UNSIGNED DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `last_login_at` timestamp NULL DEFAULT NULL COMMENT 'last login at',
  `last_logout_at` timestamp NULL DEFAULT NULL COMMENT 'last logout at',
  `last_login_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'last login ip',
  `device_token` longtext COLLATE utf8mb4_unicode_ci COMMENT 'device_token from firebase',
  `login_access` tinyint NOT NULL DEFAULT '1' COMMENT '0 = off, 1 = on',
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('Male','Female','Unisex','Others') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `religion` enum('Islam','Hindu','Christan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Islam',
  `blood_group` enum('A+','A-','B+','B-','O+','O-','AB+','AB-') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `basic_salary` double(16,2) NOT NULL DEFAULT '0.00',
  `marital_status` enum('Married','Unmarried') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unmarried',
  `contract_start_date` date DEFAULT NULL,
  `contract_end_date` date DEFAULT NULL,
  `payslip_type` tinyint NOT NULL DEFAULT '1' COMMENT '1 = monthly, 2 = weekly, 3 = daily',
  `late_check_in` int NOT NULL DEFAULT '0',
  `early_check_out` int NOT NULL DEFAULT '0',
  `extra_leave` int NOT NULL DEFAULT '0',
  `monthly_leave` int NOT NULL DEFAULT '0',
  `is_free_location` tinyint NOT NULL DEFAULT '0',
  `time_zone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asia/Dhaka',
  `social_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_me` text COLLATE utf8mb4_unicode_ci,
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pm_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pm_last_four` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `company_id`, `country_id`, `name`, `userID`, `face_recognition`, `face_data`, `email`, `phone`, `is_admin`, `is_hr`, `role_id`, `department_id`, `shift_id`, `designation_id`, `permissions`, `verification_code`, `manager_id`, `employee_id`, `employee_type`, `grade`, `nationality`, `nid_card_number`, `nid_card_id`, `facebook_link`, `linkedin_link`, `instagram_link`, `passport_number`, `passport_file_id`, `tin`, `tin_id_front_file`, `tin_id_back_file`, `bank_name`, `bank_account`, `emergency_name`, `emergency_mobile_number`, `emergency_mobile_relationship`, `email_verify_token`, `is_email_verified`, `email_verified_at`, `phone_verify_token`, `is_phone_verified`, `phone_verified_at`, `password`, `password_hints`, `avatar_id`, `status_id`, `last_login_at`, `last_logout_at`, `last_login_ip`, `device_token`, `login_access`, `address`, `gender`, `birth_date`, `religion`, `blood_group`, `joining_date`, `basic_salary`, `marital_status`, `contract_start_date`, `contract_end_date`, `payslip_type`, `late_check_in`, `early_check_out`, `extra_leave`, `monthly_leave`, `is_free_location`, `time_zone`, `social_id`, `social_type`, `about_me`, `lang`, `remember_token`, `deleted_at`, `created_at`, `updated_at`, `stripe_id`, `pm_type`, `pm_last_four`, `trial_ends_at`) VALUES
(1, 1, 17, 'Super Admin', NULL, 1, NULL, 'superadmin@onesttech.com', '+8801910077628', '1', 0, 1, 1, 1, 1, '[\"company_read\",\"company_create\",\"company_update\",\"company_delete\",\"user_banned\",\"user_unbanned\",\"general_settings_read\",\"general_settings_update\",\"email_settings_read\",\"email_settings_update\",\"storage_settings_update\",\"user_read\",\"user_edit\",\"user_update\",\"content_update\",\"subscriptions_read\"]', NULL, NULL, 'EMP-1', 'On Probation', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '9THd7SwuJL', 'verified', '2023-04-18 01:07:41', NULL, 'verified', NULL, '$2y$10$LR9NMSTnMHSX99yL1Rdrr.wgO/aXF1m4i0dnkdDmO2PDhpVqsjN4i', NULL, 55, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 'Islam', NULL, NULL, 0.00, 'Unmarried', NULL, NULL, 1, 0, 0, 0, 0, 0, 'Asia/Dhaka', NULL, NULL, NULL, NULL, '41Qqy6oJT7', NULL, '2023-04-18 01:07:41', '2023-04-18 01:07:41', NULL, NULL, NULL, NULL),
(2, 2, 17, 'Admin', NULL, 1, NULL, 'admin@onesttech.com', '+880177777777', '1', 0, 6, 4, 3, 7, '[\"team_menu\",\"team_list\",\"team_create\",\"team_update\",\"team_edit\",\"team_delete\",\"team_member_view\",\"team_member_create\",\"team_member_edit\",\"team_member_delete\",\"team_member_assign\",\"team_member_unassign\",\"dashboard\",\"hr_menu\",\"designation_read\",\"designation_create\",\"designation_update\",\"designation_delete\",\"shift_read\",\"shift_create\",\"shift_update\",\"shift_delete\",\"department_read\",\"department_create\",\"department_update\",\"department_delete\",\"user_menu\",\"user_read\",\"profile_view\",\"user_create\",\"user_edit\",\"user_update\",\"user_delete\",\"user_banned\",\"user_unbanned\",\"make_hr\",\"user_permission\",\"profile_image_view\",\"phonebook_profile\",\"support_ticket_profile\",\"advance_profile\",\"commission_profile\",\"salary_profile\",\"project_profile\",\"task_profile\",\"award_profile\",\"travel_profile\",\"attendance_profile\",\"appointment_profile\",\"visit_profile\",\"leave_request_profile\",\"notice_profile\",\"role_read\",\"role_create\",\"role_update\",\"role_delete\",\"leave_menu\",\"leave_type_read\",\"leave_type_create\",\"leave_type_update\",\"leave_type_delete\",\"leave_assign_read\",\"leave_assign_create\",\"leave_assign_update\",\"leave_assign_delete\",\"leave_request_read\",\"leave_request_create\",\"leave_request_store\",\"leave_request_update\",\"leave_request_approve\",\"leave_request_reject\",\"leave_request_delete\",\"appointment_read\",\"appointment_menu\",\"appointment_create\",\"appointment_approve\",\"appointment_reject\",\"appointment_delete\",\"weekend_read\",\"weekend_update\",\"attendance_update\",\"holiday_read\",\"holiday_create\",\"holiday_update\",\"holiday_delete\",\"schedule_read\",\"schedule_create\",\"schedule_update\",\"schedule_delete\",\"attendance_menu\",\"attendance_read\",\"attendance_create\",\"attendance_update\",\"attendance_delete\",\"generate_qr_code\",\"leave_settings_read\",\"leave_settings_update\",\"company_settings_read\",\"company_settings_update\",\"locationApi\",\"company_setup_menu\",\"company_setup_activation\",\"company_setup_configuration\",\"company_setup_ip_whitelist\",\"company_setup_location\",\"location_create\",\"location_store\",\"location_edit\",\"location_update\",\"location_delete\",\"ip_read\",\"ip_create\",\"ip_update\",\"ip_delete\",\"attendance_report_read\",\"live_tracking_read\",\"report_menu\",\"report\",\"claim_read\",\"claim_create\",\"claim_update\",\"claim_delete\",\"payment_read\",\"payment_create\",\"payment_update\",\"payment_delete\",\"visit_menu\",\"visit_read\",\"visit_view\",\"visit_update\",\"payroll_menu\",\"list_payroll_item\",\"create_payroll_item\",\"store_payroll_item\",\"update_payroll_item\",\"delete_payroll_item\",\"view_payroll_item\",\"payroll_item_menu\",\"list_payroll_set\",\"create_payroll_set\",\"store_payroll_set\",\"update_payroll_set\",\"delete_payroll_set\",\"view_payroll_set\",\"edit_payroll_set\",\"payroll_set_menu\",\"advance_salaries_menu\",\"advance_salaries_create\",\"advance_salaries_store\",\"advance_salaries_edit\",\"advance_salaries_update\",\"advance_salaries_delete\",\"advance_salaries_view\",\"advance_salaries_approve\",\"advance_salaries_list\",\"advance_salaries_pay\",\"advance_salaries_invoice\",\"advance_salaries_search\",\"payslip_menu\",\"salary_generate\",\"salary_view\",\"salary_delete\",\"salary_edit\",\"salary_update\",\"salary_payment\",\"payslip_list\",\"advance_type_menu\",\"advance_type_create\",\"advance_type_store\",\"advance_type_edit\",\"advance_type_update\",\"advance_type_delete\",\"advance_type_view\",\"advance_type_list\",\"salary_menu\",\"salary_store\",\"salary_edit\",\"salary_update\",\"salary_delete\",\"salary_view\",\"salary_list\",\"salary_search\",\"salary_pay\",\"salary_invoice\",\"salary_approve\",\"salary_generate\",\"salary_calculate\",\"account_menu\",\"account_create\",\"account_store\",\"account_edit\",\"account_update\",\"account_delete\",\"account_view\",\"account_list\",\"account_search\",\"deposit_menu\",\"deposit_create\",\"deposit_store\",\"deposit_edit\",\"deposit_update\",\"deposit_delete\",\"deposit_list\",\"expense_menu\",\"expense_create\",\"expense_store\",\"expense_edit\",\"expense_update\",\"expense_delete\",\"expense_list\",\"expense_view\",\"expense_approve\",\"expense_invoice\",\"expense_pay\",\"transaction_menu\",\"transaction_create\",\"transaction_store\",\"transaction_edit\",\"transaction_update\",\"transaction_delete\",\"transaction_view\",\"transaction_list\",\"deposit_category_menu\",\"deposit_category_create\",\"deposit_category_store\",\"deposit_category_edit\",\"deposit_category_update\",\"deposit_category_delete\",\"deposit_category_list\",\"payment_method_menu\",\"payment_method_create\",\"payment_method_store\",\"payment_method_edit\",\"payment_method_update\",\"payment_method_delete\",\"payment_method_list\",\"project_menu\",\"project_create\",\"project_store\",\"project_edit\",\"project_update\",\"project_delete\",\"project_view\",\"project_list\",\"project_activity_view\",\"project_member_view\",\"project_member_delete\",\"project_complete\",\"project_payment\",\"project_invoice_view\",\"project_discussion_create\",\"project_discussion_store\",\"project_discussion_edit\",\"project_discussion_update\",\"project_discussion_delete\",\"project_discussion_view\",\"project_discussion_list\",\"project_discussion_comment\",\"project_discussion_reply\",\"project_file_create\",\"project_file_store\",\"project_file_edit\",\"project_file_update\",\"project_file_delete\",\"project_file_view\",\"project_file_list\",\"project_file_download\",\"project_file_comment\",\"project_file_reply\",\"project_notes_create\",\"project_notes_store\",\"project_notes_edit\",\"project_notes_update\",\"project_notes_delete\",\"project_notes_list\",\"client_menu\",\"client_create\",\"client_store\",\"client_edit\",\"client_update\",\"client_delete\",\"client_view\",\"client_list\",\"task_menu\",\"task_create\",\"task_store\",\"task_edit\",\"task_update\",\"task_delete\",\"task_view\",\"task_list\",\"task_activity_view\",\"task_assign_view\",\"task_assign_delete\",\"task_complete\",\"task_discussion_create\",\"task_discussion_store\",\"task_discussion_edit\",\"task_discussion_update\",\"task_discussion_delete\",\"task_discussion_view\",\"task_discussion_list\",\"task_discussion_comment\",\"task_discussion_reply\",\"task_file_create\",\"task_file_store\",\"task_file_edit\",\"task_file_update\",\"task_file_delete\",\"task_file_view\",\"task_file_list\",\"task_file_download\",\"task_file_comment\",\"task_file_reply\",\"task_notes_create\",\"task_notes_store\",\"task_notes_edit\",\"task_notes_update\",\"task_notes_delete\",\"task_notes_list\",\"task_files_comment\",\"award_type_menu\",\"award_type_create\",\"award_type_store\",\"award_type_edit\",\"award_type_update\",\"award_type_delete\",\"award_type_view\",\"award_type_list\",\"award_menu\",\"award_create\",\"award_store\",\"award_edit\",\"award_update\",\"award_delete\",\"award_list\",\"travel_type_menu\",\"travel_type_create\",\"travel_type_store\",\"travel_type_edit\",\"travel_type_update\",\"travel_type_delete\",\"travel_type_view\",\"travel_type_list\",\"travel_menu\",\"travel_create\",\"travel_store\",\"travel_edit\",\"travel_update\",\"travel_delete\",\"travel_list\",\"travel_approve\",\"travel_payment\",\"meeting_menu\",\"meeting_create\",\"meeting_store\",\"meeting_edit\",\"meeting_update\",\"meeting_delete\",\"meeting_list\",\"performance_menu\",\"performance_settings\",\"performance_indicator_menu\",\"performance_indicator_list\",\"performance_indicator_create\",\"performance_indicator_store\",\"performance_indicator_edit\",\"performance_indicator_update\",\"performance_indicator_delete\",\"performance_appraisal_menu\",\"performance_appraisal_create\",\"performance_appraisal_store\",\"performance_appraisal_edit\",\"performance_appraisal_update\",\"performance_appraisal_delete\",\"performance_appraisal_list\",\"performance_appraisal_view\",\"performance_goal_type_menu\",\"performance_goal_type_create\",\"performance_goal_type_store\",\"performance_goal_type_edit\",\"performance_goal_type_update\",\"performance_goal_type_delete\",\"performance_goal_type_list\",\"performance_goal_menu\",\"performance_goal_create\",\"performance_goal_store\",\"performance_goal_edit\",\"performance_goal_update\",\"performance_goal_delete\",\"performance_goal_view\",\"performance_goal_list\",\"performance_competence_type_list\",\"performance_competence_type_menu\",\"performance_competence_type_create\",\"performance_competence_type_store\",\"performance_competence_type_edit\",\"performance_competence_type_update\",\"performance_competence_type_delete\",\"performance_competence_type_view\",\"performance_competence_menu\",\"performance_competence_create\",\"performance_competence_store\",\"performance_competence_edit\",\"performance_competence_update\",\"performance_competence_delete\",\"performance_competence_view\",\"performance_competence_list\",\"app_settings_menu\",\"app_settings_update\",\"language_menu\",\"make_default\",\"sales_menu\",\"sales_products_menu\",\"sales_products_create\",\"sales_products_store\",\"sales_products_edit\",\"sales_products_update\",\"sales_products_delete\",\"sales_products_view\",\"sales_products_history\",\"sales_products_import\",\"sales_product_category_menu\",\"sales_product_category_create\",\"sales_product_category_store\",\"sales_product_category_edit\",\"sales_product_category_update\",\"sales_product_category_delete\",\"sales_product_category_import\",\"sales_product_brand_menu\",\"sales_product_brand_create\",\"sales_product_brand_store\",\"sales_product_brand_edit\",\"sales_product_brand_update\",\"sales_product_brand_delete\",\"sales_product_brand_import\",\"sales_product_unit_menu\",\"sales_product_unit_create\",\"sales_product_unit_store\",\"sales_product_unit_edit\",\"sales_product_unit_update\",\"sales_product_unit_delete\",\"sales_product_unit_import\",\"sales_product_tax_menu\",\"sales_product_tax_create\",\"sales_product_tax_store\",\"sales_product_tax_edit\",\"sales_product_tax_update\",\"sales_product_tax_delete\",\"sales_product_tax_import\",\"sales_product_warehouse_menu\",\"sales_product_warehouse_create\",\"sales_product_warehouse_store\",\"sales_product_warehouse_edit\",\"sales_product_warehouse_update\",\"sales_product_warehouse_delete\",\"sales_product_warehouse_import\",\"sales_product_supplier_menu\",\"sales_product_supplier_create\",\"sales_product_supplier_store\",\"sales_product_supplier_edit\",\"sales_product_supplier_update\",\"sales_product_supplier_delete\",\"sales_product_supplier_import\",\"sales_product_supplier_clear_due\",\"sales_product_barcode_menu\",\"sales_product_barcode_generate\",\"sales_product_stock_adjustment_menu\",\"sales_product_stock_adjustment_create\",\"sales_product_stock_adjustment_store\",\"sales_product_stock_adjustment_edit\",\"sales_product_stock_adjustment_update\",\"sales_product_stock_adjustment_delete\",\"sales_product_stock_count_menu\",\"sales_product_stock_count_create\",\"sales_product_stock_count_store\",\"sales_product_stock_count_edit\",\"sales_product_stock_count_update\",\"sales_product_stock_count_delete\",\"sales_purchase_menu\",\"sales_purchase_create\",\"sales_purchase_store\",\"sales_purchase_edit\",\"sales_purchase_update\",\"sales_purchase_delete\",\"sales_purchase_add_payment\",\"sales_purchase_view_payment\",\"sales_purchase_import\",\"sales_create\",\"sales_store\",\"sales_edit\",\"sales_update\",\"sales_delete\",\"sales_add_payment\",\"sales_view_payment\",\"sales_delivery\",\"sales_invoice\",\"sales_pos_menu\",\"sales_giftcard_menu\",\"sales_giftcard_create\",\"sales_giftcard_store\",\"sales_giftcard_edit\",\"sales_giftcard_update\",\"sales_giftcard_delete\",\"sales_coupon_menu\",\"sales_coupon_create\",\"sales_coupon_store\",\"sales_coupon_edit\",\"sales_coupon_update\",\"sales_coupon_delete\",\"sales_delivery_menu\",\"sales_delivery_store\",\"sales_delivery_edit\",\"sales_delivery_update\",\"sales_delivery_delete\",\"sales_expense_category_menu\",\"sales_expense_category_create\",\"sales_expense_category_store\",\"sales_expense_category_edit\",\"sales_expense_category_update\",\"sales_expense_category_delete\",\"sales_expense_category_import\",\"sales_expense_menu\",\"sales_expense_create\",\"sales_expense_store\",\"sales_expense_edit\",\"sales_expense_update\",\"sales_expense_delete\",\"sales_quotation_menu\",\"sales_quotation_create\",\"sales_quotation_store\",\"sales_quotation_edit\",\"sales_quotation_update\",\"sales_quotation_delete\",\"sales_quotation_sale_create\",\"sales_quotation_purchase_create\",\"sales_transfer_menu\",\"sales_transfer_create\",\"sales_transfer_store\",\"sales_transfer_edit\",\"sales_transfer_update\",\"sales_transfer_delete\",\"sales_transfer_view\",\"sales_return_menu\",\"sales_return_sale_menu\",\"sales_return_sale_create\",\"sales_return_sale_store\",\"sales_return_sale_edit\",\"sales_return_sale_update\",\"sales_return_sale_delete\",\"sales_return_purchase_menu\",\"sales_return_purchase_create\",\"sales_return_purchase_store\",\"sales_return_purchase_edit\",\"sales_return_purchase_update\",\"sales_return_purchase_delete\",\"lead_type_menu\",\"lead_type_create\",\"lead_type_store\",\"lead_type_edit\",\"lead_type_update\",\"lead_type_delete\",\"lead_source_menu\",\"lead_source_create\",\"lead_source_store\",\"lead_source_edit\",\"lead_source_update\",\"lead_source_delete\",\"lead_status_menu\",\"lead_status_create\",\"lead_status_store\",\"lead_status_edit\",\"lead_status_update\",\"lead_status_delete\",\"lead_menu\",\"lead_create\",\"lead_store\",\"lead_edit\",\"lead_update\",\"lead_delete\",\"lead_view\",\"lead_detail_profile\",\"lead_detail_attachment\",\"lead_detail_email\",\"lead_detail_call\",\"lead_detail_note\",\"lead_detail_task\",\"lead_detail_reminder\",\"lead_detail_tag\",\"lead_detail_activities\",\"general_settings_read\",\"general_settings_update\",\"language_create\",\"language_store\",\"language_edit\",\"language_update\",\"language_delete\",\"setup_language\",\"content_menu\",\"content_create\",\"content_store\",\"content_edit\",\"content_update\",\"content_delete\",\"contact_menu\",\"contact_create\",\"contact_store\",\"contact_edit\",\"contact_update\",\"contact_delete\",\"announcement_menu\",\"notice_menu\",\"notice_list\",\"notice_create\",\"notice_update\",\"notice_edit\",\"notice_delete\",\"send_sms_menu\",\"send_sms_list\",\"send_sms_create\",\"send_sms_update\",\"send_sms_edit\",\"send_sms_delete\",\"send_email_menu\",\"send_email_list\",\"send_email_create\",\"send_email_update\",\"send_email_edit\",\"send_email_delete\",\"send_notification_menu\",\"send_notification_list\",\"send_notification_create\",\"send_notification_update\",\"send_notification_edit\",\"send_notification_delete\",\"announcement_menu\",\"notice_menu\",\"notice_list\",\"notice_create\",\"notice_update\",\"notice_edit\",\"notice_delete\",\"send_sms_menu\",\"send_sms_list\",\"send_sms_create\",\"send_sms_update\",\"send_sms_edit\",\"send_sms_delete\",\"send_email_menu\",\"send_email_list\",\"send_email_create\",\"send_email_update\",\"send_email_edit\",\"send_email_delete\",\"send_notification_menu\",\"send_notification_list\",\"send_notification_create\",\"send_notification_update\",\"send_notification_edit\",\"send_notification_delete\",\"support_menu\",\"support_read\",\"support_create\",\"support_reply\",\"support_delete\"]', NULL, NULL, 'EMP-2', 'On Probation', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ld9X6uTMyZ', 'verified', '2023-04-18 01:07:42', NULL, 'verified', NULL, '$2y$10$DF1z4Y3paHZe.aIqWbRFReZV.pu3g6JBlCPX6vGsLa3rgq6pD4zLS', NULL, 48, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 'Islam', NULL, NULL, 0.00, 'Unmarried', NULL, NULL, 1, 0, 0, 0, 0, 0, 'Asia/Dhaka', NULL, NULL, NULL, NULL, 'B6O3LXILnU', NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL, NULL, NULL, NULL),
(3, 2, 17, 'Manager', NULL, 1, NULL, 'manager@onesttech.com', '+88014555887', '0', 0, 3, 17, 4, 33, '[\"team_menu\",\"team_list\",\"team_create\",\"team_update\",\"team_edit\",\"team_delete\",\"team_member_view\",\"team_member_create\",\"team_member_edit\",\"team_member_delete\",\"team_member_assign\",\"team_member_unassign\",\"dashboard\",\"designation_read\",\"designation_create\",\"designation_update\",\"designation_delete\",\"shift_read\",\"shift_create\",\"shift_update\",\"shift_delete\",\"department_read\",\"department_create\",\"department_update\",\"department_delete\",\"user_menu\",\"user_read\",\"profile_view\",\"user_create\",\"user_edit\",\"user_update\",\"user_delete\",\"user_banned\",\"user_unbanned\",\"make_hr\",\"user_permission\",\"profile_image_view\",\"role_read\",\"role_create\",\"role_update\",\"role_delete\",\"leave_menu\",\"leave_type_read\",\"leave_type_create\",\"leave_type_update\",\"leave_type_delete\",\"leave_assign_read\",\"leave_assign_create\",\"leave_assign_update\",\"leave_assign_delete\",\"leave_request_read\",\"leave_request_create\",\"leave_request_approve\",\"leave_request_reject\",\"leave_request_delete\",\"appointment_read\",\"appointment_menu\",\"appointment_create\",\"appointment_approve\",\"appointment_reject\",\"appointment_delete\",\"weekend_read\",\"weekend_update\",\"attendance_update\",\"holiday_read\",\"holiday_create\",\"holiday_update\",\"holiday_delete\",\"schedule_read\",\"schedule_create\",\"schedule_update\",\"schedule_delete\",\"attendance_menu\",\"attendance_read\",\"attendance_create\",\"attendance_update\",\"attendance_delete\",\"leave_settings_read\",\"leave_settings_update\",\"company_settings_read\",\"company_settings_update\",\"locationApi\",\"company_setup_menu\",\"company_setup_activation\",\"company_setup_configuration\",\"company_setup_ip_whitelist\",\"company_setup_location\",\"ip_read\",\"ip_create\",\"ip_update\",\"ip_delete\",\"attendance_report_read\",\"live_tracking_read\",\"report_menu\",\"report\",\"claim_read\",\"claim_create\",\"claim_update\",\"claim_delete\",\"payment_read\",\"payment_create\",\"payment_update\",\"payment_delete\",\"visit_menu\",\"visit_read\",\"visit_view\",\"visit_update\",\"payroll_menu\",\"list_payroll_item\",\"create_payroll_item\",\"store_payroll_item\",\"update_payroll_item\",\"delete_payroll_item\",\"view_payroll_item\",\"payroll_item_menu\",\"list_payroll_set\",\"create_payroll_set\",\"store_payroll_set\",\"update_payroll_set\",\"delete_payroll_set\",\"view_payroll_set\",\"edit_payroll_set\",\"payroll_set_menu\",\"advance_salaries_menu\",\"advance_salaries_create\",\"advance_salaries_store\",\"advance_salaries_edit\",\"advance_salaries_update\",\"advance_salaries_delete\",\"advance_salaries_view\",\"advance_salaries_approve\",\"advance_salaries_list\",\"advance_salaries_pay\",\"advance_salaries_invoice\",\"advance_salaries_search\",\"payslip_menu\",\"salary_generate\",\"salary_view\",\"salary_delete\",\"salary_edit\",\"salary_update\",\"salary_payment\",\"payslip_list\",\"advance_type_menu\",\"advance_type_create\",\"advance_type_store\",\"advance_type_edit\",\"advance_type_update\",\"advance_type_delete\",\"advance_type_view\",\"advance_type_list\",\"salary_menu\",\"salary_store\",\"salary_edit\",\"salary_update\",\"salary_delete\",\"salary_view\",\"salary_list\",\"salary_search\",\"salary_pay\",\"salary_invoice\",\"salary_approve\",\"salary_generate\",\"salary_calculate\",\"account_menu\",\"account_create\",\"account_store\",\"account_edit\",\"account_update\",\"account_delete\",\"account_view\",\"account_list\",\"account_search\",\"deposit_menu\",\"deposit_create\",\"deposit_store\",\"deposit_edit\",\"deposit_update\",\"deposit_delete\",\"deposit_list\",\"expense_menu\",\"expense_create\",\"expense_store\",\"expense_edit\",\"expense_update\",\"expense_delete\",\"expense_list\",\"expense_view\",\"expense_approve\",\"expense_invoice\",\"expense_pay\",\"transaction_menu\",\"transaction_create\",\"transaction_store\",\"transaction_edit\",\"transaction_update\",\"transaction_delete\",\"transaction_view\",\"transaction_list\",\"deposit_category_menu\",\"deposit_category_create\",\"deposit_category_store\",\"deposit_category_edit\",\"deposit_category_update\",\"deposit_category_delete\",\"deposit_category_list\",\"payment_method_menu\",\"payment_method_create\",\"payment_method_store\",\"payment_method_edit\",\"payment_method_update\",\"payment_method_delete\",\"payment_method_list\",\"travel_menu\",\"travel_create\",\"travel_store\",\"travel_edit\",\"travel_update\",\"travel_delete\",\"travel_list\",\"travel_view\",\"travel_approve\",\"travel_invoice\",\"travel_pay\",\"meeting_menu\",\"meeting_create\",\"meeting_store\",\"meeting_edit\",\"meeting_update\",\"meeting_delete\",\"meeting_list\",\"meeting_view\",\"announcement_menu\",\"notice_menu\",\"notice_list\",\"notice_create\",\"notice_update\",\"notice_edit\",\"notice_delete\",\"send_sms_menu\",\"send_sms_list\",\"send_sms_create\",\"send_sms_update\",\"send_sms_edit\",\"send_sms_delete\",\"send_email_menu\",\"send_email_list\",\"send_email_create\",\"send_email_update\",\"send_email_edit\",\"send_email_delete\",\"send_notification_menu\",\"send_notification_list\",\"send_notification_create\",\"send_notification_update\",\"send_notification_edit\",\"send_notification_delete\",\"announcement_menu\",\"notice_menu\",\"notice_list\",\"notice_create\",\"notice_update\",\"notice_edit\",\"notice_delete\",\"send_sms_menu\",\"send_sms_list\",\"send_sms_create\",\"send_sms_update\",\"send_sms_edit\",\"send_sms_delete\",\"send_email_menu\",\"send_email_list\",\"send_email_create\",\"send_email_update\",\"send_email_edit\",\"send_email_delete\",\"send_notification_menu\",\"send_notification_list\",\"send_notification_create\",\"send_notification_update\",\"send_notification_edit\",\"send_notification_delete\",\"support_menu\",\"support_read\",\"support_create\",\"support_reply\",\"support_delete\"]', NULL, NULL, 'EMP-3', 'On Probation', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CajWyc8J0o', 'verified', '2023-04-18 01:07:42', NULL, 'verified', NULL, '$2y$10$kv8hpgYcc4/gdm9O2kqmSeVY175kT1Knw6ixk7XZjq4ST/z8mMiUq', NULL, 55, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 'Islam', NULL, NULL, 0.00, 'Unmarried', NULL, NULL, 1, 0, 0, 0, 0, 0, 'Asia/Dhaka', NULL, NULL, NULL, NULL, 'MIYFKKOlJv', NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL, NULL, NULL, NULL),
(4, 2, 17, 'Staff', NULL, 1, NULL, 'staff@onesttech.com', '+8855412547', '0', 0, 7, 18, 4, 44, '[\"team_menu\",\"team_list\",\"team_create\",\"team_update\",\"team_edit\",\"team_delete\",\"team_member_view\",\"team_member_create\",\"team_member_edit\",\"team_member_delete\",\"team_member_assign\",\"team_member_unassign\",\"dashboard\",\"designation_read\",\"designation_create\",\"designation_update\",\"designation_delete\",\"shift_read\",\"shift_create\",\"shift_update\",\"shift_delete\",\"department_read\",\"department_create\",\"department_update\",\"department_delete\",\"user_menu\",\"user_read\",\"profile_view\",\"user_create\",\"user_edit\",\"user_update\",\"user_delete\",\"user_banned\",\"user_unbanned\",\"make_hr\",\"user_permission\",\"profile_image_view\",\"role_read\",\"role_create\",\"role_update\",\"role_delete\",\"leave_menu\",\"leave_type_read\",\"leave_type_create\",\"leave_type_update\",\"leave_type_delete\",\"leave_assign_read\",\"leave_assign_create\",\"leave_assign_update\",\"leave_assign_delete\",\"leave_request_read\",\"leave_request_create\",\"leave_request_approve\",\"leave_request_reject\",\"leave_request_delete\",\"appointment_read\",\"appointment_menu\",\"appointment_create\",\"appointment_approve\",\"appointment_reject\",\"appointment_delete\",\"weekend_read\",\"weekend_update\",\"attendance_update\",\"holiday_read\",\"holiday_create\",\"holiday_update\",\"holiday_delete\",\"schedule_read\",\"schedule_create\",\"schedule_update\",\"schedule_delete\",\"attendance_menu\",\"attendance_read\",\"attendance_create\",\"attendance_update\",\"attendance_delete\",\"leave_settings_read\",\"leave_settings_update\",\"company_settings_read\",\"company_settings_update\",\"locationApi\",\"company_setup_menu\",\"company_setup_activation\",\"company_setup_configuration\",\"company_setup_ip_whitelist\",\"company_setup_location\",\"ip_read\",\"ip_create\",\"ip_update\",\"ip_delete\",\"attendance_report_read\",\"live_tracking_read\",\"report_menu\",\"report\",\"claim_read\",\"claim_create\",\"claim_update\",\"claim_delete\",\"payment_read\",\"payment_create\",\"payment_update\",\"payment_delete\",\"visit_menu\",\"visit_read\",\"visit_view\",\"visit_update\",\"payroll_menu\",\"list_payroll_item\",\"create_payroll_item\",\"store_payroll_item\",\"update_payroll_item\",\"delete_payroll_item\",\"view_payroll_item\",\"payroll_item_menu\",\"list_payroll_set\",\"create_payroll_set\",\"store_payroll_set\",\"update_payroll_set\",\"delete_payroll_set\",\"view_payroll_set\",\"edit_payroll_set\",\"payroll_set_menu\",\"advance_salaries_menu\",\"advance_salaries_create\",\"advance_salaries_store\",\"advance_salaries_edit\",\"advance_salaries_update\",\"advance_salaries_delete\",\"advance_salaries_view\",\"advance_salaries_approve\",\"advance_salaries_list\",\"advance_salaries_pay\",\"advance_salaries_invoice\",\"advance_salaries_search\",\"payslip_menu\",\"salary_generate\",\"salary_view\",\"salary_delete\",\"salary_edit\",\"salary_update\",\"salary_payment\",\"payslip_list\",\"advance_type_menu\",\"advance_type_create\",\"advance_type_store\",\"advance_type_edit\",\"advance_type_update\",\"advance_type_delete\",\"advance_type_view\",\"advance_type_list\",\"salary_menu\",\"salary_store\",\"salary_edit\",\"salary_update\",\"salary_delete\",\"salary_view\",\"salary_list\",\"salary_search\",\"salary_pay\",\"salary_invoice\",\"salary_approve\",\"salary_generate\",\"salary_calculate\",\"account_menu\",\"account_create\",\"account_store\",\"account_edit\",\"account_update\",\"account_delete\",\"account_view\",\"account_list\",\"account_search\",\"deposit_menu\",\"deposit_create\",\"deposit_store\",\"deposit_edit\",\"deposit_update\",\"deposit_delete\",\"deposit_list\",\"expense_menu\",\"expense_create\",\"expense_store\",\"expense_edit\",\"expense_update\",\"expense_delete\",\"expense_list\",\"expense_view\",\"expense_approve\",\"expense_invoice\",\"expense_pay\",\"transaction_menu\",\"transaction_create\",\"transaction_store\",\"transaction_edit\",\"transaction_update\",\"transaction_delete\",\"transaction_view\",\"transaction_list\",\"deposit_category_menu\",\"deposit_category_create\",\"deposit_category_store\",\"deposit_category_edit\",\"deposit_category_update\",\"deposit_category_delete\",\"deposit_category_list\",\"payment_method_menu\",\"payment_method_create\",\"payment_method_store\",\"payment_method_edit\",\"payment_method_update\",\"payment_method_delete\",\"payment_method_list\",\"travel_menu\",\"travel_create\",\"travel_store\",\"travel_edit\",\"travel_update\",\"travel_delete\",\"travel_list\",\"travel_view\",\"travel_approve\",\"travel_invoice\",\"travel_pay\",\"meeting_menu\",\"meeting_create\",\"meeting_store\",\"meeting_edit\",\"meeting_update\",\"meeting_delete\",\"meeting_list\",\"meeting_view\",\"announcement_menu\",\"notice_menu\",\"notice_list\",\"notice_create\",\"notice_update\",\"notice_edit\",\"notice_delete\",\"send_sms_menu\",\"send_sms_list\",\"send_sms_create\",\"send_sms_update\",\"send_sms_edit\",\"send_sms_delete\",\"send_email_menu\",\"send_email_list\",\"send_email_create\",\"send_email_update\",\"send_email_edit\",\"send_email_delete\",\"send_notification_menu\",\"send_notification_list\",\"send_notification_create\",\"send_notification_update\",\"send_notification_edit\",\"send_notification_delete\",\"announcement_menu\",\"notice_menu\",\"notice_list\",\"notice_create\",\"notice_update\",\"notice_edit\",\"notice_delete\",\"send_sms_menu\",\"send_sms_list\",\"send_sms_create\",\"send_sms_update\",\"send_sms_edit\",\"send_sms_delete\",\"send_email_menu\",\"send_email_list\",\"send_email_create\",\"send_email_update\",\"send_email_edit\",\"send_email_delete\",\"send_notification_menu\",\"send_notification_list\",\"send_notification_create\",\"send_notification_update\",\"send_notification_edit\",\"send_notification_delete\",\"support_menu\",\"support_read\",\"support_create\",\"support_reply\",\"support_delete\"]', NULL, NULL, 'EMP-4', 'On Probation', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'hU66naB72V', 'verified', '2023-04-18 01:07:42', NULL, 'verified', NULL, '$2y$10$eot1OOx6RKwJUJROBgJjhuXTicP4CGOPAITl9J4RVM0phN2zPbphK', NULL, 48, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 'Islam', NULL, NULL, 0.00, 'Unmarried', NULL, NULL, 1, 0, 0, 0, 0, 0, 'Asia/Dhaka', NULL, NULL, NULL, NULL, 'fJFyFsjsZo', NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL, NULL, NULL, NULL),
(5, 2, 17, 'Mr. Client', NULL, 1, NULL, 'client@onesttech.com', '+885541254701', '0', 0, 9, 18, 4, 44, '[\"dashboard\",\"profile_view\",\"profile_update\",\"profile_change_password\",\"user_menu\",\"user_read\",\"profile_view\",\"profile_image_view\",\"project_menu\",\"project_edit\",\"project_update\",\"project_view\",\"project_list\",\"project_activity_view\",\"project_member_view\",\"project_complete\",\"project_payment\",\"project_invoice_view\",\"project_discussion_create\",\"project_discussion_store\",\"project_discussion_edit\",\"project_discussion_update\",\"project_discussion_view\",\"project_discussion_list\",\"project_discussion_comment\",\"project_discussion_reply\",\"project_file_create\",\"project_file_store\",\"project_file_edit\",\"project_file_update\",\"project_file_view\",\"project_file_list\",\"project_file_download\",\"project_file_comment\",\"project_file_reply\",\"project_notes_create\",\"project_notes_store\",\"project_notes_edit\",\"project_notes_update\",\"project_notes_list\",\"client_menu\",\"client_edit\",\"client_update\",\"client_view\",\"client_list\",\"task_menu\",\"task_create\",\"task_store\",\"task_edit\",\"task_update\",\"task_view\",\"task_list\",\"task_activity_view\",\"task_assign_view\",\"task_complete\",\"task_discussion_create\",\"task_discussion_store\",\"task_discussion_edit\",\"task_discussion_update\",\"task_discussion_view\",\"task_discussion_list\",\"task_discussion_comment\",\"task_discussion_reply\",\"task_file_create\",\"task_file_store\",\"task_file_edit\",\"task_file_update\",\"task_file_view\",\"task_file_list\",\"task_file_download\",\"task_file_comment\",\"task_file_reply\",\"task_notes_create\",\"task_notes_store\",\"task_notes_edit\",\"task_notes_list\",\"task_files_comment\",\"support_menu\",\"support_read\",\"support_create\",\"support_reply\"]', NULL, NULL, 'EMP-5', 'On Probation', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'K8Bw5myFOD', 'verified', '2023-04-18 01:07:42', NULL, 'verified', NULL, '$2y$10$WSCsJkTWM5AKmJF4t.yOseRznBMAL2VR1gbLEkUhnkiTW7gjDGMgy', NULL, 52, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 'Islam', NULL, NULL, 0.00, 'Unmarried', NULL, NULL, 1, 0, 0, 0, 0, 0, 'Asia/Dhaka', NULL, NULL, NULL, NULL, 'iNoh55BEUI', NULL, '2023-04-18 01:07:42', '2023-04-18 01:07:42', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_devices`
--

CREATE TABLE `user_devices` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `device_token` longtext COLLATE utf8mb4_unicode_ci COMMENT 'device_token from firebase',
  `device_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'device_name from firebase',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `virtual_meetings`
--

CREATE TABLE `virtual_meetings` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` int DEFAULT '0' COMMENT '0 means unlimited',
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `host` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'jitsi',
  `description` text COLLATE utf8mb4_unicode_ci,
  `datetime` text COLLATE utf8mb4_unicode_ci,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `company_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

CREATE TABLE `visits` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` enum('created','started','reached','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'created',
  `cancel_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visits`
--

INSERT INTO `visits` (`id`, `company_id`, `date`, `title`, `description`, `user_id`, `status`, `cancel_note`, `created_at`, `updated_at`) VALUES
(1, 1, '2013-02-16', 'Quasi officiis est odit sunt perferendis necessitatibus dolorem.', 'Voluptate et et numquam illum hic adipisci. Molestiae aut expedita necessitatibus repellat sed. At quas autem quaerat fugit.', 1, 'completed', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(2, 1, '2014-01-17', 'Velit corrupti modi dolor harum aliquid et laudantium soluta.', 'Ullam repudiandae doloribus maxime ducimus consequatur. Eos magni animi sapiente aut qui saepe. Voluptatem sint blanditiis in sed. Consequatur consequatur et aut provident eveniet.', 1, 'cancelled', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(3, 1, '1974-01-22', 'Suscipit dolores quod dolorum explicabo placeat aperiam.', 'Doloribus et eum fuga natus voluptatibus deserunt iure. Adipisci deleniti aut voluptatem labore ut occaecati. Magnam praesentium sed possimus quia est impedit libero repellat.', 1, 'reached', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(4, 1, '1995-10-24', 'Eveniet quia modi explicabo dolores.', 'Corporis veritatis aliquam voluptatem possimus. Et ullam consectetur enim a qui. Sit rerum id quisquam velit distinctio et nobis eos.', 1, 'cancelled', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(5, 1, '2011-05-22', 'Odit laborum odit tempora ea.', 'Aliquam nisi ad cum exercitationem consequatur natus et officiis. Consequuntur commodi laboriosam numquam beatae dicta accusamus.', 1, 'started', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(6, 1, '2014-03-09', 'Veritatis repudiandae laudantium quasi non qui.', 'Ab quasi et eum ducimus deserunt. Molestiae ipsam nobis omnis quis in. Ducimus voluptatem quia ipsum repellendus quod sed. Occaecati dolorem fugit rerum repudiandae cupiditate officia veniam tempore.', 1, 'completed', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(7, 2, '2019-07-03', 'Et laborum minima nulla dolores sed.', 'Incidunt quis qui debitis est consequatur vero distinctio. Earum itaque voluptatem aut qui.', 2, 'started', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(8, 2, '1987-01-01', 'Pariatur voluptas ipsa qui enim est eum reiciendis.', 'Laborum in perferendis dolorem facilis quia enim. Officiis ad quo repellat unde et reiciendis. Exercitationem laboriosam ad pariatur perspiciatis.', 2, 'reached', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(9, 2, '1999-04-11', 'Exercitationem architecto odit tempore assumenda.', 'Ea consectetur ut distinctio. Vitae consequuntur rem ab sunt expedita. Modi voluptates veritatis eos et impedit dignissimos qui.', 2, 'cancelled', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(10, 2, '1985-03-03', 'Aut nostrum nulla beatae iste nihil debitis consequatur.', 'Blanditiis quo officia ea est illum et ut. Pariatur praesentium et ut et pariatur. Neque iure doloremque eveniet.', 2, 'completed', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(11, 2, '1999-08-09', 'Quisquam nobis sunt et odit repellendus beatae aut ea.', 'Consequatur quaerat dolores harum consectetur. Magni nisi quo omnis quos dolores nobis magni. Inventore et quia aliquam itaque.', 2, 'cancelled', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(12, 2, '2004-02-15', 'Eius dolor sint odit expedita et a.', 'Et in nobis iure enim quia. Provident ducimus sint pariatur dolorem perspiciatis earum voluptate qui. Ducimus ipsa magni est dolorum.', 2, 'completed', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(13, 2, '1981-02-20', 'Doloremque excepturi magnam corporis ratione.', 'Cupiditate eum et nesciunt quia similique. Deleniti iusto nemo officiis qui. In quaerat laborum quaerat quis. In illum molestias est maxime.', 3, 'reached', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(14, 2, '1974-08-21', 'Tempora et dolores aperiam quia ducimus dolor quia.', 'Et quo quam exercitationem sunt vel. Molestiae impedit sit nam est quo in aut. Reiciendis a doloremque laboriosam omnis rerum vel earum harum.', 3, 'created', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(15, 2, '2001-05-05', 'Amet consequuntur eaque sit nihil.', 'Ullam incidunt consequuntur voluptas vel. Repudiandae corrupti ea nam suscipit ut hic quos ab. Natus maxime ut mollitia id. Sit maxime ducimus eligendi natus exercitationem eveniet voluptas.', 3, 'reached', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(16, 2, '2002-04-22', 'Modi vel magnam rerum maxime.', 'Maiores ab repudiandae nihil ipsum ullam sequi. Iusto numquam distinctio corporis maxime non praesentium velit id. Harum accusantium porro esse molestiae ullam voluptas.', 3, 'completed', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(17, 2, '1995-11-17', 'Delectus doloribus quasi fugit assumenda ut omnis.', 'Numquam qui fuga doloremque et cumque ut. Est sunt quaerat nesciunt placeat impedit non. Dolores sit dolorem odio sunt corrupti magnam nesciunt.', 3, 'completed', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(18, 2, '1971-08-07', 'Exercitationem voluptates eaque sequi repellat delectus quia doloremque.', 'Minus consequatur aliquam pariatur dolorem autem tempora ut aliquam. Atque maiores ea laborum aut et tempore alias.', 3, 'created', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(19, 2, '2020-07-18', 'Eius et eaque ducimus cupiditate quia distinctio.', 'Numquam occaecati quis et delectus quia. Nisi repudiandae harum quasi at laborum beatae est. Nemo excepturi dolorum libero aperiam vitae labore dolor est.', 4, 'completed', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(20, 2, '1993-04-16', 'Sequi sed consequatur accusamus voluptates adipisci consectetur.', 'Exercitationem inventore illum aspernatur. Maiores rem sapiente qui maiores est commodi magni. Rerum asperiores qui eius esse fugit.', 4, 'reached', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(21, 2, '2012-06-24', 'Corrupti beatae similique aliquid sit perferendis dicta.', 'Eligendi voluptas id corporis rem. Ut id fugiat ullam tempora. Omnis iure aut exercitationem est ducimus.', 4, 'started', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(22, 2, '1981-01-21', 'Laboriosam ipsam odio corrupti sequi est.', 'Dolore temporibus quisquam non voluptatem ut maxime. Placeat et ullam eius et possimus aut. Error expedita aspernatur repudiandae doloremque culpa.', 4, 'completed', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(23, 2, '1992-06-28', 'Tempora molestias sequi itaque impedit accusantium assumenda esse.', 'Magnam qui ut necessitatibus unde dicta vel. Esse omnis non harum deleniti nihil molestiae. Et voluptatibus eos quidem animi tempore nihil corrupti ea.', 4, 'cancelled', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(24, 2, '2017-01-31', 'Veritatis voluptas velit necessitatibus.', 'Molestiae reiciendis totam dolore consequuntur vitae. Quidem qui similique natus. Ipsa eos placeat sed sunt doloribus veritatis quo. Eos dolorem eius delectus vitae et eos.', 4, 'completed', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(25, 2, '2021-04-07', 'Labore optio velit eos aliquid et.', 'Harum sit distinctio quae consequatur neque deleniti. Hic placeat enim ullam rerum. Blanditiis qui et rerum nemo aut voluptates dignissimos.', 5, 'reached', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(26, 2, '2005-08-07', 'Cumque non eos autem qui tenetur consectetur id mollitia.', 'Doloremque quas at rerum et quos neque nihil. Vel repudiandae delectus similique praesentium harum deserunt. Aspernatur veniam ipsa tempore doloremque. Et at omnis quasi quia pariatur.', 5, 'started', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(27, 2, '2007-08-01', 'Odit illo incidunt minima ut maiores eos.', 'Cupiditate aut aut labore. Non corporis laudantium ut mollitia ipsum corrupti occaecati voluptatibus. Quod quisquam quo corporis voluptatem sunt et et nihil.', 5, 'completed', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(28, 2, '1998-09-02', 'Esse non quasi omnis non quia cum dicta.', 'Molestias sit aspernatur et aut perspiciatis animi. Minus voluptatum quas vero ut quod dolorem odio. Quibusdam repellat rerum et expedita soluta esse neque.', 5, 'created', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(29, 2, '2001-03-17', 'Voluptatibus nihil quis aut dolores tempore.', 'Qui necessitatibus minus deserunt quo qui ut. Fugit impedit quos eos nobis nostrum aut. Quia molestiae natus maxime minima numquam voluptatem odit voluptas.', 5, 'cancelled', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(30, 2, '2008-01-18', 'Praesentium provident sunt delectus.', 'Provident et in alias unde aperiam accusantium. Asperiores dolorum velit doloribus unde. Aut sunt nihil molestias sit et sequi reiciendis. Asperiores aspernatur et nostrum quisquam magnam.', 5, 'created', NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `visit_images`
--

CREATE TABLE `visit_images` (
  `id` bigint UNSIGNED NOT NULL,
  `imageable_id` int UNSIGNED NOT NULL,
  `imageable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visit_notes`
--

CREATE TABLE `visit_notes` (
  `id` bigint UNSIGNED NOT NULL,
  `visit_id` bigint UNSIGNED NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('created','started','reached') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'created',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visit_notes`
--

INSERT INTO `visit_notes` (`id`, `visit_id`, `note`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Autem omnis soluta nesciunt. Ex consectetur explicabo perferendis quae quos et nisi. Blanditiis ut quasi molestiae doloribus doloremque. Excepturi fugiat voluptate alias sapiente sunt.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(2, 2, 'Quas sit nihil quia. Et voluptate dolore voluptate quo et repellendus. Omnis labore facere totam deleniti maiores. Unde quidem eos cum.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(3, 3, 'Nemo quam molestiae harum autem similique ut dicta. Iure mollitia excepturi consequatur iste itaque perferendis assumenda. Praesentium fugiat qui magnam et neque.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(4, 4, 'Quo ipsum eum enim unde eligendi. Eius voluptas reprehenderit exercitationem. Quis deleniti accusamus suscipit minus iusto similique consectetur.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(5, 5, 'Accusantium est magnam et velit est occaecati. Voluptates qui sed officia sint blanditiis non non. Quidem vero officia corporis sunt aut ullam. Perferendis perspiciatis rem aut quia praesentium est.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(6, 6, 'Natus eveniet dolore distinctio quo autem. Eum quam enim voluptates molestiae eveniet rerum sit. Quod dicta nisi quas error inventore. Facilis dolor harum soluta.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(7, 7, 'Similique eligendi doloremque aut id ipsum. Modi sed debitis rerum ea illum sit. Omnis quis qui consequatur in id ducimus.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(8, 8, 'Libero voluptatem non optio. Sapiente aut sed vel aspernatur omnis. Accusamus ipsam voluptate consequuntur voluptatem nobis velit molestias. Culpa sit ipsum voluptate sed quod ex.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(9, 9, 'Id dolor voluptate et quia sunt. Nesciunt qui alias beatae possimus quis. Quo laudantium deserunt enim est sed. Fugiat eos vitae repellendus eveniet velit.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(10, 10, 'Fuga ut facere maiores ullam quas ipsam. Soluta et voluptatum quam laudantium esse expedita corrupti. Non et culpa incidunt odit ut porro.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(11, 11, 'Magni qui iste labore reiciendis nam deserunt fugiat. Optio rerum quia non. Commodi pariatur nam earum dolorem beatae distinctio. Aut est culpa vitae. Saepe consequatur ut sint quas maxime id.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(12, 12, 'Saepe odit veniam est cum autem. Non iusto possimus et ex. Eaque quas possimus mollitia consequuntur sunt.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(13, 13, 'Dicta dolores ut animi ipsa sed quisquam. Maxime totam voluptatem dolores ut doloribus ea. Unde totam voluptas perspiciatis cum laboriosam.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(14, 14, 'Ab voluptatem voluptate adipisci officia. Inventore odit aliquid aut fuga. Nesciunt et error ducimus ratione.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(15, 15, 'Optio velit perspiciatis nihil magnam et. Voluptatem molestias laborum earum sint culpa fugiat. Excepturi aperiam quaerat quo unde occaecati. Earum aut accusamus sit rem ipsam unde.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(16, 16, 'Dignissimos dolorum eius doloribus in iste vel. Facilis accusamus cum id autem architecto voluptas. Atque non officia quo corporis quia. Illo beatae sed sunt ipsam sapiente autem.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(17, 17, 'Blanditiis esse et sed quae quibusdam ad perferendis. Dolorem officia nulla aut. Aut aliquam ut numquam excepturi cumque cum sit.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(18, 18, 'Reprehenderit qui laborum facere veniam incidunt. Molestias fugit est deleniti mollitia. Rerum et animi aspernatur sed eveniet qui eum. Aliquam est itaque nobis eligendi doloribus.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(19, 19, 'Illo sed aliquam velit ipsa aliquid numquam. Voluptates quas libero harum enim quia. Enim impedit aut officia est dolorum provident non.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(20, 20, 'Explicabo omnis inventore at veniam. Corrupti id et veritatis nemo molestiae non pariatur aut. Quo qui quas cupiditate voluptas natus odit sed. Doloremque et aperiam id enim.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(21, 21, 'Ad eos accusamus ut dolorem. Commodi a aliquam sed provident recusandae laudantium. Blanditiis omnis deleniti qui ratione qui illum ut.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(22, 22, 'Voluptas quos tempore consequatur ipsa aut voluptatum. Et optio rerum numquam fugiat. Vel dicta et porro aut rerum sit.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(23, 23, 'Consequatur ea perferendis quos et consequatur occaecati voluptas. Recusandae voluptate sit amet voluptatibus. Eaque nulla ut quas exercitationem.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(24, 24, 'Nulla itaque soluta voluptate unde fuga voluptatem. Quis enim quo architecto qui et. Sed molestias est cupiditate exercitationem occaecati nobis.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(25, 25, 'Iste et eaque sapiente vel sit quaerat. Perspiciatis eum alias sed dolores. Fugiat eaque tempora culpa assumenda deleniti sed vitae. Quis a et dolores nihil corrupti illum qui.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(26, 26, 'Nihil sit delectus quam adipisci cum dicta magni. Vel quos magni vel vero. Non vel omnis vero quia asperiores dignissimos. Dolores aut minus sed voluptas sit praesentium.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(27, 27, 'Earum quo est iusto non eum. Inventore quos consectetur debitis consequatur. Dolor aliquam itaque at eum eligendi quod velit. Vel vel harum aut.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(28, 28, 'Qui magnam dolorem quibusdam fuga aut aspernatur doloremque. Ullam eligendi omnis voluptatem. Quo veritatis ut enim dolor qui.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(29, 29, 'Aperiam ea a quaerat unde voluptas quia fugit. Et consequatur et fugiat sapiente. Qui voluptatem consequuntur perferendis eos omnis tempore delectus.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(30, 30, 'Quia cum libero sit odit excepturi qui qui corrupti. Voluptatem maxime vel ducimus aliquam error. Nihil necessitatibus nostrum vel dolores consequatur. Optio eum quam aut magni.', 'created', '2023-04-18 01:07:43', '2023-04-18 01:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `visit_schedules`
--

CREATE TABLE `visit_schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visit_id` bigint UNSIGNED NOT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` longtext COLLATE utf8mb4_unicode_ci,
  `status` enum('created','started','reached','end') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'created',
  `started_at` timestamp NULL DEFAULT NULL,
  `reached_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visit_schedules`
--

INSERT INTO `visit_schedules` (`id`, `title`, `visit_id`, `latitude`, `longitude`, `note`, `status`, `started_at`, `reached_at`, `created_at`, `updated_at`) VALUES
(1, 'Reached Destination', 1, NULL, NULL, NULL, 'reached', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(2, 'Visit Rescheduled', 2, NULL, NULL, NULL, 'created', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(3, 'End Visit', 3, NULL, NULL, NULL, 'end', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(4, 'End Visit', 4, NULL, NULL, NULL, 'end', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(5, 'Started Visit', 5, NULL, NULL, NULL, 'started', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(6, 'Reached Destination', 6, NULL, NULL, NULL, 'reached', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(7, 'Started Visit', 7, NULL, NULL, NULL, 'started', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(8, 'End Visit', 8, NULL, NULL, NULL, 'end', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(9, 'Visit Rescheduled', 9, NULL, NULL, NULL, 'created', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(10, 'End Visit', 10, NULL, NULL, NULL, 'end', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(11, 'End Visit', 11, NULL, NULL, NULL, 'end', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(12, 'Visit Rescheduled', 12, NULL, NULL, NULL, 'created', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(13, 'End Visit', 13, NULL, NULL, NULL, 'end', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(14, 'End Visit', 14, NULL, NULL, NULL, 'end', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(15, 'Reached Destination', 15, NULL, NULL, NULL, 'reached', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(16, 'Reached Destination', 16, NULL, NULL, NULL, 'reached', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(17, 'Reached Destination', 17, NULL, NULL, NULL, 'reached', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(18, 'Reached Destination', 18, NULL, NULL, NULL, 'reached', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(19, 'End Visit', 19, NULL, NULL, NULL, 'end', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(20, 'End Visit', 20, NULL, NULL, NULL, 'end', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(21, 'Visit Rescheduled', 21, NULL, NULL, NULL, 'created', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(22, 'Started Visit', 22, NULL, NULL, NULL, 'started', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(23, 'Started Visit', 23, NULL, NULL, NULL, 'started', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(24, 'Reached Destination', 24, NULL, NULL, NULL, 'reached', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(25, 'Reached Destination', 25, NULL, NULL, NULL, 'reached', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(26, 'Started Visit', 26, NULL, NULL, NULL, 'started', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(27, 'End Visit', 27, NULL, NULL, NULL, 'end', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(28, 'Visit Rescheduled', 28, NULL, NULL, NULL, 'created', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(29, 'Reached Destination', 29, NULL, NULL, NULL, 'reached', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43'),
(30, 'Reached Destination', 30, NULL, NULL, NULL, 'reached', NULL, NULL, '2023-04-18 01:07:43', '2023-04-18 01:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `web_to_leads`
--

CREATE TABLE `web_to_leads` (
  `id` bigint UNSIGNED NOT NULL,
  `form_key` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lead_source` int NOT NULL,
  `lead_status` int NOT NULL,
  `notify_lead_imported` int NOT NULL DEFAULT '1',
  `notify_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notify_ids` mediumtext COLLATE utf8mb4_unicode_ci,
  `responsible` int NOT NULL DEFAULT '0',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `form_data` mediumtext COLLATE utf8mb4_unicode_ci,
  `recaptcha` int NOT NULL DEFAULT '0',
  `submit_btn_name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submit_btn_text_color` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#ffffff',
  `submit_btn_bg_color` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#84c529',
  `success_submit_msg` text COLLATE utf8mb4_unicode_ci,
  `submit_action` int NOT NULL DEFAULT '0',
  `lead_name_prefix` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submit_redirect_url` mediumtext COLLATE utf8mb4_unicode_ci,
  `language` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allow_duplicate` int NOT NULL DEFAULT '1',
  `mark_public` int NOT NULL DEFAULT '0',
  `track_duplicate_field` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `track_duplicate_field_and` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `create_task_on_duplicate` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weekends`
--

CREATE TABLE `weekends` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `name` enum('saturday','sunday','monday','tuesday','wednesday','thursday','friday') COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int DEFAULT NULL,
  `is_weekend` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT 'no',
  `status_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT '1',
  `updated_by` bigint UNSIGNED DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `weekends`
--

INSERT INTO `weekends` (`id`, `company_id`, `name`, `order`, `is_weekend`, `status_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'saturday', NULL, 'yes', 1, 1, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(2, 1, 'sunday', NULL, 'yes', 1, 1, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(3, 1, 'monday', NULL, 'no', 1, 1, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(4, 1, 'tuesday', NULL, 'no', 1, 1, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(5, 1, 'wednesday', NULL, 'no', 1, 1, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(6, 1, 'thursday', NULL, 'no', 1, 1, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(7, 1, 'friday', NULL, 'no', 1, 1, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(8, 2, 'saturday', NULL, 'yes', 1, 1, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(9, 2, 'sunday', NULL, 'yes', 1, 1, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(10, 2, 'monday', NULL, 'no', 1, 1, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(11, 2, 'tuesday', NULL, 'no', 1, 1, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(12, 2, 'wednesday', NULL, 'no', 1, 1, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(13, 2, 'thursday', NULL, 'no', 1, 1, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41'),
(14, 2, 'friday', NULL, 'no', 1, 1, 1, '2023-04-18 01:07:41', '2023-04-18 01:07:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounts_company_id_foreign` (`company_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`),
  ADD KEY `activity_log_batch_uuid_index` (`batch_uuid`);

--
-- Indexes for table `advance_salaries`
--
ALTER TABLE `advance_salaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `advance_salaries_company_id_foreign` (`company_id`),
  ADD KEY `advance_salaries_id_amount_date_index` (`id`,`amount`,`date`),
  ADD KEY `advance_type_id` (`advance_type_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pay` (`pay`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `approver_id` (`approver_id`),
  ADD KEY `return_status` (`return_status`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `advance_salary_logs`
--
ALTER TABLE `advance_salary_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `advance_salary_logs_id_amount_index` (`id`,`amount`),
  ADD KEY `advance_salary_id` (`advance_salary_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `advance_types`
--
ALTER TABLE `advance_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `advance_types_company_id_foreign` (`company_id`),
  ADD KEY `advance_types_id_index` (`id`),
  ADD KEY `status_id` (`status_id`);

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
  ADD KEY `all_contents_type_title_slug_index` (`type`,`title`,`slug`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `api_setups`
--
ALTER TABLE `api_setups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `api_setups_company_id_foreign` (`company_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `appoinments`
--
ALTER TABLE `appoinments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appoinments_company_id_foreign` (`company_id`),
  ADD KEY `appoinments_created_by_foreign` (`created_by`),
  ADD KEY `appoinments_appoinment_with_foreign` (`appoinment_with`),
  ADD KEY `appoinments_attachment_file_id_foreign` (`attachment_file_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `appoinment_participants`
--
ALTER TABLE `appoinment_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appoinment_participants_participant_id_foreign` (`participant_id`),
  ADD KEY `appoinment_participants_appoinment_id_foreign` (`appoinment_id`);

--
-- Indexes for table `appraisals`
--
ALTER TABLE `appraisals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appraisals_company_id_foreign` (`company_id`),
  ADD KEY `appraisals_user_id_foreign` (`user_id`),
  ADD KEY `appraisals_added_by_foreign` (`added_by`);

--
-- Indexes for table `appreciates`
--
ALTER TABLE `appreciates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appreciates_user_id_foreign` (`user_id`),
  ADD KEY `appreciates_appreciate_by_foreign` (`appreciate_by`);

--
-- Indexes for table `app_screens`
--
ALTER TABLE `app_screens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `app_screens_status_id_foreign` (`status_id`);

--
-- Indexes for table `assign_leaves`
--
ALTER TABLE `assign_leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assign_leaves_type_id_foreign` (`type_id`),
  ADD KEY `assign_leaves_department_id_foreign` (`department_id`),
  ADD KEY `assign_leaves_status_id_foreign` (`status_id`),
  ADD KEY `assign_leaves_company_id_type_id_department_id_status_id_index` (`company_id`,`type_id`,`department_id`,`status_id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_company_id_foreign` (`company_id`),
  ADD KEY `attendances_user_id_foreign` (`user_id`),
  ADD KEY `attendances_status_id_foreign` (`status_id`);

--
-- Indexes for table `author_infos`
--
ALTER TABLE `author_infos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_infos_authorable_type_authorable_id_index` (`authorable_type`,`authorable_id`),
  ADD KEY `author_infos_created_by_foreign` (`created_by`),
  ADD KEY `author_infos_updated_by_foreign` (`updated_by`),
  ADD KEY `author_infos_approved_by_foreign` (`approved_by`),
  ADD KEY `author_infos_rejected_by_foreign` (`rejected_by`),
  ADD KEY `author_infos_cancelled_by_foreign` (`cancelled_by`),
  ADD KEY `author_infos_published_by_foreign` (`published_by`),
  ADD KEY `author_infos_unpublished_by_foreign` (`unpublished_by`),
  ADD KEY `author_infos_deleted_by_foreign` (`deleted_by`),
  ADD KEY `author_infos_archived_by_foreign` (`archived_by`),
  ADD KEY `author_infos_restored_by_foreign` (`restored_by`),
  ADD KEY `author_infos_referred_by_foreign` (`referred_by`);

--
-- Indexes for table `awards`
--
ALTER TABLE `awards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `awards_company_id_foreign` (`company_id`),
  ADD KEY `awards_user_id_foreign` (`user_id`),
  ADD KEY `awards_created_by_foreign` (`created_by`),
  ADD KEY `awards_award_type_id_foreign` (`award_type_id`),
  ADD KEY `awards_attachment_foreign` (`attachment`),
  ADD KEY `awards_goal_id_foreign` (`goal_id`),
  ADD KEY `awards_id_award_type_id_company_id_status_id_user_id_index` (`id`,`award_type_id`,`company_id`,`status_id`,`user_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `award_types`
--
ALTER TABLE `award_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `award_types_company_id_foreign` (`company_id`),
  ADD KEY `award_types_id_index` (`id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bank_accounts_account_number_unique` (`account_number`),
  ADD KEY `bank_accounts_user_id_index` (`user_id`),
  ADD KEY `bank_accounts_status_id_index` (`status_id`),
  ADD KEY `bank_accounts_author_info_id_index` (`author_info_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_name_type_serial_index` (`name`,`type`,`serial`),
  ADD KEY `categories_status_id_index` (`status_id`),
  ADD KEY `categories_author_info_id_index` (`author_info_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clients_company_id_foreign` (`company_id`),
  ADD KEY `clients_avatar_id_foreign` (`avatar_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commissions_company_id_foreign` (`company_id`),
  ADD KEY `commissions_id_index` (`id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `companies_email_unique` (`email`),
  ADD UNIQUE KEY `companies_phone_unique` (`phone`),
  ADD KEY `companies_country_id_foreign` (`country_id`),
  ADD KEY `trade_licence_id` (`trade_licence_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `company_configs`
--
ALTER TABLE `company_configs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_configs_company_id_foreign` (`company_id`);

--
-- Indexes for table `competences`
--
ALTER TABLE `competences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `competences_competence_type_id_foreign` (`competence_type_id`),
  ADD KEY `competences_company_id_foreign` (`company_id`),
  ADD KEY `competences_id_status_id_company_id_index` (`id`,`status_id`,`company_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `competence_types`
--
ALTER TABLE `competence_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `competence_types_company_id_foreign` (`company_id`),
  ADD KEY `competence_types_id_status_id_company_id_index` (`id`,`status_id`,`company_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversations_sender_id_foreign` (`sender_id`),
  ADD KEY `conversations_receiver_id_foreign` (`receiver_id`),
  ADD KEY `conversations_image_id_foreign` (`image_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `countries_country_code_unique` (`country_code`),
  ADD UNIQUE KEY `countries_name_unique` (`name`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daily_leaves`
--
ALTER TABLE `daily_leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_leaves_company_id_foreign` (`company_id`),
  ADD KEY `daily_leaves_user_id_foreign` (`user_id`),
  ADD KEY `daily_leaves_approved_by_tl_foreign` (`approved_by_tl`),
  ADD KEY `daily_leaves_approved_by_hr_foreign` (`approved_by_hr`),
  ADD KEY `daily_leaves_rejected_by_tl_foreign` (`rejected_by_tl`),
  ADD KEY `daily_leaves_rejected_by_hr_foreign` (`rejected_by_hr`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `database_backups`
--
ALTER TABLE `database_backups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `date_formats`
--
ALTER TABLE `date_formats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departments_company_id_foreign` (`company_id`),
  ADD KEY `departments_status_id_foreign` (`status_id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deposits_income_expense_category_id_foreign` (`income_expense_category_id`),
  ADD KEY `deposits_company_id_foreign` (`company_id`),
  ADD KEY `deposits_attachment_foreign` (`attachment`),
  ADD KEY `deposits_id_amount_date_index` (`id`,`amount`,`date`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `payment_method_id` (`payment_method_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `pay` (`pay`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `approver_id` (`approver_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `designations_company_id_foreign` (`company_id`),
  ADD KEY `designations_status_id_foreign` (`status_id`);

--
-- Indexes for table `discussions`
--
ALTER TABLE `discussions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discussions_company_id_foreign` (`company_id`),
  ADD KEY `discussions_project_id_foreign` (`project_id`),
  ADD KEY `discussions_user_id_foreign` (`user_id`),
  ADD KEY `discussions_id_project_id_company_id_status_id_user_id_index` (`id`,`project_id`,`company_id`,`status_id`,`user_id`),
  ADD KEY `show_to_customer` (`show_to_customer`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `discussion_comments`
--
ALTER TABLE `discussion_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discussion_comments_company_id_foreign` (`company_id`),
  ADD KEY `discussion_comments_discussion_id_foreign` (`discussion_id`),
  ADD KEY `discussion_comments_user_id_foreign` (`user_id`),
  ADD KEY `discussion_comments_attachment_foreign` (`attachment`),
  ADD KEY `discussion_comments_id_discussion_id_company_id_user_id_index` (`id`,`discussion_id`,`company_id`,`user_id`);

--
-- Indexes for table `discussion_likes`
--
ALTER TABLE `discussion_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discussion_likes_discussion_id_foreign` (`discussion_id`),
  ADD KEY `discussion_likes_user_id_foreign` (`user_id`);

--
-- Indexes for table `duty_schedules`
--
ALTER TABLE `duty_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `duty_schedules_company_id_foreign` (`company_id`),
  ADD KEY `duty_schedules_shift_id_foreign` (`shift_id`),
  ADD KEY `duty_schedules_status_id_foreign` (`status_id`),
  ADD KEY `duty_schedules_id_index` (`id`);

--
-- Indexes for table `employee_breaks`
--
ALTER TABLE `employee_breaks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_breaks_company_id_foreign` (`company_id`),
  ADD KEY `employee_breaks_user_id_foreign` (`user_id`);

--
-- Indexes for table `employee_tasks`
--
ALTER TABLE `employee_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_tasks_assigned_id_foreign` (`assigned_id`),
  ADD KEY `employee_tasks_created_by_foreign` (`created_by`),
  ADD KEY `employee_tasks_attachment_file_id_foreign` (`attachment_file_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_income_expense_category_id_foreign` (`income_expense_category_id`),
  ADD KEY `expenses_company_id_foreign` (`company_id`),
  ADD KEY `expenses_attachment_foreign` (`attachment`),
  ADD KEY `expenses_id_amount_date_index` (`id`,`amount`,`date`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pay` (`pay`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `payment_method_id` (`payment_method_id`),
  ADD KEY `approver_id` (`approver_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `expense_claims`
--
ALTER TABLE `expense_claims`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expense_claims_invoice_number_unique` (`invoice_number`),
  ADD KEY `expense_claims_company_id_foreign` (`company_id`),
  ADD KEY `expense_claims_user_id_foreign` (`user_id`),
  ADD KEY `expense_claims_attachment_file_id_foreign` (`attachment_file_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `expense_claim_details`
--
ALTER TABLE `expense_claim_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_claim_details_company_id_foreign` (`company_id`),
  ADD KEY `expense_claim_details_user_id_foreign` (`user_id`),
  ADD KEY `expense_claim_details_hrm_expense_id_foreign` (`hrm_expense_id`),
  ADD KEY `expense_claim_details_expense_claim_id_foreign` (`expense_claim_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `features_attachment_file_id_foreign` (`attachment_file_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `front_teams`
--
ALTER TABLE `front_teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `front_teams_attachment_foreign` (`attachment`),
  ADD KEY `front_teams_user_id_foreign` (`user_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goals_company_id_foreign` (`company_id`),
  ADD KEY `goals_goal_type_id_foreign` (`goal_type_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `goal_types`
--
ALTER TABLE `goal_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goal_types_company_id_foreign` (`company_id`),
  ADD KEY `goal_types_id_status_id_company_id_index` (`id`,`status_id`,`company_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `holidays_company_id_foreign` (`company_id`),
  ADD KEY `holidays_attachment_id_foreign` (`attachment_id`),
  ADD KEY `holidays_status_id_foreign` (`status_id`),
  ADD KEY `holidays_id_index` (`id`);

--
-- Indexes for table `home_pages`
--
ALTER TABLE `home_pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `hrm_expenses`
--
ALTER TABLE `hrm_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_expenses_company_id_foreign` (`company_id`),
  ADD KEY `hrm_expenses_user_id_foreign` (`user_id`),
  ADD KEY `hrm_expenses_income_expense_category_id_foreign` (`income_expense_category_id`),
  ADD KEY `hrm_expenses_attachment_file_id_foreign` (`attachment_file_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `claimed_status_id` (`is_claimed_status_id`),
  ADD KEY `claimed_approved_status_id` (`claimed_approved_status_id`);

--
-- Indexes for table `hrm_languages`
--
ALTER TABLE `hrm_languages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `income_expense_categories`
--
ALTER TABLE `income_expense_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `income_expense_categories_company_id_foreign` (`company_id`),
  ADD KEY `income_expense_categories_attachment_file_id_foreign` (`attachment_file_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `indicators`
--
ALTER TABLE `indicators`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indicators_company_id_foreign` (`company_id`),
  ADD KEY `indicators_department_id_foreign` (`department_id`),
  ADD KEY `indicators_shift_id_foreign` (`shift_id`),
  ADD KEY `indicators_designation_id_foreign` (`designation_id`),
  ADD KEY `indicators_added_by_foreign` (`added_by`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_payment_records`
--
ALTER TABLE `invoice_payment_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ip_setups`
--
ALTER TABLE `ip_setups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ip_setups_status_id_foreign` (`status_id`),
  ADD KEY `ip_setups_company_id_foreign` (`company_id`);

--
-- Indexes for table `jitsi_meetings`
--
ALTER TABLE `jitsi_meetings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `late_in_out_reasons`
--
ALTER TABLE `late_in_out_reasons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `late_in_out_reasons_company_id_foreign` (`company_id`),
  ADD KEY `late_in_out_reasons_attendance_id_foreign` (`attendance_id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leads_lead_type_id_foreign` (`lead_type_id`),
  ADD KEY `leads_lead_source_id_foreign` (`lead_source_id`),
  ADD KEY `leads_lead_status_id_foreign` (`lead_status_id`),
  ADD KEY `leads_created_by_foreign` (`created_by`),
  ADD KEY `leads_company_id_foreign` (`company_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `leads_email_integrations`
--
ALTER TABLE `leads_email_integrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lead_activity_logs`
--
ALTER TABLE `lead_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_activity_logs_lead_type_id_foreign` (`lead_type_id`),
  ADD KEY `lead_activity_logs_lead_source_id_foreign` (`lead_source_id`),
  ADD KEY `lead_activity_logs_lead_status_id_foreign` (`lead_status_id`),
  ADD KEY `lead_activity_logs_lead_id_foreign` (`lead_id`),
  ADD KEY `lead_activity_logs_created_by_foreign` (`created_by`),
  ADD KEY `lead_activity_logs_company_id_foreign` (`company_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `lead_attachments`
--
ALTER TABLE `lead_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_attachments_user_id_foreign` (`user_id`),
  ADD KEY `lead_attachments_lead_id_foreign` (`lead_id`),
  ADD KEY `lead_attachments_company_id_foreign` (`company_id`),
  ADD KEY `show_to_clients` (`show_to_clients`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `lead_integration_emails`
--
ALTER TABLE `lead_integration_emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lead_sources`
--
ALTER TABLE `lead_sources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_sources_created_by_foreign` (`created_by`),
  ADD KEY `lead_sources_updated_by_foreign` (`updated_by`),
  ADD KEY `lead_sources_company_id_foreign` (`company_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `lead_statuses`
--
ALTER TABLE `lead_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_statuses_created_by_foreign` (`created_by`),
  ADD KEY `lead_statuses_updated_by_foreign` (`updated_by`),
  ADD KEY `lead_statuses_company_id_foreign` (`company_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `lead_subscriptions`
--
ALTER TABLE `lead_subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lead_types`
--
ALTER TABLE `lead_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_types_created_by_foreign` (`created_by`),
  ADD KEY `lead_types_updated_by_foreign` (`updated_by`),
  ADD KEY `lead_types_company_id_foreign` (`company_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_requests_company_id_foreign` (`company_id`),
  ADD KEY `leave_requests_assign_leave_id_foreign` (`assign_leave_id`),
  ADD KEY `leave_requests_user_id_foreign` (`user_id`),
  ADD KEY `leave_requests_substitute_id_foreign` (`substitute_id`),
  ADD KEY `leave_requests_attachment_file_id_foreign` (`attachment_file_id`),
  ADD KEY `leave_requests_status_id_foreign` (`status_id`),
  ADD KEY `leave_requests_author_info_id_foreign` (`author_info_id`);

--
-- Indexes for table `leave_settings`
--
ALTER TABLE `leave_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_settings_company_id_foreign` (`company_id`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_types_company_id_foreign` (`company_id`),
  ADD KEY `leave_types_status_id_foreign` (`status_id`),
  ADD KEY `leave_types_name_status_id_index` (`name`,`status_id`);

--
-- Indexes for table `location_binds`
--
ALTER TABLE `location_binds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_binds_user_id_foreign` (`user_id`),
  ADD KEY `location_binds_company_id_foreign` (`company_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `location_logs`
--
ALTER TABLE `location_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_logs_user_id_foreign` (`user_id`),
  ADD KEY `location_logs_company_id_foreign` (`company_id`),
  ADD KEY `location_logs_id_user_id_company_id_date_index` (`id`,`user_id`,`company_id`,`date`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meetings_company_id_foreign` (`company_id`),
  ADD KEY `meetings_user_id_foreign` (`user_id`),
  ADD KEY `meetings_attachment_file_id_foreign` (`attachment_file_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `meeting_members`
--
ALTER TABLE `meeting_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `meeting_participants`
--
ALTER TABLE `meeting_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meeting_participants_participant_id_foreign` (`participant_id`),
  ADD KEY `meeting_participants_meeting_id_foreign` (`meeting_id`);

--
-- Indexes for table `meeting_setups`
--
ALTER TABLE `meeting_setups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meeting_setups_company_id_foreign` (`company_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `meta_information`
--
ALTER TABLE `meta_information`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meta_information_created_by_foreign` (`created_by`),
  ADD KEY `meta_information_updated_by_foreign` (`updated_by`),
  ADD KEY `meta_information_id_type_index` (`id`,`type`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notes_company_id_foreign` (`company_id`),
  ADD KEY `notes_project_id_foreign` (`project_id`),
  ADD KEY `notes_user_id_foreign` (`user_id`),
  ADD KEY `notes_id_project_id_company_id_status_id_user_id_index` (`id`,`project_id`,`company_id`,`status_id`,`user_id`),
  ADD KEY `show_to_customer` (`show_to_customer`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notices_created_by_foreign` (`created_by`),
  ADD KEY `notices_company_id_foreign` (`company_id`),
  ADD KEY `notices_department_id_foreign` (`department_id`),
  ADD KEY `notices_attachment_file_id_foreign` (`attachment_file_id`),
  ADD KEY `notices_status_id_foreign` (`status_id`);

--
-- Indexes for table `notice_departments`
--
ALTER TABLE `notice_departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notice_view_logs`
--
ALTER TABLE `notice_view_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notice_view_logs_company_id_foreign` (`company_id`),
  ADD KEY `notice_view_logs_user_id_foreign` (`user_id`),
  ADD KEY `notice_view_logs_notice_id_foreign` (`notice_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `notifications_old`
--
ALTER TABLE `notifications_old`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_old_sender_id_foreign` (`sender_id`),
  ADD KEY `notifications_old_receiver_id_foreign` (`receiver_id`),
  ADD KEY `notifications_old_image_id_foreign` (`image_id`);

--
-- Indexes for table `notification_types`
--
ALTER TABLE `notification_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notification_types_type_unique` (`type`),
  ADD KEY `notification_types_icon_foreign` (`icon`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment_histories`
--
ALTER TABLE `payment_histories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_histories_code_unique` (`code`),
  ADD KEY `payment_histories_company_id_foreign` (`company_id`),
  ADD KEY `payment_histories_user_id_foreign` (`user_id`),
  ADD KEY `payment_histories_expense_claim_id_foreign` (`expense_claim_id`),
  ADD KEY `payment_histories_attachment_file_id_foreign` (`attachment_file_id`),
  ADD KEY `status_id` (`payment_status_id`);

--
-- Indexes for table `payment_history_details`
--
ALTER TABLE `payment_history_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_history_details_company_id_foreign` (`company_id`),
  ADD KEY `payment_history_details_user_id_foreign` (`user_id`),
  ADD KEY `payment_history_details_payment_history_id_foreign` (`payment_history_id`),
  ADD KEY `payment_history_details_payment_method_id_foreign` (`payment_method_id`),
  ADD KEY `payment_history_details_paid_by_id_foreign` (`paid_by_id`),
  ADD KEY `payment_status_id` (`payment_status_id`);

--
-- Indexes for table `payment_history_logs`
--
ALTER TABLE `payment_history_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_history_logs_user_id_foreign` (`user_id`),
  ADD KEY `payment_history_logs_company_id_foreign` (`company_id`),
  ADD KEY `payment_history_logs_payment_history_id_foreign` (`payment_history_id`),
  ADD KEY `payment_history_logs_expense_claim_id_foreign` (`expense_claim_id`),
  ADD KEY `payment_history_logs_paid_by_id_foreign` (`paid_by_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_methods_company_id_foreign` (`company_id`),
  ADD KEY `payment_methods_attachment_file_id_foreign` (`attachment_file_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `payment_types`
--
ALTER TABLE `payment_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_types_status_id_index` (`status_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `portfolios_attachment_foreign` (`attachment`),
  ADD KEY `portfolios_user_id_foreign` (`user_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `product_purchases`
--
ALTER TABLE `product_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_purchases_company_id_foreign` (`company_id`),
  ADD KEY `product_purchases_stock_payment_history_id_foreign` (`stock_payment_history_id`),
  ADD KEY `product_purchases_client_id_foreign` (`client_id`);

--
-- Indexes for table `product_purchase_histories`
--
ALTER TABLE `product_purchase_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_purchase_histories_company_id_foreign` (`company_id`),
  ADD KEY `product_purchase_histories_product_purchase_id_foreign` (`product_purchase_id`),
  ADD KEY `product_purchase_histories_product_unit_id_foreign` (`product_unit_id`);

--
-- Indexes for table `product_units`
--
ALTER TABLE `product_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_units_company_id_foreign` (`company_id`),
  ADD KEY `product_units_attachment_file_id_foreign` (`attachment_file_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_client_id_foreign` (`client_id`),
  ADD KEY `projects_company_id_foreign` (`company_id`),
  ADD KEY `projects_goal_id_foreign` (`goal_id`),
  ADD KEY `projects_avatar_id_foreign` (`avatar_id`),
  ADD KEY `projects_id_client_id_company_id_status_id_index` (`id`,`client_id`,`company_id`,`status_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `priority` (`priority`),
  ADD KEY `payment` (`payment`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `project_activities`
--
ALTER TABLE `project_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_activities_company_id_foreign` (`company_id`),
  ADD KEY `project_activities_project_id_foreign` (`project_id`),
  ADD KEY `project_activities_user_id_foreign` (`user_id`),
  ADD KEY `project_activities_id_project_id_company_id_user_id_index` (`id`,`project_id`,`company_id`,`user_id`);

--
-- Indexes for table `project_files`
--
ALTER TABLE `project_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_files_company_id_foreign` (`company_id`),
  ADD KEY `project_files_project_id_foreign` (`project_id`),
  ADD KEY `project_files_user_id_foreign` (`user_id`),
  ADD KEY `project_files_attachment_foreign` (`attachment`),
  ADD KEY `project_files_id_project_id_company_id_status_id_user_id_index` (`id`,`project_id`,`company_id`,`status_id`,`user_id`),
  ADD KEY `show_to_customer` (`show_to_customer`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `project_file_comments`
--
ALTER TABLE `project_file_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_file_comments_company_id_foreign` (`company_id`),
  ADD KEY `project_file_comments_project_file_id_foreign` (`project_file_id`),
  ADD KEY `project_file_comments_user_id_foreign` (`user_id`),
  ADD KEY `project_file_comments_attachment_foreign` (`attachment`),
  ADD KEY `project_file_comments_id_project_file_id_user_id_index` (`id`,`project_file_id`,`user_id`);

--
-- Indexes for table `project_membars`
--
ALTER TABLE `project_membars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_membars_company_id_foreign` (`company_id`),
  ADD KEY `project_membars_project_id_foreign` (`project_id`),
  ADD KEY `project_membars_user_id_foreign` (`user_id`),
  ADD KEY `project_membars_added_by_foreign` (`added_by`),
  ADD KEY `project_membars_id_project_id_company_id_status_id_user_id_index` (`id`,`project_id`,`company_id`,`status_id`,`user_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `project_payments`
--
ALTER TABLE `project_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_payments_project_id_foreign` (`project_id`),
  ADD KEY `project_payments_company_id_foreign` (`company_id`),
  ADD KEY `project_payments_id_amount_index` (`id`,`amount`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `payment_method_id` (`payment_method_id`),
  ADD KEY `paid_by` (`paid_by`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `proposals`
--
ALTER TABLE `proposals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proposal_comments`
--
ALTER TABLE `proposal_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roles_company_id_foreign` (`company_id`),
  ADD KEY `roles_status_id_foreign` (`status_id`);

--
-- Indexes for table `role_users`
--
ALTER TABLE `role_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_users_user_id_foreign` (`user_id`),
  ADD KEY `role_users_role_id_foreign` (`role_id`);

--
-- Indexes for table `salary_generates`
--
ALTER TABLE `salary_generates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_generates_company_id_foreign` (`company_id`),
  ADD KEY `salary_generates_id_amount_date_index` (`id`,`amount`,`date`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `salary_payment_logs`
--
ALTER TABLE `salary_payment_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_payment_logs_salary_generate_id_foreign` (`salary_generate_id`),
  ADD KEY `salary_payment_logs_company_id_foreign` (`company_id`),
  ADD KEY `salary_payment_logs_id_amount_index` (`id`,`amount`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `payment_method_id` (`payment_method_id`),
  ADD KEY `paid_by` (`paid_by`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `salary_setups`
--
ALTER TABLE `salary_setups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_setups_company_id_foreign` (`company_id`),
  ADD KEY `salary_setups_id_gross_salary_index` (`id`,`gross_salary`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `salary_setup_details`
--
ALTER TABLE `salary_setup_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_setup_details_company_id_foreign` (`company_id`),
  ADD KEY `salary_setup_details_id_amount_index` (`id`,`amount`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `salary_setup_id` (`salary_setup_id`),
  ADD KEY `commission_id` (`commission_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_accounts`
--
ALTER TABLE `sale_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_adjustments`
--
ALTER TABLE `sale_adjustments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_billers`
--
ALTER TABLE `sale_billers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_brands`
--
ALTER TABLE `sale_brands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_cash_registers`
--
ALTER TABLE `sale_cash_registers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_categories`
--
ALTER TABLE `sale_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_coupons`
--
ALTER TABLE `sale_coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_customers`
--
ALTER TABLE `sale_customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_customer_groups`
--
ALTER TABLE `sale_customer_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_deliveries`
--
ALTER TABLE `sale_deliveries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_discounts`
--
ALTER TABLE `sale_discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_discount_plans`
--
ALTER TABLE `sale_discount_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_discount_plan_customers`
--
ALTER TABLE `sale_discount_plan_customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_discount_plan_discounts`
--
ALTER TABLE `sale_discount_plan_discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_expenses`
--
ALTER TABLE `sale_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_expense_categories`
--
ALTER TABLE `sale_expense_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_gift_cards`
--
ALTER TABLE `sale_gift_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_gift_card_recharges`
--
ALTER TABLE `sale_gift_card_recharges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_payments`
--
ALTER TABLE `sale_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_payment_with_cheques`
--
ALTER TABLE `sale_payment_with_cheques`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_pos_settings`
--
ALTER TABLE `sale_pos_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_products`
--
ALTER TABLE `sale_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_product_adjustments`
--
ALTER TABLE `sale_product_adjustments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_product_batches`
--
ALTER TABLE `sale_product_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_product_purchases`
--
ALTER TABLE `sale_product_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_product_quotations`
--
ALTER TABLE `sale_product_quotations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_product_returns`
--
ALTER TABLE `sale_product_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_product_sales`
--
ALTER TABLE `sale_product_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_product_transfers`
--
ALTER TABLE `sale_product_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_product_variants`
--
ALTER TABLE `sale_product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_product_warehouses`
--
ALTER TABLE `sale_product_warehouses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_purchases`
--
ALTER TABLE `sale_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_purchase_product_returns`
--
ALTER TABLE `sale_purchase_product_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_quotations`
--
ALTER TABLE `sale_quotations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_returns`
--
ALTER TABLE `sale_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_return_purchases`
--
ALTER TABLE `sale_return_purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_reward_point_settings`
--
ALTER TABLE `sale_reward_point_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_stock_counts`
--
ALTER TABLE `sale_stock_counts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_suppliers`
--
ALTER TABLE `sale_suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_taxes`
--
ALTER TABLE `sale_taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_transfers`
--
ALTER TABLE `sale_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_units`
--
ALTER TABLE `sale_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_variants`
--
ALTER TABLE `sale_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sale_warehouses`
--
ALTER TABLE `sale_warehouses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `search_menus`
--
ALTER TABLE `search_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_user_id_foreign` (`user_id`),
  ADD KEY `services_attachment_foreign` (`attachment`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_company_id_foreign` (`company_id`),
  ADD KEY `settings_name_value_context_index` (`name`,`value`,`context`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shifts_company_id_foreign` (`company_id`),
  ADD KEY `shifts_status_id_foreign` (`status_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `skills_user_id_foreign` (`user_id`);

--
-- Indexes for table `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_identities`
--
ALTER TABLE `social_identities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `social_identities_provider_id_unique` (`provider_id`),
  ADD KEY `social_identities_user_id_foreign` (`user_id`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `statuses_name_class_index` (`name`,`class`),
  ADD KEY `statuses_name_index` (`name`),
  ADD KEY `statuses_class_index` (`class`);

--
-- Indexes for table `stock_brands`
--
ALTER TABLE `stock_brands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_brands_company_id_foreign` (`company_id`),
  ADD KEY `stock_brands_avatar_id_foreign` (`avatar_id`),
  ADD KEY `stock_brands_status_id_index` (`status_id`),
  ADD KEY `stock_brands_author_info_id_index` (`author_info_id`);

--
-- Indexes for table `stock_categories`
--
ALTER TABLE `stock_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_categories_company_id_foreign` (`company_id`),
  ADD KEY `stock_categories_avatar_id_foreign` (`avatar_id`),
  ADD KEY `stock_categories_status_id_index` (`status_id`),
  ADD KEY `stock_categories_author_info_id_index` (`author_info_id`);

--
-- Indexes for table `stock_histories`
--
ALTER TABLE `stock_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_histories_company_id_foreign` (`company_id`),
  ADD KEY `stock_histories_stock_product_id_foreign` (`stock_product_id`),
  ADD KEY `stock_histories_product_purchase_id_foreign` (`product_purchase_id`),
  ADD KEY `stock_histories_product_unit_id_foreign` (`product_unit_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `stock_payment_histories`
--
ALTER TABLE `stock_payment_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_payment_histories_company_id_foreign` (`company_id`),
  ADD KEY `stock_payment_histories_bank_id_foreign` (`bank_id`),
  ADD KEY `stock_payment_histories_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `stock_products`
--
ALTER TABLE `stock_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_products_company_id_foreign` (`company_id`),
  ADD KEY `stock_products_avatar_id_foreign` (`avatar_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `stock_brand_id` (`stock_brand_id`),
  ADD KEY `category_id` (`stock_category_id`);

--
-- Indexes for table `stock_sales`
--
ALTER TABLE `stock_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_sales_company_id_foreign` (`company_id`),
  ADD KEY `stock_sales_client_id_foreign` (`client_id`),
  ADD KEY `stock_sales_stock_product_id_foreign` (`stock_product_id`),
  ADD KEY `stock_sales_stock_payment_history_id_foreign` (`stock_payment_history_id`),
  ADD KEY `stock_sales_status_id_foreign` (`status_id`),
  ADD KEY `stock_sales_payment_status_id_foreign` (`payment_status_id`),
  ADD KEY `stock_sales_created_by_foreign` (`created_by`),
  ADD KEY `stock_sales_updated_by_foreign` (`updated_by`),
  ADD KEY `stock_sales_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `stock_sale_histories`
--
ALTER TABLE `stock_sale_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_sale_histories_company_id_foreign` (`company_id`),
  ADD KEY `stock_sale_histories_stock_sale_id_foreign` (`stock_sale_id`),
  ADD KEY `stock_sale_histories_stock_product_id_foreign` (`stock_product_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscriptions_stripe_id_unique` (`stripe_id`),
  ADD KEY `subscriptions_user_id_stripe_status_index` (`user_id`,`stripe_status`);

--
-- Indexes for table `subscription_items`
--
ALTER TABLE `subscription_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_items_subscription_id_stripe_price_unique` (`subscription_id`,`stripe_price`),
  ADD UNIQUE KEY `subscription_items_stripe_id_unique` (`stripe_id`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_plans_identifier_unique` (`identifier`),
  ADD UNIQUE KEY `subscription_plans_stripe_id_unique` (`stripe_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_tickets_company_id_foreign` (`company_id`),
  ADD KEY `support_tickets_user_id_foreign` (`user_id`),
  ADD KEY `support_tickets_assigned_id_foreign` (`assigned_id`),
  ADD KEY `support_tickets_attachment_file_id_foreign` (`attachment_file_id`),
  ADD KEY `support_tickets_status_id_foreign` (`status_id`),
  ADD KEY `support_tickets_type_id_foreign` (`type_id`),
  ADD KEY `support_tickets_priority_id_foreign` (`priority_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_company_id_foreign` (`company_id`),
  ADD KEY `tasks_goal_id_foreign` (`goal_id`),
  ADD KEY `tasks_id_company_id_status_id_index` (`id`,`company_id`,`status_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `priority` (`priority`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `task_activities`
--
ALTER TABLE `task_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_activities_company_id_foreign` (`company_id`),
  ADD KEY `task_activities_task_id_foreign` (`task_id`),
  ADD KEY `task_activities_user_id_foreign` (`user_id`),
  ADD KEY `task_activities_id_task_id_company_id_user_id_index` (`id`,`task_id`,`company_id`,`user_id`);

--
-- Indexes for table `task_discussions`
--
ALTER TABLE `task_discussions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_discussions_company_id_foreign` (`company_id`),
  ADD KEY `task_discussions_task_id_foreign` (`task_id`),
  ADD KEY `task_discussions_user_id_foreign` (`user_id`),
  ADD KEY `task_discussions_file_id_foreign` (`file_id`),
  ADD KEY `task_discussions_id_task_id_company_id_status_id_user_id_index` (`id`,`task_id`,`company_id`,`status_id`,`user_id`),
  ADD KEY `show_to_customer` (`show_to_customer`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `task_discussion_comments`
--
ALTER TABLE `task_discussion_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_discussion_comments_company_id_foreign` (`company_id`),
  ADD KEY `task_discussion_comments_task_discussion_id_foreign` (`task_discussion_id`),
  ADD KEY `task_discussion_comments_user_id_foreign` (`user_id`),
  ADD KEY `task_discussion_comments_attachment_foreign` (`attachment`),
  ADD KEY `task_discussion_comments_id_task_discussion_id_company_id_index` (`id`,`task_discussion_id`,`company_id`);

--
-- Indexes for table `task_files`
--
ALTER TABLE `task_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_files_company_id_foreign` (`company_id`),
  ADD KEY `task_files_task_id_foreign` (`task_id`),
  ADD KEY `task_files_user_id_foreign` (`user_id`),
  ADD KEY `task_files_attachment_foreign` (`attachment`),
  ADD KEY `task_files_id_task_id_company_id_status_id_user_id_index` (`id`,`task_id`,`company_id`,`status_id`,`user_id`),
  ADD KEY `show_to_customer` (`show_to_customer`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `task_file_comments`
--
ALTER TABLE `task_file_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_file_comments_company_id_foreign` (`company_id`),
  ADD KEY `task_file_comments_task_file_id_foreign` (`task_file_id`),
  ADD KEY `task_file_comments_user_id_foreign` (`user_id`),
  ADD KEY `task_file_comments_attachment_foreign` (`attachment`),
  ADD KEY `task_file_comments_id_task_file_id_user_id_index` (`id`,`task_file_id`,`user_id`);

--
-- Indexes for table `task_members`
--
ALTER TABLE `task_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_members_company_id_foreign` (`company_id`),
  ADD KEY `task_members_task_id_foreign` (`task_id`),
  ADD KEY `task_members_user_id_foreign` (`user_id`),
  ADD KEY `task_members_added_by_foreign` (`added_by`),
  ADD KEY `task_members_id_task_id_company_id_status_id_user_id_index` (`id`,`task_id`,`company_id`,`status_id`,`user_id`),
  ADD KEY `task_members_is_creator_foreign` (`is_creator`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `task_notes`
--
ALTER TABLE `task_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_notes_company_id_foreign` (`company_id`),
  ADD KEY `task_notes_task_id_foreign` (`task_id`),
  ADD KEY `task_notes_user_id_foreign` (`user_id`),
  ADD KEY `task_notes_id_task_id_company_id_status_id_user_id_index` (`id`,`task_id`,`company_id`,`status_id`,`user_id`),
  ADD KEY `show_to_customer` (`show_to_customer`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teams_attachment_file_id_foreign` (`attachment_file_id`),
  ADD KEY `teams_company_id_foreign` (`company_id`),
  ADD KEY `teams_user_id_foreign` (`user_id`),
  ADD KEY `teams_team_lead_id_foreign` (`team_lead_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_members_team_id_foreign` (`team_id`),
  ADD KEY `team_members_user_id_foreign` (`user_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `testimonials_company_id_foreign` (`company_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_replies_support_ticket_id_foreign` (`support_ticket_id`),
  ADD KEY `ticket_replies_user_id_foreign` (`user_id`);

--
-- Indexes for table `time_zones`
--
ALTER TABLE `time_zones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_company_id_foreign` (`company_id`),
  ADD KEY `transactions_account_id_foreign` (`account_id`),
  ADD KEY `transaction_type` (`transaction_type`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `income_expense_category_id` (`income_expense_category_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `travel`
--
ALTER TABLE `travel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `travel_company_id_foreign` (`company_id`),
  ADD KEY `travel_user_id_foreign` (`user_id`),
  ADD KEY `travel_created_by_foreign` (`created_by`),
  ADD KEY `travel_travel_type_id_foreign` (`travel_type_id`),
  ADD KEY `travel_attachment_foreign` (`attachment`),
  ADD KEY `travel_goal_id_foreign` (`goal_id`),
  ADD KEY `travel_id_travel_type_id_company_id_status_id_user_id_index` (`id`,`travel_type_id`,`company_id`,`status_id`,`user_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `travel_types`
--
ALTER TABLE `travel_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `travel_types_company_id_foreign` (`company_id`),
  ADD KEY `travel_types_id_status_id_company_id_index` (`id`,`status_id`,`company_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploads_status_id_index` (`status_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD KEY `users_company_id_foreign` (`company_id`),
  ADD KEY `users_country_id_foreign` (`country_id`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_department_id_foreign` (`department_id`),
  ADD KEY `users_shift_id_foreign` (`shift_id`),
  ADD KEY `users_designation_id_foreign` (`designation_id`),
  ADD KEY `users_manager_id_foreign` (`manager_id`),
  ADD KEY `users_nid_card_id_foreign` (`nid_card_id`),
  ADD KEY `users_passport_file_id_foreign` (`passport_file_id`),
  ADD KEY `users_avatar_id_foreign` (`avatar_id`),
  ADD KEY `users_status_id_index` (`status_id`),
  ADD KEY `users_stripe_id_index` (`stripe_id`);

--
-- Indexes for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_devices_user_id_foreign` (`user_id`);

--
-- Indexes for table `virtual_meetings`
--
ALTER TABLE `virtual_meetings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `virtual_meetings_company_id_foreign` (`company_id`),
  ADD KEY `virtual_meetings_created_by_foreign` (`created_by`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visits_company_id_foreign` (`company_id`),
  ADD KEY `visits_user_id_foreign` (`user_id`);

--
-- Indexes for table `visit_images`
--
ALTER TABLE `visit_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visit_notes`
--
ALTER TABLE `visit_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visit_notes_visit_id_foreign` (`visit_id`);

--
-- Indexes for table `visit_schedules`
--
ALTER TABLE `visit_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visit_schedules_visit_id_foreign` (`visit_id`);

--
-- Indexes for table `web_to_leads`
--
ALTER TABLE `web_to_leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weekends`
--
ALTER TABLE `weekends`
  ADD PRIMARY KEY (`id`),
  ADD KEY `weekends_company_id_foreign` (`company_id`),
  ADD KEY `weekends_status_id_foreign` (`status_id`),
  ADD KEY `weekends_id_index` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `advance_salaries`
--
ALTER TABLE `advance_salaries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `advance_salary_logs`
--
ALTER TABLE `advance_salary_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `advance_types`
--
ALTER TABLE `advance_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `all_contents`
--
ALTER TABLE `all_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `api_setups`
--
ALTER TABLE `api_setups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `appoinments`
--
ALTER TABLE `appoinments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `appoinment_participants`
--
ALTER TABLE `appoinment_participants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `appraisals`
--
ALTER TABLE `appraisals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appreciates`
--
ALTER TABLE `appreciates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `app_screens`
--
ALTER TABLE `app_screens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `assign_leaves`
--
ALTER TABLE `assign_leaves`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `author_infos`
--
ALTER TABLE `author_infos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `awards`
--
ALTER TABLE `awards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `award_types`
--
ALTER TABLE `award_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `company_configs`
--
ALTER TABLE `company_configs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `competences`
--
ALTER TABLE `competences`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `competence_types`
--
ALTER TABLE `competence_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `daily_leaves`
--
ALTER TABLE `daily_leaves`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `database_backups`
--
ALTER TABLE `database_backups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `date_formats`
--
ALTER TABLE `date_formats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `discussions`
--
ALTER TABLE `discussions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discussion_comments`
--
ALTER TABLE `discussion_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discussion_likes`
--
ALTER TABLE `discussion_likes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `duty_schedules`
--
ALTER TABLE `duty_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee_breaks`
--
ALTER TABLE `employee_breaks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_tasks`
--
ALTER TABLE `employee_tasks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `expense_claims`
--
ALTER TABLE `expense_claims`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_claim_details`
--
ALTER TABLE `expense_claim_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `front_teams`
--
ALTER TABLE `front_teams`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `goal_types`
--
ALTER TABLE `goal_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `home_pages`
--
ALTER TABLE `home_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hrm_expenses`
--
ALTER TABLE `hrm_expenses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=391;

--
-- AUTO_INCREMENT for table `hrm_languages`
--
ALTER TABLE `hrm_languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `income_expense_categories`
--
ALTER TABLE `income_expense_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `indicators`
--
ALTER TABLE `indicators`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_payment_records`
--
ALTER TABLE `invoice_payment_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ip_setups`
--
ALTER TABLE `ip_setups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jitsi_meetings`
--
ALTER TABLE `jitsi_meetings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `late_in_out_reasons`
--
ALTER TABLE `late_in_out_reasons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `leads_email_integrations`
--
ALTER TABLE `leads_email_integrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_activity_logs`
--
ALTER TABLE `lead_activity_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_attachments`
--
ALTER TABLE `lead_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_integration_emails`
--
ALTER TABLE `lead_integration_emails`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_sources`
--
ALTER TABLE `lead_sources`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `lead_statuses`
--
ALTER TABLE `lead_statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `lead_subscriptions`
--
ALTER TABLE `lead_subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_types`
--
ALTER TABLE `lead_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_settings`
--
ALTER TABLE `leave_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `location_binds`
--
ALTER TABLE `location_binds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_logs`
--
ALTER TABLE `location_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `meeting_members`
--
ALTER TABLE `meeting_members`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meeting_participants`
--
ALTER TABLE `meeting_participants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meeting_setups`
--
ALTER TABLE `meeting_setups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `meta_information`
--
ALTER TABLE `meta_information`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `notice_departments`
--
ALTER TABLE `notice_departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notice_view_logs`
--
ALTER TABLE `notice_view_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `notifications_old`
--
ALTER TABLE `notifications_old`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_types`
--
ALTER TABLE `notification_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment_histories`
--
ALTER TABLE `payment_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_history_details`
--
ALTER TABLE `payment_history_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_history_logs`
--
ALTER TABLE `payment_history_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `payment_types`
--
ALTER TABLE `payment_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `portfolios`
--
ALTER TABLE `portfolios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_purchases`
--
ALTER TABLE `product_purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_purchase_histories`
--
ALTER TABLE `product_purchase_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_units`
--
ALTER TABLE `product_units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_activities`
--
ALTER TABLE `project_activities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_files`
--
ALTER TABLE `project_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_file_comments`
--
ALTER TABLE `project_file_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_membars`
--
ALTER TABLE `project_membars`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_payments`
--
ALTER TABLE `project_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposals`
--
ALTER TABLE `proposals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposal_comments`
--
ALTER TABLE `proposal_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `role_users`
--
ALTER TABLE `role_users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `salary_generates`
--
ALTER TABLE `salary_generates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_payment_logs`
--
ALTER TABLE `salary_payment_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_setups`
--
ALTER TABLE `salary_setups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_setup_details`
--
ALTER TABLE `salary_setup_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_accounts`
--
ALTER TABLE `sale_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_adjustments`
--
ALTER TABLE `sale_adjustments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_billers`
--
ALTER TABLE `sale_billers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_brands`
--
ALTER TABLE `sale_brands`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_cash_registers`
--
ALTER TABLE `sale_cash_registers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_categories`
--
ALTER TABLE `sale_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_coupons`
--
ALTER TABLE `sale_coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_customers`
--
ALTER TABLE `sale_customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_customer_groups`
--
ALTER TABLE `sale_customer_groups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_deliveries`
--
ALTER TABLE `sale_deliveries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_discounts`
--
ALTER TABLE `sale_discounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_discount_plans`
--
ALTER TABLE `sale_discount_plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_discount_plan_customers`
--
ALTER TABLE `sale_discount_plan_customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_discount_plan_discounts`
--
ALTER TABLE `sale_discount_plan_discounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_expenses`
--
ALTER TABLE `sale_expenses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_expense_categories`
--
ALTER TABLE `sale_expense_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_gift_cards`
--
ALTER TABLE `sale_gift_cards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_gift_card_recharges`
--
ALTER TABLE `sale_gift_card_recharges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_payments`
--
ALTER TABLE `sale_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_payment_with_cheques`
--
ALTER TABLE `sale_payment_with_cheques`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_pos_settings`
--
ALTER TABLE `sale_pos_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_products`
--
ALTER TABLE `sale_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_product_adjustments`
--
ALTER TABLE `sale_product_adjustments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_product_batches`
--
ALTER TABLE `sale_product_batches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_product_purchases`
--
ALTER TABLE `sale_product_purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_product_quotations`
--
ALTER TABLE `sale_product_quotations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_product_returns`
--
ALTER TABLE `sale_product_returns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_product_sales`
--
ALTER TABLE `sale_product_sales`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_product_transfers`
--
ALTER TABLE `sale_product_transfers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_product_variants`
--
ALTER TABLE `sale_product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_product_warehouses`
--
ALTER TABLE `sale_product_warehouses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_purchases`
--
ALTER TABLE `sale_purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_purchase_product_returns`
--
ALTER TABLE `sale_purchase_product_returns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_quotations`
--
ALTER TABLE `sale_quotations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_returns`
--
ALTER TABLE `sale_returns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_return_purchases`
--
ALTER TABLE `sale_return_purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_reward_point_settings`
--
ALTER TABLE `sale_reward_point_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_stock_counts`
--
ALTER TABLE `sale_stock_counts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_suppliers`
--
ALTER TABLE `sale_suppliers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_taxes`
--
ALTER TABLE `sale_taxes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_transfers`
--
ALTER TABLE `sale_transfers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_units`
--
ALTER TABLE `sale_units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_variants`
--
ALTER TABLE `sale_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_warehouses`
--
ALTER TABLE `sale_warehouses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `search_menus`
--
ALTER TABLE `search_menus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_logs`
--
ALTER TABLE `sms_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_identities`
--
ALTER TABLE `social_identities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `stock_brands`
--
ALTER TABLE `stock_brands`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_categories`
--
ALTER TABLE `stock_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_histories`
--
ALTER TABLE `stock_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_payment_histories`
--
ALTER TABLE `stock_payment_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_products`
--
ALTER TABLE `stock_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_sales`
--
ALTER TABLE `stock_sales`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_sale_histories`
--
ALTER TABLE `stock_sale_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_items`
--
ALTER TABLE `subscription_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_activities`
--
ALTER TABLE `task_activities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_discussions`
--
ALTER TABLE `task_discussions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_discussion_comments`
--
ALTER TABLE `task_discussion_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_files`
--
ALTER TABLE `task_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_file_comments`
--
ALTER TABLE `task_file_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_members`
--
ALTER TABLE `task_members`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_notes`
--
ALTER TABLE `task_notes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_zones`
--
ALTER TABLE `time_zones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=425;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT for table `translations`
--
ALTER TABLE `translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `travel`
--
ALTER TABLE `travel`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `travel_types`
--
ALTER TABLE `travel_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_devices`
--
ALTER TABLE `user_devices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `virtual_meetings`
--
ALTER TABLE `virtual_meetings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visits`
--
ALTER TABLE `visits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `visit_images`
--
ALTER TABLE `visit_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visit_notes`
--
ALTER TABLE `visit_notes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `visit_schedules`
--
ALTER TABLE `visit_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `web_to_leads`
--
ALTER TABLE `web_to_leads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weekends`
--
ALTER TABLE `weekends`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `accounts_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `accounts_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `advance_salaries`
--
ALTER TABLE `advance_salaries`
  ADD CONSTRAINT `advance_salaries_advance_type_id_foreign` FOREIGN KEY (`advance_type_id`) REFERENCES `advance_types` (`id`),
  ADD CONSTRAINT `advance_salaries_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `advance_salaries_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `advance_salaries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `advance_salaries_pay_foreign` FOREIGN KEY (`pay`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `advance_salaries_return_status_foreign` FOREIGN KEY (`return_status`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `advance_salaries_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `advance_salaries_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `advance_salaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `advance_salary_logs`
--
ALTER TABLE `advance_salary_logs`
  ADD CONSTRAINT `advance_salary_logs_advance_salary_id_foreign` FOREIGN KEY (`advance_salary_id`) REFERENCES `advance_salaries` (`id`),
  ADD CONSTRAINT `advance_salary_logs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `advance_salary_logs_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `advance_salary_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `advance_types`
--
ALTER TABLE `advance_types`
  ADD CONSTRAINT `advance_types_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `advance_types_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `all_contents`
--
ALTER TABLE `all_contents`
  ADD CONSTRAINT `all_contents_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `all_contents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `all_contents_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `all_contents_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `all_contents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `api_setups`
--
ALTER TABLE `api_setups`
  ADD CONSTRAINT `api_setups_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `api_setups_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `appoinments`
--
ALTER TABLE `appoinments`
  ADD CONSTRAINT `appoinments_appoinment_with_foreign` FOREIGN KEY (`appoinment_with`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `appoinments_attachment_file_id_foreign` FOREIGN KEY (`attachment_file_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appoinments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `appoinments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `appoinments_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `appoinment_participants`
--
ALTER TABLE `appoinment_participants`
  ADD CONSTRAINT `appoinment_participants_appoinment_id_foreign` FOREIGN KEY (`appoinment_id`) REFERENCES `appoinments` (`id`),
  ADD CONSTRAINT `appoinment_participants_participant_id_foreign` FOREIGN KEY (`participant_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `appraisals`
--
ALTER TABLE `appraisals`
  ADD CONSTRAINT `appraisals_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appraisals_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appraisals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `appreciates`
--
ALTER TABLE `appreciates`
  ADD CONSTRAINT `appreciates_appreciate_by_foreign` FOREIGN KEY (`appreciate_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `appreciates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `app_screens`
--
ALTER TABLE `app_screens`
  ADD CONSTRAINT `app_screens_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `assign_leaves`
--
ALTER TABLE `assign_leaves`
  ADD CONSTRAINT `assign_leaves_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assign_leaves_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assign_leaves_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `assign_leaves_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `author_infos`
--
ALTER TABLE `author_infos`
  ADD CONSTRAINT `author_infos_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `author_infos_archived_by_foreign` FOREIGN KEY (`archived_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `author_infos_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `author_infos_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `author_infos_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `author_infos_published_by_foreign` FOREIGN KEY (`published_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `author_infos_referred_by_foreign` FOREIGN KEY (`referred_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `author_infos_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `author_infos_restored_by_foreign` FOREIGN KEY (`restored_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `author_infos_unpublished_by_foreign` FOREIGN KEY (`unpublished_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `author_infos_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `awards`
--
ALTER TABLE `awards`
  ADD CONSTRAINT `awards_attachment_foreign` FOREIGN KEY (`attachment`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `awards_award_type_id_foreign` FOREIGN KEY (`award_type_id`) REFERENCES `award_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `awards_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `awards_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `awards_goal_id_foreign` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `awards_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `awards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `award_types`
--
ALTER TABLE `award_types`
  ADD CONSTRAINT `award_types_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `award_types_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD CONSTRAINT `bank_accounts_author_info_id_foreign` FOREIGN KEY (`author_info_id`) REFERENCES `author_infos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bank_accounts_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bank_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_author_info_id_foreign` FOREIGN KEY (`author_info_id`) REFERENCES `author_infos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `categories_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_avatar_id_foreign` FOREIGN KEY (`avatar_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `clients_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `clients_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `commissions`
--
ALTER TABLE `commissions`
  ADD CONSTRAINT `commissions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commissions_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `companies_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `companies_trade_licence_id_foreign` FOREIGN KEY (`trade_licence_id`) REFERENCES `uploads` (`id`);

--
-- Constraints for table `company_configs`
--
ALTER TABLE `company_configs`
  ADD CONSTRAINT `company_configs_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `competences`
--
ALTER TABLE `competences`
  ADD CONSTRAINT `competences_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `competences_competence_type_id_foreign` FOREIGN KEY (`competence_type_id`) REFERENCES `competence_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `competences_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `competence_types`
--
ALTER TABLE `competence_types`
  ADD CONSTRAINT `competence_types_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `competence_types_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `conversations_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `daily_leaves`
--
ALTER TABLE `daily_leaves`
  ADD CONSTRAINT `daily_leaves_approved_by_hr_foreign` FOREIGN KEY (`approved_by_hr`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daily_leaves_approved_by_tl_foreign` FOREIGN KEY (`approved_by_tl`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daily_leaves_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daily_leaves_rejected_by_hr_foreign` FOREIGN KEY (`rejected_by_hr`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daily_leaves_rejected_by_tl_foreign` FOREIGN KEY (`rejected_by_tl`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daily_leaves_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `daily_leaves_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `departments_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `deposits`
--
ALTER TABLE `deposits`
  ADD CONSTRAINT `deposits_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `deposits_attachment_foreign` FOREIGN KEY (`attachment`) REFERENCES `uploads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deposits_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deposits_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `deposits_income_expense_category_id_foreign` FOREIGN KEY (`income_expense_category_id`) REFERENCES `income_expense_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deposits_pay_foreign` FOREIGN KEY (`pay`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `deposits_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`),
  ADD CONSTRAINT `deposits_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `deposits_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`),
  ADD CONSTRAINT `deposits_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `deposits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `designations`
--
ALTER TABLE `designations`
  ADD CONSTRAINT `designations_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `designations_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `discussions`
--
ALTER TABLE `discussions`
  ADD CONSTRAINT `discussions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discussions_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discussions_show_to_customer_foreign` FOREIGN KEY (`show_to_customer`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `discussions_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `discussions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `discussion_comments`
--
ALTER TABLE `discussion_comments`
  ADD CONSTRAINT `discussion_comments_attachment_foreign` FOREIGN KEY (`attachment`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `discussion_comments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discussion_comments_discussion_id_foreign` FOREIGN KEY (`discussion_id`) REFERENCES `discussions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discussion_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `discussion_likes`
--
ALTER TABLE `discussion_likes`
  ADD CONSTRAINT `discussion_likes_discussion_id_foreign` FOREIGN KEY (`discussion_id`) REFERENCES `discussions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discussion_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `duty_schedules`
--
ALTER TABLE `duty_schedules`
  ADD CONSTRAINT `duty_schedules_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `duty_schedules_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `duty_schedules_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `employee_breaks`
--
ALTER TABLE `employee_breaks`
  ADD CONSTRAINT `employee_breaks_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_breaks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_tasks`
--
ALTER TABLE `employee_tasks`
  ADD CONSTRAINT `employee_tasks_assigned_id_foreign` FOREIGN KEY (`assigned_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `employee_tasks_attachment_file_id_foreign` FOREIGN KEY (`attachment_file_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_tasks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `employee_tasks_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `expenses_attachment_foreign` FOREIGN KEY (`attachment`) REFERENCES `uploads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `expenses_income_expense_category_id_foreign` FOREIGN KEY (`income_expense_category_id`) REFERENCES `income_expense_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_pay_foreign` FOREIGN KEY (`pay`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `expenses_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`),
  ADD CONSTRAINT `expenses_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `expenses_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`),
  ADD CONSTRAINT `expenses_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `expense_claims`
--
ALTER TABLE `expense_claims`
  ADD CONSTRAINT `expense_claims_attachment_file_id_foreign` FOREIGN KEY (`attachment_file_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_claims_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_claims_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `expense_claims_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expense_claim_details`
--
ALTER TABLE `expense_claim_details`
  ADD CONSTRAINT `expense_claim_details_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_claim_details_expense_claim_id_foreign` FOREIGN KEY (`expense_claim_id`) REFERENCES `expense_claims` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_claim_details_hrm_expense_id_foreign` FOREIGN KEY (`hrm_expense_id`) REFERENCES `hrm_expenses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_claim_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `features`
--
ALTER TABLE `features`
  ADD CONSTRAINT `features_attachment_file_id_foreign` FOREIGN KEY (`attachment_file_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `features_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `front_teams`
--
ALTER TABLE `front_teams`
  ADD CONSTRAINT `front_teams_attachment_foreign` FOREIGN KEY (`attachment`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `front_teams_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `front_teams_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `goals_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goals_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `goals_goal_type_id_foreign` FOREIGN KEY (`goal_type_id`) REFERENCES `goal_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goals_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `goal_types`
--
ALTER TABLE `goal_types`
  ADD CONSTRAINT `goal_types_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goal_types_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `holidays`
--
ALTER TABLE `holidays`
  ADD CONSTRAINT `holidays_attachment_id_foreign` FOREIGN KEY (`attachment_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `holidays_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `holidays_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `home_pages`
--
ALTER TABLE `home_pages`
  ADD CONSTRAINT `home_pages_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `home_pages_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `home_pages_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `hrm_expenses`
--
ALTER TABLE `hrm_expenses`
  ADD CONSTRAINT `hrm_expenses_attachment_file_id_foreign` FOREIGN KEY (`attachment_file_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_expenses_claimed_approved_status_id_foreign` FOREIGN KEY (`claimed_approved_status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `hrm_expenses_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_expenses_income_expense_category_id_foreign` FOREIGN KEY (`income_expense_category_id`) REFERENCES `income_expense_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_expenses_is_claimed_status_id_foreign` FOREIGN KEY (`is_claimed_status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `hrm_expenses_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `hrm_expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_languages`
--
ALTER TABLE `hrm_languages`
  ADD CONSTRAINT `hrm_languages_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `income_expense_categories`
--
ALTER TABLE `income_expense_categories`
  ADD CONSTRAINT `income_expense_categories_attachment_file_id_foreign` FOREIGN KEY (`attachment_file_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `income_expense_categories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `income_expense_categories_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `indicators`
--
ALTER TABLE `indicators`
  ADD CONSTRAINT `indicators_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `indicators_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `indicators_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `indicators_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `indicators_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ip_setups`
--
ALTER TABLE `ip_setups`
  ADD CONSTRAINT `ip_setups_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ip_setups_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `late_in_out_reasons`
--
ALTER TABLE `late_in_out_reasons`
  ADD CONSTRAINT `late_in_out_reasons_attendance_id_foreign` FOREIGN KEY (`attendance_id`) REFERENCES `attendances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `late_in_out_reasons_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leads_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leads_lead_source_id_foreign` FOREIGN KEY (`lead_source_id`) REFERENCES `lead_sources` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leads_lead_status_id_foreign` FOREIGN KEY (`lead_status_id`) REFERENCES `lead_statuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leads_lead_type_id_foreign` FOREIGN KEY (`lead_type_id`) REFERENCES `lead_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leads_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `lead_activity_logs`
--
ALTER TABLE `lead_activity_logs`
  ADD CONSTRAINT `lead_activity_logs_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_activity_logs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_activity_logs_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_activity_logs_lead_source_id_foreign` FOREIGN KEY (`lead_source_id`) REFERENCES `lead_sources` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_activity_logs_lead_status_id_foreign` FOREIGN KEY (`lead_status_id`) REFERENCES `lead_statuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_activity_logs_lead_type_id_foreign` FOREIGN KEY (`lead_type_id`) REFERENCES `lead_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_activity_logs_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `lead_attachments`
--
ALTER TABLE `lead_attachments`
  ADD CONSTRAINT `lead_attachments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_attachments_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_attachments_show_to_clients_foreign` FOREIGN KEY (`show_to_clients`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `lead_attachments_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `lead_attachments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lead_sources`
--
ALTER TABLE `lead_sources`
  ADD CONSTRAINT `lead_sources_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_sources_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `lead_sources_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `lead_sources_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `lead_statuses`
--
ALTER TABLE `lead_statuses`
  ADD CONSTRAINT `lead_statuses_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_statuses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `lead_statuses_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `lead_statuses_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `lead_types`
--
ALTER TABLE `lead_types`
  ADD CONSTRAINT `lead_types_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_types_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `lead_types_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `lead_types_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_assign_leave_id_foreign` FOREIGN KEY (`assign_leave_id`) REFERENCES `assign_leaves` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_requests_attachment_file_id_foreign` FOREIGN KEY (`attachment_file_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `leave_requests_author_info_id_foreign` FOREIGN KEY (`author_info_id`) REFERENCES `author_infos` (`id`),
  ADD CONSTRAINT `leave_requests_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_requests_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `leave_requests_substitute_id_foreign` FOREIGN KEY (`substitute_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `leave_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_settings`
--
ALTER TABLE `leave_settings`
  ADD CONSTRAINT `leave_settings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD CONSTRAINT `leave_types_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_types_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `location_binds`
--
ALTER TABLE `location_binds`
  ADD CONSTRAINT `location_binds_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `location_binds_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `location_binds_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `location_logs`
--
ALTER TABLE `location_logs`
  ADD CONSTRAINT `location_logs_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `location_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `meetings`
--
ALTER TABLE `meetings`
  ADD CONSTRAINT `meetings_attachment_file_id_foreign` FOREIGN KEY (`attachment_file_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `meetings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `meetings_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `meetings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `meeting_members`
--
ALTER TABLE `meeting_members`
  ADD CONSTRAINT `meeting_members_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `meeting_participants`
--
ALTER TABLE `meeting_participants`
  ADD CONSTRAINT `meeting_participants_meeting_id_foreign` FOREIGN KEY (`meeting_id`) REFERENCES `meetings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `meeting_participants_participant_id_foreign` FOREIGN KEY (`participant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `meeting_setups`
--
ALTER TABLE `meeting_setups`
  ADD CONSTRAINT `meeting_setups_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `meeting_setups_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `meta_information`
--
ALTER TABLE `meta_information`
  ADD CONSTRAINT `meta_information_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `meta_information_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_show_to_customer_foreign` FOREIGN KEY (`show_to_customer`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `notes_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notices`
--
ALTER TABLE `notices`
  ADD CONSTRAINT `notices_attachment_file_id_foreign` FOREIGN KEY (`attachment_file_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notices_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `notices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notices_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `notices_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `notice_view_logs`
--
ALTER TABLE `notice_view_logs`
  ADD CONSTRAINT `notice_view_logs_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `notice_view_logs_notice_id_foreign` FOREIGN KEY (`notice_id`) REFERENCES `notices` (`id`),
  ADD CONSTRAINT `notice_view_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications_old`
--
ALTER TABLE `notifications_old`
  ADD CONSTRAINT `notifications_old_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_old_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_old_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notification_types`
--
ALTER TABLE `notification_types`
  ADD CONSTRAINT `notification_types_icon_foreign` FOREIGN KEY (`icon`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `notification_types_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `payment_histories`
--
ALTER TABLE `payment_histories`
  ADD CONSTRAINT `payment_histories_attachment_file_id_foreign` FOREIGN KEY (`attachment_file_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_histories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_histories_expense_claim_id_foreign` FOREIGN KEY (`expense_claim_id`) REFERENCES `expense_claims` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_histories_payment_status_id_foreign` FOREIGN KEY (`payment_status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `payment_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_history_details`
--
ALTER TABLE `payment_history_details`
  ADD CONSTRAINT `payment_history_details_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_history_details_paid_by_id_foreign` FOREIGN KEY (`paid_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_history_details_payment_history_id_foreign` FOREIGN KEY (`payment_history_id`) REFERENCES `payment_histories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_history_details_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_history_details_payment_status_id_foreign` FOREIGN KEY (`payment_status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `payment_history_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_history_logs`
--
ALTER TABLE `payment_history_logs`
  ADD CONSTRAINT `payment_history_logs_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_history_logs_expense_claim_id_foreign` FOREIGN KEY (`expense_claim_id`) REFERENCES `expense_claims` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_history_logs_paid_by_id_foreign` FOREIGN KEY (`paid_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_history_logs_payment_history_id_foreign` FOREIGN KEY (`payment_history_id`) REFERENCES `payment_histories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_history_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD CONSTRAINT `payment_methods_attachment_file_id_foreign` FOREIGN KEY (`attachment_file_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `payment_methods_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_methods_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `payment_types`
--
ALTER TABLE `payment_types`
  ADD CONSTRAINT `payment_types_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD CONSTRAINT `portfolios_attachment_foreign` FOREIGN KEY (`attachment`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `portfolios_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `portfolios_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_purchases`
--
ALTER TABLE `product_purchases`
  ADD CONSTRAINT `product_purchases_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_purchases_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_purchases_stock_payment_history_id_foreign` FOREIGN KEY (`stock_payment_history_id`) REFERENCES `stock_payment_histories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_purchase_histories`
--
ALTER TABLE `product_purchase_histories`
  ADD CONSTRAINT `product_purchase_histories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_purchase_histories_product_purchase_id_foreign` FOREIGN KEY (`product_purchase_id`) REFERENCES `product_purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_purchase_histories_product_unit_id_foreign` FOREIGN KEY (`product_unit_id`) REFERENCES `product_units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_units`
--
ALTER TABLE `product_units`
  ADD CONSTRAINT `product_units_attachment_file_id_foreign` FOREIGN KEY (`attachment_file_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `product_units_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_units_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_avatar_id_foreign` FOREIGN KEY (`avatar_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `projects_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `projects_goal_id_foreign` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_payment_foreign` FOREIGN KEY (`payment`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `projects_priority_foreign` FOREIGN KEY (`priority`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `projects_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `project_activities`
--
ALTER TABLE `project_activities`
  ADD CONSTRAINT `project_activities_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_activities_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_files`
--
ALTER TABLE `project_files`
  ADD CONSTRAINT `project_files_attachment_foreign` FOREIGN KEY (`attachment`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `project_files_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_files_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_files_show_to_customer_foreign` FOREIGN KEY (`show_to_customer`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `project_files_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `project_files_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_file_comments`
--
ALTER TABLE `project_file_comments`
  ADD CONSTRAINT `project_file_comments_attachment_foreign` FOREIGN KEY (`attachment`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `project_file_comments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_file_comments_project_file_id_foreign` FOREIGN KEY (`project_file_id`) REFERENCES `project_files` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_file_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_membars`
--
ALTER TABLE `project_membars`
  ADD CONSTRAINT `project_membars_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_membars_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_membars_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_membars_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `project_membars_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_payments`
--
ALTER TABLE `project_payments`
  ADD CONSTRAINT `project_payments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_payments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `project_payments_paid_by_foreign` FOREIGN KEY (`paid_by`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `project_payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`),
  ADD CONSTRAINT `project_payments_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_payments_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`),
  ADD CONSTRAINT `project_payments_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `roles_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `role_users`
--
ALTER TABLE `role_users`
  ADD CONSTRAINT `role_users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salary_generates`
--
ALTER TABLE `salary_generates`
  ADD CONSTRAINT `salary_generates_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salary_generates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `salary_generates_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `salary_generates_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `salary_generates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `salary_payment_logs`
--
ALTER TABLE `salary_payment_logs`
  ADD CONSTRAINT `salary_payment_logs_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salary_payment_logs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `salary_payment_logs_paid_by_foreign` FOREIGN KEY (`paid_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `salary_payment_logs_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`),
  ADD CONSTRAINT `salary_payment_logs_salary_generate_id_foreign` FOREIGN KEY (`salary_generate_id`) REFERENCES `salary_generates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salary_payment_logs_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`),
  ADD CONSTRAINT `salary_payment_logs_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `salary_payment_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `salary_setups`
--
ALTER TABLE `salary_setups`
  ADD CONSTRAINT `salary_setups_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salary_setups_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `salary_setups_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `salary_setups_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `salary_setups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `salary_setup_details`
--
ALTER TABLE `salary_setup_details`
  ADD CONSTRAINT `salary_setup_details_commission_id_foreign` FOREIGN KEY (`commission_id`) REFERENCES `commissions` (`id`),
  ADD CONSTRAINT `salary_setup_details_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salary_setup_details_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `salary_setup_details_salary_setup_id_foreign` FOREIGN KEY (`salary_setup_id`) REFERENCES `salary_setups` (`id`),
  ADD CONSTRAINT `salary_setup_details_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `salary_setup_details_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `salary_setup_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sales_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_adjustments`
--
ALTER TABLE `sale_adjustments`
  ADD CONSTRAINT `sale_adjustments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_adjustments_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_brands`
--
ALTER TABLE `sale_brands`
  ADD CONSTRAINT `sale_brands_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_brands_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_coupons`
--
ALTER TABLE `sale_coupons`
  ADD CONSTRAINT `sale_coupons_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_coupons_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_expenses`
--
ALTER TABLE `sale_expenses`
  ADD CONSTRAINT `sale_expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_expenses_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_expense_categories`
--
ALTER TABLE `sale_expense_categories`
  ADD CONSTRAINT `sale_expense_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_expense_categories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_gift_cards`
--
ALTER TABLE `sale_gift_cards`
  ADD CONSTRAINT `sale_gift_cards_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_gift_cards_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_gift_card_recharges`
--
ALTER TABLE `sale_gift_card_recharges`
  ADD CONSTRAINT `sale_gift_card_recharges_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_gift_card_recharges_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_products`
--
ALTER TABLE `sale_products`
  ADD CONSTRAINT `sale_products_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_products_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_product_adjustments`
--
ALTER TABLE `sale_product_adjustments`
  ADD CONSTRAINT `sale_product_adjustments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_product_adjustments_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_product_purchases`
--
ALTER TABLE `sale_product_purchases`
  ADD CONSTRAINT `sale_product_purchases_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_product_purchases_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_product_sales`
--
ALTER TABLE `sale_product_sales`
  ADD CONSTRAINT `sale_product_sales_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_product_sales_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_product_variants`
--
ALTER TABLE `sale_product_variants`
  ADD CONSTRAINT `sale_product_variants_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_product_variants_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_product_warehouses`
--
ALTER TABLE `sale_product_warehouses`
  ADD CONSTRAINT `sale_product_warehouses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_product_warehouses_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_purchases`
--
ALTER TABLE `sale_purchases`
  ADD CONSTRAINT `sale_purchases_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_purchases_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_stock_counts`
--
ALTER TABLE `sale_stock_counts`
  ADD CONSTRAINT `sale_stock_counts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_stock_counts_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_suppliers`
--
ALTER TABLE `sale_suppliers`
  ADD CONSTRAINT `sale_suppliers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_suppliers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_taxes`
--
ALTER TABLE `sale_taxes`
  ADD CONSTRAINT `sale_taxes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_taxes_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_transfers`
--
ALTER TABLE `sale_transfers`
  ADD CONSTRAINT `sale_transfers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_transfers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_units`
--
ALTER TABLE `sale_units`
  ADD CONSTRAINT `sale_units_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_units_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_variants`
--
ALTER TABLE `sale_variants`
  ADD CONSTRAINT `sale_variants_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_variants_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_warehouses`
--
ALTER TABLE `sale_warehouses`
  ADD CONSTRAINT `sale_warehouses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sale_warehouses_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_attachment_foreign` FOREIGN KEY (`attachment`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `services_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `services_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shifts`
--
ALTER TABLE `shifts`
  ADD CONSTRAINT `shifts_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `shifts_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `skills`
--
ALTER TABLE `skills`
  ADD CONSTRAINT `skills_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `social_identities`
--
ALTER TABLE `social_identities`
  ADD CONSTRAINT `social_identities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `stock_brands`
--
ALTER TABLE `stock_brands`
  ADD CONSTRAINT `stock_brands_author_info_id_foreign` FOREIGN KEY (`author_info_id`) REFERENCES `author_infos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_brands_avatar_id_foreign` FOREIGN KEY (`avatar_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `stock_brands_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_brands_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_categories`
--
ALTER TABLE `stock_categories`
  ADD CONSTRAINT `stock_categories_author_info_id_foreign` FOREIGN KEY (`author_info_id`) REFERENCES `author_infos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_categories_avatar_id_foreign` FOREIGN KEY (`avatar_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `stock_categories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_categories_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_histories`
--
ALTER TABLE `stock_histories`
  ADD CONSTRAINT `stock_histories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_histories_product_purchase_id_foreign` FOREIGN KEY (`product_purchase_id`) REFERENCES `product_purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_histories_product_unit_id_foreign` FOREIGN KEY (`product_unit_id`) REFERENCES `product_units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_histories_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `stock_histories_stock_product_id_foreign` FOREIGN KEY (`stock_product_id`) REFERENCES `stock_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_payment_histories`
--
ALTER TABLE `stock_payment_histories`
  ADD CONSTRAINT `stock_payment_histories_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_payment_histories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_payment_histories_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_products`
--
ALTER TABLE `stock_products`
  ADD CONSTRAINT `stock_products_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stock_products_avatar_id_foreign` FOREIGN KEY (`avatar_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `stock_products_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_products_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `stock_products_stock_brand_id_foreign` FOREIGN KEY (`stock_brand_id`) REFERENCES `stock_brands` (`id`),
  ADD CONSTRAINT `stock_products_stock_category_id_foreign` FOREIGN KEY (`stock_category_id`) REFERENCES `stock_categories` (`id`);

--
-- Constraints for table `stock_sales`
--
ALTER TABLE `stock_sales`
  ADD CONSTRAINT `stock_sales_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_sales_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_sales_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_sales_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_sales_payment_status_id_foreign` FOREIGN KEY (`payment_status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_sales_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_sales_stock_payment_history_id_foreign` FOREIGN KEY (`stock_payment_history_id`) REFERENCES `stock_payment_histories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_sales_stock_product_id_foreign` FOREIGN KEY (`stock_product_id`) REFERENCES `stock_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_sales_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_sale_histories`
--
ALTER TABLE `stock_sale_histories`
  ADD CONSTRAINT `stock_sale_histories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_sale_histories_stock_product_id_foreign` FOREIGN KEY (`stock_product_id`) REFERENCES `stock_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_sale_histories_stock_sale_id_foreign` FOREIGN KEY (`stock_sale_id`) REFERENCES `stock_sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD CONSTRAINT `subscription_plans_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_assigned_id_foreign` FOREIGN KEY (`assigned_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `support_tickets_attachment_file_id_foreign` FOREIGN KEY (`attachment_file_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `support_tickets_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `support_tickets_priority_id_foreign` FOREIGN KEY (`priority_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `support_tickets_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `support_tickets_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `support_tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tasks_goal_id_foreign` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_priority_foreign` FOREIGN KEY (`priority`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `task_activities`
--
ALTER TABLE `task_activities`
  ADD CONSTRAINT `task_activities_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_activities_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_discussions`
--
ALTER TABLE `task_discussions`
  ADD CONSTRAINT `task_discussions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_discussions_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `task_discussions_show_to_customer_foreign` FOREIGN KEY (`show_to_customer`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `task_discussions_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `task_discussions_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_discussions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_discussion_comments`
--
ALTER TABLE `task_discussion_comments`
  ADD CONSTRAINT `task_discussion_comments_attachment_foreign` FOREIGN KEY (`attachment`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `task_discussion_comments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_discussion_comments_task_discussion_id_foreign` FOREIGN KEY (`task_discussion_id`) REFERENCES `task_discussions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_discussion_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_files`
--
ALTER TABLE `task_files`
  ADD CONSTRAINT `task_files_attachment_foreign` FOREIGN KEY (`attachment`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `task_files_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_files_show_to_customer_foreign` FOREIGN KEY (`show_to_customer`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `task_files_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `task_files_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_files_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_file_comments`
--
ALTER TABLE `task_file_comments`
  ADD CONSTRAINT `task_file_comments_attachment_foreign` FOREIGN KEY (`attachment`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `task_file_comments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_file_comments_task_file_id_foreign` FOREIGN KEY (`task_file_id`) REFERENCES `task_files` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_file_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_members`
--
ALTER TABLE `task_members`
  ADD CONSTRAINT `task_members_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_members_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_members_is_creator_foreign` FOREIGN KEY (`is_creator`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_members_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `task_members_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_notes`
--
ALTER TABLE `task_notes`
  ADD CONSTRAINT `task_notes_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_notes_show_to_customer_foreign` FOREIGN KEY (`show_to_customer`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `task_notes_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `task_notes_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_attachment_file_id_foreign` FOREIGN KEY (`attachment_file_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teams_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teams_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `teams_team_lead_id_foreign` FOREIGN KEY (`team_lead_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `teams_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `team_members_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD CONSTRAINT `testimonials_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `testimonials_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD CONSTRAINT `ticket_replies_support_ticket_id_foreign` FOREIGN KEY (`support_ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_income_expense_category_id_foreign` FOREIGN KEY (`income_expense_category_id`) REFERENCES `income_expense_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `transactions_transaction_type_foreign` FOREIGN KEY (`transaction_type`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `transactions_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `travel`
--
ALTER TABLE `travel`
  ADD CONSTRAINT `travel_attachment_foreign` FOREIGN KEY (`attachment`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `travel_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `travel_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `travel_goal_id_foreign` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `travel_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `travel_travel_type_id_foreign` FOREIGN KEY (`travel_type_id`) REFERENCES `travel_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `travel_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `travel_types`
--
ALTER TABLE `travel_types`
  ADD CONSTRAINT `travel_types_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `travel_types_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `uploads`
--
ALTER TABLE `uploads`
  ADD CONSTRAINT `uploads_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_avatar_id_foreign` FOREIGN KEY (`avatar_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_nid_card_id_foreign` FOREIGN KEY (`nid_card_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `users_passport_file_id_foreign` FOREIGN KEY (`passport_file_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD CONSTRAINT `user_devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `virtual_meetings`
--
ALTER TABLE `virtual_meetings`
  ADD CONSTRAINT `virtual_meetings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `virtual_meetings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `virtual_meetings_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `visits`
--
ALTER TABLE `visits`
  ADD CONSTRAINT `visits_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `visits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `visit_notes`
--
ALTER TABLE `visit_notes`
  ADD CONSTRAINT `visit_notes_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `visits` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `visit_schedules`
--
ALTER TABLE `visit_schedules`
  ADD CONSTRAINT `visit_schedules_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `visits` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `weekends`
--
ALTER TABLE `weekends`
  ADD CONSTRAINT `weekends_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `weekends_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
