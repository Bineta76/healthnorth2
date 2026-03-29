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
$activeTab = 'connexion';

// ==================== TRAITEMENT ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    // ===== INSCRIPTION =====
    if ($action === 'inscription') {
        $activeTab = 'inscription';
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
            $stmt = $pdo->prepare("SELECT id FROM patient WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $message = "Cet email existe déjà.";
                $type = "danger";
            } else {
                $hash = password_hash($mdp, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO patient (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nom, $prenom, $email, $hash]);

                $message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                $type = "success";
                $activeTab = 'connexion';
            }
        }
    }

    // ===== CONNEXION =====
    if ($action === 'connexion') {
        $activeTab = 'connexion';
        $email = strtolower(trim($_POST['email'] ?? ''));
        $mdp = $_POST['mdp'] ?? '';

        if (empty($email) || empty($mdp)) {
            $message = "Tous les champs sont obligatoires.";
            $type = "danger";
        } else {
            $stmt = $pdo->prepare("SELECT id, nom, mot_de_passe FROM patient WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($mdp, $user['mot_de_passe'])) {
                session_regenerate_id(true);
                $_SESSION['id_patient'] = $user['id'];
                $_SESSION['utilisateur'] = $user['nom'];

     
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
<div class="card p-4 shadow">

<h2 class="mb-4 text-center">Espace Patient</h2>

<?php if (!empty($message)): ?>
<div class="alert alert-<?= $type ?>"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<!-- Onglets Connexion / Inscription -->
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <button class="nav-link <?= $activeTab === 'connexion' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#connexion">Connexion</button>
    </li>
    <li class="nav-item">
        <button class="nav-link <?= $activeTab === 'inscription' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#inscription">Inscription</button>
    </li>
</ul>

<div class="tab-content">

    <!-- Connexion -->
    <div class="tab-pane fade <?= $activeTab === 'connexion' ? 'show active' : '' ?>" id="connexion">
        <form method="POST">
            <input type="hidden" name="action" value="connexion">

            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required
                value="<?= $_POST['email'] ?? '' ?>">
            </div>

            <div class="mb-3">
                <input type="password" name="mdp" class="form-control" placeholder="Mot de passe" required>
            </div>

            <button class="btn btn-primary w-100">Se connecter</button>
        </form>
    </div>

    <!-- Inscription -->
    <div class="tab-pane fade <?= $activeTab === 'inscription' ? 'show active' : '' ?>" id="inscription">
        <form method="POST">
            <input type="hidden" name="action" value="inscription">

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
        </form>
    </div>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>