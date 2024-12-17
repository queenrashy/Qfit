<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}
require '../Qfit/includes/dbh.inc.php'; // Ensure this file defines `$pdo` for the database connection
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profile Page</title>
  <link rel="stylesheet" href="/CSS/dashboard.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
</head>

<body>
  <nav>
    <ul>
      <li><a href="post_form.php">Post</a></li>
      <li><a href="generate.php">Generate</a></li>
      <li><a href="quote.php">Quotes</a></li>
      <li><a href="profile.php">Profile</a></li>
      <li>
        <a href="setting.html">Setting<i class="fa-solid fa-caret-down"></i></a>
        <ul class="dropdown">
          <li><a href="includes/logout.php">Log Out</a></li>
          <li><a href="service.html">Services</a></li>
          <li><a href="setting.html">Others</a></li>
        </ul>
      </li>
      <li>
        <p><i class="fa-solid fa-crown"></i>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
      </li>
    </ul>
  </nav>

  <?php
  //select user
  $stmt = $pdo->query("SELECT * FROM users WHERE username='" . $_SESSION['username'] . "' LIMIT 1");
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  try {
    // Fetch posts with like and comment counts and user profile pictures
    $stmt = $pdo->query("
        SELECT 
            posts.id AS post_id, 
            posts.content, 
            posts.created_at, 
            users.username, 
            users.profile_picture,  
            posts.user_id AS post_user_id,  
            COUNT(DISTINCT likes.id) AS like_count,
            COUNT(DISTINCT comments.id) AS comment_count
        FROM posts
        INNER JOIN users ON posts.user_id = users.id
        LEFT JOIN likes ON posts.id = likes.post_id
        LEFT JOIN comments ON posts.id = comments.post_id
        GROUP BY posts.id
        ORDER BY posts.created_at DESC
    ");

    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare a statement for fetching comments
    $stmt_comments = $pdo->prepare("
            SELECT comments.id, comments.content, users.username, users.profile_picture, comments.user_id AS commenter_id
            FROM comments 
            INNER JOIN users ON comments.user_id = users.id 
            WHERE comments.post_id = ?
        ");

    // Display posts
    foreach ($posts as $post) {
      $username = htmlspecialchars($post['username'] ?? 'Unknown');
      $profilePicture = "/uploads/" . htmlspecialchars($user['profile_pic'] ?? 'default.jpg');  //for displaying the image in post part
      // $profilePicture = "/uploads/" . htmlspecialchars($post['profile_picture'] ?? 'default.jpg');


      $likeCount = $post['like_count'];
      $commentCount = $post['comment_count'];



      echo "<div class='post'>";
      echo "<div class='post-header'>";
      echo "<img src='" . $profilePicture . "' alt='Profile Picture' class='profile-picture'>"; // Show profile picture
      echo "<strong>$username</strong> <span>" . htmlspecialchars($post['created_at']) . "</span>";
      echo "</div>";
      echo "<div class='post-content'><p>" . htmlspecialchars($post['content']) . "</p></div>";

      // Like and comment counts
      echo "<div class='post-actions'>";
      echo "<form action='includes/like_posts.php' method='POST'>
                      <input type='hidden' name='post_id' value='{$post['post_id']}'>
                      <button type='submit' class='like-btn'><i class='fa-regular fa-heart'></i>($likeCount)</button>
                  </form>";
      echo "<p>$commentCount Comments</p>";
      echo "</div>";

      // Fetch and display comments for the post
      $stmt_comments->execute([$post['post_id']]);
      $comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);
    
      echo "<div class='comments'>";
      foreach ($comments as $comment) {
        $commenter = htmlspecialchars($comment['username'] ?? 'Anonymous');
        $commenterProfilePicture = '/uploads/' . htmlspecialchars($comment['profile_picture'] ?? 'default.jpg'); // Handle default profile picture

        echo "<div class='comment'>";
        echo "<img src='$commenterProfilePicture' alt='Profile Picture' class='profile-picture'>"; // Show commenter profile picture
      // echo "<img src='" . $commenter . "' alt='Profile Picture' class='profile-picture' style='width: 100pX;'>"; // Show profile picture

        echo "<p><strong>$commenter:</strong> " . htmlspecialchars($comment['content']) . "</p>";

        // Add a delete button for the comment only for the commenter
        if ($_SESSION['user_id'] == $comment['commenter_id']) {
          echo "<form action='includes/delete_comment.php' method='POST'>
                              <input type='hidden' name='comment_id' value='{$comment['id']}'>
                              <button type='submit' class='delete-btn'><i class='fa-solid fa-trash'></i></button>
                          </form>";
        }

        echo "</div>";
      }

      // Display Delete button for the post if the logged-in user is the post owner
      if ($_SESSION['user_id'] == $post['post_user_id']) {
        echo "<form action='includes/delete_post.php' method='POST'>
                          <input type='hidden' name='post_id' value='{$post['post_id']}'>
                          <button type='submit' class='delete-btn1'>Delete Post</button>
                      </form>";
      }

      // Comment form
      echo "<form action='comments.php' method='POST' class='comment-form'>
                      <input type='hidden' name='post_id' value='{$post['post_id']}'>
                      <textarea name='content' placeholder='Write a comment...'></textarea>
                      <button type='submit'>Comment</button>
                  </form>";
      echo "</div></div>";
    }
  } catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
  }
  ?>
</body>

</html>