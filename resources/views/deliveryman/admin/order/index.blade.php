{{-- 
    DELIVERY TIMER FEATURES IMPLEMENTED:
    
    1. Restaurant Reach-Out Timer: Shows ETA for delivery man to reach restaurant
    2. Customer Reach-Out Timer: Shows ETA for complete delivery process
    3. Real-time Updates: Timers update every 30 seconds
    4. Visual Indicators: Progress bars, color coding, and status indicators
    5. Audio Notifications: Sound alerts for urgent and overdue situations
    6. Visual Animations: Pulsing effects for critical times
    7. Server Refresh: Timers refresh from server every 5 minutes or on significant location change
    8. Breakdown Details: Collapsible section showing time breakdown
    9. Status Integration: Updates when order is picked up or delivered
    
    Data Structure:
    - restaurantReachOutTimer: { eta_minutes, eta_time, factors }
    - customerReachOutTimer: { eta_minutes, eta_time, breakdown, factors }
--}}

@extends('deliveryman.admin.layouts.main')
@section('content')
    @push('css')
        <style>
            .slider-container {
                position: relative;
                width: auto;
                height: 60px;
                /* background: linear-gradient(145deg, #e2e8f0, #cbd5e1); */
                background: linear-gradient(90deg, rgb(255 255 255), rgb(255 138 0));
                border-radius: 30px;
                box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 10px;
            }

            .slider-button {
                position: absolute;
                top: 5px;
                left: 5px;
                width: 50px;
                height: 50px;
                background: linear-gradient(145deg, #ffffff, #ffffff);
                border-radius: 50%;
                cursor: pointer;
                text-align: center;
                color: white;
                font-size: 18px;
                line-height: 50px;
                box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
                transition: left 0.2s;
                user-select: none;

                /* Add SVG as a background image */
                background-image: url('data:image/svg+xml,%3Csvg fill="%23ff8a00" width="30px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 330 330"%3E%3Cpath d="M250.606,154.389l-150-149.996c-5.857-5.858-15.355-5.858-21.213,0.001 c-5.857,5.858-5.857,15.355,0.001,21.213l139.393,139.39L79.393,304.394c-5.857,5.858-5.857,15.355,0.001,21.213 C82.322,328.536,86.161,330,90,330s7.678-1.464,10.607-4.394l149.999-150.004c2.814-2.813,4.394-6.628,4.394-10.606 C255,161.018,253.42,157.202,250.606,154.389z"%3E%3C/path%3E%3C/svg%3E');
                background-size: 50%;
                background-position: center;
                background-repeat: no-repeat;
            }

            .slider-text {
                font-size: 20px;
                color: #ffffff;
                transition: color 0.3s;
                pointer-events: none;
                font-weight: bold;
            }

            .slider-container.slider-success {
                background: linear-gradient(273deg, rgb(255 255 255), rgb(25 135 84 / 30%));
                /* background-color: #4CAF50; */
                transition: background 0.3s;
            }

            .slider-success .slider-text {
                color: white;
            }

            .progress-bar {
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                background-color: rgba(76, 175, 80, 0.2);
                width: 0;
                transition: width 0.2s;
            }
        </style>
        <style>
            .map-box {
                /* max-width: 375px; */
                margin: auto;
                padding: 15px;
                /* background-color: #FFFFFF; */
                border-radius: 25px;
                overflow: hidden;
                /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
            }

            .map {
                height: 350px;
                background-color: #E4E2FD;
                position: relative;
            }

            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 15px;
                position: absolute;
                width: 100%;
                top: 0;
                z-index: 1;
            }

            .menu-icon,
            .status-icon {
                background-color: #ff8a00;
                padding: 10px;
                border-radius: 8px;
                cursor: pointer;
                color: white;
            }

            .map img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .commission-details {
                background-color: #fff;
                padding: 20px;
                border-bottom-right-radius: 25px;
                border-top-right-radius: 25px;
                /* border-right: 5px solid #ff810a; */
                border-left: 5px solid #ff810a;
                /* color: #ff810a; */
            }

            .order-details p {
                margin: 5px 0;
            }

            .live-order-box {
                background: #fff;
                /* border: 1px solid #ff810a; */
                /* padding: 20px; */
                border-radius: 25px;
                /* border-top-right-radius: 25px; */
                /* color: #FFFFFF; */
                /* color: #ff810a; */
                margin-bottom: 100px;
            }

            .live-order-details {
                /* background: #fff; */
                border-bottom: 1px solid #ff810a;
                padding: 20px;
                border-radius: 15px 15px 0px 0px;
                /* border-top-right-radius: 25px; */
                /* color: #FFFFFF; */
                color: #ff810a;
            }

            .live-order-details-box {
                background: #fff;
                /* border: 1px solid #ff810a; */
                padding: 20px;
                border-bottom-left-radius: 25px;
                border-bottom-right-radius: 25px;
                /* color: #FFFFFF; */
                color: #ff810a;
            }

            .locations {
                padding: 20px 0px 20px 0px;
            }

            .location {
                display: flex;
                justify-content: space-between;
                align-items: end;
                margin-bottom: 15px;
                padding: 15px;
                background-color: #fff;
                border-radius: 15px;

            }

            .location img {
                width: 50px;
                height: 50px;
                border-radius: 15px;
                margin-right: 15px;
            }

            .location-details {
                color: #333;
            }

            .buttons {
                display: flex;
                justify-content: space-between;
                padding: 20px;
            }

            .buttons button {
                width: 48%;
                padding: 15px;
                border-radius: 15px;
                border: none;
                cursor: pointer;
                font-weight: bold;
                font-size: 16px;
            }

            .decline {
                background-color: #ff8a00;
                color: #FFFFFF;
                width: 100% !important;
                padding: 15px 11px;
                margin: 0px 3px;
            }

            .accept {
                background-color: #198754;
                color: #FFFFFF;
                width: 100% !important;
                padding: 15px 11px;
                margin: 0px 3px;
            }

            .restaurant-fix {
                padding: 15px;
                background-color: #F6F6F6;
                border-radius: 15px;
            }

            .address-bar-icon {
                width: auto;
                height: 43px;
                background-color: #ff8a00;
                /* position: relative; */
                display: flex;
                /* justify-content: end; */
                align-items: center;
                border-radius: 0;
                /* clip-path: polygon(0 0, 82% 0, 100% -90%, 95% 100%, 0 116%); */
                /* clip-path: polygon(0 0, 85% 0, 100% 50%, 85% 100%, 0 100%); */
                /* clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%, 15% 50%); */
            }

            .res-btn {
                background-color: #ffe8d3;
            }

            @media (max-width: 576px) {
                .buttons button {
                    width: auto;
                    padding: 15px 11px;
                }

                .live-order-details-box {
                    padding: 12px 8px;
                }

                /* .restaurant-fix {
                    padding-top: 28px;
                } */

                .address-bar-icon {
                    /* clip-path: polygon(0 0, 82% 0, 100% -32%, 89% 100%, 0 100%); */
                }
            }

            .fullscreen {
                width: 100vw !important;
                height: 100vh !important;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
            }

            .delivery-section {
                padding-bottom: 140px;
            }

            .accordion-button::after {
                display: none
            }

            /* Timer Animations */
            @keyframes pulse-warning {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }

            @keyframes pulse-danger {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }

            .timer-warning {
                animation: pulse-warning 1s infinite;
            }

            .timer-danger {
                animation: pulse-danger 0.5s infinite;
            }

            .timer-status-indicator {
                position: relative;
            }

            .timer-status-indicator::after {
                content: '';
                position: absolute;
                top: -2px;
                right: -2px;
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background-color: #28a745;
            }

            .timer-status-indicator.warning::after {
                background-color: #ffc107;
                animation: pulse-warning 1s infinite;
            }

            .timer-status-indicator.danger::after {
                background-color: #dc3545;
                animation: pulse-danger 0.5s infinite;
            }

            /* Subtle progress bar styling */
            .progress {
                background-color: rgba(0, 0, 0, 0.05);
                border-radius: 1px;
            }

            .progress-bar {
                border-radius: 1px;
                transition: width 0.3s ease-in-out;
            }

            /* Deliver Within styling */
            [data-deliver-within] {
                text-shadow: 0 1px 2px rgba(0,0,0,0.1);
                letter-spacing: 0.5px;
            }

            /* Order status section styling */
            .location-details h6 {
                color: #333;
                font-weight: 600;
            }

            .location-details p {
                font-size: 0.9rem;
            }
        </style>
    @endpush
    <?php
    $zone = \App\Models\Zone::find($order->restaurant->zone_id);
    $invoiceItemsList = [];
    
    foreach ($order->details as $orderItem) {
        $itemDetails = json_decode($orderItem->food_details);
    
        if ($itemDetails->isCustomize != 1) {
            $invoiceItemsList[] = [
                'name' => $itemDetails->name,
                'quantity' => $orderItem->quantity,
                'price' => $orderItem->quantity * $orderItem->price,
            ];
        } else {
            foreach (json_decode($orderItem->variation) as $variation) {
                // $variation->option;
                foreach ($variation->values as $value) {
                    $invoiceItemsList[] = [
                        'name' => $itemDetails->name . " ($value->label)",
                        'quantity' => $value->qty,
                        'price' => $value->price * $value->qty,
                    ];
                }
            }
        }
    
        foreach (json_decode($orderItem->add_ons) as $addon) {
            $invoiceItemsList[] = [
                'name' => $addon->name,
                'quantity' => $addon->qty,
                'price' => $addon->price * $addon->qty,
            ];
        }
    }
    ?>

    <div class="">
        <div class="container delivery-section">
            <div class="row">
                <div class="col-lg-8 map-box sticky-top" style="top: 0px; z-index: 1020;">
                    <div class="commission-details shadow-sm">
                        <h5 class="fw-bolder mb-0 text-warning text-center">Order ID : #{{ $order->id }}</h5>
                        <div class="d-flex justify-content-between mt-2">
                            <div>
                                <p class="mb-0 fs-6">Order Amount</p>
                                @if ($order->delivered == null)
                                    <p class="mb-0 fs-6">Collectable Amount</p>
                                @endif
                                {{-- <p class="mb-0 fs-6">Incentive</p>
                            <p class="mb-0 fs-6">Total Commission</p> --}}
                            </div>
                            <div>
                                <p class="mb-0 fs-6">{{ Helpers::format_currency($order->order_amount) }}
                                </p>
                                @if ($order->delivered == null)
                                    <p class="mb-0 fs-6">{{ Helpers::format_currency($order->cash_to_collect) }}
                                    </p>
                                @endif
                                {{-- <p class="mb-0 fs-6">₹ 50</p>
                            <p class="mb-0 fs-6">₹ 250</p> --}}
                            </div>
                        </div>
                    </div>

                </div>
                <div class="map-box col-lg-8">
                    {{-- <div class=""> --}}
                    <div class="p-3 d-flex justify-content-between align-items-center bg-white"
                        style="border-bottom: 5px solid #ff810a;border-radius:15px;">
                        <div class="location-details">
                            <h6><strong>Order Placed</strong></h6>
                            <p class="mb-0 text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}</p>
                        </div>
                        @if ($order->delivered != null)
                            <div>
                                <h6><strong>Delivered</strong></h6>
                                <p class="mb-0 text-end">{{ \Carbon\Carbon::parse($order->delivered)->format('h:i A') }}</p>
                            </div>
                        @else
                            <div>
                                <h6><strong>Deliver Within</strong></h6>
                                @if (isset($customerReachOutTimer))
                                    <p class="mb-0 text-end text-warning fw-bold" data-deliver-within="customer" style="font-size: 1.1rem;">
                                        {{ number_format($customerReachOutTimer['eta_minutes'], 0) }} Min
                                    </p>
                                @else
                                    <p class="mb-0 text-end text-warning fw-bold" style="font-size: 1.1rem;">15-20 Min</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Timer Information Section --}}
                    @if ((isset($restaurantReachOutTimer) && $order->picked_up == null) || (isset($customerReachOutTimer) && $order->delivered == null))
                        <div class="mt-3 p-3 bg-light rounded-3">
                            <div class="row">
                                @if (isset($restaurantReachOutTimer) && $order->picked_up == null)
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-store"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">Restaurant ETA</h6>
                                                <p class="mb-1 text-primary fw-bold" data-timer="restaurant">{{ number_format($restaurantReachOutTimer['eta_minutes'], 1) }} minutes</p>
                                                <small class="text-muted" data-time="restaurant">{{ \Carbon\Carbon::parse($restaurantReachOutTimer['eta_time'])->format('h:i A') }}</small>
                                                <div class="progress mt-1" style="height: 2px; visibility: hidden;  ">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 0%" data-progress="restaurant"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if (isset($customerReachOutTimer) && $order->delivered == null)
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">Customer ETA</h6>
                                                <p class="mb-1 text-success fw-bold" data-timer="customer">{{ number_format($customerReachOutTimer['eta_minutes'], 1) }} minutes</p>
                                                <small class="text-muted" data-time="customer">{{ \Carbon\Carbon::parse($customerReachOutTimer['eta_time'])->format('h:i A') }}</small>
                                                <div class="progress mt-1" style="height: 2px; visibility: hidden;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 0%" data-progress="customer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Detailed Breakdown (Collapsible) --}}
                            @if (isset($customerReachOutTimer['breakdown']))
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#timerBreakdown" aria-expanded="false">
                                        <i class="fas fa-chart-line me-1"></i> View Breakdown
                                    </button>
                                    <div class="collapse mt-2" id="timerBreakdown">
                                        <div class="card card-body">
                                            <small class="fw-bold text-muted mb-2">TIME BREAKDOWN:</small>
                                            <div class="row small">
                                                <div class="col-6">
                                                    <div class="d-flex justify-content-between">
                                                        <span>To Restaurant:</span>
                                                        <span>{{ number_format($customerReachOutTimer['breakdown']['driver_to_restaurant'], 1) }}m</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span>Wait for Food:</span>
                                                        <span>{{ number_format($customerReachOutTimer['breakdown']['wait_for_food'], 1) }}m</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span>Pickup Time:</span>
                                                        <span>{{ number_format($customerReachOutTimer['breakdown']['pickup_fixed'], 1) }}m</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="d-flex justify-content-between">
                                                        <span>To Customer:</span>
                                                        <span>{{ number_format($customerReachOutTimer['breakdown']['restaurant_to_customer'], 1) }}m</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span>Buffer Time:</span>
                                                        <span>{{ number_format($customerReachOutTimer['breakdown']['buffer'], 1) }}m</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between fw-bold">
                                                        <span>Total:</span>
                                                        <span>{{ number_format($customerReachOutTimer['eta_minutes'], 1) }}m</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                    {{--
                </div> --}}

                     <!-- Delivery Instructions Card -->
                    @php $deliverymanInstructions = json_decode($order->delivery_instruction, true) ?? null ; @endphp
                    @if(is_array($deliverymanInstructions) && count($deliverymanInstructions) > 0)
                    <div id="notes-card" class="card border-0 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-4 shadow-md mb-6">
                        <h3 class="text-lg fw-bolder text-blue-800 flex items-center mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M15.5 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.5L15.5 3Z"/><path d="M15 3v6h6"/></svg>
                            Delivery Instructions
                        </h2>
                        <hr>
                        {!! implode('', array_map(fn($instruction) => "<p class='text-base text-blue-900 font-medium mb-2'><i class='fas fa-square-check me-2'></i>{$instruction}</p>", $deliverymanInstructions)) !!}
                    </div>
                    @endif
                    <div class="mb-3 mt-3 px-2 text-warning fw-bolder text-center">Restaurant Detail</div>
                    <div class="bg-white py-4 rounded-4 mb-4">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item border border-0">
                                <h5 class="accordion-header address-bar-icon pe-4 ps-4 py-3 text-white fw-bolder">
                                    <button class="accordion-button bg-transparent text-white p-0 shadow-none"
                                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                        aria-expanded="true" aria-controls="collapseOne">
                                        <svg fill="currentColor" width="30px" version="1.1" id="Layer_1"
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            viewBox="0 0 463 463" xml:space="preserve">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                            </g>
                                            <g id="SVGRepo_iconCarrier">
                                                <g>
                                                    <g>
                                                        <g>
                                                            <path
                                                                d="M463,187.5v-74c0-5.238-4.262-9.5-9.5-9.5H447V79.5c0-12.958-10.542-23.5-23.5-23.5H343v-0.5 c0-12.958-10.542-23.5-23.5-23.5h-176C130.542,32,120,42.542,120,55.5V56H39.5C26.542,56,16,66.542,16,79.5V104H9.5 c-5.238,0-9.5,4.262-9.5,9.5v74c0,12.376,6.37,23.288,16,29.644V416H7.5c-4.142,0-7.5,3.358-7.5,7.5c0,4.142,3.358,7.5,7.5,7.5 h448c4.142,0,7.5-3.358,7.5-7.5c0-4.142-3.358-7.5-7.5-7.5H447V217.144C456.63,210.788,463,199.876,463,187.5z M135,55.5 c0-4.687,3.813-8.5,8.5-8.5h176c4.687,0,8.5,3.813,8.5,8.5v7.961c0,0.013-0.002,0.025-0.002,0.039 c0,0.006,0.001,0.013,0.001,0.02C327.988,68.197,324.18,72,319.5,72h-176c-4.687,0-8.5-3.813-8.5-8.5V55.5z M31,79.5 c0-4.687,3.813-8.5,8.5-8.5h81.734c3.138,9.29,11.93,16,22.266,16h176c10.336,0,19.128-6.71,22.266-16H423.5 c4.687,0,8.5,3.813,8.5,8.5V104H31V79.5z M392,119v68.5c0,11.304-9.196,20.5-20.5,20.5c-11.304,0-20.5-9.196-20.5-20.5V119H392z M336,119v68.5c0,11.304-9.196,20.5-20.5,20.5c-11.304,0-20.5-9.196-20.5-20.5V119H336z M280,119v68.5 c0,11.304-9.196,20.5-20.5,20.5c-11.304,0-20.5-9.196-20.5-20.5V119H280z M224,119v68.5c0,11.304-9.196,20.5-20.5,20.5 s-20.5-9.196-20.5-20.5V119H224z M168,119v68.5c0,11.304-9.196,20.5-20.5,20.5s-20.5-9.196-20.5-20.5V119H168z M112,119v68.5 c0,11.304-9.196,20.5-20.5,20.5S71,198.804,71,187.5V119H112z M15,187.5V119h41v68.5c0,11.304-9.196,20.5-20.5,20.5 S15,198.804,15,187.5z M144,416H63v-17h81V416z M144,384H63V255h81V384z M432,416H159V247.5c0-4.142-3.358-7.5-7.5-7.5h-96 c-4.142,0-7.5,3.358-7.5,7.5V416H31V222.705c1.475,0.188,2.975,0.295,4.5,0.295c11.368,0,21.498-5.378,28-13.716 C70.002,217.622,80.132,223,91.5,223s21.498-5.378,28-13.716c6.502,8.338,16.632,13.716,28,13.716s21.498-5.378,28-13.716 c6.502,8.338,16.632,13.716,28,13.716s21.498-5.378,28-13.716c6.502,8.338,16.632,13.716,28,13.716s21.498-5.378,28-13.716 c6.502,8.338,16.632,13.716,28,13.716c11.368,0,21.498-5.378,28-13.716c6.502,8.338,16.632,13.716,28,13.716 c11.368,0,21.498-5.378,28-13.716c6.502,8.338,16.632,13.716,28,13.716c1.525,0,3.025-0.107,4.5-0.295V416z M427.5,208 c-11.304,0-20.5-9.196-20.5-20.5V119h41v68.5C448,198.804,438.804,208,427.5,208z">
                                                            </path>
                                                            <path
                                                                d="M407.5,240h-224c-4.142,0-7.5,3.358-7.5,7.5v144c0,4.142,3.358,7.5,7.5,7.5h224c4.142,0,7.5-3.358,7.5-7.5v-144 C415,243.358,411.642,240,407.5,240z M400,384h-49v-25h24.5c4.142,0,7.5-3.358,7.5-7.5c0-4.142-3.358-7.5-7.5-7.5h-64 c-4.142,0-7.5,3.358-7.5,7.5c0,4.142,3.358,7.5,7.5,7.5H336v25h-81v-25h24.5c4.142,0,7.5-3.358,7.5-7.5 c0-4.142-3.358-7.5-7.5-7.5h-64c-4.142,0-7.5,3.358-7.5,7.5c0,4.142,3.358,7.5,7.5,7.5H240v25h-49V255h49v9.909 c-13.759,3.375-24,15.806-24,30.591c0,4.142,3.358,7.5,7.5,7.5H240v0.5c0,4.142,3.358,7.5,7.5,7.5c4.142,0,7.5-3.358,7.5-7.5V303 h16.5c4.142,0,7.5-3.358,7.5-7.5c0-14.785-10.241-27.216-24-30.591V255h81v9.909c-13.759,3.375-24,15.806-24,30.591 c0,4.142,3.358,7.5,7.5,7.5H336v0.5c0,4.142,3.358,7.5,7.5,7.5c4.142,0,7.5-3.358,7.5-7.5V303h16.5c4.142,0,7.5-3.358,7.5-7.5 c0-14.785-10.241-27.216-24-30.591V255h49V384z M247.5,279c6.4,0,11.959,3.662,14.695,9h-29.39 C235.54,282.662,241.1,279,247.5,279z M343.5,279c6.4,0,11.959,3.662,14.695,9h-29.39C331.54,282.662,337.1,279,343.5,279z">
                                                            </path>
                                                            <path
                                                                d="M127.5,343c4.142,0,7.5-3.358,7.5-7.5v-16c0-4.142-3.358-7.5-7.5-7.5c-4.142,0-7.5,3.358-7.5,7.5v16 C120,339.642,123.358,343,127.5,343z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                        <span class="ms-2 fs-4 fw-bolder">
                                            {{ Str::ucfirst($order->restaurant->name) }}
                                        </span>
                                        <i class="feather-arrow-down text-nowrap ms-auto">Tap</i>
                                    </button>
                                    {{-- Restaurant Name --}}
                                </h5>
                                <div id="collapseOne" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="d-lg-flex justify-content-between">
                                            <div class="fs-6">
                                                <b>Address :</b>
                                                @php($restaurantAddress = json_decode($order->restaurant->address))
                                                <div>
                                                    {{ isset($restaurantAddress->street) ? Str::ucfirst($restaurantAddress->street) : null }}
                                                    {{ isset($restaurantAddress->city) ? Str::ucfirst($restaurantAddress->city) : null }}
                                                    -
                                                    {{ isset($restaurantAddress->pincode) ? Str::ucfirst($restaurantAddress->pincode) : null }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="mt-2 mt-lg-0 d-flex justify-content-end">
                                                    {{-- <button
                                                    class="res-btn px-3 py-2 border-0 shadow-sm rounded-3 text-warning d-flex justify-content-center align-items-center me-2">
                                                    <div class="fs-4 me-2"><i class="fa-solid fa-message"></i></div>
                                                    <div class="fs-6 fw-bolder">CHAT</div>
                                                </button> --}}

                                                    <a id="restaurantMapSelector" href="javascript:void(0)"
                                                        class="res-btn px-3 py-2 border-0 shadow-sm rounded-3 text-warning d-flex justify-content-center align-items-center me-2">
                                                        <div class="fs-4 me-2"><i class="fa-solid fa-location-dot"></i>
                                                        </div>
                                                        <div class="fs-6 fw-bolder">MAP</div>
                                                    </a>
                                                    {{-- <a href="https://www.google.com/maps/dir/?api=1&destination={{$order->restaurant->latitude . ',' . $order->restaurant->longitude }}&travelmode=driving&maptype=satellite"
                                                    class="res-btn px-3 py-2 border-0 shadow-sm rounded-3 text-warning d-flex justify-content-center align-items-center me-2">
                                                    <div class="fs-4 me-2"><i class="fa-solid fa-location-dot"></i>
                                                    </div>
                                                    <div class="fs-6 fw-bolder">MAP</div>
                                                </a> --}}
                                                    <a href="tel:{{ $order->restaurant->phone }}"
                                                        class="res-btn px-3 py-2 border-0 shadow-sm rounded-3 text-warning d-flex justify-content-center align-items-center">
                                                        <div class="fs-4 me-2"><i class="fa-solid fa-phone"></i></div>
                                                        <div class="fs-6 fw-bolder">CALL</div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 px-2 text-warning fw-bolder text-center">Customer Detail</div>
                    @php($deliveryAddress = json_decode($order->delivery_address) ?? null)
                    <div class="py-4" style="background-color: #ffe8d3; border-radius: 0px 25px;">
                        <h5 class="address-bar-icon pe-4 ps-4 mb-3 text-white fw-bolder"> <i
                                class="feather-user fs-4 me-2"></i>
                            {{ Str::ucfirst($order->customer->f_name) . ' ' . Str::ucfirst($order->customer->l_name) }} ({{$order->customer->phone}})
                        </h5>
                        <div class="d-lg-flex justify-content-between px-4">
                            <div class="fs-6">
                                <b>Address :</b>
                                {{ isset($order->lovedOne) ? Str::ucfirst($order->lovedOne->name) . ' (' . $order->lovedOne->phone . ')' : '' }}
                                <div>
                                    {{ isset($deliveryAddress->stringAddress) ? $deliveryAddress->stringAddress : '' }}
                                </div>
                            </div>
                            <div>
                                <div class="mt-2 mt-lg-0 d-flex justify-content-end">
                                    {{-- <button
                                    class="bg-white px-3 py-2 border-0 shadow-sm fs-2 rounded-3 text-warning d-flex justify-content-center align-items-center me-2">
                                    <div class="fs-4 me-2"><i class="fa-solid fa-message"></i></div>
                                    <div class="fs-6 fw-bolder">CHAT</div>
                                </button> --}}
                                    <a id="customerMapSelector" href="javascript:void(0);="
                                        class="bg-white px-3 py-2 border-0 shadow-sm rounded-3 text-warning d-flex justify-content-center align-items-center me-2">
                                        <div class="fs-4 me-2"><i class="fa-solid fa-location-dot"></i></div>
                                        <div class="fs-6 fw-bolder">MAP</div>
                                    </a>
                                    {{-- <a href="https://www.google.com/maps/dir/?api=1&destination={{ $deliveryAddress->position->lat . ',' . $deliveryAddress->position->lon }}&travelmode=driving&maptype=satellite"
                                    class="bg-white px-3 py-2 border-0 shadow-sm rounded-3 text-warning d-flex justify-content-center align-items-center me-2">
                                    <div class="fs-4 me-2"><i class="fa-solid fa-location-dot"></i></div>
                                    <div class="fs-6 fw-bolder">MAP</div>
                                </a> --}}

                                    <a href="tel:@if (isset($deliveryAddress->contact_person_number)){{ $deliveryAddress->contact_person_number }}@elseif ($order->lovedOne){{ $order->lovedOne->phone }}@else{{ $order->customer->phone }}@endif"
                                        class="bg-white px-3 py-2 border-0 shadow-sm rounded-3 text-warning d-flex justify-content-center align-items-center">
                                        <div class="fs-4 me-2"><i class="fas fa-phone"></i></div>
                                        <div class="fs-6 fw-bolder">CALL</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="live-order-box mt-4">
                        <div class="live-order-details d-flex justify-content-between align-items-center">
                            <h6 class="text-center fw-bolder mb-0">Order Detail</h6>
                            <div class="px-2 py-1 rounded-3" style="background:#1b1b84;">
                                <div class="text-white"><i
                                        class="fa-solid fa-indian-rupee-sign me-2"></i>{{ Str::ucfirst($order->payment_method) }}
                                </div>
                            </div>
                        </div>
                        @foreach ($invoiceItemsList as $listItem)
                            <div class="live-order-details-box">
                                <div class="restaurant-fix position-relative mb-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="location-details">
                                            <div>
                                                <p class="fs-5 mb-0"><strong>{{ Str::ucfirst($listItem['name']) }}
                                                    </strong></p>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fs-6 text-muted">{{ $listItem['quantity'] }} Qty</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    {{-- <a class="btn btn-success btn-lg rounded-4 d-flex justify-content-around align-items-center mt-3"
                    href="javascript:();">
                    Swipe for Action
                </a> --}}

                </div>

                {{-- <div class="col-lg-8 map-box mb-5">
                <div class="commission-details shadow-sm"> --}}
                {{-- <h5 class="fw-bolder mb-0 text-warning text-center">Order ID : #{{$order->id}}</h5> --}}

                {{-- </div>
            </div> --}}
            </div>
        </div>
        <style>
            .fixed-bottom-container {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                max-width: 848px;
                margin: 0px auto 80px auto;
                width: 100%;
            }
        </style>
        <!-- Fixed Bottom Container -->
        <div class="fixed-bottom-container shadow-none  " style="z-index: 10;">
            @if ($order->accepted == null)
                <div class="order-btn p-0 d-flex justify-content-between">
                    <button class="decline" data-order-id="{{ $order->id }}"
                        data-acceptance="reject">Decline</button>
                    <button class="accept" data-order-id="{{ $order->id }}" data-acceptance="accept">Accept</button>
                </div>
            @endif

            @if ($order->accepted != null && $order->arrived_at_door == null)
                <div class="order-btn p-0">
                    @if ($order->picked_up == null)
                        <div class="slider-container" data-order-id="{{ $order->id }}" id="sliderOrderPickup">
                            <div class="progress-bar"></div>
                            <div class="slider-button"></div>
                            <div class="slider-text ps-4">Pick UP</div>
                        </div>
                    @endif
                    @if ($order->arrived_at_door == null && $order->picked_up != null)
                        <div class="slider-container" data-order-id="{{ $order->id }}" id="sliderArrivedAtDoor">
                            <div class="progress-bar" id="progress-bar"></div>
                            <div class="slider-button" id="slider-button"></div>
                            <div class="slider-text ps-4">Arrived</div>
                        </div>
                    @endif
                </div>
            @endif

            @if ($order->picked_up != null && $order->delivered == null)
                <div class="order-btn p-0 mt-2">
                    <div class="slider-container" data-order-id="{{ $order->id }}" id="sliderDliveryOrder">
                        <div class="progress-bar" id="progress-bar"></div>
                        <div class="slider-button" id="slider-button">→</div>
                        <div class="slider-text ps-4">
                            @if ($order->cash_to_collect != 0)
                                Collect & Deliver
                                {{ App\CentralLogics\Helpers::format_currency($order->order_amount) }}
                            @else
                                Delivered
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>


    </div>
    {{-- </div>
</div> --}}



    <!-- map offcavas -->
    <!-- Full Page Offcanvas -->
    <div class="offcanvas offcanvas-bottom fullscreen " tabindex="-1" id="offcanvasMap"
        aria-labelledby="offcanvasBottomLabel">

        <i class="position-absolute top-0 end-0 text-primary me-2 mt-2 feather-x-circle fs-2" style="z-index: 1;"
            data-bs-dismiss="offcanvas"></i>
        <div class="offcanvas-body m-0 p-0 ">
            <div class="mapouter m-0 p-0">
                <div id="map-canvas2" style=" width:100%;height: 100vh"></div>
            </div>
        </div>
    </div>
@endsection


@push('javascript')
    <script src="{{ asset('assets/js/Helpers/mapHelperClass.js') }}"></script>
    <script>
        async function OrderAcceptOrReject() {
            // console.log(document.querySelectorAll('[data-accepetance]'))
            document.querySelectorAll('[data-acceptance]').forEach(element => {
                element.addEventListener('click', () => {
                    Swal.fire({
                        title: `Do you want to ${element.dataset.acceptance} the Order?`,
                        showDenyButton: true,
                        confirmButtonText: element.dataset.acceptance.toUpperCase(),
                        denyButtonText: 'Cancel',
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                location.href =
                                    '{{ route('deliveryman.admin.order-confirmation') }}?order_id=' +
                                    element.dataset.orderId + "&status=" + element.dataset
                                    .acceptance;
                                // const resp = await fetch('{{ route('deliveryman.admin.order-confirmation') }}?order_id=' + element.dataset.orderId + "&status=" + element.dataset.acceptance);
                                // const resp = await fetch('{{ route('deliveryman.admin.order-confirmation') }}?order_id=' + element.dataset.orderId + "&status=" + element.dataset.acceptance);
                                return;
                                if (!resp.ok) {
                                    const error = await resp.json();
                                    throw new Error(error.message);
                                } else {
                                    const data = await resp.json();
                                    Swal.fire('Saved!', data.message, 'success');
                                    setTimeout(() => {
                                        location.reload();
                                    }, 3000);
                                }

                            } catch (error) {
                                console.error('Error fetching data:', error);
                                Swal.fire('Error', error.message, 'error');
                            }

                        }
                    });
                })
            });
        }
        document.addEventListener("DOMContentLoaded", OrderAcceptOrReject)
    </script>
    <script>
        async function getTravelTimeByCoordinates(originLat, originLng, destinationLat, destinationLng) {
            const apiKey = 'AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk'; // Replace with your Google Maps API key

            // Construct the request URL using latitude and longitude
            const url =
                `{{ route('get-directions') }}?origin=${originLat},${originLng}&destination=${destinationLat},${destinationLng}&key=${apiKey}`;
            try {
                const response = await fetch(url, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json"
                    },
                });
                const data = await response.json();
                console.log(data); // Log the entire response to debug
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        }

        async function getTravelTime() {
            const originLat = {{ $dmPosition['lat'] }};
            const originLng = {{ $dmPosition['lon'] }};
            const destinationLat = {{ $userPosition['lat'] }};
            const destinationLng = {{ $userPosition['lon'] }};

            const res = await getTravelTimeByCoordinates(originLat, originLng, destinationLat, destinationLng);
            console.log(res); // Now res will contain the response data after the fetch has completed
        }

        // getTravelTime();
    </script>

    {{-- Real-time Timer Updates --}}
    <script>
        class DeliveryTimer {
            constructor() {
                this.restaurantETA = @json($restaurantReachOutTimer ?? null);
                this.customerETA = @json($customerReachOutTimer ?? null);
                this.orderPickedUp = {{ $order->picked_up ? 'true' : 'false' }};
                this.orderDelivered = {{ $order->delivered ? 'true' : 'false' }};
                this.notificationShown = { restaurant: false, customer: false };
                this.audioContext = null;
                this.initAudio();
                this.startTimer();
            }

            initAudio() {
                // Initialize audio context for notification sounds
                try {
                    this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
                } catch (e) {
                    console.warn('Audio context not available');
                }
            }

            playNotificationSound(type = 'warning') {
                if (!this.audioContext) return;

                const oscillator = this.audioContext.createOscillator();
                const gainNode = this.audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(this.audioContext.destination);

                if (type === 'warning') {
                    oscillator.frequency.setValueAtTime(800, this.audioContext.currentTime);
                    oscillator.frequency.exponentialRampToValueAtTime(400, this.audioContext.currentTime + 0.1);
                } else if (type === 'danger') {
                    oscillator.frequency.setValueAtTime(1200, this.audioContext.currentTime);
                    oscillator.frequency.exponentialRampToValueAtTime(600, this.audioContext.currentTime + 0.1);
                }

                gainNode.gain.setValueAtTime(0.3, this.audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, this.audioContext.currentTime + 0.2);

                oscillator.start(this.audioContext.currentTime);
                oscillator.stop(this.audioContext.currentTime + 0.2);
            }

            startTimer() {
                // Update every 30 seconds
                setInterval(() => {
                    this.updateTimers();
                }, 30000);

                // Refresh timers from server every 5 minutes
                setInterval(() => {
                    this.refreshTimersFromServer();
                }, 300000);
            }

            async refreshTimersFromServer() {
                try {
                    const response = await fetch(`{{ route('deliveryman.admin.get-updated-timers') }}?order_id={{ $order->id }}`);
                    const data = await response.json();
                    
                    if (data.success) {
                        if (data.restaurantTimer && !this.orderPickedUp) {
                            this.restaurantETA = data.restaurantTimer;
                        }
                        if (data.customerTimer && !this.orderDelivered) {
                            this.customerETA = data.customerTimer;
                        }
                    }
                } catch (error) {
                    console.error('Failed to refresh timers:', error);
                }
            }

            updateTimers() {
                const now = new Date();
                
                // Update Restaurant ETA if not picked up yet
                if (this.restaurantETA && !this.orderPickedUp) {
                    const restaurantETATime = new Date(this.restaurantETA.eta_time);
                    const remainingMinutes = Math.max(0, (restaurantETATime - now) / (1000 * 60));
                    
                    this.updateTimerDisplay('restaurant', remainingMinutes, restaurantETATime);
                    
                    // Show notification if running late
                    if (remainingMinutes <= 0 && !this.notificationShown.restaurant) {
                        this.showLateNotification('restaurant');
                        this.notificationShown.restaurant = true;
                    } else if (remainingMinutes <= 2 && remainingMinutes > 0 && !this.notificationShown.restaurant) {
                        this.showUrgentNotification('restaurant', remainingMinutes);
                        this.notificationShown.restaurant = true;
                    }
                }

                // Update Customer ETA if not delivered yet
                if (this.customerETA && !this.orderDelivered) {
                    const customerETATime = new Date(this.customerETA.eta_time);
                    const remainingMinutes = Math.max(0, (customerETATime - now) / (1000 * 60));
                    
                    this.updateTimerDisplay('customer', remainingMinutes, customerETATime);
                    
                    // Show notification if running late
                    if (remainingMinutes <= 0 && !this.notificationShown.customer) {
                        this.showLateNotification('customer');
                        this.notificationShown.customer = true;
                    } else if (remainingMinutes <= 5 && remainingMinutes > 0 && !this.notificationShown.customer) {
                        this.showUrgentNotification('customer', remainingMinutes);
                        this.notificationShown.customer = true;
                    }
                }
            }

            updateTimerDisplay(type, remainingMinutes, etaTime) {
                const timerElement = document.querySelector(`[data-timer="${type}"]`);
                const timeElement = document.querySelector(`[data-time="${type}"]`);
                const progressElement = document.querySelector(`[data-progress="${type}"]`);
                const miniTimerElement = document.querySelector(`[data-timer-mini="${type}"]`);
                const statusIndicator = document.querySelector(`[data-status-indicator="${type}"]`);
                const deliverWithinElement = document.querySelector(`[data-deliver-within="${type}"]`);
                
                if (timerElement) {
                    if (remainingMinutes > 0) {
                        timerElement.textContent = `${remainingMinutes.toFixed(1)} minutes`;
                        if (remainingMinutes <= 2) {
                            timerElement.classList.add('text-warning');
                            timerElement.classList.remove('text-success', 'text-primary');
                        } else if (remainingMinutes <= 5) {
                            timerElement.classList.add('text-success');
                            timerElement.classList.remove('text-warning', 'text-primary');
                        }
                    } else {
                        timerElement.textContent = 'Overdue';
                        timerElement.classList.add('text-danger');
                        timerElement.classList.remove('text-warning', 'text-success', 'text-primary');
                    }
                }

                // Update mini timer in commission section
                if (miniTimerElement) {
                    if (remainingMinutes > 0) {
                        miniTimerElement.textContent = `${remainingMinutes.toFixed(1)} min`;
                        if (remainingMinutes <= 2) {
                            miniTimerElement.classList.add('text-warning');
                            miniTimerElement.classList.remove('text-success', 'text-primary');
                        } else if (remainingMinutes <= 5) {
                            miniTimerElement.classList.add('text-success');
                            miniTimerElement.classList.remove('text-warning', 'text-primary');
                        }
                    } else {
                        miniTimerElement.textContent = 'Late';
                        miniTimerElement.classList.add('text-danger');
                        miniTimerElement.classList.remove('text-warning', 'text-success', 'text-primary');
                    }
                }

                if (timeElement) {
                    timeElement.textContent = etaTime.toLocaleTimeString('en-US', { 
                        hour: 'numeric', 
                        minute: '2-digit' 
                    });
                }

                // Update progress bar
                if (progressElement) {
                    const originalETA = type === 'restaurant' ? this.restaurantETA : this.customerETA;
                    if (originalETA) {
                        const totalMinutes = originalETA.eta_minutes;
                        const elapsed = totalMinutes - remainingMinutes;
                        const progressPercent = Math.min(100, Math.max(0, (elapsed / totalMinutes) * 100));
                        
                        progressElement.style.width = `${progressPercent}%`;
                        
                        // Change progress bar color based on urgency
                        if (remainingMinutes <= 2) {
                            progressElement.classList.remove('bg-primary', 'bg-success');
                            progressElement.classList.add('bg-warning');
                        } else if (remainingMinutes <= 5) {
                            progressElement.classList.remove('bg-primary', 'bg-warning');
                            progressElement.classList.add('bg-success');
                        }
                    }
                }

                // Update status indicator
                if (statusIndicator) {
                    statusIndicator.classList.remove('warning', 'danger');
                    if (remainingMinutes <= 0) {
                        statusIndicator.classList.add('danger');
                    } else if (remainingMinutes <= 2) {
                        statusIndicator.classList.add('warning');
                    }
                }

                // Update "Deliver Within" section for customer timer
                if (deliverWithinElement && type === 'customer') {
                    if (remainingMinutes > 0) {
                        deliverWithinElement.textContent = `${Math.ceil(remainingMinutes)} Min`;
                        deliverWithinElement.classList.remove('text-danger');
                        if (remainingMinutes <= 3) {
                            deliverWithinElement.classList.add('text-danger');
                            deliverWithinElement.classList.remove('text-warning');
                        } else if (remainingMinutes <= 8) {
                            deliverWithinElement.classList.add('text-warning');
                            deliverWithinElement.classList.remove('text-danger');
                        } else {
                            deliverWithinElement.classList.add('text-warning');
                            deliverWithinElement.classList.remove('text-danger');
                        }
                    } else {
                        deliverWithinElement.textContent = 'Late!';
                        deliverWithinElement.classList.add('text-danger');
                        deliverWithinElement.classList.remove('text-warning');
                    }
                }
            }

            showLateNotification(type) {
                const title = type === 'restaurant' ? 'Restaurant ETA Overdue!' : 'Customer ETA Overdue!';
                const message = type === 'restaurant' ? 
                    'You should have reached the restaurant by now.' : 
                    'You should have delivered the order by now.';
                
                // Play danger sound
                this.playNotificationSound('danger');
                
                // Add visual animation
                const timerElement = document.querySelector(`[data-timer="${type}"]`);
                if (timerElement) {
                    timerElement.parentElement.classList.add('timer-danger');
                }

                if (typeof toastr !== 'undefined') {
                    toastr.error(message, title, {
                        positionClass: 'toast-top-center',
                        timeOut: 0,
                        closeButton: true
                    });
                } else {
                    alert(`${title}\n${message}`);
                }
            }

            showUrgentNotification(type, remainingMinutes) {
                const title = type === 'restaurant' ? 'Urgent: Restaurant ETA' : 'Urgent: Customer ETA';
                const message = `Only ${remainingMinutes.toFixed(1)} minutes remaining!`;
                
                // Play warning sound
                this.playNotificationSound('warning');
                
                // Add visual animation
                const timerElement = document.querySelector(`[data-timer="${type}"]`);
                if (timerElement) {
                    timerElement.parentElement.classList.add('timer-warning');
                }

                if (typeof toastr !== 'undefined') {
                    toastr.warning(message, title, {
                        positionClass: 'toast-top-center',
                        timeOut: 5000,
                        closeButton: true
                    });
                } else {
                    alert(`${title}\n${message}`);
                }
            }

            // Method to update when order status changes
            updateOrderStatus(pickedUp = false, delivered = false) {
                this.orderPickedUp = pickedUp;
                this.orderDelivered = delivered;
                
                // Reset notifications when status changes
                if (pickedUp) {
                    this.notificationShown.restaurant = false;
                }
                if (delivered) {
                    this.notificationShown.customer = false;
                }
            }
        }

        // Initialize timer when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const deliveryTimer = new DeliveryTimer();
            
            // Store reference globally for status updates
            window.deliveryTimer = deliveryTimer;

            // Watch for significant location changes
            if (navigator.geolocation) {
                let lastPosition = null;
                
                navigator.geolocation.watchPosition((position) => {
                    const currentPosition = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    if (lastPosition) {
                        // Calculate distance moved (in km)
                        const distance = haversineDistance(lastPosition, currentPosition);
                        
                        // If moved more than 200m, refresh timers
                        if (distance > 0.2) {
                            deliveryTimer.refreshTimersFromServer();
                            lastPosition = currentPosition;
                        }
                    } else {
                        lastPosition = currentPosition;
                    }
                }, (error) => {
                    console.warn('Geolocation error:', error.message);
                }, {
                    enableHighAccuracy: true,
                    maximumAge: 30000,
                    timeout: 15000
                });
            }
        });

        // Haversine distance calculation
        function haversineDistance(pos1, pos2) {
            const R = 6371; // Earth's radius in km
            const dLat = (pos2.lat - pos1.lat) * Math.PI / 180;
            const dLng = (pos2.lng - pos1.lng) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(pos1.lat * Math.PI / 180) * Math.cos(pos2.lat * Math.PI / 180) *
                    Math.sin(dLng/2) * Math.sin(dLng/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }
    </script>

    <script>
        function showMaptocustomerAndRestaurant() {
            const map2 = new CreateMap();
            var mapCanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasMap'))
            const customerMapSelector = document.getElementById('customerMapSelector');
            const restaurantMapSelector = document.getElementById('restaurantMapSelector');

            // let dmMarker = null;
            let routeRenderer = null;
            let dmPosition = null;

            const restaurantPosition = {
                lat: {{ $order->restaurant->latitude }},
                lng: {{ $order->restaurant->longitude }}
            };

            const customerPosition = {
                lat: {{ $deliveryAddress->position->lat }},
                lng: {{ $deliveryAddress->position->lon }}
            };

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    dmPosition = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    // Initialize map centered at the delivery man's current position
                    map2.createMap(dmPosition, {
                        selector: "#map-canvas2",
                        mapClick: false,
                        mapDrag: false,
                        styles: [{
                                elementType: 'geometry',
                                stylers: [{
                                    color: '#ebe3cd'
                                }]
                            },
                            {
                                elementType: 'labels.text.fill',
                                stylers: [{
                                    color: '#523735'
                                }]
                            },
                            {
                                elementType: 'labels.text.stroke',
                                stylers: [{
                                    color: '#f5f1e6'
                                }]
                            },
                            {
                                featureType: 'water',
                                elementType: 'geometry',
                                stylers: [{
                                    color: '#c9c9c9'
                                }]
                            }
                        ]
                    });

                    // Mark restaurant and customer on the map
                    const restaurantMarker = map2.makeMarker(restaurantPosition,
                        "{{ asset('assets/user/img/icons/restaurant-map-icon.png') }}", false);
                    const customerMarker = map2.makeMarker(customerPosition,
                        "{{ asset('assets/user/img/icons/marker-icon.png') }}", false);
                    const dmMarker = map2.makeMarker(dmPosition,
                        "{{ asset('assets/user/img/icons/deliveryman-map-icon.png') }}", false);

                    const directionsService = new google.maps.DirectionsService();
                    routeRenderer = new google.maps.DirectionsRenderer({
                        suppressMarkers: true,
                        polylineOptions: {
                            strokeColor: '#0000FF',
                            strokeOpacity: 1.0,
                            strokeWeight: 6
                        }
                    });
                    routeRenderer.setMap(map2.map);

                    // Function to update delivery man's position
                    function updateDeliveryManPosition(newPosition) {
                        if (dmMarker) {
                            dmMarker.setPosition(newPosition);
                            dmMarker.setIcon({
                                url: dmMarker.getIcon().url,
                                scaledSize: new google.maps.Size(60, 60),
                            });
                        }
                        // else {
                        //     dmMarker = map2.makeMarker(newPosition, "{{ asset('assets/user/img/icons/deliveryman-map-icon.png') }}", false);
                        // }
                        dmPosition = newPosition;
                    }

                    // Function to calculate route between two points and display distance/time
                    function calculateRoute(origin, destination, routeColor = '#0000FF') {
                        console.log("Calculating route from:", origin, "to:", destination); // Debugging line

                        directionsService.route({
                            origin: origin,
                            destination: destination,
                            travelMode: google.maps.TravelMode.DRIVING
                        }, (response, status) => {
                            if (status === google.maps.DirectionsStatus.OK) {
                                routeRenderer.setDirections(response);
                                routeRenderer.setOptions({
                                    polylineOptions: {
                                        strokeColor: routeColor,
                                        strokeWeight: 6
                                    }
                                });

                                // Extract and display travel time and distance
                                const travelTime = response.routes[0].legs[0].duration.text;
                                const distance = response.routes[0].legs[0].distance.text;

                                const infoWindow = new google.maps.InfoWindow({
                                    content: `<div style="text-align: center;">
                                                                                            <b>Time: ${travelTime}</b><br>
                                                                                            <b>Distance: ${distance}</b>
                                                                                        </div>`
                                });
                                infoWindow.setPosition({
                                    lat: (origin.lat + destination.lat) / 2,
                                    lng: (origin.lng + destination.lng) / 2
                                });
                                infoWindow.open(map2.map);
                            } else {
                                console.error('Directions request failed due to: ' + status);
                            }
                        });
                    }


                    // Event listeners for route toggle buttons
                    customerMapSelector.addEventListener('click', function() {
                        routeRenderer.setOptions({
                            polylineOptions: {
                                strokeColor: '#0000FF'
                            }
                        });
                        calculateRoute(dmPosition, customerPosition, '#0000FF');
                        mapCanvas.show();
                    });

                    restaurantMapSelector.addEventListener('click', function() {
                        // toastr.success('Restaurant Map');
                        routeRenderer.setOptions({
                            polylineOptions: {
                                strokeColor: '#FF0000'
                            }
                        });
                        calculateRoute(dmPosition, restaurantPosition, '#FF0000');
                        mapCanvas.show();
                    });

                    // Watch delivery man position updates
                    navigator.geolocation.watchPosition((position) => {
                        const newPosition = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        updateDeliveryManPosition(newPosition);
                        // Update route to restaurant or customer based on selected map
                        // if (customerMapSelector.classList.contains('active')) {
                        //     calculateRoute(newPosition, customerPosition, '#0000FF');
                        // } else {
                        calculateRoute(newPosition, restaurantPosition, '#FF0000');
                        // }
                    }, (error) => {
                        console.error('Geolocation error:', error.message);
                    });
                }, (error) => {
                    console.error('Geolocation error:', error.message);
                });
            } else {
                console.error('Geolocation is not supported by this browser.');
            }
        }
        document.addEventListener('DOMContentLoaded', showMaptocustomerAndRestaurant);
    </script>
    <script>
        class Slider {
            constructor(sliderContainer, options = {}) {
                this.sliderContainer = sliderContainer;
                if (sliderContainer == null)
                    return true;
                this.sliderButton = sliderContainer.querySelector('.slider-button');
                this.progressBar = sliderContainer.querySelector('.progress-bar');
                this.sliderText = sliderContainer.querySelector('.slider-text');
                this.sliderTextContent = this.sliderText.textContent;
                this.successText = options.successText || 'Confirmed!';
                this.sliderContainerWidth = sliderContainer.offsetWidth;
                this.buttonWidth = this.sliderButton.offsetWidth;
                this.isSliding = false;
                this.startX = 0;
                this.callback = options.callback || null; // Default to null if no callback is provided

                this.init();
            }

            init() {
                // Add event listeners
                this.sliderButton.addEventListener('mousedown', this.startSlide.bind(this));
                this.sliderButton.addEventListener('touchstart', this.startSlide.bind(this));

                this.progressBar.addEventListener('mousedown', this.startSlide.bind(this));
                this.progressBar.addEventListener('touchstart', this.startSlide.bind(this));

                document.addEventListener('mousemove', this.slide.bind(this));
                document.addEventListener('touchmove', this.slide.bind(this));

                document.addEventListener('mouseup', this.endSlide.bind(this));
                document.addEventListener('touchend', this.endSlide.bind(this));
            }

            startSlide(e) {
                this.isSliding = true;
                this.startX = (e.touches ? e.touches[0].clientX : e.clientX) - this.sliderButton.offsetLeft;
            }

            slide(e) {
                if (!this.isSliding) return;

                let newX = (e.touches ? e.touches[0].clientX : e.clientX) - this.startX;
                newX = Math.max(0, Math.min(newX, this.sliderContainerWidth - this.buttonWidth));

                this.sliderButton.style.left = `${newX}px`;
                this.progressBar.style.width = `${(newX / (this.sliderContainerWidth - this.buttonWidth)) * 100}%`;
            }

            endSlide() {
                if (!this.isSliding) return;
                this.isSliding = false;
                const currentLeft = this.sliderButton.offsetLeft;
                const halfWay = (this.sliderContainerWidth - this.buttonWidth) / 2;

                // Check if fully slid
                if (currentLeft >= halfWay) {
                    this.sliderContainer.classList.add('slider-success');
                    this.sliderText.textContent = this.successText;
                    this.callback();
                    // this.deliveryConfirm(this.sliderContainer.dataset.orderId); // Pass the order ID dynamically
                } else {
                    this.resetSlider();
                }
            }

            resetSlider() {
                this.sliderButton.style.left = '0';
                this.progressBar.style.width = '0';
                this.sliderContainer.classList.remove('slider-success');
                this.sliderText.textContent = this.sliderTextContent;
            }


        }

        // sliderOrderPickup

        const sliderOrderPickup = new Slider(document.getElementById('sliderOrderPickup') || null, {
            successText: "Order Picked",
            callback: () => orderpickUp(),
        });

        // Arrived at door
        const sliderArrivedAtDoor = new Slider(document.getElementById('sliderArrivedAtDoor') || null, {
            successText: "Order Picked",
            callback: () => arrivedAtDoor()
        });

        // sliderDliveryOrder
        const sliderDeliveryOrder = new Slider(
            document.getElementById('sliderDliveryOrder') || null, {
                successText: "Order Picked",
                callback: () => {
                    const orderId = document.getElementById('sliderDliveryOrder')?.dataset.orderId;
                    deliveryConfirm(orderId, sliderDeliveryOrder);
                }
            }
        );

        function orderpickUp() {
            // Update timer status
            if (window.deliveryTimer) {
                window.deliveryTimer.updateOrderStatus(true, false);
            }
            location.href = "{!! route('deliveryman.admin.order-stage-changer') . '?order_id=' . $order->id . '&stage=picked_up' !!}";
        }

        function arrivedAtDoor() {
            location.href = "{!! route('deliveryman.admin.order-stage-changer') . '?order_id=' . $order->id . '&stage=arrived_at_door' !!}";
        }

        // document.querySelectorAll('.slider-container').forEach((sliderContainer) => {
        //     const sl = new Slider(sliderContainer, {
        //         successText : "thankd ltkd",
        //         callback: () => deliveryConfirm(25454)
        //     });
        //     // @overide
        //     // sl.endSlide()
        //     console.log(sl);

        // });

        async function deliveryConfirm(orderId, slider) {
            const verify = {{ $zone?->delivery_verification == 1 ? 'true' : 'false' }};

            if (!verify) {
                location.href = `{!! route('deliveryman.admin.order-deliver') !!}?order_id=${orderId}`;
            } else {

                try {
                    const {
                        value: otp
                    } = await Swal.fire({
                        title: 'Enter Delivery OTP',
                        input: 'text',
                        inputAttributes: {
                            maxlength: 6,
                            autocapitalize: 'off',
                            autocorrect: 'off',
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'You need to enter an OTP!';
                            } else if (!/^\d{6}$/.test(value)) {
                                return 'Please enter a valid 6-digit OTP!';
                            }
                        },
                    });
                    if (otp) {
                        const response = await fetch(
                            `{{ route('deliveryman.admin.order-varify-qr') }}?otp=${otp}&order_id=${orderId}`);
                        const resultData = await response.json();

                        if (resultData.success) {
                            Swal.fire({
                                title: resultData.success,
                                icon: 'success',
                                timer: 1000
                            });
                            if (resultData.link) {
                                location.href = resultData.link;
                            }
                        } else {
                            throw new Error(resultData.error || 'Verification failed. Please try again.');
                        }
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: error.message || 'An unexpected error occurred.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    slider.resetSlider();
                }
            }
        }
    </script>
@endpush
