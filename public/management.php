<?php

require '../config/db.php';

include '../templates/header.php';

if ($_SESSION['role'] != 'management') {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT user_id, username, role FROM users WHERE role IN ('staff', 'management')");
$stmt->execute();
$staffMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM activities");
$stmt->execute();
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['select_staff_id'], $_POST['select_new_role'])) {
    $selectedStaffId = $_POST['select_staff_id'];
    $newRole = $_POST['select_new_role'];

    $stmt = $pdo->prepare("UPDATE users SET role = :role WHERE user_id = :user_id");
    $stmt->execute(['role' => $newRole, 'user_id' => $selectedStaffId]);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['select_activity_id'])) {
    $delete_activity_id = $_POST['select_activity_id'];

    $stmt = $pdo->prepare("DELETE FROM activities WHERE activity_id = :activity_id");
    $stmt->execute(['activity_id' => $delete_activity_id]);

    $goodmsg = "Activity deleted successfully.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activity_name'], $_POST['activity_description'], $_FILES['uploadfile'])) {
    $activityName = trim($_POST['activity_name']);
    $activityDescription = trim($_POST['activity_description']);
    $fileName = $_FILES["uploadfile"]["name"];
    $tempImgLocation = $_FILES["uploadfile"]["tmp_name"];
    $finalImgLocation = "../activity-img-uploads/" . $fileName;

    $stmt = $pdo->prepare("SELECT * FROM activities WHERE activity_name = :activity_name");
    $stmt->execute(['activity_name' => $activityName]);

    if ($stmt->rowCount() > 0) {
        $errormsgactivity = "Activity already exists";
    } else {
        $stmt = $pdo->prepare("INSERT INTO activities (activity_name, activity_description, activity_img) VALUES (:activity_name, :activity_description, :activity_img)");
        $stmt->execute(['activity_name' => $activityName, 'activity_description' => $activityDescription, 'activity_img' => $finalImgLocation]);
        
        if (move_uploaded_file($tempImgLocation, $finalImgLocation)) {
            $goodmsgactivity = "Image uploaded and activity added successfully";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $errormsgactivity = "Failed to upload image";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'],$_POST['confirmpassword'] )) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];
    //this checks if the form details have been posted and stores them

    //this hashes the password and stores it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    //this prepaers and executes a statment which gets everything from the table users where username matches the stored one


    if (strlen($username) <= 3) { //this is checking if the username is under or equal to 3 characters
        $errormsg = "Username must be more than 3 characters long";
        //shows this error message if username is under or equal to 3 characters
    
    } elseif (strlen($password) <= 7) { //this is checking if the password is under or equal to 7 characters
        $errormsg = "Password must be at least 8 characters long";
        //shows an error message if password is under or equal to 7 characters
    
    } elseif ($password != $confirmpassword) { //checks if confirm password and password match
        $errormsg = "Password and confirm password do not match";
    
    } else if ($stmt->rowCount() > 0) { //checking if any rows are returned for same username to see if already registered or not
        $errormsg = "Username already taken. Please choose another one";
        //shows this error message if already registered

    } else { //otherwise it just inserts the login details into the database
        $insert = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
        $insert->execute(['username' => $username, 'password' => $hashedPassword, 'role' => 'staff']);
    
        $goodmsg = "Staff Registration Sucessfull"; //good message is set
    }
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Astro-Max | Management</title>
</head>
<body>
    <div class="general-background-container">
        <div class="roles-changing-container">
            <form method="POST" action="management.php">
                <h2 class="management-headings">Edit Roles</h2>
                <label class="roles-label" for="select-staff">Select Person:</label>
                <select id="select-staff" name="select_staff_id" required>
                    <option value="" disabled selected>Staff Member</option>
                    <?php foreach ($staffMembers as $staff): ?>
                        <option value="<?php echo htmlspecialchars($staff['user_id']); ?>">
                            <?php echo htmlspecialchars($staff['username'] . " - " . ucfirst($staff['role'])); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="roles-label" for="select-role">Select New Role:</label>
                <select id="select-role" name="select_new_role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="staff">Staff</option>
                    <option value="customer">Customer</option>
                    <option value="management">Management</option>
                </select><br>

                <button type="submit" class="updatebtn">Update Role</button>
            </form>
        </div>

        <div class="staff-register-container">
            <h2 class="management-headings">Register New Staff</h2>
            <form action="management.php" method="POST"> <!--this is the form which posts the form details-->
                <label for="username">Username: </label>
                <input type="text" class="staff-register" id="username" name="username" placeholder="Enter Username" required><br>
                <!--this is the username input box-->

                <label for="password">Password: </label>
                <input type="password" class="staff-register" id="password" name="password" placeholder="Enter Password" required><br>
                <!--password box which masks the password when entering-->

                <label for="confirmpassword">Confirm Password: </label>
                <input type="password" class="staff-register" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required><br>
                <!--password confirm to ensure the password is correct-->

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
            
                <input type="submit" value="Register" class="management-reg-btn">
            </form>
        </div>

        <div class="activity-management-container">
            <h2 class="management-headings">New Activity</h2>
            <form action="management.php" method="POST" enctype="multipart/form-data">
                <label for="activity_name">Name of activity:</label>
                <input type="text" id="activity_name" name="activity_name" class="staff-register" maxlength="20" required placeholder="Max 20 characters"><br>

                <label for="activity_description">Description of activity:</label>
                <textarea id="activity_description" name="activity_description" class="staff-register" maxlength="280" required placeholder="Max 280 characters"></textarea><br>

                <label for="file">Activity image:</label>
                <input id="file-upload" type="file" name="uploadfile" accept="image/png, image/jpeg, image/jfif" required />

                <?php if(isset($errormsgactivity)): ?>
                    <div class="error-box">
                        <p><?php echo $errormsgactivity; ?></p>
                    </div>
                <?php endif; ?><br>

                <button type="submit" class="management-reg-btn">Add Activity</button>
            </form>
        </div>

        <div class="delete-activity-container">
            <form method="POST" action="management.php">
                <h2 class="management-headings">Delete Activity</h2>
                <label class="roles-label" for="select-activity">Select Activity:</label>
                <select id="select-activity" name="select_activity_id" required>
                    <option value="" disabled selected>Activities</option>
                    <?php foreach ($activities as $activity): ?>
                        <option value="<?php echo htmlspecialchars($activity['activity_id']); ?>">
                            <?php echo htmlspecialchars($activity['activity_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>

                <button type="submit" class="management-reg-btn">Delete Activity</button>
            </form>
        </div>
    </div>
</body>
</html>