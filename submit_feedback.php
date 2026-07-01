<?php
/**
 * php/submit_feedback.php
 * Endpoint for processing client feedback form submissions from feedback.php.
 * Inserts feedback into the `testimonials` database table.
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
$stars       = isset($_POST['stars']) ? intval($_POST['stars']) : 5;
$author_name = isset($_POST['author_name']) ? trim($_POST['author_name']) : '';
$author_role = isset($_POST['author_role']) ? trim($_POST['author_role']) : '';
$text        = isset($_POST['text']) ? trim($_POST['text']) : '';

// Validation
if (empty($author_name)) {
    echo json_encode(['success' => false, 'message' => 'Your name is required.']);
    exit;
}
if (empty($author_role)) {
    echo json_encode(['success' => false, 'message' => 'Your role or company is required.']);
    exit;
}
if (empty($text)) {
    echo json_encode(['success' => false, 'message' => 'Feedback review text is required.']);
    exit;
}
if ($stars < 1 || $stars > 5) {
    $stars = 5;
}

// Auto-generate initials
$words = preg_split('/\s+/', $author_name);
$initials = "";
foreach ($words as $w) {
    if (!empty($w)) {
        // Grab first char
        $initials .= mb_substr($w, 0, 1, 'UTF-8');
    }
}
$author_initials = mb_strtoupper(mb_substr($initials, 0, 2, 'UTF-8'), 'UTF-8');
if (empty($author_initials)) {
    $author_initials = 'CL'; // Default fallback Client
}

try {
    // Insert into testimonials table using prepared statements
    $sql = "INSERT INTO `testimonials` (`stars`, `text`, `author_name`, `author_initials`, `author_role`) 
            VALUES (:stars, :text, :author_name, :author_initials, :author_role)";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':stars'           => $stars,
        ':text'            => $text,
        ':author_name'     => $author_name,
        ':author_initials' => $author_initials,
        ':author_role'     => $author_role
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your feedback has been submitted successfully.'
    ]);
    exit;

} catch (\PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to save feedback: ' . $e->getMessage()
    ]);
    exit;
}
?>
