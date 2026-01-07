{{-- Notification JavaScript Component for Real-time Updates --}}
<script>
class DeliverymanNotificationService {
    constructor() {
        this.unreadCount = 0;
        this.notifications = [];
        this.isInitialized = false;
        
        this.init();
    }
    
    init() {
        if (this.isInitialized) return;
        
        this.loadInitialCount();
        this.startPeriodicUpdates();
        this.bindEvents();
        
        this.isInitialized = true;
    }
    
    loadInitialCount() {
        $.ajax({
            url: '{{ route("deliveryman.admin.notifications.count") }}',
            method: 'GET',
            success: (response) => {
                if (response.success) {
                    this.updateNotificationCount(response.count);
                }
            },
            error: (xhr, status, error) => {
                console.error('Failed to load notification count:', error);
            }
        });
    }
    
    startPeriodicUpdates() {
        // Check for new notifications every 30 seconds
        setInterval(() => {
            this.checkForNewNotifications();
        }, 30000);
    }
    
    checkForNewNotifications() {
        $.ajax({
            url: '{{ route("deliveryman.admin.notifications.count") }}',
            method: 'GET',
            success: (response) => {
                if (response.success) {
                    const newCount = response.count;
                    
                    // If count increased, we have new notifications
                    if (newCount > this.unreadCount) {
                        this.showNewNotificationAlert(newCount - this.unreadCount);
                    }
                    
                    this.updateNotificationCount(newCount);
                }
            },
            error: (xhr, status, error) => {
                console.error('Failed to check for new notifications:', error);
            }
        });
    }
    
    updateNotificationCount(count) {
        this.unreadCount = count;
        
        // Update header badge
        const headerBadge = $('#notificationCount');
        const sidebarBadge = $('#sidebarNotificationCount');
        
        if (count > 0) {
            headerBadge.text(count).show();
            sidebarBadge.text(count).show();
        } else {
            headerBadge.hide();
            sidebarBadge.hide();
        }
        
        // Update page title if on notifications page
        if (window.location.pathname.includes('notifications')) {
            document.title = count > 0 ? `(${count}) Notifications - Foodyari` : 'Notifications - Foodyari';
        }
    }
    
