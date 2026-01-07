@extends('layouts.dashboard-main')

@push('css')
<style>
.chat-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    padding: 15px 20px;
}

.chat-messages {
    height: 60vh;
    overflow-y: auto;
    padding: 20px;
    background-color: #f5f5f5;
}

.message {
    margin-bottom: 15px;
    display: flex;
    align-items: flex-end;
}

.message.own {
    justify-content: flex-end;
}

.message-content {
    max-width: 70%;
    padding: 10px 15px;
    border-radius: 18px;
    position: relative;
}

.message.own .message-content {
    background-color: #007bff;
    color: white;
    border-bottom-right-radius: 5px;
}

.message.other .message-content {
    background-color: white;
    color: #333;
    border: 1px solid #e0e0e0;
    border-bottom-left-radius: 5px;
}

.message-time {
    font-size: 0.75rem;
    opacity: 0.7;
    margin-top: 5px;
}

.message-input-container {
    border-top: 1px solid #e0e0e0;
    padding: 15px 20px;
    background-color: white;
}

.message-input {
    border-radius: 25px;
    border: 1px solid #e0e0e0;
    padding: 10px 20px;
    resize: none;
}

.send-btn {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.typing-indicator {
    display: none;
    padding: 10px 20px;
    font-style: italic;
    color: #6c757d;
}

.online-status {
    width: 10px;
    height: 10px;
    background-color: #28a745;
    border-radius: 50%;
    display: inline-block;
    margin-left: 5px;
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

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- Chat Header -->
                <div class="chat-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('admin.chat.index') }}" class="btn btn-link text-decoration-none me-3">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <img src="{{ asset('customers/' . ($otherUser['user']->image ?? 'default.png')) }}" 
                                 class="rounded-circle me-3" width="40" height="40" alt="User Avatar">
                            <div>
                                <h6 class="mb-0">{{ $otherUser['user']->full_name ?? 'Unknown User' }}</h6>
                                <small class="text-muted">
                                    {{ $otherUser['user']->phone ?? '' }}
                                    <span class="online-status" id="userOnlineStatus"></span>
                                </small>
                            </div>
                        </div>
                        <div class="text-muted">
                            <i class="fas fa-info-circle"></i>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="chat-messages" id="chatMessages">
                    @foreach($conversation->messages as $message)
                        @if(!$message->is_deleted)
                            <div class="message {{ $message->sender_type === 'admin' ? 'own' : 'other' }}" data-message-id="{{ $message->id }}">
                                @if($message->sender_type !== 'admin')
                                    <img src="{{ asset('customers/' . ($message->sender->image ?? 'default.png')) }}" 
                                        class="rounded-circle me-2" width="30" height="30" alt="Avatar">
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
                                    
                                    <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
                                </div>
                                @if($message->sender_type === 'admin')
                                    <img src="{{ asset('admins/' . (auth('admin')->user()->image ?? 'default.png')) }}" 
                                        class="rounded-circle ms-2" width="30" height="30" alt="Avatar">
                                @endif
                            </div>
                        @endif
                    @endforeach
                    
                </div>
                <!-- Typing Indicator -->
                <div class="typing-indicator" id="typingIndicator">
                    {{ $otherUser['user']->full_name ?? 'User' }} is typing...
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
                                          rows="1"></textarea>
                            </div>
                            
                            <!-- Send Button -->
                            <button type="submit" class="btn btn-primary send-btn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
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
    const currentUserId = {{ auth('admin')->user()->id }};
    const currentUserType = 'admin';
    
    let socket;
    let reconnectInterval = 5000; // 5 seconds
    let isTyping = false;
    let typingTimeout;

    // Initialize WebSocket connection
    function initializeWebSocket() {
        try {
            // Use your WebSocket server URL
            const socket_url = '{{ env("WEB_SOCKET_URL") }}?token={{ auth("admin")->user()->auth_token }}';
            socket = new WebSocket(socket_url); // or ws://127.0.0.1:6002 for local

            socket.onopen = function(event) {
                console.log('WebSocket connected');
                
                // Subscribe to chat channel
                const subscribeMessage = {
                    type: 'subscribe',
                    channel: `chat.admin.${currentUserId}`,
                    user_id: currentUserId,
                    user_type: currentUserType
                };
                socket.send(JSON.stringify(subscribeMessage));
            };

            socket.onmessage = function(event) {
                const data = JSON.parse(event.data);
                console.log('WebSocket message received:', data);
                
                if (data.type === 'message' && data.conversation_id == conversationId) {
                    appendMessage(data.message);
                    scrollToBottom();
                }
                
                if (data.type === 'typing') {
                    handleTypingIndicator(data.is_typing, data.user_id);
                }
            };

            socket.onclose = function(event) {
                console.log('WebSocket disconnected');
                // Attempt to reconnect
                setTimeout(initializeWebSocket, reconnectInterval);
            };

            socket.onerror = function(error) {
                console.error('WebSocket error:', error);
            };
        } catch (error) {
            console.error('WebSocket connection error:', error);
            // Fallback to polling if WebSocket fails
            startPolling();
        }
    }

    // Fallback polling method
    function startPolling() {
        setInterval(function() {
            loadNewMessages();
        }, 3000); // Poll every 3 seconds
    }

    function loadNewMessages() {
        const lastMessageId = $('.message:last').data('message-id') || 0;
        
        $.get(`{{ route('admin.chat.get-messages', $conversation->id) }}?since=${lastMessageId}`)
            .done(function(messages) {
                messages.forEach(function(message) {
                    appendMessage(message);
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
            alert('Please enter a message or select files to send');
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
                sender_type: 'admin',
                sender: {
                    full_name: '{{ auth("admin")->user()->full_name }}',
                    image: '{{ auth("admin")->user()->image }}'
                },
                created_at: new Date().toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }),
                sending: true
            };
            
            appendMessage(tempMessage);
            scrollToBottom();
        }
        
        // Clear input
        $('#messageInput').val('');
        selectedFiles = [];
        $('#fileInput').val('');
        updateFilePreview();
        
        // Send via AJAX
        $.ajax({
            url: `{{ route('admin.chat.send-message', $conversation->id) }}`,
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
                alert(errorMessage);
            }
        });

    });

    // Handle typing
    $('#messageInput').on('input', function() {
        if (!isTyping) {
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
                $('#typingIndicator').show();
                scrollToBottom();
            } else {
                $('#typingIndicator').hide();
            }
        }
    }

    function appendMessage(message) {
        // Skip if message already exists (prevent duplicates)
        if (message.id && $(`[data-message-id="${message.id}"]`).length > 0 && !message.sending) {
            return;
        }
        
        const isOwn = message.sender_type === currentUserType;
        const avatarUrl = message.sender_type === 'admin' 
            ? `{{ asset('admins/') }}/${message.sender?.image || 'default.png'}`
            : `{{ asset('customers/') }}/${message.sender?.image || 'default.png'}`;
        
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
            <div class="message ${isOwn ? 'own' : 'other'}" data-message-id="${message.id || 'temp'}" ${message.sending ? 'data-sending="true"' : ''}>
                ${!isOwn ? `<img src="${avatarUrl}" class="rounded-circle me-2" width="30" height="30" alt="Avatar">` : ''}
                <div class="message-content">
                    <div class="message-checkbox-container">
                        <input type="checkbox" class="message-checkbox" value="${message.id || 'temp'}" style="display: none;">
                    </div>
                    ${message.message ? `<div class="message-text">${message.message}</div>` : ''}
                    ${attachmentsHtml}
                    <div class="message-time">
                        ${message.created_at}
                        ${message.sending ? '<i class="fas fa-clock text-muted ms-1"></i>' : ''}
                    </div>
                </div>
                ${isOwn ? `<img src="${avatarUrl}" class="rounded-circle ms-2" width="30" height="30" alt="Avatar">` : ''}
            </div>
        `;
        
        $('#chatMessages').append(messageHtml);
    }

    function scrollToBottom() {
        const chatMessages = $('#chatMessages');
        chatMessages.scrollTop(chatMessages[0].scrollHeight);
    }

    // Auto-resize textarea
    $('#messageInput').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Enter to send message (Shift+Enter for new line)
    $('#messageInput').on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            $('#messageForm').submit();
        }
    });

    // Initialize
    initializeWebSocket();
    scrollToBottom();
    
    // Mark messages as read
    setTimeout(function() {
        $.post(`{{ route('admin.chat.mark-read', $conversation->id) }}`, {
            _token: '{{ csrf_token() }}'
        });
    }, 1000);

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
                url: "{{ route('admin.chat.delete-messages') }}",
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
                    alert('Messages deleted successfully');
                },
                error: function() {
                    alert('Error deleting messages');
                }
            });
        }
    });
    
    // Clear entire conversation
    $('#clearConversationBtn').on('click', function() {
        if (confirm('Are you sure you want to clear the entire conversation? This action cannot be undone.')) {
            $.ajax({
                url: `{{ route('admin.chat.clear-conversation', $conversation->id) }}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#chatMessages').empty();
                    alert('Conversation cleared successfully');
                },
                error: function() {
                    alert('Error clearing conversation');
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
});
</script>
@endpush
