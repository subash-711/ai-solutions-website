<?php
/**
 * admin/login.php
 * Authentication interface for administration dashboard access.
 */
session_start();

// If already authenticated, redirect straight to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

// Retrieve any login errors registered in session
$error_message = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';
// Clear error message from session immediately so it does not persist on reload
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - AI-Solutions</title>
  <link rel="stylesheet" href="../css/style.css">
  <script>
    (function() {
      const theme = localStorage.getItem('theme') || 'light';
      document.documentElement.setAttribute('data-theme', theme);
    })();
  </script>
</head>
<body class="admin-body">

  <div class="card login-card">
    <div class="login-logo">
      <h2><span>AI</span>-Solutions</h2>
      <p style="margin-bottom: 16px;">Administrative Portal Login</p>
      <button type="button" class="theme-toggle" aria-label="Toggle theme">🌙 Dark Mode</button>
    </div>

    <!-- Error Alerts -->
    <?php if (!empty($error_message)): ?>
      <div class="alert alert-error" style="display: block; margin-bottom: 20px;">
        <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <form action="../php/admin_auth.php" method="POST">
      
      <!-- Username Field -->
      <div class="form-group">
        <label for="username">Admin Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="e.g. admin" required autofocus>
      </div>

      <!-- Password Field -->
      <div class="form-group" style="margin-bottom: 24px;">
        <label for="password">Security Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
      </div>

      <!-- Submit Login Button -->
      <button type="submit" class="btn btn-primary" style="width: 100%; border-radius: var(--radius-sm);">
        Secure Log In
      </button>

      <!-- Back to site Link -->
      <div style="text-align: center; margin-top: 20px;">
        <a href="../index.php" style="font-size: 13px; color: var(--color-text-muted); text-decoration: underline;">
          ← Back to Public Website
        </a>
      </div>

    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
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
  </script>
</body>
</html>
