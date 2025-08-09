<?php
require __DIR__ . '/vendor/autoload.php';
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Récupère les infos de la réservation (par exemple via $_GET ou $_SESSION)
$nom = $_GET['nom'] ?? '';
$email = $_GET['email'] ?? '';
$telephone = $_GET['telephone'] ?? '';
$date = $_GET['date'] ?? '';
$heure = $_GET['heure'] ?? '';
$nb_personnes = $_GET['nb_personnes'] ?? '';

$qrData = "Nom: $nom\nEmail: $email\nTéléphone: $telephone\nDate: $date\nHeure: $heure\nPersonnes: $nb_personnes";
$qrCode = new QrCode($qrData);
$writer = new PngWriter();
$qrCodeResult = $writer->write($qrCode);
$qrCodeBase64 = base64_encode($qrCodeResult->getString());
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="alert alert-success text-center">
        <h2>🎉 Réservation confirmée !</h2>
        <p>Voici le QR code de votre réservation :</p>
        <img src="data:image/png;base64,<?= $qrCodeBase64 ?>" alt="QR Code Réservation" class="mb-3">
        <pre class="bg-light p-2 d-inline-block text-start"><?= htmlspecialchars($qrData) ?></pre>
        <a href="dashboard_user.php" class="btn btn-primary mt-3">↩ Tableau de bord</a>
    </div>
</div>
</body>
</html>