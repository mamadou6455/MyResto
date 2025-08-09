<?php
$host = 'localhost';
$db = 'restaurant_db'; // Nom de la base de données
$user = 'root';
$pass = 'mysql'; // Par défaut vide sous XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
