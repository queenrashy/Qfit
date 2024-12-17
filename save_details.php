<?php
require_once '../Qfit/includes/dbh.inc.php';

$dob = isset($_POST['dob']) ? $_POST['dob'] : null;
$age = isset($_POST['age']) ? $_POST['age'] : null;
$gender = isset($_POST['gender']) ? $_POST['gender'] : null;

    

try {
        $sql = "INSERT INTO user_details (dob,age,gender) VALUES (:dob, :age,:gender)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':gender', $gender);

        if ($stmt->execute()) {
                header("location: dashboard.php");
                exit();
        } else {
                echo "Error: Could not save user details";
        }
} catch (PDOException $e) {
        echo "Error:" . $e->getMessage();
}

$pdo = null;
