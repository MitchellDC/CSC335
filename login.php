<?php 
session_start(); 

$host = 'localhost';
$dbname = 'student_enrollment';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
}

if (isset($_POST['login'])) {
    $email = $_POST['login_email']; 
    $password = $_POST['login_password'];

    $stmt = $pdo->prepare("SELECT * FROM login WHERE email = :email"); 
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(); 

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = $user['email'];
        header('Location: admin.php');
        exit; 
        } else {
            echo 'Invalid email or password';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card p-5 w-50 shadow">
            <div class="card-body">
                <h1 class="card-title text-center">Login</h1>
                <form method="POST" action="login.php" class="mt-4">
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
                <div class="text-center mt-3">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>