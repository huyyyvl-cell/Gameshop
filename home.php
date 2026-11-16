<?php
session_start();
if (!isset($_SESSION['gebruiker_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameShop Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table-container {
            margin-top: 20px;
            overflow-x: auto;
        }
    </style>

</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <h1>Welkom bij GameShop</h1>
        <p>
            Bij GameShop vind je een breed assortiment aan games, consoles, controllers en accessoires. 
            Onze missie is om gamers van alle leeftijden te voorzien van de beste producten en service. 
            Bezoek ons productoverzicht voor meer details!
        </p>
	<img src="GameShopLogo.png" alt="GameShop Logo">
    </div>
</body>
</html>
