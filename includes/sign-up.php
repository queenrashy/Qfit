<?php 
session_start();
require_once 'dbh.inc.php';

if (isset($_POST['next'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    if (empty($username) || empty($email) || empty($pwd)) {
        echo "All fields are required";
        exit();
    }

    $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username, 'email' => $email]);

    if ($stmt->rowCount() > 0) {
        echo "Username or email already taken!";
        exit();
    }

    $hashedPassword = password_hash($pwd, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, pwd) VALUES (:username, :email, :pwd)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute(['username' => $username, 'email' => $email, 'pwd' => $hashedPassword])) {
        echo "Account created successfully!";
        header("Location:/user_details_form.html");
        exit();
    } else {
        echo "There was an error creating your account";
    }
} else {
    echo "Invalid request!";
}
