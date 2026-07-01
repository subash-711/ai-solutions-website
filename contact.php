<?php
/**
 * contact.php
 * Contact Us page of the AI-Solutions promotional website.
 * Contains the inquiry submission form.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - AI-Solutions</title>
  <meta name="description" content="Get in touch with the AI-Solutions team to schedule a demo, request a quote, or discuss custom AI development.">
  <link rel="stylesheet" href="css/style.css">
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
        <span class="badge">Start your transition</span>
        <h2>Contact Us</h2>
        <p>Fill out the inquiry form below, and our Sunderland-based consulting team will review your specifications and contact you within 24 hours.</p>
      </div>
    </div>
  </section>

  <!-- Contact Section Grid -->
  <section class="contact-section" style="padding-bottom: 80px;">
    <div class="container">
      <div class="contact-grid">
        
        <!-- Column 1: Info panel -->
        <div class="about-content">
          <h2 style="font-size: 26px;">Get In Touch</h2>
          <p style="color: var(--color-text-muted); margin-top: 10px;">
            Whether you want to schedule a product demo or discuss custom process automation, we have tailored plans for every operational scale.
          </p>
          
          <ul class="contact-info-list">
            <li class="contact-info-item">
              <span class="contact-info-icon">📍</span>
              <div class="contact-info-text">
                <h4>Headquarters</h4>
                <p>Sunderland Software Centre, Tavistock Place, Sunderland, SR1 1PB</p>
              </div>
            </li>
            <li class="contact-info-item">
              <span class="contact-info-icon">✉️</span>
              <div class="contact-info-text">
                <h4>Inquiries Email</h4>
                <p>contact@ai-solutions.co.uk</p>
              </div>
            </li>
            <li class="contact-info-item">
              <span class="contact-info-icon">📞</span>
              <div class="contact-info-text">
                <h4>Support Phone</h4>
                <p>+44 (0) 191 555 0192</p>
              </div>
            </li>
            <li class="contact-info-item">
              <span class="contact-info-icon">⏰</span>
              <div class="contact-info-text">
                <h4>Consulting Hours</h4>
                <p>Monday - Friday: 09:00 - 17:00 GMT</p>
              </div>
            </li>
          </ul>
        </div>

        <!-- Column 2: Inquiry Form -->
        <div class="card" style="padding: 40px;">
          <!-- Alert Boxes for AJAX Feedback -->
          <div id="success-alert" class="alert alert-success" style="display: none;"></div>
          <div id="error-alert" class="alert alert-error" style="display: none;"></div>

          <form id="contactForm" method="POST" novalidate>
            
            <div class="form-row">
              <!-- Full Name -->
              <div class="form-group">
                <label for="name">Full Name <span>*</span></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
              </div>

              <!-- Email Address -->
              <div class="form-group">
                <label for="email">Email Address <span>*</span></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="john@example.com" required>
              </div>
            </div>

            <div class="form-row">
              <!-- Phone Number -->
              <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="+44 7911 123456">
              </div>

              <!-- Company Name -->
              <div class="form-group">
                <label for="company">Company Name</label>
                <input type="text" class="form-control" id="company" name="company" placeholder="Acme Corporation">
              </div>
            </div>

            <div class="form-row">
              <!-- Country Dropdown -->
              <div class="form-group">
                <label for="country">Country</label>
                <select class="form-control" id="country" name="country">
                  <option value="">Select your country...</option>
                  <option value="United Kingdom">United Kingdom</option>
                  <option value="United States">United States</option>
                  <option value="Canada">Canada</option>
                  <option value="Australia">Australia</option>
                  <option value="Germany">Germany</option>
                  <option value="France">France</option>
                  <option value="India">India</option>
                  <option value="Singapore">Singapore</option>
                  <option value="Japan">Japan</option>
                  <option value="United Arab Emirates">United Arab Emirates</option>
                  <option value="Brazil">Brazil</option>
                  <option value="South Africa">South Africa</option>
                  <option value="Ireland">Ireland</option>
                  <option value="Netherlands">Netherlands</option>
                  <option value="Switzerland">Switzerland</option>
                  <option value="Spain">Spain</option>
                  <option value="Italy">Italy</option>
                  <option value="Sweden">Sweden</option>
                  <option value="Norway">Norway</option>
                  <option value="Denmark">Denmark</option>
                  <option value="Nepal">Nepal</option>
                </select>
              </div>

              <!-- Job Title -->
              <div class="form-group">
                <label for="job_title">Job Title</label>
                <input type="text" class="form-control" id="job_title" name="job_title" placeholder="Operations Director">
              </div>
            </div>

            <!-- Job Details -->
            <div class="form-group">
              <label for="job_details">Project details & requirements</label>
              <textarea class="form-control" id="job_details" name="job_details" rows="5" placeholder="Describe your workflow bottleneck, estimated volume, or what system you want automated..."></textarea>
            </div>

            <!-- Submit Button -->
            <div style="margin-top: 24px;">
              <button type="submit" class="btn btn-primary" style="width: 100%; border-radius: var(--radius-sm); font-size: 16px;">
                Submit Secure Inquiry
              </button>
            </div>

          </form>
        </div>

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

  <!-- Scripts -->
  <script src="js/main.js"></script>
  <script src="js/chatbot.js"></script>
</body>
</html>
