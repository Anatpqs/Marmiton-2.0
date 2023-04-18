<!DOCTYPE html>
<html>


<?php
session_start();
if($_SESSION["droit"]!=1){
  header("Location:accueil.php");
}
include 'database.php';
global $db;

if (isset($_POST["IdRecette"]))
{
$IdRecette = $_POST["IdRecette"];
setcookie('IdRecette', $IdRecette, time() + 86400);
}
else
{
$IdRecette=$_COOKIE["IdRecette"];
}

?>

<head>
<meta charset="utf-8"/>
<title>Modification recette</title>
<link rel="stylesheet" href="styles/modif_recette.css" />

<script>
   function ajouter(){
          var div = document.createElement('div');
          div.innerHTML = '<div class="TextBloc ingredients"> <input type="text" name="nom_ing[]" class="InputText ingredients" required><span class="labelText ingredients">Nom: </span> </div> <div class="TextBloc ingredients"> <input type="number" name="quantite[]" class="InputText ingredients" min="0" step="0.01" required><label class="labelText ingredients">Quantité: </label> </div> <div class="TextBloc ingredients"> <label class="labelText ingredients" style="top:-15px">Unité: </label> <select id="unite[]" name="unite[]" class="InputText ingredients"> <option value="">Aucune</option> <option value="kg">kg</option> <option value="l">l</option> </select> </div>'
          document.getElementById('nv_ing').appendChild(div);
      }

      function insertDatalist() {
        var div = document.createElement('div');
        div.setAttribute('class', 'tagDiv');

        div.innerHTML = '<input class="InputText tag" list="tags" name="tag[]" required/><datalist id="tags">' +
        <?php
          $sql4 = $db->prepare("SELECT * FROM Tag;");
          $sql4->execute([]);
          $resultat4 = $sql4->fetchAll();
          $options = '';
          foreach ($resultat4 as $tag) {
            $options .= '<option value="' . $tag["Mot_clé"] . '">';
          }
        ?>     

        document.getElementById('tag_div').appendChild(div);
      }

      function removeDatalist(){
        var tag_div = document.getElementById('tag_div');
        var tagDivs = tag_div.getElementsByClassName('tagDiv');
        if (tagDivs.length > 0) {
        tag_div.removeChild(tagDivs[tagDivs.length - 1]);
        }
      }


      const tx = document.getElementsByTagName("textarea");
      for (let i = 0; i < tx.length; i++) {
        tx[i].setAttribute("style", "height:" + (tx[i].scrollHeight) + "px;overflow-y:hidden;");
        tx[i].addEventListener("input", OnInput, false);
      }
      function OnInput() {
        this.style.height = 0;
        this.style.height = (this.scrollHeight) + "px";
      }
      var loadFile = function(event) {
        var imagefile = document.getElementById('imagefile');
        imagefile.src = URL.createObjectURL(event.target.files[0]);
        imagefile.onload = function() {
          URL.revokeObjectURL(imagefile.src) // free memory
        }
      };

    </script>

</head>

