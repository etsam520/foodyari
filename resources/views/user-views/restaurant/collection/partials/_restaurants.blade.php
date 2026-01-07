{{-- dd($collectionItems); --}}
<?php
$isLoggedin = Auth::guard('customer')->check();
$favoriteRestaurants = [];
if ($isLoggedin) {
    $favoriteRestaurants = Auth::guard('customer')->user()->favoriteRestaurants()->get()->toArray();
}
?>
@foreach ($collectionItems as $collection)
    <p class="p-3 mb-0 text-secondary" style="font-size: 15px;font-weight: 700;">
        <span class="text-warning" style="font-size:25px;">{{ Str::ucfirst($collection['name']) }}</span>
    </p>
    <div class="row">
        @foreach ($collection['items'] as $item)
            @php
                $restaurant = $item['restaurant'] ?? null;
            @endphp
            @if ($restaurant != null)
                <?php
                $badges = json_decode($restaurant['badges'], true);
                ?>
                <div class="col-md-6 mb-2 mt-2 restaurant-item" onclick="location.href='{{ route('user.restaurant.get-restaurant', Str::slug($restaurant['name'])) }}'">

                    <div class="d-flex align-items-top list-card bg-white h-100 rounded overflow-hidden position-relative shadow">

                        <div class="list-card-image">
                            <div class="star mess-info position-absolute">
                                <div class="overlay-two rounded-0"></div>
                                @if ($badges != null && isset($badges['b1']))
                                    <div class="badge text-white position-relative align-self-center fs-6"> {{ Str::ucfirst($badges['b1']) }}</div>
                                @endif
                            </div>

                            @if ($isLoggedin)
                                <div class="favourite-heart text-danger position-absolute rounded-circle" onclick="event.stopPropagation()">
                                    <?php
                                    $foundFood = null;
                                    $restaurantId = $restaurant['id'];
                                    $restaurantFound = array_filter($favoriteRestaurants, function ($_restaurant) use ($restaurantId) {
                                        return $_restaurant['id'] == $restaurantId;
                                    });
                                    $restaurantFound = array_values($restaurantFound);
                                    ?>
                                    @if (isset($restaurantFound[0]))
                                    <span onclick="unfavoriteRestaurant(this)" data-id="{{$restaurant['id']}}"><i class="fas fa-heart text-danger"></i></span>
                                    @else
                                    <span onclick="favoriteRestaurant(this)" data-id="{{$restaurant['id']}}"><i class="feather-heart text-muted"></i></span>
                                    @endif
                                </div>
                            @endif

                            <div
                                class="member-plan position-absolute {{ $restaurant['type'] == 'veg' ? 'bg-success' : ($restaurant['type'] == 'non veg' ? 'bg-brown' : 'bg-warning') }}
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
                            </div>
                            @if ($restaurant['description'] != null)
                                <p class="mb-0" style="font-size:15px;">{{ Str::ucfirst($restaurant['description']) }}</p>
                            @endif
                            <div class="d-flex mt-2">
                                <div class="bg-success text-white rounded px-2 me-1">
                                    <p class="mb-0 text-white py-1 fw-bolder" style="font-size: 15px;"><i class="feather-star star_active me-2"></i>5.0</p>
                                </div>
                                @if ($isLoggedin)
                                    <div class="rounded px-2 me-1" onclick="event.stopPropagation()">
                                        <span><img src="{{asset('assets/user/img/saved-collection.png')}}" data-id="{{$restaurant['id']}}" data-type="restaurant" data-collection-id="{{$item['collection_id']}}" onclick="undoFromCollection(this)" alt="" style="height: 22px;"></span>
                                    </div>
                                @endif
                                <p class="mb-0 align-self-center fs-6"><i class="feather-map-pin me-1"></i>{{ App\CentralLogics\Helpers::formatDistance($restaurant['distance']) }}
                                </p>
                            </div>
                            <div class="mb-1 d-flex justify-content-between mt-2">
                                @if ($badges != null && isset($badges['b3']))
                                    <div class="bookmark-icon pe-2 ps-3 text-nowrap">
                                        <span class="text-white" style="border-radius: 8px 0px 0px 8px !important;font-size: 14px;">{{ Str::ucfirst($badges['b3']) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
    </div>
@endif
@endforeach
</div>
@endforeach
