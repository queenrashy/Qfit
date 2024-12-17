<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .delete-container {
            text-align: center;
            margin-top: 50px;
        }
        .delete-container button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        .delete-container button:hover {
            background-color: #ff1a1a;
        }
    </style>
</head>
<body>
    <div class="delete-container">
        <h2>Delete Your Account</h2>
        <form action="includes/delete_account.php" method="POST">
            <p>Are you sure you want to delete your account? This action cannot be undone.</p>
            <button type="submit" name="delete_account">Delete My Account</button>
        </form>
    </div>
</body>
</html>
