<?php

function randomToken($length = 6)
{
    $key = '';
    $keys = array_merge(range(0, 9));
    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }
    return $key;
}

require 'dbh.inc.php';
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    // find user with the email
    $stmt = $pdo->query("SELECT * FROM users WHERE email='$email' LIMIT 1");
    $user = $pdo->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $user_id = $user['id'];
        // generate random token
        $token = randomToken(5);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>

<body>
    <form method="post" action="index.php">
        <input type="email" placeholder="Enter email" name="email">
        <input type="submit" value="Submit">
    </form>
</body>

</html>