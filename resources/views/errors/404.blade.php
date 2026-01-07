<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Page Not Found - Food Yari</title>

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
        }
        
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(255, 129, 10, 0.4);
            color: white;
        }
        
        .error-illustration {
            margin-bottom: 30px;
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .search-section {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
        }
        
        .search-form {
            display: flex;
            max-width: 400px;
            margin: 20px auto 0;
        }
        
        .search-input {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 25px 0 0 25px;
            border-right: none;
            outline: none;
            font-size: 16px;
        }
        
        .search-input:focus {
            border-color: #ff810a;
        }
        
        .search-btn {
            background: #ff810a;
            border: 2px solid #ff810a;
            border-radius: 0 25px 25px 0;
            padding: 12px 20px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-btn:hover {
            background: #ff6b00;
            border-color: #ff6b00;
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
            
            .search-form {
                flex-direction: column;
                gap: 10px;
            }
            
            .search-input,
            .search-btn {
                border-radius: 25px;
                border: 2px solid #e2e8f0;
            }
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-card">
            <div class="error-illustration floating">
                <img src="{{asset('assets/images/icons/foodyari.logo.jpg')}}" alt="Food Yari Logo" class="error-logo">
                <i class="fas fa-utensils" style="font-size: 60px; color: #ff810a; margin-left: 20px;"></i>
            </div>
            
            <div class="error-code">404</div>
            
            <h1 class="error-title">Oops! Page Not Found</h1>
            
            <p class="error-message">
                The delicious page you're looking for seems to have been eaten! 🍽️<br>
                Don't worry, let's get you back to our tasty menu.
            </p>
            
            <a href="{{ route('userHome') }}" class="btn-home">
                <i class="fas fa-home"></i>
                Back to Dashboard
            </a>
            
            {{-- <div class="search-section">
                <p style="color: #718096; margin-bottom: 10px;">Or search for what you need:</p>
                <form class="search-form" action="{{ route('admin.dashboard') }}" method="GET">
                    <input type="text" name="search" class="search-input" placeholder="Search for restaurants, dishes...">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div> --}}
        </div>
    </div>

    <!-- Library Bundle Script -->
    <script src="{{asset('assets/js/core/libs.min.js')}}"></script>
</body>
</html>