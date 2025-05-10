-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 10:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `z`
--

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `previous_school` varchar(150) DEFAULT NULL,
  `last_grade_level` varchar(50) DEFAULT NULL,
  `gpa` decimal(4,2) DEFAULT NULL,
  `guardian_name` varchar(100) DEFAULT NULL,
  `guardian_relationship` varchar(50) DEFAULT NULL,
  `guardian_phone` varchar(20) DEFAULT NULL,
  `guardian_email` varchar(150) DEFAULT NULL,
  `guardian_address` text DEFAULT NULL,
  `course_applied` varchar(100) DEFAULT NULL,
  `preferred_start_date` date DEFAULT NULL,
  `additional_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `section_id`, `page_id`, `first_name`, `last_name`, `date_of_birth`, `gender`, `email`, `phone`, `address`, `previous_school`, `last_grade_level`, `gpa`, `guardian_name`, `guardian_relationship`, `guardian_phone`, `guardian_email`, `guardian_address`, `course_applied`, `preferred_start_date`, `additional_notes`, `created_at`) VALUES
(1, 67, 31, 'Jhon Lorenz ', 'Bacon', '2025-05-16', 'Male', 'baconjhonlorenz@gmail.com', '09398318325', '295 MLQ ST Lower Bicutan taguig City', 'Hehe elem', '100', 2.00, 'mama', 'mama', '090866677886', 'mama@email.com', 'qweqweqwe', 'qweqweqw', '2025-05-23', 'qweqweqwe', '2025-05-10 06:26:55');

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `width` varchar(50) DEFAULT NULL,
  `height` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`id`, `section_id`, `width`, `height`, `file_path`) VALUES
(8, 69, '10%', '10%', 'uploads/Screenshot 2024-10-02 094145.png');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `website_name` varchar(255) DEFAULT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `website_name`, `page_title`, `description`, `logo_path`, `created_at`) VALUES
(26, 'Thesing', 'Ttt', 'shdfiku', 'uploads/67f85901b788b_logoln.svg', '2025-04-10 23:49:21'),
(27, 'The Website', 'Home', 'jhksbdf;ikasbd', 'uploads/67f8c6c5e1c6d_logoln.svg', '2025-04-11 07:37:41'),
(28, 'This is new ', '--xx', 'HAHHA', 'uploads/67f901796c15b_logoln.svg', '2025-04-11 11:48:09'),
(29, 'TCU', 'Home', 'This is TCU', 'uploads/67f9fdd305920_67f7debac7c33_asdasda.png', '2025-04-12 05:44:51'),
(30, 'My website', 'Home', 'Home', 'uploads/67fa0e462324c_67f37c27f1b4f_computer-icons-symbol-star-png-favpng-eGnnRJAPyXie3UysM23Zqsf3k_t.jpg', '2025-04-12 06:55:02'),
(31, 'gdgdg', 'ggdf', 'gfgfg', 'uploads/67fa15bc9ac87_67f7debac7c33_asdasda.png', '2025-04-12 07:26:52');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('parent','child') NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `width` varchar(50) DEFAULT NULL,
  `height` varchar(50) DEFAULT NULL,
  `align_items` varchar(50) DEFAULT NULL,
  `justify_content` varchar(50) DEFAULT NULL,
  `padding` varchar(20) DEFAULT NULL,
  `margin` varchar(20) DEFAULT NULL,
  `background_color` varchar(7) DEFAULT NULL,
  `border_value` varchar(20) DEFAULT NULL,
  `border_style` varchar(20) DEFAULT NULL,
  `border_color` varchar(7) DEFAULT NULL,
  `border_radius` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `page_id` int(11) NOT NULL,
  `flex_direction` varchar(100) NOT NULL,
  `font_family` varchar(255) NOT NULL,
  `font_size` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `name`, `type`, `parent_id`, `width`, `height`, `align_items`, `justify_content`, `padding`, `margin`, `background_color`, `border_value`, `border_style`, `border_color`, `border_radius`, `created_at`, `page_id`, `flex_direction`, `font_family`, `font_size`) VALUES
(37, 'Body', '', 0, '100%', '100vh', 'flex-start', 'space-between', '0px', '0%', '#e1dfb2', '0%', 'solid', '#000000', '0%', '2025-04-10 17:50:11', 26, 'column', '', ''),
(38, 'Nav', '', 37, '100%', '50px', 'center', 'center', '0%', '0%', '#dedede', '0%', 'solid', '#000000', '0%', '2025-04-10 17:52:10', 26, 'column', '', ''),
(39, 'main', '', 0, '100%', '500px', 'stretch', 'flex-start', '0%', '0%', '#ff9e9e', '0%', 'solid', '#000000', '0%', '2025-04-10 18:15:50', 26, 'column', '', ''),
(44, 'body', '', 0, '100%', '100vh', 'flex-start', 'center', '0%', '0%', '#86d0c1', '0%', 'solid', '#000000', '0%', '2025-04-11 01:38:55', 27, 'column', '', ''),
(45, 'nav', '', 44, '100%', '50px', 'flex-start', 'space-between', '0%', '0%', '#a3a3a3', '0%', 'solid', '#000000', '0%', '2025-04-11 01:39:57', 27, 'row', '', ''),
(46, 'unknown', '', 44, '100', '100vh', 'flex-start', 'space-between', '30px', '<?php?>', '#a68c8c', '0%', 'solid', '#000000', '0%', '2025-04-11 03:02:16', 27, 'row', '', ''),
(47, 'section1', '', 0, '100%', '100vh', 'stretch', 'flex-start', '0%', '0%', '#e2bbbb', '0%', 'solid', '#000000', '0%', '2025-04-11 03:20:04', 27, 'row', '', ''),
(58, 'nav', '', 0, '100', '50px', 'center', 'space-between', '0%', '0%', '#e1b7b7', '0%', 'solid', '#000000', '0%', '2025-04-11 23:02:57', 28, 'row', '', ''),
(59, 'hero', '', 0, '100', '100vh', 'center', 'flex-start', '0%', '0%', '#a2b9b5', '0%', 'solid', '#001235', '0%', '2025-04-11 23:05:35', 28, 'row', '', ''),
(63, '1', '', 59, '30%', '100vh', 'center', 'space-between', '0%', '0%', '#ffffff', '0%', 'solid', '#000000', '0%', '2025-04-11 23:38:26', 28, 'row', '', ''),
(64, 'Nav', '', 0, '100', '50px', 'center', 'center', '0%', '0%', '#dbadad', '0%', 'solid', '#000000', '0%', '2025-04-12 00:55:33', 30, 'row', '', ''),
(65, 'Container', '', 0, '100', '100vh', 'center', 'center', '0%', '0%', '#fff000', '0%', 'solid', '#000000', '0%', '2025-04-12 00:57:05', 30, 'row', '', ''),
(66, 'homer', '', 0, '100', '100vh', 'stretch', 'flex-start', '0%', '0%', '#ffffff', '0%', 'solid', '#000000', '0%', '2025-04-12 01:30:47', 28, 'row', '', ''),
(68, 'flex-start', '', 0, '100', '100px', 'baseline', 'flex-start', '0%', '0%', '#eaeaea', '0', 'solid', '#000000', '0%', '2025-05-10 00:42:51', 31, 'row', 'Arial, sans-serif', '16px'),
(69, 'Center', '', 0, '100', '100vh', 'stretch', 'center', '0%', '0%', '#000000', '0%', 'solid', '#000000', '0%', '2025-05-10 01:10:40', 31, 'row', 'Arial, sans-serif', '16px');

-- --------------------------------------------------------

--
-- Table structure for table `section_elements`
--

CREATE TABLE `section_elements` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `element_type` varchar(50) NOT NULL,
  `content` text DEFAULT NULL,
  `width` varchar(50) DEFAULT NULL,
  `height` varchar(50) DEFAULT NULL,
  `alignment` varchar(50) DEFAULT NULL,
  `padding` varchar(50) DEFAULT NULL,
  `margin` varchar(50) DEFAULT NULL,
  `border` varchar(50) DEFAULT NULL,
  `border_radius` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `font_size` varchar(255) NOT NULL,
  `font_family` varchar(255) NOT NULL,
  `Content_Div` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section_elements`
