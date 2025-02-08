<?php
// api/post_question.php
include('../config/database.php');
include('../includes/authentication.php');

// Check if the request method is POST and the user is logged in
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isUserLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    $question = $_POST['question'];

    // Check if the question is empty
    if (empty($question)) {
        echo json_encode(['status' => 'error', 'message' => 'Question cannot be empty.']);
        exit;
    }

    // Prepare the SQL query to insert the question into the database
    global $mysqli; // MySQLi connection
    $query = "INSERT INTO questions (question, user_id) VALUES (?, ?)";

    // Prepare the statement
    $stmt = $mysqli->prepare($query);
    
    // Bind the parameters
    $stmt->bind_param("si", $question, $user_id); // "s" for string (question), "i" for integer (user_id)

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Question posted successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to post question.']);
    }

    // Close the statement
    $stmt->close();
}
?>
