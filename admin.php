<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

include 'student_db.php';
$student_result = $conn->query("SELECT * FROM Students");
$instructor_result = $conn->query("SELECT * FROM Instructors");
$courses_results =$conn->query("    SELECT 
        courses.course_id, 
        courses.title, 
        courses.description, 
        courses.credits, 
        courses.instructor_id, 
        courses.max_enrollment, 
        courseschedules.day, 
        courseschedules.start_time, 
        courseschedules.end_time, 
        courseschedules.room_number
    FROM courses
    LEFT JOIN courseschedules 
    ON courses.course_id = courseschedules.course_id");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
        <h1 class="text-center">Admin Page</h1>
        <div class="mb-3">
            <a href="create_db.php" class="btn btn-primary">Add New Student</a>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Students</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Enrollment Year</th>
                                    <th>Major</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php while($row = $student_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['student_id']; ?></td>
                                <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                <td><?php echo $row['address']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['phone']; ?></td>
                                <td><?php echo $row['enrollment_year']; ?></td>
                                <td><?php echo $row['major']; ?></td>
                                <td>
                                    <a href="edit.php?id=<?php echo $row['student_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete.php?id=<?php echo $row['student_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="mb-3">
            <a href="instructor.php" class="btn btn-primary">Add New Instructor</a>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-success text-white">Instructors</div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>Instructor ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Contact Info</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        <tbody>
                            <?php while($row = $instructor_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['instructor_id']; ?></td>
                                <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                <td><?php echo $row['department']; ?></td>
                                <td><?php echo $row['contact_info']; ?></td>
                                <td>
                                    <a href="instructor_edit.php?id=<?php echo $row['instructor_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="instructor_delete.php?id=<?php echo $row['instructor_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <a href="courses.php" class="btn btn-primary">Add New Course</a>
        </div>
        <div class="card">
            <div class="card-header bg-info text-white">Courses</div>
                <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered w-100">
                        <thead>
                            <tr>
                                <th>Course ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Credits</th>
                                <th>Instructor ID</th>
                                <th>Max Enrollment</th>
                                <th>Day</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Room Number</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $courses_results->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['course_id']; ?></td>
                                <td><?php echo $row['title']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['credits']; ?></td>
                                <td><?php echo $row['instructor_id']; ?></td>
                                <td><?php echo $row['max_enrollment']; ?></td>
                                <td><?php echo $row['day']; ?></td>
                                <td><?php echo $row['start_time']; ?></td>
                                <td><?php echo $row['end_time']; ?></td>
                                <td><?php echo $row['room_number']; ?></td>
                                <td>
                                    <a href="courses_edits.php?id=<?php echo $row['course_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="courses_delete.php?id=<?php echo $row['course_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>