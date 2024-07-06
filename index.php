<?php
session_start();
include_once 'config/config.php';
include_once 'models/User.php';
include_once 'views/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<div class="container">
    <h1>Bienvenue sur le portail cinéma</h1>
    <p><a href="login.php">Se connecter</a> ou <a href="register.php">S'inscrire</a></p>
</div>
</body>
</html>

<?php include 'views/footer.php'; ?>
