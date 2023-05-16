-- Adminer 4.8.1 MySQL 8.0.32 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `riddles`;
CREATE TABLE `riddles` (
                           `riddle_id` int NOT NULL AUTO_INCREMENT,
                           `user_id` int NOT NULL,
                           `riddle` varchar(255) NOT NULL,
                           `answer` varchar(255) NOT NULL,
                           PRIMARY KEY (`riddle_id`),
                           KEY `user_id` (`user_id`),
                           CONSTRAINT `riddles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
                         `id` int NOT NULL AUTO_INCREMENT,
                         `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
                         `password` varchar(255) NOT NULL,
                         `createdAt` datetime NOT NULL,
                         `updatedAt` datetime NOT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


-- 2023-05-16 17:00:45