<?php
/**
 * events.php
 * Upcoming Events and Past Events Gallery page of the AI-Solutions promotional website.
 */
require_once __DIR__ . '/php/db_connect.php';

$upcoming_events = [];
$gallery_events = [];

$fallback_events = [
    [
        'day' => '25',
        'month' => 'June 2026',
        'location' => 'Sunderland Software Centre',
        'time' => '10:00 AM BST',
        'title' => 'Sunderland AI Summit 2026',
        'description' => 'Join our engineering team for an in-depth roundtable on enterprise automation. Learn how local businesses are adopting LLM-powered virtual assistants to reduce overheads and drive efficiency.'
    ],
    [
        'day' => '12',
        'month' => 'July 2026',
        'location' => 'Online (Zoom)',
        'time' => '3:00 PM BST',
        'title' => 'Process Automation Webinar',
        'description' => 'A free, interactive demonstration of our custom Robotic Process Automation (RPA) workflows. Watch how we connect databases and automate legacy documents in real time.'
    ],
    [
        'day' => '05',
        'month' => 'Aug 2026',
        'location' => 'Newcastle Helix',
        'time' => '09:00 AM BST',
        'title' => 'Global Hackathon 2026',
        'description' => 'Showcase your development speed. Team up with developers to prototype AI applications over a 48-hour sprint. Mentoring and server credits provided by AI-Solutions.'
    ]
];

$fallback_gallery = [
    ['title' => 'AI Expo London 2025', 'subtitle' => 'Exhibition & Keynote Panel', 'image_path' => 'images/expo_london.png'],
    ['title' => 'Sunderland Startup Week', 'subtitle' => 'Pitch & Networking Event', 'image_path' => 'images/startup_week.png'],
    ['title' => 'NHS NLP Pilot Kickoff', 'subtitle' => 'Technical Launch Seminar', 'image_path' => 'images/nhs_nlp.png'],
    ['title' => 'Fintech Automation Summit', 'subtitle' => 'Executive Roundtable Session', 'image_path' => 'images/fintech_summit.png'],
    ['title' => 'AI Prototyping Workshop', 'subtitle' => 'Hands-on Developer Meetup', 'image_path' => 'images/ai_workshop.png'],
    ['title' => 'Enterprise Data Seminar', 'subtitle' => 'Technical Training Forum', 'image_path' => 'images/data_seminar.png']
];

if (isset($pdo) && $pdo !== null) {
    try {
        $events_stmt = $pdo->query("SELECT * FROM `upcoming_events` ORDER BY id ASC");
        $upcoming_events = $events_stmt->fetchAll();
        
        $gallery_stmt = $pdo->query("SELECT * FROM `gallery_events` ORDER BY id ASC");
        $gallery_events = $gallery_stmt->fetchAll();
    } catch (\PDOException $e) {
        $upcoming_events = $fallback_events;
        $gallery_events = $fallback_gallery;
    }
} else {
    $upcoming_events = $fallback_events;
    $gallery_events = $fallback_gallery;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Events & Gallery - AI-Solutions</title>
  <meta name="description" content="View upcoming tech seminars, webinars, summits, and explore our gallery of past events hosted by AI-Solutions.">
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* Custom Styling for Event List Items */
    .event-card {
      display: grid;
      grid-template-columns: 180px 1fr;
      gap: 30px;
      margin-bottom: 24px;
      align-items: center;
    }
    .event-date-box {
      background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%);
      color: white;
      border-radius: var(--radius-md);
      padding: 24px;
      text-align: center;
      box-shadow: var(--shadow-sm);
    }
    .event-date-box .day {
      font-size: 32px;
      font-weight: 800;
      font-family: var(--font-headings);
      line-height: 1;
    }
    .event-date-box .month {
      font-size: 14px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-top: 4px;
    }
    .event-details h3 {
      font-size: 20px;
      margin-bottom: 8px;
    }
    .event-meta {
      font-size: 13px;
      color: var(--color-accent);
      font-weight: 500;
      margin-bottom: 8px;
      display: flex;
      gap: 16px;
    }
    
    @media (max-width: 768px) {
      .event-card {
        grid-template-columns: 1fr;
        gap: 16px;
      }
      .event-date-box {
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
      }
      .event-date-box .day {
        font-size: 24px;
      }
      .event-date-box .month {
        margin-top: 0;
      }
    }

    /* Glassmorphism Gallery Lightbox Modal */
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
      max-width: 720px;
      padding: 24px;
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
    }
    .close-modal:hover {
      color: var(--color-text-primary);
    }
    .gallery-item {
      cursor: pointer;
    }
  </style>
  <script>
    (function() {
      const theme = localStorage.getItem('theme') || 'light';
      document.documentElement.setAttribute('data-theme', theme);
    })();
  </script>
