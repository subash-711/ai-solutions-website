<?php
/**
 * past-projects.php
 * Case Studies and Past Projects page of the AI-Solutions promotional website.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Case Studies - AI-Solutions</title>
  <meta name="description" content="Discover how AI-Solutions has transformed operations for healthcare, finance, and e-commerce partners through custom AI integrations.">
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* Unique CSS Shape graphics for Projects instead of images */
    .project-visual {
      width: 100%;
      height: 180px;
      border-radius: var(--radius-sm);
      margin-bottom: 20px;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #F3F4F6;
      border: 1px solid var(--color-border);
    }
    
    /* Project 1 Graphic (Finance - Grid & Glowing Sphere) */
    .project-visual.finance::before {
      content: '';
      position: absolute;
      width: 80px;
      height: 80px;
      background: radial-gradient(circle, rgba(37, 99, 235, 0.3) 0%, transparent 70%);
      border-radius: 50%;
      filter: blur(5px);
      animation: floatGraphic 4s ease-in-out infinite alternate;
    }
    .project-visual.finance::after {
      content: '';
      position: absolute;
      width: 120px;
      height: 2px;
      background: linear-gradient(90deg, transparent, #2563eb, transparent);
      transform: rotate(-15deg);
    }
    
    /* Project 2 Graphic (Healthcare - Concentric Rings) */
    .project-visual.healthcare .ring {
      position: absolute;
      border: 2px solid rgba(234, 88, 12, 0.15);
      border-radius: 50%;
      animation: rotateRing 8s linear infinite;
    }
    .project-visual.healthcare .ring-1 { width: 60px; height: 60px; border-color: rgba(234, 88, 12, 0.5); }
    .project-visual.healthcare .ring-2 { width: 100px; height: 100px; border-color: rgba(234, 88, 12, 0.3); border-style: dashed; }
    .project-visual.healthcare .center-dot {
      width: 16px;
      height: 16px;
      background: #EA580C;
      border-radius: 50%;
      box-shadow: 0 0 10px #EA580C;
    }
    
    /* Project 3 Graphic (E-Commerce - Bar charts & Analytics Nodes) */
    .project-visual.retail .bar-container {
      display: flex;
      align-items: flex-end;
      gap: 8px;
      height: 80px;
    }
    .project-visual.retail .bar {
      width: 12px;
      border-radius: var(--radius-sm) var(--radius-sm) 0 0;
      background: linear-gradient(180deg, var(--color-primary) 0%, rgba(234, 88, 12, 0.2) 100%);
    }
    .project-visual.retail .bar:nth-child(1) { height: 40px; }
    .project-visual.retail .bar:nth-child(2) { height: 75px; background: linear-gradient(180deg, var(--color-accent) 0%, rgba(234, 88, 12, 0.2) 100%); }
    .project-visual.retail .bar:nth-child(3) { height: 55px; }
    .project-visual.retail .bar:nth-child(4) { height: 90px; background: linear-gradient(180deg, #EA580C 0%, rgba(234, 88, 12, 0.2) 100%); }

    @keyframes floatGraphic {
      0% { transform: translateY(-8px) scale(0.95); }
      100% { transform: translateY(8px) scale(1.05); }
    }
    @keyframes rotateRing {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Glassmorphism Deal Request Modal */
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
        <span class="badge">Proven track record</span>
        <h2>Our Past Projects</h2>
        <p>Explore real-world case studies detailing how our custom software systems delivered key business transformations for our clients.</p>
      </div>
    </div>
  </section>

  <!-- Projects Grid -->
  <section class="projects-section" style="padding-bottom: 80px;">
    <div class="container">
      <div class="grid-3">
        <!-- Project 1: Finance -->
        <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <div class="project-visual finance"></div>
            <span class="badge" style="background: rgba(37, 99, 235, 0.1); color: #1d4ed8; border: 1px solid rgba(37, 99, 235, 0.2);">FinTech</span>
            <h3 style="margin-top: 10px; margin-bottom: 12px; font-size: 20px;">Apex Wealth Managers</h3>
            <p style="color: var(--color-text-muted); font-size: 14px; margin-bottom: 20px; line-height: 1.6;">
              Designed and deployed an automated portfolio rebalancing machine learning engine. The system evaluates real-time market shifts and updates customer investment layouts under predefined risk rules.
            </p>
          </div>
          <div style="border-top: 1px solid var(--color-border); padding-top: 16px; display: flex; flex-direction: column; gap: 12px;">
            <p style="font-size: 14px; font-weight: 600; color: var(--color-success); margin: 0;">
              <strong>Outcome:</strong> 85% reduction in manual execution overhead, 4.2% average increase in customer yield rates.
            </p>
            <button class="btn btn-primary btn-request-deal" data-project="Apex Wealth Managers" style="padding: 10px 16px; font-size: 13px;">
              Request Deal
            </button>
          </div>
        </div>

        <!-- Project 2: Healthcare -->
        <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <div class="project-visual healthcare">
              <div class="ring ring-1"></div>
              <div class="ring ring-2"></div>
              <div class="center-dot"></div>
            </div>
            <span class="badge" style="background: rgba(79, 70, 229, 0.1); color: var(--color-primary); border: 1px solid rgba(79, 70, 229, 0.2);">Healthcare</span>
            <h3 style="margin-top: 10px; margin-bottom: 12px; font-size: 20px;">Sunderland Clinical Trust</h3>
            <p style="color: var(--color-text-muted); font-size: 14px; margin-bottom: 20px; line-height: 1.6;">
              Implemented an AI-based natural language parser that automatically extracts patient metadata from clinical reports. Integrated directly with legacy NHS health records to speed up diagnostics indexing.
            </p>
          </div>
          <div style="border-top: 1px solid var(--color-border); padding-top: 16px; display: flex; flex-direction: column; gap: 12px;">
            <p style="font-size: 14px; font-weight: 600; color: var(--color-success); margin: 0;">
              <strong>Outcome:</strong> Indexing speed increased by 300%, eliminating file-handling backlogs for over 45,000 active records.
            </p>
            <button class="btn btn-primary btn-request-deal" data-project="Sunderland Clinical Trust" style="padding: 10px 16px; font-size: 13px;">
              Request Deal
            </button>
          </div>
        </div>

        <!-- Project 3: Retail/E-Commerce -->
        <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <div class="project-visual retail">
              <div class="bar-container">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
              </div>
            </div>
            <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #b45309; border: 1px solid rgba(245, 158, 11, 0.2);">E-Commerce</span>
            <h3 style="margin-top: 10px; margin-bottom: 12px; font-size: 20px;">Velo Global Retail</h3>
            <p style="color: var(--color-text-muted); font-size: 14px; margin-bottom: 20px; line-height: 1.6;">
              Engineered a predictive stock replenishing database system using recurrent neural networks. Automatically evaluates global sales trends to order products ahead of seasonal shipping delays.
            </p>
          </div>
          <div style="border-top: 1px solid var(--color-border); padding-top: 16px; display: flex; flex-direction: column; gap: 12px;">
            <p style="font-size: 14px; font-weight: 600; color: var(--color-success); margin: 0;">
              <strong>Outcome:</strong> Out-of-stock events reduced by 68%, inventory holding overhead dropped by 24% globally.
            </p>
            <button class="btn btn-primary btn-request-deal" data-project="Velo Global Retail" style="padding: 10px 16px; font-size: 13px;">
              Request Deal
            </button>
          </div>
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

  <!-- Deal Request Modal -->
  <div class="modal-backdrop" id="deal-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Request Deal</h3>
        <button class="close-modal" id="close-modal">&times;</button>
      </div>
      <div class="modal-alert success" id="modal-alert-success"></div>
      <div class="modal-alert error" id="modal-alert-error"></div>
      <form id="deal-request-form">
        <input type="hidden" name="project_name" id="modal-project-name">
        
        <div class="form-group">
          <label>Project Selected</label>
          <input type="text" id="modal-project-display" class="form-control" readonly style="background-color: var(--color-bg-main); font-weight: bold;">
        </div>

        <div class="form-group">
          <label for="client_name">Full Name <span style="color:var(--color-error)">*</span></label>
          <input type="text" name="client_name" id="client_name" class="form-control" placeholder="Enter your name" required>
        </div>

        <div class="form-group">
          <label for="client_email">Email Address <span style="color:var(--color-error)">*</span></label>
          <input type="email" name="client_email" id="client_email" class="form-control" placeholder="Enter your email" required>
        </div>

        <div class="form-group">
          <label for="client_phone">Phone Number</label>
          <input type="tel" name="client_phone" id="client_phone" class="form-control" placeholder="Enter your phone number">
        </div>

        <div class="form-group">
          <label for="company">Company Name</label>
          <input type="text" name="company" id="company" class="form-control" placeholder="Enter your company name">
        </div>

        <div class="form-group">
          <label for="message">Inquiry / Requirements details</label>
          <textarea name="message" id="message" class="form-control" rows="3" placeholder="Tell us about your deal requirements..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 10px;">Submit Deal Request</button>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="js/main.js"></script>
  <script src="js/chatbot.js"></script>

  <!-- Deal Request Modal Handler -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const dealModal = document.getElementById('deal-modal');
      const closeBtn = document.getElementById('close-modal');
      const projectDisplay = document.getElementById('modal-project-display');
      const projectNameInput = document.getElementById('modal-project-name');
      const requestForm = document.getElementById('deal-request-form');
      const alertSuccess = document.getElementById('modal-alert-success');
      const alertError = document.getElementById('modal-alert-error');

      // Open Modal
      document.querySelectorAll('.btn-request-deal').forEach(btn => {
        btn.addEventListener('click', () => {
          const project = btn.getAttribute('data-project');
          projectNameInput.value = project;
          projectDisplay.value = project;
          
          alertSuccess.style.display = 'none';
          alertError.style.display = 'none';
          requestForm.reset();
          projectNameInput.value = project; // reset clears inputs, re-set it here
          
          dealModal.style.display = 'flex';
          setTimeout(() => {
            dealModal.classList.add('show');
          }, 10);
        });
      });

      // Close Modal Function
      const closeModal = () => {
        dealModal.classList.remove('show');
        setTimeout(() => {
          dealModal.style.display = 'none';
        }, 300);
      };

      closeBtn.addEventListener('click', closeModal);
      
      // Close on backdrop click
      dealModal.addEventListener('click', (e) => {
        if (e.target === dealModal) {
          closeModal();
        }
      });

      // Handle Form Submit
      requestForm.addEventListener('submit', (e) => {
        e.preventDefault();
        alertSuccess.style.display = 'none';
        alertError.style.display = 'none';

        const formData = new FormData(requestForm);

        fetch('php/submit_deal_request.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alertSuccess.textContent = data.message;
            alertSuccess.style.display = 'block';
            requestForm.style.opacity = '0.5';
            requestForm.querySelectorAll('button, input, textarea').forEach(el => el.disabled = true);
            setTimeout(() => {
              closeModal();
              // Re-enable form after closing animation
              setTimeout(() => {
                requestForm.style.opacity = '1';
                requestForm.querySelectorAll('button, input, textarea').forEach(el => el.disabled = false);
              }, 300);
            }, 2000);
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
