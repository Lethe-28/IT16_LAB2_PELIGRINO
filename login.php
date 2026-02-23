<?php
session_start();
include("db.php");

$error_message = "";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])){

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                session_regenerate_id(true); // Prevent session fixation
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user'] = $row['username'];
                header("Location: dashboard.php");
                exit();
            }
        }
        $error_message = "Invalid username or password.";
    } else {
        $error_message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">
        <h2>Admin Login</h2>
        <?php if ($error_message) echo "<p style='color:red;'>".htmlspecialchars($error_message)."</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button name="login">Login</button>
        </form>
    </div>

</body>

</html>