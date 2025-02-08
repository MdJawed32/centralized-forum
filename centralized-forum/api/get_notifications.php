<?php
// api/get_notifications.php
include('../config/database.php');
include('../includes/authentication.php');

// Check if the user is logged in
if (isUserLoggedIn()) {
    $user_id = $_SESSION['user_id'];

    // Prepare the SQL query to fetch notifications for the logged-in user
    global $mysqli; // MySQLi connection
    $query = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
    
    // Prepare and execute the query
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id); // "i" for integer (user_id is an integer)
    $stmt->execute();

    // Get the result and fetch all notifications
    $result = $stmt->get_result();
    $notifications = $result->fetch_all(MYSQLI_ASSOC); // Fetch results as an associative array

    // Return the notifications in JSON format
    echo json_encode($notifications);

    // Close the statement
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
</head>
<body>
    <h1>Notifications</h1>
    <ul id="notifications-list"></ul>

    <script>
        // Fetch notifications from the API
        fetch('http://localhost/centralized-forum/api/get_notifications.php')
            .then(response => response.json())  // Parse the JSON response
            .then(data => {
                if (data.error) {
                    console.log('Error:', data.error);  // Handle errors if user is not logged in
                } else {
                    // Display the notifications on the page
                    const notificationsList = document.getElementById('notifications-list');
                    if (data.length === 0) {
                        notificationsList.innerHTML = '<li>No notifications available.</li>';
                    } else {
                        data.forEach(notification => {
                            const listItem = document.createElement('li');
                            listItem.textContent = notification.message;  // Display notification message
                            notificationsList.appendChild(listItem);
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching notifications:', error);
            });
    </script>
</body>
</html>
