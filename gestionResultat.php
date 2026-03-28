<?php
//session_start();
include 'includes/header.php';


$patients = [
    ["nom" => "Dupont", "prenom" => "Marie", "examen" => "Analyse de sang", "valeur" => 5.1, "norme" => "4.0 - 6.0"],
    ["nom" => "Martin", "prenom" => "Paul", "examen" => "Glycémie", "valeur" => 1.45, "norme" => "0.70 - 1.10"],
    ["nom" => "Bernard", "prenom" => "Sophie", "examen" => "Tension", "valeur" => 12, "norme" => "10 - 14"]
];

// Fonction statut
function statut($valeur, $norme) {
    list($min, $max) = explode(" - ", $norme);
    return ($valeur >= $min && $valeur <= $max) ? "Normal" : "Anormal";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Résultats des examens médicaux</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<h2 class="text-center mb-4">Résultats des examens médicaux</h2>

<div class="card shadow">

<div class="card-body">

<div class="table-responsive">

<table class="table table-striped table-bordered table-hover text-center">

<thead class="table-dark">

<tr>
<th>Nom</th>
<th>Prénom</th>
<th>Examen</th>
<th>Valeur</th>
<th>Norme</th>
<th>Statut</th>
</tr>

</thead>

<tbody>

<?php foreach ($patients as $patient): 
$resultat = statut($patient["valeur"], $patient["norme"]);
?>

<tr>

<td><?= htmlspecialchars($patient["nom"]) ?></td>

<td><?= htmlspecialchars($patient["prenom"]) ?></td>

<td><?= htmlspecialchars($patient["examen"]) ?></td>

<td><?= $patient["valeur"] ?></td>

<td><?= $patient["norme"] ?></td>

<td>

<?php if ($resultat == "Normal") { ?>

<span class="badge bg-success">Normal</span>

<?php } else { ?>

<span class="badge bg-danger">Anormal</span>

<?php } ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</body>

</html>