<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tableau de bord</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .card {
      transition: transform 0.4s ease, box-shadow 0.4s ease;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }
    .card i {
      transition: transform 0.3s ease, color 0.3s ease;
    }
    .card:hover i {
      transform: scale(1.3);
    }
    .card .fa-utensils { color: #007bff; }
    .card .fa-calendar-check { color: #28a745; }
    .card .fa-pen-to-square { color: #fd7e14; }
    .card .fa-history { color: #ffc107; }
    .card .fa-cart-plus { color: #dc3545; }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="images/logo.jpg" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2" style="border-radius: 100px;">
        ← Accueil
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="login_user.php">
              <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <?php if (isset($_GET['modification']) && $_GET['modification'] === 'success'): ?>
        <div class="alert alert-success text-center">Votre réservation a été modifiée avec succès.</div>
    <?php endif; ?>

    <div class="text-center">
      <h1 class="mb-4">Bienvenue, <?= htmlspecialchars($_SESSION['full_name'] ?? 'Client') ?> !</h1>
      <p class="lead">Vous êtes connecté à votre espace client.</p>
    </div>

    <div class="row justify-content-center mt-4 g-4">
      <!-- Consulter le menu -->
      <div class="col-md-3">
        <div class="card text-center shadow-sm border-primary">
          <div class="card-body">
            <i class="fas fa-utensils fa-3x mb-3"></i>
            <h5 class="card-title">Consulter le menu</h5>
            <p class="card-text">Parcourez notre sélection de plats et spécialités.</p>
            <a href="menu.php" class="btn btn-primary">Voir le menu</a>
          </div>
        </div>
      </div>

      <!-- Faire une réservation -->
      <div class="col-md-3">
        <div class="card text-center shadow-sm border-success">
          <div class="card-body">
            <i class="fas fa-calendar-check fa-3x mb-3"></i>
            <h5 class="card-title">Faire une réservation</h5>
            <p class="card-text">Réservez votre table directement en ligne.</p>
            <a href="reservation.php" class="btn btn-success">Réserver</a>
          </div>
        </div>
      </div>

      <!-- Mes commandes -->
      <div class="col-md-3">
        <div class="card text-center shadow-sm border-info">
          <div class="card-body">
            <i class="fas fa-history fa-3x mb-3"></i>
            <h5 class="card-title">Mes commandes</h5>
            <p class="card-text">Consultez l’historique de vos commandes.</p>
            <a href="mes_commandes.php" class="btn btn-info text-white">Voir mes commandes</a>
          </div>
        </div>
      </div>

      <!-- Nouvelle carte : Passer une commande -->
      <div class="col-md-3">
        <div class="card text-center shadow-sm border-danger">
          <div class="card-body">
            <i class="fas fa-cart-plus fa-3x mb-3"></i>
            <h5 class="card-title">Passer une commande</h5>
            <p class="card-text">Commandez vos plats préférés en ligne.</p>
            <a href="commande.php" class="btn btn-danger text-white">Commander</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>