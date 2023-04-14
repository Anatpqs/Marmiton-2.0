

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sportiton</title>
    <link rel="stylesheet" type="text/css" href="login.css">
  </head>
  <body>
    <div class="frame">
      <div class="avatar"></div>
      <form method="post">
        <input type="text" name="username" id="username" placeholder="Identifiant">
        <br>
        <input type="password" name="password" id="password" placeholder="Mot de passe">
        <br>
        <button type="submit" name="connexion">Connexion</button>
        <button type="submit" name="Accueil" >Accueil</button>
      </form>
    </div>
  </body>
</html>


<?php
session_start();
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
