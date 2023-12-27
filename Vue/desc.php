<?php
// Vérification si la méthode POST est utilisée pour envoyer des données
// Connexion à la base de données
$serveur = 'localhost'; 
$utilisateur_db = 'root'; 
$mot_de_passe_db = 'bddisep19'; 
$nom_base_de_donnees = 'cinebar'; 

try {
    // Connexion à la base de données via PDO
    $connexion = new PDO("mysql:host=$serveur;dbname=$nom_base_de_donnees", $utilisateur_db, $mot_de_passe_db);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérez l'ID du film depuis l'URL
    $event_id = isset($_GET['id_E']) ? $_GET['id_E'] : null;

    // Vérifiez si l'ID du film est défini
    if ($event_id !== null) {
        // Échappez l'ID pour éviter les attaques par injection SQL
        $event_id = $connexion->quote($event_id);

        // Récupérez les détails du film de la base de données
        $result = $connexion->query("SELECT * FROM events WHERE id_E = $event_id");
        $row = $result->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "ID du film non spécifié.";
        exit; // Arrête l'exécution si l'ID n'est pas spécifié
    }

} catch (PDOException $e) {
    // En cas d'erreur de connexion ou d'exécution de requête
    echo "Erreur : " . $e->getMessage();
    exit; // Arrête l'exécution en cas d'erreur
}
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/desc.css">
    <link rel="stylesheet" href="../style/billet-events.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope&family=Montserrat&display=swap" rel="stylesheet">
</head>

<body>

    <header>
    <nav>
            <img src="../images/logo-cinebar.png" alt="Logo Cinébar">
            <div class="pages">
                <a href="accueil.php">Accueil</a>
                <a href="cinema.php">Le Cinéma</a>
                <a href="cafet.php">La Cafétéria</a>
                <a href="films.php">Films</a>
                <a href="events.php">Évènements</a>
                <a href="billet.php">Billetterie</a>
                <a href="forum.php">Forum</a>
            </div>
            <div class="bouton-access">
                <form class="container" action="recherche.php" method="POST">
                    <input type="text" placeholder="Rechercher..." name="recherche">
                    <div class="search"></div>
                </form>

                <div class="bouton-pro">
                    <a href="pro.php">Réservation de salles</a>
                </div>

                <?php

                session_start();

                // Vérifiez si l'utilisateur est connecté en vérifiant la présence de la variable de session
                $estConnecte = isset($_SESSION['identifiant']);

                // Sélectionnez le bouton de connexion en PHP
                $boutonConnexion = '<div class="bouton-co">';
                if ($estConnecte) {
                    $identif = $_SESSION['identifiant'];
                    $boutonConnexion .= '<a href="profil.php"><svg xmlns="http://www.w3.org/2000/svg" height="16" width="14" 
                    viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - 
                    https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.-->
                    <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg> '
                      . $identif . ' </a>';
                    $boutonConnexion .= '<div class="menu-deroulant">';
                    $boutonConnexion .= '<a href="../Contrôleur/deconnexion.php">Se déconnecter</a>';
                    $boutonConnexion .= '</div>';
                } else {
                    // Si non connecté, affichez le bouton de connexion normal
                    $boutonConnexion .= '<a href="connexion.php">Connexion</a>';
                }
                $boutonConnexion .= '</div>';

                // Affichez le bouton de connexion généré
                echo $boutonConnexion;
                ?>

            </div>
        </nav>
    </header>

    <main>
        <section>
            <div class="container-films">
                <img src="<?= htmlspecialchars($row['affiche'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($row['nom'], ENT_QUOTES, 'UTF-8'); ?>" width="200" height="300">
                <div class="info">
                    <h1><?= htmlspecialchars($row['nom'], ENT_QUOTES, 'UTF-8'); ?></h1>
                    <div class="real"><h3>De :</h3> <?= htmlspecialchars($row['organisateur'], ENT_QUOTES, 'UTF-8'); ?></div>
                    <p><?= htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8'); ?></p>

                    <h2>Note du public :</h2>
                        <div class="rating">
                            <input type="radio" id="star5" name="rating" value="5">
                            <label for="star5"></label>
                            <input type="radio" id="star4" name="rating" value="4">
                            <label for="star4"></label>
                            <input type="radio" id="star3" name="rating" value="3">
                            <label for="star3"></label>
                            <input type="radio" id="star2" name="rating" value="2">
                            <label for="star2"></label>
                            <input type="radio" id="star1" name="rating" value="1">
                            <label for="star1"></label>
                    </div>
                </div>
            </div>
        </section>

        <section class='form_billet'>
            <form action="traitement_billet.php" method="post" class="reserv-billet">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom">
                <label for="nom">Prénom :</label>
                <input type="text" id="prenom" name="prenom">
                <label for="mail">E-mail :</label>
                <input type="mail" id="mail" name="mail">
                <label> Évènement :</label>
                <select id="movie" name="film">
                    <option value="Oppenheimer">Oppenheimer</option>
                    <option value="Indiana Jones">Indiana Jones</option>
                    <option value="Avatar">Avatar</option>
                    <option value="Napoléon">Napoléon</option>
                </select>
                <label for="places">Nombre de places :</label>
                <input type="places" id="places" name="places">
                <label for="date">Date :</label>
                <input type="date" id="date" name="date">
                <label for="horaire">Horaire :</label>
                <select id="horaire" name="horaire">
                    <option value="13h45">13h45</option>
                    <option value="17h00">17h00</option>
                    <option value="19h30">19h30</option>
                </select>
                <p>
                    <button name="send" type="submit">Réserver</button>
                </p>
                <p>
                    <button type="reset">Annuler</button>
                </p>
            </form>
        </section>
    </main>

    <footer>
        <section class='logo-adresse'>
            <img src="../images/logo-cinebar.png" alt="Logo Cinébar" >
            <div>
                <h3>Adresse :</h3>
                8 Prom. Coeur de Ville<br>
                <a>92130- Issy-les-Moulineaux</a>
            </div>
        </section>
        <div class="donnees">
            <a href="cookies.php">Gestion des cookies</a> - 
            <a href="cgu.php">CGU</a> - 
            <a href="faq.php">FAQ</a>
        </div>  
    </footer>

</body>
</html>