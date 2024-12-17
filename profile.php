<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profile page</title>
  <link rel="stylesheet" href="/CSS/profile.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
</head>

<body>
<nav>
<?php
session_start();
require '../Qfit/includes/dbh.inc.php'; // Ensure this file defines `$pdo` for the database connection

if (!isset($_SESSION['user_id'])) {
  die("User not logged in.");
}

$userId = $_SESSION['user_id'];

try {
  $sql = "SELECT profile_pic FROM users WHERE id = :user_id";
  $stmt = $pdo->prepare($sql);  // Make sure `$pdo` matches the variable in `dbh.inc.php`
  $stmt->execute(['user_id' => $userId]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $profilePic = $row['profile_pic'] ? 'uploads/' . $row['profile_pic'] : 'uploads/default.jpg';
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>
      <ul>
    
    <li class="crown"><img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture" width="150"> </li>
  
        <li><a href="dashboard.php">Home</a></li>
  
        <li><a href="post_form.php">Post</a></li>
        <li><a href="quote.php">Quotes</a></li>
        <li><a href="generate.php">Generate</a></li>
        <li>
          <a href="setting.html">Setting<i class="fa-solid fa-caret-down"></i></a>
          <ul class="dropdown">
            <li><a href="includes/logout.php">Log Out</a></li>
            <li><a href="service.html">Services</a></li>
          </ul>
        </li>
        </ul>
    </nav>

    <form action="includes/upload.php" method="post" enctype="multipart/form-data">
    <label for="profile_image">Upload Profile Image:</label>
    <input type="file" name="profile_pic" id="profile_image" accept="image/*" required>
    <button type="submit" name="submit">Upload Image</button>
</form>




<?php
require '../Qfit/includes/dbh.inc.php'; // Ensure this file defines `$pdo` for the database connection


if (!isset($_SESSION['user_id'])) {
  die("User not logged in.");
}

$userId = $_SESSION['user_id'];

try {
  // Fetch posts by user
  $sql = "SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['user_id' => $userId]);
  $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Display posts
  foreach ($posts as $post) {
    echo "<div class='post'>";
    echo "<p>" . htmlspecialchars($post['content']) . "</p>";
    echo "<small>Posted on: " . $post['created_at'] . "</small>";
    echo "</div>";
  }
} catch (PDOException $e) {
  echo "Database error: " . $e->getMessage();
}
?>
</body>
</html>