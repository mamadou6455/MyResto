<?php
require 'auth.php';
require 'db.php';

// Récupérer les stats
$resCount = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
$cmdCount = $pdo->query("SELECT COUNT(*) FROM commandes")->fetchColumn();
$msgCount = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();

// Dernières activités (3 derniers enregistrements toutes tables)
$lastActivities = [];
$resLast = $pdo->query("SELECT 'Réservation' as type, nom, created_at FROM reservations ORDER BY created_at DESC LIMIT 3")->fetchAll();
$cmdLast = $pdo->query("SELECT 'Commandes' as type, user_id as nom, created_at FROM commandes ORDER BY created_at DESC LIMIT 3")->fetchAll();
$msgLast = $pdo->query("SELECT 'Message' as type, nom, created_at FROM messages ORDER BY created_at DESC LIMIT 3")->fetchAll();
$lastActivities = array_merge($resLast, $cmdLast, $msgLast);
usort($lastActivities, function($a, $b) {
  return strtotime($b['created_at']) - strtotime($a['created_at']);
});
$lastActivities = array_slice($lastActivities, 0, 5);

// Utilisateurs connectés simulés (exemple : utilisateurs avec activité récente < 10min)
$connectedUsers = $pdo->query("SELECT id, email, last_login FROM users WHERE last_login >= NOW() - INTERVAL 10 MINUTE")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { background-color: #f8f9fa; }
    .card { cursor: pointer; transition: 0.3s ease; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 0 10px rgba(0,0,0,0.15); }
    a.card-link { text-decoration: none; color: inherit; }
    .sidebar {
      height: 100vh;
      background: #343a40;
      padding-top: 20px;
    }
    .sidebar a {
      color: #fff;
      display: block;
      padding: 10px 20px;
      text-decoration: none;
    }
    .sidebar a:hover {
      background-color: #495057;
    }
  </style>
  <script>
    // Rafraîchir automatiquement la page toutes les 60 secondes
    setInterval(function() {
      location.reload();
    }, 60000);
  </script>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <nav class="col-md-2 sidebar">
      <h4 class="text-white text-center">Administateur</h4>
      <a href="dashboard_admin.php"><i class="bi bi-house"></i> Accueil</a>
      <a href="admin_reservations.php"><i class="bi bi-calendar-check"></i> Réservations</a>
      <a href="admin_commandes.php"><i class="bi bi-receipt"></i> Commandes</a>
      <a href="admin_messages.php"><i class="bi bi-chat-dots"></i> Messages</a>
      <a href="admin_plats.php"><i class="bi bi-card-list"></i> Gérer les plats</a>
      <a href="logout_admin.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </nav>

    <!-- Contenu principal -->
    <main class="col-md-10 py-4">
      <div class="container">
        <h1 class="fw-bold mb-4">📊 Tableau de bord</h1>

        <div class="row g-4 mb-5">
          <div class="col-md-4">
            <a href="admin_reservations.php" class="card-link">
              <div class="card text-white bg-primary">
                <div class="card-body d-flex justify-content-between align-items-center">
                  <div>
                    <h5 class="card-title">Réservations</h5>
                    <p class="card-text fs-4">📅 <?= $resCount ?></p>
                  </div>
                  <i class="bi bi-calendar-check display-4"></i>
                </div>
              </div>
            </a>
          </div>

          <div class="col-md-4">
            <a href="admin_commandes.php" class="card-link">
              <div class="card text-white bg-success">
                <div class="card-body d-flex justify-content-between align-items-center">
                  <div>
                    <h5 class="card-title">Commandes</h5>
                    <p class="card-text fs-4">🍽️ <?= $cmdCount ?></p>
                  </div>
                  <i class="bi bi-receipt-cutoff display-4"></i>
                </div>
              </div>
            </a>
          </div>

          <div class="col-md-4">
            <a href="admin_messages.php" class="card-link">
              <div class="card text-white bg-info">
                <div class="card-body d-flex justify-content-between align-items-center">
                  <div>
                    <h5 class="card-title">Messages</h5>
                    <p class="card-text fs-4">💬 <?= $msgCount ?></p>
                  </div>
                  <i class="bi bi-chat-dots display-4"></i>
                </div>
              </div>
            </a>
          </div>
        </div>

        <div class="row g-4">
          <div class="col-md-8">
            <div class="card shadow">
              <div class="card-header bg-dark text-white">
                Statistiques générales
              </div>
              <div class="card-body">
                <canvas id="statsChart" height="100"></canvas>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card shadow mb-4">
              <div class="card-header bg-secondary text-white">
                Dernières activités 🕒
              </div>
              <ul class="list-group list-group-flush">
                <?php foreach ($lastActivities as $act): ?>
                  <li class="list-group-item small">
                    <strong><?= $act['type'] ?>:</strong> <?= htmlspecialchars($act['nom']) ?><br>
                    <span class="text-muted"><?= $act['created_at'] ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>

            <div class="card shadow">
              <div class="card-header bg-warning text-dark">
                Utilisateurs connectés 🟢
              </div>
              <ul class="list-group list-group-flush">
                <?php if (count($connectedUsers) > 0): ?>
                  <?php foreach ($connectedUsers as $user): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                      <div>
                        <?= htmlspecialchars($user['email']) ?><br>
                        <small class="text-muted">Dernière connexion : <?= $user['last_login'] ?></small>
                      </div>
                      <form method="post" action="logout_user.php" class="ms-2">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Déconnecter</button>
                      </form>
                    </li>
                  <?php endforeach; ?>
                <?php else: ?>
                  <li class="list-group-item text-muted">Aucun utilisateur connecté récemment</li>
                <?php endif; ?>
              </ul>
            </div>

          </div>
        </div>

      </div>
    </main>
  </div>
</div>

<script>
const ctx = document.getElementById('statsChart').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Réservations', 'Commandes', 'Messages'],
    datasets: [{
      label: 'Totaux',
      data: [<?= $resCount ?>, <?= $cmdCount ?>, <?= $msgCount ?>],
      backgroundColor: ['#0d6efd', '#198754', '#0dcaf0']
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      title: { display: true, text: 'Activité du site' }
    }
  }
});
</script>
</body>
</html>
