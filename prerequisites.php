<?php
include "student_db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'] ?? null;
    $prerequisite_course_id = $_POST['prerequisite_course_id'] ?? null;

    $sql = "INSERT INTO Prerequisites (course_id, prerequisite_course_id) 
            VALUES (?, ?)";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $course_id, $prerequisite_course_id);
        
        if ($stmt->execute()) {
            header("Location: prerequisites_list.php");
            exit();
        } else {
            throw new Exception("Prerequisite entry failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Prerequisites</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Add Course Prerequisites</h2>
    <form method="post">
        <div class="mb-3">
            <label>Course ID</label>
            <input type="number" name="course_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Prerequisite Course ID</label>
            <input type="number" name="prerequisite_course_id" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Prerequisite</button>
    </form>
</div>
</body>
</html>