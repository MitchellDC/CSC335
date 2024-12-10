<?php
include 'student_db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid course ID.");
}

$id = intval($_GET['id']);

// Fetch the course data
$stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
if (!$stmt) {
    die("Error preparing SELECT statement: " . $conn->error);
}

$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
    die("Error executing SELECT statement: " . $stmt->error);
}

$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    die("Course not found. Please check the course ID.");
}

// Fetch the course schedules
$schedules_stmt = $conn->prepare("SELECT * FROM CourseSchedules WHERE course_id = ?");
if (!$schedules_stmt) {
    die("Error preparing SELECT statement for schedules: " . $conn->error);
}

$schedules_stmt->bind_param("i", $id);
if (!$schedules_stmt->execute()) {
    die("Error executing SELECT statement for schedules: " . $schedules_stmt->error);
}

$schedules_result = $schedules_stmt->get_result();
$schedules = $schedules_result->fetch_all(MYSQLI_ASSOC);

// Handle course form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_course'])) {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $credits = $_POST['credits'] ?? 0;
    $schedule = $_POST['schedule'] ?? '';
    $instructor_id = $_POST['instructor_id'] ?? null;
    $max_enrollment = $_POST['max_enrollment'] ?? 0;

    // Update course details
    $sql = "UPDATE courses SET 
        title = ?, 
        description = ?, 
        credits = ?, 
        schedule = ?, 
        instructor_id = ?, 
        max_enrollment = ? 
        WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing UPDATE statement: " . $conn->error);
    }

    $stmt->bind_param("ssissii", $title, $description, $credits, $schedule, $instructor_id, $max_enrollment, $id);

    if ($stmt->execute()) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Handle schedule update or addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_schedule'])) {
    $course_id = $_POST['course_id'] ?? null;
    $day = $_POST['day'] ?? '';
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $room_number = $_POST['room_number'] ?? '';

    if ($course_id) {
        // Update existing schedule
        $sql = "UPDATE CourseSchedules SET day = ?, start_time = ?, end_time = ?, room_number = ? WHERE course_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $day, $start_time, $end_time, $room_number, $course_id);
    } else {
        // Add a new schedule
        $sql = "INSERT INTO CourseSchedules (course_id, day, start_time, end_time, room_number) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $id, $day, $start_time, $end_time, $room_number);
    }

    if ($stmt->execute()) {
        header("Location: course_edits.php?id=$id");
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
<title>Edit Course</title>
</head>
<body>
<div class="container mt-5">
    <h1>Edit Course</h1>
    <form method="post">
        <input type="hidden" name="update_course" value="1">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($course['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($course['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Credits</label>
            <input type="number" name="credits" class="form-control" value="<?= htmlspecialchars($course['credits']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Schedule</label>
            <input type="text" name="schedule" class="form-control" value="<?= htmlspecialchars($course['schedule']) ?>">
        </div>
        <div class="mb-3">
            <label>Instructor ID</label>
            <input type="number" name="instructor_id" class="form-control" value="<?= htmlspecialchars($course['instructor_id']) ?>">
        </div>
        <div class="mb-3">
            <label>Max Enrollment</label>
            <input type="number" name="max_enrollment" class="form-control" value="<?= htmlspecialchars($course['max_enrollment']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Course</button>
    </form>

    <h2 class="mt-5">Edit Schedules</h2>
    <?php foreach ($schedules as $schedule): ?>
    <form method="post" class="mb-4">
        <input type="hidden" name="update_schedule" value="1">
        <input type="hidden" name="course_id" value="<?= $schedule['course_id'] ?>">
        <div class="row g-3">
            <div class="col-md-2">
                <label>Day</label>
                <select id="day" name="day" class="form-select" required>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>Start Time</label>
                <input type="time" name="start_time" class="form-control" value="<?= htmlspecialchars($schedule['start_time']) ?>" required>
            </div>
            <div class="col-md-2">
                <label>End Time</label>
                <input type="time" name="end_time" class="form-control" value="<?= htmlspecialchars($schedule['end_time']) ?>" required>
            </div>
            <div class="col-md-3">
                <label>Room Number</label>
                <input type="text" name="room_number" class="form-control" value="<?= htmlspecialchars($schedule['room_number']) ?>" required>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary">Update Schedule</button>
            </div>
        </div>
    </form>
    <?php endforeach; ?>

</div>
</body>
</html>
<?php
$conn->close();
?>
