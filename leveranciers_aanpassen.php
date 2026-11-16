<?php
session_start();
require 'config.php';

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['gebruiker_id'])) {
    header('Location: index.php');
    exit;
}

// Haal alle leveranciers op voor de dropdown
$stmt = $pdo->query("SELECT * FROM Leveranciers");
$leveranciers = $stmt->fetchAll();

// Verwerk formulierinzending
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['leverancier_id'])) {
    $leverancier_id = $_POST['leverancier_id'];
    $naam = $_POST['naam'];
    $adres = $_POST['adres'];
    $telefoon = $_POST['telefoon'];
    $email = $_POST['email'];

    // Valideer de invoer
    if (!empty($naam) && !empty($adres) && !empty($telefoon) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("
            UPDATE Leveranciers
            SET naam = ?, adres = ?, telefoon = ?, email = ?
            WHERE leverancier_id = ?
        ");
        $stmt->execute([$naam, $adres, $telefoon, $email, $leverancier_id]);
        $success = "Leverancier succesvol aangepast!";
    } else {
        $error = "Vul alle velden correct in.";
    }
}

// Haal de geselecteerde leverancier op als een ID is doorgegeven
$selected_leverancier = null;
if (isset($_GET['leverancier_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM Leveranciers WHERE leverancier_id = ?");
    $stmt->execute([$_GET['leverancier_id']]);
    $selected_leverancier = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leveranciers Aanpassen</title>
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
            <h2>Leverancier Aanpassen</h2>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Selecteer een leverancier -->
            <form method="GET" class="mb-4">
                <div class="mb-3">
                    <label for="leverancier_id" class="form-label">Kies een leverancier</label>
                    <select class="form-select" id="leverancier_id" name="leverancier_id" required>
                        <option value="">-- Selecteer een leverancier --</option>
                        <?php foreach ($leveranciers as $leverancier): ?>
                            <option value="<?= htmlspecialchars($leverancier['leverancier_id']) ?>" <?= isset($_GET['leverancier_id']) && $_GET['leverancier_id'] == $leverancier['leverancier_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($leverancier['naam']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Kies Leverancier</button>
            </form>

            <!-- Toon formulier om gegevens aan te passen -->
            <?php if ($selected_leverancier): ?>
                <form method="POST">
                    <input type="hidden" name="leverancier_id" value="<?= htmlspecialchars($selected_leverancier['leverancier_id']) ?>">
                    <div class="mb-3">
                        <label for="naam" class="form-label">Naam</label>
                        <input type="text" class="form-control" id="naam" name="naam" value="<?= htmlspecialchars($selected_leverancier['naam']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="adres" class="form-label">Adres</label>
                        <input type="text" class="form-control" id="adres" name="adres" value="<?= htmlspecialchars($selected_leverancier['adres']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefoon" class="form-label">Telefoon</label>
                        <input type="text" class="form-control" id="telefoon" name="telefoon" value="<?= htmlspecialchars($selected_leverancier['telefoon']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($selected_leverancier['email']) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Opslaan</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
