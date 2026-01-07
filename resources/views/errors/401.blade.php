<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Unauthorized Access - Food Yari</title>

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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            color: #805ad5;
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
        
        .btn-login {
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
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(255, 129, 10, 0.4);
            color: white;
        }
        
        .btn-home {
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
        
        .btn-home:hover {
            border-color: #805ad5;
            color: #805ad5;
        }
        
        .error-illustration {
            margin-bottom: 30px;
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        .auth-icon {
            font-size: 80px;
            color: #805ad5;
            margin-bottom: 20px;
            display: block;
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes glow {
            0% { opacity: 1; }
            100% { opacity: 0.7; }
        }
        
        .auth-section {
            margin-top: 40px;
            padding: 30px;
            background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
            border-radius: 15px;
            border: 2px solid #e9d8fd;
        }
        
        .auth-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .auth-method {
            padding: 25px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .auth-method:hover {
            transform: translateY(-3px);
            border-color: #805ad5;
            box-shadow: 0 8px 25px rgba(128, 90, 213, 0.15);
            color: inherit;
        }
        
        .method-icon {
            font-size: 32px;
            color: #805ad5;
            margin-bottom: 15px;
            display: block;
        }
        
        .method-title {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }
        
        .method-desc {
            font-size: 14px;
            color: #718096;
            line-height: 1.4;
        }
        
        .security-info {
            margin-top: 25px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            text-align: left;
        }
        
        .security-features {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }
        
        .security-feature {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #4a5568;
        }
        
        .security-feature i {
            color: #38a169;
            font-size: 16px;
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
            
            .auth-icon {
                font-size: 60px;
            }
            
            .btn-login,
            .btn-home {
                display: block;
                margin: 10px auto;
                text-align: center;
            }
            
            .auth-section {
                padding: 20px;
            }
            
            .auth-method {
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
            
            <i class="fas fa-lock auth-icon"></i>
            
            <div class="error-code">401</div>
            
            <h1 class="error-title">Authentication Required</h1>
            
            <p class="error-message">
                You need to sign in to access our delicious kitchen! 🔐<br>
                Please authenticate to continue your food journey.
            </p>
            
            <div>
                <a href="{{ route('admin.auth.login') }}" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In Now
                </a>
                
                <a href="{{ url('/') }}" class="btn-home">
                    <i class="fas fa-home"></i>
                    Homepage
                </a>
            </div>
            
            <div class="auth-section">
                <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 18px;">
                    <i class="fas fa-shield-alt" style="color: #805ad5; margin-right: 10px;"></i>
                    Authentication Methods
                </h3>
                
                <div class="auth-methods">
                    <a href="{{ route('admin.auth.login') }}" class="auth-method">
                        <i class="fas fa-user-circle method-icon"></i>
                        <div class="method-title">Admin Login</div>
                        <div class="method-desc">Access admin dashboard with your credentials</div>
                    </a>
                    
                    <a href="{{ route('vendor.auth.login') }}" class="auth-method">
                        <i class="fas fa-store method-icon"></i>
                        <div class="method-title">Vendor Login</div>
                        <div class="method-desc">Manage your restaurant and orders</div>
                    </a>
                    
                    <a href="{{ route('deliveryman.auth.login') }}" class="auth-method">
                        <i class="fas fa-motorcycle method-icon"></i>
                        <div class="method-title">Delivery Login</div>
                        <div class="method-desc">Handle delivery assignments</div>
                    </a>
                </div>
                
                <div class="security-info">
                    <h4 style="color: #4a5568; font-size: 16px; margin-bottom: 10px;">
                        <i class="fas fa-check-circle" style="color: #38a169; margin-right: 8px;"></i>
                        Your security is our priority
                    </h4>
                    
                    <div class="security-features">
                        <div class="security-feature">
                            <i class="fas fa-shield-alt"></i>
                            <span>SSL Encryption</span>
                        </div>
                        <div class="security-feature">
                            <i class="fas fa-lock"></i>
                            <span>Secure Sessions</span>
                        </div>
                        <div class="security-feature">
                            <i class="fas fa-eye-slash"></i>
                            <span>Privacy Protection</span>
                        </div>
                        <div class="security-feature">
                            <i class="fas fa-clock"></i>
                            <span>Session Timeout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Library Bundle Script -->
    <script src="{{asset('assets/js/core/libs.min.js')}}"></script>
</body>
</html>