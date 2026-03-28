<?php
$host = "mysql-loute.alwaysdata.net";
$dbname = "loute_labo";
$username = "loute_labo";   // WAMP
$password = "loute210982";       // vide       
$port = 3306;


try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    // echo "Connexion PDO réussie !";
} catch (PDOException $e) {
    die("Erreur de connexion PDO : " . $e->getMessage());
}
?>





//$host = "localhost";
//$user = "root";
//$password = "";
//$dbname = "labo";