-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2023 at 10:31 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `amvrss`
--

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `vech_id` varchar(50) NOT NULL,
  `vech_name` varchar(100) NOT NULL,
  `vech_col` varchar(100) NOT NULL,
  `vech_cat` varchar(100) NOT NULL,
  `mission` varchar(255) NOT NULL,
  `time` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(100) NOT NULL,
  `return_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Full_name` varchar(50) NOT NULL,
  `rank` varchar(100) NOT NULL,
  `snumber` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `Full_name`, `rank`, `snumber`, `email`, `username`, `password`, `user_type`) VALUES
(2, 'Admin', '', '', 'amvrs2023@gmail.com', 'admin', '$2y$10$axs7GOw/djKkquWHfKaBTOQiRIUCKFnyuKa8iM4BI3tj2A61kR.Ru', 'admin'),
(12, 'Adamu', '', '', 'adamuamvrs@gmail.com', 'adamu', '$2y$10$lj.AHC/Gzo5dV0tq2PuXMuwF11lrSn3itOOIvOjHDdNus1GB/A0x.', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `vechicle`
--

CREATE TABLE `vechicle` (
  `id` int(11) NOT NULL,
  `vech_id` varchar(100) NOT NULL,
  `vech_name` varchar(100) NOT NULL,
  `vech_color` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `vech_img` varchar(255) NOT NULL,
  `vech_desc` longtext NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vechicle`
--

INSERT INTO `vechicle` (`id`, `vech_id`, `vech_name`, `vech_color`, `category`, `vech_img`, `vech_desc`, `status`) VALUES
(1, 'SF001', 'Toyota', 'black', 'Truck', 'SF001.jpg', 'Bulletproof with ability to auto drive', 'free'),
(2, 'SF002', 'Toyota', 'Green', 'Salon', 'SF002.jpeg', 'High speed with effective efficiency', 'free');

-- --------------------------------------------------------

--
-- Table structure for table `vech_allocated`
--

CREATE TABLE `vech_allocated` (
  `id` int(11) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `due_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vechicle`
--
ALTER TABLE `vechicle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vech_allocated`
--
ALTER TABLE `vech_allocated`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `vechicle`
--
ALTER TABLE `vechicle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vech_allocated`
--
ALTER TABLE `vech_allocated`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
