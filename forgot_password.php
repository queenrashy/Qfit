<?php
include './includes/dbh.inc.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $identifier = filter_input(INPUT_POST, 'identifier', FILTER_SANITIZE_STRING);

  if (empty($identifier)) {
      echo "<div class='error'>Please enter your email or username.</div>";
  } else {
      // Find user by email or username
      $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
      $stmt->execute([$identifier, $identifier]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user) {
          // Generate a unique reset token and set expiry time (e.g., 2 hours)
          $resetToken = bin2hex(random_bytes(16));
          $expiryTime = date('Y-m-d H:i:s', time() + (2 * 60 * 60));

          // Store the token and expiry time in the database
          $stmt = $pdo->prepare("UPDATE users SET token = ?, reset_token_expiry = ? WHERE id = ?");
          $stmt->execute([$resetToken, $expiryTime, $user['id']]);

          // Redirect to reset password page with the token
          header("Location: reset_password.php?token=" . urlencode($resetToken));
          exit;
      } else {
          echo "<div class='error'>No user found with the provided email or username.</div>";
      }
  }
}
?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login page</title>
    <link rel="stylesheet" href="/CSS/login.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
      integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
  </head>
  <body>
    <nav>
      <div>
        <h1><i class="fa-solid fa-crown"></i>Qfit</h1>
      </div>
      <div>
        <button type="submit" class="btn1">
          <a href="login.html">Sign in</a>
        </button>
        <button type="submit" class="btn2">
          <a href="sign-up.html">Sign up</a>
        </button>
      </div>
    </nav>
    <main>
    <div class="form">
        <h2>Forgot Password</h2>
        <form action="forgot_password.php" method="post">
            <input type="text" name="identifier" placeholder="Enter your email or username" required>
            <button type="submit">Request Reset Link</button>
          <a href="sign-up.html">Sign Up?</a>

        </form>
        <p><a href="login.html">Login</a></p>

    </div>
      
    
    </main>
  </body>
</html>
