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
            flex: 0 0 150px; 
        }
        .film-affiche img {
            max-width: 100%; 
            height: auto;
            display: block; 
            margin-bottom: 10px; 
        }
        .film-details p {
            color: #000;
        }
        .film-details h4 {
            margin-bottom: 5px;
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
                    <p><strong>Durée:</strong> <?php echo $film->duree; ?></p>
                    <p><strong>Genre:</strong> <?php echo $film->genre; ?></p>
                    <p><strong>Réalisateur:</strong> <?php echo $film->realisateur; ?></p>
                    <p><strong>Année de production:</strong> <?php echo $film->annee_production; ?></p>
                    <p><strong>Langue:</strong> <?php echo $film->langue; ?></p>
                    <p><strong>Description:</strong> <?php echo $film->paragraphe; ?></p>
                    <h4>Acteurs:</h4>
                    <ul>
                        <?php foreach ($film->acteurs->acteur as $acteur) : ?>
                            <li><?php echo $acteur->prenom . ' ' . $acteur->nom; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <h4>Horaires:</h4>
                    <ul>
                        <?php foreach ($film->liste_horaires->horaires as $horaire) : ?>
                            <li><strong><?php echo $horaire->jour; ?>:</strong> <?php echo $horaire->heure; ?></li>
                        <?php endforeach; ?>
                    </ul>
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