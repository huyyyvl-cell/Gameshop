<?php
session_start();
require 'config.php';

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['gebruiker_id'])) {
    header('Location: index.php');
    exit;
}

// Haal alle producten op voor de dropdown
$stmt = $pdo->query("SELECT product_id, naam FROM Producten");
$producten = $stmt->fetchAll();

// Verwerk productverwijdering
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Verwijder product uit de database
    $stmt = $pdo->prepare("DELETE FROM Producten WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $success = "Product succesvol verwijderd!";
    
    // Update lijst van producten na verwijdering
    $stmt = $pdo->query("SELECT product_id, naam FROM Producten");
    $producten = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Verwijderen</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
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
        <div class="form-container">
            <h2>Product Verwijderen</h2>
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="product_id" class="form-label">Selecteer een product</label>
                    <select class="form-select" id="product_id" name="product_id" required>
                        <option value="">-- Kies een product --</option>
                        <?php foreach ($producten as $product): ?>
                            <option value="<?= htmlspecialchars($product['product_id']) ?>">
                                <?= htmlspecialchars($product['naam']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-danger w-100">Verwijderen</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
