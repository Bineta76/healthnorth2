
<?php
//session_start();
include 'includes/header.php';

// Paramètres de connexion AlwaysData
    
$host = "mysql-loute.alwaysdata.net";
$dbname = "loute_labo";
$username = "loute_labo";   // WAMP
$password = "loute210982";       // vide       
$port = 3306;

// Connexion PDO sécurisée
try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    // Test de connexion (optionnel)
    // echo "Connexion réussie !";
} catch (PDOException $e) {
    die("❌ Impossible de se connecter à la base de données : " . htmlspecialchars($e->getMessage()));
}

// Gestion des messages flash
function flash($message, $type = 'info') {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

// Suppression d’un compte rendu
if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $stmt = $pdo->prepare("DELETE FROM bilan WHERE id = ?");
    if ($stmt->execute([$id])) {
        flash("✅ Compte rendu supprimé avec succès.", "success");
    } else {
        flash("❌ Impossible de supprimer le compte rendu.", "danger");
    }
    header("Location: bilan.php");
    exit;
}

// Ajout d’un compte rendu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['contenu'])) {
    $contenu = trim($_POST['contenu']);
    if ($contenu !== '') {
        try {
            $stmt = $pdo->prepare("INSERT INTO bilan (contenu) VALUES (?)");
            $stmt->execute([$contenu]);
            flash("✅ Compte rendu ajouté avec succès.", "success");
            header("Location: bilan.php");
            exit;
        } catch (PDOException $e) {
            flash("❌ Erreur PDO : " . htmlspecialchars($e->getMessage()), "danger");
        }
    } else {
        flash("⚠️ Le contenu est vide.", "warning");
    }
}

// Récupération des comptes rendus
try {
    $stmt = $pdo->query("SELECT id, contenu, date_creation FROM bilan ORDER BY id DESC");
    $bilanListe = $stmt->fetchAll();
} catch (PDOException $e) {
    die("❌ Erreur PDO : " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Compte Rendu Médical</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1 class="mb-4">🩺 Gestion des comptes rendus</h1>

    <!-- Message flash -->
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
            <?= $_SESSION['flash']['message'] ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Formulaire d'ajout -->
    <div class="card mb-4">
        <div class="card-header">Ajouter un nouveau compte rendu</div>
        <div class="card-body">
            <form method="post" action="">
                <div class="mb-3">
                    <textarea name="contenu" class="form-control" rows="4" placeholder="Écris ton compte rendu ici..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </div>

    <!-- Liste des comptes rendus -->
    <?php if (!empty($bilanListe)): ?>
        <?php foreach ($bilanListe as $row): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">📝 Compte rendu #<?= htmlspecialchars($row['id']) ?></h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars($row['contenu'])) ?></p>
                    <small class="text-muted">
                        Créé le : <?= date('d/m/Y H:i', strtotime($row['date_creation'])) ?>
                    </small>
                    <br><br>
                    <a href="?supprimer=<?= urlencode($row['id']) ?>"
                       class="btn btn-danger"
                       onclick="return confirm('Supprimer ce compte rendu ?');">
                       Supprimer
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted">Aucun compte rendu disponible.</p>
    <?php endif; ?>
</div>
</body>
</html>