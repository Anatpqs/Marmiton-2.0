
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
    <form action="index.php" method="post">
        <input type="submit" value="Aller à l'accueil">
    </form>
    <div>
        <?php
        session_start();
        if ($_SESSION["droit"] != -1) {
            include 'database.php';
            global $db;
            $login = $_SESSION['Login'];
            $c = $db->prepare("SELECT Pseudo FROM Utilisateur WHERE Login = :Login");
            $c->execute(['Login' => $login]);
            $resultat = $c->fetch(PDO::FETCH_ASSOC);
            echo $resultat['Pseudo'],'<br>';
            $r=$db->prepare("SELECT * FROM Commentaire WHERE IdAuteur = :IdAuteur");
            $r->execute(['IdAuteur'=>$_SESSION['id']]);
            $result = $r->fetchAll(PDO::FETCH_ASSOC);        
            foreach($result as $comm){
                echo $comm['Commentaire'],'
                <form method="post">
                    <button type="submit" name= "suprComm" value=',$comm['IdCommentaire'],'>suprimier le commentaire</button>
                </form><br><br>';
            }
        }
        else {
            header("Location:index.php");
        }
        ?>
        <div>
            <?php
            $r=$db->prepare("SELECT * FROM Recette WHERE IdCréateur = :IdCreateur");
            $r->execute(['IdCreateur'=>$_SESSION['id']]);
            $result = $r->fetchAll(PDO::FETCH_ASSOC);        
            foreach($result as $recette){
                echo "<img src=/Image/Recette/",$recette['IdRecette'],".jpg width='150px' height='150px'> </img>", $recette['Nom'],"   Note : ", $recette['Notemoy'];
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
                    $stmt = $db->prepare("DELETE FROM Commentaire WHERE IdAuteur = :auteur");
                    $stmt->execute([':auteur' =>$_SESSION['id']]);
                    $stmt = $db->prepare("DELETE Commentaire FROM Recette JOIN Commentaire ON IdRecette=Recette_com WHERE IdCréateur= :createur;");                    
                    $stmt->execute([':createur' =>$_SESSION['id']]);
                    $stmt = $db->prepare("DELETE FROM Recette WHERE IdCréateur = :Idcomm");
                    $stmt->execute([':Idcomm' =>$_SESSION['id']]);
                    $stmt = $db->prepare("DELETE FROM Utilisateur WHERE Login = :Login");
                    $stmt->execute([':Login' => $login]);
                    header("Location:deconnexion.php");
                    exit();
                }
                else{
                    $stmt = $db->prepare("DELETE FROM Commentaire WHERE IdAuteur = :auteur");
                    $stmt->execute([':auteur' =>$_SESSION['id']]);
                    $stmt = $db->prepare("UPDATE Recette SET IdCréateur=1 WHERE IdCréateur = :createur");
                    $stmt->execute([':createur' =>$_SESSION['id']]);
                    $stmt = $db->prepare("DELETE FROM Utilisateur WHERE Login = :Login");
                    $stmt->execute([':Login' => $login]);
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
    header("Location:index.php");
}
?>
</html>
