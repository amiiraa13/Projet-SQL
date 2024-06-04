<?php
    // Démarrage de la session
    session_start();

    // Variable pour stocker les erreurs
    $error="";

    // Vérification de la soumission du formulaire
    if(isset($_POST["username"])){
        // Vérification des regex pour le nom d'utilisateur et le mot de passe
        if(preg_match("/^[A-z]*$/",$_POST["username"]) && preg_match("/^(?![\d-])[a-zA-Z0-9-]{3,16}$/",$_POST["password"])){
            // Si les identifiants sont valides, rediriger vers la page index.php
            $_SESSION["username"] = $_POST["username"];
            header("Location:index.php");
            // Arrêter l'exécution du script pour éviter toute sortie indésirable
            exit();
        }
        // Si les identifiants ne sont pas valides
        else{
            $error = "Nom d'utilisateur ou mot de passe incorrect";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Formulaire de connexion -->
    <form method="post">
        <input type="text" name="username" placeholder="Votre nom"/>
        <input type="text" name="password" placeholder="Votre mot de passe"/>
        <button>Se connecter</button>
    </form>
    <!-- Affichage de l'erreur si elle n'est pas vide -->
    <?= $error !== "" ? $error : null ?>
</body>
</html>