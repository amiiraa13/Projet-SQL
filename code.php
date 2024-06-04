<?php
    session_start();

    if(!isset($_SESSION["username"])){ // Si on n'est pas un utilisateur connecté (cf login.php)
        header("Location:login.php"); // Redirige vers la page login
        exit(); // On arrête de lire le code, on s'en va directement
    }

    if(isset($_POST["disconnect"])){ // Si on clique sur "déconnexion"
        session_destroy(); // Détruit la session
        header("Location:login.php"); // Redirige vers login
    }

    try{ // Bloc contenant le code à "essayer" (vérifier qu'il ne se produise pas d'erreur)
        $db = new PDO("mysql:host=localhost;dbname=TD SQL","root","root"); // Connexion à la base de données
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Active la gestion des erreurs issues de PDO
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Définit les fetch pour retourner des tableaux associatifs par défaut
        $db->beginTransaction(); // Sauvegarde en base de données

        if(isset($_POST["delete"])){ // Si j'ai cliqué sur le bouton supprimer
            $deleteRequest= "DELETE FROM user WHERE Id=" . $_POST["delete"]; // Requête SQL de suppression, on a stocké l'id dans le bouton delete (cf value du bouton)
            $db->query($deleteRequest); // Exécute la requête
        }
        else if(isset($_POST["confirm"])){

            // $_SESSION["data"][$_POST["confirm"]] = [
            //     // 
            //     "id" => $_SESSION["data"][$_POST["confirm"]]["id"],
            //     "Nom" => $_POST["nom"],
            //     "Prénom" => $_POST["prenom"],
            //     "Mail" => $_POST["mail"],
            //     "Code Postal" => $_POST["codePostal"]
            // ];
            $modifyRequest="UPDATE user SET Nom='$_POST[nom]', Prenom='$_POST[prenom]', Mail='$_POST[mail]', Code_postal='$_POST[codePostal]' WHERE id=" .$_POST["confirm"] ;
            $db->query($modifyRequest);
        }
        else if(isset($_POST["add"])){
            $addRequest="INSERT INTO user (Nom, Prenom, Mail, Code_postal) VALUES ('$_POST[nom]', '$_POST[prenom]', '$_POST[mail]', '$_POST[codePostal]')";
            $db->query($addRequest);
        }

        $requeteSQL = "SELECT * FROM user"; // Requête SQL
        $dataDb = $db->query($requeteSQL)->fetchAll(); // Query permet d'exécuter la requête en base de données (l'éclair de workbench), fetchAll permet de convertir le résultat de la requête en tableau associatif

        $db->commit(); // Valide les changements en base de données
    }
    catch(PDOException $e){ // Si une erreur est renvoyée dans le try, rentre dans le catch avec l'exception générée par php en paramètre
        $db->rollBack(); // Annule les changements en base de données
        echo $e->getMessage(); // Affiche le message d'erreur
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./assets/style.css">
        <title>Document</title>
    </head>
    <body>
        <form method="POST">
            
            <input name="nom" placeholder="Nom"/>
            <input name="prenom" placeholder="Prenom"/>
            <input name="mail" placeholder="Mail"/>
            <input name="codePostal" placeholder="codepostal"/>
            <button name="add">Ajouter</button>

        </form>
        <form method="post">
            <button name="disconnect">Déconnexion</button>
            
            <table>
                <tr>
                    <th>Id</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Mail</th>
                    <th>Code Postal</th>
                    <th>Actions</th>
                </tr>
                <!--  -->
                <?php foreach($dataDb as $index => $value){ ?>
                    <tr>
                        <!--  -->
                        <?php if(isset($_POST["update"]) && $_POST["update"]==$value["Id"]){ ?>
                            <!--  -->
                            <td><input name="id" value="<?php echo $value["Id"] ?>" /></td>
                            <td><input name="nom" value="<?php echo $value["Nom"] ?>" /></td>
                            <td><input name="prenom" value="<?= $value["Prenom"] ?>"/></td>
                            <td><input name="mail" value="<?= $value["Mail"] ?>"/></td>
                            <td><input name="codePostal" value="<?= $value["Code_postal"] ?>"/></td>
                            <td>
                                <!--  -->
                                <button name="confirm" value="<?php echo $value["Id"] ?>">Confirmer</button>
                        <!--  -->
                        <?php } else { ?>
                            <!--  -->
                            <td><?php echo $value["Id"] ?></td>
                            <td><?= $value["Nom"] ?></td>
                            <td><?= $value["Prenom"] ?></td>
                            <td><?= $value["Mail"] ?></td>
                            <td><?= $value["Code_postal"] ?></td>
                            <td>
                                <!--  -->
                                <button name="update" value="<?php echo $value["Id"] ?>">Modifier</button>
                        <!--  -->
                        <?php } ?>
                            <!--  -->
                            <button name="delete" value="<?php echo $value["Id"] ?>">Supprimer</button>
                        </td>
                    </tr>
                <!--  -->
                <?php } ?>
            </table>
        </form>
    </body>
</html>