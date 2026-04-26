-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2026 at 04:44 PM
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
-- Database: `personal_finance_managers`
--

-- --------------------------------------------------------

--
-- Table structure for table `balances`
--

CREATE TABLE `balances` (
  `balance_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `current_balance` decimal(10,2) DEFAULT 0.00,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `balances`
--

INSERT INTO `balances` (`balance_id`, `user_id`, `current_balance`, `last_updated`) VALUES
(1, 1, 76400.00, '2026-04-02 12:16:27'),
(2, 2, 100000.00, '2026-03-17 05:48:10'),
(3, 3, 10000.00, '2026-03-13 16:25:31'),
(4, 4, 120000.00, '2026-03-20 15:23:35'),
(5, 5, 100000.00, '2026-03-23 03:47:05');

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `budget_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `monthly_limit` decimal(10,2) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`budget_id`, `user_id`, `category_id`, `monthly_limit`, `month`, `year`) VALUES
(1, 1, 1, 6000.00, 1, 2026),
(2, 1, 1, 200.00, 3, 2026),
(3, 1, 7, 100000.00, 12, 2026),
(4, 2, 1, 10000.00, 3, 2026),
(6, 2, 4, 3000.00, 3, 2026),
(7, 2, 3, 5000.00, 3, 2026),
(8, 2, 12, 10000.00, 3, 2026),
(9, 4, 1, 5000.00, 3, 2026),
(10, 4, 3, 2000.00, 3, 2026),
(11, 5, 7, 12000.00, 3, 2026),
(12, 5, 5, 1000.00, 3, 2026);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) DEFAULT NULL,
  `type` enum('income','expense') DEFAULT 'expense',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `type`, `user_id`) VALUES
(1, 'Food', 'expense', NULL),
(2, 'Transport', 'expense', NULL),
(3, 'Shopping', 'expense', NULL),
(4, 'Bills', 'expense', NULL),
(5, 'Entertainment', 'expense', NULL),
(6, 'Health', 'expense', NULL),
(7, 'Education', 'expense', NULL),
(10, 'Investment', 'expense', 3),
(11, 'Investment', 'expense', 1),
(12, 'Investment', 'expense', 2),
(14, 'Investment', 'expense', 5);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`expense_id`, `user_id`, `category_id`, `amount`, `description`, `date`) VALUES
(1, 1, 1, 1555.00, 'Grocery', '2026-03-04'),
(2, 1, 1, 1010.00, 'Grocery', '2026-03-11'),
(3, 1, 1, 200.00, 'Rice', '2026-03-11'),
(4, 1, 7, 12000.00, 'College Fees', '2026-03-13'),
(5, 1, 5, 200.00, 'Movie', '2026-03-11'),
(7, 1, 11, 10000.00, 'LIC', '2026-03-12'),
(8, 2, 4, 1500.00, 'Light and phone', '2026-03-02'),
(9, 2, 3, 4500.00, 'cloths, accessories', '2026-03-05'),
(10, 2, 12, 505.00, 'LIC', '2026-03-06'),
(13, 2, 12, 1000.00, 'RD', '2026-03-05'),
(14, 2, 7, 3200.00, 'Exam form sem VI', '2026-03-10'),
(15, 4, 1, 4500.00, 'grocery', '2026-03-20'),
(16, 4, 3, 2100.00, 'clothing', '2026-03-18'),
(17, 4, 5, 1000.00, 'movie', '2026-02-10'),
(18, 4, 7, 15000.00, 'college fees', '2026-02-18'),
(19, 4, 6, 1000.00, 'health checkup', '2026-01-20'),
(20, 4, 2, 2500.00, 'trip', '2026-01-28'),
(21, 5, 7, 12000.00, 'College Fees', '2026-03-22'),
(22, 5, 5, 800.00, 'Movie', '2026-03-23'),
(23, 5, 5, 100.00, 'Movie', '2026-03-22'),
(26, 1, 2, 50.00, 'Bus', '2026-04-02'),
(27, 1, 4, 280.00, 'Light Bill', '2026-04-02');

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

CREATE TABLE `income` (
  `income_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `income`
--

INSERT INTO `income` (`income_id`, `user_id`, `amount`, `source`, `date`) VALUES
(4, 1, 10000.00, 'salary', '2026-01-01'),
(5, 1, 1200.00, 'LBY', '2026-03-04'),
(6, 1, 12000.00, 'salary', '2026-03-01'),
(7, 1, 12000.00, 'Milk', '2026-03-13'),
(8, 3, 10000.00, 'salary', '2026-02-28'),
(9, 1, 30000.00, 'salary', '2026-03-04'),
(10, 2, 100000.00, 'salary', '2026-03-02'),
(11, 4, 40000.00, 'salary', '2026-03-02'),
(12, 4, 40000.00, 'salary', '2026-02-02'),
(13, 4, 40000.00, 'salary', '2026-01-02'),
(14, 5, 100000.00, 'salary', '2026-03-02'),
(15, 1, 10000.00, 'salary', '2026-04-02'),
(16, 1, 1200.00, 'Trading', '2026-04-01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Sakshi Babaso Jadhav', 'jadhavsakshi6470@gmail.com', '$2y$10$yAh9ECdDOpfzvtpdiVVLBO4o0Jh.I53oFF3pi3itMQum8ShXODKvq', '2026-02-24 16:08:17'),
(2, 'Soundarya Sonar', 'ss123@gmail.com', '$2y$10$5Dliggde36m4fhDhuJsy.eF6xt4Wdq.Xs8P5uWFPO1pAdtZ.Jz/Ua', '2026-03-02 09:10:25'),
(3, 'Aditya Patil', 'adityapatil9014@gmail.com', '$2y$10$MkK4pQ3GOF73jlvO4E0lkuqD07S6uIzidN8BnR0RQW9n8Di6q.bTK', '2026-03-13 16:24:42'),
(4, 'Vaishnavi Babaso Jadhav', 'vaishnu6570@gmail.com', '$2y$10$fjVIbY1zk6E6bUxfHextWOtct2qjdSSCtnkXk6Hw21uls15RC4RQy', '2026-03-20 15:13:14'),
(5, 'Dnyaneshwari Patil', 'dpatil123@gmail.com', '$2y$10$udqQbKvBGCn4HcLYetq7MuWYnZ3yddseWgAyWikreoFMIXF4HHqJa', '2026-03-23 03:46:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `balances`
--
ALTER TABLE `balances`
  ADD PRIMARY KEY (`balance_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`budget_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`category_id`,`month`,`year`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `income`
--
ALTER TABLE `income`
  ADD PRIMARY KEY (`income_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `balances`
--
ALTER TABLE `balances`
  MODIFY `balance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `budget_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `income`
--
ALTER TABLE `income`
  MODIFY `income_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `balances`
--
ALTER TABLE `balances`
  ADD CONSTRAINT `balances_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `budgets_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `income`
--
ALTER TABLE `income`
  ADD CONSTRAINT `income_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
