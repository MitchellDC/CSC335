<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session only if it hasn't started yet
}

require 'student_db.php'; // Include your DB connection

// Ensure student is logged in
if (!isset($_SESSION['student'])) {
    header('Location: student_login.php');
    exit;
}

$student_email = $_SESSION['student'];

// Get student ID from the email
$student_query = $conn->prepare("SELECT id FROM students_login WHERE email = ?");
$student_query->bind_param("s", $student_email);
$student_query->execute();
$student_result = $student_query->get_result();
$student_data = $student_result->fetch_assoc();
$student_id = $student_data['id'];

// Check if courses have been selected
if (!isset($_SESSION['selected_courses']) || empty($_SESSION['selected_courses'])) {
    header('Location: student.php');
    exit;
}

// Calculate total amount for the selected courses (this assumes a fixed price per credit, you can adjust it)
$total_amount = 0;
$selected_courses = $_SESSION['selected_courses'];

// Fetch course details for the selected courses
$placeholders = implode(',', array_fill(0, count($selected_courses), '?'));
$query = $conn->prepare("
    SELECT credits FROM Courses WHERE course_id IN ($placeholders)
");
$query->bind_param(str_repeat('i', count($selected_courses)), ...$selected_courses);
$query->execute();
$courses_result = $query->get_result();

// Calculate total amount (for simplicity, assuming 100 per credit)
while ($row = $courses_result->fetch_assoc()) {
    $total_amount += $row['credits'] * 100; // Assuming 100 per credit
}

// Process payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['make_payment'])) {
    $payment_status = 'Pending'; // or 'Paid', depending on your application logic
    $payment_date = date('Y-m-d'); // Current date
    
    // Option 2: Check if the student already made a payment today
    $check_payment_query = $conn->prepare("SELECT * FROM payments WHERE student_id = ? AND payment_date = ?");
    $check_payment_query->bind_param("is", $student_id, $payment_date);
    $check_payment_query->execute();
    $check_result = $check_payment_query->get_result();

    if ($check_result->num_rows > 0) {
        echo "You have already made a payment today.";
    } else {
        // Option 1: Insert payment record into the payments table
        $payment_query = $conn->prepare("INSERT INTO payments (student_id, payment_status, amount, payment_date) VALUES (?, ?, ?, ?)");
        $payment_query->bind_param("isds", $student_id, $payment_status, $total_amount, $payment_date);
        
        if ($payment_query->execute()) {
            // Payment inserted successfully, show success message
            unset($_SESSION['selected_courses']); // Clear selected courses after payment
            $_SESSION['payment_success'] = true; // Set a session variable for the success message
            header('Location: payment_success.php'); // Redirect to success page
            exit;
        } else {
            echo "Error processing payment.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
        <h1 class="text-center">Payment Page</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Amount: $<?= number_format($total_amount, 2); ?></h5>
                <form method="POST">
                    <button type="submit" name="make_payment" class="btn btn-success w-100">Make Payment</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

