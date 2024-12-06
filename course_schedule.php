<?php
include "student_db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'] ?? null;
    $day = $_POST['day'] ?? '';
    $time = $_POST['time'] ?? '';
    $room_number = $_POST['room_number'] ?? '';

    $sql = "INSERT INTO CourseSchedules (course_id, day, time, room_number) 
            VALUES (?, ?, ?, ?)";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $course_id, $day, $time, $room_number);
        
        if ($stmt->execute()) {
            header("Location: course_schedules.php");
            exit();
        } else {
            throw new Exception("Schedule entry failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Course Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Add Course Schedule</h2>
    <form method="post">
        <div class="mb-3">
            <label>Course ID</label>
            <input type="number" name="course_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Day</label>
            <select name="day" class="form-control">
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Time</label>
            <input type="time" name="time" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Room Number</label>
            <input type="text" name="room_number" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Schedule</button>
    </form>
</div>
</body>
</html>