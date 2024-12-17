<?php
session_start();

// Include database connection
include 'dbh.inc.php';

if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to login page
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id']; // Get the user ID from the session

    try {
        // Check if the post belongs to the current user
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
        $stmt->execute([$post_id, $user_id]);
        $post = $stmt->fetch();

        if ($post) {
            // If the post exists and belongs to the user, delete it
            // First, delete comments associated with the post
            $stmt = $pdo->prepare("DELETE FROM comments WHERE post_id = ?");
            $stmt->execute([$post_id]);

            // Then, delete likes associated with the post
            $stmt = $pdo->prepare("DELETE FROM likes WHERE post_id = ?");
            $stmt->execute([$post_id]);

            // Finally, delete the post
            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->execute([$post_id]);

            // Redirect to the dashboard or post page
            header("Location: ../profile.php");
            exit();
        } else {
            // If the post does not exist or does not belong to the user
            echo "You are not authorized to delete this post.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // If the post ID is not set or the request is invalid
    echo "Invalid request.";
}
?>
