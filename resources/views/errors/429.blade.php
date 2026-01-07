<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Too Many Requests - Food Yari</title>

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
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
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
            max-width: 650px;
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
            color: #e53e3e;
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
        
        .btn-wait {
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
            cursor: default;
            opacity: 0.7;
        }
        
        .error-illustration {
            margin-bottom: 30px;
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        .limit-icon {
            font-size: 80px;
            color: #e53e3e;
            margin-bottom: 20px;
            display: block;
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        
        .rate-limit-section {
            margin-top: 40px;
            padding: 30px;
            background: #fff5f5;
            border-radius: 15px;
            border: 2px solid #fed7d7;
        }
        
        .rate-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .rate-item {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .rate-number {
            font-size: 32px;
            font-weight: 800;
            color: #e53e3e;
            display: block;
            margin-bottom: 5px;
        }
        
        .rate-label {
            font-size: 12px;
            color: #718096;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .cooldown-timer {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        
        .timer-display {
            font-size: 48px;
            font-weight: 800;
            color: #ff810a;
            margin-bottom: 10px;
            font-family: 'Courier New', monospace;
        }
        
        .timer-label {
            font-size: 16px;
            color: #718096;
            margin-bottom: 15px;
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #ff810a, #ff6b00);
            border-radius: 4px;
            transition: width 1s ease;
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
            
            .limit-icon {
                font-size: 60px;
            }
            
            .btn-home,
            .btn-wait {
                display: block;
                margin: 10px auto;
                text-align: center;
            }
            
            .rate-limit-section {
                padding: 20px;
            }
            
            .timer-display {
                font-size: 36px;
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
            
            <i class="fas fa-exclamation-triangle limit-icon"></i>
            
            <div class="error-code">429</div>
            
            <h1 class="error-title">Too Many Orders!</h1>
            
            <p class="error-message">
                Whoa! You're making requests faster than our chef can cook! 🍳<br>
                Please take a short break and let our kitchen catch up.
            </p>
            
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn-home">
                    <i class="fas fa-home"></i>
                    Back to Dashboard
                </a>
                
                <button class="btn-wait" disabled>
                    <i class="fas fa-pause"></i>
                    Please Wait...
                </button>
            </div>
            
            <div class="rate-limit-section">
                <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 18px;">
                    <i class="fas fa-tachometer-alt" style="color: #e53e3e; margin-right: 10px;"></i>
                    Rate Limit Information
                </h3>
                
                <div class="rate-info">
                    <div class="rate-item">
                        <span class="rate-number">100</span>
                        <div class="rate-label">Requests/Hour</div>
                    </div>
                    <div class="rate-item">
                        <span class="rate-number">10</span>
                        <div class="rate-label">Requests/Minute</div>
                    </div>
                    <div class="rate-item">
                        <span class="rate-number">0</span>
                        <div class="rate-label">Remaining</div>
                    </div>
                </div>
                
                <div class="cooldown-timer">
                    <div class="timer-display" id="cooldown-timer">01:00</div>
                    <div class="timer-label">Time until you can try again</div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill" style="width: 100%;"></div>
                    </div>
                </div>
                
                <div style="margin-top: 25px; text-align: left;">
                    <h4 style="color: #4a5568; font-size: 16px; margin-bottom: 15px;">
                        <i class="fas fa-info-circle" style="color: #ff810a; margin-right: 8px;"></i>
                        Why rate limits exist:
                    </h4>
                    <ul style="color: #718096; line-height: 1.8; text-align: left; display: inline-block;">
                        <li>To ensure fair access for all users</li>
                        <li>To protect our servers from overload</li>
                        <li>To maintain optimal performance</li>
                        <li>To prevent abuse and spam</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Cooldown timer
        let timeLeft = 60; // 1 minute
        const timerElement = document.getElementById('cooldown-timer');
        const progressElement = document.getElementById('progress-fill');
        
        const timer = setInterval(() => {
            timeLeft--;
            
            // Format time as MM:SS
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            // Update progress bar
            const progress = (timeLeft / 60) * 100;
            progressElement.style.width = `${progress}%`;
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                timerElement.textContent = '00:00';
                timerElement.style.color = '#38a169';
                document.querySelector('.timer-label').textContent = 'You can try again now!';
                progressElement.style.width = '0%';
                
                // Enable retry after cooldown
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        }, 1000);
    </script>

    <!-- Library Bundle Script -->
    <script src="{{asset('assets/js/core/libs.min.js')}}"></script>
</body>
</html>