@extends('user-views.restaurant.layouts.main')

@push('css')
    <style>
        .offcanvas-bottom {
            height: 70vh !important;
            /* Adjust the height as needed */
            max-height: 70vh !important;
            /* Ensure the maximum height is set */
        }

        .overflow-x-scroll {
            display: flex;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            white-space: nowrap;
        }

        .card {
            flex: 0 0 auto;
            margin-right: .5rem;
        }

        .card .card-text {
            font-size: 14px;
        }

        .overflow-x-scroll::-webkit-scrollbar {
            display: none;
        }

        .overflow-x-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .osahan-card-body .card input[type="radio"] {
            display: none;
        }

        .highlight {
            background-color: #ff810a26 !important;
            border-color: #ff810a !important;
        }

        /* Custom tooltip styling */
        .custom-tooltip .tooltip-inner {
            background-color: rgba(255, 255, 255, 1) !important;
            /* Opaque white background */
            color: grey !important;
            /* Text color */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            /* Shadow */
        }

        .custom-tooltip .tooltip-arrow::before {
            border-top-color: #ff810a !important;
            /* Arrow color for top placement */
        }

        .product-count-number-input:focus {
            border: none;
            outline: none;
            box-shadow: none;
        }

        input::placeholder {
            font-size: 14px;
            /* Adjust the size as needed */
            color: #888;
            /* Optionally, change the color */
        }

        #tipInputContainer input {
            /* display: block; */
            width: 50%;
        }

        .swal2-modal{
            border-radius: 20px !important
        }
        @media (max-width: 576px) {
            .checkout-footer-area {
                padding-bottom: 74px;
            }
            .swal2-modal{
                width: 360px !important;
            }
        }
    </style>
@endpush

@section('containt')
    {{-- @dd(Session::get('cart')) --}}
    <!-- Top Bar -->
    <div class="d-lg-none d-block m-1">
        <div class="bg-primary d-flex w-100 rounded-4">
            <h4 class="text-white fw-bolder fs-4 me-auto mb-0 py-3 px-4" onclick="window.history.back()" style="border-right: 1px solid white;">
                <i class="fas fa-arrow-left"></i>
            </h4>
            <h4 class="fw-bold m-0 text-white w-100 align-self-baseline text-center p-3 ps-0"><i class="fas fa-shopping-bag me-2"></i>Check Out</h4>
        </div>
    </div>

    <div class="container">
        <div class="row m-0 justify-content-center">
            <div class="col-md-8 px-0 border-top checkout-footer-area">







                <!-- Ordering for someone -->
                <div class="p-0 mt-4 w-100 rounded-4" style="background: #ff810a36;    border: 1px solid #ff810a;">
                    <div class="d-flex justify-content-between p-3" >
                        <div>
                            <h5 class="fw-bolder mb-0" style="color:#ff810a ;">You Are Out of Delivery Range of {{ $restaurant->name }} Restaurant !!</h5>
                            <p>Please Change The Delivery Address</p>
                        </div>

                    </div>

                </div>
                <!-- Address Section -->
                @php
                    $userLocations = [];
                    if (auth('customer')->check()) {
                        $userLocations = auth('customer')->user()->customerAddress()->orderByDesc('is_default')->orderByDesc('id')->get();
                        $default_address = [
                            'type' => $userLocations[0]?->type ?? 'Select Address',
                            'address' => $userLocations[0]?->address ?? '',
                        ];
                    }else{
                        $userLocation = Helpers::getGuestSession('guest_location');
                        if($userLocation){
                            $default_address['type'] = $userLocation['type'];
                            $default_address['address'] = $userLocation['address'];
                        }
                    }

                @endphp
                <div class="p-3 mt-4 bg-light border-bottom rounded-top-4 w-100 d-flex justify-content-between">

                    <h5 class="fw-bolder mb-0">Address</h5>
                    <p class="mb-0 text-warning" data-bs-toggle="offcanvas" data-bs-target="#userSavedLocation">Change</p>
                </div>
                <div class="osahan-card-body p-3 rounded-bottom-4 bg-white" style="border-bottom: 1px solid #80808063;margin-bottom:35px;">
                    <p class="mb-0 location-bar" style="font-size: 15px;">{{ $default_address['address'] ?? null }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('javascript')

    <script>
        // Handle back/forward navigation
        window.onpopstate = function(event) {
            if (event.state && event.state.page === "home") {
                console.log("Navigated to Home");
                if (!userHasAccess("home")) {
                    alert("You don't have access to this page.");
                    // Redirect to a fallback page
                    history.pushState({
                        page: "allowed"
                    }, "Allowed Page", "/allowed");
                }
            } else if (event.state) {
                console.log(`Navigated to ${event.state.page}`);
            } else {
                console.log("No state found. Staying on the current page.");
            }
        };

        // Function to check if the user has access
        function userHasAccess(page) {
            // Example logic: deny access to the "restricted" page
            return page !== "restricted";
        }

        // Initialize state on page load
        window.addEventListener("DOMContentLoaded", () => {
            if (!history.state) {
                // Only set the initial state if no state exists
                history.replaceState({
                    page: "home"
                }, "Current Page", location.href);
            }
        });

        // Ensure the state persists on refresh
        window.onbeforeunload = function() {
            history.replaceState({
                page: "current"
            }, "Current Page", location.href);
        };
    </script>
@endpush
