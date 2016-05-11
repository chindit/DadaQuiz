--
-- Base de datos: `dadaquiz`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE PROCEDURE `count_points` (IN `id_quiz` INT)  BEGIN
    SET @pts = 0;
    SET @pts := (SELECT COUNT(id) FROM questions WHERE (questions.type = 'radio' OR questions.type = 'number') AND questions.quiz = id_quiz);
    SET @pts := @pts + (SELECT COUNT(reponses.id) FROM reponses LEFT JOIN questions ON questions.id = reponses.question WHERE questions.type = 'checkbox' AND reponses.correct = 1 AND questions.quiz = id_quiz);
    SELECT @pts;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `history`
--

CREATE TABLE IF NOT EXISTS `history` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz` smallint(5) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `score` smallint(5) UNSIGNED NOT NULL,
  `ip` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz` smallint(5) UNSIGNED NOT NULL,
  `question` varchar(250) NOT NULL,
  `explanation` text NOT NULL,
  `type` enum('radio','checkbox','number') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_question_quiz` (`quiz`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quiz`
--

CREATE TABLE IF NOT EXISTS `quiz` (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reponses`
--

CREATE TABLE IF NOT EXISTS `reponses` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `question` mediumint(8) UNSIGNED NOT NULL,
  `answer` varchar(100) NOT NULL,
  `correct` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_question_responses` (`question`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_question_quiz` FOREIGN KEY (`quiz`) REFERENCES `quiz` (`id`);

--
-- Filtros para la tabla `reponses`
--
ALTER TABLE `reponses`
  ADD CONSTRAINT `fk_question_responses` FOREIGN KEY (`question`) REFERENCES `questions` (`id`);

