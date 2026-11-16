<?php
session_start();
require 'config.php';

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['gebruiker_id'])) {
    header('Location: index.php');
    exit;
}

// Haal alle leveranciers op uit de database
$stmt = $pdo->query("SELECT * FROM Leveranciers");
$leveranciers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leveranciers Tonen</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="table-container">
            <h2 class="mb-4">Leveranciers</h2>
            <?php if (empty($leveranciers)): ?>
                <div class="alert alert-warning">Er zijn momenteel geen leveranciers beschikbaar.</div>
            <?php else: ?>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Naam</th>
                            <th>Adres</th>
                            <th>Telefoon</th>
                            <th>E-mail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leveranciers as $leverancier): ?>
                            <tr>
                                <td><?= htmlspecialchars($leverancier['leverancier_id']) ?></td>
                                <td><?= htmlspecialchars($leverancier['naam']) ?></td>
                                <td><?= htmlspecialchars($leverancier['adres']) ?></td>
                                <td><?= htmlspecialchars($leverancier['telefoon']) ?></td>
                                <td><?= htmlspecialchars($leverancier['email']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
