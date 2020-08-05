-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2020 at 01:03 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `taxidb`
--

-- --------------------------------------------------------

--
-- Table structure for table `configurations`
--

CREATE TABLE `configurations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `configurations`
--

INSERT INTO `configurations` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(6, 'receive-noti-4new-users', '0', NULL, '2019-12-09 13:14:03'),
(7, 'notifications-show-limit', '10', '2019-12-10 15:33:31', '2019-12-12 13:20:27'),
(10, 'new-user-welcome-email', '1', '2019-12-10 15:38:22', '2019-12-10 15:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_11_27_141818_add_apitoken_to_users', 2),
(4, '2019_11_30_112021_create_permission_tables', 3),
(7, '2019_12_04_104044_create_settings_table', 4),
(8, '2019_12_04_153357_create_config_table', 5),
(9, '2019_12_04_164728_create_role_configuration_table', 5),
(10, '2019_12_09_161836_create_notifications_table', 6),
(11, '2019_12_10_170236_create_jobs_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(2, 'App\\User', 1),
(2, 'App\\User', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('33b4323d-4bc5-4d8d-8722-227b104f9fcb', 'App\\Notifications\\NewUserCreatedDB', 'App\\User', 1, '{\"data\":\"Besha has been created a new user: Amie Ebert Jr.\",\"user\":{\"id\":17,\"name\":\"Besha\",\"email\":\"besha@gmail.com\",\"email_verified_at\":null,\"api_token\":null,\"created_at\":\"2019-12-13 09:57:58\",\"updated_at\":\"2019-12-13 09:57:58\",\"avatar\":\"http:\\/\\/localhost\\/marasielo\\/public\\/storage\\/users\\/0.jpg\",\"getroles\":[],\"settings\":[],\"roles\":[]}}', '2019-12-13 07:04:47', '2019-12-13 07:04:10', '2019-12-13 07:04:47'),
('a4a357df-097c-4e68-a9e4-6975e377dfff', 'App\\Notifications\\NewUserCreatedDB', 'App\\User', 2, '{\"data\":\"Besha has been created a new user: Amie Ebert Jr.\",\"user\":{\"id\":17,\"name\":\"Besha\",\"email\":\"besha@gmail.com\",\"email_verified_at\":null,\"api_token\":null,\"created_at\":\"2019-12-13 09:57:58\",\"updated_at\":\"2019-12-13 09:57:58\",\"avatar\":\"http:\\/\\/localhost\\/marasielo\\/public\\/storage\\/users\\/0.jpg\",\"getroles\":[],\"settings\":[],\"roles\":[]}}', NULL, '2019-12-13 07:04:10', '2019-12-13 07:04:10'),
('cfe7307c-8e39-4a81-b90b-9baa97293b56', 'App\\Notifications\\NewUserCreatedDB', 'App\\User', 2, '{\"data\":\"Murad Alwan has been created a new user: Besha\",\"user\":{\"id\":1,\"name\":\"Murad Alwan\",\"email\":\"free1soft@gmail.com\",\"email_verified_at\":null,\"api_token\":\"5lwj0sXMR757f5K28bGSiDcBYbY5RZMpuUK62AtkN5TBmcnu1fof6e6JUVaW\",\"created_at\":\"2019-11-26 11:57:41\",\"updated_at\":\"2019-12-16 07:43:57\",\"avatar\":\"http:\\/\\/localhost\\/marasielo\\/public\\/storage\\/users\\/1.jpg\",\"getroles\":[{\"id\":2,\"name\":\"developer\",\"guard_name\":\"web\",\"created_at\":\"2019-11-30 16:53:41\",\"updated_at\":\"2019-11-30 16:53:41\",\"getpermissions\":[{\"id\":4,\"name\":\"access-roles\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 13:28:55\",\"updated_at\":\"2019-12-09 13:28:55\",\"pivot\":{\"role_id\":2,\"permission_id\":4}},{\"id\":5,\"name\":\"access-users\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 13:29:06\",\"updated_at\":\"2019-12-09 13:29:06\",\"pivot\":{\"role_id\":2,\"permission_id\":5}},{\"id\":6,\"name\":\"access-permissions\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 13:29:25\",\"updated_at\":\"2019-12-09 13:29:25\",\"pivot\":{\"role_id\":2,\"permission_id\":6}},{\"id\":7,\"name\":\"access-configs\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 13:29:43\",\"updated_at\":\"2019-12-09 13:29:43\",\"pivot\":{\"role_id\":2,\"permission_id\":7}},{\"id\":8,\"name\":\"give-permissions\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 15:03:45\",\"updated_at\":\"2019-12-09 15:03:45\",\"pivot\":{\"role_id\":2,\"permission_id\":8}},{\"id\":9,\"name\":\"access-desktop\",\"guard_name\":\"web\",\"created_at\":\"2019-12-10 07:08:43\",\"updated_at\":\"2019-12-10 07:08:43\",\"pivot\":{\"role_id\":2,\"permission_id\":9}},{\"id\":10,\"name\":\"access-drivers\",\"guard_name\":\"web\",\"created_at\":\"2019-12-10 07:08:52\",\"updated_at\":\"2019-12-10 07:08:52\",\"pivot\":{\"role_id\":2,\"permission_id\":10}}],\"pivot\":{\"model_id\":1,\"role_id\":2,\"model_type\":\"App\\\\User\"},\"permissions\":[{\"id\":4,\"name\":\"access-roles\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 13:28:55\",\"updated_at\":\"2019-12-09 13:28:55\",\"pivot\":{\"role_id\":2,\"permission_id\":4}},{\"id\":5,\"name\":\"access-users\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 13:29:06\",\"updated_at\":\"2019-12-09 13:29:06\",\"pivot\":{\"role_id\":2,\"permission_id\":5}},{\"id\":6,\"name\":\"access-permissions\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 13:29:25\",\"updated_at\":\"2019-12-09 13:29:25\",\"pivot\":{\"role_id\":2,\"permission_id\":6}},{\"id\":7,\"name\":\"access-configs\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 13:29:43\",\"updated_at\":\"2019-12-09 13:29:43\",\"pivot\":{\"role_id\":2,\"permission_id\":7}},{\"id\":8,\"name\":\"give-permissions\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 15:03:45\",\"updated_at\":\"2019-12-09 15:03:45\",\"pivot\":{\"role_id\":2,\"permission_id\":8}},{\"id\":9,\"name\":\"access-desktop\",\"guard_name\":\"web\",\"created_at\":\"2019-12-10 07:08:43\",\"updated_at\":\"2019-12-10 07:08:43\",\"pivot\":{\"role_id\":2,\"permission_id\":9}},{\"id\":10,\"name\":\"access-drivers\",\"guard_name\":\"web\",\"created_at\":\"2019-12-10 07:08:52\",\"updated_at\":\"2019-12-10 07:08:52\",\"pivot\":{\"role_id\":2,\"permission_id\":10}}]}],\"roles\":[{\"id\":2,\"name\":\"developer\",\"guard_name\":\"web\",\"created_at\":\"2019-11-30 16:53:41\",\"updated_at\":\"2019-11-30 16:53:41\",\"getpermissions\":[{\"id\":4,\"name\":\"access-roles\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 13:28:55\",\"updated_at\":\"2019-12-09 13:28:55\",\"pivot\":{\"role_id\":2,\"permission_id\":4}},{\"id\":5,\"name\":\"access-users\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 13:29:06\",\"updated_at\":\"2019-12-09 13:29:06\",\"pivot\":{\"role_id\":2,\"permission_id\":5}},{\"id\":6,\"name\":\"access-permissions\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 13:29:25\",\"updated_at\":\"2019-12-09 13:29:25\",\"pivot\":{\"role_id\":2,\"permission_id\":6}},{\"id\":7,\"name\":\"access-configs\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 13:29:43\",\"updated_at\":\"2019-12-09 13:29:43\",\"pivot\":{\"role_id\":2,\"permission_id\":7}},{\"id\":8,\"name\":\"give-permissions\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 15:03:45\",\"updated_at\":\"2019-12-09 15:03:45\",\"pivot\":{\"role_id\":2,\"permission_id\":8}},{\"id\":9,\"name\":\"access-desktop\",\"guard_name\":\"web\",\"created_at\":\"2019-12-10 07:08:43\",\"updated_at\":\"2019-12-10 07:08:43\",\"pivot\":{\"role_id\":2,\"permission_id\":9}},{\"id\":10,\"name\":\"access-drivers\",\"guard_name\":\"web\",\"created_at\":\"2019-12-10 07:08:52\",\"updated_at\":\"2019-12-10 07:08:52\",\"pivot\":{\"role_id\":2,\"permission_id\":10}}],\"pivot\":{\"model_id\":1,\"role_id\":2,\"model_type\":\"App\\\\User\"},\"permissions\":[{\"id\":4,\"name\":\"access-roles\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 13:28:55\",\"updated_at\":\"2019-12-09 13:28:55\",\"pivot\":{\"role_id\":2,\"permission_id\":4}},{\"id\":5,\"name\":\"access-users\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 13:29:06\",\"updated_at\":\"2019-12-09 13:29:06\",\"pivot\":{\"role_id\":2,\"permission_id\":5}},{\"id\":6,\"name\":\"access-permissions\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 13:29:25\",\"updated_at\":\"2019-12-09 13:29:25\",\"pivot\":{\"role_id\":2,\"permission_id\":6}},{\"id\":7,\"name\":\"access-configs\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 13:29:43\",\"updated_at\":\"2019-12-09 13:29:43\",\"pivot\":{\"role_id\":2,\"permission_id\":7}},{\"id\":8,\"name\":\"give-permissions\",\"guard_name\":\"web\",\"created_at\":\"2019-12-09 15:03:45\",\"updated_at\":\"2019-12-09 15:03:45\",\"pivot\":{\"role_id\":2,\"permission_id\":8}},{\"id\":9,\"name\":\"access-desktop\",\"guard_name\":\"web\",\"created_at\":\"2019-12-10 07:08:43\",\"updated_at\":\"2019-12-10 07:08:43\",\"pivot\":{\"role_id\":2,\"permission_id\":9}},{\"id\":10,\"name\":\"access-drivers\",\"guard_name\":\"web\",\"created_at\":\"2019-12-10 07:08:52\",\"updated_at\":\"2019-12-10 07:08:52\",\"pivot\":{\"role_id\":2,\"permission_id\":10}}]}]}}', NULL, '2019-12-16 04:46:16', '2019-12-16 04:46:16');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(4, 'access-roles', 'web', '2019-12-09 10:28:55', '2019-12-09 10:28:55'),
(5, 'access-users', 'web', '2019-12-09 10:29:06', '2019-12-09 10:29:06'),
(6, 'access-permissions', 'web', '2019-12-09 10:29:25', '2019-12-09 10:29:25'),
(7, 'access-configs', 'web', '2019-12-09 10:29:43', '2019-12-09 10:29:43'),
(8, 'give-permissions', 'web', '2019-12-09 12:03:45', '2019-12-09 12:03:45');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(2, 'developer', 'web', '2019-11-30 13:53:41', '2019-11-30 13:53:41');

-- --------------------------------------------------------

--
-- Table structure for table `role_configuration`
--

CREATE TABLE `role_configuration` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `configuration_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(8, 2);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_token` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `api_token`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Murad Alwan', 'free1soft@gmail.com', NULL, '$2y$10$7XCBy.NbQfLDXAWnnkiUze7MunO79FCjmVVK5fx0LCZggALBY/IoC', '86yDNbez4yiXm2CPd9CYf3rk1KIH1ShDq7bTwJQJRdzlFpDWOIUBwDkWSfGO', 'Mhp1oOm0VbXPKCAYvlMBtiowTtFFI5cJmzZJxBRNK3SVUmMlpVNFs7XZojEG', '2019-11-26 08:57:41', '2020-08-05 07:49:07'),
(2, 'Hussam Totile', 'hussam@gmail.com', NULL, '$2y$10$BOSA8NOUXDdVg/ST0v/LDeMnP.PaRBYiHE/Zedus4Qwz.M1Tr7PsK', NULL, NULL, '2019-12-01 05:22:11', '2019-12-01 05:22:11'),
(3, 'israr totil', 'isrartoteel@yahoo.com', NULL, '$2y$10$ydw1JpTEB5D5iXLjfZsgy.nM8wq8Sb4UJ.3xHIACHwlS0jqhBZkD6', 'Pf2fXYCdJEHabzEbzPbyLWES5C9H6FkAIAhGDzgaCX1S3TI17widDAc3WxRp', NULL, '2019-12-02 05:43:53', '2020-03-01 05:47:24'),
(4, 'Ashtyn Herman', 'mortimer.luettgen@example.com', '2019-12-06 12:06:51', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'uFSfHEz3ia', '2019-12-06 12:06:51', '2019-12-06 12:06:51'),
(5, 'Amie Ebert Jr.', 'jett13@example.org', '2019-12-06 12:06:51', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'HOmCfrFkeD', '2019-12-06 12:06:51', '2019-12-06 12:06:51'),
(6, 'Prof. Jett Borer V', 'kameron.luettgen@example.com', '2019-12-06 12:06:51', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'w9xlijyPo5', '2019-12-06 12:06:51', '2019-12-06 12:06:51'),
(17, 'Besha', 'besha@gmail.com', NULL, '$2y$10$cK.TJnTfwKcGYl9irYhIR.noG9lh6JqHN/MSMHMlfRnVqeBtl8ZTa', NULL, NULL, '2019-12-13 06:57:58', '2019-12-13 06:57:58'),
(18, 'Samer', 'samer@hotmail.com', NULL, '$2y$10$/Ghch8SUezwUzbCFk8fqDOXgtgD1KPUC51LUqkFgutqBLHwtwrIAq', NULL, NULL, '2019-12-16 04:46:14', '2019-12-16 04:46:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `configurations`
--
ALTER TABLE `configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_configuration`
--
ALTER TABLE `role_configuration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_api_token_unique` (`api_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `configurations`
--
ALTER TABLE `configurations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `role_configuration`
--
ALTER TABLE `role_configuration`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
