-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2025 at 04:22 PM
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
-- Database: `rentalnest`
--

-- --------------------------------------------------------

--
-- Table structure for table `affiliates`
--

CREATE TABLE `affiliates` (
  `f_id` int(11) NOT NULL,
  `servicer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `affiliates`
--

INSERT INTO `affiliates` (`f_id`, `servicer_id`) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 4),
(3, 1),
(4, 2),
(4, 3),
(5, 1),
(6, 4),
(7, 2),
(8, 3),
(9, 1),
(10, 4),
(12, 2),
(13, 3),
(16, 1),
(17, 2),
(18, 3),
(19, 4),
(21, 1),
(22, 2),
(25, 3),
(27, 4),
(28, 1),
(30, 2);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `b_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `f_id` int(11) DEFAULT NULL,
  `r_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`b_id`, `start_date`, `end_date`, `f_id`, `r_id`, `owner_id`) VALUES
(1, '2025-08-30', '2025-09-30', NULL, 1, 1),
(2, '2025-08-30', '2025-09-02', NULL, 11, 4);

-- --------------------------------------------------------

--
-- Table structure for table `flat`
--

CREATE TABLE `flat` (
  `f_id` int(11) NOT NULL,
  `availability` tinyint(1) NOT NULL,
  `district` varchar(50) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `area` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `members_count` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `date_posted` date DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flat`
--

INSERT INTO `flat` (`f_id`, `availability`, `district`, `street`, `area`, `description`, `members_count`, `price`, `date_posted`, `owner_id`) VALUES
(1, 0, 'Dhaka', 'Badda', 'BRAC University Area', '1BHK cozy flat near BRAC University', 2, 28000, '2025-08-01', 1),
(2, 1, 'Dhaka', 'Badda', 'BRAC University Area', '2BHK flat with balcony', 3, 40000, '2025-08-02', 2),
(3, 0, 'Dhaka', 'Badda', 'Aftabnagar', '3BHK fully furnished flat', 4, 55000, '2025-08-03', 3),
(4, 1, 'Dhaka', 'Badda', 'BRAC University Area', '1BHK near market', 2, 30000, '2025-08-04', 4),
(5, 1, 'Dhaka', 'Badda', 'BRAC University Area', '2BHK flat with parking', 3, 42000, '2025-08-05', 5),
(6, 1, 'Dhaka', 'Badda', 'Aftabnagar', '1BHK, student friendly', 2, 29000, '2025-08-06', 6),
(7, 0, 'Dhaka', 'Badda', 'Aftabnagar', '3BHK, balcony and terrace', 4, 56000, '2025-08-07', 7),
(8, 1, 'Dhaka', 'Badda', 'Aftabnagar', '2BHK near main gate', 3, 39000, '2025-08-08', 8),
(9, 1, 'Dhaka', 'Badda', 'BRAC University Area', '1BHK cozy flat', 2, 28000, '2025-08-09', 9),
(10, 1, 'Dhaka', 'Badda', 'Aftabnagar', '2BHK flat with balcony', 3, 41000, '2025-08-10', 10),
(11, 1, 'Dhaka', 'Badda', 'BRAC University Area', '1BHK near bus stop', 2, 29000, '2025-08-11', 11),
(12, 0, 'Dhaka', 'Badda', 'Aftabnagar', '3BHK flat, furnished', 4, 55000, '2025-08-12', 12),
(13, 1, 'Dhaka', 'Badda', 'BRAC University Area', '2BHK near market', 3, 40000, '2025-08-13', 13),
(14, 1, 'Dhaka', 'Badda', 'BRAC University Area', '1BHK cozy, bright', 2, 30000, '2025-08-14', 14),
(15, 1, 'Dhaka', 'Badda', 'Aftabnagar', '2BHK flat near BRAC gate', 3, 42000, '2025-08-15', 15),
(16, 1, 'Dhaka', 'Dhanmondi', 'Dhanmondi-32', '2BHK flat near Dhaka University', 3, 45000, '2025-08-16', 16),
(17, 1, 'Dhaka', 'Mohakhali', 'BUET Area', '2BHK flat near BUET campus', 3, 42000, '2025-08-17', 17),
(18, 0, 'Chattogram', 'Pahartali', 'Chattogram University Area', '1BHK near Chittagong University', 2, 25000, '2025-08-18', 18),
(19, 1, 'Chattogram', 'Bakalia', 'Varsity Road', '2BHK flat with balcony', 3, 37000, '2025-08-19', 19),
(20, 1, 'Sylhet', 'Taltoli', 'Shahjalal University Area', '2BHK flat near SU campus', 3, 30000, '2025-08-20', 20),
(21, 1, 'Sylhet', 'Subid Bazar', 'SU Area', '1BHK apartment close to SU', 2, 22000, '2025-08-21', 21),
(22, 1, 'Dhaka', 'Mirpur', 'Mirpur-1', '2BHK flat near North University', 3, 38000, '2025-08-22', 22),
(23, 1, 'Dhaka', 'Uttara', 'Sector-10', '1BHK cozy flat, furnished', 2, 30000, '2025-08-23', 23),
(24, 0, 'Chattogram', 'Varsity Gate', 'University Area', '2BHK flat, furnished', 3, 35000, '2025-08-24', 24),
(25, 1, 'Dhaka', 'Banani', 'Banani Road', '2BHK apartment near university', 3, 45000, '2025-08-25', 25),
(26, 1, 'Dhaka', 'Dhanmondi', 'Dhanmondi-27', '1BHK cozy apartment', 2, 28000, '2025-08-26', 26),
(27, 1, 'Chattogram', 'Agrabad', 'Agrabad Area', '2BHK flat near office', 3, 40000, '2025-08-27', 27),
(28, 0, 'Sylhet', 'Subid Bazar', 'SU Area', '3BHK flat near campus', 4, 50000, '2025-08-28', 28),
(29, 1, 'Dhaka', 'Mohakhali', 'BUET Area', '2BHK flat, modern interior', 3, 42000, '2025-08-29', 29),
(30, 1, 'Dhaka', 'Bashundhara', 'North University Area', '1BHK flat near main road', 2, 30000, '2025-08-30', 30),
(31, 1, 'Dhaka', 'Merul Badda, DIT Road', 'Badda', 'Only for BRACU & EWU Students', 2, 30000, '2025-08-30', 1),
(32, 1, 'Dhaka', 'Mohakhali, Amtoli', 'Mohakhali', 'Newly built flat', 3, 20000, '2025-08-30', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gives`
--

CREATE TABLE `gives` (
  `rev_id` int(11) NOT NULL,
  `f_id` int(11) NOT NULL,
  `r_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gives`
--

INSERT INTO `gives` (`rev_id`, `f_id`, `r_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4),
(5, 5, 5),
(6, 6, 6),
(7, 7, 7),
(8, 8, 8),
(9, 9, 9),
(10, 10, 10),
(11, 11, 1),
(12, 12, 2),
(13, 13, 3),
(14, 14, 4),
(15, 15, 5),
(16, 16, 6),
(17, 17, 7),
(18, 18, 8),
(19, 19, 9),
(20, 20, 10),
(21, 21, 1),
(22, 22, 2),
(23, 23, 3),
(24, 24, 4),
(25, 25, 5),
(26, 26, 6),
(27, 27, 7),
(28, 28, 8),
(29, 29, 9),
(30, 30, 10);

-- --------------------------------------------------------

--
-- Table structure for table `interested_properties`
--

CREATE TABLE `interested_properties` (
  `interest_id` int(11) NOT NULL,
  `r_id` int(11) NOT NULL,
  `f_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `owner_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `NID` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `owner_type` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`owner_id`, `name`, `phone`, `email`, `NID`, `address`, `owner_type`, `gender`) VALUES
(1, 'Arif Hossain', '01710001122', 'arif.hossain@example.com', '1234567890', 'Gulshan, Dhaka', 'Sublet', 'Male'),
(2, 'Nabila Sultana', '01912223344', 'nabila.sultana@example.com', '2345678901', 'Banani, Dhaka', 'Flat', 'Female'),
(3, 'Rashed Khan', '01813334455', 'rashed.khan@example.com', '3456789012', 'Mirpur, Dhaka', 'Sublet', 'Male'),
(4, 'Tasnim Akter', '01714445566', 'tasnim.akter@example.com', '4567890123', 'Badda, Dhaka', 'Flat', 'Female'),
(5, 'Shahriar Rahman', '01915556677', 'shahriar.rahman@example.com', '5678901234', 'Dhanmondi, Dhaka', 'Sublet', 'Male'),
(6, 'Lina Begum', '01816667788', 'lina.begum@example.com', '6789012345', 'Uttara, Dhaka', 'Flat', 'Female'),
(7, 'Fahim Chowdhury', '01717778899', 'fahim.chowdhury@example.com', '7890123456', 'Motijheel, Dhaka', 'Sublet', 'Male'),
(8, 'Roksana Khatun', '01918889900', 'roksana.khatun@example.com', '8901234567', 'Taltoli, Sylhet', 'Flat', 'Female'),
(9, 'Jahid Islam', '01819990011', 'jahid.islam@example.com', '9012345678', 'Pahartali, Chattogram', 'Sublet', 'Male'),
(10, 'Tamanna Akter', '01711001122', 'tamanna.akter@example.com', '0123456789', 'Agrabad, Chattogram', 'Flat', 'Female'),
(11, 'Abdul Karim', '01711223344', 'abdul.karim@example.com', '1987456321', 'Dhanmondi, Dhaka', 'Sublet', 'Male'),
(12, 'Farhana Akter', '01987654321', 'farhana.akter@example.com', '2398745612', 'Gulshan, Dhaka', 'Flat', 'Female'),
(13, 'Mahmudul Hasan', '01812345678', 'mahmudul.hasan@example.com', '1789456323', 'Banani, Dhaka', 'Sublet', 'Male'),
(14, 'Sadia Rahman', '01633445566', 'sadia.rahman@example.com', '1982345614', 'Chawkbazar, Chittagong', 'Flat', 'Female'),
(15, 'Tanvir Ahmed', '01722334455', 'tanvir.ahmed@example.com', '2093847565', 'Khulshi, Chittagong', 'Sublet', 'Male'),
(16, 'Rafiq Uddin', '01556778899', 'rafiq.uddin@example.com', '1789456387', 'Shibganj, Bogura', 'Flat', 'Male'),
(17, 'Nusrat Jahan', '01755667788', 'nusrat.jahan@example.com', '2345678910', 'Mirpur, Dhaka', 'Sublet', 'Female'),
(18, 'Imran Hossain', '01933445566', 'imran.hossain@example.com', '1892345672', 'Agrabad, Chittagong', 'Flat', 'Male'),
(19, 'Mitu Akter', '01855667799', 'mitu.akter@example.com', '1978456391', 'Kazir Dewri, Chittagong', 'Sublet', 'Female'),
(20, 'Samiul Islam', '01788990011', 'samiul.islam@example.com', '1982345678', 'Uttara, Dhaka', 'Flat', 'Male'),
(21, 'Rumana Akter', '01711224455', 'rumana.akter@example.com', '1987456399', 'Badda, Dhaka', 'Sublet', 'Female'),
(22, 'Shahriar Khan', '01911223344', 'shahriar.khan@example.com', '2389456723', 'Dhanmondi, Dhaka', 'Flat', 'Male'),
(23, 'Fatema Begum', '01833445577', 'fatema.begum@example.com', '1982345679', 'Mirpur, Dhaka', 'Sublet', 'Female'),
(24, 'Hasan Mahmud', '01755668877', 'hasan.mahmud@example.com', '1987456320', 'Banani, Dhaka', 'Flat', 'Male'),
(25, 'Tania Sultana', '01944556677', 'tania.sultana@example.com', '2398456721', 'Uttara, Dhaka', 'Sublet', 'Female'),
(26, 'Sabbir Rahman', '01866778899', 'sabbir.rahman@example.com', '1982345610', 'Gulshan, Dhaka', 'Flat', 'Male'),
(27, 'Lamia Akter', '01799887766', 'lamia.akter@example.com', '1987456322', 'Badda, Dhaka', 'Sublet', 'Female'),
(28, 'Jahid Hasan', '01977665544', 'jahid.hasan@example.com', '1982345699', 'Pahartali, Chattogram', 'Flat', 'Male'),
(29, 'Roksana Akter', '01888776655', 'roksana.akter@example.com', '1982345671', 'Taltoli, Sylhet', 'Sublet', 'Female'),
(30, 'Shahriar Alam', '01766554433', 'shahriar.alam@example.com', '1982345688', 'Agrabad, Chattogram', 'Flat', 'Male');

-- --------------------------------------------------------

--
-- Table structure for table `renter`
--

CREATE TABLE `renter` (
  `r_id` int(11) NOT NULL,
  `st_id` varchar(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `room_preference` varchar(50) DEFAULT NULL,
  `institution` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `renter`
--

INSERT INTO `renter` (`r_id`, `st_id`, `name`, `address`, `gender`, `room_preference`, `institution`, `type`, `phone`) VALUES
(1, '23201186', 'Rakibul Hasan', 'Mohakhali, Dhaka', 'Male', 'Single Room', 'BRAC University', 'Student', '01744556677'),
(2, '2501026', 'Shamima Akter', 'Farmgate, Dhaka', 'Female', 'Shared Room', 'BUET', 'Student', '01822334455'),
(3, '22304567', 'Tariqul Islam', 'New Market, Dhaka', 'Male', 'Single Room', 'North South University', 'Student', '01911223344'),
(4, '23203214', 'Jannatul Ferdous', 'Kazipara, Dhaka', 'Female', 'Shared Room', 'BRAC University', 'Student', '01655667788'),
(5, '2502129', 'Fahim Rahman', 'Agrabad, Chittagong', 'Male', 'Single Room', 'CUET', 'Student', '01766778899'),
(6, '21304568', 'Salma Khatun', 'GEC Circle, Chittagong', 'Female', 'Shared Room', 'Chittagong University', 'Student', '01899887766'),
(7, '23105678', 'Sabbir Ahmed', 'Mirpur-10, Dhaka', 'Male', 'Single Room', 'AIUB', 'Student', '01733445566'),
(8, '22307891', 'Nishat Jahan', 'Kallyanpur, Dhaka', 'Female', 'Shared Room', 'East West University', 'Student', '01955667788'),
(9, '21123456', 'Mehedi Hasan', 'Sholashahar, Chittagong', 'Male', 'Single Room', 'IBA (DU)', 'Student', '01722334455'),
(10, '23209999', 'Sumaiya Rahman', 'Bashundhara, Dhaka', 'Female', 'Shared Room', 'North South University', 'Student', '01811223344'),
(11, '22241110', 'Himel', 'Mohakhali, Dhaka', 'Male', 'Single Room', 'BRAC University', 'Student', '01778899444');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `rev_id` int(11) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`rev_id`, `description`) VALUES
(1, 'Clean and cozy flat.'),
(2, 'Good location and quiet.'),
(3, 'Spacious rooms, well-maintained.'),
(4, 'Friendly owner, smooth process.'),
(5, 'Affordable rent for the area.'),
(6, 'Nearby shops and transport.'),
(7, 'Well-lit and airy flat.'),
(8, 'Safe and secure neighborhood.'),
(9, 'Kitchen and bathroom are clean.'),
(10, 'Good for students.'),
(11, 'Needs minor repairs.'),
(12, 'Excellent balcony view.'),
(13, 'Noise-free environment.'),
(14, 'Parking available.'),
(15, 'Good ventilation and sunlight.'),
(16, 'Owner cooperative.'),
(17, 'Utilities included.'),
(18, 'Spacious for roommates.'),
(19, 'Nice floor and furniture.'),
(20, 'Convenient for daily commute.'),
(21, 'Safe area with security.'),
(22, 'Affordable and clean.'),
(23, 'Quiet evenings, perfect study space.'),
(24, 'Friendly neighbors.'),
(25, 'Well-maintained common areas.'),
(26, 'Close to university.'),
(27, 'Easy access to bus and rickshaw.'),
(28, 'Modern kitchen and fittings.'),
(29, 'Good lighting and ventilation.'),
(30, 'Overall pleasant experience.');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `servicer_id` int(11) NOT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`servicer_id`, `price`) VALUES
(1, 1200),
(2, 1500),
(3, 1800),
(4, 2000),
(5, 2200),
(6, 2500),
(7, 2700),
(8, 2800),
(9, 2900),
(10, 3000);

-- --------------------------------------------------------

--
-- Table structure for table `service_type`
--

CREATE TABLE `service_type` (
  `servicer_id` int(11) NOT NULL,
  `service_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_type`
--

INSERT INTO `service_type` (`servicer_id`, `service_type`) VALUES
(1, 'cooking'),
(1, 'househelp'),
(2, 'surveillance'),
(2, 'wifi'),
(3, 'househelp'),
(4, 'cooking'),
(5, 'wifi'),
(6, 'surveillance'),
(7, 'househelp'),
(8, 'cooking');

-- --------------------------------------------------------

--
-- Table structure for table `viewing_appointments`
--

CREATE TABLE `viewing_appointments` (
  `appointment_id` int(11) NOT NULL,
  `availability_id` int(11) NOT NULL,
  `r_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'scheduled' COMMENT 'scheduled, completed, canceled',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visit_availability`
--

CREATE TABLE `visit_availability` (
  `availability_id` int(11) NOT NULL,
  `f_id` int(11) NOT NULL,
  `day_of_week` tinyint(1) NOT NULL COMMENT '0=Sunday, 1=Monday, ..., 6=Saturday',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visit_availability`
--

INSERT INTO `visit_availability` (`availability_id`, `f_id`, `day_of_week`, `start_time`, `end_time`) VALUES
(1, 32, 5, '11:01:00', '19:01:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `affiliates`
--
ALTER TABLE `affiliates`
  ADD PRIMARY KEY (`f_id`,`servicer_id`),
  ADD KEY `servicer_id` (`servicer_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`b_id`),
  ADD KEY `r_id` (`r_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `booking_ibfk_3` (`f_id`);

--
-- Indexes for table `flat`
--
ALTER TABLE `flat`
  ADD PRIMARY KEY (`f_id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `gives`
--
ALTER TABLE `gives`
  ADD PRIMARY KEY (`rev_id`,`f_id`,`r_id`),
  ADD KEY `f_id` (`f_id`),
  ADD KEY `r_id` (`r_id`);

--
-- Indexes for table `interested_properties`
--
ALTER TABLE `interested_properties`
  ADD PRIMARY KEY (`interest_id`),
  ADD UNIQUE KEY `r_id_f_id` (`r_id`,`f_id`),
  ADD KEY `f_id` (`f_id`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`owner_id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `NID` (`NID`);

--
-- Indexes for table `renter`
--
ALTER TABLE `renter`
  ADD PRIMARY KEY (`r_id`),
  ADD UNIQUE KEY `st_id` (`st_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`rev_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`servicer_id`);

--
-- Indexes for table `service_type`
--
ALTER TABLE `service_type`
  ADD PRIMARY KEY (`servicer_id`,`service_type`);

--
-- Indexes for table `viewing_appointments`
--
ALTER TABLE `viewing_appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `availability_id` (`availability_id`),
  ADD KEY `r_id` (`r_id`);

--
-- Indexes for table `visit_availability`
--
ALTER TABLE `visit_availability`
  ADD PRIMARY KEY (`availability_id`),
  ADD KEY `f_id` (`f_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `b_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `flat`
--
ALTER TABLE `flat`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `interested_properties`
--
ALTER TABLE `interested_properties`
  MODIFY `interest_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `owner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `renter`
--
ALTER TABLE `renter`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `viewing_appointments`
--
ALTER TABLE `viewing_appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visit_availability`
--
ALTER TABLE `visit_availability`
  MODIFY `availability_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `affiliates`
--
ALTER TABLE `affiliates`
  ADD CONSTRAINT `affiliates_ibfk_1` FOREIGN KEY (`f_id`) REFERENCES `flat` (`f_id`),
  ADD CONSTRAINT `affiliates_ibfk_2` FOREIGN KEY (`servicer_id`) REFERENCES `services` (`servicer_id`);

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`r_id`) REFERENCES `renter` (`r_id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`owner_id`) REFERENCES `owner` (`owner_id`),
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`f_id`) REFERENCES `flat` (`f_id`);

--
-- Constraints for table `flat`
--
ALTER TABLE `flat`
  ADD CONSTRAINT `flat_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `owner` (`owner_id`);

--
-- Constraints for table `gives`
--
ALTER TABLE `gives`
  ADD CONSTRAINT `gives_ibfk_1` FOREIGN KEY (`rev_id`) REFERENCES `reviews` (`rev_id`),
  ADD CONSTRAINT `gives_ibfk_2` FOREIGN KEY (`f_id`) REFERENCES `flat` (`f_id`),
  ADD CONSTRAINT `gives_ibfk_3` FOREIGN KEY (`r_id`) REFERENCES `renter` (`r_id`);

--
-- Constraints for table `interested_properties`
--
ALTER TABLE `interested_properties`
  ADD CONSTRAINT `interested_properties_ibfk_1` FOREIGN KEY (`r_id`) REFERENCES `renter` (`r_id`),
  ADD CONSTRAINT `interested_properties_ibfk_2` FOREIGN KEY (`f_id`) REFERENCES `flat` (`f_id`);

--
-- Constraints for table `service_type`
--
ALTER TABLE `service_type`
  ADD CONSTRAINT `service_type_ibfk_1` FOREIGN KEY (`servicer_id`) REFERENCES `services` (`servicer_id`);

--
-- Constraints for table `viewing_appointments`
--
ALTER TABLE `viewing_appointments`
  ADD CONSTRAINT `viewing_appointments_ibfk_1` FOREIGN KEY (`availability_id`) REFERENCES `visit_availability` (`availability_id`),
  ADD CONSTRAINT `viewing_appointments_ibfk_2` FOREIGN KEY (`r_id`) REFERENCES `renter` (`r_id`);

--
-- Constraints for table `visit_availability`
--
ALTER TABLE `visit_availability`
  ADD CONSTRAINT `visit_availability_ibfk_1` FOREIGN KEY (`f_id`) REFERENCES `flat` (`f_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
