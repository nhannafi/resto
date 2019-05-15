-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 18 Avril 2019 à 17:29
-- Version du serveur :  5.7.25-0ubuntu0.16.04.2
-- Version de PHP :  7.0.33-0ubuntu0.16.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `Resto_DB`
--

-- --------------------------------------------------------

--
-- Structure de la table `Commandes`
--

CREATE TABLE `Commandes` (
  `Id` int(11) UNSIGNED NOT NULL,
  `User_Id` int(11) UNSIGNED NOT NULL,
  `Date_Creation` datetime NOT NULL,
  `Date_Livraison` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `detailsCommande`
--

CREATE TABLE `detailsCommande` (
  `id` int(11) UNSIGNED NOT NULL,
  `Commande_Id` int(11) UNSIGNED NOT NULL,
  `Menu_Id` int(11) UNSIGNED NOT NULL,
  `Quantite` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Menus`
--

CREATE TABLE `Menus` (
  `Id` int(4) UNSIGNED NOT NULL,
  `Titre` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `Date_Debut` datetime NOT NULL,
  `Date_Fin` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Plats`
--

CREATE TABLE `Plats` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Titre` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `Description` text COLLATE utf8_unicode_ci NOT NULL,
  `Prix_Reviens` float NOT NULL,
  `Prix_Vente` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Plats_Menus`
--

CREATE TABLE `Plats_Menus` (
  `Plats_Id` int(11) UNSIGNED NOT NULL,
  `Menu_Id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Reservation`
--

CREATE TABLE `Reservation` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Nbr_Sieges` int(11) NOT NULL,
  `User_Id` int(11) UNSIGNED NOT NULL,
  `Date_Reserv` datetime NOT NULL,
  `Service` tinytext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Reviews`
--

CREATE TABLE `Reviews` (
  `Id` int(11) UNSIGNED NOT NULL,
  `User_Id` int(11) UNSIGNED NOT NULL,
  `Menu_id` int(11) UNSIGNED NOT NULL,
  `Titre` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `Contenue` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `Note` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

CREATE TABLE `Users` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Prenom` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `Nom` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Telephone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `Num_Rue` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `Rue` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `Ville` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `Code_Postal` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `Admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `Users`
--

INSERT INTO `Users` (`Id`, `Prenom`, `Nom`, `email`, `Telephone`, `Num_Rue`, `Rue`, `Ville`, `Code_Postal`, `Admin`) VALUES
(1, 'Ahmed', 'chaieb', 'ahmed.chaieb@gmail.com', '06.25.26.24.22', '101', 'despetits', 'Paris', '75013', 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `Commandes`
--
ALTER TABLE `Commandes`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `User_Id` (`User_Id`);

--
-- Index pour la table `detailsCommande`
--
ALTER TABLE `detailsCommande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Menu_Id` (`Menu_Id`),
  ADD KEY `Commande_Id` (`Commande_Id`);

--
-- Index pour la table `Menus`
--
ALTER TABLE `Menus`
  ADD PRIMARY KEY (`Id`);

--
-- Index pour la table `Plats`
--
ALTER TABLE `Plats`
  ADD PRIMARY KEY (`Id`);

--
-- Index pour la table `Plats_Menus`
--
ALTER TABLE `Plats_Menus`
  ADD KEY `Plats_Id` (`Plats_Id`),
  ADD KEY `Menu_Id` (`Menu_Id`);

--
-- Index pour la table `Reservation`
--
ALTER TABLE `Reservation`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `User_Id` (`User_Id`);

--
-- Index pour la table `Reviews`
--
ALTER TABLE `Reviews`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `User_Id` (`User_Id`),
  ADD KEY `Menu_id` (`Menu_id`);

--
-- Index pour la table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `Commandes`
--
ALTER TABLE `Commandes`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `detailsCommande`
--
ALTER TABLE `detailsCommande`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `Menus`
--
ALTER TABLE `Menus`
  MODIFY `Id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `Plats`
--
ALTER TABLE `Plats`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `Reservation`
--
ALTER TABLE `Reservation`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `Reviews`
--
ALTER TABLE `Reviews`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `Users`
--
ALTER TABLE `Users`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
