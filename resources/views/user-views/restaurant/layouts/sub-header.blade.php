@php
    // $redis = new \App\CentralLogics\Redis\RedisHelper();
    // $userLocations = [];
    // $userLocation = [];
    // if (auth('customer')->check()) {
    //     $user = auth('customer')->user();
    //     $userLocations = auth('customer')->user()->customerAddress()->orderByDesc('is_default')->orderByDesc('id')->get();
    //     $redisUserLocation  = $redis->get("user:{$user->id}:user_location");
    //     if($redisUserLocation != NULL){
    //         $loc = json_decode($redisUserLocation);
    //         $default_address = [
    //             'type' => !empty($loc) ? $loc->type : 'Select Address',
    //             'address' => !empty($loc) ? $loc->address : '',
    //         ];
    //     }
    // }else{
    //     $userLocation = Helpers::getGuestSession('guest_location');
    //     if($userLocation){
    //         $default_address['type'] = $userLocation['type'];
    //         $default_address['address'] = $userLocation['address'];
    //     }
    // }

    // NEW 

    $redis = new \App\CentralLogics\Redis\RedisHelper();

    $userCookieLocation = isset($_COOKIE['user_location']) ? json_decode($_COOKIE['user_location']) : null;
    $isManualSelection = isset($_COOKIE['user_location_manual_selection']) ? true : false;
    $isLocationUpdated = isset($_COOKIE['user_location_update']) ? true : false;

    $uloc = [];
    $userLocations = collect([]);
    $default_address = [
        'type' => 'Select Address',
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
            $default_address['type'] = $userLocation['type'] ?? 'Home';
            $default_address['address'] = $userLocation['address'] ?? '';
        }
    }

    $foodList =App\Models\Category::get('name')->pluck('name')->toArray();


@endphp
<div class="row d-lg-none d-block rounded-bottom-4" id="mobile-header" style="background-image: linear-gradient(to bottom, #FF9933, #FFFFFF);">
    <div class="text-white pt-3 px-2">
        <div class="title d-flex align-items-center justify-content-between">

            <div class="col-8">
                <a class="d-flex" style="color:#000080;" role="button" data-bs-toggle="offcanvas" data-bs-target="@if (isset($userLocations[0])) #userSavedLocation @else #userNewLocation @endif">
                    <div class="fw-bold m-0 d-flex" data-text="{{ array_key_exists('type', $default_address) ? $default_address['type'] : '' }}">
                        <i class="feather-map-pin me-2 icofont-size" style="font-size: 30px;color:#000080;"></i>
                        <div class="lh-1">
                            <p class="truncate fw-bold m-0"><b>{{ array_key_exists('type', $default_address) ? $default_address['type'] : '' }}</b></p>
                            <marquee scrollamount="3" class="text-truncate">{{ array_key_exists('address', $default_address) ? $default_address['address'] : '' }}</marquee>
                        </div>
                    </div>
                    {{-- <div class="text-break location-bar">
                        <marquee scrollamount="3" class="text-truncate">{{ array_key_exists('address', $default_address) ? $default_address['address'] : '' }}</marquee>
                    </div> --}}
                </a>
            </div>
            <div class="ms-2" id="toggle-bars">
                <a data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"class="toggle toggle-btn btn rounded-circle p-2 bg-white" style="background: black;">
                    <i class="fa-solid fa-user text-secondary" style="background: white;font-size:25px;"></i>
                </a>
            </div>

            {{-- <div class="rounded-3 d-flex p-2">
                <div class="" id="toggle-bars">
                    <a href="{{ route('user.notifications') }}"class="btn rounded-circle p-2 toggle hc-nav-trigger hc-nav-1" type="button" aria-controls="hc-nav-1" role="button">
                        <i class="fa-solid fa-bell fs-2" style="color: white;"></i>
                    </a>
                </div>
            </div> --}}
            {{-- <div class="d-flex align-items-center justify-content-end col-2 ps-0" id="toggle-bar">
                <button class="btn text-white p-0 toggle hc-nav-trigger hc-nav-1" type="button" aria-controls="hc-nav-1" role="button">
                    <img alt="#" src="{{ asset('assets/images/icons/foodYariLogo.png') }}" class="img-fluid rounded-circle header-user header-user">
                </button>
                <a class="toggle text-dark fs-5 toggle-btn align-self-center" data-bs-toggle="offcanvas" href="#offcanvasExample" href="javascript:void(0)" aria-controls="offcanvasExample">
                    <img alt="#" src="{{ asset('assets/images/icons/foodYariLogo.png') }}" class="img-fluid rounded-circle header-user header-user">
                </a>
            </div> --}}

        </div>
    </div>
    <style>
        input::placeholder {
            font-size: 18px;
            /* Adjust the size as needed */
            color: #888;
            /* Optionally, change the color */
        }
    </style>
    {{-- <div class="mb-4 text-center">
        <img src="{{ asset('assets/images/icons/chakra.webp') }}" alt="" style="height: 45px;">
    </div> --}}
    <div class="px-2 food-search-container mt-2" id="food-search-container">
        <div class="input-group bg-white rounded shadow-sm " style="opacity: 0.8">
            <span class="input-group-text bg-transparent border-0 rounded-0"><i class="feather-search"></i></span>
            <input type="text" class="form-control bg-transparent border-0 rounded-0 px-0 shadow-none" placeholder="Search {{Helpers::getRandomFood($foodList)}}" aria-label="" style="height: 45px;">
        </div>
    </div>
</div>
