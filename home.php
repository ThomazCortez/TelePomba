<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat | TelePomba</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
    --primary-color: #2a6b5f;  /* Deep forest green */
    --secondary-color: #e9f5ec;  /* Soft pastel green */
    --accent-color: #ff6b6b;  /* Warm contrast color */
    --text-color: #2e3d30;  /* Dark green-gray */
    --light-text: #ffffff;
    --light-gray-bg: #f4faf6;  /* Lighter green-gray */
}

        .chat-container {
            height: 100vh;
        }
        
        .contacts-list {
            height: calc(100vh - 120px);
            overflow-y: auto;
        }
        
        .chat-messages {
            height: calc(100vh - 180px);
            overflow-y: auto;
        }
        
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .conversation-item:hover {
    background-color: var(--light-gray-bg) !important;
    cursor: pointer;
}

        .conversation-item.bg-light {
            background-color: var(--secondary-color) !important;
        }

        .loading-messages {
            text-align: center;
            padding: 20px;
            color: var(--text-color);
        }

        .unread-badge {
            width: 10px;
            height: 10px;
            background-color: var(--accent-color);
        }

        /* Custom overrides */
        body {
    background-color: var(--secondary-color);
    color: var(--text-color);
    font-family: 'Inter', sans-serif;
}
.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #23574d;
    border-color: #23574d;
}

        .bg-light {
    background-color: var(--light-gray-bg) !important;
}

        #chatHeader,
        #messageInputContainer {
            background-color: var(--light-text);
        }

        .message-bubble {
    background-color: var(--primary-color);
    color: var(--light-text);
    border-radius: 1rem;
    padding: 0.5rem 1rem;
    max-width: 80%;
}

        .animate-delay-1 {
            animation-delay: 0.1s;
        }

        #chatHeader, #messageInputContainer {
    background-color: var(--secondary-color);
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.message-bubble.received {
    background-color: var(--light-gray-bg);
    color: var(--text-color);
}

.input-group input {
    border-radius: 20px;
    border: 1px solid rgba(0, 0, 0, 0.1);
    padding: 10px;
}

.input-group .btn {
    border-radius: 20px;
}

