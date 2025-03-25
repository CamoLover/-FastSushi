-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : sushi_mysql:3306
-- Généré le : mar. 25 mars 2025 à 08:03
-- Version du serveur : 8.0.41
-- Version de PHP : 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sushi_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id_client` bigint UNSIGNED NOT NULL,
  `nom` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mdp` varchar(450) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cp` int DEFAULT NULL,
  `ville` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id_client`, `nom`, `prenom`, `email`, `tel`, `mdp`, `adresse`, `cp`, `ville`) VALUES
(1, 'Dupont', 'Jean', 'jean.dupont@example.com', '0612345678', '$2y$12$4cF93LAePtyXnGEo1wq.2ez3A5S.bBwrghUvE0I2PPE7VeoBKMqj.', '12 rue des Lilas', 75001, 'Paris'),
(2, 'Martin', 'Sophie', 'sophie.martin@example.com', '0623456789', '$2y$12$D0Dhr46mU9Icbe6gBHs4e.bYIzATU6wlSLBDeKCSdEZuA7SZtYGXq', '34 avenue Victor Hugo', 69002, 'Lyon'),
(3, 'Lemoine', 'Paul', 'paul.lemoine@example.com', '0623456789', '$2y$12$bxR2eXdTegMlQVAWx/.z5e9edgTcasmsVITAhHSjhgrI0IX01OaRG', '78 boulevard Haussmann', 75008, 'Paris'),
(4, 'Morel', 'Camille', 'camille.morel@example.com', '0645678901', '$2y$12$JZE7xXfrKte3Sy/NRUvThO0fCtbguRmSnngAfTdKLe8E6FcwV0BxS', '32 rue du Capitole', 31000, 'Toulouse'),
(5, 'Dubois', 'Alexandre', 'alexandre.dubois@example.com', '0656789012', '$2y$12$aTyi4SqzXFfR5BhVHYMHe.vOyP.3Vh1Ob79Tr6Xt2gkilVxPPGhvW', '14 avenue de Bretagne', 35000, 'Rennes'),
(6, 'jean', 'jak', 'jean.jack@gmail.com', '0000000000', '$2y$12$HYikJYxEIjmHtcDcF5GnjOdF6dbIfN1CQfVv.yXFyyljTbb2egMyC', NULL, NULL, NULL),
(7, 'aaa', 'bbb', 'aaa.bbb@gmail.com', '0629647665', '$2y$12$DOECfdm9oW4tDWW/Fy5ZQ.wYjtZQfCr6GhG34ojeHtMxsy6skOJlC', NULL, NULL, NULL),
(8, 'jean', 'jak', 'aa.aa@aa.aa', '0000000000', '$2y$12$QIHsjNnyXsddZ1NcEZ46keRY0lLFaL9UlbThbS3cjWxLM1VwwTiwK', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id_commande` bigint UNSIGNED NOT NULL,
  `id_client` bigint UNSIGNED NOT NULL,
  `date_panier` date NOT NULL,
  `montant_tot` decimal(10,3) DEFAULT NULL,
  `statut` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id_commande`, `id_client`, `date_panier`, `montant_tot`, `statut`) VALUES
(1, 1, '2023-10-01', 15.450, 'livré'),
(2, 1, '2023-10-05', 22.300, 'en cours'),
(3, 2, '2023-10-02', 18.750, 'livré'),
(4, 2, '2023-10-06', 12.600, 'en cours'),
(5, 3, '2023-10-03', 20.000, 'livré'),
(6, 3, '2023-10-07', 25.500, 'en cours'),
(7, 4, '2023-10-04', 14.850, 'livré'),
(8, 4, '2023-10-08', 19.900, 'en cours'),
(9, 5, '2023-10-05', 16.200, 'livré'),
(10, 5, '2023-10-09', 21.750, 'en cours'),
(11, 6, '2025-03-20', 35.080, 'En attente'),
(12, 6, '2025-03-20', 35.080, 'En attente'),
(13, 6, '2025-03-20', 155.050, 'En attente'),
(14, 7, '2025-03-20', 0.000, 'En attente'),
(15, 8, '2025-03-24', 0.000, 'En attente'),
(16, 8, '2025-03-24', 7.700, 'En attente');

-- --------------------------------------------------------

