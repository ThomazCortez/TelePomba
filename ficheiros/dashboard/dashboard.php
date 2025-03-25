<?php
session_start();
// Verificar se o usuário está logado
if (!isset($_SESSION['id_utilizador'])) {
    header('Location: /TelePomba/ficheiros/login/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Clone</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .chat-list-item {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .chat-list-item:hover, .chat-list-item.active {
            background-color: #f5f5f5;
        }
        .message-received {
            background-color: #f0f2f5;
            border-radius: 0 8px 8px 8px;
        }
        .message-sent {
            background-color: #d9fdd3;
            border-radius: 8px 0 8px 8px;
        }
        .chat-container {
            height: 100vh;
        }
        .chat-sidebar {
            border-right: 1px solid #dee2e6;
        }
        .chat-messages {
            height: calc(100vh - 120px);
            overflow-y: auto;
        }
        .online-badge {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #31a24c;
        }
    </style>
</head>
<body>
    <div class="container-fluid chat-container">
        <div class="row h-100">
            <!-- Sidebar -->
            <div class="col-md-4 col-lg-3 p-0 chat-sidebar">
                <!-- Sidebar Header -->
                <div class="bg-light p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Chats</h5>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>
                    <div class="mt-2">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search or start new chat">
                        </div>
                    </div>
                </div>
                
                <!-- Chat List -->
                <div class="list-group list-group-flush overflow-auto" style="height: calc(100vh - 120px);" id="chatList">
                    <!-- Chat items will be added here by JavaScript -->
                </div>
            </div>
            
            <!-- Main Chat Area -->
            <div class="col-md-8 col-lg-9 p-0 d-flex flex-column">
                <!-- Chat Header -->
                <div class="bg-light p-3 border-bottom d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div id="currentChatAvatar" class="me-2"></div>
                        <div>
                            <h6 class="mb-0" id="currentChatName">Select a chat</h6>
                            <small class="text-muted" id="currentChatStatus">Select a chat from the sidebar</small>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary me-1">
                            <i class="bi bi-search"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Messages Area -->
                <div class="flex-grow-1 p-3 chat-messages" id="chatMessages">
                    <div class="text-center text-muted mt-5">
                        Select a chat to start messaging
                    </div>
                </div>
                
                <!-- Message Input -->
                <div class="bg-light p-3 border-top">
                    <div class="input-group">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-emoji-smile"></i>
                        </button>
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-paperclip"></i>
                        </button>
                        <input type="text" class="form-control" placeholder="Type a message">
                        <button class="btn btn-primary" type="button">
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sample chat data
        const chats = [
            { 
                name: "Maya Kasuma", 
                lastMessage: "Yes OK!", 
                time: "GOL PM", 
                unread: false,
                avatar: '<span class="d-inline-block rounded-circle bg-success p-2 text-white">MK</span>',
                status: "online",
                messages: [
                    { text: "Hi there!", time: "10:30 AM", sent: false },
                    { text: "Yes OK!", time: "10:32 AM", sent: false }
                ]
            },
            { 
                name: "Steve Ballmer", 
                lastMessage: "Developers, developers, develop...", 
                time: "GOL PM", 
                unread: false,
                avatar: '<span class="d-inline-block rounded-circle bg-primary p-2 text-white">SB</span>',
                status: "last seen today at 12:45",
                messages: [
                    { text: "I love developers!", time: "9:15 AM", sent: false },
                    { text: "Developers, developers, developers...", time: "9:16 AM", sent: false },
                    { text: "That's great!", time: "9:20 AM", sent: true }
                ]
            },
            { 
                name: "Alice Whitman", 
                lastMessage: "Yes that's my favorite tool!", 
                time: "online", 
                unread: false,
                avatar: '<span class="d-inline-block rounded-circle bg-warning p-2 text-white">AW</span>',
                status: "online",
                messages: [
                    { text: "Have you tried the new IDE?", time: "Yesterday", sent: false },
                    { text: "Yes that's my favorite tool!", time: "Just now", sent: false }
                ]
            },
            { 
                name: "Clippy", 
                lastMessage: "Are you there?", 
                time: "Yesterday", 
                unread: true,
                avatar: '<span class="d-inline-block rounded-circle bg-info p-2 text-white">C</span>',
                status: "last seen yesterday at 5:30 PM",
                messages: [
                    { text: "Hi, it looks like you're writing a message!", time: "Yesterday", sent: false },
                    { text: "Are you there?", time: "Yesterday", sent: false }
                ]
            }
        ];

        // DOM elements
        const chatList = document.getElementById('chatList');
        const chatMessages = document.getElementById('chatMessages');
        const currentChatName = document.getElementById('currentChatName');
        const currentChatStatus = document.getElementById('currentChatStatus');
        const currentChatAvatar = document.getElementById('currentChatAvatar');

        // Populate chat list
        chats.forEach(chat => {
            const chatItem = document.createElement('a');
            chatItem.className = 'list-group-item list-group-item-action chat-list-item';
            if (chat.unread) {
                chatItem.classList.add('fw-bold');
            }
            
            chatItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <div class="position-relative me-2">
                        ${chat.avatar}
                        ${chat.status === "online" ? '<span class="online-badge position-absolute bottom-0 end-0 border border-2 border-white"></span>' : ''}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">${chat.name}</h6>
                            <small class="text-muted">${chat.time}</small>
                        </div>
                        <p class="mb-0 text-truncate" style="max-width: 200px;">${chat.lastMessage}</p>
                    </div>
                </div>
            `;
            
            chatItem.addEventListener('click', function() {
                // Remove active class from all items
                document.querySelectorAll('.chat-list-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                // Add active class to clicked item
                this.classList.add('active');
                
                // Update main chat area
                currentChatName.textContent = chat.name;
                currentChatStatus.textContent = chat.status === "online" ? "online" : chat.status;
                currentChatAvatar.innerHTML = chat.avatar;
                
                // Display messages
                chatMessages.innerHTML = '';
                if (chat.messages && chat.messages.length > 0) {
                    chat.messages.forEach(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = `mb-2 d-flex ${message.sent ? 'justify-content-end' : 'justify-content-start'}`;
                        messageDiv.innerHTML = `
                            <div class="p-2 ${message.sent ? 'message-sent' : 'message-received'}">
                                <p class="mb-1">${message.text}</p>
                                <small class="text-muted d-block text-end">${message.time}</small>
                            </div>
                        `;
                        chatMessages.appendChild(messageDiv);
                    });
                    
                    // Scroll to bottom
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                } else {
                    chatMessages.innerHTML = '<div class="text-center text-muted mt-5">No messages yet</div>';
                }
            });
            
            chatList.appendChild(chatItem);
        });
    </script>
</body>
</html>