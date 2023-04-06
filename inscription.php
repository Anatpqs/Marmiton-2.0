
<!DOCTYPE html> 
<html> 
<head> 
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <title>Hello</title> 
<style type="text/css">
    
</style>
</head> 
<body> 
  <form method="post">
    <input type="text" name="Pseudo" id="Pseudo" placeholder="Entrez votre Pseudo" required><br>
    <input type="text" name="Login" id="Login" placeholder="Entrez votre email" required><br>
    <input type="password" name="Mdp" id="Mdp" placeholder="Entrez votre Mot de passe" required><br>
    <input type="password" name="Mdpverif" id="Mdpverif" placeholder="Confirmez votre Mot de passe" required>
    <input type="submit" name="formsend" id="formsend" value="S'inscrire">
  </form>
  <?php
    session_start();

    if(isset($_POST['formsend'])){
      $pseudo=$_POST['Pseudo'];
      $login=$_POST['Login'];
      $mdp=$_POST['Mdp'];
      $mdpverif=$_POST['Mdpverif'];
      if($mdp==$mdpverif){      
        $options = ['cost' => 12,];
        $hashpass = password_hash($mdp, PASSWORD_BCRYPT, $options);
        //if (password_verify($mdp,$hashpass){echo'c'est bon'}   pour verifier le mot de passe
        include 'database.php';
        global $db;
        $c = $db->prepare("SELECT Login FROM Utilisateur WHERE Login = :Login");
        $c->execute(['Login' => $login]);
        $result = $c->rowCount();
        if ($result==0){
          $q = $db->prepare("INSERT INTO Utilisateur(Pseudo,Login,Mdp,Droit) VALUES(:Pseudo,:Login,:Mdp,:Droit)");
          $q->execute([
            'Pseudo' => $pseudo,
            'Login' => $login,
            'Mdp' => $hashpass,
            'Droit' => '0'
          ]);
          $r = $db->prepare("SELECT IdUtilisateur FROM Utilisateur WHERE Login = :Login");
          $r->execute(['Login' => $login]);
          $resultat = $r->fetch(PDO::FETCH_ASSOC);
          echo 'Le compte a été créée' ;
          $_SESSION["droit"]=0;
          $_SESSION["Login"]= $login;
          $_SESSION["id"]= $resultat['IdUtilisateur'];
          touch("Images/Pdp/" . $resultat['IdUtilisateur'] . ".jpg");
          sleep(1);
          header("Location:accueil.php");
 
        }
        else{
          echo'un email est déja atribué a ce compte';
        }
      }
      else{
        echo'Les mots de passes sont différents';
      }
    }
        
 



  ?>
</body> 
</html> 
