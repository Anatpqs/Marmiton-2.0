<?php 

include 'database.php';
global $db;



$IdRecette=$_POST["IdRecette"];
$IdUtilisateur=$_POST["IdUtilisateur"];

if (isset($_POST["ajouter"]))
{
$sql=$db->prepare("INSERT INTO Recette_pref(Id_recette,Id_utilisateur) VALUES (?,?);");
$sql->execute([$IdRecette,$IdUtilisateur]);
};

if (isset($_POST["supprimer"]))
{
$sql=$db->prepare("DELETE FROM Recette_pref WHERE Id_recette=? AND Id_utilisateur=?;");
$sql->execute([$IdRecette,$IdUtilisateur]);
}

?>
