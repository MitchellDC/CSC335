<?php
include 'student_db.php';

// Validate and sanitize the ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Use prepared statement for SELECT
$stmt = $conn->prepare("SELECT * FROM Student WHERE StudentID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Check if student exists
if (!$student) {
    die("Student not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {  // "POST" not "post"
    // Get form data with proper case
    $fname = $_POST['fname'] ?? '';    // $_POST not $_post
    $lname = $_POST['lname'] ?? '';
    $dbirth = $_POST['Dbirth'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['Adress'] ?? '';

    // Use prepared statement for UPDATE
    $sql = "UPDATE Student SET 
            Fname = ?, 
            Lname = ?,
            DBirth = ?, 
            Email = ?, 
            Adress = ? 
            WHERE ID = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $fname, $lname, $dbirth, $email, $address, $id);
    
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>
<body>
    <h1>Edit Student</h1>
    <form method="post">
        <p>
            <label>First Name:</label>
            <input type="text" name="fname" value="<?php echo htmlspecialchars($student['Fname'] ?? ''); ?>" required>
        </p>
        <p>
            <label>Last Name:</label>
            <input type="text" name="lname" value="<?php echo htmlspecialchars($student['Lname'] ?? ''); ?>" required>
        </p>
        <p>
            <label>Date of Birth:</label>
            <input type="date" name="Dbirth" value="<?php echo htmlspecialchars($student['DBirth'] ?? ''); ?>" required>
        </p>
        <p>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($student['Email'] ?? ''); ?>" required>
        </p>
        <p>
            <label>Address:</label>
            <input type="text" name="Adress" value="<?php echo htmlspecialchars($student['Adress'] ?? ''); ?>" required>
        </p>
        <p>
            <input type="submit" value="Update Student">
            <a href="index.php">Cancel</a>
        </p>
    </form>
</body>
</html>
<?php
$conn->close();
?>