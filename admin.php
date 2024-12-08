<?php
include 'student_db.php';
$student_result = $conn->query("SELECT * FROM Students");
$instructor_result = $conn->query("SELECT * FROM Instructors");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>  
</head>  
<body>
    <h1>Admin Page</h1>
    <a href="create_db.php">Add new Student</a>
    <table border="1">  
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Enrollment_Year</th>
            <th>Major</th>
        </tr>  
        <?php while($row = $student_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['student_id']; ?></td>
            <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
            <td><?php echo $row['address']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['enrollment_year']; ?></td>
            <td><?php echo $row['major']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $row['student_id']; ?>">Edit</a>|
                <a href="delete.php?id=<?php echo $row['student_id']; ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
            </td>
        </tr>  
        <?php endwhile; ?>
    </table>
    <br>
    <table border="1">  
    <a href="instructor.php">Add new Instructor</a>
        <tr>
            <th>Instructor ID</th>
            <th>Name</th>
            <th>Department</th>
            <th>Contact_info</th>
        </tr>  
        <?php while($row = $instructor_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['instructor_id']; ?></td>
            <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
            <td><?php echo $row['department']; ?></td>
            <td><?php echo $row['contact_info']; ?></td>
            <td>
                <a href="instructor_edit.php?id=<?php echo $row['instructor_id']; ?>">Edit</a>|
                <a href="instructor_delete.php?id=<?php echo $row['instructor_id']; ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
            </td>
        </tr>  
        <?php endwhile; ?>
    </table>

</body>
</html>
<?php
//now clossing the conn...
$conn ->close();
?>