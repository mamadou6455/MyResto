<?php
require 'db.php';
session_start();
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        // Ajout pour QR code :
        $_SESSION['nom'] = $user['full_name'];
        $_SESSION['telephone'] = $user['telephone'] ?? 'Non renseigné';
        header("Location: dashboard_user.php");
        exit;
    } else {
        $message = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connexion - Mon Restaurant</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="images/logo.jpg" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
        ← Accueil
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="register.php">
              <i class="fas fa-user"></i> S'inscrire
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <img src="images/logo.jpg" alt="Logo" width="60" style="border-radius: 100px;">
        <h2 class="mb-4">Connexion</h2>
        <?php if ($message): ?>
          <div class="alert alert-danger"><?= $message ?></div>
        <?php endif; ?>
        <form method="POST" class="border p-4 rounded shadow-sm bg-light">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" class="form-control" name="password" required>
          </div>
          <button type="submit" class="btn btn-success w-100" style="background-color: orange; color:black">Se connecter</button>
        </form>
        <p class="mt-3 text-center">Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>
      </div>
    </div>
  </div>
</body>
</html>