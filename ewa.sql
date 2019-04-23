-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2019 at 04:45 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ewa`
--

-- --------------------------------------------------------

--
-- Table structure for table `angebot`
--

CREATE TABLE `angebot` (
  `PizzaNummer` int(4) NOT NULL,
  `PizzaName` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `Bilddatei` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `Preis` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `angebot`
--

INSERT INTO `angebot` (`PizzaNummer`, `PizzaName`, `Bilddatei`, `Preis`) VALUES
(4, 'Cäprese', 'images/p_caprese.png', 3.44),
(3, 'Funghi', 'images/p_funghi.png', 4.5),
(2, 'Margherita', 'images/p_margherita.png', 3.5),
(1, 'Salami', 'images/p_salami.png', 4);

-- --------------------------------------------------------

--
-- Table structure for table `bestelltepizza`
--

CREATE TABLE `bestelltepizza` (
  `PizzaID` int(4) NOT NULL,
  `fBestellungID` int(4) NOT NULL,
  `fPizzaNummer` int(4) NOT NULL,
  `Status` varchar(60) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bestelltepizza`
--

INSERT INTO `bestelltepizza` (`PizzaID`, `fBestellungID`, `fPizzaNummer`, `Status`) VALUES
(1, 1, 4, 'fertig'),
(2, 1, 1, 'bestellt'),
(3, 2, 1, 'bestellt'),
(4, 2, 1, 'fertig'),
(5, 2, 2, 'bestellt'),
(6, 2, 2, 'Im Ofen'),
(7, 3, 1, 'Im Ofen'),
(8, 3, 1, 'bestellt'),
(9, 3, 2, 'bestellt'),
(10, 3, 2, 'bestellt');

-- --------------------------------------------------------

--
-- Table structure for table `bestellung`
--

CREATE TABLE `bestellung` (
  `BestellungID` int(4) NOT NULL,
  `Adresse` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `Bestellzeitpunkt` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bestellung`
--

INSERT INTO `bestellung` (`BestellungID`, `Adresse`, `Bestellzeitpunkt`) VALUES
(1, 'ftdtr', '2019-04-23 14:01:48.259207'),
(2, 'fdshdfs', '2019-04-23 14:13:22.685522'),
(3, 'fdshdfs', '2019-04-23 14:13:34.027557');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `angebot`
--
ALTER TABLE `angebot`
  ADD PRIMARY KEY (`PizzaName`),
  ADD UNIQUE KEY `PizzaNummer` (`PizzaNummer`),
  ADD UNIQUE KEY `PizzaName` (`PizzaName`);

--
-- Indexes for table `bestelltepizza`
--
ALTER TABLE `bestelltepizza`
  ADD PRIMARY KEY (`PizzaID`),
  ADD UNIQUE KEY `PizzaID` (`PizzaID`),
  ADD KEY `fBestellungID` (`fBestellungID`),
  ADD KEY `fPizzaNummer` (`fPizzaNummer`);

--
-- Indexes for table `bestellung`
--
ALTER TABLE `bestellung`
  ADD PRIMARY KEY (`BestellungID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `angebot`
--
ALTER TABLE `angebot`
  MODIFY `PizzaNummer` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bestelltepizza`
--
ALTER TABLE `bestelltepizza`
  MODIFY `PizzaID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `bestellung`
--
ALTER TABLE `bestellung`
  MODIFY `BestellungID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bestelltepizza`
--
ALTER TABLE `bestelltepizza`
  ADD CONSTRAINT `bestelltepizza_ibfk_1` FOREIGN KEY (`fPizzaNummer`) REFERENCES `angebot` (`PizzaNummer`),
  ADD CONSTRAINT `bestelltepizza_ibfk_2` FOREIGN KEY (`fBestellungID`) REFERENCES `bestellung` (`BestellungID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
