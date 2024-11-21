<?php
include 'student_db.php';
$result = $conn->query("SELECT * FROM Students");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student List</title>  
</head>  
<body>
    <h1>Student List</h1>
    <a href="create_db.php">Add new Student</a>
    <table border="1">  
        <tr>
            <th>student_id</th>
            <th>name</th>
            <th>address</th>
            <th>email</th>
            <th>phone</th>
            <th>enrollment_year</th>
            <th>major</th>
        </tr>  
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['student_id']; ?></td>
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
</body>
</html>
<?php
//now clossing the conn...
$conn ->close();
?>