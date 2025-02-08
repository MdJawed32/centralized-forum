<?php
session_start();

include('../includes/functions.php');
include('../includes/authentication.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if the answer and question_id are provided
if (isset($_POST['answer']) && isset($_POST['question_id'])) {
    $answer = sanitizeInput($_POST['answer']);
    $question_id = $_POST['question_id'];
    $user_id = $_SESSION['user_id'];

    // Insert the answer into the database
    $query = "INSERT INTO answers (question_id, user_id, answer, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $mysqli->prepare($query);

    // Check if the statement was prepared correctly
    if ($stmt === false) {
        die('MySQL prepare failed: ' . $mysqli->error);
    }

    $stmt->bind_param("iis", $question_id, $user_id, $answer);

    // Check if execute was successful
    if ($stmt->execute()) {
        header('Location: view_question.php?id=' . $question_id);
        exit();
    } else {
        echo 'Error inserting answer: ' . $stmt->error;
    }
} else {
    // If no answer or question_id is provided, redirect to the dashboard or handle the error
    header('Location: dashboard.php');
    exit();
}
?>
