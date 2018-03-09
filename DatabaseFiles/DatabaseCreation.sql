CREATE DATABASE  IF NOT EXISTS `foodpantry` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `foodpantry`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: foodpantry
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.30-MariaDB

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
  `formOrder` int(5) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`categoryID`),
  UNIQUE KEY `idCategory_UNIQUE` (`categoryID`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `pets` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`clientID`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `isDeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`donationPartnerID`),
  UNIQUE KEY `DonationPartnerID_UNIQUE` (`donationPartnerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=212 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=215 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(200) NOT NULL,
  `pw` varchar(200) DEFAULT NULL,
  `permission_level` int(11) DEFAULT NULL,
  `locked` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-03-08 17:59:32
