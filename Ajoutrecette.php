<!DOCTYPE html>
<html>

<?php
// Connexion à la base de données 
include "database.php";
global $db;

session_start();
if ($_SESSION["droit"]==-1)
{
    header("Location:accueil.php");
}
?>

<head>
    <title>Sportiton</title>
    <link rel="stylesheet" href="styles/AjoutRecette.css" />

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

<body>
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
    <!-------------- FORMULAIRE POUR AJOUTER UNE RECETTE --------------->                 
    <div id="main">
        <h1>Ajouter une recette</h1>
        <br>
        <form method="post" action="" enctype="multipart/form-data">
          
          <div class="TextBloc">
            <input type="text" name="titre" class="InputText" required><span class="labelText">Titre de votre recette:</span>           
          </div>
          
          <div class="TextBloc tag">
            <input class="InputText" list="tags" id="tag" name="tag[]" required/>
            <span for="tag" class="labelText" >Choisir des mots-clés:</span>
            <datalist id="tags">
                <?php
                $sql4=$db->prepare("SELECT * FROM Tag");
                $sql4->execute([]);
                $resultat4=$sql4->fetchAll();
                foreach ($resultat4 as $tag){
                  echo '<option value='.$tag["Mot_clé"].'>';
                };
                ?>
            </datalist>     
          <button onclick="insertDatalist()">Ajouter des tags</button>   
          <button onclick="removeDatalist()">Enlever des tags</button>     
          </div>
          <div id="tag_div" style="display:inline-block"></div>

          <div class="TextBloc">
            <label class ="labelText description">Description :</label><br>
            <textarea name="Description" id="Description" class="InputText description" required></textarea>
          </div>
          <div class="TextBloc">          
            <input type="number" name="Nb_personne" class="InputText" min="1" required><span class="labelText">Nombre de personne :</span>           
          </div>
          <div class="TextBloc Temps_prep">          
            <input type="number" name="Temps_prep" class="InputText" min="1" required><span class="labelText">Temps de préparation (en min) :</span>           
          </div>
          <div class="TextBloc Temps_cuis">            
            <input type="number" name="Temps_cuis" class="InputText" min="0" required><span class="labelText">Temps de cuisson (en min) :</span>           
          </div>
            

            <!-- Ingrédient -->
            <label id="Ing" style="display:block">Ingrédients:</label>
            <div id="blocingredients">
                <div class="TextBloc ingredients">
                  <input type="text" name="nom_ing[]" class="InputText ingredients" required>
                </div>
                <div class="TextBloc ingredients">
                  <input type="number" name="quantite[]" class="InputText ingredients" min="0" step="0.01" required><label class="labelText ingredients">Quantité: </label>
                </div>
                <div class="TextBloc ingredients">
                <label class="labelText ingredients" style="top:-15px">Unité: </label>
                  <select id="unite[]" name="unite[]" class="InputText ingredients">
                    <option value="">Aucune</option> 
                    <option value="kg">kg</option>                    
                    <option value="l">l</option>
                  </select>                  
                </div>
                <div id="nv_ing">
                </div>

                <button type="button" style="float:right; margin-top: 1em;" onclick="ajouter()">Ajouter un ingrédient</button>
            </div>
           
            <br><br>
            
            <div class="TextBloc">
              <label for="Instruction" class ="labelText description">Instructions :</label><br>
              <textarea name="Instruction" id="Instruction" class="InputText description" required></textarea>
            </div>

            <!-- Img -->
            
            <div class="image-upload">
              <label for="file-input">
                <img id="imagefile" src="Images/camera.png" style="margin-top: 0px; border: solid 2px black; border-radius: 50%"/>
              </label>
              <input type="file" id="file-input" name="file" onchange="loadFile(event)"/>
            </div>
            <input type="submit" name="submit" value="Ajouter la recette" style="float:right; margin-top: -1em">
        </form>
    </div>
</body>
<footer></footer>

</html>
<!------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------------>

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
        
        $sql=$db->prepare("INSERT INTO Recette(Nom, IdCréateur, Notemoy, Nb_personne, Temps_prep, Temps_cuis, Description, Instruction, Image, État) 
        VALUES (?, ?, NULL, ?, ?, ?, ?, ?, NULL,?);");
        // Exécution de la requête
        $sql->execute([$Nom,$_SESSION["id"],$Nb_personne,$Temps_prep,$Temps_cuis,$Description,$Instruction,$Etat]);

        //Id de la recette qu'on vient d'ajouter
        $id_recette = $db->lastInsertId();

        //Ajout des ingrédients
        $nom_ings=$_POST["nom_ing"];
        $quantites=$_POST["quantite"];
        $unites=$_POST["unite"];
       

        for($i=0;$i<count($_POST["nom_ing"]);$i++)
        {

        //Recherche si l'ingrédient existe deja pr donner le meme prix
        $sql3=$db->prepare("SELECT * FROM Ingrédient WHERE Nom=? ;");
        $sql3->execute([$nom_ings[$i]]);
        $resultat=$sql3->fetch(PDO::FETCH_ASSOC);
        if ($sql3->rowCount()>=1)  
        {
          $prix=$resultat["Prix"];
        }
        else
        {
          $prix=0;
        }
          
        $sql2=$db->prepare("INSERT INTO Ingrédient(Nom,Quantité,Unité,Prix,Recette) VALUES (?,?,?,?,?)");
        $sql2->execute([$nom_ings[$i],$quantites[$i],$unites[$i],$prix,$id_recette]);

        }

        //Ajout des tags de la recette:

        $tags=$_POST["tag"];
        for($j=0;$j<count($_POST["tag"]);$j++)
        {
          $sql5=$db->prepare("INSERT INTO Tag(Mot_clé,Recette_assoc) VALUES (?,?);");
          $sql5->execute([$tags[$j],$id_recette]);
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
              rename($target_file,$target_dir . $id_recette . ".jpg");
              }
              else {
                echo "Erreur lors du téléchargement du fichier.";
              }
            }
          }

 
}
?>
