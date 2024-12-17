<?php
session_start();
require 'dbh.inc.php'; // Ensure database connection is available

if (isset($_POST['submit'])) {
    if (!isset($_SESSION['user_id'])) {
        die("User not logged in."); // Prevent unauthorized access
    }

    $userId = $_SESSION['user_id'];

    // Check if a file is uploaded without errors
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_pic'];

        // Extract file properties
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $mimeType = mime_content_type($fileTmpName);

        // Define allowed file extensions and MIME types
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $allowedMimeTypes = ['image/jpeg', 'image/png'];

        // Validate file type and size
        if (in_array($fileExt, $allowedExtensions) && in_array($mimeType, $allowedMimeTypes)) {
            if ($fileSize < 1000000) { // 1MB size limit
                // Generate a unique name for the uploaded file
                $newFileName = "profile_" . $userId . "_" . uniqid('', true) . "." . $fileExt;
                $uploadDir = realpath(__DIR__ . '/../uploads/'); // Ensure the path is correct
                $fileDestination = $uploadDir . '/' . $newFileName;

                // Ensure the upload directory exists and is writable
                if (!$uploadDir || !is_writable($uploadDir)) {
                    die("Upload directory is not writable!");
                }

                // Move the uploaded file to the desired location
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    // Retrieve the old profile picture from the database
                    $stmt = $pdo->prepare("SELECT profile_pic FROM users WHERE id = :user_id");
                    $stmt->execute(['user_id' => $userId]);
                    $oldFile = $stmt->fetchColumn();

                    // Remove the old profile picture if it's not the default and exists
                    if ($oldFile && $oldFile !== 'default.jpg' && file_exists("../uploads/$oldFile")) {
                        unlink("../uploads/$oldFile");
                    }

                    // Update the database with the new profile picture
                    $sql = "UPDATE users SET profile_pic = :profile_pic WHERE id = :user_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['profile_pic' => $newFileName, 'user_id' => $userId]);

                    // Set success message and redirect
                    $_SESSION['upload_success'] = "Profile picture updated successfully!";
                    header("Location: ../profile.php");
                    exit();
                } else {
                    // Handle file move failure
                    $_SESSION['upload_error'] = "Failed to move uploaded file.";
                    header("Location: ../profile.php");
                    exit();
                }
            } else {
                // Handle file size exceeding limit
                $_SESSION['upload_error'] = "File size too large! Maximum allowed size is 1MB.";
                header("Location: ../profile.php");
                exit();
            }
        } else {
            // Handle invalid file type
            $_SESSION['upload_error'] = "Invalid file type! Only JPG, JPEG, and PNG files are allowed.";
            header("Location: ../profile.php");
            exit();
        }
    } else {
        // Handle no file uploaded or upload error
        $_SESSION['upload_error'] = "No file uploaded or an error occurred!";
        header("Location: ../profile.php");
        exit();
    }
} else {
    echo "Invalid request!";
}
