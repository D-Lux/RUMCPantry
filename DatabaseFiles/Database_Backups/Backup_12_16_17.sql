CREATE DATABASE  IF NOT EXISTS `foodpantry` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `foodpantry`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: foodpantry
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.21-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `small` int(11) DEFAULT NULL,
  `medium` int(11) DEFAULT NULL,
  `large` int(11) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`categoryID`),
  UNIQUE KEY `idCategory_UNIQUE` (`categoryID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Beans',4,6,8,0),(2,'Beef',2,2,4,0),(3,'Cereal',4,1,2,0),(4,'Pork',1,2,2,0),(5,'Chicken',2,3,4,0),(6,'Redistribution',0,0,0,0),(7,'Chicken',0,0,0,1);
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `clientID` int(11) NOT NULL AUTO_INCREMENT,
  `numOfAdults` int(11) DEFAULT NULL,
  `numOfKids` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phoneNumber` varchar(45) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `zip` varchar(45) DEFAULT NULL,
  `foodStamps` tinyint(1) DEFAULT NULL,
  `notes` varchar(256) DEFAULT NULL,
  `redistribution` tinyint(1) DEFAULT NULL,
  `clientType` int(11) DEFAULT NULL,
  PRIMARY KEY (`clientID`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (10,2,0,'2017-12-02 11:00:37',0,'','7084719304','836 Sunnyside Rd','Roselle','IL','60172',-1,'',0,3),(11,0,0,'2017-12-02 10:41:50',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL),(12,4,5,'2017-12-02 10:43:11',0,'','6305500584','715 Briarwood Ln','Roselle','IL','60172',-1,NULL,0,3),(13,2,0,'2017-12-02 10:47:50',0,'','6309351789','319 Orchard Terrace','Roselle','IL','60172',-1,NULL,0,3),(14,2,1,'2017-12-02 10:50:49',0,'','6305299426','100 E. Thorndale','Roselle','IL','60172',-1,'dog',0,2),(15,2,1,'2017-12-02 10:54:40',0,'','7086320359','1365 Thames Terrace','Roselle','IL','60172',-1,'',0,3),(16,1,0,'2017-12-02 10:56:06',0,'','6309974224','439 Lawrence Ave. Apt G','Roselle','IL','60172',-1,NULL,0,3),(17,1,0,'2017-12-02 10:57:20',0,'','6309358943','297 Frontier','Roselle','IL','60172',-1,NULL,0,3),(18,3,0,'2017-12-02 11:00:27',0,'','2246539270','614 Rosner Dr','Roselle','IL','60172',-1,'(224)653-9270 (landline)\r\n(331)245-9673 (cell)',0,3),(19,3,3,'2017-12-02 11:39:05',0,'','8473728757','775 Woodside','Roselle','IL','60172',-1,'',0,3),(20,2,0,'2017-12-02 11:05:24',0,'','6302672024','675 Circle Dr.','Roselle','IL','60172',-1,NULL,0,3),(21,1,0,'2017-12-02 11:07:05',0,'','6309245391','854 Sunrise Pl','Roselle','IL','60172',-1,NULL,0,3),(22,1,4,'2017-12-02 11:08:49',0,'','2242615250','580 E. Lawrence #202','Roselle','IL','60172',-1,NULL,0,3),(23,2,0,'2017-12-02 11:19:20',0,'','3312452062','1035 Borden Dr','Roselle','IL','60172',-1,NULL,0,3),(24,5,0,'2017-12-02 11:26:14',0,'','6303631183','86 B Terry','Roselle','IL','60172',-1,NULL,0,3),(25,2,1,'2017-12-02 11:35:41',0,'','6303371439','77 Central #208','Roselle','IL','60172',-1,'Driver Hals number:\r\n773-441-3924',0,3),(26,4,1,'2017-12-02 11:37:06',0,'','3316423342','202 Lincoln St','Roselle','IL','60172',-1,NULL,0,3),(27,2,0,'2017-12-02 11:40:26',0,'','6302544716','560 Lawrence','Roselle','IL','60172',-1,NULL,0,3),(28,3,0,'2017-12-02 11:53:55',0,'','7083696491','226 Walter Apt A','Roselle','IL','60172',-1,'',0,3),(29,2,2,'2017-12-02 11:55:13',0,'','8472575829','285 Springhill Dr. Apt 215','Roselle','IL','60172',-1,NULL,0,3),(30,3,0,'2017-12-02 11:59:15',0,'','3312457519','30 W. Claria Dr.','Roselle','IL','60172',-1,NULL,0,3),(31,1,0,'2017-12-02 12:01:38',0,'','9207230368','945 W. Bryn Mawr Ave','Roselle','IL','60172',-1,'Rents 1 room in a house and pays no utilities',0,3),(32,1,0,'2017-12-02 12:02:47',0,'','6304615341','232 Frontier Dr','Roselle','IL','60172',-1,NULL,0,3),(33,3,1,'2017-12-02 12:04:02',0,'','6302172883','804 Blackhawk Dr','Roselle','IL','60172',-1,NULL,0,3),(34,1,2,'2017-12-16 10:32:51',0,'','8472752980','907 Cross Creek','Roselle','IL','60172',-1,NULL,0,3),(35,2,0,'2017-12-16 10:35:10',0,'','6308941318','725 Kipling Ct','Roselle','IL','60172',-1,NULL,0,2),(36,2,0,'2017-12-16 10:36:45',0,'','6308026591','45 W. Granville Ave','Roselle','IL','60172',-1,NULL,0,3),(37,2,0,'2017-12-16 10:39:13',0,'','6309800853','571 Plum Grove Rd #GB','Roselle','IL','60172',-1,'Cab: 847-303-0303',0,3),(38,1,0,'2017-12-16 10:40:07',0,'','6302056241','271 Frontier Dr.','Roselle','IL','60172',-1,NULL,0,3),(39,2,0,'2017-12-16 10:41:47',0,'','6309405134','275 Spring Hill Dr. Apt 102','Roselle','IL','60172',-1,NULL,0,3),(40,2,2,'2017-12-16 10:43:52',0,'','2246538853','620 S Roselle Rd','Roselle','IL','60172',-1,NULL,0,3),(41,1,0,'2017-12-16 10:45:56',0,'','6305406040','238 Frontier Dr.','Roselle','IL','60172',-1,NULL,0,0),(42,1,0,'2017-12-16 10:47:51',0,'','6302959105','27 Morningside','Roselle','IL','60172',-1,NULL,0,3),(43,3,2,'2017-12-16 10:53:14',0,'','6303065208','3882 Sandpiper Dr.','Hanover Park','IL','60133',-1,'',0,2),(44,1,0,'2017-12-16 10:55:30',0,'','3312027466','280 Frontier Dr','Roselle','IL','60172',-1,NULL,0,3),(45,1,0,'2017-12-16 11:12:47',0,'','6305392860','77 Portwine Rd','Roselle','IL','60172',-1,NULL,0,3),(46,1,0,'2017-12-16 11:22:22',0,'','3312457686','263 Frontier Dr','Roselle','IL','60172',-1,NULL,0,0),(47,2,0,'2017-12-16 11:23:32',0,'','6308941715','304 E. Walnut St.','Roselle','IL','60172',-1,NULL,0,3),(48,1,3,'2017-12-16 11:24:56',0,'','6304785670','280 Springhill Dr.','Roselle','IL','60172',-1,NULL,0,3),(49,2,0,'2017-12-16 11:29:01',0,'','6304617149','69 W. Walnut Ct','Roselle','IL','60172',-1,NULL,0,3),(50,1,2,'2017-12-16 11:31:38',0,'','6305596275','528 Carlsbad Tr','Roselle','IL','60172',-1,NULL,0,3),(51,2,3,'2017-12-16 11:33:13',0,'','7085278484','57 Cherry St','Roselle','IL','60172',-1,NULL,0,3),(52,2,1,'2017-12-16 11:36:28',0,'','7798611147','59 W. Walnut Ct.','Roselle','IL','60172',-1,NULL,0,3),(53,4,1,'2017-12-16 11:39:11',0,'','6303294844','211 Cedar','St. Charles','IL','60174',-1,NULL,0,1),(54,4,1,'2017-12-16 11:43:32',0,'','8477662977','585 Plum Grove Rd Apt 20','Roselle','IL','60172',-1,NULL,0,3),(55,3,0,'2017-12-16 11:46:18',0,'','7076600828','580 Lawrence Ave','Roselle','IL','60172',-1,NULL,0,0),(56,2,3,'2017-12-16 12:14:34',0,'','6309245993','338 Rodenburg Rd','Roselle','IL','60172',-1,'Gluten free, lactose free, soy free, corn free, red food dye free',0,3);
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donation`
--

DROP TABLE IF EXISTS `donation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donation` (
  `donationID` int(11) NOT NULL AUTO_INCREMENT,
  `donationPartnerID` int(11) NOT NULL,
  `dateOfPickup` date DEFAULT NULL,
  `networkPartner` varchar(45) DEFAULT 'RUMC',
  `agency` varchar(45) DEFAULT '1039a',
  `frozenNonMeat` int(11) DEFAULT NULL,
  `frozenMeat` int(11) DEFAULT NULL,
  `frozenPrepared` int(11) DEFAULT NULL,
  `refBakery` int(11) DEFAULT NULL,
  `refProduce` int(11) DEFAULT NULL,
  `refDairyAndDeli` int(11) DEFAULT NULL,
  `dryShelfStable` int(11) DEFAULT NULL,
  `dryNonFood` int(11) DEFAULT NULL,
  `dryFoodDrive` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`donationID`,`donationPartnerID`),
  UNIQUE KEY `idDonation_UNIQUE` (`donationID`),
  KEY `fk_Donation_DonationPartner1_idx` (`donationPartnerID`),
  CONSTRAINT `fk_Donation_DonationPartner1` FOREIGN KEY (`donationPartnerID`) REFERENCES `donationpartner` (`donationPartnerID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donation`
--

LOCK TABLES `donation` WRITE;
/*!40000 ALTER TABLE `donation` DISABLE KEYS */;
/*!40000 ALTER TABLE `donation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donationpartner`
--

DROP TABLE IF EXISTS `donationpartner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donationpartner` (
  `donationPartnerID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `zip` varchar(45) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  `phoneNumber` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`donationPartnerID`),
  UNIQUE KEY `DonationPartnerID_UNIQUE` (`donationPartnerID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donationpartner`
--

LOCK TABLES `donationpartner` WRITE;
/*!40000 ALTER TABLE `donationpartner` DISABLE KEYS */;
/*!40000 ALTER TABLE `donationpartner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `familymember`
--

DROP TABLE IF EXISTS `familymember`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `familymember` (
  `familyMemberID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(45) DEFAULT NULL,
  `lastName` varchar(45) DEFAULT NULL,
  `isHeadOfHousehold` tinyint(1) DEFAULT NULL,
  `notes` varchar(256) DEFAULT NULL,
  `birthDate` date DEFAULT NULL,
  `clientID` int(11) NOT NULL,
  `timestamp` datetime DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  PRIMARY KEY (`familyMemberID`,`clientID`),
  KEY `fk_FamilyMember_Client1_idx` (`clientID`),
  CONSTRAINT `fk_FamilyMember_Client1` FOREIGN KEY (`clientID`) REFERENCES `client` (`clientID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `familymember`
--

LOCK TABLES `familymember` WRITE;
/*!40000 ALTER TABLE `familymember` DISABLE KEYS */;
INSERT INTO `familymember` VALUES (12,'Catherine','Anderson',1,NULL,'1963-10-11',10,'2017-12-02 10:40:52',0,1),(13,'Ronald','Anderson',0,'','1958-09-24',10,'2017-12-02 10:41:20',0,-1),(14,'Available','Available',1,NULL,NULL,11,'2017-12-02 10:41:50',0,NULL),(15,'Fatima','Ayyad',1,NULL,'1983-02-10',12,'2017-12-02 10:43:11',0,1),(16,'Mohammed','Ayyad',0,'','1950-11-01',12,'2017-12-02 10:43:51',0,-1),(17,'Zainab','Ayyad',0,'','1958-04-01',12,'2017-12-02 10:44:07',0,1),(18,'Mariam','Ayyad',0,'','1980-10-01',12,'2017-12-02 10:44:24',0,1),(19,'Wadea','Ayyad',0,'','2008-08-01',12,'2017-12-02 10:44:46',0,0),(20,'Wissam','Ayyad',0,'','2011-08-01',12,'2017-12-02 10:45:06',0,0),(21,'Hiba','Ayyad',0,'','2006-10-01',12,'2017-12-02 10:45:27',0,0),(22,'Zaina','Ayyad',0,'','2008-05-01',12,'2017-12-02 10:45:48',0,0),(23,'Maya','Ayyad',0,'','2013-05-01',12,'2017-12-02 10:46:02',0,0),(24,'Pat','Barba',1,NULL,'1960-12-27',13,'2017-12-02 10:47:50',0,1),(25,'Donna','Benson',1,'','1939-02-23',14,'2017-12-02 10:50:19',0,1),(26,'Erin','Benson',0,'','1968-04-28',14,'2017-12-02 10:50:08',0,1),(27,'Ainsley','Benson',0,'','2005-02-24',14,'2017-12-02 10:50:34',0,1),(28,'Betty','Bishop',1,NULL,'1948-08-08',15,'2017-12-02 10:53:32',0,1),(29,'Aniya','Bishop',0,'','1999-02-22',15,'2017-12-02 10:53:57',0,1),(30,'Nikea','Ray',0,'','2013-09-13',15,'2017-12-02 10:54:30',0,1),(31,'Claudia','Brown',1,NULL,'1963-06-01',16,'2017-12-02 10:56:06',0,0),(32,'Susan','Bustamante',1,NULL,'1954-04-14',17,'2017-12-02 10:57:20',0,1),(33,'Sandra','Butler',1,NULL,'1957-01-07',18,'2017-12-02 10:58:41',0,1),(34,'Stanley','Marszalck',0,'','1921-02-14',18,'2017-12-02 10:59:18',0,-1),(35,'John','Butler',0,'','1986-01-15',18,'2017-12-02 10:59:32',0,-1),(36,'Ann Marie','Carretto',1,'','1971-12-27',19,'2017-12-02 11:02:38',0,1),(37,'Jose','Carretto',0,'','1971-11-16',19,'2017-12-02 11:02:28',0,-1),(38,'Brianna','Carretto',0,'','1999-03-27',19,'2017-12-02 11:03:11',0,1),(39,'Angela','Carretto',0,'','2001-01-09',19,'2017-12-02 11:03:38',0,1),(40,'Lily','Carretto',0,'','2004-09-16',19,'2017-12-02 11:03:54',0,1),(41,'Ruby','Carretto',0,'','2006-05-15',19,'2017-12-02 11:04:07',0,1),(42,'Nicholas','Cavaliero',1,'','1966-08-01',20,'2017-12-02 11:05:58',0,-1),(43,'Elaine','Cooper',1,NULL,'1940-08-27',21,'2017-12-02 11:07:05',0,1),(44,'Danielle','Corcoran',1,'','1984-02-03',22,'2017-12-02 11:10:32',0,1),(45,'Grace','Corcoran',0,'','2005-05-15',22,'2017-12-02 11:09:25',0,1),(46,'Emilee','Corcoran',0,'','2008-02-04',22,'2017-12-02 11:09:39',0,1),(47,'Alexander','Corcoran',0,'','2012-02-06',22,'2017-12-02 11:10:03',0,-1),(48,'Madeline','Corcoran',0,'','2017-03-19',22,'2017-12-02 11:10:19',0,1),(49,'Diane','Cozzi',1,NULL,'1955-11-21',23,'2017-12-02 11:19:20',0,1),(50,'Shauna','Craigen',1,NULL,'1965-03-06',24,'2017-12-02 11:26:14',0,1),(51,'Robert','Rivers',0,'','1966-01-01',24,'2017-12-02 11:26:54',0,-1),(52,'Zachary','Cooper',0,'','1993-06-09',24,'2017-12-02 11:27:10',0,-1),(53,'Renee','Felton',0,'','1998-08-08',24,'2017-12-02 11:27:41',0,1),(54,'Marquise','Rivers',0,'','1996-04-15',24,'2017-12-02 11:28:47',0,-1),(55,'Janice','Crueger',1,'Driver Hal\'s number: \r\n773-441-3924','1955-10-19',25,'2017-12-02 11:33:49',0,1),(56,'Sarah','Blankenship',0,'','1982-01-22',25,'2017-12-02 11:33:13',0,1),(57,'Alex','Sakalis',0,'','2013-10-23',25,'2017-12-02 11:34:30',0,-1),(58,'Christina','Crowley',1,NULL,'1982-04-11',26,'2017-12-02 11:37:06',0,1),(59,'Jonathan','Crowley',0,'','2006-06-23',26,'2017-12-02 11:37:51',0,-1),(60,'Fran','Damato',1,NULL,'1958-05-03',27,'2017-12-02 11:40:26',0,1),(61,'Dave','Damato',0,'','1960-02-03',27,'2017-12-02 11:40:54',0,-1),(62,'Vicki','Craig',1,NULL,'1968-05-12',28,'2017-12-02 11:52:59',0,1),(63,'Kevin','Craig',0,'','1991-10-17',28,'2017-12-02 11:53:31',0,-1),(64,'Anthony','Craig',0,'','1999-03-18',28,'2017-12-02 11:53:50',0,-1),(65,'Carly','Altman',1,NULL,'1984-08-24',29,'2017-12-02 11:55:13',0,1),(66,'Brian','Miller',0,'','1975-05-23',29,'2017-12-02 11:55:37',0,-1),(67,'Abriana','Pelayo',0,'','2007-02-27',29,'2017-12-02 11:55:56',0,1),(68,'Elise','Pelayo',0,'','2010-11-01',29,'2017-12-02 11:56:12',0,0),(69,'Anthony','Cozzi',0,'','1981-03-27',23,'2017-12-02 11:57:38',0,-1),(70,'Michele','Canchola',1,NULL,'1961-01-01',30,'2017-12-02 11:59:15',0,1),(71,'Christina','Conforti',1,NULL,'1940-08-09',31,'2017-12-02 12:01:12',0,1),(72,'Dolores','Brooks',1,NULL,'1952-05-13',32,'2017-12-02 12:02:48',0,1),(73,'Jacqueline','Barth',1,NULL,'1960-12-07',33,'2017-12-02 12:04:02',0,1),(74,'Riggs','Barth',0,'','1997-04-24',33,'2017-12-02 12:04:29',0,-1),(75,'Amber','Barth',0,'','1994-06-24',33,'2017-12-02 12:04:42',0,1),(76,'Lillian','Barth',0,'','2016-11-07',33,'2017-12-02 12:04:58',0,1),(77,'Nichole','Deal',1,NULL,'1986-01-30',34,'2017-12-16 10:32:56',0,1),(78,'Brook','Deal',0,'','2014-02-17',34,'2017-12-16 10:33:32',0,1),(79,'Iain','Deal',0,'','2012-06-01',34,'2017-12-16 10:33:51',0,-1),(80,'Robert','Dimpsey',1,NULL,'1934-09-12',35,'2017-12-16 10:35:10',0,-1),(81,'Roberta','Dimpsey',0,'','1937-08-05',35,'2017-12-16 10:35:40',0,1),(82,'Kimberly','Dinuzzo',1,NULL,'1960-06-29',36,'2017-12-16 10:36:45',0,1),(83,'Brenda','Dismukes',1,NULL,'1949-03-19',37,'2017-12-16 10:38:32',0,1),(84,'Michele','Doyle',1,'','1954-08-20',38,'2017-12-16 10:40:24',0,1),(85,'Robert','Ehrhardt',1,NULL,'1961-07-31',39,'2017-12-16 10:41:47',0,-1),(86,'Mari','LaCasse',0,'','1965-05-21',39,'2017-12-16 10:42:17',0,1),(87,'Gary','Ehrhart',1,NULL,'1965-12-08',40,'2017-12-16 10:43:53',0,-1),(88,'Shelley','Ehrhart',0,'','1972-11-03',40,'2017-12-16 10:44:15',0,1),(89,'Adam','Ehrhart',0,'','2006-06-14',40,'2017-12-16 10:44:35',0,-1),(90,'Alyssa','Ehrhart',0,'','2009-11-01',40,'2017-12-16 10:44:51',0,1),(91,'Arlene','Fordon',1,NULL,'1936-10-20',41,'2017-12-16 10:45:56',0,1),(92,'Pauline','Garrett',1,NULL,'1901-01-01',42,'2017-12-16 10:47:52',0,1),(93,'Beth','Gear',1,NULL,'1980-05-28',43,'2017-12-16 10:48:49',0,1),(94,'Jon','Gear',0,'','1975-07-24',43,'2017-12-16 10:49:27',0,-1),(95,'Cole','Gear',0,'','1998-09-15',43,'2017-12-16 10:53:53',0,-1),(96,'Emily','Gear',0,'','2005-10-29',43,'2017-12-16 10:54:06',0,1),(97,'Logan','Gear',0,'','2011-10-10',43,'2017-12-16 10:54:22',0,-1),(98,'Stefania','Gebarowska',1,'','1937-08-01',44,'2017-12-16 10:55:44',0,1),(99,'Frank','Gentile',1,NULL,'1944-04-01',45,'2017-12-16 11:12:47',0,-1),(100,'Tom','Glade',1,NULL,'1943-10-11',46,'2017-12-16 11:22:23',0,-1),(101,'Bea','Goke',1,NULL,'1932-03-27',47,'2017-12-16 11:23:32',0,1),(102,'Don','Goke',0,'','1929-08-11',47,'2017-12-16 11:23:56',0,-1),(103,'Kayla','Gomez',1,NULL,'1995-01-15',48,'2017-12-16 11:24:56',0,1),(104,'Blaise','Morales',0,'','2015-05-18',48,'2017-12-16 11:26:27',0,-1),(105,'Dallas','Morales',0,'','2016-04-18',48,'2017-12-16 11:26:55',0,-1),(106,'Andres','Morales',0,'','2017-09-01',48,'2017-12-16 11:27:16',0,-1),(107,'Maria','Gonzalez',1,NULL,'1964-02-28',49,'2017-12-16 11:29:01',0,1),(108,'Carlos','Gonzalez',0,'','1964-11-20',49,'2017-12-16 11:29:32',0,-1),(109,'Patty','Gotz',1,NULL,'1972-04-06',50,'2017-12-16 11:31:38',0,1),(110,'Samantha','Gotz',0,'','1999-10-26',50,'2017-12-16 11:31:59',0,1),(111,'Kylie','Gotz',0,'','2004-08-10',50,'2017-12-16 11:32:12',0,1),(112,'Anastasia','Guerrero',1,NULL,'1992-12-14',51,'2017-12-16 11:33:13',0,1),(113,'Giovanni','Addair',0,'','2010-09-10',51,'2017-12-16 11:33:52',0,-1),(114,'Christian','Guerrero',0,'','2013-01-15',51,'2017-12-16 11:34:39',0,-1),(115,'Pablo','Cantu',0,'','2014-06-12',51,'2017-12-16 11:35:01',0,-1),(116,'Maria','Poliarny',0,'','1901-08-26',51,'2017-12-16 11:35:24',0,1),(117,'Beronica','Guerrero',1,NULL,'1990-02-27',52,'2017-12-16 11:36:28',0,1),(118,'David','Hernandez Sr',0,'','1989-05-08',52,'2017-12-16 11:37:15',0,-1),(119,'David','Hernandez Jr.',0,'','2013-03-08',52,'2017-12-16 11:37:33',0,-1),(120,'Jenn','Gunn',0,NULL,'1969-06-10',53,'2017-12-16 11:39:12',0,1),(121,'Stan','Gunn',1,'','0000-00-00',53,'2017-12-16 11:40:33',0,-1),(122,'Dann','Gunn',0,'','1966-09-13',53,'2017-12-16 11:39:59',0,-1),(123,'Aaron','Gunn',0,'','1997-11-09',53,'2017-12-16 11:40:16',0,-1),(124,'Donna','Gunn',0,'','1997-12-31',53,'2017-12-16 11:40:52',0,1),(125,'Ethan','Gunn',0,'','2000-12-06',53,'2017-12-16 11:41:08',0,-1),(126,'Jackie','Hable',1,NULL,'1966-05-11',54,'2017-12-16 11:43:32',0,1),(127,'Alexandra','Hable',0,'','1997-01-02',54,'2017-12-16 11:44:10',0,1),(128,'Cristan','Hable',0,'','2008-03-21',54,'2017-12-16 11:44:25',0,-1),(129,'Pedro','Selgado',0,'','1922-01-01',54,'2017-12-16 11:44:53',0,-1),(130,'Sergio','Venciennes',0,'','1923-01-01',54,'2017-12-16 11:45:18',0,-1),(131,'Jennifer','Hamlin',1,NULL,'1966-09-08',55,'2017-12-16 11:46:18',0,1),(132,'Christian','Cosentino',0,'','1996-08-20',55,'2017-12-16 11:46:41',0,-1),(133,'Jenna','Erimilio',0,'','1985-04-24',55,'2017-12-16 11:47:01',0,1),(134,'Magda','Havenga',1,'','1970-01-01',56,'2017-12-16 12:14:10',0,1),(135,'Theodore','Havenga',0,'','1959-12-26',56,'2017-12-16 12:14:58',0,-1),(136,'Megan','Havenga',0,'','1997-06-05',56,'2017-12-16 12:15:14',0,1),(137,'Sarah','Havenga',0,'','2002-04-19',56,'2017-12-16 12:15:32',0,1),(138,'Katie','Havenga',0,'','2003-11-20',56,'2017-12-16 12:15:44',0,1);
/*!40000 ALTER TABLE `familymember` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory` (
  `inventoryID` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` int(11) DEFAULT NULL,
  `itemID` int(11) NOT NULL,
  `location` varchar(45) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`inventoryID`,`itemID`),
  KEY `fk_Inventory_Item1_idx` (`itemID`),
  CONSTRAINT `fk_Inventory_Item1` FOREIGN KEY (`itemID`) REFERENCES `item` (`itemID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice`
--

DROP TABLE IF EXISTS `invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice` (
  `invoiceID` int(11) NOT NULL AUTO_INCREMENT,
  `visitDate` date DEFAULT NULL,
  `clientID` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `visitTime` time DEFAULT NULL,
  `walkIn` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`invoiceID`,`clientID`),
  KEY `fk_Invoice_Client1_idx` (`clientID`),
  CONSTRAINT `fk_Invoice_Client1` FOREIGN KEY (`clientID`) REFERENCES `client` (`clientID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice`
--

LOCK TABLES `invoice` WRITE;
/*!40000 ALTER TABLE `invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoicedescription`
--

DROP TABLE IF EXISTS `invoicedescription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoicedescription` (
  `invoiceDescID` int(11) NOT NULL AUTO_INCREMENT,
  `invoiceID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `totalItemsPrice` int(11) DEFAULT NULL,
  `special` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`invoiceDescID`,`invoiceID`,`itemID`),
  UNIQUE KEY `invoiceDescID_UNIQUE` (`invoiceDescID`),
  KEY `fk_Invoice_InvoiceDescription_idx` (`invoiceID`),
  KEY `fk_Invoice_Item1_idx` (`itemID`),
  CONSTRAINT `fk_Invoice_InvoiceDescription` FOREIGN KEY (`invoiceID`) REFERENCES `invoice` (`invoiceID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Invoice_Item1` FOREIGN KEY (`itemID`) REFERENCES `item` (`itemID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoicedescription`
--

LOCK TABLES `invoicedescription` WRITE;
/*!40000 ALTER TABLE `invoicedescription` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoicedescription` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item` (
  `itemID` int(11) NOT NULL AUTO_INCREMENT,
  `itemName` varchar(45) DEFAULT NULL,
  `displayName` varchar(45) DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL,
  `small` int(11) DEFAULT NULL COMMENT 'small, medium, large, and walk in are all integers that describe how many of the item each family size can take',
  `medium` int(11) DEFAULT NULL COMMENT 'small, medium, large, and walk in are all integers that describe how many of the item each family size can take',
  `large` int(11) DEFAULT NULL COMMENT 'small, medium, large, and walk in are all integers that describe how many of the item each family size can take',
  `categoryID` int(11) NOT NULL,
  `rack` int(11) DEFAULT NULL,
  `shelf` int(11) DEFAULT NULL,
  `aisle` int(11) DEFAULT NULL,
  PRIMARY KEY (`itemID`,`categoryID`),
  KEY `fk_Item_Category1_idx` (`categoryID`),
  CONSTRAINT `fk_Item_Category1` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item`
--

LOCK TABLES `item` WRITE;
/*!40000 ALTER TABLE `item` DISABLE KEYS */;
/*!40000 ALTER TABLE `item` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-16 22:34:40
