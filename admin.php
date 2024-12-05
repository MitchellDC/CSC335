<?php
include 'student_db.php';
$result = $conn->query("SELECT * FROM Students");
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
            <th>Name</th>
            <th>Address</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Enrollment_Year</th>
            <th>Major</th>
        </tr>  
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
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
    <table border="2">  
        <tr>
            <th>Name</th>
            <th>Department</th>
            <th>Contact_info</th>
        </tr>  
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['department']; ?></td>
            <td><?php echo $row['contact_info']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $row['instructor_id']; ?>">Edit</a>|
                <a href="delete.php?id=<?php echo $row['instructor_id']; ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
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