</head>
<body>

  <!-- Navigation Bar -->
  <header class="header-nav">
    <div class="container nav-container">
      <a href="index.php" class="logo"><span>AI</span>-Solutions</a>
      <button class="hamburger" id="hamburger" aria-label="Toggle menu">
        <span></span>
        <span></span>
        <span></span>
      </button>
      <ul class="nav-menu" id="nav-menu">
        <li><a href="index.php" class="nav-link">Home</a></li>
        <li><a href="solutions.php" class="nav-link">Solutions</a></li>
        <li><a href="past-projects.php" class="nav-link">Past Projects</a></li>
        <li><a href="feedback.php" class="nav-link">Feedback</a></li>
        <li><a href="events.php" class="nav-link">Events</a></li>
        <li><a href="contact.php" class="nav-link">Contact Us</a></li>
        <li><button class="theme-toggle" aria-label="Toggle theme">🌙 Dark Mode</button></li>
        <li><a href="admin/login.php" class="nav-link admin-nav-btn">Admin Login</a></li>
      </ul>
    </div>
  </header>

  <!-- Header Section -->
  <section class="section-padding" style="background: radial-gradient(circle at top, rgba(79, 70, 229, 0.08) 0%, transparent 60%);">
    <div class="container">
      <div class="section-header" style="margin-bottom: 40px;">
        <span class="badge">Community & Hubs</span>
        <h2>Events & Photo Gallery</h2>
        <p>Stay informed about our upcoming summits, product webinars, and browse through highlights from our past conferences.</p>
      </div>
    </div>
  </section>

  <!-- Upcoming Events Section -->
  <section class="upcoming-events-section" style="padding-bottom: 60px;">
    <div class="container">
      <div class="section-header" style="text-align: left; margin-bottom: 40px;">
        <h2 style="font-size: 28px;">Upcoming Events</h2>
      </div>
      
      <?php if (empty($upcoming_events)): ?>
        <div class="card" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
          No upcoming events scheduled at this time. Please check back later!
        </div>
      <?php else: ?>
        <?php foreach ($upcoming_events as $event): ?>
          <div class="card event-card">
            <div class="event-date-box">
              <div class="day"><?php echo htmlspecialchars($event['day'], ENT_QUOTES, 'UTF-8'); ?></div>
              <div class="month"><?php echo htmlspecialchars($event['month'], ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
            <div class="event-details">
              <div class="event-meta">
                <span>📍 <?php echo htmlspecialchars($event['location'], ENT_QUOTES, 'UTF-8'); ?></span>
                <span>⏰ <?php echo htmlspecialchars($event['time'], ENT_QUOTES, 'UTF-8'); ?></span>
              </div>
              <h3><?php echo htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
              <p style="color: var(--color-text-muted); font-size: 15px;">
                <?php echo nl2br(htmlspecialchars($event['description'], ENT_QUOTES, 'UTF-8')); ?>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>
  </section>

  <!-- Past Events Gallery Section -->
  <section class="gallery-section section-padding" style="border-top: 1px solid var(--color-border); padding-bottom: 80px;">
    <div class="container">
      <div class="section-header" style="text-align: left; margin-bottom: 40px;">
        <h2 style="font-size: 28px;">Past Events Gallery</h2>
        <p style="margin: 0; margin-top: 8px;">A visual look back at our community panels, keynotes, and product workshops.</p>
      </div>

      <div class="gallery-grid">
        
        <?php if (empty($gallery_events)): ?>
          <div class="card" style="grid-column: span 3; text-align: center; padding: 40px; color: var(--color-text-muted);">
            No past events to display in the gallery.
          </div>
        <?php else: ?>
          <?php foreach ($gallery_events as $item): ?>
            <div class="gallery-item" 
                 style="background-image: url('<?php echo htmlspecialchars($item['image_path'], ENT_QUOTES, 'UTF-8'); ?>'); background-size: cover; background-position: center;"
                 data-title="<?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?>"
                 data-subtitle="<?php echo htmlspecialchars($item['subtitle'], ENT_QUOTES, 'UTF-8'); ?>"
                 data-image="<?php echo htmlspecialchars($item['image_path'], ENT_QUOTES, 'UTF-8'); ?>"
                 data-description="<?php echo htmlspecialchars($item['description'] ?? 'An interactive event detailing next-generation engineering systems and client case studies.', ENT_QUOTES, 'UTF-8'); ?>">
              <div class="gallery-info">
                <h4><?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></h4>
                <p><?php echo htmlspecialchars($item['subtitle'], ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer-grid">
        <!-- Col 1: Brand Info -->
        <div class="footer-col">
          <a href="index.php" class="logo" style="margin-bottom: 20px; display: inline-flex;"><span>AI</span>-Solutions</a>
          <p>Next-generation software systems, custom virtual assistants, and rapid prototyping engineered for modern enterprises.</p>
          <p style="font-size: 13px; font-style: italic; margin-top: 12px;">Empowering global workflows from Sunderland.</p>
        </div>
        <!-- Col 2: Services -->
        <div class="footer-col">
          <h4>Solutions</h4>
          <ul>
            <li><a href="solutions.php">Virtual Assistants</a></li>
            <li><a href="solutions.php">Rapid Prototyping</a></li>
            <li><a href="solutions.php">Affordable Analytics</a></li>
            <li><a href="past-projects.php">Past Projects</a></li>
          </ul>
        </div>
        <!-- Col 3: Navigation -->
        <div class="footer-col">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="index.php">Home Page</a></li>
            <li><a href="feedback.php">Client Feedback</a></li>
            <li><a href="events.php">Upcoming Events</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <li><a href="admin/login.php">Admin Portal</a></li>
          </ul>
        </div>
        <!-- Col 4: Contact -->
        <div class="footer-col">
          <h4>Get In Touch</h4>
          <div class="footer-contact-item">
            <span class="footer-contact-icon">📍</span>
            <span>Sunderland Software Centre,<br>Sunderland, SR1 1PB, UK</span>
          </div>
          <div class="footer-contact-item">
            <span class="footer-contact-icon">✉️</span>
            <span>contact@ai-solutions.co.uk</span>
          </div>
          <div class="footer-contact-item">
            <span class="footer-contact-icon">📞</span>
            <span>+44 (0) 191 555 0199</span>
          </div>
        </div>
      </div>
      
      <!-- Footer Bottom -->
      <div class="footer-bottom">
        <p>&copy; <?php echo date("Y"); ?> AI-Solutions. All rights reserved. University Project Prototype.</p>
        <ul class="footer-bottom-links">
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms of Service</a></li>
        </ul>
      </div>
    </div>
  </footer>

  <!-- Floating Chatbot UI -->
  <button class="chatbot-bubble" id="chatbot-bubble" aria-label="Open chat">
    <svg viewBox="0 0 24 24">
      <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/>
    </svg>
  </button>

  <div class="chatbot-window" id="chatbot-window">
    <div class="chatbot-header">
      <div class="chatbot-header-info">
        <div class="chatbot-avatar">🤖</div>
        <h3>AI-Solutions Bot</h3>
      </div>
      <div class="chatbot-header-actions">
        <button class="chatbot-header-btn" id="chatbot-clear" title="Clear Chat">Clear</button>
        <button class="chatbot-header-btn" id="chatbot-close" title="Close Chat" style="font-size: 16px; font-weight: bold;">&times;</button>
      </div>
    </div>
    <div class="chatbot-body" id="chatbot-body">
      <!-- Messages loaded dynamically by JS -->
    </div>
    <div class="chatbot-footer">
      <input type="text" class="chatbot-input" id="chatbot-input" placeholder="Type a message..." autocomplete="off">
      <button class="chatbot-send-btn" id="chatbot-send" aria-label="Send message">
        <svg viewBox="0 0 24 24">
          <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
        </svg>
      </button>
    </div>
  </div>

  <!-- Gallery Lightbox Modal -->
  <div class="modal-backdrop" id="gallery-lightbox">
    <div class="modal-content" style="max-width: 720px; padding: 24px;">
      <div class="modal-header">
        <h3 id="lightbox-title">Event Title</h3>
        <button class="close-modal" id="close-lightbox">&times;</button>
      </div>
      <div style="display: flex; flex-direction: column; gap: 16px;">
        <div style="width: 100%; height: 380px; border-radius: var(--radius-md); overflow: hidden; background: #000; display: flex; align-items: center; justify-content: center; border: 1px solid var(--color-border);">
          <img id="lightbox-img" src="" style="width: 100%; height: 100%; object-fit: cover;" alt="Event Image">
        </div>
        <div>
          <span id="lightbox-subtitle" class="badge" style="margin-bottom: 10px;">Subtitle / Category</span>
          <h4 style="font-size: 16px; margin-bottom: 8px; color: var(--color-text-primary);">Event Summary & Brief:</h4>
          <p id="lightbox-desc" style="color: var(--color-text-secondary); line-height: 1.6; font-size: 14px;">
            Detailed description of the event will appear here.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="js/main.js"></script>
  <script src="js/chatbot.js"></script>

  <!-- Lightbox Controller Script -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const lightbox = document.getElementById('gallery-lightbox');
      const closeBtn = document.getElementById('close-lightbox');
      const lightboxImg = document.getElementById('lightbox-img');
      const lightboxTitle = document.getElementById('lightbox-title');
      const lightboxSubtitle = document.getElementById('lightbox-subtitle');
      const lightboxDesc = document.getElementById('lightbox-desc');

      // Add click handler to all gallery items
      document.querySelectorAll('.gallery-item').forEach(item => {
        item.addEventListener('click', () => {
          const title = item.getAttribute('data-title');
          const subtitle = item.getAttribute('data-subtitle');
          const image = item.getAttribute('data-image');
          const desc = item.getAttribute('data-description') || 'No additional details are recorded for this event.';

          lightboxTitle.textContent = title;
          lightboxSubtitle.textContent = subtitle;
          lightboxImg.src = image;
          lightboxDesc.textContent = desc;

          lightbox.style.display = 'flex';
          setTimeout(() => {
            lightbox.classList.add('show');
          }, 10);
        });
      });

      // Close Lightbox Function
      const closeLightbox = () => {
        lightbox.classList.remove('show');
        setTimeout(() => {
          lightbox.style.display = 'none';
        }, 300);
      };

      closeBtn.addEventListener('click', closeLightbox);

      // Close on backdrop click
      lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
          closeLightbox();
        }
      });
    });
  </script>
</body>
</html>
