<!DOCTYPE html>
<html>
<head>
	<title>Ajouter une recette</title>
</head>
<body>
    
	<h1>Ajouter une recette</h1>
	<form method="post" action="">
		<label for="titre">Titre de la recette :</label>
		<input type="text" name="titre" id="titre" required><br><br>
		
        <label for="Nb_personne">Nombre de personne :</label>
		<input type="number" name="Nb_personne" id="Nb_personne" required><br><br>

		<label for="Temps_prep">Temps de préparation :</label>
		<input type="number" name="Temps_prep" id="Temps_prep" required><br><br>
		
		<label for="Temps_cuis">Temps de cuisson :</label>
		<input type="number" name="Temps_cuis" id="Temps_cuis" required><br><br>
		
        <label for="Description">Description :</label><br>
		<textarea name="Description" id="Description" required></textarea><br><br>

		<label for="Instruction">Instruction :</label><br>
		<textarea name="Instruction" id="Instruction" required></textarea><br><br>
		
		<input type="submit" name="submit" value="Ajouter la recette">
	</form>
</body>
</html>

<!------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------------>

<?php

// Informations de connexion à la base de données
//! A changer suivant vos identifiant à vous \\\\
$servername = "localhost";
$username = "admin";
$password = "Admin85$";
$dbname = "mydb";
//!\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

if (isset($_POST['submit'])) {
    // Récupération des données du formulaire
    $Nom = strval($_POST['titre']); 
    $Description = strval($_POST['Description']);
    $Instruction = strval($_POST['Instruction']); 
    $Nb_personne = intval($_POST['Nb_personne']);
    $Temps_prep = intval($_POST['Temps_prep']);
    $Temps_cuis = intval($_POST['Temps_cuis']);

    try {
        // Connexion à la base de données
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        
        // Configuration de PDO pour l'affichage des erreurs
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête SQL d'insertion
        $sql = "INSERT INTO Recette (Nom, IdCréateur, Notemoy, Nb_personne, Temps_prep, Temps_cuis, Description, Instruction, Image) 
            VALUES ('$Nom', '1', NULL, '$Nb_personne', '$Temps_prep', '$Temps_cuis', '$Description', '$Instruction', NULL);";
        

        //TODO Protection contre les injections SQL
    

        // Exécution de la requête
        $conn->exec($sql);

        // Affichage d'un message de confirmation
        echo "<script>alert('Recette ajouter !')</script>";

    } catch(PDOException $e) {
        // Affichage de l'erreur en cas de problème lors de l'exécution de la requête
        echo $sql . "<br>" . $e->getMessage();
    }

    // Fermeture de la connexion à la base de données
    $conn = null;
}
?>