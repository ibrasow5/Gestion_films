<?php
session_start();
include_once '../config/config.php';
include_once 'header.php';

// Vérifier si l'utilisateur est connecté et est administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Charger le fichier XML
$xml = simplexml_load_file('../exo2.xml');
if (!$xml) {
    die('Erreur lors du chargement du fichier XML.');
}

// Trouver le film à modifier par ID
$filmToEdit = null;
if (isset($_POST['film_id'])) {
    $film_id = $_POST['film_id'];
    $filmToEdit = $xml->xpath("//film[@id='$film_id']")[0];
}

// Si le formulaire de modification est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_film'])) {
    // Vérifier si le film à modifier a été trouvé
    if ($filmToEdit) {
        // Mettre à jour les données du film
        $filmToEdit->titre = $_POST['titre'];
        $filmToEdit->duree = $_POST['duree'];
        $filmToEdit->genre = $_POST['genre'];
        $filmToEdit->realisateur = $_POST['realisateur'];
        $filmToEdit->annee_production = $_POST['annee_production'];
        $filmToEdit->langue = $_POST['langue'];
        $filmToEdit->paragraphe = $_POST['paragraphe'];

        // Vérifier et traiter l'upload de l'affiche
        if ($_FILES['affiche']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../views/images/';
            $uploadFile = $uploadDir . basename($_FILES['affiche']['name']);
            if (move_uploaded_file($_FILES['affiche']['tmp_name'], $uploadFile)) {
                $filmToEdit->affiche = '' . basename($_FILES['affiche']['name']);
            } else {
                echo "Erreur lors de l'upload du fichier.";
            }
        }

        // Enregistrer les modifications dans le fichier XML
        $xml->asXML('../exo2.xml');

        // Rediriger vers la page de gestion des films
        header('Location: manage_films.php');
        exit();
    } else {
        echo "Film non trouvé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un film</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .container {
            width: 50%;
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

        input[type="file"] {
            margin-top: 10px;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifier un film</h2>
        <?php if ($filmToEdit) : ?>
            <form method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="film_id" value="<?php echo $filmToEdit['id']; ?>">
                <input type="hidden" name="update_film" value="1">
                <label for="titre">Titre:</label>
                <input type="text" id="titre" name="titre" value="<?php echo $filmToEdit->titre; ?>" required>
                <label for="duree">Durée:</label>
                <input type="text" id="duree" name="duree" value="<?php echo $filmToEdit->duree; ?>" required>
                <label for="genre">Genre:</label>
                <input type="text" id="genre" name="genre" value="<?php echo $filmToEdit->genre; ?>" required>
                <label for="realisateur">Réalisateur:</label>
                <input type="text" id="realisateur" name="realisateur" value="<?php echo $filmToEdit->realisateur; ?>" required>
                <label for="annee_production">Année de production:</label>
                <input type="text" id="annee_production" name="annee_production" value="<?php echo $filmToEdit->annee_production; ?>" required>
                <label for="langue">Langue:</label>
                <input type="text" id="langue" name="langue" value="<?php echo $filmToEdit->langue; ?>" required>
                <label for="paragraphe">Description:</label>
                <textarea id="paragraphe" name="paragraphe" rows="4" required><?php echo $filmToEdit->paragraphe; ?></textarea>
                <label for="affiche">Affiche:</label>
                <input type="file" id="affiche" name="affiche">
                <?php if ($filmToEdit->affiche) : ?>
                    <img src="<?php echo $filmToEdit->affiche; ?>" alt="Affiche du film">
                <?php endif; ?>
                <button type="submit">Mettre à jour</button>
            </form>
        <?php else : ?>
            <p>Film non trouvé.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>
