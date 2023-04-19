

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sportiton</title>
    <link rel="stylesheet" type="text/css" href="styles/login.css">
  </head>
  <body>
    <div class="login-container">
      <img src="Images/avatar.png" alt="avatar image">
    <form method="post">
        <label>Identifiant</label><input type="text" name="username" id="username">
        <br>
        <label>Mot de passe</label><input type="password" name="password" id="password">
        <br>
        <button type="submit" name="connexion">Connexion</button>
        <button type="submit" name="Accueil" >Accueil</button>
    </form>
  </body>
</html>

<?php
session_start();
if ($_SESSION["droit"] !=-1) {
  header("Location:accueil.php");
}
  
if (isset($_POST["Accueil"]))
{
  header("Location:accueil.php");
}


if(isset($_POST["connexion"])){
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
      if (password_verify($password,$resultat["Mdp"])){
        $_SESSION["droit"] = $resultat["Droit"];
        $_SESSION["Login"]= $resultat["Login"];
        $_SESSION["id"]= $resultat["IdUtilisateur"];
        $_SESSION["pseudo"]=$resultat["Pseudo"];
        header("Location:accueil.php");
      }
      else{
        // Les informations d'identification sont invalides, afficher un message d'erreur
        echo "Mauvais mot de passe";
    }
  }
  else{
    echo "Pas d'utilisateur avec ce login";
  }
}


?>
