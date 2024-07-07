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

// Map des images d'affiches des films
$film_posters = [
    'Les Brigades du Tigre' => 'images/Brigades.jpg',
    'Harry Potter à l\'école des sorciers' => 'images/harry_potter_1.jpg',
    'Harry Potter et la Chambre des Secrets' => 'images/harry_potter_2.jpg',
    'Harry Potter et le Prisonnier d\'Azkaban' => 'images/harry_potter_3.jpg',
    'Harry Potter et le Prince de Sang-Mêlé' => 'images/harry_potter_4.jpg',
    'Harry Potter et les Reliques de la Mort (Partie 1)' => 'images/harry_potter_5.jpg',
    'Spider-Man' => 'images/spiderman.jpg'
];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Portail Cinéma</title>
    <link rel="stylesheet" href="assets/css/styles.css">
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
    <ul>
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
                    <?php
                    $poster_path = isset($film_posters[(string)$film->titre]) ? $film_posters[(string)$film->titre] : 'images/default_poster.jpg';
                    ?>
                    <img src="<?php echo $poster_path; ?>" alt="Affiche de <?php echo $film->titre; ?>">
                </div>
            </div>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>

<?php include 'footer.php'; ?>
