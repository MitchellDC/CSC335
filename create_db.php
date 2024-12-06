<?php
include "student_db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $address = $_POST['address'] ?? '';
    $email = $_POST['email'] ?? '';  
    $phone = $_POST['phone'] ?? '';  
    $enrollment_year = (int)($_POST['enrollment_year'] ?? 0); 
    $major = $_POST['major'] ?? '';

    $sql = "INSERT INTO students (first_name, last_name, address, email, phone, enrollment_year, major) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            
    try {
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("ssssiss", $first_name, $last_name, $address, $email, $phone, $enrollment_year, $major);
        
        if ($stmt->execute()) {
            header("Location: admin.php");
            exit();
        } else {
            throw new Exception("Execute failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Create New Student</title>
</head>
<body>
<div class="container mt-5">
        <h1>Create New Student</h1>
        <form method="post">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name:</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name:</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address:</label>
                <input type="text" name="address" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="enrollment_year" class="form-label">Enrollment Year:</label>
                <input type="number" name="enrollment_year" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="major" class="form-label">Major:</label>
                <input type="text" name="major" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="admin.php" class="btn btn-primary">Cancel</a>
        </form>
    </div>
</body>
</html>
<?php
$conn->close();
?>