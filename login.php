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

    
    $connexion = mysqli_connect('localhost', 'root','', 'mydb','3308');
    // Vérification de la connexion
    if (!$connexion) {
        die("Connexion échouée: " . mysqli_connect_error());
    }

    $username = mysqli_real_escape_string($connexion, $username);
    $password = mysqli_real_escape_string($connexion, $password);
    
    $sql = "SELECT * FROM Utilisateur WHERE Login = '$username'";


    $resultat = mysqli_query($connexion, $sql);
    if(mysqli_num_rows($resultat)==1) {
    
    $row= mysqli_fetch_assoc($resultat);
       if (password_verify($password,$row["Mdp"]))
      { 
        $_SESSION["droit"] = $row["Droit"];
        header("Location:index.php");  
      }
    
    else {
        // Les informations d'identification sont invalides, afficher un message d'erreur
        echo("erreur");
    }

    // Fermer la connexion à la base de données
    mysqli_close($connexion);
    
  }   
}

?>
