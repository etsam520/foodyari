<?php
$redis = new \App\CentralLogics\Redis\RedisHelper();
$userLocation = [];
$locationPoint1 = [];
if (auth('customer')->check()) {
    $user = auth('customer')->user();
    $redisUserLocation  = $redis->get("user:{$user->id}:user_location");
    if($redisUserLocation != NULL){
        $us = json_decode($redisUserLocation);
        $locationPoint1['lat'] = $us->lat??0;
        $locationPoint1['lon'] = $us->lng??0;
    }
    
}else{
    $userLocation = Helpers::getGuestSession('guest_location');
    if($userLocation){
        $locationPoint1['lat'] = $userLocation['lat'];
        $locationPoint1['lon'] = $userLocation['lng'];
        
    }
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Askbootstrap">
    <meta name="author" content="Askbootstrap">
    <link rel="icon" type="image/png" href="{{asset('assets/images/icons/foodYariLogo.png')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (!empty($locationPoint1))
        <meta name="user-location"
            content="{{ json_encode(['lat' => $locationPoint1['lat'], 'lng' => $locationPoint1['lon']]) }}">
    @endif

    <title>Foodyari </title>
    <!-- Slick Slider -->
    <link href=" {{ asset('assets/user/vendor/slick/slick/slick.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/vendor/slick/slick/slick-theme.css') }}" rel="stylesheet" type="text/css">
    <!-- Feather Icon-->
    <link href="{{ asset('assets/user/vendor/icons/feather.css') }}" rel="stylesheet" type="text/css">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/user/vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/user/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/user/vendor/sidebar/demo.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/user/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}">


    <style>
        /* Base Styles */
        .auth-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ff810a;
            z-index: 9999;
            overflow: hidden;
            animation: fadeOut 1s ease-in-out 3s forwards;
        }
        
        .osahan-menu-fotter {
            visibility: hidden;
        }
    
        .location-image {
            height: 250px;
            margin-bottom: 2rem;
            animation: bounce 2s infinite alternate, pulse 2s infinite;
            transform-origin: center bottom;
        }
    
        .content-container {
            text-align: center;
            padding: 2rem;
            opacity: 0;
            animation: fadeIn 1s ease-in-out 0.5s forwards;
        }
    
        .title {
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
            font-size: 1.75rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            animation: slideUp 0.8s ease-out 1s forwards;
            opacity: 0;
            transform: translateY(20px);
        }
    
        .description {
            color: white;
            margin-bottom: 2rem;
            font-size: 1rem;
            opacity: 0;
            animation: fadeIn 0.8s ease-in-out 1.2s forwards;
        }
    
        .location-btn {
            width: 100%;
            font-weight: 700;
            font-size: 1rem;
            padding: 0.75rem;
            border: 2px solid white;
            border-radius: 8px;
            background: transparent;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0;
            animation: fadeIn 0.8s ease-in-out 1.5s forwards;
            transform: scale(1);
        }
    
        .location-btn:hover {
            background: rgba(255,255,255,0.1);
            transform: scale(1.02);
        }
    
        .location-btn i {
            margin-right: 0.5rem;
        }
    
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    
        /* @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; visibility: hidden; }
        } */
    
        @keyframes bounce {
            from { transform: translateY(0); }
            to { transform: translateY(-20px); }
        }
    
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
    
        /* Responsive Styles */
        @media (max-width: 576px) {
            .location-image {
                height: 180px;
            }
            
            .title {
                font-size: 1.5rem;
            }
            
            .description {
                font-size: 0.9rem;
            }
            
            .location-btn {
                font-size: 0.9rem;
                padding: 0.6rem;
            }
        }
    </style>

</head>


<div class="auth-wrapper">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-lg-8">
                <div class="content-container">
                    <img src="{{asset('assets/images/icons/location.svg')}}" alt="Location Icon" class="location-image">
                    <h5 class="title" id="title">FIND FOODS AND RESTAURANTS NEAR YOU</h5>
                    <p class="description" id="description">
                        By allowing location access, you can search for restaurants and foods near you and
                        receive more accurate delivery.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


<script>      
        document.addEventListener('DOMContentLoaded', () => {
            //===// refresh address //===//
            navigator.geolocation.getCurrentPosition((position) => {
                const currentLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                // console.log(currentLocation);
                let savedLocation = document.querySelector('meta[name="user-location"]')?.getAttribute(
                    'content'); 
                refreshSavedAddress(currentLocation);
          
            }, showError)
        });


        async function refreshSavedAddress(currentLocation) {
        // return
            const url = "{{ route('user.auth.refresh-saved-current-address') }}";

            try {
                const resp = await fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(currentLocation),
                });

                // If the response is not OK
                if (!resp.ok) {
                    const rdata = await resp.json();
                    console.error("Error refreshing saved address:", rdata);

                    // Display fallback message after 3 seconds
                    setTimeout(() => {
                        document.getElementById('title').textContent = "Couldn't find restaurant around you";
                        document.getElementById('description').textContent = "Try with other locations";
                    }, 3000);

                    // Redirect if URL is provided
                    if (rdata.url) {
                        location.href = rdata.url;
                    }

                    throw new Error(`HTTP error! Status: ${resp.status}`);
                }

                const data = await resp.json();
                console.log("Address refreshed successfully:", data);

                // Optional: slight delay before reload
                setTimeout(() => {
                    location.reload();
                }, 3000);

                return data;
            } catch (error) {
                console.error("Error refreshing saved address:", error);
                setTimeout(() => {
                        document.getElementById('title').textContent = "Couldn't find restaurant around you";
                        document.getElementById('description').textContent = "Failed to find locations";
                    }, 3000);
            }
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    // toastr.warning("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    toastr.warning("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    toastr.warning("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    toastr.warning("An unknown error occurred.");
                    break;
            }
        };
</script>
























<script>

    // Optional: Add JavaScript to handle the splash screen removal
    // document.addEventListener('DOMContentLoaded', function() {
    //     setTimeout(function() {
    //         const splash = document.querySelector('.auth-wrapper');
    //         splash.style.display = 'none';
    //     }, 5000); // matches the CSS animation duration
    // });
</script>

</body>

</html>
