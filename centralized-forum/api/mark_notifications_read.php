<?php
include('../config/database.php');
include('../includes/authentication.php');

// Check if the request is a POST and the user is logged in
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isUserLoggedIn()) {
    $notification_id = $_POST['notification_id'];

    // Prevent SQL injection by sanitizing the notification_id
    $notification_id = (int)$notification_id;  // Cast to integer for security

    // Prepare the SQL query to update the notification status
    global $mysqli; // MySQLi connection
    $query = "UPDATE notifications SET is_read = 1 WHERE id = ?";

    // Prepare the statement
    $stmt = $mysqli->prepare($query);
    
    // Bind the notification ID parameter
    $stmt->bind_param("i", $notification_id); // "i" for integer

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Notification marked as read.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to mark notification.']);
    }

    // Close the statement
    $stmt->close();
}
?>
