<?php
// includes/functions.php

// Function to sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Function to get user details by user ID
function getUserById($user_id) {
    global $mysqli;
    
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

// Function to get all questions
function getAllQuestions() {
    global $mysqli;
    
    $query = "SELECT * FROM questions ORDER BY created_at DESC";
    $result = $mysqli->query($query);
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to get answers by the question ID
function getAnswersByQuestionId($question_id) {
    global $mysqli;
    $query = "SELECT * FROM answers WHERE question_id = ? ORDER BY created_at DESC";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC); // Return all answers
}


// Function to get a specific question by its ID
function getQuestionById($id) {
    global $mysqli;
    $query = "SELECT * FROM questions WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id); // 'i' means integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null; // Question not found
    }
}

// Function to get answers for a particular question
function getAnswersForQuestion($question_id) {
    global $mysqli;
    
    $query = "SELECT answers.*, users.username FROM answers
            JOIN users ON answers.user_id = users.id
            WHERE answers.question_id = ? ORDER BY answers.created_at ASC";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to send notifications
function sendNotification($user_id, $message) {
    global $mysqli;
    
    $query = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("is", $user_id, $message);
    
    return $stmt->execute();
}

// Function to get unread notifications for the logged-in user
function getUnreadNotifications($user_id) {
    global $mysqli;
    
    $query = "SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to get total number of answers for a user
function getTotalAnswersForUser($user_id) {
    global $mysqli;
    
    $query = "SELECT COUNT(id) AS total_answers FROM answers WHERE user_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $row = $result->fetch_assoc();
    return $row['total_answers'];
}

// Function to calculate user's rank based on answers posted
function getUserRank($user_id) {
    global $mysqli;
    
    $query = "SELECT COUNT(answers.id) AS answer_count FROM answers
            WHERE answers.user_id = ?
            GROUP BY answers.user_id";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $row = $result->fetch_assoc();
    
    if ($row) {
        $answer_count = $row['answer_count'];
        if ($answer_count > 10) {
            return 'Expert';
        } elseif ($answer_count > 5) {
            return 'Intermediate';
        } else {
            return 'Beginner';
        }
    }
    
    return 'Newbie';
}

// Function to format date (e.g. relative date)
function formatDate($timestamp) {
    $diff = time() - strtotime($timestamp);
    $seconds = $diff;
    
    $minutes      = round($seconds / 60);           // value 60 is seconds
    $hours        = round($seconds / 3600);         // value 3600 is 60 minutes * 60 sec
    $days         = round($seconds / 86400);        // value 86400 is 24 hours * 60 minutes * 60 sec
    $weeks        = round($seconds / 604800);       // value 604800 is 7 days * 24 hours * 60 minutes * 60 sec
    $months       = round($seconds / 2629440);      // value 2629440 is (365 days in a year / 12 months) * 24 hours * 60 minutes * 60 sec
    $years        = round($seconds / 31553280);     // value 31553280 is (365 days in a year) * 24 hours * 60 minutes * 60 sec
    
    if ($seconds <= 60) {
        return "Just Now";
    } else if ($minutes <= 60) {
        return ($minutes == 1) ? "one minute ago" : "$minutes minutes ago";
    } else if ($hours <= 24) {
        return ($hours == 1) ? "an hour ago" : "$hours hours ago";
    } else if ($days <= 7) {
        return ($days == 1) ? "yesterday" : "$days days ago";
    } else if ($weeks <= 4.3) {
        return ($weeks == 1) ? "a week ago" : "$weeks weeks ago";
    } else if ($months <= 12) {
        return ($months == 1) ? "one month ago" : "$months months ago";
    } else {
        return ($years == 1) ? "one year ago" : "$years years ago";
    }
}
?>
