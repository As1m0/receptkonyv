-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 02, 2024 at 08:18 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `receptkonyv`
--

-- --------------------------------------------------------

--
-- Table structure for table `felhasznalok`
--

CREATE TABLE `felhasznalok` (
  `felh_id` int(11) NOT NULL,
  `veznev` varchar(255) NOT NULL,
  `kernev` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profil_kep_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hozzavalok`
--

CREATE TABLE `hozzavalok` (
  `hozzavalo_id` int(11) NOT NULL,
  `recept_id` int(11) DEFAULT NULL,
  `nev` varchar(255) NOT NULL,
  `mennyiseg` varchar(50) NOT NULL,
  `mertekegyseg` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kepek`
--

CREATE TABLE `kepek` (
  `kepek_id` int(11) NOT NULL,
  `recept_id` int(11) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `leiras` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recept`
--

CREATE TABLE `recept` (
  `recept_id` int(11) NOT NULL,
  `recept_neve` varchar(255) NOT NULL,
  `leiras` text NOT NULL,
  `elk_ido` int(11) NOT NULL,
  `adag` int(11) NOT NULL,
  `nehezseg` tinyint(5) NOT NULL,
  `felh_id` int(11) DEFAULT NULL,
  `reviews` text DEFAULT NULL,
  `kepek` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `recept_id` int(11) DEFAULT NULL,
  `felh_id` int(11) DEFAULT NULL,
  `komment` text DEFAULT NULL,
  `stars` int(11) DEFAULT NULL CHECK (`stars` >= 1 and `stars` <= 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `felhasznalok`
--
ALTER TABLE `felhasznalok`
  ADD PRIMARY KEY (`felh_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `hozzavalok`
--
ALTER TABLE `hozzavalok`
  ADD PRIMARY KEY (`hozzavalo_id`),
  ADD KEY `recept_id` (`recept_id`);

--
-- Indexes for table `kepek`
--
ALTER TABLE `kepek`
  ADD PRIMARY KEY (`kepek_id`),
  ADD KEY `recept_id` (`recept_id`);

--
-- Indexes for table `recept`
--
ALTER TABLE `recept`
  ADD PRIMARY KEY (`recept_id`),
  ADD KEY `felh_recept_FK` (`felh_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `recept_id` (`recept_id`),
  ADD KEY `felh_id` (`felh_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `felhasznalok`
--
ALTER TABLE `felhasznalok`
  MODIFY `felh_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hozzavalok`
--
ALTER TABLE `hozzavalok`
  MODIFY `hozzavalo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kepek`
--
ALTER TABLE `kepek`
  MODIFY `kepek_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recept`
--
ALTER TABLE `recept`
  MODIFY `recept_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hozzavalok`
--
ALTER TABLE `hozzavalok`
  ADD CONSTRAINT `hozzavalok_ibfk_1` FOREIGN KEY (`recept_id`) REFERENCES `recept` (`recept_id`);

--
-- Constraints for table `kepek`
--
ALTER TABLE `kepek`
  ADD CONSTRAINT `kepek_ibfk_1` FOREIGN KEY (`recept_id`) REFERENCES `recept` (`recept_id`);

--
-- Constraints for table `recept`
--
ALTER TABLE `recept`
  ADD CONSTRAINT `felh_recept_FK` FOREIGN KEY (`felh_id`) REFERENCES `felhasznalok` (`felh_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`recept_id`) REFERENCES `recept` (`recept_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`felh_id`) REFERENCES `felhasznalok` (`felh_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
