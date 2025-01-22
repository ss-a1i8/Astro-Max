<?php

require '../config/db.php';
include '../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: ../public/home.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$name = $user['username'];

$stmt = $pdo->prepare("SELECT COUNT(*) AS row_count FROM bookings WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$result = $stmt->fetch();
$numofbookings = $result['row_count'];

$booking_stmt = $pdo->prepare("SELECT * FROM bookings WHERE user_id = :user_id");
$booking_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$booking_stmt->execute();
$bookings = $booking_stmt->fetchAll(PDO::FETCH_ASSOC);

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Astro-Max | Profile</title>
</head>
<body>
    <div class="general-background-container">
        <div class="main-profile-container">
            <div class="profile-sidebar">
                <img src="../css/images/profile.png" alt="profile pic"><br><br><br>
                <h2 class="name-text-label">Name: </h2><h2 class="name-text-name"><?php echo htmlspecialchars($name); ?></h2></h2><br>
                <h2 class="name-text-label">Bookings: </h2><h2 class="name-text-name"><?php echo htmlspecialchars($numofbookings); ?></h2></h2><br><br>
                <div class="logout-container">
                    <a href="?action=logout" class="nav-link-logout">Logout</a>
                </div>
            </div>

            <div class="main-profile-area">
                <?php if (count($bookings) > 0): ?>
                    <h1>Your Bookings:</h1><br>
                    <ul>
                        <?php foreach ($bookings as $booking): ?>
                            <li>
                                <?php echo htmlspecialchars($booking['activity_name']); ?> |
                                <span class="booking-details">
                                    Date: <?php echo htmlspecialchars($booking['booking_date']); ?> |
                                    Time: <?php echo htmlspecialchars($booking['booking_time']); ?> 
                                </span>
                            </li><br><br>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No bookings</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>