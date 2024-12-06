<?php
include "student_db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enrollment_id = $_POST['enrollment_id'] ?? null;
    $course_id = $_POST['course_id'] ?? null;
    $student_id = $_POST['student_id'] ?? null;
    $grade = $_POST['grade'] ?? '';

    $sql = "INSERT INTO Grades (enrollment_id, course_id, student_id, grade) 
            VALUES (?, ?, ?, ?)";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $enrollment_id, $course_id, $student_id, $grade);
        
        if ($stmt->execute()) {
            // Also update Transcript
            $transcript_sql = "INSERT INTO Transcripts (student_id, course_id, semester, grade) 
                               SELECT student_id, course_id, semester, ? 
                               FROM EnrollmentRecords 
                               WHERE enrollment_id = ?";
            $transcript_stmt = $conn->prepare($transcript_sql);
            $transcript_stmt->bind_param("si", $grade, $enrollment_id);
            $transcript_stmt->execute();

            header("Location: grade_list.php");
            exit();
        } else {
            throw new Exception("Grade entry failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enter Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Enter Student Grade</h2>
    <form method="post">
        <div class="mb-3">
            <label>Enrollment ID</label>
            <input type="number" name="enrollment_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Course ID</label>
            <input type="number" name="course_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Student ID</label>
            <input type="number" name="student_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Grade</label>
            <select name="grade" class="form-control">
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="F">F</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit Grade</button>
    </form>
</div>
</body>
</html>