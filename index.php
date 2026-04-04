<?php
$host = "mysql-loute.alwaysdata.net";
$dbname = "loute_labo";
$username = "loute_labo";   // WAMP
$password = "loute210982";       // vide       
$port = 3306;
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
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
/******************** AFFICHAGE ERREURS (DEV) ********************/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/******************** SESSION SÉCURISÉE ********************/
session_set_cookie_params([
    'httponly' => true,
    'secure' => false, // ⚠️ mettre TRUE uniquement si HTTPS
    'samesite' => 'Strict'
]);

session_start();

/******************** INCLUDE HEADER ********************/
include 'includes/header.php';

/******************** CONNEXION BDD ********************/
try {
    $pdo = new PDO(
        "mysql:host=mysql-loute.alwaysdata.net;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Erreur de connexion : " . htmlspecialchars($e->getMessage()));
}

/******************** DÉCONNEXION ********************/
if (isset($_GET['action']) && $_GET['action'] === 'logout') {

    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();
    header("Location: index.php");
    exit;
}

/******************** MODE (connexion / inscription) ********************/
$mode = ($_GET['action'] ?? '') === 'inscription' ? 'inscription' : 'connexion';
$message = '';
$messageType = '';

/******************** TRAITEMENT FORMULAIRE ********************/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ===== INSCRIPTION ===== */
    if ($mode === 'inscription' && isset($_POST['inscription'])) {

        $nom   = trim($_POST['nom'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $mdp   = $_POST['mot_de_passe'] ?? '';

        if ($nom === '' || $email === '' || $mdp === '') {
            $message = "Tous les champs sont obligatoires.";
            $messageType = "danger";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Email invalide.";
            $messageType = "danger";
        } elseif (strlen($mdp) < 8) {
            $message = "Le mot de passe doit contenir au moins 8 caractères.";
            $messageType = "danger";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM patient WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $message = "Cet email est déjà enregistré.";
                $messageType = "danger";
            } else {
                $hash = password_hash($mdp, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare(
                    "INSERT INTO patient (nom, email, mot_de_passe)
                     VALUES (?, ?, ?)"
                );
                $stmt->execute([$nom, $email, $hash]);

                header("Location: login.php");
                    exit();
            }
        }
    }

    /* ===== CONNEXION ===== */
    elseif ($mode === 'connexion' && isset($_POST['connexion'])) {

        $email = strtolower(trim($_POST['email'] ?? ''));
        $mdp   = $_POST['mot_de_passe'] ?? '';

        if ($email === '' || $mdp === '') {
            $message = "Tous les champs sont obligatoires.";
            $messageType = "danger";
        } else {
            $stmt = $pdo->prepare(
                "SELECT id, nom, mot_de_passe
                 FROM patient
                 WHERE email = ?"
            );
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($mdp, $user['mot_de_passe'])) {
                session_regenerate_id(true);
                $_SESSION['id_patient'] = $user['id'];
                $_SESSION['utilisateur'] = $user['nom'];

                header("Location: index.php");
                exit;
            } else {
                $message = "Email ou mot de passe incorrect.";
                $messageType = "danger";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Health North</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<form action="login.php" method="POST">
    <input type="hidden" name="action" value="connexion">

    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="mdp" required><br><br>

    <button type="submit">Se connecter</button>
</form>