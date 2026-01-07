@php
    $userLocation = [];
    $redis = new \App\CentralLogics\Redis\RedisHelper();
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
    }
    
    if($userLocation){
        $default_address['type'] = $userLocation['type'];
        $default_address['address'] = $userLocation['address'];
        $locationPoint1['lat'] = $userLocation['lat'];
        $locationPoint1['lon'] = $userLocation['lng'];
    }


    $banners = App\Models\Banner::isActive()->latest()->get();
    // dd($banners);
    // App\Models\
    $filterBanners = [];
    $zone = null;
    foreach ($banners as $banner) {
        if($banner->type == 'location'){
            $locationPoint2 = ['lat' => $banner->latitude,'lon' =>$banner->longitude];
           $distance =  App\CentralLogics\Helpers::haversineDistance($locationPoint1, $locationPoint2);
           if((float) $banner->radius > $distance){
                $filterBanners[] = $banner;
           }
        }elseif ($banner->type == 'food') {
            if(!$zone){
                $zone = Helpers::findZoneByLocation($locationPoint1['lat'], $locationPoint1['lon']);
            }
            if($banner->zone_id == $zone?->id){
                $filterBanners[] = $banner;
            }
        }elseif ($banner->type == 'zone') {
            if(!$zone){
                $zone = Helpers::findZoneByLocation($locationPoint1['lat'], $locationPoint1['lon']);
            }
            if($banner->zone_id == $zone?->id){
                $filterBanners[] = $banner;
            }
        }elseif ($banner->type == 'restaurant') {
            // if($banner->title == "dkjfhdj"){

            //     // dd($banner->screen_to != "inside_restaurant");
            // }
            if(!$zone){
                $zone = Helpers::findZoneByLocation($locationPoint1['lat'], $locationPoint1['lon']);
            }
            if($banner->zone_id == $zone->id && ($banner->screen_to != "inside_restaurant")){
                $restaurant = App\Models\Restaurant::isActive()->find($banner->restaurant_id);
                if($restaurant){
                    $filterBanners[] = $banner;
                }
            }
        }
    }
    // dd($filterBanners);
@endphp

@if(isset($filterBanners[0]))
<div class="container py-0" id="slider-container">
    <div class="popular-slider">
        @foreach ($filterBanners as $banner)
            <div class="cat-item py-0">
                <a class="d-block text-center shadow-sm" href="javascript:void(0)">
                    <img alt="#" src="{{ asset("banner/$banner->image") }}" class="img-fluid rounded-3" >
                </a>
            </div>
        @endforeach
    </div>
</div>
@endif
