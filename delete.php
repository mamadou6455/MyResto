<?php
require 'auth.php';
require 'db.php';

if (isset($_GET['type'], $_GET['id'])) {
    $type = $_GET['type'];
    $id = (int) $_GET['id'];

    if ($type === 'reservation') {
        $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
    } elseif ($type === 'commande') {
        $stmt = $pdo->prepare("DELETE FROM commandes WHERE id = ?");
    } else {
        die("Type invalide.");
    }

    if ($stmt->execute([$id])) {
        header('Location: admin.php');
        exit;
    } else {
        echo "Erreur lors de la suppression.";
    }
} else {
    echo "Paramètres manquants.";
}
