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
    <title>WhatsApp Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
            background-color: #f8f9fa !important;
        }

        .conversation-item.bg-light {
            background-color: #e9ecef !important;
        }

        /* Loading indicator */
        .loading-messages {
            text-align: center;
            padding: 20px;
            color: #6c757d;
        }

        /* Unread message indicator */
        .unread-badge {
            width: 10px;
            height: 10px;
        }
        /* Add to existing styles */
#typingIndicator {
    height: 20px;
    font-style: italic;
    transition: opacity 0.3s ease;
}

.typing-dots {
    display: inline-flex;
    align-items: center;
}

.typing-dots span {
    width: 5px;
    height: 5px;
    margin: 0 2px;
    background-color: #6c757d;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-dots span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dots span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}
    </style>
</head>
<body>
    <div class="container-fluid chat-container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-4 bg-light p-0 border-end">
                <div class="d-flex flex-column h-100">
                    <!-- User profile header -->
                    <div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <img src="<?= $_SESSION['profile_image'] ?>" alt="Profile" class="profile-img me-2">
                                <span><?= $_SESSION['username'] ?></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#" id="logoutBtn">Logout</a></li>
                                <li><a class="dropdown-item" href="#">Settings</a></li>
                            </ul>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary" id="newChatBtn">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Search -->
                    <div class="p-2 bg-light border-bottom">
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
            
            <!-- Chat area -->
            <div class="col-md-8 p-0 d-flex flex-column">
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
    
    <!-- New Chat Modal -->
    <div class="modal fade" id="newChatModal" tabindex="-1">
        <div class="modal-dialog">
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
        const socket = io('http://localhost:3000', { // Match your Node server port
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
        // Add this at the top of your script with other variable declarations
        let activeConversationId = null;
        
        // Authenticate with socket server
        socket.emit('authenticate', currentUserId);
        
        // Load conversations
        function loadConversations() {
            fetch('includes/get_conversations.php')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('conversationsList').innerHTML = html;
                });
        }
        
        
        
        // Update your existing send message code
        document.getElementById('sendMessageBtn').addEventListener('click', () => {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (message && activeConversationId) {
        // Clear input immediately
        messageInput.value = '';
        
        // Send to server
        socket.emit('sendMessage', {
            conversationId: activeConversationId,
            senderId: currentUserId,
            content: message
        });
        
        // Don't add to UI here - wait for server confirmation
    } else if (!activeConversationId) {
        alert('Please select a conversation first');
    }
});
        
        // Also send message on Enter key
        document.getElementById('messageInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                document.getElementById('sendMessageBtn').click();
            }
        });
        
        // New chat modal
        document.getElementById('newChatBtn').addEventListener('click', () => {
            const modal = new bootstrap.Modal(document.getElementById('newChatModal'));
            modal.show();
        });
        
        // Toggle group name field based on chat type
        document.getElementById('chatType').addEventListener('change', (e) => {
            const groupNameContainer = document.getElementById('groupNameContainer');
            groupNameContainer.style.display = e.target.value === 'group' ? 'block' : 'none';
        });
        
        // Handle adding participants
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
        
        // Remove participant
        document.getElementById('participantsList').addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-close')) {
                const userId = e.target.dataset.userId;
                participants.delete(parseInt(userId));
                e.target.parentElement.remove();
            }
        });
        
        // Create new chat
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
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        type: chatType,
                        participants: Array.from(participants),
                        groupName: groupName
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    loadConversations();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('newChatModal'));
                    modal.hide();
                } else {
                    alert(result.message || 'Error creating conversation');
                }
            } catch (error) {
                console.error('Error creating conversation:', error);
                alert('Error creating conversation');
            }
        });
        
        // Listen for new messages
        socket.on('newMessage', (data) => {
    console.log('Received message:', data);
    if (activeConversationId === data.conversationId) {
        const isCurrentUser = data.senderId == currentUserId;
        const messageElement = createMessageElement(data, isCurrentUser);
        document.getElementById('messagesContainer').appendChild(messageElement);
        
        // Scroll to bottom
        const messagesContainer = document.getElementById('messagesContainer');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
});

    

        // Add this to your existing script
        document.getElementById('logoutBtn').addEventListener('click', (e) => {
            e.preventDefault();
            // Open logout confirmation modal or directly logout
            const confirmLogout = confirm('Are you sure you want to logout?');
            if (confirmLogout) {
                window.location.href = 'utilizador/logout.php';
            }
        });

        // Handle forced logout (when server detects disconnect)
        socket.on('forceLogout', () => {
            alert('You have been logged out from another device.');
            window.location.href = 'includes/logout_process.php';
        });

        function loadMessages(conversationId) {
        // Clear current messages while loading
        document.getElementById('messagesContainer').innerHTML = 'Loading...';
        
        fetch(`includes/get_messages.php?conversation_id=${conversationId}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('messagesContainer').innerHTML = html;
                document.getElementById('messageInputContainer').style.display = 'block';
                
                // Scroll to bottom
                const messagesContainer = document.getElementById('messagesContainer');
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                
                // Leave any previous conversation room
                if (activeConversationId) {
                    socket.emit('leaveConversation', activeConversationId);
                }
                
                // Join new conversation room
                socket.emit('joinConversation', conversationId);
                
                // Store active conversation
                activeConversationId = conversationId;
                
                // Update chat header data attribute
                document.getElementById('chatHeader').dataset.conversationId = conversationId;
            });
    }


        // Add event delegation for conversation items
        // Replace any existing chat selection code with this:
        document.getElementById('conversationsList').addEventListener('click', (e) => {
            const conversationItem = e.target.closest('.conversation-item');
            if (conversationItem) {
                const conversationId = conversationItem.dataset.conversationId;
                loadMessages(conversationId);
                
                // Highlight selected conversation
                document.querySelectorAll('.conversation-item').forEach(item => {
                    item.classList.remove('bg-light');
                });
                conversationItem.classList.add('bg-light');
            }
        });

        // Add this function to create message elements consistently
        function createMessageElement(data, isCurrentUser) {
            const messageElement = document.createElement('div');
            messageElement.className = `mb-3 ${isCurrentUser ? 'text-end' : 'text-start'}`;
            
            messageElement.innerHTML = `
                <div class="d-flex ${isCurrentUser ? 'justify-content-end' : 'justify-content-start'}">
                    ${!isCurrentUser ? `
                        <img src="${data.senderImage}" alt="${data.senderName}" class="profile-img me-2">
                    ` : ''}
                    <div>
                        ${!isCurrentUser ? `
                            <div class="fw-bold">${data.senderName}</div>
                        ` : ''}
                        <div class="p-2 rounded ${isCurrentUser ? 'bg-primary text-white' : 'bg-white'}">
                            ${data.content}
                        </div>
                        <small class="text-muted">${new Date(data.timestamp).toLocaleTimeString()}</small>
                    </div>
                    ${isCurrentUser ? `
                        <img src="<?= $_SESSION['profile_image'] ?>" alt="You" class="profile-img ms-2">
                    ` : ''}
                </div>
            `;
            
            return messageElement;
        }

        
        
        // Initial load
        loadConversations();
    </script>
</body>
</html>