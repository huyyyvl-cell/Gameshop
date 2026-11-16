-- Database en user sport aanmaken
CREATE DATABASE IF NOT EXISTS GameShopDB;
USE GameShopDB;

CREATE USER 'game'@'localhost' IDENTIFIED BY 'gamepw';
GRANT ALL PRIVILEGES ON GameShopDB.* TO 'game'@'localhost';
QUIT
