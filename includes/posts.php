<?php
require 'dbh.inc.php'; // Your database connection

session_start();
$userId = $_SESSION['user_id'] ?? null; // Check if the user is logged in

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = trim($_POST['content']);

    if ($userId && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, content) VALUES (:user_id, :content)");
        $stmt->execute(['user_id' => $userId, 'content' => $content]);

        // Redirect to main page after submission
        header("Location:/post_form.php"); // Replace 'main.php' with your main page URL
        exit(); // Ensure no further code is executed after the redirect
    }
}

// Fetch posts for the user
$stmt = $pdo->query("
    SELECT posts.id, posts.content, posts.created_at, users.username 
    FROM posts 
    INNER JOIN users ON posts.user_id = users.id 
    ORDER BY posts.created_at DESC
");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


