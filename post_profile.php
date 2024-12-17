<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/post_com_likes.css">
</head>

<body>
    <div class="post-box">
        <form action="includes/posts.php" method="POST">
            <textarea name="content" placeholder="Write something..."></textarea>
            <button type="submit">Post</button>
        </form>
    </div>




    <div class="post">
        <div class="post-content">
            <p>This is a sample post.</p>
        </div>
        <div class="post-actions">
            <!-- Like form -->
            <form action="like_posts.php" method="POST">
                <input type="hidden" name="post_id" value="POST_ID"> <!-- Replace POST_ID dynamically -->
                <button type="submit" class="like-btn">Like</button>
            </form>

            <!-- Comment button and section -->
            <form action="comments.php" method="POST">
                <input type="hidden" name="post_id" value="POST_ID"> <!-- Replace POST_ID dynamically -->
                <textarea name="content" placeholder="Write a comment..."></textarea>
                <button type="submit">Comment</button>
            </form>
        </div>
    </div>

</body>

</html>