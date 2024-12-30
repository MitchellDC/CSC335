<?php
session_start();
require 'student_db.php'; // Include your DB connection

// Ensure student is logged in
if (!isset($_SESSION['student'])) {
    header('Location: student_login.php');
    exit;
}

// Get student ID from the email
$student_email = $_SESSION['student'];
$student_query = $conn->prepare("SELECT id FROM student_login WHERE email = ?");
$student_query->bind_param("s", $student_email);
$student_query->execute();
$student_result = $student_query->get_result();
$student_data = $student_result->fetch_assoc();
$student_id = $student_data['id'];

// Get selected courses from session
if (!isset($_SESSION['selected_courses']) || empty($_SESSION['selected_courses'])) {
    header('Location: review_courses.php');
    exit;
}

$selected_courses = $_SESSION['selected_courses'];

// Fetch course details for the selected courses
$placeholders = implode(',', array_fill(0, count($selected_courses), '?'));
$query = $conn->prepare("
    SELECT course_id, title, description, credits 
    FROM Courses 
    WHERE course_id IN ($placeholders)
");
$query->bind_param(str_repeat('i', count($selected_courses)), ...$selected_courses);
$query->execute();
$courses_result = $query->get_result();

// If payment form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proceed_to_payment'])) {
    // Redirect to payment page
    header("Location: payment.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Your Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
        <h1 class="text-center">Review Your Courses</h1>

        <!-- Course Details -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Courses Selected:</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Course ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Credits</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $courses_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['course_id']); ?></td>
                            <td><?= htmlspecialchars($row['title']); ?></td>
                            <td><?= htmlspecialchars($row['description']); ?></td>
                            <td><?= htmlspecialchars($row['credits']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <form method="POST">
                    <button type="submit" name="proceed_to_payment" class="btn btn-primary w-100">Proceed to Payment</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
