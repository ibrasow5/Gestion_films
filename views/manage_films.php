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
    $dom = dom_import_simplexml($xml)->ownerDocument;
    $dom->formatOutput = true;

    $newFilm = $xml->liste_films->addChild('film');
    $newFilm->addAttribute('id', uniqid());
    $newFilm->addChild('titre', $_POST['titre']);
    $newFilm->addChild('duree', $_POST['duree']);
    $newFilm->addChild('genre', $_POST['genre']);
    $newFilm->addChild('realisateur', $_POST['realisateur']);
    $newFilm->addChild('annee_production', $_POST['annee_production']);
    $newFilm->addChild('langue', $_POST['langue']);
    $newFilm->addChild('paragraphe', $_POST['paragraphe']);

    // Enregistrer le fichier XML avec un formatage lisible
    $dom->save('../exo2.xml');
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

            $dom = dom_import_simplexml($xml)->ownerDocument;
            $dom->formatOutput = true;
            $dom->save('../exo2.xml');
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

            $dom = dom_import_simplexml($xml)->ownerDocument;
            $dom->formatOutput = true;
            $dom->save('../exo2.xml');
            break;
        }
        $filmIndex++;
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les films</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2, h3 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100px; 
            text-align: center;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        table td {
            background-color: #fff;
        }

        table td form {
            display: inline-block; /* Utiliser inline-block pour permettre l'espacement */
            margin-right: 5px;
        }

        table td form:first-child {
            margin-bottom: 5px; /* Espacement vertical sous le premier formulaire */
        }

        table td form button {
            margin: 0 5px;
        }
    </style>
</head>
<body>
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
                        <!-- Formulaire pour modifier un film -->
                        <form method="post" action="" style="display:inline;">
                            <input type="hidden" name="edit_film" value="1">
                            <input type="hidden" name="film_id" value="<?php echo $film['id']; ?>">
                            <button type="submit">Modifier</button>
                        </form>
                        <!-- Formulaire pour supprimer un film -->
                        <form method="post" action="" style="display:inline;">
                            <input type="hidden" name="film_id" value="<?php echo $film['id']; ?>">
                            <button type="submit" name="delete_film">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
