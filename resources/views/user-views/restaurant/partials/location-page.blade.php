@php
    $redis = new \App\CentralLogics\Redis\RedisHelper();

    $currentPosition = [];
    if (auth("customer")->check()) {
        $user = auth("customer")->user();
        $userLocations = $user->customerAddress()->orderByDesc("is_default")->orderByDesc("id")->get();
    }
@endphp

@extends("user-views.restaurant.layouts.main")
@section("containt")
    <style>
        .auth-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            min-width: 100%;
            min-height: 100vh;
            background: linear-gradient(135deg, #ff810a 0%, #ff6b35 100%);
            z-index: 111;
        }

        .osahan-menu-fotter {
            visibility: hidden;
        }

        .location-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .location-icon {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .location-icon i {
            font-size: 3rem;
            color: white;
        }

        .location-btn {
            background: white;
            color: #ff810a;
            border: none;
            padding: 16px 32px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }

        .location-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
            color: #e6730a;
        }

        .location-btn:active {
            transform: translateY(0);
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 129, 10, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .loading-content {
            text-align: center;
            color: white;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .floating-elements::before,
        .floating-elements::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .floating-elements::before {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-elements::after {
            bottom: 10%;
            right: 10%;
            animation-delay: 3s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .nearby-addresses {
            margin-top: 2rem;
            max-height: 300px;
            overflow-y: auto;
        }

        .address-item {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .address-item:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .address-item.selected {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .distance-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        @media (max-width: 576px) {
            .location-card {
                margin: 1rem;
                padding: 1.5rem;
            }
            
            .location-icon {
                width: 100px;
                height: 100px;
            }
            
            .location-icon i {
                font-size: 2.5rem;
            }
        }
    </style>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            {{-- <div class="spinner"></div> --}}
            <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5>🔍 Finding nearby addresses...</h5>
            <p>Please wait while we locate restaurants near you</p>
        </div>
    </div>

    <div class="auth-wrapper">
        <div class="floating-elements"></div>
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-12 text-center">
                    <div class="location-card mx-auto">
                        <div class="location-icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        
                        <h4 class="fw-bold text-white mb-3">📍 Find Restaurants Near You</h4>
                        <p class="text-white-75 mb-4">
                            Enable location access to discover the best restaurants around you,
                            enjoy personalized recommendations, and get faster delivery to your doorstep.
                        </p>
                        
                        <div id="nearbyAddresses" class="nearby-addresses" style="display: none;">
                            <h6 class="text-white mb-3">🎯 Found nearby addresses:</h6>
                            <div id="addressList"></div>
                            <button class="location-btn w-100 mt-3" onclick="useSelectedAddress()">
                                <i class="fa-solid fa-check me-2"></i>Use Selected Address
                            </button>
                        </div>
                        
                        <div id="locationActions">
                            <button class="location-btn w-100" onclick="getCurrentLocationAndCheck()">
                                <i class="fa-solid fa-crosshairs me-2"></i>Detect My Location
                            </button>
                            
                            <button class="btn btn-outline-light w-100 mt-3" data-bs-toggle="offcanvas" data-bs-target="#userNewLocation">
                                <i class="fa-solid fa-plus me-2"></i>Add New Address
                            </button>
                        </div>
                        
                        <small class="text-white-50 d-block mt-4">
                            🔒 Your location will only be used to improve your food ordering experience.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("javascript")
    <script>
        // User addresses from Laravel
        const userAddresses = @json($userLocations ?? []);
        let currentLocation = null;
        let selectedAddressId = null;

        // Find nearby addresses within 200 meters
        function findNearbyAddresses(latitude, longitude) {
            const distanceLimit = 6000; // 200 meters
            const nearbyAddresses = [];

            userAddresses.forEach(address => {
                if (address.latitude && address.longitude) {
                    const distance = calculateDistance(
                        latitude, longitude,
                        parseFloat(address.latitude), 
                        parseFloat(address.longitude)
                    );
                    
                    if (distance <= distanceLimit) {
                        nearbyAddresses.push({
                            ...address,
                            distance: distance
                        });
                    }
                }
            });

            // Sort by distance
            const sortedAddresses = nearbyAddresses.sort((a, b) => a.distance - b.distance);
            console.log("Nearby addresses found:", sortedAddresses);
            return sortedAddresses;

            
        }

        // Display nearby addresses
        function displayNearbyAddresses(addresses) {
            const addressList = document.getElementById('addressList');
            const nearbySection = document.getElementById('nearbyAddresses');
            const locationActions = document.getElementById('locationActions');
            
            if (addresses.length === 0) {
                // No nearby addresses found, show add new address option
                nearbySection.style.display = 'none';
                locationActions.style.display = 'block';
                showOffcanvas();
                return;
            }

            let html = '';
            addresses.forEach((address, index) => {
                const isSelected = index === 0; // Auto-select the first (nearest) one
                if (isSelected) selectedAddressId = address.id;
                
                html += `
                    <div class="address-item ${isSelected ? 'selected' : ''}" 
                         onclick="selectAddress(${address.id}, this)">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa-solid fa-${getAddressIcon(address.type)} me-2 text-white"></i>
                                    <strong class="text-white">${address.type || 'Address'}</strong>
                                    <span class="distance-badge ms-auto">${formatDistance(address.distance)}</span>
                                </div>
                                <p class="text-white-75 mb-1 small">${address.address}</p>
                                ${address.landmark ? `<p class="text-white-50 mb-0 small"><i class="fa-solid fa-location-dot me-1"></i>${address.landmark}</p>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
            
            addressList.innerHTML = html;
            nearbySection.style.display = 'block';
            locationActions.style.display = 'none';
            
            // Auto-select the nearest address after a short delay
            setTimeout(() => {
                if (addresses.length > 0) {
                    useSelectedAddress();
                }
            }, 100);
            // }, 2000);
        }

        // Get address type icon
        function getAddressIcon(type) {
            switch(type?.toLowerCase()) {
                case 'home': return 'house';
                case 'work': case 'office': return 'briefcase';
                case 'hotel': return 'bed';
                default: return 'location-dot';
            }
        }

        // Select an address
        function selectAddress(addressId, element) {
            // Remove selected class from all items
            document.querySelectorAll('.address-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Add selected class to clicked item
            element.classList.add('selected');
            selectedAddressId = addressId;
        }

        // Use selected address
        function useSelectedAddress() {
            if (!selectedAddressId) {
                showError('Please select an address first.');
                return;
            }

            showLoading('Setting your location...');

            // Create form data
            const formData = new FormData();
            formData.append('address_id', selectedAddressId);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Make API call
            fetch('{{ route("user.auth.save-user-current-address") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                hideLoading();
                if (response.ok) {
                    showSuccess('Location set successfully! Redirecting...');
                    setTimeout(() => {
                        window.location.reload();
                    }, 100);
                } else {
                    throw new Error('Failed to save address');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                showError('Failed to set location. Please try again.');
            });
        }

        // Get current location and check for nearby addresses
        function getCurrentLocationAndCheck() {
            if (!navigator.geolocation) {
                showError('Geolocation is not supported by this browser.');
                return;
            }

            showLoading('Getting your location...');

            const options = {
                // enableHighAccuracy: true,
                // timeout: 10000,
                // maximumAge: 60000
            };

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    currentLocation = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    };

                    hideLoading();

                    // Find nearby addresses
                    const nearbyAddresses = findNearbyAddresses(
                        currentLocation.latitude, 
                        currentLocation.longitude
                    );

                    displayNearbyAddresses(nearbyAddresses);
                },
                function(error) {
                    hideLoading();
                    let errorMessage;
                    
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = "Location access denied. Please enable location services and refresh the page.";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = "Location information is unavailable. Please try again.";
                            break;
                        case error.TIMEOUT:
                            errorMessage = "Location request timed out. Please try again.";
                            break;
                        default:
                            errorMessage = "An unknown error occurred while retrieving location.";
                            break;
                    }
                    
                    showError(errorMessage);
                    showOffcanvas();
                },
                options
            );
        }

        // Show loading overlay
        function showLoading(message = 'Loading...') {
            const overlay = document.getElementById('loadingOverlay');
            const text = overlay.querySelector('h5');
            if (text) text.textContent = message;
            overlay.style.display = 'flex';
        }

        // Hide loading overlay
        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        // Show success message
        function showSuccess(message) {
            // You can implement toast notifications here
            console.log('Success:', message);
        }

        // Show error message
        function showError(message) {
            console.error('Error:', message);
            toastr.warning(message);
        }

        // Show offcanvas for new address
        function showOffcanvas() {
            const locationOffcanvas = document.getElementById('userNewLocation');
            if (locationOffcanvas) {
                const bsOffcanvas = new bootstrap.Offcanvas(locationOffcanvas);
                bsOffcanvas.show();
            }

            console.log("No nearby addresses found or location access denied, showing add address form.");
            
        }

        // Set current position in form fields (for offcanvas)
        function setCurrentPosition(position) {
            const latField = document.getElementById('latitude');
            const lngField = document.getElementById('longitude');
            
            if (latField) latField.value = position.coords.latitude;
            if (lngField) lngField.value = position.coords.longitude;
        }

        // Auto-detect location on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-start location detection if user has addresses
            if (userAddresses && userAddresses.length > 0) {
                getCurrentLocationAndCheck();
            } else {
                // No saved addresses, show add address form
                showOffcanvas();
            }

            // Also set up geolocation for the offcanvas form
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(setCurrentPosition, function(error) {
                    console.log('Could not get location for form:', error);
                });
            }


            @if(!isset($user))
                const locationLandmark = document.querySelector('.location_landmark');
                const locationPhone = document.querySelector('.location_phone');
                if (locationLandmark) locationLandmark.style.display = 'none';
                if (locationPhone) locationPhone.style.display = 'none';
            @endif
        });
    </script>
@endpush
