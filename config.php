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

