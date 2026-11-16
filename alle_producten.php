<?php
session_start();
require 'config.php';

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['gebruiker_id'])) {
    header('Location: index.php');
    exit;
}

// Haal alle producten uit de database
$stmt = $pdo->query("
    SELECT 
        p.product_id, 
        p.naam AS product_naam, 
        p.type, 
        p.prijs, 
        p.voorraad, 
        l.naam AS leverancier_naam 
    FROM Producten p
    LEFT JOIN Leveranciers l ON p.leverancier_id = l.leverancier_id
");
$producten = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alle Producten</title>
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
        <h1 class="mb-4">Alle Producten</h1>
        <div class="table-container">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Naam</th>
                        <th>Type</th>
                        <th>Prijs (€)</th>
                        <th>Voorraad</th>
                        <th>Leverancier</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($producten) > 0): ?>
                        <?php foreach ($producten as $product): ?>
                            <tr>
                                <td><?= htmlspecialchars($product['product_id']) ?></td>
                                <td><?= htmlspecialchars($product['product_naam']) ?></td>
                                <td><?= htmlspecialchars($product['type']) ?></td>
                                <td><?= number_format($product['prijs'], 2) ?></td>
                                <td><?= htmlspecialchars($product['voorraad']) ?></td>
                                <td><?= htmlspecialchars($product['leverancier_naam'] ?? 'Onbekend') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Geen producten gevonden.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
