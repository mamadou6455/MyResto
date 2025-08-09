<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plats'])) {
    foreach ($_POST['plats'] as $id => $quantite) {
        $id = (int)$id;
        $quantite = max(1, (int)$quantite);
        $_SESSION['panier'][$id] = ($_SESSION['panier'][$id] ?? 0) + $quantite;
    }
}

header('Location: panier.php');
exit;
