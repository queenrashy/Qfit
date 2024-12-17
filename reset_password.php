<?php  
include './includes/dbh.inc.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the token and new password from the form
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if token exists in the database and is still valid
    $stmt = $pdo->prepare("SELECT * FROM users WHERE token = ? AND reset_token_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($newPassword === $confirmPassword) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the user's password and clear the reset token
            $stmt = $pdo->prepare("UPDATE users SET pwd = ?, token = NULL, reset_token_expiry = NULL WHERE token = ?");
            $stmt->execute([$hashedPassword, $token]);

            echo "<div class='success'>Password reset successful. You can now log in.</div>";
        } else {
            echo "<div class='error'>Passwords do not match.</div>";
        }
    } else {
        echo "<div class='error'>Invalid token or expired link.</div>";
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
      <h2>Reset Password</h2>
        <form action="reset_password.php" method="post">
            <!-- Input field for the user to enter the token -->
            <input type="text" name="token" placeholder="Enter the token" required>

            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>

            <button type="submit">Reset Password</button>
        </form>
    
        <p><a href="login.html">Login</a></p>
      </div>
    </main>
  </body>
</html>


