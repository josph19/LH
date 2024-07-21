<?php
include("connection.php");
session_start();


// Get the libelle from the query string
if (isset($_GET['libelle'])) {
    $libelle = mysqli_real_escape_string($conn, $_GET['libelle']);

    // Query to get details for the libelle
    $sql = "SELECT detaille FROM taches_details WHERE libelle = '$libelle'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $details = mysqli_fetch_assoc($result)['detaille'];
    } else {
        $details = "Détails non trouvés pour ce libellé.";
    }
} else {
    header("Location: taches.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Tâche</title>
    <link rel="stylesheet" href="css/details.css">
</head>
<body>
    <div class="container">
        <h2>Détails pour le Libellé: <?php echo htmlspecialchars($libelle); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($details)); ?></p>
        <a href="taches.php">Retour à la liste des tâches</a>
    </div>
</body>
</html>

<?php 
mysqli_close($conn);
?>
