<<?php
//session_start();
include 'includes/header.php';

$consultation = 30;
$total = 0;
$prestations = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Montant de la chambre
    $chambre = floatval($_POST["chambre"]);
    $total += $consultation + $chambre;

    // Durée d'hospitalisation ou arrêt
    $jours = intval($_POST["jours"]);
    $prixJour = floatval($_POST["prix_jour"]);
    $montantJours = $jours * $prixJour;

    $total += $montantJours;

    // Prestations supplémentaires
    if (isset($_POST["nom"]) && isset($_POST["prix"])) {
        for ($i = 0; $i < count($_POST["nom"]); $i++) {
            $nom = trim($_POST["nom"][$i]);
            $prix = floatval($_POST["prix"][$i]);

            if ($nom !== "" && $prix > 0) {
                $prestations[] = [
                    "nom" => $nom,
                    "prix" => $prix
                ];
                $total += $prix;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture Complète</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <script>
        function ajouterPrestation() {
            let container = document.getElementById("containerPrestations");
            let html = `
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="nom[]" placeholder="Nom prestation">
                    </div>
                    <div class="col-md-4">
                        <input type="number" step="0.01" class="form-control" name="prix[]" placeholder="Prix (€)">
                    </div>
                </div>
            `;
            container.insertAdjacentHTML("beforeend", html);
        }
    </script>
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-body">

            <h2 class="text-center mb-4">Facture Dynamique Complète</h2>

            <form method="post" class="mb-4">

                <div class="mb-3">
                    <h5>Consultation</h5>
                    <p class="fw-bold">Prix fixe : 30 €</p>
                </div>

                <div class="mb-3">
                    <h5>Chambre</h5>
                    <input type="number" step="0.01" name="chambre" class="form-control" placeholder="Montant de la chambre (€)" required>
                </div>

                <div class="mb-3">
                    <h5>Hospitalisation / Arrêt</h5>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="number" name="jours" class="form-control" placeholder="Nombre de jours" required>
                        </div>
                        <div class="col-md-6">
                            <input type="number" step="0.01" name="prix_jour" class="form-control" placeholder="Prix par jour (€)" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h5>Prestations supplémentaires</h5>

                    <div id="containerPrestations" class="mb-3">
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="nom[]" placeholder="Nom prestation">
                            </div>
                            <div class="col-md-4">
                                <input type="number" step="0.01" class="form-control" name="prix[]" placeholder="Prix (€)">
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="ajouterPrestation()" class="btn btn-secondary">
                        + Ajouter une prestation
                    </button>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Calculer la facture
                </button>

            </form>

            <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
                <div class="alert alert-info mt-4">
                    <h4 class="text-center">🧾 Facture détaillée</h4>
                    <hr>

                    <p>Consultation : <strong>€<?php echo number_format($consultation, 2); ?></strong></p>
                    <p>Chambre : <strong>€<?php echo number_format($chambre, 2); ?></strong></p>

                    <p>Hospitalisation / Arrêt :</p>
                    <ul>
                        <li>Jours : <strong><?php echo $jours; ?></strong></li>
                        <li>Prix par jour : <strong>€<?php echo number_format($prixJour, 2); ?></strong></li>
                        <li>Total : <strong>€<?php echo number_format($montantJours, 2); ?></strong></li>
                    </ul>

                    <?php if (!empty($prestations)): ?>
                        <h5>Prestations supplémentaires :</h5>
                        <ul class="list-group mb-3">
                            <?php foreach ($prestations as $p): ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <?php echo htmlspecialchars($p["nom"]); ?>
                                    <strong>€<?php echo number_format($p["prix"], 2); ?></strong>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <h4 class="text-center">
                        Total à payer :
                        <span class="badge bg-success fs-4">
                             €<?php echo number_format($total, 2); ?>
                        </span>
                    </h4>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
