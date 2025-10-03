-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 03, 2025 at 12:45 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `biblihome`
--

-- --------------------------------------------------------

--
-- Table structure for table `abonnements`
--

CREATE TABLE `abonnements` (
  `id_abonnement` int NOT NULL,
  `abonnement_nom` varchar(20) NOT NULL,
  `abonnement_prix` decimal(10,2) NOT NULL,
  `abonnement_duree` varchar(20) NOT NULL,
  `abonnement_blurb` varchar(255) NOT NULL,
  `abonnement_desc` text NOT NULL,
  `abonnement_perks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auteurs`
--

CREATE TABLE `auteurs` (
  `id_auteur` int NOT NULL,
  `auteur_nom` varchar(100) NOT NULL,
  `auteur_prenom` varchar(100) DEFAULT NULL,
  `auteur_image` varchar(255) DEFAULT NULL,
  `auteur_nationalite` varchar(100) DEFAULT NULL,
  `auteur_biographie` text,
  `auteur_date_naissance` date DEFAULT NULL,
  `auteur_date_deces` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carnet_adresses`
--

CREATE TABLE `carnet_adresses` (
  `id_carnet_a` int NOT NULL,
  `carnet_a_nom` varchar(100) NOT NULL,
  `carnet_a_prenom` varchar(100) NOT NULL,
  `carnet_a_ligne_une` varchar(255) NOT NULL,
  `carnet_a_ligne_deux` varchar(255) DEFAULT NULL,
  `carnet_a_ville` varchar(255) NOT NULL,
  `carnet_a_cp` int NOT NULL,
  `carnet_a_bool_relais` tinyint(1) DEFAULT '0',
  `carnet_a_tel` varchar(20) NOT NULL,
  `id_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `civilites`
--

CREATE TABLE `civilites` (
  `id_civilite` int NOT NULL,
  `civilite_nom` varchar(20) NOT NULL,
  `civilite_label` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `civilites`
--

INSERT INTO `civilites` (`id_civilite`, `civilite_nom`, `civilite_label`) VALUES
(1, 'Homme', 'Monsieur'),
(2, 'Femme', 'Madame'),
(3, 'Autre', 'Mix');

-- --------------------------------------------------------

--
-- Table structure for table `emprunt`
--

CREATE TABLE `emprunt` (
  `id_emprunt` int NOT NULL,
  `emprunt_delais_retour` datetime DEFAULT NULL,
  `emprunt_date_emprunt` datetime DEFAULT CURRENT_TIMESTAMP,
  `emprunt_date_envoi` datetime DEFAULT NULL,
  `emprunt_date_reception` datetime DEFAULT NULL,
  `emprunt_date_retour` datetime DEFAULT NULL,
  `emprunt_num_envoi` varchar(50) DEFAULT NULL,
  `emprunt_num_retour` varchar(50) DEFAULT NULL,
  `emprunt_retour_particulier` tinyint(1) DEFAULT '0',
  `id_exemplaire` int NOT NULL,
  `id_carnet_a` int NOT NULL,
  `id_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exemplaires`
--

CREATE TABLE `exemplaires` (
  `id_exemplaire` int NOT NULL,
  `id_livre` int NOT NULL,
  `exemplaire_actif` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id_faq` int NOT NULL,
  `faq_question` text NOT NULL,
  `faq_reponse` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favoris`
--

CREATE TABLE `favoris` (
  `id_favoris` int NOT NULL,
  `id_livre` int NOT NULL,
  `id_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id_genre` int NOT NULL,
  `genre_tag` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id_genre`, `genre_tag`) VALUES
(1, 'Fantasy'),
(3, 'Test2'),
(4, 'Testing Again'),
(5, 'Lets make sure nothing broke'),
(6, 'test'),
(7, 'wee'),
(8, '=nouvweq'),
(10, 'etsttfre');

-- --------------------------------------------------------

--
-- Table structure for table `langues`
--

CREATE TABLE `langues` (
  `id_langue` int NOT NULL,
  `langue_nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `langues`
--

INSERT INTO `langues` (`id_langue`, `langue_nom`) VALUES
(1, 'Français'),
(2, 'Grolandais'),
(4, 'bingbingbong');

-- --------------------------------------------------------

--
-- Table structure for table `livres`
--

CREATE TABLE `livres` (
  `id_livre` int NOT NULL,
  `livre_titre` varchar(255) NOT NULL,
  `livre_isbn` varchar(20) NOT NULL,
  `livre_couverture` varchar(255) DEFAULT NULL,
  `livre_synopsis` text,
  `livre_editeur` varchar(100) DEFAULT NULL,
  `livre_date_publication` date DEFAULT NULL,
  `livre_date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_langue` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `livres`
--

INSERT INTO `livres` (`id_livre`, `livre_titre`, `livre_isbn`, `livre_couverture`, `livre_synopsis`, `livre_editeur`, `livre_date_publication`, `livre_date_creation`, `id_langue`) VALUES
(1, 'defeferfrgesf', 'efrtgbrfdg', NULL, NULL, NULL, NULL, '2025-10-02 01:28:50', 2),
(2, 'fu', 'helo', NULL, NULL, NULL, NULL, '2025-10-02 14:13:50', 2),
(3, 'éasdfvgbn', 'zefrgeg', NULL, 'zaergtefz', 'FU', NULL, '2025-10-02 14:17:00', 2),
(4, 'test of the test', '69420', NULL, 'never gonna give uyou up never gonna letè you down neverb gonna turn around and deseetr oiyou never gonna make you cry never goinna sa y goodbye never gonna tell a lie and hrut hyou fujdhfd jhfdjsd  gfdhakjzs ejklzhlikjfhqi zfehi zihf iau hefiu hi  jzef iojkazeh  odfji', 'hlpojfdj', '2025-10-02', '2025-10-03 10:59:55', 1);

-- --------------------------------------------------------

--
-- Table structure for table `livres_auteurs`
--

CREATE TABLE `livres_auteurs` (
  `id_livre_auteur` int NOT NULL,
  `id_livre` int NOT NULL,
  `id_auteur` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livres_genres`
--

CREATE TABLE `livres_genres` (
  `id_livre_genre` int NOT NULL,
  `id_livre` int NOT NULL,
  `id_genre` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `livres_genres`
--

INSERT INTO `livres_genres` (`id_livre_genre`, `id_livre`, `id_genre`) VALUES
(1, 3, 1),
(2, 4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `livres_series`
--

CREATE TABLE `livres_series` (
  `id_livre_serie` int NOT NULL,
  `id_livre` int NOT NULL,
  `id_serie` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `livres_series`
--

INSERT INTO `livres_series` (`id_livre_serie`, `id_livre`, `id_serie`) VALUES
(1, 3, 3),
(2, 4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id_role` int NOT NULL,
  `role_name` varchar(20) NOT NULL,
  `role_level` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id_role`, `role_name`, `role_level`) VALUES
(1, 'Modérateur', 50),
(2, 'Administrateur', 100);

-- --------------------------------------------------------

--
-- Table structure for table `series`
--

CREATE TABLE `series` (
  `id_serie` int NOT NULL,
  `serie_nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `series`
--

INSERT INTO `series` (`id_serie`, `serie_nom`) VALUES
(1, 'Lord of the Rings'),
(3, 'bingas'),
(4, 'vbinfdgshjdgh'),
(5, 'trttrgf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `user_nom` varchar(100) NOT NULL,
  `user_prenom` varchar(100) NOT NULL,
  `user_mail` varchar(255) NOT NULL,
  `user_mdp` varchar(255) NOT NULL,
  `user_num_employe` int DEFAULT NULL,
  `id_role` int NOT NULL,
  `user_date_naissance` datetime DEFAULT NULL,
  `user_date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_date_abonnement` datetime DEFAULT NULL,
  `user_img` varchar(255) DEFAULT NULL,
  `id_civilite` int NOT NULL,
  `id_abonnement` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `user_nom`, `user_prenom`, `user_mail`, `user_mdp`, `user_num_employe`, `id_role`, `user_date_naissance`, `user_date_creation`, `user_date_abonnement`, `user_img`, `id_civilite`, `id_abonnement`) VALUES
(1, 'Admin', 'Gddl', 'gddl@gddl.fr', '$argon2i$v=19$m=65536,t=4,p=1$ZzZsTUFJa2pZUEpvMXZnVw$AEZ0KtfX7E+CccNboaCtYE+uwpuVyTLJx2Us140SQis', NULL, 2, NULL, '2025-09-19 11:01:44', NULL, NULL, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abonnements`
--
ALTER TABLE `abonnements`
  ADD PRIMARY KEY (`id_abonnement`);

--
-- Indexes for table `auteurs`
--
ALTER TABLE `auteurs`
  ADD PRIMARY KEY (`id_auteur`);

--
-- Indexes for table `carnet_adresses`
--
ALTER TABLE `carnet_adresses`
  ADD PRIMARY KEY (`id_carnet_a`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `civilites`
--
ALTER TABLE `civilites`
  ADD PRIMARY KEY (`id_civilite`);

--
-- Indexes for table `emprunt`
--
ALTER TABLE `emprunt`
  ADD PRIMARY KEY (`id_emprunt`),
  ADD UNIQUE KEY `emprunt_num_envoi` (`emprunt_num_envoi`),
  ADD UNIQUE KEY `emprunt_num_retour` (`emprunt_num_retour`),
  ADD KEY `id_exemplaire` (`id_exemplaire`),
  ADD KEY `id_carnet_a` (`id_carnet_a`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `exemplaires`
--
ALTER TABLE `exemplaires`
  ADD PRIMARY KEY (`id_exemplaire`),
  ADD KEY `id_livre` (`id_livre`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id_faq`);

--
-- Indexes for table `favoris`
--
ALTER TABLE `favoris`
  ADD PRIMARY KEY (`id_favoris`),
  ADD KEY `id_livre` (`id_livre`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id_genre`);

--
-- Indexes for table `langues`
--
ALTER TABLE `langues`
  ADD PRIMARY KEY (`id_langue`);

--
-- Indexes for table `livres`
--
ALTER TABLE `livres`
  ADD PRIMARY KEY (`id_livre`),
  ADD UNIQUE KEY `livre_isbn` (`livre_isbn`),
  ADD KEY `id_langue` (`id_langue`);

--
-- Indexes for table `livres_auteurs`
--
ALTER TABLE `livres_auteurs`
  ADD PRIMARY KEY (`id_livre_auteur`),
  ADD KEY `id_livre` (`id_livre`),
  ADD KEY `id_auteur` (`id_auteur`);

--
-- Indexes for table `livres_genres`
--
ALTER TABLE `livres_genres`
  ADD PRIMARY KEY (`id_livre_genre`),
  ADD KEY `id_livre` (`id_livre`),
  ADD KEY `id_genre` (`id_genre`);

--
-- Indexes for table `livres_series`
--
ALTER TABLE `livres_series`
  ADD PRIMARY KEY (`id_livre_serie`),
  ADD KEY `id_livre` (`id_livre`),
  ADD KEY `id_serie` (`id_serie`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `series`
--
ALTER TABLE `series`
  ADD PRIMARY KEY (`id_serie`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_civilite` (`id_civilite`),
  ADD KEY `id_abonnement` (`id_abonnement`),
  ADD KEY `id_role` (`id_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abonnements`
--
ALTER TABLE `abonnements`
  MODIFY `id_abonnement` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auteurs`
--
ALTER TABLE `auteurs`
  MODIFY `id_auteur` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carnet_adresses`
--
ALTER TABLE `carnet_adresses`
  MODIFY `id_carnet_a` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `civilites`
--
ALTER TABLE `civilites`
  MODIFY `id_civilite` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `emprunt`
--
ALTER TABLE `emprunt`
  MODIFY `id_emprunt` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exemplaires`
--
ALTER TABLE `exemplaires`
  MODIFY `id_exemplaire` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id_faq` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favoris`
--
ALTER TABLE `favoris`
  MODIFY `id_favoris` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id_genre` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `langues`
--
ALTER TABLE `langues`
  MODIFY `id_langue` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `livres`
--
ALTER TABLE `livres`
  MODIFY `id_livre` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `livres_auteurs`
--
ALTER TABLE `livres_auteurs`
  MODIFY `id_livre_auteur` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livres_genres`
--
ALTER TABLE `livres_genres`
  MODIFY `id_livre_genre` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `livres_series`
--
ALTER TABLE `livres_series`
  MODIFY `id_livre_serie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id_role` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `series`
--
ALTER TABLE `series`
  MODIFY `id_serie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carnet_adresses`
--
ALTER TABLE `carnet_adresses`
  ADD CONSTRAINT `carnet_adresses_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `emprunt`
--
ALTER TABLE `emprunt`
  ADD CONSTRAINT `emprunt_ibfk_1` FOREIGN KEY (`id_exemplaire`) REFERENCES `exemplaires` (`id_exemplaire`),
  ADD CONSTRAINT `emprunt_ibfk_2` FOREIGN KEY (`id_carnet_a`) REFERENCES `carnet_adresses` (`id_carnet_a`),
  ADD CONSTRAINT `emprunt_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `exemplaires`
--
ALTER TABLE `exemplaires`
  ADD CONSTRAINT `exemplaires_ibfk_1` FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id_livre`);

--
-- Constraints for table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `favoris_ibfk_1` FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id_livre`),
  ADD CONSTRAINT `favoris_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `livres`
--
ALTER TABLE `livres`
  ADD CONSTRAINT `livres_ibfk_1` FOREIGN KEY (`id_langue`) REFERENCES `langues` (`id_langue`);

--
-- Constraints for table `livres_auteurs`
--
ALTER TABLE `livres_auteurs`
  ADD CONSTRAINT `livres_auteurs_ibfk_1` FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id_livre`),
  ADD CONSTRAINT `livres_auteurs_ibfk_2` FOREIGN KEY (`id_auteur`) REFERENCES `auteurs` (`id_auteur`);

--
-- Constraints for table `livres_genres`
--
ALTER TABLE `livres_genres`
  ADD CONSTRAINT `livres_genres_ibfk_1` FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id_livre`),
  ADD CONSTRAINT `livres_genres_ibfk_2` FOREIGN KEY (`id_genre`) REFERENCES `genres` (`id_genre`);

--
-- Constraints for table `livres_series`
--
ALTER TABLE `livres_series`
  ADD CONSTRAINT `livres_series_ibfk_1` FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id_livre`),
  ADD CONSTRAINT `livres_series_ibfk_2` FOREIGN KEY (`id_serie`) REFERENCES `series` (`id_serie`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_civilite`) REFERENCES `civilites` (`id_civilite`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`id_abonnement`) REFERENCES `abonnements` (`id_abonnement`),
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
