<?php
include 'student_db.php';
$result = $conn->query("SELECT * FROM student");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student List</title>  
</head>  
<body>
    <h1>Student List</h1>
    <a href="create.php">Add new Student</a>
    <table border="1">  
        <tr>
            <th>ID</th>
            <th>FName</th>
            <th>LName</th>
            <th>DBirth</th>
            <th>Email</th>
            <th>Adress</th>
            <th>Actions</th>
        </tr>  
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['StudentID']; ?></td>
            <td><?php echo $row['FName']; ?></td>
            <td><?php echo $row['LName']; ?></td>
            <td><?php echo $row['DateOfBirth']; ?></td>
            <td><?php echo $row['Email']; ?></td>
            <td><?php echo $row['Adress']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $row['StudentID']; ?>">Edit</a>|
                <a href="delete.php?id=<?php echo $row['StudentID']; ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
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