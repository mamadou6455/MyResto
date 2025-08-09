<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupération des commandes et plats
$stmt = $pdo->prepare("
    SELECT c.id AS commande_id, c.date_commande, c.total,
           m.nom AS plat_nom, cd.quantite, m.prix, m.id AS plat_id
    FROM commandes c
    JOIN commande_details cd ON cd.commande_id = c.id
    JOIN menu m ON m.id = cd.plat_id
    WHERE c.user_id = ?
    ORDER BY c.date_commande DESC
");
$stmt->execute([$user_id]);

$commandes = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $row['commande_id'];
    if (!isset($commandes[$id])) {
        $commandes[$id] = [
            'date' => $row['date_commande'],
            'total' => $row['total'],
            'plats' => []
        ];
    }
    $commandes[$id]['plats'][] = [
        'id' => $row['plat_id'],
        'nom' => $row['plat_nom'],
        'quantite' => $row['quantite'],
        'prix' => $row['prix']
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="images/logo.jpg" alt="Logo" width="30" height="30" class="me-2">
        ← Accueil
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="dashboard_user.php"><i class="fas fa-home"></i> Tableau de bord</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login_user.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

<div class="container mt-5">
    <h2 class="mb-4">🧾 Historique de mes commandes</h2>

    <?php if (empty($commandes)): ?>
        <div class="alert alert-info">Aucune commande enregistrée.</div>
    <?php else: ?>
        <?php foreach ($commandes as $id => $commande): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-dark text-white">
                    Commande #<?= $id ?> — <?= date('d/m/Y H:i', strtotime($commande['date'])) ?>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3">
                        <?php foreach ($commande['plats'] as $plat): ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><?= htmlspecialchars($plat['nom']) ?> x <?= $plat['quantite'] ?></span>
                                <span><?= number_format($plat['prix'] * $plat['quantite'], 0, ',', ' ') ?> FCFA</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="text-end fw-bold mb-3">
                        Total : <?= number_format($commande['total'], 0, ',', ' ') ?> FCFA
                    </div>

                    <div class="d-flex justify-content-between">
  

                        <form method="post" action="repasser_commande.php">
                            <?php foreach ($commande['plats'] as $plat): ?>
                                <input type="hidden" name="plats[<?= $plat['id'] ?>]" value="<?= $plat['quantite'] ?>">
                            <?php endforeach; ?>
                            <button type="submit" class="btn btn-success">
                                🔁 Repasser cette commande
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="menu.php" class="btn btn-secondary">← Retour au menu</a>
    </div>
</div>
</body>
</html>
