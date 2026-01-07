<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    @if (str_contains(request()->header('User-Agent'), 'Foodyari Delivery'))
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    @else
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="dm_id" content="{{ auth('delivery_men')->user()->id }}">
    <meta name="description" content="Foodyari">
    <meta name="author" content="Givni Private Limited">
    <link rel="icon" type="image/png" href="{{ asset('assets/user/img/logo_web.png') }}">
    <title>Foodyari - Delivery Boy Panel</title>
    <!-- Slick Slider -->
    <link href=" {{ asset('assets/user/vendor/slick/slick/slick.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/vendor/slick/slick/slick-theme.css') }}" rel="stylesheet" type="text/css">
    <!-- Feather Icon-->
    <link href="{{ asset('assets/user/vendor/icons/feather.css') }}" rel="stylesheet" type="text/css">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/user/vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ asset('assets/user/css/style.css') }}" rel="stylesheet">
    <!-- Sidebar CSS -->
    <link href="{{ asset('assets/user/vendor/sidebar/demo.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/user/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}">
    <link href="{{ asset('assets/vendor/select2/select2.min.css') }}" rel="stylesheet" />

    <style>
        @media (max-width: 576px) {
            .sidebar {
                width: 295px !important;
            }
        }
        
        /* Enhanced Notification Styles */
        .notification-bell-header {
            transition: all 0.3s ease;
            position: relative;
        }
        
        .notification-bell-header:hover {
            transform: scale(1.1);
        }
        
        .notification-bell-header:hover i {
            color: #ff8a00 !important;
        }
        
        .notification-badge-header {
            animation: pulse-badge 2s infinite;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.3);
        }
        
        .notification-pulse {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: #dc3545;
            border-radius: 50%;
            animation: pulse-dot 1.5s infinite;
        }
        
        @keyframes pulse-badge {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(220, 53, 69, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }
        
        @keyframes pulse-dot {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.3;
                transform: scale(1.3);
            }
        }
        
        /* Sidebar Notification Styles */
        .notification-sidebar-link {
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 2px 8px;
        }
        
        .notification-sidebar-item {
            border-radius: 6px !important;
            transition: all 0.3s ease;
        }
        
        .notification-sidebar-link:hover .notification-sidebar-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .notification-badge-sidebar {
            animation: bounce-in 0.5s ease-out;
        }
        
        .notification-indicator {
            position: absolute;
            top: 15px;
            right: 8px;
            width: 6px;
            height: 6px;
            background: #28a745;
            border-radius: 50%;
            animation: blink 1s infinite;
        }
        
        @keyframes bounce-in {
            0% {
                transform: translateY(-50%) scale(0);
                opacity: 0;
            }
            50% {
                transform: translateY(-50%) scale(1.2);
                opacity: 1;
            }
            100% {
                transform: translateY(-50%) scale(1);
                opacity: 1;
            }
        }
        
        @keyframes blink {
            0%, 50% {
                opacity: 1;
            }
            51%, 100% {
                opacity: 0.3;
            }
        }
        
        /* Active notification page styling */
        .notification-sidebar-link.active .notification-sidebar-item {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white !important;
        }
        
        .notification-sidebar-link.active .notification-text {
            color: white !important;
        }
        
        .notification-sidebar-link.active i {
            color: white !important;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .notification-bell-header {
                padding: 10px !important;
            }
            
            .notification-badge-header {
                top: 2px;
                right: -2px;
            }
        }
    </style>
    @stack('css')

</head>

