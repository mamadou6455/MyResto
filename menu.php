<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}
require 'db.php';

// Liste des catégories
$categories = $pdo->query("SELECT DISTINCT categorie FROM menu")->fetchAll(PDO::FETCH_COLUMN);

// Filtrage par catégorie
$categorieFiltre = $_GET['categorie'] ?? '';
if ($categorieFiltre && in_array($categorieFiltre, $categories)) {
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE categorie = ?");
    $stmt->execute([$categorieFiltre]);
    $plats = $stmt->fetchAll();
} else {
    $plats = $pdo->query("SELECT * FROM menu")->fetchAll();
}

// Initialisation panier
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Ajouter un plat au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_plat'], $_POST['quantite'])) {
    $id = (int)$_POST['id_plat'];
    $qte = max(1, (int)$_POST['quantite']);
    $_SESSION['panier'][$id] = ($_SESSION['panier'][$id] ?? 0) + $qte;
    header("Location: menu.php?added=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Menu - Notre Restaurant</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    .plat-img {
      height: 200px;
      object-fit: cover;
      border-radius: 12px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover .plat-img {
      transform: scale(1.05);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .fade-in {
      animation: fadeIn 0.6s ease forwards;
      opacity: 0;
      transform: translateY(10px);
    }

    @keyframes fadeIn {
      to { opacity: 1; transform: translateY(0); }
    }

    .btn-animated {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-animated:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .card {
      border: 1px solid #eee;
      border-radius: 15px;
      overflow: hidden;
      transition: box-shadow 0.3s ease;
      position: relative;
    }

    .card:hover {
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .cart-icon {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: orange;
      color: black;
      padding: 10px 16px;
      border-radius: 50px;
      font-size: 18px;
      z-index: 1000;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      transition: transform 0.2s ease;
    }
    .cart-icon:hover {
      transform: scale(1.1);
    }
    .cart-icon .badge {
      background-color: red;
      color: white;
      margin-left: 5px;
    }
  </style>
</head>
<body>

<!-- Cart Icon Fixed -->
<a href="panier.php" class="cart-icon">
  🛒 <span class="badge"> <?= array_sum($_SESSION['panier']) ?> </span>
</a>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img src="images/logo.jpg" alt="Logo" width="30" height="30" class="me-2 rounded-circle"> ← Accueil
    </a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="panier.php">
            🛒 Panier
            <?php if (!empty($_SESSION['panier'])): ?>
              <span class="badge bg-danger"><?= array_sum($_SESSION['panier']) ?></span>
            <?php endif; ?>
          </a>
        </li>
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
      </ul>
    </div>
  </div>
</nav>

<!-- Contenu -->
<div class="container py-4">
  <h1 class="mb-4">🍽️ Notre Menu</h1>

  <!-- Message de confirmation -->
  <?php if (isset($_GET['added'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
      ✅ Plat ajouté au panier !
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Filtres -->
  <form method="get" class="d-flex align-items-center gap-2 mb-3">
    <label for="categorie">Catégorie :</label>
    <select name="categorie" id="categorie" class="form-select w-auto" onchange="this.form.submit()">
      <option value="">Toutes</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= htmlspecialchars($cat) ?>" <?= $cat === $categorieFiltre ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <input type="search" id="searchInput" class="form-control ms-auto" placeholder="Recherche..." style="max-width: 250px;">
  </form>

  <!-- Liste des plats -->
  <div class="row" id="platsContainer">
    <?php if (!$plats): ?>
      <p>Aucun plat trouvé.</p>
    <?php else: ?>
      <?php foreach ($plats as $plat): 
        $img = 'images/' . ($plat['image'] ?? 'default_food.jpg');
        if (!file_exists($img)) $img = 'images/default_food.jpg';
      ?>
        <div class="col-sm-6 col-md-4 col-lg-3 mb-4 plat-card fade-in" data-nom="<?= strtolower(htmlspecialchars($plat['nom'])) ?>" data-desc="<?= strtolower(htmlspecialchars($plat['description'])) ?>">
          <div class="card h-100 shadow-sm">
            <img src="<?= $img ?>" class="card-img-top plat-img" alt="<?= htmlspecialchars($plat['nom']) ?>">
            <div class="position-absolute top-0 start-0 bg-warning text-dark px-2 py-1 small fw-bold rounded-end">
              <?= htmlspecialchars($plat['categorie']) ?>
            </div>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($plat['nom']) ?></h5>
              <p class="card-text flex-grow-1"><?= htmlspecialchars($plat['description']) ?></p>
              <p class="card-text fw-bold"><?= number_format($plat['prix'], 0, ',', ' ') ?> FCFA</p>
              <form method="post" class="d-flex gap-2 align-items-center mt-auto">
                <input type="hidden" name="id_plat" value="<?= (int)$plat['id'] ?>" />
                <input type="number" name="quantite" value="1" min="1" class="form-control form-control-sm w-25" />
                <button type="submit" class="btn btn-primary btn-sm btn-animated" style="background-color: green; color: with;">
                  <span class="icon-cart"></span> Ajouter
                </button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<script>
  // Filtrage en direct
  document.getElementById('searchInput').addEventListener('input', function () {
    const filter = this.value.toLowerCase();
    document.querySelectorAll('.plat-card').forEach(card => {
      const nom = card.dataset.nom, desc = card.dataset.desc;
      card.style.display = (nom.includes(filter) || desc.includes(filter)) ? '' : 'none';
    });
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
