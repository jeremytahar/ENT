-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Hôte : db5016949224.hosting-data.io
-- Généré le : ven. 10 jan. 2025 à 22:16
-- Version du serveur : 8.0.36
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dbs13664814`
--

-- --------------------------------------------------------

--
-- Structure de la table `absence`
--

CREATE TABLE `absence` (
  `id_absence` int NOT NULL,
  `id_etudiant` int NOT NULL,
  `date` date NOT NULL,
  `debut` time NOT NULL,
  `fin` time NOT NULL,
  `duree` time GENERATED ALWAYS AS (sec_to_time((((time_to_sec(timediff(`fin`,`debut`)) - (case when ((`debut` <= '10:15:00') and (`fin` >= '10:30:00')) then time_to_sec(_utf8mb4'00:15:00') else 0 end)) - (case when ((`debut` <= '12:30:00') and (`fin` >= '13:30:00')) then time_to_sec(_utf8mb4'01:00:00') else 0 end)) - (case when ((`debut` <= '15:30:00') and (`fin` >= '15:45:00')) then time_to_sec(_utf8mb4'00:15:00') else 0 end)))) STORED,
  `motif` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `absence`
--

INSERT INTO `absence` (`id_absence`, `id_etudiant`, `date`, `debut`, `fin`, `motif`) VALUES
(1, 1, '2024-12-21', '10:30:00', '12:30:00', 'Malade'),
(2, 1, '2024-12-12', '08:15:00', '17:45:00', ''),
(3, 1, '2025-01-01', '10:30:00', '15:30:00', '');

-- --------------------------------------------------------

--
-- Structure de la table `cours_module`
--

CREATE TABLE `cours_module` (
  `id_cours` int NOT NULL,
  `id_module` int NOT NULL,
  `nom_fichier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `cours_module`
--

INSERT INTO `cours_module` (`id_cours`, `id_module`, `nom_fichier`) VALUES
(1, 5, 'tailles-devices-2024.pdf'),
(2, 5, 'bootstrap-presentation-2024.pdf');

-- --------------------------------------------------------

--
-- Structure de la table `devoir`
--

