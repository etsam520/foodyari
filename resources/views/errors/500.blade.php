<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Server Error - Food Yari</title>

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
        
        .btn-refresh {
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
        
        .btn-refresh:hover {
            border-color: #ff810a;
            color: #ff810a;
        }
        
        .error-illustration {
            margin-bottom: 30px;
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        .shake {
            animation: shake 0.5s ease-in-out infinite alternate;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes shake {
            0% { transform: translateX(0px); }
            100% { transform: translateX(2px); }
        }
        
        .server-icon {
            font-size: 80px;
            color: #e53e3e;
            margin-bottom: 20px;
            display: block;
        }
        
        .status-section {
            margin-top: 40px;
            padding: 30px;
            background: #f7fafc;
            border-radius: 15px;
            border-left: 4px solid #e53e3e;
        }
        
        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .status-item {
            text-align: left;
        }
        
        .status-label {
            font-size: 12px;
            color: #a0aec0;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        
        .status-value {
            font-size: 16px;
            color: #2d3748;
            font-weight: 600;
        }
        
        .incident-id {
            font-family: 'Courier New', monospace;
            background: #e2e8f0;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
            font-size: 14px;
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
            
            .server-icon {
                font-size: 60px;
            }
            
            .btn-home,
            .btn-refresh {
                display: block;
                margin: 10px auto;
                text-align: center;
            }
            
            .status-section {
                padding: 20px;
                text-align: center;
            }
            
            .status-item {
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
            
            <i class="fas fa-server server-icon shake"></i>
            
            <div class="error-code">500</div>
            
            <h1 class="error-title">Kitchen's Having Issues!</h1>
            
            <p class="error-message">
                Our kitchen servers are experiencing some difficulties. 🔥<br>
                Don't worry - our tech chefs are working hard to fix this!
            </p>
            
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn-home">
                    <i class="fas fa-home"></i>
                    Back to Dashboard
                </a>
                
                <button onclick="window.location.reload()" class="btn-refresh">
                    <i class="fas fa-sync-alt"></i>
                    Try Again
                </button>
            </div>
            
            <div class="status-section">
                <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 18px;">
                    <i class="fas fa-info-circle" style="color: #e53e3e; margin-right: 10px;"></i>
                    Error Details
                </h3>
                
                <div class="status-grid">
                    <div class="status-item">
                        <div class="status-label">Error Type</div>
                        <div class="status-value">Internal Server Error</div>
                    </div>
                    <div class="status-item">
                        <div class="status-label">Status Code</div>
                        <div class="status-value">HTTP 500</div>
                    </div>
                    <div class="status-item">
                        <div class="status-label">Time</div>
                        <div class="status-value">{{ date('Y-m-d H:i:s') }}</div>
                    </div>
                    <div class="status-item">
                        <div class="status-label">Incident ID</div>
                        <div class="status-value">
                            <span class="incident-id">FY-{{ substr(md5(time()), 0, 8) }}</span>
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 25px; text-align: left;">
                    <h4 style="color: #4a5568; font-size: 16px; margin-bottom: 15px;">What can you do?</h4>
                    <ul style="color: #718096; line-height: 1.8; text-align: left; display: inline-block;">
                        <li>Try refreshing the page in a few minutes</li>
                        <li>Go back to the previous page</li>
                        <li>Contact our support team if the problem persists</li>
                        <li>Check our status page for any ongoing issues</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Library Bundle Script -->
    <script src="{{asset('assets/js/core/libs.min.js')}}"></script>
</body>
</html>