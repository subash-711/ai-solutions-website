<?php
/**
 * admin_auth.php
 * Handles the administrative authentication POST request.
 * Compares entered password with bcrypt hash in database.
 */

session_start();

// Ensure it is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin/login.php');
    exit;
}

require_once __DIR__ . '/db_connect.php';

// Get and trim inputs
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Validation
if (empty($username) || empty($password)) {
    $_SESSION['login_error'] = 'Username and password are required.';
    header('Location: ../admin/login.php');
    exit;
}

try {
    // Select admin user using prepared statement to prevent SQL Injection
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :username LIMIT 1");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Successful login
        // Regenerate session ID for security to prevent session fixation
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username']  = $user['username'];
        
        // Redirect to admin dashboard
        header('Location: ../admin/dashboard.php');
        exit;
    } else {
        // Authentication failed
        $_SESSION['login_error'] = 'Invalid credentials.';
        header('Location: ../admin/login.php');
        exit;
    }
} catch (\PDOException $e) {
    // Database connection or query error
    $_SESSION['login_error'] = 'Authentication service error: ' . $e->getMessage();
    header('Location: ../admin/login.php');
    exit;
}
?>
