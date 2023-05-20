SET NAMES utf8;
SET
time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE IF NOT EXISTS `puzzlemania`;
USE `puzzlemania`;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`
(
    `id`        INT                                                     NOT NULL AUTO_INCREMENT,
    `email`     VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `password`  VARCHAR(255)                                            NOT NULL,
    `createdAt` DATETIME                                                NOT NULL,
    `updatedAt` DATETIME                                                NOT NULL,
    `profile_picture`  VARCHAR(255)                                             ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `riddles`
(
    `riddle_id`   INT          NOT NULL AUTO_INCREMENT,
    `user_id`    INT          NOT NULL,
    `riddle`      VARCHAR(255) NOT NULL,
    `answer`    VARCHAR(255) NOT NULL,
    PRIMARY KEY (`riddle_id`),
    FOREIGN KEY (user_id) REFERENCES users (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- POPULATE USERS TABLE --
INSERT INTO users(email, password, createdAt, updatedAt, profile_picture)VALUES
('user1@salle.url.edu', '5a5b1f12ee8134cf539a608981604495', NOW(), NOW(), 'profile_placeholder.png'),
('user2@salle.url.edu', '5a5b1f12ee8134cf539a608981604495', NOW(), NOW(), 'profile_placeholder.png'),
('user3@salle.url.edu', '5a5b1f12ee8134cf539a608981604495', NOW(), NOW(), 'profile_placeholder.png'),
('user4@salle.url.edu', '5a5b1f12ee8134cf539a608981604495', NOW(), NOW(), 'profile_placeholder.png'),
('user5@salle.url.edu', '5a5b1f12ee8134cf539a608981604495', NOW(), NOW(), 'profile_placeholder.png'),
('user6@salle.url.edu', '5a5b1f12ee8134cf539a608981604495', NOW(), NOW(), 'profile_placeholder.png'),
('user7@salle.url.edu', '5a5b1f12ee8134cf539a608981604495', NOW(), NOW(), 'profile_placeholder.png');

-- POPULATE RIDDLES TABLE --
INSERT INTO riddles(user_id, riddle, answer)VALUES
(1, 'It brings back the lost as though never gone, shines laughter and tears with light long since shone; a moment to make, a lifetime to shed; valued then but lost when your dead. What Is It?', 'Memory'),
(2, 'What do you get when you cross a fish with an elephant?', 'Swimming trunks'),
(3, 'I can be long, or I can be short.\nI can be grown, and I can be bought.\nI can be painted, or left bare.\nI can be round, or I can be square.\nWhat am I?', 'Fingernails'),
(4, 'I am lighter than a feather yet no man can hold me for long.', 'Breath'),
(5, 'What occurs once in every minute, twice in every moment and yet never in a thousand years?','The letter M'),
(6, 'What nationality is Santa Claus?', 'North Polish'),
(7, 'What animal is best at hitting a baseball?', 'A bat'),
(7, 'What do you call a cow that twitches?', 'Beef jerky');

