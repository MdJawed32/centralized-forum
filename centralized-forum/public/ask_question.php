<?php
// ask_question.php
include('../includes/header.php');
include('../includes/navbar.php');
include('../includes/functions.php');
include('../includes/authentication.php');

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = $_POST['question'];
    $user_id = $_SESSION['user_id'];

    // Prevent SQL Injection
    $question = sanitizeInput($question);

    // Insert the question into the database using MySQLi
    global $mysqli; // Use the MySQLi connection defined in the database.php file

    $query = "INSERT INTO questions (question, user_id) VALUES (?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("si", $question, $user_id); // "s" for string, "i" for integer
    $stmt->execute();

    // Redirect to the dashboard after inserting the question
    header("Location: dashboard.php");
    exit();
}
?>

<div class="container mx-auto p-4 flex-1">
    <h1 class="text-xl mb-4">Ask a Question</h1>
    <form method="POST" action="">
        <textarea name="question" class="border p-2 mb-4 w-full" required></textarea>
        <button type="submit" class="bg-blue-500 text-white p-2 w-full">Ask Question</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
