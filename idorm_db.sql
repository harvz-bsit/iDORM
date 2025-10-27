-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2025 at 03:59 AM
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
-- Database: `idorm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `application_approvals`
--

CREATE TABLE `application_approvals` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `application_date` datetime DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `remarks` text DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_approvals`
--

INSERT INTO `application_approvals` (`id`, `student_id`, `application_date`, `status`, `remarks`, `date_created`, `date_updated`) VALUES
(1, 'D21-00306', '2025-10-26 15:08:10', 'Approved', 'Your application is under review.', '2025-10-26 07:08:10', '2025-10-26 11:46:55');

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` int(11) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `signature_path` varchar(255) DEFAULT NULL,
  `signed_at` datetime DEFAULT current_timestamp(),
  `status` enum('Not Signed','Signed') DEFAULT 'Not Signed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `student_id`, `password`, `email`, `created_at`) VALUES
(1, 'D21-00306', '$2y$10$wPF72lD7gC2DJVJaqNx.9OGwJ6KWVBKEvj5xbaylngIY3h9XRJrRa', 'mariaestrellapatlong24@gmail.com', '2025-10-26 07:08:10');

-- --------------------------------------------------------

--
-- Table structure for table `user_educational_background`
--

CREATE TABLE `user_educational_background` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `elem_school` varchar(150) DEFAULT NULL,
  `elem_year` varchar(10) DEFAULT NULL,
  `sec_school` varchar(150) DEFAULT NULL,
  `sec_year` varchar(10) DEFAULT NULL,
  `college_school` varchar(150) DEFAULT NULL,
  `college_year` varchar(10) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `year_level` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_educational_background`
--

INSERT INTO `user_educational_background` (`id`, `student_id`, `elem_school`, `elem_year`, `sec_school`, `sec_year`, `college_school`, `college_year`, `course`, `year_level`) VALUES
(1, 'D21-00306', 'Garitan Integrated School', '2015', 'Tagudin National High School', '2021', 'Ilocos Sur Polytechnic State College', 'N/A', 'Bachelor of Science in Computer Science', '1st Year');

-- --------------------------------------------------------

--
-- Table structure for table `user_family_background`
--

CREATE TABLE `user_family_background` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `father_occupation` varchar(100) DEFAULT NULL,
  `father_age` int(11) DEFAULT NULL,
  `father_contact` varchar(20) DEFAULT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `mother_occupation` varchar(100) DEFAULT NULL,
  `mother_age` int(11) DEFAULT NULL,
  `mother_contact` varchar(20) DEFAULT NULL,
  `guardian_name` varchar(100) DEFAULT NULL,
  `guardian_contact` varchar(20) NOT NULL,
  `guardian_relation` varchar(50) DEFAULT NULL,
  `parent_income` decimal(10,2) DEFAULT NULL,
  `siblings` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_family_background`
--

INSERT INTO `user_family_background` (`id`, `student_id`, `father_name`, `father_occupation`, `father_age`, `father_contact`, `mother_name`, `mother_occupation`, `mother_age`, `mother_contact`, `guardian_name`, `guardian_contact`, `guardian_relation`, `parent_income`, `siblings`) VALUES
(1, 'D21-00306', 'Jose Patlong', 'Farmer', 22, 'N/A', 'Rosario Patlong ', 'Housewife', 70, 'N/A', 'N/A', 'N/A', 'N/A', 5000.00, 5);

-- --------------------------------------------------------

--
-- Table structure for table `user_medical_history`
--

CREATE TABLE `user_medical_history` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `blood_type` varchar(5) DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `conditions` text DEFAULT NULL,
  `illness_history` text DEFAULT NULL,
  `current_medication` text DEFAULT NULL,
  `communicable` enum('None','Yes') DEFAULT 'None',
  `communicable_name` varchar(255) DEFAULT NULL,
  `communicable_medication` varchar(255) DEFAULT NULL,
  `mental_health` enum('None','Yes') DEFAULT 'None',
  `mental_health_name` varchar(255) DEFAULT NULL,
  `mental_health_medication` varchar(255) DEFAULT NULL,
  `physical` enum('None','Yes') DEFAULT 'None',
  `physical_name` varchar(255) DEFAULT NULL,
  `physical_medication` varchar(255) DEFAULT NULL,
  `last_med_checkup` text NOT NULL,
  `menstrual_cycle` enum('Regular','Irregular') DEFAULT NULL,
  `reproductive_issue` enum('None','Yes') DEFAULT 'None',
  `reproductive_specify` varchar(255) DEFAULT NULL,
  `reproductive_medication` varchar(255) DEFAULT NULL,
  `pregnant` enum('No','Yes') DEFAULT 'No',
  `last_checkup` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `physician_name` varchar(100) DEFAULT NULL,
  `physician_contact` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_medical_history`
--

INSERT INTO `user_medical_history` (`id`, `student_id`, `height`, `weight`, `blood_type`, `allergies`, `conditions`, `illness_history`, `current_medication`, `communicable`, `communicable_name`, `communicable_medication`, `mental_health`, `mental_health_name`, `mental_health_medication`, `physical`, `physical_name`, `physical_medication`, `last_med_checkup`, `menstrual_cycle`, `reproductive_issue`, `reproductive_specify`, `reproductive_medication`, `pregnant`, `last_checkup`, `due_date`, `physician_name`, `physician_contact`) VALUES
(1, 'D21-00306', 165.00, 65.00, 'A', 'N/A', 'N/A', 'N/A', 'N/A', 'None', '', '', 'None', '', '', 'None', '', '', 'Oct 10, 2025, Eye Check up', 'Regular', 'None', '', '', 'No', '0000-00-00', '0000-00-00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_personal_information`
--

CREATE TABLE `user_personal_information` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `sex` enum('Male','Female','Other') NOT NULL,
  `civil_status` varchar(20) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `place_of_birth` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_personal_information`
--

INSERT INTO `user_personal_information` (`id`, `student_id`, `full_name`, `nickname`, `age`, `sex`, `civil_status`, `nationality`, `contact`, `address`, `date_of_birth`, `place_of_birth`, `created_at`) VALUES
(1, 'D21-00306', 'Maria Estrella Patlong', 'Harvy', 22, 'Female', 'Single', 'Filipino', '09159125631', 'Garitan, Tagudin, Ilocos Sur', '2003-05-24', 'Bio, Tagudin, Ilocos Sur', '2025-10-26 07:08:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application_approvals`
--
ALTER TABLE `application_approvals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_educational_background`
--
ALTER TABLE `user_educational_background`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_family_background`
--
ALTER TABLE `user_family_background`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_medical_history`
--
ALTER TABLE `user_medical_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_personal_information`
--
ALTER TABLE `user_personal_information`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `application_approvals`
--
ALTER TABLE `application_approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_educational_background`
--
ALTER TABLE `user_educational_background`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_family_background`
--
ALTER TABLE `user_family_background`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_medical_history`
--
ALTER TABLE `user_medical_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_personal_information`
--
ALTER TABLE `user_personal_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
