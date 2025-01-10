<?php

session_start(); //this starts the session

include '../config/db.php'; //this links the database file

include '../templates/header.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password']; //this gets the posted username and password and stores it

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    //this prepares and executes a statement which gets everything for the username and stores it in users

    if ($user && password_verify($password, $user['password'])) {
    //password verify will automatically un-hash the password and see if it matches
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; //this stores the user_id, username and role in the session
        header("Location: home.php");

    } else {
        $errormsg = "Incorrect username or password"; //this sets the error message
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css"> <!--links the stylesheet-->
    <title>Astro-Max Login</title>
</head>
<body>
    <div class="general-background-container">
        <div class="register-and-login-container">
            <h2>Login</h2>
            <form action="login.php" method="POST"> <!--this is the form which posts the form details-->
                <label for="username">Username: </label>
                <input type="text" id="username" name="username" placeholder="Enter Username" required><br>
                <!--this is the username input box-->

                <label for="password">Password: </label>
                <input type="password" id="password" name="password" placeholder="Enter Password" required><br>
                <!--password box which masks the password when entering-->

                <?php if(isset($errormsg)): ?>
                    <div class="error-box">
                        <p><?php echo $errormsg; ?></p>
                    </div>
                <?php endif; ?> <!--these are the php loops which display the messages-->

                <?php if(isset($goodmsg)): ?>
                    <div class="good-box">
                        <p><?php echo $goodmsg; ?></p>
                    </div>
                <?php endif; ?><br>
            
                <input type="submit" value="Login"><br><br> <!--this is the submit button-->

                <a href="register.php" class="login-link">Not got an account? Register here</a>
                <!--this is the redirect link which goes to register-->
            </form>
        </div>
    </div>
</body>