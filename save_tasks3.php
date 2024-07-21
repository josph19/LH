<?php
include("connection.php");
session_start();

/*// Check if the session variable for user (nom_utilisateur) is set
if (!isset($_SESSION['nom_utilisateur'])) {
    die("Utilisateur non connecté.");
}

$nom_utilisateur = $_SESSION['nom_utilisateur']; // Retrieve username from session

// Check the database connection
if (!$conn) {
    die("Erreur de connexion à la base de données: " . mysqli_connect_error());
}*/

// Check if the session variable for login (email) is set
if (!isset($_SESSION['nom_utilisateur'])) {
    die("Utilisateur non connecté.");
}

$nom_utilisateur = $_SESSION['nom_utilisateur']; // Retrieve nom_utilisateur from session

// Check the database connection
if (!$conn) {
    die("Erreur de connexion à la base de données: " . mysqli_connect_error());
}

// Fetch user's details based on email
$user_query = "SELECT nom, prenom FROM utilisateurs WHERE nom_utilisateur = '$nom_utilisateur'";
$user_result = mysqli_query($conn, $user_query);

if (!$user_result) {
    die("Erreur de la requête SQL: " . mysqli_error($conn));
}

if (mysqli_num_rows($user_result) > 0) {
    $user = mysqli_fetch_assoc($user_result);
    $nom_utilisateur = $user['nom'] . ' ' . $user['prenom'];
} else {
    die("Aucun utilisateur trouvé avec cet email.");
}


// Get the current user's username and mode
//$nom_utilisateur = $_SESSION['nom_utilisateur'];
$mode = $_SESSION['mode'];

// Check if form was submitted
if (isset($_POST['save_archive'])) {
    // Loop through the posted validation and observation arrays
    foreach ($_POST['validation'] as $row_id => $validation) {
        $observation = isset($_POST['observation'][$row_id]) ? mysqli_real_escape_string($conn, $_POST['observation'][$row_id]) : '';

        // Get the task details
        $task_query = "SELECT secteur, type, adf, ap, titre_de_gamme, libelle, valeur
                       FROM taches
                       WHERE id = $row_id";
        $task_result = mysqli_query($conn, $task_query);
        $task = mysqli_fetch_assoc($task_result);

        if ($task) {
            $secteur = mysqli_real_escape_string($conn, $task['secteur']);
            $type = mysqli_real_escape_string($conn, $task['type']);
            $adf = $task['adf'] ? '1' : '0';
            $ap = $task['ap'] ? '1' : '0';
            $titre_de_gamme = mysqli_real_escape_string($conn, $task['titre_de_gamme']);
            $libelle = mysqli_real_escape_string($conn, $task['libelle']);
            $valeur = mysqli_real_escape_string($conn, $task['valeur']);
            $validation = mysqli_real_escape_string($conn, $validation);
            $nom_utilisateur = mysqli_real_escape_string($conn, $nom_utilisateur);
            $mode = mysqli_real_escape_string($conn, $mode);

            // Insert data into archive_taches
            $insert_query = "INSERT INTO archive_taches (secteur, type, adf, ap, titre_de_gamme, libelle, valeur, validation, observation, nom_utilisateur, ps)
                             VALUES ('$secteur', '$type', '$adf', '$ap', '$titre_de_gamme', '$libelle', '$valeur', '$validation', '$observation', '$nom_utilisateur', '$mode')";
            mysqli_query($conn, $insert_query);
        }
    }

    // Redirect or show success message
    header("Location: taches.php");
    exit();
} else {
    // Handle the case where form is not submitted correctly
    echo "Aucune donnée soumise.";
}

mysqli_close($conn);
?>
