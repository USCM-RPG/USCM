/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Current Database: `skynet`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `skynet` /*!40100 DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci */;

USE `skynet`;

--
-- Table structure for table `Admins`
--

DROP TABLE IF EXISTS `Admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `GMs`
--

DROP TABLE IF EXISTS `GMs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `GMs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT 0,
  `RPG_id` int(10) unsigned NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `RPG`
--

DROP TABLE IF EXISTS `RPG`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `RPG` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rpg_name` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `rpg_name_short` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `table_prefix` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `forname` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `nickname` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `lastname` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `emailadress` varchar(65) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `password` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `use_nickname` tinyint(2) NOT NULL DEFAULT 0,
  `platoon_id` tinyint(4) DEFAULT NULL,
  `logintime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastlogintime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `discordid` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `expertise_groups`
--

DROP TABLE IF EXISTS `expertise_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `expertise_groups` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `expertise_group_name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `expertise_names`
--

DROP TABLE IF EXISTS `expertise_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `expertise_names` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `expertise_group_id` tinyint(3) unsigned DEFAULT NULL,
  `expertise_name` varchar(30) DEFAULT NULL,
  `value` tinyint(4) NOT NULL DEFAULT 0,
  `subgroup` enum('us','generic','other','mounted','support','missiles','closecombat','upp') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expertise_names_expertise_group_id_IDX` (`expertise_group_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=227 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `expertise_skill`
--

DROP TABLE IF EXISTS `expertise_skill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `expertise_skill` (
  `skillid` int(10) unsigned NOT NULL,
  `expertiseid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`skillid`,`expertiseid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `expertises`
--

DROP TABLE IF EXISTS `expertises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `expertises` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `character_id` int(10) unsigned NOT NULL,
  `expertise_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `expertises_character_id_IDX` (`character_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1519 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `psychodis_names`
--

DROP TABLE IF EXISTS `psychodis_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `psychodis_names` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `value` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `psychodisadvantages`
--

DROP TABLE IF EXISTS `psychodisadvantages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `psychodisadvantages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `character_id` int(10) unsigned NOT NULL,
  `psychodis_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `terrain_mission`
--

