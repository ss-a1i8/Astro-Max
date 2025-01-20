<?php

require '../config/db.php';
include '../templates/header.php';

$query = $pdo->query("SELECT * FROM activities");
$items = $query->fetchALL(PDO::FETCH_ASSOC);

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Astro-Max | Activities</title>
</head>
<body>
    <div class="general-background-container">
        <?php foreach ($items as $item): ?>
            <div class="seperate-activity-containers">
                <strong><?php echo htmlspecialchars($item["activity_name"]); ?></strong><br><br>
                <img src="<?php echo htmlspecialchars($item["activity_img"]); ?>"><br><br>
                <p><?php echo htmlspecialchars($item["activity_description"]); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>