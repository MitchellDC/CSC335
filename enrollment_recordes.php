<?php
include "student_db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'] ?? null;
    $course_id = $_POST['course_id'] ?? null;
    $semester = $_POST['semester'] ?? '';
    $status = $_POST['status'] ?? 'Enrolled';

    $sql = "INSERT INTO EnrollmentRecords (student_id, course_id, semester, status) 
            VALUES (?, ?, ?, ?)";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $student_id, $course_id, $semester, $status);
        
        if ($stmt->execute()) {
            header("Location: enrollment_list.php");
            exit();
        } else {
            throw new Exception("Enrollment failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enroll Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Student Enrollment</h2>
    <form method="post">
        <div class="mb-3">
            <label>Student ID</label>
            <input type="number" name="student_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Course ID</label>
            <input type="number" name="course_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Semester</label>
            <input type="text" name="semester" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Enrolled">Enrolled</option>
                <option value="Dropped">Dropped</option>
                <option value="Waitlist">Waitlist</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Enroll</button>
    </form>
</div>
</body>
</html>