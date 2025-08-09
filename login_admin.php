<?php

session_start();

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $utilisateur = $_POST['username'];
    $motdepasse = $_POST['password'];

    if ($utilisateur === 'admin' && $motdepasse === '1234') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard_admin.php');
        exit;
    } else {
        $erreur = "Identifiants incorrects.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            background: white;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h3 class="mb-3 text-center">🔐 Connexion Admin</h3>
    
    <?php if ($erreur): ?>
        <div class="alert alert-danger"><?php echo $erreur; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Nom d'utilisateur</label>
            <input type="text" class="form-control" id="username" name="username" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
    </form>
</div>

</body>
</html>