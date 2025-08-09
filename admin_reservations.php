<?php
require 'auth.php';
require 'db.php';

// Suppression réservation
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: admin_reservations.php?msg=deleted");
    exit;
}

// Récupérer toutes les réservations
$reservations = $pdo->query("SELECT * FROM reservations ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>📅 Gestion des Réservations</h1>
        <div>
            <a href="dashboard_admin.php" class="btn btn-secondary">⬅️ Retour au Dashboard</a>
            <a href="logout_admin.php" class="btn btn-danger">Déconnexion</a>
        </div>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-success">Réservation supprimée avec succès.</div>
    <?php endif; ?>

    <table id="reservationsTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Personnes</th>
                <th>Créé le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['id']) ?></td>
                <td><?= htmlspecialchars($r['nom']) ?></td>
                <td><?= htmlspecialchars($r['email']) ?></td>
                <td><?= htmlspecialchars($r['telephone']) ?></td>
                <td><?= htmlspecialchars($r['date_reservation']) ?></td>
                <td><?= htmlspecialchars($r['heure']) ?></td>
                <td><?= htmlspecialchars($r['nb_personnes']) ?></td>
                <td><?= htmlspecialchars($r['created_at']) ?></td>
                <td>
                    <a href="?delete=<?= $r['id'] ?>" 
                       class="btn btn-sm btn-danger" 
                       onclick="return confirm('Supprimer cette réservation ?');">
                       🗑️
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#reservationsTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/fr-FR.json'
            }
        });
    });
</script>
</body>
</html>
