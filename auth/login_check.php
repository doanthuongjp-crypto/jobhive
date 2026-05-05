<?php
session_start();

require_once '../DB/dbInfo.php';

try {
    // Check if already logged in
    if (!empty($_SESSION['user_id']) && is_numeric($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
        if ($_SESSION['role'] === 'admin') {
            header('Location: ../admin/dashboard.php');
        } else {
            header('Location: ../user/home/home.php');
        }
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        if (empty($email) || empty($password)) {
            throw new Exception('Please enter both email and password.');
        }

        // Connect to database
        $pdo = DbInfo::connectDB();
        if (!$pdo) {
            throw new Exception('Database connection failed.');
        }

        // Query user
        $stmt = $pdo->prepare("SELECT user_id, email, password_hash, role FROM Users WHERE email = :email");
        if (!$stmt) {
            throw new Exception('Failed to prepare query: ' . implode(' ', $pdo->errorInfo()));
        }

        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify credentials
        if (!$user || !password_verify($password, $user['password_hash'])) {
            throw new Exception('Invalid email or password.');
        }

        // Save session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header('Location: ../admin/dashboard.php');
        } else {
            header('Location: ../user/home/home.php');
        }
        exit;
    }
} catch (Exception $e) {
    $error_message = $e->getMessage();
    error_log("Login error: $error_message");
}
?>
