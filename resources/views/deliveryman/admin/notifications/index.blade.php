@extends('deliveryman.admin.layouts.main')

@section('title')
    Notifications
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="page-header bg-white p-4 rounded shadow-sm mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="mb-0">
                            <i class="feather-bell me-2 text-primary"></i>
                            Notifications
                        </h4>
                        <p class="text-muted mb-0">Stay updated with your delivery activities</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm filter-btn active" data-filter="all">
                                All (<span id="allCount">0</span>)
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm filter-btn" data-filter="unread">
                                Unread (<span id="unreadCount">0</span>)
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm filter-btn" data-filter="read">
                                Read (<span id="readCount">0</span>)
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="notification-stats">
                                <small class="text-muted">
                                    <span id="totalNotifications">0</span> total notifications •
                                    <span id="recentNotifications">0</span> in the last 7 days
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-sm btn-outline-success me-2" id="markAllReadBtn">
                                <i class="feather-check-circle me-1"></i> Mark All Read
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="deleteAllBtn">
                                <i class="feather-trash-2 me-1"></i> Clear All
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <!-- Loading State -->
                    <div id="loadingState" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2">Loading notifications...</p>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" class="text-center py-5" style="display: none;">
                        <div class="mb-3">
                            <i class="feather-bell text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="text-muted">No notifications found</h5>
                        <p class="text-muted">You're all caught up! New notifications will appear here.</p>
                    </div>

                    <!-- Notifications Container -->
                    <div id="notificationsContainer">
                        <!-- Notifications will be loaded here dynamically -->
                    </div>

                    <!-- Load More Button -->
                    <div class="text-center py-3 border-top" id="loadMoreContainer" style="display: none;">
                        <button type="button" class="btn btn-outline-primary" id="loadMoreBtn">
                            <i class="feather-refresh-cw me-1"></i> Load More
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notification Detail Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">Notification Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="notificationModalBody">
                <!-- Notification details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="deleteNotificationBtn">
                    <i class="feather-trash-2 me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .notification-item {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .notification-item:hover {
        background-color: #f8f9fa;
    }
    
    .notification-item.unread {
        background-color: #fff3cd;
        border-left: 4px solid #ffc107;
    }
    
    .notification-item.read {
        opacity: 0.8;
    }
    
    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    
    .notification-time {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .filter-btn.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }
    
    .notification-dropdown {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .notification-dropdown .dropdown-item {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .notification-dropdown .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    
    .notification-toast {
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
    
    .notification-item .dropdown-menu {
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }
    
    .notification-item .dropdown-item {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }
    
    .notification-item .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    
    .notification-item .dropdown-divider {
        margin: 0.25rem 0;
    }
</style>
@endpush

@push('javascript')
<script>
class NotificationManager {
    constructor() {
        this.currentPage = 1;
        this.currentFilter = 'all';
        this.hasMorePages = true;
        this.selectedNotificationId = null;
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.loadNotifications();
        this.loadStats();
        this.loadNotificationCount();
        
        // Auto-refresh every 30 seconds
        setInterval(() => {
            this.loadNotificationCount();
        }, 30000);
    }
    
    bindEvents() {
        $('.filter-btn').on('click', (e) => {
            const filter = $(e.target).data('filter');
            this.changeFilter(filter);
        });
        
        $('#markAllReadBtn').on('click', () => {
            this.markAllAsRead();
        });
        
        $('#deleteAllBtn').on('click', () => {
            this.deleteAllNotifications();
        });
        
        $('#loadMoreBtn').on('click', () => {
            this.loadMoreNotifications();
        });
        
        $('#deleteNotificationBtn').on('click', () => {
            this.deleteSelectedNotification();
        });
        
        // Header notification dropdown events
        $('#markAllRead').on('click', () => {
            this.markAllAsRead();
        });
        
        $('#notificationDropdown').on('show.bs.dropdown', () => {
            this.loadHeaderNotifications();
        });
    }
    
    changeFilter(filter) {
        this.currentFilter = filter;
        this.currentPage = 1;
        this.hasMorePages = true;
        
        $('.filter-btn').removeClass('active');
        $(`.filter-btn[data-filter="${filter}"]`).addClass('active');
        
        this.loadNotifications();
    }
    
    loadNotifications(append = false) {
        if (!append) {
            $('#loadingState').show();
            $('#emptyState').hide();
            $('#notificationsContainer').empty();
        }
        
        $.ajax({
            url: '{{ route("deliveryman.admin.notifications.fetch") }}',
            method: 'GET',
            data: {
                page: this.currentPage,
                per_page: 10,
                type: this.currentFilter
            },
            success: (response) => {
                $('#loadingState').hide();
                
                if (response.success) {
                    const notifications = response.notifications;
                    
                    if (notifications.length === 0 && !append) {
                        $('#emptyState').show();
                        $('#loadMoreContainer').hide();
                        return;
                    }
                    
                    notifications.forEach(notification => {
                        console.log(notification);
                        this.renderNotification(notification, append);
                    });
                    
                    // Handle pagination
                    this.hasMorePages = response.pagination.has_more;
                    $('#loadMoreContainer').toggle(this.hasMorePages);
                    
                    this.loadStats();
                } else {
                    this.showError('Failed to load notifications');
                }
            },
            error: () => {
                $('#loadingState').hide();
                this.showError('Error loading notifications');
            }
        });
    }
    
    loadMoreNotifications() {
        this.currentPage++;
        this.loadNotifications(true);
    }
    
    renderNotification(notification, append = false) {
        const isRead = notification.is_read;
        const readClass = isRead ? 'read' : 'unread';
        const iconClass = this.getNotificationIcon(notification.type);
        const iconColor = this.getNotificationIconColor(notification.type);
        
        const html = `
            <div class="notification-item ${readClass} p-3" data-id="${notification.id}">
                <div class="row align-items-center">
                    <div class="col-1">
                        <div class="notification-icon bg-${iconColor} text-white">
                            <i class="${iconClass}"></i>
                        </div>
                    </div>
                    <div class="col-9">
                        <h6 class="mb-1 ${!isRead ? 'fw-bold' : ''}">${notification.title}</h6>
                        <p class="mb-1 text-muted">${notification.message}</p>
                        <small class="notification-time">${notification.created_at}</small>
                    </div>
                    <div class="col-2 text-end">
                        ${!isRead ? '<span class="badge bg-warning rounded-pill">New</span>' : ''}
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="feather-more-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item mark-read-btn" href="#" data-id="${notification.id}" data-read="${isRead}">
                                    <i class="feather-${isRead ? 'eye-off' : 'check'} me-2"></i>${isRead ? 'Mark as Unread' : 'Mark as Read'}
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item view-details-btn" href="#" data-id="${notification.id}">
                                    <i class="feather-eye me-2"></i>View Details
                                </a></li>
                                <li><a class="dropdown-item delete-btn" href="#" data-id="${notification.id}">
                                    <i class="feather-trash-2 me-2"></i>Delete
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        if (append) {
            $('#notificationsContainer').append(html);
        } else {
            $('#notificationsContainer').prepend(html);
        }
        
        // Bind click events for new elements
        $(`.notification-item[data-id="${notification.id}"]`).on('click', (e) => {
            // Only show details if not clicking on dropdown or buttons
            if (!$(e.target).closest('.dropdown, .btn').length) {
                this.showNotificationDetails(notification);
            }
        });
        
        $(`.mark-read-btn[data-id="${notification.id}"]`).on('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.markAsReadSilently(notification.id);
        });
        
        $(`.view-details-btn[data-id="${notification.id}"]`).on('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.showNotificationDetails(notification);
        });
        
        $(`.delete-btn[data-id="${notification.id}"]`).on('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.deleteNotification(notification.id);
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
    
    showNotificationDetails(notification) {
        $('#notificationModalLabel').text(notification.title);
        
        const detailsHtml = `
            <div class="row">
                <div class="col-12">
                    <p><strong>Message:</strong> ${notification.message}</p>
                    <p><strong>Type:</strong> <span class="badge bg-secondary">${notification.type}</span></p>
                    <p><strong>Status:</strong> 
                        <span class="badge ${notification.is_read ? 'bg-success' : 'bg-warning'}">
                            ${notification.is_read ? 'Read' : 'Unread'}
                        </span>
                    </p>
                    <p><strong>Received:</strong> ${notification.created_at_full}</p>
                    ${notification.data && Object.keys(notification.data).length > 1 ? `
                        <hr>
                        <h6>Additional Details:</h6>
                        <pre class="bg-light p-3 rounded">${JSON.stringify(notification.data, null, 2)}</pre>
                    ` : ''}
                </div>
            </div>
        `;
        
        $('#notificationModalBody').html(detailsHtml);
        this.selectedNotificationId = notification.id;
        
        // Mark as read when viewing details
        if (!notification.is_read) {
            this.markAsRead(notification.id);
        }
        
        $('#notificationModal').modal('show');
    }
    
    toggleReadStatus(id) {
        this.markAsRead(id);
    }
    
    markAsRead(id) {
        $.ajax({
            url: '{{ url("/") }}/deliveryman/admin/notifications/' + id + '/read',
            method: 'GET',
            success: (response) => {
                if (response.success) {
                    $(`.notification-item[data-id="${id}"]`)
                        .removeClass('unread')
                        .addClass('read')
                        .find('.badge').remove();
                    
                    this.loadStats();
                    this.loadNotificationCount();
                    this.showSuccess('Notification marked as read');
                }
            },
            error: () => {
                this.showError('Failed to mark notification as read');
            }
        });
    }
    
    markAsReadSilently(id) {
        const $notificationItem = $(`.notification-item[data-id="${id}"]`);
        const $markReadBtn = $notificationItem.find('.mark-read-btn');
        const isCurrentlyRead = $markReadBtn.data('read');
        
        $.ajax({
            url: '{{ url("/") }}/deliveryman/admin/notifications/' + id + '/read',
            method: 'GET',
            success: (response) => {
                if (response.success) {
                    if (!isCurrentlyRead) {
                        // Mark as read
                        $notificationItem.removeClass('unread').addClass('read');
                        $notificationItem.find('.badge').fadeOut(300, function() {
                            $(this).remove();
                        });
                        
                        // Update dropdown button
                        $markReadBtn
                            .html('<i class="feather-eye-off me-2"></i>Mark as Unread')
                            .data('read', true);
                        
                        this.showToast('Marked as read', 'success');
                    } else {
                        // Mark as unread (if we implement this feature later)
                        $notificationItem.removeClass('read').addClass('unread');
                        $notificationItem.find('.col-2').prepend('<span class="badge bg-warning rounded-pill">New</span>');
                        
                        // Update dropdown button
                        $markReadBtn
                            .html('<i class="feather-check me-2"></i>Mark as Read')
                            .data('read', false);
                        
                        this.showToast('Marked as unread', 'success');
                    }
                    
                    // Update stats and counts quietly
                    this.loadStats();
                    this.loadNotificationCount();
                }
            },
            error: () => {
                this.showToast('Failed to update notification', 'error');
            }
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
                    $('.notification-item')
                        .removeClass('unread')
                        .addClass('read')
                        .find('.badge').remove();
                    
                    this.loadStats();
                    this.loadNotificationCount();
                    this.showSuccess('All notifications marked as read');
                }
            },
            error: () => {
                this.showError('Failed to mark all notifications as read');
            }
        });
    }
    
    deleteNotification(id) {
        if (!confirm('Are you sure you want to delete this notification?')) {
            return;
        }
        
        $.ajax({
            url: '{{ url("/") }}/deliveryman/admin/notifications/' + id,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                if (response.success) {
                    $(`.notification-item[data-id="${id}"]`).fadeOut(() => {
                        $(this).remove();
                    });
                    
                    this.loadStats();
                    this.loadNotificationCount();
                    this.showSuccess('Notification deleted');
                }
            },
            error: () => {
                this.showError('Failed to delete notification');
            }
        });
    }
    
    deleteSelectedNotification() {
        if (this.selectedNotificationId) {
            this.deleteNotification(this.selectedNotificationId);
            $('#notificationModal').modal('hide');
        }
    }
    
    deleteAllNotifications() {
        if (!confirm('Are you sure you want to delete ALL notifications? This action cannot be undone.')) {
            return;
        }
        
        $.ajax({
            url: '{{ route("deliveryman.admin.notifications.deleteAll") }}',
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                if (response.success) {
                    $('#notificationsContainer').empty();
                    $('#emptyState').show();
                    $('#loadMoreContainer').hide();
                    
                    this.loadStats();
                    this.loadNotificationCount();
                    this.showSuccess('All notifications deleted');
                }
            },
            error: () => {
                this.showError('Failed to delete all notifications');
            }
        });
    }
    
    loadStats() {
        $.ajax({
            url: '{{ route("deliveryman.admin.notifications.stats") }}',
            method: 'GET',
            success: (response) => {
                if (response.success) {
                    const stats = response.stats;
                    $('#allCount').text(stats.total);
                    $('#unreadCount').text(stats.unread);
                    $('#readCount').text(stats.read);
                    $('#totalNotifications').text(stats.total);
                    $('#recentNotifications').text(stats.recent);
                }
            }
        });
    }
    
    loadNotificationCount() {
        $.ajax({
            url: '{{ route("deliveryman.admin.notifications.count") }}',
            method: 'GET',
            success: (response) => {
                if (response.success) {
                    const count = response.count;
                    
                    // Update header notification badge
                    if (count > 0) {
                        $('#notificationCount').text(count).show();
                        $('#sidebarNotificationCount').text(count).show();
                    } else {
                        $('#notificationCount').hide();
                        $('#sidebarNotificationCount').hide();
                    }
                }
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
                        html = '<div class="dropdown-item text-center text-muted">No notifications</div>';
                    } else {
                        notifications.forEach(notification => {
                            const isRead = notification.is_read;
                            html += `
                                <a class="dropdown-item ${!isRead ? 'bg-light' : ''}" href="{{ route('deliveryman.admin.notifications') }}">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="${this.getNotificationIcon(notification.type)} text-${this.getNotificationIconColor(notification.type)}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 ${!isRead ? 'fw-bold' : ''}" style="font-size: 0.85rem;">${notification.title}</h6>
                                            <p class="mb-0 text-muted" style="font-size: 0.75rem;">${notification.message.substring(0, 50)}${notification.message.length > 50 ? '...' : ''}</p>
                                            <small class="text-muted">${notification.created_at}</small>
                                        </div>
                                        ${!isRead ? '<span class="badge bg-warning rounded-pill ms-2">•</span>' : ''}
                                    </div>
                                </a>
                            `;
                        });
                    }
                    
                    $('#notificationList').html(html);
                }
            }
        });
    }
    
    showSuccess(message) {
        this.showToast(message, 'success');
    }
    
    showError(message) {
        this.showToast(message, 'error');
    }
    
    showToast(message, type = 'info') {
        const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
        const icon = type === 'success' ? 'feather-check-circle' : type === 'error' ? 'feather-x-circle' : 'feather-info';
        
        const toastHtml = `
            <div class="toast align-items-center text-white ${bgClass} border-0 position-fixed notification-toast" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" 
                 id="notificationToast_${Date.now()}" 
                 role="alert" 
                 aria-live="assertive" 
                 aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <i class="${icon} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                            data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        // Add new toast
        $('body').append(toastHtml);
        
        // Show toast
        const toastElement = $('body').find('.notification-toast').last()[0];
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: type === 'success' ? 2000 : 4000
        });
        toast.show();
        
        // Remove element after it's hidden
        $(toastElement).on('hidden.bs.toast', function() {
            $(this).remove();
        });
    }
}

// Initialize notification manager when document is ready
$(document).ready(() => {
    window.notificationManager = new NotificationManager();
});
</script>
@endpush