
@php
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
    $filterMarquees = [];
    $marquees = App\Models\Marquee::isActive()->get();

    $zone = null;
    foreach ($marquees as $marquee) {
        if($marquee->type == 'location'){
            $locationPoint2 = ['lat' => $marquee->latitude,'lon' =>$marquee->longitude];
           $distance =  App\CentralLogics\Helpers::haversineDistance($locationPoint1, $locationPoint2);
           if((float) $marquee->radius > $distance){
                $filterMarquees[] = $marquee;
           }
        }elseif ($marquee->type == 'food') {
            if(!$zone){
               
                $zone = Helpers::findZoneByLocation($locationPoint1['lat'], $locationPoint1['lon']);
            }
            if($marquee->zone_id == $zone->id){
                $filterMarquees[] = $marquee;
            }
        }elseif ($marquee->type == 'zone') {
            if(!$zone){
                $zone = Helpers::findZoneByLocation($locationPoint1['lat'], $locationPoint1['lon']);
            }
            if($marquee->zone_id == $zone->id){
                $filterMarquees[] = $marquee;
            }
        }elseif ($marquee->type == 'restaurant') {
            if(!$zone){
                $zone = Helpers::findZoneByLocation($locationPoint1['lat'], $locationPoint1['lon']);
            }
            $filterMarquees[] = (function() use($marquee, $zone){
                if($zone=== null){
                    return null;
                }
                if($marquee->zone_id == $zone->id && ($marquee->screen_to == "inside_restaurant")){
                    $restaurant = App\Models\Restaurant::isActive()->find($marquee->restaurant_id);
                    if($restaurant){
                        return $marquee;
                    }
                }
            })();
        }
    }
@endphp

@if(isset($filterMarquees[0]))

<div class="container mt-3">
    <div class="notice-slider">
        @foreach ($filterMarquees as $marquee)
        <div class="notice-item m-2 ">
            <a class="d-block text-center" href="{{$marquee->link ?? "javascript:void(0)"}}">
                <img src="{{asset("marquee/$marquee->file")}}" alt="" class="w-100 rounded-4">
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif


