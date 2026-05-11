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

INSERT INTO Ag (Name, Leitung, Raum, Wochentag, FindetStatt, Beschreibung) VALUES
("Informatik", "CAKM", "OG112", "Dienstag", false, "Hier lernt ihr viel über Informatik!"),
("Sport", "POEP", "TUH2", "Mittwoch", false, "Bewegt euch!");

INSERT INTO LehrerLogin (Kuerzel, PasswordHash) VALUES ("CAKM", "$argon2id$v=19$m=2048,t=4,p=3$NUhZWWRkb0tmcUFvQ1J3dA$usK3kOHk0sN1ZbA0V2ZbdK60a/EhL9jBmQLxR43gLU8");
