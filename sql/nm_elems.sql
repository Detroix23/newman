-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 01, 2025 at 12:19 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nm_elems`
--

-- --------------------------------------------------------

--
-- Table structure for table `all_index`
--

DROP TABLE IF EXISTS `all_index`;
CREATE TABLE IF NOT EXISTS `all_index` (
  `pid` int NOT NULL COMMENT 'Player id',
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Unique ID.',
  `default_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Automatic base name of the element. Must be unique and can serve to access data.',
  `type` enum('planet','system','quartile','galaxy') NOT NULL COMMENT 'Type of the stellar element.',
  `local_name` varchar(50) DEFAULT NULL COMMENT 'Player element name within it''s area. Can be e.g. multiple planets in different system with same name, as long as it''s not in the same system.',
  `global_name` varchar(50) DEFAULT NULL COMMENT 'Player element name in the whole save. Must be completely unique.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `default_name` (`default_name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Global table that store basic identification for all elements. Refere to corresponding table to see details of any element';

--
-- Dumping data for table `all_index`
--

INSERT INTO `all_index` (`pid`, `id`, `default_name`, `type`, `local_name`, `global_name`) VALUES
(0, 1, 'P00001', 'planet', NULL, NULL),
(0, 2, 'P00002', 'planet', 'Iron planet', 'Industria++'),
(0, 3, 'P00003', 'planet', 'Copper planet', '');

-- --------------------------------------------------------

--
-- Table structure for table `planet_info`
--

DROP TABLE IF EXISTS `planet_info`;
CREATE TABLE IF NOT EXISTS `planet_info` (
  `pid` int NOT NULL COMMENT 'Player id.',
  `planet_id` int NOT NULL COMMENT 'Foreign key pointing an existing planet in ''all_index''.',
  `builds` json DEFAULT NULL COMMENT 'JSON of all building and their current PRODUCTION.',
  `ressources` json DEFAULT NULL,
  `infos` json DEFAULT NULL,
  PRIMARY KEY (`planet_id`),
  KEY `planet_id` (`planet_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `planet_info`
--

INSERT INTO `planet_info` (`pid`, `planet_id`, `builds`, `ressources`, `infos`) VALUES
(0, 1, '{\"drillMk1\": [], \"factoryMk1\": []}', '{\"r1\": {\"iron_ingot\": 0, \"copper_ingot\": 0}, \"r2\": {\"armature\": []}, \"r3\": {\"drill_head_mk1\": 0}}', NULL),
(0, 3, '{\"drillMk1\": [], \"factoryMk1\": []}', '{\"r1\": {\"iron_ingot\": 0, \"copper_ingot\": 0}, \"r2\": {\"armature\": []}, \"r3\": {\"drill_head_mk1\": 0}}', NULL),
(0, 2, '{\"drillMk1\": [], \"factoryMk1\": []}', '{\"r1\": {\"iron_ingot\": 0, \"copper_ingot\": 0}, \"r2\": {\"armature\": []}, \"r3\": {\"drill_head_mk1\": 0}}', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
