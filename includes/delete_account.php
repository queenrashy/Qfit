<?php
session_start();
require 'dbh.inc.php'; // Include your database connection file

if (isset($_POST['delete_account'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "You must be logged in to delete your account.";
        exit();
    }

    $userId = $_SESSION['user_id'];

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Delete related comments
        $sql = "DELETE FROM comments WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Delete user
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Commit transaction
        $pdo->commit();

        // Logout user
        session_destroy();
        echo "Your account has been deleted successfully.";
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
