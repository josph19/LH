<?php
    // Inclure le fichier de connexion à la base de données
    include("connection.php");
    session_start();

    $nom_utilisateur = $_SESSION['nom_utilisateur'];

    // Récupérer l'ID de l'utilisateur
    $query = "SELECT id FROM utilisateurs WHERE nom_utilisateur = '$nom_utilisateur'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['id'];

    // Récupérer les messages reçus
    $query = "SELECT u.nom_utilisateur AS sender, m.message, m.timestamp
              FROM messages m
              JOIN utilisateurs u ON m.sender_id = u.id
              WHERE m.receiver_id = '$user_id'
              ORDER BY m.timestamp DESC";
    $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boîte de Réception</title>
    <link rel="stylesheet" href="css/show_message.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
        }
        .container {
            margin: 80px auto; /* Top margin adjusted for fixed header */
            padding: 20px;
            max-width: 800px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .back-link {
            text-align: center;
        }
        .back-link a {
            color: #4CAF50;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Boîte de Réception</h1>
    </header>

    <div class="container">
        <h2>Messages Reçus</h2>
        <?php
            if (mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<thead>";
                echo "<tr><th>Utilisateur</th><th>Message</th></tr>";
                echo "</thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['sender']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['message']) . "<br><small>" . htmlspecialchars($row['timestamp']) . "</small></td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p>Aucun message reçu.</p>";
            }
        ?>
        <div class="back-link">
            <a href="interface_message.html">Retour</a>
        </div>
    </div>
</body>
</html>

<?php 
    mysqli_close($conn);
?>
