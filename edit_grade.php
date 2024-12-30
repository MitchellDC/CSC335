<?php
include 'student_db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT * FROM Grades WHERE enrollment_id = ?");
if (!$stmt) {
    die("Error preparing SELECT statement: " . $conn->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Grade not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enrollment_id = $_POST['enrollment_id'] ?? '';
    $course_id = $_POST['course_id'] ?? '';
    $student_id = $_POST['student_id'] ?? '';
    $grade = $_POST['grade'] ?? '';

    $sql = "UPDATE Grades SET 
            enrollment_id = ?, 
            course_id = ?, 
            student_id = ?,
            grade = ?
            WHERE enrollment_id = ?";
            
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing UPDATE statement: " . $conn->error);
    }

    $stmt->bind_param("iiisi", $enrollment_id, $course_id, $student_id, $grade, $id);
    
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
    <title>Edit Grade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Edit Grade</h1>
        <form method="post" class="row g-3">
            <div class="col-md-6">
                <label for="enrollment_id" class="form-label">Enrollment ID</label>
                <input type="text" class="form-control" id="enrollment_id" name="enrollment_id" value="<?= htmlspecialchars($student['enrollment_id']) ?>" required>
            </div>
            <div class="col-md-6">
                <label for="course_id" class="form-label">Course ID</label>
                <input type="text" class="form-control" id="course_id" name="course_id" value="<?= htmlspecialchars($student['course_id']) ?>" required>
            </div>
            <div class="col-md-6">
                <label for="student_id" class="form-label">Student ID</label>
                <input type="text" class="form-control" id="student_id" name="student_id" value="<?= htmlspecialchars($student['student_id']) ?>" required>
            </div>
            <div class="col-md-6">
                <label for="grade" class="form-label">Grade</label>
                <select name="grade" class="form-control">
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="F">F</option>
                </select>            
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Update Grade</button>
                <a href="admin.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>
