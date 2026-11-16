#!/bin/bash

echo "=========================================="
echo "   Installatie GameShopDB starten...      "
echo "=========================================="

# Stap 1: Database en gebruiker aanmaken als root
echo "[1/3] Database 'GameShopDB' en gebruiker 'game' aanmaken (voer MySQL root-wachtwoord in)..."
sudo mysql -u root -p < installStap1-Database+DBuser.sql

# Stap 2: Tabellen, testdata en admin-account inladen als gebruiker 'game'
echo "[2/3] Tabellen en startdata importeren in GameShopDB..."
mysql -u game -pgamepw GameShopDB < installStap2-tabellen+gebruiker+data.sql

# Stap 3: Webbestanden klaarzetten (optioneel, indien nog niet in /var/www/html)
echo "[3/3] Bestanden kopiëren naar /var/www/html/GameShop..."
sudo mkdir -p /var/www/html/GameShop
sudo cp *.php GameShopLogo.png /var/www/html/GameShop/

echo "=========================================="
echo "   Installatie voltooid!                  "
echo "   Open http://localhost/GameShop         "
echo "=========================================="
