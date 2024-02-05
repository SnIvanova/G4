<?php
include('navbar.php');
include('db.php');

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (count($errors) === 0) {

        $check_email = "SELECT * FROM Users WHERE email = '$email'";
        $result = $conn->query($check_email);

        if ($result->num_rows > 0) {
           
            $token = bin2hex(random_bytes(32));
            $update_token = "UPDATE Users SET reset_token = '$token' WHERE email = '$email'";
            $conn->query($update_token);

            
            $reset_link = "http://web.com/reset_password.php?email=$email&token=$token";
            

            echo "Password reset link has been sent to your email.";
        } else {
            $errors[] = "Email not found.";
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
    <title>Password Recovery</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Password Recovery</h2>
        
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
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </form>
    </div>
</body>
</html>