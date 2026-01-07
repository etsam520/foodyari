<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <style>
    /* General Reset */
body {
    margin: 0;
    font-family: 'Arial', sans-serif;
    background: #ff810a;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    overflow: hidden;
}

.container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}

.card {
    background: #fff;
    border-radius: 20px;
    padding: 40px 30px;
    text-align: center;
    width: 100%;
    max-width: 450px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

.card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #ff810a, #00f2fe);
}

.icon {
    width: 120px;
    height: auto;
    margin-bottom: 25px;
}

.success-checkmark {
    width: 80px;
    height: 80px;
    background: #28a745;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    animation: scaleIn 0.5s ease-in-out;
}

.success-checkmark i {
    color: white;
    font-size: 40px;
}

@keyframes scaleIn {
    0% {
        transform: scale(0);
    }
    100% {
        transform: scale(1);
    }
}

h3 {
    font-size: 28px;
    margin-bottom: 15px;
    color: #2c3e50;
    font-weight: 600;
}

p {
    font-size: 16px;
    color: #6c757d;
    margin-bottom: 25px;
    line-height: 1.5;
}

.redirect-info {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
    border-left: 4px solid #007bff;
}

.countdown-text {
    font-size: 14px;
    color: #495057;
    margin-bottom: 5px;
}

.countdown-timer {
    font-size: 24px;
    font-weight: bold;
    color: #007bff;
    margin: 10px 0;
}

.redirect-message {
    font-size: 12px;
    color: #6c757d;
}

.btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 25px;
    color: #fff;
    padding: 15px 30px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    min-width: 200px;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    color: #fff;
    text-decoration: none;
}

.btn:active {
    transform: translateY(0);
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .card {
        margin: 20px;
        padding: 30px 20px;
        border-radius: 15px;
    }
    
    h3 {
        font-size: 24px;
    }
    
    .btn {
        padding: 12px 25px;
        font-size: 14px;
        min-width: 180px;
    }
}

/* Loading animation */
.loading-dots {
    display: inline-block;
    position: relative;
    width: 20px;
    height: 20px;
}

.loading-dots div {
    position: absolute;
    top: 8px;
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: #007bff;
    animation-timing-function: cubic-bezier(0, 1, 1, 0);
}

.loading-dots div:nth-child(1) {
    left: 2px;
    animation: loading1 0.6s infinite;
}

.loading-dots div:nth-child(2) {
    left: 2px;
    animation: loading2 0.6s infinite;
}

.loading-dots div:nth-child(3) {
    left: 8px;
    animation: loading2 0.6s infinite;
}

.loading-dots div:nth-child(4) {
    left: 14px;
    animation: loading3 0.6s infinite;
}

@keyframes loading1 {
    0% { transform: scale(0); }
    100% { transform: scale(1); }
}

@keyframes loading3 {
    0% { transform: scale(1); }
    100% { transform: scale(0); }
}

@keyframes loading2 {
    0% { transform: translate(0, 0); }
    100% { transform: translate(6px, 0); }
}
    </style>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="success-checkmark">
                <i class="fas fa-check"></i>
            </div>
            
            <h3>Order Placed Successfully!</h3>
            <p>{{Str::ucfirst($message)}}</p>
            
            <div class="redirect-info">
                <div class="countdown-text">Redirecting in</div>
                <div class="countdown-timer" id="countdown">5</div>
                <div class="redirect-message">You will be redirected to your order details automatically</div>
            </div>
            
            <a href="{{$url}}" class="btn" id="redirectBtn">
                <i class="fas fa-eye"></i> View Order Details
            </a>
        </div>
    </div>
</body>
<script>
    // Auto redirect with countdown
    const intendUrl = {!! " ' ".$url." ' "??"null" !!};
    let countdown = 5;
    const countdownElement = document.getElementById('countdown');
    const redirectBtn = document.getElementById('redirectBtn');
    
    // Prevent back navigation and redirect to home
    window.addEventListener('load', function() {
        // Replace current history entry with home to break the payment flow chain
        history.replaceState(null, null, location.origin);
        // Then push the success page state
        history.pushState(null, null, location.href);
        
        // Handle back button attempts - redirect to home
        window.addEventListener('popstate', function(event) {
            event.preventDefault();
            // Redirect to home page on back navigation attempt
            window.location.replace(location.origin);
        });
    });
    
    if (intendUrl != null) {
        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(timer);
                // Use replace to prevent back navigation and redirect
                window.location.replace(intendUrl);
            }
        }, 1000);
        
        // Manual redirect button
        redirectBtn.addEventListener('click', function(e) {
            e.preventDefault();
            clearInterval(timer);
            window.location.replace(intendUrl);
        });
    }
    
    // Disable back navigation with keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Prevent Alt + Left Arrow (back navigation)
        if (e.altKey && e.keyCode === 37) {
            e.preventDefault();
            return false;
        }
        // Prevent Backspace outside of input fields
        if (e.keyCode === 8) {
            var target = e.target || e.srcElement;
            if (target.tagName !== 'INPUT' && target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                return false;
            }
        }
    });
</script>
</html>
