<!DOCTYPE html>

<?php 
session_start();
if(!isset($_SESSION["droit"]))
{ 
$_SESSION["droit"]=-1;
};
?>



<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sportiton</title>
    <link rel="shortcut icon" type="image/png" href="/image/logo.png" />
    <link rel="stylesheet" href="styles/index.css" />

    <style>
      <?php include 'styles/index.css'; ?>

    </style>

 
 
</head>

  <body>
    <div id="barre">
      <div id="logo"> </div>

      <div class="searchbar"> 
    <form method="get" action="recherche.php">
    <input type="text" name="q" id ="search" placeholder="Rechercher une recette">
    </form>
    </div>
   

  <div id=formulaire>
    <button>Ajouter une recette</button>
  </div>

  <div id="profil">
    <!-- Menu déroulant -->
      <ul class="navbar">
        <li id="li1"><a href="#"><img id ="img_profil" src="image/profil.png" alt="Profil"> </a>
            <ul>
                <?php  if ($_SESSION["droit"]==-1)
                {echo '
                <li><a href="login.php">Se connecter</a></li> 
                <li><a href="inscription.php">Créer un compte</a></li> ';}
                ?>
                <?php  if ($_SESSION["droit"]!==-1)
                {echo '
                <li><a href="profil.php">Mon Profil</a></li> 
                <li><a href="deconnexion.php">Déconnexion</a></li> ';}
                ?>
            </ul>
        </li>
      </ul>
    </div>              
             
    </div>

    <a href="test_commentaire.php">commentaire</a>
    
  </body>
</html>
