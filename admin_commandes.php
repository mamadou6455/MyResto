<?php
require 'auth.php';
require 'db.php';

// Suppression commande
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM commandes WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: admin_commandes.php?msg=deleted");
    exit;
}

// Récupérer toutes les commandes
$commandes = $pdo->query("SELECT * FROM commandes ORDER BY created_at DESC")->fetchAll();

function afficherStatut($code) {
    switch ($code) {
        case 'en_attente': return '<span class="badge bg-warning text-dark">En attente</span>';
        case 'livre': return '<span class="badge bg-success">Livré</span>';
        case 'annule': return '<span class="badge bg-danger">Annulé</span>';
        default: return '<span class="badge bg-secondary">Inconnu</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <style>
        body {
            background-image: url('images/bg-restaurant.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        table td, table th {
            vertical-align: middle !important;
        }

        h1 {
            color: #c0392b;
        }

        .btn-danger {
            background-color: #e74c3c;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Gestion des Commandes</h1>
        <div>
            <a href="dashboard_admin.php" class="btn btn-secondary">⬅️ Retour au Dashboard</a>
            <a href="logout_admin.php" class="btn btn-danger">Déconnexion</a>
        </div>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-success">Commande supprimée avec succès.</div>
    <?php endif; ?>

    <table id="commandesTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Plat</th>
                <th>Quantité</th>
                <th>Instructions</th>
                <th>Statut</th>
                <th>Créé le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($commandes as $c): ?>
                <?php if (!is_array($c)) continue; ?>
                <tr>
                    <td><?= htmlspecialchars($c['id']) ?></td>
                    <td><?= isset($c['nom_client']) ? htmlspecialchars($c['nom_client']) : 'Inconnu' ?></td>
                    <td><?= isset($c['plat']) ? htmlspecialchars($c['plat']) : '' ?></td>
                    <td><?= isset($c['quantite']) ? htmlspecialchars($c['quantite']) : '' ?></td>
                    <td><?= isset($c['instructions']) ? htmlspecialchars($c['instructions']) : '' ?></td>
                    <td><?= isset($c['status']) ? afficherStatut($c['status']) : '' ?></td>
                    <td><?= isset($c['created_at']) ? htmlspecialchars($c['created_at']) : '' ?></td>
                    <td>
                        <a href="?delete=<?= $c['id'] ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Supprimer cette commande ?');">
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
        $('#commandesTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/fr-FR.json'
            }
        });
    });
</script>
</body>
</html>
