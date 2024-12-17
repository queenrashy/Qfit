<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Post page</title>
  <link rel="stylesheet" href="/CSS/post.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
</head>

<body>
<nav>
    <ul>
    <li class="crown"><i class="fa-solid fa-crown"></i>Qfit</li>

      <li><a href="dashboard.php">Home</a></li>

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
      </ul>
  </nav>

  <form class="input-area" action="includes/posts.php" method="POST">
    <textarea name="content" id="input" rows="10" placeholder="What's on your mind?...."></textarea>
    <button class="btn3" type="submit">Send</button>
  </form> 




  <?php
  session_start();

  include 'includes/dbh.inc.php';

  // Fetch all posts
  $stmt = $pdo->query("
  SELECT posts.id, posts.content, posts.created_at, users.username 
  FROM posts 
  INNER JOIN users ON posts.user_id = users.id 
  ORDER BY posts.created_at DESC
");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as $post) {
  $username = htmlspecialchars($post['username'] ?? 'Unknown');
  echo "<div class='post'>";
  echo "<div class='post-header'><strong>$username</strong> <span>" . htmlspecialchars($post['created_at']) . "</span></div>";
  echo "<div class='post-content'><p>" . htmlspecialchars($post['content']) . "</p></div>";

  // Like and comment form
  echo "<div class='post-actions'>";
  echo "<form action='like.php' method='POST'>
            <input type='hidden' name='post_id' value='{$post['id']}'>
            <button type='submit' class='like-btn'><i class='fa-regular fa-heart'></i></button>
        </form>";

  // Display comments for the post
  echo "<div class='comments'>";
  $stmt_comments = $pdo->prepare("
      SELECT comments.content, users.username 
      FROM comments 
      INNER JOIN users ON comments.user_id = users.id 
      WHERE comments.post_id = ?
  ");
  $stmt_comments->execute([$post['id']]);
  $comments = $stmt_comments->fetchAll();

  foreach ($comments as $comment) {
      $commenter = htmlspecialchars($comment['username'] ?? 'Anonymous');
      echo "<div class='comment'><p><strong>$commenter:</strong> " . htmlspecialchars($comment['content']) . "</p></div>";
  }

  // Comment form
  echo "<form action='comments.php' method='POST' class='comment-form'>
            <input type='hidden' name='post_id' value='{$post['id']}'>
            <textarea name='content' placeholder='Write a comment...'></textarea>
            <button type='submit'>Comment</button>
        </form>";
  echo "</div></div></div>";



    // Comment form
    // echo "<form action='comments.php' method='POST' class='comment-form'>
    //           <input type='hidden' name='post_id' value='{$post['id']}'>
    //           <textarea name='content' placeholder='Write a comment...'></textarea>
    //           <button type='submit'>Comment</button>
    //       </form>";
    // echo "</div></div></div>";
}

  ?>
<!-- 
  <form class="input-area" action="includes/post.php" method="POST">
    <textarea name="content" id="input" rows="10" placeholder="What's on your mind?...."></textarea>
    <button class="btn3" type="submit">Send</button>
  </form> -->


</body>

</html>