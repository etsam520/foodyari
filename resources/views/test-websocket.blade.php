<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSocket Test</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <h1>WebSocket Connection Test</h1>
    <div id="status">Connecting...</div>
    <div id="messages"></div>
    
    <input type="text" id="messageInput" placeholder="Type a message">
    <button onclick="sendMessage()">Send Message</button>
    
    <script>
        let websocket;
        
        function connectWebSocket() {
            websocket = new WebSocket('ws://foodyari.com/ws?token=test');
            
            websocket.onopen = function(event) {
                document.getElementById('status').innerHTML = 'Connected to WebSocket';
                console.log('WebSocket connection opened');
            };
            
            websocket.onmessage = function(event) {
                const message = JSON.parse(event.data);
                console.log('Received message:', message);
                document.getElementById('messages').innerHTML += '<div>' + JSON.stringify(message) + '</div>';
            };
            
            websocket.onclose = function(event) {
                document.getElementById('status').innerHTML = 'WebSocket connection closed';
                console.log('WebSocket connection closed');
            };
            
            websocket.onerror = function(error) {
                document.getElementById('status').innerHTML = 'WebSocket error: ' + error;
                console.log('WebSocket error:', error);
            };
        }
        
        function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = {
                type: 'message',
                data: input.value
            };
            websocket.send(JSON.stringify(message));
            input.value = '';
        }
        
        // Connect when page loads
        connectWebSocket();
    </script>
</body>
</html>
