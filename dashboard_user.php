<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tableau de bord</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-pink-100">
  <nav class="bg-gray-900 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
      <a href="index.php" class="flex items-center text-white font-semibold text-lg hover:text-blue-300 transition">
        <img src="images/logo.jpg" alt="Logo" class="w-10 h-10 rounded-full mr-2 shadow-lg border-2 border-white">
        ← Accueil
      </a>
      <a href="login_user.php" class="text-gray-300 hover:text-red-400 flex items-center transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" />
        </svg>
        Déconnexion
      </a>
    </div>
  </nav>

  <div class="max-w-5xl mx-auto mt-10">
    <?php if (isset($_GET['modification']) && $_GET['modification'] === 'success'): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 text-center animate-bounce">
        Votre réservation a été modifiée avec succès.
      </div>
    <?php endif; ?>

    <div class="text-center mb-8">
      <h1 class="text-4xl font-extrabold mb-2 bg-gradient-to-r from-blue-500 via-pink-500 to-yellow-500 bg-clip-text text-transparent animate-fade-in-down">
        Bienvenue, <?= htmlspecialchars($_SESSION['full_name'] ?? 'Client') ?> !
      </h1>
      <p class="text-lg text-gray-700 animate-fade-in-up">Vous êtes connecté à votre espace client.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
      <!-- Consulter le menu -->
      <div class="bg-white rounded-2xl shadow-xl border-2 border-blue-400 p-6 text-center transform hover:scale-105 hover:shadow-2xl transition duration-300 group cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-blue-600 mb-4 group-hover:text-blue-800 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 3v13m4-13v13m4-13v13m-8 0a2 2 0 104 0m4 0a2 2 0 104 0" />
        </svg>
        <h5 class="text-xl font-semibold mb-2">Consulter le menu</h5>
        <p class="text-gray-500 mb-4">Parcourez notre sélection de plats et spécialités.</p>
        <a href="menu.php" class="inline-block bg-blue-600 text-white px-5 py-2 rounded-full font-semibold shadow hover:bg-blue-700 transition">Voir le menu</a>
      </div>

      <!-- Faire une réservation -->
      <div class="bg-white rounded-2xl shadow-xl border-2 border-green-400 p-6 text-center transform hover:scale-105 hover:shadow-2xl transition duration-300 group cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-green-600 mb-4 group-hover:text-green-800 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <rect width="18" height="14" x="3" y="5" rx="2" stroke-width="2" stroke="currentColor" fill="none"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3v4M8 3v4M3 9h18" />
        </svg>
        <h5 class="text-xl font-semibold mb-2">Faire une réservation</h5>
        <p class="text-gray-500 mb-4">Réservez votre table directement en ligne.</p>
        <a href="reservation.php" class="inline-block bg-green-600 text-white px-5 py-2 rounded-full font-semibold shadow hover:bg-green-700 transition">Réserver</a>
      </div>

      <!-- Mes commandes -->
      <div class="bg-white rounded-2xl shadow-xl border-2 border-yellow-400 p-6 text-center transform hover:scale-105 hover:shadow-2xl transition duration-300 group cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-yellow-500 mb-4 group-hover:text-yellow-700 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <circle cx="12" cy="12" r="10" stroke-width="2" stroke="currentColor" fill="none"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" />
        </svg>
        <h5 class="text-xl font-semibold mb-2">Mes commandes</h5>
        <p class="text-gray-500 mb-4">Consultez l’historique de vos commandes.</p>
        <a href="mes_commandes.php" class="inline-block bg-yellow-400 text-white px-5 py-2 rounded-full font-semibold shadow hover:bg-yellow-500 hover:text-gray-900 transition">Voir mes commandes</a>
      </div>

      <!-- Passer une commande -->
      <div class="bg-white rounded-2xl shadow-xl border-2 border-red-400 p-6 text-center transform hover:scale-105 hover:shadow-2xl transition duration-300 group cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-red-600 mb-4 group-hover:text-red-800 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 007 17h10a1 1 0 00.95-.68L21 9M7 13V6h13" />
        </svg>
        <h5 class="text-xl font-semibold mb-2">Passer une commande</h5>
        <p class="text-gray-500 mb-4">Commandez vos plats préférés en ligne.</p>
        <a href="commande.php" class="inline-block bg-red-600 text-white px-5 py-2 rounded-full font-semibold shadow hover:bg-red-700 transition">Commander</a>
      </div>
    </div>
  </div>
  <style>
    @keyframes fade-in-down {
      0% { opacity: 0; transform: translateY(-20px);}
      100% { opacity: 1; transform: translateY(0);}
    }
    @keyframes fade-in-up {
      0% { opacity: 0; transform: translateY(20px);}
      100% { opacity: 1; transform: translateY(0);}
    }
    .animate-fade-in-down { animation: fade-in-down 1s ease; }
    .animate-fade-in-up { animation: fade-in-up 1s ease; }
  </style>
</body>
</html>