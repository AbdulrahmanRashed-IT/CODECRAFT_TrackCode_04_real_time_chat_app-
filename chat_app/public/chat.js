document.addEventListener('DOMContentLoaded', () => {
    const chatBox = document.getElementById('chat-box');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const logoutBtn = document.getElementById('logout-btn');

    const ws = new WebSocket('ws://localhost:8080');

    ws.onopen = () => {
        console.log('Connected to WebSocket server');
    };

    ws.onmessage = (event) => {
        const message = event.data;
        const messageElement = document.createElement('div');
        messageElement.classList.add('message', 'other-message');
        messageElement.textContent = message;
        chatBox.appendChild(messageElement);
        chatBox.scrollTop = chatBox.scrollHeight;
    };

    ws.onclose = () => {
        console.log('Disconnected from WebSocket server');
    };

    ws.onerror = (error) => {
        console.error('WebSocket error:', error);
    };

    messageForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (message) {
            ws.send(message);
            const messageElement = document.createElement('div');
            messageElement.classList.add('message', 'my-message');
            messageElement.textContent = message;
            chatBox.appendChild(messageElement);
            chatBox.scrollTop = chatBox.scrollHeight;
            messageInput.value = '';
        }
    });
    
    logoutBtn.addEventListener('click', () => {
        window.location.href = '../api/logout.php';
    });
});