<?php
session_start();
include_once '../config/config.php';
include_once 'header.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

// Charger et afficher les films depuis le fichier XML
$xml = simplexml_load_file('../exo2.xml');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Portail Cinéma</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .film-container {
            display: flex;
            margin-bottom: 20px;
        }
        .film-details {
            flex: 1;
            padding-right: 20px;
        }
        .film-details h3 {
            margin-top: 0;
        }
        .film-affiche {
            flex: 0 0 150px; /* Définition de la largeur fixe pour l'affiche */
        }
        .film-affiche img {
            max-width: 100%; /* Assure que l'image s'adapte à la largeur de son conteneur */
            height: auto; /* Garde les proportions de l'image */
            display: block; /* Supprime l'espace blanc sous les images */
            margin-bottom: 10px; /* Espace en bas de chaque affiche */
        }
    </style>
</head>
<body>
<nav>
    <ul>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <p><a href="/Gestion_films/views/manage_films.php">Gérer les films</a></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['user'])): ?>
            <li><a href="/Gestion_films/index.php">Déconnexion</a></li>
        <?php else: ?>
            <li><a href="/Gestion_films/login.php">Connexion</a></li>
            <li><a href="/Gestion_films/register.php">Inscription</a></li>
        <?php endif; ?>
    </ul>
</nav>
<div class="container">
    <h2>Films disponibles</h2>
    <?php if (count($xml->liste_films->film) > 0) : ?>
        <?php foreach ($xml->liste_films->film as $film) : ?>
            <div class="film-container">
                <div class="film-details">
                    <h3><?php echo $film->titre; ?></h3>
                    <p>Durée: <?php echo $film->duree; ?></p>
                    <p>Genre: <?php echo $film->genre; ?></p>
                    <p>Réalisateur: <?php echo $film->realisateur; ?></p>
                    <p>Année de production: <?php echo $film->annee_production; ?></p>
                    <p>Langue: <?php echo $film->langue; ?></p>
                    <p><?php echo $film->paragraphe; ?></p>
                </div>
                <div class="film-affiche">
                    <?php if (isset($film->affiche) && !empty($film->affiche)) : ?>
                        <img src="../views/images/<?php echo $film->affiche; ?>" alt="Affiche de <?php echo $film->titre; ?>">
                    <?php else : ?>
                        <p>Aucune affiche disponible</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Aucun film trouvé.</p>
    <?php endif; ?>
</div>
</body>
</html>

<?php include 'footer.php'; ?>
