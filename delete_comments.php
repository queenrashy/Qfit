<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deleted comment </title>
</head>
<body>
foreach ($comments as $comment) {
    echo "<div class='comment'>";
    echo "<p><strong>" . htmlspecialchars($comment['username']) . ":</strong> " . htmlspecialchars($comment['content']) . "</p>";
    
    if ($_SESSION['user_id'] == $comment['user_id']) {
        echo "<form action='includes/delete_comment.php' method='POST'>
                <input type='hidden' name='comment_id' value='" . $comment['id'] . "'>
                <button type='submit' class='delete-btn'>Delete</button>
              </form>";
    }

    echo "</div>";
}

</body>
</html>