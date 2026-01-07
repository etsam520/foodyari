@extends('layouts.dashboard-main')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h3 class="page-header-title text-capitalize">
                    <div class="card-header-icon d-inline-flex mr-2 img">
                        <i class="fa fa-cog"></i>
                    </div>
                    <span>Notification Settings</span>
                </h3>
            </div>
            <div class="col-sm-auto">
                <a href="{{ route('admin.notification.inbox') }}" class="btn btn-outline-primary">
                    <i class="fas fa-inbox mr-2"></i>Back to Inbox
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell me-2"></i>Configure Notification Preferences
                    </h5>
                </div>
                <form action="{{ route('admin.notification.inbox.settings.update') }}" method="POST" id="notification-settings-form">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Configure which types of notifications you want to receive in your inbox.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Order Notifications -->
                            <div class="col-md-6">
                                <div class="notification-category mb-4">
                                    <h6 class="mb-3 text-primary">
                                        <i class="fas fa-shopping-cart me-2"></i>Order Notifications
                                    </h6>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="order_placed" id="order_placed" 
                                               {{ ($settings->order_placed ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="order_placed">
                                            New Order Placed
                                        </label>
                                        <small class="form-text text-muted d-block">Receive notifications when new orders are placed</small>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="order_confirmed" id="order_confirmed"
                                               {{ ($settings->order_confirmed ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="order_confirmed">
                                            Order Confirmed
                                        </label>
                                        <small class="form-text text-muted d-block">Receive notifications when orders are confirmed</small>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="order_cancelled" id="order_cancelled"
                                               {{ ($settings->order_cancelled ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="order_cancelled">
                                            Order Cancelled
                                        </label>
                                        <small class="form-text text-muted d-block">Receive notifications when orders are cancelled</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Restaurant Notifications -->
                            <div class="col-md-6">
                                <div class="notification-category mb-4">
                                    <h6 class="mb-3 text-success">
                                        <i class="fas fa-store me-2"></i>Restaurant Notifications
                                    </h6>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="restaurant_registered" id="restaurant_registered"
                                               {{ ($settings->restaurant_registered ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="restaurant_registered">
                                            New Restaurant Registration
                                        </label>
                                        <small class="form-text text-muted d-block">Receive notifications when new restaurants register</small>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="restaurant_approved" id="restaurant_approved"
                                               {{ ($settings->restaurant_approved ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="restaurant_approved">
                                            Restaurant Approved
                                        </label>
                                        <small class="form-text text-muted d-block">Receive notifications when restaurants are approved</small>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="restaurant_suspended" id="restaurant_suspended"
                                               {{ ($settings->restaurant_suspended ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="restaurant_suspended">
                                            Restaurant Suspended
                                        </label>
                                        <small class="form-text text-muted d-block">Receive notifications when restaurants are suspended</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Notifications -->
                            <div class="col-md-6">
                                <div class="notification-category mb-4">
                                    <h6 class="mb-3 text-info">
                                        <i class="fas fa-users me-2"></i>Customer Notifications
                                    </h6>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="customer_registered" id="customer_registered"
                                               {{ ($settings->customer_registered ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="customer_registered">
                                            New Customer Registration
                                        </label>
                                        <small class="form-text text-muted d-block">Receive notifications when new customers register</small>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="customer_support" id="customer_support"
                                               {{ ($settings->customer_support ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="customer_support">
                                            Customer Support Requests
                                        </label>
                                        <small class="form-text text-muted d-block">Receive notifications for customer support requests</small>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="customer_complaint" id="customer_complaint"
                                               {{ ($settings->customer_complaint ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="customer_complaint">
                                            Customer Complaints
                                        </label>
                                        <small class="form-text text-muted d-block">Receive notifications for customer complaints</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Delivery Notifications -->
                            <div class="col-md-6">
                                <div class="notification-category mb-4">
                                    <h6 class="mb-3 text-warning">
                                        <i class="fas fa-truck me-2"></i>Delivery Notifications
                                    </h6>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="delivery_man_registered" id="delivery_man_registered"
                                               {{ ($settings->delivery_man_registered ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="delivery_man_registered">
                                            New Delivery Man Registration
                                        </label>
                                        <small class="form-text text-muted d-block">Receive notifications when new delivery personnel register</small>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="delivery_assigned" id="delivery_assigned"
                                               {{ ($settings->delivery_assigned ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="delivery_assigned">
                                            Delivery Assigned
                                        </label>
                                        <small class="form-text text-muted d-block">Receive notifications when deliveries are assigned</small>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="delivery_completed" id="delivery_completed"
                                               {{ ($settings->delivery_completed ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="delivery_completed">
                                            Delivery Completed
                                        </label>
                                        <small class="form-text text-muted d-block">Receive notifications when deliveries are completed</small>
                                    </div>
                                </div>
                            </div>

                            <!-- System Notifications -->
                            <div class="col-md-6">
                                <div class="notification-category mb-4">
                                    <h6 class="mb-3 text-secondary">
                                        <i class="fas fa-cogs me-2"></i>System Notifications
                                    </h6>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="system_maintenance" id="system_maintenance"
                                               {{ ($settings->system_maintenance ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="system_maintenance">
                                            System Maintenance
                                        </label>
                                        <small class="form-text text-muted d-block">Receive notifications about system maintenance</small>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="system_updates" id="system_updates"
                                               {{ ($settings->system_updates ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="system_updates">
                                            System Updates
                                        </label>
                                        <small class="form-text text-muted d-block">Receive notifications about system updates</small>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="security_alerts" id="security_alerts"
                                               {{ ($settings->security_alerts ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="security_alerts">
                                            Security Alerts
                                        </label>
                                        <small class="form-text text-muted d-block">Receive notifications about security issues</small>
                                    </div>
                                </div>
                            </div>

                            <!-- General Settings -->
                            <div class="col-md-6">
                                <div class="notification-category mb-4">
                                    <h6 class="mb-3 text-dark">
                                        <i class="fas fa-sliders-h me-2"></i>General Settings
                                    </h6>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="email_notifications" id="email_notifications"
                                               {{ ($settings->email_notifications ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_notifications">
                                            Email Notifications
                                        </label>
                                        <small class="form-text text-muted d-block">Also receive notifications via email</small>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="push_notifications" id="push_notifications"
                                               {{ ($settings->push_notifications ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="push_notifications">
                                            Push Notifications
                                        </label>
                                        <small class="form-text text-muted d-block">Receive browser push notifications</small>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="sound_notifications" id="sound_notifications"
                                               {{ ($settings->sound_notifications ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sound_notifications">
                                            Sound Notifications
                                        </label>
                                        <small class="form-text text-muted d-block">Play sound for new notifications</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-secondary" id="reset-defaults">
                                    <i class="fas fa-undo me-2"></i>Reset to Defaults
                                </button>
                            </div>
                            <div class="col-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .notification-category {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid;
    }

    .notification-category:nth-child(1) { border-left-color: #007bff; }
    .notification-category:nth-child(2) { border-left-color: #28a745; }
    .notification-category:nth-child(3) { border-left-color: #17a2b8; }
    .notification-category:nth-child(4) { border-left-color: #ffc107; }
    .notification-category:nth-child(5) { border-left-color: #6c757d; }
    .notification-category:nth-child(6) { border-left-color: #343a40; }

    .form-check-input:checked {
        background-color: #007bff;
        border-color: #007bff;
    }

    .form-check-input:focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    .card-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    }

    .btn-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    }
</style>
@endpush

@push('javascript')
<script>
$(document).ready(function() {
    // Handle form submission
    $('#notification-settings-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...').prop('disabled', true);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showToast('Settings saved successfully!', 'success');
                } else {
                    showToast('Failed to save settings. Please try again.', 'error');
                }
            },
            error: function(xhr) {
                let message = 'Failed to save settings. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showToast(message, 'error');
                console.error('Error:', xhr.responseJSON);
            },
            complete: function() {
                // Restore button state
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });
    
    // Handle reset to defaults
    $('#reset-defaults').on('click', function() {
        if (confirm('Reset all notification settings to defaults? This will enable all notifications.')) {
            // Check all checkboxes
            $('#notification-settings-form input[type="checkbox"]').prop('checked', true);
            showToast('Settings reset to defaults. Don\'t forget to save!', 'info');
        }
    });
    
    // Add visual feedback when switches are toggled
    $('.form-check-input').on('change', function() {
        const label = $(this).next('.form-check-label');
        if ($(this).is(':checked')) {
            label.addClass('text-success').removeClass('text-muted');
        } else {
            label.addClass('text-muted').removeClass('text-success');
        }
    });
    
    // Initialize visual state
    $('.form-check-input').each(function() {
        const label = $(this).next('.form-check-label');
        if ($(this).is(':checked')) {
            label.addClass('text-success').removeClass('text-muted');
        } else {
            label.addClass('text-muted').removeClass('text-success');
        }
    });
    
    function showToast(message, type = 'success') {
        const icon = type === 'success' ? 'fa-check-circle' : 
                    type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
        const bgColor = type === 'success' ? '#28a745' : 
                       type === 'error' ? '#dc3545' : '#17a2b8';
        
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
</style>
@endpush