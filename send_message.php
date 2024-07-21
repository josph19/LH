<?php
    // Inclure le fichier de connexion à la base de données
    include("connection.php");
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $sender_nom_utilisateur = $_SESSION['nom_utilisateur'];
        $receiver_email = $_POST['receiver_email'];
        $message = $_POST['message'];

        // Récupérer l'ID de l'expéditeur
        $query = "SELECT id FROM utilisateurs WHERE nom_utilisateur = '$sender_nom_utilisateur'";
        $result = mysqli_query($conn, $query);
        $sender = mysqli_fetch_assoc($result);
        $sender_id = $sender['id'];

        // Récupérer l'ID du destinataire
        $query = "SELECT id FROM utilisateurs WHERE email = '$receiver_email'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) == 1) {
            $receiver = mysqli_fetch_assoc($result);
            $receiver_id = $receiver['id'];

            // Insérer le message dans la base de données
            $query = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$sender_id', '$receiver_id', '$message')";
            if (mysqli_query($conn, $query)) {
                // Redirection vers la page de la boîte de réception après envoi réussi
                header("Location: message.html");
                exit();
            } else {
                echo "Erreur: " . mysqli_error($conn);
            }
        } else {
            echo "Email du destinataire introuvable.";
        }
    }

    mysqli_close($conn);
?>
