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
    $film_id = isset($_GET['id_FE']) ? $_GET['id_FE'] : null;

    // Vérifiez si l'ID du film est défini
    if ($film_id !== null) {
        // Échappez l'ID pour éviter les attaques par injection SQL
        $film_id = $connexion->quote($film_id);

        // Récupérez les détails du film de la base de données
        $result = $connexion->query("SELECT * FROM film_event WHERE id_FE = $film_id");
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
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/details.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope&family=Montserrat&display=swap" rel="stylesheet">
</head>

<body>

    <header>
        <nav>
            <img src="images/logo-cinebar.png" alt="Logo Cinébar" >
            <div class="pages">
                <a href="cinema.html">Le Cinéma</a>
                <a href="rooftop.html">Le Rooftop</a>
                <a href="films.html">Films</a>
                <a href="events.html">Évènements</a>
                <a href="billet.html">Billetterie</a>
                <a href="forum.html">Forum</a>
            </div>
            <div class="bouton-access">
                <div class="bouton-pro">
                    <a href="pro.html">Professionnel</a>
                </div>
                <div class="bouton-co">
                    <a href="connexion.php">Connexion</a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <h1><?= htmlspecialchars($row['nom'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <img src="<?= htmlspecialchars($row['affiche'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($row['nom'], ENT_QUOTES, 'UTF-8'); ?>" width="200" height="300">
            <h2><?= htmlspecialchars($row['genre'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <p><?= htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8'); ?></p>
            <div>Date de sortie: <?= htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8'); ?></div>
            <div>Durée: <?= htmlspecialchars($row['duree'], ENT_QUOTES, 'UTF-8'); ?> minutes</div>
            <div>Réalisateur: <?= htmlspecialchars($row['realisateur'], ENT_QUOTES, 'UTF-8'); ?></div>
            <div>Acteurs: <?= htmlspecialchars($row['acteurs'], ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
    </main>

    <footer>
        <section class='logo-adresse'>
            <img src="images/logo-cinebar.png" alt="Logo Cinébar" >
            <div>
                <h3>Adresse :</h3>
                Place Darcy <br>
                <a>21000 - Dijon</a>
            </div>
        </section>
        <div class="donnees">
            <a href="cookies.html">Gestion des cookies</a> - 
            <a href="cgu.html">CGU</a> - 
            <a href="faq.html">FAQ</a>
        </div>  
    </footer>

</body>
</html>