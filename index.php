<?php
/**
 * index.php
 * Home page of the AI-Solutions promotional website.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AI-Solutions - Powering the Future of Digital Work</title>
  <meta name="description" content="AI-Solutions is a leading Sunderland-based start-up providing next-generation AI software solutions, virtual assistants, and rapid prototyping.">

  <!-- CSS FILE -->
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

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <div class="hero-content">
        <span class="badge">Next-Generation AI Start-Up</span>
        <h1>Powering the Future of Digital Work</h1>
        <p>We build premium, custom AI-powered software solutions designed to automate workflows, accelerate innovation, and optimize business processes for global enterprises.</p>
        <div class="hero-btns">
          <a href="contact.php" class="btn btn-primary">Get Started Now</a>
          <a href="solutions.php" class="btn btn-secondary">Explore Solutions</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats strip Section -->
  <section class="stats-strip">
    <div class="container">
      <div class="stats-strip-grid">
        <div class="stat-strip-item">
          <h3>150+</h3>
          <p>AI Workflows Deployed</p>
        </div>
        <div class="stat-strip-item">
          <h3>3 Weeks</h3>
          <p>Average MVP Prototype</p>
        </div>
        <div class="stat-strip-item">
          <h3>98%</h3>
          <p>Client Satisfaction</p>
        </div>
        <div class="stat-strip-item">
          <h3>40%</h3>
          <p>Overhead Reduction</p>
        </div>
      </div>
    </div>
  </section>

  <!-- About Us Section -->
  <section class="about-section section-padding">
    <div class="container">
      <div class="grid-2">
        <div class="about-content">
          <span class="badge">Who We Are</span>
          <h2>A Sunderland-Based Start-Up with a Global Mission</h2>
          <p>Founded at the heart of Sunderland's technology hub, AI-Solutions is dedicated to delivering state-of-the-art software systems. Our mission is to bridge the gap between complex artificial intelligence research and practical, value-driven business operations.</p>
          <p>We pride ourselves on our agile methodologies, engineering excellence, and customer-first approach, enabling companies around the globe to scale seamlessly using intelligent automation.</p>
          <div class="about-features">
            <div class="about-feat-item">
              <span class="about-feat-icon">✓</span>
              <span>Sunderland Technology Hub</span>
            </div>
            <div class="about-feat-item">
              <span class="about-feat-icon">✓</span>
              <span>Global Client Base</span>
            </div>
            <div class="about-feat-item">
              <span class="about-feat-icon">✓</span>
              <span>Certified Engineers</span>
            </div>
            <div class="about-feat-item">
              <span class="about-feat-icon">✓</span>
              <span>24/7 Client Support</span>
            </div>
          </div>
        </div>
        <div class="img-container-aesthetic">
          <img src="images/hq_visual.png?v=<?php echo file_exists('images/hq_visual.png') ? filemtime('images/hq_visual.png') : time(); ?>" alt="AI-Solutions Office HQ" class="aesthetic-hq-img">
        </div>
      </div>
    </div>
  </section>

  <!-- Why Choose Us Section -->
  <section class="why-choose-us section-padding">
    <div class="container">
      <div class="section-header">
        <h2>Why Choose Us?</h2>
        <p>Our solutions are designed from the ground up to match the scaling requirements of businesses seeking maximum efficiency.</p>
      </div>
      <div class="grid-3">
        <!-- Card 1 -->
        <div class="card">
          <div class="card-icon">🤖</div>
          <h3>AI Virtual Assistant</h3>
          <p style="margin-top: 12px; color: var(--color-text-muted);">Increase customer engagement and streamline support workflows with our context-aware, highly conversational AI agent models.</p>
        </div>
        <!-- Card 2 -->
        <div class="card">
          <div class="card-icon">⚡</div>
          <h3>Rapid Prototyping</h3>
          <p style="margin-top: 12px; color: var(--color-text-muted);">Transform raw concepts into functional interactive prototypes in a matter of weeks, ensuring fast time-to-market testing.</p>
        </div>
        <!-- Card 3 -->
        <div class="card">
          <div class="card-icon">💎</div>
          <h3>Affordable Solutions</h3>
          <p style="margin-top: 12px; color: var(--color-text-muted);">High-performance custom software solutions tailored to fit startup budgets, with flexible pricing and zero hidden licensing costs.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Our Workflow Process Section -->
  <section class="process-section section-padding">
    <div class="container">
      <div class="section-header">
        <span class="badge">How We Work</span>
        <h2>Our Process to AI Integration</h2>
        <p>From initial concept evaluation to production deployment, we deliver custom, reliable software systems through an agile, collaborative lifecycle.</p>
      </div>
      <div class="process-grid">
        <!-- Step 1 -->
        <div class="process-step">
          <div class="process-num">01</div>
          <h3>Consultation & Audit</h3>
          <p>We analyze your business manual operations to pinpoint bottlenecks suitable for LLM or automation integration.</p>
        </div>
        <!-- Step 2 -->
        <div class="process-step">
          <div class="process-num">02</div>
          <h3>Rapid Prototyping</h3>
          <p>We craft a functional, sandboxed prototype in less than 3 weeks to validate usability and secure design feedback.</p>
        </div>
        <!-- Step 3 -->
        <div class="process-step">
          <div class="process-num">03</div>
          <h3>Custom Development</h3>
          <p>Our engineers engineer enterprise-grade code integrated with active APIs, securely connected to database models.</p>
        </div>
        <!-- Step 4 -->
        <div class="process-step">
          <div class="process-num">04</div>
          <h3>Continuous Support</h3>
          <p>We establish active support channels and continuously refine performance models to optimize processing speeds.</p>
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
