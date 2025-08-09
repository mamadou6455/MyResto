<?php
require 'auth.php';
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']);

    // Mise à jour du champ `last_login` pour simuler une déconnexion
    $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() - INTERVAL 1 HOUR WHERE id = ?");
    $stmt->execute([$userId]);

    // Redirection vers le tableau de bord après la "déconnexion"
    header("Location: dashboard_admin.php");
    exit;
} else {
    // Accès direct non autorisé
    http_response_code(403);
    echo "Accès non autorisé.";
}
