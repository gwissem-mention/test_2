-- phpMyAdmin SQL Dump
-- version 4.6.6deb4+deb9u1
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Mer 26 Avril 2023 à 16:22
-- Version du serveur :  10.1.26-MariaDB-0+deb9u1
-- Version de PHP :  7.0.33-0+deb9u9
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
--
-- Base de données :  `plainteinternet`
--
-- --------------------------------------------------------
--
-- Structure de la table `preplainte_historique`
--
CREATE TABLE `preplainte_historique` (
    `numero` varchar(11) NOT NULL,
    `nom` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `prenom` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `date_plainte` datetime NOT NULL,
    `flag` int(1) NOT NULL DEFAULT '0' COMMENT '0=dossier creer/1=dossier en traitement par le web service/2=dossier traiter par le web service',
    `xml` mediumblob NOT NULL,
    `unite_mail_actif` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `mail_departement_actif` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `contact_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `prejudice_objet` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
    `date_suppression` datetime DEFAULT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table historique des plaintes déposées';
