<?php
/**
 * admin/logout.php
 * Handles logging out of the administrative session.
 * Clears and destroys active session tokens, then redirects to the login screen.
 */
session_start();

// 1. Unset all session variables
$_SESSION = array();

// 2. If session cookie exists, destroy it by setting back expiration
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 3. Destroy the session on server
session_destroy();

// 4. Redirect user to the login screen
header('Location: login.php');
exit;
?>
