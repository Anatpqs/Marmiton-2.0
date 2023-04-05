<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION["droit"])) {
  $_SESSION["droit"] = -1;
};

include 'database.php';
global $db;
$IdRecette = 2;
$sql = $db->prepare("SELECT * FROM recette JOIN utilisateur ON IdUtilisateur=IdCréateur WHERE IdRecette = :IdRecette ");
$sql->execute(['IdRecette' => $IdRecette]);
$resultat = $sql->fetch(PDO::FETCH_ASSOC);
function notation($note)
{
  switch ($note) {
    case 1:
      return '<img src="image/1etoile" alt="note" class="img_note"><span class="note"> 1/5 </span>';
      break;
    case 2:
      return '<img src="image/2etoile" alt="note" class="img_note"><span class="note"> 2/5 </span>';
      break;
    case 3:
      return '<img src="image/3etoile" alt="note" class="img_note"><span class="note"> 3/5 </span>';
      break;
    case 4:
      return '<img src="image/4etoile" alt="note" class="img_note"><span class="note"> 4/5 </span>';
      break;
    case 5:
      return '<img src="image/5etoile" alt="note" class="img_note"><span class="note"> 5/5 </span>';
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
    <?php echo '<h1>Recette de ' . $resultat["Nom"] . '</h1>' ?>
  </header>
  <div id="main">

    <?php
    echo notation($resultat["Notemoy"]);
    $pseudo = $resultat["Pseudo"];
    echo '<p id="auteur">Auteur : <span class="data">' . $pseudo . '</span></p>';
    ?>

    <div id="imgdiv">
      <?php echo '<img id="img" src=' . $resultat["Image"] . ' alt="gato">' ?>
    </div>
    <div id="info">
      <img id="temps" src="image/horloge.png" alt="horloge">
      <?php echo '<p id="tps_prep"><strong> Temps de préparation : </strong><span class="data">' . $resultat["Temps_prep"] . ' min</span></p>' ?>
      <img id="img_prix" src="image/euro.png" alt="euro">

      <?php $sql3 = $db->prepare("SELECT * FROM ingrédient WHERE Recette=:id");
      $sql3->execute(["id" => $IdRecette]);
      $resultat3 = $sql3->fetchAll();
      $prix_recette = 0;
      foreach ($resultat3 as $row) {
        $prix_recette += $row["Prix"];
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
    <?php echo ' <img src="image/groupe.png" alt="groupe" id="personne"> <span id="nbr">' . $resultat["Nb_personne"] . '</span> personnes ' ?>
    <!-- <input type="number" min="1" id="nbr" value="1"> -->
    <button onclick="incr()">+</button> <button onclick="decr()">-</button>
    <!-- <button onclick="calculer_prix()">envoyer</button> -->
    <br> <br>

    <ul>
      <?php $sql3 = $db->prepare("SELECT * FROM ingrédient WHERE Recette=:id");
      $sql3->execute(["id" => $IdRecette]);
      $resultat3 = $sql3->fetchAll();
      $j = 1;
      $tabQuantite = array();
      $prix_recette = 0;
      foreach ($resultat3 as $row) {
        echo "<li>" . $row["Nom"] . " : <span id=ing" . $j . ">" . $row["Quantité"] . "</span>" . $row["Unité"] . "</li>";
        $j += 1;
        $tabQuantite[] = $row["Quantité"];
        $prix_recette += $row["Prix"];
      };
      ?>

    </ul>
    <br>

    <h2>Instructions</h2>
    <div id="divprout">
      <img id="casserole" src="image/casserole.png" alt="casserole">
      <?php echo '<p><strong>Temps de cuisson : </strong><span class="data">' . $resultat["Temps_cuis"] . ' min </span></p>' ?>
    </div>
    <ol>
      <?php $instruction = str_replace(".", "<br><br>", $resultat["Instruction"]);
      echo $instruction;
      ?>
    </ol>
  </div>

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
      </form>
    </div>

    <?php
    /* affichage commentaire de la bdd*/
    if (!isset($_POST['tri'])) {
      $_POST["tri"] = "vide";
    }
    switch ($_POST["tri"]) {
      case "vide":
        $sql = $db->prepare("SELECT Commentaire,Note,Pseudo,Date FROM commentaire
         JOIN utilisateur ON IdAuteur=IdUtilisateur
          WHERE :idRecette=Recette_com");
        break;
      case "récent":
        $sql = $db->prepare("SELECT Commentaire,Note,Pseudo,Date FROM commentaire
            JOIN utilisateur ON IdAuteur=IdUtilisateur
             WHERE :idRecette=Recette_com ORDER BY Date DESC");
        break;
      case "ancien":
        $sql = $db->prepare("SELECT Commentaire,Note,Pseudo,Date FROM commentaire
              JOIN utilisateur ON IdAuteur=IdUtilisateur
               WHERE :idRecette=Recette_com ORDER BY Date");
        break;
      case "favo":
        $sql = $db->prepare("SELECT Commentaire,Note,Pseudo,Date FROM commentaire
                JOIN utilisateur ON IdAuteur=IdUtilisateur
                 WHERE :idRecette=Recette_com ORDER BY Note DESC");
        break;
      case "defavo":
        $sql = $db->prepare("SELECT Commentaire,Note,Pseudo,Date FROM commentaire
                  JOIN utilisateur ON IdAuteur=IdUtilisateur
                   WHERE :idRecette=Recette_com ORDER BY Note");
        break;
    }
    $sql->execute(["idRecette" => $IdRecette]);
    $resultat2 = $sql->fetchAll();
    $somme = 0;
    $i = 0;
    foreach ($resultat2 as $row) {
      echo "<div class='comment_afficher'>
      <div class='info_comment'>
      <img src='image/profil.png' alt='profil' class='profil_commentaire'>
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

    $Notemoy = $somme / $i;

    ?>

    <?php
    /* écrire commentaire*/
    if ($_SESSION["droit"] !== -1) {

      $id = $_SESSION["id"];
      $pseudo2 = $_SESSION["pseudo"];


      if (isset($_POST["commentaire"])) {
        $commentaire = $_POST["commentaire"];
      }
      if (isset($_POST["note"])) {
        $note = $_POST["note"];
      }
      if (isset($_POST["envoyer"])) {

        $sql = $db->prepare("INSERT INTO commentaire(Commentaire,Note,IdAuteur,Recette_com) VALUES (:Commentaire,:Note,:IdAuteur,:Recette_com)");
        $sql->execute(["Commentaire" => $commentaire, "Note" => $note, "IdAuteur" => $id, "Recette_com" => $IdRecette]);

        $Notemoy = ($Notemoy + $note) / 2;
        $sql2 = $db->prepare("UPDATE recette SET Notemoy = :note WHERE idRecette=:id");
        $sql2->execute(["note" => $Notemoy, "id" => $IdRecette]);
        echo "<meta http-equiv='refresh' content='0'>";
      }
      echo '<form method="post" id="comment-form">
    <div id="comment">
        <textarea name="commentaire" id="commentaire" placeholder="Ajouter un commentaire" maxlength="180"></textarea>
        <div id="notation"><strong>Note : </strong><span class="output">5</span>/5</div> 
        <input name="note" type="range" min="0" max="5" value="5">
        <input type="submit" name="envoyer" id="envoyer" placeholder="Envoyer">
    </div>
    </form>';
    }

    ?>

  </div>

</body>




<script>
  //bouton note
  const sizePicker = document.querySelector('input[type="range"]');
  const output = document.querySelector('.output');
  sizePicker.oninput = () => {
    output.textContent = sizePicker.value;
  }

  //Tri




  //nbr personne
  var prix = document.getElementById("prix").textContent
  const prix_uni = <?php echo $prix_recette ?>
  //prix unit ingredient

  let tabIngredient = [];
  <?php
  $n = count($tabQuantite);
  for ($i = 0; $i < $n; $i++) {
    echo "tabIngredient[$i] = $tabQuantite[$i];";
  }
  ?>
  let j = <?php echo $j; ?>;

  function incr() {
    var nbr = document.getElementById("nbr").textContent;
    nbr = parseInt(nbr);
    nbr += 1;
    document.getElementById("nbr").innerHTML = nbr;
    // calcul prix
    var resultat = prix_uni * nbr;
    document.getElementById("prix").innerHTML = resultat;
    //calcul quantité ing
    for (let i = 0; i < j; i++) {
      let prixIng = tabIngredient[i] * nbr;
      document.getElementById(`ing${i+1}`).innerHTML = prixIng;
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
      document.getElementById("prix").innerHTML = resultat;
      //calcul quantité ing 
      for (let i = 0; i < j; i++) {
        let prixIng = tabIngredient[i] * nbr;
        document.getElementById(`ing${i+1}`).innerHTML = prixIng;
      }
      //calcul ingrédient

    }

  }
</script>

</html>
