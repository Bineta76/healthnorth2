<?php
//session_start();
include 'includes/header.php';
?>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <form class="row g-3">

        <!-- Menu Régions -->
        <div class="col-md-6">
            <label for="region" class="form-label">Région</label>
            <select class="form-select" id="region" name="region">
                <option value="">-- Sélectionnez une région --</option>
                <option value="ile-de-france">Île-de-France</option>
                <option value="bretagne">Bretagne</option>
                <option value="nouvelle-aquitaine">Nouvelle-Aquitaine</option>
                <option value="occitanie">Occitanie</option>
                <option value="grand-est">Grand Est</option>
                <option value="pays-de-la-loire">Pays de la Loire</option>
                <option value="auvergne-rhone-alpes">Auvergne-Rhône-Alpes</option>
                <option value="provence">Provence-Alpes-Côte d’Azur</option>
                <option value="normandie">Normandie</option>
                <option value="hauts-de-france">Hauts-de-France</option>
                <option value="centre-val">Centre-Val de Loire</option>
                <option value="corse">Corse</option>
                <option value="bourgogne">Bourgogne-Franche-Comté</option>
                <option value="outre-mer">Outre-Mer</option>
            </select>
        </div>

        <!-- Menu Spécialités -->
        <div class="col-md-6">
            <label for="specialite" class="form-label">Spécialité médicale</label>
            <select class="form-select" id="specialite" name="specialite">
                <option value="">-- Sélectionnez une spécialité --</option>
            </select>
        </div>

    </form>
</div>

<script>
    // Listes des spécialités par région
    const specialitesParRegion = {
        "ile-de-france": [
            "Cardiologie", "Dermatologie", "Pédiatrie", "Gynécologie",
            "Radiologie", "Neurologie", "ORL", "Urologie"
        ],
        "bretagne": [
            "Médecin généraliste", "Pédiatrie", "Dermatologie"
        ],
        "nouvelle-aquitaine": [
            "Médecin généraliste", "Cardiologie", "Ophtalmologie"
        ],
        "occitanie": [
            "Gastro-entérologie", "Orthopédie", "Psychiatrie"
        ],
        "grand-est": [
            "Rhumatologie", "Endocrinologie", "Radiologie"
        ],
        // par défaut (autres régions)
        "default": [
            "Médecin généraliste",
            "Cardiologie",
            "Dermatologie",
            "Pédiatrie",
            "Gynécologie"
        ]
    };

    // Mise à jour dynamique des spécialités
    document.getElementById("region").addEventListener("change", function() {
        const region = this.value;
        const specialiteSelect = document.getElementById("specialite");

        // Reset du menu spécialité
        specialiteSelect.innerHTML = '<option value="">-- Sélectionnez une spécialité --</option>';

        let liste = specialitesParRegion[region] || specialitesParRegion["default"];

        // Ajout dynamique des options
        liste.forEach(function(s) {
            let option = document.createElement("option");
            option.value = s.toLowerCase().replaceAll(" ", "-");
            option.textContent = s;
            specialiteSelect.appendChild(option);
        });
    });
</script>
