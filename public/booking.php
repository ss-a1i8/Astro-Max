<?php

require '../config/db.php';
include '../templates/header.php';

$userId = $_SESSION['user_id'];
$activityId = $_GET['activity_id'];
$bookingDate = $_GET['date'];
$bookingTime = $_GET['time'];

$stmt = $pdo->prepare("SELECT activity_name FROM activities WHERE activity_id = :activity_id");
$stmt->execute(['activity_id' => $activityId]);
$activityName = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE booking_date = :booking_date AND booking_time = :booking_time AND activity_id = :activity_id");
$stmt->execute(['booking_date' => $bookingDate, 'booking_time' => $bookingTime, 'activity_id' => $activityId]);

$slotTaken = $stmt->fetchColumn() > 0;

if ($slotTaken) {
    die('This slot is already booked. Please choose another time.');
}

$stmt = $pdo->prepare("INSERT INTO bookings (activity_id, activity_name, booking_date, booking_time, user_id) VALUES (:activity_id, :activity_name, :booking_date, :booking_time, :user_id)");
$stmt->execute(['activity_id' => $activityId,'activity_name' => $activityName,'booking_date' => $bookingDate,'booking_time' => $bookingTime,'user_id' => $userId]);

header('Location: booking_success.php');
exit;

?>