@extends('layouts.dashboard-main')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h3 class="page-header-title text-capitalize">
                    <div class="card-header-icon d-inline-flex mr-2 img">
                        <i class="fa fa-flask"></i>
                    </div>
                    <span>Notification System Test</span>
                </h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-vial me-2"></i>Test Admin Notification System
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Use these buttons to test different types of notifications. Each test will send a notification to your admin inbox.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="test-section mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-shopping-cart me-2"></i>Order Notifications
                                </h6>
                                
                                <button class="btn btn-outline-primary mb-2 test-btn" data-type="order-placed">
                                    <i class="fas fa-plus-circle me-2"></i>Test New Order
                                </button>
                                
                                <button class="btn btn-outline-success mb-2 test-btn" data-type="order-confirmed">
                                    <i class="fas fa-check me-2"></i>Test Order Confirmed
                                </button>
                                
                                <button class="btn btn-outline-danger mb-2 test-btn" data-type="order-cancelled">
                                    <i class="fas fa-times me-2"></i>Test Order Cancelled
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="test-section mb-4">
                                <h6 class="text-success mb-3">
                                    <i class="fas fa-store me-2"></i>Restaurant Notifications
                                </h6>
                                
                                <button class="btn btn-outline-info mb-2 test-btn" data-type="restaurant-registered">
                                    <i class="fas fa-user-plus me-2"></i>Test Restaurant Registration
                                </button>
                                
                                <button class="btn btn-outline-success mb-2 test-btn" data-type="restaurant-approved">
                                    <i class="fas fa-thumbs-up me-2"></i>Test Restaurant Approved
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="test-section mb-4">
                                <h6 class="text-info mb-3">
                                    <i class="fas fa-users me-2"></i>Customer Notifications
                                </h6>
                                
                                <button class="btn btn-outline-primary mb-2 test-btn" data-type="customer-registered">
                                    <i class="fas fa-user-plus me-2"></i>Test Customer Registration
                                </button>
                                
                                <button class="btn btn-outline-warning mb-2 test-btn" data-type="customer-support">
                                    <i class="fas fa-headset me-2"></i>Test Support Request
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="test-section mb-4">
                                <h6 class="text-warning mb-3">
                                    <i class="fas fa-truck me-2"></i>Delivery Notifications
                                </h6>
                                
                                <button class="btn btn-outline-info mb-2 test-btn" data-type="delivery-registered">
                                    <i class="fas fa-motorcycle me-2"></i>Test Delivery Registration
                                </button>
                                
                                <button class="btn btn-outline-success mb-2 test-btn" data-type="delivery-completed">
                                    <i class="fas fa-check-circle me-2"></i>Test Delivery Completed
                                </button>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="test-section mb-4">
                                <h6 class="text-secondary mb-3">
                                    <i class="fas fa-cogs me-2"></i>System Notifications
                                </h6>
                                
                                <button class="btn btn-outline-secondary mb-2 test-btn" data-type="system-maintenance">
                                    <i class="fas fa-tools me-2"></i>Test Maintenance Alert
                                </button>
                                
                                <button class="btn btn-outline-dark mb-2 test-btn" data-type="security-alert">
                                    <i class="fas fa-shield-alt me-2"></i>Test Security Alert
                                </button>
                                
                                <button class="btn btn-primary mb-2 test-btn" data-type="generic-test">
                                    <i class="fas fa-bell me-2"></i>Send Generic Test
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-chart-bar me-2"></i>Quick Stats
                                    </h6>
                                    <div id="notification-stats">
                                        <div class="d-flex justify-content-between">
                                            <span>Total Notifications:</span>
                                            <span class="fw-bold" id="total-notifications">-</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Unread:</span>
                                            <span class="fw-bold text-danger" id="unread-notifications">-</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Read:</span>
                                            <span class="fw-bold text-success" id="read-notifications">-</span>
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary mt-2" id="refresh-stats">
                                        <i class="fas fa-sync-alt me-1"></i>Refresh
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-link me-2"></i>Quick Actions
                                    </h6>
                                    <a href="{{ route('admin.notification.inbox') }}" class="btn btn-primary btn-sm me-2 mb-2">
                                        <i class="fas fa-inbox me-1"></i>Open Inbox
                                    </a>
                                    <a href="{{ route('admin.notification.inbox.settings') }}" class="btn btn-secondary btn-sm mb-2">
                                        <i class="fas fa-cog me-1"></i>Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
$(document).ready(function() {
    // Load initial stats
    loadStats();
    
    // Handle test button clicks
    $('.test-btn').on('click', function() {
        const button = $(this);
        const type = button.data('type');
        const originalText = button.html();
        
        // Show loading state
        button.html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...').prop('disabled', true);
        
        // Send test notification
        sendTestNotification(type).finally(() => {
            // Restore button state
            button.html(originalText).prop('disabled', false);
            // Refresh stats
            loadStats();
        });
    });
    
    // Refresh stats button
    $('#refresh-stats').on('click', function() {
        const button = $(this);
        const originalText = button.html();
        button.html('<i class="fas fa-spinner fa-spin me-1"></i>Loading...').prop('disabled', true);
        
        loadStats().finally(() => {
            button.html(originalText).prop('disabled', false);
        });
    });
    
    function sendTestNotification(type) {
        return $.ajax({
            url: '{{ route("admin.notification.test-send") }}',
            type: 'GET',
            data: { test_type: type },
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                } else {
                    showToast(response.message || 'Failed to send notification', 'error');
                }
            },
            error: function(xhr) {
                let message = 'Failed to send test notification';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showToast(message, 'error');
                console.error('Error:', xhr.responseJSON);
            }
        });
    }
    
    function loadStats() {
        return $.ajax({
            url: '{{ route("admin.notification.inbox.count") }}',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#unread-notifications').text(response.unread_count || 0);
                    
                    // Calculate totals (you might want to add a separate endpoint for this)
                    const unread = parseInt(response.unread_count || 0);
                    $('#total-notifications').text('Loading...');
                    $('#read-notifications').text('Loading...');
                }
            },
            error: function() {
                $('#total-notifications').text('Error');
                $('#unread-notifications').text('Error');
                $('#read-notifications').text('Error');
            }
        });
    }
    
    function showToast(message, type = 'success') {
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const bgColor = type === 'success' ? '#28a745' : '#dc3545';
        
        const toast = $(`
            <div class="toast-notification" style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                padding: 1rem 1.5rem;
                max-width: 400px;
                z-index: 9999;
                border-left: 4px solid ${bgColor};
                animation: slideIn 0.3s ease;
            ">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas ${icon}" style="color: ${bgColor}; font-size: 1.2rem;"></i>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: #333;">${message}</div>
                    </div>
                    <button onclick="$(this).parent().parent().remove()" style="background: none; border: none; color: #999; cursor: pointer; padding: 0.25rem;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `);
        
        $('body').append(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }
});
</script>

<style>
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.test-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.test-btn {
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
}
</style>
@endpush