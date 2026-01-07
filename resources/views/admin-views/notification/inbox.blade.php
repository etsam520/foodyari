@extends('layouts.dashboard-main')

@push('css')
<style>
    :root {
        --notification-primary: #4f46e5;
        --notification-success: #10b981;
        --notification-warning: #f59e0b;
        --notification-danger: #ef4444;
        --notification-gray: #6b7280;
        --notification-light: #f8fafc;
        --notification-border: #e5e7eb;
        --notification-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --notification-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .notification-inbox-header {
        background: linear-gradient(135deg, var(--notification-primary) 0%, #6366f1 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .notification-inbox-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .notification-stats {
        display: flex;
        gap: 2rem;
        margin-top: 1.5rem;
    }

    .notification-stat {
        background: rgba(255, 255, 255, 0.2);
        padding: 1rem 1.5rem;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        text-align: center;
        flex: 1;
        transition: all 0.3s ease;
    }

    .notification-stat:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .notification-stat-number {
        font-size: 2rem;
        font-weight: 700;
        display: block;
    }

    .notification-stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .notification-controls {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: var(--notification-shadow);
        margin-bottom: 2rem;
    }

    .filter-tabs {
        display: flex;
        background: var(--notification-light);
        border-radius: 12px;
        padding: 0.5rem;
        margin-bottom: 1rem;
    }

    .filter-tab {
        flex: 1;
        text-align: center;
        padding: 0.75rem 1rem;
        border: none;
        background: transparent;
        border-radius: 8px;
        font-weight: 600;
        color: var(--notification-gray);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .filter-tab.active {
        background: var(--notification-primary);
        color: white;
        box-shadow: var(--notification-shadow);
    }

    .filter-tab:hover:not(.active) {
        background: rgba(79, 70, 229, 0.1);
        color: var(--notification-primary);
    }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    .action-btn-primary {
        background: var(--notification-primary);
        color: white;
    }

    .action-btn-primary:hover {
        background: #4338ca;
        transform: translateY(-1px);
    }

    .action-btn-danger {
        background: var(--notification-danger);
        color: white;
    }

    .action-btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }

    .notification-content {
        background: white;
        border-radius: 12px;
        box-shadow: var(--notification-shadow);
        overflow: hidden;
    }

    .notification-list {
        max-height: 700px;
        overflow-y: auto;
    }

    .notification-list::-webkit-scrollbar {
        width: 6px;
    }

    .notification-list::-webkit-scrollbar-track {
        background: var(--notification-light);
    }

    .notification-list::-webkit-scrollbar-thumb {
        background: var(--notification-border);
        border-radius: 3px;
    }

    .notification-list::-webkit-scrollbar-thumb:hover {
        background: var(--notification-gray);
    }

    .admin-notification-card {
        border-bottom: 1px solid var(--notification-border);
        padding: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }

    .admin-notification-card:hover {
        background: var(--notification-light);
    }

    .admin-notification-card.unread {
        background: linear-gradient(135deg, #fefefe 0%, #f0f9ff 100%);
        border-left: 4px solid var(--notification-primary);
    }

    .admin-notification-card.read {
        opacity: 0.8;
        border-left: 4px solid var(--notification-success);
    }

    .admin-notification-content {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .admin-notification-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--notification-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        flex-shrink: 0;
    }

    .admin-notification-body {
        flex: 1;
        min-width: 0;
    }

    .admin-notification-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .admin-notification-message {
        color: var(--notification-gray);
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 0.75rem;
    }

    .admin-notification-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.875rem;
    }

    .admin-notification-time {
        color: var(--notification-gray);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .admin-notification-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .admin-notification-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .admin-notification-status.unread {
        background: rgba(79, 70, 229, 0.1);
        color: var(--notification-primary);
    }

    .admin-notification-status.read {
        background: rgba(16, 185, 129, 0.1);
        color: var(--notification-success);
    }

    .admin-notification-delete-btn {
        background: rgba(239, 68, 68, 0.1);
        color: var(--notification-danger);
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .admin-notification-delete-btn:hover {
        background: var(--notification-danger);
        color: white;
    }

    .loading-spinner {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid var(--notification-border);
        border-top: 4px solid var(--notification-primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--notification-gray);
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--notification-border);
        margin-bottom: 1.5rem;
    }

    .load-more-btn {
        background: var(--notification-primary);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 2rem auto;
    }

    .load-more-btn:hover {
        background: #4338ca;
        transform: translateY(-2px);
    }

    .notification-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: var(--notification-shadow-lg);
        padding: 1rem 1.5rem;
        max-width: 400px;
        z-index: 9999;
        animation: slideIn 0.3s ease;
    }

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

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h3 class="page-header-title text-capitalize">
                    <div class="card-header-icon d-inline-flex mr-2 img">
                        <i class="fa fa-inbox"></i>
                    </div>
                    <span>Notification Inbox</span>
                </h3>
            </div>
            <div class="col-sm-auto">
                <a href="{{ route('admin.notification.inbox.settings') }}" class="btn btn-outline-primary">
                    <i class="fas fa-cog mr-2"></i>Settings
                </a>
            </div>
        </div>
    </div>

    <!-- Header with Statistics -->
    <div class="notification-inbox-header">
        <h2><i class="fas fa-inbox me-3"></i>Notification Inbox</h2>
        <p>Stay updated with all your administrative notifications</p>
        <div class="notification-stats">
            <div class="notification-stat">
                <span class="notification-stat-number" id="total-count">0</span>
                <span class="notification-stat-label">Total</span>
            </div>
            <div class="notification-stat">
                <span class="notification-stat-number" id="unread-count">{{ $unreadCount ?? 0 }}</span>
                <span class="notification-stat-label">Unread</span>
            </div>
            <div class="notification-stat">
                <span class="notification-stat-number" id="read-count">0</span>
                <span class="notification-stat-label">Read</span>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="notification-controls">
        <div class="filter-tabs">
            <button class="filter-tab active" id="filter-all">
                <i class="fas fa-list me-2"></i>All
            </button>
            <button class="filter-tab" id="filter-unread">
                <i class="fas fa-circle me-2"></i>Unread
            </button>
            <button class="filter-tab" id="filter-read">
                <i class="fas fa-check-circle me-2"></i>Read
            </button>
        </div>

        <div class="action-buttons">
            <button class="action-btn action-btn-primary" id="mark-all-read">
                <i class="fas fa-check-double"></i>
                Mark All Read
            </button>
            <button class="action-btn action-btn-danger" id="delete-all-read">
                <i class="fas fa-trash-alt"></i>
                Delete Read
            </button>
        </div>
    </div>

    <!-- Notification Content -->
    <div class="notification-content">
        <!-- Loading Spinner -->
        <div class="loading-spinner d-none" id="loading-spinner">
            <div class="spinner"></div>
        </div>

        <!-- Notification List -->
        <div class="notification-list" id="notification-list"></div>

        <!-- Empty State -->
        <div class="empty-state d-none" id="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <h4>No notifications yet</h4>
            <p>When you have notifications, they'll appear here</p>
        </div>

        <!-- Load More Button -->
        <button class="load-more-btn d-none" id="load-more">
            <i class="fas fa-plus"></i>
            Load More Notifications
        </button>
    </div>
</div>
@endsection

@push('javascript')
<script>
class AdminNotificationManager {
    constructor() {
        this.currentPage = 1;
        this.currentFilter = 'all';
        this.totalPages = 1;
        this.isLoading = false;
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadNotifications(true);
    }

    bindEvents() {
        // Load more button
        $('#load-more').on('click', () => {
            this.loadMore();
        });

        // Filter buttons
        $('#filter-all').on('click', (e) => {
            e.preventDefault();
            this.filterNotifications('all');
        });

        $('#filter-unread').on('click', (e) => {
            e.preventDefault();
            this.filterNotifications('unread');
        });

        $('#filter-read').on('click', (e) => {
            e.preventDefault();
            this.filterNotifications('read');
        });

        // Bulk actions
        $('#mark-all-read').on('click', (e) => {
            e.preventDefault();
            this.markAllAsRead();
        });

        $('#delete-all-read').on('click', (e) => {
            e.preventDefault();
            this.deleteAllRead();
        });

        // Notification clicks (delegation)
        $(document).on('click', '.admin-notification-card', (e) => {
            const $card = $(e.currentTarget);
            const notificationId = $card.data('id');
            this.markAsRead(notificationId, $card);
        });

        // Delete button clicks (delegation)
        $(document).on('click', '.admin-notification-delete-btn', (e) => {
            e.stopPropagation();
            const notificationId = $(e.currentTarget).data('id');
            this.deleteNotification(notificationId);
        });
    }

    showLoading() {
        this.isLoading = true;
        $('#loading-spinner').removeClass('d-none');
    }

    hideLoading() {
        this.isLoading = false;
        $('#loading-spinner').addClass('d-none');
    }

    showEmpty() {
        $('#empty-state').removeClass('d-none');
        $('#notification-list').addClass('d-none');
    }

    hideEmpty() {
        $('#empty-state').addClass('d-none');
        $('#notification-list').removeClass('d-none');
    }

    updateUnreadCount(count) {
        $('#unread-count').text(count);
        this.updateStatistics();
    }

    updateStatistics() {
        const totalCards = $('#notification-list .admin-notification-card').length;
        const unreadCards = $('#notification-list .admin-notification-card.unread').length;
        const readCards = totalCards - unreadCards;
        
        $('#total-count').text(totalCards);
        $('#read-count').text(readCards);
    }

    loadNotifications(reset = false) {
        if (this.isLoading) return;

        if (reset) {
            this.currentPage = 1;
            $('#notification-list').empty();
        }

        this.showLoading();

        $.ajax({
            url: "{{ route('admin.notification.inbox.fetch') }}",
            type: "GET",
            data: { 
                page: this.currentPage,
                type: this.currentFilter,
                per_page: 15
            },
            success: (response) => {
                this.hideLoading();
                
                if (response.success && response.data) {
                    const notifications = response.data;
                    const pagination = response.pagination;
                    
                    this.totalPages = pagination.last_page;

                    if (notifications.length === 0 && reset) {
                        this.showEmpty();
                        $('#load-more').addClass('d-none');
                        return;
                    }

                    this.hideEmpty();
                    this.renderNotifications(notifications, !reset);
                    
                    // Show/hide load more button
                    if (pagination.has_more) {
                        $('#load-more').removeClass('d-none');
                    } else {
                        $('#load-more').addClass('d-none');
                    }
                } else {
                    this.showError('Failed to load notifications');
                }
            },
            error: (xhr, status, error) => {
                this.hideLoading();
                this.showError('Error loading notifications: ' + error);
                console.error('Error fetching notifications:', xhr.responseJSON);
            }
        });
    }

    loadMore() {
        if (this.currentPage < this.totalPages) {
            this.currentPage++;
            this.loadNotifications(false);
        }
    }

    renderNotifications(notifications, append = true) {
        if (!append) {
            $('#notification-list').empty();
        }

        notifications.forEach(notif => {
            const card = this.createNotificationCard(notif);
            $('#notification-list').append(card);
        });
        
        this.updateStatistics();
    }

    createNotificationCard(notif) {
        const isRead = notif.read_at !== null;
        const cardClass = isRead ? 'read' : 'unread';
        
        // Safely extract notification data
        let title = 'System Notification';
        let message = 'You have a new notification';
        let type = 'system';
        
        if (notif.data) {
            if (typeof notif.data === 'string') {
                try {
                    const parsedData = JSON.parse(notif.data);
                    title = parsedData.subject || parsedData.title || title;
                    message = parsedData.message || message;
                    type = parsedData.type || type;
                } catch (e) {
                    message = notif.data;
                }
            } else {
                title = notif.data.subject || notif.data.title || title;
                message = notif.data.message || message;
                type = notif.data.type || type;
            }
        }

        const createdAt = this.formatTime(notif.created_at);
        const statusBadge = isRead ? 
            '<span class="admin-notification-status read"><i class="fas fa-check-circle"></i>Read</span>' : 
            '<span class="admin-notification-status unread"><i class="fas fa-circle"></i>New</span>';

        const avatarIcon = this.getTypeIcon(type);

        return `
            <div class="admin-notification-card ${cardClass}" data-id="${notif.id}">
                <div class="admin-notification-content">
                    <div class="admin-notification-avatar">
                        <i class="fas ${avatarIcon}"></i>
                    </div>
                    <div class="admin-notification-body">
                        <div class="admin-notification-title">${title}</div>
                        <div class="admin-notification-message">${message}</div>
                        <div class="admin-notification-meta">
                            <div class="admin-notification-time">
                                <i class="fas fa-clock"></i>
                                ${createdAt}
                            </div>
                            <div class="admin-notification-actions">
                                ${statusBadge}
                                <button class="admin-notification-delete-btn" data-id="${notif.id}" title="Delete notification">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    getTypeIcon(type) {
        const icons = {
            'order': 'fa-shopping-cart',
            'customer': 'fa-user',
            'restaurant': 'fa-store',
            'delivery': 'fa-truck',
            'system': 'fa-cog',
            'default': 'fa-bell'
        };
        return icons[type] || icons['default'];
    }

    formatTime(timestamp) {
        const now = new Date();
        const time = new Date(timestamp);
        const diffInSeconds = Math.floor((now - time) / 1000);
        
        if (diffInSeconds < 60) {
            return 'Just now';
        } else if (diffInSeconds < 3600) {
            const minutes = Math.floor(diffInSeconds / 60);
            return `${minutes} ${minutes === 1 ? 'minute' : 'minutes'} ago`;
        } else if (diffInSeconds < 86400) {
            const hours = Math.floor(diffInSeconds / 3600);
            return `${hours} ${hours === 1 ? 'hour' : 'hours'} ago`;
        } else if (diffInSeconds < 604800) {
            const days = Math.floor(diffInSeconds / 86400);
            return `${days} ${days === 1 ? 'day' : 'days'} ago`;
        } else {
            return time.toLocaleDateString();
        }
    }

    markAsRead(notificationId, notificationElement) {
        $.ajax({
            url: `{{ route('admin.notification.inbox.read', '') }}/${notificationId}`,
            type: "GET",
            success: (response) => {
                if (response.success) {
                    // Update visual state
                    notificationElement.removeClass('unread').addClass('read');
                    notificationElement.find('.admin-notification-status').removeClass('unread').addClass('read')
                        .html('<i class="fas fa-check-circle"></i>Read');
                    
                    // Update unread count
                    this.updateUnreadCount(response.unread_count);
                    
                    this.showSuccess('Notification marked as read');
                }
            },
            error: (xhr) => {
                this.showError('Failed to mark notification as read');
                console.error('Error:', xhr.responseJSON);
            }
        });
    }

    markAllAsRead() {
        if (!confirm('Mark all notifications as read?')) return;

        $.ajax({
            url: "{{ route('admin.notification.inbox.mark-all-read') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                if (response.success) {
                    this.updateUnreadCount(0);
                    this.loadNotifications(true);
                    this.showSuccess('All notifications marked as read');
                }
            },
            error: (xhr) => {
                this.showError('Failed to mark all notifications as read');
                console.error('Error:', xhr.responseJSON);
            }
        });
    }

    deleteNotification(notificationId) {
        if (!confirm('Delete this notification?')) return;

        $.ajax({
            url: `{{ route('admin.notification.inbox.delete', '') }}/${notificationId}`,
            type: "DELETE",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                if (response.success) {
                    $(`.admin-notification-card[data-id="${notificationId}"]`).fadeOut(300, function() {
                        $(this).remove();
                        // Check if list is empty
                        if ($('#notification-list').children().length === 0) {
                            window.adminNotificationManager.loadNotifications(true);
                        }
                    });
                    
                    this.updateUnreadCount(response.unread_count);
                    this.showSuccess('Notification deleted');
                }
            },
            error: (xhr) => {
                this.showError('Failed to delete notification');
                console.error('Error:', xhr.responseJSON);
            }
        });
    }

    deleteAllRead() {
        if (!confirm('Delete all read notifications? This action cannot be undone.')) return;

        $.ajax({
            url: "{{ route('admin.notification.inbox.delete-all') }}",
            type: "DELETE",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                type: 'read'
            },
            success: (response) => {
                if (response.success) {
                    this.loadNotifications(true);
                    this.showSuccess('All read notifications deleted');
                }
            },
            error: (xhr) => {
                this.showError('Failed to delete notifications');
                console.error('Error:', xhr.responseJSON);
            }
        });
    }

    filterNotifications(type) {
        this.currentFilter = type;
        this.loadNotifications(true);
        
        // Update tab states
        $('.filter-tab').removeClass('active');
        $(`#filter-${type}`).addClass('active');
    }

    showSuccess(message) {
        this.showToast(message, 'success');
    }

    showError(message) {
        this.showToast(message, 'error');
    }

    showToast(message, type = 'success') {
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const bgColor = type === 'success' ? '#10b981' : '#ef4444';
        
        const toast = $(`
            <div class="notification-toast" style="border-left: 4px solid ${bgColor};">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas ${icon}" style="color: ${bgColor}; font-size: 1.2rem;"></i>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: #1f2937;">${message}</div>
                    </div>
                    <button onclick="$(this).parent().parent().remove()" style="background: none; border: none; color: #6b7280; cursor: pointer; padding: 0.25rem;">
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
}

// Initialize notification manager when document is ready
$(document).ready(function() {
    window.adminNotificationManager = new AdminNotificationManager();
    
    // Poll for unread count every 30 seconds
    setInterval(() => {
        $.get("{{ route('admin.notification.inbox.count') }}", (response) => {
            if (response.success) {
                window.adminNotificationManager.updateUnreadCount(response.unread_count);
            }
        });
    }, 30000);
});
</script>
@endpush