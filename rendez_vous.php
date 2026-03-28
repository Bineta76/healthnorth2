<?php
//session_start();
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des rendez-vous médicaux</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">

    <h2 class="text-center mb-4">Gestion des rendez-vous médicaux</h2>

    <!-- Formulaire -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form id="rdvForm" class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Nom du médecin</label>
                    <input type="text" class="form-control" id="medecin" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nom du patient</label>
                    <input type="text" class="form-control" id="nomPatient" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Prénom du patient</label>
                    <input type="text" class="form-control" id="prenomPatient" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Heure du rendez-vous</label>
                    <input type="time" class="form-control" id="heure" required>
                </div>

                <div class="col-12 text-center">
                    <button class="btn btn-primary px-4">Créer le rendez-vous</button>
                </div>

            </form>
        </div>
    </div>

    <!-- Tableau -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Médecin</th>
                        <th>Nom patient</th>
                        <th>Prénom patient</th>
                        <th>Heure</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="listeRdv"></tbody>
            </table>
        </div>
    </div>

</div>

<!-- JavaScript -->
<script>
    const form = document.getElementById("rdvForm");
    const listeRdv = document.getElementById("listeRdv");

    form.addEventListener("submit", function(e) {
        e.preventDefault();

        const medecin = document.getElementById("medecin").value;
        const nomPatient = document.getElementById("nomPatient").value;
        const prenomPatient = document.getElementById("prenomPatient").value;
        const heure = document.getElementById("heure").value;

        const tr = document.createElement("tr");

        tr.innerHTML = `
            <td>${medecin}</td>
            <td>${nomPatient}</td>
            <td>${prenomPatient}</td>
            <td>${heure}</td>
            <td>
                <button class="btn btn-danger btn-sm">Supprimer</button>
            </td>
        `;

        tr.querySelector("button").addEventListener("click", () => {
            tr.remove();
        });

        listeRdv.appendChild(tr);
        form.reset();
    });
</script>

</body>
</html>
