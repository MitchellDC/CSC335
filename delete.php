<?php
include 'student_db.php';  

// Validate and sanitize the ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("Invalid ID");
}

// Use prepared statement to prevent SQL injection
$sql = "DELETE FROM Students WHERE student_id= ?";  
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

try {
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        throw new Exception("Error deleting record: " . $stmt->error);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $stmt->close();
    $conn->close();
}
?>

