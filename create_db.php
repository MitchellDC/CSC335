<?php
include "student_db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $email = $_POST['email'] ?? '';  
    $phone = $_POST['phone'] ?? '';  
    $enrollment_year = (int)($_POST['enrollment_year'] ?? 0); 
    $major = $_POST['major'] ?? '';

    $sql = "INSERT INTO students (name, address, email, phone, enrollment_year, major) 
            VALUES (?, ?, ?, ?, ?, ?)";
            
    try {
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("ssssis", $name, $address, $email, $phone, $enrollment_year, $major);
        
        if ($stmt->execute()) {
            header("Location: index.php");
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
<html lang="en">
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
        <form method="post" action="index.php">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address:</label>
                <input type="text" id="address" name="address" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" id="phone" name="phone" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="enrollment_year" class="form-label">Enrollment Year:</label>
                <input type="number" id="enrollment_year" name="enrollment_year" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="major" class="form-label">Major:</label>
                <input type="text" id="major" name="major" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-primary">Cancel</a>
        </form>
    </div>
</body>
</html>
<?php
$conn->close();
?>
