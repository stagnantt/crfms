-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2024 at 05:20 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lgutestdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admincredentials`
--

CREATE TABLE `admincredentials` (
  `username` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `barangay` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admincredentials`
--

INSERT INTO `admincredentials` (`username`, `firstname`, `lastname`, `barangay`, `password`, `admin_code`) VALUES
('water', 'stagnant', 'water', '123', '$2y$10$SVD.BieqENooGiEaGy33OOjGvfdgLtsfxbH5j5v1an0o.QrrBEdHG', '');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcementID` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `Topic` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Images` varchar(500) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcementID`, `username`, `Topic`, `Description`, `Images`, `created_at`) VALUES
(4, 'water', 'sonic', 'test long descriptiontest long descriptiontest long descriptiontest long descriptiontest long descriptiontest long descriptiontest long descriptiontest long descriptiontest long descriptiontest long descriptiontest long descriptiontest long descriptiontes', '427971999_225426603990729_8886241880373014013_n.jpg', '2024-10-13 18:39:08'),
(5, 'water', 'lennon', 'x123', '464109284_560033689757211_8569353342527183854_n.jpg', '2024-10-26 22:50:27'),
(7, 'water', 'New Folder', 'Admin Folder upload check', 'nice.jpeg', '2024-10-29 22:22:31');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedbackid` varchar(255) NOT NULL,
  `email` varchar(200) NOT NULL,
  `topic` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `location` varchar(250) NOT NULL,
  `images` varchar(255) NOT NULL,
  `submitted_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedbackid`, `email`, `topic`, `description`, `location`, `images`, `submitted_date`) VALUES
('B482F7E', 'sid@gmail.com', 'Feedback', 'First feedback frfr', 'Franciscan Missionary Sisters of the Sacred Heart, #27, N. Reyes Street, Xavierville III, Loyola Heights, 3rd District, Quezon City, Eastern Manila District, Metro Manila, 1108, Philippines', 'lmfao.gif', '2024-10-13 18:22:16'),
('F4C5F1D', 'sid@gmail.com', 'Feedback', 'another feedback', 'H. R. Ocampo Street, Lambak 6-B, Krus na Ligas, Diliman, 4th District, Quezon City, Eastern Manila District, Metro Manila, 1101, Philippines', 'drive.jpg', '2024-10-13 18:22:56'),
('9B5E320', 'sid@gmail.com', 'Feedback', '1x2x123', '51-D, V. Manansala Street, Lambak 6-B, UP Campus, Diliman, 4th District, Quezon City, Eastern Manila District, Metro Manila, 1101, Philippines', 'fightclub.jpg', '2024-10-28 18:35:33'),
('9BC8905', 'realaccfrfr@gmail.com', 'Feedback', 'x3', 'Bagong Pag-asa, Diliman, 1st District, Quezon City, Eastern Manila District, Metro Manila, 1100, Philippines', 'sad.gif', '2024-10-29 23:50:40');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `email` varchar(200) NOT NULL,
  `topic` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `location` varchar(250) NOT NULL,
  `images` varchar(255) NOT NULL,
  `reference_id` varchar(255) NOT NULL,
  `submitted_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'Submitted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`email`, `topic`, `description`, `location`, `images`, `reference_id`, `submitted_date`, `last_updated`, `status`) VALUES
('sid@gmail.com', 'General Inquiry', 'Table cleared, Foldered up files', 'Mother of Divine Providence School, General Ordoñez Street, Marikina Heights, District II, Marikina, Eastern Manila District, Metro Manila, 1810, Philippines', 'ayo.gif', 'REF-99BB8F1', '2024-10-29 21:13:55', '2024-10-29 22:32:39', 'Submitted'),
('realaccfrfr@gmail.com', 'General Inquiry', 'submitted frfr', 'Calumpit Street, Veterans Village, Project 7, 1st District, Quezon City, Eastern Manila District, Metro Manila, 1105, Philippines', 'woahthere.gif', 'REF-BDF29DE', '2024-10-29 22:33:21', '2024-10-29 22:33:21', 'Submitted'),
('realaccfrfr@gmail.com', 'General Inquiry', 'x2', 'Guatemala Street, Pansol, 3rd District, Quezon City, Eastern Manila District, Metro Manila, 1806, Philippines', 'GEzEpreaIAACFxc.jpg', 'REF-25819C8', '2024-10-29 23:50:16', '2024-10-29 23:50:16', 'Submitted');

-- --------------------------------------------------------

--
-- Table structure for table `usercredentials`
--

CREATE TABLE `usercredentials` (
  `username` varchar(25) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usercredentials`
--

INSERT INTO `usercredentials` (`username`, `firstname`, `lastname`, `email`, `password`, `reset_token`, `token_expiry`) VALUES
('stagnant', 'Lee', 'Eojin', 'realaccfrfr@gmail.com', '$2y$10$Bl1zWSdm8tqVKLz.rbwbdu/spjx3y3.nPQQZP6XYg1Kw5XwZRLGXO', NULL, NULL),
('zxsid', 'lee', 'eojin', 's1dcxzzxc@gmail.com', '$2y$10$E5WbDPTl7nFIciKBTR3jZulzOHp7D0boOF.nJZCgShm1m.JaCpPpS', NULL, NULL),
('x123x1', '23x123', 'x123x', '123x12@gmail.com', '$2y$10$FQtiuBcagZ7U5vxyFcIjEuk8.jvXBUnLHW.NB5EYeEvu/Ta0O2DWO', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcementID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcementID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
