<?php

// Démarrer la session
session_start();

// Vérification si la méthode POST est utilisée pour envoyer des données
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des informations du formulaire
    $identifiant = $_POST["identifiant"];
    $password = $_POST["mdp"];

    // Connexion à la base de données
    $serveur = 'localhost'; 
    $utilisateur_db = 'root'; 
    $mot_de_passe_db = 'bddisep19'; 
    $nom_base_de_donnees = 'cinebar'; 

    try {
        // Connexion à la base de données via PDO
        $connexion = new PDO("mysql:host=$serveur;dbname=$nom_base_de_donnees", $utilisateur_db, $mot_de_passe_db);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête pour vérifier si l'utilisateur existe bien
        $requete = $connexion->prepare("SELECT * FROM `utilisateur` WHERE (`mail` = :identifiant OR `pseudo` = :identifiant) AND `MotDePasse` = :password");
        $requete->bindParam(':identifiant', $identifiant);
        $requete->bindParam(':password', $password);
        $requete->bindParam(':role', $admin);
        $requete->execute();

        // Vérification si l'utilisateur existe bien 
        $resultat = $requete->fetch(PDO::FETCH_ASSOC);
        if ($resultat) {
            // Stocker des informations dans la session
            // Après la connexion réussie
            $_SESSION['identifiant'] = $resultat['pseudo'];

            // Ajoutez des messages de débogage
            echo "Connexion réussie. Redirection en cours...";
            header("Location: accueil.php");
            exit();
        } else {
            // Sinon on affiche un message d'erreur
            echo "Adresse e-mail, pseudo ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        // En cas d'erreur de connexion ou d'exécution de requête
        echo "Erreur : " . $e->getMessage();
    }
}
?>




<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/connexion.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope&family=Montserrat&display=swap" rel="stylesheet">
</head>

<body>

    <header>
        <nav>
            <img src="images/logo-cinebar.png" alt="Logo Cinébar" >
            <div class="pages">
                <a href="accueil.php">Accueil</a>
                <a href="cinema.html">Le Cinéma</a>
                <a href="rooftop.html">La Cafétéria</a>
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

    <main class='connexion'>
        <form action="connexion.php" method="post" class="co">
            <div class="form-text" id="sugg">
                <p>
                    <label for="identifiant">E-mail ou pseudonyme :</label>
                    <input type="text" id="identifiant" name="identifiant">
                </p>
                <p>
                    <label for="mdp">Mot de passe :</label>
                    <input type="password" id="mdp" name="mdp">
                </p>
            </div>
            <div class="co-bouton">
                <button name="send" type="submit">Se connecter</button>
				<p>Vous n'avez pas de compte ? <a href="inscription.php">S'inscrire</a></p>
            </div>
        </form>
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