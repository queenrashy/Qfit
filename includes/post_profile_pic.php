<?php
require 'dbh.inc.php';

try {
    // Fetch posts with user profile pictures
    $stmt = $pdo->prepare("
        SELECT posts.content, posts.created_at, users.profile_picture, users.username
        FROM posts
        JOIN users ON posts.user_id = users.id
        ORDER BY posts.created_at DESC
    ");
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <link rel="stylesheet" href="../CSS/post_com_likes.css">
</head>
<body>
    <div class="posts">
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <!-- Display profile picture -->
                <div class="user-profile">
                    <img src="<?= htmlspecialchars($post['profile_picture']) ?>" alt="Profile Picture" class="profile-pic">
                </div>
                <div class="post-content">
                    <h4><?= htmlspecialchars($post['username']) ?></h4>
                    <p><?= htmlspecialchars($post['content']) ?></p>
                    <small>Posted on <?= htmlspecialchars($post['created_at']) ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
