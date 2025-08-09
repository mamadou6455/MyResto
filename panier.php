<?php
session_start();
require 'db.php';

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$platsPanier = [];
$total = 0;

// Récupérer les plats du panier
if ($_SESSION['panier']) {
    $ids = array_keys($_SESSION['panier']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $platsPanier = $stmt->fetchAll();

    foreach ($platsPanier as $plat) {
        $total += $plat['prix'] * $_SESSION['panier'][$plat['id']];
    }
}

// Actions : modifier / supprimer / vider
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($_GET['action'] === 'supprimer') {
        unset($_SESSION['panier'][$id]);
    } elseif ($_GET['action'] === 'modifier' && isset($_GET['qte'])) {
        $qte = max(1, (int)$_GET['qte']);
        $_SESSION['panier'][$id] = $qte;
    }
    header("Location: panier.php?updated=1");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'vider') {
    $_SESSION['panier'] = [];
    header("Location: panier.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon Panier</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">
  <h1 class="mb-4">🛒 Mon Panier</h1>

  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-info alert-dismissible fade show">
      ✅ Panier mis à jour.
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <?php if (empty($_SESSION['panier'])): ?>
    <p class="alert alert-warning">Votre panier est vide. <a href="menu.php" class="btn btn-primary ms-2">← Retour au menu</a></p>
  <?php else: ?>
    <table class="table table-bordered align-middle">
      <thead>
        <tr>
          <th>Plat</th>
          <th>Quantité</th>
          <th>Prix</th>
          <th>Sous-total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($platsPanier as $p): 
          $qte = $_SESSION['panier'][$p['id']];
          $subtotal = $p['prix'] * $qte;
        ?>
        <tr>
          <td><?= htmlspecialchars($p['nom']) ?></td>
          <td>
            <form method="get" class="d-flex gap-2">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <input type="hidden" name="action" value="modifier">
              <input type="number" name="qte" value="<?= $qte ?>" min="1" class="form-control form-control-sm" />
              <button type="submit" class="btn btn-sm btn-outline-primary">Modifier</button>
            </form>
          </td>
          <td><?= number_format($p['prix'], 0, ',', ' ') ?> FCFA</td>
          <td><?= number_format($subtotal, 0, ',', ' ') ?> FCFA</td>
          <td>
            <a href="?action=supprimer&id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce plat ?');">Supprimer</a>
          </td>
        </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="3" class="text-end fw-bold">Total :</td>
          <td colspan="2" class="fw-bold"><?= number_format($total, 0, ',', ' ') ?> FCFA</td>
        </tr>
      </tbody>
    </table>

    <div class="d-flex justify-content-between">
      <a href="menu.php" class="btn btn-secondary">← Continuer les achats</a>
      <div>
        <a href="?action=vider" class="btn btn-outline-danger" onclick="return confirm('Vider tout le panier ?');">Vider le panier</a>
        <a href="commande.php" class="btn btn-success">✅ Valider la commande</a>
      </div>
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
