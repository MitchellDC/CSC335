<?php
include "student_db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'] ?? null;
    $payment_status = $_POST['payment_status'] ?? 'Pending';
    $amount = $_POST['amount'] ?? 0.00;
    $payment_date = $_POST['payment_date'] ?? date('Y-m-d');

    $sql = "INSERT INTO Payments (student_id, payment_status, amount, payment_date) 
            VALUES (?, ?, ?, ?)";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issd", $student_id, $payment_status, $amount, $payment_date);
        
        if ($stmt->execute()) {
            header("Location: payment_list.php");
            exit();
        } else {
            throw new Exception("Payment entry failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Student Payment Entry</h2>
    <form method="post">
        <div class="mb-3">
            <label>Student ID</label>
            <input type="number" name="student_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Payment Status</label>
            <select name="payment_status" class="form-control">
                <option value="Pending">Pending</option>
                <option value="Paid">Paid</option>
                <option value="Overdue">Overdue</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Payment Date</label>
            <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Submit Payment</button>
    </form>
</div>
</body>
</html>