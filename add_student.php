<?php
session_start();
include("db.php");

// Access Control Validation
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {

    $student_id = trim($_POST['student_id']);
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $course_id = filter_var($_POST['course_id'], FILTER_VALIDATE_INT);

    // Basic Input Validation
    if (!empty($student_id) && !empty($fullname) && filter_var($email, FILTER_VALIDATE_EMAIL) && $course_id) {
        $stmt = $conn->prepare("INSERT INTO students (student_id, fullname, email, course_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $student_id, $fullname, $email, $course_id);
        $stmt->execute();
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid input data. Please ensure the email is valid.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Student</title>
</head>

<body>

    <h2>Add Student</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>" . htmlspecialchars($error) . "</p>"; ?>
    <form method="POST">
        Student ID: <input type="text" name="student_id" required><br>
        Full Name: <input type="text" name="fullname" required><br>
        Email: <input type="text" name="email" required><br>
        Course ID (1 for BSIT, 2 for BSCS): <input type="number" name="course_id" required><br>
        <button name="add">Add</button>
    </form>

</body>

</html>