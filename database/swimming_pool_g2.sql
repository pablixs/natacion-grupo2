-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2026 at 04:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `swimming_pool_g2`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `ts` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `type`, `subject`, `ts`) VALUES
(2, 'swimmer_registered', 'jgismylife@gmail.com', '2026-06-01 20:22:57'),
(3, 'coach_registered', 'jgismylife3@gmail.com', '2026-06-01 20:23:12'),
(4, 'profile_completed', 'Luis Vargas', '2026-06-01 20:24:02'),
(5, 'profile_completed', 'Alan Lopez', '2026-06-01 21:08:28'),
(6, 'swimmer_registered', 'pruebas@test.com', '2026-06-01 21:56:46'),
(7, 'profile_completed', 'Pruebas Test', '2026-06-01 21:57:26'),
(8, 'swimmer_registered', 'pablicjs@gmail.com', '2026-06-05 10:44:56'),
(9, 'profile_completed', 'Pablo Gomez', '2026-06-05 10:46:37'),
(10, 'coach_registered', 'jgismylife@gmail.com', '2026-06-05 12:06:01'),
(11, 'profile_completed', 'Luis Vega', '2026-06-05 12:06:40'),
(12, 'coach_registered', 'perezjose@mailw.co', '2026-06-06 09:11:29'),
(13, 'profile_completed', 'Santiago Ruiz', '2026-06-06 09:12:31');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `swimmer_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `status` enum('Confirmed','Cancelled') DEFAULT 'Confirmed',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `coach_id` int(11) DEFAULT NULL,
  `level` enum('Principiante','Intermedio','Avanzado','') DEFAULT NULL,
  `first_day_of_week` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sabado') NOT NULL,
  `second_day_of_week` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sabado') DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `active` bigint(20) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `deactivated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `coach_id`, `level`, `first_day_of_week`, `second_day_of_week`, `start_time`, `end_time`, `capacity`, `active`, `created_at`, `deactivated_at`) VALUES
