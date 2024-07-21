<?php
 // Inclure le fichier de connexion à la base de données
 include("connection.php");

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$nom_utilisateur = $_POST['nom_utilisateur'];
$email = $_POST['email'];
$mot_de_passe = $_POST['mot_de_passe'];
$mode = $_POST['mode'];

    $sql = "INSERT INTO utilisateurs (nom, prenom, nom_utilisateur, email, mot_de_passe, mode)
            VALUES ('$nom', '$prenom', '$nom_utilisateur', '$email', '$mot_de_passe', '$mode')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Votre compte a été créé avec succès.');
                window.location.href = 'login.php';
              </script>";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }

?>

<?php 
    mysqli_close($conn);
?>
