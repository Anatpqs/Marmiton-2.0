<!DOCTYPE html>

<?php
include 'database.php';
global $db;

// Démarrage de la session utilisateur
session_start();
if (!isset($_SESSION["droit"])) {
    $_SESSION["droit"] = -1;
};

//--------------------------------------------------------------------------------------------------------------------//

// Requête SQL pour récupérer deux recettes aléatoires
$req_sql = $db->prepare('SELECT * FROM Recette WHERE État!=0 ORDER BY RAND() LIMIT 2');
$req_sql->execute();
// Récupération des résultats de la requête et stockage dans des variables
$recette1 = $req_sql->fetch();
$recette2 = $req_sql->fetch();

//--------------------------------------------------------------------------------------------------------------------//

// Requête SQl pour récupérer la recette du moment (recette ayant la meilleure moyenne)
$req_sql_RecetteDuMoment = $db->prepare('SELECT * FROM Recette WHERE État!=0 AND Notemoy = (SELECT MAX(Notemoy) FROM Recette)');
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
    <link rel="stylesheet" href="styles/accueil.css" />
</head>

<body>
    <div id="barre">
        <hr id="bb_top">
        <img id="logo" src="Images/cooking.png">
        <form method="post" action="Recherche.php" id="formsearchbar">
            <input type="search" id="searchbar" name="searchbar" placeholder="Recette..." autocomplete="off">
        </form>
        <a href="AjoutRecette.php"> <img class="icon" src="Images/chiefblanc.png"> </a>
        
        <!-- Menu déroulant -->
        <div class="profil">
            <ul class="navbar">
                <li class="li"><img class="icon" src="Images/userblanc.png">
                    <ul>
                        <?php if ($_SESSION["droit"] == -1) {
                            echo '
                  <li><a href="login.php">Se connecter</a></li> 
                  <li><a href="inscription.php">Créer un compte</a></li> ';
                        } // Si l'utilisateur n'est pas connecté, on affiche les liens de connexion et d'inscription
                        ?>
                        <?php if ($_SESSION["droit"] == 0 || $_SESSION["droit"] == -2 ) {
                            echo '
                  <li><a href="profil.php">Mon Profil</a></li> 
                  <li><a href="deconnexion.php">Déconnexion</a></li> ';
                        } // Si l'utilisateur est connecté, on affiche les liens de profil et de déconnexion
                        ?>
                        <?php if ($_SESSION["droit"] == 1) {
                            echo '<li><a href="admin.php">Admin</a></li>
                            <li><a href="deconnexion.php">Déconnexion</a></li>'; //Admin
                        }
                        ?>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Fin menu déroulant -->
        <br>

        <!-- Liens vers différentes catégories de recettes -->
        <!--// TODO Redirection vers une sélection de pages -->
        <a class="textlink">Meal Prep</a>
        <a class="textlink">Cuisiner pas cher</a>
        <a class="textlink">Prise de masse</a>
        <a class="textlink">Sèche</a>
        <hr id="bb_bottom">
    </div>

    <div class="corps">

        <!-- Section "Recette du jour" -->
        <div class="recette_du_jour">
            <div class="recette_du_jour affichage">
                <!-- Affichage de la première recette du jour -->
                <h3><?php echo $recette1['Nom']; ?></h3>
                <!-- Lien vers la recette -->
                <form id="myForm" action="recette.php" method="post">
                    <input type="hidden" name="idRecette" value="<?php echo $recette1["IdRecette"]?>">
                </form>
                <a href="#" onclick="document.getElementById('myForm').submit();"><img
                        src=" Images/Recette/<?php echo $recette1['IdRecette']; ?>.jpg"> </a>


                <p><?php echo $recette1['Notemoy']; ?></p>
            </div>

            <div class="recette_du_jour affichage">
                <!-- Affichage de la deuxième recette du jour -->
                <h3><?php echo $recette2['Nom']; ?></h3>
                <!-- Lien vers la recette -->
                <form id="myForm2" action="recette.php" method="post">
                    <input type="hidden" name="idRecette" value="<?php echo $recette2["IdRecette"]?>">
                </form>
                <a href="#" onclick="document.getElementById('myForm2').submit();"><img
                        src="Images/Recette/<?php echo $recette2['IdRecette']; ?>.jpg"> </a>

                <p><?php echo $recette2['Notemoy']; ?></p>
            </div>
        </div>

        <!-- Section "Recette du moment" -->
        <div class="recette_du_moment">
            <div class="recette_du_moment affichage">
                <h2><u>Recette du moment !</u></h2>
                <h3><?php echo $RecetteDuMoment['Nom']; ?></h3>
                <!-- Lien vers la recette -->
                <form id="myForm3" action="recette.php" method="post">
                    <input type="hidden" name="idRecette" value="<?php echo $RecetteDuMoment["IdRecette"]?>">
                </form>
                <a href="#" onclick="document.getElementById('myForm3').submit();"><img class="image"
                        src="Images/Recette/<?php echo $RecetteDuMoment['IdRecette']; ?>.jpg"> </a>


                <p><?php echo $RecetteDuMoment['Description']; ?></p>
            </div>
        </div>

    </div>
</body>

<footer>
</footer>

</html>
