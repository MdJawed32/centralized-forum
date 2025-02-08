<?php
// public/index.php

// Includes the necessary files
include('../includes/header.php');
include('../includes/navbar.php');
include('../includes/functions.php'); // This includes the function
include('../config/database.php');

// Fetch questions using the function from functions.php
$questions = getAllQuestions(); // Calling the function

?>

<div class="container mx-auto p-4 flex-1">
    <h1 class="text-xl mb-4">Welcome to the Q&A Platform</h1>

    <h2 class="text-lg mb-4">Latest Questions</h2>
    
    <?php if (empty($questions)): ?>
        <p>No questions found. Be the first to ask!</p>
    <?php else: ?>
        <ul class="space-y-4">
            <?php foreach ($questions as $question): ?>
                <li class="border p-4">
                    <h3 class="font-semibold"><?php echo htmlspecialchars($question['question']); ?></h3>
                    <p class="text-sm text-gray-500">Asked by: <?php echo htmlspecialchars($question['user_id']); ?> | <?php echo $question['created_at']; ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</div>

<?php include('../includes/footer.php'); ?>
