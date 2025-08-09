<?php 
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login_user.php");
        exit;
    }
    $title = 'Passer une réservation';
    include 'db.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($title) ? $title : 'Mon site'; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #ffecd2 0%, #fcb69f 100%);
      font-family: 'Poppins', sans-serif;
    }
    .reservation {
      padding: 40px;
      background-color: white;
      border-radius: 12px;
      max-width: 700px;
      margin: 60px auto;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      animation: fadeIn 0.8s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .titre-texte {
      font-size: 2.8rem;
      font-weight: 600;
      margin-bottom: 15px;
      text-align: center;
      color: #343a40;
    }
    .titre-texte span {
      color: #fd7e14;
    }
    .intro-text {
      text-align: center;
      font-size: 1.1rem;
      color: #6c757d;
      margin-bottom: 30px;
    }
    .form-group {
      position: relative;
      margin-bottom: 25px;
    }
    .form-group label {
      font-weight: 500;
      color: #495057;
      transition: all 0.3s ease;
    }
    .form-control {
      border-radius: 8px;
      padding: 12px;
      transition: box-shadow 0.3s ease, transform 0.2s ease;
    }
    .form-control:focus {
      box-shadow: 0 0 0 4px rgba(253, 126, 20, 0.2);
      transform: scale(1.02);
    }
    button {
      background-color: #fd7e14;
      color: white;
      border: none;
      padding: 12px 30px;
      border-radius: 8px;
      transition: all 0.3s ease;
      font-weight: 600;
    }
    button:hover {
      background-color: #e96c0a;
      transform: scale(1.05);
    }
    .alert {
      animation: slideDown 0.6s ease;
    }
    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    #preview-card {
      display: none;
      margin-top: 30px;
      padding: 20px;
      border: 1px solid #fd7e14;
      border-radius: 12px;
      background-color: #fff3e6;
      animation: fadeIn 0.5s ease-in-out;
    }
    .btn-group {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 15px;
    }
  </style>
</head>
<body>
 <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="images/logo.jpg" alt="Logo" width="30" height="30" class="me-2">
        ← Accueil
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="dashboard_user.php"><i class="fas fa-home"></i> Tableau de bord</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login_user.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

    
  <section class="reservation">
    <div class="text-center">
      <h2 class="titre-texte"><span>R</span>éservation</h2>
      <p class="intro-text">Réservez votre table en quelques secondes et vivez une expérience culinaire inoubliable.</p>

      <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
        <div class="alert alert-success">Réservation effectuée avec succès !</div>
      <?php endif; ?>
      <div id="cancel-alert" class="alert alert-warning d-none">Réservation annulée.</div>
    </div>

    <form id="reservation-form" action="traitement2.php" method="POST">
      <div class="form-group">
        <label>Nom complet</label>
        <input type="text" name="nom" class="form-control" placeholder="Votre nom complet" required>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" placeholder="exemple@email.com" required>
      </div>

      <div class="form-group">
        <label>Téléphone</label>
        <input type="text" name="telephone" class="form-control" placeholder="06XXXXXXXX" required>
      </div>

      <div class="form-group">
        <label>Date</label>
        <input type="date" name="date" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Heure</label>
        <input type="time" name="heure" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Nombre de personnes</label>
        <input type="number" name="nb_personnes" class="form-control" min="1" required>
      </div>

      <div class="text-center">
        <button type="button" onclick="showPreview()">Prévisualiser</button>
      </div>
    </form>

    <div id="preview-card">
      <h5 class="mb-3">Prévisualisation de la réservation</h5>
      <ul class="list-group">
        <li class="list-group-item"><strong>Nom :</strong> <span id="preview-nom"></span></li>
        <li class="list-group-item"><strong>Email :</strong> <span id="preview-email"></span></li>
        <li class="list-group-item"><strong>Téléphone :</strong> <span id="preview-telephone"></span></li>
        <li class="list-group-item"><strong>Date :</strong> <span id="preview-date"></span></li>
        <li class="list-group-item"><strong>Heure :</strong> <span id="preview-heure"></span></li>
        <li class="list-group-item"><strong>Nombre de personnes :</strong> <span id="preview-nb_personnes"></span></li>
      </ul>
      <form action="traitement.php" method="POST" class="mt-4">
        <input type="hidden" name="type" value="reservation">
        <input type="hidden" name="nom" id="form-nom">
        <input type="hidden" name="email" id="form-email">
        <input type="hidden" name="telephone" id="form-telephone">
        <input type="hidden" name="date" id="form-date">
        <input type="hidden" name="heure" id="form-heure">
        <input type="hidden" name="nb_personnes" id="form-nb_personnes">
        <div class="btn-group">
          <button type="submit">Confirmer</button>
          <button type="button" onclick="editReservation()">Modifier</button>
          <button type="button" onclick="cancelReservation()">Annuler</button>
        </div>
      </form>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function showPreview() {
      const form = document.forms['reservation-form'];
      const nom = form.nom.value;
      const email = form.email.value;
      const telephone = form.telephone.value;
      const date = form.date.value;
      const heure = form.heure.value;
      const nb = form.nb_personnes.value;

      document.getElementById('preview-nom').textContent = nom;
      document.getElementById('preview-email').textContent = email;
      document.getElementById('preview-telephone').textContent = telephone;
      document.getElementById('preview-date').textContent = date;
      document.getElementById('preview-heure').textContent = heure;
      document.getElementById('preview-nb_personnes').textContent = nb;

      document.getElementById('form-nom').value = nom;
      document.getElementById('form-email').value = email;
      document.getElementById('form-telephone').value = telephone;
      document.getElementById('form-date').value = date;
      document.getElementById('form-heure').value = heure;
      document.getElementById('form-nb_personnes').value = nb;

      document.getElementById('preview-card').style.display = 'block';
      document.getElementById('preview-card').scrollIntoView({ behavior: 'smooth' });
    }

    function editReservation() {
      const form = document.forms['reservation-form'];

      form.nom.value = document.getElementById('form-nom').value;
      form.email.value = document.getElementById('form-email').value;
      form.telephone.value = document.getElementById('form-telephone').value;
      form.date.value = document.getElementById('form-date').value;
      form.heure.value = document.getElementById('form-heure').value;
      form.nb_personnes.value = document.getElementById('form-nb_personnes').value;

      document.getElementById('preview-card').style.display = 'none';
      document.getElementById('reservation-form').scrollIntoView({ behavior: 'smooth' });
    }

    function cancelReservation() {
      const form = document.getElementById('reservation-form');
      form.reset();
      document.getElementById('preview-card').style.display = 'none';
      const alert = document.getElementById('cancel-alert');
      alert.classList.remove('d-none');
      alert.scrollIntoView({ behavior: 'smooth' });
      setTimeout(() => alert.classList.add('d-none'), 3000);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  </script>
</body>
</html>













