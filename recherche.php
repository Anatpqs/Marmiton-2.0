<?php
include 'database.php';
global $db;

$q = $_GET['q'];

// Requête SQL pour la recherche

$sql = $db->prepare("SELECT * FROM recette WHERE Nom LIKE :Nom ");
$sql->execute(["Nom"=>"%$q%"]);

$resultat=$sql->fetchAll();
foreach($resultat as $row)
{
echo "<a href='#'>".$row["Nom"]."</a>";
echo "<br>";
};
?>