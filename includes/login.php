<?php
session_start();
require_once 'dbh.inc.php';

if (isset($_POST['login'])) {
    $email = $_POST ['email'];
    $pwd = $_POST ['pwd'];

    $sql = "SELECT * FROM users WHERE email = :email ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();

        if(password_verify($pwd, $user['pwd'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: ../dashboard.php");
            exit();
        } else{
            echo "Invalid password";
        }
    }
    else{
            echo "User not found!";
        }
    } else {
        echo "Login form was not submitted correctly.";
    }
