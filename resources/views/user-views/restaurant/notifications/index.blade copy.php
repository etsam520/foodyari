@extends('user-views.restaurant.layouts.main')

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

    .notification-page {
        background: linear-gradient(135deg, #f59e0b 0%, #f59e0b 100%);
        min-height: 100vh;
        padding-top: 2rem;
        padding-bottom: 2rem;
    }

    .notification-container {
        background: white;
        border-radius: 20px;
        box-shadow: var(--notification-shadow-lg);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .notification-header {
        background: linear-gradient(135deg, var(--notification-primary) 0%, #6366f1 100%);
        color: white;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .notification-header::before {
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

    .notification-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
    }

    .notification-subtitle {
        opacity: 0.9;
        font-size: 1.1rem;
        position: relative;
        z-index: 2;
    }

    .notification-stats {
        display: flex;
        gap: 1.5rem;
        margin-top: 1.5rem;
        position: relative;
        z-index: 2;
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
        font-size: 1.8rem;
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
        padding: 1.5rem 2rem;
        background: var(--notification-light);
        border-bottom: 1px solid var(--notification-border);
    }

    .filter-tabs {
        display: flex;
        background: white;
        border-radius: 12px;
        padding: 0.5rem;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
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
        padding: 0 2rem 2rem;
        max-height: 600px;
        overflow-y: auto;
    }

    .notification-content::-webkit-scrollbar {
        width: 6px;
    }

    .notification-content::-webkit-scrollbar-track {
        background: var(--notification-light);
    }

    .notification-content::-webkit-scrollbar-thumb {
        background: var(--notification-border);
        border-radius: 3px;
    }

    .notification-content::-webkit-scrollbar-thumb:hover {
        background: var(--notification-gray);
    }

    .notification-card {
        background: white;
        border-radius: 16px;
        margin-bottom: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid var(--notification-border);
        cursor: pointer;
        position: relative;
    }

    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--notification-shadow-lg);
    }

    .notification-card.unread {
        border-left: 4px solid var(--notification-primary);
        background: linear-gradient(135deg, #fefefe 0%, #f8fafc 100%);
    }

    .notification-card.read {
        border-left: 4px solid var(--notification-success);
        opacity: 0.9;
    }

    .notification-card-body {
        padding: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .notification-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid var(--notification-border);
        background: var(--notification-light);
    }

    .notification-content-area {
        flex: 1;
        min-width: 0;
    }

    .notification-title-text {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .notification-message {
        color: var(--notification-gray);
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 0.75rem;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.875rem;
    }

    .notification-time {
        color: var(--notification-gray);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .notification-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .notification-status {
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

    .notification-status.unread {
        background: rgba(79, 70, 229, 0.1);
        color: var(--notification-primary);
    }

    .notification-status.read {
        background: rgba(16, 185, 129, 0.1);
        color: var(--notification-success);
    }

    .notification-delete-btn {
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

    .notification-delete-btn:hover {
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

    .empty-state-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state-description {
        font-size: 1rem;
        opacity: 0.8;
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

    .load-more-btn:disabled {
        background: var(--notification-gray);
        cursor: not-allowed;
        transform: none;
    }

    /* Toast notification styles */
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

    /* Responsive design */
    @media (max-width: 768px) {
        .notification-page {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .notification-header {
            padding: 1.5rem;
        }

        .notification-title {
            font-size: 1.5rem;
        }

        .notification-stats {
            flex-direction: column;
            gap: 1rem;
        }

        .notification-controls {
            padding: 1rem;
        }

        .filter-tabs {
            flex-direction: column;
            gap: 0.5rem;
        }

        .action-buttons {
            justify-content: center;
        }

        .notification-content {
            padding: 0 1rem 1rem;
        }

        .notification-card-body {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('containt')
<div class="notification-page">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="notification-container">
                    <!-- Header Section -->
                    <div class="notification-header">
                        <div class="notification-title">
                            <i class="fas fa-bell me-3"></i>
                            Notifications
                        </div>
                        <div class="notification-subtitle">
                            Stay updated with your latest activities
                        </div>
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

                    <!-- Controls Section -->
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

                    <!-- Content Section -->
                    <div class="notification-content">
                        <!-- Loading Spinner -->
                        <div class="loading-spinner d-none" id="loading-spinner">
                            <div class="spinner"></div>
                        </div>

                        <!-- Notification List -->
                        <div id="notification-list"></div>

                        <!-- Empty State -->
                        <div class="empty-state d-none" id="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-bell-slash"></i>
                            </div>
                            <div class="empty-state-title">No notifications yet</div>
                            <div class="empty-state-description">
                                When you have notifications, they'll appear here
                            </div>
                        </div>

                        <!-- Load More Button -->
                        <button class="load-more-btn d-none" id="load-more">
                            <i class="fas fa-plus"></i>
                            Load More Notifications
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8 col-12">
                <div class="py-3">
                    <h5 class="fw-bolder align-self-center m-0">Notifications</h5>
                </div>
                <div class="card border-0" style="border-radius: 0px 20px 20px 0px;">
                    <div class="d-flex p-3" style="border-left: 5px solid grey;">
                        <img alt="" src="{{ asset('assets/images/icons/foodYariLogo.png') }}" class="img-fluid food-type me-2" style="height: 70px;">
                        <div class="">

                            <h6 class="mb-0 fw-bolder">Payment Success!</h6>
                            <p class="mb-0">The transaction was completed successfully.</p>
                            <p class="mb-0 text-muted">
                                <small>Oct,08,2024 19:37 PM</small>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div> --}}

@endsection
@push('javascript')

<script>
    class NotificationManager {
        constructor() {
            this.currentPage = 1;
            this.currentFilter = 'all';
            this.totalPages = 1;
            this.isLoading = false;
            this.notifications = [];
            
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
            $(document).on('click', '.notification-card', (e) => {
                const $card = $(e.currentTarget);
                const notificationId = $card.data('id');
                this.markAsRead(notificationId, $card);
            });

            // Delete button clicks (delegation)
            $(document).on('click', '.delete-notification', (e) => {
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
            
            // Update navigation badge if exists
            $('.notification-badge').text(count);
            if (count > 0) {
                $('.notification-badge').removeClass('d-none');
            } else {
                $('.notification-badge').addClass('d-none');
            }
            
            // Update statistics
            this.updateStatistics();
        }

        updateStatistics() {
            const totalCards = $('#notification-list .notification-card').length;
            const unreadCards = $('#notification-list .notification-card.unread').length;
            const readCards = totalCards - unreadCards;
            
            $('#total-count').text(totalCards);
            $('#read-count').text(readCards);
        }

        loadNotifications(reset = false) {
            if (this.isLoading) return;

            if (reset) {
                this.currentPage = 1;
                $('#notification-list').empty();
                this.notifications = [];
            }

            this.showLoading();

            $.ajax({
                url: "{{ route('user.notifications.fetch') }}",
                type: "GET",
                data: { 
                    page: this.currentPage,
                    type: this.currentFilter,
                    per_page: 10
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
            
            // Update statistics after rendering
            this.updateStatistics();
        }

        createNotificationCard(notif) {
            const isRead = notif.read_at !== null;
            const cardClass = isRead ? 'read' : 'unread';
            
            // Safely extract notification data
            let title = 'Notification';
            let message = 'You have a new notification';
            let image = "{{ asset('assets/images/icons/foodYariLogo.png') }}";
            
            if (notif.data) {
                if (typeof notif.data === 'string') {
                    try {
                        const parsedData = JSON.parse(notif.data);
                        title = parsedData.subject || parsedData.title || title;
                        message = parsedData.message || message;
                        image = parsedData.image || image;
                    } catch (e) {
                        message = notif.data;
                    }
                } else {
                    title = notif.data.subject || notif.data.title || title;
                    message = notif.data.message || message;
                    image = notif.data.image || image;
                }
            }

            const createdAt = this.formatTime(notif.created_at);
            const statusBadge = isRead ? 
                '<span class="notification-status read"><i class="fas fa-check-circle"></i>Read</span>' : 
                '<span class="notification-status unread"><i class="fas fa-circle"></i>New</span>';

            return `
                <div class="notification-card ${cardClass}" data-id="${notif.id}">
                    <div class="notification-card-body">
                        <img src="${image}" alt="" class="notification-avatar" onerror="this.src='{{ asset('assets/images/icons/foodYariLogo.png') }}'">
                        <div class="notification-content-area">
                            <div class="notification-title-text">${title}</div>
                            <div class="notification-message">${message}</div>
                            <div class="notification-meta">
                                <div class="notification-time">
                                    <i class="fas fa-clock"></i>
                                    ${createdAt}
                                </div>
                                <div class="notification-actions">
                                    ${statusBadge}
                                    <button class="notification-delete-btn delete-notification" data-id="${notif.id}" title="Delete notification">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
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
                url: `./notifications/${notificationId}/read`,
                type: "GET",
                success: (response) => {
                    if (response.success) {
                        // Update visual state
                        notificationElement.removeClass('unread').addClass('read');
                        notificationElement.find('.notification-status').removeClass('unread').addClass('read')
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
                url: "{{ route('user.notifications.markAllAsRead') }}",
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
                url: `./notifications/${notificationId}`,
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    if (response.success) {
                        $(`.notification-card[data-id="${notificationId}"]`).fadeOut(300, function() {
                            $(this).remove();
                            // Check if list is empty
                            if ($('#notification-list').children().length === 0) {
                                // Reload to show proper empty state or load more
                                window.notificationManager.loadNotifications(true);
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
                url: "{{ route('user.notifications.deleteAll') }}",
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

    // WebSocket integration for real-time notifications
    class NotificationWebSocket {
        constructor(notificationManager) {
            this.notificationManager = notificationManager;
            this.socket = null;
            this.reconnectInterval = 5000;
            this.isConnected = false;
            
            this.initWebSocket();
        }

        initWebSocket() {
            try {
                @auth('customer')
                const wsUrl = "{{ env('WEB_SOCKET_URL', 'ws://127.0.0.1:6002') }}";
                this.socket = new WebSocket(wsUrl);

                this.socket.onopen = () => {
                    console.log('📡 Notification WebSocket connected');
                    this.isConnected = true;
                    
                    // Subscribe to user's notification channel
                    const subscribeMessage = {
                        type: 'subscribe',
                        channel: `notifications.customer.{{ auth('customer')->id() }}`,
                        user_id: {{ auth('customer')->id() }},
                        user_type: 'customer'
                    };
                    this.socket.send(JSON.stringify(subscribeMessage));
                };

                this.socket.onmessage = (event) => {
                    const data = JSON.parse(event.data);
                    this.handleWebSocketMessage(data);
                };

                this.socket.onclose = () => {
                    console.log('🔌 Notification WebSocket closed');
                    this.isConnected = false;
                    setTimeout(() => this.initWebSocket(), this.reconnectInterval);
                };

                this.socket.onerror = (error) => {
                    console.error('❌ Notification WebSocket error:', error);
                    this.isConnected = false;
                };
                @endauth
            } catch (error) {
                console.error('Failed to initialize WebSocket:', error);
            }
        }

        handleWebSocketMessage(data) {
            switch (data.type) {
                case 'notification':
                    this.handleNewNotification(data);
                    break;
                case 'unread_count':
                    this.notificationManager.updateUnreadCount(data.unread_count);
                    break;
                case 'notification_read':
                    this.handleNotificationRead(data);
                    break;
                case 'notification_deleted':
                    this.handleNotificationDeleted(data);
                    break;
            }
        }

        handleNewNotification(data) {
            // Show toast notification
            this.showToastNotification(data.notification);
            
            // Update unread count
            this.notificationManager.updateUnreadCount(data.unread_count || null);
            
            // If user is on notifications page, refresh the list
            if (window.location.pathname.includes('notifications')) {
                this.notificationManager.loadNotifications(true);
            }
            
            // Play notification sound
            this.playNotificationSound();
        }

        handleNotificationRead(data) {
            // Update the specific notification in the UI
            const $notificationCard = $(`.notification-card[data-id="${data.notification_id}"]`);
            if ($notificationCard.length) {
                $notificationCard.removeClass('unread').addClass('read');
                $notificationCard.find('.notification-status').removeClass('unread').addClass('read')
                    .html('<i class="fas fa-check-circle"></i>Read');
            }
            
            // Update unread count
            this.notificationManager.updateUnreadCount(data.unread_count);
        }

        handleNotificationDeleted(data) {
            // Remove the notification from UI
            const $notificationCard = $(`.notification-card[data-id="${data.notification_id}"]`);
            if ($notificationCard.length) {
                $notificationCard.fadeOut(300, function() {
                    $(this).remove();
                });
            }
            
            // Update unread count
            this.notificationManager.updateUnreadCount(data.unread_count);
        }

        showToastNotification(notification) {
            // Create modern toast notification
            const toast = $(`
                <div class="notification-toast" style="border-left: 4px solid var(--notification-primary); animation: slideIn 0.3s ease;">
                    <div style="display: flex; align-items: flex-start; gap: 1rem;">
                        <img src="${notification.image || "{{ asset('assets/images/icons/foodYariLogo.png') }}"}" 
                             style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;" alt="">
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">${notification.title}</div>
                            <div style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">${notification.message}</div>
                            <div style="color: #9ca3af; font-size: 0.75rem; margin-top: 0.5rem;">
                                <i class="fas fa-clock"></i> Just now
                            </div>
                        </div>
                        <button onclick="$(this).parent().parent().remove()" 
                                style="background: none; border: none; color: #6b7280; cursor: pointer; padding: 0.25rem; align-self: flex-start;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `);
            
            $('body').append(toast);
            
            // Auto remove after 6 seconds
            setTimeout(() => {
                toast.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 6000);
        }

        playNotificationSound() {
            try {
                const audio = new Audio("{{ asset('sound/notification-tone.mp3') }}");
                audio.volume = 0.5;
                audio.play().catch(e => console.log('Could not play notification sound:', e));
            } catch (e) {
                console.log('Notification sound not available:', e);
            }
        }
    }

    // Initialize notification manager when document is ready
    $(document).ready(function() {
        window.notificationManager = new NotificationManager();
        window.notificationWebSocket = new NotificationWebSocket(window.notificationManager);
        
        // Fallback polling every 30 seconds if WebSocket is not connected
        setInterval(() => {
            if (!window.notificationWebSocket.isConnected) {
                $.get("{{ route('user.notifications.count') }}", (response) => {
                    if (response.success) {
                        window.notificationManager.updateUnreadCount(response.unread_count);
                    }
                });
            }
        }, 30000);
    });
</script>

@endpush
