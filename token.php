<?php
// Generate a token manually and update the user's record in the database
include './includes/dbh.inc.php';

$email = 'user@example.com'; // Use the user's email or ID
$token = bin2hex(random_bytes(16)); // Generate a 32-character token
$expires = date('U') + 3600; // Token expiration time (1 hour)

$stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
$stmt->execute([$token, $expires, $email]);

// echo "Token generated and stored in the database for user: $email. Token: $token";
?>