<!DOCTYPE html>
<html>


<?php
session_start();
include 'database.php';
global $db;

$IdRecette=$_POST["IdRecette"];

?>

<head>
<meta charset="utf-8" />
<title>Modification recette</title>


<script>
    function ajouter(){
        var div = document.createElement('div');
        div.innerHTML = '<label>Nom :</label><input type="text" name="nv_nom_ing[]" class="nom_ing" required><br><label>Quantité :</label><input type="number" name="nv_quantite[]" class="quantite" min="0" step="0.01" required><br><label>Unité :</label><input type="text" name="nv_unite[]" class="unite"><br><label>Prix :</label><input type="number" name="nv_prix[]" class="prix" min="0" step="0.01" required><br>';
        document.getElementById('ing').appendChild(div);
    }
    </script>

</head>

<body>

<h1>Modifier la recette</h1>

<?php 

$sql=$db->prepare("SELECT * FROM Recette WHERE IdRecette=?;");
$sql->execute([$IdRecette]);
$resultat=$sql->fetch(PDO::FETCH_ASSOC);

echo '
    <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="IdRecette" value="'.$IdRecette.'">
        <label for="titre">Titre de la recette :</label>
        <input type="text" name="titre" id="titre" value='.$resultat["Nom"].' required><br><br>

        <label for="Nb_personne">Nombre de personne :</label>
        <input type="number" name="Nb_personne" id="Nb_personne" min="1" value='.$resultat["Nb_personne"].' required><br><br>

        <label for="Temps_prep">Temps de préparation (en min) :</label>
        <input type="number" name="Temps_prep" id="Temps_prep" min="1" value='.$resultat["Temps_prep"].' required><br><br>

        <label for="Temps_cuis">Temps de cuisson (en min) :</label>
        <input type="number" name="Temps_cuis" id="Temps_cuis" min="0" value='.$resultat["Temps_cuis"].' required><br><br>
        
        <!-- Ingrédient -->
        <div id="ing">
        <label for="Ing">Ingrédients:</label><br>
        ';

        $sql2=$db->prepare("SELECT * FROM Ingrédient WHERE Recette=?");
        $sql2->execute([$IdRecette]);
        $resultat2=$sql2->fetchAll();
        foreach($resultat2 as $ing)
        {
        
        echo '
        
            <label>Nom :</label><input type="text" name="nom_ing[]" class="nom_ing" value="'.$ing["Nom"].'" required><br>
            <label>Quantité :</label><input type="number" name="quantite[]" class="quantite" min="0" step="0.01" value="'.$ing["Quantité"].'" required><br>
            <label>Unité :</label><input type="text" name="unite[]" class="unite" value="'.$ing["Unité"].'" ><br>
            <label>Prix :</label><input type="number" name="prix[]" class="prix" min="0" step="0.01" value="'.$ing["Prix"].'" required>
            <input type="hidden" name="id[]" value="'.$ing["IdIngrédient"].'"><br>
            <br><br>';

        };

        echo '
        </div>
        <button type="button" onclick="ajouter()">Ajouter un ingrédient</button>
        <br><br> 

        <label for="Description">Description :</label><br>
        <textarea name="Description" id="Description" required>'.$resultat["Description"].'</textarea><br><br>
        
        <label for="Instruction">Instruction :</label><br>
        <textarea name="Instruction" id="Instruction" required>'.$resultat["Instruction"].'</textarea><br><br>

      
        <label for="file">Image de la recette:</label>
        <img src="Images/Recette/'.$IdRecette.'.jpg" alt="recette" width="100px" height="100px" >
        <input type="file" id="file" name="file"> 
        <br>
        <input type="submit" name="submit" value="Ajouter la recette">
    </form>
';


// Récupération des données du formulaire
if (isset($_POST['submit'])) {
    $Nom = strval($_POST['titre']); 
    $Description = strval($_POST['Description']);
    $Instruction = strval($_POST['Instruction']); 
    $Nb_personne = intval($_POST['Nb_personne']);
    $Temps_prep = intval($_POST['Temps_prep']);
    $Temps_cuis = intval($_POST['Temps_cuis']);
    $Etat=0;

   
        // Requête SQL d'insertion
        
        $sql=$db->prepare("UPDATE Recette SET Nom=? , Nb_personne=? , Temps_prep=? , Temps_cuis=? , Description=? , Instruction=? WHERE IdRecette=$IdRecette");
        // Exécution de la requête
        $sql->execute([$Nom,$Nb_personne,$Temps_prep,$Temps_cuis,$Description,$Instruction]);

        //Modification des ingrédients
        $nom_ings=$_POST["nom_ing"];
        $quantites=$_POST["quantite"];
        $unites=$_POST["unite"];
        $prix=$_POST["prix"];
        $ids=$_POST["id"];

        for($i=0;$i<count($_POST["nom_ing"]);$i++)
        {
        $sql2=$db->prepare("UPDATE Ingrédient SET Nom=? , Quantité=? , Unité=? , Prix=? WHERE IdIngrédient=?;");
        $sql2->execute([$nom_ings[$i],$quantites[$i],$unites[$i],$prix[$i],$ids[$i]]);
        }

        //Ajout de nouveau ingrédient:

        if(isset($_POST["nv_nom_ing"]))
        {
        $nv_nom_ing=$_POST["nv_nom_ing"];
        $nv_quantite=$_POST["nv_quantite"];
        $nv_unite=$_POST["nv_unite"];
        $nv_prix=$_POST["nv_prix"];

        for($j=0;$j<count($_POST["nv_nom_ing"]);$j++)
        {
          $sql3=$db->prepare("INSERT INTO Ingrédient(Nom,Quantité,Unité,Prix,Recette) VALUES (?,?,?,?,?)");
          $sql3->execute([$nv_nom_ing[$j],$nv_quantite[$j],$nv_unite[$j],$nv_prix[$j],$IdRecette]);
        }

       }
       
        // IMAGE RECETTE
        
        if(isset($_FILES['file'])) {
            $file_name = $_FILES['file']['name'];
            $file_tmp = $_FILES['file']['tmp_name'];
            $file_type = $_FILES['file']['type'];
            $file_size = $_FILES['file']['size'];
            $target_dir = "Images/Recette/"; // dossier de destination
            $target_file = $target_dir . basename($file_name);
          
            // Vérifier le type de fichier
            $allowed_types = array('image/jpeg', 'image/png');
            if(!in_array($file_type, $allowed_types)) {
              echo "Erreur: Seules les images JPEG et PNG sont autorisées.";
            }
            // Vérifier la taille du fichier
            else if($file_size > 5000000) { // 5 Mo maximum
              echo "Erreur: La taille du fichier doit être inférieure à 5 Mo.";
            }
            else {
              if(move_uploaded_file($file_tmp, $target_file)) {
                echo "Recette envoyé - En attente de validation par l'admin !";
              rename($target_file,$target_dir.$IdRecette.".jpg");
              }
              else {
                echo "Erreur lors du téléchargement du fichier.";
              }
            }
          }


  header("Location:admin.php");
}

?>
</body>

</html>