.conversation-item {
    transition: background 0.3s ease;
    padding: 10px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.typing-dots {
    display: inline-flex;
    align-items: center;
}

.typing-dots span {
    font-size: 1.5rem;
    line-height: 1;
    margin-right: 2px;
    animation: typing-dot 1.4s infinite;
    opacity: 0.4;
}

.typing-dots span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dots span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing-dot {
    0%, 60%, 100% {
        opacity: 0.4;
        transform: translateY(0);
    }
    30% {
        opacity: 1;
        transform: translateY(-5px);
    }
}

    </style>
</head>
<body>
    <div class="container-fluid chat-container">
        <div class="row">
            <!-- Sidebar with animation -->
            <div class="col-md-4 bg-light p-0 border-end animate__animated animate__slideInLeft">
                <div class="d-flex flex-column h-100">
                    <div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <?php if (!empty($_SESSION['imagem_perfil'])): ?>
                                    <img src="<?= htmlspecialchars($_SESSION['imagem_perfil']) ?>" alt="Profile" class="profile-img me-2">
                                <?php else: ?>
                                    <img src="ficheiros/dashboard/dashboard/uploads/default_imagem_perfil.jpg" alt="Profile" class="profile-img me-2">
                                <?php endif; ?>
                                <span><?= htmlspecialchars($_SESSION['username']) ?></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#" id="logoutBtn">Logout</a></li>
                                <li><a class="dropdown-item" href="ficheiros/dashboard/dashboard/perfil.php">Settings</a></li>
                            </ul>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-primary" id="newChatBtn">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="p-2 bg-light border-bottom animate__animated animate__fadeIn">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search or start new chat">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Conversations list -->
                    <div class="contacts-list" id="conversationsList">
                        <!-- Conversations will be loaded here via AJAX -->
                    </div>
                </div>
            </div>
            
            <!-- Chat area with animation -->
            <div class="col-md-8 p-0 d-flex flex-column animate__animated animate__fadeIn">
                <!-- Chat header -->
                <div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center" id="chatHeader">
                    <div class="text-center w-100">
                        <h5 class="m-0">Select a chat to start messaging</h5>
                    </div>
                </div>
                
                <!-- Messages area -->
                <div class="flex-grow-1 p-3 bg-light chat-messages" id="messagesContainer">
                    <!-- Messages will be displayed here -->
                </div>
                
                <!-- Message input -->
                <div class="p-3 bg-white border-top" id="messageInputContainer" style="display: none;">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Type a message" id="messageInput">
                        <button class="btn btn-primary" type="button" id="sendMessageBtn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- New Chat Modal with animation -->
    <div class="modal fade" id="newChatModal" tabindex="-1">
        <div class="modal-dialog animate__animated animate__zoomIn">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Chat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select chat type</label>
                        <select class="form-select" id="chatType">
                            <option value="private">Private Chat</option>
                            <option value="group">Group Chat</option>
                        </select>
                    </div>
                    <div class="mb-3" id="participantsContainer">
                        <label class="form-label">Add participants (type name and press enter)</label>
                        <input type="text" class="form-control" id="addParticipantInput">
                        <div class="mt-2" id="participantsList"></div>
                    </div>
                    <div class="mb-3" id="groupNameContainer" style="display: none;">
                        <label class="form-label">Group name</label>
                        <input type="text" class="form-control" id="groupNameInput">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="createChatBtn">Create</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.0.1/socket.io.js"></script>
    <script>

        const socket = io('http://localhost:3000', {
            reconnection: true,
            reconnectionAttempts: 5,
            reconnectionDelay: 1000,
            transports: ['websocket', 'polling']
        });

        socket.on('connect', () => {
            console.log('Connected to Socket.io server');
        });

        socket.on('connect_error', (err) => {
            console.error('Connection error:', err);
        });

        socket.on('disconnect', (reason) => {
            console.log('Disconnected:', reason);
        });

        const currentUserId = <?= $_SESSION['user_id'] ?>;
        let activeConversationId = null;
        
        socket.emit('authenticate', currentUserId);
        
        function loadConversations() {
            fetch('includes/get_conversations.php')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('conversationsList').innerHTML = html;
                    // Add animations to conversation items
                    document.querySelectorAll('.conversation-item').forEach((item, index) => {
                        item.classList.add('animate__animated', 'animate__fadeInUp');
                        item.style.animationDelay = `${index * 0.1}s`;
                    });
                });
        }

        document.getElementById('sendMessageBtn').addEventListener('click', () => {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            
            if (message && activeConversationId) {
                messageInput.value = '';
                socket.emit('sendMessage', {
                    conversationId: activeConversationId,
                    senderId: currentUserId,
                    content: message
                });
            } else if (!activeConversationId) {
                alert('Please select a conversation first');
            }
        });

        document.getElementById('messageInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                document.getElementById('sendMessageBtn').click();
            }
        });

        document.getElementById('newChatBtn').addEventListener('click', () => {
            new bootstrap.Modal(document.getElementById('newChatModal')).show();
        });

        document.getElementById('chatType').addEventListener('change', (e) => {
            document.getElementById('groupNameContainer').style.display = 
                e.target.value === 'group' ? 'block' : 'none';
        });

        const participants = new Set();
        document.getElementById('addParticipantInput').addEventListener('keypress', async (e) => {
            if (e.key === 'Enter') {
                const input = e.target;
                const username = input.value.trim();
                
                if (username) {
                    try {
                        const response = await fetch(`includes/search_user.php?username=${username}`);
                        const user = await response.json();
                        
                        if (user && !participants.has(user.id_utilizador)) {
                            participants.add(user.id_utilizador);
                            const participantElement = document.createElement('div');
                            participantElement.className = 'badge bg-primary me-1 mb-1';
                            participantElement.innerHTML = `
                                ${user.nome_utilizador}
                                <button type="button" class="ms-1 btn-close btn-close-white" 
                                    data-user-id="${user.id_utilizador}"></button>
                            `;
                            document.getElementById('participantsList').appendChild(participantElement);
                            input.value = '';
                        }
                    } catch (error) {
                        console.error('Error searching user:', error);
                    }
                }
            }
        });

        document.getElementById('participantsList').addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-close')) {
                const userId = e.target.dataset.userId;
                participants.delete(parseInt(userId));
                e.target.parentElement.remove();
            }
        });

        document.getElementById('createChatBtn').addEventListener('click', async () => {
            const chatType = document.getElementById('chatType').value;
            const groupName = chatType === 'group' ? document.getElementById('groupNameInput').value.trim() : null;
            
            if (chatType === 'group' && !groupName) {
                alert('Please enter a group name');
                return;
            }
            
            if (participants.size === 0) {
                alert('Please add at least one participant');
                return;
            }
            
            try {
                const response = await fetch('includes/create_conversation.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        type: chatType,
                        participants: Array.from(participants),
                        groupName: groupName
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    loadConversations();
                    bootstrap.Modal.getInstance(document.getElementById('newChatModal')).hide();
                } else {
                    alert(result.message || 'Error creating conversation');
                }
            } catch (error) {
                console.error('Error creating conversation:', error);
                alert('Error creating conversation');
            }
        });

        socket.on('newMessage', (data) => {
            console.log('Received message:', data);
            if (activeConversationId === data.conversationId) {
                const isCurrentUser = data.senderId == currentUserId;
                const messageElement = createMessageElement(data, isCurrentUser);
                document.getElementById('messagesContainer').appendChild(messageElement);
                
                const messagesContainer = document.getElementById('messagesContainer');
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        });

        document.getElementById('logoutBtn').addEventListener('click', (e) => {
            e.preventDefault();
            const confirmLogout = confirm('Are you sure you want to logout?');
            if (confirmLogout) {
                window.location.href = 'utilizador/logout.php';
            }
        });

        socket.on('forceLogout', () => {
            alert('You have been logged out from another device.');
            window.location.href = 'includes/logout_process.php';
        });

        function loadMessages(conversationId) {
            document.getElementById('messagesContainer').innerHTML = 'Loading...';
            
            fetch(`includes/get_messages.php?conversation_id=${conversationId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('messagesContainer').innerHTML = html;
                    document.getElementById('messageInputContainer').style.display = 'block';
                    document.getElementById('messageInputContainer').classList.add('animate__animated', 'animate__slideInUp');
                    
                    const messagesContainer = document.getElementById('messagesContainer');
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    
                    if (activeConversationId) {
                        socket.emit('leaveConversation', activeConversationId);
                    }
                    
                    socket.emit('joinConversation', conversationId);
                    activeConversationId = conversationId;
                    document.getElementById('chatHeader').dataset.conversationId = conversationId;
                });
        }

        document.getElementById('conversationsList').addEventListener('click', (e) => {
            const conversationItem = e.target.closest('.conversation-item');
            if (conversationItem) {
                const conversationId = conversationItem.dataset.conversationId;
                loadMessages(conversationId);
                
                document.querySelectorAll('.conversation-item').forEach(item => {
                    item.classList.remove('bg-light');
                });
                conversationItem.classList.add('bg-light');
            }
        });

        function createMessageElement(data, isCurrentUser) {
            const messageElement = document.createElement('div');
            messageElement.className = `mb-3 ${isCurrentUser ? 'text-end' : 'text-start'} animate__animated animate__fadeIn`;
            
            messageElement.innerHTML = `
                <div class="d-flex ${isCurrentUser ? 'justify-content-end' : 'justify-content-start'}">
                    ${!isCurrentUser ? `
                        <img src="${data.senderImage}" alt="${data.senderName}" class="profile-img me-2">
                    ` : ''}
                    <div>
                        ${!isCurrentUser ? `<div class="fw-bold">${data.senderName}</div>` : ''}
                        <div class="p-2 rounded ${isCurrentUser ? 'bg-primary text-white' : 'bg-white'}">
                            ${data.content}
                        </div>
                        <small class="text-muted">${new Date(data.timestamp).toLocaleTimeString()}</small>
                    </div>
                    ${isCurrentUser ? `
                        <img src="<?= $_SESSION['imagem_perfil'] ?>" alt="You" class="profile-img ms-2">
                    ` : ''}
                </div>
            `;
            
            return messageElement;
        }

        
        let typingTimer;
const TYPING_TIMEOUT = 2000; // 2 seconds of inactivity

document.getElementById('messageInput').addEventListener('input', (e) => {
    const message = e.target.value.trim();
    
    // Only emit typing events if there's an active conversation and some text
    if (activeConversationId && message) {
        // Emit start typing event
        socket.emit('startTyping', {
            conversationId: activeConversationId,
            userId: currentUserId
        });

        // Clear previous timer
        clearTimeout(typingTimer);

        // Set a new timer to stop typing after inactivity
        typingTimer = setTimeout(() => {
            socket.emit('stopTyping', {
                conversationId: activeConversationId,
                userId: currentUserId
            });
        }, TYPING_TIMEOUT);
    }
});

// Stop typing when message is sent
document.getElementById('sendMessageBtn').addEventListener('click', () => {
    clearTypingState();
});

document.getElementById('messageInput').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        clearTypingState();
    }
});

function clearTypingState() {
    if (activeConversationId) {
        clearTimeout(typingTimer);
        socket.emit('stopTyping', {
            conversationId: activeConversationId,
            userId: currentUserId
        });
    }
}

// Typing indicator handling
socket.on('userStartedTyping', (data) => {
    console.log('Received userStartedTyping:', data);
    
    // Ensure the event is for the current active conversation
    if (data.conversationId == activeConversationId) {
        // Only show if it's not the current user
        if (data.userId != currentUserId) {
            showTypingIndicator(data.userId);
        }
    }
});

socket.on('userStoppedTyping', (data) => {
    console.log('Received userStoppedTyping:', data);
    
    // Ensure the event is for the current active conversation
    if (data.conversationId == activeConversationId) {
        // Only hide if it's not the current user
        if (data.userId != currentUserId) {
            hideTypingIndicator(data.userId);
        }
    }
});

function showTypingIndicator(userId) {
    // Remove any existing typing indicator
    removeTypingIndicator();

    // Try to fetch user info to get profile picture
    fetch(`includes/get_user_details.php?user_id=${userId}`)
        .then(response => response.json())
        .then(user => {
            const messagesContainer = document.getElementById('messagesContainer');
            
            const indicatorHtml = `
                <div id="typingIndicator" class="mb-3 text-start animate__animated animate__fadeIn">
                    <div class="d-flex justify-content-start">
                        <img src="${user.imagem_perfil}" alt="${user.nome_utilizador}" class="profile-img me-2">
                        <div>
                            <div class="fw-bold">${user.nome_utilizador}</div>
                            <div class="p-2 rounded bg-light text-muted">
                                <div class="typing-dots">
                                    <span>.</span>
                                    <span>.</span>
                                    <span>.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            if (messagesContainer) {
                messagesContainer.insertAdjacentHTML('beforeend', indicatorHtml);
                
                // Auto-scroll to bottom
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        })
        .catch(error => {
            console.error('Error fetching user details:', error);
        });
}

function hideTypingIndicator() {
    removeTypingIndicator();
}

function removeTypingIndicator() {
    const existingIndicator = document.getElementById('typingIndicator');
    if (existingIndicator) {
        existingIndicator.remove();
    }
}


        loadConversations();
    </script>
</body>
</html>