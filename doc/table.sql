-- Création de la base de données   ::::::::  incomplet pour le moment
CREATE DATABASE statpol;
USE statpol;

-- Table des unités
CREATE TABLE unite (
    id_unite INT AUTO_INCREMENT PRIMARY KEY,
    nom_unite VARCHAR(50) NOT NULL
);

-- Table des années
CREATE TABLE annee (
    id_annee INT AUTO_INCREMENT PRIMARY KEY,
    annee YEAR NOT NULL
);

-- Table des environnements
CREATE TABLE environnement (
    id_environnement INT AUTO_INCREMENT PRIMARY KEY,
    nom_environnement VARCHAR(50) NOT NULL
);

-- Table des polluants
CREATE TABLE polluant (
    id_polluant INT AUTO_INCREMENT PRIMARY KEY,
    nom_polluant VARCHAR(100) NOT NULL
);

-- Table des noms d'instituts
CREATE TABLE nom_institut (
    id_nom_institut INT AUTO_INCREMENT PRIMARY KEY,
    nom_institut VARCHAR(255) NOT NULL
);

-- Table des codes postaux
CREATE TABLE code_postal (
    id_code_postal INT AUTO_INCREMENT PRIMARY KEY,
    code_postal VARCHAR(10) NOT NULL
);

-- Table des communes
CREATE TABLE commune (
    id_commune INT AUTO_INCREMENT PRIMARY KEY,
    nom_commune VARCHAR(100) NOT NULL
);

-- Table des départements
CREATE TABLE departement (
    id_departement INT AUTO_INCREMENT PRIMARY KEY,
    nom_departement VARCHAR(100) NOT NULL
);

-- Table des régions
CREATE TABLE region (
    id_region INT AUTO_INCREMENT PRIMARY KEY,
    nom_region VARCHAR(100) NOT NULL
);

-- Table des codes EPSG
CREATE TABLE code_epsg (
    id_code_epsg INT AUTO_INCREMENT PRIMARY KEY,
    code_epsg VARCHAR(50) NOT NULL
);

-- Table des codes APE
CREATE TABLE code_ape (
    id_code_ape INT AUTO_INCREMENT PRIMARY KEY,
    code_ape VARCHAR(10) NOT NULL
);

-- Table des libellés APE
CREATE TABLE libelle_ape (
    id_libelle_ape INT AUTO_INCREMENT PRIMARY KEY,
    libelle_ape VARCHAR(255) NOT NULL
);

-- Table des codes EPTR
CREATE TABLE code_eptr (
    id_code_eptr INT AUTO_INCREMENT PRIMARY KEY,
    code_eptr VARCHAR(50) NOT NULL
);

-- Table des libellés EPTR
CREATE TABLE libelle_eptr (
    id_libelle_eptr INT AUTO_INCREMENT PRIMARY KEY,
    libelle_eptr VARCHAR(255) NOT NULL
);

-- Table des instituts
CREATE TABLE institut (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_csv INT,
    id_nom_institut INT,
    siret VARCHAR(14),
    adresse VARCHAR(255),
    id_code_postal INT,
    id_commune INT,
    id_departement INT,
    id_region INT,
    coord_x DECIMAL(10, 6),
    coord_y DECIMAL(10, 6),
    id_code_epsg INT,
    id_code_ape INT,
    id_libelle_ape INT,
    id_code_eptr INT,
    id_libelle_eptr INT,
    FOREIGN KEY (id_nom_institut) REFERENCES nom_institut(id_nom_institut),
    FOREIGN KEY (id_code_postal) REFERENCES code_postal(id_code_postal),
    FOREIGN KEY (id_commune) REFERENCES commune(id_commune),
    FOREIGN KEY (id_departement) REFERENCES departement(id_departement),
    FOREIGN KEY (id_region) REFERENCES region(id_region),
    FOREIGN KEY (id_code_epsg) REFERENCES code_epsg(id_code_epsg),
    FOREIGN KEY (id_code_ape) REFERENCES code_ape(id_code_ape),
    FOREIGN KEY (id_libelle_ape) REFERENCES libelle_ape(id_libelle_ape),
    FOREIGN KEY (id_code_eptr) REFERENCES code_eptr(id_code_eptr),
    FOREIGN KEY (id_libelle_eptr) REFERENCES libelle_eptr(id_libelle_eptr)
);

-- Table des émissions
CREATE TABLE emission (
    id_emission INT AUTO_INCREMENT PRIMARY KEY,
    id_csv INT,
    id_institut INT,
    id_annee INT,
    id_environnement INT,
    id_polluant INT,
    id_unite INT,
    quantite DECIMAL(15, 3),
    FOREIGN KEY (id_institut) REFERENCES institut(id_institut),
    FOREIGN KEY (id_annee) REFERENCES annee(id_annee),
    FOREIGN KEY (id_environnement) REFERENCES environnement(id_environnement),
    FOREIGN KEY (id_polluant) REFERENCES polluant(id_polluant),
    FOREIGN KEY (id_unite) REFERENCES unite(id_unite)
);
