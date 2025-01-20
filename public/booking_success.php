<?php

require '../config/db.php';
include '../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Astro-Max | Booking</title>
</head>
<body>
    <div class="general-background-container">
        <div class="times-main-container">
            <h1 class="sucessfull-book">Your booking was successfull!</h1><br><br>
            <a href="booking_calendar.php">Book Again</a>
        </div>
    </div>
</body>
</html>