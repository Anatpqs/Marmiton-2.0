
<!DOCTYPE html> 
<html> 
<head> 
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <title>Sa cook</title> 
    <style type="text/css">
    form {
    display: inline-block;
    }
    body{
        background-color: grey;
    }
    </style>
    <script>
        function confirmation(){
            if (confirm("Êtes-vous sûr de vouloir supprimer votre profil ? Cette action est irréversible.")) {
            document.getElementById('confirm').value = 'yes';
            document.getElementsByName('suprofil')[0].value = 'Valider la suppresion';
            } 
            else {
                document.getElementById('confirm').value = 'no';
            }
        }
    </script>
</head> 
<body> 
    <form action="accueil.php" method="post">
        <input type="submit" value="Aller à l'accueil">
    </form>
    <div>
        <?php
        session_start();
        if ($_SESSION["droit"] != -1) {
            include 'database.php';
            global $db;
            $login = $_SESSION['Login'];
            $c = $db->prepare("SELECT Photo,Pseudo,IdUtilisateur FROM Utilisateur WHERE Login = :Login");
            $c->execute(['Login' => $login,]);
            $resultat = $c->fetch(PDO::FETCH_ASSOC);
            $taille_image=filesize("Images/Pdp/" . $_SESSION['id'] . ".jpg");

            if($taille_image>50){
                echo "<img src=/Images/Pdp/",$_SESSION['id'],".jpg width='100px' height='100px'> </img>";
            }
            else{echo "<img src=/Images/Pdp/userblanc.png width='100px' height='100px'> </img>";}
            echo $resultat['Pseudo'],'
                <form method="post" enctype="multipart/form-data">
                    <label for="file">Sélectionnez Votre nouvelle photo de profil:</label>
                    <input type="file" id="file" name="file">
                    <input type="submit" value="Télécharger">

                </form>';
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
                    echo "Le fichier a été téléchargé avec succès.";
                    rename($target_file,$target_dir . $resultat['IdUtilisateur'] . ".jpg");
                    header("refresh: 0");
                    
                    }
                    else {
                    echo "Erreur lors du téléchargement du fichier.";
                    }
                }
            }
            
            ?>
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
                foreach($result as $comm) {
                    echo $comm['Commentaire'],'
                    <form method="post">
                        <button type="submit" name="suprComm" value=',$comm['IdCommentaire'],'>supprimer le commentaire</button>
                    </form><br><br>';}
}

            switch ($_POST["tri"]) {
            
                case "aprés":
                    $sql = $db->prepare("SELECT Commentaire,Note,Date FROM Commentaire
                    WHERE IdAuteur=:Recette_com
                    AND Date>:dateselect 
                    ORDER BY Date");
                    break;
                case "avant":
                    $sql = $db->prepare("SELECT Commentaire,Note,Date FROM Commentaire
                    WHERE IdAuteur=:Recette_com
                    AND Date<:Dateselect
                    ORDER BY Date");    
                    break;
                $sql->execute(['Recette_com'=>$_SESSION['id'],
                               'Dateselect'=>$_POST['datePicker']]);
                $result = $sql->fetchAll(PDO::FETCH_ASSOC);        
                foreach($result as $comm){
                    echo $comm['Commentaire'],'
                    <form method="post">
                        <button type="submit" name= "suprComm" value=',$comm['IdCommentaire'],'>suprimier le commentaire</button>
                    </form><br><br>';
     }
            }

        }
        else {
            header("Location:accueil.php");
        }
        ?>
        <div>
            <?php
            $r=$db->prepare("SELECT * FROM Recette WHERE IdCréateur = :IdCreateur");
            $r->execute(['IdCreateur'=>$_SESSION['id']]);
            $result = $r->fetchAll(PDO::FETCH_ASSOC);        
            foreach($result as $recette){
                echo "<img src=/Images/Recette/",$recette['IdRecette'],".jpg width='150px' height='150px'> </img>", $recette['Nom'],"   Note : ", $recette['Notemoy'];
            }
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
else {
    header("Location:accueil.php");
}
?>
</html>
