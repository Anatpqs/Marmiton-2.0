<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION["droit"])) {
  $_SESSION["droit"] = -1;
}
if($_SESSION["droit"]!=-1){
  $IdUtilisateur=$_SESSION["id"];
}
;

include 'database.php';
global $db;

// QUAND VOUS CLIQUER SUR UN LIEN QUI DIRIGE VERS LA RECETTE EN UTILISANT LA METHODE POST 
if (isset($_POST["idRecette"]))
{
$IdRecette = $_POST["idRecette"];
setcookie('idRecette', $IdRecette, time() + 86400);
}
else
{
$IdRecette=$_COOKIE["idRecette"];
}

//Bouton like : 


if (isset($_POST["IdUtilisateur"])) { 
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
}
// fin like

 //Si il n'y a pas de commentaire, note = NULL
 $sql4=$db->prepare("SELECT * FROM Commentaire WHERE Recette_Com=?");
 $sql4->execute([$IdRecette]);
 if ($sql4->rowCount()==0)
 {
   $sql5 = $db->prepare("UPDATE Recette SET Notemoy = NULL WHERE idRecette=:id");
     $sql5->execute(["id" => $IdRecette]);
 };
 
//Je cherche la recette dans la base de donnée
$sql = $db->prepare("SELECT * FROM Recette JOIN Utilisateur ON IdUtilisateur=IdCréateur WHERE IdRecette = :IdRecette ");
$sql->execute(['IdRecette' => $IdRecette]);
$resultat = $sql->fetch(PDO::FETCH_ASSOC);

//Fonction affichage de la note
function notation($note)
{
  switch ($note) {
    case 1:
      return '<img src="Images/1etoile.png" alt="note" class="img_note"><span class="note"><strong> 1/5 </strong></span>';
      break;
    case 2:
      return '<img src="Images/2etoile.png" alt="note" class="img_note"><span class="note"><strong> 2/5 </strong></span>';
      break;
    case 3:
      return '<img src="Images/3etoile.png" alt="note" class="img_note"><span class="note"><strong> 3/5 </strong></span>';
      break;
    case 4:
      return '<img src="Images/4etoile.png" alt="note" class="img_note"><span class="note"><strong> 4/5 </strong></span>';
      break;
    case 5:
      return '<img src="Images/5etoile.png" alt="note" class="img_note"><span class="note"><strong> 5/5 </strong></span>';
      break;
  }
};

?>

<head>
    <?php echo '<title>Recette de ' . $resultat["Nom"] . '</title>' ?>
    <link rel="stylesheet" href="styles/recette.css" />
    <style>
    <?php include 'styles/recette.css';
    ?>
    </style>
</head>

