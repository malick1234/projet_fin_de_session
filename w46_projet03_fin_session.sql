-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 07 mai 2024 à 03:18
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `w46_projet03_fin_session`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonces`
--

DROP TABLE IF EXISTS `annonces`;
CREATE TABLE IF NOT EXISTS `annonces` (
  `NoAnnonce` int NOT NULL AUTO_INCREMENT,
  `NoUtilisateur` int DEFAULT NULL,
  `Parution` datetime DEFAULT NULL,
  `Categorie` int DEFAULT NULL,
  `DescriptionAbregee` varchar(50) DEFAULT NULL,
  `DescriptionComplete` varchar(250) DEFAULT NULL,
  `Prix` decimal(7,2) DEFAULT NULL,
  `Photo` varchar(50) DEFAULT NULL,
  `MiseAJour` datetime DEFAULT NULL,
  `Etat` int DEFAULT NULL,
  PRIMARY KEY (`NoAnnonce`),
  KEY `NoUtilisateur` (`NoUtilisateur`),
  KEY `Categorie` (`Categorie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `NoCategorie` int NOT NULL AUTO_INCREMENT,
  `Description` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`NoCategorie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `connexions`
--

DROP TABLE IF EXISTS `connexions`;
CREATE TABLE IF NOT EXISTS `connexions` (
  `NoConnexion` int NOT NULL AUTO_INCREMENT,
  `NoUtilisateur` int DEFAULT NULL,
  `Connexion` datetime DEFAULT NULL,
  `Deconnexion` datetime DEFAULT NULL,
  PRIMARY KEY (`NoConnexion`),
  KEY `NoUtilisateur` (`NoUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `NoUtilisateur` int NOT NULL AUTO_INCREMENT,
  `Courriel` varchar(50) DEFAULT NULL,
  `MotDePasse` varchar(50) DEFAULT NULL,
  `Creation` datetime DEFAULT NULL,
  `NbConnexions` int DEFAULT NULL,
  `Statut` int DEFAULT NULL,
  `NoEmpl` int DEFAULT NULL,
  `Nom` varchar(25) DEFAULT NULL,
  `Prenom` varchar(20) DEFAULT NULL,
  `NoTelMaison` varchar(15) DEFAULT NULL,
  `NoTelTravail` varchar(21) DEFAULT NULL,
  `NoTelCellulaire` varchar(15) DEFAULT NULL,
  `Modification` datetime DEFAULT NULL,
  `AutresInfos` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`NoUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `annonces`
--
ALTER TABLE `annonces`
  ADD CONSTRAINT `annonces_ibfk_1` FOREIGN KEY (`NoUtilisateur`) REFERENCES `utilisateurs` (`NoUtilisateur`),
  ADD CONSTRAINT `annonces_ibfk_2` FOREIGN KEY (`Categorie`) REFERENCES `categories` (`NoCategorie`);

--
-- Contraintes pour la table `connexions`
--
ALTER TABLE `connexions`
  ADD CONSTRAINT `connexions_ibfk_1` FOREIGN KEY (`NoUtilisateur`) REFERENCES `utilisateurs` (`NoUtilisateur`);
COMMIT;

INSERT INTO `categories` (`NoCategorie`, `Description`) VALUES ('1', 'Location'), ('2', 'Recherche'), ('3', 'À donner'), ('4', 'À vendre'), ('5', 'Service offert'), ('6', 'Autre');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
