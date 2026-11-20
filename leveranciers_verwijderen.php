<?php
session_start();
require 'config.php';

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['gebruiker_id'])) {
    header('Location: index.php');
    exit;
}

// Verwerk formulierinzending om een leverancier te verwijderen
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['leverancier_id'])) {
    $leverancier_id = $_POST['leverancier_id'];

    if (!empty($leverancier_id)) {
        $stmt = $pdo->prepare("DELETE FROM Leveranciers WHERE leverancier_id = ?");
        $stmt->execute([$leverancier_id]);
        $success = "Leverancier succesvol verwijderd!";
    } else {
        $error = "Selecteer een geldige leverancier.";
    }
}

// Haal alle leveranciers op voor de dropdown (na het verwijderen, zodat de lijst direct up-to-date is)
$stmt = $pdo->query("SELECT * FROM Leveranciers");
$leveranciers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leverancier Verwijderen</title>
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
            <h2>Leverancier Verwijderen</h2>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" onsubmit="return confirm('Weet je zeker dat je deze leverancier wilt verwijderen?');">
                <div class="mb-3">
                    <label for="leverancier_id" class="form-label">Kies een leverancier</label>
                    <select class="form-select" id="leverancier_id" name="leverancier_id" required>
                        <option value="">-- Selecteer een leverancier --</option>
                        <?php foreach ($leveranciers as $leverancier): ?>
                            <option value="<?= htmlspecialchars($leverancier['leverancier_id']) ?>">
                                <?= htmlspecialchars($leverancier['naam']) ?> (<?= htmlspecialchars($leverancier['email']) ?>)
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
