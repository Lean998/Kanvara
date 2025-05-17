-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 17, 2025 at 10:41 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kanvara`
--

-- --------------------------------------------------------

--
-- Table structure for table `collaborations`
--

CREATE TABLE `collaborations` (
  `collaboration_id` int NOT NULL,
  `user_id` int NOT NULL,
  `task_id` int NOT NULL,
  `collaboration_deleted_at` datetime DEFAULT NULL,
  `collaboration_created_at` datetime DEFAULT NULL,
  `collaboration_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `collaborations`
--

INSERT INTO `collaborations` (`collaboration_id`, `user_id`, `task_id`, `collaboration_deleted_at`, `collaboration_created_at`, `collaboration_updated_at`) VALUES
(27, 12, 46, NULL, '2025-05-16 15:37:15', '2025-05-16 15:37:15');

-- --------------------------------------------------------

--
-- Table structure for table `collaborations_subtask`
--

CREATE TABLE `collaborations_subtask` (
  `collaboration_subtask_id` int NOT NULL,
  `user_id` int NOT NULL,
  `subtask_id` int NOT NULL,
  `collaboration_subtask_deleted_at` datetime DEFAULT NULL,
  `collaboration_subtask_created_at` datetime DEFAULT NULL,
  `collaboration_subtask_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comments_id` int NOT NULL,
  `comments_comment` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `subtask_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invitations`
--

CREATE TABLE `invitations` (
  `invitation_id` int NOT NULL,
  `task_id` int NOT NULL,
  `invitation_email` varchar(255) NOT NULL,
  `invitation_code` varchar(10) NOT NULL,
  `invitation_created_at` datetime NOT NULL,
  `invitation_expires_at` datetime NOT NULL,
  `invitation_used` tinyint NOT NULL,
  `invitation_updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subtasks`
--

CREATE TABLE `subtasks` (
  `subtask_id` int NOT NULL,
  `subtask_desc` varchar(125) NOT NULL,
  `subtask_state` enum('Definida','En proceso','Completada','') DEFAULT NULL,
  `subtask_priority` enum('Baja','Normal','Alta','') DEFAULT NULL,
  `subtask_expiry` datetime DEFAULT NULL,
  `subtask_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `task_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `subtask_updated_at` datetime DEFAULT NULL,
  `subtask_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int NOT NULL,
  `task_title` varchar(25) NOT NULL,
  `task_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_priority` enum('Baja','Normal','Alta','') NOT NULL,
  `task_state` enum('Definida','En proceso','Completada','') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_expiry` datetime NOT NULL,
  `task_reminder` datetime DEFAULT NULL,
  `task_color` varchar(15) NOT NULL,
  `task_archived` tinyint DEFAULT NULL,
  `user_id` int NOT NULL,
  `task_created_at` datetime DEFAULT NULL,
  `task_updated_at` datetime DEFAULT NULL,
  `task_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `task_title`, `task_desc`, `task_priority`, `task_state`, `task_expiry`, `task_reminder`, `task_color`, `task_archived`, `user_id`, `task_created_at`, `task_updated_at`, `task_deleted_at`) VALUES
(46, 'Reunión con el equipo', 'Discutir avances y asignar nuevas tareas.', 'Baja', 'Completada', '2025-05-17 23:30:00', '2025-05-17 10:00:00', '#2ecc31', 0, 11, '2025-05-16 15:29:47', '2025-05-16 15:29:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(70) NOT NULL,
  `user_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`) VALUES
(11, 'Lucas123', 'Lucas123@gmail.com', '$2y$10$mCoRZBv9EZ6CUaYkDSKdb.6w7urFEfBRqjJLHJGDC2Csgv36zfW/.'),
(12, 'Roberto123', 'Roberto123@gmail.com', '$2y$10$5D.gXKfbRXU.a9X/hGQajuNlPEwfpKQFw.Vlfyo4TUum1sM/MOHwG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `collaborations`
--
ALTER TABLE `collaborations`
  ADD PRIMARY KEY (`collaboration_id`),
  ADD UNIQUE KEY `unique_user_task` (`user_id`,`task_id`),
  ADD UNIQUE KEY `task_user_unique` (`task_id`,`user_id`);

--
-- Indexes for table `collaborations_subtask`
--
ALTER TABLE `collaborations_subtask`
  ADD PRIMARY KEY (`collaboration_subtask_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subtask_id` (`subtask_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comments_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subtask_id` (`subtask_id`);

--
-- Indexes for table `invitations`
--
ALTER TABLE `invitations`
  ADD PRIMARY KEY (`invitation_id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `subtasks`
--
ALTER TABLE `subtasks`
  ADD PRIMARY KEY (`subtask_id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `collaborations`
--
ALTER TABLE `collaborations`
  MODIFY `collaboration_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `collaborations_subtask`
--
ALTER TABLE `collaborations_subtask`
  MODIFY `collaboration_subtask_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comments_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `invitations`
--
ALTER TABLE `invitations`
  MODIFY `invitation_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `subtasks`
--
ALTER TABLE `subtasks`
  MODIFY `subtask_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `collaborations`
--
ALTER TABLE `collaborations`
  ADD CONSTRAINT `collaborations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `collaborations_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`task_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `collaborations_subtask`
--
ALTER TABLE `collaborations_subtask`
  ADD CONSTRAINT `collaborations_subtask_ibfk_1` FOREIGN KEY (`subtask_id`) REFERENCES `subtasks` (`subtask_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `collaborations_subtask_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`subtask_id`) REFERENCES `subtasks` (`subtask_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invitations`
--
ALTER TABLE `invitations`
  ADD CONSTRAINT `invitations_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`task_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subtasks`
--
ALTER TABLE `subtasks`
  ADD CONSTRAINT `subtasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subtasks_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`task_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
