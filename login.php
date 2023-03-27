<!DOCTYPE html>

<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sportiton</title>
    <link rel="shortcut icon" type="image/png" href="/image/logo.png" />
   
  </head>

  <body>
    <form method="post">
        <label>Identifiant</label><input type="text" name="username" id="username">
        <br>
        <label>Mot de passe</label><input type="password" name="password" id="password">
        <br>
        <button type="submit" name="connexion">Connexion</button>
    </form>
  </body>
</html>


<?php
session_start();

if(isset($_POST["connexion"]))
{
    $username=$_POST["username"];
    $password=$_POST["password"];

    
    include 'database.php';
    global $db;
    // Vérification de la connexion
    if (!$db) {
        die("Connexion échouée: " . mysqli_connect_error());
    }
    
    $sql = $db->prepare("SELECT * FROM Utilisateur WHERE Login = :Login");
    $sql->execute(["Login"=>$username]);
    $resultat=$sql->fetch(PDO::FETCH_ASSOC);
   
    if($sql->rowCount()==1) {
    
       if (password_verify($password,$resultat["Mdp"]))
      { 
        $_SESSION["droit"] = $resultat["Droit"];
        $_SESSION["Login"]= $resultat["Login"];
        header("Location:index.php");  
      }
    
    else {
        // Les informations d'identification sont invalides, afficher un message d'erreur
        echo("erreur");
    }

  
  }   
}

?>
