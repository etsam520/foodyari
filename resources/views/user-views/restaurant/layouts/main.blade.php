<!DOCTYPE html>
<html lang="en">
<?php
    $redis = new \App\CentralLogics\Redis\RedisHelper();

    $userCookieLocation = isset($_COOKIE['user_location']) ? json_decode($_COOKIE['user_location']) : null;
    $isManualSelection = isset($_COOKIE['user_location_manual_selection']) ? true : false;
    $isLocationUpdated = isset($_COOKIE['user_location_update']) ? true : false;

    $uloc = [];
    $userLocations = collect([]);
    $default_address = [
        'type' => 'Home',
        'address' => '',
    ];

    $locationPoint1 = [];
    if (auth('customer')->check()) {
        $user = auth('customer')->user();
        $redisUserLocation  = $redis->get("user:{$user->id}:user_location");
        if($redisUserLocation != NULL){
            $uloc = json_decode($redisUserLocation);
        }

        $userLocations = auth('customer')
            ->user()
            ->customerAddress()
            ->when($userCookieLocation && $userCookieLocation->lat && $userCookieLocation->lng, function ($query) use ($userCookieLocation) {
                $haversine = "(6371 * acos(cos(radians($userCookieLocation->lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians($userCookieLocation->lng)) + sin(radians($userCookieLocation->lat)) * sin(radians(latitude))))";
                $query->selectRaw("*, $haversine AS distance")
                    ->orderByRaw("CASE WHEN is_default THEN 0 ELSE 1 END, distance");
            }, function ($query) {
                $query->orderByRaw("CASE WHEN is_default THEN 0 ELSE 1 END");
            })
            ->get();

        $userLocation = $userLocations->first();
        if ($userLocation) {
            $default_address['type'] = $userLocation->type ?? 'Home';
            $default_address['address'] = $userLocation->address ?? '';
        }
    } else {
        $userLocation = Helpers::getGuestSession('guest_location');
        if ($userLocation) {
            $default_address['type'] = $userLocation['type'];
            $default_address['address'] = $userLocation['address'];
        }
    }

