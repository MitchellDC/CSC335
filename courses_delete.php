<?php
include 'student_db.php';  

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("Invalid ID");
}

try {
    // Begin a transaction
    $conn->begin_transaction();

    // Delete related rows in the child table
    $sqlSchedules = "DELETE FROM courseschedules WHERE course_id = ?";
    $stmtSchedules = $conn->prepare($sqlSchedules);
    $stmtSchedules->bind_param("i", $id);
    $stmtSchedules->execute();

    // Delete the course in the parent table
    $sqlCourses = "DELETE FROM courses WHERE course_id = ?";
    $stmtCourses = $conn->prepare($sqlCourses);
    $stmtCourses->bind_param("i", $id);
    $stmtCourses->execute();

    // Commit the transaction
    $conn->commit();

    header("Location: admin.php");
    exit();
} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
} finally {
    $stmtSchedules->close();
    $stmtCourses->close();
    $conn->close();
}

?>