--

INSERT INTO `section_elements` (`id`, `section_id`, `element_type`, `content`, `width`, `height`, `alignment`, `padding`, `margin`, `border`, `border_radius`, `created_at`, `font_size`, `font_family`, `Content_Div`) VALUES
(2, 37, 'button', 'Click Me', '120px', '40px', 'center', '10px', '5px', '1px solid black', '5px', '2025-04-11 01:08:07', '', '', ''),
(3, 37, 'text', 'This is a sample paragraph.', '100%', 'auto', 'left', '5px', '5px', 'none', '0px', '2025-04-11 01:08:07', '', '', ''),
(5, 39, 'button', 'Click Me', '120px', '40px', 'center', '10px', '5px', '1px solid black', '5px', '2025-04-11 01:08:55', '', '', ''),
(6, 39, 'text', 'This is a sample paragraph.', '100%', 'auto', 'left', '5px', '5px', 'none', '0px', '2025-04-11 01:08:55', '', '', ''),
(7, 39, 'textfield', 'Default text', '200px', '35px', 'left', '5px', '5px', '1px solid gray', '4px', '2025-04-11 01:08:55', '', '', ''),
(8, 39, 'link', 'https://example.com', 'auto', 'auto', 'left', '5px', '5px', 'none', '0px', '2025-04-11 01:08:55', '', '', ''),
(9, 39, 'image', 'https://via.placeholder.com/150', '150px', '150px', 'center', '5px', '10px', '1px solid #ccc', '10px', '2025-04-11 01:08:55', '', '', ''),
(10, 37, 'text', 'This is the content of the text field', '100px', '40px', 'left', '5px', '5px', '1px solid #000', '5px', '2025-04-11 01:37:49', '', '', ''),
(11, 37, 'text', 'This is the content of the text field', '100px', '40px', 'left', '5px', '5px', '1px solid #000', '5px', '2025-04-11 01:37:58', '', '', ''),
(12, 38, 'image', 'image', '25px', '25px', 'left', '5px', '5px', '1px solid #000', '5px', '2025-04-11 07:34:31', '', '', ''),
(13, 38, 'image', 'image', '25px', '25px', 'left', '5px', '5px', '1px solid #000', '5px', '2025-04-11 07:34:44', '', '', ''),
(14, 45, 'button', 'Home', '100px', '40px', 'center', '5px', '5px', '1px solid #000', '5px', '2025-04-11 07:40:37', '', '', ''),
(15, 45, 'button', 'about', '100px', '40px', 'left', '5px', '5px', '1px solid #000', '5px', '2025-04-11 08:21:06', '', '', ''),
(16, 46, 'text', 'This is my world!!', '100px', '40px', 'center', '', '0', '0', '', '2025-04-11 09:12:52', '', '', ''),
(17, 46, 'text', 'Hakdawg', '100px', '40px', 'center', '0', '0', '0', '0', '2025-04-11 18:15:29', '', '', ''),
(18, 46, 'text', 'Mama mo hakdawg', '100px', '40px', 'center', '0', '0', '0', '0', '2025-04-11 18:18:14', '', '', ''),
(19, 46, 'link', 'facebook.com', '100px', '40px', 'center', '0', '0', '0', '0', '2025-04-11 18:33:19', '', '', ''),
(25, 58, 'link', 'Home', '100px', '20px', 'center', '5px', '5px', '1px solid #000', '5px', '2025-04-12 05:03:37', '', '', ''),
(26, 58, 'text', 'about', '100px', '20px', 'center', '5px', '5px', '1px solid #000', '5px', '2025-04-12 05:04:16', '', '', ''),
(28, 59, 'text', 'the most reliable internet connection', '100px', '50px', 'left', '5px', '5px', '1px solid #000', '5px', '2025-04-12 05:34:14', '', '', ''),
(29, 64, 'link', 'Home', '100px', '40px', 'left', '5px', '5px', '1px solid #000', '5px', '2025-04-12 06:56:10', '', '', ''),
(30, 64, 'button', 'About', '100px', '40px', 'left', '5px', '5px', '1px solid #000', '5px', '2025-04-12 06:56:23', '', '', ''),
(31, 64, 'button', 'Contact', '100px', '40px', 'left', '5px', '5px', '1px solid #000', '5px', '2025-04-12 06:56:42', '', '', ''),
(32, 65, 'text', 'Hello this is my context.', '100px', '40px', 'center', '5px', '5px', '0', '0', '2025-04-12 06:58:24', '', '', ''),
(33, 58, 'button', 'custom', '100px', '40px', 'left', '5px', '5px', '1px solid #000', '5px', '2025-04-12 07:30:32', '', '', '<div class=\" \">'),
(35, 69, 'image', '\r\n', '100px', '40px', 'left', '5px', '5px', '1px solid #000', '5px', '2025-05-10 07:10:53', 'em', 'Times New Roman', '');

-- --------------------------------------------------------

--
-- Table structure for table `tabs`
--

CREATE TABLE `tabs` (
  `tab_id` int(11) NOT NULL,
  `tab_name` varchar(50) NOT NULL,
  `page_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexes for table `section_elements`
--
ALTER TABLE `section_elements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `tabs`
--
ALTER TABLE `tabs`
  ADD KEY `fk_tabs_page` (`page_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `section_elements`
--
ALTER TABLE `section_elements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`);

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `section_elements`
--
ALTER TABLE `section_elements`
  ADD CONSTRAINT `section_elements_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tabs`
--
ALTER TABLE `tabs`
  ADD CONSTRAINT `fk_tabs_page` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
