<?php
require 'db.php';
session_start();

// Sécurité admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Répondre à un message
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['repondre'])) {
    $message_id = $_POST['message_id'];
    $reponse = $_POST['reponse'];

    $stmt = $pdo->prepare("UPDATE messages SET reponse = ? WHERE id = ?");
    $stmt->execute([$reponse, $message_id]);
}

// Récupérer les messages
$messages = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messages Clients - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
     <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>📩 Messages des utilisateurs</h1>
        <div>
            <a href="dashboard_admin.php" class="btn btn-secondary">⬅️ Retour au Dashboard</a>
            <a href="logout_admin.php" class="btn btn-danger">Déconnexion</a>
        </div>
    </div>

    <div class="container">

        <?php foreach ($messages as $msg): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($msg['nom']) ?> (<?= htmlspecialchars($msg['email']) ?>)</h5>
                    <p class="card-text"><strong>Message :</strong> <?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                    <p class="text-muted">Envoyé le : <?= $msg['created_at'] ?></p>

                    <?php if ($msg['reponse']): ?>
                        <div class="alert alert-success">
                            <strong>Réponse envoyée :</strong><br>
                            <?= nl2br(htmlspecialchars($msg['reponse'])) ?>
                        </div>
                    <?php else: ?>
                        <!-- Formulaire de réponse -->
                        <form method="POST" class="mt-3">
                            <input type="hidden" name="message_id" value="<?= $msg['id'] ?>">
                            <div class="mb-2">
                                <label class="form-label">Réponse :</label>
                                <textarea name="reponse" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" name="repondre" class="btn btn-primary">Envoyer la réponse</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
