<?php
include("connection.php");
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $query = "SELECT * FROM utilisateurs WHERE nom_utilisateur = '$nom_utilisateur' AND mot_de_passe = '$mot_de_passe'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['nom_utilisateur'] = $nom_utilisateur; // Set session variable
        header("Location: boite_reception.php");
        exit();
    } else {
        $error = "Identifiants incorrects. Veuillez réessayer.";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mode = $_POST['mode'];
    $shift = $_POST['shift'];

    // Save the mode and shift in session
    $_SESSION['mode'] = $mode;
    $_SESSION['shift'] = $shift;

    header("Location: taches.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sélection du Mode et du Quart</title>
    <link rel="stylesheet" href="css/boite_reception.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
        header .button-container a {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #45a049;
            text-decoration: none;
            border-radius: 5px;
        }
        header .button-container a:hover {
            background-color: #387c40;
        }
        .container {
            margin: 80px 20px 20px 20px; /* Top margin adjusted to accommodate fixed header */
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .container h2 {
            margin-top: 0;
        }
        .container form {
            margin: 0;
        }
        .container label {
            display: block;
            margin: 10px 0 5px;
        }
        .container select, .container button {
            padding: 10px;
            margin-top: 5px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Interface de Planification</h1>
        <div class="button-container">
            <a href="interface_message.html">Messaging Others</a>
        </div>
    </header>
    <div class="container">
        <h2>Sélectionner le Mode et le Quart</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="mode">Mode :</label>
            <select id="mode" name="mode" required>
                <option value="P">P</option>
                <option value="S">S</option>
            </select>

            <label for="shift">Quart :</label>
            <select id="shift" name="shift" required>
                <option value="1">Quart 1</option>
                <option value="2">Quart 2</option>
                <option value="3">Quart 3</option>
            </select>

            <button type="submit">Valider</button>
        </form>
    </div>
</body>
</html>
