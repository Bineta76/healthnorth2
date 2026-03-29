<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// ==================== BDD ====================
$host = "mysql-loute.alwaysdata.net"; 
$dbname = "loute_labo";
$user = "loute_labo";
$password = "loute210982";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    exit("Erreur BDD : " . $e->getMessage());
}

// ==================== VARIABLES ====================
$message = "";
$type = "";

// ==================== TRAITEMENT ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $mdp = $_POST['mdp'] ?? '';

    if (empty($nom) || empty($prenom) || empty($email) || empty($mdp)) {
        $message = "Tous les champs sont obligatoires.";
        $type = "danger";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email invalide.";
        $type = "danger";

    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/', $mdp)) {
        $message = "Mot de passe trop faible (8 caractères, 1 majuscule, 1 chiffre).";
        $type = "danger";

    } else {
        try {
            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare("SELECT id FROM patient WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $message = "Cet email existe déjà.";
                $type = "danger";
            } else {
                // Hash du mot de passe
                $hash = password_hash($mdp, PASSWORD_DEFAULT);

                // Insertion dans la base
                $stmt = $pdo->prepare("INSERT INTO patient (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nom, $prenom, $email, $hash]);

                $message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                $type = "success";

                // Redirection après 3 secondes
                header("refresh:3;url=connexion.php");
            }

        } catch (PDOException $e) {
            $message = "Erreur serveur, veuillez réessayer.";
            $type = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Inscription - Laboratoire</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
<div class="card p-4 shadow">

<h2 class="mb-4 text-center">Inscription Patient</h2>

<?php if (!empty($message)): ?>
<div class="alert alert-<?= $type ?>"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="POST">

<div class="mb-3">
<input type="text" name="nom" class="form-control" placeholder="Nom" required
value="<?= $_POST['nom'] ?? '' ?>">
</div>

<div class="mb-3">
<input type="text" name="prenom" class="form-control" placeholder="Prénom" required
value="<?= $_POST['prenom'] ?? '' ?>">
</div>

<div class="mb-3">
<input type="email" name="email" class="form-control" placeholder="Email" required
value="<?= $_POST['email'] ?? '' ?>">
</div>

<div class="mb-3">
<input type="password" name="mdp" class="form-control" placeholder="Mot de passe" required>
<small class="text-muted">8 caractères, 1 majuscule, 1 chiffre</small>
</div>

<button class="btn btn-success w-100">S'inscrire</button>

<p class="mt-3 text-center">
Déjà inscrit ? <a href="connexion.php">Se connecter</a>
</p>

</form>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>