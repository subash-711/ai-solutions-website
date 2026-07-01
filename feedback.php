<?php
/**
 * feedback.php
 * Customer Feedback page of the AI-Solutions promotional website.
 */
require_once __DIR__ . '/php/db_connect.php';

$testimonials = [];

$fallback_testimonials = [
    [
        'stars' => 5,
        'text' => 'The AI Virtual Assistant developed by AI-Solutions has completely transformed our customer relations. We saw an immediate reduction in response times and ticket backlogs. Their team was incredibly helpful throughout the transition!',
        'author_initials' => 'SH',
        'author_name' => 'Sarah Jenkins',
        'author_role' => 'Director of Operations, Apex Wealth'
    ],
    [
        'stars' => 5,
        'text' => 'Their Rapid Prototyping service allowed us to demonstrate our new concept to investors in record time. We secured the funding we needed thanks to the functional and high-performance MVP they delivered in just three weeks.',
        'author_initials' => 'MD',
        'author_name' => 'Marcus Davies',
        'author_role' => 'Founder & CEO, TechVelo'
    ],
    [
        'stars' => 4,
        'text' => 'Process automation was a major hurdle for our administrative teams. AI-Solutions built a customized RPA tool that runs seamlessly in the background, freeing up our hours and eliminating manual transcription errors.',
        'author_initials' => 'EL',
        'author_name' => 'Emily Lawson',
        'author_role' => 'Lead Operations Specialist, CareTrust'
    ],
    [
        'stars' => 5,
        'text' => 'The advanced predictive analytics engine built by AI-Solutions has given us deep visibility into our logistics. We\'ve optimized our supply runs and significantly decreased holding costs. An outstanding and affordable service.',
        'author_initials' => 'AK',
        'author_name' => 'Alok Kumar',
        'author_role' => 'Global Logistics Manager, Velo Retail'
    ],
    [
        'stars' => 5,
        'text' => 'It is rare to find a software vendor that combines technical mastery with such a high degree of transparency. The team at AI-Solutions delivered our automation system exactly as specified, under budget, and on schedule.',
        'author_initials' => 'CR',
        'author_name' => 'Claire Richardson',
        'author_role' => 'Head of Digital, Innovate UK'
    ]
];

