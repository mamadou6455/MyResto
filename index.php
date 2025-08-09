<?php 
    session_start();
    $title = 'Accueil';
    require 'header.php';
?>

<!-- Carousel dynamique -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active" style="height: 500px; background: url('images/bg-item1-min.jpg') center center/cover no-repeat;">
      <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
        <h2>Bienvenue au Restaurant GROUPE 04</h2>
        <p style="color:aliceblue;">Un voyage culinaire unique avec des plats raffinés et un service impeccable.</p>
        <a href="menu.php" class="btn btn-warning fw-bold">Voir notre menu</a>
      </div>
    </div>
    <div class="carousel-item" style="height: 500px; background: url('images/reservation.jpg') center center/cover no-repeat;">
      <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
        <h2>Réservez votre table</h2>
        <p style="color:aliceblue;">Profitez d’une ambiance chaleureuse en famille ou entre amis.</p>
        <a href="reservation.php" class="btn btn-warning fw-bold">Réserver maintenant</a>
      </div>
    </div>
    <div class="carousel-item" style="height: 500px; background: url('images/contact.jpg') center center/cover no-repeat;">
      <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
        <h2>Contactez-nous</h2>
        <p style="color:aliceblue;">Des questions ? Nous sommes à votre écoute.</p>
        <a href="contact.php" class="btn btn-warning fw-bold">Envoyer un message</a>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
    <span class="visually-hidden">Précédent</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
    <span class="visually-hidden">Suivant</span>
  </button>
</div>

<!-- A propos de nous -->
<section id="apropos" class="py-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6">
        <h2 class="mb-4"><span style="color: orange;">À</span> Propos De Nous</h2>
        
        <h6>🍽️ Bienvenue chez GROUPE-04</h6>
        <p>« Là où chaque bouchée raconte une histoire »</p>

        <p>Situé au cœur de votre ville, GROUPE-04 est bien plus qu’un simple restaurant : c’est un lieu de rencontre, de partage et de plaisir. Nous vous accueillons dans un cadre chaleureux et moderne, avec une équipe passionnée prête à vous faire vivre une véritable expérience culinaire.</p>

        <p>🧑‍🍳 Notre cuisine met à l’honneur des saveurs authentiques et variées, inspirées des traditions locales et du monde. Chaque plat est préparé avec soin, à partir d’ingrédients frais, pour satisfaire aussi bien les gourmets que les amateurs de bonne cuisine.</p>

        <p>🥘 Nos spécialités : [à compléter – par exemple : plats africains raffinés, grillades savoureuses, cuisine fusion, etc.]</p>

        <p>Que vous veniez pour un déjeuner rapide, un dîner romantique, une sortie entre amis ou un événement spécial, GROUPE-04 est l’endroit idéal pour savourer un moment unique.</p>

        <h6><strong>🕒 Horaires d’ouverture</strong></h6>
        <p>Du lundi au samedi : 11h à 22h</p>
        <p>Dimanche : fermé</p>

        <p>📍 Adresse : Bamako</p>
        <p>📞 Réservations : 12345678</p>
        <p>🌐 Site web : En construction – bientôt en ligne !</p>
 
      </div>
      
      <div class="col-md-6">
        <img src="./images/hp-our-speciality-banner.jpg" alt="Notre spécialité" class="img-fluid rounded shadow" /><br> <br><br><br>
        <img src="./images/hp-our-speciality-banner.jpg" alt="Notre spécialité" class="img-fluid rounded shadow" />
      </div>
    </div>
  </div>
</section>

<!-- Expériences professionnelles -->
<section id="expert" class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-4">Nos <span style="color: orange;">Experts</span></h2>
    <p class="text-center mb-5">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.
        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Exercitationem non eligendi fugit, voluptate facere consequuntur! Facere vitae ipsa consequatur porro quia eaque.
    </p>
    <div class="row g-4 justify-content-center">
      <?php 
        $experts = [
          ['name'=>'Mamadou Coulibaly', 'img'=>'./images/equipe1.jpg'],
          ['name'=>'Adama Dembélé', 'img'=>'./images/equipe2.jpg'],
          ['name'=>'Issiaka Koné', 'img'=>'./images/equipe3.jpg'],
          ['name'=>'Korotoum Diabaté', 'img'=>'images/equipe4.jpg'],
        ];
        foreach ($experts as $expert): ?>
          <div class="col-6 col-md-3 text-center">
            <img src="<?= htmlspecialchars($expert['img']) ?>" alt="<?= htmlspecialchars($expert['name']) ?>" class="img-fluid rounded-circle mb-3 shadow" style="height: 180px; width: 180px; object-fit: cover;">
            <h5><?= htmlspecialchars($expert['name']) ?></h5>
          </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Témoignages de nos clients -->
<section id="temoignage" class="py-5">
  <div class="container">
    <h2 class="text-center mb-4">Que Disent Nos <span style="color: orange;">Clients</span></h2>
    <p class="text-center mb-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
    <div class="row g-4 justify-content-center">
      <?php 
        $temoignages = [
          ['name'=>'Pankoro Diarra', 'img'=>'./images/t1.jpeg', 'text'=>"Votre site Web nous a permis de gagner en visibilité et d'augmenter nos réservations. Je le recommande vivement aux restaurateurs."],
          ['name'=>'Zeïnaba Maïga', 'img'=>'./images/t4.jpg', 'text'=>"Facile à utiliser et très efficace pour gérer votre restaurant."],
          ['name'=>'Souleymane Sidibé', 'img'=>'images/t3.jpg', 'text'=>'Votre système de commande en ligne a simplifié la gestion des commandes et a amélioré notre service client.'],
        ];
        foreach ($temoignages as $temoignage): ?>
          <div class="col-md-4">
            <div class="card shadow-sm h-100">
              <img src="<?= htmlspecialchars($temoignage['img']) ?>" alt="<?= htmlspecialchars($temoignage['name']) ?>" class="card-img-top" style="height: 250px; object-fit: cover;">
              <div class="card-body">
                <p class="card-text fst-italic">"<?= htmlspecialchars($temoignage['text']) ?>"</p>
                <h5 class="card-title text-end mb-0">- <?= htmlspecialchars($temoignage['name']) ?></h5>
              </div>
            </div>
          </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require 'footer.php'; ?>
