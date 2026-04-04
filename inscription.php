

<?php
// Connexion à la base de données
$host = "mysql-loute.alwaysdata.net";
$dbname = "loute_labo";
$username = "loute_labo";   // WAMP
$password = "loute210982";       // vide       
$port = 3306;

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    // Vérifier si l'email existe déjà dans la table patient
    $verif = $conn->prepare("SELECT id FROM patient WHERE email = ?");
    $verif->bind_param("s", $email);
    $verif->execute();
    $verif->store_result();

    if ($verif->num_rows > 0) {
        $message = "❌ Cet email est déjà utilisé.";
    } else {

        // Insertion dans la table patient
        $stmt = $conn->prepare("INSERT INTO patient (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nom, $email, $mot_de_passe);

        if ($stmt->execute()) {
            $message = "✅ Inscription réussie !";
        } else {
            $message = "❌ Erreur lors de l'inscription : " . $stmt->error;
        }

        $stmt->close();
    }

    $verif->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>

<h2>Inscription</h2>

<?php if (!empty($message)) echo "<p>$message</p>"; ?>

<form method="POST">
    <label>Nom :</label><br>
    <input type="text" name="nom" required><br><br>

    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="mot_de_passe" required><br><br>

    <button type="submit">S'inscrire</button>
</form>

</body>
</html>