<body>
    <header>
    
        <div id="logo_div"><a href="accueil.php"><img id="logo" src="Images/cooking.png" alt="logo"></a></div>
        <div id="titre"><?php echo '<h1>Recette de ' . $resultat["Nom"] . '</h1>' ?></div>
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
    <div id="main">
    
    <div id="entete">
        <?php

    //Si la recette vient d'être créé pas de note
    echo "<div id='entete1'>";
    if ($resultat["Notemoy"] !== NULL) {
      echo notation($resultat["Notemoy"]);
    }
    $pseudo = $resultat["Pseudo"];
    echo '<p id="auteur"><strong>Auteur : </strong><span class="data">' . $pseudo . '</span></p></div>';
    ?>

    <!-- BOUTON LIKE -->
    <?php 
    if ($_SESSION["droit"]!=-1)
    {
    // On regarde si l'utilisateur a déjà like: 

      $sql = $db->prepare("SELECT * FROM Recette_pref WHERE Id_recette = ? AND Id_utilisateur = ?");
      $sql->execute([$IdRecette, $IdUtilisateur]);
     
      // On ajoute la classe "like-active" si la requête retourne un résultat
      $likeActiveClass = $sql->rowCount()==1 ? "like-active" : "";

      // Le bouton en question :

        echo '<div class="like '.$likeActiveClass.'"></div>';
      }
    ?>

    </div>
    <!--  -->

        <div id="imgdiv">
            <?php echo '<img id="img" src=Images/Recette/' . $resultat["IdRecette"] . '.jpg alt="gato">' ?>
        </div>
        <div id="info">
            <img id="temps" src="Images/horloge.png" alt="horloge">
            <?php echo '<p id="tps_prep"><strong> Temps total : </strong><span class="data">' . $resultat["Temps_prep"]+$resultat["Temps_cuis"] . ' min</span></p>' ?>
            <img id="img_prix" src="Images/euro.png" alt="euro">

            <!-- Calcul prix de la recette -->
            <?php $sql3 = $db->prepare("SELECT * FROM Ingrédient WHERE Recette=:id");
      $sql3->execute(["id" => $IdRecette]);
      $resultat3 = $sql3->fetchAll();
      $prix_recette = 0;
      foreach ($resultat3 as $row) {
        $prix_recette += $row["Prix"]*$row["Quantité"];
      }
      ?>
            <p><strong>Prix : </strong><span class="data"><span id="prix"> <?php echo $prix_recette; ?></span>
                    euros</span></p>
        </div>

        <div id="separation">
        </div>

        <br>
        <h2>Description</h2>
        <?php echo ' <p>' . $resultat["Description"] . '</p>' ?>
        <h2>Ingrédients</h2>
        <?php echo ' <img src="Images/groupe.png" alt="groupe" id="personne"> <span id="nbr">' . $resultat["Nb_personne"] . '</span> personnes ' ?>
        <button onclick="incr()">+</button> <button onclick="decr()">-</button>
       
        <br> <br>
        <!-- Affichage des ingrédients -->
        <ul id="liste_ing">
            <?php $sql3 = $db->prepare("SELECT * FROM Ingrédient WHERE Recette=:id");
      $sql3->execute(["id" => $IdRecette]);
      $resultat3 = $sql3->fetchAll();
      $j = 1;
      $tabQuantite = array();
      $prix_recette = 0;
      foreach ($resultat3 as $row) {
        echo "<li>" . $row["Nom"] . " : <span id=ing" . $j . ">" . $row["Quantité"] . "</span> " . $row["Unité"] . "</li>";
        $j += 1;
        $tabQuantite[] = $row["Quantité"];
        $prix_recette += $row["Prix"]*$row["Quantité"]; //Multiplier par la quantité ?
      };
      ?>

        </ul>
        <br>

        <h2>Instructions</h2>
        <div id="divprout">
            <img id="casserole" src="Images/casserole.png" alt="casserole">
            <?php echo '<p><strong>Temps de préparation : </strong><span class="data">' . $resultat["Temps_prep"] . ' min </span></p>'?>&nbsp&nbsp&nbsp&nbsp
            <?php echo '<p><strong>Temps de cuisson : </strong><span class="data">' . $resultat["Temps_cuis"] . ' min </span></p>' ?>
      
        </div>
        <ol>
            <?php 
      echo nl2br($resultat["Instruction"]);
      ?>
        </ol>
    </div>

    <?php
    if ($resultat["État"]==2)
    {
    echo '
    <style type ="text/css">
      #commentairediv{
        visibility: hidden;
      }
      </style> ';
    }
    ?>
    <div id="commentairediv">
        <!-- il faut pouvoir trier les commentaires -->
        <div id="comment_select">
            <h2>Commentaires</h2>

            <form method="post">
                <select name="tri" id="tri">
                    <option value="vide">--Trier par--</option>
                    <option value="récent">Plus récent</option>
                    <option value="ancien">Plus ancien</option>
                    <option value="favo">Avis favorables</option>
                    <option value="defavo">Avis défavorables</option>
                    <input type="submit" name="submit" />
                    <input type="hidden" name="idRecette" value="<?php echo $IdRecette ?>">
            </form>
        </div>

        <?php
    /* affichage commentaire de la bdd*/
    if (!isset($_POST['tri'])) {
      $_POST["tri"] = "vide";
    }
    switch ($_POST["tri"]) {
      case "vide":
        $sql = $db->prepare("SELECT Commentaire,Note,Pseudo,Date FROM Commentaire
         JOIN Utilisateur ON IdAuteur=IdUtilisateur
          WHERE :idRecette=Recette_com");
        break;
      case "récent":
        $sql = $db->prepare("SELECT Commentaire,Note,Pseudo,Date FROM Commentaire
            JOIN Utilisateur ON IdAuteur=IdUtilisateur
             WHERE :idRecette=Recette_com ORDER BY Date DESC");
        break;
      case "ancien":
        $sql = $db->prepare("SELECT Commentaire,Note,Pseudo,Date FROM Commentaire
              JOIN Utilisateur ON IdAuteur=IdUtilisateur
               WHERE :idRecette=Recette_com ORDER BY Date");
        break;
      case "favo":
        $sql = $db->prepare("SELECT Commentaire,Note,Pseudo,Date FROM Commentaire
                JOIN Utilisateur ON IdAuteur=IdUtilisateur
                 WHERE :idRecette=Recette_com ORDER BY Note DESC");
        break;
      case "defavo":
        $sql = $db->prepare("SELECT Commentaire,Note,Pseudo,Date FROM Commentaire
                  JOIN Utilisateur ON IdAuteur=IdUtilisateur
                   WHERE :idRecette=Recette_com ORDER BY Note");
        break;
    }

    // AFFICHAGE DES COMMENTAIRES

    $sql->execute(["idRecette" => $IdRecette]);
    $resultat2 = $sql->fetchAll();
    
    $somme = 0; // pour compter la note
    $i = 0;
    foreach ($resultat2 as $row) {
      echo "<div class='comment_afficher'>
      <div class='info_comment'>
      <img src='Images/profil.png' alt='profil' class='profil_commentaire'>
      <div class='info_comment2'>
      <strong><span class='nom_comment'>" . $row['Pseudo'] . "</span></strong>
      <span class='note_comment'>" . notation($row['Note']) . "</span>
      </div></div>      
      <div class='comment_text'>" . $row['Commentaire'] . "</div>
      <p class='date_comment'>" . $row['Date'] . "</p>
      </div>
      <div id='barre_commentaire'></div>";
      $somme += $row["Note"];
      $i += 1;
    };

    //CALCUL NOTE EN FONCTION DES COMMENTAIRES
    if ($resultat["Notemoy"] !== NULL) {
      $Notemoy = $somme / $i;
    }
    ?>

        <?php
    /* écrire commentaire*/
    if ($_SESSION["droit"] != -1 && $_SESSION["droit"] !=-2) {

      $id = $_SESSION["id"];
      $pseudo2 = $_SESSION["pseudo"];


      if (isset($_POST["commentaire"])) {
        $commentaire = $_POST["commentaire"];
      }
      if (isset($_POST["note"])) {
        $note = $_POST["note"];
      }
      if (isset($_POST["envoyer"])) {

        $sql = $db->prepare("INSERT INTO Commentaire(Commentaire,Note,IdAuteur,Recette_com) VALUES (:Commentaire,:Note,:IdAuteur,:Recette_com)");
        $sql->execute(["Commentaire" => $commentaire, "Note" => $note, "IdAuteur" => $id, "Recette_com" => $IdRecette]);

        if ($resultat["Notemoy"] !== NULL) { 
        $Notemoy = ($Notemoy + $note) / 2;
        }
        else
        {
          $Notemoy=$note;
        }
        $sql2 = $db->prepare("UPDATE Recette SET Notemoy = :note WHERE idRecette=:id");
        $sql2->execute(["note" => $Notemoy, "id" => $IdRecette]);
        echo "<meta http-equiv='refresh' content='0'>";
      }
      echo '<form method="post" id="comment-form">
    <div id="comment">
        <textarea name="commentaire" id="commentaire" placeholder="Ajouter un commentaire" maxlength="180"></textarea>
        <div id="notation"><strong>Note : </strong><span class="output">5</span>/5</div> 
        <input name="note" type="range" min="0" max="5" value="5">
        <input type="hidden" name="idRecette" value="'.$IdRecette.'">
        <input type="submit" name="envoyer" id="envoyer" placeholder="Envoyer">
    </div>
    </form>';
    }

    ?>

    </div>

</body>
  
<footer>
  <a> Nous contacter </a>
</footer>

  <!-- Pour l'amdin : option desactiver les commentaires -->
  <?php
            if($_SESSION["droit"]==1)
            {
              //activer
              echo '<form method="post">
              <input type="submit" name="desac" value="Désactiver les commentaires">
              <input type="hidden" name="idRecette" value="'.$IdRecette.'">
              </form>';
              //desac
              echo '<form method="post">
              <input type="submit" name="activ" value="Activer les commentaires">
              <input type="hidden" name="idRecette" value="'.$IdRecette.'">
              </form>';
            

            if (isset($_POST["desac"]))
            {
              $sql6=$db->prepare("UPDATE Recette SET État = 2 WHERE IdRecette=?");
              $sql6->execute([$IdRecette]);
              echo "<meta http-equiv='refresh' content='0'>";
            }
            if (isset($_POST["activ"]))
            {
              $sql7=$db->prepare("UPDATE Recette SET État = 1 WHERE IdRecette=?");
              $sql7->execute([$IdRecette]);
              echo "<meta http-equiv='refresh' content='0'>";
            }

          }
  ?>



<script>
//bouton note
const sizePicker = document.querySelector('input[type="range"]');

const output = document.querySelector('.output');

if (sizePicker) {

    sizePicker.oninput = () => {

        output.textContent = sizePicker.value;

    }
}

//nbr personne
var nbr = document.getElementById("nbr").textContent;
nbr = parseInt(nbr);
var prix = document.getElementById("prix").textContent;
const prix_uni = (<?php echo $prix_recette ?>) / nbr;
//prix unit ingredient

let tabIngredient = [];
<?php
  $n = count($tabQuantite);
  for ($i = 0; $i < $n; $i++) {
    echo "tabIngredient[$i] = $tabQuantite[$i];";
  }
  ?>
// nbr d'ingrédient je crois
let j = <?php echo $j; ?>;


function incr() {
    var nbr = document.getElementById("nbr").textContent;
    nbr = parseInt(nbr);
    nbr += 1;
    document.getElementById("nbr").innerHTML = nbr;
    // calcul prix
    var resultat = prix_uni * nbr;
    resultat=Math.round(resultat * 100) / 100;
    document.getElementById("prix").innerHTML = resultat;
    //calcul quantité ing
    for (let i = 0; i < j; i++) {
        let quantIng = tabIngredient[i] * nbr;
        document.getElementById(`ing${i+1}`).innerHTML = quantIng;
    }
    //calcul ingrédient
}


function decr() {
    var nbr = document.getElementById("nbr").textContent
    nbr = parseInt(nbr)
    if (nbr > 1) {
        nbr -= 1
        document.getElementById("nbr").innerHTML = nbr
        // calcul prix
        var resultat = prix_uni * nbr;
        resultat=Math.round(resultat * 100) / 100;
        document.getElementById("prix").innerHTML = resultat;
        //calcul quantité ing 
        for (let i = 0; i < j; i++) {
            let quantIng = tabIngredient[i] * nbr;
            document.getElementById(`ing${i+1}`).innerHTML = quantIng;
        }
        //calcul ingrédient

    }

}


//BOUTON LIKE

const like = document.querySelector('.like');
    
    let countLike = 0;
    like.addEventListener('click', () => {

      const IdRecette = <?php echo $IdRecette ?> ;
      const IdUtilisateur = <?php echo $IdUtilisateur ?>;
    
        if(countLike === 0) {
            like.classList.toggle('anim-like');
            countLike = 1;
            like.style.backgroundPosition = 'right';

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'recette.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
              if (xhr.status === 200) {
                console.log(xhr.responseText);
                // réussite de la requete
              }
            };
            xhr.send('IdRecette=' + encodeURIComponent(IdRecette) + '&IdUtilisateur=' + encodeURIComponent(IdUtilisateur) +'&ajouter=');
            
        } else {
            countLike = 0;
            like.style.backgroundPosition = 'left';


            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'recette.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
              if (xhr.status === 200) {
                console.log(xhr.responseText);
                // réussite de la requete
              }
            };
            xhr.send('IdRecette=' + encodeURIComponent(IdRecette) + '&IdUtilisateur=' + encodeURIComponent(IdUtilisateur) +'&supprimer=');
            

        }
    
    });
    
    like.addEventListener('animationend', () => {
        like.classList.toggle('anim-like');
    })

</script>

</html>
