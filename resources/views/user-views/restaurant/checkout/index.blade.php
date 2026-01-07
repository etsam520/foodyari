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

        /* Custom tooltip styling - Mobile responsive */
        .custom-tooltip .tooltip-inner {
            background-color: rgba(255, 255, 255, 1) !important;
            /* Opaque white background */
            color: grey !important;
            /* Text color */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            /* Shadow */
            max-width: 250px !important; /* Limit width on mobile */
            font-size: 12px !important;
            text-align: left !important;
            word-wrap: break-word !important;
        }

        .custom-tooltip .tooltip-arrow::before {
            border-top-color: #ff810a !important;
            /* Arrow color for top placement */
        }

        /* Mobile specific tooltip styles */
        @media (max-width: 576px) {
            .custom-tooltip .tooltip-inner {
                max-width: 200px !important;
                font-size: 11px !important;
                padding: 6px 8px !important;
            }

            /* Ensure tooltips stay within viewport */
            .tooltip {
                max-width: 90vw !important;
            }
        }

        /* Ensure tooltip buttons are visible and clickable */
        [data-bs-toggle="tooltip"] {
            cursor: help;
            position: relative;
            z-index: 1;
        }

        /* CSS fallback tooltip - Mobile responsive */
        .info-tooltip {
            position: relative;
            cursor: help;
        }

        .info-tooltip:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: normal;
            max-width: 250px;
            width: max-content;
            text-align: center;
            z-index: 1050;
            pointer-events: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        .info-tooltip:hover::before {
            content: '';
            position: absolute;
            bottom: 115%;
            left: 50%;
            transform: translateX(-50%);
            border: 6px solid transparent;
            border-top-color: rgba(0, 0, 0, 0.9);
            z-index: 1050;
            pointer-events: none;
        }

        /* Mobile-specific CSS tooltip adjustments */
        @media (max-width: 576px) {
            .info-tooltip:hover::after {
                position: fixed;
                bottom: auto;
                left: 10px !important;
                right: 10px !important;
                top: 50%;
                transform: translateY(-50%) !important;
                max-width: none;
                width: auto;
                font-size: 11px;
                padding: 10px 12px;
                text-align: left;
                white-space: normal;
                word-wrap: break-word;
            }

            .info-tooltip:hover::before {
                display: none; /* Hide arrow on mobile for cleaner look */
            }

            /* Alternative: Click to show tooltip on mobile */
            .info-tooltip:active::after {
                content: attr(data-tooltip);
                position: fixed;
                bottom: auto;
                left: 10px !important;
                right: 10px !important;
                top: 50%;
                transform: translateY(-50%) !important;
                background: rgba(0, 0, 0, 0.9);
                color: white;
                padding: 12px 16px;
                border-radius: 8px;
                font-size: 12px;
                text-align: left;
                z-index: 1050;
                pointer-events: none;
                box-shadow: 0 4px 12px rgba(0,0,0,0.4);
            }
        }

        /* Debug styles */
        .tooltip-debug {
            border: 2px solid red !important;
            background: yellow !important;
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

        /* Schedule Order Section Styles */
        #schedule-order-container {
            transition: all 0.3s ease;
        }

        #schedule-form {
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-check-input:checked {
            background-color: #ff810a;
            border-color: #ff810a;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        @media (max-width: 576px) {
            .checkout-footer-area {
                padding-bottom: 74px;
            }
            .swal2-modal{
                width: 360px !important;
            }
                /* Prevent extra space/scroll after confetti on mobile */
                body {
                    overflow-x: hidden !important;
                    overflow-y: auto !important;
                    max-width: 100vw !important;
                    position: relative;
                }
                html {
                    overflow-x: hidden !important;
                    max-width: 100vw !important;
                }
        }

        /* Paper Blaster Effect Styles */
        .paper-piece {
            position: fixed;
            z-index: 10000;
            pointer-events: none;
            transition: all 6s cubic-bezier(0.25, 0.46, 0.45, 0.94); /* Longer transition */
            opacity: 1;
            transform-origin: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            will-change: transform, opacity;
        }

        /* Mobile specific paper piece fixes */
        @media (max-width: 768px) {
            .paper-piece {
                transition: all 5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            }
        }

        .paper-piece.square {
            width: 15px;
            height: 15px;
        }

        .paper-piece.rectangle {
            width: 20px;
            height: 10px;
        }

        .paper-piece.circle {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .paper-piece.triangle {
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-bottom: 14px solid;
            background: transparent !important;
            border-bottom-color: var(--paper-color);
        }

        .paper-piece.hexagon {
            width: 14px;
            height: 8px;
            background: var(--paper-color);
            position: relative;
        }

        .paper-piece.hexagon:before,
        .paper-piece.hexagon:after {
            content: "";
            position: absolute;
            width: 0;
            border-left: 7px solid transparent;
            border-right: 7px solid transparent;
        }

        .paper-piece.hexagon:before {
            bottom: 100%;
            border-bottom: 4px solid var(--paper-color);
        }

        .paper-piece.hexagon:after {
            top: 100%;
            border-top: 4px solid var(--paper-color);
        }

        .paper-piece.diamond {
            width: 12px;
            height: 12px;
            background: var(--paper-color);
            transform: rotate(45deg);
        }

        .paper-piece.glow-paper {
            box-shadow: 0 0 8px var(--paper-color), 0 0 16px var(--paper-color);
            animation: hexagonal-glow 0.5s ease-in-out infinite alternate;
        }

        @keyframes hexagonal-glow {
            0% {
                box-shadow: 0 0 8px var(--paper-color), 0 0 16px var(--paper-color);
            }
            100% {
                box-shadow: 0 0 12px var(--paper-color), 0 0 24px var(--paper-color), 0 0 32px var(--paper-color);
            }
        }

        .paper-piece.exploding {
            transform: translate(calc(var(--final-x) - 50%), calc(var(--final-y) - 50%)) rotate(var(--rotation)) scale(calc(var(--scale) * 0.2));
            opacity: 0;
        }

        /* Enhanced Boom effect for source element */
        .boom-effect {
            animation: enhanced-boom-pulse 1.2s ease-out;
        }

        @keyframes enhanced-boom-pulse {
            0% {
                transform: scale(1) rotate(0deg);
                box-shadow: 0 0 0 0 rgba(255, 129, 10, 0.8);
            }
            15% {
                transform: scale(1.1) rotate(6deg);
                box-shadow: 0 0 0 20px rgba(255, 129, 10, 0.7),
                           0 0 0 40px rgba(255, 193, 7, 0.5),
                           20px 0 0 20px rgba(255, 129, 10, 0.3),
                           -20px 0 0 20px rgba(255, 129, 10, 0.3),
                           10px 17px 0 20px rgba(255, 193, 7, 0.3),
                           -10px 17px 0 20px rgba(255, 193, 7, 0.3),
                           10px -17px 0 20px rgba(255, 193, 7, 0.3),
                           -10px -17px 0 20px rgba(255, 193, 7, 0.3);
            }
            30% {
                transform: scale(1.15) rotate(-6deg);
                box-shadow: 0 0 0 40px rgba(255, 129, 10, 0.5),
                           0 0 0 80px rgba(255, 193, 7, 0.4),
                           0 0 0 120px rgba(40, 167, 69, 0.3),
                           40px 0 0 40px rgba(255, 129, 10, 0.2),
                           -40px 0 0 40px rgba(255, 129, 10, 0.2),
                           20px 35px 0 40px rgba(255, 193, 7, 0.2),
                           -20px 35px 0 40px rgba(255, 193, 7, 0.2),
                           20px -35px 0 40px rgba(255, 193, 7, 0.2),
                           -20px -35px 0 40px rgba(255, 193, 7, 0.2);
            }
            50% {
                transform: scale(1.08) rotate(3deg);
                box-shadow: 0 0 0 60px rgba(255, 129, 10, 0.3),
                           0 0 0 120px rgba(255, 193, 7, 0.2),
                           0 0 0 180px rgba(40, 167, 69, 0.15),
                           0 0 0 240px rgba(220, 53, 69, 0.1),
                           60px 0 0 60px rgba(255, 129, 10, 0.1),
                           -60px 0 0 60px rgba(255, 129, 10, 0.1),
                           30px 52px 0 60px rgba(255, 193, 7, 0.1),
                           -30px 52px 0 60px rgba(255, 193, 7, 0.1),
                           30px -52px 0 60px rgba(255, 193, 7, 0.1),
                           -30px -52px 0 60px rgba(255, 193, 7, 0.1);
            }
            70% {
                transform: scale(1.05) rotate(-2deg);
                box-shadow: 0 0 0 80px rgba(255, 129, 10, 0.15),
                           0 0 0 160px rgba(255, 193, 7, 0.1),
                           0 0 0 240px rgba(40, 167, 69, 0.08);
            }
            100% {
                transform: scale(1) rotate(0deg);
                box-shadow: 0 0 0 100px rgba(255, 129, 10, 0),
                           0 0 0 200px rgba(255, 193, 7, 0),
                           0 0 0 300px rgba(40, 167, 69, 0);
            }
        }

        /* Enhanced modal animation */
        .swal2-modal {
            animation: modalBounceIn 0.5s ease-out;
        }

        @keyframes modalBounceIn {
            0% {
                transform: scale(0.3) rotate(-5deg);
                opacity: 0;
            }
            50% {
                transform: scale(1.05) rotate(2deg);
            }
            70% {
                transform: scale(0.95) rotate(-1deg);
            }
            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
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
                <div class="d-flex item-aligns-center p-3">
                    <div class="restaurant-wrap align-self-center me-3">
                        <img alt="#" class="img-fluid h-100 rounded-circle" src="{{ asset('restaurant/' . $restaurant->logo) }}">
                    </div>
                    <h4 class="fw-bolder align-self-center m-0">{{ Str::ucfirst($restaurant->name) }}</h4>
                </div>
                <!-- Cart Items List -->
                <div class="bg-white p-0 rounded-4" data-view-cart="all">
                    <div class="d-flex py-2 gold-members border-bottom" style="filter: blur(2px);">
                        <img alt="#" src="{{ asset('restaurant/' . $restaurant->logo) }}" class="img-fluid product-img">
                        <div class="w-100 ms-3">
                            <div class="d-flex gap-2 mb-2">
                                <div>
                                    <h6 class="mb-1 fw-bold">Chicken Tikka Sub Chilli Garlic
                                    </h6>
                                    <div class="text-start">
                                        <span class="text-danger mb-0 small"> <strike>₹
                                                4,000.00</strike></span>
                                        <span class="text-success mb-0 fs-6 fw-bolder"> ₹
                                            3,995.00</span>
                                    </div>
                                </div>
                                <div class="ms-auto gold-members">
                                    <div class="d-flex justify-content-end mb-2">
                                        <div class="text-success me-2">Veg</div>
                                        <img alt="#" src="img/veg.png" class="img-fluid food-type mt-1">
                                    </div>
                                    <span class="product-count-number d-flex p-0">
                                        <button type="button" class="btn-sm left dec btn px-2 border-0">
                                            <i class="feather-minus"></i>
                                        </button>
                                        <input class="product-count-number-input w-100 border-0" type="text" value="2">
                                        <button type="button" class="btn-sm right inc btn px-2 border-0">
                                            <i class="feather-plus"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Add more and Cooking Instructions -->
                @if ($userType == 'customer')

                    <div class="bg-white rounded-4 mt-4" >
                        <div class="p-3 m-0 border-bottom w-100 d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                <span class="me-2 fs-6"><i class="fa-solid fa-circle-plus text-warning"></i></span>
                                <div class="mb-0 align-self-center fs-5 text-secondary">
                                    Add More Items
                                </div>
                            </div>
                            <a href="{{ route('user.restaurant.get-restaurant', ['name' => Str::slug($restaurant->name)]) }}" class="px-2 py-1 fs-5 btn btn-link"><i class="feather-chevron-right text-warning"></i></a>
                        </div>
                        <div class="">
                            <div class="p-3 m-0 w-100 d-flex justify-content-between" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                <div class="d-flex align-items-center">
                                    <span class="me-2 fs-6"><i class="fa-solid fa-edit text-warning"></i></span>
                                    <div class="mb-0 align-self-center fs-5 text-secondary">
                                        Cooking Instructions
                                    </div>
                                </div>
                                <button class="px-2 py-1 fs-5 btn btn-link" type="button">
                                    <i class="feather-chevron-right text-warning"></i>
                                </button>
                            </div>
                            <div class="collapse" id="collapseExample">
                                <div class="gold-members w-100">
                                    <form action="javascrip:void(0)" id="cooking-instruction" data-instruction="{{Helpers::getOrderSessions(auth('customer')->user()->id, "cooking_instruction")}}">
                                        @csrf
                                        <div class="row px-4 py-3">
                                            <div class="col-12 p-0">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="message"><i class="feather-message-square"></i></span>
                                                    <textarea placeholder="Any Special Requirement?" aria-label="With textarea" class="form-control fs-6" name="instruction"></textarea>
                                                </div>
                                            </div>
                                            <div class="text-center"><button type="submit" class="btn btn-primary w-100 mt-3 btn-sm">Add</button></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-light rounded-4 border mt-4 py-3 px-2">
                        <!-- Delivery Instructions -->
                        <div class="p-3 w-100 fs-2 text-center fw-bolder text-secondary text-center" style="letter-spacing:3px;">
                            Delivery Instructions</div>
                        
                        {{-- @dd(Helpers::getOrderSessions(auth('customer')->user()->id, 'delivery_instruction')) --}}
                        <form action="{{ route('user.restaurant.delivery-instruction') }}" id="deliveryInstruction" method="post" data-instruction="{{json_encode(Helpers::getOrderSessions(auth('customer')->user()->id, "delivery_instruction"))}}">
                            @csrf
                            <div class="osahan-card-body">
                                <div class="overflow-x-scroll w-100">
                                    <div class="card delivery-ins text-start bg-white shadow-sm border-0 rounded-4">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <i class="feather-phone-off text-warning"></i>
                                                <div class="form-check custom-checkbox p-0">
                                                    <input class="form-check-input p-2" name="d_instruction[0]" type="checkbox" value="Avoid Calling" id="flexCheckDefault" style="border:1px solid #ff810a;">
                                                </div>
                                            </div>
                                            <h6 class="card-text fw-bold text-center">Avoid Calling</h6>
                                        </div>
                                    </div>
                                    <div class="card delivery-ins text-start bg-white shadow-sm border-0 rounded-4">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                {{-- <i class="feather-bell-off text-warning"></i> --}}
                                                <i class="fa-solid fa-bell text-warning"></i>
                                                <div class="form-check custom-checkbox p-0">
                                                    <input class="form-check-input p-2" name="d_instruction[1]" type="checkbox" value="Don't Ring the Bell" id="flexCheckDefault" style="border:1px solid #ff810a;">
                                                </div>
                                            </div>
                                            <h6 class="card-text fw-bold text-center">Don't Ring the Bell</h6>
                                        </div>
                                    </div>

                                    <div class="card delivery-ins text-start bg-white shadow-sm border-0 rounded-4">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <i class="fa-solid fa-dog text-warning"></i>
                                                <div class="form-check custom-checkbox p-0">
                                                    <input class="form-check-input p-2" type="checkbox" name="d_instruction[2]" value="Pets At Home" id="flexCheckDefault" style="border:1px solid #ff810a;">
                                                </div>
                                            </div>
                                            <h6 class="card-text fw-bold text-center">Pets At Home</h6>
                                        </div>
                                    </div>
                                    <div class="card delivery-ins text-start bg-white shadow-sm border-0 rounded-4">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <i class="fa-solid fa-door-closed text-warning"></i>
                                                <div class="form-check custom-checkbox p-0">
                                                    <input class="form-check-input p-2" type="checkbox" name="d_instruction[3]" value="Leave At the door" id="flexCheckDefault" style="border:1px solid #ff810a;">
                                                </div>
                                            </div>
                                            <h6 class="card-text fw-bold text-center"> Leave At the door</h6>
                                        </div>
                                    </div>

                                    <div class="card delivery-ins text-start bg-white shadow-sm border-0 rounded-4">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <i class="feather-plus text-warning"></i>
                                                <div class="form-check custom-checkbox p-0">
                                                    <input class="form-check-input p-2" id="leave-with-checkbox" type="checkbox" onchange="console.log(this.value)" name="d_instruction[4]" value="" id="flexCheckDefault" style="border:1px solid #ff810a;">
                                                </div>
                                            </div>
                                            <h6 class="card-text fw-bold text-center pe-1">
                                                {{-- <label for="leave-with">Leave with</label> --}}
                                                <input type="text" id="leave-with" class="form-control fs-6 px-2" placeholder="Ex:Gaurd"
                                                    style="    border-top: none;
                                                    border-right: none;
                                                    border-bottom: 1px solid gray;
                                                    border-left: none;
                                                    border-radius: 0px;">
                                            </h6>
                                            <script>
                                                document.querySelector('#leave-with').addEventListener('input', function(event) {
                                                    document.querySelector('#leave-with-checkbox').value = `${event.target.previousElementSibling.textContent} ${event.target.value} `;
                                                })
                                            </script>

                                                <script>
                                                // Robust cleanup for confetti paper pieces and mobile overflow
                                                function cleanupConfettiMobile() {
                                                    // Remove lingering .paper-piece elements
                                                    document.querySelectorAll('.paper-piece').forEach(el => el.remove());
                                                    // Reset overflow and width on body/html
                                                    document.body.style.overflowX = 'hidden';
                                                    document.body.style.maxWidth = '100vw';
                                                    document.documentElement.style.overflowX = 'hidden';
                                                    document.documentElement.style.maxWidth = '100vw';
                                                }

                                                // Listen for confetti animation end (or after modal closes)
                                                document.addEventListener('click', function(e) {
                                                    // If user interacts after confetti, trigger cleanup
                                                    setTimeout(cleanupConfettiMobile, 500);
                                                });

                                                // Optionally, cleanup on modal close (SweetAlert2)
                                                document.addEventListener('swalClose', function() {
                                                    setTimeout(cleanupConfettiMobile, 500);
                                                });
                                                </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-0 mt-3">
                                <div class="input-group">
                                    {{-- <span class="input-group-text" id="message"><i class="feather-message-square"></i></span> --}}
                                    <textarea placeholder="Any Other Instruction?" aria-label="With textarea" class="form-control fs-6" name="d_instruction[5]"></textarea>
                                </div>
                            </div>
                            <div class="text-center"><button type="submit" class="btn btn-primary w-100 mt-3 btn-sm">Add</button></div>
                        </form>

                    </div>

                    <!-- Order Scheduling Section -->
                    <div class="bg-light rounded-4 border mt-4 py-3 px-2">
                        <div class="p-3 w-100 fs-2 text-center fw-bolder text-secondary text-center" style="letter-spacing:3px;">
                            Schedule Order</div>
                        <div class="osahan-card-body">
                            <div id="schedule-order-container" class="bg-white rounded-4 p-3">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock text-warning me-2"></i>
                                        <span class="fw-bold">Schedule for later</span>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input p-2" type="checkbox" id="schedule-toggle" style="border:1px solid #ff810a;">
                                    </div>
                                </div>

                                <div id="schedule-form" class="d-none">
                                    <form id="scheduleOrderForm">
                                        @csrf
                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <label for="scheduled_date" class="form-label fw-bold">Date</label>
                                                <input type="date" class="form-control" id="scheduled_date" name="scheduled_date"
                                                       min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+7 days')) }}">
                                            </div>
                                            <div class="col-6 mb-3">
                                                <label for="scheduled_time" class="form-label fw-bold">Time</label>
                                                <input type="time" class="form-control" id="scheduled_time" name="scheduled_time">
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-warning w-100 btn-sm">Schedule Order</button>
                                        </div>
                                    </form>
                                </div>

                                <div id="schedule-display" class="d-none">
                                    <div class="alert alert-success d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="fas fa-check-circle me-2"></i>
                                            <span id="schedule-text"></span>
                                        </div>
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="remove-schedule">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Coupons Section -->
                    <div class="p-3 mt-4 fs-2 w-100 text-center fw-bolder text-secondary" style="letter-spacing:3px;border-top: 1px solid #80808063;">
                        Saving Corner</div>
                    <div applied-coupns="list">
                        @include('user-views.restaurant.checkout.coupon.applied')
                    </div>
                    <div class="p-3 m-0 bg-white border-1 border-top rounded-bottom-4 w-100 text-center">
                        <a href="javascript:void(0)" id="viewCouponListner" class="w-100">View Coupons <i class="feather-chevron-right text-warning ms-1 mt-1"></i></a>
                    </div>

                    <!-- Referral Discount Section -->
                    <div class="referral-discount-section mt-3 d-none" id="referral-discount-section">
                        <div class="p-3 bg-white rounded-4" id="referral-discount-container">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-gift text-warning me-2"></i>
                                    <span class="fw-bolder">Referral Rewards</span>
                                </div>
                                <button class="btn btn-sm btn-outline-warning" id="checkReferralRewards">
                                    <i class="fas fa-check me-1"></i> Check Rewards
                                </button>
                            </div>
                            <div id="referral-rewards-list" style="display: none;">
                                <!-- Available referral rewards will be loaded here -->
                            </div>
                            <div id="applied-referral-discount" style="display: none;">
                                <!-- Applied referral discount will be shown here -->
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Apply available referral rewards to get instant discounts on your order!
                            </small>
                        </div>
                    </div>

                    <!-- Delivery man Tips -->
                    <div class="p-3 mt-4 fs-2 w-100 text-center fw-bolder text-secondary" style="letter-spacing:3px;border-top: 1px solid #80808063;">
                        Delivery Man Tips</div>
                    <div class="osahan-card-body p-3 bg-white rounded-4">
                        <div class="overflow-x-scroll w-100">
                            <div class="card text-start bg-white shadow-sm border-1" data-dm-tips="10">
                                <div class="card-body">
                                    <h6 class="card-text fw-bolder text-center"> ₹10</h6>
                                </div>
                            </div>
                            <div class="card text-start bg-white shadow-sm border-1" data-dm-tips="20">
                                <div class="card-body">
                                    <h6 class="card-text fw-bolder text-center"> ₹20</h6>
                                </div>
                            </div>
                            <div class="card text-start bg-white shadow-sm border-1" data-dm-tips="30">
                                <div class="card-body">
                                    <h6 class="card-text fw-bolder text-center"> ₹30</h6>
                                </div>
                            </div>
                            <div class="card text-start bg-white shadow-sm border-1" data-dm-tips="50">
                                <div class="card-body">
                                    <h6 class="card-text fw-bolder text-center"> ₹50</h6>
                                </div>
                            </div>

                            <!-- Dynamic Tip Input Field (Initially Hidden) -->
                            <!-- Dynamic Input -->
                            <div id="tipInputContainer">
                                <input type="number" class="form-control py-3 shadow-sm" placeholder="Tip">
                            </div>
                        </div>
                    </div>
                    <!-- Bill Summary Section -->
                @endif

                <div class="p-3 mt-4 fs-2 w-100 text-center fw-bolder text-secondary" style="letter-spacing:3px;border-top: 1px solid #80808063;">Bill
                    Summary</div>
                <div class="bg-white p-3 clearfix rounded-4" data-billing="summery"></div>
                <!-- Ordering for someone -->
                @if ($userType == 'customer')
                    <div class="p-0 mt-4 w-100 rounded-4" style="background: #ff810a36;    border: 1px solid #ff810a;">
                        <div class="d-flex justify-content-between p-3" data-bs-toggle="collapse" data-bs-target="#collapseFood" aria-expanded="false" aria-controls="collapseFood">
                            <div>
                                <h5 class="fw-bolder mb-0" style="color:#ff810a ;">Ordering food for loved once?</h5>
                                <small>Add receiver details for
                                    a hassle free delivery</small>
                            </div>
                            <button class="px-2 py-1 fs-5 btn btn-link" type="button">
                                <i class="feather-chevron-down text-warning"></i>
                            </button>
                        </div>
                        <div class="collapse bg-white rounded-4" id="collapseFood">
                            <div class=" gold-members w-100">
                                <form id="orderForLovedOnce" onsubmit="event.preventDefault()">
                                    @csrf
                                    <div class="row px-4 py-3">
                                        <div class="col-lg-6 col-12">
                                            <div class="input-group">
                                                <span class="input-group-text" id="message"><i class="feather-user"></i></span>
                                                <input type="text" class="form-control fs-4" name="name" id="name" placeholder="Name">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-12 mt-3 mt-lg-0">
                                            <div class="input-group">
                                                <span class="input-group-text" id="message"><i class="feather-phone"></i></span>
                                                <input type="number" name="phone" id="phone" class="form-control fs-4" placeholder="Phone">
                                            </div>
                                        </div>
                                        <label for="" class="mt-3">Want to send Bill?</label>
                                        <div class="col-6">
                                            <input class="form-check-input" type="radio" name="sendBill" value="yes" id="sendBillYes">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="sendBillYes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <input class="form-check-input" type="radio" name="sendBill" value="no" id="sendBillNo">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="sendBillNo">
                                                No
                                            </label>
                                        </div>
                                        {{-- <div class="col-lg-6">
                                            <div class="form-check mt-2 mb-3">
                                                <input type="checkbox" class="form-check-input" name="confirmCheck" id="customCheck1">
                                                <label class="form-check-label ms-3 fx-6 " for="customCheck1">Confirm</label>
                                            </div>
                                        </div> --}}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
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
                <!-- Place your order -->
                <div class="row placeorder_bar mt-3 fixed-bottom d-flex justify-content-center shadow-none">
                    <div class="col-lg-8 col-12" style="display: flex;justify-content: center;">

                        <div class="bg-success rounded-4 d-flex justify-content-between align-items-center p-3 text-white" style="width: 873px;" >
                            <div class="d-flex justify-content-center me-3 fs-4">
                                <div class="me-2 ">Total</div>
                                <div class="fw-bolder" data-checkOut-price=""></div>

                            </div>
                            <a href="{{ route('user.restaurant.payment-options') }}" class="d-flex justify-content-between align-items-center fw-bolder fs-4 bg-white text-success rounded-4 p-3" style="font-weight: 900;width:60%;">Place Order<i class="feather-arrow-right ms-auto"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- Coupon popup -->
    <div class="modal fade" id="coupon" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-end modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center"> <i class="fa-solid fa-percent text-white me-3 p-2 rounded-circle" style="background:#ff810a ;"></i><span>Apply Coupon</span></h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light" id="display-coupons">
                    <div class="row">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control border-0 py-3" placeholder="Enter Coupon Code" aria-label="Recipient's username" aria-describedby="button-addon2">
                            <button class="btn bg-white text-secondary fw-bolder fs-6" type="button" id="button-addon2">APPLY</button>
                        </div>
                        <p>Best Coupon</p>
                        <div class="col-12">
                            <div class="p-3 m-0 bg-white border-bottom rounded-end-4" style="border-left: 5px solid #ff810a;">
                                <di class="d-flex justify-content-between align-self-center" v>
                                    <div>
                                        <!-- <div class="mb-0 d-flex align-self-center"> -->
                                        <h6 class="fw-bolder mb-0 align-self-center">SJOPWNOP</h6>
                                        <p class="mb-0" style="color:#09c4b2;">Save another ₹23 on this order</p>
                                        <!-- </div> -->
                                    </div>
                                    <a class="text-warning fs-6 fw-bold align-self-center" href="">Apply</a>
                                </di>
                                <hr>
                                <p class="mb-0">Use code WELCOMEBACK100 & get flat ₹100 off on orders above ₹199.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- offcanvas customize food --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="customizeCart" aria-labelledby="customizeCartLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="customizeCartLabel">Customize Cart</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" data-products="all">
            <p>Some placeholder content for the customize cart offcanvas.</p>
        </div>
    </div>
    {{-- offcanvas customize single food --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="customizeSingelFood" aria-labelledby="customizeCartLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="customizeCartLabel">Customize Cart</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" data-products="single">
            <p>Some placeholder content for the customize cart offcanvas.</p>
        </div>
    </div>
@endpush

@push('javascript')

    <script>
        // Mobile-responsive tooltip initialization function
        function initializeTooltips() {
            // Check if Bootstrap is available
            if (typeof bootstrap === 'undefined' || !bootstrap.Tooltip) {
                console.warn('Bootstrap Tooltip not available, using CSS fallback');
                return;
            }

            // Get all tooltip trigger elements
            var tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');

            // Detect if mobile device
            var isMobile = window.innerWidth <= 576;

            // Initialize each tooltip
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                // Dispose existing tooltip if any
                var existingTooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
                if (existingTooltip) {
                    existingTooltip.dispose();
                }

                // Determine optimal placement for mobile
                var placement = 'top';
                if (isMobile) {
                    var rect = tooltipTriggerEl.getBoundingClientRect();
                    var screenWidth = window.innerWidth;
                    var screenHeight = window.innerHeight;

                    // If element is in top half of screen, show tooltip below
                    if (rect.top < screenHeight / 2) {
                        placement = 'bottom';
                    }
                    // If element is near right edge, show tooltip on left
                    else if (rect.right > screenWidth * 0.7) {
                        placement = 'left';
                    }
                    // If element is near left edge, show tooltip on right
                    else if (rect.left < screenWidth * 0.3) {
                        placement = 'right';
                    }
                }

                // Create new tooltip with mobile-optimized settings
                try {
                    new bootstrap.Tooltip(tooltipTriggerEl, {
                        placement: placement,
                        boundary: 'viewport', // Keep within viewport
                        container: 'body', // Attach to body for better positioning
                        trigger: isMobile ? 'click' : 'hover focus', // Use click on mobile
                        delay: { show: 100, hide: isMobile ? 2000 : 100 }, // Longer delay on mobile
                        fallbackPlacements: ['top', 'bottom', 'left', 'right'], // Auto fallback
                        popperConfig: {
                            modifiers: [
                                {
                                    name: 'preventOverflow',
                                    options: {
                                        boundary: 'viewport',
                                        padding: 8
                                    }
                                },
                                {
                                    name: 'flip',
                                    options: {
                                        fallbackPlacements: ['top', 'bottom', 'left', 'right']
                                    }
                                }
                            ]
                        }
                    });
                } catch (e) {
                    console.error('Error creating tooltip:', e);
                }
            });

            console.log('Initialized', tooltipTriggerList.length, 'mobile-responsive tooltips');
        }

        // Make function globally available
        window.initializeTooltips = initializeTooltips;

        // CSRF token refresh function
        async function refreshCSRFToken() {
            try {
                const response = await fetch('{{ route("csrf-token") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.csrf_token) {
                        // Update meta tag
                        document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                        console.log('CSRF token refreshed');
                        return data.csrf_token;
                    }
                }
            } catch (error) {
                console.error('Failed to refresh CSRF token:', error);
            }
            return null;
        }

        // Global error handler for CSRF token expiry
        window.handleCSRFError = async function(retryFunction) {
            const newToken = await refreshCSRFToken();
            if (newToken && retryFunction) {
                return retryFunction();
            } else {
                toastr.error('Session expired. Please refresh the page.');
                return false;
            }
        };

        // Debug function to check CSRF token
        window.debugCSRF = function() {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            console.log('Current CSRF Token:', token);
            console.log('Token length:', token?.length);
            console.log('Session active:', typeof Laravel !== 'undefined' ? Laravel.csrfToken : 'Unknown');
            return token;
        };

        // Mobile touch handler for tooltips
        function handleMobileTooltips() {
            if (window.innerWidth <= 576) {
                // Add click listeners for mobile tooltip handling
                document.addEventListener('click', function(e) {
                    // Close all tooltips when clicking elsewhere
                    if (!e.target.closest('[data-bs-toggle="tooltip"]')) {
                        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
                            var tooltip = bootstrap.Tooltip.getInstance(el);
                            if (tooltip) {
                                tooltip.hide();
                            }
                        });
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips when page loads
            initializeTooltips();

            // Initialize mobile touch handlers
            handleMobileTooltips();

            // Also reinitialize after a short delay to catch any dynamically loaded content
            setTimeout(initializeTooltips, 1000);

            // Reinitialize tooltips on window resize (for orientation changes)
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    console.log('Window resized, reinitializing tooltips for new screen size');
                    initializeTooltips();
                    handleMobileTooltips();
                }, 250);
            });

            // Handle orientation change on mobile devices
            window.addEventListener('orientationchange', function() {
                setTimeout(function() {
                    console.log('Orientation changed, reinitializing tooltips');
                    initializeTooltips();
                    handleMobileTooltips();
                }, 500);
            });
        });
        document.querySelectorAll('.card[data-dm-tips]').forEach(card => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.card[data-dm-tips]').forEach(c => {
                    c.classList.remove('border-warning', 'highlight');
                    c.querySelector('.card-text').classList.remove('text-warning');
                    c.style.backgroundColor = ''; // Reset background color
                });
                card.classList.add('border-warning', 'highlight');
                card.querySelector('.card-text').classList.add('text-warning');
                // card.querySelector('input[type="radio"]').checked = true;
            });
        });
    </script>

    <script>
        // Grab the radio button and input container
        const otherTipRadio = document.getElementById("otherTip");
        const tipInputContainer = document.getElementById("tipInputContainer");

        // Event Listener
        if(otherTipRadio != null){
            otherTipRadio.addEventListener("change", function() {
                if (this.checked) {
                    tipInputContainer.style.display = "block"; // Show input
                }
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            /*============//Get Cart Items //=================*/
            async function getCarts() {
                const url = "{{ route('user.restaurant.get-cart-items') }}"
                try {
                    const resp = await fetch(url);
                    if (!resp.ok) {
                        const error = await resp.json();
                        throw new Error(error.message);
                    }

                    const result = await resp.json();

                    document.querySelector('[data-view-cart="all"]').innerHTML = result.view;
                    // Reinitialize tooltips for any new cart content
                    initializeTooltips();
                    billingSummery();
                    checkExistingReferralDiscount();

                    document.querySelectorAll('.gold-members input[data-item-cart-id]').forEach(productInput => {
                        console.log();
                        productInput.closest('.gold-members').querySelector('button[data-increase-by="1"]').addEventListener('click', () => {
                        
                            const qty = parseInt(productInput.value) + 1;
                            updateProductQuantity(productInput, qty);
                        })
                        productInput.closest('.gold-members').querySelector('button[data-increase-by="-1"]').addEventListener('click', () => {
                            const qty = parseInt(productInput.value) - 1;
                            if(qty < 0) return;
                            updateProductQuantity(productInput, qty);
                        })

                        productInput.addEventListener('keyup', (event) => {
                            const productInput = event.target;
                            const quantity = parseInt(productInput.value);
                            if (!isNaN(quantity)) {
                                updateProductQuantity(productInput, quantity);
                            }
                        });


                        async function updateProductQuantity(productInput, qty = 1) {
                            const udata = {
                                cart_item_id: productInput.dataset.itemCartId,
                                item_index: productInput.dataset.itemIndex,
                                item_type: productInput.dataset.itemType,
                                item_position: productInput.dataset.itemPosition,
                                item_quantity: qty
                            };

                            try {
                                const resp = await fetch('{{ route('user.restaurant.update_cart') }}', {
                                    method: "POST",
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Content-Type': 'application/json' // Added content type for JSON
                                    },
                                    body: JSON.stringify(udata),
                                });

                                if (!resp.ok) {
                                    const error = await resp.json();
                                    toastr.error(error.message); // Error handling if response is not ok
                                } else {
                                    const result = await resp.json();
                                    getCarts();
                                }
                            } catch (error) {
                                console.error("Error occurred:", error);
                                toastr.error("An error occurred while updating the product quantity.");
                            }
                        }
                    })

                } catch (error) {
                    console.error('Error :', error);
                    toastr.error(error);
                }
            }

            var customizeCartCanvas = new bootstrap.Offcanvas(document.getElementById('customizeCart'));
            var customizeSingelFood = new bootstrap.Offcanvas(document.getElementById('customizeSingelFood'));




            /*============//Add to cart//=================*/
            async function addToCart(product_id, qty, price, options = Null) {
                const url = "{{ route('user.restaurant.add-to-cart') }}"
                try {
                    const resp = await fetch(url, {
                        method: "post",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({
                            id: product_id,
                            qty: qty,
                            price: price,
                            options: options
                        })
                    });
                    if (!resp.ok) {
                        const error = await resp.json();

                        throw new Error(error.message);
                    }
                    const result = await resp.json();
                    toastr.success(result.message)
                    getCarts();


                } catch (error) {
                    console.error('Error:', error);
                    toastr.error(error.message);
                }
            }

            /*============//remove cart item//=================*/

            function deliveryManTips() {
                document.querySelectorAll('[data-dm-tips]').forEach(element => {
                    element.addEventListener('click',  () =>{
                        const dm_tips = element.dataset.dmTips;
                        if(setDmTips(dm_tips)){
                            if (!element.classList.contains('highlight')) {
                                element.classList.add('highlight');
                            }
                        } else {
                            if (element.classList.contains('highlight')) {
                                element.classList.remove('highlight');
                            }
                        }
                    })
                })
            }
            const setDmTips = async (dm_tips) => {
                const url = "{{ route('user.restaurant.dm-tips') }}?dm_tips=" + dm_tips;
                try {
                    const resp = await fetch(url);
                    if (!resp.ok) {
                        throw new Error("Something Going Wrong");
                    }
                    const result = await resp.json();

                    if (resp.ok && result !== null) {
                        // toastr.success(result.message)
                        await billingSummery();
                        return true;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    // toastr.error(error);
                }
            }

            deliveryManTips();
            document.getElementById('tipInputContainer')?.addEventListener('input', event => {
                const tipInput = event.target;
                const tip = parseInt(tipInput.value, 10); // Specify radix 10 for clarity

                if (!isNaN(tip) || tip > 0) {
                    // Use the parsed tip value here
                    setDmTips(tip);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Tip',
                        text: 'Please enter a valid tip amount.',
                    })
                }
            });
            getCarts();

            /*============//helping function//=================*/
            function product_discount(price, discount, d_type = 'amount') {
                if (d_type === 'percent') {
                    return parseInt(price) - (parseInt(price) * parseInt(discount) / 100);
                } else {
                    return parseInt(price) - parseInt(discount);
                }
            }

            function currencySymbolsuffix(amount, symbol = 'INR') {
                let icon = {
                    'USD': '$',
                    'INR': '₹'
                };
                return icon[symbol] + ' ' + amount;
            }
            /*============//helping function end//=================*/

            async function billingSummery() {
                // const url = "{{ route('user.restaurant.billing-summery') }}";
                const url = `${APP_URL}/billing-summery`;
                try {
                    const resp = await fetch(url);
                    if (!resp.ok) {
                        const error = await resp.json();
                        throw new Error(error.message);
                    }

                    const result = await resp.json();
                    document.querySelector('[data-billing="summery"]').innerHTML = result.view;
                    if (result.order_amount != null) {
                        document.querySelector('[data-checkOut-price]').innerHTML = currencySymbolsuffix(result.order_amount);
                    }else{
                        document.querySelector('[data-checkOut-price]').innerHTML = currencySymbolsuffix(0);
                    }

                    // Reinitialize tooltips for the new content
                    setTimeout(initializeTooltips, 100);

                } catch (error) {
                    console.error('Error :', error);
                    toastr.error(error);
                }
            }


            /*============//cooking Insruction//=================*/
            (function cookingInstruction() {
                const instructionForm = document.getElementById('cooking-instruction');
                const savedInstruction = instructionForm?.dataset.instruction;
                if (savedInstruction != null || undefined) {
                    instructionForm['instruction'].value = savedInstruction;
                }

                instructionForm?.addEventListener('submit', async (event) => {
                    event.preventDefault(); // Prevent the default form submission behavior
                    const url = "{{ route('user.restaurant.cooking-instruction') }}";
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const resp = await fetch(url, {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                instruction: instructionForm.instruction.value
                            })
                        });

                        if (!resp.ok) {
                            if (resp.status === 419) {
                                throw new Error("Session expired. Please refresh the page and try again.");
                            }
                            const errorData = await resp.json();
                            throw new Error(errorData.message || "Something went wrong");
                        }

                        const result = await resp.json();
                        if (result && result.message) {
                            toastr.success(result.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        toastr.error(error.message || 'An error occurred');
                    }
                });
            })();

            /*============//delivery Insruction//=================*/
            (function deliveryInstruction() {
                const instructionForm = document.getElementById('deliveryInstruction');
                // console.log("instruction form ",instructionForm);
                if (instructionForm == null) {
                    return;
                }

                const savedInstruction = instructionForm?.dataset.instruction != null && instructionForm.dataset.instruction !== '' ?
                    JSON.parse(instructionForm?.dataset.instruction) :
                    {};
                
                // console.log(savedInstruction, "printing saved instruction   ", typeof savedInstruction === 'object', Array.isArray(savedInstruction)) ;
                console.log("Saved Instruction:", savedInstruction != null , typeof savedInstruction === 'object' ,!Array.isArray(savedInstruction));
                if(savedInstruction != null && typeof savedInstruction === 'string'){
                    savedInstruction = [savedInstruction];
                }else if (savedInstruction != null && typeof savedInstruction === 'object' && !Array.isArray(savedInstruction)) {

                    if (savedInstruction[0] != null || undefined) {
                        instructionForm['d_instruction[0]'].checked = true;
                    }
                    

                    if (savedInstruction[1] != null || undefined) {
                        instructionForm['d_instruction[1]'].checked = true;
                    }
                    if (savedInstruction[2] != null || undefined) {
                        instructionForm['d_instruction[2]'].checked = true;
                    }
                    if (savedInstruction[3] != null || undefined) {
                        instructionForm['d_instruction[3]'].checked = true;
                    }
                    if (savedInstruction[4] != null || undefined) {
                        instructionForm['d_instruction[4]'].checked = true;
                        instructionForm['leave-with'].value = savedInstruction[4].replace("Leave with", "").trim();
                    }
                    if (savedInstruction[5] != null || undefined) {
                        instructionForm['d_instruction[5]'].value = savedInstruction[5].replace("Leave with", "").trim();
                    }
                    

                }
                
                instructionForm.addEventListener('submit', async (event) => {
                    event.preventDefault(); // Prevent the default form submission behavior
                    const formdata = new FormData(instructionForm);

                    // Ensure CSRF token is included in FormData
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    if (!formdata.has('_token')) {
                        formdata.append('_token', csrfToken);
                    }

                    const url = instructionForm.action;
                    try {
                        const resp = await fetch(url, {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formdata
                        });

                        if (!resp.ok) {
                            if (resp.status === 419) {
                                throw new Error("Session expired. Please refresh the page and try again.");
                            }
                            const errorData = await resp.json();
                            throw new Error(errorData.message || "Something went wrong");
                        }

                        const result = await resp.json();
                        if (result && result.message) {
                            toastr.success(result.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        toastr.error(error.message || 'An error occurred');
                    }
                });
            })();

            /*============//ORDER LOVED ONCE Insruction//=================*/

            const orderForLovedOnceForm = document.getElementById('orderForLovedOnce');
            console.log(orderForLovedOnceForm);
            orderForLovedOnceForm?.sendBill.forEach(item => item.addEventListener('click',handleOrderForLovedOnce));

            async function handleOrderForLovedOnce() {
                try {
                    // Validate required fields
                    if (orderForLovedOnceForm['name'].value.trim() === '' || orderForLovedOnceForm['phone'].value.trim() === '') {
                        toastr.info("Please fill in all required fields before submitting.");
                        return;
                    }

                    const formData = new FormData(orderForLovedOnceForm);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Ensure CSRF token is included
                    if (!formData.has('_token')) {
                        formData.append('_token', csrfToken);
                    }

                    const resp = await fetch("{{ route('user.restaurant.loved-one-data-store') }}", {
                        method: "POST",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        body: formData,
                    });

                    if (!resp.ok) {
                        if (resp.status === 419) {
                            toastr.error("Session expired. Please refresh the page and try again.");
                            return;
                        }
                        const error = await resp.json();
                        toastr.error(error.message || "Something went wrong");
                    } else {
                        const result = await resp.json();
                        toastr.success("Form submitted successfully!");
                    }
                } catch (error) {
                    console.error("Error occurred:", error);
                    toastr.error("An error occurred while submitting the form.");
                }
            }

            async function getLovedOneDate() {
                const url = "{{ route('user.restaurant.get-loved-one-stored-data') }}";
                try {
                    const resp = await fetch(url, {
                        redirect: "manual" // prevent auto-following redirects
                    });

                    // Check if response is a redirect
                    if (resp.status === 302) {
                        return null;
                    }

                    if (!resp.ok) {
                        throw new Error("Something went wrong");
                    }

                    const result = await resp.json();

                    if (result != null) {
                        orderForLovedOnceForm.name.value = result?.name || '';
                        orderForLovedOnceForm.phone.value = result?.phone || '';

                        if (result.sendBill) {
                            orderForLovedOnceForm.sendBill.forEach(sendBillCheckBox => {
                                sendBillCheckBox.checked = (sendBillCheckBox.value === "yes");
                            });
                        } else if (result.sendBill === false) {
                            orderForLovedOnceForm.sendBill.forEach(sendBillCheckBox => {
                                sendBillCheckBox.checked = (sendBillCheckBox.value === "no");
                            });
                        }
                    }

                } catch (error) {
                    console.error('Error:', error);
                    // toastr.error(error.message);
                    return null;
                }
            }

            setTimeout(getLovedOneDate, 2000);



            /*============//Coupons Process//=================*/
            const viewCouponButton = document.getElementById('viewCouponListner');
            viewCouponButton?.addEventListener('click', viewCoupons)

            async function viewCoupons() {
                try {
                    const resp = await fetch("{{ route('user.restaurant.get-coupons') }}");
                    if (!resp.ok) {
                        const error = await resp.json();
                        throw new Error(error.message);
                    }

                    const result = await resp.json();
                    document.getElementById('display-coupons').innerHTML = result.view;
                    // Reinitialize tooltips for any new coupon content
                    initializeTooltips();
                    $('#coupon').modal('show');
                    couponApply();

                } catch (error) {
                    console.error('Error :', error);
                    toastr.error(error);
                }
            }

            /*============//Referral Rewards Process//=================*/
            const checkReferralRewardsButton = document.getElementById('checkReferralRewards');
            checkReferralRewardsButton?.addEventListener('click', checkReferralRewards);

            async function checkReferralRewards() {
                try {
                    const resp = await fetch("{{ route('user.referral.claimed-rewards') }}");
                    if (!resp.ok) {
                        const error = await resp.json();
                        throw new Error(error.message);
                    }

                    const result = await resp.json();
                    if (result.success) {
                        displayReferralRewards(result.claimed_rewards);
                    }

                } catch (error) {
                    console.error('Error:', error);
                    toastr.error('Failed to load referral rewards');
                }
            }

            function displayReferralRewards(rewards) {
                const container = document.getElementById('referral-rewards-list');

                // Filter for unlocked discount rewards only
                const availableDiscountRewards = rewards.filter(reward => (!reward.is_used) &&
                    reward.is_unlocked &&
                    reward.reward_type === 'discount'
                );
                console.log(availableDiscountRewards);

                if (availableDiscountRewards.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-3">
                            <i class="fas fa-gift fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No discount rewards available for this order.</p>
                            <small class="text-muted">Complete more orders to unlock rewards!</small>
                        </div>
                    `;
                } else {
                    container.innerHTML = availableDiscountRewards.map(reward => {
                        let discountText = '';
                        if (reward.discount_type === 'percentage') {
                            discountText = `${reward.reward_value}% OFF${reward.max_amount ? ` (Max ₹${reward.max_amount})` : ''}`;
                        } else {
                            discountText = `₹${reward.reward_value} OFF`;
                        }

                        return `
                            <div class="referral-reward-item border rounded p-2 mb-2" style="border-color: #ffc107 !important;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold text-warning">
                                            <i class="fas fa-gift me-1"></i>
                                            ${discountText}
                                        </div>
                                        <small class="text-muted">Referral reward • Ready to use</small>
                                    </div>
                                    <button class="btn btn-sm btn-warning" onclick="applyReferralDiscount(${reward.id})">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        `;
                    }).join('');
                }

                container.style.display = 'block';
            }

            // Move these functions to global scope for onclick handlers
            window.applyReferralDiscount = async function(rewardId) {
                try {
                    const url = `{{ route('user.restaurant.apply-referral-discount') }}?reward_id=${rewardId}`;
                    const resp = await fetch(url);

                    if (!resp.ok) {
                        const result = await resp.json();
                        throw new Error(result.message);
                    }

                    const result = await resp.json();

                    if (result.success) {
                        let discountText = '';
                        if (result.reward.discount_type === 'percentage') {
                            discountText = `${result.reward.reward_value}% OFF${result.reward.max_amount ? ` (Max ₹${result.reward.max_amount})` : ''}`;
                        } else {
                            discountText = `₹${result.reward.reward_value} OFF`;
                        }

                        // Show applied discount
                        document.getElementById('applied-referral-discount').innerHTML = `
                            <div class="alert alert-success d-flex justify-content-between align-items-center p-2 mb-2">
                                <div>
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>${discountText}</strong> applied!
                                </div>
                                <button class="btn btn-sm btn-outline-danger" onclick="removeReferralDiscount()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                        document.getElementById('applied-referral-discount').style.display = 'block';
                        document.getElementById('referral-rewards-list').style.display = 'none';

                        // Refresh billing summary
                        await billingSummery();

                        toastr.success(result.message);
                    }

                } catch (error) {
                    console.error('Error:', error);
                    toastr.error(error.message || 'Failed to apply referral discount');
                }
            };

            window.removeReferralDiscount = async function() {
                try {
                    const resp = await fetch("{{ route('user.restaurant.remove-referral-discount') }}");

                    if (!resp.ok) {
                        const result = await resp.json();
                        throw new Error(result.message);
                    }

                    const result = await resp.json();

                    if (result.success) {
                        document.getElementById('applied-referral-discount').style.display = 'none';
                        document.getElementById('referral-rewards-list').style.display = 'block';

                        // Refresh billing summary
                        await billingSummery();

                        toastr.success('Referral discount removed');
                    }

                } catch (error) {
                    console.error('Error:', error);
                    toastr.error('Failed to remove referral discount');
                }
            };

            // Remove this function since we already have billingSummery function

            async function checkExistingReferralDiscount() {
                try {
                    const resp = await fetch("{{ route('user.restaurant.check-referral-discount-status') }}");
                    if (resp.ok) {
                        const result = await resp.json();
                         if(result.is_reward) document.getElementById('referral-discount-section').classList.remove('d-none');
                        if (result.applied) {
                            // Show applied discount UI

                            let discountText = '';
                            if (result.reward.discount_type === 'percentage') {
                                discountText = `${result.reward.reward_value}% OFF${result.reward.max_amount ? ` (Max ₹${result.reward.max_amount})` : ''}`;
                            } else {
                                discountText = `₹${result.reward.reward_value} OFF`;
                            }
                            document.getElementById('applied-referral-discount').innerHTML = `
                                <div class="alert alert-success d-flex justify-content-between align-items-center p-2 mb-2">
                                    <div>
                                        <i class="fas fa-check-circle me-2"></i>
                                        <strong>${discountText}</strong> applied!
                                    </div>
                                    <button class="btn btn-sm btn-outline-danger" onclick="removeReferralDiscount()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            `;
                            document.getElementById('applied-referral-discount').style.display = 'block';
                            document.getElementById('referral-rewards-list').style.display = 'none';
                        }
                    }
                } catch (error) {
                    console.error('Error checking referral discount status:', error);
                }
            }

            function couponApply() {
                document.querySelectorAll('[data-coupon="apply"]').forEach(coupon => {
                    coupon.addEventListener('click', async (event) => {
                        event.stopPropagation();
                        const couponId = coupon.getAttribute('couponId');
                        const couponCode = coupon.getAttribute('couponCode');
                        const url = `{{ route('user.restaurant.apply-coupons') }}?coupon_id=${encodeURIComponent(couponId)}&coupon_code=${encodeURIComponent(couponCode)}`;

                        try {
                            const resp = await fetch(url);
                            if (!resp.ok) {
                                const result = await resp.json();
                                throw new Error(result.message);
                            }

                            const result = await resp.json();
                            document.querySelector('[applied-coupns="list"]').innerHTML = result.applied
                            // Reinitialize tooltips for any new applied coupon content
                            initializeTooltips();
                            $('#coupon').modal('hide');
                            // Trigger confetti effect
                            createConfetti();

                            Swal.fire({
                                // title: result.message,
                                html: `
                                    <h4 style="margin: 0; font-weight: bold;">${result.message}</h4>
                                    <img class="blaster-source" src="{{ asset('assets/user/img/logo_web.png') }}" alt="Coupon Icon" style="width: 60px; margin: 15px 0;">
                                    <h4 style="margin: 0; font-weight: bold;">${result.saved}</h4>
                                    <div class="text-muted">More exciting deals are on the way—save more with each order</div>
                                    <h3 style="color: orange; font-weight: bold;">Woohoo!</h3>
                                `,
                                showConfirmButton: false,
                                showCloseButton: true,
                                width: 400,
                                padding: '1.5em',
                                background: '#ffffff',
                                customClass: {
                                    popup: 'custom-popup',
                                    title: 'custom-title'
                                }
                            }).then(() => {
                                // Update the applied coupons section
                                $('#applied-coupons-container').html(result.applied);
                                // Reinitialize tooltips for any new applied coupon content
                                initializeTooltips();
                            });
                            billingSummery();
                        } catch (error) {
                            console.error('Error:', error);
                            Swal.fire({
                                title: error.message,
                                // text: "That thing is still around?",
                                icon: "info"
                            });
                        }
                    });
                });
            }

        });
    </script>
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

        // Paper Blaster Effect Function
        function createConfetti() {
            // Wait for the modal to be fully visible and positioned
            setTimeout(() => {
                const modal = document.querySelector('.swal2-modal') || document.querySelector('.swal2-popup');
                if (modal) {
                    // Force a reflow to ensure modal is in final position
                    modal.getBoundingClientRect();
                    createPaperExplosion(modal);
                }
            }, 300); // Increased delay for mobile
        }

        function createPaperExplosion(sourceElement) {
            const colors = ['#ff810a', '#ffc107', '#28a745', '#dc3545', '#6f42c1', '#fd7e14', '#17a2b8', '#e83e8c', '#fff', '#ffd700'];

            // Force reflow to get accurate position
            const rect = sourceElement.getBoundingClientRect();

            // Account for page scroll on mobile
            const scrollX = window.pageXOffset || document.documentElement.scrollLeft;
            const scrollY = window.pageYOffset || document.documentElement.scrollTop;

            // Calculate center position relative to viewport (not page)
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;

            // Detect mobile device
            const isMobile = window.innerWidth <= 768;
            const waveCount = isMobile ? 3 : 4; // Fewer waves on mobile

            // Hexagonal explosion pattern - 6 main directions at 60-degree intervals
            const hexagonalDirections = [
                0,          // 0° (right)
                Math.PI/3,  // 60° (upper right)
                2*Math.PI/3, // 120° (upper left)
                Math.PI,    // 180° (left)
                4*Math.PI/3, // 240° (lower left)
                5*Math.PI/3  // 300° (lower right)
            ];

            // Create multiple waves with hexagonal pattern
            for (let wave = 0; wave < waveCount; wave++) {
                setTimeout(() => {
                    createHexagonalWave(colors, centerX, centerY, wave, hexagonalDirections, isMobile);
                }, wave * 150); // Stagger the waves
            }

            // Add boom effect to the source
            sourceElement.classList.add('boom-effect');
            setTimeout(() => {
                sourceElement.classList.remove('boom-effect');
            }, 1200);
        }

        function createHexagonalWave(colors, centerX, centerY, wave, directions, isMobile = false) {
            const papersPerDirection = isMobile ? 10 + wave * 3 : 15 + wave * 5; // Fewer papers on mobile
            const spreadAngle = Math.PI / 6; // 30-degree spread per direction

            directions.forEach((baseAngle, directionIndex) => {
                for (let i = 0; i < papersPerDirection; i++) {
                    // Create spread within each hexagonal direction
                    const angleVariation = (spreadAngle * (i / papersPerDirection - 0.5)) + (Math.random() - 0.5) * 0.3;
                    const finalAngle = baseAngle + angleVariation;

                    // Vary velocity for depth effect
                    const baseVelocity = isMobile ? 150 + wave * 80 : 200 + wave * 100;
                    const velocity = baseVelocity + Math.random() * (isMobile ? 300 : 400);

                    const color = colors[Math.floor(Math.random() * colors.length)];

                    setTimeout(() => {
                        createHexagonalPaperPiece(color, centerX, centerY, finalAngle, velocity, wave, i, isMobile);
                    }, i * 20 + directionIndex * 30); // Stagger within each direction
                }
            });
        }

        function createHexagonalPaperPiece(color, originX, originY, angle, velocity, wave, index, isMobile = false) {
            const paper = document.createElement('div');
            paper.classList.add('paper-piece');

            // Enhanced shapes with hexagonal preference
            const shapes = isMobile
                ? ['square', 'circle', 'triangle', 'hexagon'] // Simpler shapes on mobile
                : ['square', 'rectangle', 'circle', 'triangle', 'hexagon', 'diamond'];
            const shape = shapes[Math.floor(Math.random() * shapes.length)];
            paper.classList.add(shape);

            if (['triangle', 'hexagon', 'diamond'].includes(shape)) {
                paper.style.setProperty('--paper-color', color);
            } else {
                paper.style.backgroundColor = color;
            }

            // Set initial position using fixed positioning (no scroll offset needed)
            paper.style.left = originX + 'px';
            paper.style.top = originY + 'px';

            // Calculate precise hexagonal trajectory
            const screenWidth = window.innerWidth;
            const screenHeight = window.innerHeight;

            // Adjust velocity based on screen size
            const screenScale = isMobile ? Math.min(screenWidth, screenHeight) / 400 : 1;
            const adjustedVelocity = velocity * screenScale;

            // Calculate final position based on angle and velocity
            let finalX = originX + Math.cos(angle) * adjustedVelocity;
            let finalY = originY + Math.sin(angle) * adjustedVelocity;

            // Ensure hexagonal spread covers full screen
            const maxDistance = Math.max(screenWidth, screenHeight);
            const coverageMultiplier = isMobile ? 0.7 : 0.8;
            if (adjustedVelocity < maxDistance * (isMobile ? 0.5 : 0.7)) {
                finalX = originX + Math.cos(angle) * (maxDistance * coverageMultiplier);
                finalY = originY + Math.sin(angle) * (maxDistance * coverageMultiplier);
            }

            // Add wave-based variations and gravity
            finalY += Math.random() * (isMobile ? 150 : 200) + wave * (isMobile ? 60 : 80);
            finalX += (Math.random() - 0.5) * (isMobile ? 80 : 100); // Slight horizontal drift

            // Enhanced rotation for hexagonal effect
            const rotationSpeed = isMobile ? 300 + wave * 120 : 360 + wave * 180;
            const finalRotation = (Math.random() * rotationSpeed * 2 - rotationSpeed) + (angle * 180/Math.PI);

            paper.style.setProperty('--final-x', finalX + 'px');
            paper.style.setProperty('--final-y', finalY + 'px');
            paper.style.setProperty('--rotation', finalRotation + 'deg');
            paper.style.setProperty('--scale', (0.6 + Math.random() * 1.2 + wave * 0.2));

            // Add hexagonal glow effect for special papers (less on mobile)
            if (!isMobile && index % 10 === 0) {
                paper.classList.add('glow-paper');
            }

            document.body.appendChild(paper);

            // Force reflow to ensure paper is positioned before animation
            paper.getBoundingClientRect();

            // Trigger animation with hexagonal timing - immediate on mobile for better performance
            const animationDelay = isMobile ? Math.random() * 100 : Math.random() * 200 + wave * 100;
            setTimeout(() => {
                paper.classList.add('exploding');
            }, animationDelay);

            // Remove paper piece after duration (shorter on mobile)
            const cleanupTime = isMobile ? 7000 + wave * 1000 : 9000 + wave * 1500;
            setTimeout(() => {
                if (paper.parentNode) {
                    paper.parentNode.removeChild(paper);
                }
            }, cleanupTime);
        }
    </script>

    <script>
        // Order Scheduling functionality
        document.addEventListener('DOMContentLoaded', function() {
            const scheduleToggle = document.getElementById('schedule-toggle');
            const scheduleForm = document.getElementById('schedule-form');
            const scheduleDisplay = document.getElementById('schedule-display');
            const scheduleOrderForm = document.getElementById('scheduleOrderForm');
            const removeScheduleBtn = document.getElementById('remove-schedule');
            const scheduleText = document.getElementById('schedule-text');

            // Check if there's already a scheduled time
            checkScheduleStatus();

            // Toggle schedule form
            scheduleToggle.addEventListener('change', function() {
                if (this.checked) {
                    scheduleForm.classList.remove('d-none');
                    scheduleDisplay.classList.add('d-none');
                } else {
                    scheduleForm.classList.add('d-none');
                    checkScheduleStatus();
                }
            });

            // Handle schedule form submission
            scheduleOrderForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch('{{ route("user.restaurant.schedule-order") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        scheduleForm.classList.add('d-none');
                        scheduleDisplay.classList.remove('d-none');
                        scheduleText.textContent = data.message;
                        scheduleToggle.checked = false;
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Something went wrong');
                });
            });

            // Handle schedule removal
            removeScheduleBtn.addEventListener('click', function() {
                fetch('{{ route("user.restaurant.remove-schedule") }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        scheduleDisplay.classList.add('d-none');
                        scheduleToggle.checked = false;
                        toastr.success(data.message);
                        // Clear form
                        document.getElementById('scheduled_date').value = '';
                        document.getElementById('scheduled_time').value = '';
                    } else {
                        toastr.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Something went wrong');
                });
            });

            // Check current schedule status
            function checkScheduleStatus() {
                @if(auth('customer')->check())
                    @php
                        $scheduledTime = Helpers::getOrderSessions(auth('customer')->user()->id, "order_scheduled_time");
                    @endphp
                    @if($scheduledTime)
                        scheduleDisplay.classList.remove('d-none');
                        scheduleText.textContent = 'Scheduled for {{ Carbon\Carbon::parse($scheduledTime)->format('d M Y, h:i A') }}';
                    @endif
                @endif
            }

            // Set minimum time based on current time + 30 minutes
            function setMinTime() {
                const now = new Date();
                const dateInput = document.getElementById('scheduled_date');
                const timeInput = document.getElementById('scheduled_time');

                dateInput.addEventListener('change', function() {
                    const selectedDate = new Date(this.value);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    if (selectedDate.getTime() === today.getTime()) {
                        // If today is selected, set minimum time to current time + 30 minutes
                        const minTime = new Date();
                        minTime.setMinutes(minTime.getMinutes() + 30);
                        timeInput.min = minTime.toTimeString().slice(0, 5);
                    } else {
                        // If future date is selected, no time restriction
                        timeInput.min = '';
                    }
                });
            }

            setMinTime();
        });
    </script>
@endpush