// echo "<pre>";
// print_r($userCookieLocation);
// print_r($userLocations->toArray());
// echo "</pre>";
?>
{{-- @dd($userLocations[0]) --}}

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Best Food Providing Meal">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (isset($uloc))
        <meta name="user-location"
            content="{{json_encode($uloc)}}">
    @endif
    <meta name="author" content="Givni Private Limited">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icons/foodYariLogo.png') }}">
    <title>Foodyari</title>
    {{-- <link rel="manifest" href="/foodyari_live/manifest.json"> --}}
    <link rel="manifest" href="/manifest.json">

    <link href="{{ asset('assets/user/vendor/slick/slick/slick.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/vendor/slick/slick/slick-theme.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/vendor/icons/feather.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/css/custom.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/vendor/sidebar/demo.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/css/restaurant.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/icheck-material@1.0.1/icheck-material.min.css">
    <script>
        const APP_URL = '{{ url('/') }}';
    </script>
    <style>
        .truncate {
            display: inline-block;
            max-width: 100px;
            /* Adjust this value to fit your layout */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-item {
            padding: 0px !important;
        }

        .nav-item h2 {
            background: none;
            padding: 0px !important;
        }

        .accordion-button:focus {
            z-index: 3;
            border-color: #ff810a !important;
            outline: 0;
            box-shadow: #ff810a !important;
        }

        .accordion-button:not(.collapsed) {
            color: #ff810a !important;
            background-color: #ff810a2e !important;
            box-shadow: #ff810a !important;
        }

        .bg-warning {
            background-color: #ff810a !important;
        }

        .sidebar {
            width: 350px !important;
        }

        .truncate-text {
            display: inline-block;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #darkModeToggle {
            position: fixed;
            top: 15px;
            right: 15px;
            padding: 10px 15px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            z-index: 9999;
        }

        @media (max-width: 576px) {
            .toggle-btn {
                color: white !important;
            }

            .sidebar {
                width: 295px !important;
            }

            .truncate-text {
                display: inline-block;
                max-width: 100px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .ordered-restaurant-truncate {
                display: inline-block;
                max-width: 200px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }
    </style>

    @stack('css')
</head>

<body class="fixed-bottom-bar">
    @include('user-views.restaurant.layouts.header')

    {{-- osahan home page start --}}
    <div class="osahan-home-page">
        <div class="container-fluid position-relative">
            <!-- Slider for header section -->

            {{-- <div class="popular-slider ">
                <div class="popular-item p-0 p-lg-2">
                    <a class="d-block text-center shadow-sm" href="javascript:();">
                        <div class="position-relative">
                            <img alt="#" src="{{ asset('assets/user/img/banner-1.jpg') }}" class="img-fluid rounded">
                            <div class="overlay"></div>
                        </div>
                    </a>
                </div>
            </div> --}}


            {{-- @php
                $userLocations = [];
                $userLocation = [];
                if (auth('customer')->check()) {
                    $userLocations = auth('customer')
                        ->user()
                        ->customerAddress()
                        ->orderByDesc('is_default')
                        ->orderByDesc('id')
                        ->get();
                    $userLocation = $userLocations->first();
                    $default_address = [
                        'type' => !empty($userLocation) ? $userLocation->type : 'Select Address',
                        'address' => !empty($userLocation) ? $userLocation->address : '',
                    ];
                } else {
                    $userLocation = Helpers::getGuestSession('guest_location');
                    if ($userLocation) {
                        $default_address['type'] = $userLocation['type'];
                        $default_address['address'] = $userLocation['address'];
                    }
                }

            @endphp --}}

            <!-- Header Section -->

            @stack('sub-header')
        </div>
        {{-- slider --}}
        @stack('mobile-header')
        @stack('slider')


        @yield('containt')

    </div>
    @stack('modal')

    {{-- Saved Location --}}
    <div class="offcanvas offcanvas-bottom rounded-4 rounded-bottom-0 overflow-hidden" id="userSavedLocation"
        tabindex="-1" aria-labelledby="userMapLabel" style="height: 80vh">
        <div class="offcanvas-header p-0">
            <div class="alert alert-warning w-100" role="alert">
                <div class="d-flex justify-content-between align-items-center">
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    <strong>Search Location</strong>

                    <button class="btn btn-primary" data-bs-toggle="offcanvas"
                        data-bs-target="#userNewLocation">New</button>
                </div>
            </div>
        </div>
        <div class="offcanvas-body">
            @if ($userLocations)
                <form action="{{ route('user.auth.save-user-current-address') }}" method="POST">
                    @csrf
                    <input type="hidden" name="manual_selection" value="1">
                    @foreach ($userLocations as $location)
                        <div class="card mb-2">
                            <label for="address_{{ $location->id }}">
                                <div class="card-body p-2 shadow-sm">
                                    <div class="w-100 d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">
                                                {{ $location->type ?? 'Home' }}
                                                @if (!empty($location->distance))
                                                    @php $d = floatval($location->distance); @endphp

                                                    @if (!empty($location->is_default))
                                                        {{-- Selected (primary) --}}
                                                        @if ($d >= 1)
                                                            <small class="badge bg-primary">{{ round($d, 2) }} km</small>
                                                        @else
                                                            <small class="badge bg-primary">{{ round($d * 1000) }} mtr</small>
                                                        @endif
                                                    @else
                                                        {{-- Not selected: success if under 200m, otherwise secondary --}}
                                                        @if ($d < 0.2)
                                                            <small class="badge bg-success">{{ round($d * 1000) }} mtr</small>
                                                        @else
                                                            @if ($d >= 1)
                                                                <small class="badge bg-secondary">{{ round($d, 2) }} km</small>
                                                            @else
                                                                <small class="badge bg-secondary">{{ round($d * 1000) }} mtr</small>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                            </h5>
                                            <p class="card-text">{{ $location->address }}</p>
                                        </div>
                                        <div class="icheck-material-teal">
                                            <input type="radio" name="address_id" value="{{ $location->id }}"
                                                @if ($location->is_default) checked @endif class="p-2"
                                                id="address_{{ $location->id }}" class=""
                                                onchange="this.form.submit()">
                                                <label for="address_{{ $location->id }}"></label>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </form>
            @else
                <div class="text-center">
                    <img src="{{ asset('assets/user/img/location.png') }}" alt="No Address" class="img-fluid"
                        style="max-width: 150px;">
                    <h5 class="mt-3">No saved addresses</h5>
                    <p class="text-muted">Please add a new address to continue.</p>
                    <button class="btn btn-primary" data-bs-toggle="offcanvas"
                        data-bs-target="#userNewLocation">Add New Address</button>
                </div>
            @endif
        </div>
    </div>

    {{-- New Location --}}
    <div class="offcanvas offcanvas-bottom" id="userNewLocation" tabindex="-1" aria-labelledby="userMapLabel"
        style="height: 100vh">
        <div class="offcanvas-body p-0">
            <button type="button" class="btn position-absolute z-3  btn-primary rounded-4 rounded-start-0 mt-2 shadow"
                id="openSavedLocation"><i class="feather-arrow-left fs-3"></i></button>
            <button type="button" class="btn-close position-absolute z-3 top-0 end-0 p-3" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            <button type="button"
                class="btn position-absolute z-3  btn-primary rounded-4 rounded-end-0 mt-2 shadow bottom-50 end-0"
                id="chooseCurrentLocation"><i class="feather-map-pin fs-3"></i><span class="fs-5">Current
                    Location</span></button>

            <div id="map-canvas" style=" width:100%;height: 50vh"></div>
            <div class="p-2">
                <form action="{{ route('user.auth.save-user-address') }}" method="POST" id="save-new-address-form">
                    @csrf
                    <input type="hidden" name="latitude" id="latitude" value="">
                    <input type="hidden" name="longitude" id="longitude" value="">
                    <div class="mb-3">
                        <label for="" class="form-label mb-0">Address</label>
                        <input type="text" class="form-control form-control-lg" name="address" id="search-input"
                            aria-describedby="helpId" placeholder="Address" />
                    </div>
                    <div class="mb-3 location_landmark">
                        <label for="" class="form-label mb-0">Landmark</label>
                        <textarea name="landmark" class="form-control form-control-lg" placeholder="Ladmark"></textarea>
                    </div>
                    <div class="mb-3 location_phone">
                        <label for="" class="form-label mb-0">Phone</label>
                        <input type="number" class="form-control form-control-lg" name="phone"
                            placeholder="Eg: 9155289998" />
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label mb-0">Tag</label>
                        <input type="text" class="form-control form-control-lg" name="type"
                            placeholder="Eg: Home, Office" />
                    </div>
                    <div class="mb-3">
                        <button type="submit" id="save-address-btn" class="btn btn-primary w-100" disabled>Save Address</button>
                        <small id="save-address-hint" class="text-muted text-center d-block mt-1">Fetching current location...</small>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('user-views.restaurant.layouts.footer')


    <script type="text/javascript" src="{{ asset('assets/user/vendor/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/user/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/user/vendor/slick/slick/slick.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/user/vendor/sidebar/hc-offcanvas-nav.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/user/js/osahan.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"></script>
    <script src="{{ asset('assets/vendor/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert2/sweetalert2@11.js') }}"></script>
    <!-- Google Maps JS: keep deferred; corrected 'loading' param (previously typo 'oading') -->
    <script defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&loading=async&libraries=geometry,places">
    </script>
    <script src="{{ asset('assets/js/Helpers/mapHelper.js') }}"></script>
    <script src="{{ asset('firebase/index.js') }}" type="module"></script>
    <script src="{{ asset('assets/js/Helpers/custom.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/darkmode-js"></script> --}}
    {{-- <script>
        // Initialize Darkmode.js
        const darkmode = new Darkmode({
            mixColor: "#fff",
            backgroundColor: "#121212",
            saveInCookies: true, // Remember user preference
            label: "🌓"
        });

        // Custom button event listener
        document.getElementById("darkModeToggle").addEventListener("click", function() {
            darkmode.toggle();
        });

        // Apply dark mode if previously enabled
        if (darkmode.isActivated()) {
            document.body.classList.add("darkmode--activated");
        }
    </script> --}}


    <div class="modal fade" id="updateLocationModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="updateLocationModalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content rounded-top-4" style="border-top-left-radius:16px;border-top-right-radius:16px;">
                <div class="modal-body py-4 text-center">
                    <div class="mb-3">
                        <div style="width:72px;height:72px;margin:0 auto;border-radius:18px;background:linear-gradient(135deg,#ff810a33,#ff810a);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-map-pin" style="color:white;font-size:30px"></i>
                        </div>
                    </div>

                    <h5 class="mb-1 fw-bold">Location changed</h5>
                    <p class="mb-3 text-muted small px-3">We detected a different location. Update your delivery location to see restaurants and services near you.</p>

                    <div class="d-grid gap-2 px-3">
                        <button id="updateLocationConfirm" type="button" class="btn btn-primary btn-lg rounded-pill">
                            Update location
                        </button>
                        <button id="updateLocationIgnore" type="button" class="btn btn-outline-secondary btn-lg rounded-pill">
                            Keep current
                        </button>
                    </div>

                    <button type="button" class="btn btn-link text-muted small mt-3" data-bs-dismiss="modal">Remind me later</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        feather.replace(); // This is crucial for Feather Icons to work
    </script>

    @include('inc.toastr')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('h4.truncate').forEach(element => {
                const fullText = element.getAttribute('data-text');
                if (fullText.length > 10) {
                    element.textContent = fullText.substring(0, 7) + '...';
                }
            });
        });
    </script>
    <script>
        const userSavedLocation = new bootstrap.Offcanvas('#userSavedLocation')
        const userNewLocation = new bootstrap.Offcanvas('#userNewLocation')

        // Fast Map Initialization Optimization
        document.querySelector('#userNewLocation').addEventListener('shown.bs.offcanvas', event => {
            optimizedInitMap();
        });

        document.querySelector('#openSavedLocation').addEventListener('click', () => {
            if( userNewLocation ) userNewLocation.hide();
            if( userSavedLocation ) userSavedLocation.show();
        });


    </script>

    <script>
        // Location Management::START
        // update current position to cookie every 5 minutes [without time interval, just store another cookie for time management]
        const isManualSelection = {{ $isManualSelection ? 'true' : 'false' }};
        // const isLocationUpdated = {{ $isLocationUpdated ? 'true' : 'false' }};
        const currentPageRoute = "{{ Route::currentRouteName() }}";

        // const isLocationAlertSowable = {{ !$isManualSelection && !$isLocationUpdated ? 'true' : 'false' }};
        const isLocationAlertSowable = {{ !$isManualSelection ? 'true' : 'false' }};

        var currentLat = null;
        var currentLng = null;
        const serverCurrentLocation = @json($uloc ?? null);
        function updateLocationCookie() {
            console.log("Updating location cookie...");

            navigator.geolocation.getCurrentPosition((position) => {
                const currentLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                currentLat = position.coords.latitude;
                currentLng = position.coords.longitude;
                // store to cookie
                setCookie('user_location', JSON.stringify(currentLocation), 15, 'minutes');

                // if location is changed more than 200m, show alert
                if (serverCurrentLocation && isLocationAlertSowable) {
                    const distance = calculateDistance(serverCurrentLocation.lat,serverCurrentLocation.lng,currentLocation.lat,currentLocation.lng);
                    console.log("Distance from server location: " + distance + " meters");
                    if (distance > 200) {
                        showLocationUpdateAlert();
                    }
                }
            }, showError);
        }

        // function to show alert for location update
        const updateLocationModal = new bootstrap.Modal(document.getElementById('updateLocationModal'));
        function showLocationUpdateAlert() {
            if (updateLocationModal) updateLocationModal.show();
        }
        function hideLocationUpdateAlert() {
            if (updateLocationModal) updateLocationModal.hide();
        }

        // "Update location" - get current position and store (reuses existing function available in page)
        document.getElementById('updateLocationConfirm').addEventListener('click', function () {
            hideLocationUpdateAlert();

            const userSavedLocationOffcanvas = new bootstrap.Offcanvas(document.getElementById('userSavedLocation'));
            if(userSavedLocationOffcanvas) userSavedLocationOffcanvas.show();
        });

        // "Keep current" - mark manual selection so user isn't repeatedly prompted
        document.getElementById('updateLocationIgnore').addEventListener('click', function () {
            setCookie('user_location_manual_selection', '1', 30, 'minutes');
            hideLocationUpdateAlert();
        });

        // // Ensure modal appears like a mobile bottom sheet on small screens
        // window.addEventListener('resize', function () {
        //     if (window.innerWidth <= 576) {
        //         updateLocationModal.querySelector('.modal-dialog').style.maxWidth = '100%';
        //         updateLocationModal.querySelector('.modal-dialog').style.left = '0';
        //     } else {
        //         updateLocationModal.querySelector('.modal-dialog').style.maxWidth = '420px';
        //     }
        // });

        document.addEventListener('DOMContentLoaded', function() {
            updateLocationCookie();
            // setInterval(updateLocationCookie, 5 * 60 * 1000); // Update every 5 minutes
        });

        // Location Management::END

        /***********************************************************
         * Optimized Map Initialization
         * Goals:
         *  - Show map immediately using cached or server location (no geolocation wait)
         *  - Fetch geolocation asynchronously & update marker if significantly different
         *  - Debounce reverse-geocoding on marker drag
         *  - Avoid re-loading Google script & double initialization
         ***********************************************************/
        let mapInitInProgress = false;
        let mapReady = false;
        let geocodeDebounceTimer = null;

        // Local (scoped) debounce renamed to avoid collision with any global debounce implementation
        function mapDebounce(fn, delay = 400) {
            return function(...args) {
                clearTimeout(geocodeDebounceTimer);
                geocodeDebounceTimer = setTimeout(() => fn.apply(this, args), delay);
            }
        }

        function getCachedLocation() {
            // Priority: localStorage customLocation (not expired) -> cookie user_location -> serverCurrentLocation -> fallback (0,0 avoided -> example coords)
            try {
                const cookieLoc = getCookie('user_location');
                if (cookieLoc) {
                    const parsed = JSON.parse(cookieLoc);
                    if (parsed.lat && parsed.lng) return { lat: parseFloat(parsed.lat), lng: parseFloat(parsed.lng) };
                }
            } catch (e) {}
            if (serverCurrentLocation && serverCurrentLocation.lat && serverCurrentLocation.lng) {
                return { lat: serverCurrentLocation.lat, lng: serverCurrentLocation.lng };
            }
            return { lat: 25.5941, lng: 85.1376 }; // Patna, India as fallback
        }

        function ensureGoogleReady(retry = 0) {
            return new Promise((resolve, reject) => {
                if (window.google && window.google.maps) return resolve();
                if (retry > 50) return reject(new Error('Google Maps failed to load'));
                setTimeout(() => ensureGoogleReady(retry + 1).then(resolve).catch(reject), 100);
            });
        }

        function optimizedInitMap() {
            if (mapReady || mapInitInProgress) return;
            mapInitInProgress = true;

            // Show temporary loading state
            const mapCanvas = document.getElementById('map-canvas');
            if (mapCanvas && !document.getElementById('map-loading')) {
                const loader = document.createElement('div');
                loader.id = 'map-loading';
                loader.style.position = 'absolute';
                loader.style.top = '50%';
                loader.style.left = '50%';
                loader.style.transform = 'translate(-50%, -50%)';
                loader.style.background = 'rgba(255,255,255,0.9)';
                loader.style.padding = '6px 14px';
                loader.style.borderRadius = '8px';
                loader.style.fontSize = '14px';
                loader.style.boxShadow = '0 2px 6px rgba(0,0,0,0.15)';
                loader.innerText = 'Loading map...';
                mapCanvas.style.position = 'relative';
                mapCanvas.appendChild(loader);
            }

            ensureGoogleReady().then(() => {
                const initialLocation = getCachedLocation();
                // Create map instantly with cached/fallback location
                myMap.CreateMap(initialLocation, {
                    selector: '#map-canvas',
                    marker: {
                        location: initialLocation,
                        img: "{{ asset('assets/user/img/icons/marker-icon.png') }}",
                        draggable: true
                    }
                });

                if (document.getElementById('map-loading')) {
                    document.getElementById('map-loading').remove();
                }

                // Only initial reverse geocode if we had a precise stored location (custom or cookie) else delay until geolocation
                if (initialLocation) {
                    reverseGeocodePosition(new google.maps.LatLng(initialLocation.lat, initialLocation.lng));
                }

                // Debounced dragend listener
                google.maps.event.addListener(myMap.marker, 'dragend', mapDebounce(function() {
                    reverseGeocodePosition(myMap.marker.getPosition());
                }, 500));

                // Autocomplete binding
                const input = document.getElementById('search-input');
                if (input) {
                    const autocomplete = new google.maps.places.Autocomplete(input);
                    autocomplete.addListener('place_changed', function() {
                        const place = autocomplete.getPlace();
                        if (!place.geometry) return;
                        const loc = place.geometry.location;
                        myMap.map.setCenter(loc);
                        myMap.marker.setPosition(loc);
                        reverseGeocodePosition(loc);
                    });
                }

                // Choose current location button -> re-fetch geolocation
                const chooseBtn = document.getElementById('chooseCurrentLocation');
                if (chooseBtn) {
                    chooseBtn.addEventListener('click', () => {
                        fetchGeolocation(true, { userInitiated: true, highAccuracy: true });
                    });
                    if (currentLat === null || currentLng === null) {
                        chooseBtn.click(); // Auto-click to fetch if we don't have any location yet
                    } else {
                        setLocateButtonState(false);
                    }
                }

                // Fetch geolocation asynchronously without blocking initial map
                fetchGeolocation(false);
                mapReady = true;
            }).catch(err => {
                console.error('Map init error:', err);
                if (document.getElementById('map-loading')) {
                    document.getElementById('map-loading').innerText = 'Failed to load map';
                }
            });
        }

        let geoRequestInFlight = false;
        function setLocateButtonState(loading) {
            const btn = document.getElementById('chooseCurrentLocation');
            if (!btn) return;
            if (loading) {
                btn.dataset.originalInnerHtml = btn.dataset.originalInnerHtml || btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="feather-map-pin fs-3"></i><span class="fs-6 ms-1">Locating...</span>';
                const saveBtn = document.getElementById('save-address-btn');
                if (saveBtn) saveBtn.disabled = true;

                const hint = document.getElementById('save-address-hint');
                if (hint) hint.innerText = 'Fetching current location...';
            } else {
                if (btn.dataset.originalInnerHtml) btn.innerHTML = btn.dataset.originalInnerHtml;
                btn.disabled = false;

                const saveBtn = document.getElementById('save-address-btn');
                if (saveBtn) saveBtn.disabled = false;
                const hint = document.getElementById('save-address-hint');
                if (hint) hint.innerText = '';
            }
        }
        function fetchGeolocation(forceCenter = false, opts = {}) {
            if (!navigator.geolocation) {
                toastr.warning('Geolocation not supported on this device');
                return;
            }
            if (geoRequestInFlight) return; // prevent duplicate requests
            geoRequestInFlight = true;
            if (opts.userInitiated) setLocateButtonState(true);

            const options = { enableHighAccuracy: !!opts.highAccuracy, timeout: 24000, maximumAge: 120000 };
            navigator.geolocation.getCurrentPosition(pos => {
                geoRequestInFlight = false;
                if (opts.userInitiated) setLocateButtonState(false);
                const loc = { lat: pos.coords.latitude, lng: pos.coords.longitude };
                const currentMarkerPos = myMap && myMap.marker ? myMap.marker.getPosition() : null;
                const needUpdate = forceCenter || !currentMarkerPos || calculateDistance(
                    currentMarkerPos.lat(), currentMarkerPos.lng(), loc.lat, loc.lng
                ) > 20; // tighter threshold when user explicitly requests
                if (needUpdate && myMap && myMap.map) {
                    const latLng = new google.maps.LatLng(loc.lat, loc.lng);
                    myMap.map.setCenter(latLng);
                    myMap.marker.setPosition(latLng);
                    reverseGeocodePosition(latLng);
                } else if (opts.userInitiated) {
                    toastr.info('Already near your current location');
                }
            }, err => {
                geoRequestInFlight = false;
                if (opts.userInitiated) setLocateButtonState(false);
                let message = 'Unable to retrieve location';
                if (err.code === err.PERMISSION_DENIED) message = 'Location permission denied';
                else if (err.code === err.POSITION_UNAVAILABLE) message = 'Location unavailable';
                else if (err.code === err.TIMEOUT) message = 'Location request timed out';
                console.debug('Geolocation skipped/failed', err);
                console.error(message);
            }, options);
        }

        function reverseGeocodePosition(position) {
            getAddress(position); // wrapper call retained for backwards compatibility
        }
        // Legacy initMap reference kept if other scripts call it
        function initMap() { optimizedInitMap(); }
        // window.addEventListener('load', initMap);

        function getAddress(positon, returnOnly = false) {

            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                location: positon
            }, function(results, status) {
                if (status === "OK") {

                    const formatted_address = results[0].formatted_address || null;

                    if (returnOnly) {
                        return formatted_address;
                    }

                    if (document.querySelector('[data-user-address]')) {
                        document.querySelector('[data-user-address]').textContent = formatted_address;
                    }
                    if (document.querySelector('[data-address="1"]')) {
                        document.querySelector('[data-address="1"]').textContent = formatted_address;
                    }
                    if (document.querySelector('[data-address="2"]')) {
                        document.querySelector('[data-address="2"]').textContent = formatted_address;
                    }

                    document.querySelector('#search-input').value = formatted_address;
                    // console.log(myMap);
                    if (!myMap.map) {
                        const _location = {
                            lat: positon.lat(),
                            lng: positon.lng()
                        };
                        myMap.CreateMap(_location, {
                            selector: "#map-canvas",
                            marker: {
                                location: _location,
                                img: "{{ asset('assets/user/img/icons/marker-icon.png') }}",
                                draggable: true
                            }
                        });
                    }

                    myMap.map.setCenter(positon);
                    myMap.marker.setPosition(positon);
                    // Update hidden inputs for form submission
                    if (document.getElementById('latitude')) document.getElementById('latitude').value = positon.lat();
                    if (document.getElementById('longitude')) document.getElementById('longitude').value = positon.lng();
                    // tryEnableSaveAddress();
                    var expirationDate = new Date();
                    expirationDate.setTime(expirationDate.getTime() + (15 * 60 * 1000));

                    var data = {
                        lat: positon.lat(),
                        lng: positon.lng(),
                        expires: expirationDate.getTime()
                    };

                    localStorage.setItem('customLocation', JSON.stringify(data));
                    myMap.setElementsPosition(data)

                    // Now you can extract address components from results[0] as needed
                } else {
                    console.error('Geocode was not successful for the following reason: ' + status);
                }
            });
        }

        function geocodeAddress() {
            var geocoder = new google.maps.Geocoder();
            var address = document.getElementById('search-input').value;

            geocoder.geocode({
                'address': address
            }, function(results, status) {
                if (status === 'OK') {
                    var formattedAddress = results[0].formatted_address;
                    const location = results[0].geometry.location;
                    myMap.map.setCenter(location);
                    myMap.marker.setPosition(location);
                    getAddress(myMap.marker.getPosition());
                    // tryEnableSaveAddress();
                } else {
                    console.error('Geocode was not successful for the following reason: ' + status);
                }
            });
        }


        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    console.error("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    console.error("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    console.error("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    console.error("An unknown error occurred.");
                    break;
            }
        };

        // // Enable Save Address button when we have both coordinates & non-empty address input
        // function tryEnableSaveAddress() {
        //     const lat = document.getElementById('latitude')?.value;
        //     const lng = document.getElementById('longitude')?.value;
        //     const addressVal = document.getElementById('search-input')?.value?.trim();
        //     const btn = document.getElementById('save-address-btn');
        //     const hint = document.getElementById('save-address-hint');
        //     if (btn) {
        //         if (lat && lng && addressVal) {
        //             btn.disabled = false;
        //             if (hint) { hint.textContent = 'Ready to save.'; hint.classList.remove('text-danger'); }
        //         } else {
        //             btn.disabled = true;
        //             if (hint) { hint.textContent = 'Waiting for location...'; }
        //         }
        //     }
        // }
        // Observe manual edits in address field
        // document.addEventListener('input', function(e){
        //     if (e.target && e.target.id === 'search-input') {
        //         tryEnableSaveAddress();
        //     }
        // });
    </script>
    <script>
        navigator.serviceWorker.addEventListener('message', function(event) {
            console.log('CLIENT:: Received message from service worker:', event);
            if (event.data?.notificationData?.data?.audio_link) {
                audio.src = event.data?.notificationData?.data?.audio_link;
            }

            let title = event.data.notificationData.notification.title || event.data.notificationData.data
                .message || '';
            if (event.data.action === 'playAudio') {
                console.log('CLIENT:: Playing audio');
                audio.play().catch(function(error) {
                    console.error('CLIENT:: Error playing audio:', error);
                });

                if (event.data?.notificationData?.data?.order_status == '_pending') {

                } else {
                    toastr.options = {
                            "positionClass": "toast-bottom-right",
                            "timeOut": "5000"
                        },
                        toastr.info(title);
                }
            } else {
                toastr.options = {
                        "positionClass": "toast-bottom-right",
                        "timeOut": "5000"
                    },
                    toastr.info(title);
            }
        });
    </script>

    @stack('javascript')

</body>

</html>
