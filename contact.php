<?php
require 'db.php';
session_start();

// Enregistrement message
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['message'])) {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $email, $message]);
    $msg_sent = true;

    // Envoi d'email de confirmation
    $to = $email;
    $subject = "Confirmation de réception de votre message";
    $body = "Bonjour $nom,\n\nMerci pour votre message. Nous vous répondrons bientôt.\n\n-- Restaurant";
    $headers = "From: mamadoucoulibaly6455@gmail.com";
    mail($to, $subject, $body, $headers);
}

// Like (avec IP)
if (isset($_GET['like']) && !isset($_SESSION['liked'])) {
    $_SESSION['liked'] = true;
    $ip = $_SERVER['REMOTE_ADDR'];

    $stmt = $pdo->prepare("SELECT * FROM likes WHERE ip_address = ?");
    $stmt->execute([$ip]);

    if ($stmt->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO likes (ip_address) VALUES (?)");
        $stmt->execute([$ip]);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact & Like</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #ffecd2 0%, #fcb69f 100%);
            font-family: 'Poppins', sans-serif;
            animation: fadeIn 1s ease-in;
        }
        .container {
            max-width: 600px;
        }
        .card-custom {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: fadeInUp 1s ease;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .btn-like:hover, .btn-send:hover {
            transform: scale(1.05);
            transition: 0.3s ease;
        }
        .btn-send {
            background-color: orange;
            border: none;
        }
        .btn-send:hover {
            background-color: darkorange;
        }
        .btn-like {
            border-color: #ff4d4d;
            color: #ff4d4d;
            background-color: #fff;
        }
        .btn-like:hover {
            background-color: #ffe6e6;
        }
        .close-btn img {
            width: 30px;
        }
        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        #loader {
            display: none;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="images/logo.jpg" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2" style="border-radius: 100px;">
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

<div class="container my-5">
    <div class="card card-custom p-4">
        <h2 class="text-center mb-4">💬 Contactez-nous</h2>

        <?php if (isset($msg_sent)): ?>
            <div class="alert alert-success text-center">Merci pour votre message !</div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom complet</label>
                <input type="text" class="form-control" name="nom" id="nom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Adresse Email</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Votre message</label>
                <textarea name="message" class="form-control" rows="4" required></textarea>
            </div>
            <button class="btn btn-send w-100 py-2">✉️ Envoyer le message</button>
        </form>

        <hr class="my-4">

        <div class="text-center">
            <a href="?like=true" class="btn btn-like px-4">❤️ J'aime</a>
            <?php
                $count = $pdo->query("SELECT COUNT(*) FROM likes")->fetchColumn();
                echo "<p class='mt-2'>$count personnes aiment notre restaurant</p>";
            ?>
        </div>
    </div>
</div>
</body>
</html>