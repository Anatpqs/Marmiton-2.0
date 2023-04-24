<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sa cook</title>
    <link rel="stylesheet" href="styles/profil.css" />
    <script>

        // Fonction pour recharger la page
        function reloadPage() {
            window.location.reload();
        };

        // Fonction pour demander confirmation avant de supprimer un profil
        function confirmation() {
            if (confirm("Êtes-vous sûr de vouloir supprimer votre profil ? Cette action est irréversible.")) {
                // Si l'utilisateur confirme, on modifie la valeur de l'input hidden "confirm" à "yes" pour valider la suppression
                document.getElementById('confirm').value = 'yes';
                document.getElementsByName('suprofil')[0].value = 'Valider la suppression';
            }
            else {
                // Sinon, on modifie la valeur de l'input hidden "confirm" à "no" pour annuler la suppression
                document.getElementById('confirm').value = 'no';
            }
        };

        // Fonction pour afficher l'image sélectionnée avant l'upload
        var loadFile = function (event) {
            var imagefile = document.getElementById('imagefile');
            imagefile.src = URL.createObjectURL(event.target.files[0]);
            imagefile.onload = function () {
                URL.revokeObjectURL(imagefile.src) // free memory
            }
            // On soumet automatiquement le formulaire au chargement de l'image pour uploader l'image
            document.getElementById("form1").submit();
        };
    </script>
</head>

