@extends('user-views.restaurant.layouts.main')

@push('css')
<style>
    :root {
        --notification-primary: #ff810a;
        --notification-success: #10b981;
        --notification-warning: #f59e0b;
        --notification-danger: #ef4444;
        --notification-gray: #6b7280;
        --notification-light: #fff5ed;
        --notification-border: #ffe5d1;
        --notification-shadow: 0 4px 12px rgba(255, 129, 10, 0.15);
        --notification-shadow-lg: 0 10px 25px rgba(255, 129, 10, 0.2);
    }

    .notification-page {
        background: #f9f9f9;
        min-height: 100vh;
        padding-top: 1rem;
        padding-bottom: 5rem;
    }

    .notification-container {
        background: white;
        /* border-radius: 24px; */
        /* box-shadow: var(--notification-shadow-lg); */
        overflow: hidden;
        margin-bottom: 2rem;
        border: 1px solid var(--notification-border);
    }

    .notification-header {
        background: linear-gradient(135deg, #ff6b35 0%, #ff810a 50%, #ff9500 100%);
        color: white;
        padding: 2rem 1.5rem;
        position: relative;
        overflow: visible;
        z-index: 100;
    }

    .notification-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite;
        z-index: 1;
        pointer-events: none;
    }

    .notification-header::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: -10%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        animation: float 10s ease-in-out infinite reverse;
        z-index: 1;
        pointer-events: none;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .notification-title {
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1002;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .notification-title-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
        z-index: 2;
    }

    .notification-title i {
        font-size: 1.5rem;
        animation: bellRing 2s ease-in-out infinite;
    }

    @keyframes bellRing {
        0%, 100% { transform: rotate(0deg); }
        10%, 30% { transform: rotate(-10deg); }
        20%, 40% { transform: rotate(10deg); }
        50% { transform: rotate(0deg); }
    }

    .notification-subtitle {
        opacity: 0.95;
        font-size: 1rem;
        position: relative;
        z-index: 2;
        font-weight: 500;
    }

    .notification-stats {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        position: relative;
        z-index: 2;
        flex-wrap: wrap;
    }

    .notification-stat {
        background: rgba(255, 255, 255, 0.25);
        padding: 1rem 1.25rem;
        border-radius: 16px;
        backdrop-filter: blur(10px);
        text-align: center;
        flex: 1;
        min-width: 100px;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.3);
        position: relative;
        z-index: 2;
    }

    .notification-stat:hover {
        background: rgba(255, 255, 255, 0.35);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .notification-stat-number {
        font-size: 2rem;
        font-weight: 800;
        display: block;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .notification-stat-label {
        font-size: 0.8rem;
        opacity: 0.95;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 600;
    }

    .notification-controls {
        padding: 1.5rem;
        background: #fffbf7;
        border-bottom: 1px solid var(--notification-border);
        position: relative;
        z-index: 50;
    }

    .filter-tabs {
        display: flex;
        background: white;
        border-radius: 16px;
        padding: 0.4rem;
        box-shadow: 0 2px 8px rgba(255, 129, 10, 0.1);
        margin-bottom: 1rem;
        border: 1px solid var(--notification-border);
    }

    .filter-tab {
        flex: 1;
        text-align: center;
        padding: 0.75rem 1rem;
        border: none;
        background: transparent;
        border-radius: 12px;
        font-weight: 700;
        color: var(--notification-gray);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .filter-tab.active {
        background: linear-gradient(135deg, #ff810a, #ff9500);
        color: white;
        box-shadow: 0 4px 12px rgba(255, 129, 10, 0.3);
        transform: scale(1.02);
    }

    .filter-tab:hover:not(.active) {
        background: rgba(255, 129, 10, 0.08);
        color: var(--notification-primary);
    }

    .filter-tab i {
        font-size: 0.85rem;
    }

    /* Dropdown Menu Styles */
    .notification-menu-dropdown {
        position: relative;
        z-index: 9999;
    }

    .notification-menu-btn {
        background: rgba(255, 255, 255, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        position: relative;
        z-index: 10000;
    }

    .notification-menu-btn:hover {
        background: rgba(255, 255, 255, 0.35);
        transform: scale(1.05);
    }

    .notification-menu-btn i {
        font-size: 1.2rem;
        animation: none;
    }

    .notification-dropdown-menu {
        position: absolute;
        top: 50px;
        right: 0;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.05);
        min-width: 220px;
        padding: 0.5rem;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 10001;
    }

    .notification-dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .notification-dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        font-weight: 600;
        font-size: 0.9rem;
        color: #1f2937;
    }

    .notification-dropdown-item:hover {
        background: rgba(255, 129, 10, 0.1);
        color: var(--notification-primary);
    }

    .notification-dropdown-item.danger:hover {
        background: rgba(239, 68, 68, 0.1);
        color: var(--notification-danger);
    }

    .notification-dropdown-item i {
        font-size: 1rem;
        width: 20px;
        text-align: center;
    }

    .action-buttons {
        display: none;
    }

    .action-btn {
        padding: 0.65rem 1.25rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .action-btn-primary {
        background: linear-gradient(135deg, #ff810a, #ff9500);
        color: white;
    }

    .action-btn-primary:hover {
        background: linear-gradient(135deg, #ff9500, #ffb347);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 129, 10, 0.3);
    }

    .action-btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .action-btn-danger:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .notification-content {
        padding: 1.5rem;
        max-height: 650px;
        overflow-y: auto;
        background: #fafafa;
    }

    .notification-content::-webkit-scrollbar {
        width: 8px;
    }

    .notification-content::-webkit-scrollbar-track {
        background: #f0f0f0;
        border-radius: 10px;
    }

    .notification-content::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #ff810a, #ff9500);
        border-radius: 10px;
    }

    .notification-content::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #ff9500, #ffb347);
    }

    .notification-card {
        background: white;
        border-radius: 18px;
        margin-bottom: 1rem;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        cursor: pointer;
        position: relative;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .notification-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(135deg, #ff810a, #ff9500);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .notification-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(255, 129, 10, 0.2);
        border-color: var(--notification-border);
    }

    .notification-card:hover::before {
        opacity: 1;
    }

    .notification-card.unread {
        background: linear-gradient(135deg, #fffaf5 0%, #fff5ed 100%);
        border-color: #ffe5d1;
    }

    .notification-card.unread::before {
        opacity: 1;
    }

    .notification-card.read {
        opacity: 0.85;
    }

    .notification-card.read::before {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .notification-card-body {
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .notification-avatar {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        object-fit: cover;
        border: 2px solid var(--notification-border);
        background: var(--notification-light);
        box-shadow: 0 2px 8px rgba(255, 129, 10, 0.15);
        flex-shrink: 0;
    }

    .notification-content-area {
        flex: 1;
        min-width: 0;
    }

    .notification-title-text {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .notification-message {
        color: #4b5563;
        font-size: 0.9rem;
        line-height: 1.6;
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
        gap: 0.4rem;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .notification-status.unread {
        background: linear-gradient(135deg, rgba(255, 129, 10, 0.15), rgba(255, 149, 0, 0.15));
        color: #ff810a;
        border: 1px solid rgba(255, 129, 10, 0.3);
    }

    .notification-status.read {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.15));
        color: var(--notification-success);
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .notification-status i {
        font-size: 0.65rem;
    }

    .notification-delete-btn {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.12), rgba(220, 38, 38, 0.12));
        color: var(--notification-danger);
        border: 1px solid rgba(239, 68, 68, 0.2);
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .notification-delete-btn:hover {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .loading-spinner {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        flex-direction: column;
        gap: 1rem;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid rgba(255, 129, 10, 0.2);
        border-top: 4px solid #ff810a;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading-text {
        color: #ff810a;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--notification-gray);
    }

    .empty-state-icon {
        font-size: 5rem;
        background: linear-gradient(135deg, #ff810a, #ff9500);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1.5rem;
        animation: float 3s ease-in-out infinite;
    }

    .empty-state-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #1f2937;
    }

    .empty-state-description {
        font-size: 1rem;
        opacity: 0.7;
        color: #6b7280;
    }

    .load-more-btn {
        background: linear-gradient(135deg, #ff810a, #ff9500);
        color: white;
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 2rem auto;
        box-shadow: 0 4px 12px rgba(255, 129, 10, 0.3);
        font-size: 0.95rem;
    }

    .load-more-btn:hover {
        background: linear-gradient(135deg, #ff9500, #ffb347);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 129, 10, 0.4);
    }

    .load-more-btn:disabled {
        background: linear-gradient(135deg, #9ca3af, #6b7280);
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Toast notification styles */
    .notification-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        padding: 1.25rem 1.5rem;
        max-width: 400px;
        z-index: 9999;
        animation: slideIn 0.3s ease;
        border: 1px solid var(--notification-border);
    }

    @keyframes slideIn {
        from {
            transform: translateX(120%);
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
            padding-top: 0px;
            padding-bottom: 1rem;
        }

        .notification-header {
            padding: 1.5rem 1rem;
        }

        .notification-title {
            font-size: 1.35rem;
        }

        .notification-title-left span {
            font-size: 1.35rem;
        }

        .notification-title i {
            font-size: 1.2rem;
        }

        .notification-menu-btn {
            width: 36px;
            height: 36px;
        }

        .notification-menu-btn i {
            font-size: 1.1rem;
        }

        .notification-subtitle {
            font-size: 0.85rem;
        }

        .notification-stats {
            gap: 0.75rem;
        }

        .notification-stat {
            padding: 0.85rem 1rem;
            min-width: 80px;
        }

        .notification-stat-number {
            font-size: 1.5rem;
        }

        .notification-stat-label {
            font-size: 0.7rem;
        }

        .notification-controls {
            padding: 1rem;
        }

        .filter-tabs {
            padding: 0.3rem;
        }

        .filter-tab {
            padding: 0.6rem 0.75rem;
            font-size: 0.8rem;
            /* flex-direction: column; */
            gap: 0.25rem;
        }

        .filter-tab i {
            font-size: 1rem;
        }

        .action-buttons {
            justify-content: center;
        }

        .action-btn {
            flex: 1;
            justify-content: center;
            font-size: 0.8rem;
            padding: 0.6rem 1rem;
        }

        .notification-content {
            padding: 1rem;
            max-height: 500px;
        }

        .notification-card-body {
            padding: 1rem;
        }

        .notification-avatar {
            width: 50px;
            height: 50px;
        }

        .notification-title-text {
            font-size: 0.95rem;
        }

        .notification-message {
            font-size: 0.85rem;
        }

        .notification-time {
            font-size: 0.75rem;
        }

        .load-more-btn {
            padding: 0.85rem 1.75rem;
            font-size: 0.85rem;
        }

        .notification-toast {
            left: 10px;
            right: 10px;
            max-width: calc(100% - 20px);
        }
    }

    @media (max-width: 480px) {
        .notification-header {
            padding: 1.25rem 0.85rem;
        }

        .notification-title {
            font-size: 1.15rem;
        }

        .notification-title-left span {
            font-size: 1.15rem;
        }

        .notification-menu-btn {
            width: 34px;
            height: 34px;
        }

        .notification-menu-btn i {
            font-size: 1rem;
        }

        .notification-stats {
            gap: 0.5rem;
        }

        .notification-stat {
            padding: 0.75rem 0.75rem;
            min-width: 70px;
        }

        .notification-stat-number {
            font-size: 1.3rem;
        }

        .notification-stat-label {
            font-size: 0.65rem;
        }

        .filter-tab {
            padding: 0.5rem;
            font-size: 0.7rem;
        }

        .notification-avatar {
            width: 44px;
            height: 44px;
        }

        .notification-card-body {
            padding: 0.85rem;
            gap: 0.75rem;
        }

        .notification-status {
            padding: 0.3rem 0.7rem;
            font-size: 0.65rem;
        }

        .notification-delete-btn {
            width: 32px;
            height: 32px;
        }
    }
</style>
@endpush

@section('containt')
<div class="notification-page">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8 px-0">
                <div class="notification-container">
                    <!-- Header Section -->
                    <div class="notification-header">
                        <div class="notification-title">
                            <div class="notification-title-left">
                                <i class="fas fa-bell"></i>
                                <span>Notifications</span>
                            </div>

                            <!-- Dropdown Menu -->
                            <div class="notification-menu-dropdown">
                                <button class="notification-menu-btn" id="notification-menu-toggle" type="button">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="notification-dropdown-menu" id="notification-dropdown">
                                    <button class="notification-dropdown-item" id="mark-all-read">
                                        <i class="fas fa-check-double"></i>
                                        <span>Mark All Read</span>
                                    </button>
                                    <button class="notification-dropdown-item danger" id="delete-all-read">
                                        <i class="fas fa-trash-alt"></i>
                                        <span>Delete All Read</span>
                                    </button>
                                </div>
                            </div>
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
                    </div>

                    <!-- Content Section -->
                    <div class="notification-content">
                        <!-- Loading Spinner -->
                        <div class="loading-spinner d-none" id="loading-spinner">
                            <div class="spinner"></div>
                            <div class="loading-text">Loading notifications...</div>
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
            // Dropdown toggle
            $('#notification-menu-toggle').on('click', (e) => {
                e.stopPropagation();
                $('#notification-dropdown').toggleClass('show');
            });

            // Close dropdown when clicking outside
            $(document).on('click', (e) => {
                if (!$(e.target).closest('.notification-menu-dropdown').length) {
                    $('#notification-dropdown').removeClass('show');
                }
            });

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
                $('#notification-dropdown').removeClass('show');
                this.markAllAsRead();
            });

            $('#delete-all-read').on('click', (e) => {
                e.preventDefault();
                $('#notification-dropdown').removeClass('show');
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
