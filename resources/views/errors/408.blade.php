<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Request Timeout - Food Yari</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/icons/foodYariLogo.png')}}" />

    <!-- Library / Plugin Css Build -->
    <link rel="stylesheet" href="{{asset('assets/css/core/libs.min.css')}}" />

    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="{{asset('assets/css/hope-ui.min.css?v=2.0.0')}}" />

    <!-- Custom Css -->
    <link rel="stylesheet" href="{{asset('assets/css/custom.min.css?v=2.0.0')}}" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        .error-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .error-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            padding: 60px 40px;
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        
        .error-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 30px;
        }
        
        .error-code {
            font-size: 120px;
            font-weight: 800;
            color: #3182ce;
            line-height: 1;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .error-title {
            font-size: 32px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 15px;
        }
        
        .error-message {
            font-size: 18px;
            color: #718096;
            margin-bottom: 40px;
            line-height: 1.6;
        }
        
        .btn-home {
            background: linear-gradient(135deg, #ff810a 0%, #ff6b00 100%);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            border: none;
            box-shadow: 0 10px 25px rgba(255, 129, 10, 0.3);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-right: 15px;
            margin-bottom: 10px;
        }
        
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(255, 129, 10, 0.4);
            color: white;
        }
        
        .btn-retry {
            background: transparent;
            color: #718096;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            cursor: pointer;
        }
        
        .btn-retry:hover {
            border-color: #3182ce;
            color: #3182ce;
        }
        
        .error-illustration {
            margin-bottom: 30px;
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        .timeout-icon {
            font-size: 80px;
            color: #3182ce;
            margin-bottom: 20px;
            display: block;
            animation: tick 1s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes tick {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .timeout-section {
            margin-top: 30px;
            padding: 30px;
            background: #f0f9ff;
            border-radius: 15px;
            border: 2px solid #bfdbfe;
        }
        
        .tips-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .tip-item {
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            text-align: left;
        }
        
        .tip-icon {
            font-size: 24px;
            color: #3182ce;
            margin-bottom: 15px;
            display: block;
        }
        
        .tip-title {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }
        
        .tip-desc {
            font-size: 14px;
            color: #718096;
            line-height: 1.4;
        }
        
        .countdown {
            font-size: 18px;
            color: #3182ce;
            font-weight: 600;
            margin-top: 20px;
        }
        
        .loading-dots {
            display: inline-block;
        }
        
        .loading-dots::after {
            content: '';
            animation: dots 1.5s steps(4, end) infinite;
        }
        
        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }
        
        @media (max-width: 768px) {
            .error-card {
                padding: 40px 20px;
            }
            
            .error-code {
                font-size: 80px;
            }
            
            .error-title {
                font-size: 24px;
            }
            
            .error-message {
                font-size: 16px;
            }
            
            .timeout-icon {
                font-size: 60px;
            }
            
            .btn-home,
            .btn-retry {
                display: block;
                margin: 10px auto;
                text-align: center;
            }
            
            .timeout-section {
                padding: 20px;
            }
            
            .tip-item {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-card">
            <div class="error-illustration floating">
                <img src="{{asset('assets/images/icons/foodyari.logo.jpg')}}" alt="Food Yari Logo" class="error-logo">
            </div>
            
            <i class="fas fa-hourglass-half timeout-icon"></i>
            
            <div class="error-code">408</div>
            
            <h1 class="error-title">Request Timed Out</h1>
            
            <p class="error-message">
                Your order took too long to process! ⏰<br>
                Our kitchen might be extra busy right now. Let's try again.
            </p>
            
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn-home">
                    <i class="fas fa-home"></i>
                    Back to Dashboard
                </a>
                
                <button onclick="window.location.reload()" class="btn-retry">
                    <i class="fas fa-redo"></i>
                    <span class="loading-dots">Try Again</span>
                </button>
            </div>
            
            <div class="timeout-section">
                <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 18px;">
                    <i class="fas fa-lightbulb" style="color: #3182ce; margin-right: 10px;"></i>
                    Quick Tips to Avoid Timeouts
                </h3>
                
                <div class="tips-grid">
                    <div class="tip-item">
                        <i class="fas fa-wifi tip-icon"></i>
                        <div class="tip-title">Check Connection</div>
                        <div class="tip-desc">Ensure you have a stable internet connection</div>
                    </div>
                    
                    <div class="tip-item">
                        <i class="fas fa-clock tip-icon"></i>
                        <div class="tip-title">Peak Hours</div>
                        <div class="tip-desc">Try again during off-peak hours for faster response</div>
                    </div>
                    
                    <div class="tip-item">
                        <i class="fas fa-sync-alt tip-icon"></i>
                        <div class="tip-title">Refresh Page</div>
                        <div class="tip-desc">Sometimes a simple refresh can solve the issue</div>
                    </div>
                    
                    <div class="tip-item">
                        <i class="fas fa-phone tip-icon"></i>
                        <div class="tip-title">Contact Support</div>
                        <div class="tip-desc">If problem persists, reach out to our support team</div>
                    </div>
                </div>
                
                <div class="countdown">
                    <i class="fas fa-info-circle"></i>
                    Auto-retry in <span id="countdown">30</span> seconds
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-retry countdown
        let countdown = 30;
        const countdownElement = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.reload();
            }
        }, 1000);
    </script>

    <!-- Library Bundle Script -->
    <script src="{{asset('assets/js/core/libs.min.js')}}"></script>
</body>
</html>