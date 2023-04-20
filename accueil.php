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

//Fonction affichage de la note
function notation($note)
{
  switch ($note) {
    case 1:
      return '<img src="Images/1etoile.png" alt="note" class="img_note">';
      break;
    case 2:
      return '<img src="Images/2etoile.png" alt="note" class="img_note">';
      break;
    case 3:
      return '<img src="Images/3etoile.png" alt="note" class="img_note">';
      break;
    case 4:
      return '<img src="Images/4etoile.png" alt="note" class="img_note">';
      break;
    case 5:
      return '<img src="Images/5etoile.png" alt="note" class="img_note">';
      break;
  }
};

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

<header id="barre">
    <hr id="bb_top">
    <img id="logo" src="Images/cooking.png">

    <!-- Barre de recherche pour rechercher des recettes -->
    <form method="post" action="Recherche.php" id="formsearchbar"> 
        <input type="search" id="searchbar" name="searchbar" placeholder="Recette..." autocomplete="off">
    </form>
    <!-- ----------------------------------------------- -->

    <a href="AjoutRecette.php"> <img class="icon" src="Images/chiefblanc.png"> </a>
    
    <!-- Menu déroulant -->
    <div class="profil">
        <ul class="navbar">
            <!-- Affichage de la photo de profil de l'utilisateur -->
            <?php 
                if(filesize("Images/Pdp/" . $_SESSION['id'] . ".jpg")>50){
                    echo"<li class='li'><img id='imagefile' class='image_profil' src='Images/Pdp/",$_SESSION['id'],".jpg'>";
                }
                else{
                    echo"<li class='li'><img id='imagefile'class='icon' src='Images/Pdp/userblanc.png'>";    
                }
            ?>
                <ul>
                    <?php if ($_SESSION["droit"] == -1) { // Si l'utilisateur n'est pas connecté, on affiche les liens de connexion et d'inscription
                        echo '
                <li><a href="login.php">Se connecter</a></li> 
                <li><a href="inscription.php">Créer un compte</a></li> ';
                    } 
                    ?>
                    <?php if ($_SESSION["droit"] == 0 || $_SESSION["droit"] == -2 ) { // Si l'utilisateur est connecté, on affiche les liens de profil et de déconnexion
                        echo '
                <li><a href="profil.php">Mon Profil</a></li> 
                <li><a href="deconnexion.php">Déconnexion</a></li> ';
                    } 
                    ?>
                    <?php if ($_SESSION["droit"] == 1) { // Si l'admin est connecté, on lui affiche ses options correspondantes
                        echo '<li><a href="admin.php">Admin</a></li>
                        <li><a href="profil.php">Mon Profil</a></li>
                        <li><a href="deconnexion.php">Déconnexion</a></li>'; 
                    }
                    ?>
                </ul>
            </li>
        </ul>
    </div>
    <!-- Fin menu déroulant -->

    <!-- Liens vers différentes catégories de recettes -->
    <!--// TODO Redirection vers une sélection de pages -->
    <a class="textlink">Meal Prep</a>
    <a class="textlink">Cuisiner pas cher</a>
    <a class="textlink">Prise de masse</a>
    <a class="textlink">Sèche</a>
    <hr id="bb_bottom">
</header>

