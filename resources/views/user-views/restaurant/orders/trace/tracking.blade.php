@php
    $customerOrderData = json_decode($order->orderCalculationStmt?->customerData);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOODYARI Order Tracking</title>
    <!-- Load Tailwind CSS -->

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <!-- Google Maps API -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&libraries=geometry,places"></script>
    <style>
        /* Custom styles for aesthetic enhancements */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f9;
        }
        /* Animation for status pulse */
        @keyframes pulse-ring {
            0% { transform: scale(0.33); }
            80%, 100% { opacity: 0; }
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .status-pulse {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .status-pulse-ring {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #059669; /* Green color */
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #059669; /* Green color */
            animation: pulse-dot 2s ease-in-out infinite;
        }
        /* Custom styles for smooth transitions on collapsible content */
        .collapsible-content {
            transition: max-height 0.4s ease-in-out, padding 0.4s ease-in-out;
        }
        /* Live Indicator Animation */
        .live-indicator {
            animation: pulse-dot 1.5s infinite;
        }
        /* Modal Backdrop */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 50;
        }
    </style>
    <meta name="order_location" content="{{$order->delivery_address}}">
</head>
<body class="min-h-screen">
    <!-- Fixed Header -->
    <header class="sticky top-0 bg-white p-4 shadow-md z-10">
        <div class="flex justify-between items-center max-w-lg mx-auto">
            <button onclick="window.location.href='{{ route('user.dashboard') }}'" class="text-gray-600 hover:text-[#FF6600]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <h1 class="text-xl font-extrabold text-[#FF6600]">FOODYARI</h1>
            <div class="text-xs font-semibold text-gray-500">
                Order <span id="order-id">#{{ $order->id }}</span>
            </div>
        </div>
    </header>
    <!-- Main Content Area -->
    <main class="p-4 pt-0 pb-20 max-w-lg mx-auto">
        
        <!-- Real-time Status & ETA Card -->
        <div id="eta-card" class="bg-[#FF6600] text-white p-4 rounded-xl shadow-lg mt-4 mb-4 text-center">
            <h2 id="main-status-text" class="text-xl font-extrabold mb-2 min-h-[56px] flex items-center justify-center">
                @if($order->order_status == 'pending')
                    Pet pooja ka safar shuru ho gaya hai!
                @elseif($order->order_status == 'confirmed')
                    Rasoi mein mehnat jaari! Khushbu aane lagi hai...
                @elseif($order->order_status == 'processing')
                    Taaza aur Garam! Aapka order pickup ke liye ready hai.
                @elseif($order->order_status == 'handover')
                    Khushiyon ki delivery<br>ab bas raste mein hai!
                @elseif($order->order_status == 'picked_up')
                    Garma-garam khaana bas kuch hi pal door!
                @elseif($order->order_status == 'delivered')
                    Safar Poora Hua! Ab bas khaane ka maza lijiye. 🍽️
                @else
                    Aapka order tracking mein hai!
                @endif
            </h2>
            @if(isset($deliveryData) && $order->order_status != 'delivered')
            <div class="flex items-center justify-center border-t border-white/30 pt-3">
                <span class="text-sm font-semibold flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span id="eta-text-label">
                        @if($order->order_status == 'delivered')
                            Delivered by {{ $order->delivery_man ? Str::ucfirst($order->delivery_man->f_name) : 'Delivery Person' }}
                        @else
                            Estimated Arrival:
                        @endif
                    </span>
                    @if($order->order_status != 'delivered')
                        <span id="live-update-indicator" class="ml-2 w-2 h-2 rounded-full bg-white live-indicator"></span>
                    @endif
                </span>
                <span class="text-3xl font-extrabold ml-3 tabular-nums tracking-wider" id="delivery-timer">
                    @if($order->order_status == 'delivered')
                        Enjoy!
                    @elseif($deliveryData && isset($deliveryData['eta_minutes']))
                        {{ floor($deliveryData['eta_minutes']) }}:{{ str_pad(floor(($deliveryData['eta_minutes'] - floor($deliveryData['eta_minutes'])) * 60), 2, '0', STR_PAD_LEFT) }}
                    @else
                        25:00
                    @endif
                </span>
            </div>
            @endif
            <p class="text-xs mt-2 opacity-80" id="restaurant-name">From {{ Str::ucfirst($order->restaurant->name) }}</p>
        </div>
        <!-- Handover OTP Card -->
        @if($order->picked_up != null && ($order->delivered == null && $order->canceled == null) && $order->otp)
        <div id="otp-card" onclick="openOtpModal()" class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded-xl shadow-md mb-6 cursor-pointer hover:bg-yellow-200 transition-colors">
            <div class="flex items-center justify-between">
                <span class="font-bold text-lg flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9V7a2 2 0 00-2-2H8a2 2 0 00-2 2v2"></path></svg>
                    Handover OTP
                </span>
                <div class="flex items-center space-x-3">
                     <span class="text-xl font-extrabold text-yellow-700 tracking-wider">
                        {{ $order->otp }}
                    </span>
                    <button onclick="event.stopPropagation(); openOtpModal();" class="text-sm text-yellow-700 font-semibold underline hover:no-underline">
                        View QR
                    </button>
                </div>
            </div>
            <p class="text-sm mt-1 opacity-90">
                Aapka khaana aapke darwaze par hai. Jaldi milte hain!
            </p>
        </div>
        @endif
        <!-- Delivery Instructions Card -->
        @php $deliverymanInstructions = json_decode($order->delivery_instruction, true) ?? null ; @endphp
        @if(is_array($deliverymanInstructions) && count($deliverymanInstructions) > 0)
        <div id="notes-card" class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-xl shadow-md mb-6">
            <h2 class="text-lg font-bold text-blue-800 mb-2 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M15.5 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.5L15.5 3Z"/><path d="M15 3v6h6"/></svg>
                Delivery Instructions
            </h2>
            {!! implode('', array_map(fn($instruction) => "<p class='text-base text-blue-900 font-medium'>{$instruction}</p>", $deliverymanInstructions)) !!}
        </div>
        @endif

        <!-- Delivery Timing Information Card -->
        @if($deliveryData || $etaInfo || $dmLastLocation)
        <div class="bg-gradient-to-r from-green-50 to-blue-50 p-4 rounded-xl shadow-md mb-6 border border-green-200">
            <h2 class="text-lg font-bold text-gray-700 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Delivery Timeline
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($deliveryData)
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <h3 class="text-sm font-semibold text-green-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Delivery ETA
                    </h3>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">
                            {{ floor($deliveryData['eta_minutes']) }} mins
                        </div>
                        <div class="text-xs text-gray-500">
                            Expected by {{ \Carbon\Carbon::parse($deliveryData['eta_time'])->format('h:i A') }}
                        </div>
                    </div>
                    @if(isset($deliveryData['factors']))
                    <div class="mt-2 text-xs text-gray-600">
                        {{-- <div class="flex justify-between">
                            <span>Traffic Factor:</span>
                            <span class="font-medium">{{ $deliveryData['factors']['traffic'] }}x</span>
                        </div> --}}
                        @if($deliveryData['factors']['rain'] > 1)
                        <div class="flex justify-between text-blue-600">
                            <span>Rain Factor:</span>
                            <span class="font-medium">{{ $deliveryData['factors']['rain'] }}x</span>
                        </div>
                        @endif
                        @if($deliveryData['factors']['night'] > 1)
                        <div class="flex justify-between text-purple-600">
                            <span>Night Factor:</span>
                            <span class="font-medium">{{ $deliveryData['factors']['night'] }}x</span>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
                @endif

                @if($deliveryData && !$order->picked_up)
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <h3 class="text-sm font-semibold text-blue-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        Driver ETA to Restaurant
                    </h3>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">
                            {{-- @dd($processingInfo) --}}
                            {{ floor($deliveryData['breakdown']['driver_to_restaurant']) }} mins
                        </div>
                        <div class="text-xs text-gray-500">
                            Pickup expected by {{ \Carbon\Carbon::parse($deliveryData['breakdown']['pickup_eta_time'])->format('h:i A') }}
                        </div>
                    </div>
                </div>
                @endif

                @if($dmLastLocation && false)
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <h3 class="text-sm font-semibold text-orange-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Driver Location
                    </h3>
                    <div class="text-center">
                        <div class="text-sm font-medium text-gray-700">
                            {{ $dmLastLocation->currentLocation->lat ?? 'N/A' }}, {{ $dmLastLocation->currentLocation->lng ?? 'N/A' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            Last updated: {{ $dmLastLocation->currentLocation->timestamp ?? 'Unknown' }}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            @if($processingInfo['is_processing'])
            <div class="mt-4 bg-white p-3 rounded-lg shadow-sm" id="cooking-progress-card">
                <h3 class="text-sm font-semibold text-purple-700 mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path></svg>
                    <span id="cooking-status-text">
                        @if($processingInfo['status'] == 'ready')
                            Order Ready for Pickup
                        @elseif($processingInfo['status'] == 'overdue')
                            Cooking Overdue
                        @elseif($processingInfo['status'] == 'preparing')
                            Preparing to Cook
                        @else
                            Cooking in Progress
                        @endif
                    </span>
                    <span class="ml-2 w-2 h-2 rounded-full animate-pulse 
                        @if($processingInfo['status'] == 'ready') bg-green-500
                        @elseif($processingInfo['status'] == 'overdue') bg-red-500
                        @else bg-purple-500 @endif"></span>
                </h3>
                <div class="flex justify-between text-sm mb-1">
                    <span>Progress</span>
                    <span class="font-medium" id="cooking-percentage">{{ round($processingInfo['completion_percentage']) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 mb-2 overflow-hidden">
                    <div id="cooking-progress-bar" class="h-3 rounded-full transition-all duration-1000 ease-out
                        @if($processingInfo['status'] == 'ready') bg-gradient-to-r from-green-500 to-green-600
                        @elseif($processingInfo['status'] == 'overdue') bg-gradient-to-r from-red-500 to-red-600
                        @else bg-gradient-to-r from-purple-500 to-purple-600 @endif
                        relative overflow-hidden" 
                         style="width: {{ $processingInfo['completion_percentage'] }}%">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-30 animate-pulse"></div>
                    </div>
                </div>
                <div class="flex justify-between text-xs text-gray-600">
                    <span>Elapsed: <span id="elapsed-time">{{ $processingInfo['elapsed_time'] }}</span>min</span>
                    <span class="text-{{ $processingInfo['status'] == 'overdue' ? 'red' : 'purple' }}-600">
                        Remaining: <span id="remaining-time">{{ $processingInfo['remaining_time'] }}</span>min
                    </span>
                </div>
                @if($processingInfo['extra_cooking_time'] > 0)
                <div class="mt-2 p-2 bg-blue-50 rounded-md border-l-4 border-blue-400">
                    <div class="text-xs text-blue-700 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Extra cooking time added: {{ $processingInfo['extra_cooking_time'] }}min
                    </div>
                </div>
                @endif
                
                @if($processingInfo['estimated_completion'])
                <div class="mt-2 text-xs text-gray-500 text-center">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Expected completion: {{ \Carbon\Carbon::parse($processingInfo['estimated_completion'])->format('h:i A') }}
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif

        <!-- Map Section (Only if order is not delivered) -->
        @if($order->order_status != "delivered")
        <div class="bg-white p-4 rounded-xl shadow-md mb-6">
            <div id="map-canvas2" style="width:100%;height: 50vh" class="rounded-lg"></div>
        </div>
        @endif

        <!-- Delivery Agent Card -->
        @if ($order->delivery_man)
        <?php 
        $deliveryMan = $order->delivery_man;
        $orderDeliveriesCount = \App\Models\Order::where('delivery_man_id', $deliveryMan->id)
                                    ->where('order_status', 'delivered')
                                    ->select('id')
                                    ->count();
        $dmRating = \App\Models\Review::where('deliveryman_id', $deliveryMan->id)
                        ->avg('rating');
        ?>
        <div id="agent-container" class="bg-white p-4 rounded-xl shadow-md mb-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4">Delivery Agent</h2>
            <div id="agent-card-details" class="flex items-center justify-between">
                <div class="flex items-center">
                    <img id="agent-photo" src="{{ $order->delivery_man->image ? asset('delivery-man/' . $order->delivery_man->image) : asset('assets/user/img/default-avatar.png') }}" alt="Agent Photo" class="w-12 h-12 rounded-full object-cover mr-3 bg-gray-200">
                    <div>
                        <p class="font-bold text-gray-800" id="agent-name">{{ Str::ucfirst($order->delivery_man->f_name) }} {{ Str::ucfirst($order->delivery_man->l_name ?? '') }}</p>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-3 h-3 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.638-.921 1.94 0l1.24 3.824a1 1 0 00.95.691h4.028c.969 0 1.371 1.24.588 1.81l-3.264 2.373a1 1 0 00-.364 1.118l1.24 3.824c.3.921-.755 1.688-1.54 1.118l-3.264-2.373a1 1 0 00-1.176 0l-3.264 2.373c-.784.57-1.84-.197-1.54-1.118l1.24-3.824a1 1 0 00-.364-1.118L2.052 9.252c-.783-.57-.381-1.81.588-1.81h4.028a1 1 0 00.95-.691l1.24-3.824z"/></svg>
                            <span id="agent-rating">{{number_format($dmRating, 1)}}</span>
                            <span class="text-xs text-gray-400 ml-3 mr-1">|</span>
                            <span class="text-xs text-gray-600 font-medium flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2zM12 3v3"></path></svg>
                                Vehicle
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            <span class="font-semibold text-gray-700" id="agent-deliveries">{{ $orderDeliveriesCount }}</span> Deliveries Completed
                        </p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="tel:{{ $order->delivery_man->phone }}" class="bg-white text-[#FF6600] border border-[#FF6600] p-2 rounded-full hover:bg-gray-50 transition-colors shadow-sm" aria-label="Call Agent">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </a>
                    {{-- <button onclick="simulateAction('Messaging Agent (Chat)')" class="bg-[#FF6600] text-white p-2 rounded-full hover:bg-[#ff8833] transition-colors shadow-sm" aria-label="Message Agent">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-4 4v-4z"></path></svg>
                    </button> --}}
                </div>
            </div>
        </div>
        @endif
        {{-- @dd($order->order_status) --}}
        <!-- Order Progress Timeline -->
        <div class="bg-white p-4 rounded-xl shadow-md mb-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4">Order Progress</h2>
            <div id="status-timeline" class="relative">
                @php
                    $statusOrder = ['pending','accepted', 'confirmed', 'processing','dm_at_restaurant', 'handover', 'picked_up','order_on_way','arrived_at_door', 'delivered'];
                    $statusLabels = [
                        'pending' => ['title' => 'Order Placed', 'foodieLine' => 'Pet pooja ka safar shuru ho gaya hai!' ],
                        'accepted' => ['title' => 'Order Accepted', 'foodieLine' => 'Rasoi ne tumhara order accept kar liya hai!'],
                        'confirmed' => ['title' => 'Order Confirmed', 'foodieLine' => 'Rasoi ne tumhara order confirm kar liya hai!'],
                        'processing' => ['title' => 'Preparing Food', 'foodieLine' => 'Rasoi mein mehnat jaari! Khushbu aane lagi hai...'],
                        'dm_at_restaurant' => ['title' => 'Driver at Restaurant', 'foodieLine' => 'Driver rasoi par pahunch gaya hai. Jaldi milte hain!'],
                        'handover' => ['title' => 'Ready for Pickup', 'foodieLine' => 'Taaza aur Garam! Driver aa gaya hai.'],
                        'picked_up' => ['title' => 'Out for Delivery', 'foodieLine' => 'Khushiyon ki delivery ab bas raste mein hai!'],
                        'order_on_way' => ['title' => 'Order On The Way', 'foodieLine' => 'Garama-garam khaana bas kuch hi pal door!'],
                        'arrived_at_door' => ['title' => 'Arrived at Door', 'foodieLine' => 'Driver aapke darwaze par pahunch gaya hai!'],
                        'delivered' => ['title' => 'Delivered', 'foodieLine' => 'Safar Poora Hua! Ab bas khaane ka maza lijiye. 🍽️']
                    ];
                    $currentStatusIndex = array_search($order->order_status, $statusOrder);
                    if ($currentStatusIndex === false) $currentStatusIndex = 0;
                @endphp


                <?php foreach($statusOrder as $index => $status):?>
                        <?php
                        $isActive = $index == $currentStatusIndex;
                        $isCompleted = $index == $currentStatusIndex;
                        $isFinal = $index == count($statusOrder) - 1;
                        $statusInfo = $statusLabels[$status];
                        
                        $iconHtml = '';
                        $titleClass = 'text-gray-500';
                        $foodieLineClass = 'text-gray-400';
                        $dotClass = 'bg-gray-400';
                        
                        if ($isCompleted) {
                            $iconHtml = '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                            $titleClass = 'text-green-600';
                            $foodieLineClass = 'text-green-700';
                            $dotClass = 'bg-green-600';
                        } elseif ($isActive) {
                            $iconHtml = '<div class="status-pulse flex justify-center items-center"><div class="status-pulse-ring absolute bg-[#FF6600] opacity-75"></div><div class="status-dot bg-[#FF6600]"></div></div>';
                            $titleClass = 'text-gray-900';
                            $foodieLineClass = 'text-gray-700';
                            $dotClass = 'bg-[#FF6600]';
                            
                            if ($status == 'picked_up' && $order->delivery_man) {
                                $agentImage = $order->delivery_man->image ? asset('delivery-man/' . $order->delivery_man->image) : asset('assets/user/img/default-avatar.png');
                                $iconHtml = '<img src="' . $agentImage . '" alt="Agent" class="w-5 h-5 rounded-full object-cover border-2 border-[#FF6600] shadow-md">';
                            }
                        }

                        $contentTime = null;
                        if(isset($order[$status]) && $order[$status] != null) $contentTime = App\CentralLogics\Helpers::format_time($order[$status]);
                        if($contentTime == null) continue;
                        ?>
                    
                    
                    <div class="flex relative pb-8">
                        @if (!$isFinal)
                            <div class="absolute top-0 left-2 w-0.5 {{ $isCompleted ? 'bg-green-600' : 'bg-gray-300' }} h-full"></div>
                        @endif
                        <div class="z-10 w-5 h-5 flex items-center justify-center rounded-full flex-shrink-0 {{ $isCompleted ? $dotClass : ($isActive ? 'bg-white' : $dotClass) }} transition-colors duration-500">
                            {!! $iconHtml !!}
                        </div>
                        <div class="flex-grow pl-4">
                            <h3 class="text-base font-extrabold {{ $titleClass }}">{{ $statusInfo['title'] }}</h3>
                           @if($isActive) <p class="text-sm font-semibold {{ $foodieLineClass }}">{{ $statusInfo['foodieLine'] }}</p> @endif  
                           @if($contentTime) <p class="mt-1 text-xs text-gray-500">{{ $contentTime }}</p> @endif
                        </div>
                    </div>
                    <?php if ($isActive) break; ?>
                <?php endforeach; ?>
            </div>
        </div>



        <!-- Collapsible Order Details -->
        <div class="bg-white p-4 rounded-xl shadow-md mb-6">
            <button onclick="toggleDetails()" class="flex justify-between items-center w-full focus:outline-none">
                <h2 class="text-lg font-bold text-gray-700">Order Items (@if($customerOrderData?->foodItemList){{ count($customerOrderData->foodItemList) }}@else 0 @endif)</h2>
                <svg id="details-arrow" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div id="order-details-content" class="collapsible-content mt-4 overflow-hidden" style="max-height: 0;">
                <div id="item-list">
                    @if ($customerOrderData?->foodItemList != null)
                        @foreach ($customerOrderData->foodItemList as $key => $listItem)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center flex-1 pr-2 min-w-0">
                                    <span class="text-sm text-gray-700 font-semibold mr-2 flex-shrink-0">{{ $listItem->quantity }}x</span>
                                    <span class="text-base font-medium text-gray-800 whitespace-nowrap overflow-hidden text-ellipsis">{{ Str::ucfirst($listItem->foodName) }}</span>
                                    @if(isset($listItem->notes) && $listItem->notes)
                                        <span class="text-xs text-[#FF6600] italic ml-1 whitespace-nowrap overflow-hidden text-ellipsis">({{ $listItem->notes }})</span>
                                    @endif
                                </div>
                                <span class="text-base font-semibold text-gray-800 flex-shrink-0">{{ Helpers::format_currency($listItem->foodPrice) }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="border-t border-gray-200 mt-4 pt-4">
                    <p class="text-sm text-gray-600">Total Item Cost: <span class="font-semibold text-gray-800" id="items-total-cost">{{ Helpers::format_currency($customerOrderData?->sumOfFoodPriceBeforDiscount ?? 0) }}</span></p>
                </div>
            </div>
        </div>


        <!-- Drop-off Address Card -->
        <div id="address-card" class="bg-white p-4 rounded-xl shadow-md mb-6">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-bold text-gray-700 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Your Delivery Address
                </h2>
            </div>
            <p id="drop-off-address-content" class="text-sm text-gray-600 font-medium">
                @php
                    $deliveryAddress = json_decode($order->delivery_address, true);
                @endphp
                {{ $deliveryAddress['stringAddress'] ?? $order->delivery_address }}
            </p>
        </div>
        
        <!-- Collapsible Restaurant Details -->
        <div class="bg-white p-4 rounded-xl shadow-md mb-6">
            <button onclick="toggleRestaurantDetails()" class="flex justify-between items-center w-full focus:outline-none">
                <h2 class="text-lg font-bold text-gray-700">Restaurant Details</h2>
                <svg id="restaurant-details-arrow" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div id="restaurant-details-content" class="collapsible-content mt-4 overflow-hidden" style="max-height: 0;">
                <div id="restaurant-info">
                    <p class="font-bold text-gray-800">{{ Str::ucfirst($order->restaurant->name) }}</p>
                    @php($restaurantAddress = json_decode($order->restaurant->address, true))
                    <p class="text-sm text-gray-600">{{ $restaurantAddress['street']." ".$restaurantAddress['city'] ?? 'Address not available' }}</p>
                    <p class="text-sm text-[#FF6600]">Phone: {{ $order->restaurant->phone ?? 'Not available' }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="tel:{{ $order->restaurant->phone }}" class="w-full bg-blue-500 text-white py-2 rounded-lg font-semibold hover:bg-blue-600 transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.5l1 4 3-3 4-1.5 1 4 3 3 1 4 4 1.5V19a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                        Call Restaurant
                    </a>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="bg-white p-4 rounded-xl shadow-md mb-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4">Payment Summary</h2>
            <div id="payment-summary-details" class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span>Sub Total (@if($customerOrderData?->foodItemList){{ count($customerOrderData->foodItemList) }}@else 0 @endif Items)</span>
                    <span class="font-medium">{{ Helpers::format_currency($customerOrderData?->sumOfFoodPriceBeforDiscount ?? 0) }}</span>
                </div>
                @if ($customerOrderData?->sumOfDiscount > 0)
                <div class="flex justify-between text-red-600">
                    <span>Discount</span>
                    <span class="font-medium">-{{ Helpers::format_currency($customerOrderData?->sumOfDiscount) }}</span>
                </div>
                @endif
                @if ($customerOrderData?->couponDiscountAmount > 0)
                <div class="flex justify-between text-red-600">
                    <span>Coupon Discount</span>
                    <span class="font-medium">-{{ Helpers::format_currency($customerOrderData?->couponDiscountAmount) }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span>Platform Fee</span>
                    <span class="font-medium text-green-600">{{ Helpers::format_currency($customerOrderData?->platformCharge ?? 0) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Delivery Charge</span>
                    <span class="font-medium">{{ Helpers::format_currency($customerOrderData?->deliveryCharge ?? 0) }}</span>
                </div>
                @if ($customerOrderData?->sumofPackingCharge > 0)
                <div class="flex justify-between">
                    <span>Packing Charge</span>
                    <span class="font-medium">{{ Helpers::format_currency($customerOrderData?->sumofPackingCharge) }}</span>
                </div>
                @endif
                <div class="flex justify-between text-xs text-gray-500 pt-1 border-t border-gray-100">
                    <span>GST ({{ $customerOrderData?->gstPercent ?? 0 }}%)</span>
                    <span class="font-normal">{{ Helpers::format_currency($customerOrderData?->gstAmount ?? 0) }}</span>
                </div>
                @if ($customerOrderData?->dm_tips > 0)
                <div class="flex justify-between text-yellow-600">
                    <span>Delivery Tips</span>
                    <span class="font-medium">{{ Helpers::format_currency($customerOrderData?->dm_tips) }}</span>
                </div>
                @endif
            </div>
            <div class="border-t border-dashed border-gray-300 my-3 pt-3">
                <div class="flex justify-between font-bold text-lg text-gray-900">
                    <span>Total Paid</span>
                    <span id="payment-total">{{ Helpers::format_currency($customerOrderData?->billingTotal ?? 0) }}</span>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2 text-right mb-4">Payment Method: <span class="font-medium" id="payment-method">{{ Str::ucfirst($order->payment_method ?? 'N/A') }}</span> 
                @if ($order->payment_status == 'paid')
                    <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Paid</span>
                @else
                    <span class="ml-2 px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">{{ Str::ucfirst($order->payment_status) }}</span>
                @endif
            </p>

            <!-- Download Invoice Button -->
            <a href="{{ route('user.restaurant.order-invoice', ['order_id' => $order->id]) }}" class="w-full bg-green-100 text-green-800 py-2.5 rounded-lg font-semibold hover:bg-green-200 transition-colors flex items-center justify-center text-sm border border-green-200 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                Download Your Invoice
            </a>

            {{-- @if ($order->share_token != null) --}}
            <button class="w-full bg-blue-100 text-blue-800 py-2.5 rounded-lg font-semibold hover:bg-blue-200 transition-colors flex items-center justify-center text-sm border border-blue-200 mb-4" onclick="shareLink('{{ route('user.restaurant.order-trace',['order_id' => $order->id]) }}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" x2="15.42" y1="13.51" y2="17.49"/><line x1="15.41" x2="8.59" y1="6.51" y2="10.49"/></svg>
                Share Order
            </button>
            {{-- @endif --}}

            <div class="pt-4 border-t border-gray-200">
                <button onclick="openNeedHelp({{ $order->id }})" class="w-full bg-gray-100 text-gray-700 py-2 rounded-lg font-semibold hover:bg-gray-200 transition-colors flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m-7.072 0l-3.536-3.536m7.072 0V3m0 2.636V9.45M3 12h2m14 0h2M7.828 20.172l3.536-3.536M18.364 18.364l-3.536-3.536m0 0a9 9 0 10-12.728-12.728 9 9 0 0012.728 12.728z"></path></svg>
                    Need Help?
                </button>
            </div>
        </div>

        <!-- Action Message Box -->
        <div id="action-message" class="fixed bottom-0 left-0 right-0 bg-green-500 text-white text-center p-3 transition-transform duration-300 transform translate-y-full rounded-t-xl shadow-2xl z-50"></div>
    </main>

   

    <!-- OTP/QR Code Modal -->
    <div id="otp-modal" class="fixed inset-0 hidden items-center justify-center modal-backdrop transition-opacity duration-300 opacity-0">
        <div class="bg-white p-6 rounded-xl shadow-2xl w-11/12 max-w-sm transform transition-transform duration-300 scale-90">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-extrabold text-gray-800">Delivery Code</h2>
                <button onclick="closeOtpModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <p class="text-sm text-center text-gray-600 mb-4">Delivery agent ko yeh code ya QR dikhayein.</p>
            <div class="flex justify-center items-center p-6 bg-gray-100 rounded-lg mb-6">
                <img id="modal-qr" src="https://placehold.co/200x200/4c4083/f1f1f1?text=Delivery+QR" alt="QR Code" class="w-48 h-48 rounded-lg border-4 border-white shadow-lg"/>
            </div>
            <div class="text-center">
                <p class="text-xl font-semibold text-gray-700 mb-1">OTP Number</p>
                <span id="modal-otp" class="text-5xl font-black text-[#FF6600] tracking-widest">{{ $order->otp ?? '0000' }}</span>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/Helpers/mapHelperClass.js') }}"></script>
    <script>
        // --- Utility Functions ---
        const simulateAction = (action) => {
            const messageBox = document.getElementById('action-message');
            messageBox.textContent = `${action}! (Simulated)`;
            messageBox.classList.remove('translate-y-full');
            setTimeout(() => messageBox.classList.add('translate-y-full'), 3000);
        };
        const openNeedHelp = (orderId) => {
            const phone = '{{ \App\CentralLogics\Helpers::getBusinessPhone() }}';
            const url = `https://api.whatsapp.com/send?phone=${phone}&text=Hello,%20I%20need%20help%20with%20my%20order%20ID%20${orderId}.`;
            window.open(url, '_blank'); 
        };

        // --- OTP Modal Functions ---
        const openOtpModal = () => {
            @if($order->order_status !== 'picked_up')
                return;
            @endif
            const modal = document.getElementById('otp-modal');
            document.getElementById('modal-otp').textContent = '{{ $order->otp ?? '0000' }}';
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                modal.querySelector('div').classList.remove('scale-90');
            }, 10);
        };

        const closeOtpModal = () => {
            const modal = document.getElementById('otp-modal');
            modal.classList.remove('opacity-100');
            modal.querySelector('div').classList.add('scale-90');
            setTimeout(() => modal.classList.add('hidden'), 300);
        };
        
        // --- Collapsible Functions ---
        const renderCollapsible = (contentId, arrowId, isCollapsed) => {
            const content = document.getElementById(contentId);
            const arrow = document.getElementById(arrowId);
            if (isCollapsed) {
                content.style.maxHeight = '0';
                arrow.classList.remove('rotate-180');
            } else {
                content.style.maxHeight = content.scrollHeight + 'px';
                arrow.classList.add('rotate-180');
            }
        };

        let isDetailsCollapsed = true;
        let isRestaurantDetailsCollapsed = true;

        const toggleDetails = () => {
            isDetailsCollapsed = !isDetailsCollapsed;
            renderCollapsible('order-details-content', 'details-arrow', isDetailsCollapsed);
        };
        
        const toggleRestaurantDetails = () => {
            isRestaurantDetailsCollapsed = !isRestaurantDetailsCollapsed;
            renderCollapsible('restaurant-details-content', 'restaurant-details-arrow', isRestaurantDetailsCollapsed);
        };

        // --- Share Function ---
        function shareLink(link) {
            // Copy the link to the clipboard
            navigator.clipboard.writeText(link).then(() => {
                console.log('Link copied to clipboard: ', link);

                // Now open the share dialog (if supported)
                if (navigator.share) {
                    navigator.share({
                        title: 'Check out my FOODYARI order!',
                        text: 'Take a look at my food order!',
                        url: link
                    }).then(() => {
                        simulateAction('Order shared successfully');
                        console.log('Thanks for sharing!');
                    }).catch((err) => {
                        console.log('Error while sharing: ', err);
                    });
                } else {
                    simulateAction('Link copied to clipboard');
                }
            }).catch((err) => {
                console.error('Error in copying link to clipboard: ', err);
                simulateAction('Error copying link');
            });
        }

        // --- ETA Timer (Fixed Version) ---
        function startEtaTimer(durationMinutes) {
            const timerEl = document.getElementById('delivery-timer');
            if (!timerEl || durationMinutes <= 0) return;
            
            // Convert minutes to total seconds (and fix floating-point errors)
            let timer = Math.floor(durationMinutes * 60);
            
            const timerInterval = setInterval(() => {
                const minutes = Math.floor(timer / 60);
                const seconds = timer % 60;
                
                timerEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                
                if (--timer < 0) {
                    clearInterval(timerInterval);
                    timerEl.textContent = "Now!";
                }
            }, 1000);
        }

        // Start timer if order is not delivered
        @if($order->order_status != 'delivered')
            @if($deliveryData && isset($deliveryData['eta_minutes']))
                // Use real delivery time from backend
                startEtaTimer({{ $deliveryData['eta_minutes'] }});
            @else
                // Fallback to default time
                startEtaTimer(25);
            @endif
        @endif

        // --- Cooking Progress Timer ---
        @if(isset($processingInfo) && $processingInfo['is_processing'] && $processingInfo['status'] !== 'ready')
        function startCookingProgressTimer() {
            const progressBar = document.getElementById('cooking-progress-bar');
            const percentageEl = document.getElementById('cooking-percentage');
            const elapsedTimeEl = document.getElementById('elapsed-time');
            const remainingTimeEl = document.getElementById('remaining-time');
            const statusTextEl = document.getElementById('cooking-status-text');
            
            if (!progressBar || !percentageEl) return;
            
            const totalTime = {{ $processingInfo['total_time'] }}; // in minutes
            const initialElapsed = {{ $processingInfo['elapsed_time'] }}; // in minutes
            let currentElapsed = initialElapsed;
            
            const updateProgress = () => {
                currentElapsed += (1/60); // Add 1 second (converted to minutes)
                const percentage = Math.min(100, (currentElapsed / totalTime) * 100);
                const remaining = Math.max(0, totalTime - currentElapsed);
                
                // Update UI elements
                progressBar.style.width = percentage + '%';
                percentageEl.textContent = Math.round(percentage) + '%';
                elapsedTimeEl.textContent = Math.floor(currentElapsed);
                remainingTimeEl.textContent = Math.floor(remaining);
                
                // Update status based on progress
                if (percentage >= 100) {
                    statusTextEl.textContent = 'Should be ready soon!';
                    progressBar.className = progressBar.className.replace(/bg-gradient-to-r from-purple-\d+ to-purple-\d+/, 'bg-gradient-to-r from-orange-500 to-orange-600');
                    clearInterval(cookingTimer);
                } else if (remaining <= 2) {
                    statusTextEl.textContent = 'Almost ready!';
                    progressBar.className = progressBar.className.replace(/bg-gradient-to-r from-purple-\d+ to-purple-\d+/, 'bg-gradient-to-r from-yellow-500 to-yellow-600');
                }
            };
            
            const cookingTimer = setInterval(updateProgress, 1000); // Update every second
        }
        
        // Start cooking progress timer
        startCookingProgressTimer();
        @endif

        // --- Map Integration ---
        document.addEventListener('DOMContentLoaded', function () {
            const map2 = new CreateMap();
            let dmMarker = null;
            let previousDmPosition = null;
            let dmDirectionsRenderer = null;
            let dmInfowindow = null;
            const orderLocation = JSON.parse(document.querySelector('meta[name=order_location]').content);

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const currentLocation = {
                        lat: parseFloat(orderLocation.position.lat),
                        lng: parseFloat(orderLocation.position.lon)
                    };
                    // console.log("Current location: ", currentLocation);

                    const restaurantPosition = {
                        lat: {{ $order->restaurant->latitude }},
                        lng: {{ $order->restaurant->longitude }}
                    };

                    map2.createMap(currentLocation, {
                        selector: "#map-canvas2",
                        mapClick: false,
                        mapDrag: false,
                        styles: [
                            {
                                elementType: 'geometry',
                                stylers: [{ color: '#ebe3cd' }]
                            },
                            {
                                elementType: 'labels.text.fill',
                                stylers: [{ color: '#523735' }]
                            },
                            {
                                elementType: 'labels.text.stroke',
                                stylers: [{ color: '#f5f1e6' }]
                            },
                            {
                                featureType: 'water',
                                elementType: 'geometry',
                                stylers: [{ color: '#c9c9c9' }]
                            }
                        ]
                    });

                    const customerMarker = map2.makeMarker(currentLocation, "{{ asset('assets/user/img/icons/marker-icon.png') }}", false);
                    const restaurantMarker = map2.makeMarker(restaurantPosition, "{{ asset('assets/user/img/icons/restaurant-map-icon.png') }}", false);

                    map2.map.setCenter({
                        lat: (currentLocation.lat + restaurantPosition.lat) / 2,
                        lng: (currentLocation.lng + restaurantPosition.lng) / 2
                    });
                    map2.map.setZoom(12);

                    const directionsService = new google.maps.DirectionsService();
                    const directionsRenderer = new google.maps.DirectionsRenderer({
                        suppressMarkers: true,
                        polylineOptions: {
                            strokeColor: '#0000FF',
                            strokeOpacity: 1.0,
                            strokeWeight: 6
                        }
                    });

                    directionsRenderer.setMap(map2.map);

                    directionsService.route({
                        origin: restaurantPosition,
                        destination: currentLocation,
                        travelMode: google.maps.TravelMode.DRIVING
                    }, (response, status) => {
                        if (status === google.maps.DirectionsStatus.OK) {
                            directionsRenderer.setDirections(response);
                            const travelTime = response.routes[0].legs[0].duration.text;
                            const distance = response.routes[0].legs[0].distance.text;

                            const infowindow = new google.maps.InfoWindow({
                                content: `<div style="text-align: center;">
                                    <b>Restaurant: {{ $order->restaurant->name }}<br>Distance: ${distance}<br>Travel Time: ${travelTime}</b>
                                </div>`
                            });

                            restaurantMarker.addListener('click', () => {
                                infowindow.open(map2.map, restaurantMarker);
                            });
                        } else {
                            console.error('Directions request failed due to ' + status);
                        }
                    });

                    // Add delivery man marker if location is available
                    @if($dmLastLocation && $dmLastLocation->currentLocation)
                    const dmPosition = {
                        lat: {{ $dmLastLocation->currentLocation->lat }},
                        lng: {{ $dmLastLocation->currentLocation->lng }}
                    };
                    
                    if (dmPosition.lat && dmPosition.lng) {
                        dmMarker = map2.makeMarker(dmPosition, "{{ asset('assets/user/img/icons/deliveryman-map-icon.png') }}", false);

                        dmInfowindow = new google.maps.InfoWindow({
                            content: `<div style="text-align: center;">
                                <b>Delivery Agent<br>{{ $order->delivery_man ? Str::ucfirst($order->delivery_man->f_name) : 'Driver' }}</b><br>
                                <small>Last updated: {{ $dmLastLocation->currentLocation->timestamp ?? 'Unknown' }}</small>
                            </div>`
                        });

                        dmMarker.addListener('click', () => {
                            dmInfowindow.open(map2.map, dmMarker);
                        });

                        // Create directions from delivery man to customer if picked up
                        @if($order->order_status == 'picked_up')
                        const dmDirectionsRenderer = new google.maps.DirectionsRenderer({
                            suppressMarkers: true,
                            polylineOptions: {
                                strokeColor: '#FFA500',
                                strokeOpacity: 1.0,
                                strokeWeight: 6
                            }
                        });

                        dmDirectionsRenderer.setMap(map2.map);

                        directionsService.route({
                            origin: dmPosition,
                            destination: currentLocation,
                            travelMode: google.maps.TravelMode.DRIVING
                        }, (response, status) => {
                            if (status === google.maps.DirectionsStatus.OK) {
                                dmDirectionsRenderer.setDirections(response);
                                const dmTravelTime = response.routes[0].legs[0].duration.text;
                                const dmDistance = response.routes[0].legs[0].distance.text;

                                dmInfowindow.setContent(`<div style="text-align: center;">
                                    <b>Delivery Agent<br>{{ $order->delivery_man ? Str::ucfirst($order->delivery_man->f_name) : 'Driver' }}</b><br>
                                    Distance: ${dmDistance}<br>Travel Time: ${dmTravelTime}<br>
                                    <small>Last updated: {{ $dmLastLocation->currentLocation->timestamp ?? 'Unknown' }}</small>
                                </div>`);
                            }
                        });
                        @endif
                    }
                    @endif

                    document.addEventListener('locationUpdated', function (e) {
                        console.log("Delivery man position updated: Custom event", e.detail);
                        try {
                            const newDmPosition = {
                                lat: e?.detail?.data?.lat || 0,
                                lng: e?.detail?.data?.lng || 0
                            };

                            console.log("Delivery man position updated: ", newDmPosition);
                            if(newDmPosition.lat == 0 || newDmPosition.lng == 0) return

                            if (!dmMarker) {
                                // First time creation
                                dmMarker = map2.makeMarker(newDmPosition, "{{ asset('assets/user/img/icons/deliveryman-map-icon.png') }}", false);

                                dmInfowindow = new google.maps.InfoWindow({ content: "" });

                                dmMarker.addListener('click', () => {
                                    dmInfowindow.open(map2.map, dmMarker);
                                });

                                dmDirectionsRenderer = new google.maps.DirectionsRenderer({
                                    suppressMarkers: true,
                                    polylineOptions: {
                                        strokeColor: '#FFA500',
                                        strokeOpacity: 1.0,
                                        strokeWeight: 6
                                    }
                                });

                                dmDirectionsRenderer.setMap(map2.map);
                            } else {
                                dmMarker.setPosition(newDmPosition);
                            }

                            // Only update if position changed significantly
                            if (!previousDmPosition || distanceBetween(previousDmPosition, newDmPosition) > 0.0001) {
                                previousDmPosition = newDmPosition;

                                directionsService.route({
                                    origin: newDmPosition,
                                    destination: currentLocation,
                                    travelMode: google.maps.TravelMode.DRIVING
                                }, (response, status) => {
                                    if (status === google.maps.DirectionsStatus.OK) {
                                        dmDirectionsRenderer.setDirections(response);

                                        const dmTravelTime = response.routes[0].legs[0].duration.text;
                                        const dmDistance = response.routes[0].legs[0].distance.text;

                                        dmInfowindow.setContent(`<div style="text-align: center;">
                                            <b>Delivery Man<br>Distance: ${dmDistance}<br>Travel Time: ${dmTravelTime}</b>
                                        </div>`);
                                    } else {
                                        console.error('Directions request failed for delivery man due to ' + status);
                                    }
                                });
                            }
                        } catch (error) {
                            console.error('Error updating delivery man position:', error);
                        }
                    });

                });
            } else {
                console.error("Geolocation is not supported by this browser.");
            }

            // Helper to calculate distance (in degrees)
            function distanceBetween(pos1, pos2) {
                const dx = pos1.lat - pos2.lat;
                const dy = pos1.lng - pos2.lng;
                return Math.sqrt(dx * dx + dy * dy);
            }
        });
    </script>
    <script>
        function shareLink(link) {
            // const link = window.location.href; // Get the current page URL or any custom link

            // Copy the link to the clipboard
            navigator.clipboard.writeText(link).then(() => {
                console.log('Link copied to clipboard: ', link);

                // Now open the share dialog (if supported)
                if (navigator.share) {
                    navigator.share({
                        title: 'Check this out!',
                        text: 'Take a look at this amazing page!',
                        url: link
                    }).then(() => {
                        console.log('Thanks for sharing!');
                    }).catch((err) => {
                        console.log('Error while sharing: ', err);
                    });
                } else {
                    alert('Web Share API not supported in this browser. But the link is copied to your clipboard!');
                }
            }).catch((err) => {
                console.error('Error in copying link to clipboard: ', err);
            });
        }

        function initDMLocation() {

            const socket = new WebSocket('wss://foodyari.com/ws');
            // const socket = new WebSocket('ws://127.0.0.1:6002');
            let intervalId = null;

            socket.onopen = () => {
                console.log("📡 Connected to WebSocket");

                const data = {
                    user_id: 909,
                    type: 'customer',
                };

                socket.send(JSON.stringify(data));

            };

            socket.onmessage = (event) => {
                console.log("📥 Incoming message:", event.data);

                try {
                    const parsedData = JSON.parse(event.data);

                    const customEvent = new CustomEvent('locationUpdated', {
                        detail: parsedData
                    });
                    document.dispatchEvent(customEvent);
                } catch (error) {
                    console.error("Error parsing WebSocket message:", error);
            }
                const customEvent = new CustomEvent('locationUpdated', {
                    detail: event.data  // Pass full data in `detail`
                });
                    // Emit the event globally
                document.dispatchEvent(customEvent);
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
        
        // Initialize real-time location tracking
        document.addEventListener("DOMContentLoaded", initDMLocation);
    </script>

</body>
</html>
