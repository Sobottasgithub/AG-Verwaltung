INSERT INTO Klassen (Klasse) VALUES
("5a"), ("5b"), ("5c"),
("6a"), ("6b"), ("6c"),
("7a"), ("7b"), ("7c"),
("8a"), ("8b"), ("8c"),
("9a"), ("9b"), ("9c"),
("10a"), ("10b"), ("10c"),
("K11"), ("K12"), ("K13");

INSERT INTO Schueler (Vorname, Nachname, Email, Klasse) VALUES ("Patrick", "Schulze", "example@email.com", "K12");

INSERT INTO Lehrer (Kuerzel, Nachname, Vorname) VALUES
('CAKM','Cakmaz','Ferit'),
('HARJ','Harjes','Olaf'),
('STRI','Stricker','Ines'),
('BRUE','Brückler','Thomas'),
('MNDT','Mundt','Meline'),
('WEIS','Weis','Torsten'),
('GOEL','Göldner','Marcus'),
('LANG','Lange','Judith'),
('POEP','Poeplau','Nicola'),
('GROE','Grön','Judith'),
('MEYR','Meyer','Merlin'),
('SHUT','Schuh','Teresa'),
('SCHA','Schäfer','Alexander'),
('DENZ','Denz','Steffi'),
('GOTT','Ilka','Goltsche');

INSERT INTO Schulleitung (Kuerzel, Bezeichnung) VALUES ("CAKM", "Schulleiter");

INSERT INTO Ag (Name, Leitung, Raum, Wochentag, FindetStatt, Beschreibung, Uhrzeit) VALUES
("Fußball-AG", "GOEL", "TUH1", "Dienstag", false, "Ihr interessiert euch für Fußball? Ihr wollt Tore schießen und euch in kleinen Wettbewerben mit anderen messen? Ihr wollt neue Techniken erlernen oder euch weiter verbessern? Dann seid ihr bei uns genau richtig in der Fußball-AG.", "14:00"),
("Leichtathletik", "HARJ", "TUH2", "Freitag", false, "Schneller, höher, weiter? Hast Du Spaß am Sprinten, Werfen oder Springen? Dann komm in die Leichtathletik AG, wir werden die verschiedenen Disziplinen der Leichtathletik trainieren und auch neue ausprobieren.", "13:00"),
("Garten-AG", "POEP", "Garten", "Dienstag", false, " Die Garten Ag findet Montag und Mittwoch von 13 Uhr bis 14 Uhr in der Mittagspause im Schulgarten statt. Die Arbeitsgemeinschaft pflegt den Schulgarten und kümmert sich um den Teich. Wenn es sehr schlechtes Wetter ist, sind wir im Raum Biotop und basteln z.B. Insektenhotels und Ähnliches. Die Ag ist als offenes Angebot konzipiert. Das heißt man kann ohne Anmeldung an der AG als Pausenbeschäftigung teilnehmen. Es ist auch kein Problem erst in der Mensa zu Essen und dann zur Ag zu kommen.", "14:00"),
("Mint-Garage", "HARJ", "CH04", "Mittwoch", false, "Spaß am Tüfteln? Eigene Ideen für Projekte mit dem 3D-Drucker? In der MINT-Garage könnt ihr auch mit dem Arduino verschiedene Projekte verwirklichen oder an anderen elektronischen Apparaturen arbeiten. Auch Werkzeug und Material für Holzarbeiten stehen zur Verfügung.", "13:20");

INSERT INTO LehrerLogin (Kuerzel, PasswordHash) VALUES ("CAKM", "$argon2id$v=19$m=131072,t=4,p=2$Li9aanV2SmFBZHdaMTRDSQ$iLEyLYc7kUcvnwszXzECLWa+RTIsSjTYzrdsBGCOj4A");
INSERT INTO Admin (Name, PasswordHash) VALUES ("Admin", "$argon2id$v=19$m=131072,t=4,p=2$Li9aanV2SmFBZHdaMTRDSQ$iLEyLYc7kUcvnwszXzECLWa+RTIsSjTYzrdsBGCOj4A");
