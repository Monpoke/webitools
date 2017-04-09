-- phpMyAdmin SQL Dump
-- version 4.0.10.12
-- http://www.phpmyadmin.net
--
-- Client: 127.13.97.130:3306
-- Généré le: Dim 09 Avril 2017 à 11:43
-- Version du serveur: 5.5.52
-- Version de PHP: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `webitools`
--

-- --------------------------------------------------------

--
-- Structure de la table `bans_chat`
--

CREATE TABLE IF NOT EXISTS `bans_chat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_site` int(10) unsigned NOT NULL,
  `ip` varchar(45) NOT NULL,
  `pseudo` varchar(45) NOT NULL,
  PRIMARY KEY (`id`,`id_site`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- Structure de la table `connexions`
--

CREATE TABLE IF NOT EXISTS `connexions` (
  `id_site` int(11) NOT NULL,
  `date_modif` int(11) NOT NULL,
  `texte_pseudo` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Pseudo',
  `texte_pass1` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Mot de passe',
  `texte_pass2` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Confirmer votre mot de passe',
  `texte_email` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Email',
  `texte_date` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Date de naissance (jj-mm-aaaa)',
  `texte_sexe` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Sexe',
  `texte_btn_inscrire` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Connexion',
  `champ_1` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ffffff;',
  `champ_2` int(3) unsigned NOT NULL DEFAULT '950',
  `champ_3` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '000000',
  `champ_4` int(3) unsigned NOT NULL DEFAULT '500',
  `champ_5` int(2) unsigned NOT NULL DEFAULT '15',
  `champ_6` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ffffff',
  `champ_7` int(2) unsigned NOT NULL DEFAULT '10',
  `champ_8` int(2) NOT NULL DEFAULT '2',
  `champ_9` varchar(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '20',
  `champ_10` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '20;20',
  `champ_14` varchar(13) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'FFEFD5',
  `champ_12` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'solid 1px 000000',
  `champ_15` int(2) unsigned NOT NULL DEFAULT '50',
  `champ_16` int(2) unsigned NOT NULL DEFAULT '50',
  `champ_17` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '25',
  `champ_18` int(2) unsigned NOT NULL DEFAULT '40',
  `champ_19` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'left',
  `champ_20` varchar(13) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'FFB200',
  `champ_21` varchar(13) COLLATE utf8_unicode_ci NOT NULL DEFAULT '00FFE2',
  `champ_header` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `champ_22` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `champ_23` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `champ_24` int(2) unsigned NOT NULL DEFAULT '15',
  `champ_25` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '000000',
  `champ_26` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '000000',
  `champ_27` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '000000',
  `champ_28` int(2) NOT NULL DEFAULT '40',
  `champ_29` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `champ_30` int(20) unsigned NOT NULL,
  `type` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `champ_31` int(10) unsigned NOT NULL,
  `champ_32` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `champ_33` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `champ_34` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `champ_35` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `test_pseudo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `test_pass` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_site`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions`
--

CREATE TABLE IF NOT EXISTS `inscriptions` (
  `id_site` int(11) NOT NULL,
  `date_modif` int(11) NOT NULL,
  `texte_pseudo` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Pseudo',
  `texte_pass1` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Mot de passe',
  `texte_pass2` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Confirmer votre mot de passe',
  `texte_email` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Email',
  `texte_date` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Date de naissance (jj-mm-aaaa)',
  `texte_sexe` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Sexe',
  `texte_btn_inscrire` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S\\''inscrire',
  `champ_1` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ffffff;',
  `champ_2` int(3) unsigned NOT NULL DEFAULT '950',
  `champ_3` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '000000',
  `champ_4` int(3) unsigned NOT NULL DEFAULT '500',
  `champ_5` int(2) unsigned NOT NULL DEFAULT '15',
  `champ_6` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ffffff',
  `champ_7` int(2) unsigned NOT NULL DEFAULT '10',
  `champ_8` int(2) NOT NULL DEFAULT '2',
  `champ_9` varchar(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '20',
  `champ_10` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '20;20',
  `champ_14` varchar(13) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'FFEFD5',
  `champ_12` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'solid 1px 000000',
  `champ_15` int(2) unsigned NOT NULL DEFAULT '50',
  `champ_16` int(2) unsigned NOT NULL DEFAULT '50',
  `champ_17` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '25',
  `champ_18` int(2) unsigned NOT NULL DEFAULT '40',
  `champ_19` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'left',
  `champ_20` varchar(13) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'FFB200',
  `champ_21` varchar(13) COLLATE utf8_unicode_ci NOT NULL DEFAULT '00FFE2',
  `champ_header` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `champ_22` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `champ_23` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `champ_24` int(2) unsigned NOT NULL DEFAULT '15',
  `champ_25` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '000000',
  `champ_26` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '000000',
  `champ_27` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '000000',
  `champ_28` int(2) NOT NULL DEFAULT '40',
  `champ_29` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `champ_30` int(20) unsigned NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 : page module ; 2 : integre',
  `champ_31` int(11) NOT NULL,
  `champ_32` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `champ_33` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `champ_34` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `champ_35` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `champ_36` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 : redirection ; 0 : msg',
  PRIMARY KEY (`id_site`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `liensjours`
--

CREATE TABLE IF NOT EXISTS `liensjours` (
  `id_site` int(11) NOT NULL,
  `date_modif` int(11) NOT NULL,
  `lien_1` varchar(100) NOT NULL,
  `lien_2` varchar(100) NOT NULL,
  `lien_3` varchar(100) NOT NULL,
  `lien_4` varchar(45) NOT NULL,
  `lien_5` varchar(100) NOT NULL,
  `lien_6` varchar(100) NOT NULL,
  `lien_7` varchar(100) NOT NULL,
  `en_cours` varchar(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_site`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `membres`
--

CREATE TABLE IF NOT EXISTS `membres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `pass` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `date_inscription` int(11) NOT NULL,
  `rang` tinyint(4) NOT NULL,
  `ip` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `derniere_connexion` int(11) NOT NULL,
  `pub` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'oui',
  `vip` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'non',
  `fin_vip` int(11) NOT NULL,
  `reglement` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'non',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=572 ;

-- --------------------------------------------------------

--
-- Structure de la table `mg_news`
--

CREATE TABLE IF NOT EXISTS `mg_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auteur` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `date` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `news` text COLLATE utf8_unicode_ci NOT NULL,
  `suite` text COLLATE utf8_unicode_ci NOT NULL,
  `idcat` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `mg_news_cat`
--

CREATE TABLE IF NOT EXISTS `mg_news_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `mg_news_comment`
--

CREATE TABLE IF NOT EXISTS `mg_news_comment` (
  `idcom` int(11) NOT NULL AUTO_INCREMENT,
  `idnews` int(11) NOT NULL DEFAULT '0',
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pseudo` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `date` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`idcom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `parrainages`
--

CREATE TABLE IF NOT EXISTS `parrainages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_site` int(11) NOT NULL,
  `pseudo_parrain` varchar(45) NOT NULL,
  `pseudo_filleuil` varchar(45) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=87 ;

-- --------------------------------------------------------

--
-- Structure de la table `publicites`
--

CREATE TABLE IF NOT EXISTS `publicites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) NOT NULL,
  `image` varchar(255) NOT NULL,
  `texte` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `date_ajout` int(11) NOT NULL,
  `debut_campagne` int(11) NOT NULL,
  `fin_campagne` int(11) NOT NULL,
  `out` int(11) NOT NULL DEFAULT '0',
  `emplacement` int(10) unsigned NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `publicites_out`
--

CREATE TABLE IF NOT EXISTS `publicites_out` (
  `id_campagne` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `sites`
--

CREATE TABLE IF NOT EXISTS `sites` (
  `id_site` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_ajout` int(11) NOT NULL,
  `id_membre` int(11) NOT NULL,
  `etat` tinyint(4) NOT NULL COMMENT '1: non validé; 2: ok',
  `nbre_modules` int(11) NOT NULL DEFAULT '0',
  `service_inscription` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'non' COMMENT 'oui / non',
  `options_inscription` text COLLATE utf8_unicode_ci NOT NULL,
  `service_vote` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'non',
  `inscrits` int(11) NOT NULL,
  `type` varchar(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'norm' COMMENT 'ou demo',
  `service_connexion` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'non',
  `service_liensjours` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'non',
  `service_tchat` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `quota_cache` int(2) NOT NULL DEFAULT '10',
  PRIMARY KEY (`id_site`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=855 ;

-- --------------------------------------------------------

--
-- Structure de la table `tchat`
--

CREATE TABLE IF NOT EXISTS `tchat` (
  `id_site` int(11) NOT NULL,
  `date_modif` int(11) NOT NULL,
  `id_page` int(11) NOT NULL,
  `champ_1` varchar(4) NOT NULL DEFAULT 'DESC',
  `cle_modo` varchar(5) NOT NULL,
  `modos` text NOT NULL,
  UNIQUE KEY `id_site` (`id_site`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tchat_messages`
--

CREATE TABLE IF NOT EXISTS `tchat_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_site` int(11) NOT NULL,
  `date_post` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `etat` int(11) NOT NULL DEFAULT '1' COMMENT '1 : affiché; 2 : masqué',
  `ip` varchar(255) NOT NULL,
  `moderateur` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37541 ;

-- --------------------------------------------------------

--
-- Structure de la table `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `id_vote` int(11) NOT NULL AUTO_INCREMENT,
  `id_site` int(11) NOT NULL,
  `voteur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id_vote`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=686 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
