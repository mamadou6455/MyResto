<?php
require 'db.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $telephone = htmlspecialchars($_POST["telephone"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm_password"];

    if ($password !== $confirm) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, telephone) VALUES (?, ?, ?, ?)");
        try {
            $stmt->execute([$name, $email, $hashed_password, $telephone]);
            header("Location: login_user.php");
            exit;
        } catch (PDOException $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inscription - Mon Restaurant</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="images/logo.jpg" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2" style="border-radius: 10px;">
        ← Accueil
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="login_user.php">
              <i class="fas fa-user"></i> Connexion
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="text-center mb-4">
          <img src="images/logo.jpg" alt="Logo" width="60" style="border-radius: 100px;">
          <h2 class="mt-2">Créer un compte</h2>
        </div>
        <?php if ($message): ?>
          <div class="alert alert-danger text-center"><?= $message ?></div>
        <?php endif; ?>
        <form method="POST" class="border p-4 rounded shadow-sm bg-light">
          <div class="mb-3">
            <label for="name" class="form-label">Nom complet</label>
            <input type="text" class="form-control" name="name" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Adresse Email</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" class="form-control" name="telephone" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" name="password" required>
          </div>
          <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
            <input type="password" class="form-control" name="confirm_password" required>
          </div>
          <button type="submit" class="btn btn-primary w-100" style="background-color: orange; color: black;">S'inscrire</button>
        </form>
        <p class="mt-3 text-center">Déjà inscrit ? <a href="login_user.php">Se connecter</a></p>
      </div>
    </div>
  </div>
</body>
</html>