<?php
include "student_db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $department = $_POST['department'] ?? '';
    $email = $_POST['contact_info'] ?? '';  

    $sql = "INSERT INTO instructors (name, department, contact_info) 
            VALUES (?, ?, ?)";
            
    try {
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("sss", $name, $department, $email);
        
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
    <title>Create New Instructor</title>
</head>
<body>
<div class="container mt-5">
        <h1>Create New Instructor</h1>
        <form method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department:</label>
                <input type="text" name="department" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contact_info" class="form-label">Email:</label>
                <input type="email" name="contact_info" class="form-control" required>
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