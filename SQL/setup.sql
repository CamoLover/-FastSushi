-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema fastsushi
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema fastsushi
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `fastsushi` DEFAULT CHARACTER SET utf8 ;
USE `fastsushi` ;

-- -----------------------------------------------------
-- Table `fastsushi`.`Client`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `fastsushi`.`Client` (
  `id_client` INT NOT NULL,
  `nom` VARCHAR(45) NOT NULL,
  `prenom` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NULL,
  `tel` VARCHAR(45) NULL,
  `mdp` VARCHAR(45) NOT NULL,
  `adresse` VARCHAR(100) NULL,
  `cp` INT NULL,
  `ville` VARCHAR(45) NULL,
  PRIMARY KEY (`id_client`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fastsushi`.`Panier`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `fastsushi`.`Panier` (
  `id_panier` INT NOT NULL,
  `id_session` VARCHAR(45) NOT NULL,
  `id_client` INT NULL,
  `date_panier` DATE NOT NULL,
  `montant_tot` DECIMAL(10,3) NULL,
  PRIMARY KEY (`id_panier`),
  INDEX `id_client_idx` (`id_client` ASC) VISIBLE,
  CONSTRAINT `id_client`
    FOREIGN KEY (`id_client`)
    REFERENCES `fastsushi`.`Client` (`id_client`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fastsushi`.`Produit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `fastsushi`.`Produit` (
  `id_produit` INT NOT NULL,
  `nom` VARCHAR(45) NOT NULL,
  `type_produit` VARCHAR(45) NOT NULL,
  `prix_ttc` DECIMAL(10,3) NOT NULL,
  `prix_ht` DECIMAL(10,4) NOT NULL,
  `photo` VARCHAR(200) NULL,
  `tva` DECIMAL(10,4) NOT NULL,
  `description` VARCHAR(200) NULL,
  PRIMARY KEY (`id_produit`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fastsushi`.`Commande`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `fastsushi`.`Commande` (
  `id_commande` INT NOT NULL,
  `id_client` INT NOT NULL,
  `date_panier` DATE NOT NULL,
  `montant_tot` DECIMAL(10,3) NULL,
  `statut` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_commande`),
  INDEX `id_client_idx` (`id_client` ASC) VISIBLE,
  CONSTRAINT `id_client`
    FOREIGN KEY (`id_client`)
    REFERENCES `fastsushi`.`Client` (`id_client`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fastsushi`.`Panier_ligne`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `fastsushi`.`Panier_ligne` (
  `id_panier_ligne` INT NOT NULL,
  `id_panier` INT NOT NULL,
  `id_produit` INT NOT NULL,
  `quantite` INT NOT NULL,
  `nom` VARCHAR(45) NOT NULL,
  `prix_ht` DECIMAL(10,4) NOT NULL,
  `prix_ttc` DECIMAL(10,3) NOT NULL,
  PRIMARY KEY (`id_panier_ligne`),
  INDEX `id_panier_idx` (`id_panier` ASC) VISIBLE,
  INDEX `id_produit_idx` (`id_produit` ASC) VISIBLE,
  CONSTRAINT `id_panier`
    FOREIGN KEY (`id_panier`)
    REFERENCES `fastsushi`.`Panier` (`id_panier`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `id_produit`
    FOREIGN KEY (`id_produit`)
    REFERENCES `fastsushi`.`Produit` (`id_produit`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fastsushi`.`Commande_ligne`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `fastsushi`.`Commande_ligne` (
  `id_commande_ligne` INT NOT NULL,
  `id_commande` INT NOT NULL,
  `id_produit` INT NOT NULL,
  `quantite` INT NOT NULL,
  `nom` VARCHAR(45) NOT NULL,
  `prix_ht` DECIMAL(10,4) NOT NULL,
  `prix_ttc` DECIMAL(10,3) NOT NULL,
  PRIMARY KEY (`id_commande_ligne`),
  INDEX `id_commande_idx` (`id_commande` ASC) VISIBLE,
  INDEX `id_produit_idx` (`id_produit` ASC) VISIBLE,
  CONSTRAINT `id_commande`
    FOREIGN KEY (`id_commande`)
    REFERENCES `fastsushi`.`Commande` (`id_commande`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `id_produit`
    FOREIGN KEY (`id_produit`)
    REFERENCES `fastsushi`.`Produit` (`id_produit`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fastsushi`.`Ingredient`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `fastsushi`.`Ingredient` (
  `id_ingredient` INT NOT NULL,
  `nom` VARCHAR(45) NOT NULL,
  `photo` VARCHAR(200) NULL,
  `prix_ht` DECIMAL(10,4) NOT NULL,
  PRIMARY KEY (`id_ingredient`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fastsushi`.`Employe`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `fastsushi`.`Employe` (
  `id_employe` INT NOT NULL,
  `nom` VARCHAR(45) NOT NULL,
  `prenom` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `mdp` VARCHAR(45) NOT NULL,
  `statut_emp` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_employe`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fastsushi`.`Compo_panier`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `fastsushi`.`Compo_panier` (
  `id_panier_ligne` INT NOT NULL,
  `id_ingredient` INT NOT NULL,
  `prix` DECIMAL(10,4) NOT NULL,
  PRIMARY KEY (`id_panier_ligne`, `id_ingredient`),
  INDEX `id_ingredient_idx` (`id_ingredient` ASC) VISIBLE,
  CONSTRAINT `id_ingredient`
    FOREIGN KEY (`id_ingredient`)
    REFERENCES `fastsushi`.`Ingredient` (`id_ingredient`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `id_panier_ligne`
    FOREIGN KEY (`id_panier_ligne`)
    REFERENCES `fastsushi`.`Panier_ligne` (`id_panier_ligne`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fastsushi`.`Compo_commande`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `fastsushi`.`Compo_commande` (
  `id_commande_ligne` INT NOT NULL,
  `id_ingredient` INT NOT NULL,
  `prix` DECIMAL(10,4) NOT NULL,
  PRIMARY KEY (`id_commande_ligne`, `id_ingredient`),
  INDEX `id_ingredient_idx` (`id_ingredient` ASC) VISIBLE,
  CONSTRAINT `id_commande_ligne`
    FOREIGN KEY (`id_commande_ligne`)
    REFERENCES `fastsushi`.`Commande_ligne` (`id_commande_ligne`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `id_ingredient`
    FOREIGN KEY (`id_ingredient`)
    REFERENCES `fastsushi`.`Ingredient` (`id_ingredient`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
