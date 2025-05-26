<?php
session_start();

include_once '../../modules/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "<script>alert('Please fill in all fields.');</script>";
    } else {
        
        $sql = $connection->prepare("SELECT userID, userName, passKEY FROM user WHERE username = ?");
        $sql->bind_param("s", $username);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if ($password === $row['passKEY']) {
                $_SESSION['userID'] = $row['userID'];
                $_SESSION['username'] = $row['userName'];
                header("Location: ../userPage/homepage.php");
                exit();
            } else {
                echo "<script>alert('Invalid password.');</script>";
            }
        } else {
            echo "<script>alert('No user found with that username.');</script>";
        }
    }
}
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>

<div class="login-BG">
    <div class="login-container">
        <div class="login-content">
            <div class="login-topWrapper">
                <h1>LOG IN</h1>
            </div>
            <div class="login-botWrapper">
                <form method="POST" action="">
                    <label for="username">Username</label>
                    <input type="text" placeholder="Username" name="username" id="username" required>

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>

                    <button type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
</div> <!-- login-BG -->
    
</body>
</html>