<!--Déconnection à la page admin -->
<?php
session_start();
session_destroy();
header('Location: login_admin.php');
exit;
