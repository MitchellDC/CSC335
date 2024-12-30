<?php
session_start();

// Check if the payment was successful
if (!isset($_SESSION['payment_success']) || !$_SESSION['payment_success']) {
    header('Location: student.php'); // Redirect to student page if payment was not successful
    exit;
}

// Clear the session variable after displaying the message
unset($_SESSION['payment_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
        <h1 class="text-center text-success">Thank You for Your Payment!</h1>
        <p class="text-center">Your payment has been successfully processed. You can now proceed with your course registration or view your courses.</p>
        <div class="text-center">
            <a href="student.php" class="btn btn-primary">Go to My Courses</a>
        </div>
    </div>
</body>
</html>