if (isset($pdo) && $pdo !== null) {
    try {
        $testimonials_stmt = $pdo->query("SELECT * FROM `testimonials` ORDER BY id ASC");
        $testimonials = $testimonials_stmt->fetchAll();
    } catch (\PDOException $e) {
        $testimonials = $fallback_testimonials;
    }
} else {
    $testimonials = $fallback_testimonials;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Client Testimonials - AI-Solutions</title>
  <meta name="description" content="Read reviews and testimonials from our enterprise customers detailing their experiences using AI-Solutions' software products.">
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* Glassmorphism Feedback Submission Modal */
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
      max-width: 480px;
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
    .modal-alert {
      padding: 10px 14px;
      border-radius: var(--radius-sm);
      font-size: 13px;
      margin-bottom: 16px;
      display: none;
    }
    .modal-alert.success {
      display: block;
      background: rgba(79, 70, 229, 0.1);
      color: var(--color-primary);
      border: 1px solid rgba(79, 70, 229, 0.2);
    }
    .modal-alert.error {
      display: block;
      background: rgba(239, 68, 68, 0.1);
      color: var(--color-error);
      border: 1px solid rgba(239, 68, 68, 0.2);
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
        <span class="badge">Trusted globally</span>
        <h2>Customer Feedback</h2>
        <p>Discover what our clients have to say about partnering with AI-Solutions for their software integration needs.</p>
        <button id="btn-open-feedback" class="btn btn-primary" style="margin-top: 24px;">+ Share Your Feedback</button>
      </div>
    </div>
  </section>

  <!-- Testimonials Grid -->
  <section class="testimonials-section" style="padding-bottom: 80px;">
    <div class="container">
      <div class="testimonial-grid">
        
        <?php if (empty($testimonials)): ?>
          <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--color-text-muted);">
            No customer feedback posted yet.
          </div>
        <?php else: ?>
          <?php foreach ($testimonials as $t): ?>
            <div class="card testimonial-card">
              <div class="stars"><?php echo str_repeat('★', $t['stars']) . str_repeat('☆', 5 - $t['stars']); ?></div>
              <p style="font-style: italic; color: var(--color-text-secondary); line-height: 1.6; font-size: 15px;">
                "<?php echo htmlspecialchars($t['text'], ENT_QUOTES, 'UTF-8'); ?>"
              </p>
              <div class="testimonial-author">
                <div class="avatar-placeholder"><?php echo htmlspecialchars($t['author_initials'], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="author-info">
                  <h4><?php echo htmlspecialchars($t['author_name'], ENT_QUOTES, 'UTF-8'); ?></h4>
                  <p><?php echo htmlspecialchars($t['author_role'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
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

  <!-- Feedback Submission Modal -->
  <div class="modal-backdrop" id="feedback-submit-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Share Your Feedback</h3>
        <button class="close-modal" id="close-feedback-modal">&times;</button>
      </div>
      <div class="modal-alert success" id="feedback-alert-success"></div>
      <div class="modal-alert error" id="feedback-alert-error"></div>
      <form id="feedback-submission-form">
        
        <div class="form-group">
          <label for="rating_stars">Rating Stars <span style="color:var(--color-error)">*</span></label>
          <select name="stars" id="rating_stars" class="form-control" required>
            <option value="5">★★★★★ (5 Stars)</option>
            <option value="4">★★★★☆ (4 Stars)</option>
            <option value="3">★★★☆☆ (3 Stars)</option>
            <option value="2">★★☆☆☆ (2 Stars)</option>
            <option value="1">★☆☆☆☆ (1 Star)</option>
          </select>
        </div>

        <div class="form-group">
          <label for="user_name">Full Name <span style="color:var(--color-error)">*</span></label>
          <input type="text" name="author_name" id="user_name" class="form-control" placeholder="Enter your full name" required>
        </div>

        <div class="form-group">
          <label for="user_role">Company / Job Role <span style="color:var(--color-error)">*</span></label>
          <input type="text" name="author_role" id="user_role" class="form-control" placeholder="e.g. Director, Innovate UK" required>
        </div>

        <div class="form-group">
          <label for="feedback_message">Your Feedback / Review <span style="color:var(--color-error)">*</span></label>
          <textarea name="text" id="feedback_message" class="form-control" rows="4" placeholder="Tell us about your experience partnering with AI-Solutions..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 10px;">Submit Feedback</button>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="js/main.js"></script>
  <script src="js/chatbot.js"></script>

  <!-- Feedback Submission Handler Script -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const modal = document.getElementById('feedback-submit-modal');
      const openBtn = document.getElementById('btn-open-feedback');
      const closeBtn = document.getElementById('close-feedback-modal');
      const form = document.getElementById('feedback-submission-form');
      const alertSuccess = document.getElementById('feedback-alert-success');
      const alertError = document.getElementById('feedback-alert-error');

      // Open Modal
      openBtn.addEventListener('click', () => {
        alertSuccess.style.display = 'none';
        alertError.style.display = 'none';
        form.reset();
        
        modal.style.display = 'flex';
        setTimeout(() => {
          modal.classList.add('show');
        }, 10);
      });

      // Close Modal Function
      const closeModal = () => {
        modal.classList.remove('show');
        setTimeout(() => {
          modal.style.display = 'none';
        }, 300);
      };

      closeBtn.addEventListener('click', closeModal);
      
      // Close on backdrop click
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          closeModal();
        }
      });

      // Handle Form Submit
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        alertSuccess.style.display = 'none';
        alertError.style.display = 'none';

        const formData = new FormData(form);

        fetch('php/submit_feedback.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alertSuccess.textContent = data.message;
            alertSuccess.style.display = 'block';
            form.style.opacity = '0.5';
            form.querySelectorAll('button, input, textarea, select').forEach(el => el.disabled = true);
            setTimeout(() => {
              closeModal();
              // Re-enable form after closing animation
              setTimeout(() => {
                form.style.opacity = '1';
                form.querySelectorAll('button, input, textarea, select').forEach(el => el.disabled = false);
                window.location.reload();
              }, 300);
            }, 1800);
          } else {
            alertError.textContent = data.message;
            alertError.style.display = 'block';
          }
        })
        .catch(err => {
          alertError.textContent = 'An error occurred. Please try again.';
          alertError.style.display = 'block';
          console.error(err);
        });
      });
    });
  </script>
</body>
</html>
