<?php
session_start();

// Include database connection
include 'dbh.inc.php';

if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to login page
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_id'])) {
    $comment_id = $_POST['comment_id'];
    $user_id = $_SESSION['user_id']; // Get the user ID from the session

    try {
        // Check if the comment belongs to the current user
        $stmt = $pdo->prepare("SELECT * FROM comments WHERE id = ? AND user_id = ?");
        $stmt->execute([$comment_id, $user_id]);
        $comment = $stmt->fetch();

        if ($comment) {
            // If the comment exists and belongs to the user, delete it
            $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
            $stmt->execute([$comment_id]);

            // Redirect to the dashboard or post page
            header("Location:../dashboard.php");
            exit();
        } else {
            // If the comment does not exist or does not belong to the user
            echo "You are not authorized to delete this comment.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // If the comment ID is not set or the request is invalid
    echo "Invalid request.";
}
?>
