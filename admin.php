<!DOCTYPE html>
<html>


<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin</title>

    <!-- Javascript -->

</head>

<body>
    <?php
    //Connexion
    include "database.php";
    global $db;
    ?>

    <h2>Recette à valider :</h2>
    <?php
    //Recette à valider
    $sql=$db->prepare("SELECT * FROM Recette WHERE État=0");
    $sql->execute([]);
    $resultat=$sql->fetchAll();
    foreach($resultat as $recette)
    {
        //Afficher les recettes à valider
        echo '<form id="myForm_'.$recette["IdRecette"].'" action="recette.php" method="post">
        <input type="hidden" name="idRecette" value="'.$recette["IdRecette"].'">
     </form>
     <a href="#" onclick="document.getElementById(\'myForm_'.$recette["IdRecette"].'\').submit();">'.$recette["Nom"].'</a>
    <form method="post">
    <input type="submit" name="validation_'.$recette["IdRecette"].'" value="Valider la recette">
    </form>';
    
    //Validation des recettes
     if (isset($_POST["validation_".$recette["IdRecette"]]))
     {
        $sql2=$db->prepare("UPDATE Recette SET État = 1 WHERE IdRecette=?");
        $sql2->execute([$recette["IdRecette"]]);
        echo "<meta http-equiv='refresh' content='0'>";
     }
    }
    

?>

<!-- Suppresion et modification recette -->
<h2>Liste des recettes disponibles :</h2>
<?php
$sql3=$db->prepare("SELECT * FROM Recette WHERE État!=0;");
$sql3->execute([]);
$resultat3=$sql3->fetchAll();
foreach($resultat3 as $recette)
{
    echo '<form id="form_recette_'.$recette["IdRecette"].'" action="recette.php" method="post">
        <input type="hidden" name="idRecette" value="'.$recette["IdRecette"].'">
     </form>
     <a href="#" onclick="document.getElementById(\'form_recette_'.$recette["IdRecette"].'\').submit();">'.$recette["Nom"].'</a>
     <form method="post">
    <input type="submit" name="suppression_'.$recette["IdRecette"].'" value="Supprimer la recette">
    </form>
    <form method="post" action="modif_recette.php">
    <input type="submit" name="modification_'.$recette["IdRecette"].'" value="Modifier la recette">
    <input type="hidden" name="IdRecette" value="'.$recette["IdRecette"].'">
   </form>';

//SUPPRESSION RECETTE
   if (isset($_POST["suppression_".$recette["IdRecette"]]))
{
    $sql4=$db->prepare("DELETE FROM Recette WHERE IdRecette=?;");
    $sql4->execute([$recette["IdRecette"]]);
    echo "<meta http-equiv='refresh' content='0'>";
}

//MODIFICATION RECETTE

}
?>


<!-- DESACTIVER COMMENTAIRE POUR UN UTILISATEUR -->
<h2>Liste des utilisateurs :</h2>
<?php
$sql5=$db->prepare("SELECT * FROM Utilisateur");
$sql5->execute([]);
$resultat5=$sql5->fetchAll();
foreach($resultat5 as $user)
{
    echo $user["Pseudo"];
    if ($user["Droit"]!=-2){
    echo '<form id="form_user_'.$user["IdUtilisateur"].'"  method="post">
    <input type="submit" name="sup_'.$user["IdUtilisateur"].'" value="Desactiver commentaire">
    </form>'
    .'<br>';
    }
    else
    {
    echo '<form id="form_user_activer_'.$user["IdUtilisateur"].'"  method="post">
    <input type="submit" name="activer_'.$user["IdUtilisateur"].'" value="Activer commentaire">
    </form>'
    .'<br>';
    }

    //DESAC COMMENTAIRE UTILISATEUR
    if (isset($_POST["sup_".$user["IdUtilisateur"]]))
    {
        $sql6=$db->prepare("UPDATE Utilisateur SET Droit=-2 WHERE IdUtilisateur=?");
        $sql6->execute([$user["IdUtilisateur"]]);
        echo "<meta http-equiv='refresh' content='0'>";
    }
    //Re-activer les coms
    if (isset($_POST["activer_".$user["IdUtilisateur"]]))
    {
        $sql6=$db->prepare("UPDATE Utilisateur SET Droit=0 WHERE IdUtilisateur=?");
        $sql6->execute([$user["IdUtilisateur"]]);
        echo "<meta http-equiv='refresh' content='0'>";
    }
}

?>
</body>

</html>
