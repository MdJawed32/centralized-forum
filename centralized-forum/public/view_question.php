<?php
// public/view_question.php

include('../includes/header.php');
include('../includes/navbar.php');
include('../includes/functions.php');
include('../includes/authentication.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if the question ID is provided in the URL
if (isset($_GET['id'])) {
    $question_id = $_GET['id'];

    // Fetch the specific question from the database
    $question = getQuestionById($question_id);

    // Fetch answers related to this question
    $answers = getAnswersByQuestionId($question_id);
} else {
    // If no question ID is provided, redirect to the dashboard
    header('Location: dashboard.php');
    exit();
}

?>

<div class="container mx-auto p-4 flex-1">
    <?php if ($question): ?>
        <h1 class="text-xl mb-4"><?php echo htmlspecialchars($question['question']); ?></h1>
        <p class="text-sm text-gray-600">Asked by: <?php echo htmlspecialchars($question['user_id']); ?> | <?php echo formatDate($question['created_at']); ?></p>

        <!-- Display the answers -->
        <h2 class="text-lg mt-4">Answers</h2>
        <ul>
            <?php foreach ($answers as $answer): ?>
                <li class="mb-4 p-4 border bg-gray-100">
                    <p class="text-sm"><?php echo htmlspecialchars($answer['answer']); ?></p>
                    <p class="text-xs text-gray-500">Answered by: <?php echo htmlspecialchars($answer['user_id']); ?> | <?php echo formatDate($answer['created_at']); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Form to add a new answer  -->
        <h3 class="text-md mt-4">Add Answer</h3>
        <form action="add_answer.php" method="POST">
            <textarea name="answer" class="w-full p-2 border mb-4" required></textarea>
            <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
            <button type="submit" class="bg-blue-500 text-white p-2 rounded" name="answer">Submit Answer</button>
        </form>

    <?php else: ?>
        <p class="text-red-500">Question not found.</p>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>
