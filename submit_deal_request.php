<?php
/**
 * php/submit_deal_request.php
 * Endpoint for processing "Request Deal" form submissions from past-projects.php.
 * Inserts request details into the `deal_requests` database table.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
    exit;
}

require_once __DIR__ . '/db_connect.php';

if (!isset($pdo) || $pdo === null) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed. Test mode fallback active.'
    ]);
    exit;
}

// Extract and sanitize inputs
$project_name = isset($_POST['project_name']) ? trim($_POST['project_name']) : '';
$client_name  = isset($_POST['client_name']) ? trim($_POST['client_name']) : '';
$client_email = isset($_POST['client_email']) ? trim($_POST['client_email']) : '';
$client_phone = isset($_POST['client_phone']) ? trim($_POST['client_phone']) : '';
$company      = isset($_POST['company']) ? trim($_POST['company']) : '';
$message      = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validation
if (empty($project_name)) {
    echo json_encode(['success' => false, 'message' => 'Project name is missing.']);
    exit;
}
if (empty($client_name)) {
    echo json_encode(['success' => false, 'message' => 'Your name is required.']);
    exit;
}
if (empty($client_email)) {
    echo json_encode(['success' => false, 'message' => 'Your email address is required.']);
    exit;
}
if (!filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

try {
    // Insert into deal_requests table using a prepared statement to prevent SQL injection
    $sql = "INSERT INTO `deal_requests` (`project_name`, `client_name`, `client_email`, `client_phone`, `company`, `message`) 
            VALUES (:project_name, :client_name, :client_email, :client_phone, :company, :message)";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':project_name' => $project_name,
        ':client_name'  => $client_name,
        ':client_email' => $client_email,
        ':client_phone' => !empty($client_phone) ? $client_phone : null,
        ':company'      => !empty($company) ? $company : null,
        ':message'      => !empty($message) ? $message : null
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Your deal request has been submitted successfully! Our business development team will contact you shortly.'
    ]);
    exit;

} catch (\PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to save deal request: ' . $e->getMessage()
    ]);
    exit;
}
?>