<body>
    <?php session_start();
    if ($_SESSION["droit"] == -1) {
        //si l'utilisateur n'est pas connécté on le renvoi a la page d'accueil
        header("Location:accueil.php");
    } ?>
    <header>
        <div id="logo_div"><a href="accueil.php"><img id="logo" src="Images/cooking.png" alt="logo"></a></div>
        <div id="titre">
            <?php echo '<h1>Profil ', $_SESSION["pseudo"], '</h1>' ?>
        </div>
        <div class="profil">
            <ul class="navbar">
                
                <?php if (filesize("Images/Pdp/" . $_SESSION['id'] . ".jpg") > 50) {
                    // Si l'image de profil existe, on l'affiche
                    $image=1;
                    echo "<li class='li'><img id='imagefile' class='icon' src='Images/Pdp/", $_SESSION['id'], ".jpg'>";
                } else {
                    // Sinon, on affiche une image par défaut
                    $image=0;
                    echo "<li class='li'><img id='imagefile'class='icon' src='Images/Pdp/userblanc.png'>";
                }

                echo '<ul>';
                if ($_SESSION["droit"] == 1  || $_SESSION["droit"] == -2) {
                    // Si l'utilisateur est connecté en tant qu'admin, on affiche le lien la page admin en plus de celui pour changer la photo de profil et se déconnecter 
                    echo '<li><a href="admin.php">Admin</a></li>';
                }
                echo '<li><a onclick="document.getElementById(\'file-input\').click();">Changer de photo
                        </a></li>
                        <form method="post" enctype="multipart/form-data" id="form1">
                        <input type="file" id="file-input" onchange="loadFile(event)" name="file">
                        
                        </form>
                        <li><a href="deconnexion.php">Déconnexion</a></li> 

            </ul>
            </li>
            </ul>
        </div>

    </header>
    <div>';

                // Fonction permettant de créer l'affichage des notes sous forme d'étoiles
                function notation($note)
                {
                    switch ($note) {
                        case 0:
                            return '<img src="Images/pas_de_note.png" alt="note" class="img_note"><span class="note"><strong> ?/5 </strong></span>';

                        case 1:
                            return '<img src="Images/1etoile.png" alt="note" class="img_note"><span class="note"><strong> 1/5 </strong></span>';

                        case 2:
                            return '<img src="Images/2etoile.png" alt="note" class="img_note"><span class="note"><strong> 2/5 </strong></span>';

                        case 3:
                            return '<img src="Images/3etoile.png" alt="note" class="img_note"><span class="note"><strong> 3/5 </strong></span>';

                        case 4:
                            return '<img src="Images/4etoile.png" alt="note" class="img_note"><span class="note"><strong> 4/5 </strong></span>';

                        case 5:
                            return '<img src="Images/5etoile.png" alt="note" class="img_note"><span class="note"><strong> 5/5 </strong></span>';

                    }
                }


                include 'database.php'; //Connexion à la base de donnée
                global $db;


                ?>
                <!-- Cette section contient les commentaires de l'utilisateur -->
                <div id="commentairediv">
                    <div id="comment_select">
                        <h2>Commentaires</h2>
                        <form method="post">
                            <select name="tri" id="tri">
                                <option value="avant">Avant</option>
                                <option value="aprés">Aprés</option>
                                <input type="date" id="datePicker" name="datePicker">
                                <!--Date à laquelle on compare les commentaires-->
                                <input type="submit" name="submit" value="Aplliquer le tri" />
                        </form>
                        <script>document.getElementById('datePicker').valueAsDate = new Date();</script>
                        <!-- Initialise la valeur à la date du jour-->
                    </div>
                    <?php
                    if (!isset($_POST['tri'])) {
                        //requete pour obtenir les commentaire de l'utilisateur et leur information des l'arrivé dans la page profil
                        $sql = $db->prepare("SELECT Commentaire, Note,IdCommentaire, Date, Recette_com, Nom FROM Commentaire JOIN Recette ON Recette_com=IdRecette
                     WHERE IdAuteur = :Recette_com
                     ORDER BY Date DESC");
                        $sql->execute(['Recette_com' => $_SESSION['id']]);
                        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($result as $row) {
                            //affichage info commentaire
                            echo "<div class='comment_afficher'>
                        <div class='info_comment'>";
                        if($image==1){
                            echo"<img src='Images/Pdp/" . $_SESSION['id'] . ".jpg' alt='profil' class='profil_commentaire'>";
                        }
                        else{
                            echo"<img src='Images/profil.png' alt='profil' class='profil_commentaire'>";
                        } 
                                echo"<div class='info_comment2'>
                                    <strong><span class='nom_comment'></span></strong>
                                    <span class='note_comment'>" . notation($row['Note']) . $row['Nom'] . "</span>
                                </div>
                                
                                <form method='post' class='supprComm'>         <!--Bouton pour suprimer le commentaire associer -->
                                <button type='submit' name='suprComm'>supprimer le commentaire</button>
                                <input type='hidden' name='idcomm' id='idcomm' value='" . $row['IdCommentaire'] . "'></input>    <!--on garde en mémoir la recette et l'id du commentaire pour la note moyenne a recalculer -->
                                <input type='hidden' name='idrecette' id='idrecette' value='" . $row['Recette_com'] . "'</input>
                            </form>
                        </div>
                    <!-- affichage commentaire et date -->
                    <div class='comment_text'>" . $row['Commentaire'] . "</div>
                    <p class='date_comment'>" . $row['Date'] . "</p>
                    </div>
                    <div id='barre_commentaire'></div>";
                        }
                    } else {
                        switch ($_POST["tri"]) {
                            //si l'utilisateur tri les commentaire il faut aussi prendre la date en considération dans la requête
                    
                            case "aprés":
                                $sql = $db->prepare("SELECT Commentaire,Note,Date ,Nom FROM Commentaire ,IdCommentaire, Recette_com JOIN Recette ON Recette_com=IdRecette
            WHERE IdAuteur=:Recette_com
            AND Date>:Dateselect 
            ORDER BY Date");
                                break;
                            case "avant":
                                $sql = $db->prepare("SELECT Commentaire,Note,Date, Nom, IdCommentaire, Recette_com FROM Commentaire JOIN Recette ON Recette_com=IdRecette
            WHERE IdAuteur=:Recette_com
            AND Date<:Dateselect
            ORDER BY Date");
                                break;
                        }
                        $sql->execute([
                            'Recette_com' => $_SESSION['id'],
                            'Dateselect' => $_POST['datePicker']
                        ]);
                        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($result as $row) {
                            echo "<div class='comment_afficher'>
                        <div class='info_comment'>
                            <img src='Images/Pdp/" . $_SESSION['id'] . ".jpg' alt='profil' class='profil_commentaire'>
                                <div class='info_comment2'>
                                    <strong><span class='nom_comment'></span></strong>
                                    <span class='note_comment'>" . notation($row['Note']) . $row['Nom'] . "</span>
                                </div>
                                
                                <form method='post' class='supprComm'>         <!--Bouton pour suprimer le commentaire associer -->
                                <button type='submit' name='suprComm'>supprimer le commentaire</button>
                                <input type='hidden' name='idcomm' id='idcomm' value='" . $row['IdCommentaire'] . "'></input>    <!--on garde en mémoir la recette et l'id du commentaire pour la note moyenne a recalculer -->
                                <input type='hidden' name='idrecette' id='idrecette' value='" . $row['Recette_com'] . "'</input>
                            </form>
                        </div>
                    <!-- affichage commentaire et date -->
                    <div class='comment_text'>" . $row['Commentaire'] . "</div>
                    <p class='date_comment'>" . $row['Date'] . "</p>
                    </div>
                    <div id='barre_commentaire'></div>";
                        }

                    }
                    ?>
                    <!-- Cette section contient les recettes favorites de l'utilisateur -->
                </div>
                <div id=favoris>
                    <h2>Favoris</h2>
                    <?php
                    //on recupere les recette favorite lié a l'Id utilisateur
                    $r = $db->prepare("SELECT * FROM Recette JOIN Recette_pref ON IdRecette=Id_recette WHERE Id_utilisateur = :ID");
                    $r->execute(['ID' => $_SESSION['id']]);
                    $result = $r->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $recette) {
                        //Affichage des recette et leur info avec lien dans image
                        echo "<div class='info_favo'>
                            <form id='myForm_" . $recette['IdRecette'] . "' action='recette.php' method='post'>
                                <input type='hidden' name='idRecette' value=" . $recette['IdRecette'] . ">
                            </form>
                            <a href='#' onclick=\"document.getElementById('myForm_" . $recette['IdRecette'] . "').submit();\">
                                <img src=/Images/Recette/", $recette['IdRecette'], ".jpg class='image_favo'>
                            </a>
                            <div class='info_re'><h3>", $recette['Nom'], "</h3></div>
                            <div class='pous'>", notation($recette['Notemoy']), "</div></div>
                            <div id='barre_commentaire'></div>";
                    }
                    ?>

                </div>
                <!-- Cette section contient les recettes créés par l'utilisateur -->
                <div id=favoris>
                    <h2>Mes Recettes</h2>
                    <?php
                    //requete pour recette avec l'utilisateur comme créateur
                    $r = $db->prepare("SELECT * FROM Recette WHERE IdCréateur = :IdCreateur");
                    $r->execute(['IdCreateur' => $_SESSION['id']]);
                    $result = $r->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $recette) {
                        //Affichage des recette et leur info avec lien dans image
                        echo "<div class='info_favo'>
                <form id='myForm_" . $recette['IdRecette'] . "' action='recette.php' method='post'>
                    <input type='hidden' name='idRecette' value=" . $recette['IdRecette'] . ">
                </form>
                <a href='#' onclick=\"document.getElementById('myForm_" . $recette['IdRecette'] . "').submit();\">
                    <img src=/Images/Recette/", $recette['IdRecette'], ".jpg class='image_favo'>
                </a>
                <div class='info_re'><h3>", $recette['Nom'], "</h3></div>
                <div class='pous'>", notation($recette['Notemoy']), "</div></div>
                <div id='barre_commentaire'></div>";
                    }
                    ?>
                </div>
        </div>
        <!-- Cette section contient le formulaire pour la supression de compte  -->
        <form id="supresionrec" method="post">
            <input type="hidden" name="confirm" id="confirm" value="no">
            <input type="submit" name="suprofil" value="Supprimer le compte">
            <select name="typesupr">
                <option value="suprprofil" selected> Suprimer uniquement le compte</option>
                <option value="suprrecette">Suprimer le compte et les recette</option>
            </select>
        </form>
</body>

<!-- Cette section contient toute les consequence des différent form utilisé  -->
<?php
if (isset($_POST['suprofil'])) {
    //cette partie est active lorque l'utilisateur appui sur suprimé mon profil

    if (isset($_POST['confirm']) && $_POST['confirm'] == 'no')
    {
        echo '<script>confirmation()</script>';//Fonction de double vérification 
    }

    if (isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
        // Test sur la duble vérification
        $typesupr = 1;
        if ($_POST['typesupr'] == "suprrecette") {
            //test sur supression avec ou sans recette
            $stmt = $db->prepare("DELETE FROM Utilisateur WHERE Login = :Login");
            $stmt->execute([':Login' => $_SESSION["Login"]]);//ici on suprime les recette, les commentaire ON CASCADE
        } 
        else {
            $stmt = $db->prepare("UPDATE Recette SET IdCréateur=1 WHERE IdCréateur = :createur");//on change le créateur des recette par "Utilisateur Suprimé"
            $stmt->execute([':createur' => $_SESSION['id']]);
            $stmt = $db->prepare("DELETE FROM Utilisateur WHERE Login = :Login");//Supression ON CASCADE des commentaire 
            $stmt->execute([':Login' => $_SESSION["Login"]]);
        }
        unlink('Images/Pdp/' . $_SESSION["id"]. ".jpg");//On suprimme sa photo de profile de la base de donné
        session_destroy();
        session_unset();
        echo '<meta http-equiv="refresh" content="0; URL=accueil.php">';//On va sur la page d'accueil
    }
   
}
if (isset($_POST['suprComm'])) {
    //Supression commentaire
    $commasupr = $_POST['idcomm'];
    $stmt = $db->prepare("DELETE FROM Commentaire WHERE IdCommentaire = :Idcomm");//supression de la bdd
    $stmt->execute([':Idcomm' => $commasupr]);
    $q = $db->prepare("SELECT * FROM Commentaire WHERE Recette_com=:recette");//Requete sur les commentaire restant de la recette
    $q->execute([':recette' => $_POST['idrecette']]);
    $count = $db->prepare("Select Count(*) From Commentaire WHERE Recette_com=:recette");//comptage de ces commentaire
    $count->execute([':recette' => $_POST['idrecette']]);
    $c = $count->fetch(PDO::FETCH_ASSOC);
    if ($c['Count(*)'] == 0) {
        // si il ne reste plus de commentaire on met la note moyenne a NULL
        $q0 = $db->prepare("UPDATE Recette SET Notemoy=NULL WHERE IdRecette=:commsup");
        $q0->execute([':commsup' => $_POST['idrecette']]);
    } else {
        //si il reste des commentaire on calcul la nouvelle moyenne
        $s = 0;
        foreach ($q as $commentaire) {
            $s += $commentaire['Note'];
            $s = $s / $c['Count(*)'];
            $q0 = $db->prepare("UPDATE Recette SET Notemoy=:note WHERE IdRecette=:commsup");//on actualise la note de la rectete
            $q0->execute([
                ':commsup' => $_POST['idrecette'],
                ':note' => $s
            ]);
        }
    }
    echo "<meta http-equiv='refresh' content='0'>";//on rafraichi la page pour que le commentaire soit bien suprimé
}
if (isset($_FILES['file'])) {
    //lorsque l'utilisateur clique sur changer d'image de profil dans le menue déroulant
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_type = $_FILES['file']['type'];
    $file_size = $_FILES['file']['size'];
    $target_dir = "Images/Pdp/"; // dossier de destination
    $target_file = $target_dir . basename($file_name);

    // Vérifier le type de fichier
    $allowed_types = array('image/jpeg', 'image/png');
    if (!in_array($file_type, $allowed_types)) {
        echo "Erreur: Seules les images JPEG et PNG sont autorisées.";
    }
    // Vérifier la taille du fichier
    else if ($file_size > 5000000) { // 5 Mo maximum
        echo "Erreur: La taille du fichier doit être inférieure à 5 Mo.";
    } else {
        if (move_uploaded_file($file_tmp, $target_file)) {
            echo "<script>alert('La photo à bien été changer.')</script>";
            rename($target_file, $target_dir . $_SESSION['id'] . ".jpg");

        } else {
            echo "Erreur lors du téléchargement du fichier.";
        }
    }
}
?>
<footer>
    <a> Nous contacter </a>
</footer>
</html>
