<?php

session_start();

$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset ($_SESSION['role']);
$role = $isLoggedIn ? $_SESSION['role'] : null;


if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();

    header("Location: http://localhost:3000/public/home.php");
    exit();
}

?>




<header class="navbar">
    <div class="logo">
        <a href="http://localhost:3000/public/home.php">
            <img src="../css/images/logo.png" alt="Astro-Max Logo">
        </a>
    </div>
    <nav class="nav-links">
        <a href="http://localhost:3000/public/home.php" class="nav-link">Home</a>
        <a href="#" class="nav-link">Activities</a>
        <a href="#" class="nav-link">Booking</a>
        <?php if ($isLoggedIn): ?>
            <?php if ($role === 'customer'): ?>
                <a href="http://localhost:3000/public/customer.php" class="nav-link">Profile</a>
            <?php elseif ($role === 'staff'): ?>
                <a href="http://localhost:3000/public/staff.php" class="nav-link">Dashboard</a>
            <?php elseif ($role === 'management'): ?>
                <a href="http://localhost:3000/public/management.php" class="nav-link">Dashboard</a>
            <?php endif; ?>
        <?php endif; ?>
    </nav>
    <div class="login-register-logout-container">
        <?php if ($isLoggedIn): ?>
            <div class="logout-container">
                <a href="?action=logout" class="nav-link-logout">Logout</a>
            </div>
        <?php else: ?>
            <div class="login-register">
                <a href="http://localhost:3000/public/login.php" class="login-link">Login | Register</a>
            </div>
        <?php endif; ?>
    </div>
</header>