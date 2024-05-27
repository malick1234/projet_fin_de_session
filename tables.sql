USE abelmalick_database;

-- Création de la table 'utilisateurs'
CREATE TABLE utilisateurs (
    NoUtilisateur INT NOT NULL AUTO_INCREMENT,
    Courriel VARCHAR(50) NOT NULL UNIQUE,
    MotDePasse VARCHAR(15) NOT NULL,
    Creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    NbConnexions INT DEFAULT 0,
    Statut INT,
    NoEmpl INT,
    Nom VARCHAR(25) NOT NULL,
    Prenom VARCHAR(20) NOT NULL,
    NoTelMaison VARCHAR(15),
    NoTelTravail VARCHAR(21),
    NoTelCellulaire VARCHAR(15),
    Modification DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    AutresInfos VARCHAR(50),
    PRIMARY KEY (NoUtilisateur),
    CHECK (NbConnexions BETWEEN 0 AND 9999),
    CHECK (Statut IN (0, 1, 2, 3, 4, 5, 9)),
    CHECK (NoEmpl BETWEEN 1 AND 9999),
    CHECK (MotDePasse REGEXP '^[A-Za-z0-9]{5,15}$'),
    CHECK (NoTelMaison REGEXP '^\\(\\d{3}\\) \\d{3}-\\d{4}[PN]$'),
    CHECK (NoTelTravail REGEXP '^\\(\\d{3}\\) \\d{3}-\\d{4} #\\d{4}[PN]$'),
    CHECK (NoTelCellulaire REGEXP '^\\(\\d{3}\\) \\d{3}-\\d{4}[PN]$')
);

-- Ajout de l'utilisateur administrateur
INSERT INTO utilisateurs (NoUtilisateur, Courriel, MotDePasse, Creation, Statut, NoEmpl, Nom, Prenom)
VALUES (1, 'admin@gmail.com', 'Secret123', NOW(), 1, 1, 'Administrateur', 'Admin')
ON DUPLICATE KEY UPDATE Courriel = Courriel;

-- Création de la table 'connexions'
CREATE TABLE connexions (
    NoConnexion INT NOT NULL AUTO_INCREMENT,
    NoUtilisateur INT,
    Connexion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Deconnexion DATETIME,
    PRIMARY KEY (NoConnexion),
    FOREIGN KEY (NoUtilisateur) REFERENCES utilisateurs(NoUtilisateur)
);

-- Création de la table 'annonces'
CREATE TABLE annonces (
    NoAnnonce INT NOT NULL AUTO_INCREMENT,
    NoUtilisateur INT,
    Parution DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Categorie INT,
    DescriptionAbregee VARCHAR(50),
    DescriptionComplete VARCHAR(250),
    Prix DECIMAL(10, 2),
    Photo VARCHAR(50),
    MiseAJour DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    Etat INT,
    PRIMARY KEY (NoAnnonce),
    FOREIGN KEY (NoUtilisateur) REFERENCES utilisateurs(NoUtilisateur),
    CHECK (Prix BETWEEN 0.00 AND 99999.99),
    CHECK (Categorie BETWEEN 1 AND 6),
    CHECK (Etat IN (1, 2, 3))
);

-- Création de la table 'categories'
CREATE TABLE categories (
    NoCategorie INT,
    Description VARCHAR(20),
    PRIMARY KEY (NoCategorie),
    CHECK (NoCategorie BETWEEN 1 AND 6)
);

-- Ajout des catégories
INSERT INTO categories (NoCategorie, Description) VALUES
(1, 'Location'),
(2, 'Recherche'),
(3, 'À vendre'),
(4, 'À donner'),
(5, 'Service offert'),
(6, 'Autre')
ON DUPLICATE KEY UPDATE Description = VALUES(Description);
