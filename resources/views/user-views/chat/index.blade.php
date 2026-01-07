@extends('user-views.restaurant.layouts.main')

@push('css')
<style>
    .chat-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .chat-card {
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .chat-sidebar {
        background-color: #f8f9fa;
        border-right: 1px solid #e0e0e0;
        min-height: 70vh;
    }

    .conversation-item {
        cursor: pointer;
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .conversation-item:hover {
        background-color: #e3f2fd;
    }

    .conversation-item.active {
        background-color: #2196f3;
        color: white;
    }

    .chat-main {
        background-color: white;
        min-height: 70vh;
    }

    .chat-welcome {
        height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .support-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        color: white;
        margin-bottom: 20px;
    }

    .support-card h2 {
        margin-bottom: 15px;
        font-weight: 600;
    }

    .support-card p {
        margin-bottom: 25px;
        opacity: 0.9;
    }

    .btn-start-chat {
        background-color: rgba(255,255,255,0.2);
        border: 2px solid white;
        color: white;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-start-chat:hover {
        background-color: white;
        color: #667eea;
        transform: translateY(-2px);
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .quick-action-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .quick-action-card:hover {
        transform: translateY(-5px);
    }

    .quick-action-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        color: white;
        font-size: 24px;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
    }

    .unread-badge {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        color: white;
        border-radius: 50%;
        padding: 2px 8px;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>
@endpush

@section('containt')
<div class="chat-container">
    <div class="row">
        <div class="col-12">
            @if($conversations->count() > 0)
                <div class="card chat-card">
                    <div class="row g-0">
                        <!-- Chat Sidebar -->
                        <div class="col-md-4 chat-sidebar">
                            <div class="p-3 border-bottom">
                                <h5 class="mb-0">Messages</h5>
                            </div>
                            <div class="conversation-list">
                                @foreach($conversations as $conversation)
                                    @php
                                        $otherUser = $conversation->getOtherUser(
                                            auth('customer')->user()->id ?? Session::get('userInfo')->id, 
                                            'customer'
                                        );
                                    @endphp
                                    <div class="conversation-item" 
                                         onclick="window.location.href='{{ route('user.chat.conversation', $conversation->id) }}'">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('admins/' . ($otherUser['user']->image ?? 'default.png')) }}" 
                                                 class="user-avatar me-3" alt="Admin Avatar">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $otherUser['user']->full_name ?? 'Support Team' }}</h6>
                                                <p class="mb-0 text-muted small">
                                                    @if($conversation->lastMessage)
                                                        {{ Str::limit($conversation->lastMessage->message, 40) }}
                                                    @else
                                                        Start a conversation...
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                @if($conversation->unread_message_count > 0)
                                                    <span class="unread-badge">{{ $conversation->unread_message_count }}</span>
                                                @endif
                                                <div class="text-muted small">
                                                    {{ $conversation->last_message_time ? $conversation->last_message_time->diffForHumans() : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Chat Main -->
                        <div class="col-md-8 chat-main">
                            <div class="chat-welcome">
                                <div class="text-center">
                                    <i class="fas fa-comments fa-4x mb-3" style="opacity: 0.7;"></i>
                                    <h4>Select a conversation to continue</h4>
                                    <p>Choose a conversation from the sidebar to start chatting</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Welcome Screen -->
                <div class="support-card">
                    <div class="mb-4">
                        <i class="fas fa-headset fa-4x mb-3"></i>
                        <h2>Need Help? We're Here for You!</h2>
                        <p>Our support team is ready to assist you with any questions or concerns. Start a conversation with us and get the help you need.</p>
                        
                        <form action="{{ route('user.chat.start-admin') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-start-chat">
                                <i class="fas fa-comment me-2"></i>Start Chat with Support
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h6>General Questions</h6>
                        <p class="text-muted small">Ask about our services, features, or general inquiries</p>
                    </div>
                    
                    <div class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h6>Order Support</h6>
                        <p class="text-muted small">Get help with your orders, delivery, or payment issues</p>
                    </div>
                    
                    <div class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <h6>Account Issues</h6>
                        <p class="text-muted small">Need help with your account settings or profile?</p>
                    </div>
                    
                    <div class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="fas fa-bug"></i>
                        </div>
                        <h6>Technical Support</h6>
                        <p class="text-muted small">Report bugs or technical issues you're experiencing</p>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Frequently Asked Questions</h5>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                        How do I track my order?
                                    </button>
                                </h2>
                                <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        You can track your order by going to the "My Orders" section in your account dashboard.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                        How do I change my delivery address?
                                    </button>
                                </h2>
                                <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        You can update your delivery address in your account settings under "Addresses".
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                        What payment methods do you accept?
                                    </button>
                                </h2>
                                <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        We accept credit/debit cards, digital wallets, and cash on delivery.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
$(document).ready(function() {
    // Add any additional JavaScript functionality here
    console.log('Chat page loaded');
});
</script>
@endpush
