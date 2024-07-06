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

// Ajouter un film
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_film'])) {
    $newFilm = $xml->liste_films->addChild('film');
    $newFilm->addChild('titre', $_POST['titre']);
    $newFilm->addChild('duree', $_POST['duree']);
    $newFilm->addChild('genre', $_POST['genre']);
    $newFilm->addChild('realisateur', $_POST['realisateur']);
    $newFilm->addChild('annee_production', $_POST['annee_production']);
    $newFilm->addChild('langue', $_POST['langue']);
    $newFilm->addChild('paragraphe', $_POST['paragraphe']);
    $xml->asXML('../exo2.xml');
}

// Modifier un film
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_film'])) {
    foreach ($xml->liste_films->film as $film) {
        if ($film['id'] == $_POST['film_id']) {
            $film->titre = $_POST['titre'];
            $film->duree = $_POST['duree'];
            $film->genre = $_POST['genre'];
            $film->realisateur = $_POST['realisateur'];
            $film->annee_production = $_POST['annee_production'];
            $film->langue = $_POST['langue'];
            $film->paragraphe = $_POST['paragraphe'];
            $xml->asXML('../exo2.xml');
            break;
        }
    }
}

// Supprimer un film
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_film'])) {
    $filmIndex = 0;
    foreach ($xml->liste_films->film as $film) {
        if ($film['id'] == $_POST['film_id']) {
            unset($xml->liste_films->film[$filmIndex]);
            $xml->asXML('../exo2.xml');
            break;
        }
        $filmIndex++;
    }
}
?>

<div class="container">
    <h2>Gérer les films</h2>
    <h3>Ajouter un film</h3>
    <form method="post" action="">
        <input type="hidden" name="add_film" value="1">
        <label for="titre">Titre:</label>
        <input type="text" id="titre" name="titre" required>
        <label for="duree">Durée:</label>
        <input type="text" id="duree" name="duree" required>
        <label for="genre">Genre:</label>
        <input type="text" id="genre" name="genre" required>
        <label for="realisateur">Réalisateur:</label>
        <input type="text" id="realisateur" name="realisateur" required>
        <label for="annee_production">Année de production:</label>
        <input type="text" id="annee_production" name="annee_production" required>
        <label for="langue">Langue:</label>
        <input type="text" id="langue" name="langue" required>
        <label for="paragraphe">Description:</label>
        <textarea id="paragraphe" name="paragraphe" required></textarea>
        <button type="submit">Ajouter</button>
    </form>

    <h3>Films existants</h3>
    <table>
        <tr>
            <th>Titre</th>
            <th>Durée</th>
            <th>Genre</th>
            <th>Réalisateur</th>
            <th>Année</th>
            <th>Langue</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($xml->liste_films->film as $film) : ?>
            <tr>
                <td><?php echo $film->titre; ?></td>
                <td><?php echo $film->duree; ?></td>
                <td><?php echo $film->genre; ?></td>
                <td><?php echo $film->realisateur; ?></td>
                <td><?php echo $film->annee_production; ?></td>
                <td><?php echo $film->langue; ?></td>
                <td><?php echo $film->paragraphe; ?></td>
                <td>
                    <form method="post" action="" style="display:inline;">
                        <input type="hidden" name="film_id" value="<?php echo $film['id']; ?>">
                        <button type="submit" name="edit_film">Modifier</button>
                    </form>
                    <form method="post" action="" style="display:inline;">
                        <input type="hidden" name="film_id" value="<?php echo $film['id']; ?>">
                        <button type="submit" name="delete_film">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include 'footer.php'; ?>
