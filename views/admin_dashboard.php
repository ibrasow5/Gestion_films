<?php
session_start();
include_once '../config/config.php';
include_once 'header.php';

// Vérifier si l'utilisateur est connecté et est administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Charger et afficher les films depuis le fichier XML
$xml = simplexml_load_file('../exo2.xml');
?>

<div class="container">
    <h2>Tableau de bord administrateur</h2>
    <p><a href="manage_films.php">Gérer les films</a></p>
    <ul>
        <?php foreach ($xml->liste_films->film as $film) : ?>
            <li>
                <h3><?php echo $film->titre; ?></h3>
                <p>Durée: <?php echo $film->duree; ?></p>
                <p>Genre: <?php echo $film->genre; ?></p>
                <p>Réalisateur: <?php echo $film->realisateur; ?></p>
                <p>Année de production: <?php echo $film->annee_production; ?></p>
                <p>Langue: <?php echo $film->langue; ?></p>
                <p><?php echo $film->paragraphe; ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php include 'footer.php'; ?>
