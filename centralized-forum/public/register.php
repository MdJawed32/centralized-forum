<?php
// public/register.php
include('../includes/header.php');
include('../includes/navbar.php');
// include('../includes/authentication.php');
include('../config/database.php'); // Include the MySQLi database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the username and password (you can add more validation here if necessary)
    if (empty($username) || empty($password)) {
        echo "<p class='text-red-500'>Username and Password are required.</p>";
    } else {
        // Hash the password before saving to the database
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert the user into the database
        global $mysqli; // MySQLi connection object
        $query = "INSERT INTO users (username, password) VALUES (?, ?)";

        // Prepare the statement
        $stmt = $mysqli->prepare($query);

        // Bind parameters (username as string, password as string)
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the query and check for success
        if ($stmt->execute()) {
            // Redirect to the login page after successful registration
            header("Location: login.php");
            exit;
        } else {
            echo "<p class='text-red-500'>Registration failed. Please try again.</p>";
        }

        // Close the prepared statement
        $stmt->close();
    }
}
?>

<div class="container mx-auto p-4 flex-1">
    <h1 class="text-xl mb-4">Register</h1>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" class="border p-2 mb-4 w-full" required>
        <input type="password" name="password" placeholder="Password" class="border p-2 mb-4 w-full" required>
        <button type="submit" class="bg-blue-500 text-white p-2 w-full">Register</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
