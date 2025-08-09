<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];

    if ($type === 'reservation') {
        $nom = htmlspecialchars(trim($_POST['nom']));
        $email = htmlspecialchars(trim($_POST['email']));
        $telephone = htmlspecialchars(trim($_POST['telephone']));
        $date = htmlspecialchars(trim($_POST['date']));
        $heure = htmlspecialchars(trim($_POST['heure']));
        $nb_personnes = htmlspecialchars(trim($_POST['nb_personnes']));

        if (empty($nom) || empty($email) || empty($telephone) || empty($date) || empty($heure) || empty($nb_personnes)) {
            $message = "Veuillez remplir tous les champs de la réservation.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO reservations (nom, email, telephone, date_reservation, heure, nb_personnes) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $email, $telephone, $date, $heure, $nb_personnes]);
            $message = "Réservation enregistrée avec succès.";
        }
    } elseif ($type === 'commande') {
        $nom = htmlspecialchars(trim($_POST['nom']));
        $plat = htmlspecialchars(trim($_POST['plat']));
        $quantite = htmlspecialchars(trim($_POST['quantite']));
        $instructions = htmlspecialchars(trim($_POST['instructions']));

        if (empty($nom) || empty($plat) || empty($quantite)) {
            $message = "Veuillez remplir tous les champs de la commande.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO commandes (nom_client, plat, quantite, instructions) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nom, $plat, $quantite, $instructions]);
            $message = "Commande enregistrée avec succès.";
        }
    } else {
        $message = "Type de formulaire inconnu.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Confirmation</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .fade-in {
      opacity: 0;
      animation: fadeIn 1.5s forwards;
    }
    @keyframes fadeIn {
      to {
        opacity: 1;
      }
    }
    .spinner-container {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 2rem;
    }
  </style>
  <script>
    window.addEventListener('DOMContentLoaded', function () {
      // Jouer un son léger de confirmation
      const audio = new Audio('https://www.myinstants.com/media/sounds/success-fanfare-trumpets.mp3');
      audio.volume = 0.3; // Son léger
      audio.play();

      // Redirection après 5 secondes
      setTimeout(function() {
        window.location.href = 'index.php';
      }, 5000);
    });
  </script>
</head>
<body>
  <div class="container mt-5">
      <div class="alert alert-success text-center fade-in">
          <?php echo $message ?? 'Aucune action détectée.'; ?>
          <br><small>Redirection vers l'accueil dans 5 secondes...</small>
      </div>
      <div class="spinner-container">
        <div class="spinner-border text-success" role="status">
          <span class="visually-hidden">Chargement...</span>
        </div>
      </div>
  </div>
</body>
</html>
