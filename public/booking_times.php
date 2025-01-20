<?php

require '../config/db.php';
include '../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$date = $_GET['date'];
$activityId = $_GET['activity_id'];

$stmt = $pdo->prepare("SELECT booking_time FROM bookings WHERE booking_date = :date AND activity_id = :activity_id");
$stmt->execute(['date' => $date, 'activity_id' => $activityId]);
$bookedTimes = $stmt->fetchAll(PDO::FETCH_COLUMN);

$timeSlots = ['09:00:00', '10:00:00', '11:00:00', '12:00:00','13:00:00', '14:00:00', '15:00:00', '16:00:00'];

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
            <h1>Available Time Slots for <?= htmlspecialchars($date) ?></h1><br><br><br>
            <div class="time-slots">
                <?php foreach ($timeSlots as $slot): ?>
                    <?php $isBooked = in_array($slot, $bookedTimes); ?>
                    <div class="time-slot <?= $isBooked ? 'unavailable' : 'available' ?>" 
                        <?= !$isBooked ? 'onclick="location.href=\'booking.php?date=' . $date . '&time=' . $slot . '&activity_id=' . $activityId . '\'"' : '' ?>>
                        <?= date('h:i A', strtotime($slot)) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>