CREATE TABLE `devoir` (
  `id_devoir` int NOT NULL,
  `id_module` int NOT NULL,
  `titre` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` datetime NOT NULL,
  `type` enum('evaluation','depot') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `note_max` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `devoir`
--

INSERT INTO `devoir` (`id_devoir`, `id_module`, `titre`, `date`, `type`, `note_max`) VALUES
(1, 5, 'Portfolio S3', '2024-12-31 12:00:00', 'depot', 20),
(2, 2, 'Miniblog', '2024-11-30 23:55:00', 'depot', 20),
(3, 3, 'Tracking', '2024-12-25 23:55:00', 'depot', 20),
(4, 5, 'QCM #3', '2025-01-15 15:00:00', 'evaluation', 15),
(5, 5, 'TP TEST #1', '2025-01-15 23:59:00', 'depot', 20),
(6, 5, 'QCM #4', '2025-01-18 00:00:00', 'evaluation', 20),
(9, 5, 'QCM #5', '2025-01-30 00:00:00', 'evaluation', 20);

-- --------------------------------------------------------

--
-- Structure de la table `etudiant_module`
--

CREATE TABLE `etudiant_module` (
  `id_etudiant` int NOT NULL,
  `id_module` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `etudiant_module`
--

INSERT INTO `etudiant_module` (`id_etudiant`, `id_module`) VALUES
(1, 1),
(32, 1),
(1, 2),
(32, 2),
(1, 3),
(32, 3),
(1, 4),
(32, 4),
(1, 5),
(32, 5),
(1, 6),
(32, 6),
(1, 7),
(32, 7),
(1, 8),
(32, 8);

-- --------------------------------------------------------

--
-- Structure de la table `module`
--

CREATE TABLE `module` (
  `id_module` int NOT NULL,
  `titre` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_professeur` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `module`
--

INSERT INTO `module` (`id_module`, `titre`, `id_professeur`) VALUES
(1, 'BUT MMI Pédagogique', 13),
(2, 'Développement Web Back', 2),
(3, 'Audiovisuel & Motion Design', 3),
(4, 'Déploiement de services', 9),
(5, 'Intégration Web', 1),
(6, 'Développement Javascript', 11),
(7, 'Communication & Marketing', 13),
(8, 'Anglais', 14);

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

CREATE TABLE `note` (
  `id_note` int NOT NULL,
  `id_etudiant` int NOT NULL,
  `id_module` int NOT NULL,
  `id_devoir` int NOT NULL,
  `date_note` date NOT NULL,
  `note` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `note`
--

INSERT INTO `note` (`id_note`, `id_etudiant`, `id_module`, `id_devoir`, `date_note`, `note`) VALUES
(10, 1, 5, 1, '2024-12-31', 17),
(13, 32, 5, 1, '2024-12-31', 20),
(14, 1, 5, 5, '2025-01-15', 20),
(15, 32, 5, 5, '2025-01-15', 19),
(16, 1, 5, 4, '2025-01-15', 15),
(17, 32, 5, 4, '2025-01-15', 14),
(18, 1, 5, 6, '2025-01-18', 14),
(19, 32, 5, 6, '2025-01-18', 18),
(20, 1, 5, 9, '2025-01-30', 20),
(21, 32, 5, 9, '2025-01-30', 20),
(22, 1, 3, 3, '2024-12-25', 20),
(23, 32, 3, 3, '2024-12-25', 20),
(24, 1, 2, 2, '2024-11-30', 15),
(25, 32, 2, 2, '2024-11-30', 17),
(26, 1, 5, 1, '2024-12-31', 17);

-- --------------------------------------------------------

--
-- Structure de la table `professeur_module`
--

CREATE TABLE `professeur_module` (
  `id_professeur` int NOT NULL,
  `id_module` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `professeur_module`
--

INSERT INTO `professeur_module` (`id_professeur`, `id_module`) VALUES
(10, 1),
(3, 2),
(4, 3),
(6, 4),
(2, 5),
(8, 6),
(10, 7),
(11, 8);

-- --------------------------------------------------------

--
-- Structure de la table `reinitialisation_mot_de_passe`
--

CREATE TABLE `reinitialisation_mot_de_passe` (
  `id` int NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `expiration` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int NOT NULL,
  `nom` varchar(70) NOT NULL,
  `prenom` varchar(70) NOT NULL,
  `login` varchar(70) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `discord` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `role` enum('etudiant','professeur') NOT NULL,
  `formation` enum('BUT MMI 1','BUT MMI 2','BUT MMI 3') DEFAULT NULL,
  `parcours` enum('Développement Web et Dispositifs Interactifs','Création Numérique') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `login`, `password`, `email`, `discord`, `role`, `formation`, `parcours`) VALUES
(1, 'Tahar', 'Jérémy', 'jeremy.tahar', '$2y$10$sHJGn7jdnt3BzAGf2/bRVugSwZ97g.jwS28I.ULvSSclv0fycoCMW', 'jeremy.tahar@edu.univ-eiffel.fr', NULL, 'etudiant', 'BUT MMI 2', 'Développement Web et Dispositifs Interactifs'),
(2, 'Charpentier', 'Gaëlle', 'gaelle.charpentier', '$2y$10$oF7IOyH3HhiHKqZ9OpoCnelhOcjbQZBzbXLmw5ANFdBOsZzdkl3Lq', 'gaelle.charpentier@univ-eiffel.fr', 'gaellecharpentier', 'professeur', NULL, NULL),
(3, 'Eppstein', 'Renaud', 'renaud.eppstein', '$2y$10$kK3R3Cp63r9WW5UopF5HCOq6OyIixBNUN2MRZkRTYzzk26.mTV.hm', 'renaud.eppstein@univ-eiffel.fr', 'renaud_e', 'professeur', NULL, NULL),
(4, 'Tasso', 'Anne', 'anne.tasso', '$2y$10$.cAKSII/IJ64sViySj10ROTPBI5XgTh5udRb9UQnFR47ATIUzQJPG', 'anne.tasso@univ-eiffel.fr', 'annetee', 'professeur', NULL, NULL),
(5, 'Aurouet', 'Carole', 'carole.aurouet ', '$2y$10$2Et9us2AoGOkyj84mgbrN.V5JthF6w4n5A53zEfy8/hpbzHLy9eay', 'carole.aurouet@univ-eiffel.fr', 'caroleaurouet_39147', 'professeur', NULL, NULL),
(6, 'Berthet', 'Matthieu', 'matthieu.berthet', '$2y$10$G/Jr4.3MBhkAjHwb2xymZO2IbiF6fCZgC6zgY3D8Yr6doezGdQ/Ou', 'matthieu.berthet@univ-eiffel.fr', 'mberthet.', 'professeur', NULL, NULL),
(7, 'Charpentier', 'Alexis', 'alexis.charpentier', '$2y$10$J4S.6m1adgmCJwByQ6QOHelJ8pUpczafHt8WVD16xz.r8ItYG8Zky', 'alexis.charpentier@edu.univ-eiffel.fr', 'alexischarp', 'professeur', NULL, NULL),
(8, 'Gambette', 'Philippe', 'philippe.gambette', '$2y$10$7GbjQQIf/rIESDGWfQamfebKwSP09zhFXOZjnSrb3se8sWmfZs4Um', 'philippe.gambette@univ-eiffel.fr', 'philippegambette', 'professeur', NULL, NULL),
(9, 'Houziaux', 'Tony', 'tony.houziaux', '$2y$10$9cehEYtjNJ3bHS92J1.Cu.EnePppEPS.Qr.bAsXJs3gl2f9bfuNzC', 'tony.houziaux@univ-eiffel.fr', 'tonyhouziaux', 'professeur', NULL, NULL),
(10, 'Jaoued', 'Leyla', 'leyla.jaoued', '$2y$10$aaRi181C3c.JSRnbdRTh5.azovRvBMJIlkEEjTQqdPZLhk0cVx47a', 'leyla.jaoued@univ-eiffel.fr', 'leyla.jaoued', 'professeur', NULL, NULL),
(11, 'Leroy', 'Alexandre', 'alexandre.leroy', '$2y$10$AjxgPhTThpILpbJEGpsjrerEXJDZBYn6isfdPP/s4QT1NsRhN5nRK', 'alexandre.leroy@univ-eiffel.fr', 'alexandreleroy', 'professeur', NULL, NULL),
(12, 'Lo', 'Hervé', 'herve.lo', '$2y$10$Aa9B5L3ci2q2ALM7xdlIsujgnuxU7ZhY2hQpL/3eFYE/h3lpmPBYu', 'herve.lo.prof@gmail.com', 'hervelo', 'professeur', NULL, NULL),
(13, 'Niel', 'Odile', 'odile.niel', '$2y$10$w2nUfqDmFsKdNP960iyZeOLVPSc02ADHzkZknOCOvCUxPbLLVHaSW', 'odile.niel@univ-eiffel.fr', 'odileniel', 'professeur', NULL, NULL),
(14, 'Poisson', 'Frederic', 'frederic.poisson', '$2y$10$uDM5zdbv4Wz6jPrzsnR4fuoVPEvK2dkaP2YbOAoVX2cen1xJOKPrO', 'frederic.poisson@univ-eiffel.fr', 'misterfish2904', 'professeur', NULL, NULL),
(15, 'Bister', 'Florence', 'florence.bister', '$2y$10$gnppdMXD5UDxBWrG8aYoF./VSFl14ZJqqM7HT1OLAEo9etVpsY32S', 'florence.bister@univ-eiffel.fr', 'florencebister', 'professeur', NULL, NULL),
(32, 'Gonçalves', 'Eve', 'eve.goncalves', '$2y$10$Oz358zmm1fx0Y64bZmsf4e.Xib1vvWLEBfF/SpqbVKFjeLXfIlUkC', 'eve.goncalves@edu.univ-eiffel.fr', NULL, 'etudiant', 'BUT MMI 2', 'Création Numérique');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `absence`
--
ALTER TABLE `absence`
  ADD PRIMARY KEY (`id_absence`),
  ADD KEY `id_etudiant` (`id_etudiant`);

--
-- Index pour la table `cours_module`
--
ALTER TABLE `cours_module`
  ADD PRIMARY KEY (`id_cours`),
  ADD KEY `id_module` (`id_module`);

--
-- Index pour la table `devoir`
--
ALTER TABLE `devoir`
  ADD PRIMARY KEY (`id_devoir`),
  ADD KEY `devoir_ibfk_1` (`id_module`);

--
-- Index pour la table `etudiant_module`
--
ALTER TABLE `etudiant_module`
  ADD PRIMARY KEY (`id_etudiant`,`id_module`),
  ADD KEY `etudiant_module_ibfk_2` (`id_module`);

--
-- Index pour la table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id_module`),
  ADD KEY `id_professeur` (`id_professeur`);

--
-- Index pour la table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`id_note`),
  ADD KEY `id_etudiant` (`id_etudiant`),
  ADD KEY `id_module` (`id_module`),
  ADD KEY `id_devoir` (`id_devoir`);

--
-- Index pour la table `professeur_module`
--
ALTER TABLE `professeur_module`
  ADD PRIMARY KEY (`id_professeur`,`id_module`),
  ADD KEY `professeur_module_ibfk_2` (`id_module`);

--
-- Index pour la table `reinitialisation_mot_de_passe`
--
ALTER TABLE `reinitialisation_mot_de_passe`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `absence`
--
ALTER TABLE `absence`
  MODIFY `id_absence` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `cours_module`
--
ALTER TABLE `cours_module`
  MODIFY `id_cours` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `devoir`
--
ALTER TABLE `devoir`
  MODIFY `id_devoir` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `module`
--
ALTER TABLE `module`
  MODIFY `id_module` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `note`
--
ALTER TABLE `note`
  MODIFY `id_note` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `reinitialisation_mot_de_passe`
--
ALTER TABLE `reinitialisation_mot_de_passe`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `absence`
--
ALTER TABLE `absence`
  ADD CONSTRAINT `absence_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cours_module`
--
ALTER TABLE `cours_module`
  ADD CONSTRAINT `cours_module_ibfk_1` FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `devoir`
--
ALTER TABLE `devoir`
  ADD CONSTRAINT `devoir_ibfk_1` FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `etudiant_module`
--
ALTER TABLE `etudiant_module`
  ADD CONSTRAINT `etudiant_module_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `etudiant_module_ibfk_2` FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `module`
--
ALTER TABLE `module`
  ADD CONSTRAINT `module_ibfk_1` FOREIGN KEY (`id_professeur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `note_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `note_ibfk_2` FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `note_ibfk_3` FOREIGN KEY (`id_devoir`) REFERENCES `devoir` (`id_devoir`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `professeur_module`
--
ALTER TABLE `professeur_module`
  ADD CONSTRAINT `professeur_module_ibfk_1` FOREIGN KEY (`id_professeur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `professeur_module_ibfk_2` FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
