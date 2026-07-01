<?php
/**
 * submit_inquiry.php
 * Handles form submissions from the Contact page via AJAX.
 * Sanitizes input, performs server-side validations, and saves to database.
 */

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method. Only POST is allowed.'
    ]);
    exit;
}

// Include database connection
require_once __DIR__ . '/db_connect.php';

// Retrieve and sanitize inputs
$name        = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8') : '';
$email       = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8') : '';
$phone       = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone']), ENT_QUOTES, 'UTF-8') : '';
$company     = isset($_POST['company']) ? htmlspecialchars(trim($_POST['company']), ENT_QUOTES, 'UTF-8') : '';
$country     = isset($_POST['country']) ? htmlspecialchars(trim($_POST['country']), ENT_QUOTES, 'UTF-8') : '';
$job_title   = isset($_POST['job_title']) ? htmlspecialchars(trim($_POST['job_title']), ENT_QUOTES, 'UTF-8') : '';
$job_details = isset($_POST['job_details']) ? htmlspecialchars(trim($_POST['job_details']), ENT_QUOTES, 'UTF-8') : '';

// --- Server-side Validation ---

// 1. Required fields check
if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Full Name is required.']);
    exit;
}

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Email Address is required.']);
    exit;
}

// 2. Email format validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address format.']);
    exit;
}

// 3. Phone format validation (optional field, but if provided, must be valid)
if (!empty($phone)) {
    // Regex allows digits, spaces, hyphens, plus sign, and parenthesis. Min 7, max 20 chars.
    if (!preg_match('/^[0-9\-\+\s\(\)]{7,20}$/', $phone)) {
        echo json_encode(['success' => false, 'message' => 'Invalid phone number format. Use numbers, spaces, or +-().']);
        exit;
    }
}

// --- Insert into Database ---
try {
    $sql = "INSERT INTO inquiries (name, email, phone, company, country, job_title, job_details) 
            VALUES (:name, :email, :phone, :company, :country, :job_title, :job_details)";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        ':name'        => $name,
        ':email'       => $email,
        ':phone'       => $phone ? $phone : null,
        ':company'     => $company ? $company : null,
        ':country'     => $country ? $country : null,
        ':job_title'   => $job_title ? $job_title : null,
        ':job_details' => $job_details ? $job_details : null
    ]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Thank you! We will be in touch soon.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to save your inquiry. Please try again later.'
        ]);
    }
} catch (\PDOException $e) {
    // Log the actual exception locally, return a safe message
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
