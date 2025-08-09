<?php
session_start();
require 'db.php';

if (!isset($_POST['commande_id'], $_POST['montant'])) {
    header('Location: menu.php');
    exit;
}

$commande_id = intval($_POST['commande_id']);
$montant = floatval($_POST['montant']);

// Ici tu pourrais intégrer un vrai module de paiement (API, etc.)
// Pour la démo, on affiche juste la confirmation

$title = "Paiement de la commande";
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
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body text-center">
            <h3 class="card-title mb-4">Paiement de la commande #<?= $commande_id ?></h3>
            <p class="card-text mb-3"><strong>Montant à payer :</strong> <?= number_format($montant, 0, ',', ' ') ?> FCFA</p>
            <form method="post" action="paiement_valide.php">
                <input type="hidden" name="commande_id" value="<?= $commande_id ?>">
                <input type="hidden" name="montant" value="<?= $montant ?>">
                <button type="submit" class="btn btn-success w-100">Valider le paiement</button>
            </form>
            <a href="menu.php" class="btn btn-link mt-3">Annuler et retourner au menu</a>
        </div>
    </div>
</div>
</body>
</html>