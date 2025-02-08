<?php
// api/get_ranking.php
include('../config/database.php');

// Prepare the SQL query to get the ranking of users based on points or the number of answers
global $mysqli; // Use the MySQLi connection defined in database.php

$query = "SELECT users.username, COUNT(answers.id) AS answer_count 
        FROM users
        LEFT JOIN answers ON users.id = answers.user_id
        GROUP BY users.id 
        ORDER BY answer_count DESC";

// Execute the query
$result = $mysqli->query($query);

// Check if there are any rankings returned
if ($result->num_rows > 0) {
    // Fetch all the rankings as an associative array
    $ranking = $result->fetch_all(MYSQLI_ASSOC);

    // Return the rankings in JSON format
    echo json_encode($ranking);
} else {
    // If no rankings are found, return an empty array
        echo json_encode([]);
}
?>
