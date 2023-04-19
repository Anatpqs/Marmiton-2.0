
<!DOCTYPE html> 
<html> 

<head> 
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <title>Sa cook</title> 
    <link rel="stylesheet" href="styles/profil.css" />
    <script>
        
        function confirmation(){
            if (confirm("Êtes-vous sûr de vouloir supprimer votre profil ? Cette action est irréversible.")) {
            document.getElementById('confirm').value = 'yes';
            document.getElementsByName('suprofil')[0].value = 'Valider la suppresion';
            } 
            else {
                document.getElementById('confirm').value = 'no';
            }
        };
        var loadFile = function(event) {
        var imagefile = document.getElementById('imagefile');
        imagefile.src = URL.createObjectURL(event.target.files[0]);
        imagefile.onload = function() {
          URL.revokeObjectURL(imagefile.src) // free memory
        }
        document.getElementById("form1").submit();
      };
    </script>
</head> 
<body> 
    <header>
        <?php session_start()?>
        <div id="logo_div"><a href="accueil.php"><img id="logo" src="Images/cooking.png" alt="logo"></a></div>
        <div id="titre"><?php echo '<h1>Profil ',$_SESSION["pseudo"],'</h1>' ?></div>
        <div class="profil">
        <ul class="navbar">
            <?php if(filesize("Images/Pdp/" . $_SESSION['id'] . ".jpg")>50){
                echo"<li class='li'><img id='imagefile' class='icon' src='Images/Pdp/",$_SESSION['id'],".jpg'>";
            }
            else{
                echo"<li class='li'><img id='imagefile'class='icon' src='Images/Pdp/userblanc.png'>";    
            }?>

                <ul>
                    <?php if ($_SESSION["droit"] == 0) {
                        echo '<li><a onclick="document.getElementById(\'file-input\').click();">Changer de photo
                        </a></li>
                        <form method="post" enctype="multipart/form-data" id="form1">
                        <input type="file" id="file-input" onchange="loadFile(event)" name="file">
                        
                        </form>
                        <li><a href="deconnexion.php">Déconnexion</a></li> ';
                    } // Si l'utilisateur est connecté, on affiche les liens de profil et de déconnexion
                    ?>
                    <?php if ($_SESSION["droit"] == 1) {
                        echo '<li><a href="admin.php">Admin</a></li>
                        <li><a onclick="document.getElementById(\'file-input\').click();">Changer de photo
                        </a></li>
                        <form method="post" enctype="multipart/form-data" id="form1">
                        <input type="file" id="file-input" onchange="loadFile(event)" name="file">
                        
                        </form>
                        <li><a href="deconnexion.php">Déconnexion</a></li> '; //Admin
                    }
                    ?>
                </ul>
            </li>
        </ul>
    </div>
   
    </header>
    <div>
        <?php
        function notation($note){
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
}

        if ($_SESSION["droit"] != -1) {
            include 'database.php';
            global $db;
            if(isset($_FILES['file'])) {
                $file_name = $_FILES['file']['name'];
                $file_tmp = $_FILES['file']['tmp_name'];
                $file_type = $_FILES['file']['type'];
                $file_size = $_FILES['file']['size'];
                $target_dir = "Images/Pdp/"; // dossier de destination
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
                    echo "<div id=changpdpreu>La photo a bien été changer.</div>";
                    rename($target_file,$target_dir . $_SESSION['id'] . ".jpg");
                    
                    }
                    else {
                    echo "Erreur lors du téléchargement du fichier.";
                    }
                }
            }
            
            ?>
            <div id="commentairediv">
            <div id="comment_select">
                <h2>Commentaires</h2>
                <form method="post">
                    <select name="tri" id="tri">

                        <option value="avant">Avant</option>
                        <option value="aprés">Aprés</option>
                    <input type="date" id="datePicker" name="datePicker">
                    <input type="submit" name="submit" value="Aplliquer le tri"/>
                </form>
                <script>document.getElementById('datePicker').valueAsDate = new Date();</script>
            </div>
            <?php
            if (!isset($_POST['tri'])) {
                $sql = $db->prepare("SELECT Commentaire, Note, Date FROM Commentaire
                     WHERE IdAuteur = :Recette_com
                     ORDER BY Date DESC");
                $sql->execute(['Recette_com' => $_SESSION['id']]);
                $result = $sql->fetchAll(PDO::FETCH_ASSOC);        
                foreach($result as $row) {
                    echo "<div class='comment_afficher'>
                        <div class='info_comment'>
                            <img src='Images/Pdp/".$_SESSION['id'].".jpg' alt='profil' class='profil_commentaire'>
                                <div class='info_comment2'>
                                    <strong><span class='nom_comment'></span></strong>
                                    <span class='note_comment'>" . notation($row['Note']) . "</span>
                                </div>

                                <form method='post' class='supprComm' >
                                <button type='submit' name='suprComm'>supprimer le commentaire</button>
                            </form>
                        </div>

                    <div class='comment_text'>" . $row['Commentaire'] . "</div>
                    <p class='date_comment'>" . $row['Date'] . "</p>
                    </div>
                    <div id='barre_commentaire'></div>";}
}
else{
    switch ($_POST["tri"]) {
            
        case "aprés":
            $sql = $db->prepare("SELECT Commentaire,Note,Date FROM Commentaire
            WHERE IdAuteur=:Recette_com
            AND Date>:Dateselect 
            ORDER BY Date");
            break;
        case "avant":
            $sql = $db->prepare("SELECT Commentaire,Note,Date FROM Commentaire
            WHERE IdAuteur=:Recette_com
            AND Date<:Dateselect
            ORDER BY Date");    
            break;
        }
        $sql->execute(['Recette_com'=>$_SESSION['id'],
                       'Dateselect'=>$_POST['datePicker']]);
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);        
        foreach($result as $row){
            echo "<div class='comment_afficher'>
                <div class='info_comment'>
                    <img src='Images/Pdp/".$row["IdAuteur"].".jpg' alt='profil' class='profil_commentaire'>
                        <div class='info_comment2'>
                        <strong><span class='nom_comment'></span></strong>
                        <span class='note_comment'>" . notation($row['Note']) . "</span>
                    </div>

                        <form method='post' class='supprComm' >
                        <button type='submit' name='suprComm'>supprimer le commentaire</button>
                    </form>
                </div>

            <div class='comment_text'>" . $row['Commentaire'] . "</div>
            <p class='date_comment'>" . $row['Date'] . "</p>
            </div>
            <div id='barre_commentaire'></div>";}

}
}

            
        else {
            header("Location:accueil.php");
        }
        ?>
        </div>
        <div id=favoris>
            <h2>Favoris</h2>
                <?php
                    $r=$db->prepare("SELECT * FROM Recette JOIN Recette_pref ON IdRecette=Id_recette WHERE Id_utilisateur = :ID");
                    $r->execute(['ID'=>$_SESSION['id']]);
                    $result = $r->fetchAll(PDO::FETCH_ASSOC);        
                    foreach($result as $recette){
                        
                        echo "<div class='info_favo'><img src=/Images/Recette/",$recette['Id_recette'],".jpg class='image_favo'></img>
                        <div><label>", $recette['Nom'],"   Note : ", $recette['Notemoy'],"</label></div>
                        </div>
                        <div id='barre_commentaire'></div>";
                    }
                ?>
            
        </div>
        <div id=favoris>
            <h2>Mes Recette</h2>
            <?php
            $r=$db->prepare("SELECT * FROM Recette WHERE IdCréateur = :IdCreateur");
            $r->execute(['IdCreateur'=>$_SESSION['id']]);
            $result = $r->fetchAll(PDO::FETCH_ASSOC);        
            foreach($result as $recette){
                echo "<div class='info_favo'><img src=/Images/Recette/",$recette['IdRecette'],".jpg class='image_favo'></img>
                <div><label>", $recette['Nom'],"   Note : ", $recette['Notemoy'],"</label></div>
                </div>
                <div id='barre_commentaire'></div>";            }
            ?>
        </div>
    </div>
    <form id="supresionrec" method="post">
        <input type="hidden" name="confirm" id="confirm" value="">
        <input type="submit" name="suprofil" value="Supprimer le compte">
        <select name="typesupr">
            <option value="suprprofil" selected> Suprimer uniquement le compte</option>
            <option value="suprrecette">Suprimer le compte et les recette</option>
        </select>
    </form>
</body> 
<footer>
  <a> Nous contacter </a>
</footer>
<?php
if ($_SESSION["droit"] != -1) {
        if (isset($_POST['suprofil'])) {
            if (isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
                $typesupr=1;
                if($_POST['typesupr']=="suprrecette"){
                    $stmt = $db->prepare("DELETE FROM Utilisateur WHERE Login = :Login");
                    $stmt->execute([':Login' => $login]);
                    unlink('Images/Pdp/'.$resultat['IdUtilisateur'].".jpg");
                    header("Location:deconnexion.php");
                    exit();
                }
                else{
                    $stmt = $db->prepare("UPDATE Recette SET IdCréateur=1 WHERE IdCréateur = :createur");
                    $stmt->execute([':createur' =>$_SESSION['id']]);
                    $stmt = $db->prepare("DELETE FROM Utilisateur WHERE Login = :Login");
                    $stmt->execute([':Login' => $login]);
                    unlink('Images/Pdp/'.$resultat['IdUtilisateur'].".jpg");
                    header("Location:deconnexion.php");
                    exit();
                }
            }
             echo'<script>confirmation()</script>';
        }    
        if (isset($_POST['suprComm'])) {
            $commasupr = $_POST['suprComm'];
            $stmt = $db->prepare("DELETE FROM Commentaire WHERE IdCommentaire = :Idcomm");
            $stmt->execute([':Idcomm' =>$commasupr]);
            header("Location:profil.php");
        }
        }

?>
</html>
