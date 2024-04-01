-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mar. 26 mai 2020 à 15:37
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP :  7.4.1

--
-- Base de données :  `ubistock`
--

-- --------------------------------------------------------

--
-- Structure de la table `company`
--

CREATE TABLE `company` (
  `company_id` VARCHAR2(16) PRIMARY KEY NOT NULL,
  `company_name` text NOT NULL,

  `ceo_id` VARCHAR2(16) NOT NULL,
  `root_id` VARCHAR2(16) UNIQUE NOT NULL,

  FOREIGN KEY (ceo_id) REFERENCES company_user(company_user_id),
  FOREIGN KEY (root_id) REFERENCES storage(storage_id)
);

-- --------------------------------------------------------

--
-- Structure de la table `company_user`
--

CREATE TABLE `company_user` (
  `company_user_id` VARCHAR2(16) PRIMARY KEY NOT NULL,
  `company_user_name` text NOT NULL,
  `company_user_surname` text NOT NULL,
  `company_id` VARCHAR2(16) NOT NULL,

  `accreditation_level` INTEGER NOT NULL DEFAULT 0,
  `password_hash` text NOT NULL,
  `email` TEXT NOT NULL,

  UNIQUE(email),
  FOREIGN KEY (company_id) REFERENCES company(company_id)
);

-- --------------------------------------------------------

--
-- Structure de la table `log`
--

CREATE TABLE `company_log` (
  `company_log_id` VARCHAR2(16) PRIMARY KEY NOT NULL,
  `action` INTEGER NOT NULL,
  `company_user_id` VARCHAR2(16) NOT NULL,
  `target_id` TEXT NOT NULL,
  `target_name` TEXT NOT NULL,
  `quantity` INTEGER NOT NULL,
  `date` datetime NOT NULL,
  `reason` TEXT NOT NULL,
  `priority` tinyint(8) NOT NULL,

  FOREIGN KEY (company_user_id) REFERENCES company_user(company_user_id)
);

-- --------------------------------------------------------

--
-- Structure de la table `resource_minimum`
--

CREATE TABLE `resource_minimum` (
  `resource_id` VARCHAR(16) PRIMARY KEY NOT NULL,
  `minimum` INTEGER NOT NULL,

  FOREIGN KEY (resource_id) REFERENCES resource(resource_id)
);

CREATE TABLE `storage_minimum` (
  `storage_id` VARCHAR2(16) NOT NULL,
  `minimum` INTEGER NOT NULL,
  `resource_type` tinyint(8),
  `resource_name` text,

  FOREIGN KEY (storage_id) REFERENCES storage(storage_id),
  constraint pk PRIMARY KEY (storage_id, resource_name, resource_type)
);

-- --------------------------------------------------------

--
-- Structure de la table `storage`
--

CREATE TABLE `storage` (
  `storage_id` VARCHAR2(16) PRIMARY KEY NOT NULL,
  `storage_name` text NOT NULL,
  `family` TEXT NOT NULL,

  `company_id` VARCHAR2(16) NOT NULL,
  `root_id` VARCHAR2(16) NOT NULL,

  FOREIGN KEY (root_id) REFERENCES storage(storage_id),
  FOREIGN KEY (company_id) REFERENCES company(company_id)
);

-- --------------------------------------------------------

--
-- Structure de la table `resource`
--

CREATE TABLE `resource` (
  `resource_id` VARCHAR2(16) PRIMARY KEY NOT NULL,
  `resource_name` text NOT NULL,
  `resource_type` tinyint(8) NOT NULL,

  `storage_id` VARCHAR2(16) NOT NULL,
  `qty` INTEGER NOT NULL DEFAULT 1,

  FOREIGN KEY (storage_id) REFERENCES storage(storage_id)
);

-- --------------------------------------------------------

--
-- Structure de la table `company_group`
--

CREATE TABLE `company_group` (
  `company_group_id` VARCHAR2(16) PRIMARY KEY NOT NULL,
  `company_group_name` text NOT NULL,

  `company_id` VARCHAR2(16) NOT NULL,
  FOREIGN KEY (company_id) REFERENCES company(company_id)
);

-- --------------------------------------------------------

--
-- Structure de la table `company_group_member`
--

CREATE TABLE `company_group_member` (
  `company_group_id` VARCHAR2(16) NOT NULL,
  `company_user_id` VARCHAR2(16) NOT NULL,
  PRIMARY KEY(`company_group_id`, `company_user_id`),
  FOREIGN KEY (company_user_id) REFERENCES company_user(company_user_id),
  FOREIGN KEY (company_group_id) REFERENCES company_group(company_group_id)
);

-- --------------------------------------------------------

--
-- Structure de la table `company_group_storage`
--

CREATE TABLE `company_group_storage` (
  `company_group_id` VARCHAR2(16) NOT NULL,
  `storage_id` VARCHAR2(16) NOT NULL,

  FOREIGN KEY (storage_id) REFERENCES storage(storage_id),
  FOREIGN KEY (company_group_id) REFERENCES company_group(company_group_id),
  UNIQUE(`company_group_id`, `storage_id`)
);

CREATE TABLE `token` (
  `company_user_id` VARCHAR2(16) NOT NULL,
  `token_id` VARCHAR2(32) NOT NULL,
  `token_creation` datetime NOT NULL,

  FOREIGN KEY (company_user_id) REFERENCES company_user(company_user_id)  
);

-- --------------------------------------------------------