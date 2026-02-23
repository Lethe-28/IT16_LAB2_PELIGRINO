<?php
session_start();
include("db.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h2>Welcome <?php echo htmlspecialchars($_SESSION['user']); ?></h2>

    <a href="add_student.php">Add Student</a> |
    <a href="logout.php">Logout</a>

    <h3>Student List</h3>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Student ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Course</th>
            <th>Action</th>
        </tr>

        <?php
        // Use a JOIN to combine the students table and courses table based on the foreign key
        $query = "SELECT students.id, students.student_id, students.fullname, students.email, courses.course_name 
          FROM students 
          JOIN courses ON students.course_id = courses.id";

        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                <td>
                    <form action="delete_student.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <button type="submit"
                            onclick="return confirm('Are you sure you want to delete this student?');">Delete</button>
                    </form>
                </td>
            </tr>
        <?php } ?>

    </table>

</body>

</html>