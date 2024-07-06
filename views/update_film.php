<?php
require_once '../controllers/FilmController.php';
$filmController = new FilmController();
$films = $filmController->getFilms();

$filmToUpdate = null;
foreach ($films as $film) {
    if ($film->titre == $_GET['titre']) {
        $filmToUpdate = $film;
        break;
    }
}
?>

<?php include 'header.php'; ?>
<div class="container">
    <h2>Modifier le film</h2>
    <form action="manage_films.php" method="post">
        <input type="hidden" name="oldTitle" value="<?php echo $filmToUpdate->titre; ?>">
        <div>
            <label>Titre</label>
            <input type="text" name="titre" value="<?php echo $filmToUpdate->titre; ?>" required>
        </div>
        <div>
            <label>Genre</label>
            <input type="text" name="genre" value="<?php echo $filmToUpdate->genre; ?>" required>
        </div>
        <div>
            <label>Réalisateur</label>
            <input type="text" name="realisateur" value="<?php echo $filmToUpdate->realisateur; ?>" required>
        </div>
        <div>
            <label>Durée</label>
            <input type="text" name="duree" value="<?php echo $filmToUpdate->duree; ?>" required>
        </div>
        <div>
            <label>Année de production</label>
            <input type="text" name="annee_production" value="<?php echo $filmToUpdate->annee_production; ?>" required>
        </div>
        <div>
            <label>Langue</label>
            <input type="text" name="langue" value="<?php echo $filmToUpdate->langue; ?>" required>
        </div>
        <div>
            <label>Résumé</label>
            <textarea name="paragraphe" required><?php echo $filmToUpdate->paragraphe; ?></textarea>
        </div>
        <div>
            <input type="submit" name="update" value="Modifier">
        </div>
    </form>
</div>
<?php include 'footer.php'; ?>
