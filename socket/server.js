const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const mysql = require('mysql2/promise');

const app = express();
const server = http.createServer(app);

// Configure CORS properly
const io = socketIo(server, {
    cors: {
      origin: ["http://localhost", "http://localhost:80"],
      methods: ["GET", "POST"],
      credentials: true
    }
});

// Database connection
const dbConfig = {
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'telepomba'
};

// Add this helper function (put it with your other database functions)
async function updateUserStatus(userId, status) {
    try {
        const conn = await mysql.createConnection(dbConfig);
        await conn.execute(
            'UPDATE utilizadores SET estado = ?, ultima_atividade = NOW() WHERE id_utilizador = ?',
            [status, userId]
        );
        conn.end();
    } catch (error) {
        console.error('Error updating user status:', error);
    }
}

async function getUserName(userId) {
    try {
        const conn = await mysql.createConnection(dbConfig);
        const [rows] = await conn.execute(
            'SELECT nome_utilizador FROM utilizadores WHERE id_utilizador = ?',
            [userId]
        );
        conn.end();
        return rows[0]?.nome_utilizador || 'Unknown';
    } catch (error) {
        console.error('Error fetching username:', error);
        return 'Unknown';
    }
}


// Store online users
const onlineUsers = new Map();

io.on('connection', async (socket) => {
    console.log('New client connected');
    
    // Authenticate user
    socket.on('authenticate', async (userId) => {
        onlineUsers.set(userId, socket.id);
        io.emit('userStatusChanged', { userId, status: 'online' });
    });

    socket.on('userLogout', (userId) => {
        onlineUsers.delete(userId);
        io.emit('userStatusChanged', { userId, status: 'offline' });
        
        // Update database status if you want to persist it
        updateUserStatus(userId, 'offline');
    });
    
    
    // Join conversation
    socket.on('joinConversation', (conversationId) => {
        // Leave all conversation rooms except the new one
        const rooms = Object.keys(socket.rooms);
        rooms.forEach(room => {
            if (room !== socket.id && room.startsWith('conversation_')) {
                socket.leave(room);
            }
        });
        // Join the new conversation room
        socket.join(`conversation_${conversationId}`);
        console.log(`User ${socket.id} joined conversation ${conversationId}`);
    });
    
    // Send message
    socket.on('sendMessage', async (data) => {
        try {
            const { conversationId, senderId, content } = data;
            console.log('Saving message to DB:', data);
            
            const conn = await mysql.createConnection(dbConfig);
            
            // Save to database
            await conn.execute(
                'INSERT INTO mensagens (id_conversa, id_remetente, conteudo) VALUES (?, ?, ?)',
                [conversationId, senderId, content]
            );
            
            // Get sender info
            const [user] = await conn.execute(
                'SELECT nome_utilizador, imagem_perfil FROM utilizadores WHERE id_utilizador = ?',
                [senderId]
            );
            
            conn.end();
            
            // Create message data object
            const messageData = {
                conversationId,
                senderId,
                senderName: user[0].nome_utilizador,
                senderImage: user[0].imagem_perfil,
                content,
                timestamp: new Date()
            };

            // Typing indicator events
    socket.on('startTyping', (data) => {
        console.log('Server received startTyping:', data);
        // Broadcast to all users in the conversation EXCEPT the sender
        socket.to(`conversation_${data.conversationId}`).emit('userStartedTyping', {
            conversationId: data.conversationId,
            userId: data.userId
        });
    });

    socket.on('stopTyping', (data) => {
        console.log('Server received stopTyping:', data);
        // Broadcast to all users in the conversation EXCEPT the sender
        socket.to(`conversation_${data.conversationId}`).emit('userStoppedTyping', {
            conversationId: data.conversationId,
            userId: data.userId
        });
    });

            
            // Broadcast to everyone in the room (including sender)
            console.log('Broadcasting to room:', `conversation_${conversationId}`);
            io.to(`conversation_${conversationId}`).emit('newMessage', messageData);
            
        } catch (error) {
            console.error('Error sending message:', error);
        }
    });
    
    // Disconnect
    socket.on('disconnect', () => {
        console.log('Client disconnected');
        // Find and remove user from onlineUsers
        for (let [userId, socketId] of onlineUsers.entries()) {
            if (socketId === socket.id) {
                onlineUsers.delete(userId);
                io.emit('userStatusChanged', { userId, status: 'offline' });
                break;
            }
        }
    });
});

const PORT = 3000;
server.listen(PORT, '0.0.0.0', () => {  // Explicitly listen on all interfaces
  console.log(`Socket server running on port ${PORT}`);
});