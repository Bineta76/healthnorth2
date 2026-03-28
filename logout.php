<?php
//session_start();
session_destroy(); // Supprimer toutes les données de session
header("Location: index.php"); // Redirection vers la page d'accueil après déconnexion
exit();
