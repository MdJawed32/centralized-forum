<?php

include('../config/database.php');

// Function to log in the user
function loginUser($username, $password) {
    global $mysqli;

    // Prevent SQL Injection
    $username = sanitizeInput($username);

    // Prepare the SQL query to fetch the user by username
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $username); // 's' means string
    $stmt->execute();
    $result = $stmt->get_result();

    // If a user is found
    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Verify the password using password_verify() if the password matches
        if (password_verify($password, $user['password'])) {
            // Start the session and set session variables
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Add any other user details as needed

            return true;
        }
    }

    // If no user is found or the password does not match
    return false;
}

// Check if the sanitizeInput function is already declared
if (!function_exists('sanitizeInput')) {
    function sanitizeInput($data) {
        global $mysqli;
        return htmlspecialchars(trim($mysqli->real_escape_string($data)), ENT_QUOTES, 'UTF-8');
    }
}

// Function to check if the user is logged in
function isUserLoggedIn() {
    session_start();
    return isset($_SESSION['user_id']);
}

?>
