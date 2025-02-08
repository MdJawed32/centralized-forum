<?php
// api/get_questions.php
include('../config/database.php');

// Prepare the SQL query to fetch questions
global $mysqli; // Use the MySQLi connection defined in database.php

$query = "SELECT * FROM questions ORDER BY created_at DESC";

// Prepare and execute the query
$result = $mysqli->query($query);

// Check if there are any questions in the result set
if ($result->num_rows > 0) {
    // Fetch all the questions as an associative array
    $questions = $result->fetch_all(MYSQLI_ASSOC);

    // Return the questions in JSON format
    echo json_encode($questions);
} else {
    // If no questions are found, return an empty array
    echo json_encode([]);
}
?>
