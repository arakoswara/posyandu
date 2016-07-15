-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 15, 2016 at 03:37 
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_posyandu`
--

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'visitor');

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`id`, `user_id`, `role_id`) VALUES
(1, 1, 1),
(2, 2, 2);

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `activation_code`, `active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@gmail.com', '$2y$10$foQYIpRVEi.PB5Oo6ZN1N.umumghKr4w7yep4ZmVL3wHPCdxwxh6y', '', 1, 'wuEoqoRcPi96VhNXz1cocd4tmVEhc8BnKur3MnAUSkh1pf3sX7aEG28bU36m', '2016-05-02 05:50:21', '2016-07-14 18:34:31'),
(2, 'Petugas', 'petugas@gmail.com', '$2y$10$K6FjyGaeLIJCXG/JAJiZkOwcIKxggnQA.0jzXVrOH3Th4tlbtghzS', '', 1, 'stL6sfNxaPCR8bKRpRsjtIjqmYYi02mVuznZyeRaiyfVPchGbM7cCJwjf7kY', '2016-06-18 09:46:50', '2016-07-12 21:02:07');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
