<?php
require 'student_db.php'; // Ensure this connects to the database

session_start();

// Initialize error message
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        // Handle Registration
        $email = $_POST['register_email'];
        $password = password_hash($_POST['register_password'], PASSWORD_DEFAULT); // Hash password securely

        try {
            $stmt = $conn->prepare("INSERT INTO students_login (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();

            // Automatically log in and redirect
            $_SESSION['student'] = $email;
            header("Location: student.php");
            exit;
        } catch (Exception $e) {
            $error = $conn->errno === 1062 
                ? 'An account with this email already exists.' 
                : 'Error registering account: ' . $e->getMessage();
        }
    } elseif (isset($_POST['login'])) {
        // Handle Login
        $email = $_POST['login_email'];
        $password = $_POST['login_password'];

        $stmt = $conn->prepare("SELECT password FROM students_login WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['student'] = $email;
                header("Location: student.php");
                exit;
            } else {
                $error = 'Invalid password.';
            }
        } else {
            $error = 'No account found with that email.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login/Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card p-5 w-50 shadow">
            <h1 class="text-center mb-4">Student Login/Register</h1>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST">
                <div class="mb-3">
                    <label for="login_email" class="form-label">Email:</label>
                    <input type="email" id="login_email" name="login_email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="login_password" class="form-label">Password:</label>
                    <input type="password" id="login_password" name="login_password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>

            <hr class="my-4">

            <!-- Registration Form -->
            <form method="POST">
                <div class="mb-3">
                    <label for="register_email" class="form-label">Email:</label>
                    <input type="email" id="register_email" name="register_email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="register_password" class="form-label">Password:</label>
                    <input type="password" id="register_password" name="register_password" class="form-control" required>
                </div>
                <button type="submit" name="register" class="btn btn-success w-100">Register</button>
            </form>
        </div>
    </div>
</body>
</html>
