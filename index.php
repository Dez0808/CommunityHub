<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website - Now with Chat</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* 
 * Lagro High School - Chat Interface Styles
 * Designed to match school branding and existing pages
 */

        /* Import Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&family=Cormorant+Garamond:wght@500;600;700&display=swap');

        /* Chat Interface Variables */
        :root {
            /* School color scheme */
            --primary: rgb(32, 91, 62);
            --primary-dark: rgb(24, 68, 47);
            --primary-light: rgba(32, 91, 62, 0.1);
            --secondary: rgb(52, 140, 81);
            --secondary-light: rgba(52, 140, 81, 0.15);
            --gold: #D4AF37;
            --gold-light: rgba(212, 175, 55, 0.15);
            --white: #ffffff;
            --gray-light: #f5f5f5;
            --gray: #636e72;
            --dark: #2d3436;
            --accent-color: #a0e4ff;
            --off-white: #f8f9fa;

            /* Typography */
            --font-heading: 'Cormorant Garamond', serif;
            --font-body: 'Poppins', sans-serif;

            /* Spacing */
            --space-xs: 0.5rem;
            --space-sm: 1rem;
            --space-md: 2rem;

            /* Transitions */
            --transition-fast: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --transition-medium: 0.5s ease;
            --box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
            --border-radius-sm: 8px;
        }

        /* Chat Interface Styles */
        #chat-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 350px;
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
            transition: var(--transition-medium);
            max-height: 500px;
            font-family: var(--font-body);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        #chat-container.closed {
            height: 0;
            visibility: hidden;
            opacity: 0;
            transform: translateY(20px);
        }

        #chat-container.minimized {
            height: 50px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        #chat-header {
            padding: 12px 15px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white);
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            border-bottom: 2px solid var(--gold);
            position: relative;
        }

        #chat-header::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-image: linear-gradient(90deg, var(--gold), transparent, var(--gold));
            opacity: 0.7;
        }

        #chat-notification {
            background-color: var(--gold);
            color: var(--dark);
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            margin-left: 10px;
            visibility: hidden;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        #chat-notification.show {
            visibility: visible;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        #chat-header-right {
            display: flex;
            align-items: center;
        }

        #chat-header-title {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            font-family: var(--font-heading);
            font-weight: 600;
            font-size: 1.2rem;
            letter-spacing: 0.5px;
        }

        .minimize-chat,
        .close-chat {
            transition: var(--transition-fast);
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .minimize-chat:hover,
        .close-chat:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        #chat-type-selector {
            background: var(--white);
            border: none;
            padding: 12px 15px;
            width: 100%;
            border-bottom: 1px solid var(--gray-light);
            font-family: var(--font-body);
            color: var(--dark);
            font-size: 0.9rem;
            outline: none;
            cursor: pointer;
            transition: var(--transition-fast);
            position: relative;
            background-image: linear-gradient(to right, var(--primary-light), transparent);
            background-size: 0 100%;
            background-repeat: no-repeat;
            background-position: left;
        }

        #chat-type-selector:focus {
            background-size: 100% 100%;
        }

        #chat-type-selector option {
            background-color: var(--white);
            color: var(--dark);
        }

        #announcement-notice {
            background-color: var(--gold-light);
            border-left: 4px solid var(--gold);
            padding: 12px 15px;
            margin-bottom: 0;
            display: none;
            font-size: 0.85rem;
            color: var(--dark);
            animation: fadeIn 0.3s ease;
        }

        #ai-assistant-notice {
            background-color: var(--secondary-light);
            border-left: 4px solid var(--secondary);
            padding: 12px 15px;
            margin-bottom: 0;
            display: none;
            font-size: 0.85rem;
            color: var(--dark);
            animation: fadeIn 0.3s ease;
        }

        #chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 350px;
            background-color: var(--off-white);
            background-image:
                radial-gradient(circle at 25px 25px, rgba(32, 91, 62, 0.03) 2%, transparent 0%),
                radial-gradient(circle at 75px 75px, rgba(212, 175, 55, 0.03) 2%, transparent 0%);
            background-size: 100px 100px;
        }

        /* Scrollbar styling */
        #chat-messages::-webkit-scrollbar {
            width: 8px;
        }

        #chat-messages::-webkit-scrollbar-track {
            background: var(--gray-light);
        }

        #chat-messages::-webkit-scrollbar-thumb {
            background-color: var(--gray);
            border-radius: 20px;
        }

        .message {
            max-width: 80%;
            padding: 12px 15px;
            border-radius: var(--border-radius-sm);
            position: relative;
            font-size: 0.95rem;
            line-height: 1.5;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: var(--transition-fast);
        }

        .message:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .message.sent {
            background-color: var(--secondary-light);
            align-self: flex-end;
            border-bottom-right-radius: 4px;
            border-right: 2px solid var(--secondary);
        }

        .message.received {
            background-color: var(--white);
            align-self: flex-start;
            border-bottom-left-radius: 4px;
            border-left: 2px solid var(--primary);
        }

        .message .time {
            font-size: 10px;
            color: var(--gray);
            margin-top: 5px;
            text-align: right;
            font-weight: 300;
        }

        .message .sender {
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 12px;
            color: var(--primary-dark);
            position: relative;
            display: inline-block;
            padding-bottom: 2px;
        }

        .message .sender::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: var(--gold);
            opacity: 0.5;
        }

        .message .message-content {
            word-break: break-word;
        }

        .message .status {
            font-style: italic;
            font-size: 9px;
            margin-right: 5px;
            color: var(--gray);
        }

        #chat-form {
            display: flex;
            padding: 12px 15px;
            border-top: 1px solid var(--gray-light);
            background-color: var(--white);
            position: relative;
        }

        #chat-form::before {
            content: '';
            position: absolute;
            top: -1px;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(to right, var(--primary-light), transparent);
            opacity: 0.3;
        }

        #chat-input {
            flex: 1;
            border: 1px solid var(--gray-light);
            border-radius: 20px;
            padding: 10px 15px;
            outline: none;
            font-family: var(--font-body);
            font-size: 0.95rem;
            color: var(--dark);
            transition: var(--transition-fast);
            background-color: var(--off-white);
        }

        #chat-input:focus {
            border-color: var(--secondary-light);
            box-shadow: 0 0 0 3px var(--secondary-light);
        }

        #chat-input::placeholder {
            color: var(--gray);
            opacity: 0.7;
        }

        #chat-submit {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-left: 10px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: var(--transition-fast);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        #chat-submit:hover:not(:disabled) {
            transform: translateY(-2px) rotate(5deg);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
        }

        #chat-submit:disabled {
            background: linear-gradient(135deg, #cccccc, #dddddd);
            cursor: not-allowed;
            box-shadow: none;
        }

        #chat-submit i {
            font-size: 16px;
            transform: translateX(1px);
        }

        #chat-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white);
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            box-shadow: var(--box-shadow);
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999;
            transition: var(--transition-fast);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        #chat-toggle::after {
            content: '';
            position: absolute;
            top: -8px;
            right: -8px;
            bottom: -8px;
            left: -8px;
            border-radius: 50%;
            border: 2px solid var(--gold);
            opacity: 0.3;
            transition: var(--transition-fast);
            animation: pulseGold 3s infinite;
        }

        @keyframes pulseGold {
            0% {
                transform: scale(1);
                opacity: 0.3;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.1;
            }

            100% {
                transform: scale(1);
                opacity: 0.3;
            }
        }

        #chat-toggle:hover {
            transform: scale(1.05) rotate(5deg);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        #chat-toggle:hover::after {
            opacity: 0.5;
            transform: scale(1.1);
        }

        #chat-toggle i {
            font-size: 24px;
        }

        /* Animation for new messages */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .new-message {
            animation: fadeIn 0.3s ease;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            #chat-container {
                width: 300px;
                right: 10px;
                bottom: 10px;
            }

            #chat-toggle {
                width: 50px;
                height: 50px;
                right: 10px;
                bottom: 10px;
            }

            #chat-toggle i {
                font-size: 20px;
            }

            #chat-header-title {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            #chat-container {
                width: calc(100% - 20px);
                right: 10px;
                max-width: none;
            }

            #chat-messages {
                max-height: 300px;
            }
        }

        /* Status indicator */
        .message .status {
            position: relative;
            padding-left: 15px;
        }

        .message .status::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--gray);
        }

        /* Gold status color for success */
        .message .status.success::before {
            background-color: var(--gold);
        }

        /* School branding stamp */
        #chat-container::after {
            content: '';
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            z-index: 0;
            opacity: 0.05;
            background-image: radial-gradient(circle, var(--gold) 20%, transparent 21%),
                radial-gradient(circle, var(--gold) 20%, transparent 21%);
            background-size: 10px 10px;
            background-position: 0 0, 5px 5px;
            pointer-events: none;
        }

        /* Extra decorative patterns */
        #chat-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.05) 25%, transparent 25%),
                linear-gradient(-45deg, rgba(255, 255, 255, 0.05) 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, rgba(255, 255, 255, 0.05) 75%),
                linear-gradient(-45deg, transparent 75%, rgba(255, 255, 255, 0.05) 75%);
            background-size: 20px 20px;
            opacity: 0.3;
            pointer-events: none;
        }

        /* Typing indicator */
        .typing-indicator {
            display: flex;
            align-items: center;
            margin-top: 5px;
        }

        .typing-indicator span {
            height: 8px;
            width: 8px;
            margin: 0 1px;
            background-color: var(--gray);
            border-radius: 50%;
            display: inline-block;
            opacity: 0.4;
        }

        .typing-indicator span:nth-child(1) {
            animation: pulse 1s infinite;
        }

        .typing-indicator span:nth-child(2) {
            animation: pulse 1s infinite 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation: pulse 1s infinite 0.4s;
        }
    </style>
