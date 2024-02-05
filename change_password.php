<?php
session_start();
include('db.php');
include('navbar.php');

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    }

    $get_password = "SELECT password FROM Users WHERE id = $user_id";
    $result = $conn->query($get_password);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (!password_verify($current_password, $row["password"])) {
            $errors[] = "Current password is incorrect.";
        }
    }

    if ($new_password !== $confirm_password) {
        $errors[] = "New password and confirm password do not match.";
    }

    if (count($errors) === 0) {
        
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_password = "UPDATE Users SET password = '$hashed_password' WHERE id = $user_id";
        $conn->query($update_password);

        echo "Password changed successfully!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Change Password</h2>
        
        <?php
        
        if (count($errors) > 0) {
            echo '<div class="alert alert-danger">';
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
            echo '</div>';
        }
        ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>
    </div>
</body>
</html>