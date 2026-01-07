@extends('layouts.dashboard-main')

@push('css')
<style>
    .chat-container {
        height: 80vh;
    }

    .chat-sidebar {
        height: 100%;
        border-right: 1px solid #e0e0e0;
    }

    .chat-conversation-list {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }

    .conversation-item {
        cursor: pointer;
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .conversation-item:hover {
        background-color: #f8f9fa;
    }

    .conversation-item.active {
        background-color: #e3f2fd;
    }

    .chat-main {
        height: 100%;
    }

    .message-container {
        height: calc(100vh - 300px);
        overflow-y: auto;
        padding: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }

    .message {
        margin-bottom: 15px;
        display: flex;
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
    }

    .message.other .message-content {
        background-color: #f1f1f1;
        color: #333;
    }

    .message-time {
        font-size: 0.75rem;
        opacity: 0.7;
        margin-top: 5px;
    }

    .message-input {
        border-top: 1px solid #e0e0e0;
        padding: 15px;
        background-color: #f8f9fa;
    }

    .unread-count {
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 0.75rem;
        min-width: 18px;
        text-align: center;
    }

    .online-indicator {
        width: 8px;
        height: 8px;
        background-color: #28a745;
        border-radius: 50%;
        position: absolute;
        bottom: 2px;
        right: 2px;
    }

    .user-avatar {
        position: relative;
        display: inline-block;
    }
</style>
@endpush

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Chat Management</h4>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newChatModal">
                        <i class="fas fa-plus"></i> New Chat
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0 chat-container">
                        <!-- Chat Sidebar -->
                        <div class="col-md-4 chat-sidebar">
                            <div class="p-3 border-bottom">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search conversations..." id="searchConversations">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="chat-conversation-list" id="conversationList">
                                @forelse($conversations as $conversation)
                                    @php
                                        $otherUser = $conversation->getOtherUser(auth('admin')->user()->id, 'admin');
                                    @endphp
                                    <div class="conversation-item" 
                                         data-conversation-id="{{ $conversation->id }}"
                                         data-user-id="{{ $otherUser['id'] }}"
                                         data-user-type="{{ $otherUser['type'] }}">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-3">
                                                <img src="{{ asset('customers/' . ($otherUser['user']->image ?? 'default.png')) }}" 
                                                     class="rounded-circle" 
                                                     width="40" height="40" 
                                                     alt="User Avatar">
                                                <div class="online-indicator"></div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $otherUser['user']->full_name ?? 'Unknown User' }}</h6>
                                                <p class="mb-0 text-muted small">
                                                    @if($conversation->lastMessage)
                                                        {{ Str::limit($conversation->lastMessage->message, 50) }}
                                                    @else
                                                        No messages yet
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                @if($conversation->unread_message_count > 0)
                                                    <span class="unread-count">{{ $conversation->unread_message_count }}</span>
                                                @endif
                                                <div class="text-muted small">
                                                    {{ $conversation->last_message_time ? $conversation->last_message_time->diffForHumans() : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center p-4 text-muted">
                                        <i class="fas fa-comments fa-3x mb-3"></i>
                                        <p>No conversations yet. Start a new chat!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Chat Main -->
                        <div class="col-md-8 chat-main">
                            <div class="h-100 d-flex flex-column" id="chatMain">
                                <div class="flex-grow-1 d-flex align-items-center justify-content-center text-muted">
                                    <div class="text-center">
                                        <i class="fas fa-comments fa-4x mb-3"></i>
                                        <h5>Select a conversation to start chatting</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                <h5 class="modal-title">Start New Conversation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="newChatForm" method="GET" action="javascript:void(0);">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customerSearch" class="form-label">Search Customer</label>
                        <input type="text" class="form-control" id="customerSearch" placeholder="Search by name, phone, or email">
                        <div id="customerResults" class="mt-2"></div>
                    </div>
                    <input type="hidden" id="selectedCustomerId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Start Chat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
$(document).ready(function() {
    let currentConversationId = null;

    // Customer search functionality
    let searchTimeout;
    $('#customerSearch').on('input', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val();
        
        if (query.length < 2) {
            $('#customerResults').empty();
            return;
        }

        searchTimeout = setTimeout(function() {
            $.get('{{ route("admin.chat.customers") }}', { search: query })
                .done(function(customers) {
                    let html = '';
                    customers.forEach(function(customer) {
                        html += `
                            <div class="customer-result p-2 border rounded mb-2" style="cursor: pointer;" data-customer-id="${customer.id}">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('customers/') }}/${customer.image || 'default.png'}" class="rounded-circle me-2" width="30" height="30">
                                    <div>
                                        <strong>${customer.f_name} ${customer.l_name}</strong>
                                        <div class="small text-muted">${customer.phone} • ${customer.email}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    $('#customerResults').html(html);
                });
        }, 300);
    });

    // Customer selection
    $(document).on('click', '.customer-result', function() {
        const customerId = $(this).data('customer-id');
        const customerName = $(this).find('strong').text();
        
        $('#selectedCustomerId').val(customerId);
        $('#customerSearch').val(customerName);
        $('#customerResults').empty();
    });

    // Start new chat
    $('#newChatForm').on('submit', function(e) {

        e.preventDefault();
        const customerId = $('#selectedCustomerId').val();
        
        if (!customerId) {
            alert('Please select a customer');
            return;
        }

        $.post('{{ route("admin.chat.start") }}', {
            customer_id: customerId,
            _token: '{{ csrf_token() }}'
        }).done(function(response) {
            window.location.reload();
        }).fail(function(xhr) {
            alert('Error starting conversation');
        });
    });

    // Load conversation
    $('.conversation-item').on('click', function() {
        const conversationId = $(this).data('conversation-id');
        loadConversation(conversationId);
        
        // Update active state
        $('.conversation-item').removeClass('active');
        $(this).addClass('active');
    });

    function loadConversation(conversationId) {
        currentConversationId = conversationId;
        
        // Load conversation UI via AJAX\
        location.href = '{{ route("admin.chat.conversation", ":id") }}'.replace(':id', conversationId);
    //     $.get('{{ route("admin.chat.conversation", ":id") }}'.replace(':id', conversationId))
    //         .done(function(html) {
    //             $('#chatMain').html(html);
    //             initializeChat();
    //         });
    }

    function initializeChat() {
        // Initialize WebSocket connection and chat functionality
        // This will be implemented in the conversation view
    }
});
</script>
@endpush
