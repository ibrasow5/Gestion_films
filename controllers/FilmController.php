<?php
require_once '../config/config.php';

class FilmController {
    public function getFilms() {
        $xml = simplexml_load_file('../exo2.xml');
        return $xml->liste_films->film;
    }

    public function addFilm($filmData) {
        $xml = simplexml_load_file('../exo2.xml');
        $film = $xml->liste_films->addChild('film');
        $film->addChild('titre', $filmData['titre']);
        $film->addChild('genre', $filmData['genre']);
        $film->addChild('realisateur', $filmData['realisateur']);
        $film->addChild('duree', $filmData['duree']);
        $film->addChild('annee_production', $filmData['annee_production']);
        $film->addChild('langue', $filmData['langue']);
        $film->addChild('paragraphe', $filmData['paragraphe']);
        $xml->asXML('../exo2.xml');
    }

    public function deleteFilm($titre) {
        $xml = simplexml_load_file('../exo2.xml');
        foreach ($xml->liste_films->film as $film) {
            if ($film->titre == $titre) {
                $dom = dom_import_simplexml($film);
                $dom->parentNode->removeChild($dom);
            }
        }
        $xml->asXML('../exo2.xml');
    }

    public function updateFilm($oldTitle, $filmData) {
        $xml = simplexml_load_file('../exo2.xml');
        foreach ($xml->liste_films->film as $film) {
            if ($film->titre == $oldTitle) {
                $film->titre = $filmData['titre'];
                $film->genre = $filmData['genre'];
                $film->realisateur = $filmData['realisateur'];
                $film->duree = $filmData['duree'];
                $film->annee_production = $filmData['annee_production'];
                $film->langue = $filmData['langue'];
                $film->paragraphe = $filmData['paragraphe'];
            }
        }
        $xml->asXML('../exo2.xml');
    }
}
?>
<?php
require_once '../config/config.php';

class FilmController {
    public function getFilms() {
        $xml = simplexml_load_file('../exo2.xml');
        return $xml->liste_films->film;
    }

    public function addFilm($filmData) {
        $xml = simplexml_load_file('../exo2.xml');
        $film = $xml->liste_films->addChild('film');
        $film->addChild('titre', $filmData['titre']);
        $film->addChild('genre', $filmData['genre']);
        $film->addChild('realisateur', $filmData['realisateur']);
        $film->addChild('duree', $filmData['duree']);
        $film->addChild('annee_production', $filmData['annee_production']);
        $film->addChild('langue', $filmData['langue']);
        $film->addChild('paragraphe', $filmData['paragraphe']);
        $xml->asXML('../exo2.xml');
    }

    public function deleteFilm($titre) {
        $xml = simplexml_load_file('../exo2.xml');
        foreach ($xml->liste_films->film as $film) {
            if ($film->titre == $titre) {
                $dom = dom_import_simplexml($film);
                $dom->parentNode->removeChild($dom);
            }
        }
        $xml->asXML('../exo2.xml');
    }

    public function updateFilm($oldTitle, $filmData) {
        $xml = simplexml_load_file('../exo2.xml');
        foreach ($xml->liste_films->film as $film) {
            if ($film->titre == $oldTitle) {
                $film->titre = $filmData['titre'];
                $film->genre = $filmData['genre'];
                $film->realisateur = $filmData['realisateur'];
                $film->duree = $filmData['duree'];
                $film->annee_production = $filmData['annee_production'];
                $film->langue = $filmData['langue'];
                $film->paragraphe = $filmData['paragraphe'];
            }
        }
        $xml->asXML('../exo2.xml');
    }
}
?>
