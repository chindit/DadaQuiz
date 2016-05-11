SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


DELIMITER $$
CREATE PROCEDURE `count_points` (IN `id_quiz` INT)  BEGIN
    SET @pts = 0;
    SET @pts := (SELECT COUNT(id) FROM questions WHERE (questions.type <> 'checkbox') AND questions.quiz = id_quiz);
    SET @pts := @pts + (SELECT COUNT(reponses.id) FROM reponses LEFT JOIN questions ON questions.id = reponses.question WHERE questions.type = 'checkbox' AND reponses.correct = 1 AND questions.quiz = id_quiz);
    SELECT @pts;
END$$

DELIMITER ;

CREATE TABLE IF NOT EXISTS `history` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz` smallint(5) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `score` smallint(5) UNSIGNED NOT NULL,
  `ip` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_history_quiz` (`quiz`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `questions` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz` smallint(5) UNSIGNED NOT NULL,
  `question` varchar(250) NOT NULL,
  `explanation` text NOT NULL,
  `type` enum('radio','checkbox','number','order') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_question_quiz` (`quiz`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `quiz` (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `reponses` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `question` mediumint(8) UNSIGNED NOT NULL,
  `answer` varchar(100) NOT NULL,
  `correct` tinyint(1) NOT NULL DEFAULT '0',
  `poids` tinyint(3) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_question_responses` (`question`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `history`
  ADD CONSTRAINT `fk_history_quiz` FOREIGN KEY (`quiz`) REFERENCES `quiz` (`id`);

ALTER TABLE `questions`
  ADD CONSTRAINT `fk_question_quiz` FOREIGN KEY (`quiz`) REFERENCES `quiz` (`id`);

ALTER TABLE `reponses`
  ADD CONSTRAINT `fk_question_responses` FOREIGN KEY (`question`) REFERENCES `questions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
