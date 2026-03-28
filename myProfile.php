<?php
//session_start();


// Vérification de l'utilisateur connecté (à adapter selon votre logique)
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Connexion à la base de données et récupération des infos de l'utilisateur
// Exemple à adapter selon votre code
include 'includes/db.php';
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Utilisateur introuvable.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Profil</title>
</head>
<body>
    <h2>Modifier mon profil</h2>
    <form method="POST" action="update_profil.php"> <!-- Assurez-vous que ce fichier traite la mise à jour -->
        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
        <br>
        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
        <br>
        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <br>
        <label>Numéro de sécurité sociale :</label>
        <input type="text" name="numerodesecuritesociale" value="<?= htmlspecialchars($user['numerodesecuritesociale']) ?>" required pattern="\d{15}" title="15 chiffres requis">
        <br>
        <label>Mot de passe (laisser vide si inchangé) :</label>
        <input type="password" name="mdp">
        <br>
        <button type="submit">Mettre à jour</button>
    </form>
</body>
</html>
