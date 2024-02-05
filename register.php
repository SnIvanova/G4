<?php
include('navbar.php');
include('db.php');

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);


   if (empty($name) || empty($email) || empty($_POST["password"])) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (count($errors) === 0) {
        
        $check_email = "SELECT * FROM Users WHERE email = '$email'";
        $result = $conn->query($check_email);

        if ($result->num_rows > 0) {
            $errors[] = "Email is already registered.";
        } else {
           
            $sql = "INSERT INTO Users (name, email, password) VALUES ('$name', '$email', '$password')";

            if ($conn->query($sql) === TRUE) {
                echo "Registration successful!";
            } else {
                $errors[] = "Error: " . $sql . "<br>" . $conn->error;
            }
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
    <title>Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Registration</h2>
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
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</body>
</html>