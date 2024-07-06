<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portail Cinéma</title>
    <style>
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        header h1 a {
            margin: 0;
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            display: inline-block;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            text-align: center;
            background-color: #444;
        }

        nav ul li {
            display: inline;
            margin-right: 15px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            display: inline-block;
        }

        nav ul li a:hover {
            background-color: #FF0000;
        }

        footer {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2, h3 {
            color: #333;
        }

        .film-container {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .film-container h3 {
            margin-top: 0;
        }

        .film-affiche {
            max-width: 150px;
            margin-right: 20px;
            position: relative;
        }

        .film-details {
            flex-grow: 1;
        }

        .film-details h3 {
            margin-top: 0;
            color: #333;
        }

        .film-details p {
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <h1><a href="/Gestion_films/views/index.php">Portail Cinéma</a></h1>
    </header>
    
