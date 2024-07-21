<?php
include("connection.php");
session_start();

$mode = $_SESSION['mode'];
$shift = $_SESSION['shift'];

// Get the current date
$current_date = date("Y-m-d");

// Determine the current week and day of the week
$current_week = date("W");
$current_day = date("N"); // 1 (Monday) to 7 (Sunday)

// Convert to corresponding day character (L, M, M, J, V, S, D)
$days = ['L', 'M', 'M', 'J', 'V', 'S', 'D'];
$current_day_char = $days[$current_day - 1];

// Check for the maximum available week in the database
$check_week_sql = "SELECT DISTINCT semaine FROM taches ORDER BY semaine DESC LIMIT 1";
$check_week_result = mysqli_query($conn, $check_week_sql);
$max_week = mysqli_fetch_assoc($check_week_result)['semaine'];

// Limit to the first 8 weeks if the maximum available week is less than 8:
$max_weeks = min($max_week, 8);

// Query to get the tasks
$sql = "SELECT secteur, type, adf, ap, titre_de_gamme, libelle, valeur
        FROM taches
        WHERE ps = '$mode' 
        AND semaine <= $max_weeks
        /*AND semaine = $current_week */ /* I will came back to her later and delete then the check week statement */
        AND jour = '$current_day_char'";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tâches</title>
    <link rel="stylesheet" href="css/taches.css">
    <script src="js/taches.js"></script>
    <style>
        .actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .legend {
            font-size: 0.9em;
            color: #555;
        }
        .highlight-h {
            color: green;
        }
        .highlight-p {
            color: red;
        }
        .highlight-s {
            color: red;
        }
        .shift-icon {
            font-size: 1.5em;
            margin-left: 10px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tâches pour le <?php echo $current_date; ?> (Quart <?php echo $shift; ?>)</h2>
        <form method="post" action="save_tasks3.php">
        <table border="1">
            <thead>
                <tr>
                    <th>Secteur</th>
                    <th>Type</th>
                    <th>ADF</th>
                    <th>AP</th>
                    <th>Titre de Gamme</th>
                    <th>Libellé</th>
                    <th>Valeur</th>
                    <th>Validation</th>
                    <th>Observation</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['secteur']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                        echo "<td>" . ($row['adf'] ? 'Oui' : 'Non') . "</td>";
                        echo "<td>" . ($row['ap'] ? 'Oui' : 'Non') . "</td>";
                        echo "<td>" . htmlspecialchars($row['titre_de_gamme']) . "</td>";
                        //echo "<td>" . htmlspecialchars($row['libelle']) . "</td>";
                        echo "<td><a href='details.php?libelle=" . urlencode($row['libelle']) . "'>Voir Détails</a></td>";
                        echo "<td>" . htmlspecialchars($row['valeur']) . "</td>";
                        echo "<td>
                                 <select name='validation[$row_id]'>
                                     <option value='in progress'>In Progress</option>
                                     <option value='fait'>Fait</option>
                                     <option value='non fait'>Non Fait</option>
                                 </select>
                              </td>";
                        echo "<td><input type='text' name='observation[$row_id]'></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>Aucune tâche trouvée pour cette période.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button type="submit" name="save_archive">Sauvegarder</button>
        </form>
        <form method="post" action="deconnexion.php">
            <button type="submit">Déconnexion</button>
        </form>
        <div class="legend">
                <p>p : poste</p>
                <p>s : secteur</p>
                <p>h : secteur housekeeping</p>
            </div>
    </div>
</body>
</html>

<?php 
mysqli_close($conn);
?>
