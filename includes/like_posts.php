<?php
session_start();

// Include the database connection file
require 'dbh.inc.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page or show an error
    header("Location: login.html");
    exit();
}

// Get the user ID from session
$user_id = $_SESSION['user_id'];

// Verify that the user exists in the database
$stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    try {
        // Check if the user has already liked this post
        $stmt = $pdo->prepare("SELECT * FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$post_id, $user_id]);
        $like = $stmt->fetch();

        if ($like) {
            // If already liked, remove the like
            $stmt = $pdo->prepare("DELETE FROM likes WHERE id = ?");
            $stmt->execute([$like['id']]);
        } else {
            // If not liked yet, add a new like
            $stmt = $pdo->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
            $stmt->execute([$post_id, $user_id]);
        }

        // Redirect back to the dashboard or previous page
        header("Location:../dashboard.php");
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
