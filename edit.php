<?php
include 'student_db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT * FROM Students WHERE student_id = ?");
if (!$stmt) {
    die("Error preparing SELECT statement: " . $conn->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Student not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $student_id = $_POST['student_id'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $address = $_POST['address'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $enrollment_year = (int)($_POST['enrollment_year'] ?? 0); 
    $major = $_POST['major'] ?? '';

    $sql = "UPDATE Students SET 
            student_id = ?, 
            first_name = ?, 
            last_name = ?,
            address = ?,
            email = ?, 
            phone = ?,
            enrollment_year = ?, 
            major = ? 
            WHERE student_id = ?";
            
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing UPDATE statement: " . $conn->error);
    }

    $stmt->bind_param("issssissi", $student_id, $first_name, $last_name, $address, $email, $phone, $enrollment_year, $major, $id);
    
    if ($stmt->execute()) {
        header("Location: admin.php");
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
        <div>
            <label>Student ID:</label>
            <input type="text" name="student_id" value="<?= htmlspecialchars($student['student_id']) ?>" required>
        </div>
        <div>
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($student['first_name']) ?>" required>
        </div>
        <div>
            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>" required>
        </div>
        <div>
            <label>Address:</label>
            <input type="text" name="address" value="<?= htmlspecialchars($student['address']) ?>" required>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
        </div>
        <div>
            <label>Phone Number:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($student['phone']) ?>" required>
        </div>
        <div>
            <label>Enrollment Year:</label>
            <input type="number" name="enrollment_year" value="<?= htmlspecialchars($student['enrollment_year']) ?>" required>
        </div>
        <div>
            <label>Major:</label>
            <input type="text" name="major" value="<?= htmlspecialchars($student['major']) ?>" required>
        </div>
        <div>
            <input type="submit" value="Update Student">
            <a href="admin.php">Cancel</a>
        </div>
    </form>
</body>
</html>
<?php
$conn->close();
?>