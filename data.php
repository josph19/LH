<?php
header('Content-Type: application/json');

// Include the database connection file
include("connection.php");
session_start();

// Get the selected date from the request
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'); // json send and get

// Prepare the SQL query with date filtering
$sql = $conn->prepare("SELECT `nom_utilisateur`, `validation` FROM `archive_taches` WHERE DATE(`date_archived`) = ?");
$sql->bind_param('s', $selectedDate);
$sql->execute();
$result = $sql->get_result();

$validation_counts = array();

// Process results
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $user = $row['nom_utilisateur'];
        $validation = $row['validation'];
        
        if (!isset($validation_counts[$user])) {
            $validation_counts[$user] = ['fait' => 0, 'non fait' => 0];
        }
        
        if (in_array($validation, ['fait', 'non fait'])) {
            $validation_counts[$user][$validation]++;
        }
    }
}

// Convert to format suitable for Chart.js
$labels = array();
$fait_data = array();
$non_fait_data = array();

foreach ($validation_counts as $user => $counts) {
    $labels[] = $user;
    $fait_data[] = $counts['fait'];
    $non_fait_data[] = $counts['non fait'];
}

$data = array(
    "labels" => $labels,
    "datasets" => array(
        array(
            "label" => "Fait",
            "data" => $fait_data,
            "backgroundColor" => "rgba(75, 192, 192, 0.2)",
            "borderColor" => "rgba(75, 192, 192, 1)",
            "borderWidth" => 1
        ),
        array(
            "label" => "Non Fait",
            "data" => $non_fait_data,
            "backgroundColor" => "rgba(255, 99, 132, 0.2)",
            "borderColor" => "rgba(255, 99, 132, 1)",
            "borderWidth" => 1
        )
    )
);

echo json_encode($data);

$conn->close();
?>
