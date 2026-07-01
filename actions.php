<?php
/**
 * admin/actions.php
 * Handles database operations (Insert, Update, Delete) for Events, Gallery, and Feedback.
 * Authorized admin session required.
 */

session_start();

// Authentication Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../php/db_connect.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$action = isset($_POST['action']) ? $_POST['action'] : '';
$tab = 'inquiries'; // Default tab redirect fallback

function set_msg($text, $type = 'success') {
    $_SESSION['admin_msg'] = $text;
    $_SESSION['admin_msg_type'] = $type;
}

function handle_file_upload($post_name, $fallback = '') {
    if (isset($_FILES[$post_name]) && $_FILES[$post_name]['error'] === UPLOAD_ERR_OK) {
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES[$post_name]['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_exts)) {
            throw new \Exception("Invalid file extension. Only JPG, JPEG, PNG, GIF, and WEBP are allowed.");
        }
        $new_name = 'upload_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $target_dir = __DIR__ . '/../images/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        if (move_uploaded_file($_FILES[$post_name]['tmp_name'], $target_dir . $new_name)) {
            return 'images/' . $new_name;
        } else {
            throw new \Exception("Failed to save uploaded file.");
        }
    }
    return $fallback;
}

try {
    switch ($action) {
        
        // ==========================================
        // UPCOMING EVENTS ACTIONS
        // ==========================================
        case 'add_event':
            $tab = 'events';
            $day = trim($_POST['day']);
            $month = trim($_POST['month']);
            $title = trim($_POST['title']);
            $location = trim($_POST['location']);
            $time = trim($_POST['time']);
            $description = trim($_POST['description']);

            if (empty($day) || empty($month) || empty($title) || empty($location) || empty($time) || empty($description)) {
                set_msg("All event fields are required.", "error");
            } else {
                $stmt = $pdo->prepare("INSERT INTO `upcoming_events` (`day`, `month`, `title`, `location`, `time`, `description`) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$day, $month, $title, $location, $time, $description]);
                set_msg("Upcoming event added successfully!");
            }
            break;

        case 'edit_event':
            $tab = 'events';
            $id = intval($_POST['id']);
            $day = trim($_POST['day']);
            $month = trim($_POST['month']);
            $title = trim($_POST['title']);
            $location = trim($_POST['location']);
            $time = trim($_POST['time']);
            $description = trim($_POST['description']);

            if (empty($id) || empty($day) || empty($month) || empty($title) || empty($location) || empty($time) || empty($description)) {
                set_msg("All event fields are required.", "error");
            } else {
                $stmt = $pdo->prepare("UPDATE `upcoming_events` SET `day` = ?, `month` = ?, `title` = ?, `location` = ?, `time` = ?, `description` = ? WHERE `id` = ?");
                $stmt->execute([$day, $month, $title, $location, $time, $description, $id]);
                set_msg("Upcoming event updated successfully!");
            }
            break;

        case 'delete_event':
            $tab = 'events';
            $id = intval($_POST['id']);
            if (empty($id)) {
                set_msg("Invalid event ID.", "error");
            } else {
                $stmt = $pdo->prepare("DELETE FROM `upcoming_events` WHERE `id` = ?");
                $stmt->execute([$id]);
                set_msg("Upcoming event deleted successfully!");
            }
            break;


        // ==========================================
        // GALLERY EVENTS ACTIONS
        // ==========================================
        case 'add_gallery':
            $tab = 'gallery';
            $title = trim($_POST['title']);
            $subtitle = trim($_POST['subtitle']);
            $description = trim($_POST['description']);

            $image_path = '';
            try {
                $image_path = handle_file_upload('image_file', trim($_POST['image_path'] ?? ''));
            } catch (\Exception $ex) {
                set_msg($ex->getMessage(), "error");
                break;
            }

            if (empty($title) || empty($subtitle) || empty($image_path) || empty($description)) {
                set_msg("All gallery fields are required.", "error");
            } else {
                $stmt = $pdo->prepare("INSERT INTO `gallery_events` (`title`, `subtitle`, `image_path`, `description`) VALUES (?, ?, ?, ?)");
                $stmt->execute([$title, $subtitle, $image_path, $description]);
                set_msg("Gallery item added successfully!");
            }
            break;

        case 'edit_gallery':
            $tab = 'gallery';
            $id = intval($_POST['id']);
            $title = trim($_POST['title']);
            $subtitle = trim($_POST['subtitle']);
            $description = trim($_POST['description']);

            $image_path = '';
            try {
                $image_path = handle_file_upload('image_file', trim($_POST['image_path'] ?? ''));
            } catch (\Exception $ex) {
                set_msg($ex->getMessage(), "error");
                break;
            }

            if (empty($id) || empty($title) || empty($subtitle) || empty($image_path) || empty($description)) {
                set_msg("All gallery fields are required.", "error");
            } else {
                $stmt = $pdo->prepare("UPDATE `gallery_events` SET `title` = ?, `subtitle` = ?, `image_path` = ?, `description` = ? WHERE `id` = ?");
                $stmt->execute([$title, $subtitle, $image_path, $description, $id]);
                set_msg("Gallery item updated successfully!");
            }
            break;

        case 'delete_gallery':
            $tab = 'gallery';
            $id = intval($_POST['id']);
            if (empty($id)) {
                set_msg("Invalid gallery ID.", "error");
            } else {
                $stmt = $pdo->prepare("DELETE FROM `gallery_events` WHERE `id` = ?");
                $stmt->execute([$id]);
                set_msg("Gallery item deleted successfully!");
            }
            break;


        // ==========================================
        // CLIENT TESTIMONIALS/FEEDBACK ACTIONS
        // ==========================================
        case 'add_feedback':
            $tab = 'feedback';
            $stars = intval($_POST['stars']);
            $text = trim($_POST['text']);
            $author_name = trim($_POST['author_name']);
            $author_role = trim($_POST['author_role']);
            
            // Auto generate initials
            $words = explode(" ", $author_name);
            $initials = "";
            foreach ($words as $w) {
                if (!empty($w)) {
                    $initials .= strtoupper($w[0]);
                }
            }
            $author_initials = substr($initials, 0, 2);

            if (empty($stars) || empty($text) || empty($author_name) || empty($author_role)) {
                set_msg("All feedback fields are required.", "error");
            } else {
                $stmt = $pdo->prepare("INSERT INTO `testimonials` (`stars`, `text`, `author_name`, `author_initials`, `author_role`) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$stars, $text, $author_name, $author_initials, $author_role]);
                set_msg("Client feedback added successfully!");
            }
            break;

        case 'edit_feedback':
            $tab = 'feedback';
            $id = intval($_POST['id']);
            $stars = intval($_POST['stars']);
            $text = trim($_POST['text']);
            $author_name = trim($_POST['author_name']);
            $author_role = trim($_POST['author_role']);
            
            // Auto generate initials
            $words = explode(" ", $author_name);
            $initials = "";
            foreach ($words as $w) {
                if (!empty($w)) {
                    $initials .= strtoupper($w[0]);
                }
            }
            $author_initials = substr($initials, 0, 2);

            if (empty($id) || empty($stars) || empty($text) || empty($author_name) || empty($author_role)) {
                set_msg("All feedback fields are required.", "error");
            } else {
                $stmt = $pdo->prepare("UPDATE `testimonials` SET `stars` = ?, `text` = ?, `author_name` = ?, `author_initials` = ?, `author_role` = ? WHERE `id` = ?");
                $stmt->execute([$stars, $text, $author_name, $author_initials, $author_role, $id]);
                set_msg("Client feedback updated successfully!");
            }
            break;

        case 'delete_feedback':
            $tab = 'feedback';
            $id = intval($_POST['id']);
            if (empty($id)) {
                set_msg("Invalid feedback ID.", "error");
            } else {
                $stmt = $pdo->prepare("DELETE FROM `testimonials` WHERE `id` = ?");
                $stmt->execute([$id]);
                set_msg("Client feedback deleted successfully!");
            }
            break;

        // ==========================================
        // SITE SETTINGS & MEDIA MANAGER ACTIONS
        // ==========================================
        case 'update_hq_image':
            $tab = 'settings';
            if (isset($_FILES['hq_image']) && $_FILES['hq_image']['error'] === UPLOAD_ERR_OK) {
                $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $filename = $_FILES['hq_image']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed_exts)) {
                    set_msg("Invalid file extension. Only JPG, JPEG, PNG, GIF, and WEBP are allowed.", "error");
                } else {
                    $target_file = __DIR__ . '/../images/hq_visual.png';
                    if (move_uploaded_file($_FILES['hq_image']['tmp_name'], $target_file)) {
                        set_msg("Landing page About image updated successfully!");
                    } else {
                        set_msg("Failed to overwrite landing page image.", "error");
                    }
                }
            } else {
                set_msg("No file uploaded or file upload error occurred.", "error");
            }
            break;

        case 'upload_general_asset':
            $tab = 'settings';
            if (isset($_FILES['asset_file']) && $_FILES['asset_file']['error'] === UPLOAD_ERR_OK) {
                $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $filename = $_FILES['asset_file']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed_exts)) {
                    set_msg("Invalid file extension. Only JPG, JPEG, PNG, GIF, and WEBP are allowed.", "error");
                } else {
                    // Sanitize file name to avoid path injection
                    $clean_name = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', pathinfo($filename, PATHINFO_FILENAME));
                    $new_name = $clean_name . '_' . time() . '.' . $ext;
                    $target_dir = __DIR__ . '/../images/';
                    if (!is_dir($target_dir)) {
                        mkdir($target_dir, 0777, true);
                    }
                    if (move_uploaded_file($_FILES['asset_file']['tmp_name'], $target_dir . $new_name)) {
                        set_msg("General asset 'images/" . htmlspecialchars($new_name) . "' uploaded successfully!");
                    } else {
                        set_msg("Failed to save general asset.", "error");
                    }
                }
            } else {
                set_msg("No file uploaded or file upload error occurred.", "error");
            }
            break;

        case 'delete_general_asset':
            $tab = 'settings';
            $filename = isset($_POST['file_name']) ? trim($_POST['file_name']) : '';
            if (empty($filename)) {
                set_msg("No file name specified.", "error");
            } else {
                $clean_filename = basename($filename);
                $file_path = __DIR__ . '/../images/' . $clean_filename;
                
                if ($clean_filename === 'hq_visual.png') {
                    set_msg("For safety, you cannot delete the default landing page HQ visual.", "error");
                } elseif (file_exists($file_path)) {
                    if (unlink($file_path)) {
                        set_msg("Asset '" . htmlspecialchars($clean_filename) . "' deleted successfully.");
                    } else {
                        set_msg("Failed to delete asset file.", "error");
                    }
                } else {
                    set_msg("File not found or already deleted.", "error");
                }
            }
            break;

        default:
            set_msg("Invalid admin action specified.", "error");
            break;
    }
} catch (\Exception $e) {
    set_msg("Action execution failed: " . $e->getMessage(), "error");
}

header("Location: dashboard.php?tab=" . urlencode($tab));
exit;
?>
