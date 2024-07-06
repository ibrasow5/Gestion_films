<?php include 'header.php'; ?>
<div class="container">
    <h2>Ajouter un film</h2>
    <form action="manage_films.php" method="post">
        <div>
            <label>Titre</label>
            <input type="text" name="titre" required>
        </div>
        <div>
            <label>Genre</label>
            <input type="text" name="genre" required>
        </div>
        <div>
            <label>Réalisateur</label>
            <input type="text" name="realisateur" required>
        </div>
        <div>
            <label>Durée</label>
            <input type="text" name="duree" required>
        </div>
        <div>
            <label>Année de production</label>
            <input type="text" name="annee_production" required>
        </div>
        <div>
            <label>Langue</label>
            <input type="text" name="langue" required>
        </div>
        <div>
            <label>Résumé</label>
            <textarea name="paragraphe" required></textarea>
        </div>
        <div>
            <input type="submit" name="add" value="Ajouter">
        </div>
    </form>
</div>
<?php include 'footer.php'; ?>
