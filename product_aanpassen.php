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

// Haal leveranciers op voor de dropdown
$stmt = $pdo->query("SELECT leverancier_id, naam FROM Leveranciers");
$leveranciers = $stmt->fetchAll();

// Haal productinformatie op als een product is geselecteerd
$product = null;
if (isset($_GET['product_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM Producten WHERE product_id = ?");
    $stmt->execute([$_GET['product_id']]);
    $product = $stmt->fetch();
}

// Verwerk formulierinzending
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $naam = $_POST['naam'];
    $type = $_POST['type'];
    $prijs = $_POST['prijs'];
    $voorraad = $_POST['voorraad'];
    $leverancier_id = $_POST['leverancier_id'];

    if (!empty($naam) && in_array($type, ['game', 'console', 'controller', 'accessoire']) && is_numeric($prijs) && is_numeric($voorraad)) {
        $stmt = $pdo->prepare("
            UPDATE Producten 
            SET naam = ?, type = ?, prijs = ?, voorraad = ?, leverancier_id = ?
            WHERE product_id = ?
        ");
        $stmt->execute([$naam, $type, $prijs, $voorraad, $leverancier_id, $product_id]);
        $success = "Product succesvol bijgewerkt!";
    } else {
        $error = "Vul alle velden correct in.";
    }

    // Haal het bijgewerkte product opnieuw op
    $stmt = $pdo->prepare("SELECT * FROM Producten WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Aanpassen</title>
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
            <h2>Product Aanpassen</h2>
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="GET">
                <div class="mb-3">
                    <label for="product_id" class="form-label">Selecteer een product</label>
                    <select class="form-select" id="product_id" name="product_id" onchange="this.form.submit()">
                        <option value="">-- Kies een product --</option>
                        <?php foreach ($producten as $item): ?>
                            <option value="<?= htmlspecialchars($item['product_id']) ?>" <?= isset($_GET['product_id']) && $_GET['product_id'] == $item['product_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($item['naam']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <?php if ($product): ?>
                <form method="POST">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">
                    <div class="mb-3">
                        <label for="naam" class="form-label">Productnaam</label>
                        <input type="text" class="form-control" id="naam" name="naam" value="<?= htmlspecialchars($product['naam']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="game" <?= $product['type'] == 'game' ? 'selected' : '' ?>>Game</option>
                            <option value="console" <?= $product['type'] == 'console' ? 'selected' : '' ?>>Console</option>
                            <option value="controller" <?= $product['type'] == 'controller' ? 'selected' : '' ?>>Controller</option>
                            <option value="accessoire" <?= $product['type'] == 'accessoire' ? 'selected' : '' ?>>Accessoire</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="prijs" class="form-label">Prijs (€)</label>
                        <input type="number" step="0.01" class="form-control" id="prijs" name="prijs" value="<?= htmlspecialchars($product['prijs']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="voorraad" class="form-label">Voorraad</label>
                        <input type="number" class="form-control" id="voorraad" name="voorraad" value="<?= htmlspecialchars($product['voorraad']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="leverancier_id" class="form-label">Leverancier</label>
                        <select class="form-select" id="leverancier_id" name="leverancier_id" required>
                            <?php foreach ($leveranciers as $leverancier): ?>
                                <option value="<?= htmlspecialchars($leverancier['leverancier_id']) ?>" <?= $product['leverancier_id'] == $leverancier['leverancier_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($leverancier['naam']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Opslaan</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
