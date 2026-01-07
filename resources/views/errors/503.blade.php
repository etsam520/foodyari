<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Service Unavailable - Food Yari</title>

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
            background: linear-gradient(135deg, #ff9a56 0%, #ffad56 100%);
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
            max-width: 700px;
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
            color: #ff810a;
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
        
        .btn-notify {
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
        }
        
        .btn-notify:hover {
            border-color: #ff810a;
            color: #ff810a;
        }
        
        .error-illustration {
            margin-bottom: 30px;
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        .maintenance-icon {
            font-size: 80px;
            color: #ff810a;
            margin-bottom: 20px;
            display: block;
            animation: rotate 4s linear infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .maintenance-section {
            margin-top: 40px;
            padding: 30px;
            background: linear-gradient(135deg, #fff5f0 0%, #fff8f5 100%);
            border-radius: 15px;
            border: 2px solid #fed7cc;
        }
        
        .maintenance-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .info-item {
            text-align: left;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .info-label {
            font-size: 12px;
            color: #ff810a;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-value {
            font-size: 16px;
            color: #2d3748;
            font-weight: 600;
        }
        
        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            margin-top: 10px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #ff810a, #ff6b00);
            border-radius: 3px;
            animation: progress 3s ease-in-out infinite;
        }
        
        @keyframes progress {
            0%, 100% { width: 30%; }
            50% { width: 80%; }
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 25px;
        }
        
        .feature-item {
            padding: 15px;
            background: rgba(255, 129, 10, 0.1);
            border-radius: 10px;
            color: #ff810a;
            font-size: 14px;
            font-weight: 600;
        }
        
        .feature-icon {
            font-size: 20px;
            margin-bottom: 8px;
            display: block;
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
            
            .maintenance-icon {
                font-size: 60px;
            }
            
            .btn-home,
            .btn-notify {
                display: block;
                margin: 10px auto;
                text-align: center;
            }
            
            .maintenance-section {
                padding: 20px;
            }
            
            .info-item {
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
            
            <i class="fas fa-tools maintenance-icon"></i>
            
            <div class="error-code">503</div>
            
            <h1 class="error-title">Kitchen Under Maintenance</h1>
            
            <p class="error-message">
                We're currently upgrading our kitchen to serve you better! 🔧<br>
                Our chefs are working hard to bring you an even more delicious experience.
            </p>
            
            <div>
                <a href="{{ url('/') }}" class="btn-home">
                    <i class="fas fa-home"></i>
                    Visit Homepage
                </a>
                
                <a href="#" onclick="subscribeToNotifications()" class="btn-notify">
                    <i class="fas fa-bell"></i>
                    Notify When Ready
                </a>
            </div>
            
            <div class="maintenance-section">
                <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 18px;">
                    <i class="fas fa-clock" style="color: #ff810a; margin-right: 10px;"></i>
                    Maintenance Information
                </h3>
                
                <div class="maintenance-info">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar"></i>
                            Started
                        </div>
                        <div class="info-value">{{ date('M d, Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-clock"></i>
                            Duration
                        </div>
                        <div class="info-value">2-4 Hours</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-chart-line"></i>
                            Progress
                        </div>
                        <div class="info-value">
                            In Progress
                            <div class="progress-bar">
                                <div class="progress-fill"></div>
                            </div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-users"></i>
                            Impact
                        </div>
                        <div class="info-value">All Services</div>
                    </div>
                </div>
                
                <div style="margin-top: 25px;">
                    <h4 style="color: #4a5568; font-size: 16px; margin-bottom: 15px; text-align: left;">
                        <i class="fas fa-sparkles" style="color: #ff810a; margin-right: 8px;"></i>
                        What's Coming:
                    </h4>
                    <div class="feature-grid">
                        <div class="feature-item">
                            <i class="fas fa-rocket feature-icon"></i>
                            Faster Performance
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-shield-alt feature-icon"></i>
                            Enhanced Security
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-mobile-alt feature-icon"></i>
                            Better Mobile Experience
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-star feature-icon"></i>
                            New Features
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function subscribeToNotifications() {
            alert('Thank you! We\'ll notify you once our kitchen is back online. 🍽️');
        }
    </script>

    <!-- Library Bundle Script -->
    <script src="{{asset('assets/js/core/libs.min.js')}}"></script>
</body>
</html>