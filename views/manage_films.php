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

    // Gestion de l'affiche du film
    if ($_FILES['affiche']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../views/images/';
        $uploadFile = $uploadDir . basename($_FILES['affiche']['name']);
        move_uploaded_file($_FILES['affiche']['tmp_name'], $uploadFile);
        $newFilm->addChild('affiche', $_FILES['affiche']['name']);
    }

    // Gestion des acteurs
    $acteursNode = $newFilm->addChild('acteurs');
    foreach ($_POST['acteurs'] as $acteur) {
        $acteurNode = $acteursNode->addChild('acteur');
        list($nom, $prenom) = explode(" ", trim($acteur));
        $acteurNode->addChild('nom', $nom);
        $acteurNode->addChild('prenom', $prenom);
    }

    // Gestion des horaires
    $horairesNode = $newFilm->addChild('liste_horaires');
    foreach ($_POST['horaires'] as $jour => $horaires) {
        $horairesNodeJour = $horairesNode->addChild('horaires');
        $horairesNodeJour->addChild('jour', $jour);

        // Construire la chaîne d'horaires avec heures et minutes séparées par "|"
        $horaireString = '';
        foreach ($horaires as $horaire) {
            $horaireString .= $horaire['heure'] . ':' . $horaire['minute'] . '|';
        }
        // Retirer le dernier "|" s'il y en a un
        $horaireString = rtrim($horaireString, '|');

        // Ajouter la chaîne complète d'horaires dans le XML
        $horairesNodeJour->addChild('heure', $horaireString);
    }


    // Enregistrer le fichier XML avec un formatage lisible
    $dom->save('../exo2.xml');

    // Rediriger vers la page de gestion après l'ajout
    header('Location: manage_films.php');
    exit();
}

// Modifier un film
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_film'])) {
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

    // Rediriger vers la page de gestion après la modification
    header('Location: manage_films.php');
    exit();
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

    // Rediriger vers la page de gestion après la suppression
    header('Location: manage_films.php');
    exit();
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
        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="add_film" value="1">
            <label for="titre">Titre:</label>
            <input type="text" id="titre" name="titre" required>
            <label for="duree">Durée:</label>
            <input type="text" id="duree" name="duree" required>
            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" required>
            <label for="realisateur">Réalisateur:</label>
            <input type="text" id="realisateur" name="realisateur" required>
            <label for="acteurs">Acteurs:</label>
            
            <div id="acteurs-container">
                <div class="acteur-input">
                    <input type="text" name="acteurs[]" required>
                </div>
            </div>
            
            <button type="button" onclick="ajouterActeur()">Ajouter un acteur</button>
            <label for="annee_production">Année de production:</label>
            <input type="text" id="annee_production" name="annee_production" required>
            <label for="langue">Langue:</label>
            <input type="text" id="langue" name="langue" required>
            <label for="paragraphe">Description:</label>
            <textarea id="paragraphe" name="paragraphe" required></textarea>
            <label for="acteurs">Acteurs:</label>
        
            <label for="horaires">Horaires:</label>
            <div id="horaires-container">
                <?php $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche']; ?>
                <?php foreach ($jours as $jour) : ?>
                    <label><?php echo $jour; ?>:</label><br>
                    <?php for ($i = 0; $i < 3; $i++) : ?>
                        <select name="horaires[<?php echo $jour; ?>][<?php echo $i; ?>][heure]">
                            <?php for ($h = 0; $h <= 23; $h++) : ?>
                                <option value="<?php echo sprintf("%02d", $h); ?>"><?php echo sprintf("%02d", $h); ?></option>
                            <?php endfor; ?>
                        </select>
                        :
                        <select name="horaires[<?php echo $jour; ?>][<?php echo $i; ?>][minute]">
                            <?php for ($m = 0; $m <= 59; $m += 1) : ?>
                                <option value="<?php echo sprintf("%02d", $m); ?>"><?php echo sprintf("%02d", $m); ?></option>
                            <?php endfor; ?>
                        </select>
                        <br>
                    <?php endfor; ?>
                <?php endforeach; ?>
            </div>

            <label for="affiche">Affiche du film:</label>
            <input type="file" id="affiche" name="affiche" accept="image/*" required>
            <br>
            <button type="submit">Ajouter le film</button>
        </form>

        
    </div>
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
                <th>Acteurs</th>
                <th>Horaires</th>
                <th>Affiche</th>
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
                    <td style="max-width: 150px; max-height: 200px;"><?php echo $film->paragraphe; ?></td>
                    <td style="max-width: 150px; max-height: 200px;">
                        <?php foreach ($film->acteurs->acteur as $acteur) {
                            echo $acteur->nom . ' ' . $acteur->prenom . '<br>';
                        } ?>
                    </td>
                    <td style="max-width: 150px; max-height: 200px;">
                        <?php foreach ($film->liste_horaires->horaires as $horaire) {
                            echo $horaire->jour . ': ';
                            foreach ($horaire->heure as $index => $heure) {
                                echo $heure . ':' . $horaire->minute[$index] . ' ';
                            }
                            echo '<br>';
                        } ?>
                    </td>
                    <td style="max-width: 150px; max-height: 200px;">
                        <?php if (isset($film->affiche) && !empty($film->affiche)) : ?>
                            <img src="../views/images/<?php echo $film->affiche; ?>" alt="Affiche" style="width: 100%; height: auto;">
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="delete_film" value="1">
                            <input type="hidden" name="film_id" value="<?php echo $film['id']; ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                        <form method="post" action="update_film.php">
                            <input type="hidden" name="film_id" value="<?php echo $film['id']; ?>">
                            <button type="submit">Modifier</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <script>
        function ajouterActeur() {
            var container = document.getElementById('acteurs-container');
            var newInput = document.createElement('div');
            newInput.className = 'acteur-input';
            newInput.innerHTML = '<input type="text" name="acteurs[]" required>';
            container.appendChild(newInput);
        }
    </script>
</body>
</html>
