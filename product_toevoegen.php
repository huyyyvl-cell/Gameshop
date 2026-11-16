<?php
session_start();
require 'config.php';

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['gebruiker_id'])) {
    header('Location: index.php');
    exit;
}

// Haal leveranciers op uit de database voor de dropdown
$stmt = $pdo->query("SELECT leverancier_id, naam FROM Leveranciers");
$leveranciers = $stmt->fetchAll();

// Verwerk formulierinzending
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $naam = $_POST['naam'];
    $type = $_POST['type'];
    $prijs = $_POST['prijs'];
    $voorraad = $_POST['voorraad'];
    $leverancier_id = $_POST['leverancier_id'];

    if (!empty($naam) && in_array($type, ['game', 'console', 'controller', 'accessoire']) && is_numeric($prijs) && is_numeric($voorraad)) {
        $stmt = $pdo->prepare("
            INSERT INTO Producten (naam, type, prijs, voorraad, leverancier_id) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$naam, $type, $prijs, $voorraad, $leverancier_id]);
        $success = "Product succesvol toegevoegd!";
    } else {
        $error = "Vul alle velden correct in.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Toevoegen</title>
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
            <h2>Nieuw Product Toevoegen</h2>
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="naam" class="form-label">Productnaam</label>
                    <input type="text" class="form-control" id="naam" name="naam" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="">Selecteer een type</option>
                        <option value="game">Game</option>
                        <option value="console">Console</option>
                        <option value="controller">Controller</option>
                        <option value="accessoire">Accessoire</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="prijs" class="form-label">Prijs (€)</label>
                    <input type="number" step="0.01" class="form-control" id="prijs" name="prijs" required>
                </div>
                <div class="mb-3">
                    <label for="voorraad" class="form-label">Voorraad</label>
                    <input type="number" class="form-control" id="voorraad" name="voorraad" required>
                </div>
                <div class="mb-3">
                    <label for="leverancier_id" class="form-label">Leverancier</label>
                    <select class="form-select" id="leverancier_id" name="leverancier_id" required>
                        <option value="">Selecteer een leverancier</option>
                        <?php foreach ($leveranciers as $leverancier): ?>
                            <option value="<?= htmlspecialchars($leverancier['leverancier_id']) ?>">
                                <?= htmlspecialchars($leverancier['naam']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Toevoegen</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
