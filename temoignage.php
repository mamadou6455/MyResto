<?php 
    $title = 'Témoingages de nos clients';
 ?>
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($title)){ echo  $title;} else{echo 'Mon site';} ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <!--bouton de retour à l'accueil-->
     <div class="btnVP">
            <li>
                <a href="index.php" class="btnX">
                    <img src="images/close.png" alt="close">
                </a>
            </li>
        </div>
     <!--Témoingnage de nos clients-->
    <section class="temoignage" id="temoignage">
        <div class="titre blanc">
            <h2 class="titre-texte">Que Disent Nos <span>C</span>lients</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
        </div>
        <div class="contenu">
            <div class="box">
                <div class="imbox">
                    <img src="./images/t1.jpeg" alt="image">
                </div>
                <div class="text">
                    <p>Votre site Web nous a permis de gagner en visibilité et d'augmenter nos réservations. Je le recommande vivement aux restaurateurs.</p>
                    <h3>Franck leroi</h3>
                </div>
            </div>
            <div class="box">
                <div class="imbox">
                    <img src="./images/t2.jpg" alt="image">
                </div>
                <div class="text">
                    <p>Facile à utiliser et très efficace pour gérer votre restaurant.</p>
                    <h3>Pierre Dubois</h3>
                </div>
            </div>
            <div class="box">
                <div class="imbox">
                    <img src="./images/t3.jpg" alt="image">
                </div>
                <div class="text">
                    <p>"Votre système de commande en ligne a simplifié la gestion des commandes et a amélioré notre service client."</p>
                    <h3>Sophie Martel</h3>
                </div>
            </div>
    </div>
    </section>
</body>
</html>