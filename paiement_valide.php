<?php
session_start();
require 'db.php';
require __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

if (!isset($_POST['commande_id'], $_POST['montant'])) {
    header('Location: menu.php');
    exit;
}

$commande_id = intval($_POST['commande_id']);
$montant = floatval($_POST['montant']);

// Exemple d'infos supplémentaires (à adapter selon ton application)
// Debug temporaire
// Retire ces lignes après vérification
$nom = $_SESSION['nom'] ?? 'Inconnu';
$telephone = $_SESSION['telephone'] ?? 'Non renseigné';
$date = date('d/m/Y H:i');

// Texte QR code multi-ligne
$qrData = "Commande : #$commande_id\nMontant : " . number_format($montant, 0, ',', ' ') . " FCFA\nDate : $date\nNom : $nom\nTéléphone : $telephone";

$qrCode = new QrCode($qrData);
$writer = new PngWriter();
$qrCodeResult = $writer->write($qrCode);
$qrCodeString = $qrCodeResult->getString();
$qrCodeBase64 = base64_encode($qrCodeString);

$title = "Paiement confirmé";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="alert alert-success text-center">
        <h2>🎉 Paiement effectué avec succès !</h2>
        <p>Votre paiement pour la commande <strong>#<?= $commande_id ?></strong> d'un montant de <strong><?= number_format($montant, 0, ',', ' ') ?> FCFA</strong> a bien été pris en compte.</p>
        <hr>
        <p>Scannez ce QR code pour conserver vos informations de paiement :</p>
        <img src="data:image/png;base64,<?= $qrCodeBase64 ?>" alt="QR Code Paiement" class="mb-3">
        <br>
        <pre class="bg-light p-2 d-inline-block text-start"><?= htmlspecialchars($qrData) ?></pre>
        <br>
        <a href="menu.php" class="btn btn-primary mt-3">↩ Retour au menu</a>
        <a href="mes_commandes.php" class="btn btn-outline-secondary mt-3">📜 Voir mes commandes</a>
    </div>
</div>
</body>
</html>