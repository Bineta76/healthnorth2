<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

/******************** CONNEXION BDD ********************/
$host = "mysql-loute.alwaysdata.net";
$dbname = "loute_labo";
$username = "loute_labo";
$password = "loute210982";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

/******************** SESSION ********************/
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

/******************** SÉCURITÉ SESSION ********************/
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} elseif (time() - $_SESSION['created'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}

if (!isset($_SESSION['ip'])) {
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['ua'] = $_SERVER['HTTP_USER_AGENT'];
} else {
    if ($_SESSION['ip'] !== $_SERVER['REMOTE_ADDR'] ||
        $_SESSION['ua'] !== $_SERVER['HTTP_USER_AGENT']) {
        session_destroy();
        exit("Session invalide.");
    }
}

/******************** CSRF ********************/
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

/******************** VARIABLES ********************/
$message = '';
$messageType = '';
$activeTab = 'connexion'; // 👈 IMPORTANT

/******************** LOGOUT ********************/
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = [];
    session_destroy();
    header("Location: index.php");
    exit;
}

/******************** TRAITEMENT ********************/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Erreur CSRF");
    }

    $action = $_POST['action'] ?? '';

    /* ===== INSCRIPTION ===== */
    if ($action === 'inscription') {

        $activeTab = 'inscription'; // 👈 RESTE SUR ONGLET

        $nom   = trim($_POST['nom'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $mdp   = $_POST['mot_de_passe'] ?? '';

        if ($nom === '' || $email === '' || $mdp === '') {
            $message = "Tous les champs sont obligatoires.";
            $messageType = "danger";

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Email invalide.";
            $messageType = "danger";

        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/', $mdp)) {
            $message = "Mot de passe trop faible.";
            $messageType = "danger";

        } else {
            try {
                $check = $pdo->prepare("SELECT id FROM patient WHERE email = ?");
                $check->execute([$email]);

                if ($check->fetch()) {
                    $message = "Cet email existe déjà.";
                    $messageType = "danger";
                } else {
                    $hash = password_hash($mdp, PASSWORD_DEFAULT);

                    $stmt = $pdo->prepare("INSERT INTO patient (nom, email, mot_de_passe) VALUES (?, ?, ?)");
                    $stmt->execute([$nom, $email, $hash]);

                    session_regenerate_id(true);
                    $_SESSION['id_patient'] = $pdo->lastInsertId();
                    $_SESSION['utilisateur'] = $nom;

                    header("Location: index.php");
                    exit;
                }

            } catch (PDOException $e) {
                $message = "Erreur serveur.";
                $messageType = "danger";
            }
        }
    }

    /* ===== CONNEXION ===== */
    elseif ($action === 'connexion') {

        $activeTab = 'connexion'; // 👈 RESTE SUR ONGLET

        $email = strtolower(trim($_POST['email'] ?? ''));
        $mdp   = $_POST['mot_de_passe'] ?? '';

        if ($email === '' || $mdp === '') {
            $message = "Tous les champs sont obligatoires.";
            $messageType = "danger";

        } else {
            $stmt = $pdo->prepare("SELECT id, nom, mot_de_passe FROM patient WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

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
<title>Laboratoire - Connexion / Inscription</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-5">

<?php if (!empty($message)): ?>
<div class="alert alert-<?= $messageType ?>">
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <button class="nav-link <?= $activeTab === 'connexion' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#connexion">
            Connexion
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link <?= $activeTab === 'inscription' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#inscription">
            Inscription
        </button>
    </li>
</ul>

<div class="tab-content">

<!-- CONNEXION -->
<div class="tab-pane fade <?= $activeTab === 'connexion' ? 'show active' : '' ?>" id="connexion">
<form method="POST">
<input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
<input type="hidden" name="action" value="connexion">

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label>Mot de passe</label>
<input type="password" name="mot_de_passe" class="form-control" required>
</div>

<button class="btn btn-primary">Se connecter</button>
</form>
</div>

<!-- INSCRIPTION -->
<div class="tab-pane fade <?= $activeTab === 'inscription' ? 'show active' : '' ?>" id="inscription">
<form method="POST">
<input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
<input type="hidden" name="action" value="inscription">

<div class="mb-3">
<label>Nom</label>
<input type="text" name="nom" class="form-control" required>
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label>Mot de passe</label>
<input type="password" name="mot_de_passe" class="form-control" required>
<small>8 caractères, 1 majuscule, 1 chiffre</small>
</div>

<button class="btn btn-success">S'inscrire</button>
</form>
</div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>