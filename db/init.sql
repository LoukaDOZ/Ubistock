-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  jeu. 25 juin 2020 à 16:19
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP :  7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `ubistock`
--

-- --------------------------------------------------------

--
-- Structure de la table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `company_id` varchar(16) NOT NULL,
  `company_name` text NOT NULL,
  `ceo_id` varchar(16) NOT NULL,
  `root_id` varchar(16) NOT NULL,
  PRIMARY KEY (`company_id`),
  UNIQUE KEY `root_id` (`root_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `company_group`
--

DROP TABLE IF EXISTS `company_group`;
CREATE TABLE `company_group` (
  `company_group_id` varchar(16) NOT NULL,
  `company_group_name` text NOT NULL,
  `company_id` varchar(16) NOT NULL,
  PRIMARY KEY (`company_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `company_group_member`
--

DROP TABLE IF EXISTS `company_group_member`;
CREATE TABLE `company_group_member` (
  `company_group_id` varchar(16) NOT NULL,
  `company_user_id` varchar(16) NOT NULL,
  PRIMARY KEY (`company_group_id`,`company_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `company_group_storage`
--

DROP TABLE IF EXISTS `company_group_storage`;
CREATE TABLE `company_group_storage` (
  `company_group_id` varchar(16) NOT NULL,
  `storage_id` varchar(16) NOT NULL,
  UNIQUE KEY `company_group_id` (`company_group_id`,`storage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `company_log`
--

DROP TABLE IF EXISTS `company_log`;
CREATE TABLE `company_log` (
  `company_log_id` varchar(16) NOT NULL,
  `action` int(11) NOT NULL,
  `company_user_id` varchar(16) NOT NULL,
  `target_id` text NOT NULL,
  `target_name` text NOT NULL,
  `quantity` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `reason` text NOT NULL,
  `priority` tinyint(8) NOT NULL,
  PRIMARY KEY (`company_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `company_user`
--

DROP TABLE IF EXISTS `company_user`;
CREATE TABLE `company_user` (
  `company_user_id` varchar(16) NOT NULL,
  `company_user_name` text NOT NULL,
  `company_user_surname` text NOT NULL,
  `company_id` varchar(16) NOT NULL,
  `accreditation_level` int(11) NOT NULL DEFAULT 0,
  `password_hash` text NOT NULL,
  `email` varchar(32) NOT NULL,
  PRIMARY KEY (`company_user_id`),
  UNIQUE KEY `email` (`email`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `resource`
--

DROP TABLE IF EXISTS `resource`;
CREATE TABLE `resource` (
  `resource_id` varchar(16) NOT NULL,
  `resource_name` varchar(64) NOT NULL,
  `resource_type` varchar(16) NOT NULL,
  `storage_id` varchar(16) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `resource_minimum`
--

DROP TABLE IF EXISTS `resource_minimum`;
CREATE TABLE `resource_minimum` (
  `resource_id` varchar(16) NOT NULL,
  `minimum` int(11) NOT NULL,
  PRIMARY KEY (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `storage`
--

DROP TABLE IF EXISTS `storage`;
CREATE TABLE `storage` (
  `storage_id` varchar(16) NOT NULL,
  `storage_name` text NOT NULL,
  `family` text NOT NULL,
  `company_id` varchar(16) NOT NULL,
  `root_id` varchar(16) NOT NULL,
  PRIMARY KEY (`storage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `storage_minimum`
--

DROP TABLE IF EXISTS `storage_minimum`;
CREATE TABLE `storage_minimum` (
  `storage_id` varchar(16) NOT NULL,
  `minimum` int(11) NOT NULL,
  `resource_type` varchar(16),
  `resource_name` varchar(64),
  UNIQUE KEY `storage_id` (`storage_id`, `resource_name`, `resource_type`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `token`
--

DROP TABLE IF EXISTS `token`;
CREATE TABLE `token` (
  `company_user_id` varchar(16) NOT NULL,
  `token_id` varchar(32) NOT NULL,
  `token_creation` datetime NOT NULL,
  PRIMARY KEY (`token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `company_group`
--
ALTER TABLE `company_group`
  ADD CONSTRAINT `company_group_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`);

--
-- Contraintes pour la table `company_group_member`
--
ALTER TABLE `company_group_member`
  ADD CONSTRAINT `company_group_member_ibfk_1` FOREIGN KEY (`company_user_id`) REFERENCES `company_user` (`company_user_id`),
  ADD CONSTRAINT `company_group_member_ibfk_2` FOREIGN KEY (`company_group_id`) REFERENCES `company_group` (`company_group_id`);

--
-- Contraintes pour la table `company_group_storage`
--
ALTER TABLE `company_group_storage`
  ADD CONSTRAINT `company_group_storage_ibfk_1` FOREIGN KEY (`storage_id`) REFERENCES `storage` (`storage_id`),
  ADD CONSTRAINT `company_group_storage_ibfk_2` FOREIGN KEY (`company_group_id`) REFERENCES `company_group` (`company_group_id`);

--
-- Contraintes pour la table `company_log`
--
ALTER TABLE `company_log`
  ADD CONSTRAINT `company_log_ibfk_1` FOREIGN KEY (`company_user_id`) REFERENCES `company_user` (`company_user_id`);

--
-- Contraintes pour la table `company_user`
--
ALTER TABLE `company_user`
  ADD CONSTRAINT `company_user_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`);

--
-- Contraintes pour la table `resource`
--
ALTER TABLE `resource`
  ADD CONSTRAINT `resource_ibfk_1` FOREIGN KEY (`storage_id`) REFERENCES `storage` (`storage_id`);

--
-- Contraintes pour la table `resource_minimum`
--
ALTER TABLE `resource_minimum`
  ADD CONSTRAINT `resource_minimum_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`resource_id`);

--
-- Contraintes pour la table `storage`
--
ALTER TABLE `storage`
  ADD CONSTRAINT `root_id` FOREIGN KEY (`root_id`) REFERENCES `storage` (`storage_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `storage_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`);

--
-- Contraintes pour la table `storage_minimum`
--
ALTER TABLE `storage_minimum`
  ADD CONSTRAINT `storage_id` FOREIGN KEY (`storage_id`) REFERENCES `storage` (`storage_id`);

--
-- Contraintes pour la table `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `token_ibfk_1` FOREIGN KEY (`company_user_id`) REFERENCES `company_user` (`company_user_id`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