(9, 117, 'Principiante', 'Lunes', 'Miércoles', '09:00:00', '12:00:00', 10, 1, '2026-06-05 23:28:48', NULL),
(10, 117, 'Intermedio', 'Martes', 'Viernes', '15:00:00', '18:00:00', 15, 1, '2026-06-06 01:12:24', NULL),
(11, 117, 'Avanzado', 'Miércoles', 'Sabado', '20:00:00', '21:00:00', 20, 1, '2026-06-06 01:13:04', NULL),
(12, 117, 'Principiante', 'Lunes', 'Viernes', '15:00:00', '17:00:00', 5, 1, '2026-06-06 01:13:40', NULL),
(13, 117, 'Avanzado', 'Lunes', 'Viernes', '08:00:00', '10:00:00', 10, 0, '2026-06-06 02:05:49', NULL),
(14, 117, 'Intermedio', 'Lunes', 'Jueves', '08:00:00', '09:00:00', 20, 0, '2026-06-06 12:38:27', NULL),
(15, 118, 'Intermedio', 'Lunes', 'Jueves', '08:00:00', '09:00:00', 20, 0, '2026-06-06 13:10:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lessons_specialties`
--

CREATE TABLE `lessons_specialties` (
  `lesson_id` int(11) NOT NULL,
  `specialty_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons_specialties`
--

INSERT INTO `lessons_specialties` (`lesson_id`, `specialty_id`) VALUES
(9, 1),
(9, 2),
(10, 3),
(10, 4),
(11, 1),
(12, 1),
(12, 4),
(13, 1),
(13, 2),
(14, 2),
(15, 3),
(15, 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(77, 115, 'pruebas@test.com', '36abe7791112bde9a46d6bac888d0fc5', '2026-06-01 21:57:26', '2026-06-02 00:56:46'),
(78, 116, 'pablicjs@gmail.com', '21deb61d39539118bb4889fba4275224', '2026-06-05 10:46:37', '2026-06-05 13:44:56'),
(79, 117, 'jgismylife@gmail.com', '08560094183c929b392ace54a1e3002f', '2026-06-05 12:06:40', '2026-06-05 15:06:01'),
(80, 118, 'perezjose@mailw.co', '06cc493304634d88e5a391e4084848cb', '2026-06-06 09:12:31', '2026-06-06 12:11:29'),
(81, 116, 'pablicjs@gmail.com', '9534d673623390a39434650e1f14808bb423b63c8c5a5df0511fe77aaca39415', '2026-06-06 10:21:57', '2026-06-06 13:19:55');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `specialty` varchar(100) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT 'default-profile.png',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `first_name`, `last_name`, `phone`, `specialty`, `birth_date`, `profile_image`, `created_at`, `updated_at`, `deleted_at`) VALUES
(45, 115, 'Pruebas', 'Test', '1199228844', NULL, '2004-09-17', 'swimmer_ptest_4334.png', '2026-06-02 00:57:26', '2026-06-02 00:57:26', NULL),
(46, 116, 'Pablo', 'Gomez', '1199887766', NULL, '2003-08-17', 'swimmer_pgomez_3884.jpg', '2026-06-05 13:46:37', '2026-06-05 13:46:37', NULL),
(47, 117, 'Luis', 'Vega', '1100229933', 'mariposa', '2003-08-17', 'swimmer_lvega_6150.png', '2026-06-05 15:06:40', '2026-06-05 15:06:40', NULL),
(48, 118, 'Santiago', 'Ruiz', '1199228844', 'mariposa', '1994-05-18', 'default-profile.png', '2026-06-06 12:12:31', '2026-06-06 12:12:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'Administrator'),
(2, 'Coach'),
(3, 'Swimmer');

-- --------------------------------------------------------

--
-- Table structure for table `specialties`
--

CREATE TABLE `specialties` (
  `id` int(11) NOT NULL,
  `specialty` enum('Crol','Espalda','Pecho','Mariposa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `specialties`
--

INSERT INTO `specialties` (`id`, `specialty`) VALUES
(1, 'Crol'),
(2, 'Espalda'),
(3, 'Pecho'),
(4, 'Mariposa');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `profile_created` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role_id`, `profile_created`, `created_at`, `updated_at`, `deleted_at`) VALUES
(115, 'pruebas@test.com', '$2y$10$O8sDRpBvLbyzoxggO718wemg8moxHh.0kgt/uhXETLc0Z8gLQmYTy', 1, 1, '2026-06-02 00:56:46', '2026-06-02 00:57:49', NULL),
(116, 'pablicjs@gmail.com', '$2y$10$wPG09gF9ZMHPWJvcuvbTPOJDXm8c7OzKxXbkJC50ywNgoUbN8/eJS', 1, 1, '2026-06-05 13:44:56', '2026-06-06 13:21:57', NULL),
(117, 'jgismylife@gmail.com', '$2y$10$o3qhAkkfe8zSPd9YWPhYIOIK16.jQcvXkvL6K04eJOiEILYE/enwu', 2, 1, '2026-06-05 15:06:01', '2026-06-05 15:06:40', NULL),
(118, 'perezjose@mailw.co', '$2y$10$4w8HLuZ2oc7ZyrrrHIPod.O0rdCEO/7NUtqgc.JqX3Np2eCpyhJqO', 2, 1, '2026-06-06 12:11:29', '2026-06-06 12:12:31', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_type` (`type`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_booking` (`swimmer_id`,`lesson_id`),
  ADD KEY `fk_booking_lesson` (`lesson_id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lesson_coach` (`coach_id`);

--
-- Indexes for table `lessons_specialties`
--
ALTER TABLE `lessons_specialties`
  ADD PRIMARY KEY (`lesson_id`,`specialty_id`),
  ADD KEY `fk_specialtyid` (`specialty_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `token` (`token`),
  ADD KEY `fk_passwordResets_userId` (`user_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_profile_user` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `specialties`
--
ALTER TABLE `specialties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD KEY `fk_user_role` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `specialties`
--
ALTER TABLE `specialties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `fk_coachId` FOREIGN KEY (`coach_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lessons_specialties`
--
ALTER TABLE `lessons_specialties`
  ADD CONSTRAINT `fk_lessonsid` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_specialtyid` FOREIGN KEY (`specialty_id`) REFERENCES `specialties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mail` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_roleid` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
