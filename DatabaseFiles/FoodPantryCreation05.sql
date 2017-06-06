-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema FoodPantry
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema FoodPantry
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `FoodPantry` DEFAULT CHARACTER SET utf8 ;
USE `FoodPantry` ;

-- -----------------------------------------------------
-- Table `FoodPantry`.`Category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `FoodPantry`.`Category` (
  `categoryID` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `small` INT NULL,
  `medium` INT NULL,
  `large` INT NULL,
  `walkIn` INT NULL,
  PRIMARY KEY (`categoryID`),
  UNIQUE INDEX `idCategory_UNIQUE` (`categoryID` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FoodPantry`.`Item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `FoodPantry`.`Item` (
  `itemID` INT NOT NULL AUTO_INCREMENT,
  `itemName` VARCHAR(45) NULL,
  `displayName` VARCHAR(45) NULL,
  `price` DECIMAL(8,2) NULL,
  `timestamp` DATETIME NULL,
  `isDeleted` TINYINT(1) NULL,
  `small` INT NULL COMMENT 'small, medium, large, and walk in are all integers that describe how many of the item each family size can take',
  `medium` INT NULL COMMENT 'small, medium, large, and walk in are all integers that describe how many of the item each family size can take',
  `large` INT NULL COMMENT 'small, medium, large, and walk in are all integers that describe how many of the item each family size can take',
  `walkIn` INT NULL COMMENT 'small, medium, large, and walk in are all integers that describe how many of the item each family size can take',
  `factor` INT NULL COMMENT 'factor for the \"weight\" of the quantity',
  `categoryID` INT NOT NULL,
  PRIMARY KEY (`itemID`, `categoryID`),
  INDEX `fk_Item_Category1_idx` (`categoryID` ASC),
  CONSTRAINT `fk_Item_Category1`
    FOREIGN KEY (`categoryID`)
    REFERENCES `FoodPantry`.`Category` (`categoryID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FoodPantry`.`Client`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `FoodPantry`.`Client` (
  `clientID` INT NOT NULL AUTO_INCREMENT,
  `numOfAdults` INT NULL,
  `numOfKids` INT NULL,
  `timestamp` DATETIME NULL,
  `isDeleted` TINYINT(1) NULL,
  `email` VARCHAR(45) NULL,
  `phoneNumber` VARCHAR(45) NULL,
  `address` VARCHAR(45) NULL,
  `city` VARCHAR(45) NULL,
  `state` VARCHAR(45) NULL,
  `zip` VARCHAR(45) NULL,
  `foodStamps` TINYINT(1) NULL,
  `notes` VARCHAR(256) NULL,
  PRIMARY KEY (`clientID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FoodPantry`.`Invoice`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `FoodPantry`.`Invoice` (
  `invoiceID` INT NOT NULL AUTO_INCREMENT,
  `visitDate` DATE NULL,
  `clientID` INT NOT NULL,
  `status` INT NULL,
  `visitTime` TIME NULL,
  PRIMARY KEY (`invoiceID`, `clientID`),
  INDEX `fk_Invoice_Client1_idx` (`clientID` ASC),
  CONSTRAINT `fk_Invoice_Client1`
    FOREIGN KEY (`clientID`)
    REFERENCES `FoodPantry`.`Client` (`clientID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FoodPantry`.`InvoiceDescription`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `FoodPantry`.`InvoiceDescription` (
  `invoiceID` INT NOT NULL,
  `itemID` INT NOT NULL,
  `quantity` INT NULL,
  `totalItemsPrice` INT NULL,
  PRIMARY KEY (`invoiceID`, `itemID`),
  INDEX `fk_Invoice_InvoiceDescription_idx` (`invoiceID` ASC),
  INDEX `fk_Invoice_Item1_idx` (`itemID` ASC),
  CONSTRAINT `fk_Invoice_InvoiceDescription`
    FOREIGN KEY (`invoiceID`)
    REFERENCES `FoodPantry`.`Invoice` (`invoiceID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Invoice_Item1`
    FOREIGN KEY (`itemID`)
    REFERENCES `FoodPantry`.`Item` (`itemID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FoodPantry`.`Inventory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `FoodPantry`.`Inventory` (
  `inventoryID` INT NOT NULL AUTO_INCREMENT,
  `quantity` INT NULL,
  `itemID` INT NOT NULL,
  `location` VARCHAR(45) NULL,
  `timestamp` DATETIME NULL,
  PRIMARY KEY (`inventoryID`, `itemID`),
  INDEX `fk_Inventory_Item1_idx` (`itemID` ASC),
  CONSTRAINT `fk_Inventory_Item1`
    FOREIGN KEY (`itemID`)
    REFERENCES `FoodPantry`.`Item` (`itemID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FoodPantry`.`FamilyMember`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `FoodPantry`.`FamilyMember` (
  `familyMemberID` INT NOT NULL AUTO_INCREMENT,
  `firstName` VARCHAR(45) NULL,
  `lastName` VARCHAR(45) NULL,
  `isHeadOfHousehold` TINYINT(1) NULL,
  `notes` VARCHAR(256) NULL,
  `birthDate` DATE NULL,
  `clientID` INT NOT NULL,
  `timestamp` DATETIME NULL,
  `isDeleted` TINYINT(1) NULL,
  PRIMARY KEY (`familyMemberID`, `clientID`),
  INDEX `fk_FamilyMember_Client1_idx` (`clientID` ASC),
  CONSTRAINT `fk_FamilyMember_Client1`
    FOREIGN KEY (`clientID`)
    REFERENCES `FoodPantry`.`Client` (`clientID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FoodPantry`.`DonationPartner`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `FoodPantry`.`DonationPartner` (
  `donationPartnerID` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `city` VARCHAR(45) NULL,
  `state` VARCHAR(45) NULL,
  `zip` VARCHAR(45) NULL,
  `address` VARCHAR(45) NULL,
  `phoneNumber` VARCHAR(45) NULL,
  UNIQUE INDEX `DonationPartnerID_UNIQUE` (`donationPartnerID` ASC),
  PRIMARY KEY (`donationPartnerID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FoodPantry`.`Donation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `FoodPantry`.`Donation` (
  `donationID` INT NOT NULL AUTO_INCREMENT,
  `donationPartnerID` INT NOT NULL,
  `dateOfPickup` DATE NULL,
  `networkPartner` VARCHAR(45) NULL DEFAULT 'RUMC',
  `agency` VARCHAR(45) NULL DEFAULT '1039a',
  `frozenNonMeat` INT NULL,
  `frozenMeat` INT NULL,
  `frozenPrepared` INT NULL,
  `refBakery` INT NULL,
  `refProduce` INT NULL,
  `refDairyAndDeli` INT NULL,
  `dryShelfStable` INT NULL,
  `dryNonFood` INT NULL,
  `dryFoodDrive` INT UNSIGNED NULL,
  PRIMARY KEY (`donationID`, `donationPartnerID`),
  UNIQUE INDEX `idDonation_UNIQUE` (`donationID` ASC),
  INDEX `fk_Donation_DonationPartner1_idx` (`donationPartnerID` ASC),
  CONSTRAINT `fk_Donation_DonationPartner1`
    FOREIGN KEY (`donationPartnerID`)
    REFERENCES `FoodPantry`.`DonationPartner` (`donationPartnerID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;