<body>
    <div class="corps">

        <!-- Section "Recette du jour" -->
        <div class="recette_du_jour">
            <div class="recette_du_jour affichage">
                <!-- Affichage de la première recette du jour -->
                <h3 class="textblanc"><?php echo $recette1['Nom']; ?></h3>
                <!-- Lien vers la recette -->
                <form id="myForm" action="recette.php" method="post">
                    <input type="hidden" name="idRecette" value="<?php echo $recette1["IdRecette"]?>">
                </form>
                <a href="#" onclick="document.getElementById('myForm').submit();"><img class="image" src=" Images/Recette/<?php echo $recette1['IdRecette']; ?>.jpg"></a>
                <!-- -------------------- -->
                <?php
                //Si la recette vient d'être créé pas de note
                if ($recette1["Notemoy"] !== NULL) {
                    echo notation($recette1["Notemoy"]);
                }
                ?>
            </div>

            <div class="recette_du_jour affichage">
            
                <?php
                    // Récupération de l'ID de l'utilisateur connecté.
                    $login = $_SESSION['Login'];
                    $reqLog = $db->prepare("SELECT Pseudo,IdUtilisateur FROM Utilisateur WHERE Login = :Login");
                    $reqLog->execute(['Login' => $login]);
                    $resultat = $reqLog->fetch(PDO::FETCH_ASSOC);
                    // Stockage de l'ID de l'utilisateur connecté.
                    $user_id = $resultat['IdUtilisateur'];
                    
                    // Récupération des recettes favorites de l'utilisateur.
                    $req_fav = $db->prepare("SELECT Recette_pref.Id_recette
                    FROM Recette_pref
                    WHERE Recette_pref.Id_utilisateur = :user_id");
                    $req_fav->execute(['user_id' => $user_id]);

                    // Vérification si l'utilisateur a des recettes favorites.
                    if ($req_fav->rowCount() > 0) {
                             
                        // Récupération d'une recette aléatoire correspondant aux tags trouvés.
                        $placeholders = implode(",", array_fill(0, count($tags), "?"));
                        $req_recette = $db->prepare("SELECT DISTINCT Recette.IdRecette, Recette.Nom
                        FROM Recette
                        JOIN Tag ON Tag.Recette_assoc = Recette.IdRecette
                        WHERE Tag.Mot_clé IN (
                            SELECT Mot_clé FROM Tag WHERE Recette_assoc IN (
                                SELECT Id_recette FROM Recette_pref WHERE Id_utilisateur=:user_id
                            )
                        )
                        AND Recette.IdRecette NOT IN (
                            SELECT Id_recette FROM Recette_pref WHERE Id_utilisateur=:user_id
                        )
                        ORDER BY RAND()
                        LIMIT 1");
                        $req_recette->execute([':user_id' => $user_id]);
                        $resultat_recette = $req_recette->fetch(PDO::FETCH_ASSOC);

                        // Affichage de la recette aléatoire.
                        if ($resultat_recette) {
                            echo "<h3><u class='textblanc' >Recette qui pourrait vous intéresser</u></h3>";
                            echo "<h3 class='textblanc'>" . $resultat_recette['Nom'] . "</h3>";
                            echo '<a href="#" onclick="document.getElementById("myForm2").submit();"><img class="image" src="Images/Recette/'.$resultat_recette['IdRecette'].'.jpg"> </a>';
                        }
                    }else {
                        // Affichage de la deuxième recette du jour
                        echo '<h3 class="textblanc">'. $recette2['Nom'] .'</h3>
                        <!-- Lien vers la recette -->   
                        <form id="myForm2" action="recette.php" method="post">
                            <input type="hidden" name="idRecette" value='.$recette2['IdRecette'].'>
                        </form>
                        <a href="#" onclick="document.getElementById("myForm2").submit();"><img class="image" src="Images/Recette/'.$recette2['IdRecette'].'.jpg"> </a>';
                        //Si la recette vient d'être créé pas de note
                        if ($recette2["Notemoy"] !== NULL) {
                        echo notation($recette2["Notemoy"]);
                        }
                    }
                ?>                            

            </div>
        </div>

        <!-- Section "Recette du moment" -->
        <div class="recette_du_moment">
            <div class="recette_du_moment affichage">
                <h2 class="textblanc"><u class="textblanc">Recette du moment !</u></h2>
                <h3 class="textblanc"><?php echo $RecetteDuMoment['Nom']; ?></h3>
                <!-- Lien vers la recette -->
                <form id="myForm3" action="recette.php" method="post">
                    <input type="hidden" name="idRecette" value="<?php echo $RecetteDuMoment["IdRecette"]?>">
                </form>
                <a class="imageMoment" href="#" onclick="document.getElementById('myForm3').submit();"><img class="image" src="Images/Recette/<?php echo $RecetteDuMoment['IdRecette']; ?>.jpg"> </a>
                <!-- -------------------- -->
                <?php
                    //Si la recette vient d'être créé pas de note
                    if ($RecetteDuMoment["Notemoy"] !== NULL) {
                    echo notation($RecetteDuMoment["Notemoy"]);
                    }
                ?>
                <p class="textblanc"><?php echo $RecetteDuMoment['Description']; ?></p>
            </div>
        </div>

    </div>
</body>

<footer>
</footer>

</html>