    showNewNotificationAlert(newCount) {
        // Create a subtle notification for new messages
        const alertHtml = `
            <div class="alert alert-info alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" 
                 id="newNotificationAlert">
                <div class="d-flex align-items-center">
                    <i class="feather-bell me-2"></i>
                    <div>
                        <strong>New Notification${newCount > 1 ? 's' : ''}!</strong><br>
                        <small>You have ${newCount} new notification${newCount > 1 ? 's' : ''}</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Remove existing alert if any
        $('#newNotificationAlert').remove();
        
        // Add new alert
        $('body').append(alertHtml);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            $('#newNotificationAlert').fadeOut(() => {
                $(this).remove();
            });
        }, 5000);
        
        // Play notification sound if supported
        this.playNotificationSound();
    }
    
    playNotificationSound() {
        try {
            // Check if browser supports Audio API
            if (typeof Audio !== 'undefined') {
                // Create a simple notification sound
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);
                
                gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + 0.2);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.2);
            }
        } catch (error) {
            console.log('Notification sound not supported:', error);
        }
    }
    
    bindEvents() {
        // Mark all notifications as read from header dropdown
        $(document).on('click', '#markAllRead', (e) => {
            e.preventDefault();
            this.markAllAsRead();
        });
        
        // Refresh notifications when dropdown is opened
        $(document).on('show.bs.dropdown', '#notificationDropdown', () => {
            this.loadHeaderNotifications();
        });
    }
    
    markAllAsRead() {
        $.ajax({
            url: '{{ route("deliveryman.admin.notifications.markAllAsRead") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                if (response.success) {
                    this.updateNotificationCount(0);
                    this.showSuccessMessage('All notifications marked as read');
                    
                    // Refresh the dropdown content
                    this.loadHeaderNotifications();
                    
                    // If on notifications page, refresh the list
                    if (typeof window.notificationManager !== 'undefined') {
                        window.notificationManager.loadNotifications();
                    }
                }
            },
            error: (xhr, status, error) => {
                console.error('Failed to mark all as read:', error);
                this.showErrorMessage('Failed to mark notifications as read');
            }
        });
    }
    
    loadHeaderNotifications() {
        $.ajax({
            url: '{{ route("deliveryman.admin.notifications.fetch") }}',
            method: 'GET',
            data: {
                page: 1,
                per_page: 5,
                type: 'all'
            },
            success: (response) => {
                if (response.success) {
                    const notifications = response.notifications;
                    let html = '';
                    
                    if (notifications.length === 0) {
                        html = '<div class="dropdown-item text-center text-muted py-3">No notifications</div>';
                    } else {
                        notifications.forEach(notification => {
                            const isRead = notification.is_read;
                            const iconClass = this.getNotificationIcon(notification.type);
                            const iconColor = this.getNotificationIconColor(notification.type);
                            
                            html += `
                                <a class="dropdown-item notification-dropdown-item ${!isRead ? 'unread' : ''}" 
                                   href="{{ route('deliveryman.admin.notifications') }}"
                                   data-id="${notification.id}">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3 mt-1">
                                            <i class="${iconClass} text-${iconColor}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 ${!isRead ? 'fw-bold' : ''}" style="font-size: 0.85rem;">
                                                ${notification.title}
                                            </h6>
                                            <p class="mb-1 text-muted" style="font-size: 0.75rem;">
                                                ${notification.message.length > 60 ? notification.message.substring(0, 60) + '...' : notification.message}
                                            </p>
                                            <small class="text-muted">${notification.created_at}</small>
                                        </div>
                                        ${!isRead ? '<div class="ms-2"><span class="badge bg-warning rounded-pill">New</span></div>' : ''}
                                    </div>
                                </a>
                            `;
                        });
                    }
                    
                    $('#notificationList').html(html);
                }
            },
            error: (xhr, status, error) => {
                console.error('Failed to load header notifications:', error);
                $('#notificationList').html('<div class="dropdown-item text-center text-danger py-3">Failed to load notifications</div>');
            }
        });
    }
    
    getNotificationIcon(type) {
        const icons = {
            'order': 'feather-shopping-bag',
            'delivery': 'feather-truck',
            'payment': 'feather-credit-card',
            'system': 'feather-settings',
            'default': 'feather-bell'
        };
        return icons[type] || icons.default;
    }
    
    getNotificationIconColor(type) {
        const colors = {
            'order': 'primary',
            'delivery': 'success',
            'payment': 'warning',
            'system': 'info',
            'default': 'secondary'
        };
        return colors[type] || colors.default;
    }
    
    showSuccessMessage(message) {
        this.showToast(message, 'success');
    }
    
    showErrorMessage(message) {
        this.showToast(message, 'error');
    }
    
    showToast(message, type = 'info') {
        const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
        
        const toastHtml = `
            <div class="toast align-items-center text-white ${bgClass} border-0 position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999;" 
                 id="notificationToast" 
                 role="alert" 
                 aria-live="assertive" 
                 aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                            data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        // Remove existing toast
        $('#notificationToast').remove();
        
        // Add new toast
        $('body').append(toastHtml);
        
        // Show toast
        const toast = new bootstrap.Toast(document.getElementById('notificationToast'));
        toast.show();
    }
}

// Initialize the notification service when document is ready
$(document).ready(() => {
    // Only initialize if we're authenticated
    @if(Auth::guard('delivery_men')->check())
        window.deliverymanNotificationService = new DeliverymanNotificationService();
    @endif
});
</script>

<style>
/* Additional styles for notification dropdown */
.notification-dropdown-item {
    padding: 0.75rem 1rem !important;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s ease;
}

.notification-dropdown-item:hover {
    background-color: #f8f9fa !important;
}

.notification-dropdown-item.unread {
    background-color: #fff3cd !important;
    border-left: 3px solid #ffc107;
}

.notification-dropdown-item:last-child {
    border-bottom: none;
}

/* Toast animations */
.toast {
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Notification badges */
.badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
    }
    70% {
        box-shadow: 0 0 0 5px rgba(220, 53, 69, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
    }
}
</style>