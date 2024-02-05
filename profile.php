<?php
session_start();
include('navbar.php');
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $new_name = mysqli_real_escape_string($conn, $_POST["new_name"]);
    $new_email = mysqli_real_escape_string($conn, $_POST["new_email"]);

    if (empty($new_name) || empty($new_email)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
        
        $check_email = "SELECT * FROM Users WHERE email = '$new_email' AND id != $user_id";
        $result = $conn->query($check_email);

        if ($result->num_rows == 0) {
            
            $update_user = "UPDATE Users SET name = '$new_name', email = '$new_email' WHERE id = $user_id";
            $conn->query($update_user);

            $_SESSION['user_name'] = $new_name;
            echo "Profile updated successfully!";
        } else {
            $errors[] = "Email is already registered by another user.";
        }
    
}
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM Users WHERE id = $user_id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$user_name = $row['name'];
$user_email = $row['email'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>User Profile</h2>
        
       
        
       

        <form method="post" action="">
            <div class="form-group">
                <label for="new_name">Name:</label>
                <input type="text" class="form-control" id="new_name" name="new_name" value="<?php echo $user_name; ?>" required>
            </div>
            <div class="form-group">
                <label for="new_email">Email:</label>
                <input type="email" class="form-control" id="new_email" name="new_email" value="<?php echo $user_email; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</body>
</html>