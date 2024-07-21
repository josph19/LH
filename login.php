<?php
    // Inclure le fichier de connexion à la base de données
    include("connection.php");
    session_start();

    // Vérifier si le formulaire est soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les données du formulaire
        $nom_utilisateur = $_POST['nom_utilisateur'];
        $mot_de_passe = $_POST['mot_de_passe'];

        // Vérifier les identifiants dans la base de données
        $query = "SELECT * FROM utilisateurs WHERE nom_utilisateur = '$nom_utilisateur' AND mot_de_passe = '$mot_de_passe'";
        $result = mysqli_query($conn, $query);

        // Vérifier s'il y a une correspondance dans la base de données
        if (mysqli_num_rows($result) == 1) {
            $row = $result->fetch_assoc();
            if ($row['mode'] == 'Opérateur') {
                // L'authentification est réussie et l'utilisateur est un opérateur
                $_SESSION['nom_utilisateur'] = $nom_utilisateur; // Set session variable
                header("Location: boite_reception.php");
                exit();
            } elseif ($row['mode'] == 'Administrateur') {
                // L'authentification est réussie et l'utilisateur est un administrateur
                $_SESSION['nom_utilisateur'] = $nom_utilisateur; // Set session variable
                header("Location: statistique.html");
                exit();
            } else {
                // L'utilisateur n'a pas un rôle valide
                $error = "Rôle utilisateur invalide.";
            }
        } else {
            // Afficher un message d'erreur si les identifiants sont incorrects
            $error = "Identifiants incorrects. Veuillez réessayer.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Connexion</title>
    <style>
        body {
            background-image: linear-gradient(
                     rgba(0, 0, 0, 0.1), 
                      rgba(0, 0, 0, 0.1)
            ),  url('images/lafarge_holcim_meknes.jpeg');
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 2em;
            margin: 0;
        }
        .header p {
            font-size: 1.2em;
            margin: 10px 0;
        }
        .quote {
            font-style: italic;
            margin-top: 10px;
        }
        .info {
            font-size: 1em;
            color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bienvenue à l'application de planification des gammes</h1>
        <p>“La qualité d'un travail est le reflet de la passion que l'on y met.”</p>
        <p class="info">Cette application est conçue pour optimiser la planification des tâches pour LafargeHolcim Maroc à Meknès.</p>
    </div>

    <div class="container">
    <h2>Connexion</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
            <label for="nom_utilisateur">Utilisateur:</label>
            <input type="text" name="nom_utilisateur" required>
        </div>
        <div>
            <label for="mot_de_passe">Mot de passe:</label>
            <input type="password" name="mot_de_passe" required>
        </div>
        <div>
            <input type="submit" value="Se connecter">
        </div>
    </form>
    <?php
        // Afficher le message d'erreur le cas échéant
        if (isset($error)) {
            echo '<div style="color:red;">' . $error . '</div>';
        }
    ?>
    <!-- Lien vers la création de compte -->
     <a href="signup.html">Créer un compte ?</a>
     </div>
</body>
</html>

<?php 
    mysqli_close($conn);
?>
