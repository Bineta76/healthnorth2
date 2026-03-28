<?php
//session_start();
include 'includes/header.php';
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrousel d'aide</title>
    <!-- Lien vers le CSS de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Personnalisation des boutons de navigation */
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(100%); /* Inverse les couleurs pour un meilleur contraste */
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 5%; /* Largeur des boutons */
            background-color: rgba(0, 0, 0, 0.5); /* Fond noir semi-transparent */
            opacity: 1; /* Toujours visible */
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background-color: rgba(0, 0, 0, 0.8); /* Fond plus foncé au survol */
        }

        /* Ajustement des images pour qu'elles soient responsives */
        .carousel-item img {
            max-height: 500px; /* Hauteur maximale des images */
            object-fit: cover; /* Ajuste l'image sans déformation */
            margin: auto;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Carrousel d'aide</h1>

    <!-- Début du carrousel -->
    <div id="customCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
        <div class="carousel-inner">
            <!-- Première slide -->
            <div class="carousel-item active">
                <img src="assets/images/monEspaceSanté.jpg" class="d-block w-40" alt="Accéder à mon parcours santé">
                <div class="carousel-caption d-none d-md-block">
                    <h3>Accéder à mon parcours santé</h3>
                    <p><a href="https://www.monespacesante.fr" class="btn btn-primary">Visiter le site</a></p>
                </div>
            </div>

            <!-- Deuxième slide -->
            <div class="carousel-item">
                <img src="assets/images/Point_d_interrogation.jpg" class="d-block w-40" alt="Découvrir Santé">
                <div class="carousel-caption d-none d-md-block">
                    <h3>Découvrir Santé</h3>
                    <p>Retrouvez tous vos documents et informations de santé.</p>
                    <p>Cliquer sur l'espace santé</p>
                </div>
            </div>

            <!-- Troisième slide -->
            <div class="carousel-item">
                <img src="assets/images/espace_sante.jpg" class="d-block w-40" alt="J'ai oublié mon mot de passe et mes identifiants">
                <div class="carousel-caption d-none d-md-block">
                    <h3>J'ai oublié mon mot de passe</h3>
                    <p><a href="" class="btn btn-secondary">Consulter la FAQ</a></p>
                </div>
            </div>
        </div>

        <!-- Boutons de navigation -->
        <a class="carousel-control-prev" href="#customCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Précédent</span>
        </a>
        <a class="carousel-control-next" href="#customCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Suivant</span>
        </a>
    </div>
</div>

<!-- Inclusion des scripts nécessaires -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function(){
        // Initialisation du carrousel
        $('#customCarousel').carousel({
            interval: 3000, // Change toutes les 3 secondes
            ride: 'carousel' // Démarrage automatique
        });
    });
</script>

</body>
</html>
</ BR>
<footer>
<div class="container-fluid">
    <center>
        <p>&copy; 2024 HEALTH NORTH - Tous droits réservés.</p>
        <p>Contactez-nous : contact@healthnorth.fr | 03 20 xx xx xx</p>
    </center>
</div>
</footer>
</body>
</html>
