<!DOCTYPE html>
<html>

<?php
    session_start();

    if (!isset($_SESSION["droit"])) {
    $_SESSION["droit"] = -1;
    };

    //Fonction affichage de la note
    function notation($note)
    {
    switch ($note) {
        case 1:
        return '<img src="Images/1etoile.png" alt="note" class="img_note"><span class="note"> 1/5 </span>';
        break;
        case 2:
        return '<img src="Images/2etoile.png" alt="note" class="img_note"><span class="note"> 2/5 </span>';
        break;
        case 3:
        return '<img src="Images/3etoile.png" alt="note" class="img_note"><span class="note"> 3/5 </span>';
        break;
        case 4:
        return '<img src="Images/4etoile.png" alt="note" class="img_note"><span class="note"> 4/5 </span>';
        break;
        case 5:
        return '<img src="Images/5etoile.png" alt="note" class="img_note"><span class="note"> 5/5 </span>';
        break;
    }
    };

?>

<head>
    <title>Sportiton</title>
    <link rel="stylesheet" href="styles/Recherche.css" />
</head>

<body>  
    <header>
        <div id="logo_div"><a href="accueil.php"><img id="logo" src="Images/cooking.png" alt="logo"></a></div>
        <form method="post" id="formsearchbar">
            <input type="search" id="searchbar" name="searchbar" placeholder="Recette..." autocomplete="off">
        </form> 
        <div id="profil"><a href="profil.php"><img src="Images/userblanc.png" alt="profil" id="profil_img"></a></div>
    </header>
    <div id="results" name="results">
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Récupère la chaîne de recherche
                $searchbar = $_POST["searchbar"];
                
                include 'database.php';
                global $db;
                // Vérification de la connexion
                if (!$db) {
                    die("Connexion échouée: " . mysqli_connect_error());
                } 
        
                // Requête SQL pour récupérer les recettes contenant la chaîne de recherche
                $sql = $db->prepare("SELECT * FROM Recette WHERE Nom LIKE :search");
                $sql->execute(['search' => '%'.$searchbar.'%']);
                $result = $sql->fetchAll();
        
                // Affiche les résultats de la recherche
                if (count($result) > 0) {
                    foreach ($result as $row) {
                        
                    echo '<form id="myForm'.$row["IdRecette"].'" action="recette.php" method="post">
                        <input type="hidden" name="idRecette" value="'.$row["IdRecette"].'">
                        </form>
                        <h2 style="display: inline"><a href="#" onclick="document.getElementById(\'myForm'.$row["IdRecette"].'\').submit();">'.$row["Nom"].'</a></h2>';

                        echo " " . notation($row['Notemoy']) . " ";
                        echo "<p>" . $row["Description"] . "</p>";
                        echo "<br><hr><br>";
                    }
                } else {
                    echo "Aucune recette trouvée pour ''".$searchbar."''";
                }
        
            }
        ?>
    </div>

</body>
</html>