<body class="fixed-bottom-bar">

    @include('deliveryman.admin.layouts.header')
    {{-- @include('deliveryman.admin.partials.notifications') --}}



    @yield('content')




    @include('deliveryman.admin.layouts.nav')


    @include('deliveryman.admin.layouts.footer')


    {{-- user location modle --}}
    <div class="modal fade" id="userMap" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content bg-transparent">
                <div class="d-flex justify-content-between">
                    <h5 class="modal-title text-white"><img src="{{ asset('assets/user/img/icons/userBuilding.svg') }}"
                            alt=""> MY LOCATION
                    </h5>
                    <span class="bg-transparent" data-bs-dismiss="modal">
                        <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"
                            width="30">
                            <path
                                d="M12,2.01c-5.51,0-9.99,4.47-9.99,9.99c0,5.51,4.47,9.99,9.99,9.99c5.52,0,9.99-4.47,9.99-9.99C21.99,6.49,17.52,2.01,12,2.01z M12,19.87c-4.34,0-7.87-3.53-7.87-7.87c0-4.34,3.53-7.87,7.87-7.87c4.34,0,7.87,3.53,7.87,7.87C19.87,16.34,16.34,19.87,12,19.87z"
                                fill="#fff"></path>
                            <rect width="11.3" height="2.12" x="6.42" y="10.94" fill="#fff"></rect>
                        </svg>
                    </span>
                </div>

                <div class="modal-body p-0  ">
                    <div id="map-canvas" style=" width:100%;height: 50vh"></div>
                    <div class="row">
                        <div class="col-md-6 mx-auto">
                            <div class="form-group d-flex">
                                <input type="text" id="search-input" class="form-control rounded-0"
                                    placeholder="Enter Address or Place">
                                <button data-address="search" class="btn btn-primary rounded-0">Search</button>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid mt-3 bg-white p-4 shadow-lg rounded mb-2">
                        <div class="row">
                            <div class="col-md-9">
                                <h3 class="text-primary">Address</h3>
                                <hr>
                                <div class="d-flex">
                                    <i class="feather-map-pin text-primary mt-1 mt-lg-1 me-2"></i>
                                    <div>
                                        <div>
                                            <span class="mb-1 mt-1" data-user-address="true">
                                            </span>
                                        </div>
                                        <button class="btn btn-primary mt-2" data-address="change">Change</button>
                                    </div>
                                </div>

                                {{-- <div class="form-group mt-3">
                                <input type="text" name="tag"
                                    class="form-control edit-address-input edit-address-tag mb-2" placeholder="Eg. Home, Work"
                                    value="">
                            </div> --}}

                            </div>
                            <div class="col-md-3">
                                <img src="{{ asset('assets/user/img/icons/user-location.gif') }}" style="width: 100%"
                                    alt="">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="{{ asset('assets/user/vendor/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/user/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <!-- slick Slider JS-->
    <script type="text/javascript" src="{{ asset('assets/user/vendor/slick/slick/slick.min.js') }}"></script>
    <!-- Sidebar JS-->
    <script type="text/javascript" src="{{ asset('assets/user/vendor/sidebar/hc-offcanvas-nav.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script type="text/javascript" src="{{ asset('assets/user/js/osahan.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"></script>
    <script src="{{ asset('assets/vendor/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert2/sweetalert2@11.js') }}"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&libraries=geometry,places">
    </script>
    <script src="{{ asset('assets/js/Helpers/mapHelper.js') }}"></script>
    <script src=" {{ asset('firebase/index.js') }}" type="module"></script>
    @vite(['resources/js/app.js'])

    @stack('javascript')
    
    {{-- Include Notification Service --}}
    @include('deliveryman.admin.partials.notification-service')
    @stack('js')

    @if (Session::has('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ Session::get('success') }}',
                timer: 2000,
                showConfirmButton: false,
            });
        </script>
    @endif

    @if (Session::has('info'))
        <script>
            Swal.fire({
                icon: 'info',
                title: '{{ Session::get('info') }}',
                timer: 2000,
                showConfirmButton: false,
            });
        </script>
    @endif

    @if (Session::has('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '{{ Session::get('error') }}',
                timer: 2000,
                showConfirmButton: false,
            });
        </script>
    @endif

    @if (Session::has('warning'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: '{{ Session::get('warning') }}',
                timer: 2000,
                showConfirmButton: false,
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoadedj', function() {

            const socket = new WebSocket("ws://127.0.0.1:6001/app/local?protocol=7&client=js&version=7.2.0");

            socket.onopen = () => {
                console.log("Connected to WebSocket");

                setInterval(() => {
                    navigator.geolocation.getCurrentPosition((pos) => {
                        const data = {
                            // event:"pusher:pong",
                            event: "customer_location_update",
                            channel: `public-delivery.${$('meta[name="dm_id"]').attr('content')}`,
                            type: 'location_update',
                            order_id: 123, // dynamic order ID
                            lat: pos.coords.latitude,
                            lng: pos.coords.longitude
                        };
                        socket.send(JSON.stringify(data));
                    });
                }, 1000);
            };

            socket.onmessage = (event) => {
                console.log("Server:", event.data);
            };
            Echo.private(`order.123`)
                .listen('DeliveryLocationUpdated', (e) => {
                    console.log('Location received:', e.lat, e.lng);
                    updateMapMarker(e.lat, e.lng);
                });
        });
    </script>
    <script>
        function initDMLocation() {
            if (!navigator.geolocation) {
                Swal.fire({
                    icon: 'error',
                    title: 'Geolocation is not supported by this browser.',
                    timer: 2000,
                    showConfirmButton: false,
                });
                return;
            }

            const dmId = $('meta[name="dm_id"]').attr('content');
            if (!dmId) {
                console.error("❌ DM ID not found in meta tag.");
                return;
            }

            // const socket = new WebSocket('wss://foodyari.com/ws');
            const socket = new WebSocket('ws://192.168.1.7:6002');
            let intervalId = null;

            socket.onopen = () => {
                console.log("📡 Connected to WebSocket");
                /*intervalId = setInterval(() => {
                    navigator.geolocation.getCurrentPosition(
                        (pos) => {
                            const data = {
                                id: dmId,
                                lat: pos.coords.latitude,
                                lng: pos.coords.longitude
                            };
                            socket.send(JSON.stringify(data));
                        },
                        (err) => {
                            console.error(" Geolocation error:", err.message);
                        }
                    );
                }, 5000); // Send location every 5 seconds */
                let lastLat = null;
                let lastLng = null;

                intervalId = setInterval(() => {
                    navigator.geolocation.getCurrentPosition(
                        (pos) => {
                            const lat = pos.coords.latitude;
                            const lng = pos.coords.longitude;

                            // If location hasn't changed, don't send
                            if (lat != lastLat && lng === lastLng) {
                                return;
                            }

                            // Update stored coordinates
                            lastLat = lat;
                            lastLng = lng;

                            const data = {
                                user_id: dmId,
                                type: 'deliveryman',
                                lat: lat,
                                lng: lng
                            };

                            socket.send(JSON.stringify(data));
                        },
                        (err) => {
                            console.error("📍 Geolocation error:", err.message);
                        }
                    );
                }, 5000);
            };

            socket.onmessage = (event) => {
                console.log("📥 Incoming message:", event.data);
            };

            socket.onerror = (error) => {
                console.error("❌ WebSocket error:", error);
            };

            socket.onclose = () => {
                console.log("🔌 WebSocket closed");
                if (intervalId) clearInterval(intervalId);
            };

            // Optional: clean up on page unload
            window.addEventListener("beforeunload", () => {
                if (socket.readyState === WebSocket.OPEN) {
                    socket.close();
                }
            });
        }
        document.addEventListener("DOMContentLoaded", initDMLocation);
    </script>



    <script>
        var audio = new Audio("{{ asset('sound/notification-tone.mp3') }}");
        navigator.serviceWorker.addEventListener('message', function(event) {
            console.log('CLIENT:: Received message from service worker:', event);
            if (event.data?.notificationData?.data?.audio_link) {
                audio.src = event.data?.notificationData?.data?.audio_link;
            }

            let title = event.data?.notificationData?.notification?.title || event.data?.notificationData?.data
                ?.message || '';
            if (event.data.action === 'playAudio') {
                console.log('CLIENT:: Playing audio');
                audio.play().catch(function(error) {
                    console.error('CLIENT:: Error playing audio:', error);
                });

                if (event.data?.notificationData?.data?.order_status == 'pending') {
                    audio.loop = true;
                    confirmOrder(event.data?.notificationData?.data?.order_id, title)
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: title,
                        timer: 2000,
                        showConfirmButton: false,
                    });
                }
            } else {
                Swal.fire({
                    icon: 'info',
                    title: title,
                    timer: 2000,
                    showConfirmButton: false,
                });
            }
        });

        // document.addEventListener("DOMContentLoaded", function() {
        // // Trigger a click event after the page loads
        // var playButton = document.getElementById("playButton");

        // playButton.click();

        // playButton.addEventListener("click", function() {
        //     audio.play().catch(function(error) {
        //     console.error("Audio playback failed:", error);
        //     });
        // });
        // });
    </script>
    <script>
        async function confirmOrder(orderId, title) {
            Swal.fire({
                title: title,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Accept',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then(async (result) => {
                if (result.isConfirmed) {
                    location.href = "{{ route('deliveryman.admin.order-confirmation') }}?order_id=" +
                        orderId + "&status=accept";
                    return;
                    try {
                        const resp = await fetch(
                            '{{ route('deliveryman.admin.order-confirmation') }}?order_id=' + orderId +
                            "&status=accept");
                        if (!resp.ok) {
                            const error = await resp.json();
                            throw new Error(error.message);
                        } else {
                            const data = await resp.json();
                            Swal.fire('Saved!', data.message, 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 5000);
                        }
                    } catch (error) {
                        console.error('Error fetching data:', error);
                        Swal.fire('Error', error.message, 'error');
                    }
                }
            });
        }
    </script>
</body>

</html>
