CREATE DATABASE IF NOT EXISTS `weba-te03-2026` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `weba-te03-2026`;

DROP TABLE IF EXISTS `exercise`;
DROP TABLE IF EXISTS `course`;

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `deadline` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `exercise` (
  `id` int(11) NOT NULL,
  `courseId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `finished` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `course`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `exercise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courseId` (`courseId`);

ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `exercise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `exercise`
  ADD CONSTRAINT `exercise_ibfk_1` FOREIGN KEY (`courseId`) REFERENCES `course` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/* *** *** *** Data *** *** *** */
INSERT INTO `course` (`name`, `deadline`) VALUE
	("Développement web PHP", "2025-06-22 23:59:59"),
	("Bases de données MySQL", null),
	("JavaScript interactif", "2025-04-01 00:00:00"),
	("Architecture MVC", "2025-05-25 16:35:00")
;

INSERT INTO `exercise` (courseId, name, description, finished) VALUE
	(1, "Créer la structure du MVC", "But : avoir une structure MVC pour organiser correctement le code de l'application.", 0),
	(2, "Modélisation", "Créer un modèle de données simple avec plusieurs tables liées.", 1),
	(2, "Requêtes SQL", "Écrire des requêtes SQL permettant d'insérer, modifier, supprimer et lire des données.", 1),
	(2, "Jointures", "Utiliser des jointures pour récupérer des données provenant de plusieurs tables.", 0),
	(3, "Manipulation du DOM", "Modifier dynamiquement le contenu d'une page web avec JavaScript.", 0),
	(3, "Événements", "Gérer au moins cinq événements utilisateur dans une interface web.", 1),
	(4, "Contrôleur", "Comprendre le rôle du contrôleur dans une architecture MVC.", 1),
	(4, "Modèle", "Comprendre le rôle du modèle et son lien avec la base de données.", 1)
;