<header>
      <div id="logo_div"><a href="accueil.php"><img id="logo" src="Images/cooking.png" alt="logo"></a></div>
      <div id="titre"><?php echo '<h1>Sportiton</h1>' ?></div>
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
                      <?php if ($_SESSION["droit"] == 0) {
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
    </header>

<body>
  
<div id="main">
<h1>Modifier la recette</h1>
<br>
<?php 

$sql=$db->prepare("SELECT * FROM Recette WHERE IdRecette=?;");
$sql->execute([$IdRecette]);
$resultat=$sql->fetch(PDO::FETCH_ASSOC);

echo '
    <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="IdRecette" value="'.$IdRecette.'">

      <div class="TextBloc">
        <input type="text" name="titre" class="InputText" value="'.$resultat["Nom"].'" required><span class="labelText">Titre de votre recette:</span> 
      </div>

      <div class="TextBloc tag">
      <span for="tag" class="labelText" >Choisir des mots-clés:</span><br>';
        //Affichage mot clé 
        $sql5=$db->prepare("SELECT * FROM Tag WHERE Recette_assoc=?;");
        $sql5->execute([$IdRecette]);
        $resultat5=$sql5->fetchAll();
        foreach ($resultat5 as $tag)
        {
          echo '
          <input list="tags" class="InputText" id="tag" name="tag[]" value='.$tag["Mot_clé"].' required>
          <input type="hidden" name="id_tag[]" value='.$tag["IdTag"].'>
          <datalist id="tags">';
          
            $sql4=$db->prepare("SELECT * FROM Tag;");
            $sql4->execute([]);
            $resultat4=$sql4->fetchAll();
            foreach ($resultat4 as $tags)
            {
              echo '<option value='.$tags["Mot_clé"].'>';
      
            };
          echo '</datalist>';
        }
        //Ajout nv_tags
        echo '
        <button type="button" onclick="insertDatalist()">Ajouter des tags</button>
        <button type="button" onclick="removeDatalist()">Enlever des tags</button> 
        </div>

        <div id="tag_div" style="display:inline-block"> </div>

        <div class="TextBloc">
          <label class="labelText description">Description :</label><br>
          <textarea name="Description" id="Description" class="InputText description" required>'.$resultat["Description"].'</textarea>
        </div>

        <div class="TextBloc">
          <input type="number" name="Nb_personne" class="InputText" min="1" value='.$resultat["Nb_personne"].' required><span class="labelText">Nombre de personne :</span>   
        </div>

        <div class="TextBloc Temps_prep">  
          <input type="number" name="Temps_prep" class="InputText" min="1" value='.$resultat["Temps_prep"].' required><span class="labelText">Temps de préparation (en min) :</span>           
        </div>

        <div class="TextBloc Temps_cuis">   
          <input type="number" name="Temps_cuis" class="InputText" min="0" value='.$resultat["Temps_cuis"].' required><span class="labelText">Temps de cuisson (en min) :</span>
        </div>

        <!-- Ingrédient -->
        <label id="Ing" style="display:block">Ingrédients:</label>
        <div id="blocingredients">
        
        ';

        $sql2=$db->prepare("SELECT * FROM Ingrédient WHERE Recette=?");
        $sql2->execute([$IdRecette]);
        $resultat2=$sql2->fetchAll();
        foreach($resultat2 as $ing)
        {
        
        echo '
            <div class="TextBloc ingredients">
              <input type="text" name="nom_ing[]" class="InputText ingredients" value="'.$ing["Nom"].'" required><span class="labelText ingredients">Nom: </span>
            </div>

            <div class="TextBloc ingredients">
              <input type="number" name="quantite[]" class="InputText ingredients" min="0" step="0.01" value="'.$ing["Quantité"].'" required><label class="labelText ingredients">Quantité: </label>
            </div>

            <div class="TextBloc ingredients">
              <label class="labelText ingredients" style="top:-15px">Unité: </label>
              <select id="unite[]" name="unite[]" class="InputText ingredients">
                    <option value="" '.(($ing["Unité"] === "") ? "selected" : "").'>Aucune</option> 
                    <option value="kg" '.(($ing["Unité"] === "kg") ? "selected" : "").'>kg</option>                    
                    <option value="l" '.(($ing["Unité"] === "l") ? "selected" : "").'>l</option>
              </select>                  
            </div>

            <input type="hidden" name="id[]" value="'.$ing["IdIngrédient"].'">
            ';

        };

        echo '
        <div id="nv_ing"></div>
        <button type="button" style="float:right; margin-top: 1em;" onclick="ajouter()">Ajouter un ingrédient</button>
        </div>
        <br><br>

        <div class="TextBloc">
          <label for="Instruction" class ="labelText description">Instruction :</label><br>
          <textarea name="Instruction" id="Instruction" class="InputText description"  required>'.$resultat["Instruction"].'</textarea>
        </div>
      
        
        <div class="image-upload">
              <label for="file-input">
                <img id="imagefile" src="Images/Recette/'.$IdRecette.'.jpg" style="margin-top: 0px; border: solid 2px black; border-radius: 50%"/>
              </label>
              <input type="file" id="file-input" name="file" onchange="loadFile(event)"/>
            </div>
            <input type="submit" name="submit" value="Modifier la recette" style="float:right; margin-top: -1em">
        </div>
    </form>
'; ?>

</div>

<?php
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

       //Modification des tags existants
       $tag=$_POST["tag"];
       $id_tag=$_POST["id_tag"];

       for($i=0;$i<count($_POST["id_tag"]);$i++)
       {
        $sql6=$db->prepare("UPDATE Tag SET Mot_Clé=? WHERE IdTag=? AND Recette_assoc=?");
        $sql6->execute([$tag[$i],$id_tag[$i],$IdRecette]);
       }

       //Ajout nv tags
       if (isset($_POST["nv_tag"])){
        $nv_tag=$_POST["nv_tag"];

        for($i=0;$i<count($_POST["nv_tag"]);$i++)
        {
        $sql7=$db->prepare("INSERT INTO Tag(Mot_clé,Recette_assoc) VALUES (?,?);");
        $sql7->execute([$nv_tag[$i],$IdRecette]);
        }

       }
       
        // IMAGE RECETTE
        if (isset($_POST["file"])){
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
        }
echo "<meta http-equiv='refresh' content='0'>";
}


?>
</body>

<footer></footer>

</html>
