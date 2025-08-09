<?php
require 'auth.php';
require 'db.php';

// Gérer l'upload d'image
$imagePath = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir);
    $filename = basename($_FILES['image_file']['name']);
    $targetFile = $uploadDir . time() . '_' . $filename;
    if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetFile)) {
        $imagePath = $targetFile;
    }
}

// Ajouter ou modifier un plat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'], $_POST['prix'], $_POST['categorie'], $_POST['description'])) {
    $nom = trim($_POST['nom']);
    $prix = floatval($_POST['prix']);
    $categorie = trim($_POST['categorie']);
    $description = trim($_POST['description']);
    $image = $imagePath ?: ($_POST['image'] ?? '');

    if (isset($_POST['id']) && $_POST['id'] !== '') {
        // Modifier un plat existant
        $id = intval($_POST['id']);
        $pdo->prepare("UPDATE menu SET nom = ?, prix = ?, categorie = ?, description = ?, image = ? WHERE id = ?")
            ->execute([$nom, $prix, $categorie, $description, $image, $id]);
    } else {
        // Ajouter un nouveau plat
        $pdo->prepare("INSERT INTO menu (nom, prix, categorie, description, image) VALUES (?, ?, ?, ?, ?)")
            ->execute([$nom, $prix, $categorie, $description, $image]);
    }
    header("Location: admin_plats.php");
    exit;
}

// Supprimer un plat
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo->prepare("DELETE FROM menu WHERE id = ?")->execute([$id]);
    header("Location: admin_plats.php");
    exit;
}

// Récupérer les plats
$plats = $pdo->query("SELECT * FROM menu ORDER BY id DESC")->fetchAll();

// Préremplir le formulaire en cas de modification
$editPlat = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE id = ?");
    $stmt->execute([$id]);
    $editPlat = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des Plats</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-4">Gestion des plats</h1>
        <div>
            <a href="dashboard_admin.php" class="btn btn-secondary">⬅️ Retour au Dashboard</a>
            <a href="logout_admin.php" class="btn btn-danger">Déconnexion</a>
        </div>
    </div>

  <form method="POST" enctype="multipart/form-data" class="row g-3 mb-4">
    <input type="hidden" name="id" value="<?= $editPlat['id'] ?? '' ?>">
    <div class="col-md-3">
      <input type="text" name="nom" class="form-control" placeholder="Nom du plat" value="<?= $editPlat['nom'] ?? '' ?>" required>
    </div>
    <div class="col-md-2">
      <input type="number" name="prix" step="0.01" class="form-control" placeholder="Prix (FCFA)" value="<?= $editPlat['prix'] ?? '' ?>" required>
    </div>
    <div class="col-md-2">
      <input type="text" name="categorie" class="form-control" placeholder="Catégorie" value="<?= $editPlat['categorie'] ?? '' ?>" required>
    </div>
    <div class="col-md-3">
      <input type="text" name="description" class="form-control" placeholder="Description" value="<?= $editPlat['description'] ?? '' ?>">
    </div>
    <div class="col-md-2">
      <input type="text" name="image" class="form-control" placeholder="URL de l'image" value="<?= $editPlat['image'] ?? '' ?>">
      <input type="file" name="image_file" class="form-control mt-1">
    </div>
    <div class="col-md-12 text-end">
      <button type="submit" class="btn btn-<?= $editPlat ? 'warning' : 'primary' ?>">
        <?= $editPlat ? 'Modifier' : 'Ajouter' ?>
      </button>
    </div>
  </form>

  <table class="table table-bordered table-striped bg-white">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prix</th>
        <th>Catégorie</th>
        <th>Description</th>
        <th>Image</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($plats as $plat): ?>
      <tr>
        <td><?= $plat['id'] ?></td>
        <td><?= htmlspecialchars($plat['nom']) ?></td>
        <td><?= number_format($plat['prix'], 0, ',', ' ') ?> FCFA</td>
        <td><?= htmlspecialchars($plat['categorie']) ?></td>
        <td><?= htmlspecialchars($plat['description'] ?? '') ?></td>
        <td><?php if ($plat['image']): ?><img src="<?= htmlspecialchars($plat['image']) ?>" alt="Image" width="60"><?php endif; ?></td>
        <td>
          <a href="?edit=<?= $plat['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
          <a href="?delete=<?= $plat['id'] ?>" onclick="return confirm('Supprimer ce plat ?')" class="btn btn-sm btn-danger">Supprimer</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
