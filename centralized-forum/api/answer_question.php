<?php
// api/post_question.php
include('../config/database.php');
include('../includes/authentication.php');

// Check if the user is logged in and the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isUserLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    $question = $_POST['question'];

    // Check if the question is empty
    if (empty($question)) {
        echo json_encode(['status' => 'error', 'message' => 'Question cannot be empty.']);
        exit;
    }

    // Prevent SQL Injection
    $question = sanitizeInput($question);

    // Prepare the SQL query to insert the question into the database
    global $mysqli; // MySQLi connection
    $query = "INSERT INTO questions (question, user_id) VALUES (?, ?)";
    $stmt = $mysqli->prepare($query);

    // Bind parameters to the query
    $stmt->bind_param("si", $question, $user_id); // "s" for string, "i" for integer

    // Execute the query and check the result
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Question posted successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to post question.']);
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request or user not logged in.']);
}
?>
