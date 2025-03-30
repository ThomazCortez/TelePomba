const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const mysql = require('mysql2/promise');

const app = express();
const server = http.createServer(app);

// Configure CORS properly
const io = socketIo(server, {
    cors: {
        origin: "*",
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

// Helper functions
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

    // Authentication handler
    socket.on('authenticate', async (userId) => {
        onlineUsers.set(userId, socket.id);
        io.emit('userStatusChanged', { userId, status: 'online' });
    });

    // Logout handler
    socket.on('userLogout', (userId) => {
        onlineUsers.delete(userId);
        io.emit('userStatusChanged', { userId, status: 'offline' });
        updateUserStatus(userId, 'offline');
    });

    // Conversation handler
    socket.on('joinConversation', (conversationId) => {
        const rooms = Object.keys(socket.rooms);
        rooms.forEach(room => {
            if (room !== socket.id && room.startsWith('conversation_')) {
                socket.leave(room);
            }
        });
        socket.join(`conversation_${conversationId}`);
        console.log(`User ${socket.id} joined conversation ${conversationId}`);
    });

    // Message handler (FIXED)
    socket.on('sendMessage', async (data) => {
        try {
            const { conversationId, senderId, content, messageType = 'text' } = data;
            
            if (!conversationId) {
                throw new Error('Missing conversationId');
            }

            const conn = await mysql.createConnection(dbConfig);
            
            await conn.execute(
                'INSERT INTO mensagens (id_conversa, id_remetente, conteudo, tipo_mensagem) VALUES (?, ?, ?, ?)',
                [conversationId, senderId, content, messageType]
            );

            const [user] = await conn.execute(
                'SELECT nome_utilizador, imagem_perfil FROM utilizadores WHERE id_utilizador = ?',
                [senderId]
            );
            
            conn.end();

            const messageData = {
                conversationId,
                senderId,
                senderName: user[0].nome_utilizador,
                senderImage: user[0].imagem_perfil,
                content,
                messageType,
                timestamp: new Date()
            };

            io.to(`conversation_${conversationId}`).emit('newMessage', messageData);
        } catch (error) {
            console.error('Error sending message:', error.message);
            socket.emit('messageError', { error: error.message });
        }
    });

    // Typing indicators (MOVED OUTSIDE OF sendMessage HANDLER)
    socket.on('startTyping', (data) => {
        if (!data.conversationId) return;
        socket.to(`conversation_${data.conversationId}`).emit('userStartedTyping', {
            conversationId: data.conversationId,
            userId: data.userId
        });
    });

    socket.on('stopTyping', (data) => {
        if (!data.conversationId) return;
        socket.to(`conversation_${data.conversationId}`).emit('userStoppedTyping', {
            conversationId: data.conversationId,
            userId: data.userId
        });
    });

    // Disconnect handler
    socket.on('disconnect', () => {
        console.log('Client disconnected');
        for (const [userId, socketId] of onlineUsers.entries()) {
            if (socketId === socket.id) {
                onlineUsers.delete(userId);
                io.emit('userStatusChanged', { userId, status: 'offline' });
                break;
            }
        }
    });
});

const PORT = 3000;
server.listen(PORT, '0.0.0.0', () => {
    console.log(`Socket server running on port ${PORT}`);
});