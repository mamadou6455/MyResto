<?php 
session_start();
require 'db.php';

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

// Redirection si le panier est vide
if (empty($_SESSION['panier'])) {
    header("Location: menu.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupération des plats dans le panier
$ids = array_keys($_SESSION['panier']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM menu WHERE id IN ($placeholders)");
$stmt->execute($ids);
$plats = $stmt->fetchAll();

// Calcul du total et préparation des détails
$total = 0;
$details = "";
foreach ($plats as $plat) {
    $qte = $_SESSION['panier'][$plat['id']];
    $sousTotal = $plat['prix'] * $qte;
    $total += $sousTotal;
    $details .= "{$plat['nom']} (x{$qte}) - " . number_format($sousTotal, 0, ',', ' ') . " FCFA\n";
}

// Insertion de la commande
$stmt = $pdo->prepare("INSERT INTO commandes (user_id, date_commande, total) VALUES (?, NOW(), ?)");
$stmt->execute([$user_id, $total]);
$commande_id = $pdo->lastInsertId();

// Insertion des détails de commande
$insertDetails = $pdo->prepare("INSERT INTO commande_details (commande_id, plat_id, quantite) VALUES (?, ?, ?)");
foreach ($plats as $plat) {
    $insertDetails->execute([$commande_id, $plat['id'], $_SESSION['panier'][$plat['id']]]);
}

// Vider le panier
$_SESSION['panier'] = [];

$title = "Commande Confirmée";
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
      <h2>✅ Merci pour votre commande !</h2>
      <p>Votre commande a bien été enregistrée sous le numéro <strong>#<?= $commande_id ?></strong>.</p>
      <p><strong>Total :</strong> <?= number_format($total, 0, ',', ' ') ?> FCFA</p>
      <a href="menu.php" class="btn btn-primary">↩ Retour au menu</a>
      <a href="mes_commandes.php" class="btn btn-outline-secondary">📜 Voir mes commandes</a>
      <form action="paiement.php" method="post" class="d-inline">
        <input type="hidden" name="commande_id" value="<?= $commande_id ?>">
        <input type="hidden" name="montant" value="<?= $total ?>">
        <button type="submit" class="btn btn-success">💳 Payer maintenant</button>
      </form>
    </div>
  </div>
</body>
</html>
