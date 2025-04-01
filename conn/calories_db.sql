-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2024 at 04:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `calories_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_calorie`
--

CREATE TABLE `tbl_calorie` (
  `tbl_calorie_id` int(11) NOT NULL,
  `calorie_amount` int(11) NOT NULL,
  `calorie_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_calorie`
--

INSERT INTO `tbl_calorie` (`tbl_calorie_id`, `calorie_amount`, `calorie_date`) VALUES
(1, 1234, '2024-06-24'),
(2, 12341, '2024-06-25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_calorie`
--
ALTER TABLE `tbl_calorie`
  ADD PRIMARY KEY (`tbl_calorie_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_calorie`
--
ALTER TABLE `tbl_calorie`
  MODIFY `tbl_calorie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
