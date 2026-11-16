<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];

    $stmt = $pdo->prepare("SELECT * FROM Gebruikers WHERE gebruikersnaam = ?");
    $stmt->execute([$gebruikersnaam]);
    $gebruiker = $stmt->fetch();

    if ($gebruiker && hash('sha256', $wachtwoord) == $gebruiker['wachtwoord_hash']) {
        $_SESSION['gebruiker_id'] = $gebruiker['gebruiker_id'];
        $_SESSION['rol'] = $gebruiker['rol'];
        header('Location: home.php');
        exit;
    } else {
        $error = "Ongeldige inloggegevens.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameShop Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand img {
            height: 40px;
        }
        .form-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="GameShopLogo.png" alt="GameShop Logo"> GameShop
            </a>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="form-container">
        <h2 class="text-center">Inloggen</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="gebruikersnaam" class="form-label">Gebruikersnaam</label>
                <input type="text" class="form-control" id="gebruikersnaam" name="gebruikersnaam" required>
            </div>
            <div class="mb-3">
                <label for="wachtwoord" class="form-label">Wachtwoord</label>
                <input type="password" class="form-control" id="wachtwoord" name="wachtwoord" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Inloggen</button>
        </form>
        <?php if (isset($error)) echo "<p class='text-danger text-center mt-3'>$error</p>"; ?>
    </div>
</body>
</html>
