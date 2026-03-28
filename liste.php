<?php
//session_start();
include 'includes/header.php';
?>
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gestion des Rendez-vous</title>

  <!-- Lien vers Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container py-5">
    <h1 class="text-center mb-4 text-primary">Gestion des Rendez-vous</h1>

    <!-- Formulaire d'ajout -->
    <form id="rdvForm" class="row g-3 justify-content-center mb-4">
      <div class="col-md-3 col-sm-6">
        <input type="text" id="nom" class="form-control" placeholder="Nom" required>
      </div>
      <div class="col-md-3 col-sm-6">
        <input type="text" id="prenom" class="form-control" placeholder="Prénom" required>
      </div>
      <div class="col-md-3 col-sm-6">
        <input type="time" id="heure" class="form-control" required>
      </div>
      <div class="col-md-2 col-sm-6 text-center">
        <button type="submit" class="btn btn-primary w-100">Ajouter</button>
      </div>
    </form>

    <!-- Tableau responsive -->
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle text-center">
        <thead class="table-primary">
          <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Heure</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="listeRdv"></tbody>
      </table>
    </div>
  </div>

  <!-- Script JS -->
  <script>
    const form = document.getElementById("rdvForm");
    const listeRdv = document.getElementById("listeRdv");

    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const nom = document.getElementById("nom").value.trim();
      const prenom = document.getElementById("prenom").value.trim();
      const heure = document.getElementById("heure").value;

      if (!nom || !prenom || !heure) return;

      const tr = document.createElement("tr");

      tr.innerHTML = `
        <td>${nom}</td>
        <td>${prenom}</td>
        <td>${heure}</td>
        <td>
          <button class="btn btn-danger btn-sm delete">Supprimer</button>
        </td>
      `;

      tr.querySelector(".delete").addEventListener("click", () => {
        tr.remove();
      });

      listeRdv.appendChild(tr);
      form.reset();
    });
  </script>

  <!-- JS Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
