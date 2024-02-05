<?php
include('db.php');

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $token = mysqli_real_escape_string($conn, $_POST["token"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    
    if (empty($email) || empty($token) || empty($_POST["password"])) {
        $errors[] = "All fields are required.";
    }

    if (count($errors) === 0) {
        
        $check_token = "SELECT * FROM User WHERE email = '$email' AND reset_token = '$token'";
        $result = $conn->query($check_token);

        if ($result->num_rows > 0) {
            
            $update_password = "UPDATE User SET password = '$password', reset_token = NULL WHERE email = '$email'";
            $conn->query($update_password);

            echo "Password reset successful! <a href='login.php'>Login</a>";
        } else {
            $errors[] = "Invalid email or token.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Reset Password</h2>
        
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
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $_GET['email'] ?? ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="token">Token:</label>
                <input type="text" class="form-control" id="token" name="token" value="<?php echo $_GET['token'] ?? ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>
</body>
</html>