</head>

<body>
    <!-- Chat Toggle Button -->
    <button id="chat-toggle">
        <i class="fas fa-comments"></i>
    </button>

    <!-- Chat Interface -->
    <div id="chat-container" class="closed">
        <div id="chat-header">
            <div id="chat-header-title">Chat</div>
            <div id="chat-header-right">
                <div id="chat-notification">0</div>
                <i class="fas fa-minus minimize-chat" style="margin-left: 10px; padding-left: 5px; padding-top: 3px;"></i>
                <i class="fas fa-times close-chat" style="margin-left: 10px; padding-left: 6.5px; padding-top: 3px;"></i>
            </div>
        </div>

        <select id="chat-type-selector">
            <option value="community">Community Chat</option>
            <option value="announcements">Announcements</option>
            <option value="ai-assistant">AI Assistant</option>
        </select>

        <div id="announcement-notice">
            Only administrators can post to the announcement channel.
        </div>

        <div id="ai-assistant-notice">
            Ask the AI Assistant any questions about Lagro High School, academics, or student life. The AI will respond with helpful information.
        </div>

        <div id="chat-messages"></div>

        <form id="chat-form">
            <input type="text" id="chat-input" placeholder="Type a message..." autocomplete="off">
            <button type="submit" id="chat-submit">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initChat();
        });

        let pollingInterval;
        let notificationCount = 0;
        let currentChatType = 'community';
        let disablePolling = false; // Flag to control polling

        function initChat() {
            // Chat toggle button
            document.getElementById('chat-toggle').addEventListener('click', toggleChat);

            // Chat header for minimizing
            document.querySelector('.minimize-chat').addEventListener('click', function(e) {
                e.stopPropagation();
                minimizeChat();
            });

            // Chat header for closing
            document.querySelector('.close-chat').addEventListener('click', function(e) {
                e.stopPropagation();
                closeChat();
            });

            // Chat header for toggling (new functionality)
            document.getElementById('chat-header').addEventListener('click', function(e) {
                // Only handle the click if it's directly on the header or title
                if (e.target === this || e.target.id === 'chat-header-title') {
                    if (document.getElementById('chat-container').classList.contains('minimized')) {
                        document.getElementById('chat-container').classList.remove('minimized');
                    } else {
                        closeChat();
                    }
                }
            });

            // Chat type selector
            document.getElementById('chat-type-selector').addEventListener('change', function() {
                currentChatType = this.value;

                // Show/hide appropriate notices
                const announcementNotice = document.getElementById('announcement-notice');
                const aiAssistantNotice = document.getElementById('ai-assistant-notice');
                
                announcementNotice.style.display = 'none';
                aiAssistantNotice.style.display = 'none';
                
                if (currentChatType === 'announcements') {
                    announcementNotice.style.display = 'block';
                } else if (currentChatType === 'ai-assistant') {
                    aiAssistantNotice.style.display = 'block';
                    
                    // Add welcome message for AI Assistant
                    document.getElementById('chat-messages').innerHTML = '';
                    const welcomeMessage = {
                        sender: 'AI Assistant',
                        message: 'Hello! I\'m your AI assistant for Lagro High School. How can I help you today?',
                        time: formatTime(new Date())
                    };
                    document.getElementById('chat-messages').innerHTML = formatMessages([welcomeMessage]);
                    scrollToBottom();
                }

                // Load appropriate messages for community or announcements
                if (currentChatType !== 'ai-assistant') {
                    document.getElementById('chat-messages').innerHTML = '';
                    loadMessages(getLoadUrl(), document.getElementById('chat-messages'));
                }
            });

            // Message form submission
            document.getElementById('chat-form').addEventListener('submit', function(e) {
                e.preventDefault();
                sendMessage();
            });

            // Load initial messages (default to community chat)
            loadMessages(getLoadUrl(), document.getElementById('chat-messages'));

            // Start polling for new messages
            startPolling();
        }

        function toggleChat() {
            const chatContainer = document.getElementById('chat-container');

            if (chatContainer.classList.contains('closed')) {
                openChat();
            } else {
                closeChat();
            }
        }

        function openChat() {
            document.getElementById('chat-container').classList.remove('closed');
            document.getElementById('chat-toggle').style.display = 'none';

            // Reset notification count
            resetNotifications();

            // Force a refresh of messages
            if (currentChatType !== 'ai-assistant') {
                loadMessages(getLoadUrl(), document.getElementById('chat-messages'));
            }
        }

        function closeChat() {
            document.getElementById('chat-container').classList.add('closed');
            document.getElementById('chat-container').classList.remove('minimized');
            document.getElementById('chat-toggle').style.display = 'flex';
        }

        function minimizeChat() {
            document.getElementById('chat-container').classList.add('minimized');
        }

        function getLoadUrl() {
            if (currentChatType === 'community') return 'load_messages.php';
            if (currentChatType === 'announcements') return 'load_messages2.php';
            return null; // AI Assistant doesn't load from server
        }

        function getSendUrl() {
            if (currentChatType === 'community') return 'send_message.php';
            if (currentChatType === 'announcements') return 'send_message2.php';
            if (currentChatType === 'ai-assistant') return 'chat_ai.php';
            return null;
        }

        function loadMessages(url, container) {
            if (!url) return; // Skip for AI Assistant
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = formatMessages(data);
                    scrollToBottom();
                })
                .catch(error => {
                    console.error('Error loading messages:', error);
                    container.innerHTML = '<div class="message received"><div class="sender">System</div>Failed to load messages. Please try again.</div>';
                });
        }

        function sendMessage() {
            const input = document.getElementById('chat-input');
            const message = input.value.trim();

            if (message === '') return;

            // IMPORTANT: Disable polling to prevent duplicates
            disablePolling = true;
            clearInterval(pollingInterval);

            // Generate a temporary ID for the message
            const tempMessageId = 'temp-' + Date.now();
            const username = 'DemoUser'; // Replace with actual username from session if available

            // Add message to the UI immediately with pending status
            const messagesContainer = document.getElementById('chat-messages');
            const messageElement = document.createElement('div');
            messageElement.id = tempMessageId;
            messageElement.className = 'message sent new-message';
            messageElement.innerHTML = `
    <div class="message-content">${escapeHtml(message)}</div>
    <div class="time">
        <span class="status">Sending...</span>
        ${formatTime(new Date())}
    </div>
`;
            messagesContainer.appendChild(messageElement);
            scrollToBottom();

            // Clear input field
            input.value = '';

            // Prepare form data
            const formData = new FormData();
            formData.append('message', message);
            formData.append('username', username);

            // For AI Assistant, show typing indicator
            if (currentChatType === 'ai-assistant') {
                showTypingIndicator();
            }

            // Send to server
            fetch(getSendUrl(), {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Update the temporary message with success status
                    const tempMessage = document.getElementById(tempMessageId);
                    if (tempMessage) {
                        tempMessage.querySelector('.status').textContent = 'Sent';
                        tempMessage.querySelector('.status').classList.add('success');
                    }

                    // For AI Assistant, handle the response
                    if (currentChatType === 'ai-assistant') {
                        // Remove typing indicator
                        removeTypingIndicator();
                        
                        // Add AI response
                        if (data.status === 'success') {
                            const aiMessage = document.createElement('div');
                            aiMessage.className = 'message received new-message';
                            aiMessage.innerHTML = `
                                <div class="sender">${data.sender || 'AI Assistant'}</div>
                                <div class="message-content">${escapeHtml(data.message)}</div>
                                <div class="time">${data.time || formatTime(new Date())}</div>
                            `;
                            messagesContainer.appendChild(aiMessage);
                            scrollToBottom();
                        }
                    }
                    // For announcement channel, check if user is allowed to post
                    else if (currentChatType === 'announcements' && data.status === 'error') {
                        // Add a system message explaining the error
                        const messageElement = document.createElement('div');
                        messageElement.className = 'message received new-message';
                        messageElement.innerHTML = `
            <div class="sender">System</div>
            <div class="message-content">${data.message || "Only administrators can post to the announcement channel."}</div>
            <div class="time">${formatTime(new Date())}</div>
        `;
                        messagesContainer.appendChild(messageElement);

                        // Remove the pending message
                        if (tempMessage) {
                            tempMessage.remove();
                        }
                    }

                    // Resume polling after a significant delay (5 seconds)
                    setTimeout(() => {
                        disablePolling = false;
                        startPolling();
                    }, 5000);
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                    
                    // Remove typing indicator if AI Assistant
                    if (currentChatType === 'ai-assistant') {
                        removeTypingIndicator();
                    }
                    
                    const tempMessage = document.getElementById(tempMessageId);
                    if (tempMessage) {
                        tempMessage.querySelector('.status').textContent = 'Failed to send';
                    }

                    // Resume polling if there was an error
                    disablePolling = false;
                    startPolling();
                });
        }

        function formatMessages(data) {
            let html = '';

            for (let i = 0; i < data.length; i++) {
                const message = data[i];
                const isCurrentUser = message.sender === 'DemoUser'; // Replace with actual username check
                const messageClass = isCurrentUser ? 'sent' : 'received';

                html += `
        <div class="message ${messageClass}">
            ${!isCurrentUser ? `<div class="sender">${escapeHtml(message.sender)}</div>` : ''}
            <div class="message-content">${escapeHtml(message.message)}</div>
            <div class="time">${message.time}</div>
        </div>
    `;
            }

            return html;
        }

        function showTypingIndicator() {
            const messagesContainer = document.getElementById('chat-messages');
            const typingElement = document.createElement('div');
            typingElement.className = 'message received';
            typingElement.id = 'typing-indicator';
            
            const senderElement = document.createElement('div');
            senderElement.className = 'sender';
            senderElement.textContent = 'AI Assistant';
            
            const typingContent = document.createElement('div');
            typingContent.className = 'typing-indicator';
            typingContent.innerHTML = '<span></span><span></span><span></span>';
            
            typingElement.appendChild(senderElement);
            typingElement.appendChild(typingContent);
            messagesContainer.appendChild(typingElement);
            scrollToBottom();
        }

        function removeTypingIndicator() {
            const typingIndicator = document.getElementById('typing-indicator');
            if (typingIndicator) {
                typingIndicator.remove();
            }
        }

        function startPolling() {
            // Only start if not disabled and not in AI Assistant mode
            if (!disablePolling && currentChatType !== 'ai-assistant') {
                // Clear any existing interval first
                clearInterval(pollingInterval);

                // Check for new messages every 5 seconds
                pollingInterval = setInterval(() => {
                    if (!disablePolling) {
                        checkForNewMessages();
                    }
                }, 5000);
            }
        }

        function checkForNewMessages() {
            // Skip if polling is disabled or in AI Assistant mode
            if (disablePolling || currentChatType === 'ai-assistant') return;

            fetch(getLoadUrl())
                .then(response => response.json())
                .then(data => {
                    // We only care about notifications when the chat is closed or minimized
                    // The full message list is loaded when the chat is opened
                    if (document.getElementById('chat-container').classList.contains('closed') ||
                        document.getElementById('chat-container').classList.contains('minimized')) {

                        // Check if there are new messages (simplified approach)
                        const currentCount = document.querySelectorAll('#chat-messages .message').length;
                        if (data.length > currentCount) {
                            incrementNotification(data.length - currentCount);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error checking for new messages:', error);
                });
        }

        function incrementNotification(count = 1) {
            notificationCount += count;
            const notification = document.getElementById('chat-notification');
            notification.textContent = notificationCount;
            notification.classList.add('show');
        }

        function resetNotifications() {
            notificationCount = 0;
            const notification = document.getElementById('chat-notification');
            notification.textContent = '0';
            notification.classList.remove('show');
        }

        function scrollToBottom() {
            const messagesContainer = document.getElementById('chat-messages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // New function to format time in UTC+8
        function formatTime(date) {
            // Convert to UTC+8
            const utc8Date = new Date(date.getTime() + (8 * 60 * 60 * 1000));

            return utc8Date.toLocaleString('en-US', {
                month: 'numeric',
                day: 'numeric',
                year: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                hour12: true,
                timeZone: 'UTC'
            });
        }
    </script>
</body>

</html>