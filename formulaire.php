<?php
//session_start();
include 'includes/header.php';


// Connexion MySQL
$host = "mysql-loute.alwaysdata.net";
$dbname = "loute_labo";
$username = "loute_labo";   // WAMP
$password = "loute210982";       // vide       
$port = 3306;

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Erreur de connexion à la base de données"
    ]);
    exit;
}

// Récupération des données JSON
$data = json_decode(file_get_contents("php://input"), true);

$requiredFields = ["nom", "prenom", "date_rdv", "heure_rdv", "docteur"];

foreach ($requiredFields as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Champ manquant ou vide : $field"
        ]);
        exit;
    }
}

$nom       = trim($data["nom"]);
$prenom    = trim($data["prenom"]);
$date_rdv  = trim($data["date_rdv"]);
$heure_rdv = trim($data["heure_rdv"]);
$docteur   = trim($data["docteur"]);

// Requête préparée (SÉCURITÉ 🔐)
$stmt = $conn->prepare(
    "INSERT INTO rendez_vous (nom, prenom, date_rdv, heure_rdv, docteur)
     VALUES (?, ?, ?, ?, ?)"
);

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Erreur de préparation SQL"
    ]);
    exit;
}

$stmt->bind_param("sssss", $nom, $prenom, $date_rdv, $heure_rdv, $docteur);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Rendez-vous créé avec succès",
        "id" => $stmt->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Erreur lors de l'insertion"
    ]);
}

$stmt->close();
$conn->close();
