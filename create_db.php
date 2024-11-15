<?php
include "student_db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fname = $_POST['Fname'] ?? '';
    $lname = $_POST['Lname'] ?? '';
    $dateofbirth = $_POST['DateOfBirth'] ?? '';  
    $email = $_POST['Email'] ?? '';
    $address = $_POST['Adress'] ?? '';

    // Use the exact column names from your database
    $sql = "INSERT INTO student (FName, LName, DateOfBirth, Email, Adress) 
            VALUES (?, ?, ?, ?, ?)";
            
    try {
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("sssss", $fname, $lname, $dateofbirth, $email, $address);
        
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
            <label>First Name:</label>
            <input type="text" name="Fname" required>
        </p>
        <p>
            <label>Last Name:</label>
            <input type="text" name="Lname" required>
        </p>
        <p>
            <label>Date of Birth:</label>
            <input type="date" name="DateOfBirth">  
        </p>
        <p>
            <label>Email:</label>
            <input type="email" name="Email">
        </p>
        <p>
            <label>Address:</label>
            <input type="text" name="Adress">
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