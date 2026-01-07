@extends('layouts.user.app')

@section('title', 'Order Placed Successfully')

@push('css')
<style>
    .success-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px;
    }

    .success-card {
        background: white;
        border-radius: 20px;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 100%;
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .success-icon {
        width: 100px;
        height: 100px;
        background: #28a745;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        animation: checkmark 0.6s ease-in-out 0.3s both;
    }

    @keyframes checkmark {
        0% {
            transform: scale(0);
        }
        50% {
            transform: scale(1.3);
        }
        100% {
            transform: scale(1);
        }
    }

    .success-icon i {
        color: white;
        font-size: 3rem;
    }

    .success-title {
        color: #333;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        animation: fadeIn 0.8s ease-out 0.5s both;
    }

    .success-message {
        color: #666;
        font-size: 1.1rem;
        margin-bottom: 2rem;
        line-height: 1.6;
        animation: fadeIn 0.8s ease-out 0.7s both;
    }

    .order-info {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        animation: fadeIn 0.8s ease-out 0.9s both;
    }

    .order-number {
        font-size: 1.3rem;
        font-weight: 600;
        color: #28a745;
        margin-bottom: 0.5rem;
    }

    .order-type {
        color: #666;
        font-size: 0.95rem;
    }

    .redirect-info {
        background: #e3f2fd;
        border-left: 4px solid #2196f3;
        padding: 1rem;
        margin-bottom: 2rem;
        border-radius: 0 8px 8px 0;
        animation: fadeIn 0.8s ease-out 1.1s both;
    }

    .redirect-text {
        color: #1976d2;
        font-size: 0.9rem;
        margin: 0;
    }

    .countdown {
        font-weight: 600;
        color: #1976d2;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        animation: fadeIn 0.8s ease-out 1.3s both;
    }

    .btn-primary-custom {
        background: #28a745;
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .btn-primary-custom:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        color: white;
        text-decoration: none;
    }

    .btn-secondary-custom {
        background: transparent;
        border: 2px solid #6c757d;
        color: #6c757d;
        padding: 10px 30px;
        border-radius: 25px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .btn-secondary-custom:hover {
        background: #6c757d;
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .progress-bar {
        width: 100%;
        height: 4px;
        background: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
        margin-top: 1rem;
    }

    .progress-fill {
        height: 100%;
        background: #28a745;
        border-radius: 2px;
        width: 0%;
        transition: width 0.1s ease;
    }

    @media (max-width: 576px) {
        .success-card {
            padding: 2rem 1.5rem;
            margin: 1rem;
        }

        .success-title {
            font-size: 1.5rem;
        }

        .action-buttons {
            flex-direction: column;
            align-items: center;
        }

        .btn-primary-custom,
        .btn-secondary-custom {
            width: 100%;
            max-width: 250px;
        }
    }
</style>
@endpush

@section('content')
<div class="success-container">
    <div class="success-card">
        <!-- Success Icon -->
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <!-- Success Title -->
        <h1 class="success-title">Order Placed Successfully!</h1>

        <!-- Success Message -->
        <p class="success-message">
            Thank you for your order! We're preparing your delicious meal with care.
        </p>

        <!-- Order Information -->
        <div class="order-info">
            <div class="order-number">Order #{{ $orderId ?? 'N/A' }}</div>
            <div class="order-type">
                @if($isScheduled ?? false)
                    <i class="fas fa-clock me-1"></i>Scheduled Order
                @else
                    <i class="fas fa-motorcycle me-1"></i>Current Order
                @endif
            </div>
        </div>

        <!-- Auto Redirect Info -->
        <div class="redirect-info">
            <p class="redirect-text">
                <i class="fas fa-info-circle me-1"></i>
                Redirecting you to track your order in <span class="countdown" id="countdown">5</span> seconds...
            </p>
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ $redirectUrl }}" class="btn-primary-custom" id="trackOrderBtn">
                <i class="fas fa-map-marker-alt me-2"></i>Track Your Order
            </a>
            <a href="{{ route('user.dashboard') }}" class="btn-secondary-custom">
                <i class="fas fa-home me-2"></i>Back to Home
            </a>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prevent back navigation
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };

    // Disable browser back button
    window.addEventListener('beforeunload', function(e) {
        e.preventDefault();
        e.returnValue = '';
    });

    // Auto redirect countdown
    let countdown = 5;
    const countdownElement = document.getElementById('countdown');
    const progressFill = document.getElementById('progressFill');
    const redirectUrl = '{{ $redirectUrl }}';

    // Start progress animation
    progressFill.style.width = '0%';

    const timer = setInterval(function() {
        countdown--;
        countdownElement.textContent = countdown;
        
        // Update progress bar
        const progress = ((5 - countdown) / 5) * 100;
        progressFill.style.width = progress + '%';

        if (countdown <= 0) {
            clearInterval(timer);
            // Replace current history entry and redirect
            window.location.replace(redirectUrl);
        }
    }, 1000);

    // Manual track order button
    document.getElementById('trackOrderBtn').addEventListener('click', function(e) {
        e.preventDefault();
        clearInterval(timer);
        window.location.replace(redirectUrl);
    });

    // Show success animation
    setTimeout(function() {
        document.querySelector('.success-card').style.opacity = '1';
    }, 100);
});

// Additional security to prevent back navigation
(function() {
    if (window.history && window.history.pushState) {
        window.history.pushState('forward', null, window.location.href);
        window.addEventListener('popstate', function() {
            window.history.pushState('forward', null, window.location.href);
        });
    }
})();
</script>
@endpushwindow.history