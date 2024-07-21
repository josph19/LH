<?php
// Inclure le fichier de connexion à la base de données
include("connection.php");
session_start();

// Fonction pour générer le fichier CSV
function generateCSV($conn) {
    // Nom du fichier CSV
    $filename = "archive_taches_" . date('Ymd') . ".csv";
    
    // Définir les en-têtes pour le téléchargement du fichier CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);

    // Créer un flux de sortie ouvert
    $output = fopen('php://output', 'w');

    // Ajouter l'en-tête des colonnes au CSV
    fputcsv($output, array('ID', 'Secteur', 'Type', 'ADF', 'AP', 'Titre de Gamme', 'Libelle', 'Valeur', 'Validation', 'Observation', 'Nom Utilisateur', 'PS', 'Date Archivage'));

    // Récupérer les données de la table archive_taches
    $query = "SELECT * FROM archive_taches";
    $result = mysqli_query($conn, $query);

    // Ajouter les données au CSV
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }

    // Fermer le flux de sortie
    fclose($output);
    exit();
}

// Vérifier si le bouton de téléchargement a été cliqué
if (isset($_POST['download_csv'])) {
    generateCSV($conn);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive des Employés</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            margin-top: 50px;
        }
        .download-icon {
            font-size: 50px;
            cursor: pointer;
            color: #007bff;
            border: none;
            background: none;
        }
        .download-icon:hover {
            color: #0056b3;
        }
        .home-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .home-icon a {
            text-decoration: none;
            color: #007bff;
            font-size: 24px;
        }
        .home-icon a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="home-icon">
            <a href="statistique.html" title="Retour à l'accueil"><i class="fas fa-home"></i> Retour à l'accueil</a>
        </div>
        <h1>Archive des Employés</h1>
        <form method="post">
            <button type="submit" name="download_csv" class="download-icon">
                <i class="fa fa-download"></i>
                Télécharger l'archive (CSV)
            </button>
        </form>
    </div>

</body>
</html>
