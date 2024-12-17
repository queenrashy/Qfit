<?php
include 'includes/dbh.inc.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Redirect to login if user is not logged in
    header("Location: dashboard.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    $user_id = 1; // Replace with actual user ID from session

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

    header("Location: post_form.php"); // Redirect to the main page
    exit();
}
?>
