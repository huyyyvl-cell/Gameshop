# 🎮 GameShop Webapplicatie & Database

Een PHP- en MySQL-webapplicatie voor het beheren van producten en leveranciers binnen een gameshop[cite: 2, 3, 9].

## 📋 Overzicht van de bestanden

| Bestand | Beschrijving |
| :--- | :--- |
| `_LeesDitEerst.txt` | Algemene installatie-instructies (door Jan Moeskops, 25-11-2024)[cite: 2] |
| `start.sh` | Het automatische installatie- en opstartscript[cite: 2] |
| `installStap1-Database+DBuser.sql` | SQL-script voor het aanmaken van de database en databasegebruiker (`root`)[cite: 2, 8] |
| `installStap2-tabellen+gebruiker+data.sql` | SQL-script voor de tabellen, standaarddata en admin-gebruiker[cite: 2, 9] |
| `config.php` | Websiteconfiguratie en PDO-databaseverbinding[cite: 2, 4] |
| `index.php` | Start- en inlogpagina van de website[cite: 2, 7] |
| `home.php` | Welkomst- en infopagina na het inloggen[cite: 2, 6] |
| `navbar.php` | De navigatiebalk van de website[cite: 2] |
| `alle_producten.php` | Overzicht van alle producten en bijbehorende leveranciers[cite: 2, 3] |
| `product_toevoegen.php` | Pagina om nieuwe producten toe te voegen[cite: 2] |
| `product_aanpassen.php` | Pagina om bestaande producten te wijzigen[cite: 2] |
| `product_verwijderen.php` | Pagina om producten te verwijderen[cite: 2] |
| `leveranciers_tonen.php` | Overzicht van alle leveranciers[cite: 2] |
| `leveranciers_toevoegen.php` | Pagina om nieuwe leveranciers toe te voegen[cite: 2, 11] |
| `leveranciers_aanpassen.php` | Pagina om gegevens van leveranciers te wijzigen[cite: 2, 10] |
| `GameShopLogo.png` | Het logo van de GameShop |

---

## 🚀 Snelle Installatie (Linux / Bash)

Plaats alle bestanden in de map van je webserver (bijv. `/var/www/html`) en voer het installatiescript uit[cite: 2]:

```bash
bash start.sh
```

---

## 🛠️ Handmatige Database Setup (Copy & Paste)

Wil je de database handmatig opzetten in een MySQL/MariaDB-omgeving of database-engine (zoals phpMyAdmin, MySQL Workbench of DBeaver)? Kopieer en plak onderstaande scripts in volgorde[cite: 8, 9].

### Stap 1: Database en gebruiker aanmaken
Voer dit script uit als `root`-gebruiker om `GameShopDB` en de gebruiker `game` aan te maken[cite: 2, 8]:

```sql
-- Database en user aanmaken
CREATE DATABASE IF NOT EXISTS GameShopDB;
USE GameShopDB;

CREATE USER 'game'@'localhost' IDENTIFIED BY 'gamepw';
GRANT ALL PRIVILEGES ON GameShopDB.* TO 'game'@'localhost';
FLUSH PRIVILEGES;
```

### Stap 2: Tabellen, testdata en admin-account aanmaken
Voer dit script uit om de tabellen (`Leveranciers`, `Producten`, `Gebruikers`) en startdata in te laden[cite: 9]:

```sql
USE GameShopDB;

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
```

---

## ⚙️ Configuratie & Inloggegevens

### Databaseconfiguratie (`config.php`)
Standaard maakt de applicatie verbinding met de volgende gegevens[cite: 4]:

```php
<?php
$host = 'localhost'; // Databasehost
$db = 'GameShopDB'; // Databasenaam
$user = 'game'; // Databasegebruiker
$pass = 'gamepw'; // Wachtwoord (pas aan als nodig)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}
?>
```

### Standaard Admin-login
Na het uitvoeren van Stap 2 kun je inloggen op `index.php` met[cite: 7, 9]:
* **Gebruikersnaam:** `admin`[cite: 9]
* **Wachtwoord:** `admin123`[cite: 9]
