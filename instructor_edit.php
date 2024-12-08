<?php
include 'student_db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT * FROM Instructors WHERE instructor_id = ?");
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
    $instructor_id = $_POST['instructor_id'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $department = $_POST['department'] ?? '';
    $contact_info = $_POST['contact_info'] ?? '';


    $sql = "UPDATE instructors SET 
    instructor_id = ?, 
    first_name = ?, 
    last_name = ?,
    department = ?, 
    contact_info = ?
    WHERE instructor_id = ?";

            
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing UPDATE statement: " . $conn->error);
    }

    $stmt->bind_param("issssi", $instructor_id, $first_name, $last_name, $department, $contact_info, $id);
    
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
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Edit Instructor</title>
</head>
<body>
<div class="container mt-5">
        <h1>Edit Instructor</h1>
        <form method="post">
            <div class="mb-3">
                <label for="instructor_id" class="form-label">Instructor ID:</label>
                <input type="text" name="instructor_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name:</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name:</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department:</label>
                <input type="text" name="department" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contact_info" class="form-label">Email:</label>
                <input type="email" name="contact_info" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="admin.php" class="btn btn-primary">Cancel</a>
        </form>
    </div>
</body>
</html>
<?php
$conn->close();
?>