CREATE DATABASE AG_Verwaltung;
USE AG_Verwaltung;

CREATE TABLE Klassen (
  Klasse varchar(3) PRIMARY KEY  
);

CREATE TABLE Schueler (
    SID int AUTO_INCREMENT PRIMARY KEY,
    Vorname varchar(255) NOT NULL,
    Nachname varchar(255) NOT NULL,
    Email varchar(255),
    Klasse varchar(3),
    FOREIGN KEY (Klasse) REFERENCES Klassen(Klasse)
); 

CREATE TABLE Lehrer (
    Kuerzel varchar(4) PRIMARY KEY,
    Vorname varchar(255) NOT NULL,
    Nachname varchar(255) NOT NULL
);

CREATE TABLE Schulleitung (
    Kuerzel varchar(4) PRIMARY KEY,
    Bezeichnung varchar(255) NOT NULL,
    FOREIGN KEY (Kuerzel) REFERENCES Lehrer(Kuerzel)
);

CREATE TABLE Ag (
    Name varchar(255) PRIMARY KEY,
    Leitung varchar(4),
    FOREIGN KEY (Leitung) REFERENCES Lehrer(Kuerzel),
    Raum varchar(6),
    Wochentag varchar(12),
    FindetStatt boolean,
    Beschreibung varchar(1024)
);

CREATE TABLE Teilnahme (
    TID int AUTO_INCREMENT PRIMARY KEY,
    AgName varchar(255),
    FOREIGN KEY (AgName) REFERENCES Ag(Name),
    SID int,
    FOREIGN KEY (SID) REFERENCES Schueler(SID),
    Genehmigt boolean DEFAULT false
);

CREATE TABLE LehrerLogin (
    Kuerzel varchar(4) PRIMARY KEY,
    FOREIGN KEY (Kuerzel) REFERENCES Lehrer(Kuerzel),
    PasswordHash varchar(255) NOT NULL
);

CREATE TABLE Admin (
    Name varchar(255) PRIMARY KEY,
    PasswordHash varchar(255) NOT NULL
);

CREATE USER defaultUser@localhost IDENTIFIED BY 'USER PASSWORD';
GRANT SELECT ON Ag TO defaultUser@localhost;
GRANT SELECT ON Lehrer TO defaultUser@localhost;
GRANT SELECT ON Klassen TO defaultUser@localhost;
GRANT SELECT, INSERT ON Schueler TO defaultUser@localhost;
GRANT SELECT, INSERT ON Teilnahme TO defaultUser@localhost;

CREATE USER lehrer@localhost IDENTIFIED BY 'LEHRER PASSWORD';
GRANT SELECT ON Ag TO lehrer@localhost;
GRANT SELECT ON Teilnahme TO lehrer@localhost;
GRANT SELECT ON Schueler TO lehrer@localhost;
GRANT UPDATE (Genehmigt) ON Teilnahme TO lehrer@localhost;
GRANT SELECT ON LehrerLogin TO lehrer@localhost;
GRANT SELECT ON Lehrer TO lehrer@localhost;

CREATE USER schulleitung@localhost IDENTIFIED BY 'SCHULLEITUNG PASSWORD';
GRANT SELECT ON Ag TO schulleitung@localhost;
GRANT UPDATE (FindetStatt) ON Ag TO schulleitung@localhost;
GRANT SELECT ON LehrerLogin TO schulleitung@localhost;
GRANT SELECT ON Schulleitung TO schulleitung@localhost;
GRANT SELECT ON Teilnahme TO schulleitung@localhost;
GRANT SELECT ON Lehrer TO schulleitung@localhost;

