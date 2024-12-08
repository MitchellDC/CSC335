<?php
include 'student_db.php';  

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("Invalid ID");
}

$sql = "DELETE FROM instructors WHERE instructor_id= ?";  
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

try {
    if ($stmt->execute()) {
        header("Location: admin.php");
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
