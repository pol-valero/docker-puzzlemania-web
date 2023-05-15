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
    `team`      INT,
    `createdAt` DATETIME                                                NOT NULL,
    `updatedAt` DATETIME                                                NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (team) REFERENCES teams (id)
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

CREATE TABLE `teams`
(
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `name`          VARCHAR(255)    NOT NULL,
    `numMembers`    INT             NOT NULL,
    `createdAt`     DATETIME        NOT NULL,
    `updatedAt`     DATETIME        NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `games`
(
    `id`            INT         NOT NULL AUTO_INCREMENT,
    `user_id`       INT         NOT NULL,
    `score`         INT         NOT NULL,
    `riddle1_id`    INT         NOT NULL,
    `riddle2_id`    INT         NOT NULL,
    `riddle3_id`    INT         NOT NULL,
    `createdAt`     DATETIME    NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (riddle1_id) REFERENCES riddles (riddle_id),
    FOREIGN KEY (riddle2_id) REFERENCES riddles (riddle_id),
    FOREIGN KEY (riddle3_id) REFERENCES riddles (riddle_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;