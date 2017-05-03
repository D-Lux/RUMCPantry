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
-- Table `FoodPantry`.`Item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `FoodPantry`.`Item` (
  `itemID` INT NOT NULL,
  `itemName` VARCHAR(45) NULL,
  `category` VARCHAR(45) NULL,
  `displayName` VARCHAR(45) NULL,
  `price` DECIMAL(8,2) NULL,
  `timestamp` DATETIME NULL,
  `isDeleted` TINYINT(1) NULL,
  PRIMARY KEY (`itemID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FoodPantry`.`Client`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `FoodPantry`.`Client` (
  `clientID` INT NOT NULL,
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
  PRIMARY KEY (`clientID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FoodPantry`.`Invoice`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `FoodPantry`.`Invoice` (
  `invoiceID` INT NOT NULL,
  `visitDate` DATE NULL,
  `clientID` INT NOT NULL,
  `hasBeenFilled` TINYINT(1) NULL,
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
  `inventoryID` INT NOT NULL,
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
  `FamilyMemberID` INT NOT NULL,
  `firstName` VARCHAR(45) NULL,
  `lastName` VARCHAR(45) NULL,
  `isHeadOfHousehold` TINYINT(1) NULL,
  `notes` VARCHAR(256) NULL,
  `birthDate` DATE NULL,
  `clientID` INT NOT NULL,
  `timestamp` DATETIME NULL,
  `isDeleted` TINYINT(1) NULL,
  PRIMARY KEY (`FamilyMemberID`, `clientID`),
  INDEX `fk_FamilyMember_Client1_idx` (`clientID` ASC),
  CONSTRAINT `fk_FamilyMember_Client1`
    FOREIGN KEY (`clientID`)
    REFERENCES `FoodPantry`.`Client` (`clientID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
