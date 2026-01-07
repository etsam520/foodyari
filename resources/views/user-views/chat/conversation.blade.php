@extends('user-views.restaurant.layouts.main')

@push('css')
<style>
    .chat-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }

    .chat-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 20px;
    }

    .chat-messages {
        height: 60vh;
        overflow-y: auto;
        padding: 20px;
        background-color: #f8f9fa;
        background-image: 
            radial-gradient(circle at 1px 1px, rgba(255,255,255,0.3) 1px, transparent 0);
        background-size: 20px 20px;
    }

    .message {
        margin-bottom: 15px;
        display: flex;
        align-items: flex-end;
        animation: fadeInUp 0.3s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message.own {
        justify-content: flex-end;
    }

    .message-content {
        max-width: 70%;
        padding: 12px 16px;
        border-radius: 20px;
        position: relative;
        word-wrap: break-word;
    }

    .message.own .message-content {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom-right-radius: 5px;
    }

    .message.other .message-content {
        background-color: white;
        color: #333;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border-bottom-left-radius: 5px;
    }

    .message-time {
        font-size: 0.75rem;
        opacity: 0.7;
        margin-top: 5px;
    }

    .message-input-container {
        background-color: white;
        padding: 20px;
        border-radius: 0 0 15px 15px;
        border-top: 1px solid #e0e0e0;
    }

    .message-input {
        border-radius: 25px;
        border: 1px solid #e0e0e0;
        padding: 12px 20px;
        resize: none;
        transition: border-color 0.3s ease;
    }

    .message-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .send-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 50%;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease;
    }

    .send-btn:hover {
        transform: scale(1.1);
    }

    .typing-indicator {
        display: none;
        padding: 10px 20px;
        font-style: italic;
        color: #6c757d;
        background-color: rgba(255,255,255,0.8);
        border-radius: 15px;
        margin: 0 20px;
    }

    .typing-dots {
        display: inline-block;
    }

    .typing-dots span {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #667eea;
        margin: 0 1px;
        animation: typing 1.4s infinite ease-in-out;
    }

    .typing-dots span:nth-child(1) { animation-delay: -0.32s; }
    .typing-dots span:nth-child(2) { animation-delay: -0.16s; }

    @keyframes typing {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    .online-indicator {
        width: 10px;
        height: 10px;
        background-color: #28a745;
        border-radius: 50%;
        display: inline-block;
        margin-left: 8px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
    }

    .user-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .chat-card {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .connection-status {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 10px 20px;
        border-radius: 25px;
        color: white;
        font-size: 0.9rem;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .connection-status.connected {
        background-color: #28a745;
    }

    .connection-status.disconnected {
        background-color: #dc3545;
    }

    .connection-status.connecting {
        background-color: #ffc107;
        color: #333;
    }

    .message-checkbox-container {
        position: absolute;
        top: 5px;
        right: 5px;
    }

    .message {
        position: relative;
    }

    .message:hover .message-checkbox {
        display: block !important;
    }

    .message.selected {
        background-color: #e3f2fd;
        border: 1px solid #2196f3;
    }

    .attachment-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 8px;
    }

    .file-attachment {
        background: white;
        max-width: 250px;
    }

    .image-attachment img {
        border-radius: 8px;
    }

    #filePreviewContainer {
        background: #f8f9fa;
    }

    .file-preview-item {
        background: white;
        border-radius: 8px;
        padding: 10px;
        position: relative;
    }

    .file-preview-remove {
        position: absolute;
        top: 5px;
        right: 5px;
    }
</style>
@endpush

@section('containt')
<div class="chat-container">
    <!-- Connection Status -->
    <div class="connection-status connecting" id="connectionStatus">
        <i class="fas fa-wifi me-2"></i>Connecting...
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card chat-card">
                <!-- Chat Header -->
                <div class="chat-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('user.chat.index') }}" class="btn btn-link text-white text-decoration-none me-3">
                                <i class="fas fa-arrow-left fa-lg"></i>
                            </a>
                            <img src="{{ asset('admins/' . ($otherUser['user']->image ?? 'default.png')) }}" 
                                 class="user-avatar me-3" alt="Support Avatar">
                            <div>
                                <h5 class="mb-0">{{ $otherUser['user']->full_name ?? 'Support Team' }}</h5>
                                <small class="opacity-75">
                                    Customer Support
                                    <span class="online-indicator" id="supportOnlineStatus"></span>
                                </small>
                            </div>
                        </div>
                        <div class="text-white">
                            <button class="btn btn-link text-white" data-bs-toggle="tooltip" title="Support Info">
                                <i class="fas fa-info-circle fa-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Chat Messages -->
                <div class="chat-messages" id="chatMessages">
                    @foreach($conversation->messages as $message)
                        @if(!$message->is_deleted)
                        <div class="message {{ $message->sender_type === 'customer' ? 'own' : 'other' }}" data-message-id="{{ $message->id }}">
                            @if($message->sender_type !== 'customer')
                                <img src="{{ asset('admins/' . ($message->sender->image ?? 'default.png')) }}" 
                                     class="user-avatar me-2" alt="Support Avatar">
                            @endif
                            <div class="message-content">
                                <div class="message-checkbox-container">
                                    <input type="checkbox" class="message-checkbox" value="{{ $message->id }}" style="display: none;">
                                </div>
                                
                                @if($message->message)
                                    <div class="message-text">{{ $message->message }}</div>
                                @endif
                                
                                @if($message->hasAttachments())
                                    <div class="message-attachments mt-2">
                                        @foreach($message->attachments as $attachment)
                                            <div class="attachment-item mb-2">
                                                @if(str_starts_with($attachment['type'], 'image/'))
                                                    <div class="image-attachment">
                                                        <img src="{{ asset('storage/' . $attachment['path']) }}" 
                                                             class="img-thumbnail" 
                                                             style="max-width: 200px; max-height: 200px; cursor: pointer;"
                                                             onclick="openImageModal('{{ asset('storage/' . $attachment['path']) }}', '{{ $attachment['name'] }}')"
                                                             alt="{{ $attachment['name'] }}">
                                                        <div class="small text-muted">{{ $attachment['name'] }}</div>
                                                    </div>
                                                @else
                                                    <div class="file-attachment d-flex align-items-center p-2 border rounded">
                                                        <i class="fas fa-file me-2"></i>
                                                        <div class="flex-grow-1">
                                                            <div class="fw-bold">{{ $attachment['name'] }}</div>
                                                            <div class="small text-muted">
                                                                {{ number_format($attachment['size'] / 1024, 1) }} KB
                                                            </div>
                                                        </div>
                                                        <a href="{{ asset('storage/' . $attachment['path']) }}" 
                                                           class="btn btn-sm btn-outline-primary" 
                                                           download="{{ $attachment['name'] }}">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <div class="message-time">
                                    {{ $message->created_at->format('H:i') }}
                                    @if($message->sender_type === 'customer')
                                        <i class="fas fa-check ms-1 {{ $message->is_seen ? 'text-info' : '' }}" 
                                           title="{{ $message->is_seen ? 'Seen' : 'Sent' }}"></i>
                                    @endif
                                </div>
                            </div>
                            @if($message->sender_type === 'customer')
                                @php
                                    $currentUser = auth('customer')->user() ?? Session::get('userInfo');
                                @endphp
                                <img src="{{ asset('customers/' . ($currentUser->image ?? 'default.png')) }}" 
                                     class="user-avatar ms-2" alt="Your Avatar">
                            @endif
                        </div>
                        @endif
                        
                    @endforeach
                </div>

                <!-- Typing Indicator -->
                <div class="typing-indicator" id="typingIndicator">
                    Support is typing
                    <span class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </div>

                <!-- Message Input -->
                <div class="message-input-container">
                    <!-- File Upload Preview -->
                    <div id="filePreviewContainer" class="d-none mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Selected Files:</h6>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="clearFiles">
                                <i class="fas fa-times"></i> Clear All
                            </button>
                        </div>
                        <div id="filePreviewList" class="row"></div>
                    </div>

                    <!-- Chat Actions -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-danger" id="deleteSelectedBtn" style="display: none;">
                                <i class="fas fa-trash"></i> Delete Selected
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning" id="clearConversationBtn">
                                <i class="fas fa-broom"></i> Clear Chat
                            </button>
                        </div>
                        <div>
                            <small class="text-muted">Select messages to delete them</small>
                        </div>
                    </div>

                    <form id="messageForm" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex align-items-end gap-2">
                            <!-- File Upload Button -->
                            <button type="button" class="btn btn-outline-primary" id="fileUploadBtn">
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <input type="file" id="fileInput" name="attachments[]" multiple style="display: none;" 
                                   accept="image/*,.pdf,.doc,.docx,.txt,.zip,.rar">
                            
                            <!-- Message Text Area -->
                            <div class="flex-grow-1">
                                <textarea class="form-control message-input" 
                                          id="messageInput" 
                                          name="message"
                                          placeholder="Type your message..."
                                          rows="1"
                                          maxlength="1000"></textarea>
                            </div>
                            
                            <!-- Send Button -->
                            <button type="submit" class="btn btn-primary send-btn" id="sendButton">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">Press Enter to send, Shift+Enter for new line</small>
                        <small class="text-muted">
                            <span id="charCount">0</span>/1000
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="">
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
$(document).ready(function() {
    const conversationId = {{ $conversation->id }};
    @php
        $currentUser = auth('customer')->user() ?? Session::get('userInfo');
    @endphp
    const currentUserId = {{ $currentUser->id }};
    const currentUserType = 'customer';
    
    let socket;
    let reconnectInterval = 5000;
    let isTyping = false;
    let typingTimeout;
    let isConnected = false;

    // Initialize WebSocket connection
    function initializeWebSocket() {
        updateConnectionStatus('connecting');
        
        try {
            socketURL = "{{ env('WEB_SOCKET_URL') }}?token={{ $currentUser->api_token }}";
            socket = new WebSocket(socketURL);

            socket.onopen = function(event) {
                console.log('WebSocket connected');
                isConnected = true;
                updateConnectionStatus('connected');
                
                const subscribeMessage = {
                    type: 'subscribe',
                    channel: `chat.customer.${currentUserId}`,
                    user_id: currentUserId,
                    user_type: currentUserType
                };
                socket.send(JSON.stringify(subscribeMessage));
            };

            socket.onmessage = function(event) {
                const data = JSON.parse(event.data);
                console.log('Message received:', data);
                
                if (data.type === 'message' && data.conversation_id == conversationId) {
                    appendMessage(data.message);
                    scrollToBottom();
                    playNotificationSound();
                }
                
                if (data.type === 'typing') {
                    handleTypingIndicator(data.is_typing, data.user_id);
                }
            };

            socket.onclose = function(event) {
                console.log('WebSocket disconnected');
                isConnected = false;
                updateConnectionStatus('disconnected');
                setTimeout(initializeWebSocket, reconnectInterval);
            };

            socket.onerror = function(error) {
                console.error('WebSocket error:', error);
                isConnected = false;
                updateConnectionStatus('disconnected');
            };
        } catch (error) {
            console.error('WebSocket connection error:', error);
            updateConnectionStatus('disconnected');
            startPolling();
        }
    }

    // Update connection status
    function updateConnectionStatus(status) {
        const statusEl = $('#connectionStatus');
        statusEl.removeClass('connected disconnected connecting').addClass(status);
        
        switch(status) {
            case 'connected':
                statusEl.html('<i class="fas fa-wifi me-2"></i>Connected');
                setTimeout(() => statusEl.fadeOut(), 3000);
                break;
            case 'disconnected':
                statusEl.html('<i class="fas fa-wifi me-2"></i>Disconnected').show();
                break;
            case 'connecting':
                statusEl.html('<i class="fas fa-spinner fa-spin me-2"></i>Connecting...').show();
                break;
        }
    }

    // Fallback polling
    function startPolling() {
        setInterval(loadNewMessages, 5000);
    }

    function loadNewMessages() {
        const lastMessageTime = $('.message:last').data('created-at') || 0;
        
        $.get(`{{ route('user.chat.get-messages', $conversation->id) }}?since=${lastMessageTime}`)
            .done(function(messages) {
                messages.forEach(function(message) {
                    if (message.sender_id !== currentUserId) {
                        appendMessage(message);
                        playNotificationSound();
                    }
                });
                if (messages.length > 0) {
                    scrollToBottom();
                }
            });
    }

    // Send message
    $('#messageForm').on('submit', function(e) {
        e.preventDefault();
        
        const message = $('#messageInput').val().trim();
        const hasFiles = selectedFiles.length > 0;
        
        if (!message && !hasFiles) {
            showErrorAlert('Please enter a message or select files to send');
            return;
        }
        
        // Create form data for file upload
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        
        if (message) {
            formData.append('message', message);
        }
        
        selectedFiles.forEach(file => {
            formData.append('attachments[]', file);
        });
        
        // Add message to UI immediately (if text message)
        if (message) {
            const tempMessage = {
                message: message,
                sender_type: 'customer',
                sender: {
                    full_name: '{{ $currentUser->full_name }}',
                    image: '{{ $currentUser->image }}'
                },
                created_at: new Date().toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }),
                sending: true
            };
            
            appendMessage(tempMessage);
            scrollToBottom();
        }
        
        // Clear input
        $('#messageInput').val('').trigger('input');
        selectedFiles = [];
        $('#fileInput').val('');
        updateFilePreview();
        
        // Send via AJAX
        $.ajax({
            url: `{{ route('user.chat.send-message', $conversation->id) }}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Message sent successfully:', response);
                
                // Remove temporary message
                $('.message[data-sending="true"]').remove();
                
                // Always add the real message to UI
                if (response.success && response.message) {
                    appendMessage(response.message);
                    scrollToBottom();
                    
                    // Also send via WebSocket if connected
                    if (socket && socket.readyState === WebSocket.OPEN) {
                        const wsMessage = {
                            type: 'message',
                            conversation_id: conversationId,
                            message: response.message
                        };
                        socket.send(JSON.stringify(wsMessage));
                    }
                }
            },
            error: function(xhr) {
                console.error('Message send failed:', xhr);
                $('.message[data-sending="true"]').remove();
                
                let errorMessage = 'Failed to send message. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                showError(errorMessage);
            }
        });
    });

    // Handle typing
    $('#messageInput').on('input', function() {
        const length = $(this).val().length;
        $('#charCount').text(length);
        
        if (!isTyping && length > 0) {
            isTyping = true;
            sendTypingStatus(true);
        }
        
        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(function() {
            isTyping = false;
            sendTypingStatus(false);
        }, 1000);
    });

    function sendTypingStatus(typing) {
        if (socket && socket.readyState === WebSocket.OPEN) {
            const typingMessage = {
                type: 'typing',
                conversation_id: conversationId,
                user_id: currentUserId,
                user_type: currentUserType,
                is_typing: typing
            };
            socket.send(JSON.stringify(typingMessage));
        }
    }

    function handleTypingIndicator(isTyping, userId) {
        if (userId !== currentUserId) {
            if (isTyping) {
                $('#typingIndicator').slideDown();
                scrollToBottom();
            } else {
                $('#typingIndicator').slideUp();
            }
        }
    }

    function appendMessage(message) {
        // Skip if message already exists (prevent duplicates)
        if (message.id && $(`[data-message-id="${message.id}"]`).length > 0 && !message.sending) {
            return;
        }
        
        const isOwn = message.sender_type === currentUserType;
        const avatarUrl = message.sender_type === 'customer' 
            ? `{{ asset('customers/') }}/${message.sender?.image || 'default.png'}`
            : `{{ asset('admins/') }}/${message.sender?.image || 'default.png'}`;
        
        // Build attachments HTML
        let attachmentsHtml = '';
        if (message.attachments && message.attachments.length > 0) {
            attachmentsHtml = '<div class="message-attachments mt-2">';
            message.attachments.forEach(attachment => {
                if (attachment.type.startsWith('image/')) {
                    attachmentsHtml += `
                        <div class="attachment-item mb-2">
                            <div class="image-attachment">
                                <img src="{{ asset('storage/') }}/${attachment.path}" 
                                     class="img-thumbnail" 
                                     style="max-width: 200px; max-height: 200px; cursor: pointer;"
                                     onclick="openImageModal('{{ asset('storage/') }}/${attachment.path}', '${attachment.name}')"
                                     alt="${attachment.name}">
                                <div class="small text-muted">${attachment.name}</div>
                            </div>
                        </div>`;
                } else {
                    attachmentsHtml += `
                        <div class="attachment-item mb-2">
                            <div class="file-attachment d-flex align-items-center p-2 border rounded">
                                <i class="fas fa-file me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">${attachment.name}</div>
                                    <div class="small text-muted">${(attachment.size / 1024).toFixed(1)} KB</div>
                                </div>
                                <a href="{{ asset('storage/') }}/${attachment.path}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   download="${attachment.name}">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>`;
                }
            });
            attachmentsHtml += '</div>';
        }
        
        const messageHtml = `
            <div class="message ${isOwn ? 'own' : 'other'}" 
                 data-message-id="${message.id || 'temp'}" 
                 data-created-at="${message.created_at}"
                 ${message.sending ? 'data-sending="true"' : ''}>
                ${!isOwn ? `<img src="${avatarUrl}" class="user-avatar me-2" alt="Avatar">` : ''}
                <div class="message-content">
                    <div class="message-checkbox-container">
                        <input type="checkbox" class="message-checkbox" value="${message.id || 'temp'}" style="display: none;">
                    </div>
                    ${message.message ? `<div class="message-text">${message.message}</div>` : ''}
                    ${attachmentsHtml}
                    <div class="message-time">
                        ${message.created_at}
                        ${message.sending ? '<i class="fas fa-clock text-muted ms-1"></i>' : ''}
                        ${isOwn && !message.sending ? `<i class="fas fa-check ms-1 ${message.is_seen ? 'text-info' : ''}" title="${message.is_seen ? 'Seen' : 'Sent'}"></i>` : ''}
                    </div>
                </div>
                ${isOwn ? `<img src="${avatarUrl}" class="user-avatar ms-2" alt="Avatar">` : ''}
            </div>
        `;
        
        $('#chatMessages').append(messageHtml);
    }

    function scrollToBottom() {
        const chatMessages = $('#chatMessages');
        chatMessages.animate({ scrollTop: chatMessages[0].scrollHeight }, 300);
    }

    function playNotificationSound() {
        try {
            const audio = new Audio('{{ asset("sound/notification-tone.mp3") }}');
            audio.volume = 0.3;
            audio.play().catch(e => console.log('Audio play failed:', e));
        } catch (e) {
            console.log('Audio not available');
        }
    }

    function showError(message) {
        const errorHtml = `
            <div class="alert alert-danger alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 1050;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('body').append(errorHtml);
        
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    function showErrorAlert(message) {
        showError(message);
    }

    // Auto-resize textarea
    $('#messageInput').on('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    // Enter to send
    $('#messageInput').on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            $('#messageForm').submit();
        }
    });

    // File Upload Functionality
    let selectedFiles = [];
    
    $('#fileUploadBtn').on('click', function() {
        $('#fileInput').click();
    });
    
    $('#fileInput').on('change', function() {
        const files = Array.from(this.files);
        selectedFiles = selectedFiles.concat(files);
        updateFilePreview();
    });
    
    function updateFilePreview() {
        if (selectedFiles.length > 0) {
            $('#filePreviewContainer').removeClass('d-none');
            const previewList = $('#filePreviewList');
            previewList.empty();
            
            selectedFiles.forEach((file, index) => {
                const filePreview = $(`
                    <div class="col-md-6 mb-2">
                        <div class="file-preview-item">
                            <button type="button" class="btn btn-sm btn-danger file-preview-remove" data-index="${index}">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="d-flex align-items-center">
                                ${file.type.startsWith('image/') ? 
                                    `<img src="${URL.createObjectURL(file)}" class="img-thumbnail me-2" style="width: 50px; height: 50px;">` :
                                    `<i class="fas fa-file me-2"></i>`
                                }
                                <div>
                                    <div class="fw-bold">${file.name}</div>
                                    <div class="small text-muted">${(file.size / 1024).toFixed(1)} KB</div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                previewList.append(filePreview);
            });
        } else {
            $('#filePreviewContainer').addClass('d-none');
        }
    }
    
    $(document).on('click', '.file-preview-remove', function() {
        const index = $(this).data('index');
        selectedFiles.splice(index, 1);
        updateFilePreview();
    });
    
    $('#clearFiles').on('click', function() {
        selectedFiles = [];
        $('#fileInput').val('');
        updateFilePreview();
    });

    // Message Selection and Deletion
    let selectedMessages = [];
    
    $(document).on('change', '.message-checkbox', function() {
        const messageId = $(this).val();
        if ($(this).is(':checked')) {
            selectedMessages.push(messageId);
            $(this).closest('.message').addClass('selected');
        } else {
            selectedMessages = selectedMessages.filter(id => id !== messageId);
            $(this).closest('.message').removeClass('selected');
        }
        
        if (selectedMessages.length > 0) {
            $('#deleteSelectedBtn').show();
        } else {
            $('#deleteSelectedBtn').hide();
        }
    });
    
    // Toggle message selection mode
    $(document).on('click', '.message', function(e) {
        if (e.target.type !== 'checkbox' && !$(e.target).closest('.attachment-item').length) {
            const checkbox = $(this).find('.message-checkbox');
            checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
        }
    });
    
    // Delete selected messages
    $('#deleteSelectedBtn').on('click', function() {
        if (selectedMessages.length === 0) return;
        
        if (confirm(`Are you sure you want to delete ${selectedMessages.length} message(s)?`)) {
            $.ajax({
                url: "{{ route('user.chat.delete-messages') }}",
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    message_ids: selectedMessages
                },
                success: function(response) {
                    selectedMessages.forEach(id => {
                        $(`[data-message-id="${id}"]`).fadeOut(300, function() {
                            $(this).remove();
                        });
                    });
                    selectedMessages = [];
                    $('#deleteSelectedBtn').hide();
                    showSuccessAlert('Messages deleted successfully');
                },
                error: function() {
                    showErrorAlert('Error deleting messages');
                }
            });
        }
    });
    
    // Clear entire conversation
    $('#clearConversationBtn').on('click', function() {
        if (confirm('Are you sure you want to clear the entire conversation? This action cannot be undone.')) {
            $.ajax({
                url: `{{ route('user.chat.clear-conversation', $conversation->id) }}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#chatMessages').empty();
                    showSuccessAlert('Conversation cleared successfully');
                },
                error: function() {
                    showErrorAlert('Error clearing conversation');
                }
            });
        }
    });

    // Open image modal
    window.openImageModal = function(imageUrl, imageName) {
        $('#modalImage').attr('src', imageUrl);
        $('#imageModalLabel').text(imageName);
        $('#imageModal').modal('show');
    }
    
    // Show success alert
    function showSuccessAlert(message) {
        const alertHtml = `
            <div class="alert alert-success alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 1050;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('body').append(alertHtml);
        
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize
    initializeWebSocket();
    scrollToBottom();
});
</script>
@endpush
