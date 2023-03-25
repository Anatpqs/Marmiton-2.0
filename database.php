<?php
define('HOST', 'localhost');
define('DB_NAME', 'mydb');
define('USER', 'Utilisateur');
define('PASS', 'Mot_de-passe');

try {
    $db = new PDO("mysql:host=" . HOST . ";dbname=" . DB_NAME, USER, PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    echo $e->getMessage();
}
?>
