<?php
/**
 * solutions.php
 * Software Solutions page of the AI-Solutions promotional website.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Our Solutions - AI-Solutions</title>
  <meta name="description" content="Explore our innovative AI solutions: Virtual Assistants, Rapid Prototyping, Process Automation, and Advanced Data Analytics.">
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* Glassmorphism Solutions Details Modal */
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
      max-width: 600px;
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
      font-size: 22px;
      font-weight: 800;
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
    .modal-body {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    .modal-list-title {
      font-weight: 700;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--color-primary);
      margin-bottom: 8px;
    }
    .modal-list {
      list-style: none;
      padding-left: 0;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .modal-list-item {
      font-size: 14px;
      color: var(--color-text-secondary);
      display: flex;
      align-items: baseline;
      gap: 8px;
      line-height: 1.5;
    }
    .modal-list-item::before {
      content: '✓';
      color: var(--color-accent);
      font-weight: bold;
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

  <!-- Solutions Header -->
  <section class="section-padding" style="background: radial-gradient(circle at top, rgba(79, 70, 229, 0.08) 0%, transparent 60%);">
    <div class="container">
      <div class="section-header" style="margin-bottom: 40px;">
        <span class="badge">Enterprise Software Suite</span>
        <h2>Cutting-Edge AI Solutions</h2>
        <p>We build robust, intelligent digital products engineered to solve complex operational challenges and drive productivity.</p>
      </div>
    </div>
  </section>

  <!-- Solutions Grid Section -->
  <section class="solutions-section" style="padding-bottom: 80px;">
    <div class="container">
      <div class="grid-2">
        <!-- Solution 1: AI Virtual Assistant -->
        <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
          <div>
            <div class="card-icon">💬</div>
            <h3>AI Virtual Assistant</h3>
            <span class="badge" style="margin-top: 8px; margin-bottom: 16px;">NLP & Chatbots</span>
            <p style="color: var(--color-text-muted); font-size: 15px; margin-bottom: 20px;">
              Deploy intelligent, context-aware virtual assistants capable of automating customer support, resolving common tickets, and driving high-converting user interactions. Built on secure LLMs trained specifically on your enterprise knowledge base.
            </p>
          </div>
          <div>
            <button class="btn btn-secondary btn-learn-more" style="font-size: 13px; padding: 8px 20px; width: auto;"
                    data-title="AI Virtual Assistant"
                    data-subtitle="NLP & Chatbots"
                    data-description="Deploy intelligent, context-aware virtual assistants capable of automating customer support, resolving common tickets, and driving high-converting user interactions. Built on secure LLMs trained specifically on your enterprise knowledge base."
                    data-features="24/7 automated support parsing customer intents;Seamless handoff to human support representatives;Out-of-the-box integrations with CRMs, email, and live chat platforms;Multi-lingual capabilities with localized model fine-tuning"
                    data-usecases="Automated helpdesk triage;Website sales conversion assistants;Internal enterprise document search bots"
                    data-slug="virtual-assistant">
              Learn More
            </button>
          </div>
        </div>

        <!-- Solution 2: Rapid Prototyping -->
        <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
          <div>
            <div class="card-icon">⚙️</div>
            <h3>Rapid Prototyping</h3>
            <span class="badge" style="margin-top: 8px; margin-bottom: 16px;">MVP Development</span>
            <p style="color: var(--color-text-muted); font-size: 15px; margin-bottom: 20px;">
              Validate your digital concepts in record time. We build production-ready Minimum Viable Products (MVPs) in weeks instead of months, leveraging dynamic web systems to test user flows and gain market validation quickly.
            </p>
          </div>
          <div>
            <button class="btn btn-secondary btn-learn-more" style="font-size: 13px; padding: 8px 20px; width: auto;"
                    data-title="Rapid Prototyping"
                    data-subtitle="MVP Development"
                    data-description="Validate your digital concepts in record time. We build production-ready Minimum Viable Products (MVPs) in weeks instead of months, leveraging dynamic web systems to test user flows and gain market validation quickly."
                    data-features="High-fidelity interactive UI mockups and animations;Production-grade database setup and hosting;Custom admin consoles and back-office management tables;Clean, well-documented codebases ready for team scaling"
                    data-usecases="Startup pitch-deck MVPs;Technical proof-of-concept validations;Internal tools rapid testing"
                    data-slug="rapid-prototyping">
              Learn More
            </button>
          </div>
        </div>

        <!-- Solution 3: Process Automation -->
        <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
          <div>
            <div class="card-icon">🔁</div>
            <h3>Process Automation</h3>
            <span class="badge" style="margin-top: 8px; margin-bottom: 16px;">RPA & Workflows</span>
            <p style="color: var(--color-text-muted); font-size: 15px; margin-bottom: 20px;">
              Eliminate repetitive manual tasks. Our Robotic Process Automation (RPA) tools connect legacy software systems, automate data ingestion, process standard documentation, and orchestrate complex background workflows with zero human error.
            </p>
          </div>
          <div>
            <button class="btn btn-secondary btn-learn-more" style="font-size: 13px; padding: 8px 20px; width: auto;"
                    data-title="Process Automation"
                    data-subtitle="RPA & Workflows"
                    data-description="Eliminate repetitive manual tasks. Our Robotic Process Automation (RPA) tools connect legacy software systems, automate data ingestion, process standard documentation, and orchestrate complex background workflows with zero human error."
                    data-features="Background workflow scheduling and event-driven triggers;OCR document parsing (PDF invoices, scans, and email attachments);Legacy API bridges and data sync scripts;Failure tracking alert systems with Slack/email notifications"
                    data-usecases="Automatic invoice processing;Client registration synchronization;Automated backup pipelines"
                    data-slug="process-automation">
              Learn More
            </button>
          </div>
        </div>

        <!-- Solution 4: Data Analytics -->
        <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
          <div>
            <div class="card-icon">📊</div>
            <h3>Data Analytics</h3>
            <span class="badge" style="margin-top: 8px; margin-bottom: 16px;">Predictive Insights</span>
            <p style="color: var(--color-text-muted); font-size: 15px; margin-bottom: 20px;">
              Unlock hidden patterns in your corporate data. We implement machine learning forecasting models, automated report builders, and customized real-time charts designed to assist executives in making data-driven decisions.
            </p>
          </div>
          <div>
            <button class="btn btn-secondary btn-learn-more" style="font-size: 13px; padding: 8px 20px; width: auto;"
                    data-title="Data Analytics"
                    data-subtitle="Predictive Insights"
                    data-description="Unlock hidden patterns in your corporate data. We implement machine learning forecasting models, automated report builders, and customized real-time charts designed to assist executives in making data-driven decisions."
                    data-features="Dynamic dashboard graphs and charts;Deep analysis pipelines with CSV/Excel import-export;Recurrent Neural Network (RNN) stock and sales forecasting;Multi-tenant reporting layers with custom view permissions"
                    data-usecases="E-commerce stock replenishment optimization;Financial yield reporting dashboards;Client activity trends tracking"
                    data-slug="data-analytics">
              Learn More
            </button>
          </div>
        </div>       </div>
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

  <!-- Solution Details Modal -->
  <div class="modal-backdrop" id="solution-details-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="sol-title">Solution Title</h3>
        <button class="close-modal" id="close-sol-modal">&times;</button>
      </div>
      <div class="modal-body">
        <div>
          <span id="sol-subtitle" class="badge">Category</span>
          <p id="sol-desc" style="color: var(--color-text-secondary); line-height: 1.6; font-size: 15px; margin-top: 12px; margin-bottom: 24px;">
            Detailed description of the solution goes here.
          </p>
        </div>

        <div>
          <div class="modal-list-title">Key Core Features</div>
          <ul id="sol-features-list" class="modal-list">
            <!-- Populated dynamically -->
          </ul>
        </div>

        <div style="margin-top: 12px;">
          <div class="modal-list-title">Enterprise Use Cases</div>
          <ul id="sol-usecases-list" class="modal-list">
            <!-- Populated dynamically -->
          </ul>
        </div>

        <div style="margin-top: 24px; border-top: 1px solid var(--color-border); padding-top: 20px;">
          <a id="sol-inquire-btn" href="contact.php" class="btn btn-primary" style="width: 100%; padding: 12px;">Inquire About This Solution</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="js/main.js"></script>
  <script src="js/chatbot.js"></script>

  <!-- Solutions Modal Controller Script -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const modal = document.getElementById('solution-details-modal');
      const closeBtn = document.getElementById('close-sol-modal');
      const solTitle = document.getElementById('sol-title');
      const solSubtitle = document.getElementById('sol-subtitle');
      const solDesc = document.getElementById('sol-desc');
      const solFeatures = document.getElementById('sol-features-list');
      const solUsecases = document.getElementById('sol-usecases-list');
      const solInquireBtn = document.getElementById('sol-inquire-btn');

      // Add click handler to all learn more buttons
      document.querySelectorAll('.btn-learn-more').forEach(btn => {
        btn.addEventListener('click', () => {
          const title = btn.getAttribute('data-title');
          const subtitle = btn.getAttribute('data-subtitle');
          const desc = btn.getAttribute('data-description');
          const features = btn.getAttribute('data-features').split(';');
          const usecases = btn.getAttribute('data-usecases').split(';');
          const slug = btn.getAttribute('data-slug');

          // Set text content
          solTitle.textContent = title;
          solSubtitle.textContent = subtitle;
          solDesc.textContent = desc;

          // Clear lists
          solFeatures.innerHTML = '';
          solUsecases.innerHTML = '';

          // Populate features
          features.forEach(feat => {
            const li = document.createElement('li');
            li.className = 'modal-list-item';
            li.textContent = feat;
            solFeatures.appendChild(li);
          });

          // Populate use cases
          usecases.forEach(use => {
            const li = document.createElement('li');
            li.className = 'modal-list-item';
            li.textContent = use;
            solUsecases.appendChild(li);
          });

          // Set up contact link redirect
          solInquireBtn.href = `contact.php?interest=${slug}`;

          // Open modal
          modal.style.display = 'flex';
          setTimeout(() => {
            modal.classList.add('show');
          }, 10);
        });
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
    });
  </script>
</body>
</html>
