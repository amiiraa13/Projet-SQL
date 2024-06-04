<?php
    // Ouvre une session
    session_start();

    // Vérifie si l'utilisateur est connecté
    if(!isset($_SESSION["username"])){
        // Redirige vers la page de connexion
        header("Location:login.php");
    }

    // Tableau de données initial
    $data = [
        ["id"=>1, "Nom"=>"Yolo", "Prénom"=>"Swag", "Mail"=>"yolo@swag.fr", "Code Postal"=>"13006"],
        ["id"=>2, "Nom"=>"Yala", "Prénom"=>"Swog", "Mail"=>"yala@swog.com", "Code Postal"=>"06000"],
        ["id"=>3, "Nom"=>"Yili", "Prénom"=>"Swog", "Mail"=>"yala@swog.com", "Code Postal"=>"06000"],
        ["id"=>4, "Nom"=>"Yulu", "Prénom"=>"Swog", "Mail"=>"yala@swog.com", "Code Postal"=>"06000"],
        ["id"=>5, "Nom"=>"Yyly", "Prénom"=>"Swog", "Mail"=>"yala@swog.com", "Code Postal"=>"06000"],
    ];  

    // Initialise le tableau de données dans la session s'il n'existe pas
    if(!isset($_SESSION["data"])){
        $_SESSION["data"] = $data;
    }

    // Traite les actions de formulaire
    if(isset($_POST["confirm"])){
        // Met à jour une ligne de données
        $_SESSION["data"][$_POST["confirm"]] = [
            "id" => $_SESSION["data"][$_POST["confirm"]]["id"],
            "Nom" => $_POST["nom"],
            "Prénom" => $_POST["prenom"],
            "Mail" => $_POST["mail"],
            "Code Postal" => $_POST["codePostal"]
        ];
    } elseif(isset($_POST["delete"])){
        // Supprime une ligne de données
        unset($_SESSION["data"][$_POST["delete"]]);
    } elseif(isset($_POST["disconnect"])){
        // Détruit la session et redirige vers la page de connexion
        session_destroy();
        header("Location:login.php");

    } else if(isset($_POST["add_new"])){ 
        
        $newNom = $_POST["new_nom"];
        $newPrenom = $_POST["new_prenom"];
        $newMail = $_POST["new_mail"];
        $newCodePostal = $_POST["new_codePostal"];

        
        $newId = count($_SESSION["data"]) + 1;
        
        array_push($_SESSION["data"], [
            "id" => $newId,
            "Nom" => $newNom,
            "Prénom" => $newPrenom,
            "Mail" => $newMail,
            "Code Postal" => $newCodePostal
        ]);
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
        <form method="post">
            <!-- Bouton de déconnexion -->
            <button name="disconnect">Déconnexion</button>
            
            <!-- Tableau pour afficher les données -->
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Mail</th>
                    <th>Code Postal</th>
                    <th>Actions</th>
                </tr>
                <!-- Boucle pour parcourir le tableau de données -->
                <?php foreach($_SESSION["data"] as $index => $value){ ?>
                    <tr>
                        <!-- Vérifie si le bouton "Modifier" est cliqué -->
                        <?php if(isset($_POST["update"]) && $_POST["update"]==$value["id"]){ ?>
                            <!-- Transforme les données en champs d'entrée -->
                            <td><input name="nom" value="<?php echo $value["Nom"] ?>" /></td>
                            <td><input name="prenom" value="<?= $value["Prénom"] ?>"/></td>
                            <td><input name="mail" value="<?= $value["Mail"] ?>"/></td>
                            <td><input name="codePostal" value="<?= $value["Code Postal"] ?>"/></td>
                            <td>
                                <!-- Bouton "Confirmer" pour la mise à jour -->
                                <button name="confirm" value="<?php echo $index ?>">Confirmer</button>
                        <!-- Si le bouton "Modifier" n'est pas cliqué, affiche les données -->
                        <?php } else { ?>
                            <td><?= $value["Nom"] ?></td>
                            <td><?= $value["Prénom"] ?></td>
                            <td><?= $value["Mail"] ?></td>
                            <td><?= $value["Code Postal"] ?></td>
                            <td>
                                <!-- Bouton "Modifier" pour éditer les données -->
                                <button name="update" value="<?php echo $value["id"] ?>">Modifier</button>
                        <?php } ?>
                            <!-- Bouton "Supprimer" pour supprimer une entrée -->
                            <button name="delete" value="<?php echo $index ?>">Supprimer</button>
                        </td>
                    </tr>
                <?php } ?>
                <!-- Ajouter une nouvelle ligne avec des champs vides pour entrer de nouvelles données -->
                <tr>
                    <td><input name="new_nom" placeholder="Nom" /></td>
                    <td><input name="new_prenom" placeholder="Prénom" /></td>
                    <td><input name="new_mail" placeholder="Mail" /></td>
                    <td><input name="new_codePostal" placeholder="Code Postal" /></td>
                    <td><button name="add_new">Ajouter</button></td>
                </tr>
            </table>
        </form>
    </body>
</html>