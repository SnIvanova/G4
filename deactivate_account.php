<?php
session_start();
include('navbar.php');
include('db.php');

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $password = $_POST["password"];

  
    if (empty($password)) {
        $errors[] = "Please enter your password last time.";
    }

  
    $get_password = "SELECT password FROM Users WHERE id = $user_id";
    $result = $conn->query($get_password);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (!password_verify($password, $row["password"])) {
            $errors[] = "Password is incorrect.";
        }
    }

    if (count($errors) === 0) {
     
        $delete_user = "DELETE FROM Users WHERE id = $user_id";
        $conn->query($delete_user);


        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deactivate Account</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Deactivate Account</h2>
        
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
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-danger">Deactivate Account</button>
        </form>
    </div>
</body>
</html>