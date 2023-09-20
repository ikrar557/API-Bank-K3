-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 20, 2023 at 09:22 AM
-- Server version: 5.7.40-log
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tsa_bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `TblAkun`
--

CREATE TABLE `TblAkun` (
  `idAkun` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `norek` int(11) NOT NULL,
  `saldo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `TblAkun`
--

INSERT INTO `TblAkun` (`idAkun`, `nama`, `norek`, `saldo`) VALUES
(2, 'Ikrar Bagaskara', 15151, 2400000),
(5, 'Rahayu Ningrum', 85151, 7000),
(8, 'Ichsan', 481238, 100000),
(3, 'ilham', 1511121, 0),
(1, 'sasa', 25572190, 5000),
(898, 'Ikrar Kara', 123456789, 15000000),
(900, 'Sades Mousepad', 987456123, 3275000);

-- --------------------------------------------------------

--
-- Table structure for table `tblTransaksi`
--

CREATE TABLE `tblTransaksi` (
  `idTransaksi` int(11) NOT NULL,
  `tanggalTransaksi` timestamp NOT NULL,
  `nominal` int(255) NOT NULL,
  `norek` int(11) NOT NULL,
  `jenistrans` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tblTransaksi`
--

INSERT INTO `tblTransaksi` (`idTransaksi`, `tanggalTransaksi`, `nominal`, `norek`, `jenistrans`) VALUES
(1, '2023-09-13 01:35:00', 18000, 1511121, 'Debit'),
(88, '2023-09-18 02:57:29', 50000, 25572190, 'Debit'),
(99, '2023-09-18 02:57:29', 3000000, 15151, 'Kredit'),
(521, '2023-09-18 02:59:17', 5000000, 1511121, 'Kredit');

-- --------------------------------------------------------

--
-- Table structure for table `TblTransfer`
--

CREATE TABLE `TblTransfer` (
  `idTransfer` int(11) NOT NULL,
  `rektujuan` int(11) NOT NULL,
  `rekasal` int(11) NOT NULL,
  `nominal` int(11) NOT NULL,
  `tgltf` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `TblTransfer`
--

INSERT INTO `TblTransfer` (`idTransfer`, `rektujuan`, `rekasal`, `nominal`, `tgltf`, `status`) VALUES
(1308, 15151, 123456789, 20000, '2023-09-18 16:00:00', 'success'),
(1504, 15151, 123456789, 9000, '2023-09-18 16:00:00', 'success'),
(2466, 15151, 123456789, 9000, '2023-09-18 16:00:00', 'success'),
(3519, 15151, 123456789, 20000, '2023-09-18 16:00:00', 'success'),
(4698, 15151, 123456789, 9000, '2023-09-18 16:00:00', 'success'),
(4950, 15151, 123456789, 20000, '2023-09-18 16:00:00', 'success'),
(5346, 15151, 123456789, 30000, '2023-09-18 16:00:00', 'success'),
(7331, 15151, 123456789, 90000, '2023-09-18 16:00:00', 'success'),
(7608, 15151, 123456789, 9000, '2023-09-18 16:00:00', 'success'),
(7826, 15151, 123456789, 9000, '2023-09-18 16:00:00', 'success'),
(8769, 15151, 123456789, 3000, '2023-09-18 16:00:00', 'success'),
(9948, 15151, 123456789, 10000, '2023-09-18 16:00:00', 'success'),
(9957, 15151, 123456789, 100000, '2023-09-18 16:00:00', 'success'),
(150303, 15151, 123456789, 20000, '2023-09-19 08:05:15', 'berhasil'),
(156746, 15151, 123456789, 15151, '2023-09-19 08:32:45', 'success'),
(174364, 85151, 15151, 5000, '2023-09-19 13:48:24', 'success'),
(177852, 15151, 123456789, 15151, '2023-09-19 08:29:50', 'success'),
(202593, 15151, 123456789, 20000, '2023-09-19 08:14:25', 'berhasil'),
(233155, 15151, 123456789, 15151, '2023-09-19 08:27:27', 'success'),
(267999, 481238, 15151, 0, '2023-09-19 13:52:39', 'success'),
(272417, 15151, 123456789, 3000, '2023-09-19 08:43:33', 'success'),
(327029, 15151, 123456789, 20000, '2023-09-19 08:18:43', 'berhasil'),
(362236, 15151, 123456789, 20000, '2023-09-19 08:04:53', 'berhasil'),
(450676, 15151, 123456789, 20000, '2023-09-19 08:05:20', 'berhasil'),
(565463, 15151, 123456789, 20000, '2023-09-19 08:04:45', 'berhasil'),
(585026, 15151, 123456789, 3000, '2023-09-19 08:42:31', 'success'),
(587085, 85151, 481238, 2000, '2023-09-19 13:30:48', 'success'),
(587189, 15151, 123456789, 20000, '2023-09-19 08:15:01', 'berhasil'),
(605857, 15151, 123456789, 15151, '2023-09-19 08:30:52', 'success'),
(632974, 481238, 15151, 50000, '2023-09-20 01:06:42', 'success'),
(666429, 15151, 123456789, 20000, '2023-09-19 08:14:58', '0'),
(801221, 15151, 123456789, 20000, '2023-09-19 08:04:32', 'berhasil'),
(812690, 15151, 123456789, 20000, '2023-09-19 08:14:36', '0'),
(839216, 15151, 123456789, 3000, '2023-09-19 08:43:23', 'success'),
(844625, 481238, 15151, 50000, '2023-09-20 01:13:35', 'success');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `TblAkun`
--
ALTER TABLE `TblAkun`
  ADD PRIMARY KEY (`norek`);

--
-- Indexes for table `tblTransaksi`
--
ALTER TABLE `tblTransaksi`
  ADD PRIMARY KEY (`idTransaksi`),
  ADD KEY `norek` (`norek`);

--
-- Indexes for table `TblTransfer`
--
ALTER TABLE `TblTransfer`
  ADD PRIMARY KEY (`idTransfer`),
  ADD KEY `rekasal` (`rekasal`),
  ADD KEY `rektujuan` (`rektujuan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `TblTransfer`
--
ALTER TABLE `TblTransfer`
  MODIFY `idTransfer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=844626;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblTransaksi`
--
ALTER TABLE `tblTransaksi`
  ADD CONSTRAINT `norek` FOREIGN KEY (`norek`) REFERENCES `TblAkun` (`norek`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `TblTransfer`
--
ALTER TABLE `TblTransfer`
  ADD CONSTRAINT `rekasal` FOREIGN KEY (`rekasal`) REFERENCES `TblAkun` (`norek`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `rektujuan` FOREIGN KEY (`rektujuan`) REFERENCES `TblAkun` (`norek`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
