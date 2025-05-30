-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2025 at 06:37 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `registration2`
--

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `batch` varchar(10) DEFAULT NULL,
  `regno` varchar(25) DEFAULT NULL,
  `loginid` varchar(25) DEFAULT NULL,
  `sname` varchar(30) DEFAULT NULL,
  `fname` varchar(30) DEFAULT NULL,
  `course` varchar(10) DEFAULT NULL,
  `exam_date` date DEFAULT NULL,
  `exam_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`batch`, `regno`, `loginid`, `sname`, `fname`, `course`, `exam_date`, `exam_time`) VALUES
('25001', 'YS-HBA/78-7500123/2025', '7507723@hba.com', 'PARIMAL BISWAS', 'PARITOSH BISWAS', 'CITA', '2025-05-21', '18:15:00'),
('24001', 'YS-HBA/78-7500555/2025', '7507723@hba.com', 'dd BISWAS', 'ff BISWAS', 'CDTA', '2025-05-22', '16:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(11) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `session` varchar(3) DEFAULT NULL,
  `course` varchar(10) DEFAULT NULL,
  `batch` varchar(10) DEFAULT NULL,
  `regno` varchar(25) DEFAULT NULL,
  `class_days` varchar(20) DEFAULT NULL,
  `class_times` varchar(20) DEFAULT NULL,
  `sname` varchar(30) DEFAULT NULL,
  `fname` varchar(30) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `cast` varchar(10) DEFAULT NULL,
  `phone1` varchar(10) DEFAULT NULL,
  `phone2` varchar(10) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `last_exam` varchar(10) DEFAULT NULL,
  `mp_mark` int(11) DEFAULT NULL,
  `hs_mark` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`id`, `timestamp`, `session`, `course`, `batch`, `regno`, `class_days`, `class_times`, `sname`, `fname`, `gender`, `cast`, `phone1`, `phone2`, `dob`, `last_exam`, `mp_mark`, `hs_mark`) VALUES
(1, '2025-04-28 15:24:14', '77', 'CITA', '25001', 'YS-HBA/77-7700123/2025', 'TUE-FRI', '4PM-6PM', 'SUBINAY ROY', 'PARIMAL ROY', 'MALE', 'GEN', '9998989898', '5585588585', '2001-05-11', 'MP', 52, NULL),
(10, '2025-05-22 13:50:25', '77', 'CITA', '25001', 'YS-HBA/69-6900123/2024', '', '', 'PALLAB SAHA', 'SANJIB SAHA', '', '', '1111111111', '2222222222', '2001-05-10', '', 20, 20),
(11, '2025-05-22 14:14:04', '77', 'CITA', '25001', 'YS-HBA/75-7588888/2025', 'MON-THR', '4PM-6PM', 'PALLAB SAHA', 'SANJIB SAHA', 'MALE', 'SC', '2222222222', '3333333333', '2004-05-05', '', 33, 33),
(12, '2025-05-22 14:18:59', '77', 'ADFAS', '43001', 'YS-HBA/89-8900520/2027', 'MON-THR', '4PM-6PM', 'PALLAB SAHA', 'SANJIB SAHA', 'MALE', 'GENERAL', '8000000000', '9000000000', '2007-05-04', 'HS', 56, 55),
(13, '2025-05-22 14:37:37', '77', 'CITA', '25001', 'YS-HBA/78-8856666/2025', 'MON-THR', '12PM-2PM', 'PALLAB SAHA', 'SANJIB SAHA', 'MALE', 'GENERAL', '1234456534', '2342343243', '2005-05-02', 'MP', 55, NULL),
(14, '2025-05-22 14:42:37', '77', 'CITA', '25001', 'YS-HBA/78-7804500/2025', 'MON-THR', '4PM-6PM', 'PALLAB SAHA', 'SANJIB SAHA', 'MALE', 'GENERAL', '9999999999', '8888888888', '0005-02-01', 'MP', 55, NULL),
(15, '2025-05-22 14:48:09', '77', 'DITA', '35001', 'YS-HBA/777777777777777', 'MON-THR', '2PM-4PM', 'EWREWR EWREWR', 'EWREWR EWR', 'MALE', 'GENERAL', '7800000000', '8900000000', '2001-01-01', 'MP', 55, NULL),
(16, '2025-05-22 14:55:59', '77', 'DITA', '35001', 'YS-HBA/78-7800123/2025', 'MON-THR', '2PM-4PM', 'PALLAB SAHA', 'SANJIB SAHA', 'MALE', 'GENERAL', '3333333333', '3333333334', '2001-05-20', 'MP', 33, NULL),
(17, '2025-05-22 15:15:28', '77', 'CITA', '25002', 'YS-HBA/76-7600128/2025', 'TEU-FRI', '4PM-6PM', 'A', 'AS', 'MALE', 'GENERAL', '1111111111', '1111111122', '2002-05-04', 'HS', 22, 11),
(18, '2025-05-22 15:21:47', '77', 'CITA', '25001', 'YS-HBA/44-8800232/2025', 'WED-SAT', '2PM-4PM', 'REWREW ER', 'EWREW E', 'MALE', 'GENERAL', '3333333333', '2222222222', '2001-01-01', 'HS', 33, 33),
(19, '2025-05-23 08:54:17', '77', 'DITA', '35001', 'YS-HBA/75-888888888888', 'MON-THR', '4PM-6PM', 'EEE', 'EE', 'MALE', 'ST', '8888888888', '7777777777', '2000-05-01', 'MP', 56, NULL),
(20, '2025-05-24 08:08:51', '77', 'CITA', '25001', 'YS-HBA/76-7600123/2025', 'MON-THR', '4PM-6PM', 'BIMAL BABU', 'KAMAL BABU', 'MALE', 'SC', '7777777777', '8888888888', '2001-05-04', 'MP', 45, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `SESSION` varchar(3) DEFAULT NULL,
  `COURSE` varchar(10) DEFAULT NULL,
  `CITA` varchar(50) DEFAULT NULL,
  `DITA` varchar(50) DEFAULT NULL,
  `ADITA` varchar(50) DEFAULT NULL,
  `CDTA` varchar(50) DEFAULT NULL,
  `DDTA` varchar(50) DEFAULT NULL,
  `CFAS` varchar(50) DEFAULT NULL,
  `DFAS` varchar(50) DEFAULT NULL,
  `ADFAS` varchar(50) DEFAULT NULL,
  `CDTP` varchar(50) DEFAULT NULL,
  `DDTP` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `SESSION`, `COURSE`, `CITA`, `DITA`, `ADITA`, `CDTA`, `DDTA`, `CFAS`, `DFAS`, `ADFAS`, `CDTP`, `DDTP`) VALUES
(21, '77', 'CITA', '25001', '35001', '45001', '24001', '34001', '23001', '33001', '43001', '22001', '32001'),
(22, '', 'DITA', '25002', '', '45002', '24002', '34002', '', '', '', '', ''),
(23, '', 'ADITA', '25003', '', '', '24003', '', '', '', '', '', ''),
(24, '', 'CDTA', '', '', '', '', '', '', '', '', '', ''),
(25, '', 'DDTA', '', '', '', '', '', '', '', '', '', ''),
(26, '', 'CFAS', '', '', '', '', '', '', '', '', '', ''),
(27, '', 'DFAS', '', '', '', '', '', '', '', '', '', ''),
(28, '', 'ADFAS', '', '', '', '', '', '', '', '', '', ''),
(29, '', 'CDTP', '', '', '', '', '', '', '', '', '', ''),
(30, '', 'DDTP', '', '', '', '', '', '', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;