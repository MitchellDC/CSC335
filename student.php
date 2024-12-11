<?php
include 'student_db.php';

// Default semester filter

$semester = isset($_GET['semester']) ? $_GET['semester'] : 'Fall 2025';

// Fetch available semesters dynamically
$semesters_query = "SELECT DISTINCT semester FROM EnrollmentRecords";
$semesters_result = $conn->query($semesters_query);

// Fetch courses for the selected semester
$courses_query = $conn->prepare("
    SELECT 
        c.course_id, 
        c.title, 
        c.description, 
        c.credits, 
        CONCAT(i.first_name, ' ', i.last_name) AS instructor_name,
        cs.day, 
        cs.start_time, 
        cs.end_time, 
        cs.room_number 
    FROM Courses c
    JOIN EnrollmentRecords er ON c.course_id = er.course_id
    LEFT JOIN Instructors i ON c.instructor_id = i.instructor_id
    LEFT JOIN CourseSchedules cs ON c.course_id = cs.course_id
    WHERE er.semester = ?
");
$courses_query->bind_param("s", $semester);
$courses_query->execute();
$courses_result = $courses_query->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
        <h1 class="text-center">Available Courses</h1>

        <!-- Semester Filter -->
        <form method="GET" class="mb-3">
            <label for="semester" class="form-label">Select Semester</label>
            <select id="semester" name="semester" class="form-select" onchange="this.form.submit()">
                <?php while ($row = $semesters_result->fetch_assoc()): ?>
                <option value="<?= $row['semester']; ?>" <?= $semester == $row['semester'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($row['semester']); ?>
                </option>
                <?php endwhile; ?>
            </select>
        </form>

        <!-- Courses Table -->
        <div class="card">
            <div class="card-header bg-info text-white">Courses</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Course ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Credits</th>
                            <th>Instructor</th>
                            <th>Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Room</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $courses_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['course_id']); ?></td>
                            <td><?= htmlspecialchars($row['title']); ?></td>
                            <td><?= htmlspecialchars($row['description']); ?></td>
                            <td><?= htmlspecialchars($row['credits']); ?></td>
                            <td><?= htmlspecialchars($row['instructor_name']); ?></td>
                            <td><?= htmlspecialchars($row['day']); ?></td>
                            <td><?= htmlspecialchars($row['start_time']); ?></td>
                            <td><?= htmlspecialchars($row['end_time']); ?></td>
                            <td><?= htmlspecialchars($row['room_number']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

