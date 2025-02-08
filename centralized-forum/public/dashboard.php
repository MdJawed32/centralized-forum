<?php
// public/dashboard.php

include('../includes/header.php');
include('../includes/navbar.php');
include('../includes/functions.php');
include('../includes/authentication.php');

// session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$questions = getAllQuestions();
?>

<div class="container mx-auto p-4 flex-1">
    <h1 class="text-xl mb-4">All Questions</h1>
    <ul>
        <?php foreach ($questions as $question): ?>
            <li class="mb-4">
                <a href="view_question.php?id=<?php echo $question['id']; ?>" class="text-blue-500"><?php echo $question['question']; ?></a>
                <p class="text-sm text-gray-600"><?php echo formatDate($question['created_at']); ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php include('../includes/footer.php'); ?>
