DROP TABLE IF EXISTS Users;

CREATE TABLE Users (
  Id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  LastAuthId VARCHAR(255) NOT NULL,
  Login VARCHAR(255) NOT NULL,
  Password VARCHAR(255) NOT NULL,
  Role TINYINT(4) NOT NULL,
  DateCreated DATETIME NOT NULL,
  LastLogin DATETIME,
  ApiKey VARCHAR(32),
  Active TINYINT(1) NOT NULL,
  PRIMARY KEY (Id)
);

INSERT INTO Users (Login, Password, Active, Role, DateCreated )
  VALUES
  ( 'terrybd@gmail.com', PASSWORD('m@nageM3nt'), 1, 15, NOW()),
  ( 'admin@sneakyfoxsoftware.com', PASSWORD('1234'), 1, 15, NOW());

DROP TABLE IF EXISTS UserSettings;

CREATE TABLE UserSettings (
  Id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  UserId INT UNSIGNED NOT NULL,
  Settings TEXT,
  PRIMARY KEY (Id)
);

DROP TABLE IF EXISTS Roles;

CREATE TABLE Roles (
  Id TINYINT(1) NOT NULL,
  Description VARCHAR(255),
  PRIMARY KEY (Id)
);

INSERT INTO Roles (Id, Description)
VALUES
  (0, 'Site User'),
  (1, 'Administrator'),
  (2, 'View User'),
  (4, 'Read Only'),
  (8, 'Editor');

DROP TABLE IF EXISTS UserSettings;

CREATE TABLE UserSettings (
  UserId INT UNSIGNED NOT NULL,
  Settings TEXT,
  PRIMARY KEY (UserId)
);

CREATE TABLE UserVerification (
  UserId INT UNSIGNED NOT NULL,
  Verification VARCHAR(255) NOT NULL,
  IsVerified TINYINT(1) NOT NULL,
  PRIMARY KEY (UserId)
);
