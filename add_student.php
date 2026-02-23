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
        <input type="text" name="student_id" placeholder="Student ID" required><br>
        <input type="text" name="fullname" placeholder="Full Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>

        <select name="course_id" required style="width: 90%; padding: 8px; margin: 5px;">
            <option value="" disabled selected>Select a Course</option>
            <?php
            // Fetch all courses from the database to populate the dropdown
            $courses_query = "SELECT id, course_name FROM courses";
            $courses_result = $conn->query($courses_query);

            while ($course = $courses_result->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($course['id']) . '">' . htmlspecialchars($course['course_name']) . '</option>';
            }
            ?>
        </select><br>

        <button name="add">Add Student</button>
    </form>
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
    </div>

</body>

</html>