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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-16 22:33:03