--
-- Structure de la table `commande_lignes`
--

CREATE TABLE `commande_lignes` (
  `id_commande_ligne` bigint UNSIGNED NOT NULL,
  `id_commande` bigint UNSIGNED NOT NULL,
  `id_produit` bigint UNSIGNED NOT NULL,
  `quantite` int NOT NULL,
  `nom` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix_ht` decimal(10,4) NOT NULL,
  `prix_ttc` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commande_lignes`
--

INSERT INTO `commande_lignes` (`id_commande_ligne`, `id_commande`, `id_produit`, `quantite`, `nom`, `prix_ht`, `prix_ttc`) VALUES
(1, 1, 1, 2, 'Salade Choux', 4.5000, 4.950),
(2, 1, 5, 1, 'Soupe Miso', 3.5000, 3.850),
(3, 2, 3, 1, 'Salade Fève de soja', 4.2000, 4.620),
(4, 2, 7, 3, 'Sushi Saumon', 8.0000, 8.800),
(5, 3, 2, 1, 'Salade Wakame', 5.0000, 5.500),
(6, 3, 6, 2, 'Soupe Ramen crevettes', 7.0000, 7.700),
(7, 4, 4, 1, 'Salade Crevettes', 6.0000, 6.600),
(8, 4, 8, 2, 'Sushi Thon', 8.5000, 9.350),
(9, 5, 9, 1, 'Sushi Crevettes', 7.5000, 8.250),
(10, 5, 10, 1, 'Sushi Daurade', 9.0000, 9.900),
(11, 6, 11, 1, 'Sushi Anguille', 10.0000, 11.000),
(12, 6, 12, 2, 'Makis', 6.0000, 6.600),
(13, 11, 15, 1, 'Spring Rolls personnalisé', 14.3000, 14.980),
(14, 11, 13, 1, 'Makis personnalisé', 19.5000, 20.100),
(15, 12, 15, 1, 'Spring Rolls personnalisé', 14.3000, 14.980),
(16, 12, 13, 1, 'Makis personnalisé', 19.5000, 20.100),
(17, 13, 1, 1, 'Salade Choux', 4.5000, 4.950),
(18, 13, 2, 1, 'Salade Wakame', 5.0000, 5.500),
(19, 13, 3, 1, 'Salade Fève de soja', 4.2000, 4.620),
(20, 13, 4, 1, 'Salade Crevettes', 6.0000, 6.600),
(21, 13, 5, 1, 'Soupe Miso', 3.5000, 3.850),
(22, 13, 6, 1, 'Soupe Ramen crevettes', 7.0000, 7.700),
(23, 13, 7, 1, 'Soupe Ramen Poulet', 6.5000, 7.150),
(24, 13, 8, 1, 'Sushi Saumon', 8.0000, 8.800),
(25, 13, 9, 1, 'Sushi Thon', 8.5000, 9.350),
(26, 13, 10, 1, 'Sushi Crevettes', 7.5000, 8.250),
(27, 13, 12, 1, 'Sushi Anguille', 10.0000, 11.000),
(28, 13, 11, 1, 'Sushi Daurade', 9.0000, 9.900),
(29, 13, 18, 1, 'Crispy Nutella pané', 6.0000, 6.600),
(30, 13, 17, 1, 'Maki Nutella banane', 5.5000, 6.050),
(31, 13, 16, 1, 'Moelleux Chocolat', 5.0000, 5.500),
(32, 13, 13, 1, 'Makis personnalisé', 11.5000, 12.100),
(33, 13, 14, 1, 'California Rolls personnalisé', 19.0000, 19.650),
(34, 13, 15, 1, 'Spring Rolls personnalisé', 16.8000, 17.480),
(35, 14, 6, 1, 'Soupe Ramen crevettes', 7.0000, 7.700),
(36, 14, 12, 1, 'Sushi Anguille', 10.0000, 11.000),
(37, 14, 15, 1, 'Spring Rolls personnalisé', 9.8000, 10.480),
(38, 15, 1, 1, 'Salade Choux', 4.5000, 4.950),
(39, 15, 7, 1, 'Soupe Ramen Poulet', 6.5000, 7.150),
(40, 15, 9, 1, 'Sushi Thon', 8.5000, 9.350),
(41, 16, 5, 2, 'Soupe Miso', 3.5000, 3.850),
(42, 16, 16, 15, 'Moelleux Chocolat', 5.0000, 5.500);

-- --------------------------------------------------------

--
-- Structure de la table `compo_commandes`
--

CREATE TABLE `compo_commandes` (
  `id_commande_ligne` bigint UNSIGNED NOT NULL,
  `id_ingredient` bigint UNSIGNED NOT NULL,
  `prix` decimal(10,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `compo_commandes`
--

INSERT INTO `compo_commandes` (`id_commande_ligne`, `id_ingredient`, `prix`) VALUES
(15, 1, 2.5000),
(15, 2, 1.0000),
(15, 5, 4.0000),
(16, 5, 4.0000),
(16, 6, 5.0000),
(16, 7, 4.5000),
(32, 1, 2.5000),
(32, 2, 1.0000),
(32, 3, 2.0000),
(33, 4, 3.5000),
(33, 5, 4.0000),
(33, 6, 5.0000),
(34, 7, 4.5000),
(34, 8, 2.5000),
(34, 9, 3.0000),
(37, 2, 1.0000),
(37, 3, 2.0000);

-- --------------------------------------------------------

--
-- Structure de la table `compo_paniers`
--

CREATE TABLE `compo_paniers` (
  `id_panier_ligne` bigint UNSIGNED NOT NULL,
  `id_ingredient` bigint UNSIGNED NOT NULL,
  `prix` decimal(10,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `employes`
--

CREATE TABLE `employes` (
  `id_employe` bigint UNSIGNED NOT NULL,
  `nom` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mdp` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut_emp` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `employes`
--

INSERT INTO `employes` (`id_employe`, `nom`, `prenom`, `email`, `mdp`, `statut_emp`) VALUES
(1, 'Bernard', 'Luc', 'luc.bernard@example.com', '$2y$12$u4DdfzJpts/MDCgL5BrPZelPngAsyv64gubPv9Rv6FCGq1PUSeyS6', 'Manager'),
(2, 'Moreau', 'Elise', 'elise.moreau@example.com', '$2y$12$u3WIvVkqYnldPItwnX2IO.2UfoY/h0HTTEE0ZTWkmnLIkqT0yIYaa', 'Assistant'),
(3, 'Rousseau', 'Nicolas', 'nicolas.rousseau@example.com', '$2y$12$Yk0zKEMAlUw6483AMf5PFeUqJjtNwtkpV9YqVQXF88mTRamZmJOBC', 'Technicien');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ingredients`
--

CREATE TABLE `ingredients` (
  `id_ingredient` bigint UNSIGNED NOT NULL,
  `nom` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prix_ht` decimal(10,4) NOT NULL,
  `type_ingredient` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ingredients`
--

INSERT INTO `ingredients` (`id_ingredient`, `nom`, `photo`, `prix_ht`, `type_ingredient`) VALUES
(1, 'Fromage', 'fromage.png', 2.5000, 'fromage'),
(2, 'Concombre', 'concombre.png', 1.0000, 'légume'),
(3, 'Avocat', 'avocat.png', 2.0000, 'fruit'),
(4, 'Thon', 'thon.png', 3.5000, 'poisson'),
(5, 'Saumon', 'saumon.png', 4.0000, 'poisson'),
(6, 'Crevettes', 'crevette.png', 5.0000, 'poisson'),
(7, 'Daurade', 'daurade.png', 4.5000, 'poisson'),
(8, 'Mangue', 'mangue.png', 2.5000, 'fruit'),
(9, 'Boursin', 'boursin.png', 3.0000, 'fromage');

-- --------------------------------------------------------

--
-- Structure de la table `ingredient_produit`
--

CREATE TABLE `ingredient_produit` (
  `id` bigint UNSIGNED NOT NULL,
  `ingredient_id` bigint UNSIGNED NOT NULL,
  `produit_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(3, '0001_01_01_000001_create_cache_table', 1),
(4, '0001_01_01_000002_create_jobs_table', 1),
(5, '2025_02_18_142649_create_clients_table', 1),
(6, '2025_02_18_142730_create_paniers_table', 1),
(7, '2025_02_18_142747_create_produits_table', 1),
(8, '2025_02_18_142806_create_commandes_table', 1),
(9, '2025_02_18_142826_create_panier_lignes_table', 1),
(10, '2025_02_18_142847_create_commande_lignes_table', 1),
(11, '2025_02_18_142902_create_ingredients_table', 1),
(12, '2025_02_18_142917_create_employes_table', 1),
(13, '2025_02_18_142929_create_compo_paniers_table', 1),
(14, '2025_02_18_143028_create_compo_commandes_table', 1),
(15, '2025_02_19_083501_create_ingredient_produit_table', 1),
(16, '2025_02_19_094728_populate_produit', 1),
(17, '2025_02_19_120652_populate_ingredient', 1),
(18, '2025_02_19_142058_populate_clients', 1),
(19, '2025_02_19_144040_populate_employes', 1),
(20, '2025_02_20_100904_populate_commande', 1),
(21, '2025_02_20_104729_populate_commande_ligne', 1),
(22, '2025_02_21_092440_create_sessions_table', 1),
(23, '2025_03_06_090305_populate_paniers', 1),
(25, '2024_03_21_000000_fix_compo_commandes_foreign_key_constraint', 2),
(26, '2025_03_06_105042_populate_panier_lignes', 2);

-- --------------------------------------------------------

--
-- Structure de la table `paniers`
--

CREATE TABLE `paniers` (
  `id_panier` bigint UNSIGNED NOT NULL,
  `id_session` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_client` bigint UNSIGNED DEFAULT NULL,
  `date_panier` date NOT NULL,
  `montant_tot` decimal(10,3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `paniers`
--

INSERT INTO `paniers` (`id_panier`, `id_session`, `id_client`, `date_panier`, `montant_tot`) VALUES
(1, 'session_123456', 1, '2025-03-20', 25.500),
(2, 'session_789012', 2, '2025-03-20', 40.750),
(3, 'session_345678', 3, '2025-03-20', 15.000),
(4, 'session_567864', 4, '2025-03-20', 45.000),
(5, 'session_376329', 5, '2025-03-20', 24.010),
(6, 'PafbnL6sT6YaG9CykdSN3TNsMoAjkM2hj8ah1Eyz', 6, '2025-03-20', 14.850),
(7, 'ufI5lBt7f20zWHYNy9RcWrBKCJLi44Tyb6nosnnX', 7, '2025-03-20', 0.000),
(8, 'XoDN0EkzJ7K3TZqLsvBnc6nrSNSLMdSt5BWeEXeS', 8, '2025-03-24', 108.900);

-- --------------------------------------------------------

--
-- Structure de la table `panier_lignes`
--

CREATE TABLE `panier_lignes` (
  `id_panier_ligne` bigint UNSIGNED NOT NULL,
  `id_panier` bigint UNSIGNED NOT NULL,
  `id_produit` bigint UNSIGNED NOT NULL,
  `quantite` int NOT NULL,
  `nom` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix_ht` decimal(10,4) NOT NULL,
  `prix_ttc` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `panier_lignes`
--

INSERT INTO `panier_lignes` (`id_panier_ligne`, `id_panier`, `id_produit`, `quantite`, `nom`, `prix_ht`, `prix_ttc`) VALUES
(1, 1, 1, 1, 'salade choux', 4.5000, 4.950),
(2, 2, 5, 1, 'Soupe Miso', 3.5000, 3.850),
(3, 3, 6, 1, 'Soupe Ramen crevettes', 7.0000, 7.700),
(4, 4, 8, 1, 'Sushi Saumon', 8.0000, 8.800),
(5, 5, 17, 1, 'Maki Nutella banane', 5.5000, 6.050),
(8, 1, 1, 1, 'salade choux', 4.5000, 4.950),
(9, 2, 5, 1, 'Soupe Miso', 3.5000, 3.850),
(10, 3, 6, 1, 'Soupe Ramen crevettes', 7.0000, 7.700),
(11, 4, 8, 1, 'Sushi Saumon', 8.0000, 8.800),
(12, 5, 17, 1, 'Maki Nutella banane', 5.5000, 6.050),
(31, 6, 12, 1, 'Sushi Anguille', 10.0000, 11.000),
(32, 6, 5, 1, 'Soupe Miso', 3.5000, 3.850),
(41, 8, 17, 18, 'Maki Nutella banane', 5.5000, 6.050);

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id_produit` bigint UNSIGNED NOT NULL,
  `nom` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_produit` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix_ttc` decimal(10,3) NOT NULL,
  `prix_ht` decimal(10,4) NOT NULL,
  `photo` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tva` decimal(10,4) NOT NULL,
  `description` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id_produit`, `nom`, `type_produit`, `prix_ttc`, `prix_ht`, `photo`, `tva`, `description`) VALUES
(1, 'Salade Choux', 'Entrée', 4.950, 4.5000, 'saladechoux.png', 0.1000, 'Délicieuse salade de choux assaisonnée avec une sauce légère et savoureuse.'),
(2, 'Salade Wakame', 'Entrée', 5.500, 5.0000, 'saladewakame.png', 0.1000, 'Salade d\'algues wakame marinées, riche en saveurs et en bienfaits nutritionnels.'),
(3, 'Salade Fève de soja', 'Entrée', 4.620, 4.2000, 'saladefevedesoja.png', 0.1000, 'Salade fraîche de fèves de soja avec une touche de sésame et de sauce soja.'),
(4, 'Salade Crevettes', 'Entrée', 6.600, 6.0000, 'saladecrevettejpg.png', 0.1000, 'Salade de crevettes croquantes accompagnée d\'une vinaigrette citronnée.'),
(5, 'Soupe Miso', 'Soupe', 3.850, 3.5000, 'soupemiso.png', 0.1000, 'Soupe japonaise traditionnelle à base de miso et de tofu.'),
(6, 'Soupe Ramen crevettes', 'Soupe', 7.700, 7.0000, 'ramencrevette.png', 0.1000, 'Ramen aux crevettes avec un bouillon riche et parfumé.'),
(7, 'Soupe Ramen Poulet', 'Soupe', 7.150, 6.5000, 'ramenpoulet.png', 0.1000, 'Ramen au poulet tendre dans un bouillon savoureux.'),
(8, 'Sushi Saumon', 'Plats', 8.800, 8.0000, 'sushisaumon.png', 0.1000, 'Sushi frais au saumon avec du riz vinaigré.'),
(9, 'Sushi Thon', 'Plats', 9.350, 8.5000, 'sushithon.png', 0.1000, 'Sushi savoureux au thon rouge.'),
(10, 'Sushi Crevettes', 'Plats', 8.250, 7.5000, 'sushicrevette.png', 0.1000, 'Sushi délicat aux crevettes décortiquées.'),
(11, 'Sushi Daurade', 'Plats', 9.900, 9.0000, 'sushidaurade.png', 0.1000, 'Sushi raffiné à la daurade tendre.'),
(12, 'Sushi Anguille', 'Plats', 11.000, 10.0000, 'sushianguille.png', 0.1000, 'Sushi unique à l\'anguille grillée et caramélisée.'),
(13, 'Makis', 'Customisation', 6.600, 6.0000, 'maki.png', 0.1000, 'Rouleaux de riz garnis d\'ingrédients frais et enroulés dans des feuilles de nori.'),
(14, 'California Rolls', 'Customisation', 7.150, 6.5000, 'californiarolls.png', 0.1000, 'Makis inversés garnis de crabe et d\'avocat.'),
(15, 'Spring Rolls', 'Customisation', 7.480, 6.8000, 'springrolls.png', 0.1000, 'Rouleaux de printemps frais et légers.'),
(16, 'Moelleux Chocolat', 'Desserts', 5.500, 5.0000, 'fondantchocolat.png', 0.1000, 'Gâteau fondant au chocolat avec un cœur coulant.'),
(17, 'Maki Nutella banane', 'Desserts', 6.050, 5.5000, 'makinutellabanane.png', 0.1000, 'Makis sucrés garnis de Nutella et de banane.'),
(18, 'Crispy Nutella pané', 'Desserts', 6.600, 6.0000, 'croustibananenutella.png', 0.1000, 'Délice croustillant au Nutella pané.');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('XoDN0EkzJ7K3TZqLsvBnc6nrSNSLMdSt5BWeEXeS', NULL, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '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', 1742810432);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `commandes_id_client_index` (`id_client`);

--
-- Index pour la table `commande_lignes`
--
ALTER TABLE `commande_lignes`
  ADD PRIMARY KEY (`id_commande_ligne`),
  ADD KEY `commande_lignes_id_commande_index` (`id_commande`),
  ADD KEY `commande_lignes_id_produit_index` (`id_produit`);

--
-- Index pour la table `compo_commandes`
--
ALTER TABLE `compo_commandes`
  ADD PRIMARY KEY (`id_commande_ligne`,`id_ingredient`),
  ADD KEY `compo_commandes_id_ingredient_index` (`id_ingredient`);

--
-- Index pour la table `compo_paniers`
--
ALTER TABLE `compo_paniers`
  ADD PRIMARY KEY (`id_panier_ligne`,`id_ingredient`),
  ADD KEY `compo_paniers_id_ingredient_index` (`id_ingredient`);

--
-- Index pour la table `employes`
--
ALTER TABLE `employes`
  ADD PRIMARY KEY (`id_employe`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id_ingredient`);

--
-- Index pour la table `ingredient_produit`
--
ALTER TABLE `ingredient_produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingredient_produit_ingredient_id_foreign` (`ingredient_id`),
  ADD KEY `ingredient_produit_produit_id_foreign` (`produit_id`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `paniers`
--
ALTER TABLE `paniers`
  ADD PRIMARY KEY (`id_panier`),
  ADD KEY `paniers_id_client_index` (`id_client`);

--
-- Index pour la table `panier_lignes`
--
ALTER TABLE `panier_lignes`
  ADD PRIMARY KEY (`id_panier_ligne`),
  ADD KEY `panier_lignes_id_panier_index` (`id_panier`),
  ADD KEY `panier_lignes_id_produit_index` (`id_produit`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id_produit`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id_commande` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `commande_lignes`
--
ALTER TABLE `commande_lignes`
  MODIFY `id_commande_ligne` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `employes`
--
ALTER TABLE `employes`
  MODIFY `id_employe` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id_ingredient` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `ingredient_produit`
--
ALTER TABLE `ingredient_produit`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `paniers`
--
ALTER TABLE `paniers`
  MODIFY `id_panier` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `panier_lignes`
--
ALTER TABLE `panier_lignes`
  MODIFY `id_panier_ligne` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id_produit` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_id_client_foreign` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`);

--
-- Contraintes pour la table `commande_lignes`
--
ALTER TABLE `commande_lignes`
  ADD CONSTRAINT `commande_lignes_id_commande_foreign` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`),
  ADD CONSTRAINT `commande_lignes_id_produit_foreign` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`);

--
-- Contraintes pour la table `compo_commandes`
--
ALTER TABLE `compo_commandes`
  ADD CONSTRAINT `compo_commandes_id_commande_ligne_foreign` FOREIGN KEY (`id_commande_ligne`) REFERENCES `commande_lignes` (`id_commande_ligne`) ON DELETE CASCADE,
  ADD CONSTRAINT `compo_commandes_id_ingredient_foreign` FOREIGN KEY (`id_ingredient`) REFERENCES `ingredients` (`id_ingredient`);

--
-- Contraintes pour la table `compo_paniers`
--
ALTER TABLE `compo_paniers`
  ADD CONSTRAINT `compo_paniers_id_ingredient_foreign` FOREIGN KEY (`id_ingredient`) REFERENCES `ingredients` (`id_ingredient`),
  ADD CONSTRAINT `compo_paniers_id_panier_ligne_foreign` FOREIGN KEY (`id_panier_ligne`) REFERENCES `panier_lignes` (`id_panier_ligne`);

--
-- Contraintes pour la table `ingredient_produit`
--
ALTER TABLE `ingredient_produit`
  ADD CONSTRAINT `ingredient_produit_ingredient_id_foreign` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id_ingredient`) ON DELETE CASCADE,
  ADD CONSTRAINT `ingredient_produit_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paniers`
--
ALTER TABLE `paniers`
  ADD CONSTRAINT `paniers_id_client_foreign` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`);

--
-- Contraintes pour la table `panier_lignes`
--
ALTER TABLE `panier_lignes`
  ADD CONSTRAINT `panier_lignes_id_panier_foreign` FOREIGN KEY (`id_panier`) REFERENCES `paniers` (`id_panier`),
  ADD CONSTRAINT `panier_lignes_id_produit_foreign` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
