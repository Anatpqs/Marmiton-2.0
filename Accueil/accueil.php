<!DOCTYPE html>

<?php
// Définition des informations de connexion à la base de données
  $servername = "localhost";
  $username = "admin";
  $password = "Admin85$";
  $dbname = "mydb";

  // Connexion à la base de données
  try {
    $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  } catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
  }

  // Démarrage de la session utilisateur
  session_start();
  if(!isset($_SESSION["droit"]))
  { 
  $_SESSION["droit"]=-1;
  };
  
  //--------------------------------------------------------------------------------------------------------------------//
  
  // Requête SQL pour récupérer deux recettes aléatoires
  $req_sql = $bdd->prepare('SELECT * FROM Recette ORDER BY RAND() LIMIT 2');
  $req_sql -> execute();
  // Récupération des résultats de la requête et stockage dans des variables
  $recette1 = $req_sql->fetch();
  $recette2 = $req_sql->fetch();

  //--------------------------------------------------------------------------------------------------------------------//

  // Requête SQl pour récupérer la recette du moment (recette ayant la meilleure moyenne)
  $req_sql_RecetteDuMoment = $bdd->prepare('SELECT * FROM Recette WHERE Notemoy = (SELECT MAX(Notemoy) FROM Recette)');
  $req_sql_RecetteDuMoment->execute();
  //Récupération du résultat de la requête et stockage dans la variable
  $RecetteDuMoment = $req_sql_RecetteDuMoment->fetch();

?>

<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sportiton</title>
    <!-- Icon dans l'onglet du navigateur -->
    <link rel="shortcut icon" href="Images/chiefblanc.png" />
    <!-- Import du fichier CSS --> 
    <link rel="stylesheet" href="accueil.css" /> 
  </head>

  <body>
    <div id="barre">
      <hr id="bb_top">   
      <img id="logo" src="Images/cooking.png">
      <input type="text" id="recherche" placeholder="Recette..." style="margin-right: 0vh">
      <img class="icon" src="Images/chiefblanc.png">
      <!-- Menu déroulant -->
      <div class="profil">
        <ul class="navbar">
          <li class="li"><img class="icon" src="Images/userblanc.png">
              <ul>
                  <?php  if ($_SESSION["droit"]==-1)
                  {echo '
                  <li><a href="login.php">Se connecter</a></li> 
                  <li><a href="inscription.php">Créer un compte</a></li> ';} // Si l'utilisateur n'est pas connecté, on affiche les liens de connexion et d'inscription
                  ?>
                  <?php  if ($_SESSION["droit"]!==-1)
                  {echo '
                  <li><a href="profil.php">Mon Profil</a></li> 
                  <li><a href="deconnexion.php">Déconnexion</a></li> ';} // Si l'utilisateur est connecté, on affiche les liens de profil et de déconnexion
                  ?>
              </ul>
          </li>
        </ul>
      </div>
      <!-- Fin menu déroulant -->
      <br>
      
      <!-- Liens vers différentes catégories de recettes -->
      <u class="utextlink"><a class="textlink">Meal Prep</a></u>
      <u class="utextlink"><a class="textlink">Cuisiner pas cher</a></u>
      <u class="utextlink"><a class="textlink">Prise de masse</a></u>
      <u class="utextlink"><a class="textlink">Sèche</a></u>
      <hr id="bb_bottom">
    </div>

    <div class="corps">

      <!-- Section "Recette du jour" -->
      <div class="recette_du_jour">
        <div class="recette_du_jour affichage">
          <!-- Affichage de la première recette du jour -->
          <h3><?php echo $recette1['Nom']; ?></h3>
          <a href="recette.php?id=<?php echo $recette1['IdRecette']; ?>"> <img src="Images/<?php echo $recette1['IdRecette']; ?>.jpg"> </a>
          <p><?php echo $recette1['Notemoy']; ?></p>         
        </div>

        <div class="recette_du_jour affichage">
          <!-- Affichage de la deuxième recette du jour -->
          <h3><?php echo $recette2['Nom']; ?></h3>
          <a href="recette.php?id=<?php echo $recette2['IdRecette']; ?>"> <img src="Images/<?php echo $recette2['IdRecette']; ?>.jpg"> </a>
          <p><?php echo $recette2['Notemoy']; ?></p>
        </div>
      </div>
      
      <!-- Section "Recette du moment" -->
      <div class="recette_du_moment">
        <div class="recette_du_moment affichage">
          <h2><u>Recette du moment !</u></h2>
          <h3><?php echo $RecetteDuMoment['Nom']; ?></h3>
          <a href="recette.php?id=<?php echo $RecetteDuMoment['IdRecette']; ?>"> <img class="image" src="Images/<?php echo $RecetteDuMoment['IdRecette']; ?>.jpg"> </a>
          <p><?php echo $RecetteDuMoment['Description']; ?></p>
        </div>
      </div>

    </div>
  </body>

  <footer>
  </footer>
  
</html>