<?php
include "student_db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $credits = $_POST['credits'] ?? 0;
    $schedule = $_POST['schedule'] ?? '';
    $instructor_id = $_POST['instructor_id'] ?? null;
    $max_enrollment = $_POST['max_enrollment'] ?? 0;

    $sql = "INSERT INTO Courses (title, description, credits, schedule, instructor_id, max_enrollment) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisii", $title, $description, $credits, $schedule, $instructor_id, $max_enrollment);
        
        if ($stmt->execute()) {
            header("Location: course_list.php");
            exit();
        } else {
            throw new Exception("Course creation failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Create New Course</h2>
    <form method="post">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Credits</label>
            <input type="number" name="credits" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Schedule</label>
            <input type="text" name="schedule" class="form-control">
        </div>
        <div class="mb-3">
            <label>Instructor ID</label>
            <input type="number" name="instructor_id" class="form-control">
        </div>
        <div class="mb-3">
            <label>Max Enrollment</label>
            <input type="number" name="max_enrollment" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Create Course</button>
    </form>
</div>
</body>
</html>