-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : ven. 06 fév. 2026 à 14:50
-- Version du serveur : 8.0.44
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `BLOGART26`
--

-- --------------------------------------------------------

--
-- Structure de la table `ARTICLE`
--

CREATE TABLE `ARTICLE` (
  `numArt` int NOT NULL,
  `dtCreaArt` datetime DEFAULT CURRENT_TIMESTAMP,
  `dtMajArt` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `libTitrArt` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `libChapoArt` text COLLATE utf8mb3_unicode_ci,
  `libAccrochArt` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `parag1Art` text COLLATE utf8mb3_unicode_ci,
  `libSsTitr1Art` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `parag2Art` text COLLATE utf8mb3_unicode_ci,
  `libSsTitr2Art` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `parag3Art` text COLLATE utf8mb3_unicode_ci,
  `libConclArt` text COLLATE utf8mb3_unicode_ci,
  `urlPhotArt` varchar(70) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `numThem` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `ARTICLE`
--

INSERT INTO `ARTICLE` (`numArt`, `dtCreaArt`, `dtMajArt`, `libTitrArt`, `libChapoArt`, `libAccrochArt`, `parag1Art`, `libSsTitr1Art`, `parag2Art`, `libSsTitr2Art`, `parag3Art`, `libConclArt`, `urlPhotArt`, `numThem`) VALUES
(17, '2026-02-06 10:40:00', '2026-02-06 15:09:09', 'Soirée match au BEC : une ambiance engagée', 'Le BEC a vécu une nouvelle soirée de championnat rythmée par l’engagement et le collectif.', 'Une soirée où le collectif a porté l’équipe.', 'Vendredi soir, joueurs, staff et supporters se sont retrouvés au gymnase pour une rencontre disputée dans une ambiance chaleureuse.', 'Une intensité constante', 'L’équipe a su imposer son rythme grâce à une défense solidaire et une implication collective visible tout au long du match.', 'Un club uni', 'Après la rencontre, les échanges entre joueurs, bénévoles et supporters ont prolongé l’esprit BEC.', 'Une soirée réussie qui reflète parfaitement les valeurs du club.', 'imgArt2.jpg', 1),
(18, '2026-02-06 10:50:00', '2026-02-06 15:08:58', 'Les jeunes du BEC en pleine progression', 'Les équipes jeunes continuent d’apprendre et de progresser match après match.', 'Un week-end riche en apprentissage.', 'Tout au long du week-end, les jeunes licenciés ont disputé plusieurs rencontres avec sérieux et implication.', 'Former dans la durée', 'Les coachs ont mis l’accent sur les fondamentaux, la communication et le respect des consignes collectives.', 'Un cadre rassurant', 'Parents et bénévoles ont accompagné les équipes, créant une ambiance positive et encourageante.', 'Ces rencontres confirment l’importance de la formation au sein du BEC.', 'imgArt2.jpg', 2),
(19, '2026-02-06 11:00:00', '2026-02-06 15:09:13', 'Week-end décisif pour les SF1', 'Les Séniors Filles 1 s’apprêtent à disputer une rencontre importante.', 'Un match clé pour le classement.', 'Les SF1 abordent ce week-end avec sérieux et détermination face à un adversaire direct.', 'Une préparation rigoureuse', 'Le groupe travaille avec intensité à l’entraînement afin d’aborder cette rencontre dans les meilleures conditions.', 'Un soutien attendu', 'Le public aura un rôle essentiel pour pousser les joueuses et créer une ambiance favorable.', 'Un rendez-vous important pour la suite de la saison.', 'imgArt3.jpg', 3),
(20, '2026-02-06 11:00:00', '2026-02-06 15:09:18', 'Week-end décisif pour les SF1', 'Les Séniors Filles 1 s’apprêtent à disputer une rencontre importante.', 'Un match clé pour le classement.', 'Les SF1 abordent ce week-end avec sérieux et détermination face à un adversaire direct.', 'Une préparation rigoureuse', 'Le groupe travaille avec intensité à l’entraînement afin d’aborder cette rencontre dans les meilleures conditions.', 'Un soutien attendu', 'Le public aura un rôle essentiel pour pousser les joueuses et créer une ambiance favorable.', 'Un rendez-vous important pour la suite de la saison.', 'imgArt4.jpg', 3),
(31, '2026-02-06 15:00:00', '2026-02-06 15:46:33', 'Soirée match au BEC : une ambiance engagée', 'Le gymnase a vibré pour une soirée de championnat rythmée par l’intensité et le collectif.', 'Une soirée où le collectif a fait la différence.', 'Vendredi soir, joueurs, staff et supporters se sont retrouvés pour une rencontre disputée et très suivie.', 'Une intensité constante', 'Le BEC a su rester solide grâce à une défense active et une vraie communication entre les lignes.', 'Un club uni', 'Après le match, l’esprit BEC s’est prolongé : échanges, remerciements et convivialité autour du groupe.', 'Une belle soirée qui confirme la force du club quand tout le monde avance ensemble.', 'imgArt5.jpg', 1),
(32, '2026-02-06 15:05:00', '2026-02-06 15:46:39', 'Tournoi intergénérationnel : un moment de partage', 'Le BEC a réuni plusieurs générations sur le terrain dans un esprit festif et bienveillant.', 'Le basket comme lien entre générations.', 'Le temps d’un tournoi, enfants, parents, licenciés et bénévoles ont joué ensemble dans une ambiance conviviale.', 'Un format accessible', 'Les mini-matchs ont permis à chacun de participer, quel que soit le niveau, avec l’envie de partager.', 'Des souvenirs communs', 'Au-delà du jeu, l’événement a renforcé la cohésion et créé de nouveaux souvenirs pour la communauté BEC.', 'Merci à tous : ce type de rendez-vous fait vivre les valeurs du club au quotidien.', 'imgArt6.jpg', 1),
(33, '2026-02-06 15:10:00', '2026-02-06 15:46:44', 'Freddy Dogoum : une référence pour les SF1', 'Coach des SF1, Freddy Dogoum incarne l’exigence, la stabilité et la progression collective.', 'Un coach engagé au service du collectif.', 'À la tête des SF1, Freddy construit une dynamique de travail structurée, exigeante et tournée vers la progression.', 'Une méthode claire', 'Rigueur, discipline et confiance : le groupe avance avec des repères solides et une préparation régulière.', 'Une équipe soudée', 'Le coach valorise le collectif : chaque joueuse a un rôle, et l’unité reste la base des performances.', 'Un pilier du projet SF1, moteur de la dynamique et repère pour son groupe.', 'imgArt7.jpg', 2),
(34, '2026-02-06 15:15:00', '2026-02-06 15:46:49', 'Les jeunes du BEC en progression', 'Les équipes jeunes continuent d’apprendre et de construire des repères match après match.', 'Un week-end riche en apprentissages.', 'Sur les dernières rencontres, les jeunes ont montré de l’envie, du sérieux et une belle implication sur les consignes.', 'Former dans la durée', 'Les coachs travaillent les fondamentaux : placements, passes, communication et effort défensif.', 'Une ambiance positive', 'Parents et bénévoles accompagnent les équipes et créent un cadre motivant qui aide à progresser.', 'Ces matchs confirment l’importance de la formation et du plaisir de jouer au BEC.', 'imgArt8.jpg', 3),
(35, '2026-02-06 15:20:00', '2026-02-06 15:46:53', 'Le BEC remercie ses bénévoles', 'Derrière chaque match, une équipe de l’ombre s’active : accueil, table, buvette et logistique.', 'Merci à celles et ceux qui rendent tout possible.', 'Chaque week-end, des bénévoles assurent l’organisation et permettent aux équipes de jouer dans de bonnes conditions.', 'Un rôle essentiel', 'Sans eux, pas de matchs fluides, pas d’accueil, pas d’ambiance : leur présence est indispensable.', 'Un esprit club', 'Leur engagement renforce le lien entre générations et donne au BEC son identité conviviale.', 'Un grand merci : votre implication fait grandir le club au quotidien.', 'imgArt9.jpg', 2),
(36, '2026-02-06 15:25:00', '2026-02-06 15:46:58', 'Week-end à domicile : le club en action', 'Plusieurs rencontres se sont enchaînées au gymnase avec une organisation solide et une belle ambiance.', 'Un week-end 100% vie de club.', 'Entre l’accueil, la table de marque et la buvette, le BEC a mobilisé beaucoup de monde pour faire vivre le gymnase.', 'Une logistique bien rodée', 'Grâce à l’implication de chacun, les rencontres se sont déroulées dans de bonnes conditions.', 'Une énergie collective', 'Ces journées rapprochent les équipes, les familles et les bénévoles autour d’un même objectif.', 'Le BEC remercie toutes les personnes mobilisées sur ce week-end chargé.', 'imgArt10.jpg', 1),
(37, '2026-02-06 15:30:00', '2026-02-06 15:47:02', 'Focus : la défense, base de nos progrès', 'Le BEC met l’accent sur la régularité défensive pour gagner en maîtrise et en stabilité.', 'Construire par l’effort et la discipline.', 'Sur cette période, les équipes travaillent la défense : attitude, entraide et communication pour mieux contrôler le jeu.', 'Un socle collectif', 'Une défense solide permet de rester dans le match, même lorsque l’adresse baisse ou que le rythme change.', 'Des repères à ancrer', 'Répéter les fondamentaux à l’entraînement aide à transformer les efforts en automatismes en match.', 'La progression se construit dans la régularité, match après match.', 'imgArt11.jpg', 3),
(38, '2026-02-06 15:35:00', '2026-02-06 15:47:10', 'Une victoire construite ensemble', 'Une performance collective a permis au BEC de décrocher un succès important à domicile.', 'Une victoire qui récompense le collectif.', 'Portée par l’ambiance, l’équipe a montré de la solidarité et une vraie volonté de jouer juste sur les temps forts.', 'Rester solides', 'Le groupe a su répondre présent en défense et gérer les moments clés avec plus de calme et de discipline.', 'Le public au rendez-vous', 'Les tribunes ont apporté une énergie précieuse, notamment dans les passages décisifs du match.', 'Un succès encourageant, qui donne confiance et confirme la dynamique du groupe.', 'imgArt12.jpg', 1),
(39, '2026-02-06 15:40:00', '2026-02-06 15:47:14', 'Un clin d’œil : quand le basket rassemble', 'Parfois, un simple moment au gymnase résume l’esprit du BEC : sourire, respect et partage.', 'Ces instants simples font la force du club.', 'Entre deux matchs, un échange, un encouragement ou une discussion rappelle que le basket dépasse le score.', 'Une ambiance familiale', 'Au BEC, joueurs, familles et bénévoles partagent un même espace, avec une vraie proximité.', 'Une identité forte', 'Ces petits moments créent des souvenirs et renforcent le sentiment d’appartenance à la communauté du club.', 'Le BEC, c’est aussi ça : du sport, du lien, et une énergie collective qui rassemble.', 'imgArt13.jpg', 4),
(40, '2026-02-06 15:45:00', '2026-02-06 15:47:18', 'Un match formateur malgré le résultat', 'Malgré une rencontre compliquée, le BEC retient une attitude solide et des axes de progrès clairs.', 'Apprendre pour mieux rebondir.', 'Le match n’a pas tourné comme prévu, mais le groupe est resté uni et n’a jamais lâché l’effort.', 'Garder l’état d’esprit', 'Dans les moments difficiles, la solidarité et la communication restent des points essentiels pour progresser.', 'Se remettre au travail', 'Cette rencontre met en lumière des points à corriger, qui seront travaillés dès les prochaines séances.', 'Chaque match compte : l’objectif est de grandir, ensemble, tout au long de la saison.', 'imgArt14.jpg', 3);

-- --------------------------------------------------------

--
-- Structure de la table `boutique`
--

CREATE TABLE `boutique` (
  `numArtBoutique` int UNSIGNED NOT NULL,
  `libArtBoutique` varchar(255) NOT NULL,
  `descArtBoutique` text,
  `couleursArtBoutique` json NOT NULL,
  `taillesArtBoutique` json NOT NULL,
  `prixAdulteArtBoutique` decimal(10,2) NOT NULL,
  `prixEnfantArtBoutique` decimal(10,2) DEFAULT NULL,
  `urlPhotoArtBoutique` json NOT NULL,
  `categorieArtBoutique` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `boutique`
--

INSERT INTO `boutique` (`numArtBoutique`, `libArtBoutique`, `descArtBoutique`, `couleursArtBoutique`, `taillesArtBoutique`, `prixAdulteArtBoutique`, `prixEnfantArtBoutique`, `urlPhotoArtBoutique`, `categorieArtBoutique`, `created_at`, `updated_at`) VALUES
(1, 'T-shirt', 'T-shirt officiel du BEC.', '[\"Rouge\", \"Noir\"]', '[\"XS\", \"S\", \"M\", \"L\", \"XL\", \"XXL\"]', 15.00, NULL, '[]', 'Vêtement', '2026-02-06 04:11:45', '2026-02-06 04:11:45'),
(2, 'Short molleton', 'Short confortable en molleton.', '[\"Noir\"]', '[\"XS\", \"S\", \"M\", \"L\", \"XL\", \"XXL\"]', 20.00, NULL, '[]', 'Vêtement', '2026-02-06 04:11:45', '2026-02-06 04:11:45'),
(3, 'Chaussettes', 'Chaussettes de sport BEC.', '[\"Rouge\", \"Blanc\"]', '[\"XS\", \"S\", \"M\", \"L\", \"XL\", \"XXL\"]', 10.00, NULL, '[]', 'Vêtement', '2026-02-06 04:11:45', '2026-02-06 04:11:45'),
(4, 'Polo', 'Polo club pour les supporters.', '[\"Rouge\", \"Blanc\"]', '[\"XS\", \"S\", \"M\", \"L\", \"XL\", \"XXL\"]', 25.00, NULL, '[]', 'Vêtement', '2026-02-06 04:11:45', '2026-02-06 04:11:45'),
(5, 'Short coton fin', 'Short léger en coton fin.', '[\"Noir\"]', '[\"XS\", \"S\", \"M\", \"L\", \"XL\", \"XXL\"]', 15.00, NULL, '[]', 'Vêtement', '2026-02-06 04:11:45', '2026-02-06 04:11:45'),
(6, 'Casquette', 'Casquette officielle du BEC.', '[\"Blanc\", \"Bleu\"]', '[\"Taille unique\"]', 15.00, NULL, '[]', 'Accessoire', '2026-02-06 04:11:45', '2026-02-06 04:11:45'),
(7, 'Pull', 'Pull chaud pour l\'entraînement ou le quotidien.', '[\"Rouge\", \"Noir\"]', '[\"XS\", \"S\", \"M\", \"L\", \"XL\", \"XXL\"]', 35.00, 32.00, '[]', 'Vêtement', '2026-02-06 04:11:45', '2026-02-06 04:11:45'),
(8, 'Short entraînement', 'Short respirant pour les séances.', '[\"Rouge\"]', '[\"XS\", \"S\", \"M\", \"L\", \"XL\", \"XXL\"]', 15.00, NULL, '[]', 'Vêtement', '2026-02-06 04:11:45', '2026-02-06 04:11:45'),
(9, 'Serviette 100 x 140 cm', 'Serviette idéale pour l\'entraînement.', '[\"Rouge\", \"Blanc\"]', '[\"100 x 140 cm\"]', 20.00, NULL, '[]', 'Accessoire', '2026-02-06 04:11:45', '2026-02-06 04:11:45'),
(10, 'Doudoune sans manche', 'Doudoune légère sans manche.', '[\"Noir\", \"Bleu\"]', '[\"XS\", \"S\", \"M\", \"L\", \"XL\", \"XXL\"]', 36.00, 34.00, '[]', 'Vêtement', '2026-02-06 04:11:45', '2026-02-06 04:11:45'),
(11, 'Doudoune manche longue', 'Doudoune chaude à manches longues.', '[\"Noir\", \"Bleu\"]', '[\"XS\", \"S\", \"M\", \"L\", \"XL\", \"XXL\"]', 42.00, 40.00, '[]', 'Vêtement', '2026-02-06 04:11:45', '2026-02-06 04:11:45'),
(12, 'Jogging', 'Jogging BEC confortable.', '[\"Blanc\", \"Noir\"]', '[\"XS\", \"S\", \"M\", \"L\", \"XL\", \"XXL\"]', 30.00, NULL, '[]', 'Vêtement', '2026-02-06 04:11:45', '2026-02-06 04:11:45'),
(13, 'Sac à dos', 'Sac à dos pratique pour les matchs.', '[\"Noir\"]', '[\"Taille unique\"]', 25.00, NULL, '[]', 'Accessoire', '2026-02-06 04:11:45', '2026-02-06 04:11:45'),
(14, 'Gourde', 'Gourde réutilisable pour l\'entraînement.', '[\"Blanc\", \"Rouge\"]', '[\"Taille unique\"]', 6.00, NULL, '[]', 'Accessoire', '2026-02-06 04:11:45', '2026-02-06 04:11:45');

-- --------------------------------------------------------

--
-- Structure de la table `COMMENT`
--

CREATE TABLE `COMMENT` (
  `numCom` int NOT NULL,
  `dtCreaCom` datetime DEFAULT CURRENT_TIMESTAMP,
  `libCom` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `dtModCom` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `attModOK` tinyint(1) DEFAULT '0',
  `notifComKOAff` text COLLATE utf8mb3_unicode_ci,
  `dtDelLogCom` datetime DEFAULT NULL,
  `delLogiq` tinyint(1) DEFAULT '0',
  `numArt` int NOT NULL,
  `numMemb` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `COMMENT`
--

INSERT INTO `COMMENT` (`numCom`, `dtCreaCom`, `libCom`, `dtModCom`, `attModOK`, `notifComKOAff`, `dtDelLogCom`, `delLogiq`, `numArt`, `numMemb`) VALUES
(305, '2026-02-06 18:00:00', 'Très bon résumé, on sent l’énergie du club.', NULL, 1, NULL, NULL, 0, 37, 7),
(306, '2026-02-06 18:01:00', 'Très bon résumé, on sent l’énergie du club.', NULL, 1, NULL, NULL, 0, 18, 7),
(307, '2026-02-06 18:02:00', 'Super ambiance, ça fait plaisir.', NULL, 1, NULL, NULL, 0, 17, 7),
(308, '2026-02-06 18:03:00', 'Force aux joueurs/joueuses pour le prochain match !', NULL, 1, NULL, NULL, 0, 38, 8),
(309, '2026-02-06 18:04:00', 'Contenu nickel, merci !', NULL, 1, NULL, NULL, 0, 31, 8),
(310, '2026-02-06 18:05:00', 'Super article, merci pour le partage !', NULL, 1, NULL, NULL, 0, 20, 8),
(311, '2026-02-06 18:06:00', 'Merci aux bénévoles, indispensable.', NULL, 1, NULL, NULL, 0, 20, 9),
(312, '2026-02-06 18:07:00', 'Allez le BEC !', NULL, 1, NULL, NULL, 0, 19, 9),
(313, '2026-02-06 18:08:00', 'Top ! On retrouve bien l’esprit collectif.', NULL, 1, NULL, NULL, 0, 38, 9),
(314, '2026-02-06 18:09:00', 'Bravo à l’équipe, continuez comme ça.', NULL, 1, NULL, NULL, 0, 18, 10),
(315, '2026-02-06 18:10:00', 'Belle dynamique, hâte de voir la suite !', NULL, 1, NULL, NULL, 0, 37, 10),
(316, '2026-02-06 18:11:00', 'Ça donne envie de venir au gymnase.', NULL, 1, NULL, NULL, 0, 33, 10),
(317, '2026-02-06 18:12:00', 'Super ambiance, ça fait plaisir.', NULL, 1, NULL, NULL, 0, 31, 11),
(318, '2026-02-06 18:13:00', 'Super article, merci pour le partage !', NULL, 1, NULL, NULL, 0, 20, 11),
(319, '2026-02-06 18:14:00', 'Top ! On retrouve bien l’esprit collectif.', NULL, 1, NULL, NULL, 0, 18, 11),
(320, '2026-02-06 18:15:00', 'Article clair et agréable à lire.', NULL, 1, NULL, NULL, 0, 39, 12),
(321, '2026-02-06 18:16:00', 'Très bon résumé, on sent l’énergie du club.', NULL, 1, NULL, NULL, 0, 17, 12),
(322, '2026-02-06 18:17:00', 'Contenu nickel, merci !', NULL, 1, NULL, NULL, 0, 33, 12),
(323, '2026-02-06 18:18:00', 'Belle dynamique, hâte de voir la suite !', NULL, 1, NULL, NULL, 0, 40, 13),
(324, '2026-02-06 18:19:00', 'Merci aux bénévoles, indispensable.', NULL, 1, NULL, NULL, 0, 34, 13),
(325, '2026-02-06 18:20:00', 'Super article, merci pour le partage !', NULL, 1, NULL, NULL, 0, 20, 13),
(326, '2026-02-06 18:21:00', 'Ça donne envie de venir au gymnase.', NULL, 1, NULL, NULL, 0, 37, 14),
(327, '2026-02-06 18:22:00', 'Super ambiance, ça fait plaisir.', NULL, 1, NULL, NULL, 0, 35, 14),
(328, '2026-02-06 18:23:00', 'Bravo à l’équipe, continuez comme ça.', NULL, 1, NULL, NULL, 0, 19, 14),
(329, '2026-02-06 18:24:00', 'Contenu nickel, merci !', NULL, 1, NULL, NULL, 0, 40, 15),
(330, '2026-02-06 18:25:00', 'Allez le BEC !', NULL, 1, NULL, NULL, 0, 31, 15),
(331, '2026-02-06 18:26:00', 'Top ! On retrouve bien l’esprit collectif.', NULL, 1, NULL, NULL, 0, 32, 15),
(332, '2026-02-06 18:27:00', 'Très bon résumé, on sent l’énergie du club.', NULL, 1, NULL, NULL, 0, 35, 16),
(333, '2026-02-06 18:28:00', 'Super article, merci pour le partage !', NULL, 1, NULL, NULL, 0, 40, 16),
(334, '2026-02-06 18:29:00', 'Article clair et agréable à lire.', NULL, 1, NULL, NULL, 0, 38, 16),
(335, '2026-02-06 18:30:00', 'Merci aux bénévoles, indispensable.', NULL, 1, NULL, NULL, 0, 37, 17),
(336, '2026-02-06 18:31:00', 'Ça donne envie de venir au gymnase.', NULL, 1, NULL, NULL, 0, 18, 17),
(337, '2026-02-06 18:32:00', 'Bravo à l’équipe, continuez comme ça.', NULL, 1, NULL, NULL, 0, 20, 17),
(338, '2026-02-06 18:33:00', 'Super ambiance, ça fait plaisir.', NULL, 1, NULL, NULL, 0, 34, 18),
(339, '2026-02-06 18:34:00', 'Belle dynamique, hâte de voir la suite !', NULL, 1, NULL, NULL, 0, 19, 18),
(340, '2026-02-06 18:35:00', 'Top ! On retrouve bien l’esprit collectif.', NULL, 1, NULL, NULL, 0, 32, 18),
(341, '2026-02-06 18:36:00', 'Force aux joueurs/joueuses pour le prochain match !', NULL, 1, NULL, NULL, 0, 33, 19),
(342, '2026-02-06 18:37:00', 'Article clair et agréable à lire.', NULL, 1, NULL, NULL, 0, 20, 19),
(343, '2026-02-06 18:38:00', 'Contenu nickel, merci !', NULL, 1, NULL, NULL, 0, 31, 19),
(344, '2026-02-06 18:39:00', 'Allez le BEC !', NULL, 1, NULL, NULL, 0, 17, 20),
(345, '2026-02-06 18:40:00', 'Très bon résumé, on sent l’énergie du club.', NULL, 1, NULL, NULL, 0, 32, 20),
(346, '2026-02-06 18:41:00', 'Super article, merci pour le partage !', NULL, 1, NULL, NULL, 0, 40, 20),
(347, '2026-02-06 18:42:00', 'Bravo à l’équipe, continuez comme ça.', NULL, 1, NULL, NULL, 0, 33, 21),
(348, '2026-02-06 18:43:00', 'Super ambiance, ça fait plaisir.', NULL, 1, NULL, NULL, 0, 35, 21),
(349, '2026-02-06 18:44:00', 'Ça donne envie de venir au gymnase.', NULL, 1, NULL, NULL, 0, 19, 21),
(350, '2026-02-06 18:45:00', 'Article clair et agréable à lire.', NULL, 1, NULL, NULL, 0, 31, 22),
(351, '2026-02-06 18:46:00', 'Merci aux bénévoles, indispensable.', NULL, 1, NULL, NULL, 0, 32, 22),
(352, '2026-02-06 18:47:00', 'Belle dynamique, hâte de voir la suite !', NULL, 1, NULL, NULL, 0, 18, 22),
(353, '2026-02-06 18:48:00', 'Contenu nickel, merci !', NULL, 1, NULL, NULL, 0, 34, 23),
(354, '2026-02-06 18:49:00', 'Top ! On retrouve bien l’esprit collectif.', NULL, 1, NULL, NULL, 0, 17, 23),
(355, '2026-02-06 18:50:00', 'Allez le BEC !', NULL, 1, NULL, NULL, 0, 39, 23),
(356, '2026-02-06 18:51:00', 'Super article, merci pour le partage !', NULL, 1, NULL, NULL, 0, 36, 24),
(357, '2026-02-06 18:52:00', 'Très bon résumé, on sent l’énergie du club.', NULL, 1, NULL, NULL, 0, 33, 24),
(358, '2026-02-06 18:53:00', 'Bravo à l’équipe, continuez comme ça.', NULL, 1, NULL, NULL, 0, 20, 24),
(359, '2026-02-06 18:54:00', 'Ça donne envie de venir au gymnase.', NULL, 1, NULL, NULL, 0, 39, 25),
(360, '2026-02-06 18:55:00', 'Super ambiance, ça fait plaisir.', NULL, 1, NULL, NULL, 0, 35, 25),
(361, '2026-02-06 18:56:00', 'Article clair et agréable à lire.', NULL, 1, NULL, NULL, 0, 31, 25),
(362, '2026-02-06 18:57:00', 'Merci aux bénévoles, indispensable.', NULL, 1, NULL, NULL, 0, 19, 26),
(363, '2026-02-06 18:58:00', 'Contenu nickel, merci !', NULL, 1, NULL, NULL, 0, 36, 26),
(364, '2026-02-06 18:59:00', 'Top ! On retrouve bien l’esprit collectif.', NULL, 1, NULL, NULL, 0, 38, 26);

-- --------------------------------------------------------

--
-- Structure de la table `EQUIPE`
--

CREATE TABLE `EQUIPE` (
  `numEquipe` int NOT NULL,
  `codeEquipe` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomEquipe` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `club` varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Bordeaux étudiant club',
  `categorie` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `section` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `niveau` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptionEquipe` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `photoDLequipe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photoStaff` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `EQUIPE`
--

INSERT INTO `EQUIPE` (`numEquipe`, `codeEquipe`, `nomEquipe`, `club`, `categorie`, `section`, `niveau`, `descriptionEquipe`, `photoDLequipe`, `photoStaff`) VALUES
(5, 'SF1', 'Seniors filles 1', 'Bordeaux étudiant club', 'Senior', 'Féminine', 'National 3', 'Equipe SF1', NULL, NULL),
(6, 'SF2', 'Seniors filles 2', 'Bordeaux étudiant club', 'Senior', 'Féminin', 'Pré-national', 'Équipe senior féminine 2', NULL, NULL),
(7, 'SF3', 'Seniors filles 3', 'Bordeaux étudiant club', 'Senior', 'Féminin', 'Pré-national', 'Équipe senior féminine 3', NULL, NULL),
(2, 'SG1', 'Seniors garçons 1', 'Bordeaux étudiant club', 'Senior', 'Masculin', 'Pré-national', 'Équipe fanion senior 1', NULL, NULL),
(3, 'SG2', 'Seniors garçons 2', 'Bordeaux étudiant club', 'Senior', 'Masculin', 'Régional 2', 'Équipe senior 2', NULL, NULL),
(1, 'SG3', 'Séniors garçons 3', 'Bordeaux étudiant club', 'Senior', 'Masculine', 'Départementale 3', 'adasda', 'photos-equipes/SG3-photo-equipe.jpg', 'photos-equipes/SG3-photo-staff.jpg'),
(4, 'SG4', 'Seniors garçons 4', 'Bordeaux étudiant club', 'Senior', 'Masculin', 'Départemental 4', 'Équipe senior 4', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `JOUEUR`
--

CREATE TABLE `JOUEUR` (
  `numJoueur` int NOT NULL,
  `surnomJoueur` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenomJoueur` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomJoueur` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `urlPhotoJoueur` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dateNaissance` date DEFAULT NULL,
  `codeEquipe` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `posteJoueur` tinyint UNSIGNED NOT NULL,
  `numeroMaillot` int DEFAULT NULL,
  `dateRecrutement` date DEFAULT NULL,
  `clubsPrecedents` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `JOUEUR`
--

INSERT INTO `JOUEUR` (`numJoueur`, `surnomJoueur`, `prenomJoueur`, `nomJoueur`, `urlPhotoJoueur`, `dateNaissance`, `codeEquipe`, `posteJoueur`, `numeroMaillot`, `dateRecrutement`, `clubsPrecedents`) VALUES
(1, 'Mehdikops', 'Mehdi', 'Afankous', NULL, '1291-03-18', 'SG3', 1, 1, '2025-09-01', NULL),
(2, 'SEN1-J01', 'Alex', 'Durand', NULL, '1997-03-12', 'SG1', 1, 1, '2022-07-01', NULL),
(3, 'SEN1-J02', 'Hugo', 'Martin', NULL, '1996-05-20', 'SG1', 2, 2, '2021-07-01', NULL),
(4, 'SEN1-J03', 'Lucas', 'Petit', NULL, '1998-01-04', 'SG1', 3, 3, '2020-07-01', NULL),
(5, 'SEN1-J04', 'Nolan', 'Bernard', NULL, '1995-09-10', 'SG1', 4, 4, '2019-07-01', NULL),
(6, 'SEN1-J05', 'Maxime', 'Robert', NULL, '1994-11-16', 'SG1', 5, 5, '2020-08-15', NULL),
(7, 'SEN1-J06', 'Theo', 'Richard', NULL, '1997-02-21', 'SG1', 6, 6, '2023-07-01', NULL),
(8, 'SEN1-J07', 'Enzo', 'Dubois', NULL, '1996-06-18', 'SG1', 7, 7, '2022-07-01', NULL),
(9, 'SEN1-J08', 'Paul', 'Moreau', NULL, '1999-08-30', 'SG1', 8, 8, '2021-08-01', NULL),
(10, 'SEN1-J09', 'Louis', 'Fournier', NULL, '1993-12-02', 'SG1', 9, 9, '2020-07-01', NULL),
(11, 'SEN1-J10', 'Jules', 'Girard', NULL, '1998-04-15', 'SG1', 10, 10, '2019-07-01', NULL),
(12, 'SEN2-J01', 'Antoine', 'Leroy', NULL, '1996-02-14', 'SG2', 1, 1, '2022-07-01', NULL),
(13, 'SEN2-J02', 'Thomas', 'Roux', NULL, '1995-07-19', 'SG2', 2, 2, '2021-07-01', NULL),
(14, 'SEN2-J03', 'Adrien', 'David', NULL, '1997-09-25', 'SG2', 3, 3, '2020-07-01', NULL),
(15, 'SEN2-J04', 'Bastien', 'Bertrand', NULL, '1994-01-07', 'SG2', 4, 4, '2019-07-01', NULL),
(16, 'SEN2-J05', 'Florian', 'Morel', NULL, '1996-11-03', 'SG2', 5, 5, '2020-08-15', NULL),
(17, 'SEN2-J06', 'Quentin', 'Simon', NULL, '1998-05-11', 'SG2', 6, 6, '2023-07-01', NULL),
(18, 'SEN2-J07', 'Romain', 'Laurent', NULL, '1997-03-22', 'SG2', 7, 7, '2022-07-01', NULL),
(19, 'SEN2-J08', 'Nathan', 'Lefevre', NULL, '1999-12-08', 'SG2', 8, 8, '2021-08-01', NULL),
(20, 'SEN2-J09', 'Kylian', 'Michel', NULL, '1993-06-29', 'SG2', 9, 9, '2020-07-01', NULL),
(21, 'SEN2-J10', 'Loic', 'Garcia', NULL, '1998-10-17', 'SG2', 10, 10, '2019-07-01', NULL),
(22, 'SEN3-J01', 'Mathis', 'Perrin', NULL, '1996-03-09', 'SG4', 1, 1, '2022-07-01', NULL),
(23, 'SEN3-J02', 'Yann', 'Robin', NULL, '1995-05-28', 'SG4', 2, 2, '2021-07-01', NULL),
(24, 'SEN3-J03', 'Evan', 'Clement', NULL, '1997-01-19', 'SG4', 3, 3, '2020-07-01', NULL),
(25, 'SEN3-J04', 'Sacha', 'Morin', NULL, '1994-08-13', 'SG4', 4, 4, '2019-07-01', NULL),
(26, 'SEN3-J05', 'Dylan', 'Roche', NULL, '1996-12-05', 'SG4', 5, 5, '2020-08-15', NULL),
(27, 'SEN3-J06', 'Noe', 'Schmitt', NULL, '1998-04-27', 'SG4', 6, 6, '2023-07-01', NULL),
(28, 'SEN3-J07', 'Eliot', 'Henry', NULL, '1997-02-16', 'SG4', 7, 7, '2022-07-01', NULL),
(29, 'SEN3-J08', 'Malo', 'Boyer', NULL, '1999-09-01', 'SG4', 8, 8, '2021-08-01', NULL),
(30, 'SEN3-J09', 'Gabriel', 'Giraud', NULL, '1993-07-23', 'SG4', 9, 9, '2020-07-01', NULL),
(31, 'SEN3-J10', 'Leo', 'Chevalier', NULL, '1998-10-30', 'SG4', 10, 10, '2019-07-01', NULL),
(32, 'SEN4-J01', 'Hugo', 'Masson', NULL, '1996-02-11', 'SF1', 1, 1, '2022-07-01', NULL),
(33, 'SEN4-J02', 'Tom', 'Garnier', NULL, '1995-06-14', 'SF1', 2, 2, '2021-07-01', NULL),
(34, 'SEN4-J03', 'Noah', 'Riviere', NULL, '1997-01-26', 'SF1', 3, 3, '2020-07-01', NULL),
(35, 'SEN4-J04', 'Axel', 'Barbier', NULL, '1994-09-06', 'SF1', 4, 4, '2019-07-01', NULL),
(36, 'SEN4-J05', 'Liam', 'Marchand', NULL, '1996-11-21', 'SF1', 5, 5, '2020-08-15', NULL),
(37, 'SEN4-J06', 'Nils', 'Charpentier', NULL, '1998-05-09', 'SF1', 6, 6, '2023-07-01', NULL),
(38, 'SEN4-J07', 'Ethan', 'Rolland', NULL, '1997-03-18', 'SF1', 7, 7, '2022-07-01', NULL),
(39, 'SEN4-J08', 'Aaron', 'Aubert', NULL, '1999-12-12', 'SF1', 8, 8, '2021-08-01', NULL),
(40, 'SEN4-J09', 'Kevin', 'Guillot', NULL, '1993-06-03', 'SF1', 9, 9, '2020-07-01', NULL),
(41, 'SEN4-J10', 'Simon', 'Bouvier', NULL, '1998-10-25', 'SF1', 10, 10, '2019-07-01', NULL),
(42, 'SEN5-J01', 'Emma', 'Dupont', NULL, '1998-03-07', 'SF2', 1, 1, '2022-07-01', NULL),
(43, 'SEN5-J02', 'Lea', 'Lemaire', NULL, '1997-05-18', 'SF2', 2, 2, '2021-07-01', NULL),
(44, 'SEN5-J03', 'Chloe', 'Lopez', NULL, '1999-02-12', 'SF2', 3, 3, '2020-07-01', NULL),
(45, 'SEN5-J04', 'Manon', 'Fontaine', NULL, '1996-09-03', 'SF2', 4, 4, '2019-07-01', NULL),
(46, 'SEN5-J05', 'Ines', 'Lambert', NULL, '1998-11-30', 'SF2', 5, 5, '2020-08-15', NULL),
(47, 'SEN5-J06', 'Sarah', 'Muller', NULL, '1997-04-19', 'SF2', 6, 6, '2023-07-01', NULL),
(48, 'SEN5-J07', 'Louna', 'Perez', NULL, '1999-06-22', 'SF2', 7, 7, '2022-07-01', NULL),
(49, 'SEN5-J08', 'Camille', 'Colin', NULL, '1998-12-10', 'SF2', 8, 8, '2021-08-01', NULL),
(50, 'SEN5-J09', 'Julie', 'Arnaud', NULL, '1996-07-29', 'SF2', 9, 9, '2020-07-01', NULL),
(51, 'SEN5-J10', 'Alyssa', 'Renaud', NULL, '1999-10-26', 'SF2', 10, 10, '2019-07-01', NULL),
(52, 'SEN6-J01', 'Laura', 'Roy', NULL, '1998-02-05', 'SF3', 1, 1, '2022-07-01', NULL),
(53, 'SEN6-J02', 'Clara', 'Gomez', NULL, '1997-05-27', 'SF3', 2, 2, '2021-07-01', NULL),
(54, 'SEN6-J03', 'Lucie', 'Allard', NULL, '1999-01-21', 'SF3', 3, 3, '2020-07-01', NULL),
(55, 'SEN6-J04', 'Elena', 'Gaudin', NULL, '1996-08-11', 'SF3', 4, 4, '2019-07-01', NULL),
(56, 'SEN6-J05', 'Nina', 'Baron', NULL, '1998-12-19', 'SF3', 5, 5, '2020-08-15', NULL),
(57, 'SEN6-J06', 'Maeva', 'Hernandez', NULL, '1997-04-23', 'SF3', 6, 6, '2023-07-01', NULL),
(58, 'SEN6-J07', 'Romane', 'Navarro', NULL, '1999-06-09', 'SF3', 7, 7, '2022-07-01', NULL),
(59, 'SEN6-J08', 'Elise', 'Vidal', NULL, '1998-12-02', 'SF3', 8, 8, '2021-08-01', NULL),
(60, 'SEN6-J09', 'Pauline', 'Brun', NULL, '1996-07-16', 'SF3', 9, 9, '2020-07-01', NULL),
(61, 'SEN6-J10', 'Lina', 'Moulin', NULL, '1999-10-20', 'SF3', 10, 10, '2019-07-01', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `LIKEART`
--

CREATE TABLE `LIKEART` (
  `numMemb` int NOT NULL,
  `numArt` int NOT NULL,
  `likeA` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `LIKEART`
--

INSERT INTO `LIKEART` (`numMemb`, `numArt`, `likeA`) VALUES
(7, 17, 1),
(7, 18, 0),
(7, 37, 0),
(8, 20, 1),
(8, 31, 0),
(8, 38, 0),
(9, 19, 1),
(9, 20, 0),
(9, 38, 0),
(10, 18, 0),
(10, 33, 0),
(10, 37, 1),
(11, 18, 0),
(11, 20, 1),
(11, 31, 0),
(12, 17, 1),
(12, 33, 0),
(12, 39, 0),
(13, 20, 0),
(13, 34, 0),
(13, 40, 1),
(14, 19, 0),
(14, 35, 0),
(14, 37, 1),
(15, 31, 1),
(15, 32, 0),
(15, 40, 0),
(16, 35, 0),
(16, 38, 0),
(16, 40, 1),
(17, 18, 1),
(17, 20, 1),
(17, 37, 0),
(18, 19, 0),
(18, 32, 1),
(18, 34, 0),
(19, 20, 0),
(19, 31, 1),
(19, 33, 0),
(20, 17, 0),
(20, 32, 1),
(20, 40, 1),
(21, 19, 1),
(21, 33, 1),
(21, 35, 0),
(22, 18, 1),
(22, 31, 0),
(22, 32, 1),
(23, 17, 0),
(23, 34, 1),
(23, 39, 0),
(24, 20, 1),
(24, 33, 0),
(24, 36, 0),
(25, 31, 0),
(25, 35, 1),
(25, 39, 0),
(26, 19, 1),
(26, 36, 1),
(26, 38, 0);

-- --------------------------------------------------------

--
-- Structure de la table `MATCH`
--

CREATE TABLE `MATCH` (
  `numMatch` int NOT NULL,
  `codeEquipe` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `clubAdversaire` varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `numEquipeAdverse` int DEFAULT NULL,
  `saison` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phase` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `journee` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dateMatch` date NOT NULL,
  `heureMatch` time DEFAULT NULL,
  `lieuMatch` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scoreBec` int DEFAULT NULL,
  `scoreAdversaire` int DEFAULT NULL,
  `source` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `MATCH`
--

INSERT INTO `MATCH` (`numMatch`, `codeEquipe`, `clubAdversaire`, `numEquipeAdverse`, `saison`, `phase`, `journee`, `dateMatch`, `heureMatch`, `lieuMatch`, `scoreBec`, `scoreAdversaire`, `source`) VALUES
(1, 'SG1', 'US CHARTRONS BORDEAUX', NULL, '2025 - 2026', 'saison régulière', 'J1', '2025-09-20', '22:00:00', 'Extérieur', 43, 81, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(2, 'SG2', 'BORDEAUX BASTIDE BASKET', NULL, '2025 - 2026', 'saison régulière', 'J1', '2025-09-20', '22:00:00', 'Extérieur', 67, 60, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(3, 'SF3', 'SA GAZINET CESTAS', NULL, '2025 - 2026', 'saison régulière', 'J1', '2025-09-20', '22:30:00', 'Domicile', 64, 50, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145370'),
(4, 'SG3', 'ENTENTE SPORTIVE BLANQUEFORT', 2, '2025 - 2026', 'saison régulière', 'J1', '2025-09-21', '15:00:00', 'Extérieur', 102, 48, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145647'),
(5, 'SF2', 'BRESSUIRE LE REVEIL', NULL, '2025 - 2026', 'saison régulière', 'J1', '2025-09-21', '17:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(6, 'SF1', 'ABB CORNEBARRIEU', NULL, '2025 - 2026', 'saison régulière', 'J1', '2025-09-21', '17:30:00', 'Domicile', 75, 40, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(7, 'SG2', 'UNION SPORTIVE TULLE CORREZE', NULL, '2025 - 2026', 'saison régulière', 'J2', '2025-09-27', '20:00:00', 'Domicile', 69, 88, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(8, 'SF3', 'US CHARTRONS BORDEAUX', NULL, '2025 - 2026', 'saison régulière', 'J2', '2025-09-27', '22:00:00', 'Extérieur', 79, 33, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145370'),
(9, 'SG1', 'AYTRE BASKET BALL', NULL, '2025 - 2026', 'saison régulière', 'J2', '2025-09-27', '22:30:00', 'Domicile', 68, 52, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(10, 'SG3', 'STADE BORDELAIS', NULL, '2025 - 2026', 'saison régulière', 'J2', '2025-09-28', '15:00:00', 'Domicile', 61, 60, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145647'),
(11, 'SF2', 'AYTRE BASKET BALL', NULL, '2025 - 2026', 'saison régulière', 'J2', '2025-09-28', '17:00:00', 'Domicile', 52, 57, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(12, 'SF1', 'COTEAUX DU LUY BASKET', NULL, '2025 - 2026', 'saison régulière', 'J2', '2025-09-28', '17:30:00', 'Extérieur', 72, 76, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(13, 'SG1', 'CASTELNAU MEDOC BC', NULL, '2025 - 2026', 'saison régulière', 'J3', '2025-10-04', '22:00:00', 'Extérieur', 90, 105, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(14, 'SG2', 'UNION SAINT BRUNO BORDEAUX', NULL, '2025 - 2026', 'saison régulière', 'J3', '2025-10-04', '22:00:00', 'Extérieur', 52, 70, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(15, 'SF3', 'US TALENCE', NULL, '2025 - 2026', 'saison régulière', 'J3', '2025-10-04', '22:30:00', 'Domicile', 45, 52, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145370'),
(16, 'SF2', 'AMICALE LOISIRS CASTILLONNES BASKET', NULL, '2025 - 2026', 'saison régulière', 'J3', '2025-10-05', '17:00:00', 'Extérieur', 52, 47, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(17, 'SF1', 'ENTENTE PESSAC BASKET CLUB', 1, '2025 - 2026', 'saison régulière', 'J3', '2025-10-05', '17:30:00', 'Domicile', 82, 62, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(18, 'SG3', 'EN - CTC MEDOC ESTUAIRE - LUDON BASKET CLUB', 3, '2025 - 2026', 'saison régulière', 'J3', '2025-10-05', '19:00:00', 'Extérieur', 72, 49, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145647'),
(19, 'SG4', 'B.IZON', 2, '2025 - 2026', 'saison régulière', 'J3', '2025-10-05', '19:00:00', 'Extérieur', 34, 41, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005179778'),
(20, 'SG2', 'AIXE BC VAL DE VIENNE', NULL, '2025 - 2026', 'saison régulière', 'J4', '2025-10-11', '20:00:00', 'Domicile', 73, 64, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(21, 'SG1', 'CEP POITIERS', NULL, '2025 - 2026', 'saison régulière', 'J4', '2025-10-11', '22:30:00', 'Domicile', 57, 67, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(22, 'SF3', 'STE EULALIE BASKET BALL', NULL, '2025 - 2026', 'saison régulière', 'J4', '2025-10-11', '23:00:00', 'Extérieur', 57, 60, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145370'),
(23, 'SG4', 'BC ST AVIT ST NAZAIRE', NULL, '2025 - 2026', 'saison régulière', 'J4', '2025-10-12', '15:00:00', 'Extérieur', 63, 57, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005179778'),
(24, 'SF2', 'LIMOGES ABC EN LIMOUSIN', 2, '2025 - 2026', 'saison régulière', 'J4', '2025-10-12', '17:00:00', 'Domicile', 49, 53, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(25, 'SF1', 'AS ST DELPHIN', 2, '2025 - 2026', 'saison régulière', 'J4', '2025-10-12', '17:30:00', 'Extérieur', 74, 73, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(26, 'SG4', 'COUTRAS GUITRES BASKET', NULL, '2025 - 2026', 'saison régulière', 'J1', '2025-10-19', '15:00:00', 'Domicile', 46, 49, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005179778'),
(27, 'SG3', 'BASKET CLUB MARCHEPRIME', NULL, '2025 - 2026', 'saison régulière', 'J4', '2025-10-19', '17:00:00', 'Domicile', 83, 64, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145647'),
(28, 'SF1', 'HAGETMAU MOMUY CASTAIGNOS BASKET', NULL, '2025 - 2026', 'saison régulière', 'J5', '2025-10-26', '16:30:00', 'Domicile', 69, 53, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(29, 'SG1', 'JSA BORDEAUX BASKET', 2, '2025 - 2026', 'saison régulière', 'J5', '2025-11-01', '21:00:00', 'Extérieur', 72, 57, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(30, 'SG2', 'AS ST DELPHIN', NULL, '2025 - 2026', 'saison régulière', 'J5', '2025-11-01', '21:00:00', 'Extérieur', 62, 75, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(31, 'SF3', 'IE - CTC SMB - SAM - SA MERIGNACAIS', NULL, '2025 - 2026', 'saison régulière', 'J5', '2025-11-01', '21:30:00', 'Domicile', 54, 42, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145370'),
(32, 'SG3', 'AS MARTIGNAS', 2, '2025 - 2026', 'saison régulière', 'J5', '2025-11-02', '15:00:00', 'Extérieur', 53, 31, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145647'),
(33, 'SF2', 'POUZIOUX VOUNEUIL/BIARD BC', NULL, '2025 - 2026', 'saison régulière', 'J5', '2025-11-02', '16:00:00', 'Extérieur', 46, 76, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(34, 'SG4', 'STE EULALIE BASKET BALL', NULL, '2025 - 2026', 'saison régulière', 'J5', '2025-11-02', '16:00:00', 'Domicile', 38, 58, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005179778'),
(35, 'SF1', 'IE - AUCH BASKET CLUB', 1, '2025 - 2026', 'saison régulière', 'J6', '2025-11-02', '16:30:00', 'Extérieur', 75, 42, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(36, 'SG3', 'ENTENTE SPORTIVE BLANQUEFORT', 2, '2025 - 2026', 'saison régulière', 'J6', '2025-11-08', '19:00:00', 'Domicile', 111, 42, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145647'),
(37, 'SG2', 'CA BRIVE CORREZE SECTION BASKET', NULL, '2025 - 2026', 'saison régulière', 'J6', '2025-11-08', '21:00:00', 'Domicile', 79, 65, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(38, 'SF3', 'UNION SPORTIVE BREDOISE BASKET', 2, '2025 - 2026', 'saison régulière', 'J6', '2025-11-08', '22:00:00', 'Extérieur', 48, 58, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145370'),
(39, 'SF2', 'CA BRIVE CORREZE SECTION BASKET', NULL, '2025 - 2026', 'saison régulière', 'J6', '2025-11-09', '14:15:00', 'Domicile', 54, 45, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(40, 'SG4', 'COUTRAS GUITRES BASKET', NULL, '2025 - 2026', 'saison régulière', 'J6', '2025-11-09', '16:00:00', 'Extérieur', 34, 48, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005179778'),
(41, 'SF1', 'IE - CTC GRAND DAX BASKET - ADOUR DAX LANDES BASKET', NULL, '2025 - 2026', 'saison régulière', 'J7', '2025-11-09', '16:30:00', 'Domicile', 95, 43, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(42, 'SF3', 'LE TAILLAN BASKET', 2, '2025 - 2026', 'saison régulière', 'J7', '2025-11-15', '17:00:00', 'Domicile', 49, 52, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145370'),
(43, 'SG2', 'IE - CTC DORDOGNE SUD BASKET - US BERGERAC BASKET', NULL, '2025 - 2026', 'saison régulière', 'J7', '2025-11-15', '19:30:00', 'Domicile', 81, 55, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(44, 'SG1', 'COGNAC BASKET AVENIR', NULL, '2025 - 2026', 'saison régulière', 'J7', '2025-11-15', '21:30:00', 'Domicile', 74, 66, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(45, 'SF2', 'CA BEGLES', NULL, '2025 - 2026', 'saison régulière', 'J7', '2025-11-16', '16:00:00', 'Domicile', 42, 58, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(46, 'SG3', 'STADE BORDELAIS', NULL, '2025 - 2026', 'saison régulière', 'J7', '2025-11-16', '16:00:00', 'Extérieur', 58, 66, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145647'),
(47, 'SF1', 'B. COMMINGES SALIES DU SALAT', 1, '2025 - 2026', 'saison régulière', 'J8', '2025-11-16', '16:30:00', 'Extérieur', 89, 84, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(48, 'SG1', 'US CENON RIVE DROITE', NULL, '2025 - 2026', 'saison régulière', 'J8', '2025-11-27', '22:00:00', 'Domicile', 77, 69, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(49, 'SG2', 'ES ST FRONT DE PRADOUX', NULL, '2025 - 2026', 'saison régulière', 'J8', '2025-11-29', '21:00:00', 'Extérieur', 74, 61, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(50, 'SF3', 'SA GAZINET CESTAS', NULL, '2025 - 2026', 'saison régulière', 'J8', '2025-11-29', '21:30:00', 'Extérieur', 54, 41, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145370'),
(51, 'SG3', 'EN - CTC MEDOC ESTUAIRE - LUDON BASKET CLUB', 3, '2025 - 2026', 'saison régulière', 'J8', '2025-11-30', '14:00:00', 'Domicile', 75, 60, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145647'),
(52, 'SF2', 'CHAURAY BASKET CLUB', 2, '2025 - 2026', 'saison régulière', 'J8', '2025-11-30', '16:00:00', 'Extérieur', 52, 42, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(53, 'SG4', 'B.IZON', 2, '2025 - 2026', 'saison régulière', 'J8', '2025-11-30', '16:00:00', 'Domicile', 51, 28, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005179778'),
(54, 'SF1', 'LE TAILLAN BASKET', NULL, '2025 - 2026', 'saison régulière', 'J9', '2025-11-30', '16:30:00', 'Domicile', 86, 40, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(55, 'SF3', 'US CHARTRONS BORDEAUX', NULL, '2025 - 2026', 'saison régulière', 'J9', '2025-12-06', '17:00:00', 'Domicile', 65, 46, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145370'),
(56, 'SG2', 'BEAUNE-RILHAC-BONNAC BASKET', NULL, '2025 - 2026', 'saison régulière', 'J9', '2025-12-06', '19:00:00', 'Domicile', 63, 70, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(57, 'SG1', 'BOULAZAC BASKET DORDOGNE', 2, '2025 - 2026', 'saison régulière', 'J9', '2025-12-06', '21:15:00', 'Domicile', 71, 57, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(58, 'SF1', 'FEYTIAT BASKET 87', NULL, '2025 - 2026', 'saison régulière', 'J10', '2025-12-07', '14:00:00', 'Extérieur', 82, 53, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(59, 'SG4', 'BC ST AVIT ST NAZAIRE', NULL, '2025 - 2026', 'saison régulière', 'J9', '2025-12-07', '14:00:00', 'Domicile', 59, 56, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005179778'),
(60, 'SF2', 'UNION SPORTIVE BREDOISE BASKET', NULL, '2025 - 2026', 'saison régulière', 'J9', '2025-12-07', '16:00:00', 'Domicile', 43, 49, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(61, 'SG3', 'BASKET CLUB MARCHEPRIME', NULL, '2025 - 2026', 'saison régulière', 'J9', '2025-12-07', '16:00:00', 'Extérieur', 69, 64, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145647'),
(62, 'SF3', 'US TALENCE', NULL, '2025 - 2026', 'saison régulière', 'J10', '2025-12-13', '20:00:00', 'Extérieur', 66, 71, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145370'),
(63, 'SG4', 'STE EULALIE BASKET BALL', NULL, '2025 - 2026', 'saison régulière', 'J10', '2025-12-13', '20:00:00', 'Extérieur', 48, 60, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005179778'),
(64, 'SG1', 'ASPTT LIMOGES', NULL, '2025 - 2026', 'saison régulière', 'J10', '2025-12-13', '21:00:00', 'Extérieur', 85, 68, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(65, 'SG2', 'LIMOGES LANDOUGE LOISIRS BASKET', NULL, '2025 - 2026', 'saison régulière', 'J10', '2025-12-13', '21:30:00', 'Extérieur', 68, 83, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(66, 'SF2', 'ASPTT LIMOGES', NULL, '2025 - 2026', 'saison régulière', 'J10', '2025-12-14', '16:00:00', 'Extérieur', 58, 79, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(67, 'SG3', 'AS MARTIGNAS', 2, '2025 - 2026', 'saison régulière', 'J10', '2025-12-14', '16:00:00', 'Domicile', 68, 54, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145647'),
(68, 'SF3', 'STE EULALIE BASKET BALL', NULL, '2025 - 2026', 'saison régulière', 'J11', '2026-01-10', '21:00:00', 'Domicile', 46, 39, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145370'),
(69, 'SG1', 'ENTENTE PESSAC BASKET CLUB', NULL, '2025 - 2026', 'saison régulière', 'J11', '2026-01-10', '22:00:00', 'Extérieur', 60, 70, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(70, 'SG2', 'IE - CTC MEDOC ESTUAIRE - AS PIAN MEDOC BASKET', NULL, '2025 - 2026', 'saison régulière', 'J11', '2026-01-10', '22:00:00', 'Extérieur', 62, 84, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(71, 'SG3', 'STADE BORDELAIS', NULL, '2025 - 2026', 'play-off', 'J1', '2026-01-11', '14:00:00', 'Domicile', 77, 71, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248420'),
(72, 'SF2', 'IE - CTC UBVP - VILLENEUVE BASKET CLUB', NULL, '2025 - 2026', 'saison régulière', 'J11', '2026-01-11', '16:00:00', 'Extérieur', 54, 49, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(73, 'SG4', 'COUTRAS GUITRES BASKET', NULL, '2025 - 2026', 'play-off', 'J1', '2026-01-11', '16:00:00', 'Domicile', 36, 51, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248439'),
(74, 'SF1', 'ELAN CHALOSSAIS', NULL, '2025 - 2026', 'saison régulière', 'J11', '2026-01-11', '16:30:00', 'Extérieur', 66, 70, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(75, 'SG2', 'BORDEAUX BASTIDE BASKET', NULL, '2025 - 2026', 'saison régulière', 'J12', '2026-01-17', '19:00:00', 'Domicile', 65, 62, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(76, 'SG1', 'US CHARTRONS BORDEAUX', NULL, '2025 - 2026', 'saison régulière', 'J12', '2026-01-17', '21:15:00', 'Domicile', 72, 85, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(77, 'SF3', 'IE - CTC SMB - SAM - SA MERIGNACAIS', NULL, '2025 - 2026', 'saison régulière', 'J12', '2026-01-17', '21:30:00', 'Extérieur', 49, 51, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145370'),
(78, 'SG3', 'BOULIAC BASKET CLUB', 2, '2025 - 2026', 'play-off', 'J2', '2026-01-18', '14:00:00', 'Domicile', 50, 81, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248420'),
(79, 'SF2', 'BRESSUIRE LE REVEIL', NULL, '2025 - 2026', 'saison régulière', 'J12', '2026-01-18', '16:00:00', 'Domicile', 67, 46, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(80, 'SG4', 'CA CARBON BLANC OMNISPORT', NULL, '2025 - 2026', 'play-off', 'J2', '2026-01-18', '16:00:00', 'Extérieur', 42, 82, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248439'),
(81, 'SF1', 'ABB CORNEBARRIEU', NULL, '2025 - 2026', 'saison régulière', 'J12', '2026-01-18', '16:30:00', 'Extérieur', 85, 65, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(82, 'SF3', 'UNION SPORTIVE BREDOISE BASKET', 2, '2025 - 2026', 'saison régulière', 'J13', '2026-01-31', '21:00:00', 'Domicile', 58, 54, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145370'),
(83, 'SG2', 'UNION SPORTIVE TULLE CORREZE', NULL, '2025 - 2026', 'saison régulière', 'J13', '2026-01-31', '21:00:00', 'Extérieur', 68, 77, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(84, 'SG1', 'AYTRE BASKET BALL', NULL, '2025 - 2026', 'saison régulière', 'J13', '2026-01-31', '22:00:00', 'Extérieur', 56, 68, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(85, 'SG3', 'ENTENTE PESSAC BASKET CLUB', 3, '2025 - 2026', 'play-off', 'J3', '2026-02-01', '14:00:00', 'Domicile', 72, 63, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248420'),
(86, 'SF2', 'AYTRE BASKET BALL', NULL, '2025 - 2026', 'saison régulière', 'J13', '2026-02-01', '16:00:00', 'Extérieur', 54, 67, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(87, 'SG4', 'CA BEGLES', 3, '2025 - 2026', 'play-off', 'J3', '2026-02-01', '16:00:00', 'Extérieur', 36, 67, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248439'),
(88, 'SF1', 'COTEAUX DU LUY BASKET', NULL, '2025 - 2026', 'saison régulière', 'J13', '2026-02-01', '16:30:00', 'Domicile', 57, 59, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(89, 'SG2', 'UNION SAINT BRUNO BORDEAUX', NULL, '2025 - 2026', 'saison régulière', 'J14', '2026-02-07', '19:00:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(90, 'SG1', 'CASTELNAU MEDOC BC', NULL, '2025 - 2026', 'saison régulière', 'J14', '2026-02-07', '21:15:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(91, 'SF3', 'LE TAILLAN BASKET', 2, '2025 - 2026', 'saison régulière', 'J14', '2026-02-07', '21:30:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005145370'),
(92, 'SG4', 'STE EULALIE BASKET BALL', NULL, '2025 - 2026', 'play-off', 'J4', '2026-02-08', '14:00:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248439'),
(93, 'SF2', 'AMICALE LOISIRS CASTILLONNES BASKET', NULL, '2025 - 2026', 'saison régulière', 'J14', '2026-02-08', '16:00:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(94, 'SG3', 'AGJA CAUDERAN', 2, '2025 - 2026', 'play-off', 'J4', '2026-02-08', '16:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248420'),
(95, 'SF1', 'ENTENTE PESSAC BASKET CLUB', 1, '2025 - 2026', 'saison régulière', 'J14', '2026-02-08', '16:30:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(96, 'SF1', 'AS ST DELPHIN', 2, '2025 - 2026', 'saison régulière', 'J15', '2026-02-22', '16:30:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(97, 'SG1', 'CEP POITIERS', NULL, '2025 - 2026', 'saison régulière', 'J15', '2026-02-28', '21:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(98, 'SG2', 'AIXE BC VAL DE VIENNE', NULL, '2025 - 2026', 'saison régulière', 'J15', '2026-02-28', '21:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(99, 'SF2', 'LIMOGES ABC EN LIMOUSIN', 2, '2025 - 2026', 'saison régulière', 'J15', '2026-03-01', '16:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(100, 'SG3', 'BLEUETS ILLATS', 2, '2025 - 2026', 'play-off', 'J5', '2026-03-01', '16:00:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248420'),
(101, 'SG4', 'CASTELNAU MEDOC BC', 3, '2025 - 2026', 'play-off', 'J5', '2026-03-01', '16:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248439'),
(102, 'SF1', 'HAGETMAU MOMUY CASTAIGNOS BASKET', NULL, '2025 - 2026', 'saison régulière', 'J16', '2026-03-01', '16:30:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(103, 'SG2', 'AS ST DELPHIN', NULL, '2025 - 2026', 'saison régulière', 'J16', '2026-03-07', '19:00:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(104, 'SG1', 'JSA BORDEAUX BASKET', 2, '2025 - 2026', 'saison régulière', 'J16', '2026-03-07', '21:15:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(105, 'SF2', 'POUZIOUX VOUNEUIL/BIARD BC', NULL, '2025 - 2026', 'saison régulière', 'J16', '2026-03-08', '16:00:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(106, 'SG3', 'STADE BORDELAIS', NULL, '2025 - 2026', 'play-off', 'J6', '2026-03-08', '16:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248420'),
(107, 'SG4', 'COUTRAS GUITRES BASKET', NULL, '2025 - 2026', 'play-off', 'J6', '2026-03-08', '16:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248439'),
(108, 'SF1', 'IE - AUCH BASKET CLUB', 1, '2025 - 2026', 'saison régulière', 'J17', '2026-03-08', '16:30:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(109, 'SF2', 'CA BRIVE CORREZE SECTION BASKET', NULL, '2025 - 2026', 'saison régulière', 'J17', '2026-03-21', '19:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(110, 'SG2', 'CA BRIVE CORREZE SECTION BASKET', NULL, '2025 - 2026', 'saison régulière', 'J17', '2026-03-21', '21:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(111, 'SG3', 'BOULIAC BASKET CLUB', 2, '2025 - 2026', 'play-off', 'J7', '2026-03-21', '21:30:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248420'),
(112, 'SG4', 'CA CARBON BLANC OMNISPORT', NULL, '2025 - 2026', 'play-off', 'J7', '2026-03-22', '16:00:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248439'),
(113, 'SF1', 'IE - CTC GRAND DAX BASKET - ADOUR DAX LANDES BASKET', NULL, '2025 - 2026', 'saison régulière', 'J18', '2026-03-22', '16:30:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(114, 'SG1', 'COGNAC BASKET AVENIR', NULL, '2025 - 2026', 'saison régulière', 'J18', '2026-03-28', '21:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(115, 'SG2', 'IE - CTC DORDOGNE SUD BASKET - US BERGERAC BASKET', NULL, '2025 - 2026', 'saison régulière', 'J18', '2026-03-28', '21:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(116, 'SF2', 'CA BEGLES', NULL, '2025 - 2026', 'saison régulière', 'J18', '2026-03-29', '17:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(117, 'SG3', 'ENTENTE PESSAC BASKET CLUB', 3, '2025 - 2026', 'play-off', 'J8', '2026-03-29', '17:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248420'),
(118, 'SG4', 'CA BEGLES', 3, '2025 - 2026', 'play-off', 'J8', '2026-03-29', '17:00:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248439'),
(119, 'SF1', 'B. COMMINGES SALIES DU SALAT', 1, '2025 - 2026', 'saison régulière', 'J19', '2026-03-29', '17:30:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(120, 'SG2', 'ES ST FRONT DE PRADOUX', NULL, '2025 - 2026', 'saison régulière', 'J19', '2026-04-04', '20:00:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(121, 'SG4', 'STE EULALIE BASKET BALL', NULL, '2025 - 2026', 'play-off', 'J9', '2026-04-04', '21:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248439'),
(122, 'SG1', 'US CENON RIVE DROITE', NULL, '2025 - 2026', 'saison régulière', 'J19', '2026-04-04', '22:15:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(123, 'SF2', 'CHAURAY BASKET CLUB', 2, '2025 - 2026', 'saison régulière', 'J19', '2026-04-05', '17:00:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(124, 'SG3', 'AGJA CAUDERAN', 2, '2025 - 2026', 'play-off', 'J9', '2026-04-05', '17:00:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248420'),
(125, 'SF1', 'LE TAILLAN BASKET', NULL, '2025 - 2026', 'saison régulière', 'J20', '2026-04-05', '17:30:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(126, 'SF1', 'FEYTIAT BASKET 87', NULL, '2025 - 2026', 'saison régulière', 'J21', '2026-04-12', '17:30:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(127, 'SF1', 'ELAN CHALOSSAIS', NULL, '2025 - 2026', 'saison régulière', 'J22', '2026-04-19', '17:30:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139512'),
(128, 'SG1', 'BOULAZAC BASKET DORDOGNE', 2, '2025 - 2026', 'saison régulière', 'J20', '2026-04-25', '22:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(129, 'SG2', 'BEAUNE-RILHAC-BONNAC BASKET', NULL, '2025 - 2026', 'saison régulière', 'J20', '2026-04-25', '22:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(130, 'SG3', 'BLEUETS ILLATS', 2, '2025 - 2026', 'play-off', 'J10', '2026-04-26', '15:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248420'),
(131, 'SF2', 'UNION SPORTIVE BREDOISE BASKET', NULL, '2025 - 2026', 'saison régulière', 'J20', '2026-04-26', '17:00:00', 'Extérieur', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(132, 'SG4', 'CASTELNAU MEDOC BC', 3, '2025 - 2026', 'play-off', 'J10', '2026-04-26', '17:00:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005248439'),
(133, 'SG2', 'LIMOGES LANDOUGE LOISIRS BASKET', NULL, '2025 - 2026', 'saison régulière', 'J21', '2026-05-02', '20:00:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(134, 'SG1', 'ASPTT LIMOGES', NULL, '2025 - 2026', 'saison régulière', 'J21', '2026-05-02', '22:15:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(135, 'SF2', 'ASPTT LIMOGES', NULL, '2025 - 2026', 'saison régulière', 'J21', '2026-05-03', '17:00:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159'),
(136, 'SG2', 'IE - CTC MEDOC ESTUAIRE - AS PIAN MEDOC BASKET', NULL, '2025 - 2026', 'saison régulière', 'J22', '2026-05-09', '20:00:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005138117'),
(137, 'SG1', 'ENTENTE PESSAC BASKET CLUB', NULL, '2025 - 2026', 'saison régulière', 'J22', '2026-05-09', '22:15:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005137983'),
(138, 'SF2', 'IE - CTC UBVP - VILLENEUVE BASKET CLUB', NULL, '2025 - 2026', 'saison régulière', 'J22', '2026-05-10', '17:00:00', 'Domicile', NULL, NULL, 'https://competitions.ffbb.com/ligues/naq/comites/0033/clubs/naq0033024/equipes/200000005139159');

-- --------------------------------------------------------

--
-- Structure de la table `MEMBRE`
--

CREATE TABLE `MEMBRE` (
  `numMemb` int NOT NULL,
  `prenomMemb` varchar(70) COLLATE utf8mb3_unicode_ci NOT NULL,
  `nomMemb` varchar(70) COLLATE utf8mb3_unicode_ci NOT NULL,
  `pseudoMemb` varchar(70) COLLATE utf8mb3_unicode_ci NOT NULL,
  `passMemb` varchar(70) COLLATE utf8mb3_unicode_ci NOT NULL,
  `eMailMemb` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `dtCreaMemb` datetime DEFAULT CURRENT_TIMESTAMP,
  `dtMajMemb` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `accordMemb` tinyint(1) DEFAULT '1',
  `cookieMemb` varchar(70) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `numStat` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `MEMBRE`
--

INSERT INTO `MEMBRE` (`numMemb`, `prenomMemb`, `nomMemb`, `pseudoMemb`, `passMemb`, `eMailMemb`, `dtCreaMemb`, `dtMajMemb`, `accordMemb`, `cookieMemb`, `numStat`) VALUES
(1, 'Freddie', 'Mercury', 'Admin99', '12345678', 'freddie.mercury@gmail.com', '2019-05-29 10:13:43', NULL, 1, NULL, 1),
(2, 'Phil', 'Collins', 'Phil09', '12345678', 'phil.collins@gmail.com', '2020-01-09 10:13:43', NULL, 1, NULL, 2),
(3, 'Julie', 'La Rousse', 'juju1989', '12345678', 'julie.larousse@gmail.com', '2020-03-15 14:33:23', '2024-01-12 14:36:48', 1, NULL, 3),
(6, 'Mehdi', 'Afankous', 'Afanpeak', '$2y$10$IkvW2agoxm5EJcOUU4ov5ePGn7IQyZyqDWcGX4trjN1t.Y.jB36Zq', 'afantastik041@gmail.com', '2026-02-06 10:48:40', '2026-02-06 14:03:44', 1, '0', 1),
(7, 'Lucas', 'Martin', 'lucasmar10', '12345678', 'lucas.martin@example.com', '2026-02-06 16:00:00', NULL, 1, NULL, 3),
(8, 'Emma', 'Bernard', 'emmaber11', '12345678', 'emma.bernard@example.com', '2026-02-06 16:01:00', NULL, 1, NULL, 3),
(9, 'Nathan', 'Dubois', 'nathandub12', '12345678', 'nathan.dubois@example.com', '2026-02-06 16:02:00', NULL, 1, NULL, 3),
(10, 'Chloé', 'Thomas', 'chloetho13', '12345678', 'chloe.thomas@example.com', '2026-02-06 16:03:00', NULL, 1, NULL, 3),
(11, 'Hugo', 'Robert', 'hugorob14', '12345678', 'hugo.robert@example.com', '2026-02-06 16:04:00', NULL, 1, NULL, 3),
(12, 'Léa', 'Richard', 'learic15', '12345678', 'lea.richard@example.com', '2026-02-06 16:05:00', NULL, 1, NULL, 3),
(13, 'Tom', 'Petit', 'tomp et16', '12345678', 'tom.petit@example.com', '2026-02-06 16:06:00', NULL, 1, NULL, 3),
(14, 'Manon', 'Durand', 'manondur17', '12345678', 'manon.durand@example.com', '2026-02-06 16:07:00', NULL, 1, NULL, 3),
(15, 'Enzo', 'Leroy', 'enzoler18', '12345678', 'enzo.leroy@example.com', '2026-02-06 16:08:00', NULL, 1, NULL, 3),
(16, 'Camille', 'Moreau', 'camillemor19', '12345678', 'camille.moreau@example.com', '2026-02-06 16:09:00', NULL, 1, NULL, 3),
(17, 'Jules', 'Simon', 'julessim20', '12345678', 'jules.simon@example.com', '2026-02-06 16:10:00', NULL, 1, NULL, 3),
(18, 'Sarah', 'Laurent', 'sarahlau21', '12345678', 'sarah.laurent@example.com', '2026-02-06 16:11:00', NULL, 1, NULL, 3),
(19, 'Louis', 'Lefebvre', 'louisl ef22', '12345678', 'louis.lefebvre@example.com', '2026-02-06 16:12:00', NULL, 1, NULL, 3),
(20, 'Inès', 'Michel', 'inesmic23', '12345678', 'ines.michel@example.com', '2026-02-06 16:13:00', NULL, 1, NULL, 3),
(21, 'Noah', 'Garcia', 'noahgar24', '12345678', 'noah.garcia@example.com', '2026-02-06 16:14:00', NULL, 1, NULL, 3),
(22, 'Jade', 'David', 'jadedav25', '12345678', 'jade.david@example.com', '2026-02-06 16:15:00', NULL, 1, NULL, 3),
(23, 'Arthur', 'Bertrand', 'arthurber26', '12345678', 'arthur.bertrand@example.com', '2026-02-06 16:16:00', NULL, 1, NULL, 3),
(24, 'Zoé', 'Roux', 'zoerou27', '12345678', 'zoe.roux@example.com', '2026-02-06 16:17:00', NULL, 1, NULL, 3),
(25, 'Maxime', 'Vincent', 'maximevin28', '12345678', 'maxime.vincent@example.com', '2026-02-06 16:18:00', NULL, 1, NULL, 3),
(26, 'Clara', 'Fournier', 'clarafou29', '12345678', 'clara.fournier@example.com', '2026-02-06 16:19:00', NULL, 1, NULL, 3);

-- --------------------------------------------------------

--
-- Structure de la table `MOTCLE`
--

CREATE TABLE `MOTCLE` (
  `numMotCle` int NOT NULL,
  `libMotCle` varchar(60) COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `MOTCLE`
--

INSERT INTO `MOTCLE` (`numMotCle`, `libMotCle`) VALUES
(1, 'Bordeaux'),
(2, 'Basket'),
(3, 'Club'),
(4, 'esprit d&#039;équipe'),
(5, 'Passion'),
(6, 'Benevole'),
(7, 'FFBB'),
(8, 'Associatif'),
(9, 'Supporter'),
(10, 'Championnat'),
(11, 'Victoire'),
(12, 'Match à domicile'),
(13, 'Match à l\'exterieur'),
(14, 'Saison sportive');

-- --------------------------------------------------------

--
-- Structure de la table `MOTCLEARTICLE`
--

CREATE TABLE `MOTCLEARTICLE` (
  `numArt` int NOT NULL,
  `numMotCle` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `PERSONNEL`
--

CREATE TABLE `PERSONNEL` (
  `numPersonnel` int NOT NULL,
  `surnomPersonnel` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenomPersonnel` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomPersonnel` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `urlPhotoPersonnel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emailPersonnel` varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephonePersonnel` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estStaffEquipe` tinyint(1) NOT NULL DEFAULT '0',
  `numEquipeStaff` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roleStaffEquipe` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estDirection` tinyint(1) NOT NULL DEFAULT '0',
  `posteDirection` varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estCommissionTechnique` tinyint(1) NOT NULL DEFAULT '0',
  `posteCommissionTechnique` varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estCommissionAnimation` tinyint(1) NOT NULL DEFAULT '0',
  `posteCommissionAnimation` varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estCommissionCommunication` tinyint(1) NOT NULL DEFAULT '0',
  `posteCommissionCommunication` varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `PERSONNEL`
--

INSERT INTO `PERSONNEL` (`numPersonnel`, `surnomPersonnel`, `prenomPersonnel`, `nomPersonnel`, `urlPhotoPersonnel`, `emailPersonnel`, `telephonePersonnel`, `estStaffEquipe`, `numEquipeStaff`, `roleStaffEquipe`, `estDirection`, `posteDirection`, `estCommissionTechnique`, `posteCommissionTechnique`, `estCommissionAnimation`, `posteCommissionAnimation`, `estCommissionCommunication`, `posteCommissionCommunication`) VALUES
(26, 'ANIM-BEN1', 'Sandra', 'Collet', NULL, 'animation1@bec.fr', '0610000011', 0, NULL, NULL, 0, NULL, 0, NULL, 1, 'Membre commission animation', 0, NULL),
(27, 'ANIM-BEN2', 'Guillaume', 'Pages', NULL, 'animation2@bec.fr', '0610000012', 0, NULL, NULL, 0, NULL, 0, NULL, 1, 'Membre commission animation', 0, NULL),
(28, 'ANIM-BEN3', 'Alexia', 'Fabre', NULL, 'animation3@bec.fr', '0610000013', 0, NULL, NULL, 0, NULL, 0, NULL, 1, 'Membre commission animation', 0, NULL),
(29, 'ANIM-BEN4', 'Vincent', 'Archer', NULL, 'animation4@bec.fr', '0610000014', 0, NULL, NULL, 0, NULL, 0, NULL, 1, 'Membre commission animation', 0, NULL),
(30, 'ANIM-BEN5', 'Marine', 'Gallet', NULL, 'animation5@bec.fr', '0610000015', 0, NULL, NULL, 0, NULL, 0, NULL, 1, 'Membre commission animation', 0, NULL),
(3, 'ASST-SEN1', 'Marc', 'Assistant', NULL, 'assistant.sen1@bec.fr', '0600000002', 1, 'SG1', 'Assistant coach', 0, NULL, 0, NULL, 0, NULL, 0, NULL),
(5, 'ASST-SEN2', 'Romain', 'Assistant', NULL, 'assistant.sen2@bec.fr', '0600000004', 1, 'SG2', 'Assistant coach', 0, NULL, 0, NULL, 0, NULL, 0, NULL),
(7, 'ASST-SEN3', 'Florian', 'Assistant', NULL, 'assistant.sen3@bec.fr', '0600000006', 1, 'SG4', 'Assistant coach', 0, NULL, 0, NULL, 0, NULL, 0, NULL),
(9, 'ASST-SEN4', 'Quentin', 'Assistant', NULL, 'assistant.sen4@bec.fr', '0600000008', 1, 'SF1', 'Assistant coach', 0, NULL, 0, NULL, 0, NULL, 0, NULL),
(11, 'ASST-SEN5', 'Julie', 'Assistant', NULL, 'assistant.sen5@bec.fr', '0600000010', 1, 'SF2', 'Assistant coach', 0, NULL, 0, NULL, 0, NULL, 0, NULL),
(13, 'ASST-SEN6', 'Emma', 'Assistant', NULL, 'assistant.sen6@bec.fr', '0600000012', 1, 'SF3', 'Assistant coach', 0, NULL, 0, NULL, 0, NULL, 0, NULL),
(2, 'COACH-SEN1', 'Pierre', 'Coach', NULL, 'coach.sen1@bec.fr', '0600000001', 1, 'SG1', 'Coach', 0, NULL, 0, NULL, 0, NULL, 0, NULL),
(4, 'COACH-SEN2', 'Julien', 'Coach', NULL, 'coach.sen2@bec.fr', '0600000003', 1, 'SG2', 'Coach', 0, NULL, 0, NULL, 0, NULL, 0, NULL),
(6, 'COACH-SEN3', 'Nicolas', 'Coach', NULL, 'coach.sen3@bec.fr', '0600000005', 1, 'SG4', 'Coach', 0, NULL, 0, NULL, 0, NULL, 0, NULL),
(8, 'COACH-SEN4', 'Baptiste', 'Coach', NULL, 'coach.sen4@bec.fr', '0600000007', 1, 'SF1', 'Coach', 0, NULL, 0, NULL, 0, NULL, 0, NULL),
(10, 'COACH-SEN5', 'Sophie', 'Coach', NULL, 'coach.sen5@bec.fr', '0600000009', 1, 'SF2', 'Coach', 0, NULL, 0, NULL, 0, NULL, 0, NULL),
(12, 'COACH-SEN6', 'Claire', 'Coach', NULL, 'coach.sen6@bec.fr', '0600000011', 1, 'SF3', 'Coach', 0, NULL, 0, NULL, 0, NULL, 0, NULL),
(31, 'COM-BEN1', 'Caroline', 'Vallin', NULL, 'com1@bec.fr', '0610000016', 0, NULL, NULL, 0, NULL, 0, NULL, 0, NULL, 1, 'Membre commission communication'),
(32, 'COM-BEN2', 'Anthony', 'Blanc', NULL, 'com2@bec.fr', '0610000017', 0, NULL, NULL, 0, NULL, 0, NULL, 0, NULL, 1, 'Membre commission communication'),
(33, 'COM-BEN3', 'Laura', 'Vigne', NULL, 'com3@bec.fr', '0610000018', 0, NULL, NULL, 0, NULL, 0, NULL, 0, NULL, 1, 'Membre commission communication'),
(34, 'COM-BEN4', 'Jeremy', 'Tessier', NULL, 'com4@bec.fr', '0610000019', 0, NULL, NULL, 0, NULL, 0, NULL, 0, NULL, 1, 'Membre commission communication'),
(35, 'COM-BEN5', 'Pauline', 'Serre', NULL, 'com5@bec.fr', '0610000020', 0, NULL, NULL, 0, NULL, 0, NULL, 0, NULL, 1, 'Membre commission communication'),
(16, 'DIR-BEN1', 'Nathalie', 'Perrier', NULL, 'direction1@bec.fr', '0610000001', 0, NULL, NULL, 1, 'Membre direction', 0, NULL, 0, NULL, 0, NULL),
(17, 'DIR-BEN2', 'Olivier', 'Lemoine', NULL, 'direction2@bec.fr', '0610000002', 0, NULL, NULL, 1, 'Membre direction', 0, NULL, 0, NULL, 0, NULL),
(18, 'DIR-BEN3', 'Sonia', 'Marin', NULL, 'direction3@bec.fr', '0610000003', 0, NULL, NULL, 1, 'Membre direction', 0, NULL, 0, NULL, 0, NULL),
(19, 'DIR-BEN4', 'Eric', 'Legrand', NULL, 'direction4@bec.fr', '0610000004', 0, NULL, NULL, 1, 'Membre direction', 0, NULL, 0, NULL, 0, NULL),
(20, 'DIR-BEN5', 'Isabelle', 'Noel', NULL, 'direction5@bec.fr', '0610000005', 0, NULL, NULL, 1, 'Membre direction', 0, NULL, 0, NULL, 0, NULL),
(1, 'mehdiafankous', 'mehdi', 'afankous', '/src/uploads/photos-benevoles/af.mehdi.jpg', NULL, NULL, 1, 'SG3', 'coach', 1, 'président', 1, 'responsable technique', 1, 'annimateur', 1, 'community manager'),
(21, 'TECH-BEN1', 'Damien', 'Perrot', NULL, 'tech1@bec.fr', '0610000006', 0, NULL, NULL, 0, NULL, 1, 'Membre commission technique', 0, NULL, 0, NULL),
(22, 'TECH-BEN2', 'Laurent', 'Benoit', NULL, 'tech2@bec.fr', '0610000007', 0, NULL, NULL, 0, NULL, 1, 'Membre commission technique', 0, NULL, 0, NULL),
(23, 'TECH-BEN3', 'Celine', 'Guerin', NULL, 'tech3@bec.fr', '0610000008', 0, NULL, NULL, 0, NULL, 1, 'Membre commission technique', 0, NULL, 0, NULL),
(24, 'TECH-BEN4', 'Pascal', 'Leger', NULL, 'tech4@bec.fr', '0610000009', 0, NULL, NULL, 0, NULL, 1, 'Membre commission technique', 0, NULL, 0, NULL),
(25, 'TECH-BEN5', 'Marion', 'Jacquet', NULL, 'tech5@bec.fr', '0610000010', 0, NULL, NULL, 0, NULL, 1, 'Membre commission technique', 0, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `STATUT`
--

CREATE TABLE `STATUT` (
  `numStat` int NOT NULL,
  `libStat` varchar(25) COLLATE utf8mb3_unicode_ci NOT NULL,
  `dtCreaStat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `STATUT`
--

INSERT INTO `STATUT` (`numStat`, `libStat`, `dtCreaStat`) VALUES
(1, 'Administrateur', '2023-02-19 15:15:59'),
(2, 'Modérateur', '2023-02-19 15:19:12'),
(3, 'Membre', '2023-02-20 08:43:24');

-- --------------------------------------------------------

--
-- Structure de la table `THEMATIQUE`
--

CREATE TABLE `THEMATIQUE` (
  `numThem` int NOT NULL,
  `libThem` varchar(60) COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `THEMATIQUE`
--

INSERT INTO `THEMATIQUE` (`numThem`, `libThem`) VALUES
(1, 'L\'événement'),
(2, 'L\'acteur-clé'),
(3, 'Le mouvement émergeant'),
(4, 'L\'insolite / le clin d\'œil');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `ARTICLE`
--
ALTER TABLE `ARTICLE`
  ADD PRIMARY KEY (`numArt`),
  ADD KEY `ARTICLE_FK` (`numArt`),
  ADD KEY `FK_ASSOCIATION_1` (`numThem`);

--
-- Index pour la table `boutique`
--
ALTER TABLE `boutique`
  ADD PRIMARY KEY (`numArtBoutique`);

--
-- Index pour la table `COMMENT`
--
ALTER TABLE `COMMENT`
  ADD PRIMARY KEY (`numCom`),
  ADD KEY `COMMENT_FK` (`numCom`),
  ADD KEY `FK_ASSOCIATION_2` (`numArt`),
  ADD KEY `FK_ASSOCIATION_3` (`numMemb`);

--
-- Index pour la table `EQUIPE`
--
ALTER TABLE `EQUIPE`
  ADD PRIMARY KEY (`codeEquipe`),
  ADD UNIQUE KEY `uniq_equipe_num` (`numEquipe`);

--
-- Index pour la table `JOUEUR`
--
ALTER TABLE `JOUEUR`
  ADD PRIMARY KEY (`surnomJoueur`),
  ADD UNIQUE KEY `uniq_joueur_num` (`numJoueur`),
  ADD KEY `idx_joueur_equipe` (`codeEquipe`);

--
-- Index pour la table `LIKEART`
--
ALTER TABLE `LIKEART`
  ADD PRIMARY KEY (`numMemb`,`numArt`),
  ADD KEY `LIKEART_FK` (`numMemb`,`numArt`),
  ADD KEY `FK_LIKEART1` (`numArt`);

--
-- Index pour la table `MATCH`
--
ALTER TABLE `MATCH`
  ADD PRIMARY KEY (`numMatch`),
  ADD KEY `idx_match_equipe` (`codeEquipe`);

--
-- Index pour la table `MEMBRE`
--
ALTER TABLE `MEMBRE`
  ADD PRIMARY KEY (`numMemb`),
  ADD KEY `MEMBRE_FK` (`numMemb`),
  ADD KEY `FK_ASSOCIATION_4` (`numStat`);

--
-- Index pour la table `MOTCLE`
--
ALTER TABLE `MOTCLE`
  ADD PRIMARY KEY (`numMotCle`),
  ADD KEY `MOTCLE_FK` (`numMotCle`);

--
-- Index pour la table `MOTCLEARTICLE`
--
ALTER TABLE `MOTCLEARTICLE`
  ADD PRIMARY KEY (`numArt`,`numMotCle`),
  ADD KEY `MOTCLEARTICLE_FK` (`numArt`),
  ADD KEY `MOTCLEARTICLE2_FK` (`numMotCle`);

--
-- Index pour la table `PERSONNEL`
--
ALTER TABLE `PERSONNEL`
  ADD PRIMARY KEY (`surnomPersonnel`),
  ADD UNIQUE KEY `uniq_personnel_num` (`numPersonnel`),
  ADD KEY `idx_personnel_equipe` (`numEquipeStaff`);

--
-- Index pour la table `STATUT`
--
ALTER TABLE `STATUT`
  ADD PRIMARY KEY (`numStat`),
  ADD KEY `STATUT_FK` (`numStat`);

--
-- Index pour la table `THEMATIQUE`
--
ALTER TABLE `THEMATIQUE`
  ADD PRIMARY KEY (`numThem`),
  ADD KEY `THEMATIQUE_FK` (`numThem`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `ARTICLE`
--
ALTER TABLE `ARTICLE`
  MODIFY `numArt` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `boutique`
--
ALTER TABLE `boutique`
  MODIFY `numArtBoutique` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `COMMENT`
--
ALTER TABLE `COMMENT`
  MODIFY `numCom` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=365;

--
-- AUTO_INCREMENT pour la table `EQUIPE`
--
ALTER TABLE `EQUIPE`
  MODIFY `numEquipe` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `JOUEUR`
--
ALTER TABLE `JOUEUR`
  MODIFY `numJoueur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT pour la table `MATCH`
--
ALTER TABLE `MATCH`
  MODIFY `numMatch` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT pour la table `MEMBRE`
--
ALTER TABLE `MEMBRE`
  MODIFY `numMemb` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `MOTCLE`
--
ALTER TABLE `MOTCLE`
  MODIFY `numMotCle` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `PERSONNEL`
--
ALTER TABLE `PERSONNEL`
  MODIFY `numPersonnel` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `STATUT`
--
ALTER TABLE `STATUT`
  MODIFY `numStat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `THEMATIQUE`
--
ALTER TABLE `THEMATIQUE`
  MODIFY `numThem` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `ARTICLE`
--
ALTER TABLE `ARTICLE`
  ADD CONSTRAINT `FK_ASSOCIATION_1` FOREIGN KEY (`numThem`) REFERENCES `THEMATIQUE` (`numThem`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `COMMENT`
--
ALTER TABLE `COMMENT`
  ADD CONSTRAINT `FK_ASSOCIATION_2` FOREIGN KEY (`numArt`) REFERENCES `ARTICLE` (`numArt`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_ASSOCIATION_3` FOREIGN KEY (`numMemb`) REFERENCES `MEMBRE` (`numMemb`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `JOUEUR`
--
ALTER TABLE `JOUEUR`
  ADD CONSTRAINT `fk_joueur_equipe` FOREIGN KEY (`codeEquipe`) REFERENCES `EQUIPE` (`codeEquipe`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `LIKEART`
--
ALTER TABLE `LIKEART`
  ADD CONSTRAINT `FK_LIKEART1` FOREIGN KEY (`numArt`) REFERENCES `ARTICLE` (`numArt`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_LIKEART2` FOREIGN KEY (`numMemb`) REFERENCES `MEMBRE` (`numMemb`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `MATCH`
--
ALTER TABLE `MATCH`
  ADD CONSTRAINT `fk_match_equipe` FOREIGN KEY (`codeEquipe`) REFERENCES `EQUIPE` (`codeEquipe`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `MEMBRE`
--
ALTER TABLE `MEMBRE`
  ADD CONSTRAINT `FK_ASSOCIATION_4` FOREIGN KEY (`numStat`) REFERENCES `STATUT` (`numStat`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `MOTCLEARTICLE`
--
ALTER TABLE `MOTCLEARTICLE`
  ADD CONSTRAINT `FK_MotCleArt1` FOREIGN KEY (`numMotCle`) REFERENCES `MOTCLE` (`numMotCle`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_MotCleArt2` FOREIGN KEY (`numArt`) REFERENCES `ARTICLE` (`numArt`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `PERSONNEL`
--
ALTER TABLE `PERSONNEL`
  ADD CONSTRAINT `fk_personnel_equipe` FOREIGN KEY (`numEquipeStaff`) REFERENCES `EQUIPE` (`codeEquipe`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
