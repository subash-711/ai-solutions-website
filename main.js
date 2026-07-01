/**
 * main.js
 * Primary front-end script for AI-Solutions.
 * Handles mobile hamburger menus, active link states, client validations, and AJAX form posts.
 */

document.addEventListener('DOMContentLoaded', () => {
  
  // --- 1. Responsive Hamburger Navigation ---
  const hamburger = document.getElementById('hamburger');
  const navMenu = document.getElementById('nav-menu');

  if (hamburger && navMenu) {
    // Toggle menu state on button click
    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('active');
      navMenu.classList.toggle('active');
    });

    // Close menu if a nav link is clicked
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
      });
    });
  }

  // --- 2. Active Link Highlighting ---
  // Parse filename from path (fallback to index.php if empty)
  let currentPath = window.location.pathname.split('/').pop();
  if (currentPath === '' || currentPath === 'admin') {
    currentPath = 'index.php';
  } else if (currentPath === 'dashboard.php' || currentPath === 'login.php') {
    // If inside admin dashboard, don't try matching standard header links
    currentPath = 'admin';
  }

  const navLinks = document.querySelectorAll('.nav-link');
  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    // Check if link matches the current script file
    if (href === currentPath || (currentPath === 'index.php' && href === './') || (currentPath === 'index.php' && href === '')) {
      link.classList.add('active');
    } else {
      link.classList.remove('active');
    }
  });

  // --- 3. Contact Us Form client-side validation & AJAX Submit ---
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
      e.preventDefault(); // Stop standard form redirect page reload

      const successAlert = document.getElementById('success-alert');
      const errorAlert = document.getElementById('error-alert');

      // Clear alerts from view
      successAlert.style.display = 'none';
      errorAlert.style.display = 'none';

      // Fetch input elements
      const nameField = document.getElementById('name');
      const emailField = document.getElementById('email');
      const phoneField = document.getElementById('phone');

      const name = nameField.value.trim();
      const email = emailField.value.trim();
      const phone = phoneField.value.trim();

      // Form validation rules
      let isValid = true;
      let errorMsg = '';

      if (name === '') {
        isValid = false;
        errorMsg = 'Full Name is required.';
        nameField.focus();
      } else if (email === '') {
        isValid = false;
        errorMsg = 'Email Address is required.';
        emailField.focus();
      } else if (!validateEmailFormat(email)) {
        isValid = false;
        errorMsg = 'Please enter a valid email address structure (e.g. name@example.com).';
        emailField.focus();
      } else if (phone !== '' && !validatePhoneFormat(phone)) {
        isValid = false;
        errorMsg = 'Invalid phone number format. Use numbers, spaces, or characters like +-().';
        phoneField.focus();
      }

      if (!isValid) {
        // Show validation error
        errorAlert.textContent = errorMsg;
        errorAlert.style.display = 'block';
        return;
      }

      // Prepare payload
      const formData = new FormData(contactForm);

      // Perform non-reloading POST via AJAX
      fetch('php/submit_inquiry.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Server response returned an error code.');
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          // Success state
          successAlert.textContent = data.message;
          successAlert.style.display = 'block';
          contactForm.reset(); // Reset form elements
        } else {
          // Failure response state
          errorAlert.textContent = data.message;
          errorAlert.style.display = 'block';
        }
      })
      .catch(error => {
        // Networking error
        errorAlert.textContent = 'An error occurred during submission. Please try again later.';
        errorAlert.style.display = 'block';
        console.error('AJAX Error:', error);
      });
    });
  }

  // Helper validation regex: Email
  function validateEmailFormat(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  }

  // Helper validation regex: Phone numbers between 7-20 digits
  function validatePhoneFormat(phone) {
    const re = /^[0-9\-\+\s\(\)]{7,20}$/;
    return re.test(phone);
  }

  // --- 4. Light/Dark Theme Switcher ---
  const currentTheme = localStorage.getItem('theme') || 'light';
  document.documentElement.setAttribute('data-theme', currentTheme);

  const themeToggleBtns = document.querySelectorAll('.theme-toggle');
  updateToggleButtons(currentTheme);

  themeToggleBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      let theme = document.documentElement.getAttribute('data-theme');
      if (theme === 'dark') {
        theme = 'light';
      } else {
        theme = 'dark';
      }
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
