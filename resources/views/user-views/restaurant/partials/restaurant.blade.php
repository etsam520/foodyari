@php
    $closeShowable = true;

    $isLoggedin = Auth::guard('customer')->check();
    if($isLoggedin){
        $favoriteRestaurants = Auth::guard('customer')->user()->favoriteRestaurants()->get()->toArray();
    }
@endphp
@if ($restaurants)
    @foreach ($restaurants as $restaurant)
        <?php
        // dd($restaurant);
        $restaurantTiming = Helpers::remainingTime($restaurant['opening_time'], $restaurant['closing_time']);
        $badges = json_decode($restaurant['badges'],true);
        ?>

        @if ($restaurant['is_open_now'] != true  && $closeShowable)
        <div class="col-12 mb-3 mt-3">
            <div class="text-muted fs-5 text-center fw-bolder">Restaurant Not Delivering Currently</div>
            @php
                $closeShowable = false;
            @endphp
        </div>
        @endif
        {{-- @php($tempClosingCheck = !isset($restaurantTiming) || $restaurantTiming['isClosed'] == true || $restaurant->isClosed == true || $restaurant->zone->status == 0) --}}
        <div class="col-md-6 mb-2 mt-2" onclick="location.href='{{ route('user.restaurant.get-restaurant',($restaurant['url_slug']??Str::slug($restaurant['name'])))}}'">

            <div class="d-flex align-items-top list-card bg-white h-100 rounded overflow-hidden position-relative shadow {!! $restaurant['is_open_now'] != true || $restaurant['zone_status'] == 0? 'closed-restaurant' : null !!}">

                <div class="list-card-image">

                    @if ( $badges != null && isset($badges['b1']))
                    <div class="star mess-info position-absolute">
                        <div class="overlay-two rounded-0"></div>
                        <div class="badge text-white position-relative align-self-center fs-6"> {{ Str::ucfirst($badges['b1']) }}</div>
                    </div>
                    @endif

                    @if($isLoggedin)

                        <div class="favourite-heart text-danger position-absolute rounded-circle" onclick="event.stopPropagation()">
                            <?php
                            $foundFood = null;
                            $restaurantId = $restaurant['id'];
                            $restaurantFound = array_filter($favoriteRestaurants, function ($restaurant) use($restaurantId)  {
                                        return $restaurant['id'] == $restaurantId;
                                    });
                            $restaurantFound = array_values($restaurantFound);
                            ?>
                            @if (isset($restaurantFound[0]))
                            {{-- <img src="{{asset('assets/user/img/favourite.png')}}" onclick="unfavoriteRestaurant(this)" data-id="{{$restaurant->id}}" class="img-fluid" style="width: 30px" alt="non-fav-food"> --}}
                            <span onclick="unfavoriteRestaurant(this)" data-id="{{$restaurant['id']}}"><i class="fas fa-heart text-danger"></i></span>
                            @else
                            {{-- <img src="{{asset('assets/user/img/non_favourite.png')}}" onclick="favoriteRestaurant(this)" data-id="{{$restaurant->id}}" class="img-fluid" style="width: 30px" alt="fav-food"> --}}
                            <span onclick="favoriteRestaurant(this)" data-id="{{$restaurant['id']}}"><i class="feather-heart text-muted"></i></span>
                            @endif
                        </div>
                    @endif

                    <div class="member-plan position-absolute {{ $restaurant['type'] == 'veg' ? 'bg-success' : ($restaurant['type'] == 'non veg' ? 'bg-brown' : 'bg-warning') }}
                        text-white rounded fw-bolder px-2 shadow">
                        @if ($restaurant['type'] == 'veg')
                            Veg
                        @elseif ($restaurant['type'] == 'non veg')
                            Non Veg
                        @else
                            Veg | Non veg
                        @endif

                    </div>

                    <a href="javascript:void(0)">
                        <img alt="#" src="{{ asset('restaurant/' . $restaurant['logo']) }}" class="img-fluid item-img w-100">
                    </a>
                </div>
                <div class="py-lg-3 py-2 ps-3 pe-0 position-relative w-100">
                    <div class="list-card-body">
                        <h6 class="mb-1">
                            <a href="javascript:void(0)" class="text-black">
                                {{ Str::upper($restaurant['name']) }}
                            </a>
                        </h6>
                        <div class="list-card-badge mb-1 d-flex">
                            @if ($badges != null && isset($badges['b2']))
                                <div class="badge text-bg-danger badge-two me-3" style="font-size:13px;">{{ Str::ucfirst($badges['b2']) }}
                            @endif
                        </div>
                        {{-- <div class="d-flex" style="font-size:15px;">
                            <i class="feather-clock mt-1 me-1"></i>
                            <div>09:00 PM</div>
                        </div> --}}
                    </div>
                    @if($restaurant['description'] != null)
                    <p class="mb-0" style="font-size:15px;">{{Str::ucfirst($restaurant['description'])}}</p>
                    @endif
                    <div class="d-flex mt-2">
                        <div class="bg-success text-white rounded px-2 me-1">
                            <p class="mb-0 text-white py-1 fw-bolder" style="font-size: 15px;"><i class="feather-star star_active me-2"></i>5.0</p>
                        </div>
                        @if($isLoggedin)
                        <div class="rounded px-2 me-1 align-self-center" onclick="event.stopPropagation()">

                            @if(!empty(array_filter($collectionItems, function ($item) use($restaurant) {
                                return $item['item_id'] == $restaurant['id'] ;
                            })))
                            @php($found_collection = array_filter($collectionItems, function ($item) use($restaurant) {
                                return $item['item_id'] == $restaurant['id'] ;
                            }))
                            <span><img src="{{asset('assets/user/img/saved-collection.png')}}" data-id="{{$restaurant['id']}}" data-type="restaurant" data-collection-id="{{array_values($found_collection)[0]['collection_id']}}" onclick="undoFromCollection(this)" alt="" style="height: 22px;"></span>
                            @else
                            <span><img src="{{asset('assets/user/img/save-collection.png')}}" data-id="{{$restaurant['id']}}" data-type="restaurant" onclick="addToCollection(this)" alt="" style="height: 22px;"></span>
                            @endif
                        </div>
                        @endif
                        <div class="mb-0 d-flex align-items-center fs-6 text-muted opacity-25">
                            <i class="feather-map-pin me-1"></i><span>{{ App\CentralLogics\Helpers::formatDistance($restaurant['distance']) }}</span>
                        </div>
                    </div>
                    <div class="mb-1 d-flex justify-content-between mt-2">
                        <div class="d-flex align-items-center fs-6" style="font-size:15px;">
                        @if ($restaurant['zone_status'] == 0)
                            <i class="fas fa-hourglass-end mt-1 me-1"></i><span>Zone Closed &nbsp;</span>
                        @elseif($restaurant['temp_close'])
                            <i class="fas fa-hourglass-end mt-1 me-1"></i><span>Temporarily Closed &nbsp;</span>

                        @elseif ($restaurantTiming== null)
                        <i class="fas fa-hourglass-end mt-1 me-1"></i><span>Closed Today &nbsp;</span>
                        @else
                        {{-- @dd($restaurantTiming) --}}
                            @if ($restaurantTiming['isClosed'])
                                <i class="fas fa-refresh mt-1 me-1"></i><span>Reopens &nbsp;</span>
                                <div class="me-2 text-nowrap">{!! $restaurantTiming['format'] !!} </div>
                            @else
                                @if ($restaurantTiming['closingDifferance']->h < 1)
                                    
                                <i class="fas fa-hourglass-end mt-1 me-1"></i><span>Closing &nbsp;</span>
                                <div class="me-2 text-nowrap">{!! $restaurantTiming['format'] !!} </div>
                                @else
                                <i class="fas fa-motorcycle mt-1 me-1 text-primary"></i><span class="text-primary">within {{ \Carbon\Carbon::parse($restaurant['min_delivery_time'])->format('i') }} mins</span>
                                @endif
                            @endif
                        @endif
                            {{-- <div class="me-2">9 min <span class="text-warning">Left</span> </div> --}}
                        </div>
                        @if ( $badges != null && isset( $badges['b3']))
                            <div class="bookmark-icon pe-2 ps-3 text-nowrap">
                                <span class="text-white" style="border-radius: 8px 0px 0px 8px !important;font-size: 14px;">{{ Str::ucfirst($badges->b3) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endforeach
@else
    <div class="col-md-12 mb-3">
        <div style="filter: grayscale(100%);" class=" list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">

            <div class="py-3 ps-3 pe-0 position-relative w-100">
                <div class="list-card-body text-center">
                    <h6 class="mb-1">
                        <a href="javascript:void(0)" class="text-black">
                            Restuarant Not Available
                        </a>
                    </h6>
                </div>
            </div>
        </div>
    </div>
@endif
