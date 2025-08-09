<?php
require 'auth.php';  // Vérifie que l'admin est connecté
require 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
    exit;
}

$id = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

if (!$id || !$status) {
    echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
    exit;
}

// Liste des statuts autorisés
$allowed_statuses = ['en attente', 'en cours', 'livré', 'annulé'];
if (!in_array($status, $allowed_statuses, true)) {
    echo json_encode(['success' => false, 'error' => 'Statut invalide']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE commandes SET status = :status WHERE id = :id");
    $stmt->execute([
        ':status' => $status,
        ':id' => $id,
    ]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        // Aucun changement effectué (id introuvable ou même status)
        echo json_encode(['success' => false, 'error' => 'Aucune modification effectuée']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur serveur : ' . $e->getMessage()]);
}
