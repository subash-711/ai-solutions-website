/**
 * chatbot.js
 * Rule-based clientside AI Chatbot simulation for the AI-Solutions website.
 * Contains responses with 800ms delay and a typing indicator.
 */

document.addEventListener('DOMContentLoaded', () => {
  const bubble = document.getElementById('chatbot-bubble');
  const windowEl = document.getElementById('chatbot-window');
  const closeBtn = document.getElementById('chatbot-close');
  const clearBtn = document.getElementById('chatbot-clear');
  const sendBtn = document.getElementById('chatbot-send');
  const inputEl = document.getElementById('chatbot-input');
  const bodyEl = document.getElementById('chatbot-body');

  // Seed initial greeting if chat is empty
  if (bodyEl.children.length === 0) {
    appendMessage("Hello! Welcome to AI-Solutions. How can I help you today?", "bot");
  }

  // Toggle Chatbot Window Open/Close
  bubble.addEventListener('click', () => {
    windowEl.classList.toggle('active');
    scrollToBottom();
    if (windowEl.classList.contains('active')) {
      inputEl.focus();
    }
  });

  // Close Window Action
  closeBtn.addEventListener('click', (e) => {
    e.stopPropagation(); // Prevent bubbling up to window/body click
    windowEl.classList.remove('active');
  });

  // Clear Chat Action
  clearBtn.addEventListener('click', () => {
    bodyEl.innerHTML = '';
    appendMessage("Chat cleared. Hello! How can I help you today?", "bot");
    scrollToBottom();
    inputEl.focus();
  });

  // Send Message Action (button click)
  sendBtn.addEventListener('click', handleUserSubmit);

  // Send Message Action (Enter key)
  inputEl.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      handleUserSubmit();
    }
  });

  /**
   * Processes the user's message, displays it, triggers the typing delay,
   * and renders the chatbot response.
   */
  function handleUserSubmit() {
    const messageText = inputEl.value.trim();
    if (messageText === '') return;

    // 1. Append user message
    appendMessage(messageText, 'user');
    inputEl.value = '';
    scrollToBottom();

    // 2. Display Typing Indicator
    const typingEl = appendTypingIndicator();
    scrollToBottom();

    // 3. POST user message to php/chat.php via Fetch API
    const formData = new FormData();
    formData.append('message', messageText);

    fetch('php/chat.php', {
      method: 'POST',
      body: formData
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      removeTypingIndicator(typingEl);
      const reply = data.reply || "I'm sorry, I encountered an issue processing your request. Please try again.";
      appendMessage(reply, 'bot');
      scrollToBottom();
    })
    .catch(error => {
      console.error('Chat error:', error);
      removeTypingIndicator(typingEl);
      appendMessage("I'm sorry, I'm having trouble connecting to my service. Please check your network connection.", 'bot');
      scrollToBottom();
    });
  }

  /**
   * Appends a text message bubble into the chat body.
   */
  function appendMessage(text, sender) {
    const msgDiv = document.createElement('div');
    msgDiv.classList.add('chat-msg', sender === 'user' ? 'chat-msg-user' : 'chat-msg-bot');
    msgDiv.textContent = text;
    bodyEl.appendChild(msgDiv);
  }

  /**
   * Renders the typing indicator element and appends it to the chat container.
   */
  function appendTypingIndicator() {
    const typingDiv = document.createElement('div');
    typingDiv.className = 'typing-indicator chat-msg-bot';
    typingDiv.style.alignSelf = 'flex-start';
    typingDiv.innerHTML = `
      <div class="typing-dot" style="display:inline-block;"></div>
      <div class="typing-dot" style="display:inline-block; margin-left: 2px;"></div>
      <div class="typing-dot" style="display:inline-block; margin-left: 2px;"></div>
    `;
    bodyEl.appendChild(typingDiv);
    return typingDiv;
  }

  /**
   * Safely deletes the typing element from the view.
   */
  function removeTypingIndicator(element) {
    if (element && element.parentNode) {
      element.parentNode.removeChild(element);
    }
  }

  /**
   * Scrolls the chat dialogue box to the bottom to display the latest message.
   */
  function scrollToBottom() {
    bodyEl.scrollTop = bodyEl.scrollHeight;
  }
});
