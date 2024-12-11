<?php
include "student_db.php"; // Include database connection

// Handle Create and Update operations
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $schedule_id = $_POST['schedule_id'] ?? null;
    $course_id = $_POST['course_id'] ?? null;
    $day = $_POST['day'] ?? '';
    $time = $_POST['time'] ?? '';
    $room_number = $_POST['room_number'] ?? '';

    if ($schedule_id) {
        // Update operation
        $sql = "UPDATE CourseSchedules 
                SET course_id = ?, day = ?, time = ?, room_number = ? 
                WHERE schedule_id = ?";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssi", $course_id, $day, $time, $room_number, $schedule_id);

            if ($stmt->execute()) {
                $message = "Schedule updated successfully!";
            } else {
                throw new Exception("Update failed: " . $stmt->error);
            }
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        // Create operation
        $sql = "INSERT INTO CourseSchedules (course_id, day, time, room_number) 
                VALUES (?, ?, ?, ?)";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $course_id, $day, $time, $room_number);

            if ($stmt->execute()) {
                $message = "Schedule added successfully!";
            } else {
                throw new Exception("Insert failed: " . $stmt->error);
            }
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Handle Delete operation
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM CourseSchedules WHERE schedule_id = ?";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $delete_id);

        if ($stmt->execute()) {
            $message = "Schedule deleted successfully!";
        } else {
            throw new Exception("Delete failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch all schedules for the student
$sql = "SELECT * FROM CourseSchedules";
$schedules = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Schedule Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Manage Your Schedule</h2>

    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Form for Adding/Editing a Schedule -->
    <form method="post" class="mb-4">
        <input type="hidden" name="schedule_id" id="schedule_id">
        <div class="mb-3">
            <label>Course ID</label>
            <input type="number" name="course_id" id="course_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Day</label>
            <select name="day" id="day" class="form-control" required>
                <option value="">Select Day</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Time</label>
            <input type="time" name="time" id="time" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Room Number</label>
            <input type="text" name="room_number" id="room_number" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Schedule</button>
        <button type="reset" class="btn btn-secondary" onclick="resetForm()">Reset</button>
    </form>

    <!-- Display Student's Schedules -->
    <h3>Your Schedules</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Schedule ID</th>
            <th>Course ID</th>
            <th>Day</th>
            <th>Time</th>
            <th>Room Number</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $schedules->fetch_assoc()): ?>
            <tr>
                <td><?= $row['schedule_id'] ?></td>
                <td><?= $row['course_id'] ?></td>
                <td><?= $row['day'] ?></td>
                <td><?= $row['time'] ?></td>
                <td><?= $row['room_number'] ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editSchedule(<?= htmlspecialchars(json_encode($row)) ?>)">Edit</button>
                    <a href="?delete_id=<?= $row['schedule_id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    function editSchedule(schedule) {
        document.getElementById('schedule_id').value = schedule.schedule_id;
        document.getElementById('course_id').value = schedule.course_id;
        document.getElementById('day').value = schedule.day;
        document.getElementById('time').value = schedule.time;
        document.getElementById('room_number').value = schedule.room_number;
    }

    function resetForm() {
        document.getElementById('schedule_id').value = '';
    }
</script>
</body>
</html>
