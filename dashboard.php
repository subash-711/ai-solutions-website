<?php
/**
 * admin/dashboard.php
 * Administrative dashboard. Supports tabbed views for inquiries, deal requests,
 * events management, photo gallery events management, and feedback management.
 */
session_start();

// Redirect to login page if user session is not authenticated
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Connect to database
require_once __DIR__ . '/../php/db_connect.php';

$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'inquiries';
$allowed_tabs = ['inquiries', 'deal_requests', 'events', 'gallery', 'feedback', 'settings'];
if (!in_array($active_tab, $allowed_tabs)) {
    $active_tab = 'inquiries';
}

// Retrieve messages from session
$alert_msg = isset($_SESSION['admin_msg']) ? $_SESSION['admin_msg'] : '';
$alert_type = isset($_SESSION['admin_msg_type']) ? $_SESSION['admin_msg_type'] : '';
unset($_SESSION['admin_msg']);
unset($_SESSION['admin_msg_type']);

try {
    // 1. Fetch Inquiries
    $inquiries_stmt = $pdo->query("SELECT * FROM `inquiries` ORDER BY submitted_at DESC");
    $inquiries = $inquiries_stmt->fetchAll();

    // 2. Fetch Deal Requests
    $deals_stmt = $pdo->query("SELECT * FROM `deal_requests` ORDER BY requested_at DESC");
    $deal_requests = $deals_stmt->fetchAll();

    // 3. Fetch Upcoming Events
    $events_stmt = $pdo->query("SELECT * FROM `upcoming_events` ORDER BY id ASC");
    $upcoming_events = $events_stmt->fetchAll();

    // 4. Fetch Gallery Events
    $gallery_stmt = $pdo->query("SELECT * FROM `gallery_events` ORDER BY id ASC");
    $gallery_events = $gallery_stmt->fetchAll();

    // 5. Fetch Client Feedback
    $feedback_stmt = $pdo->query("SELECT * FROM `testimonials` ORDER BY id ASC");
    $testimonials = $feedback_stmt->fetchAll();

    // Counts for stat cards
    $total_inquiries = count($inquiries);
    $total_deals     = count($deal_requests);
    $total_events    = count($upcoming_events);
    $total_gallery   = count($gallery_events);
    $total_feedback  = count($testimonials);

} catch (\PDOException $e) {
    $error_msg = "Database Error: " . $e->getMessage();
    $inquiries = $deal_requests = $upcoming_events = $gallery_events = $testimonials = [];
    $total_inquiries = $total_deals = $total_events = $total_gallery = $total_feedback = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - AI-Solutions</title>
  <link rel="stylesheet" href="../css/style.css">
  <script>
    (function() {
      const theme = localStorage.getItem('theme') || 'light';
      document.documentElement.setAttribute('data-theme', theme);
    })();
  </script>
  <style>
    /* CSS Grid overlay fixes for flex layout inside stats */
    .stat-card {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    /* Modal styles specifically for dashboard.php */
    .modal-backdrop {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(15, 23, 42, 0.4);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      z-index: 10000;
      display: none;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .modal-backdrop.show {
      display: flex;
      opacity: 1;
    }
    .modal-content {
      background: var(--color-bg-card);
      border: 1px solid var(--color-border);
      border-radius: var(--radius-md);
      width: 100%;
      max-width: 520px;
      padding: 32px;
      box-shadow: var(--shadow-lg), 0 0 35px rgba(79, 70, 229, 0.15);
      position: relative;
      transform: translateY(-30px);
      transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .modal-backdrop.show .modal-content {
      transform: translateY(0);
    }
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      border-bottom: 1px solid var(--color-border);
      padding-bottom: 12px;
    }
    .modal-header h3 {
      font-size: 20px;
      font-weight: 700;
      margin: 0;
      color: var(--color-text-primary);
    }
    .close-modal {
      background: none;
      border: none;
      font-size: 28px;
      color: var(--color-text-muted);
      cursor: pointer;
      line-height: 1;
      padding: 0;
      transition: var(--transition-fast);
      align-self: center;
    }
    .close-modal:hover {
      color: var(--color-text-primary);
    }
    .form-group {
      margin-bottom: 16px;
    }
    .form-group label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 6px;
      color: var(--color-text-primary);
    }
    .form-control {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid var(--color-border);
      border-radius: var(--radius-sm);
      background: var(--color-bg-main);
      color: var(--color-text-primary);
      font-family: var(--font-body);
      font-size: 14px;
      transition: var(--transition-fast);
    }
    .form-control:focus {
      border-color: var(--color-accent);
      outline: none;
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }
    .btn-submit {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
    }
  </style>
</head>
<body style="padding-top: 0; background-color: var(--color-bg-main);">

  <!-- Dashboard Header / Nav -->
  <header class="admin-header">
    <div class="container admin-nav">
      <a href="../index.php" class="logo" target="_blank"><span>AI</span>-Solutions <span>Admin</span></a>
      <div class="admin-user-info">
        <span>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['admin_username'], ENT_QUOTES, 'UTF-8'); ?></strong></span>
        <button class="theme-toggle" aria-label="Toggle theme" style="margin-left:12px; margin-right:4px;">🌙 Dark Mode</button>
        <a href="logout.php" class="btn btn-secondary" style="padding: 6px 16px; font-size: 13px;">Log Out</a>
      </div>
    </div>
  </header>

  <!-- Dashboard Main Content -->
  <main class="container">
    
    <!-- Stats Row -->
    <section class="stats-grid">
      <div class="card stat-card">
        <div class="stat-details">
          <h3>Inquiries</h3>
          <div class="stat-value"><?php echo $total_inquiries; ?></div>
        </div>
        <div class="stat-icon">📥</div>
      </div>
      <div class="card stat-card">
        <div class="stat-details">
          <h3>Deal Requests</h3>
          <div class="stat-value"><?php echo $total_deals; ?></div>
        </div>
        <div class="stat-icon">🤝</div>
      </div>
      <div class="card stat-card">
        <div class="stat-details">
          <h3>Upcoming Events</h3>
          <div class="stat-value"><?php echo $total_events; ?></div>
        </div>
        <div class="stat-icon">📅</div>
      </div>
      <div class="card stat-card">
        <div class="stat-details">
          <h3>Gallery Items</h3>
          <div class="stat-value"><?php echo $total_gallery; ?></div>
        </div>
        <div class="stat-icon">🖼️</div>
      </div>
      <div class="card stat-card">
        <div class="stat-details">
          <h3>Testimonials</h3>
          <div class="stat-value"><?php echo $total_feedback; ?></div>
        </div>
        <div class="stat-icon">💬</div>
      </div>
    </section>

    <!-- Error/Notification Alerts -->
    <?php if (isset($error_msg)): ?>
      <div class="alert alert-error" style="display: block; margin-bottom: 24px;">
        <?php echo htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($alert_msg)): ?>
      <div class="alert <?php echo $alert_type === 'error' ? 'alert-error' : 'alert-success'; ?>" style="display: block; margin-bottom: 24px; padding: 14px; border-radius: var(--radius-sm); border-left: 4px solid <?php echo $alert_type === 'error' ? 'var(--color-error)' : 'var(--color-success)'; ?>; background: <?php echo $alert_type === 'error' ? 'rgba(239,68,68,0.08)' : 'rgba(16,185,129,0.08)'; ?>; color: <?php echo $alert_type === 'error' ? 'var(--color-error)' : 'var(--color-success)'; ?>;">
        <strong><?php echo $alert_type === 'error' ? 'Error: ' : 'Success: '; ?></strong>
        <?php echo htmlspecialchars($alert_msg, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <!-- Admin Tabs Container -->
    <div class="admin-tabs">
      <button class="admin-tab-btn <?php echo $active_tab === 'inquiries' ? 'active' : ''; ?>" onclick="switchTab('inquiries')">📥 Customer Inquiries (<?php echo $total_inquiries; ?>)</button>
      <button class="admin-tab-btn <?php echo $active_tab === 'deal_requests' ? 'active' : ''; ?>" onclick="switchTab('deal_requests')">🤝 Deal Requests (<?php echo $total_deals; ?>)</button>
      <button class="admin-tab-btn <?php echo $active_tab === 'events' ? 'active' : ''; ?>" onclick="switchTab('events')">📅 Upcoming Events (<?php echo $total_events; ?>)</button>
      <button class="admin-tab-btn <?php echo $active_tab === 'gallery' ? 'active' : ''; ?>" onclick="switchTab('gallery')">🖼️ Gallery Events (<?php echo $total_gallery; ?>)</button>
      <button class="admin-tab-btn <?php echo $active_tab === 'feedback' ? 'active' : ''; ?>" onclick="switchTab('feedback')">💬 Client Feedback (<?php echo $total_feedback; ?>)</button>
      <button class="admin-tab-btn <?php echo $active_tab === 'settings' ? 'active' : ''; ?>" onclick="switchTab('settings')">⚙️ Site Settings</button>
    </div>

    <!-- ========================================== -->
    <!-- TAB 1: CUSTOMER INQUIRIES                  -->
    <!-- ========================================== -->
    <div id="tab-inquiries" class="admin-tab-content <?php echo $active_tab === 'inquiries' ? 'active' : ''; ?>">
      <div class="admin-actions-row">
        <h2 class="admin-title">Customer Inquiries</h2>
      </div>

      <?php if (count($inquiries) === 0): ?>
        <div class="card" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
          No inquiries found in database. Submissions will appear here once users fill out the contact form.
        </div>
      <?php else: ?>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th style="width: 60px;">#</th>
                <th>Name</th>
                <th>Email Address</th>
                <th>Phone</th>
                <th>Company</th>
                <th>Country</th>
                <th>Job Title</th>
                <th>Submitted At</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($inquiries as $index => $row): ?>
                <tr class="expandable-row" data-target="inq-details-<?php echo $row['id']; ?>">
                  <td>
                    <span class="arrow-toggle">▶</span>
                    <?php echo $index + 1; ?>
                  </td>
                  <td style="font-weight: 500; color: var(--color-text-primary);">
                    <?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>
                  </td>
                  <td>
                    <a href="mailto:<?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?>" style="color: var(--color-accent); text-decoration: underline;" onclick="event.stopPropagation();">
                      <?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                  </td>
                  <td>
                    <?php echo !empty($row['phone']) ? htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8') : '<span style="color:var(--color-text-muted);">-</span>'; ?>
                  </td>
                  <td>
                    <?php echo !empty($row['company']) ? htmlspecialchars($row['company'], ENT_QUOTES, 'UTF-8') : '<span style="color:var(--color-text-muted);">-</span>'; ?>
                  </td>
                  <td>
                    <?php echo !empty($row['country']) ? htmlspecialchars($row['country'], ENT_QUOTES, 'UTF-8') : '<span style="color:var(--color-text-muted);">-</span>'; ?>
                  </td>
                  <td>
                    <?php echo !empty($row['job_title']) ? htmlspecialchars($row['job_title'], ENT_QUOTES, 'UTF-8') : '<span style="color:var(--color-text-muted);">-</span>'; ?>
                  </td>
                  <td style="font-size: 13px; color: var(--color-text-muted);">
                    <?php echo htmlspecialchars($row['submitted_at'], ENT_QUOTES, 'UTF-8'); ?>
                  </td>
                </tr>
                <tr class="details-row" id="inq-details-<?php echo $row['id']; ?>">
                  <td colspan="8">
                    <div class="details-box">
                      <h5>Job / Project Requirements Details:</h5>
                      <p><?php echo !empty($row['job_details']) ? nl2br(htmlspecialchars($row['job_details'], ENT_QUOTES, 'UTF-8')) : 'No additional requirements or details provided.'; ?></p>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <!-- ========================================== -->
    <!-- TAB 2: DEAL REQUESTS                       -->
    <!-- ========================================== -->
    <div id="tab-deal_requests" class="admin-tab-content <?php echo $active_tab === 'deal_requests' ? 'active' : ''; ?>">
      <div class="admin-actions-row">
        <h2 class="admin-title">Deal Requests (Past Projects)</h2>
      </div>

      <?php if (count($deal_requests) === 0): ?>
        <div class="card" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
          No deal requests submitted yet.
        </div>
      <?php else: ?>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th style="width: 60px;">#</th>
                <th>Project Name</th>
                <th>Client Name</th>
                <th>Email Address</th>
                <th>Phone</th>
                <th>Company</th>
                <th>Requested At</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($deal_requests as $index => $row): ?>
                <tr class="expandable-row" data-target="deal-details-<?php echo $row['id']; ?>">
                  <td>
                    <span class="arrow-toggle">▶</span>
                    <?php echo $index + 1; ?>
                  </td>
                  <td style="font-weight: bold; color: var(--color-primary);">
                    <span class="badge-deal"><?php echo htmlspecialchars($row['project_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                  </td>
                  <td style="font-weight: 500; color: var(--color-text-primary);">
                    <?php echo htmlspecialchars($row['client_name'], ENT_QUOTES, 'UTF-8'); ?>
                  </td>
                  <td>
                    <a href="mailto:<?php echo htmlspecialchars($row['client_email'], ENT_QUOTES, 'UTF-8'); ?>" style="color: var(--color-accent); text-decoration: underline;" onclick="event.stopPropagation();">
                      <?php echo htmlspecialchars($row['client_email'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                  </td>
                  <td>
                    <?php echo !empty($row['client_phone']) ? htmlspecialchars($row['client_phone'], ENT_QUOTES, 'UTF-8') : '<span style="color:var(--color-text-muted);">-</span>'; ?>
                  </td>
                  <td>
                    <?php echo !empty($row['company']) ? htmlspecialchars($row['company'], ENT_QUOTES, 'UTF-8') : '<span style="color:var(--color-text-muted);">-</span>'; ?>
                  </td>
                  <td style="font-size: 13px; color: var(--color-text-muted);">
                    <?php echo htmlspecialchars($row['requested_at'], ENT_QUOTES, 'UTF-8'); ?>
                  </td>
                </tr>
                <tr class="details-row" id="deal-details-<?php echo $row['id']; ?>">
                  <td colspan="7">
                    <div class="details-box">
                      <h5>Deal Request Details & Message:</h5>
                      <p><?php echo !empty($row['message']) ? nl2br(htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8')) : 'No additional message details provided.'; ?></p>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <!-- ========================================== -->
    <!-- TAB 3: UPCOMING EVENTS                     -->
    <!-- ========================================== -->
    <div id="tab-events" class="admin-tab-content <?php echo $active_tab === 'events' ? 'active' : ''; ?>">
      <div class="admin-actions-row">
        <h2 class="admin-title">Manage Upcoming Events</h2>
        <button class="btn btn-primary btn-sm" onclick="openEventModal()">+ Add Upcoming Event</button>
      </div>

      <?php if (count($upcoming_events) === 0): ?>
        <div class="card" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
          No events currently scheduled. Click "+ Add Upcoming Event" to create one.
        </div>
      <?php else: ?>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th style="width: 80px;">Date</th>
                <th>Month/Year</th>
                <th>Title</th>
                <th>Location</th>
                <th>Time</th>
                <th>Description</th>
                <th style="width: 150px; text-align: center;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($upcoming_events as $event): ?>
                <tr>
                  <td style="font-weight: 800; color: var(--color-primary); font-size: 16px;"><?php echo htmlspecialchars($event['day'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td style="font-weight: 500;"><?php echo htmlspecialchars($event['month'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td style="font-weight: bold; color: var(--color-text-primary);"><?php echo htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($event['location'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td style="font-size: 13px; font-weight: 500; color: var(--color-accent);"><?php echo htmlspecialchars($event['time'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td style="font-size: 13px; color: var(--color-text-secondary); max-width: 250px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                    <?php echo htmlspecialchars($event['description'], ENT_QUOTES, 'UTF-8'); ?>
                  </td>
                  <td style="text-align: center;">
                    <div class="action-buttons">
                      <button class="btn btn-edit btn-sm" onclick='openEventModal(<?php echo json_encode($event, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP); ?>)'>Edit</button>
                      <form action="actions.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');" style="display:inline;">
                        <input type="hidden" name="action" value="delete_event">
                        <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <!-- ========================================== -->
    <!-- TAB 4: GALLERY EVENTS                      -->
    <!-- ========================================== -->
    <div id="tab-gallery" class="admin-tab-content <?php echo $active_tab === 'gallery' ? 'active' : ''; ?>">
      <div class="admin-actions-row">
        <h2 class="admin-title">Manage Gallery Events & Image Text</h2>
        <button class="btn btn-primary btn-sm" onclick="openGalleryModal()">+ Add Gallery Item</button>
      </div>

      <?php if (count($gallery_events) === 0): ?>
        <div class="card" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
          No gallery events recorded. Click "+ Add Gallery Item" to insert one.
        </div>
      <?php else: ?>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th style="width: 100px;">Preview</th>
                <th>Title (Image Text)</th>
                <th>Subtitle</th>
                <th>Image Path</th>
                <th>Description</th>
                <th style="width: 150px; text-align: center;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($gallery_events as $item): ?>
                <tr>
                  <td>
                    <div style="width: 80px; height: 50px; border-radius: var(--radius-sm); overflow: hidden; background: #000; display: flex; align-items: center; justify-content: center; border: 1px solid var(--color-border);">
                      <img src="../<?php echo htmlspecialchars($item['image_path'], ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="Preview">
                    </div>
                  </td>
                  <td style="font-weight: bold; color: var(--color-text-primary);"><?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td style="color: var(--color-text-secondary);"><?php echo htmlspecialchars($item['subtitle'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td style="font-size: 12px; font-family: monospace; color: var(--color-text-muted);"><?php echo htmlspecialchars($item['image_path'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td style="font-size: 13px; color: var(--color-text-secondary); max-width: 250px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;"><?php echo htmlspecialchars($item['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                  <td style="text-align: center;">
                    <div class="action-buttons">
                      <button class="btn btn-edit btn-sm" onclick='openGalleryModal(<?php echo json_encode($item, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP); ?>)'>Edit</button>
                      <form action="actions.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this gallery item?');" style="display:inline;">
                        <input type="hidden" name="action" value="delete_gallery">
                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <!-- ========================================== -->
    <!-- TAB 5: CLIENT FEEDBACK                     -->
    <!-- ========================================== -->
    <div id="tab-feedback" class="admin-tab-content <?php echo $active_tab === 'feedback' ? 'active' : ''; ?>">
      <div class="admin-actions-row">
        <h2 class="admin-title">Manage Client Feedback</h2>
        <button class="btn btn-primary btn-sm" onclick="openFeedbackModal()">+ Add Client Feedback</button>
      </div>

      <?php if (count($testimonials) === 0): ?>
        <div class="card" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
          No feedback testimonials found. Click "+ Add Client Feedback" to insert testimonials.
        </div>
      <?php else: ?>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th style="width: 100px;">Stars</th>
                <th>Client Name</th>
                <th>Role / Company</th>
                <th>Initials</th>
                <th>Feedback Text</th>
                <th style="width: 150px; text-align: center;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($testimonials as $t): ?>
                <tr>
                  <td style="color: #FBBF24; font-size: 15px; font-weight: bold;">
                    <?php echo str_repeat('★', $t['stars']) . str_repeat('☆', 5 - $t['stars']); ?>
                  </td>
                  <td style="font-weight: bold; color: var(--color-text-primary);"><?php echo htmlspecialchars($t['author_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td style="font-size: 13px; color: var(--color-text-secondary);"><?php echo htmlspecialchars($t['author_role'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td>
                    <span style="display:inline-block; width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(135deg, var(--color-primary), var(--color-accent)); color: white; text-align: center; line-height: 30px; font-weight: bold; font-size: 12px;">
                      <?php echo htmlspecialchars($t['author_initials'], ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                  </td>
                  <td style="font-size: 13px; font-style: italic; color: var(--color-text-secondary); max-width: 300px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                    "<?php echo htmlspecialchars($t['text'], ENT_QUOTES, 'UTF-8'); ?>"
                  </td>
                  <td style="text-align: center;">
                    <div class="action-buttons">
                      <button class="btn btn-edit btn-sm" onclick='openFeedbackModal(<?php echo json_encode($t, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP); ?>)'>Edit</button>
                      <form action="actions.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this feedback?');" style="display:inline;">
                        <input type="hidden" name="action" value="delete_feedback">
                        <input type="hidden" name="id" value="<?php echo $t['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <!-- ========================================== -->
    <!-- SITE SETTINGS & MEDIA MANAGER TAB          -->
    <!-- ========================================== -->
    <div id="tab-settings" class="admin-tab-content <?php echo $active_tab === 'settings' ? 'active' : ''; ?>">
      <div class="admin-actions-row">
        <h2 class="admin-title">Site Settings & Media Manager</h2>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 32px; margin-bottom: 40px; align-items: start;">
        <!-- Card 1: Landing Page Hero/About Image -->
        <div class="card">
          <h3 style="margin-bottom: 16px; font-size: 18px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px; color: var(--color-text-primary);">Landing Page HQ Image</h3>
          <p style="font-size: 13px; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.5;">
            Replace the primary About visual image on the home page. The uploaded image will overwrite the default <code>images/hq_visual.png</code>.
          </p>
          <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 24px;">
            <div style="width: 120px; height: 120px; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--color-border); background: var(--color-bg-main); flex-shrink: 0;">
              <img src="../images/hq_visual.png?v=<?php echo file_exists('../images/hq_visual.png') ? filemtime('../images/hq_visual.png') : time(); ?>" alt="HQ Visual Preview" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div>
              <span style="font-size: 12px; font-weight: bold; color: var(--color-text-primary); display: block; margin-bottom: 4px;">Current Visual:</span>
              <span style="font-size: 11px; font-family: monospace; color: var(--color-text-muted);">images/hq_visual.png</span>
            </div>
          </div>
          <form action="actions.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update_hq_image">
            <div class="form-group" style="margin-bottom: 16px;">
              <input type="file" name="hq_image" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary" style="font-size: 13px; padding: 10px 24px;">Upload & Overwrite Image</button>
          </form>
        </div>

        <!-- Card 2: Upload General Asset -->
        <div class="card" style="height: 100%;">
          <h3 style="margin-bottom: 16px; font-size: 18px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px; color: var(--color-text-primary);">Upload New Asset</h3>
          <p style="font-size: 13px; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.5;">
            Upload images here to save them directly to the <code>images/</code> folder. You can then copy their paths to use in gallery events, upcoming deals, or anywhere else on the site.
          </p>
          <form action="actions.php" method="POST" enctype="multipart/form-data" style="margin-top: 36px;">
            <input type="hidden" name="action" value="upload_general_asset">
            <div class="form-group" style="margin-bottom: 24px;">
              <input type="file" name="asset_file" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary" style="font-size: 13px; padding: 10px 24px;">Upload Asset File</button>
          </form>
        </div>
      </div>

      <!-- Media Assets List Table -->
      <div class="card">
        <h3 style="margin-bottom: 20px; font-size: 18px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px; color: var(--color-text-primary);">Media Assets Library</h3>
        <?php
        $images_dir = __DIR__ . '/../images/';
        $image_files = [];
        if (is_dir($images_dir)) {
            $files = scandir($images_dir);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                $path = $images_dir . $file;
                if (is_file($path)) {
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $image_files[] = [
                            'name' => $file,
                            'path' => 'images/' . $file,
                            'size' => filesize($path),
                            'mtime' => filemtime($path)
                        ];
                    }
                }
            }
            usort($image_files, function($a, $b) {
                return $b['mtime'] - $a['mtime'];
            });
        }
        ?>

        <?php if (count($image_files) === 0): ?>
          <p style="color: var(--color-text-muted); text-align: center; padding: 20px;">No image files found in images/ folder.</p>
        <?php else: ?>
          <div class="table-container">
            <table>
              <thead>
                <tr>
                  <th style="width: 80px; text-align: center;">Preview</th>
                  <th>Filename</th>
                  <th>Relative Path</th>
                  <th>Size</th>
                  <th style="width: 200px; text-align: center;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($image_files as $img): ?>
                  <tr>
                    <td style="text-align: center;">
                      <a href="../<?php echo $img['path']; ?>" target="_blank">
                        <img src="../<?php echo $img['path']; ?>?v=<?php echo $img['mtime']; ?>" alt="<?php echo htmlspecialchars($img['name']); ?>" style="width: 44px; height: 44px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
                      </a>
                    </td>
                    <td style="font-weight: bold; color: var(--color-text-primary);"><?php echo htmlspecialchars($img['name']); ?></td>
                    <td>
                      <code style="font-size: 12px; padding: 2px 6px; background: var(--color-bg-main); border: 1px solid var(--color-border); border-radius: 4px; color: var(--color-primary);"><?php echo htmlspecialchars($img['path']); ?></code>
                    </td>
                    <td style="font-size: 13px; color: var(--color-text-secondary);">
                      <?php echo round($img['size'] / 1024, 1); ?> KB
                    </td>
                    <td style="text-align: center;">
                      <div class="action-buttons" style="justify-content: center; gap: 8px;">
                        <button type="button" class="btn btn-edit btn-sm" onclick="copyToClipboard('<?php echo htmlspecialchars($img['path']); ?>', this)">Copy Path</button>
                        <?php if ($img['name'] !== 'hq_visual.png'): ?>
                          <form action="actions.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this asset file?');" style="display:inline;">
                            <input type="hidden" name="action" value="delete_general_asset">
                            <input type="hidden" name="file_name" value="<?php echo htmlspecialchars($img['name']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                          </form>
                        <?php else: ?>
                          <span style="font-size: 11px; color: var(--color-text-muted); font-style: italic;">Protected</span>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>

  </main>

  <!-- ========================================== -->
  <!-- MODALS FOR CRUD ACTIONS                    -->
  <!-- ========================================== -->

  <!-- Modal 1: Events Modal -->
  <div class="modal-backdrop" id="event-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="event-modal-title">Add Upcoming Event</h3>
        <button class="close-modal" onclick="closeModal('event-modal')">&times;</button>
      </div>
      <form id="event-form" action="actions.php" method="POST">
        <input type="hidden" name="action" id="event-action" value="add_event">
        <input type="hidden" name="id" id="event-id" value="">
        
        <div style="display: grid; grid-template-columns: 100px 1fr; gap: 16px;">
          <div class="form-group">
            <label for="event-day">Day (DD)</label>
            <input type="text" name="day" id="event-day" class="form-control" placeholder="e.g. 25" required>
          </div>
          <div class="form-group">
            <label for="event-month">Month & Year</label>
            <input type="text" name="month" id="event-month" class="form-control" placeholder="e.g. June 2026" required>
          </div>
        </div>

        <div class="form-group">
          <label for="event-title">Event Title</label>
          <input type="text" name="title" id="event-title" class="form-control" placeholder="e.g. AI Technology Summit" required>
        </div>

        <div class="form-group">
          <label for="event-location">Location Address</label>
          <input type="text" name="location" id="event-location" class="form-control" placeholder="e.g. Software Centre, Sunderland" required>
        </div>

        <div class="form-group">
          <label for="event-time">Time</label>
          <input type="text" name="time" id="event-time" class="form-control" placeholder="e.g. 10:00 AM BST" required>
        </div>

        <div class="form-group">
          <label for="event-description">Event Description</label>
          <textarea name="description" id="event-description" class="form-control" rows="4" placeholder="Brief summary of event..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-submit">Save Event Details</button>
      </form>
    </div>
  </div>

  <!-- Modal 2: Gallery Modal -->
  <div class="modal-backdrop" id="gallery-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="gallery-modal-title">Add Gallery Item</h3>
        <button class="close-modal" onclick="closeModal('gallery-modal')">&times;</button>
      </div>
      <form id="gallery-form" action="actions.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" id="gallery-action" value="add_gallery">
        <input type="hidden" name="id" id="gallery-id" value="">

        <div class="form-group">
          <label for="gallery-title">Title (Image Text)</label>
          <input type="text" name="title" id="gallery-title" class="form-control" placeholder="e.g. AI Summit London" required>
        </div>

        <div class="form-group">
          <label for="gallery-subtitle">Subtitle / Category</label>
          <input type="text" name="subtitle" id="gallery-subtitle" class="form-control" placeholder="e.g. Technical Seminar Session" required>
        </div>

        <div class="form-group">
          <label for="gallery-image_file">Upload New Image File (Optional)</label>
          <input type="file" name="image_file" id="gallery-image_file" class="form-control" accept="image/*">
          <small style="color:var(--color-text-muted); font-size:11px; margin-top:4px; display:block;">Supported types: JPG, PNG, WEBP, GIF.</small>
        </div>

        <div class="form-group">
          <label for="gallery-image_path">Or Image Path (Relative to root)</label>
          <input type="text" name="image_path" id="gallery-image_path" class="form-control" placeholder="e.g. images/expo_london.png">
          <small style="color:var(--color-text-muted); font-size:11px; margin-top:4px; display:block;">Use local images like <em>images/expo_london.png</em>, <em>images/startup_week.png</em>, etc.</small>
        </div>

        <div class="form-group">
          <label for="gallery-description">Event Description / Brief</label>
          <textarea name="description" id="gallery-description" class="form-control" rows="4" placeholder="Detailed brief shown in the picture lightbox modal..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-submit">Save Gallery Item</button>
      </form>
    </div>
  </div>

  <!-- Modal 3: Feedback Modal -->
  <div class="modal-backdrop" id="feedback-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="feedback-modal-title">Add Client Feedback</h3>
        <button class="close-modal" onclick="closeModal('feedback-modal')">&times;</button>
      </div>
      <form id="feedback-form" action="actions.php" method="POST">
        <input type="hidden" name="action" id="feedback-action" value="add_feedback">
        <input type="hidden" name="id" id="feedback-id" value="">

        <div class="form-group">
          <label for="feedback-stars">Rating Score (1-5 Stars)</label>
          <select name="stars" id="feedback-stars" class="form-control" required>
            <option value="5">★★★★★ (5 Stars)</option>
            <option value="4">★★★★☆ (4 Stars)</option>
            <option value="3">★★★☆☆ (3 Stars)</option>
            <option value="2">★★☆☆☆ (2 Stars)</option>
            <option value="1">★☆☆☆☆ (1 Star)</option>
          </select>
        </div>

        <div class="form-group">
          <label for="feedback-author_name">Client Name</label>
          <input type="text" name="author_name" id="feedback-author_name" class="form-control" placeholder="e.g. Sarah Jenkins" required>
        </div>

        <div class="form-group">
          <label for="feedback-author_role">Role / Company Information</label>
          <input type="text" name="author_role" id="feedback-author_role" class="form-control" placeholder="e.g. Director of Operations, Apex Wealth" required>
        </div>

        <div class="form-group">
          <label for="feedback-text">Feedback Text</label>
          <textarea name="text" id="feedback-text" class="form-control" rows="4" placeholder="Enter customer testimonial text here..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-submit">Save Feedback Testimonial</button>
      </form>
    </div>
  </div>

  <!-- JavaScript Modules for Tabs and Modals -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // 1. Setup collapsibles (Inquiries and Deal Requests)
      const rows = document.querySelectorAll('.expandable-row');
      rows.forEach(row => {
        row.addEventListener('click', () => {
          row.classList.toggle('expanded');
          const targetId = row.getAttribute('data-target');
          const detailsRow = document.getElementById(targetId);
          if (detailsRow) {
            detailsRow.style.display = detailsRow.style.display === 'table-row' ? 'none' : 'table-row';
          }
        });
      });
      
      // 2. Read query parameters to set current tab on page load
      const urlParams = new URLSearchParams(window.location.search);
      const activeTabParam = urlParams.get('tab');
      if (activeTabParam) {
        switchTab(activeTabParam);
      }

      // 3. Theme Toggle setup for Dashboard
      const themeToggleBtns = document.querySelectorAll('.theme-toggle');
      const initialTheme = document.documentElement.getAttribute('data-theme') || 'light';
      updateToggleButtons(initialTheme);

      themeToggleBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          let theme = document.documentElement.getAttribute('data-theme') || 'light';
          theme = theme === 'dark' ? 'light' : 'dark';
          document.documentElement.setAttribute('data-theme', theme);
          localStorage.setItem('theme', theme);
          updateToggleButtons(theme);
        });
      });

      function updateToggleButtons(theme) {
        themeToggleBtns.forEach(btn => {
          if (theme === 'dark') {
            btn.innerHTML = '☀️ Light Mode';
          } else {
            btn.innerHTML = '🌙 Dark Mode';
          }
        });
      }
    });

    // Switch active Tab
    function switchTab(tabId) {
      // Update Tab button classes
      document.querySelectorAll('.admin-tab-btn').forEach(btn => {
        btn.classList.remove('active');
      });
      
      // Update active content classes
      document.querySelectorAll('.admin-tab-content').forEach(content => {
        content.classList.remove('active');
      });

      // Show requested tab content and button
      const targetBtn = Array.from(document.querySelectorAll('.admin-tab-btn')).find(btn => 
        btn.getAttribute('onclick').includes(tabId)
      );
      if (targetBtn) targetBtn.classList.add('active');

      const targetContent = document.getElementById('tab-' + tabId);
      if (targetContent) targetContent.classList.add('active');

      // Update URL without page refresh
      const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tab=' + tabId;
      window.history.pushState({path: newUrl}, '', newUrl);
    }

    function copyToClipboard(text, btn) {
      navigator.clipboard.writeText(text).then(() => {
        const origText = btn.innerText;
        btn.innerText = 'Copied!';
        btn.style.background = 'var(--color-success)';
        btn.style.color = '#fff';
        setTimeout(() => {
          btn.innerText = origText;
          btn.style.background = '';
          btn.style.color = '';
        }, 1500);
      }).catch(err => {
        console.error('Could not copy text: ', err);
        alert('Failed to copy. Path is: ' + text);
      });
    }

    // Modal Control Logic
    function closeModal(modalId) {
      const modal = document.getElementById(modalId);
      modal.classList.remove('show');
      setTimeout(() => {
        modal.style.display = 'none';
      }, 300);
    }

    // Event Modal Edit/Add
    function openEventModal(eventData = null) {
      const modal = document.getElementById('event-modal');
      const form = document.getElementById('event-form');
      const title = document.getElementById('event-modal-title');
      
      form.reset();
      
      if (eventData) {
        title.innerText = 'Edit Upcoming Event';
        document.getElementById('event-action').value = 'edit_event';
        document.getElementById('event-id').value = eventData.id;
        document.getElementById('event-day').value = eventData.day;
        document.getElementById('event-month').value = eventData.month;
        document.getElementById('event-title').value = eventData.title;
        document.getElementById('event-location').value = eventData.location;
        document.getElementById('event-time').value = eventData.time;
        document.getElementById('event-description').value = eventData.description;
      } else {
        title.innerText = 'Add Upcoming Event';
        document.getElementById('event-action').value = 'add_event';
        document.getElementById('event-id').value = '';
      }

      modal.style.display = 'flex';
      setTimeout(() => {
        modal.classList.add('show');
      }, 10);
    }

    // Gallery Modal Edit/Add
    function openGalleryModal(itemData = null) {
      const modal = document.getElementById('gallery-modal');
      const form = document.getElementById('gallery-form');
      const title = document.getElementById('gallery-modal-title');
      
      form.reset();
      
      if (itemData) {
        title.innerText = 'Edit Gallery Item';
        document.getElementById('gallery-action').value = 'edit_gallery';
        document.getElementById('gallery-id').value = itemData.id;
        document.getElementById('gallery-title').value = itemData.title;
        document.getElementById('gallery-subtitle').value = itemData.subtitle;
        document.getElementById('gallery-image_path').value = itemData.image_path;
        document.getElementById('gallery-description').value = itemData.description || '';
      } else {
        title.innerText = 'Add Gallery Item';
        document.getElementById('gallery-action').value = 'add_gallery';
        document.getElementById('gallery-id').value = '';
        document.getElementById('gallery-description').value = '';
      }

      modal.style.display = 'flex';
      setTimeout(() => {
        modal.classList.add('show');
      }, 10);
    }

    // Feedback Modal Edit/Add
    function openFeedbackModal(feedbackData = null) {
      const modal = document.getElementById('feedback-modal');
      const form = document.getElementById('feedback-form');
      const title = document.getElementById('feedback-modal-title');
      
      form.reset();
      
      if (feedbackData) {
        title.innerText = 'Edit Client Feedback';
        document.getElementById('feedback-action').value = 'edit_feedback';
        document.getElementById('feedback-id').value = feedbackData.id;
        document.getElementById('feedback-stars').value = feedbackData.stars;
        document.getElementById('feedback-author_name').value = feedbackData.author_name;
        document.getElementById('feedback-author_role').value = feedbackData.author_role;
        document.getElementById('feedback-text').value = feedbackData.text;
      } else {
        title.innerText = 'Add Client Feedback';
        document.getElementById('feedback-action').value = 'add_feedback';
        document.getElementById('feedback-id').value = '';
      }

      modal.style.display = 'flex';
      setTimeout(() => {
        modal.classList.add('show');
      }, 10);
    }
  </script>
</body>
</html>
