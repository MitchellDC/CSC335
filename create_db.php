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
<html>
<head>
    <title>Create New Student</title>
</head>
<body>
    <h1>Create New Student</h1>
    <form method="post">
        <p>
            <label>Name:</label>
            <input type="text" name="name" required>
        </p>
        <p>
            <label>Address:</label>
            <input type="text" name="address" required>
        </p>
        <p>
            <label>Email:</label>
            <input type="email" name="email" required>
        </p>
        <p>
            <label>Phone Number:</label>
            <input type="text" name="phone" required>
        </p>
        <p>
            <label>Enrollment Year:</label>
            <input type="number" name="enrollment_year" required>
        </p>
        <p>
            <label>Major:</label>
            <input type="text" name="major" required>
        </p>
        <p>
            <input type="submit" value="Save">
            <a href="index.php">Cancel</a>
        </p>
    </form>
</body>
</html>
<?php
$conn->close();
?>
