<?php
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'student_enrollment');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['register'])) {
    $email = $_POST['register_email'];
    $password = $_POST['register_password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM login WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $emailCount);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($emailCount > 0) {
        $error = "This email is already registered.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO login (email, password_hash) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $email, $hashedPassword);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card p-5 w-50 shadow">
            <div class="card-body">
                <h1 class="card-title text-center">Register Your Account</h1>
                <form method="POST" action="register.php" class="mt-4">
                    <div class="mb-3">
                        <label for="register_email" class="form-label">Email:</label>
                        <input type="email" id="register_email" name="register_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="register_password" class="form-label">Password:</label>
                        <input type="password" id="register_password" name="register_password" class="form-control" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                </form>
                <div class="text-center mt-3">
                    <p>Have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
