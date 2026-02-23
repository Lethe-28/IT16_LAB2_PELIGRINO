<?php
include("db.php");

// The password we actually want to use
$plaintext = "admin123";

// Generate a fresh, mathematically perfect hash
$hashed_password = password_hash($plaintext, PASSWORD_BCRYPT);

// Update the database directly
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
$stmt->bind_param("s", $hashed_password);

if ($stmt->execute()) {
    echo "<h1>Success!</h1>";
    echo "The admin password has been successfully reset to: <b>admin123</b><br>";
    echo "Hash saved in database: " . $hashed_password . "<br><br>";
    echo "<a href='login.php'>Click here to go to the login page</a>";
} else {
    echo "Failed to reset password: " . $conn->error;
}
