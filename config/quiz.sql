-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 04-05-2016 a las 23:12:50
-- Versión del servidor: 10.1.13-MariaDB
-- Versión de PHP: 7.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: dadaquiz
--
<br />
<b>Fatal error</b>:  Uncaught Error: Class 'PMA\libraries\plugins\export\SqlParser\Context' not found in /usr/share/webapps/phpMyAdmin/libraries/plugins/export/ExportSql.php:1559
Stack trace:
#0 /usr/share/webapps/phpMyAdmin/libraries/plugins/export/ExportSql.php(1992): PMA\libraries\plugins\export\ExportSql-&gt;getTableDef('dadaquiz', 'quiz', '\n', 'tbl_export.php?...', false, true, false, true, Array)
#1 /usr/share/webapps/phpMyAdmin/libraries/export.lib.php(840): PMA\libraries\plugins\export\ExportSql-&gt;exportStructure('dadaquiz', 'quiz', '\n', 'tbl_export.php?...', 'create_table', 'table', false, true, false, false, Array)
#2 /usr/share/webapps/phpMyAdmin/export.php(490): PMA_exportTable('dadaquiz', 'quiz', 'structure', Object(PMA\libraries\plugins\export\ExportSql), '\n', 'tbl_export.php?...', 'table', false, true, false, false, '1', 0, 0, '', Array)
#3 {main}
  thrown in <b>/usr/share/webapps/phpMyAdmin/libraries/plugins/export/ExportSql.php</b> on line <b>1559</b><br />
