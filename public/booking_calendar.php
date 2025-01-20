<?php

require '../config/db.php';
include '../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['activity_id'])) {
    $selectedActivity = $_GET['activity_id'];
    $stmt = $pdo->prepare("SELECT activity_name FROM activities WHERE activity_id = :activity_id");
    $stmt->bindParam(':activity_id', $selectedActivity, PDO::PARAM_INT);
    $stmt->execute();
    $activity = $stmt->fetch(PDO::FETCH_ASSOC);
    $activityName = $activity['activity_name'];
}

$stmt = $pdo->prepare("SELECT activity_id, activity_name FROM activities");
$stmt->execute();
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT booking_date, COUNT(*) AS total_slots FROM bookings GROUP BY booking_date");
$stmt->execute();
$bookedDates = $stmt->fetchAll(PDO::FETCH_ASSOC);

$daysInMonth = date('t');
$currentMonth = date('Y-m');
$today = date('Y-m-d');
$justMonth = date('F');

$fullyBookedDates = array_column(array_filter($bookedDates, fn($d) => $d['total_slots'] >= 8), 'booking_date');

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
        <div class="calendar-main-container">
            <h1>Booking Calendar for <?php echo($justMonth) ?></h1><br><br>
            <form method="GET" action="booking_calendar.php">
                <label for="activity">Select an Activity:</label>
                <select id="activity" name="activity_id" class="select-booking" required>
                    <option value="" disabled selected>Select an Activity</option>
                    <?php foreach ($activities as $activity): ?>
                        <option value="<?= htmlspecialchars($activity['activity_id']) ?>">
                            <?= htmlspecialchars($activity['activity_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="management-reg-btn">Select Activity</button><br><br>
            </form>

            <?php if (isset($_GET['activity_id'])): ?>
                <?php $selectedActivity = $_GET['activity_id']; ?>
                <h1>Available Dates for <?= htmlspecialchars($activityName) ?></h1>
                <div class="calendar">
                    <?php
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $date = $currentMonth . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                        $isPastDate = $date < $today;
                        $isFullyBooked = in_array($date, $fullyBookedDates);
                        $isUnavailable = $isPastDate || $isFullyBooked;

                        echo '<div class="day ' . ($isUnavailable ? 'unavailable' : 'available') . '"' .
                            (!$isUnavailable ? ' onclick="location.href=\'booking_times.php?date=' . $date . '&activity_id=' . $selectedActivity . '\'"' : '') .
                            '>' . $day . '</div>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>