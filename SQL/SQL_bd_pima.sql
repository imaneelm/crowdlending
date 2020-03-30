-- Création de la base de données
DROP DATABASE IF EXISTS bd_pima;
CREATE DATABASE bd_pima;
USE bd_pima;

-- Creation des tables
CREATE TABLE Role(
  idRole INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  nomRole VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE Membre
(
    idMembre INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    pseudo VARCHAR(16) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    mdp VARCHAR(200) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    adresse VARCHAR(200) NOT NULL,
    telephone VARCHAR(15) NOT NULL,
    idRole INT NOT NULL,
    note int,
    image varchar(255) DEFAULT 'profil.png',
    banni BOOLEAN NOT NULL DEFAULT FALSE,
    cle varchar(32) NOT NULL,
    actif int(11) DEFAULT NULL,
    CONSTRAINT UC_Membre UNIQUE (pseudo,email),
    FOREIGN KEY (idRole) REFERENCES Role(idRole)
);

CREATE TABLE Categorie
(
  idCategorie INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  nom VARCHAR(200) NOT NULL UNIQUE
);

CREATE TABLE Etat
(
    idEtat INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nom VARCHAR(200) NOT NULL
);

CREATE TABLE Objet
(
  idObjet INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  nom VARCHAR(200) NOT NULL,
  idCategorie INT NOT NULL,
    idEtat INT NOT NULL,
  idPossesseur INT NOT NULL,
    signale INT NOT NULL DEFAULT 0,
	image varchar(255) DEFAULT 'default.jpg',
  FOREIGN KEY (idCategorie) REFERENCES Categorie(idCategorie),
    FOREIGN KEY (idEtat) REFERENCES Etat(idEtat),
  FOREIGN KEY (idPossesseur) REFERENCES Membre(idMembre)
);

CREATE TABLE Emprunt
(
    idEmprunt INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    idObjet INT NOT NULL,
    dateDebut DATE NOT NULL,
    dateFin DATE NOT NULL,
    idEmprunteur INT NOT NULL,
    validation TINYINT(1) NOT NULL DEFAULT 0,
    commentaire VARCHAR(2000),
    date_commentaire VARCHAR(2000),
    noteEmprunteur INT DEFAULT 0,
    FOREIGN KEY (idObjet)  REFERENCES Objet(idObjet) ON DELETE CASCADE,
    FOREIGN KEY (idEmprunteur)  REFERENCES Membre(idMembre)
);

CREATE TABLE Message
(
  idMessage INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  idEmetteur INT NOT NULL,
  idRecepteur INT NOT NULL,
  Message TEXT NOT NULL,
  dateMessage TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (idEmetteur) REFERENCES Membre(idMembre),
  FOREIGN KEY (idRecepteur) REFERENCES Membre(idMembre)
);

CREATE TABLE TypeNotification
(
    idTypeNotification INT PRIMARY KEY NOT NULL,
    type VARCHAR(200)
);

CREATE TABLE Notification
(
    idNotification INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    idMembreNotif INT NOT NULL, -- la personne qui reçoit la notification
    idMembreLien INT,  -- lien vers la personne concernée par la notification
    idObjet INT,
    idTypeNotification INT NOT NULL,
    dateNotif DATE NOT NULL,
    lu BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (idMembreNotif) REFERENCES Membre(idMembre),
    FOREIGN KEY (idMembreLien) REFERENCES Membre(idMembre),
    FOREIGN KEY (idObjet) REFERENCES Objet(idObjet) ON DELETE CASCADE,
    FOREIGN KEY (idTypeNotification) REFERENCES TypeNotification(idTypeNotification)
);

CREATE TABLE NotificationMessage
(
  idNotification INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  idRecepteur INT NOT NULL,
  idEmetteur INT NOT NULL,
  idMessage INT NOT NULL,
  idTypeNotification INT NOT NULL,
  dateNotif DATE NOT NULL,
  lu BOOLEAN NOT NULL DEFAULT FALSE,
  FOREIGN KEY (idRecepteur) REFERENCES Membre(idMembre),
  FOREIGN KEY (idEmetteur) REFERENCES Membre(idMembre),
  FOREIGN KEY (idTypeNotification) REFERENCES TypeNotification(idTypeNotification),
  FOREIGN KEY (idMessage) REFERENCES Message(idMessage)
);

CREATE TABLE Favori (
  idFavori INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  idObjet INT(11) NOT NULL,
  idMembre INT(11) NOT NULL,
  FOREIGN KEY (idObjet) REFERENCES Objet(idObjet) ON DELETE CASCADE,
  FOREIGN KEY (idMembre) REFERENCES Membre(idMembre)
);

ALTER TABLE Favori
  ADD CONSTRAINT uq_favori UNIQUE(idObjet, idMembre);

CREATE TABLE Reclamation (
  idRec int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  idEmprunt int(11) NOT NULL UNIQUE,
  idEmprunteur int(11) NOT NULL,
  idPossesseur int(11) NOT NULL,
  motif VARCHAR(20),
  reponse VARCHAR(100),
  averti INT DEFAULT 0,
  FOREIGN KEY (idEmprunteur) REFERENCES  Membre(idMembre),
  FOREIGN KEY (idPossesseur) REFERENCES Membre(idMembre),
  FOREIGN KEY (idEmprunt) REFERENCES Emprunt(idEmprunt) ON DELETE CASCADE
);

-- Insertion
INSERT INTO Role (idRole, nomRole)
  VALUES (1, "admin");
INSERT INTO Role (idRole, nomRole)
  VALUES (2, "membre");

INSERT INTO Membre (idMembre, pseudo, email, mdp, nom, prenom, adresse, telephone, idRole)
  VALUES (1, "Alex", "alex@mail.com", "123", "Weber", "Alexandra", "12 rue Pierre Mauroy, 91000 Evry", "0687352610", 1);
INSERT INTO Membre (idMembre, pseudo, email, mdp, nom, prenom, adresse, telephone, idRole)
  VALUES (2, "Emy", "emy46@mail.com", "123", "Perrin", "Emy", "5 avenue Simone Veil, 91000 Evry", "0719253701", 2);
INSERT INTO Membre (idMembre, pseudo, email, mdp, nom, prenom, adresse, telephone, idRole)
    VALUES (3, "Max", "max1@mail.com", "123", "Millet", "Maxime", "2 rue du Village, 91000 Evry", "0617284612", 2);

INSERT INTO Etat VALUES (1, "Bon");
INSERT INTO Etat VALUES (2, "Pas mal");
INSERT INTO Etat VALUES (3, "Mauvais");

INSERT INTO Categorie VALUES (1, "Non catégorisé");
INSERT INTO Categorie VALUES (2, "Bureau");
INSERT INTO Categorie VALUES (3, "Bricolage");
INSERT INTO Categorie VALUES (4, "Sport");
INSERT INTO Categorie VALUES (5, "Jeux");
INSERT INTO Categorie VALUES (6, "Cuisine");

INSERT INTO Objet (idObjet, nom, idCategorie, idEtat, idPossesseur)
                    VALUES (1, "Agrafeuse", 2, 1, 1),
                    (2, "Imprimante", 2, 2, 2),
                    (3, "Perceuse", 3, 1, 3),
                    (4, "Blender", 6, 1, 1),
                    (5, "Marteau", 3, 2, 3),
                    (6, "Rollers", 4, 2, 1),
                    (7, "Skis", 4, 1, 2),
                    (8, "Loup garou", 5, 2, 1),
                    (9, "Console Wii", 5, 1, 2),
                    (10, "Batteur", 6, 1, 3);

INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (1, "2019-10-04", "2019-10-06", 2, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (1, "2019-11-03", "2019-11-10", 2, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (1, "2019-11-11", "2019-11-14", 3, 0);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (1, "2019-11-13", "2019-11-16", 2, 0);

INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (2, "2019-09-03", "2019-09-05", 3, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (2, "2019-09-06", "2019-09-20", 1, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (2, "2019-11-12", "2019-11-20", 3, 0);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (2, "2019-12-05", "2019-12-07", 1, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (2, "2019-12-08", "2019-12-12", 3, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (2, "2019-12-15", "2019-12-17", 1, 1);

INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (3, "2019-08-23", "2019-09-01", 2, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (3, "2019-09-06", "2019-09-20", 1, 2);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (3, "2019-11-05", "2019-11-07", 1, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (3, "2019-11-08", "2019-11-12", 2, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (3, "2019-11-13", "2019-11-20", 2, 0);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (3, "2019-11-21", "2019-11-22", 1, 0);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (3, "2019-11-15", "2019-11-17", 1, 0);

INSERT INTO TypeNotification VALUES (1, "demandeEmprunt"), (2, "refusEmprunt"), (3, "validationEmprunt"),
    (4, "nouveauCommentaire"), (5, "nouveauFavori"), (6, "objetSupprime"), (7, "messageEnvoye"), (8, "avertissement");

INSERT INTO Favori (idFavori, idObjet, idMembre) VALUES (21, 3, 1);
INSERT INTO Favori (idFavori, idObjet, idMembre) VALUES (25, 2, 1);

INSERT INTO Reclamation (idEmprunteur, idPossesseur,idEmprunt, motif )
    VALUES (2,1,1, "retard");

-- Triggers pour les notifications

-- Demande d'emprunt
CREATE TRIGGER demande_emprunt AFTER INSERT
ON Emprunt FOR EACH ROW
INSERT INTO Notification(idMembreNotif, idMembreLien, idObjet, idTypeNotification, dateNotif) VALUES(
    (SELECT idPossesseur FROM Objet WHERE idObjet = NEW.idObjet), -- le propriétaire
    NEW.idEmprunteur,
    NEW.idObjet,
    1,
    NOW()
);

-- Validation emprunt
DELIMITER |
CREATE TRIGGER validation_emprunt AFTER UPDATE
ON Emprunt FOR EACH ROW
BEGIN
IF (NEW.validation <> OLD.validation) THEN

    -- Refus
    IF (NEW.validation = 2) THEN
        INSERT INTO Notification(idMembreNotif, idMembreLien, idObjet, idTypeNotification, dateNotif) VALUES(
            NEW.idEmprunteur,
            (SELECT idPossesseur FROM Objet WHERE idObjet = NEW.idObjet),
            NEW.idObjet,
            2,
            NOW());

    -- Validation
    ELSEIF (NEW.validation = 1) THEN
        INSERT INTO Notification(idMembreNotif, idMembreLien, idObjet, idTypeNotification, dateNotif) VALUES(
            NEW.idEmprunteur,
            (SELECT idPossesseur FROM Objet WHERE idObjet = NEW.idObjet),
            NEW.idObjet,
            3,
            NOW());
    END IF;

END IF;
END |
DELIMITER ;

DELIMITER |
CREATE TRIGGER ajout_commentaire AFTER UPDATE
ON Emprunt FOR EACH ROW
BEGIN
IF (NEW.commentaire <> OLD.commentaire) THEN
    INSERT INTO Notification(idMembreNotif, idMembreLien, idObjet, idTypeNotification, dateNotif) VALUES(
        (SELECT idPossesseur FROM Objet WHERE idObjet = NEW.idObjet),
        NEW.idEmprunteur,
        NEW.idObjet,
        4,
        NOW());
END IF;
END |
DELIMITER ;

CREATE TRIGGER ajout_favori AFTER INSERT
ON Favori FOR EACH ROW
INSERT INTO Notification(idMembreNotif, idMembreLien, idObjet, idTypeNotification, dateNotif) VALUES(
    (SELECT idPossesseur FROM Objet WHERE idObjet = NEW.idObjet),
    NEW.idMembre,
    NEW.idObjet,
    5,
    NOW());


DROP TRIGGER IF EXISTS suppression_objet;

CREATE TRIGGER suppression_objet BEFORE DELETE
ON Objet FOR EACH ROW
INSERT INTO Notification(idMembreNotif, idTypeNotification, dateNotif) VALUES(
    (SELECT idPossesseur FROM Objet WHERE idObjet = OLD.idObjet),
    6,
    NOW()
);

CREATE TRIGGER envoi_message AFTER INSERT
ON Message FOR EACH ROW
INSERT INTO NotificationMessage(idRecepteur, idEmetteur, idMessage, idTypeNotification, dateNotif) VALUES(
    (SELECT idRecepteur FROM Message WHERE idMessage = NEW.idMessage), (SELECT idEmetteur FROM Message WHERE idMessage = NEW.idMessage),
    NEW.idMessage,
    7,
    NOW()
);

DELIMITER |
CREATE TRIGGER bannissement AFTER INSERT
ON Notification FOR EACH ROW
BEGIN
IF (NEW.idTypeNotification = 8) THEN
  IF ((SELECT COUNT(*) FROM Notification WHERE idMembreNotif = NEW.idMembreNotif AND idTypeNotification = 8) >=3) THEN
    UPDATE Membre SET BANNI = TRUE WHERE idMembre = NEW.idMembreNotif;
  END IF;
END IF;
END |
DELIMITER ;

INSERT INTO Notification(idMembreNotif, idTypeNotification, dateNotif) VALUES (3, 8, NOW());
INSERT INTO Notification(idMembreNotif, idTypeNotification, dateNotif) VALUES (3, 8, NOW());-- Création de la base de données
DROP DATABASE IF EXISTS bd_pima;
CREATE DATABASE bd_pima;
USE bd_pima;

-- Creation des tables
CREATE TABLE Role(
  idRole INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  nomRole VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE Membre
(
    idMembre INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    pseudo VARCHAR(16) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    mdp VARCHAR(200) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    adresse VARCHAR(200) NOT NULL,
    telephone VARCHAR(15) NOT NULL,
    idRole INT NOT NULL,
    note int,
    image varchar(255) DEFAULT 'profil.png',
    banni BOOLEAN NOT NULL DEFAULT FALSE,
    CONSTRAINT UC_Membre UNIQUE (pseudo,email),
    FOREIGN KEY (idRole) REFERENCES Role(idRole)
);

CREATE TABLE Categorie
(
  idCategorie INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  nom VARCHAR(200) NOT NULL UNIQUE
);

CREATE TABLE Etat
(
    idEtat INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nom VARCHAR(200) NOT NULL
);

CREATE TABLE Objet
(
  idObjet INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  nom VARCHAR(200) NOT NULL,
  idCategorie INT NOT NULL,
    idEtat INT NOT NULL,
  idPossesseur INT NOT NULL,
    signale INT NOT NULL DEFAULT 0,
  image varchar(255) DEFAULT 'default.jpg',
  FOREIGN KEY (idCategorie) REFERENCES Categorie(idCategorie),
    FOREIGN KEY (idEtat) REFERENCES Etat(idEtat),
  FOREIGN KEY (idPossesseur) REFERENCES Membre(idMembre)
);

CREATE TABLE Emprunt
(
    idEmprunt INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    idObjet INT NOT NULL,
    dateDebut DATE NOT NULL,
    dateFin DATE NOT NULL,
    idEmprunteur INT NOT NULL,
    validation TINYINT(1) NOT NULL DEFAULT 0,
    commentaire VARCHAR(2000),
    date_commentaire VARCHAR(2000),
    noteEmprunteur INT DEFAULT 0,
    FOREIGN KEY (idObjet)  REFERENCES Objet(idObjet) ON DELETE CASCADE,
    FOREIGN KEY (idEmprunteur)  REFERENCES Membre(idMembre)
);

CREATE TABLE Message
(
  idMessage INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  idEmetteur INT NOT NULL,
  idRecepteur INT NOT NULL,
  Message TEXT NOT NULL,
  dateMessage TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (idEmetteur) REFERENCES Membre(idMembre),
  FOREIGN KEY (idRecepteur) REFERENCES Membre(idMembre)
);

CREATE TABLE TypeNotification
(
    idTypeNotification INT PRIMARY KEY NOT NULL,
    type VARCHAR(200)
);

CREATE TABLE Notification
(
    idNotification INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    idMembreNotif INT NOT NULL, -- la personne qui reçoit la notification
    idMembreLien INT,  -- lien vers la personne concernée par la notification
    idObjet INT,
    idTypeNotification INT NOT NULL,
    dateNotif DATE NOT NULL,
    lu BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (idMembreNotif) REFERENCES Membre(idMembre),
    FOREIGN KEY (idMembreLien) REFERENCES Membre(idMembre),
    FOREIGN KEY (idObjet) REFERENCES Objet(idObjet) ON DELETE CASCADE,
    FOREIGN KEY (idTypeNotification) REFERENCES TypeNotification(idTypeNotification)
);

CREATE TABLE NotificationMessage
(
  idNotification INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  idRecepteur INT NOT NULL,
  idEmetteur INT NOT NULL,
  idMessage INT NOT NULL,
  idTypeNotification INT NOT NULL,
  dateNotif DATE NOT NULL,
  lu BOOLEAN NOT NULL DEFAULT FALSE,
  FOREIGN KEY (idRecepteur) REFERENCES Membre(idMembre),
  FOREIGN KEY (idEmetteur) REFERENCES Membre(idMembre),
  FOREIGN KEY (idTypeNotification) REFERENCES TypeNotification(idTypeNotification),
  FOREIGN KEY (idMessage) REFERENCES Message(idMessage)
);

CREATE TABLE Favori (
  idFavori INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  idObjet INT(11) NOT NULL,
  idMembre INT(11) NOT NULL,
  FOREIGN KEY (idObjet) REFERENCES Objet(idObjet) ON DELETE CASCADE,
  FOREIGN KEY (idMembre) REFERENCES Membre(idMembre)
);

ALTER TABLE Favori
  ADD CONSTRAINT uq_favori UNIQUE(idObjet, idMembre);

CREATE TABLE Reclamation (
  idRec int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  idEmprunt int(11) NOT NULL UNIQUE,
  idEmprunteur int(11) NOT NULL,
  idPossesseur int(11) NOT NULL,
  motif VARCHAR(20),
  reponse VARCHAR(100),
  FOREIGN KEY (idEmprunteur) REFERENCES  Membre(idMembre),
  FOREIGN KEY (idPossesseur) REFERENCES Membre(idMembre),
  FOREIGN KEY (idEmprunt) REFERENCES Emprunt(idEmprunt) ON DELETE CASCADE
);

-- Insertion
INSERT INTO Role (idRole, nomRole)
  VALUES (1, "admin");
INSERT INTO Role (idRole, nomRole)
  VALUES (2, "membre");

INSERT INTO Membre (idMembre, pseudo, email, mdp, nom, prenom, adresse, telephone, idRole)
  VALUES (1, "Alex", "alex@mail.com", "123", "Weber", "Alexandra", "12 rue Pierre Mauroy, 91000 Evry", "0687352610", 1);
INSERT INTO Membre (idMembre, pseudo, email, mdp, nom, prenom, adresse, telephone, idRole)
  VALUES (2, "Emy", "emy46@mail.com", "123", "Perrin", "Emy", "5 avenue Simone Veil, 91000 Evry", "0719253701", 2);
INSERT INTO Membre (idMembre, pseudo, email, mdp, nom, prenom, adresse, telephone, idRole)
    VALUES (3, "Max", "max1@mail.com", "123", "Millet", "Maxime", "2 rue du Village, 91000 Evry", "0617284612", 2);

INSERT INTO Etat VALUES (1, "Bon");
INSERT INTO Etat VALUES (2, "Pas mal");
INSERT INTO Etat VALUES (3, "Mauvais");

INSERT INTO Categorie VALUES (1, "Non catégorisé");
INSERT INTO Categorie VALUES (2, "Bureau");
INSERT INTO Categorie VALUES (3, "Bricolage");
INSERT INTO Categorie VALUES (4, "Sport");
INSERT INTO Categorie VALUES (5, "Jeux");
INSERT INTO Categorie VALUES (6, "Cuisine");

INSERT INTO Objet (idObjet, nom, idCategorie, idEtat, idPossesseur)
                    VALUES (1, "Agrafeuse", 2, 1, 1),
                    (2, "Imprimante", 2, 2, 2),
                    (3, "Perceuse", 3, 1, 3),
                    (4, "Blender", 6, 1, 1),
                    (5, "Marteau", 3, 2, 3),
                    (6, "Rollers", 4, 2, 1),
                    (7, "Skis", 4, 1, 2),
                    (8, "Loup garou", 5, 2, 1),
                    (9, "Console Wii", 5, 1, 2),
                    (10, "Batteur", 6, 1, 3);

INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (1, "2019-10-04", "2019-10-06", 2, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (1, "2019-11-03", "2019-11-10", 2, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (1, "2019-11-11", "2019-11-14", 3, 0);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (1, "2019-11-13", "2019-11-16", 2, 0);

INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (2, "2019-09-03", "2019-09-05", 3, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (2, "2019-09-06", "2019-09-20", 1, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (2, "2019-11-12", "2019-11-20", 3, 0);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (2, "2019-12-05", "2019-12-07", 1, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (2, "2019-12-08", "2019-12-12", 3, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (2, "2019-12-15", "2019-12-17", 1, 1);

INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (3, "2019-08-23", "2019-09-01", 2, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (3, "2019-09-06", "2019-09-20", 1, 2);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (3, "2019-11-05", "2019-11-07", 1, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (3, "2019-11-08", "2019-11-12", 2, 1);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (3, "2019-11-13", "2019-11-20", 2, 0);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (3, "2019-11-21", "2019-11-22", 1, 0);
INSERT INTO Emprunt (idObjet, dateDebut, dateFin, idEmprunteur, validation) VALUES (3, "2019-11-15", "2019-11-17", 1, 0);

INSERT INTO TypeNotification VALUES (1, "demandeEmprunt"), (2, "refusEmprunt"), (3, "validationEmprunt"),
    (4, "nouveauCommentaire"), (5, "nouveauFavori"), (6, "objetSupprime"), (7, "messageEnvoye"), (8, "avertissement");

INSERT INTO Favori (idFavori, idObjet, idMembre) VALUES (21, 3, 1);
INSERT INTO Favori (idFavori, idObjet, idMembre) VALUES (25, 2, 1);

INSERT INTO Reclamation (idEmprunteur, idPossesseur,idEmprunt, motif )
    VALUES (2,1,1, "retard");

-- Triggers pour les notifications

-- Demande d'emprunt
CREATE TRIGGER demande_emprunt AFTER INSERT
ON Emprunt FOR EACH ROW
INSERT INTO Notification(idMembreNotif, idMembreLien, idObjet, idTypeNotification, dateNotif) VALUES(
    (SELECT idPossesseur FROM Objet WHERE idObjet = NEW.idObjet), -- le propriétaire
    NEW.idEmprunteur,
    NEW.idObjet,
    1,
    NOW()
);

-- Validation emprunt
DELIMITER |
CREATE TRIGGER validation_emprunt AFTER UPDATE
ON Emprunt FOR EACH ROW
BEGIN
IF (NEW.validation <> OLD.validation) THEN

    -- Refus
    IF (NEW.validation = 2) THEN
        INSERT INTO Notification(idMembreNotif, idMembreLien, idObjet, idTypeNotification, dateNotif) VALUES(
            NEW.idEmprunteur,
            (SELECT idPossesseur FROM Objet WHERE idObjet = NEW.idObjet),
            NEW.idObjet,
            2,
            NOW());

    -- Validation
    ELSEIF (NEW.validation = 1) THEN
        INSERT INTO Notification(idMembreNotif, idMembreLien, idObjet, idTypeNotification, dateNotif) VALUES(
            NEW.idEmprunteur,
            (SELECT idPossesseur FROM Objet WHERE idObjet = NEW.idObjet),
            NEW.idObjet,
            3,
            NOW());
    END IF;

END IF;
END |
DELIMITER ;

DELIMITER |
CREATE TRIGGER ajout_commentaire AFTER UPDATE
ON Emprunt FOR EACH ROW
BEGIN
IF (NEW.commentaire <> OLD.commentaire) THEN
    INSERT INTO Notification(idMembreNotif, idMembreLien, idObjet, idTypeNotification, dateNotif) VALUES(
        (SELECT idPossesseur FROM Objet WHERE idObjet = NEW.idObjet),
        NEW.idEmprunteur,
        NEW.idObjet,
        4,
        NOW());
END IF;
END |
DELIMITER ;

CREATE TRIGGER ajout_favori AFTER INSERT
ON Favori FOR EACH ROW
INSERT INTO Notification(idMembreNotif, idMembreLien, idObjet, idTypeNotification, dateNotif) VALUES(
    (SELECT idPossesseur FROM Objet WHERE idObjet = NEW.idObjet),
    NEW.idMembre,
    NEW.idObjet,
    5,
    NOW());


DROP TRIGGER IF EXISTS suppression_objet;

CREATE TRIGGER suppression_objet BEFORE DELETE
ON Objet FOR EACH ROW
INSERT INTO Notification(idMembreNotif, idTypeNotification, dateNotif) VALUES(
    (SELECT idPossesseur FROM Objet WHERE idObjet = OLD.idObjet),
    6,
    NOW()
);

CREATE TRIGGER envoi_message AFTER INSERT
ON Message FOR EACH ROW
INSERT INTO NotificationMessage(idRecepteur, idEmetteur, idMessage, idTypeNotification, dateNotif) VALUES(
    (SELECT idRecepteur FROM Message WHERE idMessage = NEW.idMessage), (SELECT idEmetteur FROM Message WHERE idMessage = NEW.idMessage),
    NEW.idMessage,
    7,
    NOW()
);

DELIMITER |
CREATE TRIGGER bannissement AFTER INSERT
ON Notification FOR EACH ROW
BEGIN
IF (NEW.idTypeNotification = 8) THEN
  IF ((SELECT COUNT(*) FROM Notification WHERE idMembreNotif = NEW.idMembreNotif AND idTypeNotification = 8) >=3) THEN
    UPDATE Membre SET BANNI = TRUE WHERE idMembre = NEW.idMembreNotif;
  END IF;
END IF;
END |
DELIMITER ;

INSERT INTO Notification(idMembreNotif, idTypeNotification, idObjet, dateNotif) VALUES (3, 8, 2, NOW());
INSERT INTO Notification(idMembreNotif, idTypeNotification, idObjet, dateNotif) VALUES (3, 8, 2, NOW());
INSERT INTO Notification(idMembreNotif, idTypeNotification, idObjet, dateNotif) VALUES (3, 8, 2, NOW());
