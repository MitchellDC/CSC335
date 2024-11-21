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
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $enrollment_year = (int)($_POST['enrollment_year'] ?? 0); 
    $major = $_POST['major'] ?? '';

    $sql = "UPDATE Students SET 
            name = ?, 
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

    $stmt->bind_param("ssssisi", $name, $address, $email, $phone, $enrollment_year, $major, $id);
    
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
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>
        </p>
        <p>
            <label>Address:</label>
            <input type="text" name="address" value="<?= htmlspecialchars($student['address']) ?>" required>
        </p>
        <p>
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
        </p>
        <p>
            <label>Phone Number:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($student['phone']) ?>" required>
        </p>
        <p>
            <label>Enrollment Year:</label>
            <input type="number" name="enrollment_year" value="<?= htmlspecialchars($student['enrollment_year']) ?>" required>
        </p>
        <p>
            <label>Major:</label>
            <input type="text" name="major" value="<?= htmlspecialchars($student['major']) ?>" required>
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
