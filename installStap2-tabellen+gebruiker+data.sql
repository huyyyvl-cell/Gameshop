USE GameShopDB

-- Tabel voor leveranciers
CREATE TABLE Leveranciers (
    leverancier_id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    adres VARCHAR(255),
    telefoon VARCHAR(20),
    email VARCHAR(100),
    UNIQUE (email)
);

-- Tabel voor producten
CREATE TABLE Producten (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    type ENUM('game', 'console', 'controller', 'accessoire') NOT NULL,
    prijs DECIMAL(10, 2) NOT NULL,
    voorraad INT DEFAULT 0,
    leverancier_id INT,
    FOREIGN KEY (leverancier_id) REFERENCES Leveranciers(leverancier_id) ON DELETE SET NULL
);

-- Tabel voor gebruikersaccounts
CREATE TABLE Gebruikers (
    gebruiker_id INT AUTO_INCREMENT PRIMARY KEY,
    gebruikersnaam VARCHAR(50) NOT NULL UNIQUE,
    wachtwoord_hash VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'gebruiker') DEFAULT 'gebruiker'
);

-- Voeg enkele fictieve leveranciers toe
INSERT INTO Leveranciers (naam, adres, telefoon, email)
VALUES
    ('Game Distributeur Nederland', 'Amsterdam, NL', '020-1234567', 'info@gamedistributeur.nl'),
    ('Console Leveranciers BV', 'Rotterdam, NL', '010-7654321', 'sales@consoleleveranciers.nl');

-- Voeg enkele producten toe
INSERT INTO Producten (naam, type, prijs, voorraad, leverancier_id)
VALUES
    ('PlayStation 5', 'console', 499.99, 10, 2),
    ('Xbox Series X', 'console', 499.99, 5, 2),
    ('The Legend of Zelda: Tears of the Kingdom', 'game', 59.99, 20, 1),
    ('Nintendo Switch Controller', 'controller', 59.99, 15, 1);

-- Voeg een admin-gebruiker toe (wachtwoord: admin123)
INSERT INTO Gebruikers (gebruikersnaam, wachtwoord_hash, rol)
VALUES ('admin', SHA2('admin123', 256), 'admin');
