-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 26 Février 2021 à 22:00
-- Version du serveur :  5.7.33-0ubuntu0.16.04.1
-- Version de PHP :  7.2.34-18+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `essence`
--

-- --------------------------------------------------------

--
-- Structure de la table `pdv`
--

CREATE TABLE `pdv` (
  `id` int(7) NOT NULL,
  `latitude` int(7) NOT NULL,
  `longitude` int(7) NOT NULL,
  `cp` int(5) NOT NULL,
  `pop` enum('A','R') NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `ouverture` text NOT NULL,
  `services` text NOT NULL,
  `rupture` text NOT NULL,
  `fermeture` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `prix`
--

CREATE TABLE `prix` (
  `id_pdv` int(7) NOT NULL,
  `id` int(1) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `maj` varchar(255) NOT NULL,
  `valeur` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `pdv`
--
ALTER TABLE `pdv`
  ADD UNIQUE KEY `id` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