DROP TABLE IF EXISTS `terrain_mission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `terrain_mission` (
  `expertise_id` int(10) unsigned NOT NULL DEFAULT 0,
  `mission_id` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`expertise_id`,`mission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_advantage_names`
--

DROP TABLE IF EXISTS `uscm_advantage_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_advantage_names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advantage_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `value` tinyint(4) DEFAULT NULL,
  `visible` tinyint(4) NOT NULL DEFAULT 1,
  `version` tinyint(3) unsigned NOT NULL DEFAULT 3,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_advantages`
--

DROP TABLE IF EXISTS `uscm_advantages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_advantages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `character_id` smallint(6) NOT NULL DEFAULT 0,
  `advantage_name_id` smallint(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1062 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_advdisadv_bonus`
--

DROP TABLE IF EXISTS `uscm_advdisadv_bonus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_advdisadv_bonus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `advid` int(10) unsigned DEFAULT NULL,
  `disadvid` int(10) unsigned DEFAULT NULL,
  `traitid` int(10) unsigned DEFAULT NULL,
  `modifier_dice_value` int(11) DEFAULT NULL,
  `modifier_basic_value` int(11) DEFAULT NULL,
  `table_point_name` set('attribute_names','skill_names','awareness','leadership','fearlimit','exhaustionlimit','psycholimit','psychopoints','fearpoints','exhaustionpoints','carrycapacity','combatload') NOT NULL,
  `column_id` int(10) unsigned DEFAULT NULL,
  `value_always_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_attribute_names`
--

DROP TABLE IF EXISTS `uscm_attribute_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_attribute_names` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_name` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_attributes`
--

DROP TABLE IF EXISTS `uscm_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_attributes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `character_id` int(10) unsigned NOT NULL DEFAULT 0,
  `attribute_id` int(10) unsigned NOT NULL DEFAULT 0,
  `value` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3118 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_certificate_names`
--

DROP TABLE IF EXISTS `uscm_certificate_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_certificate_names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `mainskill` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_certificate_requirements`
--

DROP TABLE IF EXISTS `uscm_certificate_requirements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_certificate_requirements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `certificate_id` int(11) NOT NULL DEFAULT 0,
  `req_item` int(11) NOT NULL DEFAULT 0,
  `value` tinyint(4) NOT NULL DEFAULT 0,
  `value_greater` tinyint(4) NOT NULL DEFAULT 1,
  `table_name` set('skill_names','attribute_names') NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=161 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_certificates`
--

DROP TABLE IF EXISTS `uscm_certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_certificates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `certificate_name_id` int(11) NOT NULL DEFAULT 0,
  `character_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=212 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_characters`
--

DROP TABLE IF EXISTS `uscm_characters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_characters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT 0,
  `platoon_id` tinyint(6) NOT NULL DEFAULT 1,
  `forname` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `lastname` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `Enlisted` date NOT NULL DEFAULT '0000-00-00',
  `Age` tinyint(2) NOT NULL DEFAULT 0,
  `Gender` set('Male','Female') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `UnusedXP` tinyint(2) DEFAULT 0,
  `AwarenessPoints` tinyint(4) NOT NULL DEFAULT 0,
  `CoolPoints` tinyint(2) DEFAULT 0,
  `ExhaustionPoints` tinyint(4) NOT NULL DEFAULT 0,
  `FearPoints` tinyint(4) NOT NULL DEFAULT 0,
  `LeadershipPoints` tinyint(4) NOT NULL DEFAULT 0,
  `PsychoPoints` tinyint(2) DEFAULT 0,
  `TraumaPoints` tinyint(4) NOT NULL DEFAULT 0,
  `MentalPoints` tinyint(4) NOT NULL DEFAULT 0,
  `scrating` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `status` set('Active','PoW','Retired','Dead') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'Active',
  `status_desc` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `encalien` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `encgrey` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `encpred` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `encai` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `encarach` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `extramissions` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `extrasims` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `version` tinyint(1) unsigned NOT NULL DEFAULT 2,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=472 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_disadvantage_names`
--

DROP TABLE IF EXISTS `uscm_disadvantage_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_disadvantage_names` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `disadvantage_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `value` tinyint(4) NOT NULL DEFAULT 0,
  `visible` tinyint(4) NOT NULL DEFAULT 1,
  `version` tinyint(3) unsigned NOT NULL DEFAULT 3,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_disadvantages`
--

DROP TABLE IF EXISTS `uscm_disadvantages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_disadvantages` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `character_id` smallint(6) NOT NULL DEFAULT 0,
  `disadvantage_name_id` smallint(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=654 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_medal_names`
--

DROP TABLE IF EXISTS `uscm_medal_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_medal_names` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `medal_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `medal_short` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `medal_glory` tinyint(2) NOT NULL DEFAULT 0,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `foreign_medal` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_mission_names`
--

DROP TABLE IF EXISTS `uscm_mission_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_mission_names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mission_name_short` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `mission_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `gm` int(11) DEFAULT NULL,
  `briefing` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `debriefing` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `platoon_id` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=252 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_mission_tags`
--

DROP TABLE IF EXISTS `uscm_mission_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_mission_tags` (
  `missionid` int(11) NOT NULL,
  `tagid` int(11) NOT NULL,
  KEY `uscm_mission_tags_missionid_IDX` (`missionid`) USING BTREE,
  KEY `uscm_mission_tags_tagid_IDX` (`tagid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_missions`
--

DROP TABLE IF EXISTS `uscm_missions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_missions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `character_id` int(11) NOT NULL DEFAULT 0,
  `mission_id` smallint(6) NOT NULL DEFAULT 0,
  `rank_id` smallint(4) DEFAULT NULL,
  `medal_id` smallint(4) DEFAULT NULL,
  `notes` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `previous_rank_id` smallint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_mission` (`character_id`,`mission_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1585 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_news`
--

DROP TABLE IF EXISTS `uscm_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `written_by` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `text` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=279 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci COMMENT='News for USCM';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_platoon_certificates`
--

DROP TABLE IF EXISTS `uscm_platoon_certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_platoon_certificates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `certificate_id` int(11) NOT NULL DEFAULT 0,
  `platoon_id` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_platoon_names`
--

DROP TABLE IF EXISTS `uscm_platoon_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_platoon_names` (
  `id` tinyint(6) NOT NULL AUTO_INCREMENT,
  `name_short` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `name_long` varchar(35) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_rank_names`
--

DROP TABLE IF EXISTS `uscm_rank_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_rank_names` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `rank_long` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `rank_short` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `rank_desc` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `rank_type` char(1) NOT NULL DEFAULT 'E',
  `rank_value` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_ranks`
--

DROP TABLE IF EXISTS `uscm_ranks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_ranks` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `character_id` int(11) NOT NULL DEFAULT 0,
  `rank_id` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=450 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_simulation_names`
--

DROP TABLE IF EXISTS `uscm_simulation_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_simulation_names` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_simulation_scores`
--

DROP TABLE IF EXISTS `uscm_simulation_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_simulation_scores` (
  `mission_id` int(10) unsigned NOT NULL,
  `character_id` int(10) unsigned NOT NULL,
  `score` int(11) NOT NULL,
  `scoretime` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `mission` (`mission_id`),
  KEY `character` (`character_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_skill_groups`
--

DROP TABLE IF EXISTS `uscm_skill_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_skill_groups` (
  `id` tinyint(6) NOT NULL AUTO_INCREMENT,
  `skill_group_name` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_skill_names`
--

DROP TABLE IF EXISTS `uscm_skill_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_skill_names` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `skill_group_id` tinyint(6) NOT NULL DEFAULT 0,
  `skill_name` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `default_value` tinyint(1) NOT NULL DEFAULT 0,
  `Optional` tinyint(1) DEFAULT 0,
  `Description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `version` tinyint(1) unsigned NOT NULL DEFAULT 3,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_skills`
--

DROP TABLE IF EXISTS `uscm_skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_skills` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `character_id` int(10) unsigned NOT NULL DEFAULT 0,
  `skill_name_id` int(10) unsigned NOT NULL DEFAULT 0,
  `value` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=170205 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_specialty`
--

DROP TABLE IF EXISTS `uscm_specialty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_specialty` (
  `id` smallint(10) NOT NULL AUTO_INCREMENT,
  `character_id` smallint(10) NOT NULL DEFAULT 0,
  `specialty_name_id` smallint(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=454 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_specialty_names`
--

DROP TABLE IF EXISTS `uscm_specialty_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_specialty_names` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `specialty_name` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_tags`
--

DROP TABLE IF EXISTS `uscm_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_trait_names`
--

DROP TABLE IF EXISTS `uscm_trait_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_trait_names` (
  `id` smallint(11) NOT NULL AUTO_INCREMENT,
  `trait_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `version` tinyint(3) unsigned NOT NULL DEFAULT 3,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uscm_traits`
--

DROP TABLE IF EXISTS `uscm_traits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uscm_traits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `character_id` int(11) NOT NULL DEFAULT 0,
  `trait_name_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=303 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;
